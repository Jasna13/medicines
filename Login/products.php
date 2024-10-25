<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'medico_shop');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$productToEdit = null;

// Function to convert image to Base64
function imageToBase64($imageFile) {
    $imageData = file_get_contents($imageFile);
    return 'data:' . mime_content_type($imageFile) . ';base64,' . base64_encode($imageData);
}

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $discount = $_POST['discount'];

    // Validate that the discounted price is not greater than the actual price
    if ($discount > $price) {
        echo "Error: Discounted price cannot be greater than the actual price.";
        exit;
    }

    // Handle file upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        // Allowed image types
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES["image"]["type"], $allowedTypes)) {
            // Convert image to base64
            $imageBase64 = imageToBase64($_FILES["image"]["tmp_name"]);

            // Prepare and execute the insert query
            $stmt = $conn->prepare("INSERT INTO products (name, price, stock, category, discounted_price, image) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdiiss", $name, $price, $stock, $category, $discount, $imageBase64);
            if ($stmt->execute()) {
                echo "<script>alert('Product added successfully');</script>";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Error: Only image files (jpeg, png, gif) are allowed.";
        }
    } else {
        echo "Error: File upload error.";
    }
}

// Handle Delete Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully');</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle Edit Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $productToEdit = $result->fetch_assoc(); // Product data to edit
    }
}

// Handle Update Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $discount = $_POST['discount'];

    // Validate discounted price
    if ($discount > $price) {
        echo "Error: Discounted price cannot be greater than the actual price.";
        exit;
    }

    // Check if a new image is uploaded
    if (!empty($_FILES["image"]["name"])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES["image"]["type"], $allowedTypes)) {
            // Convert image to base64
            $imageBase64 = imageToBase64($_FILES["image"]["tmp_name"]);

            // Update query with new image
            $stmt = $conn->prepare("UPDATE products SET name=?, price=?, stock=?, category=?, discounted_price=?, image=? WHERE id=?");
            $stmt->bind_param("sdiissi", $name, $price, $stock, $category, $discount, $imageBase64, $id);
        } else {
            echo "Error: Only image files (jpeg, png, gif) are allowed.";
            exit;
        }
    } else {
        // Update query without image
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, stock=?, category=?, discounted_price=? WHERE id=?");
        $stmt->bind_param("sdiisi", $name, $price, $stock, $category, $discount, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully');</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$products = array();
$result = $conn->query("SELECT * FROM products");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
        }
        section {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        button {
            background-color: #444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius:5px;
            margin-right:10px;
            cursor: pointer;
        }
        .button:hover {
            background-color:#666;
        }
        button.update {
            background-color: #337ab7;
        }
        button.delete {
            background-color: #d9534f;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<header>
    <h1>Product Management</h1>
    <nav>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="products.php">Product Management</a></li>
            <li><a href="stock.php">Stock Management</a></li>
            <li><a href="staff.php">Staff Management</a></li>
            <li><a href="order.html">Order Management</a></li>
        </ul>
    </nav>
</header>
<section id="product" class="panel">
    <h2>Manage Products</h2>

    <form id="product-form" method="POST" enctype="multipart/form-data">
        <?php if ($productToEdit): ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $productToEdit['id'] ?>">
        <?php else: ?>
            <input type="hidden" name="action" value="add">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="product-name">Product Name:</label>
            <input type="text" id="product-name" name="name" value="<?= $productToEdit['name'] ?? '' ?>" required>
        </div>
        <div class="form-group">
            <label for="product-price">Product Price:</label>
            <input type="number" id="product-price" name="price" value="<?= $productToEdit['price'] ?? '' ?>" required>
        </div>
        <div class="form-group">
            <label for="product-stock">Stock Quantity:</label>
            <input type="number" id="product-stock" name="stock" value="<?= $productToEdit['stock'] ?? '' ?>" required>
        </div>
        <div class="form-group">
            <label for="product-category">Category:</label>
            <input type="text" id="product-category" name="category" value="<?= $productToEdit['category'] ?? '' ?>" required>
        </div>
        <div class="form-group">
            <label for="product-discount">Discounted Price:</label>
            <input type="number" id="product-discount" name="discount" value="<?= $productToEdit['discounted_price'] ?? '' ?>" required>
        </div>
        <div class="form-group">
            <label for="product-image">Product Image:</label>
            <input type="file" id="product-image" name="image">
        </div>
        
        <button type="submit"><?= $productToEdit ? 'Update Product' : 'Add Product' ?></button>
    </form>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Discounted Price</th>
            <th>Image</th>
            <th>Final Price</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= number_format($product['price'], 2) ?></td>
                <td><?= $product['stock'] ?></td>
                <td><?= htmlspecialchars($product['category']) ?></td>
                <td><?= number_format($product['discounted_price'], 2) ?></td>
                <td><img src="<?= $product['image'] ?>" alt="Product Image"></td>
                <td>
                    <?php 
                        $final_price = $product['price'] - $product['discounted_price'];
                        echo number_format($final_price, 2);
                    ?>
                </td>
                <td>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <button type="submit" class="update">Edit</button>
                    </form>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <button type="submit" class="delete" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
</body>
<footer>
        <p>&copy; 2024 Admin Dashboard</p>
    </footer>
</html> 