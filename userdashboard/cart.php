<?php
session_start();
$con=mysqli_connect("localhost","root","root","medico_shop");
// Get user ID from session
$uid = $_SESSION['uid'];

// Fetch cart items for this user
$query = "SELECT cart.cid, products.name, products.price, cart.quantity, (products.price * cart.quantity) AS total
          FROM cart
          INNER JOIN products ON cart.id = products.id
          WHERE cart.uid = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
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
<!-- <style>
        /* General Reset */
        
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

img {
    width: 150px; /* Adjust the width as needed */
    height: auto;
}

body {
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Ensures body takes full height of viewport */
    background-color: #F0F5F1; /* Light green background for the page */
}

/* Header Styling */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #2E7D32; /* Modern dark green for header */
    padding: 15px;
    color: white;
}

/* Targeting the title */
h1.title {
    font-size: 48px;
    font-weight: bold;
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
    background-color:#66BB6A;
}

/* Product Card Styling */
.main-content {
    flex-grow: 1;
}

.product-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #ffffff;
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
    color: #C62828; /* Red for price */
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
    background-color: #388E3C; /* Medium green for add to cart */
    color: white;
}

button.add-to-cart:hover {
    background-color: #2E7D32; /* Darker green on hover */
}

button.buy-now {
    background-color: #1B5E20; /* Darker green for buy now */
    color: white;
}

button.buy-now:hover {
    background-color: #0056b3; /* Deep blue for hover effect */
}

/* Align the buttons */
.buttons-container {
    display: flex;
    gap: 20px; /* Adds space between the buttons */
    margin-top: 20px;
}

/* Footer Styling */
footer {
    background-color: #2E7D32; /* Same green as header */
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

/* Cart title */
h2 {
    text-align: center;
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
} -->
<style>
/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: center;
}

th {
    background-color: #43A047; /* Brighter green for table headers */
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #e2e2e2;
}

/* Input fields in forms
input[type="number"] {
    width: 60px;
    padding: 5px;
    text-align: center;
}

input[type="submit"] {
    padding: 6px 12px;
    margin-top: 5px;
    cursor: pointer;
    background-color: #43A047; /* Matching green for buttons */
    /* color: white;
    border: none;
    border-radius: 5px;
}

input[type="submit"]:hover {
    background-color: #388E3C;
}

 */
.checkout{
    display: inline-block;
    padding: 10px 20px;
    background-color: #43A047;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
    margin-top: 20px;
}

.checkout:hover {
    background-color: #388E3C;
}

</style>
<body>

<h2>Your Cart</h2>

<table>
    <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['price']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td><?php echo $row['total']; ?></td>
        <td>
            <!-- Form to update quantity or remove from cart -->
            <form action="update_cart.php" method="POST">
                <input type="hidden" name="cart_id" value="<?php echo $row['cid']; ?>">
                <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1">
                <input type="submit" value="Update Quantity">
            </form>
            <form action="remove_from_cart.php" method="POST">
                <input type="hidden" name="cart_id" value="<?php echo $row['cid']; ?>">
                <input type="submit" value="Remove">
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a class="checkout" href="checkout.php">Proceed to Checkout </a>
</body>
<footer>
        <p>&copy; 2024 MediCare. All rights reserved.</p>
    </footer>

</html>
