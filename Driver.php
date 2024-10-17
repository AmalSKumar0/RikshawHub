<?php include 'header.php'; ?>
<link rel="stylesheet" href="styles/passenger.css">
<link rel="stylesheet" href="styles/displayCards.css">
<link rel="stylesheet" href="styles/DisplayBox.css">
<link rel="stylesheet" href="styles/driver.css">
<?php session_start();
// session for editing and viewing profile of the driver
$_SESSION['whoEdit'] = 'driver';
$_SESSION['whoami'] = 'driver';
?>
<?php
// connecting to database
$conn = mysqli_connect("localhost", "root", "", "rikshawhub");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//fetching details of the driver from driver table
$stmt = $conn->prepare("SELECT * FROM driver WHERE driver_id LIKE ?");
$stmt->bind_param("s", $_SESSION['did']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$_SESSION['name'] = $row['name'];
$_SESSION['img']=$row['Auto_img'];
//  if the driver is ready for the ride $flag=2
if ($row['is_active']) {
    $_SESSION['cloc'] = $row['current_location'];
    $flag = 2;
} else {
    $flag = 1;
} //flag 1 if he is offline
//if he enters the data and clicks the go live button
if (isset($_GET['live']) && $_GET['live'] == 'live') {
    $sql = "UPDATE driver SET is_active = 1, current_location = ? WHERE driver_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $_GET['cloc'], $_SESSION['did']);
    // adding current location to session for future use 
    $_SESSION['cloc'] = $_GET['cloc'];
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
    } //reloads the file after updating table}
}

//  if he wants to take rest or end his trip
// he will click offline button
// will log him out and the details like curertn loaction is removed
if ((isset($_GET['offline']) && $_GET['offline'] == 'true') || (isset($_GET['change']) && $_GET['change'] == 'true')) {
    $sql = "UPDATE driver SET is_active = 0, current_location = NULL WHERE driver_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['did']);
    if ($stmt->execute()) {
        if (isset($_GET['offline']) && $_GET['offline'] == 'true') {
            session_destroy(); //he logged out so no session
            echo "<script>alert('Thank you for your service today, have a great day');</script>";
            echo '<script>window.location.href="index.php";</script>';
        }
    } else {
        // failed to go offline
        header("Location: " . $_SERVER('PHP_SELF'));
    }
    $stmt->close();
}

//   if the driver accepts the users request
if (isset($_GET['accept'])) {
    $sql = "UPDATE bookings SET status = 'accepted' WHERE driver_id = ? and pass_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $_SESSION['did'], $_GET['accept']);
    if ($stmt->execute()) {
        //passWaitFlag is for knowing that driver has accepted the request for a view 
        $_SESSION['passWaitFlag'] = 1;
        //refreshing page to avoid duplicating the same data in bookings table
        header($_SERVER['PHP_SELF']);
    } else {
        //bookings table didnt get updated
        header($_SERVER['PHP_SELF']);
    }
    $stmt->close();
}
if (isset($_GET['cancel'])) {
    $sql = "UPDATE bookings SET status = 'canceled' WHERE driver_id = ? and pass_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $_SESSION['did'], $_GET['cancel']);
    if ($stmt->execute()) {
        header($_SERVER['PHP_SELF']);
    } else {
        //add a error message
    }
    $stmt->close();
}

?>
<nav>
    <div class="nav__header">
        <div class="nav__logo">
            <a href="#">RICKSHAW<span>HUB</span>.</a>
        </div>
        <div class="nav__menu__btn" id="menu-btn">
            <span><i class="ri-menu-line"></i></span>
        </div>
    </div>
    <ul class="nav__links" id="nav-links">
        <!-- <li><a href="#">Destination</a></li> -->
        <li><a href="index.php">Home</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#" id="testimoniesButton">Reviews</a></li>
        <li><a href="contact.php" id="aboutusButton">Contact us</a></li>
    </ul>
    <div class="admin"><a href="profile.php">
         <div class="circle"><img class="pfp-auto" src="uploads/<?php echo $_SESSION['img']; ?>" alt="profile pic"></div>
            <span><?php echo " " . $_SESSION['name']; ?></span> </a>
    </div>
</nav>

<div class="booking-section">
    <!-- booking section -->
    <h1 class="TagLine">BE THE <span class='ride'>DIFFERENCE</span> ON EVERY ROAD</h1>
    <div class="cardCont <?php if ($flag != 1) {
                                echo 'display';
                            } ?>">
        <div class="searchcard">
            <svg class="line" xmlns="http://www.w3.org/2000/svg">
                <path d="M50,0 L50,300" stroke="orange" stroke-width="2" fill="none" stroke-dasharray="5,5" />
            </svg>
            <div class="searchcard-content">
                <h2 class="searchcard-title">LET EVERY PASSENGER KNOW—YOU'RE READY TO ROLL! </h2>
                <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="fromTo">
                        <div class="form-group">
                            <label for="from">Current location:</label>
                            <input type="text" name="cloc" id="autocomplete" placeholder="Current location" required>
                            <div id="suggestions"></div>
                            <div id="from-error" class="error-message"></div> <!-- Error Message -->
                        </div>
                        <div class="find-button">
                            <button value="live" name="live" class="search">GO LIVE!</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
