<?php
// delete_course.php

// Start the session to verify if admin is logged in
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.html");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP MySQL username
$password = ""; // Default XAMPP MySQL password
$dbname = "lms"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete course
if (isset($_GET['id'])) {
    $courseId = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $courseId);

    if ($stmt->execute()) {
        echo "<script>alert('Course deleted successfully'); window.location.href='manage_courses.php';</script>";
    } else {
        echo "<script>alert('Error deleting course: " . $stmt->error . "'); window.location.href='manage_courses.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Invalid request'); window.location.href='manage_courses.php';</script>";
}

// Close the connection
$conn->close();
?>
