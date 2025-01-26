<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "astha";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the URL
if (isset($_GET['product_id'])) {
  $productId = $_GET['product_id'];
} else {
  die("Product ID is missing.");
}

// Initialize error and success messages
$errorMessage = "";
$successMessage = "";

// Handle form submission for adding a variation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $variationName = $_POST['variation_name'];
  $price = $_POST['price'];
  $picture = $_FILES['picture'];

  // Check if fields are empty
  if (empty($variationName) || empty($price) || $picture['error'] !== UPLOAD_ERR_OK) {
    $errorMessage = "All fields are required.";
  } else {
    // Read file content as binary data
    $imageData = file_get_contents($picture['tmp_name']);
    $imageType = $picture['type']; // Get MIME type of the image

    if ($imageData === false) {
      $errorMessage = "Failed to read the uploaded file.";
    } else {
      // Prepare the SQL query to insert the variation
      $sql = "INSERT INTO product_variations (product_id, variation_name, price, picture, picture_type) 
                    VALUES (?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("isdbs", $productId, $variationName, $price, $imageData, $imageType);
      $stmt->send_long_data(3, $imageData); // Send binary data to the prepared statement

      // Execute the query and check for success
      if ($stmt->execute()) {
        $successMessage = "Variation added successfully!";
        header("Location: viewProducts.php");
        exit();
      } else {
        $errorMessage = "Failed to add variation. Error: " . $stmt->error;
      }
    }
  }
}
?>

<?php require_once('../templat/header.php') ?>
<?php require_once('../templat/sidebar.php') ?>

<div class="container mt-5">
  <h3 class="text-center">Add New Variation for Product</h3>

  <?php if ($errorMessage): ?>
    <div class="alert alert-danger"><?= $errorMessage ?></div>
  <?php endif; ?>
  <?php if ($successMessage): ?>
    <div class="alert alert-success"><?= $successMessage ?></div>
  <?php endif; ?>

  <form action="addVariation.php?product_id=<?= $productId ?>" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="variation_name" class="form-label">Variation Name</label>
      <input type="text" name="variation_name" id="variation_name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="price" class="form-label">Price</label>
      <input type="number" name="price" id="price" class="form-control" step="0.01" required>
    </div>

    <div class="mb-3">
      <label for="picture" class="form-label">Picture</label>
      <input type="file" name="picture" id="picture" class="form-control" accept="image/*" required>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-success">Add Variation</button>
    </div>
  </form>

  <div class="text-center mt-3">
    <a href="viewProducts.php" class="btn btn-secondary">Back to Products Overview</a>
  </div>
</div>

<?php require_once('../templat/footer.php') ?>

<?php
$conn->close();
?>