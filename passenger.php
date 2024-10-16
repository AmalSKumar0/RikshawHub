<?php session_start(); ?>
<?php include 'header.php'; ?>
<link rel="stylesheet" href="styles/passenger.css">
<link rel="stylesheet" href="styles/displayCards.css">
<link rel="stylesheet" href="styles/DisplayBox.css">


<!-- All helper functions -->
<?php include 'phpFormSubmit/passHelperFunctions.php'; ?>

<?php
$conn = mysqli_connect("localhost", "root", "", "rikshawhub");
//connecting to database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//declaring session to edit and view the profile of the passenger
$_SESSION['whoEdit'] = 'pass';
$_SESSION['whoami'] = 'pass';

// getching booking data from bookings table 
$stmt = $conn->prepare("SELECT * FROM bookings WHERE pass_id LIKE ?");
$stmt->bind_param("s", $_SESSION['uid']);
$stmt->execute();
$result = $stmt->get_result();
// if the booking exists flag=3 and user view is created
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['did'] = $row['driver_id'];
    $_SESSION['from'] = $row['from'];
    $_SESSION['to'] = $row['to'];
    $flag = 3;
} else {
    $flag = 1;
} //if there is no data of user on bookings table, user is viewed the booking form
if (isset($_GET['search']) && $_GET['search'] == 'search' || isset($_SESSION['UserView'])) {
    unset($_SESSION['UserView']);
    $flag = 2;
}
if (isset($_GET['another']) && $_GET['another'] == 'another') $flag = 1;
?>

<!-- All form submission -->
 <?php include 'phpFormSubmit/passForm.php'; ?>

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
        <img class="pfp" src="images/<?php if($_SESSION['gender']=='Male') echo 'man'; else echo 'woman'; ?>.png" alt="profile pic">
            <span><?php echo " " . $_SESSION['name']; ?></span> </a>
    </div>
