<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="images/favicon.ico" title="Favicon"/>
	<title>RICKSHAWHUB</title>
	<link rel="stylesheet" type="text/css" href="styles/LoginReg.css">
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <!-- background animation -->
<div class="area" >
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
</div >
<!-- background aniamtion ends here -->
<?php
 $err="";
$conn = mysqli_connect("localhost", "root", "", "rikshawhub");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start();
// if the register button is clicked the user data is stored in database passenger
if (isset($_POST["Register"]) && $_POST["Register"] == "submit") {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm']) ? $_POST['confirm'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    if($confirm_password != $password) {//if password doesnt match
        echo "<p>Password does not match</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO passenger (name, email, phone_no, address, password, gender) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $address, $password, $gender);
        if ($stmt->execute()) {
            $_SESSION['name'] = $name;
            $stmt = $conn->prepare("SELECT pass_id FROM passenger WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            // user is registered in and a passenger session is started with his pass_id
            $_SESSION['uid'] = $user['pass_id'];
            echo '<script>window.location.href="passenger.php";</script>';
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}
//login button is pressed this will check the user exist or not 
//cross checks the password as well
if (isset($_POST["login"]) && $_POST["login"] == "submit") {
    $email = $_POST['logmail'];
    $password = $_POST['logpass'];
    $stmt = $conn->prepare("SELECT password FROM passenger WHERE email like ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows == 0) {
       $err="User not found";//user not found
    } else {
        $stmt->bind_result($db_password);
        $stmt->fetch();
        //cross checking passwords
        if ($db_password == $password) {
            $stmt = $conn->prepare("SELECT * FROM passenger WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            //user is logged in and session variables are declared
            $_SESSION['name'] = $user['name'];
            $_SESSION['uid'] = $user['pass_id'];
            echo '<script>window.location.href="passenger.php";</script>';
        } else {
            $err="Incorrect password";
        }
        $stmt->close();
    }
}

mysqli_close($conn);
?>
	<body>
        <div class="wrapper" >
        <div class="inner">
        <div class="image-holder">
        <img src="images/registration-form.jpg" alt="">
        </div>
        <a href="index.php" class="cross"></a>  
        <!-- user registraion form -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="disable">
        <h3>Passenger <span>Registration</span></h3>
        <h5 class="error"><?php echo $err; ?></h5>
        <div class="form-wrapper">
        <input type="text" placeholder="Name" required name="name" class="form-control" fdprocessedid="k0kcxa">
        <i class="zmdi zmdi-account"></i>
        </div>
        <div class="form-wrapper">
        <input type="email" required placeholder="Email Address" name="email" class="form-control" fdprocessedid="8zam0p">
        <i class="zmdi zmdi-email"></i>
        </div>
        <div class="form-wrapper">
        <select name="gender" required id="" class="form-control" fdprocessedid="yn864i">
        <option value="" disabled="" selected="">Gender</option>
        <option value="male">Male</option>
        <option value="femal">Female</option>
        <option value="other">Other</option>
        </select>
        <i class="zmdi zmdi-caret-down" style="font-size: 17px"></i>
        </div>
        <div class="form-wrapper">
          <input type="text" required placeholder="Address" name="address" class="form-control">
          <i class="zmdi zmdi-lock"></i>
          </div>
          <div class="form-wrapper">
            <input type="tel" required placeholder="Phone number" name="phone" class="form-control" fdprocessedid="kci4k">
            <i class="zmdi zmdi-lock"></i>
            </div>
              <div class="form-wrapper">
                <input type="password" placeholder="Password" class="form-control" fdprocessedid="kci4k" name="password" required>
                <input type="password" placeholder="Confirm Password" class="form-control" fdprocessedid="fbisd" name="confirm" required>
                <i class="zmdi zmdi-lock"></i>
                </div>
                <div class="buttons">
        <button fdprocessedid="lfyluc" value="submit" name="Register">Register
        </button>
        <div class="logbtn">Login</div></div>
        </form>
        <!-- user login form -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="login">
            <h3 >Passenger <span>Login</span></h3>
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
            <button fdprocessedid="lfyluc" value="submit" name="login">Login
            </button><div class="regbtn">Register</div>
            </div>
            </form>
        </div>
        </div>
        <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
    <script src='scripts/reg.js'></script>  
    </body>
</body>
</html>