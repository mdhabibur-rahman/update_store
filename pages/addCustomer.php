<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "astha";
$conn = new mysqli($servername, $username, $password, $database);

if (isset($_POST['submitBtn'])) {
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $conn->query("CALL call_customer('$name', '$phone','$email','$password')");
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
      <label for="exampleInputEmail1" class="form-label">Name</label>
      <input type="text" class="form-control border border-1 border-dark" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="name" name="name">
    </div>
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Phone name</label>
      <input type="tel" class="form-control border border-1 border-dark" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="phone" name="phone">
    </div>
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Email address</label>
      <input type="email" class="form-control border border-1 border-dark" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="email" name="email">
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Password</label>
      <input type="password" class="form-control border border-1 border-dark" id="exampleInputPassword1" placeholder="password" name="password">
    </div>
    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input border border-1 border-dark" id="exampleCheck1">
      <label class="form-check-label" for="exampleCheck1">Check me out</label>
    </div>
    <button type="submit" class="btn btn-primary w-100" name="submitBtn">Submit</button>
  </form>
</div>
<?php
require_once('../templat/footer.php')
?>