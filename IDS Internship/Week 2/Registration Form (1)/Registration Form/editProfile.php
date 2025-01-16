<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_form_ids";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $education = htmlspecialchars($_POST['education']);
    $graduationDate = htmlspecialchars($_POST['graduation_date']);
    $experience = htmlspecialchars($_POST['experience']);
    $skills = implode(", ", $_POST['skills']);

    // Update user data
    $sql_update = "UPDATE Users SET first_name = ?, last_name = ?, email = ?, address = ?, education = ?, graduation_date = ?, experience = ?, skills = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssidi", $firstName, $lastName, $email, $address, $education, $graduationDate, $experience, $skills, $user_id);

    if ($stmt_update->execute()) {
        // Redirect to profile page on successful update
        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . $stmt_update->error;
    }

    $stmt_update->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css">
    <title>Edit Profile</title>
</head>
<body>
    <div id="container">
        <span>Edit Profile</span>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>"><br><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>"><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"><br><br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>"><br><br>

            <label for="education">Education:</label>
            <input type="text" id="education" name="education" value="<?php echo htmlspecialchars($user['education']); ?>"><br><br>

            <label for="graduation_date">Graduation Date:</label>
            <input type="date" id="graduation_date" name="graduation_date" value="<?php echo htmlspecialchars($user['graduation_date']); ?>"><br><br>

            <label for="experience">Experience in years:</label>
            <input type="number" id="experience" name="experience" value="<?php echo htmlspecialchars($user['experience']); ?>"><br><br>

            <label for="skills">Skills:</label>
            <select id="skills" name="skills[]" multiple>
                <option value="html" <?php if (strpos($user['skills'], 'html') !== false) echo 'selected'; ?>>HTML</option>
                <option value="css" <?php if (strpos($user['skills'], 'css') !== false) echo 'selected'; ?>>CSS</option>
                <option value="javascript" <?php if (strpos($user['skills'], 'javascript') !== false) echo 'selected'; ?>>JavaScript</option>
                <option value="php" <?php if (strpos($user['skills'], 'php') !== false) echo 'selected'; ?>>PHP</option>
                <option value="mysql" <?php if (strpos($user['skills'], 'mysql') !== false) echo 'selected'; ?>>MySQL</option>
            </select><br><br>

            <input type="submit" value="Update">
        </form>
        <div style='text-align:center'><a href="profile.php">Go to Profile</a></div>
    </div>
</body>
</html>
