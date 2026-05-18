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

$success_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['claim_item_id'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }
    if (!isset($_SESSION['user_id'])) {
        die("Error: You must be logged in to claim an item.");
    }

    $item_id = $_POST['claim_item_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $sql = "INSERT INTO Claim (Item_id, User_id, Status) VALUES (?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$item_id, $user_id]);
        
        $success_message = "Your claim request has been sent to the Admin!";
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}

$stmt = $conn->prepare("SELECT * FROM Items WHERE Type = 'Found' AND Status = 'Approved' ORDER BY Item_id DESC");
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    
    <a href="#" id="btn-notifications" class="nav-pill" style="position: relative;">
        Notifications
        <span id="unread-dot" class="hidden" style="position: absolute; top: -2px; right: -2px; height: 14px; width: 14px; background-color: #d9534f; border-radius: 50%; border: 2px solid #31365a;"></span>
    </a>
    
    <a href="logout.php" class="nav-pill logout-pill">Log out</a>
</nav>
</header>

<section class="welcome-section">
<a href="post_found.php" class="nav-pill" style="display: inline-block; padding: 10px 40px; margin-bottom: 20px;">Post a New Found Item</a>
<h2 class="sub-greeting">Browse Found Items</h2>

<?php if(!empty($success_message)): ?>
    <p style="color: #4b527e; font-size: 20px; font-weight: bold; margin-top: 10px;">✨ <?php echo $success_message; ?> ✨</p>
<?php endif; ?>
</section>

<section class="browse-container">
   <?php if (empty($items)): ?>
    <?php else: ?>
        <?php foreach($items as $item): ?>
            <div class="browse-card">
                <div class="browse-image-area">
                    <?php if(!empty($item['Image'])): ?>
                        <img src="../Assets/Media/<?php echo htmlspecialchars($item['Image'], ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100%; max-height: 100%;">
                    <?php else: ?>
                        <p style="color: #31365a;">No Image</p>
                    <?php endif; ?>
                </div>
                <div class="card-desc">
                    <p><strong><?php echo htmlspecialchars($item['Title'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
                    <p><?php echo htmlspecialchars($item['Location'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="claim_item_id" value="<?php echo $item['Item_id']; ?>">
                    <button type="submit" class="card-btn">Claim</button>
                </form>
                
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

                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?></p>
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