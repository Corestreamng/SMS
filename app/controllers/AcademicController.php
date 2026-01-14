<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Academic Controller - Handles classes, subjects, timetable
 */
class AcademicController extends Controller
{
    public function classes()
    {
        if (!$this->auth->hasPermission('academics.view')) {
            $_SESSION['error'] = 'Permission denied';
            $this->redirect('/dashboard');
        }
        
        $sectionId = $_GET['section_id'] ?? null;
        
        $sections = $this->db->fetchAll("SELECT * FROM sections WHERE is_active = 1 ORDER BY display_order");
        
        $sql = "SELECT c.*, s.name as section_name 
                FROM classes c
                LEFT JOIN sections s ON c.section_id = s.id
                WHERE c.is_active = 1";
        
        if ($sectionId) {
            $sql .= " AND c.section_id = ?";
            $classes = $this->db->fetchAll($sql, [$sectionId]);
        } else {
            $classes = $this->db->fetchAll($sql);
        }
        
        $this->view('academics.classes', [
            'sections' => $sections,
            'classes' => $classes,
            'selectedSection' => $sectionId
        ]);
    }
    
    public function subjects()
    {
        if (!$this->auth->hasPermission('academics.view')) {
            $_SESSION['error'] = 'Permission denied';
            $this->redirect('/dashboard');
        }
        
        $subjects = $this->db->fetchAll("SELECT * FROM subjects WHERE is_active = 1 ORDER BY name");
        
        $this->view('academics.subjects', ['subjects' => $subjects]);
    }
    
    public function timetable()
    {
        if (!$this->auth->hasPermission('academics.view')) {
            $_SESSION['error'] = 'Permission denied';
            $this->redirect('/dashboard');
        }
        
        $classId = $_GET['class_id'] ?? null;
        
        $classes = $this->db->fetchAll("SELECT * FROM classes WHERE is_active = 1 ORDER BY name");
        
        $timetable = [];
        if ($classId) {
            $sql = "SELECT t.*, s.name as subject_name, st.first_name, st.last_name
                    FROM timetable t
                    JOIN subjects s ON t.subject_id = s.id
                    LEFT JOIN staff stf ON t.teacher_id = stf.id
                    LEFT JOIN users st ON stf.user_id = st.id
                    WHERE t.class_id = ?
                    ORDER BY 
                        FIELD(t.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                        t.start_time";
            
            $timetable = $this->db->fetchAll($sql, [$classId]);
        }
        
        $this->view('academics.timetable', [
            'classes' => $classes,
            'timetable' => $timetable,
            'selectedClass' => $classId
        ]);
    }
}
