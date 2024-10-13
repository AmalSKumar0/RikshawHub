<?php
// Assuming the connection $conn has already been established
$stmt = $conn->prepare("SELECT * FROM driver WHERE driver_id = ?");
$searchTerm = $_SESSION['driverid']; // No need for wildcard search on IDs
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>


<link rel="stylesheet" href="snippets/window.css">
<div class="paycard">
<div class="payment-card">
  <div class="left-section">
  <h3>DRIVER DETAILS:</h3>
    <div class="car-info">
      <div class="image-container">
        <img src="uploads/<?php echo $row['Auto_img']; ?>" alt="Car Image" class="car-image" />
      </div>
    </div>
    
    <div class="car-specs">
    <div class="specs-left">
        <p><strong>Name:</strong> <?php echo $row['name']; ?> </p>
        <p><strong>Gender:</strong> <?php echo $row['gender']; ?> </p>
        <p><strong>Address:</strong> <?php echo $row['address']; ?> </p>
    </div>
    <div class="specs-right">
        <p><strong>Rating:</strong> <?php echo $row['rating']; ?> </p>
        <p><strong>Licence No:</strong> <?php echo $row['licence_no']; ?> </p>
        <p><strong>Vehicle No:</strong> <?php echo $row['vehicle_no']; ?> </p>
    </div>
</div>
  </div>

  <div class="right-section">
  <img src="snippets/Accept Evrything.gif" alt="Car Image" class="gif" />
    <h2>Payment detail</h2>
    <div class="payment-info">
      <p>Pyament to: <?php echo $row['name'];?></p>
      <p>From: <?php echo explode(',', $_SESSION['from'])[0];?> To: <?php echo explode(',', $_SESSION['to'])[0];?></p>
        <p>Subtotal: <?php echo '₹'.$_SESSION['price'];?></p>
        <p>Total Distance: <?php echo $_SESSION['distance']." KM";?></p>
        <p>Promo code: none</p>
        <h3>Total: <?php echo '₹'.$_SESSION['price'];?></h3>
    </div>
    <form style="display: block; " id="paymentbuttons" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
            <button name="Back" class="back-button">Back</button>
            <button name="Book" value="<?php echo $_SESSION['driverid']; ?>" class="purchase-button">Purchase</button>
    </form>
  </div>
</div>
</div>
