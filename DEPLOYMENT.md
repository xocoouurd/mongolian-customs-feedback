# Deployment Guide - Mongolian Customs Feedback Form

This guide will walk you through deploying the customs feedback form on a server. The application is a simple PHP-based feedback system that sends submissions via email.

---

## Table of Contents

1. [System Requirements](#system-requirements)
2. [Project Overview](#project-overview)
3. [Deployment Options](#deployment-options)
   - [Option A: Deploy on Existing Server with Nginx](#option-a-deploy-on-existing-server-with-nginx)
   - [Option B: Deploy on Fresh Ubuntu Server](#option-b-deploy-on-fresh-ubuntu-server)
4. [Email Configuration](#email-configuration)
5. [Testing the Application](#testing-the-application)
6. [Troubleshooting](#troubleshooting)
7. [File Structure](#file-structure)

---

## System Requirements

### Server Requirements
- **OS**: Ubuntu 20.04+ (or any Linux distribution with nginx/Apache)
- **Web Server**: Nginx or Apache
- **PHP**: Version 8.0 or higher
- **PHP Extensions**:
  - php-fpm (for nginx)
  - php-mbstring
  - php-curl
- **Composer**: PHP dependency manager
- **Disk Space**: ~50MB minimum

### Email Requirements
- Gmail account (or any SMTP email service)
- Gmail App Password (if using Gmail with 2FA enabled)

---

## Project Overview

This is a single-page feedback form application with:
- **Frontend**: `index.html` (HTML, CSS, JavaScript)
- **Backend**: `submit.php` (PHP with PHPMailer)
- **Configuration**: `config.php` (email settings)
- **Dependencies**: PHPMailer (installed via Composer)

The form allows users to submit three types of feedback:
- **Санал** (Suggestion)
- **Гомдол** (Complaint)
- **Өргөдөл** (Petition)

All submissions are sent to a configured email address via SMTP.

---

## Deployment Options

### Option A: Deploy on Existing Server with Nginx

This option is for servers that already have Nginx, PHP, and other web applications running.

#### Step 1: Check Existing Software

First, verify what's already installed:

```bash
# Check nginx
nginx -v

# Check PHP
php --version

# Check if PHP-FPM is running
systemctl status php*-fpm

# Check composer
composer --version
```

#### Step 2: Install Missing Components

If any components are missing, install them:

```bash
# Update package list
sudo apt update

# Install PHP and required extensions (adjust version if needed)
sudo apt install -y php php-fpm php-mbstring php-curl

# Install composer if not present
sudo apt install -y composer

# Verify PHP-FPM is running
sudo systemctl start php8.3-fpm  # Adjust version number
sudo systemctl enable php8.3-fpm
```

#### Step 3: Upload Project Files

Upload all project files to your desired location. For example:

```bash
# Create project directory
sudo mkdir -p /srv/www/sumiya_1

# Set ownership to your user (replace 'yourusername' with actual username)
sudo chown -R yourusername:yourusername /srv/www/sumiya_1

# Upload files via SCP, SFTP, or git
# Example with SCP from your local machine:
# scp -r /path/to/project/* user@server:/srv/www/sumiya_1/
```

Your project directory should contain:
- `index.html`
- `submit.php`
- `config.php`
- `composer.json`
- `composer.lock`
- Logo file (`.webp` image)

#### Step 4: Install PHP Dependencies

```bash
# Navigate to project directory
cd /srv/www/sumiya_1

# Install PHPMailer via Composer
composer install

# This will create a 'vendor' directory with PHPMailer
```

#### Step 5: Configure Nginx

You need to add a location block to serve the application. You have two options:

##### Option 5a: Serve at a Directory Path (e.g., `http://yourserver.com/sumiya_1`)

Edit your nginx configuration file:

```bash
sudo nano /etc/nginx/sites-available/default
```

Inside the `server` block, add this location configuration:

```nginx
# Sumiya_1 project location
location /sumiya_1/ {
    alias /srv/www/sumiya_1/;
    index index.html index.htm index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_param SCRIPT_FILENAME $request_filename;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;  # Adjust PHP version
    }
}
```

**Important**: Find the correct PHP-FPM socket path:
```bash
ls /run/php/
```
Look for a file like `php8.3-fpm.sock` and use that version number.

##### Option 5b: Serve at a Subdomain (e.g., `http://feedback.yourserver.com`)

Create a new nginx configuration file:

```bash
sudo nano /etc/nginx/sites-available/feedback.yourserver.com
```

Add this configuration:

```nginx
server {
    listen 80;
    server_name feedback.yourserver.com;

    root /srv/www/sumiya_1;
    index index.html index.htm index.php;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;  # Adjust PHP version
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/feedback.yourserver.com /etc/nginx/sites-enabled/
```

#### Step 6: Test and Reload Nginx

```bash
# Test nginx configuration for syntax errors
sudo nginx -t

# If test passes, reload nginx
sudo systemctl reload nginx
```

#### Step 7: Set File Permissions

Ensure nginx can read the files:

```bash
# Set appropriate permissions
sudo chown -R www-data:www-data /srv/www/sumiya_1
sudo chmod -R 755 /srv/www/sumiya_1

# Config file should not be publicly accessible (already handled by nginx)
sudo chmod 644 /srv/www/sumiya_1/config.php
```

#### Step 8: Configure Email Settings (REQUIRED)

⚠️ **CRITICAL STEP**: The application will not work until you configure email settings!

Edit the configuration file:

```bash
nano /srv/www/sumiya_1/config.php
```

Replace all placeholder values:
- `YOUR_EMAIL@gmail.com` → Your actual Gmail address
- `YOUR_APP_PASSWORD` → Your Gmail App Password (see [Email Configuration](#email-configuration))
- `RECIPIENT@example.com` → Email address where submissions should be sent

**The file will look like this BEFORE editing:**
```php
'smtp_username' => 'YOUR_EMAIL@gmail.com',
'smtp_password' => 'YOUR_APP_PASSWORD',
'to_email' => 'RECIPIENT@example.com',
```

**And like this AFTER editing:**
```php
'smtp_username' => 'yourname@gmail.com',
'smtp_password' => 'abcd efgh ijkl mnop',
'to_email' => 'recipient@example.com',
```

See the [Email Configuration](#email-configuration) section below for detailed instructions.

---

### Option B: Deploy on Fresh Ubuntu Server

This option is for a brand new Ubuntu server with nothing installed.

#### Step 1: Update System

```bash
# Update package lists
sudo apt update

# Upgrade existing packages
sudo apt upgrade -y
```

#### Step 2: Install Nginx

```bash
# Install nginx
sudo apt install -y nginx

# Start and enable nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Check status
sudo systemctl status nginx

# Allow through firewall if UFW is enabled
sudo ufw allow 'Nginx HTTP'
```

#### Step 3: Install PHP and Extensions

```bash
# Install PHP and required extensions
sudo apt install -y php php-fpm php-mbstring php-curl

# Start and enable PHP-FPM
sudo systemctl start php8.3-fpm  # Version may vary
sudo systemctl enable php8.3-fpm

# Verify PHP version
php --version
```

#### Step 4: Install Composer

```bash
# Install Composer
sudo apt install -y composer

# Verify installation
composer --version
```

#### Step 5: Create Project Directory

```bash
# Create directory
sudo mkdir -p /srv/www/sumiya_1

# Get your username
whoami

# Set ownership (replace 'yourusername' with output from whoami)
sudo chown -R yourusername:yourusername /srv/www/sumiya_1
```

#### Step 6: Upload Project Files

Transfer your project files to `/srv/www/sumiya_1/`. You can use:

**Option 1: SCP from your local machine**
```bash
# From your local machine
scp -r /path/to/project/* user@server_ip:/srv/www/sumiya_1/
```

**Option 2: Git clone (if using version control)**
```bash
cd /srv/www/sumiya_1
git clone https://your-repo-url.git .
```

**Option 3: Manual upload via SFTP**
Use FileZilla, WinSCP, or any SFTP client to upload files.

#### Step 7: Install Dependencies

```bash
cd /srv/www/sumiya_1
composer install
```

#### Step 8: Configure Nginx

Find your PHP-FPM socket version:

```bash
ls /run/php/
```

You should see something like `php8.3-fpm.sock`. Note the version number.

Edit the default nginx configuration:

```bash
sudo nano /etc/nginx/sites-available/default
```

Replace the entire `server` block with this (or add to it):

```nginx
server {
    listen 80 default_server;
    listen [::]:80 default_server;

    root /srv/www/sumiya_1;
    index index.html index.htm index.php;

    server_name _;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;  # Use your PHP version
    }

    location ~ /\.ht {
        deny all;
    }
}
```

**Important**: Replace `php8.3-fpm.sock` with your actual PHP version from the `ls /run/php/` command.

#### Step 9: Test and Restart Nginx

```bash
# Test configuration
sudo nginx -t

# If successful, restart nginx
sudo systemctl restart nginx
```

#### Step 10: Set Permissions

```bash
sudo chown -R www-data:www-data /srv/www/sumiya_1
sudo chmod -R 755 /srv/www/sumiya_1
```

#### Step 11: Configure Email (REQUIRED)

⚠️ **CRITICAL STEP**: The application will not work until you configure email settings!

Edit the configuration file:

```bash
nano /srv/www/sumiya_1/config.php
```

Replace all placeholder values:
- `YOUR_EMAIL@gmail.com` → Your actual Gmail address
- `YOUR_APP_PASSWORD` → Your Gmail App Password (see next section)
- `RECIPIENT@example.com` → Email address where submissions should be sent

See the [Email Configuration](#email-configuration) section below for detailed instructions on generating Gmail App Passwords.

---

## Email Configuration

### For Gmail Users

#### Step 1: Enable 2-Factor Authentication

1. Go to your Google Account: https://myaccount.google.com/
2. Navigate to **Security**
3. Enable **2-Step Verification** if not already enabled

#### Step 2: Generate App Password

1. Go to: https://myaccount.google.com/apppasswords
2. Select app: **Mail**
3. Select device: **Other (Custom name)** → Enter "Customs Form"
4. Click **Generate**
5. **Copy the 16-character password** (you'll need this)

#### Step 3: Update config.php

Edit the configuration file:

```bash
nano /srv/www/sumiya_1/config.php
```

Update these values:

```php
return [
    // SMTP Settings
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,
    'smtp_encryption' => 'tls',

    // Email Credentials
    'smtp_username' => 'your-email@gmail.com',        // Your Gmail address
    'smtp_password' => 'xxxx xxxx xxxx xxxx',         // App password from Step 2

    // Sender Information
    'from_email' => 'your-email@gmail.com',           // Same as username
    'from_name' => 'Монголын Гааль Систем',

    // Receiver Email
    'to_email' => 'recipient@example.com',            // Where to receive submissions
];
```

**Security Note**: The `config.php` file contains sensitive credentials. Ensure it's not publicly accessible (nginx configuration already prevents direct access to `.php` files without execution).

### For Other Email Providers

If using a different SMTP provider, update these settings:

| Provider | SMTP Host | Port | Encryption |
|----------|-----------|------|------------|
| Gmail | smtp.gmail.com | 587 | tls |
| Outlook/Hotmail | smtp-mail.outlook.com | 587 | tls |
| Yahoo | smtp.mail.yahoo.com | 587 | tls |
| Custom SMTP | your.smtp.host | 587 or 465 | tls or ssl |

---

## Testing the Application

### Step 1: Access the Application

Open your web browser and navigate to:

- **If using directory path**: `http://your-server-ip/sumiya_1/`
- **If using subdomain**: `http://feedback.yourserver.com/`
- **If on fresh server**: `http://your-server-ip/`

### Step 2: Visual Check

You should see:
- Navy blue header with logo and "МОНГОЛЫН ГААЛЬ"
- Page title "Санал хүсэлт илгээх"
- Three buttons: Санал, Гомдол, Өргөдөл

### Step 3: Test Form Submission

1. Click any of the three buttons
2. A text area should appear with a back button
3. Enter some test text
4. Click **Илгээх** button
5. Button should show a loading spinner with "Илгээж байна..."
6. After a few seconds, you should see success message: "Таны хүсэлт амжилттай илгээгдлээ. Баярлалaa!"
7. Check the receiver email inbox for the submission

### Step 4: Verify Email Format

The email should contain:
- Subject: `[Санал] Шинэ хүсэлт ирлээ` (type varies)
- Body with:
  - Төрөл: [type]
  - Огноо: [timestamp]
  - Мессеж: [user's message]

---

## Troubleshooting

### Issue: Cannot Access the Website (404 Error)

**Possible Causes:**
1. Nginx not running
2. Wrong file path in nginx config
3. Nginx configuration not reloaded

**Solutions:**

```bash
# Check if nginx is running
sudo systemctl status nginx

# If not running, start it
sudo systemctl start nginx

# Check nginx error logs
sudo tail -50 /var/log/nginx/error.log

# Verify file path exists
ls -la /srv/www/sumiya_1/

# Reload nginx configuration
sudo nginx -t && sudo systemctl reload nginx
```

### Issue: PHP Files Download Instead of Execute

**Cause**: PHP-FPM not configured or not running

**Solution:**

```bash
# Check PHP-FPM status
systemctl status php*-fpm

# If not running, start it
sudo systemctl start php8.3-fpm

# Check nginx PHP configuration
sudo nginx -t

# Verify PHP socket exists
ls /run/php/

# Restart both services
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
```

### Issue: Form Submits but No Email Received

**Possible Causes:**
1. Wrong email credentials
2. App password not generated
3. SMTP blocked by firewall
4. Gmail security blocking the login

**Solutions:**

```bash
# Check PHP error logs
sudo tail -50 /var/log/nginx/error.log

# Test SMTP credentials manually with telnet
telnet smtp.gmail.com 587

# Verify config.php has correct credentials
cat /srv/www/sumiya_1/config.php

# Check if port 587 is open
sudo netstat -tlnp | grep 587
```

**For Gmail:**
- Ensure 2FA is enabled
- Generate a new App Password
- Check Google Account security page for blocked sign-in attempts
- Allow "Less secure app access" if needed (not recommended)

### Issue: Permission Denied Errors

**Cause**: Wrong file permissions

**Solution:**

```bash
# Fix ownership
sudo chown -R www-data:www-data /srv/www/sumiya_1

# Fix permissions
sudo chmod -R 755 /srv/www/sumiya_1

# Restart services
sudo systemctl restart nginx php8.3-fpm
```

### Issue: "Class 'PHPMailer' Not Found"

**Cause**: Composer dependencies not installed

**Solution:**

```bash
cd /srv/www/sumiya_1
composer install

# Verify vendor directory exists
ls -la vendor/phpmailer/
```

### Issue: Slow Email Sending

This is normal. SMTP email sending can take 3-10 seconds depending on:
- Network latency to SMTP server
- Email server processing time
- Network speed

The loading spinner provides user feedback during this time.

### Checking Logs

```bash
# Nginx error log
sudo tail -f /var/log/nginx/error.log

# PHP-FPM error log (path may vary)
sudo tail -f /var/log/php8.3-fpm.log

# System log
sudo tail -f /var/log/syslog
```

---

## File Structure

```
/srv/www/sumiya_1/
│
├── index.html                          # Main page (frontend)
├── submit.php                          # Form handler (backend)
├── config.php                          # Email configuration
├── composer.json                       # PHP dependencies definition
├── composer.lock                       # Locked dependency versions
├── d8f55e86-3b3e-41e3-b45d-d01f3e660111_Logo-2.webp  # Logo image
│
└── vendor/                             # Composer dependencies (auto-generated)
    └── phpmailer/                      # PHPMailer library
        └── phpmailer/
            └── ...
```

### Important Files to Configure

1. **config.php** - Email settings (MUST configure before use)
2. **Nginx configuration** - Web server routing

### Files You Should NOT Modify

1. **vendor/** directory - Auto-generated by Composer
2. **composer.lock** - Maintains dependency versions

---

## Security Considerations

### 1. Protect config.php

The `config.php` file contains sensitive email credentials. It should:
- **NOT** be directly accessible via web browser (nginx prevents this)
- Have restrictive file permissions: `644` or `600`
- Be excluded from version control (add to `.gitignore` if using git)

### 2. HTTPS/SSL

For production use, you should enable HTTPS:

```bash
# Install Certbot for Let's Encrypt SSL
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate (replace with your domain)
sudo certbot --nginx -d your-domain.com

# Certbot will automatically update nginx configuration
```

### 3. Rate Limiting

To prevent spam, consider adding nginx rate limiting:

```nginx
# In nginx configuration, add to http block
limit_req_zone $binary_remote_addr zone=feedback:10m rate=5r/m;

# In location block for submit.php
location ~ submit\.php$ {
    limit_req zone=feedback burst=2;
    # ... rest of PHP configuration
}
```

### 4. Firewall

Ensure only necessary ports are open:

```bash
# If using UFW
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable
```

---

## Backup and Maintenance

### Backup

Regularly backup these files:

```bash
# Backup script example
tar -czf sumiya_1_backup_$(date +%Y%m%d).tar.gz /srv/www/sumiya_1/
```

### Updates

Update PHP dependencies periodically:

```bash
cd /srv/www/sumiya_1
composer update
```

### Monitoring

Monitor error logs for issues:

```bash
# Create a simple monitoring script
watch -n 60 'tail -20 /var/log/nginx/error.log'
```

---

## Support

### Common Commands Reference

```bash
# Restart nginx
sudo systemctl restart nginx

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm

# Test nginx config
sudo nginx -t

# View nginx error log
sudo tail -50 /var/log/nginx/error.log

# Check running services
systemctl status nginx php8.3-fpm

# Check open ports
sudo netstat -tlnp

# Check file permissions
ls -la /srv/www/sumiya_1/
```

### Additional Resources

- Nginx documentation: https://nginx.org/en/docs/
- PHPMailer documentation: https://github.com/PHPMailer/PHPMailer
- PHP-FPM configuration: https://www.php.net/manual/en/install.fpm.php
- Gmail SMTP settings: https://support.google.com/mail/answer/7126229

---

## Quick Start Checklist

- [ ] Server has Ubuntu 20.04+ installed
- [ ] Nginx installed and running
- [ ] PHP 8.0+ installed
- [ ] PHP-FPM installed and running
- [ ] Composer installed
- [ ] Project files uploaded to `/srv/www/sumiya_1/`
- [ ] Ran `composer install` in project directory
- [ ] Nginx configuration added and tested
- [ ] File permissions set correctly (`www-data:www-data`, `755`)
- [ ] Gmail App Password generated
- [ ] ⚠️ **`config.php` updated with real email credentials** (replace ALL placeholder values!)
- [ ] Nginx reloaded: `sudo systemctl reload nginx`
- [ ] Tested form submission successfully
- [ ] Received test email

## Post-Download Configuration

After downloading/cloning this repository, you **MUST** edit `config.php` before the application will work:

1. Open `config.php` in a text editor
2. Replace `YOUR_EMAIL@gmail.com` with your actual Gmail address (appears in 3 places)
3. Replace `YOUR_APP_PASSWORD` with your Gmail App Password
4. Replace `RECIPIENT@example.com` with the email address that should receive submissions
5. Save the file

**The application will NOT send emails until this is done!**

---

**Deployment Date**: _[Fill in after deployment]_
**Deployed By**: _[Your name]_
**Server IP**: _[Server IP address]_
**Access URL**: _[Application URL]_

---

*This documentation is maintained as part of the Mongolian Customs Feedback Form project.*
