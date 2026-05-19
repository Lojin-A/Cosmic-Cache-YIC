# Cosmic Cache YIC - Lost & Found System

## Overview
Cosmic Cache YIC is a centralized lost-and-found web application designed to help students and staff securely report and claim misplaced items on campus. 

## Features
* **Authentication:** Secure login/logout flow using `password_hash()` and `password_verify()` with secure session management.
* **Authorization:** Role-based access control (Student vs Admin).
* **Item Management:** Post lost/found items with image uploads and view approved listings.
* **Claims & Moderation:** Students can claim items; Admins can approve/deny posts and claims via a secure dashboard.
* **Live Notifications:** Localized popup notifications alerting users when their posts or claims are approved or denied.

## Security Implemented
* **SQL Injection Prevention:** Used PDO prepared statements with positional parameters (`?`) for all database queries.
* **XSS Prevention:** Output escaping using `htmlspecialchars()` to sanitize all user-submitted data.
* **CSRF Protection:** Implemented session-based CSRF tokens validated on all state-changing actions (like form submissions).
* **Authentication Security:** Passwords securely hashed with `password_hash()`, and `session_regenerate_id(true)` applied on login to prevent session fixation.

## Setup Instructions
1. Open **Laragon** and click "Start All" to run the Apache and MySQL services.
2. Open your database manager (click the "Database" button in Laragon, or open phpMyAdmin) and create a new database named `cosmic_cache_db`.
3. **Import the Database:** Import the provided `cosmic_cache_db.sql` file into your new database to generate all the necessary tables. *(This must be done before setting up the admin).*
4. Place the project folder into Laragon's root directory (usually located at `C:\laragon\www`).
5. Open `Includes/db_connect.php` and update the `$username` and `$password` variables. *(Note: Laragon's default credentials are usually username: `root` and password: `""`)*.

### Admin Account Setup (Required)
Because our system enforces strict password hashing for security, the Admin account must be securely generated through the system:

1. Create a file named `setup_admin.php` in the project root folder and paste the following code:
```php
<?php
require 'Includes/db_connect.php';

$email = 'Admin@gmail.com';
$name = 'Admin';
$plain_password = 'admin123*admin';
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

$sql = "INSERT INTO User (Email, Name, Password, Role) VALUES (?, ?, ?, 'admin')";
$stmt = $conn->prepare($sql);
$stmt->execute([$email, $name, $hashed_password]);

echo "Admin created successfully! CRITICAL: Delete this file (setup_admin.php) immediately.";
?>
2. Navigate to http: `//localhost/cosmic_cache/setup_admin.php` in your browser to execute the script and insert the admin into the database.
3. **Important:** Delete `setup_admin.php` from your folder immediately after running it to maintain system security.

## Launch
Navigate to `http://localhost/cosmic_cache/index.php` in your web browser. 

## Login Credentials
**Admin Account:**
* Email: Admin@gmail.com
* Password: admin123*admin