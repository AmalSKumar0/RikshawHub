<?php include 'header.php'; ?>
<link rel="stylesheet" href="styles/passenger.css">
<link rel="stylesheet" href="styles/displayCards.css">
<link rel="stylesheet" href="styles/DisplayBox.css">


<?php
// Simple file-based caching mechanism
function getCache($key) {
    $cacheFile = sys_get_temp_dir() . '/cache_' . md5($key) . '.json';
    if (file_exists($cacheFile)) {
        $data = file_get_contents($cacheFile);
        return json_decode($data, true);
    }
    return false;
}

function setCache($key, $data) {
    $cacheFile = sys_get_temp_dir() . '/cache_' . md5($key) . '.json';
    file_put_contents($cacheFile, json_encode($data));
}

function getDistanceBetweenPlaces($place1, $place2) {
    $apiKey = '7a3a0c1b40264ee896db8d29b4f3c388';

    // Function to get coordinates using cURL Multi for parallel requests
    function getCoordinates($places, $apiKey) {
        $results = [];
        $mh = curl_multi_init();
        $curlHandles = [];

        foreach ($places as $key => $place) {
            // Check cache first
            $cached = getCache($place);
            if ($cached) {
                $results[$key] = $cached;
                continue;
            }

            $url = "https://api.opencagedata.com/geocode/v1/json?q=" . urlencode($place) . "&key=" . $apiKey . "&limit=1";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Set timeout to 5 seconds
            curl_multi_add_handle($mh, $ch);
            $curlHandles[$key] = $ch;
        }

        // Execute all queries simultaneously
        $running = null;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        // Retrieve the results
        foreach ($curlHandles as $key => $ch) {
            $response = curl_multi_getcontent($ch);
            $data = json_decode($response, true);
            if ($data && isset($data['results'][0]['geometry'])) {
                $coord = [
                    'lat' => $data['results'][0]['geometry']['lat'],
                    'lng' => $data['results'][0]['geometry']['lng']
                ];
                $results[$key] = $coord;
                setCache($places[$key], $coord); // Cache the result
            } else {
                $results[$key] = null; // Indicate failure
            }
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }

        curl_multi_close($mh);
        return $results;
    }

    // Function to calculate distance using Haversine formula
    function haversineDistance($coord1, $coord2) {
        $earthRadius = 6371; // Radius of the Earth in kilometers

        $latFrom = deg2rad($coord1['lat']);
        $lonFrom = deg2rad($coord1['lng']);
        $latTo = deg2rad($coord2['lat']);
        $lonTo = deg2rad($coord2['lng']);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in kilometers
    }

    // Get coordinates for both places in parallel
    $places = ['place1' => $place1, 'place2' => $place2];
    $coords = getCoordinates($places, $apiKey);

    // Check if both places were found
    if ($coords['place1'] && $coords['place2']) {
        // Calculate distance
        $distance = haversineDistance($coords['place1'], $coords['place2']);
        return number_format($distance, 2);
    } else {
        return 0;
    }
}

?>


<?php
function calculateTripPrice($distance) {
    // Ensure distance is numeric
    if (!is_numeric($distance)) {
        return "Invalid input: Distance must be a numeric value.";
    }

    // Convert distance to a float just in case it was passed as a string
    $distance = floatval($distance);

    // Pricing rules
    $baseFare = 30; // Minimum charge for up to 1.5 km
    $baseDistance = 1.5; // Base distance in km
    $ratePerKm = 15; // Charge per additional km
    $commissionRate = 0.10; // Commission rate (10%)

    // Calculate total fare based on distance
    if ($distance <= $baseDistance) {
        $fare = $baseFare; // Minimum fare for distances up to 1.5 km
    } else {
        $additionalDistance = $distance - $baseDistance;
        $fare = $baseFare + ($additionalDistance * $ratePerKm);
    }

    // Add commission to the fare
    $commission = $fare * $commissionRate;
    $totalPrice = $fare + $commission;

    // Round the total price to the nearest whole number
    $totalPrice = round($totalPrice);

    return $totalPrice; // Return the rounded total price
}

