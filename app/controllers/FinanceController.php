<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Finance Controller - Handles payment records and financial operations
 */
class FinanceController extends Controller
{
    public function index()
    {
        if (!$this->auth->hasPermission('finance.view')) {
            $_SESSION['error'] = 'Permission denied';
            $this->redirect('/dashboard');
        }
        
        // Get financial summary
        $summary = $this->getFinancialSummary();
        
        // Get recent payments
        $sql = "SELECT fp.*, s.matric_number, u.first_name, u.last_name, fs.name as fee_name
                FROM fee_payments fp
                JOIN students s ON fp.student_id = s.id
                JOIN users u ON s.user_id = u.id
                JOIN fee_structure fs ON fp.fee_structure_id = fs.id
                ORDER BY fp.payment_date DESC
                LIMIT 20";
        
        $recentPayments = $this->db->fetchAll($sql);
        
        $this->view('finance.index', [
            'summary' => $summary,
            'recentPayments' => $recentPayments
        ]);
    }
    
    public function payments()
    {
        if (!$this->auth->hasPermission('finance.view')) {
            $_SESSION['error'] = 'Permission denied';
            $this->redirect('/dashboard');
        }
        
        $page = $_GET['page'] ?? 1;
        $perPage = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT fp.*, s.matric_number, u.first_name, u.last_name, fs.name as fee_name
                FROM fee_payments fp
                JOIN students s ON fp.student_id = s.id
                JOIN users u ON s.user_id = u.id
                JOIN fee_structure fs ON fp.fee_structure_id = fs.id
                ORDER BY fp.payment_date DESC
                LIMIT ? OFFSET ?";
        
        $payments = $this->db->fetchAll($sql, [$perPage, $offset]);
        
        $this->view('finance.payments', ['payments' => $payments]);
    }
    
    private function getFinancialSummary()
    {
        $summary = [];
        
        // Total revenue this month
        $sql = "SELECT SUM(amount_paid) as total FROM fee_payments 
                WHERE MONTH(payment_date) = MONTH(CURDATE()) 
                AND YEAR(payment_date) = YEAR(CURDATE())
                AND status = 'completed'";
        $result = $this->db->fetchOne($sql);
        $summary['monthly_revenue'] = $result['total'] ?? 0;
        
        // Total revenue this year
        $sql = "SELECT SUM(amount_paid) as total FROM fee_payments 
                WHERE YEAR(payment_date) = YEAR(CURDATE())
                AND status = 'completed'";
        $result = $this->db->fetchOne($sql);
        $summary['yearly_revenue'] = $result['total'] ?? 0;
        
        // Pending payments
        $sql = "SELECT COUNT(*) as count FROM fee_payments WHERE status = 'pending'";
        $result = $this->db->fetchOne($sql);
        $summary['pending_count'] = $result['count'] ?? 0;
        
        return $summary;
    }
}
