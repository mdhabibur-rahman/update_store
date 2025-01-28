<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "astha");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch all purchase orders
$sql = "SELECT po.id, s.name AS supplier_name, po.product_name, po.product_price, po.quantity, po.order_date
        FROM purchase_orders po
        INNER JOIN suppliers s ON po.supplier_id = s.id
        ORDER BY po.order_date DESC";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<?php require_once('../templat/header.php'); ?>
<?php require_once('../templat/sidebar.php'); ?>

<div class="container-fluid my-5">
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <!-- View All Orders -->
      <div class="ordersContainer">
        <h3>All Purchase Orders</h3>

        <!-- Check if there are any orders -->
        <?php if ($result->num_rows > 0): ?>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Supplier</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $row['id']; ?></td>
                  <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                  <td>$<?php echo number_format($row['product_price'], 2); ?></td>
                  <td><?php echo $row['quantity']; ?></td>
                  <td>$<?php echo number_format($row['product_price'] * $row['quantity'], 2); ?></td>
                  <td><?php echo date("F j, Y h:i A", strtotime($row['order_date'])); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No orders found.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require_once('../templat/footer.php'); ?>