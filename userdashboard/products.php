<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products | MediCare</title>
  <link rel="stylesheet" href="style.css">
  <!-- <script src="script.js" defer></script> -->
  <style>
    /* Category Dropdown */
select {
    padding: 10px 15px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ccc;
    background-color: #fff;
    color: #333;
    cursor: pointer;
    margin-bottom: 20px;
    justify-content: center;
}
/* Category Container */
.category-container {
    display: flex;
    justify-content: center; /* Centers the dropdown horizontally */
    margin-bottom: 20px;
}

select:focus {
    border-color: #4CAF50;
    outline: none;
}
    /* Modal styles */
    .modal {
      display: none; 
      position: fixed; 
      z-index: 1; 
      left: 0;
      top: 0;
      width: 100%; 
      height: 100%; 
      overflow: auto; 
      background-color: rgb(0,0,0);
      background-color: rgba(0,0,0,0.9); 
      padding-top: 60px;
    }
    .modal-content {
      margin: auto;
      display: block;
      width: 80%; 
      max-width: 700px; 
    }
    .close {
      position: absolute;
      top: 15px;
      right: 35px;
      color: #fff;
      font-size: 40px;
      font-weight: bold;
    }
    .close:hover,
    .close:focus {
      color: #bbb;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
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
    </div>
  </header>

  <!-- Category Selection Section -->
  <section class="category-section">
    <h2>Select Category</h2>
    <select id="categorySelect">
      <option value="all">All Products</option>
      <option value="medicines">Medicines</option>
      <option value="supplements">Supplements</option>
      <option value="medical-equipment">Medical Equipment</option>
      <option value="personal-care">Personal Care</option>
    </select>
  </section>

  <!-- Products Section -->
  <section class="products-section">
    <h2>Products</h2>
    <div class="products-grid">
      <?php
      // Database connection
      $servername = "localhost"; 
      $username = "root"; 
      $password = "root"; 
      $dbname = "medico_shop"; 

      $conn = new mysqli($servername, $username, $password, $dbname);

      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Get category from the request or default to 'all'
      $category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : 'all';
      $sql = ($category === 'all') ? "SELECT * FROM products" : "SELECT * FROM products WHERE category='$category'";

      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              // Check for discounted price
              $discountedPrice = (isset($row['discounted_price']) && $row['discounted_price'] > 0 && $row['discounted_price'] < $row['price'])
                                ? number_format($row['discounted_price'], 2)
                                : number_format($row['price'], 2);

              $priceDisplay = ($discountedPrice < $row['price'])
                              ? "<span class='original-price'>\$" . number_format($row['price'], 2) . "</span> \$" . $discountedPrice
                              : "\$" . $discountedPrice;

            
              // Display product card
              echo "<div class='product-card' data-category='" . htmlspecialchars($row['category'], ENT_QUOTES) . "' data-id='" . htmlspecialchars($row['id'], ENT_QUOTES) . "'>
                      <img src=".$row['image']." alt='" . htmlspecialchars($row['name'], ENT_QUOTES) . "' style='width: 150px; height: 150px;' class='product-image' /> <!-- Product Image -->
                      <h3>" . htmlspecialchars($row['name'], ENT_QUOTES) . "</h3>
                      <p>Price: $priceDisplay</p>
                      <button class='btn view-details' onclick='viewDetails(\"" . htmlspecialchars($row['id'], ENT_QUOTES) . "\")'>View Details</button>
                    </div>";
          }
      } else {
          echo "<p>No products available.</p>";
      }

      $conn->close();
      ?>
    </div>
  </section>

  <!-- Modal for image viewing -->
  <div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="img01">
    <div id="caption"></div>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; 2024 MediCare. All rights reserved.</p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // View Details functionality
      window.viewDetails = function(productId) {
        window.location.href = "product_details.php?id=" + productId; // Change to your product details page
      };

      // Image viewing functionality
      const modal = document.getElementById("myModal");
      const modalImg = document.getElementById("img01");
      const captionText = document.getElementById("caption");

      document.querySelectorAll('.product-image').forEach(img => {
        img.onclick = function(){
          modal.style.display = "block";
          modalImg.src = this.src;
          captionText.innerHTML = this.alt;
        }
      });

      const closeModal = function() {
        modal.style.display = "none";
      }

      // Category selection functionality
      const categorySelect = document.getElementById('categorySelect');
      categorySelect.addEventListener('change', function() {
        const selectedCategory = categorySelect.value;
        const products = document.querySelectorAll('.product-card');

        products.forEach(product => {
          const productCategory = product.getAttribute('data-category');
          product.style.display = (selectedCategory === 'all' || productCategory === selectedCategory) ? 'block' : 'none';
        });
      });
    });
  </script>
</body>
</html>