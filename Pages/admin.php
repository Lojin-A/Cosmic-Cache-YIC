<?php
session_start();
require '../includes/db_connect.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$admin_message = "";

// =========================================================
// 1. HANDLE ADMIN BUTTON CLICKS
// =========================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    $item_id = $_POST['item_id'] ?? null;
    $claim_id = $_POST['claim_id'] ?? null;

    if ($action == 'deny_item') {
        
        $stmt = $conn->prepare("DELETE FROM Items WHERE Item_id = ?");
        $stmt->execute([$item_id]);
        $admin_message = "Item denied and deleted. Notification sent to user.";
    } 
    elseif ($action == 'accept_item') {
        
        $stmt = $conn->prepare("UPDATE Items SET Status = 'Approved' WHERE Item_id = ?");
        $stmt->execute([$item_id]);
        $admin_message = "Item approved! Notification sent to user.";
    } 
    elseif ($action == 'delete_lost') {
        
        $stmt = $conn->prepare("DELETE FROM Items WHERE Item_id = ?");
        $stmt->execute([$item_id]);
        $admin_message = "Lost item manually deleted.";
    } 
    elseif ($action == 'approve_claim') {
        
        $stmt = $conn->prepare("DELETE FROM Items WHERE Item_id = ?");
        $stmt->execute([$item_id]);
        $admin_message = "Claim approved! Item removed and notification sent to user to visit office.";
    } 
    elseif ($action == 'reject_claim') {
        
        $stmt = $conn->prepare("DELETE FROM Claim WHERE Claim_id = ?");
        $stmt->execute([$claim_id]);
        $admin_message = "Claim rejected. Item remains on the site. Notification sent to user.";
    }
}

// =========================================================
// 2. FETCH DATA FOR THE DASHBOARD
// =========================================================

$pending_items_stmt = $conn->query("SELECT * FROM Items WHERE Status = 'Pending'");
$pending_items = $pending_items_stmt->fetchAll();

$approved_lost_stmt = $conn->query("SELECT * FROM Items WHERE Status = 'Approved' AND Type = 'Lost'");
$approved_lost_items = $approved_lost_stmt->fetchAll();

$claims_sql = "
    SELECT Claim.Claim_id, Claim.Item_id, Items.Title, User.Name AS ClaimerName 
    FROM Claim 
    JOIN Items ON Claim.Item_id = Items.Item_id 
    JOIN User ON Claim.User_id = User.User_id 
    WHERE Claim.Status = 'Pending'
";
$claims_stmt = $conn->query($claims_sql);
$pending_claims = $claims_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmic Cache YIC - Admin Dashboard</title>
    <link rel="stylesheet" href="../Assets/CSS/style.css">
</head>
<body>

    <main class="os-window">
        
        <header class="window-topbar">
            <a href="#" class="title-link">
                <div class="title-group">
                    <img src="../Assets/Media/pixel_sparkle.png" alt="icon" class="title-icon">
                    <div class="window-title">Cosmic Cache YIC</div>
                </div>
            </a>
            <nav class="top-nav">
                <a href="logout.php" class="nav-pill logout-pill">Log out</a>
            </nav>
        </header>

        <section class="welcome-section">
            <h2 class="greeting">Welcome Admin!</h2>
            <?php if(!empty($admin_message)): ?>
                <p style="color: green; font-size: 20px; font-weight: bold;"><?php echo $admin_message; ?></p>
            <?php endif; ?>
        </section>

        <section class="card-container">
            
            <div class="action-card">
                <h3 class="form-title" style="margin-bottom: 10px; font-size: 28px;">Item Posts</h3>
                
                <div class="scroll-box">
                    
                    <?php foreach ($pending_items as $item): ?>
                        <div class="admin-item">
                            <?php if(!empty($item['Image'])): ?>
                                <div class="image-area" style="width: 120px; height: 120px; margin: 0 auto 15px auto;">
                                    <img src="../Assets/Media/<?php echo htmlspecialchars($item['Image']); ?>" style="height: 100%; width: 100%; object-fit: contain;">
                                </div>
                            <?php endif; ?>
                            
                            <p style="color: #31365a; font-size: 20px;">ID: <strong><?php echo $item['Item_id']; ?></strong></p>
                            <p style="color: #31365a; font-size: 20px;">Type: <strong><?php echo $item['Type']; ?> (Pending)</strong></p>
                            <p style="color: #31365a; font-size: 20px;">Item: <strong><?php echo htmlspecialchars($item['Title']); ?></strong></p>
                            
                            <div class="btn-group">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="accept_item">
                                    <input type="hidden" name="item_id" value="<?php echo $item['Item_id']; ?>">
                                    <button type="submit" class="card-btn admin-btn">Accept</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="deny_item">
                                    <input type="hidden" name="item_id" value="<?php echo $item['Item_id']; ?>">
                                    <button type="submit" class="card-btn admin-btn deny-btn">Deny</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach ($approved_lost_items as $item): ?>
                        <div class="admin-item" style="border: 2px dashed #4b527e;">
                            <p style="color: #31365a; font-size: 20px;">ID: <strong><?php echo $item['Item_id']; ?></strong></p>
                            <p style="color: #31365a; font-size: 20px;">Type: <strong>Lost (Approved)</strong></p>
                            <p style="color: #31365a; font-size: 20px;">Item: <strong><?php echo htmlspecialchars($item['Title']); ?></strong></p>
                            
                            <div class="btn-group">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_lost">
                                    <input type="hidden" name="item_id" value="<?php echo $item['Item_id']; ?>">
                                    <button type="submit" class="card-btn admin-btn deny-btn" style="width: 100%;">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if(empty($pending_items) && empty($approved_lost_items)): ?>
                        <p style="color: #31365a; font-size: 20px;">No pending posts.</p>
                    <?php endif; ?>

                </div>
            </div>

            <div class="action-card">
                <h3 class="form-title" style="margin-bottom: 10px; font-size: 28px;">Pending Claim Requests</h3>
                
                <div class="scroll-box">
                    
                    <?php foreach ($pending_claims as $claim): ?>
                        <div class="admin-item">
                            <p style="color: #31365a; font-size: 20px;">Target Item: <strong><?php echo htmlspecialchars($claim['Title']); ?></strong></p>
                            <p style="color: #31365a; font-size: 20px;">Item ID: <strong><?php echo $claim['Item_id']; ?></strong></p>
                            <p style="color: #31365a; font-size: 20px;">Claimed by: <strong><?php echo htmlspecialchars($claim['ClaimerName']); ?></strong></p>
                            
                            <div class="btn-group">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="approve_claim">
                                    <input type="hidden" name="item_id" value="<?php echo $claim['Item_id']; ?>">
                                    <button type="submit" class="card-btn admin-btn">Approve</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="reject_claim">
                                    <input type="hidden" name="claim_id" value="<?php echo $claim['Claim_id']; ?>">
                                    <button type="submit" class="card-btn admin-btn deny-btn">Reject</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if(empty($pending_claims)): ?>
                        <p style="color: #31365a; font-size: 20px;">No pending claims.</p>
                    <?php endif; ?>

                </div>
            </div>

        </section>

        <footer class="window-footer">
            Cosmic Cache YIC © 2026 | Developed by Lojin & Jana
        </footer>

    </main>

    <script src="../Assets/JS/script.js"></script>
</body>
</html>