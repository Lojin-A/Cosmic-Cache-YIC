<?php
session_start();
require '../Includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['found-item-name']; 
    $location = $_POST['found-item-location'];
    $description = $_POST['found-item-description'];
    $date = date('Y-m-d');

    $status = 'Pending';

    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO Items 
            (User_id, Title, Description, Type, Location, Event_date, Status)
            VALUES (?, ?, ?, 'Found', ?, ?, Approved)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $title, $description, $location, $date, $status]);

    header("Location: found_items.php?success=1");
    exit();
}

$is_logged_in = false;
$user_name = "";
$user_email = "";

if (isset($_SESSION['user_id'])) {
    $is_logged_in = true;
    $user_name = $_SESSION['name'];
    
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
<title>Post Found Item - Cosmic Cache YIC</title>
<link rel="stylesheet" href="../Assets/CSS/style.css?v=7">
</head>

<body>

<main class="os-window">

<header class="window-topbar">
<a href="../index.php" class="title-link">
<div class="title-group">
<img src="../Assets/Media/pixel_sparkle.png" alt="icon" class="title-icon">
<div class="window-title">Cosmic Cache YIC</div>
</div>
</a>
<nav class="top-nav">
<a href="#" class="nav-pill" id="btn-account">My Account</a>
<a href="#" class="nav-pill" id="btn-notifications">Notifications</a>
<a href="logout.php" class="nav-pill logout-pill">Log out</a>
</nav>
</header>

<section class="form-section post-page" style="flex-direction: column; align-items: center;">
    
    <h2 class="form-title" style="text-align: center; width: 100%;">Post A Found Item</h2>

    <div class="form-card" style="max-width: 650px; width: 100%;">
        
        <form id="report-found-form" method="POST" action="post_found.php" enctype="multipart/form-data">

            <div class="input-group">
                <label>Item Name :</label>
                <input type="text" name="found-item-name" id="found-item-name" placeholder="What did you find?" required>
            </div>

            <div class="input-group">
                <label>Where was it found? :</label>
                <input type="text" name="found-item-location" id="found-item-location" placeholder="e.g. Near the library" required>
            </div>

            <div class="input-group">
                <label>Description :</label>
                <input type="text" name="found-item-description" id="found-item-description" placeholder="Color, brand, etc...">
            </div>

            <div class="input-group">
                <label>
                    Upload Photo :<br>
                    <span class="sub-greeting">optional</span>
                </label>
                <input type="file" name="item-photo"  id="found-item-photo" accept="image/*">
            </div>

            <p id="found-error" class="error-text hidden"></p>

            <button type="submit" class="card-btn form-btn">Submit</button>
        </form>

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