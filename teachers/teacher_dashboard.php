<?php
// Start the session
session_start();

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    // Redirect to teacher login page if not logged in
    header("Location: teacher_login.php");
    exit;
}

// Display teacher's name from session
$teacher_name = $_SESSION['teacher_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard-container {
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .welcome-message {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .logout-button {
            background-color: #dc3545;
        }
        .logout-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>Teacher Dashboard</h2>
    <p class="welcome-message">Welcome, <?php echo htmlspecialchars($teacher_name); ?>!</p>
    
    <a href="show_classes.php" class="button">show classes</a>
    <a href="manage_courses.php" class="button">Manage Courses</a>
    <a href="manage_assignments.php" class="button">Manage Assignments</a>

    <a href="logout.php" class="button logout-button">Logout</a>
</div>

</body>
</html>
