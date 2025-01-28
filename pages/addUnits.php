<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "astha";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['submitBtn'])) {
  $unit = trim($_POST['unit']); // Trim any extra spaces

  if (!empty($unit)) {
    // Insert unit into the units table
    $insertQuery = "INSERT INTO units (unit_name) VALUES ('$unit')";
    if ($conn->query($insertQuery) === TRUE) {
      echo "<script>alert('Unit added successfully!');</script>";
    } else {
      echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
  } else {
    echo "<script>alert('Please enter a unit.');</script>";
  }
}

?>
<?php
require_once('../templat/header.php');
require_once('../templat/sidebar.php');
?>
<div class="container mb-3 w-50">
  <form action="" method="POST">
    <div class="mb-3 mt-5">
      <label for="unit" class="form-label">Add Unit</label>
      <input type="text" class="form-control border border-1 border-dark" id="unit" placeholder="Enter unit" name="unit" required>
    </div>
    <button type="submit" class="btn btn-primary w-100" name="submitBtn">Submit</button>
  </form>
</div>
<?php
require_once('../templat/footer.php');
?>