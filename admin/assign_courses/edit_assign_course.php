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

if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);

    $result = $conn->query("SELECT * FROM assign_course WHERE course_id = $course_id");
    $assignment = $result->fetch_assoc();

    $teacher_id = $assignment['teacher_id'];
    $class_id = $assignment['class_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = intval($_POST['course_id']);
    $teacher_id = intval($_POST['teacher_id']);
    $class_id = intval($_POST['class_id']);

    $stmt = $conn->prepare("UPDATE assign_course SET teacher_id = ?, class_id = ? WHERE course_id = ?");
    $stmt->bind_param("iii", $teacher_id, $class_id, $course_id);

    if ($stmt->execute()) {
        echo "<script>alert('Assignment updated successfully'); window.location.href='assign_courses.php';</script>";
    } else {
        echo "<div>Error updating assignment: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

$teachers = $conn->query("SELECT teacher_id, teacher_name FROM teachers")->fetch_all(MYSQLI_ASSOC);
$classes = $conn->query("SELECT class_id, class_name FROM classes")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Assigned Course</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
        .alert {
            margin-top: 20px;
            padding: 10px;
            color: white;
            background-color: #f44336;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Assigned Course</h2>
        <form method="POST">
            <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course_id); ?>">
            
            <div class="form-group">
                <label for="teacher">Select Teacher:</label>
                <select name="teacher_id" required>
                    <option value="">Select a Teacher</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?php echo $teacher['teacher_id']; ?>" 
                            <?php if ($teacher['teacher_id'] == $teacher_id) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($teacher['teacher_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="class">Select Class:</label>
                <select name="class_id" required>
                    <option value="">Select a Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['class_id']; ?>" 
                            <?php if ($class['class_id'] == $class_id) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($class['class_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="submit-button">Update Assignment</button>
        </form>
    </div>
</body>
</html>
