<?php
$host = 'localhost';
$dbname = 'cosmic_cache_db';
$username = 'root';
$password = '';

try {
    
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  
            PDO::ATTR_EMULATE_PREPARES => false               
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
}
?>

