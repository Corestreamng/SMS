<?php
/**
 * Application Configuration
 */

// Database Configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'sms_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// Application Settings
define('APP_NAME', 'School Management System');
define('APP_VERSION', '1.0.0');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost');
define('TIMEZONE', 'UTC');

// Security Settings
define('SESSION_LIFETIME', 7200); // 2 hours
define('PASSWORD_MIN_LENGTH', 8);
define('ENABLE_2FA', true);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutes

// Email Configuration
define('MAIL_HOST', getenv('MAIL_HOST') ?: 'smtp.gmail.com');
define('MAIL_PORT', getenv('MAIL_PORT') ?: 587);
define('MAIL_USERNAME', getenv('MAIL_USERNAME') ?: '');
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD') ?: '');
define('MAIL_FROM_ADDRESS', getenv('MAIL_FROM_ADDRESS') ?: 'noreply@sms.com');
define('MAIL_FROM_NAME', getenv('MAIL_FROM_NAME') ?: APP_NAME);

// SMS Configuration
define('SMS_PROVIDER', getenv('SMS_PROVIDER') ?: 'twilio');
define('SMS_API_KEY', getenv('SMS_API_KEY') ?: '');
define('SMS_API_SECRET', getenv('SMS_API_SECRET') ?: '');
define('SMS_FROM_NUMBER', getenv('SMS_FROM_NUMBER') ?: '');

// File Upload Settings
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_PATH', BASE_PATH . '/uploads');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'csv', 'xlsx']);

// Pagination
define('ITEMS_PER_PAGE', 20);

// Set timezone
date_default_timezone_set(TIMEZONE);
