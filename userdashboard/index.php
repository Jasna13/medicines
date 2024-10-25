<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    // Redirect to login page if not logged in
    header("Location:http://localhost/MINI%20PROJECT/Login/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>MediCare</title>
</head>
<body>
  <!-- Navbar -->
  <header>
    <div class="header-container">
        <span class="brand-name">MediCare</span>
    </div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="cart.php">Add to Cart</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="profile.php">Profile</a></li>
        <?php if (isset($_SESSION['uid'])): ?>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <!-- Search Section -->
  <section class="search-section-top">
    <form id="searchForm" action="search_results.php" method="POST">
      <input type="text" id="searchQuery" name="searchQuery" placeholder="Search for a product..." required>
      <button type="submit" class="btn">Search</button>
    </form>
    <div id="searchResults"></div>
  </section>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-content">
      <h2>Your Health, Our Priority</h2>
      <p>Shop for all your medical needs at affordable prices</p>
      <a href="products.php" class="btn">Shop Now</a>
    </div>
  </section>

  <!-- Discount Products Section -->
  <section class="discount-products-section">
    <h2>Discounted Products</h2>
    <div class="products-grid">
      <?php
      $host = "localhost";
      $dbname = "medico_shop";
      $username = "root";
      $password = "root";

      // Create connection
      $conn = new mysqli($host, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Prepare SQL query to fetch only discounted products
      $sql = "SELECT id, name AS product_name, image AS product_image, price AS original_price, discounted_price FROM products WHERE discounted_price > 0";
      $result = $conn->query($sql);

      // Check if the query was successful
      if (!$result) {
          die("Error executing query: " . $conn->error);
      }

      // Output data for each discounted product
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              // Calculate the final price after discount
              $finalPrice = number_format($row['original_price'] - $row['discounted_price'], 2);
              // Make sure the image path is correct. If the images folder is inside the Dashboard folder, adjust the path accordingly
              $imagePath = "../" . htmlspecialchars($row['product_image'], ENT_QUOTES); 
              echo "<div class='product-card'>
                      <img src='$imagePath' alt='" . htmlspecialchars($row['product_name'], ENT_QUOTES) . "'>
                      <h3>" . htmlspecialchars($row['product_name'], ENT_QUOTES) . "</h3>
                      <p><span class='original-price'>₹" . number_format($row['original_price'], 2) . "</span> ₹" . htmlspecialchars($finalPrice, ENT_QUOTES) . "</p>
                      <a href='product_details.php?id=" . htmlspecialchars($row['id'], ENT_QUOTES) . "' class='btn view-details'>View Details</a>
                    </div>";
          }
      } else {
          echo "<p>No discounted products available.</p>";
      }

      // Close the connection
      $conn->close();
      ?>
    </div>
  </section>
</body>
</html>
