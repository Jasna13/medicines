<?php
$con=mysqli_connect("localhost","root","root","medico_shop");

$cart_id = $_POST['cart_id'];

$delete_query = "DELETE FROM cart WHERE cid = ?";
$stmt = $con->prepare($delete_query);
$stmt->bind_param("i", $cart_id);
$stmt->execute();

header("Location: cart.php");
exit();
?>
