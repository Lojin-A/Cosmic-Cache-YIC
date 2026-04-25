<?php
session_start();
require '../Includes/db_connect.php';
$stmt = $conn->prepare("SELECT * FROM items WHERE Status = 'found' ORDER BY Item_id DESC");
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 1. Check if the user is logged in
$is_logged_in = false;
$user_name = "";
$user_email = "";

if (isset($_SESSION['user_id'])) {
    $is_logged_in = true;
    $user_name = $_SESSION['name'];
    
    // Grab their email from the database for the My Account popup
    $sql = "SELECT Email FROM User WHERE User_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if ($user) {
        $user_email = $user['Email'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Found Items</title>
<link rel="stylesheet" href="../Assets/CSS/style.css">
</head>

<body>
<main class="os-window">

<header class="window-topbar">
<a href="../index.php" class="title-link">
<div class="title-group">
<img src="../Assets/Media/pixel_sparkle.png" class="title-icon">
<div class="window-title">Cosmic Cache YIC</div>
</div>
</a>

<nav class="top-nav">
<a href="#" id="btn-account" class="nav-pill">My Account</a>
<a href="#" id="btn-notifications" class="nav-pill">Notifications</a>
<a href="logout.php" class="nav-pill logout-pill">Log out</a>
</nav>
</header>

<section class="welcome-section">
<a href="post_found.php" class="nav-pill" style="display: inline-block; padding: 10px 40px; margin-bottom: 20px;">Post a New Found Item</a>
<h2 class="sub-greeting">Browse Found Items</h2>
</section>

<section class="card-container">
    <?php if (empty($items)): ?>
        <!-- Display this message if the database table is empty -->
        <p style="text-align: center; color: white;">No found items in the database yet.</p>
    <?php else: ?>
        <!-- Loop through each row fetched from the Items table -->
        <?php foreach ($items as $item): ?>
            <div class="action-card">
                <div class="image-area-img">
                    <!-- Show item image if exists, otherwise show default placeholder -->
                    <img src="../Assets/Media/<?php echo !empty($item['Image']) ? $item['Image'] : 'pixel_sparkle.png'; ?>" style="width:100%; height:100%;">
                </div>
                <div class="card-desc">
                    <!-- Display item title and location from the database -->
                    <p><strong><?php echo htmlspecialchars($item['Title']); ?></strong></p>
                    <p><?php echo htmlspecialchars($item['Location']); ?></p>
                </div>
                <button class="card-btn">Claim</button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<footer class="window-footer">
Cosmic Cache YIC © 2026 | Developed by Lojin & Jana
</footer>

        <?php if ($is_logged_in): ?>
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
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
                    <button class="card-btn" onclick="closePopups()">Close</button>
                </div>
            </div>
        <?php endif; ?>

    </main>

<script src="../Assets/JS/script.js"></script>
</body>
</html>
