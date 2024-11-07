<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Course</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f4f4f9;
        }
        .container {
            width: 300px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Course</h2>
        <form action="add_course.php" method="post">
            <label for="course_name">Course Name:</label>
            <input type="text" id="course_name" name="course_name" required>

            <label for="course_des">Course Description:</label>
            <textarea id="course_des" name="course_des" required></textarea>

            <input type="submit" value="Add Course">
        </form>
        <?php
        // PHP script starts only after form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = ""; // or your database password
            $dbname = "my_lms";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("<div class='message'>Connection failed: " . $conn->connect_error . "</div>");
            }

            // Retrieve and sanitize form data
            $course_name = isset($_POST['course_name']) ? $_POST['course_name'] : '';
            $course_des = isset($_POST['course_des']) ? $_POST['course_des'] : '';

            if (!empty($course_name) && !empty($course_des)) {
                // Prepare and bind
                $stmt = $conn->prepare("INSERT INTO courses (course_name, course_des) VALUES (?, ?)");
                $stmt->bind_param("ss", $course_name, $course_des);

                // Execute and provide feedback
                if ($stmt->execute()) {
                    echo "<div class='message'>Course added successfully!</div>";
                } else {
                    echo "<div class='message'>Error: " . $stmt->error . "</div>";
                }

                // Close connections
                $stmt->close();
            } else {
                echo "<div class='message'>Please fill in all fields.</div>";
            }
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
