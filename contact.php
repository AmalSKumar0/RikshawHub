<?php include 'header.php'; ?>
<link rel="stylesheet" href="styles/contact.css">
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
        <li><a href="index.php">Home</a></li>
        <li><a href="about.html" >About Us</a></li>
        <li><a href="#" id="testimoniesButton">Reviews</a></li>
        <li><a href="PassReg.php" style="color:#ff833e;">Contact us</a></li>
        <!-- <li><a href="#" id="aboutusButton">Contact us</a></li> -->
      </ul>
      <div class="nav__btns">
      <a href="PassReg.php" class="btn sign__in">Login/Signin</a>
      </div>
    </nav>
    <section>
            <div class="container">
                <div class="contactInfo"> 
                    <div>
                        <h2>Contact Info</h2>
                        <ul class="info">
                            <li>
                                <span><img src="images/icons/location.png"></span>
                                <span>MES college <br>
                                    Erumely<br>
                                    Kottayam</span>
                                </span>
                            </li>
                            <li>
                                <span><img src="images/icons/mail.png"></span>
                                <!-- <span>nassosanagn@gmail.com</span> -->
                                <span><a href = "mailto: rickshawhub@gmail.com">rickshawhub@gmail.com</a></span>
                            </li>
                            <li>
                                <span><img src="images/icons/call.png"></span>
                                <span>+91 98765 4321</span>
                            </li>

                        </ul>
                    </div>
                   
                </div>
                    <div class="contactForm">
                        <h2>Send a Message</h2>
                        <div class="formBox">
                        <div class="inputBox w50">
                            <input type="text" name="" required>
                            <span>First Name</span>
                        </div>
                        <div class="inputBox w50">
                            <input type="text" required>
                            <span>Last Name</span>
                        </div>
                        <div class="inputBox w50">
                            <input type="email" required>
                            <span>Email Address</span>
                        </div>
                        <div class="inputBox w50">
                            <input type="text" required>
                            <span>Mobile Number</span>
                        </div>
                        <div class="inputBox w100">
                            <textarea required></textarea>
                            <span>Write your message here...</span>
                        </div>
                        <div class="inputBox w100">
                            <input type="submit" value="Send">
                        </div>
                    </div>
                </div>
        </section>
<?php include 'footer.php'; ?>