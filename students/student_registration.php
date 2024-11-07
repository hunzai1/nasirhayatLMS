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

// Fetch available classes from the classes table
$classes = [];
try {
    $class_query = "SELECT class_id, class_name FROM classes";
    $class_stmt = $pdo->query($class_query);
    $classes = $class_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to fetch classes: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $students_name = $_POST['students_name'];
    $students_email = $_POST['students_email'];
    $students_password = $_POST['students_password'];
    $gender = $_POST['gender'];
    $class_id = $_POST['class_id'];

    // Hash the password for secure storage
    $hashed_password = password_hash($students_password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert student data
    $query = "INSERT INTO students (students_name, students_email, students_password, gender, class_id) 
              VALUES (:students_name, :students_email, :students_password, :gender, :class_id)";
    $stmt = $pdo->prepare($query);

    // Bind parameters to the query
    $stmt->bindParam(":students_name", $students_name);
    $stmt->bindParam(":students_email", $students_email);
    $stmt->bindParam(":students_password", $hashed_password); // Use hashed password
    $stmt->bindParam(":gender", $gender);
    $stmt->bindParam(":class_id", $class_id);

    // Execute the query and check if the insertion is successful
    if ($stmt->execute()) {
        echo "<script>alert('Student registered successfully!');</script>";
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
    <title>Student Registration</title>
    <style>
        /* Basic styling for the form */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
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
    <h2>Student Registration</h2>
    <form method="POST" action="student_registration.php">
        <label for="students_name">Name</label>
        <input type="text" id="students_name" name="students_name" required>

        <label for="students_email">Email</label>
        <input type="email" id="students_email" name="students_email" required>

        <label for="students_password">Password</label>
        <input type="password" id="students_password" name="students_password" required>

        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <label for="class_id">Class</label>
        <select id="class_id" name="class_id" required>
            <option value="">Select Class</option>
            <?php foreach ($classes as $class): ?>
                <option value="<?= htmlspecialchars($class['class_id']) ?>"><?= htmlspecialchars($class['class_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Register</button>
    </form>
</div>

</body>
</html>
