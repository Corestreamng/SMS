# Features Documentation

## School Management System - Complete Feature List

### 1. User Management and Registration

#### 1.1 User Registration
- **Manual Registration**: Add users individually through the web interface
- **Bulk Upload**: Upload multiple users via CSV file
  - Download template with pre-defined format
  - Automatic validation of data
  - Error reporting for failed entries
- **Automatic Credentials**: System generates username and password
- **Email Notifications**: Automatic email with login credentials
- **SMS Notifications**: Optional SMS with login details (configurable)

#### 1.2 Student Matric Number System
- **Manual Entry**: Option to manually enter matric numbers during registration
- **Auto-Generation**: Automatic generation based on configurable prefix
- **Sequential Numbering**: Format: PREFIX + 5-digit number (e.g., STD00001)
- **Last Number Setting**: Administrators can set the last registered number
- **Unique Validation**: Ensures no duplicate matric numbers

#### 1.3 Authentication & Security
- **Multiple Login Methods**: Username, Email, or Staff ID
- **Two-Factor Authentication (2FA)**: Optional TOTP-based 2FA
- **Session Management**: Secure session handling with timeouts
- **Account Lockout**: Automatic lockout after failed login attempts
- **Password Security**: BCrypt password hashing
- **CSRF Protection**: Token-based CSRF validation

### 2. System Configuration and Settings

#### 2.1 School Configuration Page
- **General Settings**:
  - School name, email, phone, address
  - Academic year start month
  
- **Academic Settings**:
  - Maximum test scores (configurable limits)
  - Maximum exam scores (configurable limits)
  - Pass percentage threshold
  - Result sheet language (English, Arabic, or Both)

- **Exam Score Limits**:
  - Prevents human errors by enforcing maximum scores
  - Configurable per exam type
  - Default weights:
    - Assignment: 10%
    - First Test: 15%
    - Second Test: 15%
    - Mid-term Exam: 20%
    - Final Exam: 40%

- **Grading System**:
  - Configurable grade boundaries
  - Verbal evaluation keys in English and Arabic
  - Grade point system
  - Remarks: Outstanding, Excellent, Good, Average, Below Average, Fail

#### 2.2 Automatic Promotion Settings
- **Enable/Disable**: Toggle automatic promotion
- **Performance Threshold**: Minimum percentage for auto-promotion
- **Student Reshuffling**: Automatic class reassignment
- **Performance-Based Suggestions**: Recommendations based on grades

#### 2.3 School Calendar
- **Event Management**: Create, view, update, delete events
- **Event Types**: Holiday, Exam, Meeting, Event, Other
- **Bilingual Support**: Titles in English and Arabic
- **Public/Private**: Control event visibility
- **Date Range**: Support for multi-day events

### 3. Academic Features

#### 3.1 Section and Class Management
- **Sections**: Pre-configured sections
  - Nursery (الحضانة)
  - Primary (الابتدائية)
  - Junior Secondary (الإعدادية)
  - Senior Secondary (الثانوية)
  
- **Classes**:
  - Class name (English and Arabic)
  - Section assignment
  - Class numeric level
  - Capacity
  - Room number
  - Active/Inactive status

- **View by Section**: Filter and manage classes by section
- **CRUD Operations**: Full create, read, update, delete functionality

#### 3.2 Subject Management
- **Subject Information**:
  - Name (English and Arabic)
  - Subject code (optional)
  - Type: Theory, Practical, or Both
  - Description
  - Active/Inactive status

- **Class-Subject Assignment**:
  - Map subjects to classes
  - Assign teachers to subjects
  - Track which subjects are offered in each class

#### 3.3 Flexible Timetable System
- **Class-Based Timetables**: Individual timetable per class
- **Daily Schedule**:
  - Day of week
  - Subject
  - Teacher assignment
  - Start and end time
  - Room number
  
- **Term-Based**: Timetables linked to academic terms
- **Teacher View**: View teacher schedules
- **Print Support**: Print-friendly timetable formats

#### 3.4 Exam and Results System
- **Exam Types**: Configurable exam types with weights
- **Weighted Scoring**: Automatic calculation based on weights
- **Per-Subject Exams**: Create exams for specific subjects and classes
- **Grade Assignment**: Automatic grade calculation
- **Bilingual Results**: English and Arabic support
- **Attendance Integration**: Display attendance on results

### 4. Attendance and Tracking

#### 4.1 Attendance Recording
- **Twice Daily**: Morning and Afternoon sessions
- **Status Options**:
  - Present
  - Absent
  - Late
  - Excused

- **Bulk Recording**: Record entire class at once
- **Date Selection**: Record for any date
- **Class-Based**: Select class and session
- **Teacher Assignment**: Track who recorded attendance

#### 4.2 Attendance Reporting
- **Student Results**: Attendance days displayed on result sheets
- **Statistics**: Calculate attendance percentages
- **History**: View historical attendance records
- **Reports**: Generate attendance reports by class, student, or date range

### 5. Finance and Payment

#### 5.1 Fee Structure
- **Fee Types**:
  - Tuition
  - Admission
  - Examination
  - Transport
  - Library
  - Sports
  - Other

