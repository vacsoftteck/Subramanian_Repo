<?php
session_start();
include 'credentialsdb.php'; // Including the database connection

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    header("Location: Welcome.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Fetch user from database without prepared statements
    $query = "SELECT password FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && $password === $user['password']) {
        $_SESSION['user'] = $username;
        header("Location: Welcome.php");
        exit();
    } else {
        $error = "Invalid username/password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
        .logo {
            display: block;
            margin: 0 auto;
            width: 150px;
            height: auto;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="vac (2).jpg" alt="Company Logo" class="logo">
        <form action="" method="POST">
            <label>Username</label>
            <input type="text" placeholder="Username" name="username" required><br>
            <label>Password</label>
            <input type="password" placeholder="Password" name="password" required><br>
            <?php if (!empty($error)) { ?>
                <p class="error"><?php echo $error; ?></p>
            <?php } ?>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
