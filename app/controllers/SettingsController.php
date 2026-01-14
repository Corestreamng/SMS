<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Settings Controller - Handles school configuration
 */
class SettingsController extends Controller
{
    public function index()
    {
        if (!$this->auth->hasPermission('settings.view')) {
            $_SESSION['error'] = 'Permission denied';
            $this->redirect('/dashboard');
        }
        
        // Get all settings grouped by category
        $sql = "SELECT * FROM system_settings ORDER BY category, setting_key";
        $settings = $this->db->fetchAll($sql);
        
        // Group by category
        $grouped = [];
        foreach ($settings as $setting) {
            $category = $setting['category'] ?? 'general';
            $grouped[$category][] = $setting;
        }
        
        // Get exam types
        $examTypes = $this->db->fetchAll("SELECT * FROM exam_types ORDER BY display_order");
        
        // Get grading system
        $gradingSystem = $this->db->fetchAll("SELECT * FROM grading_system ORDER BY min_percentage DESC");
        
        $this->view('settings.index', [
            'settings' => $grouped,
            'examTypes' => $examTypes,
            'gradingSystem' => $gradingSystem
        ]);
    }
    
    public function update()
    {
        if (!$this->auth->hasPermission('settings.manage')) {
            $this->json(['success' => false, 'message' => 'Permission denied'], 403);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Update general settings
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'setting_') === 0) {
                    $settingKey = str_replace('setting_', '', $key);
                    
                    $sql = "UPDATE system_settings SET setting_value = ? WHERE setting_key = ?";
                    $this->db->execute($sql, [$value, $settingKey]);
                }
            }
            
            // Update exam types if provided
            if (isset($_POST['exam_types'])) {
                foreach ($_POST['exam_types'] as $examType) {
                    if (isset($examType['id'])) {
                        $sql = "UPDATE exam_types SET name = ?, weight_percentage = ? WHERE id = ?";
                        $this->db->execute($sql, [$examType['name'], $examType['weight'], $examType['id']]);
                    }
                }
            }
            
            $this->db->commit();
            
            $_SESSION['success'] = 'Settings updated successfully';
            $this->redirect('/settings');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Error updating settings: ' . $e->getMessage();
            $this->redirect('/settings');
        }
    }
}
