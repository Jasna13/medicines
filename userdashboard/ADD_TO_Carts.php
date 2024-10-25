<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Cart</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS file -->
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
<body>

<h2>Product Details</h2>

<!-- Sample product display -->
<div class="product">
    <img src="product_image_url" alt="Product Image">
    <h3>Product Name</h3>
    <p>Price: $product_price</p>

    <form action="add_to_cart_action.php" method="POST">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" value="1" min="1">
        <input type="submit" value="Add to Cart">
    </form>
</div>

</body>
</html>
