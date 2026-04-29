<?php
session_start();
require '../includes/db_connect.php';

$error = "";
$submitted_email = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $submitted_email = htmlspecialchars($email);
    $sql = "SELECT * FROM User WHERE Email = ? AND Password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['User_id'];
        $_SESSION['role'] = $user['Role'];     
        $_SESSION['name'] = $user['Name'];
        
        if ($_SESSION['role'] === 'admin') {
            header("Location: admin.php"); 
        } else {
            header("Location: ../index.php"); 
        }
        exit();
    } else {
        $error = "Incorrect email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmic Cache YIC - Login</title>
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
                <h2 class="form-title">Log In</h2>
                
                <form id="login-form" method="POST" action="log_in.php">
                    
                    <div class="input-group">
                        <label for="email">Email :</label>
                        <input type="text" id="email" name="email" placeholder="Enter your email">
                    </div>

                    <div class="input-group">
                        <label for="password">Password :</label>
                        <input type="password" id="password" name="password" placeholder="Enter password">
                    </div>

                    <?php if(!empty($error)): ?>
                        <p id="php-error" class="error-text"><?php echo $error; ?></p>
                    <?php endif; ?>

                    <p id="error-message" class="error-text hidden"></p>

                    <button type="submit" class="card-btn form-btn">Login</button>
                </form>
                <br>

                <div class="signup-prompt">
                    <p>Don't have an account yet?</p>
                    <a href="sign_up.php" class="dashed-link">sign up</a>
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