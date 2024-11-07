<?php
// edit_course.php

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

// Fetch course data for the given ID
if (isset($_GET['id'])) {
    $courseId = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();

    if (!$course) {
        echo "<script>alert('Course not found'); window.location.href='manage_courses.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request'); window.location.href='manage_courses.php';</script>";
    exit;
}

// Update course data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_course'])) {
    $courseName = $_POST['course_name'];
    $courseDescription = $_POST['course_description'];

    // Update the course in the database
    $updateStmt = $conn->prepare("UPDATE courses SET name = ?, description = ? WHERE id = ?");
    $updateStmt->bind_param("ssi", $courseName, $courseDescription, $courseId);

    if ($updateStmt->execute()) {
        echo "<script>alert('Course updated successfully'); window.location.href='manage_courses.php';</script>";
    } else {
        echo "<script>alert('Error updating course: " . $updateStmt->error . "');</script>";
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
    <title>Edit Course</title>
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

<h2>Edit Course</h2>

<form action="" method="POST">
    <input type="text" name="course_name" placeholder="Course Name" class="form-input" value="<?php echo htmlspecialchars($course['name']); ?>" required>
    <input type="text" name="course_description" placeholder="Course Description" class="form-input" value="<?php echo htmlspecialchars($course['description']); ?>" required>
    <button type="submit" name="update_course" class="submit-button">Update Course</button>
</form>

<a href="manage_courses.php" class="submit-button" style="margin-top: 20px; display: block; width: 200px;">Back to Manage Courses</a>

</body>
</html>
