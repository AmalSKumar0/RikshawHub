
<div class="into">
<h1 class="TagLine">All <span class="ride">Drivers!</span></h1>
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
    if (isset($_GET['delete'])) {
        $id_to_delete = $_GET['delete'];
        $sql = "DELETE FROM driver WHERE driver_id = ?";
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
    if (isset($_GET['edit'])) {
        $_SESSION['did']=$_GET['edit'];
        $_SESSION['whoEdit']='driver';
        $_SESSION['whoami']='admin';
        echo '<script>window.location.href="./admin/editPage.php";</script>';
    }
}

$sql = "SELECT * FROM driver";
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
                        <button name="delete" value='<?php echo $row["driver_id"];?>'>DELETE</button>
                        <button name="edit" value='<?php echo $row["driver_id"];?>'>EDIT</button>
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

