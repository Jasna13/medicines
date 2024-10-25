<?php
session_start();
$con = mysqli_connect("localhost", "root", "root", "medico_shop");

// Get user ID from session
$uid = $_SESSION['uid'];

// Get data from the form
$name = $_POST['name'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$payment_method = $_POST['payment_method'];
$total_amount = $_POST['total_amount'];

// Insert the order into the orders table
$query = "INSERT INTO orders (uid, name, address, contact, total_amount, payment_method) 
          VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($query);
$stmt->bind_param("isssds", $uid, $name, $address, $contact, $total_amount, $payment_method);
$stmt->execute();

// Get the last inserted order ID
$order_id = $stmt->insert_id;

// Insert each cart item into the order_items table
$query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
          SELECT ?, cart.id, cart.quantity, products.price
          FROM cart
          INNER JOIN products ON cart.id = products.id
          WHERE cart.uid = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("ii", $order_id, $uid);
$stmt->execute();

// Clear the user's cart
$query = "DELETE FROM cart WHERE uid = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $uid);
$stmt->execute();

// Redirect to confirmation page
header("Location: order_confirmation.php?order_id=" . $order_id);
exit();
?>
