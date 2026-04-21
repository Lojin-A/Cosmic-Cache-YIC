<?php
session_start();
require '../includes/db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$stmt = $conn->prepare("INSERT INTO items (item_name, location, description, status) VALUES (?, ?, ?, 'found')");
$stmt->execute([$_POST['found-item-name'], $_POST['found-item-location'], $_POST['found-item-description']]);
header("Location: found_items.php");
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Report Lost Item - Cosmic Cache YIC</title>
<link rel="stylesheet" href="../Assets/CSS/style.css">
</head>

<body>

<main class="os-window">

<header class="window-topbar">
<a href="../index.html" class="title-link">
<div class="title-group">
<img src="../Assets/Media/pixel_sparkle.png" alt="icon" class="title-icon">
<div class="window-title">Cosmic Cache YIC</div>
</div>
</a>
<nav class="top-nav">
<a href="#" class="nav-pill" id="btn-account">My Account</a>
<a href="#" class="nav-pill" id="btn-notifications">Notifications</a>
<a href="../index.html" class="nav-pill logout-pill">Log out</a>
</nav>
</header>

<section class="form-section">
<div class="form-card">
<h2 class="form-title">Report A Lost Item</h2>

<form id="report-lost-form" method="POST">

<div class="input-group">
<label>Item Name :</label>
<input type="text" name="item-name" id="item-name" placeholder="What did you lose?" required>
</div>

<div class="input-group">
<label>Last Seen At :</label>
<input type="text" name="item-location" id="item-location" placeholder="e.g. Lab 4" required>
</div>

<div class="input-group">
<label>Date :</label>
<input type="date" id="item-date">
</div>

<div class="input-group">
<label>Description :</label>
<input type="text" name="item-description" id="item-description" placeholder="Color, brand, etc...">
</div>

<div class="input-group">
<label>
Upload Photo :<br>
<span class="sub-greeting">optional</span>
</label>
<input type="file" id="item-photo" accept="image/*">
</div>

<p id="report-error" class="error-text hidden"></p>

<button type="submit" class="card-btn form-btn">Submit</button>
</form>

</div>
</section>

<footer class="window-footer">
Cosmic Cache YIC © 2026 | Developed by Lojin & Jana
</footer>

<div id="popup-notifications" class="popup-overlay hidden">
<div class="popup-box">
<h3>Notifications</h3>
<p>You have no new notifications at this time.</p>
<button class="card-btn" onclick="closePopups()">Close</button>
</div>
</div>

<div id="popup-account" class="popup-overlay hidden">
<div class="popup-box">
<h3>Your Profile</h3>
<p><strong>Name:</strong> Lojin</p>
<p><strong>Email:</strong> lojin@example.com</p>
<button class="card-btn" onclick="closePopups()">Close</button>
</div>
</div>

</main>
<script src="../Assets/JS/script.js"></script>

</body>
</html>
