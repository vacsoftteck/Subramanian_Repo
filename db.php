<?php
$allData = []; // Store customer data
$databases = ["php_database", "php_database2", "php_database3"]; // Add more DB names here
$connections = []; // To store connections

// Establish connections dynamically
foreach ($databases as $db) {
    $conn = new mysqli("localhost", "root", "vac321", $db);
    if ($conn->connect_error) {
        die("Connection failed to $db: " . $conn->connect_error);
    }
    $connections[$db] = $conn;
}

// Query each database
$sql = "SELECT id, customer_name, expiry_date FROM customers";

foreach ($connections as $db => $conn) {
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['database'] = $db;
            $allData[] = $row;
        }
    }
    $conn->close(); // Close each connection
}
?>