- **Frequency**: One-time, Monthly, Quarterly, Yearly, Per-term
- **Class-Based**: Different fees for different classes
- **Section-Based**: Different fees for different sections
- **Academic Year**: Link fees to specific years

#### 5.2 Payment Management
- **Payment Recording**:
  - Student selection
  - Amount paid
  - Payment method (Cash, Bank Transfer, Cheque, Card, Online)
  - Transaction ID
  - Receipt number generation
  - Payment date
  
- **Payment Status**: Pending, Completed, Failed, Refunded
- **Collector Tracking**: Record who collected payment

#### 5.3 Payment Notifications
- **Email Notifications**: Automatic email on payment
- **SMS Notifications**: Optional SMS confirmation
- **Receipt Generation**: Automated receipt numbers
- **Payment History**: View all payments for a student

#### 5.4 Financial Reports
- **Monthly Revenue**: Total revenue for current month
- **Yearly Revenue**: Total revenue for current year
- **Pending Payments**: List of unpaid fees
- **Payment History**: Detailed payment records
- **Fee Collection Reports**: Track fee collection by type

### 6. Security and Access Control

#### 6.1 Two-Factor Authentication
- **TOTP-Based**: Time-based one-time passwords
- **Optional**: Users can enable/disable 2FA
- **QR Code**: Easy setup with authenticator apps
- **Backup Codes**: Recovery options

#### 6.2 Roles and Permissions
- **Default Roles**:
  - Super Administrator (full access)
  - Administrator
  - Teacher
  - Student
  - Parent
  - Accountant
  - Librarian

- **Permission System**:
  - Module-based permissions
  - Granular access control (View, Create, Edit, Delete)
  - Custom role creation
  - Permission assignment to roles

- **User-Role Assignment**: Assign roles to specific users
- **System Roles**: Protected default roles
- **Permission Inheritance**: Roles inherit permissions

#### 6.3 Activity Logging
- **User Actions**: Track all user activities
- **IP Address**: Record IP addresses
- **User Agent**: Track browser/device information
- **Timestamp**: When action occurred
- **Module Tracking**: Which module was accessed
- **Audit Trail**: Complete audit trail for compliance

### 7. Additional Features

#### 7.1 Mobile Responsiveness
- **Responsive Design**: Works on all screen sizes
- **Mobile Navigation**: Optimized for touch devices
- **Adaptive Layouts**: Content adapts to screen size
- **Touch-Friendly**: Large touch targets for mobile
- **Tested Browsers**: Chrome, Firefox, Safari, Edge (mobile and desktop)

#### 7.2 Staff Management
- **Staff ID Number**: Unique identifier for each staff member
- **Staff ID Login**: Can use Staff ID to login
- **Staff Types**:
  - Teaching
  - Non-teaching
  - Administrative

- **Staff Information**:
  - Department
  - Designation
  - Qualification
  - Date of joining
  - Salary
  - Experience
  - Specialization
  - Photo

#### 7.3 Notification System
- **Email Support**: SMTP-based email notifications
- **SMS Support**: SMS provider integration (Twilio, etc.)
- **Notification Types**:
  - Registration confirmation
  - Payment confirmation
  - Exam schedules
  - Event reminders
  
- **Communication Logs**: Track all sent emails and SMS
- **Status Tracking**: Pending, Sent, Failed
- **Error Logging**: Record failed notification attempts

#### 7.4 Dashboard Features
- **Welcome Card**: Personalized greeting with user name
- **Current Date/Time**: Display current date and time
- **System Status**: Show system availability
- **Statistics Cards**:
  - Total Students
  - Total Staff
  - Total Classes
  - Today's Attendance
  - Pending Payments (for finance users)
  
- **Recent Activities**: Last 10 system activities
- **Upcoming Events**: Next 5 calendar events
- **System Information**: Version, status, school name

## Technical Features

### Database
- **MySQL**: Production-ready schema
- **22+ Tables**: Comprehensive data model
- **Relationships**: Proper foreign keys and constraints
- **Indexes**: Optimized for performance
- **Bilingual**: UTF8MB4 charset for Arabic support

### Security
- **SQL Injection**: Prepared statements
- **XSS Protection**: Output escaping
- **CSRF Protection**: Token validation
- **Password Hashing**: BCrypt algorithm
- **Session Security**: HTTP-only cookies
- **File Upload**: Validation and sanitization

### Architecture
- **MVC Pattern**: Clean separation of concerns
- **Routing**: Clean URL routing
- **Autoloading**: PSR-4 compatible autoloader
- **Database Abstraction**: PDO-based data access
- **Template System**: PHP-based views

### Documentation
- **Installation Guide**: Step-by-step setup
- **README**: Feature overview
- **Code Comments**: Well-documented code
- **Setup Script**: Automated database setup

## Configuration Options

All configurable via Settings page or environment variables:
- Database credentials
- Email settings (SMTP)
- SMS settings
- File upload limits
- Session timeout
- Login attempt limits
- Academic year settings
- Score limits
- Grading system
- Notification preferences
- Matric number format
- Automatic promotion rules

## Data Import/Export

### CSV Templates
- Student bulk upload template
- Staff bulk upload template
- Parent bulk upload template

### Bulk Operations
- Import multiple students
- Import multiple staff members
- Automatic validation
- Error reporting
- Success/failure tracking
