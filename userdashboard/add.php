<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "medico_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID and quantity from the form
$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Assuming user_id is stored in session
$userId = $_SESSION['uid'] ?? 1; // Use the session user id, fallback to 1 if not set

// Check if the product is already in the cart for the current user
$sql_check = "SELECT * FROM cart WHERE uid = ? AND id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $userId, $productId);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // If product already exists in the cart, update the quantity
    $sql_update = "UPDATE cart SET quantity = quantity + ?, added_at = NOW() WHERE uid = ? AND id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("iii", $quantity, $userId, $productId);
    $stmt_update->execute();
    $_SESSION['message'] = "Product quantity updated in the cart!";
} else {
    // If product is not in the cart, insert new record
    $sql_insert = "INSERT INTO cart (uid, id, quantity, added_at) VALUES (?, ?, ?, NOW())";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iii", $userId, $productId, $quantity);
    $stmt_insert->execute();
    $_SESSION['message'] = "Product added to the cart!";
}

// Redirect back to the product page or cart page
// header("Location:Add_to_cart.html"); // Or redirect to the product page if you prefer
exit;
?>
