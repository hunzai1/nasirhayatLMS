<?php
// Database connection setup
$servername = "localhost";  // Typically localhost with XAMPP
$username = "root";         // Default username for XAMPP MySQL
$password = "";             // Default password for XAMPP MySQL is empty
$dbname = "my_lms";         // Updated to use your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);  // Hashing password for security

    // Insert data into the admin table
    $sql = "INSERT INTO admin (admin_name, admin_email, admin_password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $admin_name, $admin_email, $admin_password);

    if ($stmt->execute()) {
        echo "Admin registered successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
