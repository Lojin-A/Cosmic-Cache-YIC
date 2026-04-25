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
<title>Lost Items</title>
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
<a href="post_lost.php" class="nav-pill" style="display: inline-block; padding: 10px 40px; margin-bottom: 20px;">Report a Lost Item</a>
<h2 class="sub-greeting">Browse Lost Items</h2>
</section>

<section class="card-container">

<div class="action-card">
<div class="image-area-img">
<img src="../Assets/Media/found_lost1.jpg" style="width:100%; height:100%;"></div>
<div class="card-desc">
<p>_________</p>
<p>_________</p>
</div>
<button class="card-btn">Claim</button>
</div>

<div class="action-card">
<div class="image-area-img">
<img src="../Assets/Media/found_lost2.jpg" style="width:100%; height:100%;">
</div>
<div class="card-desc">
<p>_________</p>
<p>_________</p>
</div>
<button class="card-btn">Claim</button>
</div>

<div class="action-card">
<div class="image-area-img">
<img src="../Assets/Media/found-lost3.jpg" style="width:100%; height:100%;">
</div>
<div class="card-desc">
<p>_________</p>
<p>_________</p>
</div>
<button class="card-btn">Claim</button>
</div>

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