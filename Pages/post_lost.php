<?php
session_start();
require '../Includes/db_connect.php';

$update_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_name'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $new_name = trim($_POST['new_name']);
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE User SET Name = ? WHERE User_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$new_name, $user_id]);

    $_SESSION['name'] = $new_name;
    $user_name = $new_name;

    $update_message = "Name updated successfully!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $title = $_POST['item-name'];
    $location = $_POST['item-location'];
    $description = $_POST['item-description'];
    $date = $_POST['item-date'];

    // Safety check to ensure session exists
    if (!isset($_SESSION['user_id'])) {
        die("Error: Session missing. Please log in.");
    }
    $user_id = $_SESSION['user_id'];
    $image_name = NULL; 

    if (isset($_FILES['item-photo']) && $_FILES['item-photo']['error'] === UPLOAD_ERR_OK) {
        $original_name = $_FILES['item-photo']['name'];
        $image_name = time() . "_" . basename($original_name);
        $target_path = "../Assets/Media/" . $image_name;
        move_uploaded_file($_FILES['item-photo']['tmp_name'], $target_path);
    }
    

    try {
        $sql = "INSERT INTO Items 
                (User_id, Title, Description, Type, Location, Event_date, Status, Image)
                VALUES (?, ?, ?, 'Lost', ?, ?, 'Pending', ?)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $title, $description, $location, $date, $image_name]);
        header("Location: lost_items.php?success=1");
        exit();

    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
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
<title>Report Lost Item - Cosmic Cache YIC</title>
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
    <a href="#" id="btn-account" class="nav-pill">My Account</a>
    
    <a href="#" id="btn-notifications" class="nav-pill" style="position: relative;">
        Notifications
        <span id="unread-dot" class="hidden" style="position: absolute; top: -2px; right: -2px; height: 14px; width: 14px; background-color: #d9534f; border-radius: 50%; border: 2px solid #31365a;"></span>
    </a>
    
    <a href="logout.php" class="nav-pill logout-pill">Log out</a>
</nav>
</header>

<section class="form-section post-page" style="flex-direction: column; align-items: center; padding-top: 20px;">
    
    <h2 class="form-title" style="text-align: center; width: 100%; margin-bottom: 15px;">Report A Lost Item</h2>

    <div class="form-card" style="max-width: 650px; width: 100%;">

        <form id="report-lost-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

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
                <input type="date" name="item-date" id="item-date" required>
            </div>

            <div class="input-group">
                <label>Description :</label>
                <input type="text" name="item-description" id="item-description" placeholder="Color, brand, etc..." required>
            </div>

            <div class="input-group">
                <label>
                    Upload Photo :<br>
                    <span class="sub-greeting">optional</span>
                </label>
                <input type="file" name="item-photo" id="item-photo" accept="image/*">
            </div>

            <p id="report-error" class="error-text hidden"></p>

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
                    
                    <div id="notif-content" style="max-height: 200px; overflow-y: auto; text-align: left; margin-bottom: 20px;">
                    </div>

                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button class="card-btn" id="btn-clear-notifs" style="background-color: #d9534f; color: white;">Clear All</button>
                        <button class="card-btn" onclick="closePopups()">Close</button>
                    </div>
                </div>
            </div>

            <script>
                const currentUserId = "<?php echo $_SESSION['user_id']; ?>";
            </script>

            <div id="popup-account" class="popup-overlay hidden">
                <div class="popup-box">
                    <h3>Your Profile</h3>
                                        <?php if(!empty($update_message)): ?>
                     <p style="color: #618659; font-weight: bold;">
                    <?php echo $update_message; ?>
                      </p>
                  <?php endif; ?>

                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'); ?></p>
                    
                      <form method="POST" style="margin-top: 15px;">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                   <input 
                    type="text" 
                    name="new_name"
                    value="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>"
                    required
                    style="width:100%; max-width: 300px; padding:10px; border-radius:10px; border:none; margin-bottom:10px; box-sizing: border-box;">
                   <button type="submit" name="update_name" class="card-btn">Update Name</button><br><br>
              </form>
                    <button class="card-btn" onclick="closePopups()">Close</button>
                </div>
            </div>
        <?php endif; ?>

    </main>
<script src="../Assets/JS/script.js"></script>

</body>
</html>