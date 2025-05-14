<?php 
session_start();
date_default_timezone_set('Asia/Kolkata'); // Set to your timezone

require "db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Data</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; color: #333; }
        h1 { margin-bottom: 10px; font-size: 28px; font-weight: bold; color: #2c3e50; }
        table { width: 60%; margin: 15px auto; border-collapse: collapse; background-color: white; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; font-weight: 550; }
        tr:hover { background-color: #F5F5F5; }
        th { background-color: #3498db; color: white; }
        th:hover { background-color: #2980b9; }
        .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background-color: white; margin: 15% auto; padding: 20px; width: 30%; text-align: center; border-radius: 5px; position:relative; }
        .logout-btn-container { position: absolute; top: 10px; right: 10px; }
        .logout-btn { background-color: red; color: white; border: none; padding: 10px 15px; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .logout-btn:hover{background-color:#880808; transform:scale(1.1);}
       
        .editBtn { background-color: #00a2ed; padding: 8px 12px; color: white; border-radius: 3px; cursor: pointer; }
        .editBtn:hover{background-color:#007FFF; transform:scale(1.03);}
        .save-model {width: 100%; padding: 12px;background-color: #27ae60;color: white;border: none;border-radius: 5px;cursor: pointer;font-size: 16px;font-weight: bold;transition: background-color 0.3s ease;margin-top: 15px;}
        .save-model:hover{ background-color: #2ecc71;}
        .close-model-btn {  padding: 5px 10px;  background-color: red; color: white; border: none; border-radius:3px; cursor: pointer; position: absolute;  top: 5px;  right: 5px; font-size: 18px;font-weight: bold;width: 30px; /* Ensures a square button */height: 30px;display: flex; align-items: center;justify-content: center;}
        .close-model-btn:hover {background-color: darkred;}
         /* Modal title */
        .modal-content h2 { font-size: 22px;font-weight: bold;color: #2c3e50;margin-bottom: 10px;}
         /* Customer name styling */
        #customerName {font-size: 16px;font-weight: 600;color: #34495e;margin-bottom: 15px;}
         /* Label styling */
        .modal-content label {font-size: 16px;font-weight: 600;color: #2c3e50;display: block;margin-bottom: 8px;text-align: left;}
         /* Expiry date input */
        .modal-content input[type="date"] {width: 100%;padding: 10px;font-size: 14px;border: 1px solid #ccc;border-radius: 5px;box-sizing: border-box;text-align: center;}
        h1 {font-family: 'Arial', sans-serif; font-size: 28px;font-weight: bold;color: #333; display: relative; align-items: center;gap: 10px; }
        h1 i {color: #007bff; font-size: 24px; }

        .input-customer{width: 100%;padding: 10px;font-size: 14px;border: 1px solid #ccc;border-radius: 5px;box-sizing: border-box;text-align: center; }

    </style>
</head>
<body>
<h1>Customer Data <i class="fas fa-users"></i></h1>

    <div class="logout-btn-container">
    <form action="loggingoff.php" method="post">
        <button type="submit" class="logout-btn">
        Logout <i class="fas fa-sign-out-alt"> </i> 
        </button>
    </form>
</div>

    <table>
        <tr>
            <th>Customer Name</th>
            <th>Expiry Date</th>
            <th>Actions</th>   
        </tr>

        <?php foreach ($allData as $row) { ?>
        <!-- making changes here -->
        <tr>
    <td><?= htmlspecialchars($row['customer_name']) ?></td>
    <td><?= date("d-m-Y", strtotime($row['expiry_date'])) ?></td>
    <td>
        <button class="editBtn" 
            data-id="<?= $row['id'] ?>" 
            data-name="<?= htmlspecialchars($row['customer_name']) ?>" 
            data-expiry="<?= htmlspecialchars($row['expiry_date']) ?>"
            data-database="<?= $row['database'] ?>">
            Edit <i class="fas fa-edit" style="margin-left: 5px;"></i>
        </button>
    </td>
</tr>
            <!-- till here  -->
        <?php } ?>
    </table>

    <!-- making changes here -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2>Edit Expiry Date</h2><br><br>
            <!-- <p id="customerName"></p> -->
            <label for="">Customer Name:</label>
            <input id="customerName"type="text" class="input-customer" readonly>
            <iframe name="hiddenFrame" style="display:none;"></iframe>
         <form id="editForm" action="update_customer.php" method="post" target="hiddenFrame" onsubmit="showAlert()">
    <input type="hidden" name="id" id="customerId">
    <input type="hidden" name="database" id="databaseName">
    <label>Expiry Date:</label>
    <input type="date" name="expiry_date" id="expiryDate">
    <br><br>
    <button type="submit" class="save-model">Save</button>
    <button type="button" id="closeModal" class="close-model-btn">X</button>
</form>
        </div>
    </div>
    <!-- till here  -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButtons = document.querySelectorAll(".editBtn");
            const modal = document.getElementById("editModal");
            const customerNameInput = document.getElementById("customerName");
            const expiryDateInput = document.getElementById("expiryDate");
            const customerIdInput = document.getElementById("customerId");
            const databaseNameInput = document.getElementById("databaseName");
            const closeModal = document.getElementById("closeModal");
            
            editButtons.forEach(button => {
                button.addEventListener("click", function() {
                    customerIdInput.value = this.dataset.id;
                    databaseNameInput.value = this.dataset.database;
                    // customerName.textContent = "Customer: " + this.dataset.name;
                    customerNameInput.value = this.dataset.name;
                    expiryDateInput.value = this.dataset.expiry;
                    modal.style.display = "flex";
                });
            });

            closeModal.addEventListener("click", function() {
                modal.style.display = "none";
            });
        });
    </script>
</body>
</html>
