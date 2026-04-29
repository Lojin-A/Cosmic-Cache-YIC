<?php
session_start();
require '../Includes/db_connect.php';

$stmt = $conn->prepare("SELECT * FROM Items WHERE Type = 'Lost' AND Status = 'Approved' ORDER BY Item_id DESC");
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$is_logged_in = false;
$user_name = "";
$user_email = "";

if (isset($_SESSION['user_id'])) {
    $is_logged_in = true;
    $user_name = $_SESSION['name'];
    
    $sql = "SELECT Email FROM User WHERE User_id = ?";
    $user_stmt = $conn->prepare($sql);
    $user_stmt->execute([$_SESSION['user_id']]);
    $user = $user_stmt->fetch();
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
<link rel="stylesheet" href="../Assets/CSS/style.css?v=3">
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

<section class="browse-container">

<?php foreach($items as $item): ?>
    <div class="browse-card">
        <div class="browse-image-area">
            <?php if(!empty($item['Image'])): ?>
                <img src="../Assets/Media/<?php echo htmlspecialchars($item['Image']); ?>">
            <?php else: ?>
                <p style="color: #31365a;">No Image</p>
            <?php endif; ?>
        </div>
        <div class="card-desc">
            <p><?php echo htmlspecialchars($item['Title']); ?></p>
            <p><?php echo htmlspecialchars($item['Event_date']); ?></p>
        </div>
    </div>
<?php endforeach; ?>

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