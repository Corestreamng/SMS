<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Dashboard Controller
 */
class DashboardController extends Controller
{
    public function index()
    {
        $user = $this->auth->user();
        
        // Get statistics
        $stats = $this->getStatistics();
        
        // Get recent activities
        $activities = $this->getRecentActivities();
        
        // Get calendar events
        $upcomingEvents = $this->getUpcomingEvents();
        
        $data = [
            'user' => $user,
            'stats' => $stats,
            'activities' => $activities,
            'events' => $upcomingEvents,
            'currentDate' => date('l, F d, Y'),
            'currentTime' => date('h:i A')
        ];
        
        $this->view('dashboard.index', $data);
    }
    
    private function getStatistics()
    {
        $stats = [];
        
        // Total Students
        $sql = "SELECT COUNT(*) as count FROM students WHERE status = 'active'";
        $result = $this->db->fetchOne($sql);
        $stats['total_students'] = $result['count'] ?? 0;
        
        // Total Staff
        $sql = "SELECT COUNT(*) as count FROM staff";
        $result = $this->db->fetchOne($sql);
        $stats['total_staff'] = $result['count'] ?? 0;
        
        // Total Classes
        $sql = "SELECT COUNT(*) as count FROM classes WHERE is_active = 1";
        $result = $this->db->fetchOne($sql);
        $stats['total_classes'] = $result['count'] ?? 0;
        
        // Today's Attendance
        $sql = "SELECT 
                    COUNT(DISTINCT student_id) as present_count
                FROM attendance 
                WHERE date = CURDATE() AND status = 'present'";
        $result = $this->db->fetchOne($sql);
        $stats['today_attendance'] = $result['present_count'] ?? 0;
        
        // Pending Payments
        if ($this->auth->hasPermission('finance.view')) {
            $sql = "SELECT COUNT(*) as count FROM fee_payments WHERE status = 'pending'";
            $result = $this->db->fetchOne($sql);
            $stats['pending_payments'] = $result['count'] ?? 0;
        }
        
        return $stats;
    }
    
    private function getRecentActivities()
    {
        $sql = "SELECT al.*, u.first_name, u.last_name 
                FROM activity_logs al
                LEFT JOIN users u ON al.user_id = u.id
                ORDER BY al.created_at DESC
                LIMIT 10";
        
        return $this->db->fetchAll($sql);
    }
    
    private function getUpcomingEvents()
    {
        $sql = "SELECT * FROM calendar_events 
                WHERE start_date >= CURDATE() AND is_public = 1
                ORDER BY start_date ASC
                LIMIT 5";
        
        return $this->db->fetchAll($sql);
    }
}