// Check if $flag == 2 and session variable passWaitFlag is set
if ($flag == 2 && isset($_SESSION['passWaitFlag'])) {
    // Retrieve the booking and passenger details
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE driver_id = ?");
    $stmt->bind_param("i", $_SESSION['did']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $_SESSION['price'] = $row['price'];

    $stmt = $conn->prepare("SELECT name,phone_no FROM passenger WHERE pass_id = ?");
    $stmt->bind_param("i", $row['pass_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $_SESSION['otp'] = $row['OTP'];
?>
    <div class="cardCont">
        <div class="searchcard confirmcard">
            <svg class="line" xmlns="http://www.w3.org/2000/svg">
                <path d="M50,0 L50,300" stroke="orange" stroke-width="2" fill="none" stroke-dasharray="5,5" />
            </svg>
            <div class="searchcard-content">
                <h2 class="searchcard-title">Your passenger is <?php echo $user['name']; ?></h2>
                <p class="confirm-details">Phone no:<?php echo $user['phone_no']; ?></p>
                <p class="confirm-details">Their landmark is <?php echo $row['landmark']; ?></p>
                <p class="confirm-details">The trip is from <?php
                                                            $from = explode(' ', $row['from']);
                                                            $to = explode(' ', $row['to']);
                                                            echo $from[0] . ' to ' . $to[0];
                                                            ?>
                </p>
                <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="fromTo">
                        <div class="form-group">
                            <label for="from">Enter the 6-digit OTP:</label>
                            <input type="number" name="otp" id="from" placeholder="OTP FOR CONFIRMATION" required>
                        </div>
                        <div class="find-button">
                            <button value="true" name="completed" style="width: 170px;" class="search">COMPLETE
                                TRIP</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    // Handle form submission for OTP verification
    if (isset($_GET['completed'])) {
        if ($_GET['otp'] == $_SESSION['otp']) {

            $sql = "UPDATE bookings 
SET status = 'completed' 
WHERE driver_id = ? AND status = 'accepted'";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $_SESSION['did']);

            if ($stmt->execute()) {
                include 'snippets/thankyou.php';
            } else {
                // Log or display error message for debugging
                error_log("Update failed: " . $stmt->error);
                echo "<script>window.location.reload();</script>"; // Redirect to error page
                exit;
            }

            $stmt->close();
        } else {
            echo "<script>alert('Invalid OTP');</script>";
            echo "<script>window.location.reload();</script>";
            exit;
        }
    }
    if (isset($_GET['goBack'])) {
        unset($_SESSION['passWaitFlag']);
        // Redirect to avoid form resubmission or refresh
        echo '<script>window.location.href="Driver.php";</script>';
        exit;
    }
}

// If $flag == 2 and session variable passWaitFlag is not set
if ($flag == 2 && !isset($_SESSION['passWaitFlag'])) {
    ?>
    <div class="onlive">
        <form method="get" style="display:inline;" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <button value="true" name="change" class="offline search"><span>CHANGE LOCATION</span></button>
            <button value="true" name="offline" class="offline search">GO OFFLINE</button>
        </form>
    </div>
    </div>
    <h3 class="request-title">PASSENGERS REQUESTED YOUR AUTO</h3>
    <div class="card-container">
        <?php
        $stmt = $conn->prepare("SELECT * FROM bookings WHERE driver_id = ? AND status='requested';");
        $stmt->bind_param("i", $_SESSION['did']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
                <div class="card">
                    <div class="card-content">
                        <h3>TRIP from:<?php
                                        $from = explode(' ', $row['from']);
                                        $to = explode(' ', $row['to']);
                                        echo $from[0] . ' to ' . $to[0];
                                        ?>
                        </h3>
                        <p>Price: Rs<?php echo $row['price']; ?></p>
                        <p>Distance:<?php echo $row['distance']; ?> KM</p>
                        <div class="button-group">
                            <form method="get" style="display:inline;"
                                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <button name="cancel" value='<?php echo $row["pass_id"]; ?>'>Cancel</button>
                                <button name="accept" value='<?php echo $row["pass_id"]; ?>'>Accept</button>
                            </form>
                        </div>
                    </div>
                </div>
    <?php }
        } else {
            echo "No bookings available in " . $_SESSION['cloc'];
        }
        $stmt->close();
    }
    ?>
   
    <script src="scripts/passenger.js"></script>
    <?php include 'footer.php'; ?>