<?php
session_start(); // Start session for user cart or order handling

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

// Get product ID and quantity from the URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

if ($productId > 0 && $quantity > 0) {
    // Fetch product details to process the order
    $sql = "SELECT * FROM products WHERE id = $productId";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();

    if ($product) {
        // Display order summary in a form
        $finalPrice = $product['discounted_price'] > 0 ? $product['price'] - $product['discounted_price'] : $product['price'];
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Order Summary | MediCare</title>
            <link rel="stylesheet" href="styles.css">
            <style>
                /* General Reset */
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: Arial, sans-serif;
                }

                /* Header Styling */
                header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    background-color:#06782c;
                    padding: 15px;
                    color: white;
                }

                /* Logo Styling */
                .logo h1 {
                    font-size: 24px;
                }

                /* Navigation Styling */
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

                /* Order Summary Styling */
                main {
                    max-width: 900px;
                    margin: 20px auto;
                    padding: 20px;
                    background-color: #f9f9f9;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }

                /* Button Styling */
                button {
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    font-size: 16px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                    background-color: #28a745;
                    color: white;
                }

                button:hover {
                    background-color: #218838;
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
            </style>
        </head>
        <body>
            <header>
                <h1 class="logo">MediCare</h1>
                <nav>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="products.html">Products</a></li>
                        <li><a href="contact.html">Contact</a></li>
                        <li><a href="Add_to_cart.html">Add to Cart</a></li>
                    </ul>
                </nav>
            </header>

            <main>
                <h2>Order Summary</h2>
                <form action="place_order.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES); ?>">
                    <input type="hidden" name="quantity" value="<?php echo htmlspecialchars($quantity, ENT_QUOTES); ?>">
                    
                    <p>Product: <strong><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?></strong></p>
                    <p>Quantity: <strong><?php echo htmlspecialchars($quantity, ENT_QUOTES); ?></strong></p>
                    <p>Total Price: <strong>$<?php echo number_format($finalPrice* $quantity, 2); ?></strong></p>
                    
                    <div>
                        <label for="shipping_address">Shipping Address:</label>
                        <textarea id="shipping_address" name="shipping_address" required></textarea>
                    </div>
                    <div>
                        <label for="contact_number">Contact Number:</label>
                        <input type="tel" id="contact_number" name="contact_number" required>
                    </div>
                    
                    <button type="submit" class="btn place-order">Place Order</button>
                </form>
            </main>

            <footer>
                <p>&copy; 2024 MediCare. All rights reserved.</p>
            </footer>
        </body>
        </html>
        <?php
    } else {
        echo "Product not found!";
    }
} else {
    echo "Invalid product ID or quantity!";
}

$conn->close();
?>
