<link rel="stylesheet" href="snippets/window.css">
<div class="paycard">
    <div class="thankyou">
        <div class="cardThank">
            <div class="card-content-thank">
                <h2>Thank You!</h2>
                <p>You have been paid RS <span class="amount"><?php echo $_SESSION['price']; ?></span> for completing the trip.</p>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
                    <button class="go-back-btn" name="goBack" value="true">Go Back</button>
                </form>
            </div>
        </div>
    </div>
</div>