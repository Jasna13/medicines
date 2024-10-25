<?php
session_start(); // Start the session

// Check if there is a message to display
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); // Clear the message after displaying it

// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = "root"; 
$dbname = "medico_shop"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID from the URL and fetch product details
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM products WHERE id = $productId";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

if (!$product) {
    echo "<h2>Product not found!</h2>";
    exit;
}

// Calculate final price after discount
$finalPrice = $product['discounted_price'] > 0 ? $product['price'] - $product['discounted_price'] : $product['price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?> | MediCare</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        img{
            width: 150px; /* Adjust the width as needed */
            height: auto;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensures body takes full height of viewport */
        }

        /* Header Styling */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #06782c;
            padding: 15px;
            color: white;
        }

        .logo h1 {
            font-size: 24px;
        }

        nav {
            display: flex;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* Product Card Styling */
        .main-content {
            flex-grow: 1; /* Allows the main content to expand and push the footer down */
        }

        .product-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            overflow: hidden;
        }

        .product-image img {
            max-width: 250px;
            border-radius: 10px;
            object-fit: cover;
        }

        .product-info {
            flex-grow: 1;
            margin-left: 20px;
        }

        .product-info h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-info .price {
            font-size: 20px;
            color: red;
            margin-bottom: 15px;
        }

        /* Button Styling */
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.add-to-cart {
            background-color:#06782c;
            color: white;
        }

        button.add-to-cart:hover {
            background-color: #06782c;
        }

        button.buy-now {
            background-color: #06782c;
            color: white;
        }

        button.buy-now:hover {
            background-color: #0056b3;
        }

        /* Align the buttons */
        .buttons-container {
            display: flex;
            gap: 20px; /* Adds space between the buttons */
            margin-top: 20px;
        }

        /* Footer Styling */
        footer {
            background-color: #06782c;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .logo {
    width: 150px; /* Adjust the size */
    height: auto;
}

    </style>
</head>
<body>
<header>
    <div class="header-container">
        <img src="medicare-logo-designs-health-service-hospital-clinic-logo_1093924-122.jpg" alt="Shop Logo" class="logo" height="50" width="100">
        <span class="brand-name">MediCare</span>
    </div>
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="products.html">Products</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="cart.php">Add to Cart</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="profile.html">Profile</a></li>
        </ul>
    </nav>
</header>

    <main class="main-content">
        <div class="product-card">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>" />
            </div>
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?></h2>
                <p class="price">
                    <?php if ($product['discounted_price'] > 0): ?>
                        <span class="original-price" style="text-decoration: line-through;">₹<?php echo number_format($product['price'], 2); ?></span>
                        ₹<?php echo number_format($finalPrice, 2); ?>
                    <?php else: ?>
                        ₹<?php echo number_format($product['price'], 2); ?>
                    <?php endif; ?>
                </p>
                
                <!-- Form to add product to cart -->
                <form action="add_to_cart_actions.php" method="POST" onsubmit="return showAlert()">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES); ?>">
                    <div>
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="1" required>
                    </div>
                    <div class="buttons-container">
                        <button type="submit" class="btn add-to-cart">Add to Cart</button>
                        <button type="button" class="btn buy-now" onclick="buyNow(<?php echo htmlspecialchars($product['id'], ENT_QUOTES); ?>)">Buy Now</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 MediCare. All rights reserved.</p>
    </footer>

    <script>
        function buyNow(productId) {
            const quantity = document.getElementById('quantity').value;
            window.location.href = "buy_now.php?id=" + productId + "&quantity=" + quantity;
        }

        function showAlert() {
            const quantity = document.getElementById('quantity').value;
            alert("Added " + quantity + " item(s) to your cart!");
            return true; // Proceed with form submission
        }
    </script>
</body>
</html>
