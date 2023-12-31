<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iset";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $con = mysqli_connect($servername, $username, $password, $dbname);

    if (!$con) {
        die("Error connecting to the database: " . mysqli_connect_error());
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM utilisateurs WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['loggedin'] = true;
        header("Location: adminutil.php");
        exit;
    } else {
        $loginError = "Invalid email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f1f1f1;
        }

        .login-container {
            width: 300px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        input[type="email"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            text-align: center;
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($loginError)) { echo "<p class='error-message'>$loginError</p>"; } ?>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Your email" required><br>
            <input type="password" name="password" placeholder="Your password" required><br>
            <input type="submit" name="login" value="Login">
        </form>
    </div>
</body>

</html>
