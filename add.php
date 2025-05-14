<?php
session_start();
require "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $expiry_date = $_POST['expiry_date'];

    foreach ($databases as $db) {
        $conn = new mysqli("localhost", "root", "vac321", $db);
        if ($conn->connect_error) {
            die("Connection failed to $db: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO customers (customer_name, expiry_date) VALUES (?, ?)");
        $stmt->bind_param("ss", $customer_name, $expiry_date);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    header("Location: Welcome.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>
</head>
<body>
    <h1>Add Customer</h1>
    <form method="post">
        <label>Customer Name:</label>
        <input type="text" name="customer_name" required>
        
        <label>Expiry Date:</label>
        <input type="date" name="expiry_date" required>

        <button type="submit">Add</button>
    </form>
    <button onclick="location.href='Welcome.php'">Back</button>
</body>
</html>
