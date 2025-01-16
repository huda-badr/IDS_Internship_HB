<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/registration.css">
    <title>Login</title>
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
    <span>Login</span>
    <?php
    session_start();
    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli("localhost", "root", "", "db_form_ids");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        if (empty($email) || empty($password)) {
            $errors[] = "Please fill out all fields.";
        }

        if (empty($errors)) {
            $sql = "SELECT * FROM Users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    header("Location: profile.php");
                    exit();
                } else {
                    $errors[] = "Invalid password.";
                }
            } else {
                $errors[] = "No user found with this email.";
            }

            $stmt->close();
        }

        $conn->close();
    }
    ?>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>

        <input type="submit" value="Login">
    </form>
    <a href="registration.php">Don't Have an Account? Sign Up</a>
</div>
</body>
</html>
