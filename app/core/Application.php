<?php

namespace App\Core;

/**
 * Main Application class
 */
class Application
{
    private $router;
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->router = new Router();
        $this->setupRoutes();
    }
    
    private function setupRoutes()
    {
        // Auth routes
        $this->router->get('/', 'AuthController@showLogin');
        $this->router->get('/login', 'AuthController@showLogin');
        $this->router->post('/login', 'AuthController@login');
        $this->router->get('/logout', 'AuthController@logout');
        $this->router->get('/register', 'AuthController@showRegister');
        $this->router->post('/register', 'AuthController@register');
        
        // Dashboard
        $this->router->get('/dashboard', 'DashboardController@index');
        
        // User Management
        $this->router->get('/users', 'UserController@index');
        $this->router->get('/users/create', 'UserController@create');
        $this->router->post('/users/store', 'UserController@store');
        $this->router->get('/users/edit/{id}', 'UserController@edit');
        $this->router->post('/users/update/{id}', 'UserController@update');
        $this->router->post('/users/delete/{id}', 'UserController@delete');
        $this->router->post('/users/bulk-upload', 'UserController@bulkUpload');
        $this->router->get('/users/download-template', 'UserController@downloadTemplate');
        
        // Student Management
        $this->router->get('/students', 'StudentController@index');
        $this->router->get('/students/create', 'StudentController@create');
        $this->router->post('/students/store', 'StudentController@store');
        $this->router->get('/students/edit/{id}', 'StudentController@edit');
        $this->router->post('/students/update/{id}', 'StudentController@update');
        $this->router->post('/students/bulk-upload', 'StudentController@bulkUpload');
        
        // Staff Management
        $this->router->get('/staff', 'StaffController@index');
        $this->router->get('/staff/create', 'StaffController@create');
        $this->router->post('/staff/store', 'StaffController@store');
        
        // Attendance
        $this->router->get('/attendance', 'AttendanceController@index');
        $this->router->post('/attendance/record', 'AttendanceController@record');
        
        // Academic Management
        $this->router->get('/academics/classes', 'AcademicController@classes');
        $this->router->get('/academics/subjects', 'AcademicController@subjects');
        $this->router->get('/academics/timetable', 'AcademicController@timetable');
        
        // Exams and Results
        $this->router->get('/exams', 'ExamController@index');
        $this->router->get('/results', 'ResultController@index');
        
        // Finance
        $this->router->get('/finance', 'FinanceController@index');
        $this->router->get('/finance/payments', 'FinanceController@payments');
        
        // Settings
        $this->router->get('/settings', 'SettingsController@index');
        $this->router->post('/settings/update', 'SettingsController@update');
        
        // Calendar
        $this->router->get('/calendar', 'CalendarController@index');
    }
    
    public function run()
    {
        $this->router->dispatch();
    }
}
