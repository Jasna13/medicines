<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Medico</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login to Your Account</h2>
        <form action="" method="post">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-button" name="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </div>
</body>
</html>
<?php
session_start(); // Start the session
include("connection.php"); // Include the connection file

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statements to avoid SQL injection
    $stmt = $con->prepare("SELECT * FROM user WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password); // Bind email and password
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;

    if ($num > 0) {
        $row = $result->fetch_assoc();  // Fetch user data
        $utype = $row['utype'];  // Get the user type
        $uid = $row['uid']; // Get the user ID

        // Set session variables
        $_SESSION['uid'] = $uid;
        session_regenerate_id(true); // Regenerate session ID for security

        // Redirect based on user type
        if ($utype = 'user') {
            error_log("Redirecting to user dashboard");
            header("Location: http://localhost/MINI%20PROJECT/userdashboard/index.php"); // Redirect to user page
            exit();
        } elseif ($utype == 'staff') {
            error_log("Redirecting to staff dashboard");
            header("Location: http://localhost/MINI%20PROJECT/StaffDashboard/staffindex.html"); // Redirect to staff page
            exit();
        } elseif ($utype == 'admin') {
            error_log("Redirecting to admin dashboard");
            header("Location: admin_dashboard.php"); // Redirect to admin page
            exit();
        }
    } else {
        error_log("Login failed: incorrect email or password");
        echo '<script>alert("Email ID or password is incorrect")</script>';
    }
}
?>
