<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $productName = $_POST['product_name'];
  $productDescription = $_POST['product_description'];

  // Database connection
  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "astha";

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  try {
    // Start a database transaction
    $conn->begin_transaction();

    // Insert product into the `products` table
    $stmt = $conn->prepare("INSERT INTO products (name, description) VALUES (?, ?)");
    if (!$stmt) {
      throw new Exception("Error preparing product insert query: " . $conn->error);
    }

    $stmt->bind_param("ss", $productName, $productDescription);
    if (!$stmt->execute()) {
      throw new Exception("Error executing product insert query: " . $stmt->error);
    }

    $productId = $conn->insert_id; // Get the ID of the newly inserted product
    $stmt->close();

    // Prepare statement for product variations
    $stmt = $conn->prepare("INSERT INTO product_variations (product_id, variation_name, price, picture, picture_type, expiration_date) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
      throw new Exception("Error preparing variation insert query: " . $conn->error);
    }

    foreach ($_POST['variations'] as $index => $variation) {
      $variationName = $variation['name'];
      $variationPrice = $variation['price'];
      $expirationDate = $variation['expiration_date'];

      // Correctly access the file for the current variation
      $fileTmpName = $_FILES['variations']['tmp_name'][$index]['picture'];

      if (!is_uploaded_file($fileTmpName)) {
        throw new Exception("File upload error for variation: $variationName");
      }

      // Read the image file content as binary
      $imageData = file_get_contents($fileTmpName);
      if ($imageData === false) {
        throw new Exception("Failed to read file for variation: $variationName");
      }

      // Get the MIME type of the uploaded image
      $pictureType = mime_content_type($fileTmpName);

      // Insert variation into the `product_variations` table
      $stmt->bind_param("isssss", $productId, $variationName, $variationPrice, $imageData, $pictureType, $expirationDate);
      $stmt->send_long_data(3, $imageData); // Send binary data to the prepared statement
      if (!$stmt->execute()) {
        throw new Exception("Error executing variation insert query: " . $stmt->error);
      }
    }

    $stmt->close();
    $conn->commit();

    echo "Product and variations added successfully!";
  } catch (Exception $e) {
    $conn->rollback();
    echo "Failed to add product: " . $e->getMessage();
  }

  $conn->close();
}
?>

<?php require_once('../templat/header.php') ?>
<?php require_once('../templat/sidebar.php') ?>

<form method="POST" action="addProducts.php" enctype="multipart/form-data" class="m-5">
  <h3>Add Product</h3>

  <label>Product Name:</label>
  <input type="text" name="product_name" required><br>

  <label>Product Description:</label>
  <textarea name="product_description"></textarea><br>

  <h3>Product Variations</h3>
  <div id="variations">
    <div class="variation">
      <label>Variation Name:</label>
      <input type="text" name="variations[0][name]" required><br>

      <label>Price:</label>
      <input type="number" name="variations[0][price]" step="0.01" required><br>

      <label>Picture:</label>
      <input type="file" name="variations[0][picture]" accept="image/*" required><br>

      <label>Expiration Date:</label>
      <input type="date" name="variations[0][expiration_date]" required><br>
    </div>
  </div>

  <button type="button" onclick="addVariation()">Add Variation</button><br><br>

  <button type="submit">Submit</button>
</form>

<script>
  let variationCount = 1;

  function addVariation() {
    const container = document.getElementById('variations');
    const div = document.createElement('div');
    div.className = 'variation';
    div.innerHTML = `
            <label>Variation Name:</label>
            <input type="text" name="variations[${variationCount}][name]" required><br>
            
            <label>Price:</label>
            <input type="number" name="variations[${variationCount}][price]" step="0.01" required><br>

            <label>Picture:</label>
            <input type="file" name="variations[${variationCount}][picture]" accept="image/*" required><br>

            <label>Expiration Date:</label>
            <input type="date" name="variations[${variationCount}][expiration_date]" required><br>
        `;
    container.appendChild(div);
    variationCount++;
  }
</script>

<?php require_once('../templat/footer.php') ?>