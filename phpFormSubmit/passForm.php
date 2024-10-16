<?php
if (isset($_GET['View'])) {
    $_SESSION['did'] = $_GET['View'];
    $_SESSION['UserView'] = true;

    // Perform server-side redirection
    header("Location: profileView.php");
    exit(); // Terminate the script to prevent further processing
}
if (isset($_GET['payment'])) {
    $_SESSION['UserView'] = true;
    $_SESSION['driverid'] = $_GET['payment'];
    include 'snippets/paymentWindow.php';
}

if (isset($_GET['Book'])) { //passenger booked a particular auto which they find intresting

    $from = $_SESSION['from'];
    $to = $_SESSION['to'];
    $landmark = $_SESSION['landmark'];
    $_SESSION['did'] = $_GET['Book'];
    $status = "requested";
    $price = $_SESSION['price'];
    $distance = $_SESSION['distance'];
    $stmt = $conn->prepare("INSERT INTO bookings (pass_id, driver_id, `from`, `to`, landmark, status,price,distance) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissssii", $_SESSION['uid'], $_GET['Book'], $from, $to, $landmark, $status, $price, $distance);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        // echo '<script>alert("Booked successfully");</script>';
    } else {
        echo '<script>alert("Booking failed");</script>';
    }
}
if (isset($_GET['review'])) include 'snippets/review.php';
if (isset($_GET['submitReview'])) {
    // Get the form data
    $rating = $_GET['feedback'];         // The rating (stars)
    $text = $_GET['review_text'];      // The review text

    // Example pass_id and driver_id (you can retrieve these from session or form)
    $pass_id = $_SESSION['uid'];       // Assuming these are passed in the form
    $driver_id = $_SESSION['did'];   // Or set them from session data like $_SESSION['driver_id']
    // Prepare the SQL insert query
    $updated_rating = 0;
    if ($_SESSION["review_rating"] == 0) {
        $updated_rating = $rating;
    } else {
        $updated_rating = ($_SESSION["review_rating"] + $rating) / 2;
        $_SESSION["review_rating"] = $updated_rating;
    }
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
            echo '<script>consol.log("Error? ' . $pass_id . ' ' . $driver_id . '");</script>';
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the query: " . $mysqli->error;
    }
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