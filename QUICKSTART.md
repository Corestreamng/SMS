# Quick Start Guide

## Get Started in 5 Minutes

### Prerequisites
- PHP 7.4+ installed
- MySQL 5.7+ installed
- Web server (Apache/Nginx)

### Quick Installation Steps

1. **Clone or Download**
   ```bash
   git clone https://github.com/Corestreamng/SMS.git
   cd SMS
   ```

2. **Configure Environment**
   ```bash
   cp .env.example .env
   nano .env  # Edit with your database credentials
   ```

3. **Run Setup**
   ```bash
   php setup.php
   ```

4. **Access the System**
   - Open your browser
   - Go to: `http://localhost/SMS` (or your configured URL)
   - Login with default credentials:
     - **Username**: admin
     - **Email**: admin@school.com
     - **Password**: Admin@123

5. **First Steps After Login**
   - [ ] Change the admin password immediately
   - [ ] Go to Settings and configure school information
   - [ ] Create academic year and terms
   - [ ] Set up sections and classes
   - [ ] Add subjects
   - [ ] Configure exam types and grading system
   - [ ] Start adding students and staff

### Common Setup Issues

**Database Connection Error:**
```bash
# Verify MySQL is running
sudo systemctl status mysql

# Check credentials in .env file
DB_HOST=localhost
DB_NAME=sms_db
DB_USER=root
DB_PASS=your_password
```

**Permission Denied:**
```bash
# Fix file permissions
chmod 755 uploads/
chmod -R 755 uploads/students uploads/staff uploads/documents
```

**Page Not Found (404):**
```bash
# Enable Apache mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Check .htaccess file exists
ls -la .htaccess
```

### Quick Configuration

**For Development:**
```bash
# .env file
APP_ENV=development
APP_URL=http://localhost/SMS
DB_HOST=localhost
DB_NAME=sms_db
DB_USER=root
DB_PASS=
```

**For Production:**
```bash
# .env file
APP_ENV=production
APP_URL=https://your-school-domain.com
DB_HOST=localhost
DB_NAME=sms_db
DB_USER=sms_user
DB_PASS=strong_password_here

# Enable HTTPS redirect in .htaccess
# Uncomment these lines:
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### Next Steps

#### Add Your First Student
1. Go to **Students** > **Add Student**
2. Fill in the form
3. System auto-generates matric number
4. Student receives email with login credentials

#### Bulk Upload Students
1. Go to **Students** > **Bulk Upload**
2. Download CSV template
3. Fill in student data
4. Upload CSV file
5. System processes and sends notifications

#### Record Attendance
1. Go to **Attendance**
2. Select class, date, and session
3. Mark attendance for all students
4. Save records

#### Configure Exam System
1. Go to **Settings**
2. Review exam types and weights
3. Adjust as needed for your school
4. Configure grading system

### Features to Explore

- **Dashboard**: View system statistics and recent activities
- **Calendar**: Manage school events
- **Finance**: Track payments and generate receipts
- **Timetable**: Create class schedules
- **Reports**: Generate various reports
- **Settings**: Configure all system parameters

### Getting Help

- Check `FEATURES.md` for complete feature list
- Read `INSTALL.md` for detailed installation
- Review `README.md` for overview

### Security Checklist

- [ ] Changed default admin password
- [ ] Configured HTTPS in production
- [ ] Set strong database password
- [ ] Configured file upload limits
- [ ] Enabled 2FA for admin accounts
- [ ] Reviewed user permissions
- [ ] Set up regular database backups

### Support

For issues or questions:
- Email: support@school.com
- Documentation: Check README.md and FEATURES.md

---

**Congratulations!** Your School Management System is ready to use! 🎉
