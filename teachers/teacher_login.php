<?php
session_start(); // Start a session to store teacher data on successful login

// Database connection details
$host = "localhost"; // XAMPP usually runs on localhost
$dbname = "my_lms"; // Your database name
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP

try {
    // Create a PDO instance to connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_email = $_POST['teacher_email']; // Assuming "teacher_email" input is used for email
    $teacher_password = $_POST['teacher_password'];

    // Prepare the SQL query to fetch teacher data by email
    $query = "SELECT * FROM teachers WHERE teacher_email = :teacher_email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":teacher_email", $teacher_email);
    $stmt->execute();
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if teacher exists and verify password using password_verify
    if ($teacher && password_verify($teacher_password, $teacher['teacher_password'])) {
        // Password is correct; store teacher data in session
        $_SESSION['teacher_id'] = $teacher['teacher_id'];
        $_SESSION['teacher_name'] = $teacher['teacher_name'];

        // Redirect to student dashboard or any other page
        header("Location: teacher_dashboard.php");
        exit;
    } else {
        // If credentials don't match, display an error message
        echo "<script>alert('Invalid email or password. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .login-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .login-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-form input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>

<div class="login-form">
    <h2>Teacher Login</h2>
    <form method="POST" action="teacher_login.php">
        <label for="teacher_email">Email</label>
        <input type="email" id="teacher_email" name="teacher_email" required>

        <label for="teacher_password">Password</label>
        <input type="password" id="teacher_password" name="teacher_password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
