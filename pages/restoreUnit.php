<?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$database = "astha";
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to restore data from unit_restore to units table
function restoreData($id, $conn)
{
  $restoreQuery = "INSERT INTO units (id, unit_name) 
                     SELECT id, unit_name FROM unit_restore WHERE id = ?";
  if ($stmt = $conn->prepare($restoreQuery)) {
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      $deleteQuery = "DELETE FROM unit_restore WHERE id = ?";
      if ($stmtDelete = $conn->prepare($deleteQuery)) {
        $stmtDelete->bind_param("i", $id);
        $stmtDelete->execute();
        $stmtDelete->close();
      } else {
        die("Delete prepare failed: " . $conn->error);
      }
    } else {
      die("Restore execute failed: " . $stmt->error);
    }
    $stmt->close();
  } else {
    die("Restore prepare failed: " . $conn->error);
  }
}

// Function to permanently delete data from unit_restore
function deletePermanently($id, $conn)
{
  $deleteQuery = "DELETE FROM unit_restore WHERE id = ?";
  if ($stmt = $conn->prepare($deleteQuery)) {
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
      die("Permanent delete failed: " . $stmt->error);
    }
    $stmt->close();
  } else {
    die("Delete prepare failed: " . $conn->error);
  }
}

// Check if restore request exists
if (isset($_GET['restore_id'])) {
  $restore_id = (int)$_GET['restore_id']; // Ensure ID is an integer
  restoreData($restore_id, $conn); // Call the restore function
  header("Location: restoreUnit.php"); // Redirect back after restoring
  exit();
}

// Check if permanent delete request exists
if (isset($_GET['delete_id'])) {
  $delete_id = (int)$_GET['delete_id']; // Ensure ID is an integer
  deletePermanently($delete_id, $conn); // Call the permanent delete function
  header("Location: restoreUnit.php"); // Redirect back after deletion
  exit();
}

// Fetch all units from the unit_restore table
$productList = $conn->query("SELECT * FROM unit_restore");

// Include the header and sidebar
require_once('../templat/header.php');
require_once('../templat/sidebar.php');
?>

<?php
if ($productList && $productList->num_rows > 0) {
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
            background-color: rgb(119, 77, 9);
            color: white;
            font-weight: bold;
        }
        td {
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
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
            <th>ID</th>
            <th>Unit Name</th>
            <th>Actions</th>
          </tr>";

  while ($row = $productList->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['unit_name']) . "</td>";
    echo '<td>
            <!-- Restore Button -->
            <a href="restoreUnit.php?restore_id=' . $row['id'] . '" style="text-decoration:none; color:green">
                <i class="fa fa-refresh"></i> Restore
            </a> |
            <!-- Permanent Delete Button -->
            <a href="restoreUnit.php?delete_id=' . $row['id'] . '" style="text-decoration:none; color:red" onclick="return confirm(\'Are you sure you want to delete this permanently?\');">
                <i class="fa fa-trash"></i> Delete Permanently
            </a>
        </td>';
    echo "</tr>";
  }

  echo "</table>";
  echo "</form>";
} else {
  echo "<p style='text-align:center;'>No units available to restore or delete.</p>";
}
?>

<?php require_once('../templat/footer.php'); ?>
