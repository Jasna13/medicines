<?php
$con=mysqli_connect("localhost","root","root","medico_shop");

$cart_id = $_POST['cart_id'];
$quantity = $_POST['quantity'];

$update_query = "UPDATE cart SET quantity = ? WHERE cid = ?";
$stmt = $con->prepare($update_query);
$stmt->bind_param("ii", $quantity, $cart_id);
$stmt->execute();

header("Location: cart.php");
exit();
?>
