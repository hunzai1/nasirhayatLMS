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

$students_name = $_SESSION['students_name'];

// Fetch student's profile picture from the database
$sql = "SELECT profile_picture FROM students WHERE students_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $students_id);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

// If no profile picture is available, use a default image
if (empty($profile_picture)) {
    $profile_picture = "/path/to/default_profile_picture.jpg"; // Change this to your default image path
}

// Fetch assigned courses
$sql = "SELECT courses.course_name FROM course_enroll 
        INNER JOIN courses ON course_enroll.course_id = courses.course_id 
        WHERE course_enroll.students_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $students_id);
$stmt->execute();
$result = $stmt->get_result();
$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row['course_name'];
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 20px;
        }
        .dashboard-container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        h2 {
            color: #333;
            margin: 10px 0;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
        }
        .section a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        .section a:hover {
            text-decoration: underline;
        }
        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #dc3545;
            text-decoration: none;
            border-radius: 5px;
        }
        .logout-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <!-- Display Profile Picture at the top -->
    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-picture">

    <!-- Welcome Message -->
    <h2>Welcome, <?php echo htmlspecialchars($students_name); ?>!</h2>

    <!-- Course Overview Section -->
    <div class="section">
        <h3>Course Overview</h3>
        <p>View a detailed overview of your courses.</p>
        <a href="student_dashboard/course_overview.php">Go to Course Overview</a>
    </div>

    <!-- Class Schedule Section -->
    <div class="section">
        <h3>Class Schedule</h3>
        <p>Check your class schedule and upcoming sessions.</p>
        <a href="student_dashboard/class_schedule.php">View Class Schedule</a>
    </div>

    <!-- Edit Profile Section -->
    <div class="section">
        <h3>Edit Profile</h3>
        <p>Update your personal information and account settings.</p>
        <a href="student_dashboard/edit_profile.php">Edit Profile</a>
    </div>

    <!-- Assigned Courses Section -->
    <div class="section">
        <h3>Assigned Courses</h3>
        <p>List of courses you are enrolled in:</p>
        <ul>
            <?php if (!empty($courses)) : ?>
                <?php foreach ($courses as $course) : ?>
                    <li><?php echo htmlspecialchars($course); ?></li>
                <?php endforeach; ?>
            <?php else : ?>
                <li>No courses assigned.</li>
            <?php endif; ?>
        </ul>
        <a href="student_dashboard/inrol.php">New Courses to Assign</a>
    </div>

    <!-- Logout Button -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</div>

</body>
</html>
