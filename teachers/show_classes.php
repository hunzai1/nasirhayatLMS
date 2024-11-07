<?php
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit;
}

// Display teacher's name from session
$teacher_id = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_lms";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch assigned classes, courses, and student counts
$sql = "SELECT classes.class_name, courses.course_name, 
               classes.total_students, classes.girls_total, classes.boys_total 
        FROM assign_course 
        INNER JOIN classes ON assign_course.class_id = classes.class_id
        INNER JOIN courses ON assign_course.course_id = courses.course_id
        WHERE assign_course.teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}
$stmt->close();
$conn->close();
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard-container {
            width: 100%;
            max-width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
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
    
    <!-- Assigned Courses and Classes Table -->
    <h3>Your Assigned Courses and Classes</h3>
    <?php if (!empty($assignments)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Course</th>
                    <th>Total Students</th>
                    <th>Girls</th>
                    <th>Boys</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignments as $assignment) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($assignment['class_name']); ?></td>
                        <td><?php echo htmlspecialchars($assignment['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($assignment['total_students']); ?></td>
                        <td><?php echo htmlspecialchars($assignment['girls_total']); ?></td>
                        <td><?php echo htmlspecialchars($assignment['boys_total']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No classes or courses assigned.</p>
    <?php endif; ?>
    
    <a href="logout.php" class="button logout-button">Logout</a>
</div>

</body>
</html>
