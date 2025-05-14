<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location:login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME PAGE</title>
</head>
<body>

<h1>Welcome to the home page</h1>
<h2>You have successfully logged in !</h2>

<form action="switchoff.php">
    <button>LOGOUT</button>
</form>

    
</body>
</html>