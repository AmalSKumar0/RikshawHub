<!DOCTYPE html>
<html>
<head>
    <title>Driver Registration</title>
    <link rel="shortcut icon" href="images/favicon.ico" title="Favicon"/>
    <link rel="stylesheet" type="text/css" href="styles/LoginReg.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<?php
session_start();
 $err="";//error variable to show error in our input field
 //connecting to our database
$conn = mysqli_connect("localhost", "root", "", "rikshawhub");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//if the driver registered his details
//it will be stored in temporary database table for the admin to view and accept or reject
if (isset($_POST["Register"]) && $_POST["Register"] == "submit") {
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $gender = isset($_POST['gender']) ? htmlspecialchars(trim($_POST['gender'])) : '';
    $address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $licence = isset($_POST['licence']) ? htmlspecialchars(trim($_POST['licence'])) : '';
    $vehicle = isset($_POST['vehicle']) ? htmlspecialchars(trim($_POST['vehicle'])) : '';
    $auto_image = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $target_dir = "uploads/";//uploading our driver's pic to uploads file for future uses
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        
        if ($check !== false) {//check whather the file is image or not
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO temporarydriver (name, email, phone_no, address, vehicle_no, licence_no, password, gender, Auto_img, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("sssssssss", $name, $email, $phone, $address, $vehicle, $licence, $hashed_password, $gender, $auto_image);
                
                if ($stmt->execute()) {
                    echo "<script>alert('please wait till admin admit your account');</script>";
                } else {
                   $err="Error: " . $stmt->error . "";
                }
                $stmt->close();
            } else {
                $err= "Sorry, there was an error uploading your file.";
            }
        } else {
            $err= "File is not an image.";
    }
}

// if the user trying to login
if (isset($_POST["login"]) && $_POST["login"] == "submit") {
    $email = isset($_POST['logmail']) ? htmlspecialchars(trim($_POST['logmail'])) : '';
    $password = isset($_POST['logpass']) ? $_POST['logpass'] : '';
    
    $stmt = $conn->prepare("SELECT password FROM driver WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows == 0) {
        $err= "User not found";
    } else {
        $stmt->bind_result($db_password);
        $stmt->fetch();
        
        // Verify the hashed password
        if (password_verify($password, $db_password)) {
            $stmt = $conn->prepare("SELECT * FROM driver WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // $_SESSION['Dname'] = $user['name'];
            $_SESSION['did'] = $user['driver_id'];
            echo '<script>window.location.href="Driver.php";</script>';
        } else {
            $err="Incorrect password";
        }
        $stmt->close();
    }
}

mysqli_close($conn);
?>
<body>
<!-- animation for the view -->
<div class="area">
    <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>
<div class="wrapper">
    <div class="inner">
        <div class="image-holder">
            <img src="images/registration-form.jpg" alt="">
        </div>
        <a href="index.php" class="cross"></a>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" class="disable">
            <h3>Driver <span>Registration</span></h3>
            <div class="form-wrapper">
                <input type="text" placeholder="Name" class="form-control" name="name" required>
                <i class="zmdi zmdi-account"></i>
            </div>
            <div class="form-wrapper">
                <input type="email" placeholder="Email Address" class="form-control" name="email" required>
                <i class="zmdi zmdi-email"></i>
            </div>
            <div class="form-wrapper">
                <select required class="form-control" name="gender">
                    <option value="" disabled="" selected="">Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <i class="zmdi zmdi-caret-down" style="font-size: 17px"></i>
            </div>
            <div class="form-wrapper">
                <input type="text" required placeholder="Address" class="form-control" name="address">
                <i class="zmdi zmdi-home"></i>
            </div>
            <div class="form-wrapper">
                <input type="tel" required placeholder="Phone number" class="form-control" name="phone">
                <i class="zmdi zmdi-phone"></i>
            </div>
            <div class="form-wrapper">
                <input type="text" required placeholder="Licence number" class="form-control" name="licence">
                <i class="zmdi zmdi-card"></i>
            </div>
            <div class="form-wrapper">
                <input type="text" required placeholder="Vehicle number" class="form-control" name="vehicle">
                <i class="zmdi zmdi-car"></i>
            </div>
            <div class="form-wrapper">
                <label for="imageUpload">Image of the auto:</label>
                <input type="file" id="imageUpload" name="image" accept="image/*" required>
                <i class="zmdi zmdi-camera"></i>
            </div>
            <div class="form-wrapper">
                <input type="password" placeholder="Password" class="form-control" name="password" required>
                <input type="password" placeholder="Confirm Password" class="form-control" name="confirm" required>
                <i class="zmdi zmdi-lock"></i>
            </div>
            <h5 class="error"><?php echo $err; ?></h5>
            <div class="buttons">
                <button value="submit" name="Register">Register</button>
                <div class="logbtn">Login</div>
            </div>
        </form>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="login">
            <h3>Driver <span>Login</span></h3>
            <div class="form-wrapper">
                <input type="email" placeholder="Email Address" class="form-control" name="logmail" required>
                <i class="zmdi zmdi-email"></i>
            </div>
            <div class="form-wrapper">
                <input type="password" placeholder="Password" class="form-control" name="logpass" required>
                <i class="zmdi zmdi-lock"></i>
            </div>
            <h5 class="error"><?php echo $err; ?></h5>
            <div class="buttons">
                <button value="submit" name="login">Login</button>
                <div class="regbtn">Register</div>
            </div>
        </form>
    </div>
</div>
<script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script src='scripts/reg.js'></script>  
</body>
</html>
