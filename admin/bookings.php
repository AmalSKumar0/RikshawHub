<div class="into">
<h1 class="TagLine">All <span class="ride">Bookings!</span></h1>
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
        //deleting the content of temporarydriver database
        $sql = "DELETE FROM bookings WHERE book_id = ?";
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
        $accepted_id = $_GET['edit'];
    }
}

$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) { ?>
        <div class="card">
            <div class="card-content">
                <h2>BOOKING ID:<?php echo $row['book_id']; ?></h2>
                <div class="section">
                    <p>From: <?php echo $row['from']; ?></p>
                    <p>To: <?php echo $row['to']; ?></p>
                </div>
                <div class="section">
                    <p>Passenger: <?php 
                    $stmt = $conn->prepare("SELECT name FROM passenger WHERE pass_id = ?");
                    $stmt->bind_param("s", $row['pass_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                    echo $user['name'];
                    ?></p>
                    <p>Driver: <?php 
                    $stmt = $conn->prepare("SELECT name FROM driver WHERE driver_id = ?");
                    $stmt->bind_param("s", $row['driver_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                    echo $user['name'];
                    ?></p>
                    <p>status:<?phpif($row['status']=='cancel confirmed')echo 'Canceled'; else if($row['status']=='complete confirmed') echo 'completed'; else echo $row['status'];?></p>
                </div>
                <div class="button-group">
                    <form method='get' style='display:inline;' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <button name="delete" value='<?php echo $row["book_id"];?>'>DELETE</button>
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