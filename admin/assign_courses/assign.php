<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update if necessary
$dbname = "my_lms";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<div class='message'>Connection failed: " . $conn->connect_error . "</div>");
}

// Handle deletion of an assigned course
if (isset($_GET['delete_course_id'])) {
    $delete_course_id = $_GET['delete_course_id'];

    $deleteStmt = $conn->prepare("DELETE FROM assign_course WHERE course_id = ?");
    $deleteStmt->bind_param("i", $delete_course_id);

    if ($deleteStmt->execute()) {
        echo "<div class='message'>Assigned course deleted successfully!</div>";
    } else {
        echo "<div class='message'>Error: " . $deleteStmt->error . "</div>";
    }
    $deleteStmt->close();
}

// Retrieve assigned courses data with JOINs to get names based on IDs
$query = "
    SELECT 
        assign_course.course_id, 
        courses.course_name, 
        classes.class_name, 
        teachers.teacher_name
    FROM assign_course
    JOIN courses ON assign_course.course_id = courses.course_id
    JOIN classes ON assign_course.class_id = classes.class_id
    JOIN teachers ON assign_course.teacher_id = teachers.teacher_id
";

$assignResult = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Assigned Courses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f4f4f9;
        }
        .container {
            width: 80%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .message {
            text-align: center;
            margin: 10px 0;
            color: #333;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .edit-btn, .delete-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
        }
        .edit-btn {
            background-color: #4CAF50;
        }
        .edit-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .delete-btn:hover {
            background-color: #d73925;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Assigned Courses</h2>
        <table>
            <tr>
                <th>Course Name</th>
                <th>Class Name</th>
                <th>Assigned Teacher</th>
                <th>Actions</th>
            </tr>

            <?php
            // Display each assigned course with Edit and Delete options
            if ($assignResult->num_rows > 0) {
                while ($row = $assignResult->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['course_name']) . "</td>
                            <td>" . htmlspecialchars($row['class_name']) . "</td>
                            <td>" . htmlspecialchars($row['teacher_name']) . "</td>
                            <td>
                                <a href='edit_assign_course.php?course_id=" . urlencode($row['course_id']) . "' class='edit-btn'>Edit</a>
                                <a href='assign_courses.php?delete_course_id=" . urlencode($row['course_id']) . "' class='delete-btn'>Delete</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No assigned courses available.</td></tr>";
            }

            // Close the database connection
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
