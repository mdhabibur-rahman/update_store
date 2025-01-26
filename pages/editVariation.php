<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "astha";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$variationId = $_GET['id']; // Get variation ID from URL

// Fetch the variation details
$variationSql = "SELECT * FROM product_variations WHERE id = ?";
$stmt = $conn->prepare($variationSql);
$stmt->bind_param("i", $variationId);
$stmt->execute();
$result = $stmt->get_result();
$variation = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the updated data from the form
  $variationName = $_POST['variation_name'];
  $variationPrice = $_POST['price'];
  $pictureType = $variation['picture_type']; // Default to the existing picture type

  // Handle file upload if a new image is selected
  if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
    $fileTmpName = $_FILES['picture']['tmp_name'];
    $imageData = file_get_contents($fileTmpName);
    $pictureType = mime_content_type($fileTmpName); // Get the MIME type of the uploaded file
  } else {
    // If no new file is uploaded, keep the old picture and type
    $imageData = $variation['picture'];
  }

  // Update the variation in the database
  $updateSql = "UPDATE product_variations SET variation_name = ?, price = ?, picture = ?, picture_type = ? WHERE id = ?";
  $stmt = $conn->prepare($updateSql);
  $stmt->bind_param("sdssi", $variationName, $variationPrice, $imageData, $pictureType, $variationId);
  $stmt->send_long_data(2, $imageData); // Send binary data to the prepared statement
  $stmt->execute();
  $stmt->close();

  echo "Variation updated successfully!";
  // Redirect to another page after successful update
  header("Location: viewProducts.php");
  exit;
}
?>

<?php require_once('../templat/header.php') ?>
<?php require_once('../templat/sidebar.php') ?>

<div class="container mt-5">
  <h3 class="text-center">Edit Variation</h3>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="variation_name" class="form-label text-light">Variation Name</label>
      <input type="text" class="form-control" name="variation_name" value="<?= htmlspecialchars($variation['variation_name']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="price" class="form-label text-light">Price</label>
      <input type="number" class="form-control" name="price" value="<?= htmlspecialchars($variation['price']) ?>" step="0.01" required>
    </div>
    <div class="mb-3">
      <label for="picture" class="form-label text-light">Picture</label>
      <input type="file" class="form-control" name="picture" accept="image/*">
      <!-- Display current image if available -->
      <?php if ($variation['picture']): ?>
        <img src="data:<?= htmlspecialchars($variation['picture_type']) ?>;base64,<?= base64_encode($variation['picture']) ?>" alt="Current Image" width="100" class="mt-2">
      <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-success">Update Variation</button>
    <!-- Cancel button that redirects back to the product list -->
    <a href="viewProducts.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?php require_once('../templat/footer.php') ?>