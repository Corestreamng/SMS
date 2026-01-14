<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Attendance Controller
 */
class AttendanceController extends Controller
{
    public function index()
    {
        if (!$this->auth->hasPermission('attendance.view')) {
            $_SESSION['error'] = 'Permission denied';
            $this->redirect('/dashboard');
        }
        
        $date = $_GET['date'] ?? date('Y-m-d');
        $classId = $_GET['class_id'] ?? null;
        $session = $_GET['session'] ?? 'morning';
        
        // Get classes for dropdown
        $classes = $this->db->fetchAll("SELECT * FROM classes WHERE is_active = 1 ORDER BY name");
        
        $students = [];
        $attendance = [];
        
        if ($classId) {
            // Get students in class
            $sql = "SELECT s.*, u.first_name, u.last_name 
                    FROM students s
                    JOIN users u ON s.user_id = u.id
                    WHERE s.class_id = ? AND s.status = 'active'
                    ORDER BY u.last_name, u.first_name";
            
            $students = $this->db->fetchAll($sql, [$classId]);
            
            // Get existing attendance for this date
            $sql = "SELECT * FROM attendance WHERE class_id = ? AND date = ? AND session = ?";
            $attendanceRecords = $this->db->fetchAll($sql, [$classId, $date, $session]);
            
            foreach ($attendanceRecords as $record) {
                $attendance[$record['student_id']] = $record['status'];
            }
        }
        
        $this->view('attendance.index', [
            'classes' => $classes,
            'students' => $students,
            'attendance' => $attendance,
            'selectedClass' => $classId,
            'selectedDate' => $date,
            'selectedSession' => $session
        ]);
    }
    
    public function record()
    {
        if (!$this->auth->hasPermission('attendance.record')) {
            $this->json(['success' => false, 'message' => 'Permission denied'], 403);
        }
        
        $classId = $_POST['class_id'] ?? null;
        $date = $_POST['date'] ?? date('Y-m-d');
        $session = $_POST['session'] ?? 'morning';
        $attendanceData = $_POST['attendance'] ?? [];
        
        try {
            $this->db->beginTransaction();
            
            foreach ($attendanceData as $studentId => $status) {
                // Check if record exists
                $sql = "SELECT id FROM attendance WHERE student_id = ? AND date = ? AND session = ?";
                $existing = $this->db->fetchOne($sql, [$studentId, $date, $session]);
                
                if ($existing) {
                    // Update
                    $sql = "UPDATE attendance SET status = ?, recorded_by = ? WHERE id = ?";
                    $this->db->execute($sql, [$status, $this->auth->user()['id'], $existing['id']]);
                } else {
                    // Insert
                    $sql = "INSERT INTO attendance (student_id, class_id, date, session, status, recorded_by)
                            VALUES (?, ?, ?, ?, ?, ?)";
                    $this->db->execute($sql, [$studentId, $classId, $date, $session, $status, $this->auth->user()['id']]);
                }
            }
            
            $this->db->commit();
            
            $_SESSION['success'] = 'Attendance recorded successfully';
            $this->json(['success' => true, 'message' => 'Attendance recorded successfully']);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $this->json(['success' => false, 'message' => 'Error recording attendance: ' . $e->getMessage()], 500);
        }
    }
}
