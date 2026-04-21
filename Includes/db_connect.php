<?php
$host = "localhost";
$username = "root";
$database = "cosmic_cache_db";
$passwords = ["Ja252267@&", "lomysql*123"]; 
$conn = null;

foreach ($passwords as $pwd) {
    try {
        $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $pwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        break;
    } catch(PDOException $e) {
    }
}
if (!$conn) {
    die("Database Connection Failed. Both passwords were incorrect.");
}
?>