<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - RickshawHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    line-height: 1.6;
    color: #333;
}

header {
    background-color: white;
    padding: 20px 0;
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1100px;
    margin: 0 auto;
}

.logo {
    font-size: 24px;
    font-weight: bold;
    color: #f97b4e;
}

.nav-links {
    list-style-type: none;
    display: flex;
    gap: 20px;
}

.nav-links a {
    text-decoration: none;
    color: #333;
    padding: 5px 15px;
    font-weight: bold;
    transition: color 0.3s ease;
}

.nav-links a:hover, .nav-links .active {
    color: #f97b4e;
}

.btn-contact {
    background-color: #f97b4e;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 20px;
    font-weight: bold;
}

.btn-contact:hover {
    background-color: #ff8f65;
}

.about-hero {
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 100px 50px;
    background-color: #fefefe;
}

.about-content {
    max-width: 600px;
}

.about-content h1 {
    font-size: 3rem;
    color: #333;
}

.about-content h1 .highlight {
    color: #f97b4e;
}

.about-content p {
    font-size: 1.2rem;
    margin-top: 20px;
}

.about-image img {
    width: 350px;
}

.about-details {
    background-color: #fff8f0;
    padding: 60px 50px;
    text-align: center;
}

.details-content {
    max-width: 900px;
    margin: 0 auto;
}

.details-content h2 {
    font-size: 2.2rem;
    margin-bottom: 20px;
    color: #333;
}

.details-content p {
    font-size: 1.2rem;
    margin-bottom: 40px;
}

.details-content ul {
    list-style-type: none;
}

.details-content ul li {
    font-size: 1.1rem;
    margin-bottom: 15px;
}

footer {
    background-color: #333;
    color: #fff;
    padding: 20px 0;
    text-align: center;
}

footer p {
    margin: 0;
}

    </style>
</head>
<body>
    <header>
    <?php include 'header.php'; ?>
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
      <a href="adminLog.php" class="btn sign__in">Contact us</a>
      </div>
    </nav>
    </header>

    <section class="about-hero">
        <div class="about-content">
            <h1>About <span class="highlight">RickshawHub</span></h1>
            <p>At RickshawHub, we connect passengers with reliable, affordable auto-rickshaw rides, ensuring a faster and smoother commute in the city.</p>
        </div>
        <div class="about-image">
            <img src="auto_image.png" alt="Rickshaw Image">
        </div>
    </section>

    <section class="about-details">
        <div class="details-content">
            <h2>Our Mission</h2>
            <p>RickshawHub aims to revolutionize the way people commute by offering quick, affordable, and eco-friendly rides through a platform that empowers auto drivers and serves the local community.</p>

            <h2>Why Choose Us?</h2>
            <ul>
                <li>Convenient ride booking with transparent fares.</li>
                <li>Real-time tracking to ensure a safe journey.</li>
                <li>Support local auto drivers and help them grow.</li>
                <li>Reliable, on-time rides at affordable rates.</li>
            </ul>

            <h2>Our Vision</h2>
            <p>We envision a future where local transport is easy, reliable, and supports both the passengers and drivers. RickshawHub is more than a ride—it’s a community initiative to help cities move smarter.</p>
        </div>
    </section>

    <footer>
        <p>© 2024 RickshawHub. All rights reserved.</p>
    </footer>
</body>
</html>
