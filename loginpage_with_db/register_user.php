<?php

$server="localhost";
$username="root";
$password="vac321";
$dbname="php_database";

// create connecntion
$conn= new mysqli($servername,$username,$password,$dbname);

// check connection
if($conn->connect_error){
    die("Connection failed:" .$conn->connect_error); 
}

// inserting user with hash password
$new_username="admin";
$new_password=password_has("vac321",PASSWORD_DEFAULT);

$sql=   "INSERT INTO users(username,password)VALUES ('$new_username', '$new_password')";

if($conn->query($sql)===TRUE){

    echo "User registered successfully";
}
else{
     echo "Error:" .$sql. "<br>". $conn->error;

}
$conn->close();
?>