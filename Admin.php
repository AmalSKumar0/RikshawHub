<?php include 'header.php'; ?>
<link rel="stylesheet" href="styles/displayCards.css">

<!-- <link rel="stylesheet" href="styles/profile.css"> -->
<?php           //session starting for setting flags for selecting the page to be loade
   session_start();
   if(isset($_SESSION['adminFlag'])) {$flag=$_SESSION['adminFlag'];}
   else {$flag=1;}
   if(isset($_GET['newDrivers']) && $_GET['newDrivers']=='true') {
    $_SESSION['adminFlag']=1;
    header("Location: " . $_SERVER['PHP_SELF']);
  } elseif(isset($_GET['allPassengers']) && $_GET['allPassengers']=='true') {
    $_SESSION['adminFlag']=2;
    header("Location: " . $_SERVER['PHP_SELF']);
  } elseif(isset($_GET['allDrivers']) && $_GET['allDrivers']=='true') {
    $_SESSION['adminFlag']=3;
    header("Location: " . $_SERVER['PHP_SELF']);
  } elseif(isset($_GET['bookings']) && $_GET['bookings']=='true') {
    $_SESSION['adminFlag']=4;
    header("Location: " . $_SERVER['PHP_SELF']);
  }
  
?>
<!-- header -->
<nav>
      <div class="nav__header">
        <div class="nav__logo">
          <a href="#">RICKSHAW<span>HUB</span>.</a>
        </div>
        <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="nav__menu__btn" id="menu-btn">
          <span><i class="ri-menu-line"></i></span>
        </div>
      </div>
      <ul class="nav__links" id="nav-links">
        <!-- <li><a href="#">Destination</a></li> -->
  <li><a href="index.php">Home</a></li>
  <li><a><button name="newDrivers" value="true" <?php if($flag==1) echo "style='color:#ff833e;'"; ?> onclick="window.location.href='?newDrivers=true'">New Drivers</button></a></li>
  <li><a><button name="allPassengers" value="true" <?php if($flag==2) echo "style='color:#ff833e;'"; ?> onclick="window.location.href='?allPassengers=true'">All Passengers</button></a></li>
  <li><a><button name="allDrivers" value="true" <?php if($flag==3) echo "style='color:#ff833e;'"; ?> onclick="window.location.href='?allDrivers=true'">All Drivers</button></a></li>
  <li><a><button name="bookings" value="true" <?php if($flag==4) echo "style='color:#ff833e;'"; ?> onclick="window.location.href='?bookings=true'">Bookings</button></a></li>
</ul>
    </form>
      <div class="admin">
     Welcome<span><?php  echo " ".$_SESSION['admin'];?></span> 
      </div>
    </nav>
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
    <?php
    switch($flag){
          case 1:include 'admin/newDrivers.php';break;//New Driver accepting rejecting page
          case 2:include 'admin/allPassengers.php';break;// all passenger view delete update
          case 3:include 'admin/allDrivers.php';break;// all driver view delete update
          case 4:include 'admin/bookings.php';break;//all bookings view and delete
    }?>
   <!-- footer -->
<?php include 'footer.php'; ?>
