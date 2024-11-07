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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $students_name = $_POST['students_name'];
    
    // Profile picture upload handling
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/profile_pictures/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
    }

    $stmt = $conn->prepare("UPDATE students SET students_name = ?, profile_picture = ? WHERE students_id = ?");
    $stmt->bind_param("ssi", $students_name, $target_file, $students_id);
    if ($stmt->execute()) {
        $_SESSION['students_name'] = $students_name;
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile.";
    }
    $stmt->close();
}

$sql = "SELECT students_name, profile_picture FROM students WHERE students_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $students_id);
$stmt->execute();
$stmt->bind_result($students_name, $profile_picture);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
</head>
<body>
    <h2>Edit Profile</h2>
    <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
        <label for="students_name">Name:</label>
        <input type="text" id="students_name" name="students_name" value="<?php echo htmlspecialchars($students_name); ?>" required>

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" id="profile_picture" name="profile_picture">
        <?php if ($profile_picture) : ?>
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" width="100">
        <?php endif; ?>
        
        <button type="submit">Save Changes</button>
    </form>
    <a href="student_dashboard.php">Back to Dashboard</a>
</body>
</html>
