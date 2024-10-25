<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - MediCare</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <!-- Navbar -->
    <!-- Navbar -->
  <header>
    <div class="header-container">
          <!-- <img src="medicare-logo-designs-health-service-hospital-clinic-logo_1093924-122.jpg" alt="Shop Logo" class="logo" height="50" width="100"> -->
          <span class="brand-name">MediCare</span>
      </div>
        <nav>
          <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="cart.php">Add to Cart</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="profile.php">Profile</a></li>
            <?php if (isset($_SESSION['uid'])): ?>
              <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
              <li><a href="login.php">Login</a></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </header>
    <style>

h1, h2 {
    color: #388E3C;
    margin-bottom: 10px;
}

section {
    margin-bottom: 30px;
}

p, ul {
    font-size: 1rem;
    margin-bottom: 20px;
}

ul {
    list-style-type: disc;
    padding-left: 20px;
}

ul li {
    margin-bottom: 10px;
}

strong {
    color: #000;
}

/* About MediCare Section Styling */
.about-medicare {
    background-color: #f5f5f5; /* Light background color for contrast */
    padding: 20px;
    border-radius: 50px; /* Rounded corners for a softer look */
    border-left: 5px solid #2e6d3e; /* Green accent border on the left */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    margin-bottom: 30px;
    transition: background-color 0.3s ease; /* Smooth hover effect */
}

/* Heading in About MediCare Section */
.about-medicare h2 {
    font-size: 1.8rem;
    color: #2e6d3e; /* Matching green color */
    margin-bottom: 15px;
}
/* For the "About MediCare" box */
.about-medicare-box {
    max-width: 600px; /* Adjust the width to make it smaller */
    margin: 0 auto; /* Centers the box on the page */
    padding: 20px;
    border: 1px solid #ddd;
    background-color: #fff;
    border-radius: 10px;
}

/* For the "Our Mission" box */
.mission-box {
    max-width: 900px; /* Adjust the width to match */
    margin: 20px auto; /* Adds some space between the boxes */
    padding: 20px;
    border: 1px solid #ddd;
    background-color: #f0fff0;
    border-radius: 10px;
    border-left: 5px solid #2e6d3e;
}


/* Text Styling in About MediCare Section */
.about-medicare p {
    font-size: 1.1rem;
    color: #333;
    line-height: 0.5;
}

/* Hover Effect for the About Section */
.about-medicare:hover {
    background-color: #e8f5e9; /* Light green on hover */
}
.mission:hover {
    background-color: #e8f5e9; /* Light green on hover */
}
.offerings:hover{
    background-color:#e8f5e9;
}
.why-choose-us:hover{
    background-color: #e8f5e9;
}
.commitment:hover{
    background-color: #e8f5e9;
}
/* Bold Text Styling in the About Section */
.about-medicare strong {
    color: #2e6d3e; /* Green for emphasized text */
    font-weight: bold;
}

.mission, .offerings, .why-choose-us, .commitment {
    padding: 15px;
    background-color: #fff;
    border-radius: 50px;
    box-shadow: 0 20px 10px rgba(0, 0, 0, 0.1);
    border-left: 5px solid #2e6d3e;
}

.mission p, .commitment p {
    font-size: 1.1rem;
}
</style>

    <!-- Main About Us Section -->
    <body>

    <section class="about-medicare">
        <h1>About MediCare</h1>
        <p>Welcome to <strong>MediCare</strong>, your one-stop destination for all your healthcare and medical needs</p>
        <p> At MediCare, we are committed to providing the best quality healthcare products at affordable prices.</p> 
        <p> From over-the-counter medicines to prescription drugs, medical devices, and personal care items</p>
        <p>we offer a wide range of products to help you stay healthy and happy.</p>
    </section>

    <section class="mission">
        <h2>Our Mission</h2>
        <p>Our mission is simple: <strong>to make healthcare accessible and affordable for everyone.</strong> We believe that everyone deserves the best care, and that is why we are dedicated to bringing you high-quality products, expert advice, and excellent customer service.</p>
    </section>

    <section class="offerings">
        <h2>What We Offer</h2>
        <ul>
            <li><strong>Medicines:</strong> Prescription and over-the-counter medicines for all health conditions.</li>
            <li><strong>Medical Devices:</strong> Blood pressure monitors, thermometers, glucose meters, and more.</li>
            <li><strong>Personal Care:</strong> Skincare, hygiene products, vitamins, and supplements to maintain overall well-being.</li>
            <li><strong>Baby & Mother Care:</strong> Products specially formulated for babies and mothers.</li>
            <li><strong>Health Supplements:</strong> A variety of vitamins, minerals, and supplements to enhance health.</li>
        </ul>
    </section>

    <section class="why-choose-us">
        <h2>Why Choose Us?</h2>
        <ul>
            <li>Experienced and knowledgeable staff ready to assist with your healthcare needs.</li>
            <li>A wide selection of trusted and well-known brands.</li>
            <li>Competitive pricing to ensure affordability without compromising on quality.</li>
            <li>Convenient online ordering with fast delivery.</li>
            <li>Regular discounts and promotions to help you save more.</li>
        </ul>
    </section>

    <section class="commitment">
        <h2>Our Commitment to You</h2>
        <p>We are more than just a medical shop — we are your health partners. Our commitment is to provide you with safe, reliable, and high-quality healthcare solutions. Whether you need advice on medication or a quick refill on your prescription, we are here to help you every step of the way. Thank you for choosing MediCare. We look forward to serving you and your family's health needs!</p>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <p>&copy; 2024 MediCare. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
