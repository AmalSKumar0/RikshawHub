<?php
session_start();
require_once 'config.php';

$err = "";

// Passenger Registration logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $gender = isset($_POST['gender']) ? htmlspecialchars(trim($_POST['gender'])) : '';
    $address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : '';
    
    if ($password !== $confirm) {
        $err = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("
            SELECT email FROM driver WHERE email = ?
            UNION
            SELECT email FROM temporarydriver WHERE email = ?
            UNION
            SELECT email FROM passenger WHERE email = ?");
        $stmt->bind_param("sss", $email, $email, $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $err = "Email address already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO passenger (name, email, phone_no, address, password, gender) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $email, $phone, $address, $hashed_password, $gender);
            if ($stmt->execute()) {
                // Fetch the new passenger to set session variables
                $stmt = $conn->prepare("SELECT * FROM passenger WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                
                $_SESSION['name'] = $user['name'];
                $_SESSION['gender'] = $user['gender'];
                $_SESSION['uid'] = $user['pass_id'];
                header("Location: passenger.php");
                exit();
            } else {
                $err = "Registration failed. Try again.";
            }
        }
        $stmt->close();
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
    <title>Passenger Registration | RICKSHAWHUB</title>
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
                <p class="hero-desc">Create your passenger account to start booking quick, affordable, and safe rides across town.</p>
            </div>
            
            <div class="hero-graphics">
                <img src="assets/header.png" alt="Auto Rickshaw Graphic">
            </div>

            <div class="hero-footer">
                <p>&copy; <?php echo date("Y"); ?> RikshawHub. All rights reserved.</p>
            </div>
        </div>

        <!-- Right Column: Registration Form -->
        <div class="auth-form-section">
            <h3 class="auth-view-title">Passenger <span>Registration</span></h3>

            <!-- Global Notifications -->
            <?php if (!empty($err)): ?>
                <div class="alert alert-error">
                    <i class="ri-error-warning-line"></i>
                    <span><?php echo $err; ?></span>
                </div>
            <?php endif; ?>

            <form method="post" action="PassReg.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <div class="input-with-icon">
                            <input type="text" placeholder="John Doe" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            <i class="ri-user-line"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-with-icon">
                            <input type="email" placeholder="john@example.com" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <i class="ri-mail-line"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <div class="input-with-icon">
                            <select name="gender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <div class="input-with-icon">
                            <input type="tel" placeholder="10-digit mobile number" name="phone" pattern="[0-9]{10}" title="Ten digit phone number" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            <i class="ri-phone-line"></i>
                        </div>
                    </div>

                    <div class="form-group form-group-full">
                        <label class="form-label">Address</label>
                        <div class="input-with-icon">
                            <input type="text" placeholder="Home address" name="address" required value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                            <i class="ri-home-4-line"></i>
                        </div>
                    </div>

                    <div class="form-group form-group-full">
                        <label class="form-label">Passwords</label>
                        <div class="password-row">
                            <div class="input-with-icon">
                                <input type="password" placeholder="Password" name="password" required>
                                <i class="ri-lock-line"></i>
                            </div>
                            <div class="input-with-icon">
                                <input type="password" placeholder="Confirm Password" name="confirm" required>
                                <i class="ri-lock-check-line"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 30px;">
                    <button type="submit" class="submit-btn">Register</button>
                    <a href="login.php" class="toggle-view-link">Already have an account? <strong>Login</strong></a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>