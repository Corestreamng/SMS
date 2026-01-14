<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Student Controller - Handles student management
 */
class StudentController extends Controller
{
    public function index()
    {
        if (!$this->auth->hasPermission('students.view')) {
            $_SESSION['error'] = 'You do not have permission to view students';
            $this->redirect('/dashboard');
        }
        
        $page = $_GET['page'] ?? 1;
        $perPage = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $perPage;
        
        // Get students with pagination
        $sql = "SELECT s.*, u.email, u.phone, c.name as class_name, sec.name as section_name
                FROM students s
                LEFT JOIN users u ON s.user_id = u.id
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN sections sec ON s.section_id = sec.id
                ORDER BY s.created_at DESC
                LIMIT ? OFFSET ?";
        
        $students = $this->db->fetchAll($sql, [$perPage, $offset]);
        
        // Get total count
        $countSql = "SELECT COUNT(*) as count FROM students";
        $totalResult = $this->db->fetchOne($countSql);
        $total = $totalResult['count'];
        
        $data = [
            'students' => $students,
            'total' => $total,
            'page' => $page,
            'totalPages' => ceil($total / $perPage)
        ];
        
        $this->view('students.index', $data);
    }
    
    public function create()
    {
        if (!$this->auth->hasPermission('students.create')) {
            $_SESSION['error'] = 'You do not have permission to create students';
            $this->redirect('/students');
        }
        
        // Get classes and sections for dropdown
        $classes = $this->db->fetchAll("SELECT * FROM classes WHERE is_active = 1 ORDER BY name");
        $sections = $this->db->fetchAll("SELECT * FROM sections WHERE is_active = 1 ORDER BY display_order");
        
        $data = [
            'classes' => $classes,
            'sections' => $sections
        ];
        
        $this->view('students.create', $data);
    }
    
