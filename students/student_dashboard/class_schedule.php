<?php
session_start();

if (!isset($_SESSION['students_id'])) {
    header("Location: student_login.php");
    exit();
}

$students_id = $_SESSION['students_id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_lms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query for retrieving class schedules associated with student’s courses
$sql = "SELECT classes.class_name, classes.class_time 
        FROM course_enroll 
        INNER JOIN courses ON course_enroll.course_id = courses.course_id 
        INNER JOIN classes ON courses.course_id = classes.course_id
        WHERE course_enroll.students_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $students_id);
$stmt->execute();
$result = $stmt->get_result();
$schedule = [];
while ($row = $result->fetch_assoc()) {
    $schedule[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Class Schedule</title>
</head>
<body>
    <h2>Class Schedule</h2>
    <?php if (!empty($schedule)) : ?>
        <ul>
            <?php foreach ($schedule as $class) : ?>
                <li>
                    <strong><?php echo htmlspecialchars($class['class_name']); ?></strong> - <?php echo htmlspecialchars($class['class_time']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No classes scheduled.</p>
    <?php endif; ?>
    <a href="student_dashboard.php">Back to Dashboard</a>
</body>
</html>
