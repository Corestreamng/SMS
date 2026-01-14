<?php
/**
 * Helper Functions
 * Common utility functions used throughout the application
 */

namespace App\Helpers;

class Helpers
{
    /**
     * Sanitize output to prevent XSS
     */
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Format date for display
     */
    public static function formatDate($date, $format = 'M d, Y')
    {
        return date($format, strtotime($date));
    }
    
    /**
     * Format date and time for display
     */
    public static function formatDateTime($datetime, $format = 'M d, Y H:i')
    {
        return date($format, strtotime($datetime));
    }
    
    /**
     * Generate a random password
     */
    public static function generatePassword($length = 10)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        return substr(str_shuffle($chars), 0, $length);
    }
    
    /**
     * Format currency
     */
    public static function formatCurrency($amount, $currency = '$')
    {
        return $currency . number_format($amount, 2);
    }
    
    /**
     * Calculate age from date of birth
     */
    public static function calculateAge($dob)
    {
        $birthDate = new \DateTime($dob);
        $today = new \DateTime('today');
        return $birthDate->diff($today)->y;
    }
    
    /**
     * Get user's initials
     */
    public static function getInitials($firstName, $lastName)
    {
        return strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
    }
    
    /**
     * Validate email address
     */
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate phone number
     */
    public static function isValidPhone($phone)
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return strlen($phone) >= 10;
    }
    
    /**
     * Generate unique filename for uploads
     */
    public static function generateUniqueFilename($originalName)
    {
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid() . '_' . time() . '.' . $ext;
    }
    
    /**
     * Get file size in human readable format
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
    
    /**
     * Calculate percentage
     */
    public static function calculatePercentage($obtained, $total)
    {
        if ($total == 0) {
            return 0;
        }
        return round(($obtained / $total) * 100, 2);
    }
    
    /**
     * Get grade based on percentage
     */
    public static function getGrade($percentage, $db)
    {
        $sql = "SELECT grade, remarks, remarks_arabic 
                FROM grading_system 
                WHERE ? BETWEEN min_percentage AND max_percentage 
                LIMIT 1";
        
        $result = $db->fetchOne($sql, [$percentage]);
        return $result ?: ['grade' => 'F', 'remarks' => 'Fail', 'remarks_arabic' => 'راسب'];
    }
    
    /**
     * Truncate text
     */
    public static function truncate($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) > $length) {
            return substr($text, 0, $length) . $suffix;
        }
        return $text;
    }
    
    /**
     * Create slug from string
     */
    public static function createSlug($string)
    {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }
    
    /**
     * Check if request is AJAX
     */
    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Get client IP address
     */
    public static function getClientIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '';
        }
    }
    
    /**
     * Redirect with message
     */
    public static function redirectWithMessage($url, $message, $type = 'success')
    {
        $_SESSION[$type] = $message;
        header('Location: ' . $url);
        exit();
    }
    
    /**
     * Validate file upload
     */
    public static function validateFileUpload($file, $allowedExtensions = [], $maxSize = null)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'error' => 'File upload error'];
        }
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!empty($allowedExtensions) && !in_array($ext, $allowedExtensions)) {
            return ['valid' => false, 'error' => 'File type not allowed'];
        }
        
        if ($maxSize && $file['size'] > $maxSize) {
            return ['valid' => false, 'error' => 'File size exceeds limit'];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Send JSON response
     */
    public static function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    /**
     * Array to CSV
     */
    public static function arrayToCsv($data, $filename = 'export.csv')
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        if (!empty($data)) {
            // Write header
            fputcsv($output, array_keys($data[0]));
            
            // Write rows
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Debug helper
     */
    public static function dump($var, $exit = false)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        
        if ($exit) {
            exit();
        }
    }
}
