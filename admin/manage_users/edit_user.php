<?php
// edit_user.php

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

// Fetch user data for the given ID
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "<script>alert('User not found'); window.location.href='manage_users.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request'); window.location.href='manage_users.php';</script>";
    exit;
}

// Update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update the user in the database
    $updateStmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
    $updateStmt->bind_param("sssi", $name, $email, $role, $userId);

    if ($updateStmt->execute()) {
        echo "<script>alert('User updated successfully'); window.location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('Error updating user: " . $updateStmt->error . "');</script>";
    }
    $updateStmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-input {
            padding: 10px;
            margin: 5px;
            width: 200px;
        }
        .submit-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Edit User</h2>

<form action="" method="POST">
    <input type="text" name="name" placeholder="Name" class="form-input" value="<?php echo htmlspecialchars($user['name']); ?>" required>
    <input type="email" name="email" placeholder="Email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    <select name="role" class="form-input" required>
        <option value="student" <?php echo ($user['role'] === 'student') ? 'selected' : ''; ?>>Student</option>
        <option value="teacher" <?php echo ($user['role'] === 'teacher') ? 'selected' : ''; ?>>Teacher</option>
    </select>
    <button type="submit" name="update_user" class="submit-button">Update User</button>
</form>

<a href="manage_users.php" class="submit-button" style="margin-top: 20px; display: block; width: 200px; margin: auto;">Back to Manage Users</a>

</body>
</html>
