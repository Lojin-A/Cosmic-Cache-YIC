<?php
session_start();
require '../Includes/db_connect.php';

// Fetch Approved Found Items from the database
$stmt = $conn->prepare("SELECT * FROM Items WHERE Type = 'Found' AND Status = 'Approved' ORDER BY Item_id DESC");
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
<link rel="stylesheet" href="../Assets/CSS/style.css?v=5">
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

<section class="browse-container">
    
    <?php foreach ($items as $item): ?>
        <div class="browse-card">
            <div class="browse-image-area">
                <img src="../Assets/Media/<?php echo !empty($item['Image']) ? htmlspecialchars($item['Image']) : 'pixel_sparkle.png'; ?>" alt="Item Image">
            </div>
            <div class="card-desc">
                <p><strong><?php echo htmlspecialchars($item['Title']); ?></strong></p>
                <p><?php echo htmlspecialchars($item['Location']); ?></p>
            </div>
            <button class="card-btn" style="margin-top: auto;">Claim</button>
        </div>
    <?php endforeach; ?>
    
    <?php if (empty($items)): ?>
        <p style="text-align: center; color: #31365a; width: 100%; font-size: 20px;">No found items yet.</p>
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