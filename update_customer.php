<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $expiryDate = $_POST['expiry_date'];
    $database = $_POST['database'];

    // Database connection
    $conn = mysqli_connect("localhost", "root", "vac321", $database);
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Update query
    $query = "UPDATE customers SET expiry_date = '$expiryDate' WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Expiry date updated successfully!');
                parent.location.reload(); // Reload Welcome.php
              </script>";
    } else {
        echo "<script>alert('Update failed: " . mysqli_error($conn) . "');</script>";
    }

    mysqli_close($conn);
}
?>
