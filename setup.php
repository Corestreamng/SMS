#!/usr/bin/env php
<?php
/**
 * Setup Script for School Management System
 * Run this script to initialize the database and create default admin user
 */

echo "===========================================\n";
echo "School Management System - Setup Script\n";
echo "===========================================\n\n";

// Load configuration
require_once __DIR__ . '/config/config.php';

// Database connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "✓ Connected to MySQL server\n";
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database created/verified: " . DB_NAME . "\n";
    
    // Select database
    $pdo->exec("USE " . DB_NAME);
    
    // Read and execute SQL file
    $sqlFile = __DIR__ . '/config/database.sql';
    if (!file_exists($sqlFile)) {
        die("✗ Error: database.sql file not found!\n");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^(--|\/\*)/', $statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // Skip if table/data already exists
                if ($e->getCode() != 23000) { // Duplicate entry
                    echo "Warning: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "✓ Database schema created\n";
    
    // Create default admin user
    $adminUsername = 'admin';
    $adminEmail = 'admin@school.com';
    $adminPassword = 'Admin@123';
    $adminPasswordHash = password_hash($adminPassword, PASSWORD_DEFAULT);
    
    // Check if admin already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$adminUsername, $adminEmail]);
    
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password_hash, first_name, last_name, role_id, is_active, email_verified)
            VALUES (?, ?, ?, 'System', 'Administrator', 1, 1, 1)
        ");
        
        $stmt->execute([$adminUsername, $adminEmail, $adminPasswordHash]);
        
        echo "✓ Default admin user created\n";
        echo "\n";
        echo "===========================================\n";
        echo "Admin Login Credentials:\n";
        echo "===========================================\n";
        echo "Username: " . $adminUsername . "\n";
        echo "Email: " . $adminEmail . "\n";
        echo "Password: " . $adminPassword . "\n";
        echo "===========================================\n";
        echo "\n⚠️  IMPORTANT: Change the admin password after first login!\n";
    } else {
        echo "✓ Admin user already exists\n";
    }
    
    echo "\n✓ Setup completed successfully!\n";
    echo "\nYou can now access the system at: " . APP_URL . "\n\n";
    
} catch (PDOException $e) {
    die("✗ Database Error: " . $e->getMessage() . "\n");
} catch (Exception $e) {
    die("✗ Error: " . $e->getMessage() . "\n");
}
