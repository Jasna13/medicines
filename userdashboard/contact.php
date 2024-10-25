<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "medico_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert form data into the database
    $sql = "INSERT INTO contact (user_id, name, email, message) VALUES (NULL, '$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Message sent successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - MediCare</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
</head>
<body>
  <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f7f6;
    color: #333;
    padding: 20px;
}

.contact-container {
    max-width: 600px;
    margin: 50px auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 2em;
    color: #4CAF50;
    text-align: center;
    margin-bottom: 20px;
}

p {
    text-align: center;
    font-size: 1.1em;
    margin-bottom: 30px;
    color: #777;
}

.contact-form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

.form-group input, 
.form-group textarea {
    width: 100%;
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.form-group input:focus, 
.form-group textarea:focus {
    border-color: #4CAF50;
    outline: none;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
}

.submit-btn {
    padding: 15px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1.2em;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #45a049;
}
/* Same as before */

.success {
    color: #4CAF50;
    font-weight: bold;
    margin-bottom: 20px;
}

.error {
    color: red;
    font-weight: bold;
    margin-bottom: 20px;
}

  </style>

    <div class="contact-container">
        <h1>Contact Us</h1>
        <p>If you have any questions or concerns, please feel free to reach out to us using the form below.</p>

        <form action="#" method="post" class="contact-form">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>

            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>

</body>
</html>