</nav>
<div class="booking-section into">
    <!-- views based on the flags we mensioned before -->
    <h1 class="TagLine">
        <?php if ($flag == 2) {
            if (isset($_GET['from']) && isset($_GET['to']) && isset($_GET['landmark'])) {
                $_SESSION['from'] = $_GET['from'];
                $_SESSION['to'] = $_GET['to'];
                $_SESSION['landmark'] = $_GET['landmark'];
                $distance = getDistanceBetweenPlaces($_SESSION['from'], $_SESSION['to']);
                $_SESSION['distance'] = $distance;
                $_SESSION['price'] = calculateTripPrice($distance);
            }
            echo "RICKSHAWS NEAR<span class='ride'> " . explode(',', $_SESSION['from'])[0] . "</span>";
        } else if ($flag == 1) {
            echo "Ready, Set,<span class='ride'> Ride!</span>";
        } else if ($flag == 3) {
            echo "YOUR <span class='ride'> RIKSHAW </span> is on the way";
        } ?>
    </h1>
    <?php if ($flag == 2) {
        echo "<form class='anotherbtn' method='get' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'><button class='offline' name='another' value='another'>SEARCH ANOTHER PLACE </button></form>";
    } ?>
    <div class="cardCont into <?php if ($flag != 1) echo "display"; ?>">
        <div class="searchcard">
            <div class="searchcard-image">
                <img src="images/registration-form-12.jpg" alt="">
            </div>
            <svg class="line" xmlns="http://www.w3.org/2000/svg">
                <path d="M50,0 L50,300" stroke="orange" stroke-width="2" fill="none" stroke-dasharray="5,5" />
            </svg>
            <div class="searchcard-content special-card">
                <h2 class="searchcard-title">BOOK YOUR RICKSHAW </h2>
                <!-- form for booking where data enter from,to and landmark for his trip -->
                <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="fromTo">
                        <div class="form-group">
                            <label for="from">From</label>
                            <input type="text" name="from" id="autocomplete" placeholder="Current location" required>
                            <div id="suggestions"></div>
                            <div id="from-error" class="error-message"></div> <!-- Error Message -->
                        </div>

                        <!-- To Field -->
                        <div class="form-group">
                            <label for="to">To</label>
                            <input type="text" name="to" id="autocomplete2" placeholder="Select location" required>
                            <div id="suggestions2"></div>
                            <div id="to-error" class="error-message"></div> <!-- Error Message -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="landmark">Landmark</label>
                        <input type="text" name="landmark" id="landmark" placeholder="Enter landmark" required>
                    </div><br>
                    <div class="find-button">
                        <!-- search button will search the availabale rikshaw near the user -->
                        <button value="search" name="search" class="search">SEARCH</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php if ($flag == 3) {
    ?>
    <div class="bookwindow cardCont">
        <div class="searchcard">
            <?php
                // Fetching details of the driver that the passenger booked
                $stmt = $conn->prepare("SELECT * FROM driver WHERE driver_id = ?");
                $stmt->bind_param("i", $_SESSION['did']);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $_SESSION["review_rating"] = $row['rating'];
                ?>
            <div class="dia into">
                <img class="imageView" src="uploads/<?php echo htmlspecialchars($row['Auto_img']); ?>" alt="">
            </div>
            <svg class="line" xmlns="http://www.w3.org/2000/svg">
                <path d="M50,0 L50,300" stroke="orange" stroke-width="2" fill="none" stroke-dasharray="5,5" />
            </svg>
            <div class="searchcard-content passvie">
                <div class='trip-info'>
                    <h2 style="color: #ff833e;">
                        TRIP: FROM
                        <?php echo explode(' ', strtoupper($_SESSION['from']))[0]; ?>
                        TO
                        <?php echo explode(' ', strtoupper($_SESSION['to']))[0]; ?>
                    </h2>
                </div>
                <div class='driver-details'>
                    <h4>Driver Details</h4>
                    <p><?php echo htmlspecialchars($row['name']); ?></p>
                    <p>Address: <?php echo htmlspecialchars($row['address']); ?></p>
                    <p>Gender: <?php echo htmlspecialchars($row['gender']); ?></p>
                    <p>Phone No: <?php echo htmlspecialchars($row['phone_no']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($row['email']); ?></p>
                    <p>Vehicle No: <span class="vno"><?php echo htmlspecialchars($row['vehicle_no']); ?></span></p>

                    <?php
                        // 4 views on the basis of the status of the booking
                        $stmt = $conn->prepare("SELECT * FROM bookings WHERE driver_id = ?");
                        $stmt->bind_param("i", $row['driver_id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $Bookingdata = $result->fetch_assoc();

                        if ($Bookingdata['status'] == 'requested') { // if the rickshaw is requested only
                            echo "<div class='status-message'>Rickshaw is requested</div>";
                        } elseif ($Bookingdata['status'] == 'accepted') { // if the driver accepted the passenger's request
                            echo "<div class='status-message'>Rickshaw is on its way</div>";
                            $otp = generateOTP();
                            $sql = "UPDATE bookings SET otp = ? WHERE pass_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("si", $otp, $_SESSION['uid']);
                            $stmt->execute();
                            echo "<div class='otp-message'>Once you reach the destination, tell this OTP to the driver:</div>";
                            echo "<div class='highlighted-otp'>" . htmlspecialchars($otp) . "</div>";
                        } elseif ($Bookingdata['status'] == 'canceled') { // if the booking is rejected by the driver
                            echo "<div class='status-message' style='color: #dc3545;'>Rickshaw is canceled</div>";
                        ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method='get'>
                        <button class="confirm" name='confirm' value="false">Confirm</button>
                    </form>
                    <?php
                        } elseif ($Bookingdata['status'] == 'completed') { // if the trip is completed and the OTP matches the driver's OTP
                            echo "<div class='status-message' style='color: #28a745;'>Passenger reached destination</div>";
                        ?>
                    <form style="display:flex;flex-direction:row;" class="button-review"
                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method='get'>
                        <button class="search pass-confirm-button" name='review' value="">Review</button>
                        <button class="search pass-confirm-button" name='confirm' value="true">Confirm</button>
                    </form>
                    <?php
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<div class="card-container into">
    <?php


    if ($flag == 2) {
        $stmt = $conn->prepare("SELECT * FROM driver WHERE current_location LIKE ? AND driver_id NOT IN (SELECT driver_id FROM bookings WHERE status = 'requested')");
        if (isset($_GET['from'])) $from = $_GET['from'];
        else $from = $_SESSION['from'];
        $searchTerm = '%' . $from . '%';
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
    <div class="card">
        <div class="card-image">
            <!-- image of the auto available to the passenger -->
            <img src="uploads/<?php echo $row['Auto_img']; ?>" alt="<?php echo $row['name']; ?>">
        </div>
        <div class="card-content">
            <h2><?php echo $row['name']; ?></h2>
            <div class="section">
                <p>Phone No: <?php echo $row['phone_no']; ?></p>
            </div>
            <p>Email: <?php echo $row['email']; ?></p>
            <p>Vehicle No: <span class="vno"><?php echo $row['vehicle_no']; ?></span></p>
            <div class="button-group book-btn">
                <form method='get' style='display:inline;'
                    action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <button name="View" value='<?php echo $row["driver_id"]; ?>'>View</button>
                    <button name="payment" value='<?php echo $row["driver_id"]; ?>'>Book</button>
                </form>
            </div>
        </div>
    </div>
    <?php }
        } else {
            echo "<div class='no-results'>No rickshaws available in your area. Please try searching a different location or try again later.</div>";
        }
    }
    ?>

</div>
</div>

<script src="scripts/passenger.js"></script>
<?php include 'footer.php'; ?>