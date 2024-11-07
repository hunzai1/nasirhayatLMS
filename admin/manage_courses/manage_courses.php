<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = ""; // Update this if needed
    $dbname = "my_lms";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("<div class='message'>Connection failed: " . $conn->connect_error . "</div>");
    }

    // Retrieve and sanitize form data
    $course_name = isset($_POST['course_name']) ? trim($_POST['course_name']) : '';
    $course_des = isset($_POST['course_des']) ? trim($_POST['course_des']) : '';

    if (!empty($course_name) && !empty($course_des)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO courses (course_name, course_des) VALUES (?, ?)");
        $stmt->bind_param("ss", $course_name, $course_des);

        // Execute and provide feedback
        if ($stmt->execute()) {
            echo "<div class='message'>Course added successfully!</div>";
        } else {
            echo "<div class='message'>Error: " . $stmt->error . "</div>";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "<div class='message'>Please fill in all fields.</div>";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
    <style>
        /* Styling (as in previous examples) */
        /* Styles for form, messages, and buttons */
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Course</h2>
        <form action="manage_course.php" method="post">
            <label for="course_name">Course Name:</label>
            <input type="text" id="course_name" name="course_name" required>

            <label for="course_des">Course Description:</label>
            <textarea id="course_des" name="course_des" required></textarea>

            <input type="submit" value="Add Course">
        </form>
    </div>

    <?php
    // Display courses
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("<div class='message'>Connection failed: " . $conn->connect_error . "</div>");
    }

    $result = $conn->query("SELECT * FROM courses");
    if ($result->num_rows > 0) {
        echo "<div class='container'><h2>Course List</h2><table><tr><th>Course Name</th><th>Description</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['course_name'] . "</td>
                    <td>" . $row['course_des'] . "</td>
                    <td>
                        <a href='edit_course.php?id=" . $row['course_id'] . "' class='edit-btn'>Edit</a>
                        <a href='manage_course.php?delete_id=" . $row['course_id'] . "' class='delete-btn'>Delete</a>
                    </td>
                </tr>";
        }
        echo "</table></div>";
    } else {
        echo "<div class='message'>No courses available.</div>";
    }

    // Handle course deletion
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $deleteStmt = $conn->prepare("DELETE FROM courses WHERE course_id = ?");
        $deleteStmt->bind_param("i", $delete_id);
        if ($deleteStmt->execute()) {
            echo "<div class='message'>Course deleted successfully!</div>";
        } else {
            echo "<div class='message'>Error: " . $deleteStmt->error . "</div>";
        }
        $deleteStmt->close();
    }

    $conn->close();
    ?>
</body>
</html>
