<?php
session_start();
$con = mysqli_connect("localhost", "root", "root", "medico_shop");

// Get user ID from session
$uid = $_SESSION['uid'];

// Fetch cart items for this user
$query = "SELECT products.name, products.price, cart.quantity, (products.price * cart.quantity) AS total
          FROM cart
          INNER JOIN products ON cart.id = products.id
          WHERE cart.uid = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total amount
$total_amount = 0;
while ($row = $result->fetch_assoc()) {
    $total_amount += $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Checkout</h2>

<!-- Display Cart Summary -->
<h3>Your Order Summary</h3>
<table>
    <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>

    <?php
    $result->data_seek(0); // Reset the result pointer to loop again
    while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo $row['total']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<h3>Total Amount: $<?php echo number_format($total_amount, 2); ?></h3>

<!-- Billing and Shipping Form -->
<h3>Billing and Shipping Information</h3>
<form action="place_order.php" method="POST">
    <label for="name">Full Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="address">Shipping Address:</label>
    <textarea id="address" name="address" required></textarea>

    <label for="contact">Contact Number:</label>
    <input type="text" id="contact" name="contact" required>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method" required>
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
    </select>

    <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">

    <input type="submit" value="Place Order">
</form>

</body>
</html>
