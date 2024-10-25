<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
  <script src="script.js" defer></script>
</head>
<body>
  <!-- Navbar -->
  <header>
    <div class="navbar">
      <h1 class="logo">MediCare</h1>
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="products.php">Products</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="Add_to_cart.php">Add to Cart</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Search Section -->
  <section class="search-section-top">
    <form id="searchForm">
      <input type="text" id="searchQuery" placeholder="Search for a product..." required>
      <button type="submit" class="btn">Search</button>
    </form>
    <div id="searchResults"></div>
  </section>

  <!-- Home Section -->
  <section class="hero-section">
    <div class="hero-content">
      <h2>Your Health, Our Priority</h2>
      <p>Shop for all your medical needs at affordable prices</p>
      <a href="products.php" class="btn">Shop Now</a>
    </div>
  </section>

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
    $sql = "SELECT name AS product_name, image AS product_image, price AS original_price, discounted_price FROM products WHERE discounted_price > 0";
    $result = $conn->query($sql);

    // Output data for each discounted product
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Calculate the final price after discount
            $finalPrice = number_format($row['original_price']-$row['discounted_price']);
            // $discountDisplay = "<span class='original-price'>\$" . number_format($row['price'], 2) . "</span> \$" . $finalPrice;
            echo "<div class='product-card'>
                    <img src='".htmlspecialchars($row['product_image'], ENT_QUOTES)."' alt='".htmlspecialchars($row['product_name'], ENT_QUOTES)."'>
                    <h3>".htmlspecialchars($row['product_name'], ENT_QUOTES)."</h3>
                    <p><span class='original-price'>₹".number_format($row['original_price'], 2)."</span> ₹".htmlspecialchars($finalPrice, ENT_QUOTES)."</p>
                    <button class='btn buy-now'>Buy Now</button>
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

if (isset($_POST['searchQuery'])) {
    $searchQuery = $conn->real_escape_string($_POST['searchQuery']);

    // Prepare SQL query to fetch products based on search query
    $sql = "SELECT name AS product_name, image AS product_image, price AS original_price, discounted_price FROM products WHERE product_name LIKE '%$searchQuery%' AND discounted_price > 0";
    $result = $conn->query($sql);

    $searchResults = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
    
    header('Content-Type: application/json'); // Set header for JSON response
    echo json_encode($searchResults); // Send JSON data
}
?>
