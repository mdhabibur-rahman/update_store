<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "astha");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission (order processing)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $supplier_id = $_POST['supplier_id'];
  $product_name = $_POST['product_name'];
  $product_price = $_POST['product_price'];
  $quantity = $_POST['quantity'];

  // Insert order into the purchase_orders table
  $sql = "INSERT INTO purchase_orders (supplier_id, product_name, product_price, quantity, order_date)
            VALUES ('$supplier_id', '$product_name', '$product_price', '$quantity', NOW())";

  if ($conn->query($sql) === TRUE) {
    $success_message = "Order placed successfully!";
  } else {
    $error_message = "Error: " . $sql . "<br>" . $conn->error;
  }
}

?>

<?php require_once('../templat/header.php'); ?>
<?php require_once('../templat/sidebar.php'); ?>

<div class="container-fluid my-5">
  <div class="row">
    <div class="col-lg-8 col-md-12">
      <!-- Purchase Product Form -->
      <div class="purchaseFormContainer mb-4">
        <h3>Purchase Products from Supplier</h3>

        <!-- Show success or error message -->
        <?php if (isset($success_message)): ?>
          <div class="alert alert-success">
            <?php echo $success_message; ?>
          </div>
        <?php elseif (isset($error_message)): ?>
          <div class="alert alert-danger">
            <?php echo $error_message; ?>
          </div>
        <?php endif; ?>

        <form action="purchase_product.php" method="POST">
          <div class="mb-3">
            <label for="supplier" class="form-label">Select Supplier</label>
            <select class="form-control" id="supplier" name="supplier_id" required>
              <?php
              // Fetch all suppliers
              $supplierSql = "SELECT id, name FROM suppliers";
              $supplierResult = $conn->query($supplierSql);

              // Display suppliers in the select dropdown
              if ($supplierResult->num_rows > 0) {
                while ($supplier = $supplierResult->fetch_assoc()) {
                  echo "<option value='{$supplier['id']}'>{$supplier['name']}</option>";
                }
              } else {
                echo "<option value=''>No suppliers available</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="product" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="product" name="product_name" placeholder="Enter product name" required>
          </div>

          <div class="mb-3">
            <label for="product_price" class="form-label">Product Price</label>
            <input type="number" class="form-control" id="product_price" name="product_price" placeholder="Enter product price" min="0" step="0.01" required>
          </div>

          <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
          </div>

          <div class="mb-3">
            <button type="submit" class="btn btn-primary">Place Order</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once('../templat/footer.php'); ?>

<?php
// Close the connection
$conn->close();
?>