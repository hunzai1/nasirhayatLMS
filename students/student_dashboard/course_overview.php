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

$sql = "SELECT courses.course_name, courses.course_des, courses.course_id 
        FROM course_enroll 
        INNER JOIN courses ON course_enroll.course_id = courses.course_id 
        WHERE course_enroll.students_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $students_id);
$stmt->execute();
$result = $stmt->get_result();
$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Overview</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        .course-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        .course-card:hover {
            transform: translateY(-10px);
        }

        .course-card h3 {
            background-color: #007bff;
            color: #fff;
            margin: 0;
            padding: 15px;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .course-card p {
            padding: 15px;
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        .button-container {
            display: none;
            padding: 15px;
            text-align: center;
        }

        .button-container a {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            margin: 5px;
            display: inline-block;
        }

        .button-container a:hover {
            background-color: #218838;
        }

        .back-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            margin-top: 20px;
            text-align: center;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

    </style>
    <script>
        function toggleButtons(courseId) {
            var buttons = document.getElementById('buttons-' + courseId);
            if (buttons.style.display === "none" || buttons.style.display === "") {
                buttons.style.display = "block";
            } else {
                buttons.style.display = "none";
            }
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Course Overview</h2>

    <?php if (!empty($courses)) : ?>
        <?php foreach ($courses as $course) : ?>
            <div class="course-card">
                <h3 onclick="toggleButtons(<?php echo $course['course_id']; ?>)">
                    <?php echo htmlspecialchars($course['course_name']); ?>
                </h3>
                <p><?php echo nl2br(htmlspecialchars($course['course_des'])); ?></p>
                
                <!-- Button container for each course (initially hidden) -->
                <div id="buttons-<?php echo $course['course_id']; ?>" class="button-container">
                    <a href="assignments.php?course_id=<?php echo $course['course_id']; ?>">Assignments</a>
                    <a href="class_timing.php?course_id=<?php echo $course['course_id']; ?>">class timing</a>
                    <a href="course_outline.php?course_id=<?php echo $course['course_id']; ?>">course outline</a>
                    <a href="quizzes.php?course_id=<?php echo $course['course_id']; ?>">Quizzes</a>
                    <a href="instructor.php?course_id=<?php echo $course['course_id']; ?>">instructor</a>
                    <a href="lectures.php?course_id=<?php echo $course['course_id']; ?>">Lectures</a>
                    <a href="results.php?course_id=<?php echo $course['course_id']; ?>">Results</a>
                    <a href="attendance.php?course_id=<?php echo $course['course_id']; ?>">Attendance</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p style="text-align: center; color: #666; font-size: 1.2rem;">No courses assigned yet.</p>
    <?php endif; ?>

    <a href="student_dashboard.php" class="back-button">Back to Dashboard</a>
</div>

</body>
</html>
