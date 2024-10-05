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
        <p><strong>Color:</strong> White</p>
        <p><strong>Mileage:</strong> 25000 Km</p>
    </div>
    <div class="specs-right">
        <p><strong>Transmission:</strong> Manual</p>
        <p><strong>Drive unit:</strong> Front</p>
        <p><strong>Daily ID:</strong> #002</p>
    </div>
</div>
  </div>

  <div class="right-section">
  <img src="snippets/Accept Evrything.gif" alt="Car Image" class="gif" />
    <h2>Payment detail</h2>
    <div class="payment-info">
        <p>Subtotal: <?php echo $_SESSION['price'];?></p>
        <p>Shipping: Free</p>
        <p>Promo code: - $100</p>
        <h3>Total: $1,350</h3>
    </div>
    <form style="display: block; " action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
            <button name="Back" class="back-button">Back</button>
            <button name="Book" value="<?php echo $_SESSION['driverid']; ?>" class="purchase-button">Purchase</button>
    </form>
  </div>
</div>
</div>
