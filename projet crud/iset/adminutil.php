<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iset";

function connectDB($servername, $username, $password, $dbname) {
    return mysqli_connect($servername, $username, $password, $dbname);
}

function deleteRecord($con, $nom, $prenom, $email) {
    $delete_query = "DELETE FROM utilisateurs WHERE nom = '$nom' AND prenom = '$prenom' AND email = '$email'";
    mysqli_query($con, $delete_query);
}

function updateRecord($con, $nom, $prenom, $email, $password, $phone) {
    $update_query = "UPDATE utilisateurs SET password='$password', phone='$phone' WHERE nom='$nom' AND prenom='$prenom' AND email='$email'";
    mysqli_query($con, $update_query);
}

function insertRecord($con, $nom, $prenom, $email, $password, $phone) {
    $insert_query = "INSERT INTO utilisateurs (nom, prenom, email, password, phone) VALUES ('$nom', '$prenom', '$email', '$password', '$phone')";
    mysqli_query($con, $insert_query);
}

function displayRecords($con) {
    $result = mysqli_query($con, "SELECT * FROM utilisateurs");
    if (!$result || mysqli_num_rows($result) === 0) {
        echo "<tr><td colspan='6'>No records found!</td></tr>";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['nom']."</td>";
            echo "<td>".$row['prenom']."</td>";
            echo "<td>".$row['email']."</td>";
            echo "<td>".$row['password']."</td>";
            echo "<td>".$row['phone']."</td>";
            echo "<td>";
            echo "<a href='?action=delete&nom=".$row['nom']."&prenom=".$row['prenom']."&email=".$row['email']."'>Delete</a>";
            echo "<a href='#' onclick='fillForm(\"".$row['nom']."\",\"".$row['prenom']."\",\"".$row['email']."\",\"".$row['password']."\",\"".$row['phone']."\")'>Update</a>";
            echo "</td>";
            echo "</tr>";
        }
    }
}

$con = connectDB($servername, $username, $password, $dbname);

if (!$con) {
    die("Error connecting to the database: " . mysqli_connect_error());
}

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

switch ($action) {
    case 'delete':
        deleteRecord($con, $_GET["nom"], $_GET["prenom"], $_GET["email"]);
        header("Location: adminutil.php");
        exit;
    case 'update':
        updateRecord($con, $_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["password"], $_POST["phone"]);
        header("Location: adminutil.php");
        exit;
    case 'add':
        insertRecord($con, $_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["password"], $_POST["phone"]);
        header("Location: adminutil.php");
        exit;
    default:
        break;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        form div {
            margin-bottom: 15px;
            overflow: hidden;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 12px);
            padding: 8px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
        }

        button[type="reset"] {
            background-color: #dc3545;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }

        td a:hover {
            text-decoration: underline;
        }

        hr {
            border: none;
            border-top: 1px solid #ccc;
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <h1>User Management</h1>
    <form action="" method="POST">
        <div>
            <label for="nom">Nom:</label>
            <input type="text" placeholder="Your nom" name="nom" required>
        </div>

        <div>
            <label for="prenom">Prenom:</label>
            <input type="text" placeholder="Your prenom" name="prenom" required>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" placeholder="Your email" name="email" required>
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" placeholder="Your password" name="password" required>
        </div>

        <div>
            <label for="phone">Phone:</label>
            <input type="text" placeholder="Your phone" name="phone" required>
        </div>

        <div>
            <button type="submit" name="action" value="add">Add</button>
            <button type="submit" name="action" value="update">Update</button>
            <button type="reset">Reset</button>
        </div>
    </form>

    <hr>

    <table>
        <thead>
            <tr>
                <th>NOM</th>
                <th>PRENOM</th>
                <th>EMAIL</th>
                <th>PASSWORD</th>
                <th>PHONE</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            <?php displayRecords($con); ?>
        </tbody>
    </table>

    <script>
        function fillForm(nom, prenom, email, password, phone) {
            document.querySelector("input[name='nom']").value = nom;
            document.querySelector("input[name='prenom']").value = prenom;
            document.querySelector("input[name='email']").value = email;
            document.querySelector("input[name='password']").value = password;
            document.querySelector("input[name='phone']").value = phone;
        }
    </script>
</body>

</html>
