<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Authentication Controller
 */
class AuthController extends Controller
{
    protected function checkAuth()
    {
        // Don't check auth for auth controller
    }
    
    public function showLogin()
    {
        if ($this->auth->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.login');
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }
        
        $identifier = $_POST['identifier'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($identifier) || empty($password)) {
            $_SESSION['error'] = 'Please provide both identifier and password';
            $this->redirect('/login');
        }
        
        $result = $this->auth->login($identifier, $password);
        
        if (!$result['success']) {
            $_SESSION['error'] = $result['message'];
            $this->redirect('/login');
        }
        
        if ($result['requires_2fa']) {
            $this->redirect('/verify-2fa');
        }
        
        $_SESSION['success'] = 'Login successful';
        $this->redirect('/dashboard');
    }
    
    public function logout()
    {
        $this->auth->logout();
        $this->redirect('/login');
    }
    
    public function showRegister()
    {
        $this->view('auth.register');
    }
    
    public function register()
    {
        // Implement user registration
        // For security, this might be restricted to admins only
        $_SESSION['error'] = 'Registration is restricted. Please contact administrator.';
        $this->redirect('/login');
    }
}
