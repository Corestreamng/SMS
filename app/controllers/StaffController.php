<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Staff Controller
 */
class StaffController extends Controller
{
    public function index()
    {
        if (!$this->auth->hasPermission('staff.view')) {
            $_SESSION['error'] = 'Permission denied';
            $this->redirect('/dashboard');
        }
        
        $sql = "SELECT s.*, u.first_name, u.last_name, u.email, u.phone, u.staff_id
                FROM staff s
                JOIN users u ON s.user_id = u.id
                ORDER BY u.last_name, u.first_name";
        
        $staff = $this->db->fetchAll($sql);
        
        $this->view('staff.index', ['staff' => $staff]);
    }
}
