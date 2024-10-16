<link rel="stylesheet" href="snippets/window.css">
<div class="paycard">
    <div class="thankyou">
        <div class="cardThank">
            <div class="card-content-thank">
                <h2>Thank You!</h2>
                <?php
                $price = $_SESSION['price'];
                $reducedPrice = $price * 0.9; // Subtracting 10%
                ?>

                <p>Congratulations! You have received a payment of <span class="amount">₹ <?php echo $reducedPrice; ?></span> for successfully completing the trip</p>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
                    <button class="go-back-btn" name="goBack" value="true">Go Back</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="scripts/confetti.js"></script>