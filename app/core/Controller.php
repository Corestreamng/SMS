<?php

namespace App\Core;

/**
 * Base Controller class
 */
class Controller
{
    protected $db;
    protected $auth;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->auth = new Auth();
        
        // Check if user is logged in for protected routes
        $this->checkAuth();
    }
    
    protected function checkAuth()
    {
        $publicRoutes = ['/', '/login', '/register'];
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        if (!in_array($currentPath, $publicRoutes) && !$this->auth->isLoggedIn()) {
            $this->redirect('/login');
        }
    }
    
    protected function view($view, $data = [])
    {
        extract($data);
        $viewFile = APP_PATH . '/views/' . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            die("View not found: " . $view);
        }
    }
    
    protected function redirect($path)
    {
        header('Location: ' . $path);
        exit();
    }
    
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    protected function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/dashboard';
        $this->redirect($referer);
    }
    
    protected function validateCsrf()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRF token validation failed');
        }
    }
    
    protected function generateCsrf()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
