<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<div class="contain">
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <div id="testimonial-slider" class="owl-carousel">
                    <?php
                // Fetching reviews from the database
                $stmt = $conn->prepare("SELECT * FROM reviews WHERE driver_id = ?");
                $stmt->bind_param("i", $_SESSION['did']);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                  while ($review = $result->fetch_assoc()) {
                    $user_stmt= $conn->prepare("SELECT * FROM passenger WHERE pass_id = ?");
                    $user_stmt->bind_param("i", $review['pass_id']);
                    $user_stmt->execute();
                    $user_result = $user_stmt->get_result()->fetch_assoc();
                    ?>
                <div class="testimonial">
                    <div class="pic">
                        <img src="images/<?php if($user_result['gender']=='Male') echo 'man'; else echo 'woman'; ?>.png">
                    </div>
                    <div class="testimonial-content">
                        <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                    </div>
                    <h3 class="testimonial-title">
                        <a href="#"><?php echo $user_result['name']; ?></a>
                        <small>- <?php echo $review['stars']."/5"; ?></small>
                    </h3>
                </div>
                  <?php }
                } else {
                  echo "<p>No reviews yet.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
    $(document).ready(function(){
    $('#testimonial-slider').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsive:{
            0:{ items:1 },
            600:{ items:1 },
            1000:{ items:1 }
        }
    });
});

</script>
