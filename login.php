<?php
session_start();
require_once 'config.php';

$err = "";
$success = "";

// Handle Login Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['logmail']) ? htmlspecialchars(trim($_POST['logmail'])) : '';
    $password = isset($_POST['logpass']) ? $_POST['logpass'] : '';
    
    if (empty($email) || empty($password)) {
        $err = "Email and password are required.";
    } else {
        // 1. Check Admin Table
        $stmt = $conn->prepare("SELECT password, name FROM admintable WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_password, $adminName);
            $stmt->fetch();
            if (password_verify($password, $db_password)) {
                $_SESSION['admin'] = $adminName;
                header("Location: Admin.php");
                exit();
            } else {
                $err = "Incorrect password.";
            }
            $stmt->close();
        } else {
            $stmt->close();
            // 2. Check Driver Table
            $stmt = $conn->prepare("SELECT password, driver_id FROM driver WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($db_password, $driver_id);
                $stmt->fetch();
                if (password_verify($password, $db_password)) {
                    $_SESSION['did'] = $driver_id;
                    header("Location: Driver.php");
                    exit();
                } else {
                    $err = "Incorrect password.";
                }
                $stmt->close();
            } else {
                $stmt->close();
                // 3. Check Passenger Table
                $stmt = $conn->prepare("SELECT password, pass_id, name, gender FROM passenger WHERE email LIKE ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($db_password, $pass_id, $name, $gender);
                    $stmt->fetch();
                    if (password_verify($password, $db_password)) {
                        $_SESSION['name'] = $name;
                        $_SESSION['gender'] = $gender;
                        $_SESSION['uid'] = $pass_id;
                        header("Location: passenger.php");
                        exit();
                    } else {
                        $err = "Incorrect password.";
                    }
                    $stmt->close();
                } else {
                    $stmt->close();
                    // 4. Check Temporary Driver Table (pending approval)
                    $stmt = $conn->prepare("SELECT email FROM temporarydriver WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $stmt->store_result();
                    
                    if ($stmt->num_rows > 0) {
                        $err = "Your driver account is pending approval by the administrator.";
                    } else {
                        $err = "Account not found with this email.";
                    }
                    $stmt->close();
                }
            }
        }
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="shortcut icon" href="images/favicon.ico" title="Favicon"/>
    <title>Login | RICKSHAWHUB</title>
    <link rel="stylesheet" href="styles/auth.css">
</head>
<body>
    <!-- Background Animation -->
    <div class="area">
        <ul class="circles">
            <li></li><li></li><li></li><li></li><li></li>
            <li></li><li></li><li></li><li></li><li></li>
        </ul>
    </div>

    <!-- Main Auth Card -->
    <div class="auth-card">
        <!-- Close Button -->
        <a href="index.php" class="cross-btn" aria-label="Go to home">&times;</a>

        <!-- Left Column: Branding / Hero -->
        <div class="auth-hero">
            <div class="hero-branding">
                <h1 class="hero-logo">RICKSHAW<span>HUB</span>.</h1>
                <p class="hero-desc">Connecting you with local rides easily. Affordable, on time, and anywhere you need to go.</p>
            </div>
            
            <div class="hero-graphics">
                <img src="assets/header.png" alt="Auto Rickshaw Graphic">
            </div>

            <div class="hero-footer">
                <p>&copy; <?php echo date("Y"); ?> RikshawHub. All rights reserved.</p>
            </div>
        </div>

        <!-- Right Column: Login Form -->
        <div class="auth-form-section">
            <h3 class="auth-view-title" style="margin-bottom: 10px;">Welcome <span>Back</span></h3>
            <p style="color: var(--text-light); margin-bottom: 25px; font-size: 0.9rem;">Sign in to your passenger, driver, or admin account.</p>

            <!-- Global Notifications -->
            <?php if (!empty($err)): ?>
                <div class="alert alert-error">
                    <i class="ri-error-warning-line"></i>
                    <span><?php echo $err; ?></span>
                </div>
            <?php endif; ?>

            <form method="post" action="login.php">
                <div class="form-grid">
                    <div class="form-group form-group-full">
                        <label class="form-label">Email Address</label>
                        <div class="input-with-icon">
                            <input type="email" placeholder="name@example.com" name="logmail" required>
                            <i class="ri-mail-line"></i>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-full">
                        <label class="form-label">Password</label>
                        <div class="input-with-icon">
                            <input type="password" placeholder="••••••••" name="logpass" required>
                            <i class="ri-lock-line"></i>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 30px;">
                    <button type="submit" class="submit-btn">Login</button>
                    
                    <div style="display: flex; flex-direction: column; gap: 8px; text-align: center; margin-top: 10px;">
                        <a href="PassReg.php" class="toggle-view-link">Need a ride? <strong>Register as passenger</strong></a>
                        <a href="DriverReg.php" class="toggle-view-link">Want to earn? <strong>Register as driver</strong></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
