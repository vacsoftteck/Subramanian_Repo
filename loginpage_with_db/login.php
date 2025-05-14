<?php
session_start();

// Database connection

$servername="localhost";
$username="root";
$password="vac321";
$dbname="php_database";

$conn = new mysqli($servername,$username,$password,$dbname);
//checking connection

if($conn->connect_error){
    die("connection failed: " . $conn->connect_error);
}

$error="";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $username=$_POST["username"];
    $password=$_POST["password"];

    // check user in the database
    $sql= "select * from users where username= ?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result = $stmt->fetch_assoc();

    if($result->num_rows==1){
        $row=$result->fetch_assoc();
    
        // verifying the password
        if(password_verify($password,$row['password'])){
            $_SESSION['user']=$username;
            header("Location:Welcome.php");
            exit();
        }

        else{
            $error="Invalid username/password";
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN PAGE</title>
</head>
<body>
 <div class="container">
    <form action="" method="POST">
        <label>username</label>
        <input type="text" name="username" placeholder=username required><br>
        <label>password</label>
        <input type="password" name="password" placeholder=password required><br>
        <button type="submit">Login</button>

    </form>
 </div>
</body>
</html>
