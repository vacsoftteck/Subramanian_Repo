<?php
$host = "localhost"; // Change if needed
$dbname = "credentials";
$dbuser = "root"; // Change to your database username
$dbpass = "vac321"; // Change to your database password

$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
