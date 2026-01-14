<?php
/**
 * School Management System - Main Entry Point
 * 
 * @package SMS
 * @version 1.0.0
 */

// Start session
session_start();

// Define base paths
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Load configuration
require_once CONFIG_PATH . '/config.php';
require_once APP_PATH . '/core/autoload.php';

// Initialize application
$app = new \App\Core\Application();
$app->run();
