<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/registration.css">
    <title>Registration Form</title>
    <style>
        #container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 15px;
            border: 1px solid #936e79;
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace;
        }

        #container span {
            font-size: 24px;
            font-weight: bold;
            color: #955f70;
            font-family: 'Courier New', Courier, monospace;
        }

        form {
            margin-top: 20px;
            font-family: 'Courier New', Courier, monospace;
        }

        label {
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="number"],
        textarea,
        select {
            width: calc(100% - 20px);
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button[type="submit"] {
            background-color: #955f70;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #936e79;
        }

        div > a {
            color: #955f70;
            text-decoration: none;
        }

        div > a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div id="container">
    <span>Registration</span>
    <?php
    $errors = [];
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Database connection
        $conn = new mysqli("localhost", "root", "", "db_form_ids");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Collecting and sanitizing input
        $firstName = htmlspecialchars($_POST['first_name']);
        $lastName = htmlspecialchars($_POST['last_name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $confirmPassword = htmlspecialchars($_POST['confirm_password']);
        $address = htmlspecialchars($_POST['address']);
        $education = htmlspecialchars($_POST['education']);
        $graduationDate = htmlspecialchars($_POST['graduation_date']);
        $experience = htmlspecialchars($_POST['experience']);
        $skills = implode(", ", $_POST['skills']);

        // Validating input
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword) || empty($address) || empty($education) || empty($graduationDate) || empty($experience) || empty($skills)) {
            $errors[] = "Please fill out all fields.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }

        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }

        if (strtotime($graduationDate) <= strtotime(date("Y-m-d"))) {
            $errors[] = "Graduation date must be greater than today.";
        }

        if ($experience < 0) {
            $errors[] = "Experience in years cannot be negative.";
        }

        // If there are no errors, insert data into the database
        if (empty($errors)) {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO Users (first_name, last_name, email, password, address, education, graduation_date, experience, skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssid", $firstName, $lastName, $email, $passwordHash, $address, $education, $graduationDate, $experience, $skills);

            if ($stmt->execute()) {
                $successMessage = "Registration successful! Welcome $firstName $lastName.";
            } else {
                $errors[] = "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    }
    ?>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($successMessage): ?>
        <p><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <form action="registration.php" method="post">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name"><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name"><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address"><br><br>

        <label for="education">Education:</label>
        <input type="text" id="education" name="education"><br><br>

        <label for="graduation_date">Graduation Date:</label>
        <input type="date" id="graduation_date" name="graduation_date"><br><br>

        <label for="experience">Experience in years:</label>
        <input type="number" id="experience" name="experience"><br><br>

        <label for="skills">Skills:</label>
        <select id="skills" name="skills[]" multiple>
            <option value="html">HTML</option>
            <option value="css">CSS</option>
            <option value="javascript">JavaScript</option>
            <option value="php">PHP</option>
            <option value="mysql">MySQL</option>
        </select><br><br>

        <input type="submit" value="Register">
    </form>
    <a href="login.php">Have an Account? Log In</a>
</div>
</body>
</html>
