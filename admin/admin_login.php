<?php
// admin_login.php

// Predefined admin credentials
$adminUsername = "admin";
$adminPassword = "nasir123@";

// Get the posted form data
$username = $_POST['username'];
$password = $_POST['password'];

// Validate login
if ($username === $adminUsername && $password === $adminPassword) {
    // Start a session and redirect to the admin dashboard
    session_start();
    $_SESSION['admin_logged_in'] = true;
    header("Location: admin_dashboard.php");
    exit;
} else {
    // If login fails, show an error message
    echo "<script>alert('Invalid credentials'); window.location.href='admin_login.html';</script>";
}
?>
