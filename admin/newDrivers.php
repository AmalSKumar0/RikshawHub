<div class="into">
    <h1 class="TagLine">Newly registered <span class="ride">Drivers!</span></h1>
    <div class="card-container">
    <?php
function handleError($error) {
    error_log($error);
    die("Something went wrong! Please try again later.");
}

$conn = mysqli_connect("localhost", "root", "", "rikshawhub");

if ($conn->connect_error) {
    handleError("Connection failed: " . $conn->connect_error);
}
//if the buttons are clicked
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['delete'])) {//deleting the content of temporarydriver database
                                 //on clicking the delete button
        $id_to_delete = $_GET['delete'];
        //deleteing image stored in uploads directory
        $sql = "SELECT Auto_img FROM temporarydriver WHERE driver_id = ?";
        if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_to_delete);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $image_path = 'uploads/' . $row['Auto_img'];
            if (file_exists($image_path)) {
                if (unlink($image_path)) {
                    echo "<script>console.log('Image deleted successfully');</script>";
                } else {
                    handleError("Error deleting image file.");
                }
            } else {
                echo "<script>console.log('Image file not found');</script>";
            }
        }
    }//deleting the content of temporarydriver database
        $sql = "DELETE FROM temporarydriver WHERE driver_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id_to_delete);
            if ($stmt->execute()) {
                echo "<script>alert('Deleted successfully');</script>";
            } else {
                handleError("Error deleting record: " . $stmt->error);
            }
            $stmt->close();
        } else {
            handleError("Error preparing statement: " . $conn->error);
        }
    }
    //deleteing the records form temporary and placing them at driver database
    if (isset($_GET['accept'])) {
        $accepted_id = $_GET['accept'];

        $sql = "SELECT * FROM temporarydriver WHERE driver_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $accepted_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                 //inserting the data of new driver into driver table by admin
                $stmt = $conn->prepare("INSERT INTO driver (name, email, phone_no, address, vehicle_no, licence_no, password, gender, Auto_img, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("sssssssss", $row['name'], $row['email'], $row['phone_no'], $row['address'], $row['vehicle_no'], $row['licence_no'], $row['password'], $row['gender'], $row['Auto_img']);
                if ($stmt->execute()) {
                    echo "<script>alert('Accepted successfully');</script>";
                    $stmt = $conn->prepare("DELETE FROM temporarydriver WHERE driver_id = ?");
                    $stmt->bind_param("i", $accepted_id);
                    if ($stmt->execute()) {
                        echo "<script>console.log('".$accepted_id." deleted');</script>";
                    } else {
                        handleError("Error deleting record: " . $stmt->error);
                    }
                } else {
                    handleError("Error inserting record: " . $stmt->error);
                }
                $stmt->close();
            } else {
                echo "<script>alert('No such record found.');</script>";
            }
        } else {
            handleError("Error preparing statement: " . $conn->error);
        }
    }
}

$sql = "SELECT * FROM temporarydriver";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) { ?>
        <div class="card">
            <div class="card-image">
                <img src="uploads/<?php echo $row['Auto_img']; ?>" alt="<?php echo $row['name']; ?>">
            </div>
            <div class="card-content">
                <h2><?php echo $row['name']; ?></h2>
                <div class="section">
                    <p>Address: <?php echo $row['address']; ?></p>
                    <p>Gender: <?php echo $row['gender']; ?></p>
                </div>
                <div class="section">
                    <p>Phone No: <?php echo $row['phone_no']; ?></p>
                </div>
                <p>Email: <?php echo $row['email']; ?></p>
                <p>Licence No: <?php echo $row['licence_no']; ?></p>
                <p>Vehicle No: <span class="vno"><?php echo $row['vehicle_no']; ?></span></p>
                <div class="button-group">
                    <form method='get' style='display:inline;' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <button name="delete" value='<?php echo $row["driver_id"];?>'>Cancel</button>
                        <button name="accept" value='<?php echo $row["driver_id"];?>'>Accept</button>
                    </form>
                </div>
            </div>
        </div>
    <?php }
} else {
    echo "0 results <br><br><br><br><br><br><br><br><br><br>";
}

$conn->close();
?>
</div>
</div>
