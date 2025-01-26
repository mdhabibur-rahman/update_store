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

// Delete product or variation
if (isset($_GET['delete_product'])) {
  $productId = $_GET['delete_product'];

  // Delete variations for the product first
  $deleteVariations = "DELETE FROM product_variations WHERE product_id = $productId";
  $conn->query($deleteVariations);

  // Then delete the product
  $deleteProduct = "DELETE FROM products WHERE id = $productId";
  $conn->query($deleteProduct);

  header("Location: viewProducts.php");
  exit();
}

if (isset($_GET['delete_variation'])) {
  $variationId = $_GET['delete_variation'];

  // Get the product_id for the variation
  $variationQuery = "SELECT product_id FROM product_variations WHERE id = $variationId";
  $result = $conn->query($variationQuery);
  $row = $result->fetch_assoc();
  $productId = $row['product_id'];

  // Delete the variation
  $deleteVariation = "DELETE FROM product_variations WHERE id = $variationId";
  $conn->query($deleteVariation);

  // Check if there are remaining variations for the product
  $checkVariations = "SELECT * FROM product_variations WHERE product_id = $productId";
  $variationsRemaining = $conn->query($checkVariations);

  // If no more variations left, delete the product as well
  if ($variationsRemaining->num_rows == 0) {
    $deleteProduct = "DELETE FROM products WHERE id = $productId";
    $conn->query($deleteProduct);
  }

  header("Location: viewProducts.php");
  exit();
}

// Toggle variation visibility
if (isset($_GET['toggle_variation_visibility'])) {
  $variationId = $_GET['toggle_variation_visibility'];
  $visibilityQuery = "UPDATE product_variations SET is_visible = NOT is_visible WHERE id = $variationId";
  $conn->query($visibilityQuery);
  header("Location: viewProducts.php");
  exit();
}

// Retrieve all products from the products table
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<?php require_once('../templat/header.php') ?>
<?php require_once('../templat/sidebar.php') ?>

<div class="container mt-5">
  <div class="mb-4 text-center">
    <a href="addProducts.php" class="btn btn-success">Add Product</a>
  </div>

  <h3 class="text-center">Products Overview</h3>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-striped text-dark table-bordered rounded-3">
        <thead class="bg-dark text-light rounded-top">
          <tr>
            <th>Product Name</th>
            <th>Product Description</th>
            <th>Variations</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($product = $result->fetch_assoc()): ?>
            <tr class="bg-dark">
              <td><?= htmlspecialchars($product['name']) ?></td>
              <td><?= nl2br(htmlspecialchars($product['description'])) ?></td>
              <td>
                <?php
                $productId = $product['id'];
                $variationSql = "SELECT * FROM product_variations WHERE product_id = $productId";
                $variationResult = $conn->query($variationSql);
                ?>
                <?php if ($variationResult->num_rows > 0): ?>
                  <table class="table table-sm table-bordered bg-secondary text-dark rounded-3">
                    <thead class="bg-secondary rounded-top">
                      <tr>
                        <th>Variation Name</th>
                        <th>Price</th>
                        <th>Picture</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php while ($variation = $variationResult->fetch_assoc()): ?>
                        <tr class="bg-dark">
                          <td><?= htmlspecialchars($variation['variation_name']) ?></td>
                          <td>$<?= number_format($variation['price'], 2) ?></td>
                          <td>
                            <img src="serveImage.php?id=<?= $variation['id'] ?>"
                              alt="<?= htmlspecialchars($variation['variation_name']) ?>"
                              class="img-fluid rounded" style="max-width: 80px; max-height: 80px;">
                          </td>
                          <td>
                            <?= $variation['is_visible'] ? '<span class="badge bg-success">Visible</span>' : '<span class="badge bg-danger">Hidden</span>' ?>
                          </td>
                          <td>
                            <a href="viewProducts.php?toggle_variation_visibility=<?= $variation['id'] ?>" class="btn btn-secondary btn-sm">
                              <?= $variation['is_visible'] ? 'Hide' : 'Show' ?>
                            </a>
                            <a href="editVariation.php?id=<?= $variation['id'] ?>" style="text-decoration: none;">
                              <i class="fa fa-edit" style="color: black;"></i>
                            </a>
                            <a href="viewProducts.php?delete_variation=<?= $variation['id'] ?>" style="text-decoration: none; color: red;" onclick="return confirm('Are you sure you want to delete this variation?');">
                              <i class="fa fa-trash" style="color: red;"></i>
                            </a>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                <?php else: ?>
                  <span class="badge bg-danger">No variations available</span>
                <?php endif; ?>
                <a href="addVariation.php?product_id=<?= $product['id'] ?>" class="btn btn-primary mt-2">Add Variation</a>
              </td>
              <td>
                <a href="editProduct.php?id=<?= $product['id'] ?>" style="text-decoration: none;">
                  <i class="fa fa-edit" style="color: black;"></i>
                </a>
                <a href="viewProducts.php?delete_product=<?= $product['id'] ?>" style="text-decoration: none; color: red;" onclick="return confirm('Are you sure you want to delete this product and its variations?');">
                  <i class="fa fa-trash" style="color: red;"></i>
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-warning shadow">
      <strong>Oops!</strong> No products found in the database.
    </div>
  <?php endif; ?>

  <?php $conn->close(); ?>
</div>

<?php require_once('../templat/footer.php') ?>