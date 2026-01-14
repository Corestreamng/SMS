# Installation Guide

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Required PHP extensions:
  - PDO
  - PDO_MySQL
  - mbstring
  - openssl
  - json
  - curl (for email/SMS features)

## Step-by-Step Installation

### 1. Server Setup

**For Apache:**
- Ensure mod_rewrite is enabled
- Configure virtual host to point to the project directory

**For Nginx:**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/SMS;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

### 2. Database Setup

1. Create a MySQL database:
```sql
CREATE DATABASE sms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Create a database user:
```sql
CREATE USER 'sms_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON sms_db.* TO 'sms_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Application Configuration

1. Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```

2. Edit `.env` and update database credentials:
```
DB_HOST=localhost
DB_NAME=sms_db
DB_USER=sms_user
DB_PASS=your_password
```

3. Update `config/config.php` if not using environment variables

### 4. Run Setup Script

```bash
php setup.php
```

This will:
- Create database tables
- Insert default data
- Create admin user

### 5. Set Permissions

```bash
chmod 755 uploads/
chmod 755 uploads/students/
chmod 755 uploads/staff/
chmod 755 uploads/documents/
chmod 644 config/config.php
```

### 6. Configure Email/SMS (Optional)

For email notifications:
1. Update SMTP settings in `.env`
2. Test email configuration

For SMS notifications:
1. Sign up for Twilio or similar service
2. Update SMS settings in `.env`
3. Enable SMS in system settings

## Post-Installation

1. **Login to Admin Panel:**
   - URL: http://your-domain.com
   - Username: admin
   - Password: Admin@123 (change immediately!)

2. **Configure School Settings:**
   - Go to Settings
   - Update school information
   - Configure academic settings
   - Set score limits
   - Configure matric number format

3. **Create Roles and Permissions:**
   - Review default roles
   - Customize permissions as needed

4. **Add Academic Year and Terms:**
   - Create current academic year
   - Add terms/semesters

5. **Set Up Sections and Classes:**
   - Add sections (Nursery, Primary, etc.)
   - Create classes within sections

6. **Add Subjects:**
   - Create subjects
   - Assign to classes

## Bulk User Upload

### Student Upload Template
CSV format: `first_name, last_name, email, phone, date_of_birth, gender, class_id, section_id, matric_number`

### Staff Upload Template
CSV format: `first_name, last_name, email, phone, staff_id, staff_type, designation, role`

## Troubleshooting

### Database Connection Error
- Verify database credentials in `.env` or `config/config.php`
- Ensure MySQL service is running
- Check database user permissions

### 404 Errors on All Pages
- Enable mod_rewrite (Apache)
- Check .htaccess file exists
- Verify web server configuration

### File Upload Issues
- Check upload directory permissions
- Verify PHP upload_max_filesize setting
- Ensure post_max_size is adequate

### Email Not Sending
- Verify SMTP credentials
- Check firewall settings
- Enable less secure apps (Gmail)
- Use app-specific password

## Security Recommendations

1. **Change Default Password:** Immediately after installation
2. **Use HTTPS:** Install SSL certificate in production
3. **Regular Backups:** Set up automated database backups
4. **Update PHP:** Keep PHP and MySQL updated
5. **Restrict Access:** Limit config file access
6. **Enable 2FA:** For admin accounts

## Maintenance

### Backup Database
```bash
mysqldump -u sms_user -p sms_db > backup_$(date +%Y%m%d).sql
```

### Update Application
```bash
git pull origin main
# Run any migration scripts if provided
```

## Support

For issues and questions:
- Check documentation
- Review error logs
- Contact support: support@school.com