    public function store()
    {
        if (!$this->auth->hasPermission('students.create')) {
            $this->json(['success' => false, 'message' => 'Permission denied'], 403);
        }
        
        // Validate input
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $classId = $_POST['class_id'] ?? null;
        $sectionId = $_POST['section_id'] ?? null;
        $dateOfBirth = $_POST['date_of_birth'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $matricNumber = $_POST['matric_number'] ?? '';
        
        // Generate matric number if not provided
        if (empty($matricNumber)) {
            $matricNumber = $this->generateMatricNumber();
        }
        
        try {
            $this->db->beginTransaction();
            
            // Create user account
            $username = strtolower($firstName . '.' . $lastName . rand(100, 999));
            $password = $this->generateRandomPassword();
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $userSql = "INSERT INTO users (username, email, password_hash, first_name, last_name, phone, role_id, is_active, email_verified)
                        VALUES (?, ?, ?, ?, ?, ?, ?, 1, 0)";
            
            // Get student role ID
            $roleResult = $this->db->fetchOne("SELECT id FROM roles WHERE name = 'student' LIMIT 1");
            $studentRoleId = $roleResult['id'] ?? 4;
            
            $this->db->execute($userSql, [$username, $email, $passwordHash, $firstName, $lastName, $phone, $studentRoleId]);
            $userId = $this->db->lastInsertId();
            
            // Create student record
            $studentSql = "INSERT INTO students (user_id, matric_number, class_id, section_id, date_of_birth, gender, admission_date, status)
                          VALUES (?, ?, ?, ?, ?, ?, CURDATE(), 'active')";
            
            $this->db->execute($studentSql, [$userId, $matricNumber, $classId, $sectionId, $dateOfBirth, $gender]);
            
            $this->db->commit();
            
            // Send notification email/SMS with credentials
            $this->sendRegistrationNotification($email, $phone, $username, $password, $firstName);
            
            $_SESSION['success'] = 'Student created successfully. Matric Number: ' . $matricNumber;
            $this->redirect('/students');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Error creating student: " . $e->getMessage());
            $_SESSION['error'] = 'Error creating student: ' . $e->getMessage();
            $this->redirect('/students/create');
        }
    }
    
    public function bulkUpload()
    {
        if (!$this->auth->hasPermission('users.bulk_upload')) {
            $this->json(['success' => false, 'message' => 'Permission denied'], 403);
        }
        
        if (!isset($_FILES['csv_file'])) {
            $this->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
        
        $file = $_FILES['csv_file'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->json(['success' => false, 'message' => 'File upload error'], 400);
        }
        
        // Validate file type
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            $this->json(['success' => false, 'message' => 'Only CSV files are allowed'], 400);
        }
        
        try {
            $handle = fopen($file['tmp_name'], 'r');
            $header = fgetcsv($handle); // Skip header row
            
            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            
            $this->db->beginTransaction();
            
            while (($row = fgetcsv($handle)) !== false) {
                try {
                    // Expected CSV format: first_name, last_name, email, phone, date_of_birth, gender, class_id, section_id, matric_number
                    $this->createStudentFromCsvRow($row);
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Row error: " . $e->getMessage();
                }
            }
            
            fclose($handle);
            $this->db->commit();
            
            $this->json([
                'success' => true,
                'message' => "Imported $successCount students successfully. $errorCount errors.",
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $this->json(['success' => false, 'message' => 'Bulk upload failed: ' . $e->getMessage()], 500);
        }
    }
    
    public function downloadTemplate()
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="student_upload_template.csv"');
        
        $output = fopen('php://output', 'w');
        
        // CSV Header
        fputcsv($output, [
            'first_name',
            'last_name',
            'email',
            'phone',
            'date_of_birth',
            'gender',
            'class_id',
            'section_id',
            'matric_number (optional)'
        ]);
        
        // Sample row
        fputcsv($output, [
            'John',
            'Doe',
            'john.doe@example.com',
            '1234567890',
            '2010-01-15',
            'male',
            '1',
            '2',
            ''
        ]);
        
        fclose($output);
        exit();
    }
    
    private function createStudentFromCsvRow($row)
    {
        list($firstName, $lastName, $email, $phone, $dob, $gender, $classId, $sectionId, $matricNumber) = $row;
        
        if (empty($matricNumber)) {
            $matricNumber = $this->generateMatricNumber();
        }
        
        // Create user
        $username = strtolower($firstName . '.' . $lastName . rand(100, 999));
        $password = $this->generateRandomPassword();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $roleResult = $this->db->fetchOne("SELECT id FROM roles WHERE name = 'student' LIMIT 1");
        $studentRoleId = $roleResult['id'] ?? 4;
        
        $userSql = "INSERT INTO users (username, email, password_hash, first_name, last_name, phone, role_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($userSql, [$username, $email, $passwordHash, $firstName, $lastName, $phone, $studentRoleId]);
        $userId = $this->db->lastInsertId();
        
        // Create student
        $studentSql = "INSERT INTO students (user_id, matric_number, class_id, section_id, date_of_birth, gender, admission_date, status)
                      VALUES (?, ?, ?, ?, ?, ?, CURDATE(), 'active')";
        
        $this->db->execute($studentSql, [$userId, $matricNumber, $classId, $sectionId, $dob, $gender]);
        
        // Send notification
        $this->sendRegistrationNotification($email, $phone, $username, $password, $firstName);
    }
    
    private function generateMatricNumber()
    {
        // Get prefix and last number from settings
        $prefixResult = $this->db->fetchOne("SELECT setting_value FROM system_settings WHERE setting_key = 'matric_prefix'");
        $prefix = $prefixResult['setting_value'] ?? 'STD';
        
        $lastNumResult = $this->db->fetchOne("SELECT setting_value FROM system_settings WHERE setting_key = 'matric_last_number'");
        $lastNum = (int)($lastNumResult['setting_value'] ?? 0);
        
        $newNum = $lastNum + 1;
        $matricNumber = $prefix . str_pad($newNum, 5, '0', STR_PAD_LEFT);
        
        // Update last number in settings
        $this->db->execute("UPDATE system_settings SET setting_value = ? WHERE setting_key = 'matric_last_number'", [$newNum]);
        
        return $matricNumber;
    }
    
    private function generateRandomPassword($length = 10)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        return substr(str_shuffle($chars), 0, $length);
    }
    
    private function sendRegistrationNotification($email, $phone, $username, $password, $firstName)
    {
        // Send email notification
        $subject = 'Welcome to ' . APP_NAME;
        $message = "Dear $firstName,\n\n";
        $message .= "Your account has been created successfully.\n\n";
        $message .= "Login Credentials:\n";
        $message .= "Username: $username\n";
        $message .= "Password: $password\n\n";
        $message .= "Please login and change your password immediately.\n\n";
        $message .= "Best regards,\n" . APP_NAME;
        
        // Log email
        $this->db->execute(
            "INSERT INTO communication_logs (type, recipient, subject, message, status) VALUES (?, ?, ?, ?, ?)",
            ['email', $email, $subject, $message, 'pending']
        );
        
        // Send SMS notification if enabled
        $smsEnabled = $this->db->fetchOne("SELECT setting_value FROM system_settings WHERE setting_key = 'enable_sms_notifications'");
        if ($smsEnabled && $smsEnabled['setting_value'] === 'true' && !empty($phone)) {
            $smsMessage = "Welcome to " . APP_NAME . ". Username: $username, Password: $password";
            $this->db->execute(
                "INSERT INTO communication_logs (type, recipient, message, status) VALUES (?, ?, ?, ?)",
                ['sms', $phone, $smsMessage, 'pending']
            );
        }
    }
    
    public function edit($id)
    {
        if (!$this->auth->hasPermission('students.edit')) {
            $_SESSION['error'] = 'Permission denied';
            $this->redirect('/students');
        }
        
        $sql = "SELECT s.*, u.* FROM students s
                LEFT JOIN users u ON s.user_id = u.id
                WHERE s.id = ?";
        
        $student = $this->db->fetchOne($sql, [$id]);
        
        if (!$student) {
            $_SESSION['error'] = 'Student not found';
            $this->redirect('/students');
        }
        
        $classes = $this->db->fetchAll("SELECT * FROM classes WHERE is_active = 1");
        $sections = $this->db->fetchAll("SELECT * FROM sections WHERE is_active = 1");
        
        $this->view('students.edit', [
            'student' => $student,
            'classes' => $classes,
            'sections' => $sections
        ]);
    }
    
    public function update($id)
    {
        if (!$this->auth->hasPermission('students.edit')) {
            $_SESSION['error'] = 'Permission denied';
            $this->redirect('/students');
        }
        
        // Update logic here
        $_SESSION['success'] = 'Student updated successfully';
        $this->redirect('/students');
    }
}
