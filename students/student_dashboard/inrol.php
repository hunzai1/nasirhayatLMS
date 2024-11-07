<?php
// Start the session
session_start();

// Check if the student is logged in
if (!isset($_SESSION['students_id'])) {
    // Redirect to login page if not logged in
    header("Location: student_login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "my_lms";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student ID from session
$students_id = $_SESSION['students_id'];

// Handle course enrollment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id']; // Get selected course ID

    // Insert enrollment into course_enroll table if not already enrolled
    $stmt = $conn->prepare("INSERT INTO course_enroll (students_id, course_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $students_id, $course_id);
    
    if ($stmt->execute()) {
        $success_message = "Successfully enrolled in the course!";
    } else {
        $error_message = "You are already enrolled in this course or an error occurred.";
    }
    $stmt->close();
}

// Fetch available courses
$courses = [];
$sql = "SELECT course_id, course_name FROM courses";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in Courses</title>
    <style>
        /* Styling for the enrollment page */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f3f3f3;
        }
        .enrollment-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
        }
        .enrollment-container h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            font-weight: bold;
        }
        .form-group select,
        .form-group button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            margin-top: 10px;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .success-message, .error-message {
            color: green;
            margin-top: 10px;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>

<div class="enrollment-container">
    <h2>Enroll in a Course</h2>
    <?php if (isset($success_message)): ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="form-group">
            <label for="course_id">Select a Course:</label>
            <select id="course_id" name="course_id" required>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['course_id']; ?>">
                        <?php echo htmlspecialchars($course['course_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <button type="submit">Enroll</button>
        </div>
    </form>
</div>

</body>
</html>
