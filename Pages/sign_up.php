<?php
session_start();
require '../includes/db_connect.php';

$error = "";
$submitted_name = "";
$submitted_email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $submitted_name = htmlspecialchars($name);
    $submitted_email = htmlspecialchars($email);
    $check_sql = "SELECT * FROM User WHERE Email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->execute([$email]);

    if ($check_stmt->rowCount() > 0) {
        $error = "This email is already registered. Please log in.";
    } else {
        $insert_sql = "INSERT INTO User (Email, Name, Password, Role) VALUES (?, ?, ?, 'Student')";
        $insert_stmt = $conn->prepare($insert_sql);
        
        if ($insert_stmt->execute([$email, $name, $password])) {
            $_SESSION['user_id'] = $conn->lastInsertId(); 
            $_SESSION['role'] = 'Student';
            $_SESSION['name'] = $name;

            header("Location: ../index.php");
            exit();
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmic Cache YIC - Sign Up</title>
    <link rel="stylesheet" href="../Assets/CSS/style.css">
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
        </header>

        <section class="form-section">
            <div class="form-card">
                <h2 class="form-title">Sign Up</h2>
                
                <form id="signup-form" method="POST" action="sign_up.php">
                    
                    <div class="input-group">
                        <label for="name">Name :</label>
                        <input type="text" id="name" name="name" placeholder="Enter your name" value="<?php echo $submitted_name; ?>">
                    </div>

                    <div class="input-group">
                        <label for="email">Email :</label>
                        <input type="text" id="email" name="email" placeholder="Enter your email" value="<?php echo $submitted_email; ?>">
                    </div>

                    <div class="input-group">
                        <label for="password">Password :</label>
                        <input type="password" id="password" name="password" placeholder="Create password" autocomplete="new-password">
                    </div>

                    <div class="input-group">
                        <label for="confirm-password">Confirm Password :</label>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm password" autocomplete="new-password">
                    </div>

                    <?php if(!empty($error)): ?>
                        <p id="php-signup-error" class="error-text"><?php echo $error; ?></p>
                    <?php endif; ?>

                    <p id="signup-error" class="error-text hidden"></p>

                    <button type="submit" class="card-btn form-btn">Sign Up</button>
                </form>

            </div>
        </section>

        <footer class="window-footer">
            Cosmic Cache YIC © 2026 | Developed by Lojin & Jana
        </footer>

    </main>

    <script src="../Assets/JS/script.js"></script>
</body>
</html>