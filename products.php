
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
    
    // Handle image upload
    if (isset($_FILES['images']) && $_FILES['images']['error'] == 0) {
        $image = $_FILES['images'];
        $imagePath = 'images/' . basename($image['name']);  // Set the path where the image will be saved
        move_uploaded_file($image['tmp_name'], $imagePath);  // Move the uploaded file to the server
    } else {
        $imagePath = '';  // If no image, set empty
    }
    
    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO products (name, price, stock, category, discounted_price, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sdisss', $name, $price, $stock, $category, $discount, $imagePath);
    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Error adding product: " . $stmt->error;
    }
}

// Handle Delete Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $productId = $_POST['id'];
    
    // Delete the product from the database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $productId);
    if ($stmt->execute()) {
        echo "Product deleted successfully!";
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
}

// Handle Edit Product (Retrieve the product data to pre-fill the form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $productId = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $productToEdit = $result->fetch_assoc();
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

    // Handle image upload (if new image is uploaded)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imagePath = 'images/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);
    } else {
        // If no new image uploaded, keep the old image path
        $imagePath = $productToEdit['image'];
    }

    // Update the product in the database
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, stock = ?, category = ?, discounted_price = ?, image = ? WHERE id = ?");
    $stmt->bind_param('sdisssi', $name, $price, $stock, $category, $discount, $imagePath, $id);
    
    if ($stmt->execute()) {
        echo "Product updated successfully!";
        header('Location: products.php');  // Redirect after update to avoid form resubmission
        exit;
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}

// Fetch products for display
$products = [];
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
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
            height:130px;
        }
        nav{
            background-color:#333;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            /* display: inline; */
            /* margin-right: 20px; */
        }
        nav ul li a {
            color: white;
            text-decoration: none;
        }
        section {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #444;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        button.delete {
            background-color: #dc3545;
        }
        button.delete:hover {
            background-color: #c82333;
        }
        img {
            max-width: 80px;
            height: auto;
            border-radius: 5px;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, select {
            width: calc(100% - 16px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #444;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<nav>
<header>
    <h1>Product Management</h1>
        <ul>
            <li><a href="index2.php">Dashboard</a></li>
            <li><a href="products.php">Product Management</a></li>
            <li><a href="stock.php">Stock Management</a></li>
            <li><a href="staff.php">Staff Management</a></li>
            <li><a href="order.html">Order Management</a></li>
        </ul>
</header>
</nav>
<section id="product">
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
<footer>
    <p>&copy; 2024 Admin Dashboard</p>
</footer>
</body>
</html>
