# MikroTik Manager - Deployment Guide

## 🚀 Production Deployment

### Prerequisites
- PHP 8.0 or higher
- MySQL/MariaDB 8.0 or higher
- Web server (Apache/Nginx)
- SSL certificate for HTTPS

### Installation Steps

#### 1. Database Setup
```bash
# Create database
mysql -u root -p

CREATE DATABASE mikrotik_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'mikrotik_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON mikrotik_manager.* TO 'mikrotik_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import schema
mysql -u mikrotik_user -p mikrotik_manager < db_schema/schema.sql
```

#### 2. Configuration
Update `config.php` with production settings:
```php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'mikrotik_manager');
define('DB_USER', 'mikrotik_user');
define('DB_PASS', 'your_secure_password');

// Session security (set to true in production)
ini_set('session.cookie_secure', '1');  // Requires HTTPS
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
```

#### 3. File Permissions
```bash
# Set proper ownership
chown -R www-data:www-data /path/to/api_mon

# Set permissions
find /path/to/api_mon -type d -exec chmod 755 {} \;
find /path/to/api_mon -type f -exec chmod 644 {} \;
```

#### 4. Initial Login
```
URL: https://yourdomain.com
Username: superadmin
Password: admin123
```

**⚠️ IMPORTANT: Change the superadmin password immediately after first login!**

---

## 🔐 Security Checklist

- [ ] Change default superadmin password
- [ ] Use HTTPS (SSL certificate installed)
- [ ] Secure database credentials (use environment variables if possible)
- [ ] Enable PHP opcache for performance
- [ ] Set `display_errors = Off` in php.ini
- [ ] Regular database backups configured
- [ ] Monitor and rotate MikroTik API passwords
- [ ] Implement rate limiting for API endpoints (optional)
- [ ] Configure firewall rules

---

## 👥 User Roles & Permissions

### Superadmin
- Full system access
- Manage all users and MikroTik devices
- Create admins and users
- No MikroTik assignment required

### Admin
- Manage own MikroTik device
- Create regular users (auto-assigned to admin's MikroTik)
- Update own MikroTik settings
- Cannot create other admins or superadmins
- Cannot access settings of other admins

### User
- Read-only dashboard access
- View data from assigned MikroTik only
- Cannot access settings page
- Cannot create or manage users

---

## 📝 Testing Data

For development/staging, run the seeder:
```bash
php seeder.php
```

This creates:
- 1 superadmin (superadmin/admin123)
- 2 admin users (admin_jakarta, admin_bandung / password)
- 4 regular users (user_jakarta1, user_bandung1, etc. / password)
- 3 sample MikroTik devices

**🛑 DO NOT run seeder in production!**

---

## 🔄 Backup & Recovery

### Backup Script
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u mikrotik_user -p mikrotik_manager > backup_$DATE.sql
```

### Restore
```bash
mysql -u mikrotik_user -p mikrotik_manager < backup_YYYYMMDD_HHMMSS.sql
```

---

## 🐛 Troubleshooting

### Database Connection Failed
- Check database credentials in `config.php`
- Verify MySQL service is running
- Check firewall rules

### Session Issues
- Clear browser cookies
- Check PHP session directory permissions
- Verify session settings in php.ini

### MikroTik Connection Failed
- Verify MikroTik API is enabled
- Check host/IP and port (default: 8728)
- Confirm username/password
- Test network connectivity

---

## 📊 Maintenance

### Update User Password
```sql
UPDATE users 
SET password = '$2y$10$NEW_HASH_HERE'
WHERE username = 'superadmin';
```

### Remove Unused MikroTik
```sql
-- First, unassign users
UPDATE users SET mikrotik_id = NULL WHERE mikrotik_id = X;
-- Then delete
DELETE FROM mikrotik_settings WHERE id = X;
```

---

## 📞 Support

For issues or questions, review:
- [walkthrough.md](file:///C:/Users/g0str/.gemini/antigravity/brain/0cbe757f-2dca-4650-bc4f-f31043c62c3f/walkthrough.md) - Implementation details
- Database schema comments in `db_schema/schema.sql`
