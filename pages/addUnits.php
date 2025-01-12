<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "astha";
$conn = new mysqli($servername, $username, $password, $database);

if (isset($_POST['submitBtn'])) {
  $unit = $_POST['unit'];

  $conn->query("CALL call_units('$unit')");
}

?>
<?php
require_once('../templat/header.php')
?>
<?php
require_once('../templat/sidebar.php')
?>
<div class="container mb-3 w-50">
  <form action="" method="POST">
    <div class="mb-3 mt-5">
      <label for="exampleInputEmail1" class="form-label">Add Unit</label>
      <input type="text" class="form-control border border-1 border-dark" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="unit" name="unit">
    </div>
    <button type="submit" class="btn btn-primary w-100" name="submitBtn">Submit</button>
  </form>
</div>
<?php
require_once('../templat/footer.php')
?>