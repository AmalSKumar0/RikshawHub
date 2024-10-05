<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/favicon.ico" title="Favicon"/>
    <title>Document</title>
</head>
<?php
//connecting to database
   $conn = mysqli_connect("localhost", "root", "", "rikshawhub");
   if (!$conn) {
       die("Connection failed: " . mysqli_connect_error());
   }
   session_start();
   //both are same cause the user is viewing his won profile
   $_SESSION['whoEdit']=$_SESSION['whoami'];
   if ($_SESSION['whoami'] == 'pass') {//fetching data of the passenger for the view
    $stmt = $conn->prepare("SELECT * FROM passenger WHERE pass_id = ?");
    $stmt->bind_param("s", $_SESSION['uid']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $name = $user['name'];
    $email = $user['email'];
    $gender = $user['gender'];
    $address = $user['address'];
    $phone = $user['phone_no'];

} elseif ($_SESSION['whoami'] == 'driver') {//fetching data of the driver for the view
    $stmt = $conn->prepare("SELECT * FROM driver WHERE driver_id = ?");
    $stmt->bind_param("s", $_SESSION['did']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $name = $user['name'];
    $email = $user['email'];
    $gender = $user['gender'];
    $address = $user['address'];
    $phone = $user['phone_no'];
    $licence = $user['licence_no'];
    $vehicle = $user['vehicle_no'];
    $img= $user['Auto_img'];
    $date=$user['created_at'];
    $rating=$user['rating'];
}
if(isset($_GET['edit'])){//if the edit button is click will take uer to editing interface
  echo '<script>window.location.href="./admin/editPage.php";</script>';
}
if(isset($_GET['back'])){//if back button is clicked the user is taken back to the former page 
  if ($_SESSION['whoami'] == 'pass'){
    // session_destroy();
    echo '<script>window.location.href="passenger.php";</script>';
  }
  else{
    // session_destroy();
    echo '<script>window.location.href="driver.php";</script>';
  }
}
?>
<body>
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
<link rel="stylesheet" href="styles/profile.css">
<!-- the view for profile -->
<div class="card into">
    <div class="img-avatar">
    <img src="<?php if($_SESSION['whoami'] == 'driver'){?>uploads/<?php echo $img;} else{ echo 'images/profile.jpg';}?>" alt="">
    </div>
    <div class="card-text">
      <div class="portada">
      <img src="images/profile-bg.jpg"  style="width: 100%; height: 100%; border-top-left-radius: 20px; border-bottom-left-radius: 20px; object-fit: cover;">
      </div>
      <div class="title-total">   
        <div class="title"><?php if($_SESSION['whoami'] == 'driver'){ echo 'Driver';} else{ echo 'passenger';}?></div>
        <h2><?php echo strtoupper($name);?></h2></BR>
        <!-- soting deails for driver and passenger -->
         <div class="details">
            <li>Gender:<?php echo $gender;?></li>
            <li>Email:<?php echo $email;?></li>
            <li>Phone no:<?php echo $phone;?></li>
            <li>Address:<?php echo $address;?></li>
            <?php if($_SESSION['whoami'] == 'driver'){ ?>
              <!-- if the view is for passenger -->
              <li>Rating:<?php echo $rating;?></li>
            <li>Licence no:<?php echo $licence;?></li>
            <li>Vehicle no:<?php echo $vehicle;?></li>
            <li>Joined on:<?php echo $date;?></li>
            <?php } ?>
            <a class="reset-pass" href="admin/passwordReset.php">reset password</a>
         </div>
    <div class="actions">
      <!-- button for user interaction -->
      <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <button name="edit" value="true">EDIT</button></form><form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <button name="back" value="true">BACK</button></form>
      </div></div>
    </div>
  </div>
</body>
</html>

