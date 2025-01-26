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

$productId = $_GET['id']; // Get product ID from URL

// Fetch the product details
$productSql = "SELECT * FROM products WHERE id = $productId";
$productResult = $conn->query($productSql);
$product = $productResult->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the updated data from the form
  $productName = $_POST['product_name'];
  $productDescription = $_POST['product_description'];

  // Update product in the database
  $updateSql = "UPDATE products SET name = ?, description = ? WHERE id = ?";
  $stmt = $conn->prepare($updateSql);
  $stmt->bind_param("ssi", $productName, $productDescription, $productId);
  $stmt->execute();

  echo "Product updated successfully!";
  // Optionally, redirect to another page after successful update
  header("Location: viewProducts.php");
}

?>

<?php require_once('../templat/header.php') ?>
<?php require_once('../templat/sidebar.php') ?>

<div class="container mt-5">
  <h3 class="text-center">Edit Product</h3>

  <form method="POST">
    <div class="mb-3">
      <label for="product_name" class="form-label text-light">Product Name</label>
      <input type="text" class="form-control" name="product_name" value="<?= htmlspecialchars($product['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="product_description" class="form-label text-light">Product Description</label>
      <textarea class="form-control" name="product_description" required><?= htmlspecialchars($product['description']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-success">Update Product</button>
    <!-- Cancel button that redirects back to the product list -->
    <a href="viewProducts.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?php require_once('../templat/footer.php') ?>