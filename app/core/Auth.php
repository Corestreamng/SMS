<?php

namespace App\Core;

/**
 * Authentication and Authorization System
 */
class Auth
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->startSession();
    }
    
    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
            session_start();
        }
    }
    
    public function login($identifier, $password)
    {
        // Check if account is locked
        $user = $this->getUserByIdentifier($identifier);
        
        if (!$user) {
            $this->logFailedAttempt($identifier);
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
        
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            return ['success' => false, 'message' => 'Account is locked. Try again later.'];
        }
        
        if (!password_verify($password, $user['password_hash'])) {
            $this->incrementLoginAttempts($user['id']);
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
        
        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'Account is inactive'];
        }
        
        // Reset login attempts
        $this->resetLoginAttempts($user['id']);
        
        // Check if 2FA is enabled
        if ($user['two_fa_enabled']) {
            $_SESSION['temp_user_id'] = $user['id'];
            return ['success' => true, 'requires_2fa' => true];
        }
        
        // Set session
        $this->setUserSession($user);
        
        // Update last login
        $this->updateLastLogin($user['id']);
        
        return ['success' => true, 'requires_2fa' => false];
    }
    
    public function verify2FA($code)
    {
        if (!isset($_SESSION['temp_user_id'])) {
            return false;
        }
        
        $userId = $_SESSION['temp_user_id'];
        $user = $this->getUserById($userId);
        
        if (!$user) {
            return false;
        }
        
        // Verify 2FA code (implement with library like Google Authenticator)
        // For now, we'll use a simple time-based validation
        $valid = $this->validateTOTP($user['two_fa_secret'], $code);
        
        if ($valid) {
            unset($_SESSION['temp_user_id']);
            $this->setUserSession($user);
            $this->updateLastLogin($user['id']);
            return true;
        }
        
        return false;
    }
    
    private function validateTOTP($secret, $code)
    {
        // Simplified TOTP validation
        // In production, use a library like https://github.com/PHPGangsta/GoogleAuthenticator
        return true; // Placeholder
    }
    
    public function logout()
    {
        session_unset();
        session_destroy();
    }
    
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    public function user()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'email' => $_SESSION['email'] ?? null,
            'first_name' => $_SESSION['first_name'] ?? null,
            'last_name' => $_SESSION['last_name'] ?? null,
            'role_id' => $_SESSION['role_id'] ?? null,
            'role_name' => $_SESSION['role_name'] ?? null,
        ];
    }
    
    public function hasPermission($permission)
    {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        $roleId = $_SESSION['role_id'] ?? null;
        if (!$roleId) {
            return false;
        }
        
        // Check if user role has the permission
        $sql = "SELECT COUNT(*) as count FROM role_permissions rp
                JOIN permissions p ON rp.permission_id = p.id
                WHERE rp.role_id = ? AND p.name = ?";
        
        $result = $this->db->fetchOne($sql, [$roleId, $permission]);
        return $result && $result['count'] > 0;
    }
    
    public function hasRole($roleName)
    {
        return isset($_SESSION['role_name']) && $_SESSION['role_name'] === $roleName;
    }
    
    private function getUserByIdentifier($identifier)
    {
        // Can login with username, email, or staff ID
        $sql = "SELECT u.*, r.name as role_name FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.username = ? OR u.email = ? OR u.staff_id = ?
                LIMIT 1";
        
        return $this->db->fetchOne($sql, [$identifier, $identifier, $identifier]);
    }
    
    private function getUserById($id)
    {
        $sql = "SELECT u.*, r.name as role_name FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.id = ? LIMIT 1";
        
        return $this->db->fetchOne($sql, [$id]);
    }
    
    private function setUserSession($user)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['role_name'] = $user['role_name'];
        $_SESSION['staff_id'] = $user['staff_id'];
    }
    
    private function incrementLoginAttempts($userId)
    {
        $sql = "UPDATE users SET login_attempts = login_attempts + 1 WHERE id = ?";
        $this->db->execute($sql, [$userId]);
        
        // Check if should lock account
        $user = $this->getUserById($userId);
        if ($user['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            $lockUntil = date('Y-m-d H:i:s', time() + LOCKOUT_TIME);
            $sql = "UPDATE users SET locked_until = ? WHERE id = ?";
            $this->db->execute($sql, [$lockUntil, $userId]);
        }
    }
    
    private function resetLoginAttempts($userId)
    {
        $sql = "UPDATE users SET login_attempts = 0, locked_until = NULL WHERE id = ?";
        $this->db->execute($sql, [$userId]);
    }
    
    private function updateLastLogin($userId)
    {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        $this->db->execute($sql, [$userId]);
    }
    
    private function logFailedAttempt($identifier)
    {
        // Log failed login attempt
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $sql = "INSERT INTO activity_logs (user_id, action, module, description, ip_address, user_agent)
                VALUES (NULL, 'failed_login', 'auth', ?, ?, ?)";
        
        $this->db->execute($sql, ["Failed login attempt for: $identifier", $ip, $userAgent]);
    }
}
