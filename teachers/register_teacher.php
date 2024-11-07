<?php
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
    // Retrieve and sanitize form inputs
    $teacher_email = $_POST['teacher_email'];
    $teacher_password = $_POST['teacher_password'];
    $teacher_quali = $_POST['teacher_quali'];
    $teacher_name = $_POST['teacher_name'];
    $teacher_gender = $_POST['teacher_gender'];

    // Hash the password for secure storage
    $hashed_password = password_hash($teacher_password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert teacher data
    $query = "INSERT INTO teachers (teacher_email, teacher_password, teacher_quali, teacher_name, teacher_gender) 
              VALUES (:teacher_email, :teacher_password, :teacher_quali, :teacher_name, :teacher_gender)";
    $stmt = $pdo->prepare($query);

    // Bind parameters to the query
    $stmt->bindParam(":teacher_email", $teacher_email);
    $stmt->bindParam(":teacher_password", $hashed_password); // Use hashed password
    $stmt->bindParam(":teacher_quali", $teacher_quali);
    $stmt->bindParam(":teacher_name", $teacher_name);
    $stmt->bindParam(":teacher_gender", $teacher_gender);

    // Execute the query and check if the insertion is successful
    if ($stmt->execute()) {
        echo "<script>alert('Teacher registered successfully!');</script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration</title>
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
        .registration-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .registration-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .registration-form input, select {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .registration-form button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .registration-form button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>

<div class="registration-form">
    <h2>Register Teacher</h2>
    <form method="POST" action="register_teacher.php">
        <label for="teacher_name">Name</label>
        <input type="text" id="teacher_name" name="teacher_name" required>

        <label for="teacher_email">Email</label>
        <input type="email" id="teacher_email" name="teacher_email" required>

        <label for="teacher_password">Password</label>
        <input type="password" id="teacher_password" name="teacher_password" required>

        <label for="teacher_quali">Qualification</label>
        <input type="text" id="teacher_quali" name="teacher_quali" required>

        <label for="teacher_gender">Gender</label>
        <select id="teacher_gender" name="teacher_gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <button type="submit">Register</button>
    </form>
</div>

</body>
</html>
