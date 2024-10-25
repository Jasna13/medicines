<?php
session_start();
// Assuming login logic here
// After successful login:
$_SESSION['uid'] = $uid; // Set this to the user's ID or username
header("Location:http://localhost/MINI%20PROJECT/userdashboard/index.php"); // Redirect to the main page
exit;
?>
