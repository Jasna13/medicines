<?php
session_start();

// Ensure a proper database connection is established
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "medico_shop";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure POST request and necessary values are provided
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $userId = $_SESSION['uid']; // Assuming you are storing user ID in session

    // Prepare the SQL statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO cart (uid,id, quantity, added_at) VALUES (?, ?, ?, NOW())");
    
    if ($stmt) {
        // Bind parameters (user ID, product ID, and quantity)
        $stmt->bind_param('iii', $userId, $productId, $quantity);

        // Execute the statement
        if ($stmt->execute()) {
            // Success message, redirect back or show alert
            $_SESSION['message'] = "Product added to cart successfully!";
            header("Location: cart.php");
            exit;
        } else {
            // Error handling if execution fails
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing SQL: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}

// Close the connection
$conn->close();
?>
