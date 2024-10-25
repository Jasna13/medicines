<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | MediCare</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="navbar">
            <h1 class="logo">MediCare</h1>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="products.html">Products</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="Add_to_cart.html">Add to Cart</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="cart-section">
        <h2>Your Cart</h2>
        <div class="cart-items">
        <?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "medico_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming user_id is stored in the session
$userId = $_SESSION['uid'] ?? 1; // Fallback to 1 if not set
$sql = "SELECT products.id, products.name, products.price, cart.quantity 
        FROM cart 
        JOIN products ON cart.id = products.id 
        WHERE cart.uid = ?"; // Using uid for filtering

// Prepare statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL prepare error: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$totalPrice = 0; // Initialize total price

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $totalPrice += $row['price'] * $row['quantity']; // Calculate total price based on quantity
        echo "<div class='cart-item'>
                <p>Product: " . htmlspecialchars($row['name'], ENT_QUOTES) . "</p>
                <p>Price: $" . number_format($row['price'], 2) . "</p>
                <p>Quantity: " . htmlspecialchars($row['quantity'], ENT_QUOTES) . "</p>
                <form action='remove_from_cart.php' method='post'>
                    <input type='hidden' name='product_id' value='" . htmlspecialchars($row['id'], ENT_QUOTES) . "'>
                    <button type='submit' class='remove-button'>Remove</button>
                </form>
              </div>";
    }
} else {
    echo "<p>Your cart is empty.</p>";
}

$stmt->close();
$conn->close();
?>

<?php if ($totalPrice > 0): ?>
    <div class="total-price">
        <h3>Total Price: $<?php echo number_format($totalPrice, 2); ?></h3>
    </div>
<?php endif; ?>

        </div>
    </section>

    <footer>
        <p>&copy; 2024 MediCare. All rights reserved.</p>
    </footer>
</body>
</html>
