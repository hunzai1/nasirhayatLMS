<?php
// Start the session
session_start();

// Database connection details
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "my_lms"; // The name of your database

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $students_name = $_POST['students_name']; // Get the student name
    $students_password = $_POST['students_password']; // Get the password

    // Prepare SQL statement to fetch student data
    $stmt = $conn->prepare("SELECT students_id, students_name, students_password FROM students WHERE students_name = ?");
    $stmt->bind_param("s", $students_name);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $hashed_password);
    
    if ($stmt->fetch()) {
        // Verify the password
        if (password_verify($students_password, $hashed_password)) {
            // Set session variables
            $_SESSION['students_id'] = $id;
            $_SESSION['students_name'] = $name;

            // Redirect to the student dashboard
            header("Location: student_dashboard.php"); // Change this if necessary
            exit();
        } else {
            $error = "Invalid name or password.";
        }
    } else {
        $error = "Invalid name or password.";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f3f3f3;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
        }
        .login-container h2 {
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
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
        .register-link {
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Student Login</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="students_name">Name:</label>
            <input type="text" id="students_name" name="students_name" required>
        </div>
        <div class="form-group">
            <label for="students_password">Password:</label>
            <input type="password" id="students_password" name="students_password" required>
        </div>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <div class="form-group">
            <button type="submit">Login</button>
        </div>
    </form>
    <a href="student_registration.php" class="register-link">Don't have an account? Register here</a>
</div>

</body>
</html>
