<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "astha";
$conn = new mysqli($servername, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submitBtn'])) {
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];

  // Insert data into suppliers table
  $stmt = $conn->prepare("INSERT INTO suppliers (name, phone, email, created_at) VALUES (?, ?, ?, NOW())");
  $stmt->bind_param("sss", $name, $phone, $email);
  $stmt->execute();
  $stmt->close();

  echo "Data inserted successfully!";
}
?>
<?php
require_once('../templat/header.php');
require_once('../templat/sidebar.php');
?>
<div class="container mb-3 w-50">
  <form action="" method="POST">
    <div class="mb-3 mt-5">
      <label for="exampleInputName" class="form-label">Name</label>
      <input type="text" class="form-control border border-1 border-dark" id="exampleInputName" placeholder="Name" name="name" required>
    </div>
    <div class="mb-3">
      <label for="exampleInputPhone" class="form-label">Phone</label>
      <input type="tel" class="form-control border border-1 border-dark" id="exampleInputPhone" placeholder="Phone" name="phone" required>
    </div>
    <div class="mb-3">
      <label for="exampleInputEmail" class="form-label">Email address</label>
      <input type="email" class="form-control border border-1 border-dark" id="exampleInputEmail" placeholder="Email" name="email" required>
    </div>
    <button type="submit" class="btn btn-primary w-100" name="submitBtn">Submit</button>
  </form>
</div>
<?php
require_once('../templat/footer.php');
?>