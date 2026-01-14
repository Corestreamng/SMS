<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * User Controller - Handles user management
 */
class UserController extends Controller
{
    public function index()
    {
        if (!$this->auth->hasPermission('users.view')) {
            $_SESSION['error'] = 'Permission denied';
            $this->redirect('/dashboard');
        }
        
        $sql = "SELECT u.*, r.display_name as role_name 
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                ORDER BY u.created_at DESC
                LIMIT 50";
        
        $users = $this->db->fetchAll($sql);
        
        $this->view('users.index', ['users' => $users]);
    }
    
    public function downloadTemplate()
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="user_upload_template.csv"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['username', 'email', 'first_name', 'last_name', 'phone', 'role', 'staff_id (optional)']);
        fputcsv($output, ['john.doe', 'john@example.com', 'John', 'Doe', '1234567890', 'teacher', 'TCH001']);
        
        fclose($output);
        exit();
    }
}
