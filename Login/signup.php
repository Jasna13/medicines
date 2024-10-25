<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Medico Online Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sign-up-container">
        <h2>Create Your Account</h2>
        <form action=" " method="post">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="phone_number">Phone Number</label>
                <input type="number" id="phone_number" name="phone_number" min="1000000000" max="9999999999" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="input-group">
                <label for="role">Role: </label>
                <select class="form-control" name="role">
                    <option>User</option>
                </select>
            </div>
            <button type="submit" class="sign-up-button" name="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
<?php 
include("connection.php");

if (isset($_POST['submit'])) {
    $uname = $_POST['username'];
    $email = $_POST['email'];
    $phno = $_POST['phone_number'];
    $password = $_POST['password'];
    $cpwd =$_POST['confirm_password'];
    $role=$_POST['role'];

    if ($password === $cpwd) {
        // Insert into the database
        $sql = "INSERT INTO user (username, email, phone_number, password,utype) VALUES ('$uname', '$email', '$phno', '$password','$role')";
        $result = mysqli_query($con, $sql);

        if ($result)if ($result) {
            // Use JavaScript to show an alert and redirect
            echo '<script>
                alert("Successfully registered, now you can login");
                window.location.href = "login.php";
                </script>';
    
        } else {
            echo '<script>alert("Failed to insert")</script>';
        }
    } else {
        echo '<script>alert("Passwords do not match")</script>';
    }
}
?>