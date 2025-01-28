<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "astha";
$conn = new mysqli($servername, $username, $password, $database);

// Create restore table if it does not exist
$restoreTableQuery = "CREATE TABLE IF NOT EXISTS supplier_restore (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($restoreTableQuery);

// Delete supplier
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id']; // Ensure ID is an integer

    // Backup supplier data before deletion
    $backupQuery = "INSERT INTO supplier_restore (id, name, phone, email)
                    SELECT id, name, phone, email FROM suppliers WHERE id = $delete_id";
    $conn->query($backupQuery);

    // Delete supplier from the main table
    $deleteQuery = "DELETE FROM suppliers WHERE id = $delete_id";
    if ($conn->query($deleteQuery) === TRUE) {
        header("Location: viewSuppliers.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Update supplier
if (isset($_POST['edit_id'])) {
    $edit_id = (int)$_POST['edit_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    if (!empty($name) && !empty($phone) && !empty($email)) {
        $updateQuery = "UPDATE suppliers SET name='$name', phone='$phone', email='$email' WHERE id=$edit_id";
        if ($conn->query($updateQuery) === TRUE) {
            header("Location: viewSuppliers.php");
            exit();
        } else {
            echo "<script>alert('Error updating supplier.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}

// Fetch all suppliers
$productList = $conn->query('SELECT * FROM suppliers');
?>

<?php require_once('../templat/header.php'); ?>
<?php require_once('../templat/sidebar.php'); ?>

<?php
if ($productList) {
    echo "
    <style>
        /* Table styling */
        table {
            border-collapse: collapse;
            width: 50%;
            position: fixed;
            top: 10%;
            left: 30%;
            z-index: 1000;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            text-align: left;
            padding: 12px;
        }
        th {
            background-color:rgb(119, 77, 9);
            color: white;
            font-weight: bold;
        }
        td {
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        /* Add responsive styling */
        @media (max-width: 768px) {
            table {
                width: 90%;
                left: 5%;
                top: 5%;
            }
        }
        
        /* Small Popup Styling */
        .popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            z-index: 2000;
        }

        .popup input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid black;
            border-radius: 4px;
        }

        .popup button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .popup button:hover {
            background-color:rgb(211, 48, 19);
        }

        .close-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 50%;
            font-size: 14px;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .close-btn:hover {
            background-color: #d32f2f;
        }
    </style>
    ";

    echo "<form method='POST'>";
    echo "<table class='mt-5'>";
    echo "<tr>
            <th>Id</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Actions</th>
          </tr>";

    $rowNumber = 1; // Initialize row number

    while ($row = $productList->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $rowNumber++ . "</td>"; // Display row number starting from 1
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo '<td>
            <!-- Edit Link -->
            <a href="javascript:void(0);" class="editBtn" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-phone="' . $row['phone'] . '" data-email="' . $row['email'] . '" style="text-decoration:none; color:black">
                <i class="fa fa-edit"></i>
            </a>
            <!-- Delete Link -->
            <a href="viewSuppliers.php?delete_id=' . $row['id'] . '" class="pos_item_btn" id="item_delete" name="delBtn" style="text-decoration:none; color:black">
                <i class="fa fa-trash"></i>
            </a>
        </td>';
        echo "</tr>";
    }

    echo "</table>";
    echo "</form>";
} else {
    echo "Error fetching supplier data.";
}
?>

<!-- Small Edit Popup -->
<div id="editPopup" class="popup">
    <button class="close-btn" id="closePopup">&times;</button>
    <h3>Edit Supplier</h3>
    <form id="editForm" method="POST">
        <input type="hidden" name="edit_id" id="edit_id">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" required><br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>
        <button type="submit">Update</button>
    </form>
</div>

<script>
    // JavaScript logic remains the same
</script>

<?php require_once('../templat/footer.php'); ?>