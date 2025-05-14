<?php

session_start();

$valid_username="admin";
$valid_password="vac321";
$error ="";

if (isset($_SESSION['user'])) {
    header("Location: Welcome.php");
    exit();
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $username= $_POST['username'];
    $password= $_POST['password'];

    if($username === $valid_username && $password === $valid_password){
        $_SESSION['user'] = $username; 
        header("Location: home.php");
        exit();
    }

    else{

        $error="Invalid username or password";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>LOGIN</h2>

        <form action="" method = "POST">
            <label >username</label>
            <input type="text" name="username" placeholder="Username"  required><br><br>
            <label >password</label>
            <input type="password" name="password" placeholder="password" required><br><br>


            <?php if (!empty($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
            <?php } ?>


            <button type="submit" name="submit">Login</button>

        </form>

    </div>
    
</body>
</html>

