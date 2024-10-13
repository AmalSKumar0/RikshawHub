<?php include 'header.php'; ?>
<?php session_start(); ?>
<!-- landing page of our website -->
    <nav>
      <div class="nav__header">
        <div class="nav__logo">
          <a href="#">RICKSHAW<span>HUB</span>.</a>
        </div>
        <div class="nav__menu__btn" id="menu-btn">
          <span><i class="ri-menu-line"></i></span>
        </div>
      </div>
      <ul class="nav__links" id="nav-links">
        <!-- <li><a href="#">Destination</a></li> -->
        <li><a href="#" style="color:#ff833e;">Home</a></li>
        <li><a href="about.html" >About Us</a></li>
        <li><a href="#" id="testimoniesButton">Reviews</a></li>
        <li><a href="PassReg.php" >Ride now</a></li>
        <!-- <li><a href="#" id="aboutusButton">Contact us</a></li> -->
      </ul>
      <div class="nav__btns">
      <a href="contact.php" class="btn sign__in">Contact us</a>
      </div>
    </nav>
    <br>
    <header class="header__container">
      <div class="header__image">
        <div class="header__image__card header__image__card-1">
          <span><i class="ri-key-line"></i></span>
          Taxi Booking
        </div>
        <div class="header__image__card header__image__card-2">
          <span><a href="adminLog.php"><i class="ri-passport-line"></i></a></span>
          Affordable
        </div>
        <div class="header__image__card header__image__card-3">
          <span><i class="ri-map-2-line"></i></span>
          Anywhere
        </div>
        <div class="header__image__card header__image__card-4">
          <span><i class="ri-guide-line"></i></span>
          On time
        </div>
        <img class="auto-pic" src="assets/header.png" alt="header" />
      </div>
      <div class="header__content">
        <h1>LETS GO!<br/><span>QUICK</span> <span>RIDES</span> FOR<br> A FASTER LIFE</h1>
        <p>
          Start Your Ride Today and Explore the City <br>     - Your Adventure Awaits with Convenient Journeys, Memorable Stops, and Endless Possibilities!
        </p>
        <div class="container">
          <div class="input__row">
            <a class="passenger" href="<?php if(isset($_SESSION['uid'])){echo "passenger.php";} else{ echo "PassReg.php";}?>">I'M A PASSENGER</a><a class="driver" href="<?php if(isset($_SESSION['did'])){echo "Driver.php";} else{ echo "DriverReg.php";}?>">I'M A DRIVER</a>
          </div>
        </div>
      </div>
    </header>
    <br><br><br><br>
     <!--Testimonials-->
     <aside id="testimonials" class="scrollto text-center" data-enllax-ratio=".2">

      <div class="row clearfix" id="testimoniesSection">

          <div class="section-heading">
              <h3>FEEDBACK</h3>
              <h2 class="section-title">What our customers are saying</h2>
          </div>
        <div class="testcon">
          <!--User Testimonial-->
          <blockquote class="col-3 testimonial classic john" >
              <img class="imgu" src="assets/user-images/user-1.jpg" alt="User"/>
              <q>This auto rickshaw booking app is fantastic! It’s super easy to use, and the drivers are always punctual. Highly recommend it for anyone needing a quick ride around town!</q>
              <footer>John Doe - Happy Customer</footer>
          </blockquote>
          <!-- End of Testimonial-->

          <!--User Testimonial-->
          <blockquote class="col-3 testimonial classic user2">
              <img class="imgu" src="assets/user-images/user-2.jpg" alt="User"/>
              <q>I’ve tried several rickshaw apps, but this one stands out. The interface is clean, and the booking process is seamless. Plus, the drivers are friendly and reliable.</q>
              <footer>Emily Johnson - Happy Customer</footer>
          </blockquote>
          <!-- End of Testimonial-->

          <!--User Testimonial-->
          <blockquote class="col-3 testimonial classic user3">
              <img class="imgu" src="assets/user-images/user-3.jpg" alt="User"/>
              <q>Great app with a user-friendly design! I love how quickly I can book a ride and track my rickshaw in real-time. It’s made my daily commute so much smoother.</q>
              <footer>John Smith - Happy Customer</footer>
          </blockquote>
        </div>
          <!-- End of Testimonial-->
 
      </div>
      <br><br><br><br>
  </aside>
  <!--End of Testimonials-->
  <?php include 'footer.php'; ?>