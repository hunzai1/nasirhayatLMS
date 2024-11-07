<?php
// admin_dashboard.php

// Start the session to verify if admin is logged in
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to admin login page if not logged in
    header("Location: admin_login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('path/to/background-image.jpg'); /* Add your background image path */
            background-size: cover;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard-container {
            width: 100%;
            max-width: 800px;
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent background */
            padding: 30px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            text-align: center;
        }
        .dashboard-header {
            margin-bottom: 20px;
        }
        .dashboard-header h2 {
            color: #333;
            margin: 0;
            font-size: 2.5em; /* Larger font size */
        }
        .dashboard-header p {
            color: #666;
            font-size: 1.2em; /* Larger font size */
            margin-top: 10px;
        }
        .dashboard-menu {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .menu-item {
            text-align: center;
            padding: 15px;
            border: none;
            border-radius: 5px;
            width: 30%;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            font-size: 1.2em; /* Larger font size */
            transition: background-color 0.3s, transform 0.3s; /* Add transform for hover */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        }
        .menu-item:hover {
            background-color: #0056b3;
            transform: translateY(-5px); /* Slight upward movement on hover */
        }
        .logout {
            display: block;
            margin-top: 30px;
            font-size: 1.1em; /* Larger font size */
            color: #dc3545; /* Red color for logout */
            text-decoration: none;
        }
        .logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h2>Welcome to Admin Dashboard</h2>
        <p>Manage the LMS for Hasegawa Memorial Public School</p>
    </div>

    <div class="dashboard-menu">
        <a href="manage_users/manage_users.php" class="menu-item">Manage Users</a>
        <a href="manage_courses/manage_courses.php" class="menu-item">Manage Courses</a>
        <a href="assign_courses/assign_courses.php" class="menu-item">Assign Courses to Teachers</a>
    </div>

    <a href="logout.php" class="logout">Logout</a>
</div>

</body>
</html>
