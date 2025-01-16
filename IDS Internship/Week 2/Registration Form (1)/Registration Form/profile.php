<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "db_form_ids");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/profile.css">
    <title>My Profile</title>
</head>
<body>
<div id="container">
    <span>My Profile</span>
    <p>First Name: <?php echo $user['first_name']; ?></p>
    <p>Last Name: <?php echo $user['last_name']; ?></p>
    <p>Email: <?php echo $user['email']; ?></p>
    <p>Address: <?php echo $user['address']; ?></p>
    <p>Education: <?php echo $user['education']; ?></p>
    <p>Graduation Date: <?php echo $user['graduation_date']; ?></p>
    <p>Experience: <?php echo $user['experience']; ?> years</p>
    <p>Skills: <?php echo $user['skills']; ?></p>
    <a href="editProfile.php">Edit Profile</a>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
