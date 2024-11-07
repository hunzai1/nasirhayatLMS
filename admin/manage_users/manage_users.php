<?php
// manage_users.php

// Start the session to verify if the admin is logged in
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to admin login page if not logged in
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

// Function to fetch students by class
function fetchStudentsByClass($conn, $class) {
    $sql = "SELECT * FROM students WHERE class = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $class);
    $stmt->execute();
    return $stmt->get_result();
}

// Fetch students by class
$students_9th = fetchStudentsByClass($conn, '9th');
$students_10th = fetchStudentsByClass($conn, '10th');
$students_11th = fetchStudentsByClass($conn, '11th');
$students_12th = fetchStudentsByClass($conn, '12th');

// Fetch all teachers
$sql_teachers = "SELECT * FROM users";
$result_teachers = $conn->query($sql_teachers);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 20px;
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 40px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .submit-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            text-decoration: none;
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
        .action-links a {
            color: #007bff;
            text-decoration: none;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Manage Students and Teachers</h2>

<!-- Section for 9th Class Students -->
<h3>9th Class Students</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($students_9th->num_rows > 0) {
            while ($student = $students_9th->fetch_assoc()) {
                echo "<tr>
                        <td>{$student['id']}</td>
                        <td>{$student['name']}</td>
                        <td>{$student['email']}</td>
                        <td>{$student['gender']}</td>
                        <td class='action-links'>
                            <a href='edit_user.php?id={$student['id']}'>Edit</a> | 
                            <a href='delete_user.php?id={$student['id']}'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No students found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Section for 10th Class Students -->
<h3>10th Class Students</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($students_10th->num_rows > 0) {
            while ($student = $students_10th->fetch_assoc()) {
                echo "<tr>
                        <td>{$student['id']}</td>
                        <td>{$student['name']}</td>
                        <td>{$student['email']}</td>
                        <td>{$student['gender']}</td>
                        <td class='action-links'>
                            <a href='edit_user.php?id={$student['id']}'>Edit</a> | 
                            <a href='delete_user.php?id={$student['id']}'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No students found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Section for 11th Class Students -->
<h3>11th Class Students</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($students_11th->num_rows > 0) {
            while ($student = $students_11th->fetch_assoc()) {
                echo "<tr>
                        <td>{$student['id']}</td>
                        <td>{$student['name']}</td>
                        <td>{$student['email']}</td>
                        <td>{$student['gender']}</td>
                        <td class='action-links'>
                            <a href='edit_user.php?id={$student['id']}'>Edit</a> | 
                            <a href='delete_user.php?id={$student['id']}'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No students found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Section for 12th Class Students -->
<h3>12th Class Students</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($students_12th->num_rows > 0) {
            while ($student = $students_12th->fetch_assoc()) {
                echo "<tr>
                        <td>{$student['id']}</td>
                        <td>{$student['name']}</td>
                        <td>{$student['email']}</td>
                        <td>{$student['gender']}</td>
                        <td class='action-links'>
                            <a href='edit_user.php?id={$student['id']}'>Edit</a> | 
                            <a href='delete_user.php?id={$student['id']}'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No students found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Section for Teachers -->
<h3>Teachers</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result_teachers->num_rows > 0) {
            while ($teacher = $result_teachers->fetch_assoc()) {
                echo "<tr>
                        <td>{$teacher['id']}</td>
                        <td>{$teacher['name']}</td>
                        <td>{$teacher['email']}</td>
                        <td>{$teacher['gender']}</td>
                        <td class='action-links'>
                            <a href='edit_teacher.php?id={$teacher['id']}'>Edit</a> | 
                            <a href='delete_teacher.php?id={$teacher['id']}'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No teachers found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<a href="../admin_dashboard.php" class="submit-button">Back to Dashboard</a>

</body>
</html>
