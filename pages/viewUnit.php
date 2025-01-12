<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "astha";
$conn = new mysqli($servername, $username, $password, $database);

// Ensure the unit_restore table exists
$restoreTableQuery = "CREATE TABLE IF NOT EXISTS unit_restore (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unit_name VARCHAR(255) NOT NULL,
    deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($restoreTableQuery);

// Function to back up data to unit_restore before deletion
function backupDataBeforeDelete($id, $conn)
{
  $backupQuery = "INSERT INTO unit_restore (id, unit_name) 
                    SELECT id, unit_name FROM units WHERE id = ?";
  $stmt = $conn->prepare($backupQuery);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
}

// Delete a unit
if (isset($_GET['delete_id'])) {
  $delete_id = (int)$_GET['delete_id'];

  // Backup the data
  backupDataBeforeDelete($delete_id, $conn);

  // Delete from units table
  $deleteQuery = "DELETE FROM units WHERE id = ?";
  $stmt = $conn->prepare($deleteQuery);
  $stmt->bind_param("i", $delete_id);
  $stmt->execute();
  $stmt->close();

  header("Location: viewUnit.php"); // Refresh the page
  exit();
}

// Update a unit
if (isset($_POST['edit_id'])) {
  $edit_id = $_POST['edit_id'];
  $unit_name = $_POST['unit_name'];

  if (!empty($unit_name)) {
    $updateQuery = "UPDATE units SET unit_name = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $unit_name, $edit_id);
    if ($stmt->execute()) {
      echo "<script>alert('Unit updated successfully!');</script>";
      header("Location: viewUnit.php");
      exit();
    } else {
      echo "<script>alert('Error updating unit.');</script>";
    }
    $stmt->close();
  } else {
    echo "<script>alert('Please enter a unit name.');</script>";
  }
}

// Fetch all units
$unitList = $conn->query("SELECT * FROM units");
?>

<?php require_once('../templat/header.php'); ?>
<?php require_once('../templat/sidebar.php'); ?>

<?php if ($unitList): ?>
  <style>
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

    th,
    td {
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

    .popup input,
    .popup button {
      width: 100%;
      margin-bottom: 10px;
    }

    .popup button {
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    .popup button:hover {
      background-color: #d32f2f;
    }
  </style>

  <form method="POST">
    <table>
      <tr>
        <th>Id</th>
        <th>Unit Name</th>
        <th>Actions</th>
      </tr>
      <?php while ($row = $unitList->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']); ?></td>
          <td><?= htmlspecialchars($row['unit_name']); ?></td>
          <td>
            <!-- Edit Button -->
            <a href="javascript:void(0);"
              class="editBtn"
              data-id="<?= $row['id']; ?>"
              data-unit_name="<?= $row['unit_name']; ?>"
              style="text-decoration: none; color: black;">
              <i class="fa fa-edit"></i>
            </a>
            <!-- Delete Button -->
            <a href="viewUnit.php?delete_id=<?= $row['id']; ?>"
              style="text-decoration: none; color: black;">
              <i class="fa fa-trash"></i>
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  </form>

  <!-- Edit Popup -->
  <div id="editPopup" class="popup">
    <button id="closePopup">&times;</button>
    <h3>Edit Unit</h3>
    <form method="POST">
      <input type="hidden" name="edit_id" id="edit_id">
      <label for="unit_name">Unit Name:</label>
      <input type="text" name="unit_name" id="unit_name" required>
      <button type="submit">Update</button>
    </form>
  </div>

  <script>
    var editLinks = document.querySelectorAll(".editBtn");
    var popup = document.getElementById("editPopup");
    var closePopup = document.getElementById("closePopup");

    editLinks.forEach(function(link) {
      link.onclick = function() {
        document.getElementById('edit_id').value = this.getAttribute('data-id');
        document.getElementById('unit_name').value = this.getAttribute('data-unit_name');
        popup.style.display = "block";
      };
    });

    closePopup.onclick = function() {
      popup.style.display = "none";
    };

    window.onclick = function(event) {
      if (event.target === popup) {
        popup.style.display = "none";
      }
    };
  </script>
<?php else: ?>
  <p>No units found.</p>
<?php endif; ?>

<?php require_once('../templat/footer.php'); ?>