?>



<?php
$conn = mysqli_connect("localhost", "root", "", "rikshawhub");
//connecting to database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start();
//declaring session to edit and view the profile of the passenger
$_SESSION['whoEdit']='pass';
$_SESSION['whoami']='pass';
// getching booking data from bookings table 
$stmt = $conn->prepare("SELECT * FROM bookings WHERE pass_id LIKE ?");
$stmt->bind_param("s", $_SESSION['uid']);
$stmt->execute();
$result = $stmt->get_result();
// if the booking exists flag=3 and user view is created
if($result->num_rows > 0){
  $row = $result->fetch_assoc();
  $_SESSION['did']=$row['driver_id'];
  $_SESSION['from']=$row['from'];
  $_SESSION['to']=$row['to'];
  $flag=3;
}
else{$flag=1;}//if there is no data of user on bookings table, user is viewed the booking form
if(isset($_GET['search']) && $_GET['search']=='search' ) $flag=2;
if(isset($_GET['another']) && $_GET['another']=='another') $flag=1;

//otp generator
function generateOTP($length = 6) {
  $otp = '';
  for ($i = 0; $i < $length; $i++) {
      $otp .= mt_rand(0, 9);
  }
  return $otp;
}
// If confirmed the passenger reached destination
if (isset($_GET['confirm'])) {
    // Set the status based on confirmation
    $status = ($_GET['confirm'] == 'false') ? 'cancel' : 'complete';
    
    // First, retrieve the booking details you want to insert into the new table
    $sql = "SELECT * FROM bookings WHERE pass_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['uid']);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();

        // Now, insert the booking details into the `completed_bookings` table
        $insert_sql = "INSERT INTO completed_bookings (pass_id, driver_id, status, completion_time) 
                       VALUES (?, ?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iis", $booking['pass_id'], $booking['driver_id'], $status);
        
        if ($insert_stmt->execute()) {
            // After successful insertion, delete the booking from the `bookings` table
            $delete_sql = "DELETE FROM bookings WHERE pass_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $_SESSION['uid']);
            $delete_stmt->execute();
            
            // Redirect to the same page after completion
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
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
        <li><a href="#" id="aboutusButton">Contact us</a></li>
    </ul>
    <div class="admin"><a href="profile.php">
            <span><?php  echo " ".$_SESSION['name'];?></span> </a>
    </div>
</nav>
<div class="booking-section into">
    <!-- views based on the flags we mensioned before -->
    <h1 class="TagLine">
        <?php if($flag==2){
            if (isset($_GET['from']) && isset($_GET['to']) && isset($_GET['landmark'])) {
                $_SESSION['from'] = $_GET['from'];
                $_SESSION['to'] = $_GET['to'];
                $_SESSION['landmark'] = $_GET['landmark'];
                $distance = getDistanceBetweenPlaces($_SESSION['from'], $_SESSION['to']);
                $_SESSION['distance'] = $distance;
                $_SESSION['price']=calculateTripPrice($distance);
            }
            echo "RICKSHAWS NEAR<span class='ride'> ".explode(',', $_SESSION['from'])[0]."</span>";}  else if($flag==1) {echo "Ready, Set,<span class='ride'> Ride!</span>";}else if($flag==3){echo "YOUR <span class='ride'> RIKSHAW </span> is on the way" ;} ?>
    </h1>
    <?php if($flag==2){echo "<form class='anotherbtn' method='get' action='".htmlspecialchars($_SERVER['PHP_SELF'])."'><button class='offline' name='another' value='another'>SEARCH ANOTHER PLACE </button></form>";}?>
    <div class="cardCont into <?php if($flag!=1) echo "display"; ?>">
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
    // if (isset($_GET['Book'])) { // if the user books a particular driver
    //     $_SESSION['did'] = $_GET['Book'];
    // }
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
            $_SESSION["review_rating"]=$row['rating'];
            ?>
            <div class="dia into">
                <img class="imageView" src="uploads/<?php echo htmlspecialchars($row['Auto_img']); ?>" alt="">
            </div>
            <svg class="line" xmlns="http://www.w3.org/2000/svg">
                <path d="M50,0 L50,300" stroke="orange" stroke-width="2" fill="none" stroke-dasharray="5,5" />
            </svg>
            <div class="searchcard-content passvie">
                <div class='trip-info'>
                <h2 style="color: #ff833e;" >
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
                    <form style="display:flex;flex-direction:row;" class="button-review" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method='get'>
                    <button class="search pass-confirm-button" name='review' value="">Review</button>
                        <button class="search pass-confirm-button" name='confirm' value="true">Confirm</button>
                    </form>
                    
                       
                         <?php if(isset($_GET['review'])) include 'snippets/review.php'; 
                         if (isset($_GET['submitReview'])) {
                             // Get the form data
                             $rating = $_GET['feedback'];         // The rating (stars)
                             $text = $_GET['review_text'];      // The review text
                         
                             // Example pass_id and driver_id (you can retrieve these from session or form)
                             $pass_id = $_SESSION['uid'];       // Assuming these are passed in the form
                             $driver_id = $_SESSION['did'];   // Or set them from session data like $_SESSION['driver_id']
                             // Prepare the SQL insert query
                             $updated_rating=0;
                             if($_SESSION["review_rating"]==0){$updated_rating=$rating;}
                             else {$updated_rating=($_SESSION["review_rating"]+$rating)/2;$_SESSION["review_rating"]=$updated_rating;}
                             $stmt = $conn->prepare("UPDATE  driver set rating=? where driver_id=?");
                             $stmt->bind_param("ii", $updated_rating, $driver_id); 
                             $stmt->execute();

                             $sql = "INSERT INTO reviews (pass_id, driver_id, review_text, created_date, stars) 
                                     VALUES (?, ?, ?, NOW(), ?)";
                         
                             // Prepare the statement
                             if ($stmt = $conn->prepare($sql)) {
                                 // Bind the parameters
                                 $stmt->bind_param("iisi", $pass_id, $driver_id, $text, $rating); 
                         
                                 // Execute the statement
                                 if ($stmt->execute()) {
                                     echo '<script>window.location.href="passenger.php";</script>';
                                 } else {
                                     echo '<script>consol.log("Error? '.$pass_id.' '.$driver_id.'");</script>';
                                     echo "Error: " . $stmt->error;
                                 }
                         
                                 // Close the statement
                                 $stmt->close();
                             } else {
                                 echo "Error preparing the query: " . $mysqli->error;
                             }
                         }
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


if ($flag==2) {
  
  $stmt = $conn->prepare("SELECT * FROM driver WHERE current_location LIKE ? AND driver_id NOT IN (SELECT driver_id FROM bookings WHERE status = 'requested')");
  $searchTerm = '%' . $_GET['from'] . '%';
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

if(isset($_GET['payment'])){
    $_SESSION['driverid']=$_GET['payment'];
    include 'snippets/paymentWindow.php';
}

if (isset($_GET['Book'])) {//passenger booked a particular auto which they find intresting
    
    $from = $_SESSION['from'];
    $to = $_SESSION['to'];
    $landmark = $_SESSION['landmark'];
    $_SESSION['did']=$_GET['Book'];
    $status = "requested";
    $price = $_SESSION['price'];
    $distance = $_SESSION['distance'];
    $stmt = $conn->prepare("INSERT INTO bookings (pass_id, driver_id, `from`, `to`, landmark, status,price,distance) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissssii", $_SESSION['uid'], $_GET['Book'], $from, $to, $landmark, $status, $price ,$distance);

    if ($stmt->execute()) {
         header("Location: " . $_SERVER['PHP_SELF']);
        // echo '<script>alert("Booked successfully");</script>';
    } else {
        echo '<script>alert("Booking failed");</script>';
    }
} 
$conn->close();
?>

</div>

</div>
<style>
#suggestions,
#suggestions2 {
    border: 1px solid #ddd;
    max-width: 300px;
    margin-top: 5px;
    position: absolute;
    background-color: white;
    z-index: 1000;
}

.suggestion-item {
    padding: 5px;
    cursor: pointer;
}

.suggestion-item:hover {
    background-color: #eee;
}
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let debounceTimeout1, debounceTimeout2;
        const cache = {}; // Cache object to store previous API responses
        let validSelections = { from: false, to: false }; // To track valid selections

        // Autocomplete for the first input (From)
        document.getElementById('autocomplete').addEventListener('input', function () {
            const query = this.value.trim();
            clearTimeout(debounceTimeout1);
            if (query) {
                debounceTimeout1 = setTimeout(() => fetchSuggestions(query, 'suggestions', 'from'), 300);
            } else {
                document.getElementById('suggestions').innerHTML = '';
                document.getElementById('from-error').textContent = '';
                validSelections.from = false;
            }
        });

        // Autocomplete for the second input (To)
        document.getElementById('autocomplete2').addEventListener('input', function () {
            const query = this.value.trim();
            clearTimeout(debounceTimeout2);
            if (query) {
                debounceTimeout2 = setTimeout(() => fetchSuggestions(query, 'suggestions2', 'to'), 300);
            } else {
                document.getElementById('suggestions2').innerHTML = '';
                document.getElementById('to-error').textContent = '';
                validSelections.to = false;
            }
        });

        function fetchSuggestions(query, suggestionBoxId, field) {
            if (cache[query]) {
                displaySuggestions(cache[query], suggestionBoxId, field);
                return;
            }

            const southIndiaBbox = '72.5,8.0,80.5,15.0'; // Bounding box for South India

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=5&viewbox=${southIndiaBbox}&bounded=1`)
                .then(response => response.json())
                .then(data => {
                    cache[query] = data; // Cache the results
                    displaySuggestions(data, suggestionBoxId, field);
                })
                .catch(error => console.error('Error fetching Nominatim suggestions:', error));
        }

        function displaySuggestions(data, suggestionBoxId, field) {
            const suggestionsContainer = document.getElementById(suggestionBoxId);
            suggestionsContainer.innerHTML = ''; // Clear previous suggestions

            if (data.length === 0) {
                document.getElementById(`${field}-error`).textContent = 'Invalid location';
                validSelections[field] = false;
            } else {
                document.getElementById(`${field}-error`).textContent = '';
                validSelections[field] = true;
            }

            // Populate the suggestion list
            data.forEach(function (place) {
                const suggestionItem = document.createElement('div');
                suggestionItem.classList.add('suggestion-item');
                suggestionItem.textContent = place.display_name;
                suggestionItem.onclick = function () {
                    document.getElementById(`autocomplete${field === 'from' ? '' : '2'}`).value = place.display_name;
                    suggestionsContainer.innerHTML = ''; // Clear suggestions after selection
                    validSelections[field] = true;
                };
                suggestionsContainer.appendChild(suggestionItem);
            });
        }

        function validateForm() {
            let isValid = true;

            // Validate 'From' field
            const fromInput = document.getElementById('autocomplete').value.trim();
            if (!validSelections.from || fromInput === '') {
                document.getElementById('from-error').textContent = 'Please select a valid "From" location';
                isValid = false;
            } else {
                document.getElementById('from-error').textContent = '';
            }

            // Validate 'To' field
            const toInput = document.getElementById('autocomplete2').value.trim();
            if (!validSelections.to || toInput === '') {
                document.getElementById('to-error').textContent = 'Please select a valid "To" location';
                isValid = false;
            } else {
                document.getElementById('to-error').textContent = '';
            }

            return isValid;
        }
    });
</script>

<?php include 'footer.php'; ?>