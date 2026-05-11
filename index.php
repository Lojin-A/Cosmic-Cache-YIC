<?php
session_start();
require 'includes/db_connect.php'; 

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
    <title>Cosmic Cache YIC - Home</title>
    <link rel="stylesheet" href="Assets/CSS/style.css">
</head>
<body>

    <main class="os-window">
        
        <header class="window-topbar">
            <a href="index.php" class="title-link">
                <div class="title-group">
                    <img src="Assets/Media/pixel_sparkle.png" alt="icon" class="title-icon">
                    <div class="window-title">Cosmic Cache YIC</div>
                </div>
            </a>

            <nav class="top-nav">
                <?php if ($is_logged_in): ?>
                    <a href="#" id="btn-account" class="nav-pill">My Account</a>
                    
                    <a href="#" id="btn-notifications" class="nav-pill" style="position: relative;">
                        Notifications
                        <span id="unread-dot" class="hidden" style="position: absolute; top: -2px; right: -2px; height: 14px; width: 14px; background-color: #d9534f; border-radius: 50%; border: 2px solid #31365a;"></span>
                    </a>
                    
                    <a href="Pages/logout.php" class="nav-pill logout-pill">Log out</a>
                <?php else: ?>
                    <a href="Pages/log_in.php" class="nav-pill logout-pill">Log in</a>
                <?php endif; ?>
            </nav>
        </header>

        <section class="welcome-section">
            <h2 class="greeting">
                <?php if ($is_logged_in): ?>
                    Welcome <?php echo htmlspecialchars($user_name); ?>!
                <?php else: ?>
                    Welcome to Cosmic Cache YIC!
                <?php endif; ?>
            </h2>
            <p class="sub-greeting">What would you like to do today?</p>
        </section>

        <section class="card-container">
            
            <div class="action-card">
                <div class="image-area">
                   <img src="Assets/Media/lost.jpeg" style="height: 100%; width: 100%;">
                </div> 
                <p class="card-desc">Did you lose something important on campus? File a report here so others can help you find it.</p>
                <a href="<?php echo $is_logged_in ? 'Pages/lost_items.php' : 'Pages/log_in.php'; ?>" class="card-btn">Browse Lost Items</a>
            </div>

            <div class="action-card">
                 <div class="image-area">
                    <img src="Assets/Media/found.jpeg" style="height: 100%; width: 100%;">
                </div>
                <p class="card-desc">Did you find an item that belongs to someone else? Browse the database or post a new found item.</p>
                <a href="<?php echo $is_logged_in ? 'Pages/found_items.php' : 'Pages/log_in.php'; ?>" class="card-btn">Browse Found Items</a>
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
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
                    <button class="card-btn" onclick="closePopups()">Close</button>
                </div>
            </div>
        <?php endif; ?>

    </main>

    <script src="Assets/JS/script.js"></script>

</body>
</html>