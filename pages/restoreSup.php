<?php
// Display errors for debugging purposes
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$database = "astha";
$conn = new mysqli($servername, $username, $password, $database);

// Function to backup data before deletion
function backupDataBeforeDelete($id, $conn)
{
  // Prepare the backup query string
  $backupQuery = "INSERT INTO supplier_restore (id, name, phone, email, password) 
                    SELECT id, name, phone, email, password 
                    FROM suppliers WHERE id = ?";

  // Prepare the statement
  if ($stmt = $conn->prepare($backupQuery)) {
    // Bind the parameter
    $stmt->bind_param("i", $id);
    // Execute the query
    if ($stmt->execute()) {
      $stmt->close(); // Close the statement if successful
    } else {
      die('Backup failed: ' . $stmt->error); // If execution fails, show the error
    }
  } else {
    die('Prepare failed: ' . $conn->error); // If preparation fails, show the error
  }
}

// Function to restore data to suppliers table and delete from supplier_restore
function restoreData($id, $conn)
{
  // Insert data into suppliers table
  $restoreQuery = "INSERT INTO suppliers (id, name, phone, email, password) 
                     SELECT id, name, phone, email, password 
                     FROM supplier_restore WHERE id = ?";

  if ($stmt = $conn->prepare($restoreQuery)) {
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      // Delete data from supplier_restore table
      $deleteQuery = "DELETE FROM supplier_restore WHERE id = ?";
      if ($stmt = $conn->prepare($deleteQuery)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close(); // Close after delete
      } else {
        die('Prepare failed for delete: ' . $conn->error);
      }
    } else {
      die('Restore failed: ' . $stmt->error);
    }
  } else {
    die('Prepare failed for restore: ' . $conn->error);
  }
}

// Function to permanently delete data from supplier_restore
function permanentDelete($id, $conn)
{
  // Directly delete from supplier_restore table without backup
  $deleteQuery = "DELETE FROM supplier_restore WHERE id = ?";

  if ($stmt = $conn->prepare($deleteQuery)) {
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      $stmt->close(); // Close after delete
    } else {
      die('Delete failed: ' . $stmt->error);
    }
  } else {
    die('Prepare failed for permanent delete: ' . $conn->error);
  }
}

// Check if restore or delete request exists
if (isset($_GET['restore_id'])) {
  $restore_id = (int)$_GET['restore_id']; // Ensure ID is cast to integer for safety
  restoreData($restore_id, $conn); // Restore the data and remove from supplier_restore
  header("Location: restoreSup.php"); // Redirect after restore
  exit();
}

if (isset($_GET['delete_id'])) {
  $delete_id = (int)$_GET['delete_id']; // Ensure ID is cast to integer for safety
  backupDataBeforeDelete($delete_id, $conn); // Backup data before delete
  permanentDelete($delete_id, $conn); // Permanently delete the data
  header("Location: restoreSup.php"); // Redirect after permanent delete
  exit();
}

// Fetch all suppliers from the supplier_restore table
$productList = $conn->query('SELECT * FROM supplier_restore');
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
    </style>";

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
                <!-- Restore Link with FontAwesome Icon -->
                <a href="restoreSup.php?restore_id=' . $row['id'] . '" class="pos_item_btn" style="text-decoration:none; color:green">
                    <i class="fa fa-refresh"></i> Restore
                </a>
                <!-- Permanent Delete Link with FontAwesome Icon -->
                <a href="restoreSup.php?delete_id=' . $row['id'] . '" class="pos_item_btn" style="text-decoration:none; color:red">
                    <i class="fa fa-trash"></i> Permanent Delete
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

<?php require_once('../templat/footer.php'); ?>
