# School Management System (SMS)

A comprehensive, production-ready School Management System built with PHP.

## Features

### User Management & Authentication
- Role-based access control (Super Admin, Admin, Teacher, Student, Parent, Accountant)
- Two-factor authentication (2FA) support
- Login with username, email, or staff ID
- Bulk user upload via CSV
- Email and SMS notifications upon registration
- Automatic matric number generation

### Student Management
- Student registration with detailed profiles
- Matric number generation (manual and automatic)
- Bulk student upload via CSV
- Student status tracking (active, inactive, graduated, transferred)
- Parent/guardian information management

### Academic Features
- Flexible class and section management (Nursery, Primary, Secondary, etc.)
- Subject management with optional subject codes
- Flexible timetable system
- Multiple exam types with configurable weights
  - Assignment (10%)
  - First Test (15%)
  - Second Test (15%)
  - Mid-term Exam (20%)
  - Final Exam (40%)
- Bilingual support (English/Arabic) for result sheets
- Grading system with verbal evaluations

### Attendance System
- Twice-daily attendance recording (Morning/Afternoon)
- Attendance tracking displayed on student results
- Attendance reports and statistics

### Finance Module
- Comprehensive payment records
- Multiple fee types (tuition, admission, examination, transport, library, sports)
- Payment notifications
- Financial reports and summaries

### School Configuration
- Score limits configuration to prevent errors
- General evaluation ratings and verbal evaluation keys
- Automatic promotion settings
- School calendar management
- System-wide settings management

### Security Features
- Two-factor authentication
- Role and permission management
- Activity logging
- Session management with lockout on failed attempts
- CSRF protection

### Additional Features
- Mobile-responsive design
- Dashboard with statistics and recent activities
- Calendar events management
- Email and SMS notification system
- User activity tracking

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Corestreamng/SMS.git
   cd SMS
   ```

2. **Configure Database**
   - Create a MySQL database
   - Import the schema: `config/database.sql`
   - Update database credentials in `config/config.php`

3. **Configure Web Server**
   - Point document root to the project directory
   - Ensure PHP 7.4+ is installed
   - Enable required PHP extensions: PDO, PDO_MySQL, mbstring

4. **Set Permissions**
   ```bash
   chmod 755 uploads/
   chmod 644 config/config.php
   ```

5. **Create Default Admin User**
   Run this SQL after database setup:
   ```sql
   INSERT INTO users (username, email, password_hash, first_name, last_name, role_id, is_active)
   VALUES ('admin', 'admin@school.com', '$2y$10$hash', 'Admin', 'User', 1, 1);
   ```
   Default password should be hashed using `password_hash('admin123', PASSWORD_DEFAULT)`

6. **Configure Email/SMS** (Optional)
   Update email and SMS settings in `config/config.php` or set environment variables

## Configuration

### Environment Variables
- `DB_HOST` - Database host (default: localhost)
- `DB_NAME` - Database name (default: sms_db)
- `DB_USER` - Database username (default: root)
- `DB_PASS` - Database password
- `MAIL_HOST` - SMTP host
- `MAIL_PORT` - SMTP port
- `MAIL_USERNAME` - SMTP username
- `MAIL_PASSWORD` - SMTP password
- `SMS_API_KEY` - SMS provider API key
- `SMS_API_SECRET` - SMS provider API secret

### System Settings
Configure via Settings page in the application:
- School information
- Academic year settings
- Score limits
- Matric number format
- Automatic promotion rules
- Notification preferences

## Usage

### Bulk Upload
1. Download CSV template from the respective module
2. Fill in user/student data
3. Upload via the bulk upload feature
4. System automatically sends credentials to users

### Matric Number Generation
- Auto-generated based on prefix and last number
- Format: PREFIX + 5-digit number (e.g., STD00001)
- Can be manually set during registration
- Administrators can set last registered number in settings

### Attendance Recording
1. Select class, date, and session (morning/afternoon)
2. Mark students as Present, Absent, Late, or Excused
3. Save attendance records
4. View attendance statistics on student results

### Result Generation
- Weighted exam scores calculated automatically
- Grading based on configurable system
- Bilingual results (English/Arabic)
- Attendance days displayed on results

## Security

- Password hashing using PHP's `password_hash()`
- Session security with HTTP-only cookies
- CSRF token validation
- SQL injection prevention via prepared statements
- XSS protection through output escaping
- Role-based access control
- Activity logging for audit trail

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Contributing

This is a production system. Please follow best practices:
1. Test all changes thoroughly
2. Maintain backward compatibility
3. Update documentation
4. Follow existing code style
5. Add appropriate error handling

## License

Proprietary - All rights reserved

## Support

For support and inquiries, contact: info@school.com

## Version

Current Version: 1.0.0
