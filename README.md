# Cosmic Cache YIC - Lost & Found System

## Overview
Cosmic Cache YIC is a centralized lost-and-found web application designed to help students and staff securely report and claim misplaced items on campus. 

## Features
* **Authentication:** Secure login/logout flow with password hashing (bcrypt) and session management.
* **Authorization:** Role-based access control (Student vs Admin).
* **Item Management:** Post lost/found items with image uploads and view approved listings.
* **Claims & Moderation:** Students can claim items; Admins can approve/deny posts and claims via a secure dashboard.
* **Live Notifications:** Real-time, localized popup notifications alerting users when their posts or claims are approved or denied.

## Security Implemented
* **SQL Injection Prevention:** Used PDO prepared statements with parameter binding for all database queries.
* **XSS Prevention:** Output escaping using htmlspecialchars() with ENT_QUOTES and UTF-8 encoding.
* **CSRF Protection:** Implemented session-based CSRF tokens validated via hash_equals() on all state-changing actions.
* **Authentication Security:** Passwords hashed with password_hash(), and session_regenerate_id(true) applied on login.

## Setup Instructions
1. Install a local server environment (e.g., Laragon or XAMPP) and start the Apache and MySQL services.
2. Open phpMyAdmin and create a new database named `cosmic_cache_db`.
3. Import the provided `cosmic_cache_db.sql` file to generate the tables and sample data.
4. Place the project folder into your server's root directory (`htdocs` for XAMPP or `www` for Laragon).
5. Open `Includes/db_connect.php` and update the `$passwords` array to match your local MySQL root password if necessary.
6. Navigate to `http://localhost/cosmic_cache/index.php` in your web browser.

## Login Credentials
**Admin Account:**
* Email: Admin@gmail.com
* Password: admin123*admin

