<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.html");
    exit;
}

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "my_lms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete_course_id'])) {
    $delete_course_id = intval($_GET['delete_course_id']);
    $deleteStmt = $conn->prepare("DELETE FROM assign_course WHERE course_id = ?");
    $deleteStmt->bind_param("i", $delete_course_id);
    if ($deleteStmt->execute()) {
        echo "<div>Assigned course deleted successfully!</div>";
    } else {
        echo "<div>Error: " . $deleteStmt->error . "</div>";
    }
    $deleteStmt->close();
}

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

