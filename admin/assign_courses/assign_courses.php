<?php
// Start the session to verify if admin is logged in
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.html");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP MySQL password
$dbname = "my_lms";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<div class='message'>Connection failed: " . $conn->connect_error . "</div>");
}

// Handle course assignment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign_course'])) {
    $teacher_id = intval($_POST['teacher_id']);
    $course_id = intval($_POST['course_id']);
    $class_id = intval($_POST['class_id']);

    // Insert the assignment into the assign_course table
    $stmt = $conn->prepare("INSERT INTO assign_course (course_id, teacher_id, class_id) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $course_id, $teacher_id, $class_id);

    if ($stmt->execute()) {
        echo "<script>alert('Course assigned successfully'); window.location.href='assign_courses.php';</script>";
    } else {
        echo "<script>alert('Error assigning course: " . $stmt->error . "');</script>";
    }
    $stmt->close();
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

// Fetch all teachers
$teachers = [];
$teacher_result = $conn->query("SELECT teacher_id, teacher_name FROM teachers");
if ($teacher_result->num_rows > 0) {
    while ($row = $teacher_result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

// Fetch all courses
$courses = [];
$course_result = $conn->query("SELECT course_id, course_name FROM courses");
if ($course_result->num_rows > 0) {
    while ($row = $course_result->fetch_assoc()) {
        $courses[] = $row;
    }
}

// Fetch all classes
$classes = [];
$class_result = $conn->query("SELECT class_id, class_name FROM classes");
if ($class_result->num_rows > 0) {
    while ($row = $class_result->fetch_assoc()) {
        $classes[] = $row;
    }
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
    <title>Assign Courses to Teachers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            width: 80%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group select {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-button:hover {
            background-color: #0056b3;
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
    <h2>Assign Courses to Teachers</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="teacher">Select Teacher:</label>
            <select name="teacher_id" required>
                <option value="">Select a Teacher</option>
                <?php foreach ($teachers as $teacher): ?>
                    <option value="<?php echo $teacher['teacher_id']; ?>">
                        <?php echo htmlspecialchars($teacher['teacher_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="course">Select Course:</label>
            <select name="course_id" required>
                <option value="">Select a Course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['course_id']; ?>">
                        <?php echo htmlspecialchars($course['course_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="class">Select Class:</label>
            <select name="class_id" required>
                <option value="">Select a Class</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo $class['class_id']; ?>">
                        <?php echo htmlspecialchars($class['class_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="assign_course" class="submit-button">Assign Course</button>
    </form>
</div>

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
                            <a href='delete_assign_course.php?delete_course_id=" . urlencode($row['course_id']) . "' class='delete-btn'>Delete</a>
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
