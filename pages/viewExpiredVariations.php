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

// Query for expired variations (with product name)
$expiredQuery = "
  SELECT pv.*, p.name AS product_name
  FROM product_variations pv
  JOIN products p ON pv.product_id = p.id
  WHERE pv.expiration_date < CURDATE()
";
$expiredResult = $conn->query($expiredQuery);

// Query for variations expiring in the next 30 days (with product name)
$expiring30Query = "
  SELECT pv.*, p.name AS product_name
  FROM product_variations pv
  JOIN products p ON pv.product_id = p.id
  WHERE pv.expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
";
$expiring30Result = $conn->query($expiring30Query);

// Query for variations expiring in the next 7 days (with product name)
$expiring7Query = "
  SELECT pv.*, p.name AS product_name
  FROM product_variations pv
  JOIN products p ON pv.product_id = p.id
  WHERE pv.expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
";
$expiring7Result = $conn->query($expiring7Query);
?>

<?php require_once('../templat/header.php') ?>
<?php require_once('../templat/sidebar.php') ?>

<div class="container mt-5">
  <h3 class="text-center">Expired Product Variations</h3>

  <h4 class="mt-4">Expired Variations</h4>
  <?php if ($expiredResult->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-striped text-dark table-bordered rounded-3">
        <thead class="bg-dark text-light rounded-top">
          <tr>
            <th>Product Id</th>
            <th>Product Name</th>
            <th>Variation Name</th>
            <th>Price</th>
            <th>Expiration Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($variation = $expiredResult->fetch_assoc()): ?>
            <tr class="bg-dark">
              <td><?= htmlspecialchars($variation['product_id']) ?></td>
              <td><?= htmlspecialchars($variation['product_name']) ?></td>
              <td><?= htmlspecialchars($variation['variation_name']) ?></td>
              <td>$<?= number_format($variation['price'], 2) ?></td>
              <td><?= htmlspecialchars($variation['expiration_date']) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-warning shadow">
      <strong>Oops!</strong> No expired variations found.
    </div>
  <?php endif; ?>

  <h4 class="mt-4">Variations Expiring in 30 Days</h4>
  <?php if ($expiring30Result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-striped text-dark table-bordered rounded-3">
        <thead class="bg-dark text-light rounded-top">
          <tr>
            <th>Product Id</th>
            <th>Product Name</th>
            <th>Variation Name</th>
            <th>Price</th>
            <th>Expiration Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($variation = $expiring30Result->fetch_assoc()): ?>
            <tr class="bg-dark">
              <td><?= htmlspecialchars($variation['product_id']) ?></td>
              <td><?= htmlspecialchars($variation['product_name']) ?></td>
              <td><?= htmlspecialchars($variation['variation_name']) ?></td>
              <td>$<?= number_format($variation['price'], 2) ?></td>
              <td><?= htmlspecialchars($variation['expiration_date']) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-warning shadow">
      <strong>Oops!</strong> No variations expiring in the next 30 days found.
    </div>
  <?php endif; ?>

  <h4 class="mt-4">Variations Expiring in 7 Days</h4>
  <?php if ($expiring7Result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-striped text-dark table-bordered rounded-3">
        <thead class="bg-dark text-light rounded-top">
          <tr>
            <th>Product Id</th>
            <th>Product Name</th>
            <th>Variation Name</th>
            <th>Price</th>
            <th>Expiration Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($variation = $expiring7Result->fetch_assoc()): ?>
            <tr class="bg-dark">
              <td><?= htmlspecialchars($variation['product_id']) ?></td>
              <td><?= htmlspecialchars($variation['product_name']) ?></td>
              <td><?= htmlspecialchars($variation['variation_name']) ?></td>
              <td>$<?= number_format($variation['price'], 2) ?></td>
              <td><?= htmlspecialchars($variation['expiration_date']) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-warning shadow">
      <strong>Oops!</strong> No variations expiring in the next 7 days found.
    </div>
  <?php endif; ?>

  <?php $conn->close(); ?>
</div>

<?php require_once('../templat/footer.php') ?>