<div class="into">
<h1 class="TagLine">All <span class="ride">Passengers!</span></h1>
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
        $sql = "DELETE FROM passenger WHERE pass_id = ?";
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
        $_SESSION['uid']=$_GET['edit'];
        $_SESSION['whoEdit']='pass';
        $_SESSION['whoami']='admin';
        echo '<script>window.location.href="./admin/editPage.php";</script>';
    }
}

$sql = "SELECT * FROM passenger";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) { ?>
        <div class="card">
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
                <div class="button-group">
                    <form method='get' style='display:inline;' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <button name="delete" value='<?php echo $row["pass_id"];?>'>DELETE</button>
                        <button name="edit" value='<?php echo $row["pass_id"];?>'>EDIT</button>
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
