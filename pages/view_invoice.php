<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "astha");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get order ID from the URL
$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Fetch the order data
$sql = "SELECT o.id, o.customer_name, o.customer_email, o.customer_phone, o.total_price, o.order_date, 
               od.product_id, od.quantity, od.price, od.total_price AS product_total, p.name AS product_name
        FROM orders o
        INNER JOIN order_details od ON o.id = od.order_id
        INNER JOIN products p ON od.product_id = p.id
        WHERE o.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

// If no order found
if ($result->num_rows === 0) {
  echo "Order not found.";
  exit;
}

$order = $result->fetch_assoc();

$conn->close();
?>

<?php require_once('../templat/header.php'); ?>
<?php require_once('../templat/sidebar.php'); ?>

<div class="container-fluid my-5">
  <h3>Invoice for Order #<?php echo $order['id']; ?></h3>
  <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
  <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
  <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
  <p><strong>Order Date:</strong> <?php echo date("F j, Y h:i A", strtotime($order['order_date'])); ?></p>
  <p><strong>Total Price:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>

  <h4>Order Details</h4>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Display the order details
      do {
      ?>
        <tr>
          <td><?php echo htmlspecialchars($order['product_name']); ?></td>
          <td><?php echo $order['quantity']; ?></td>
          <td>$<?php echo number_format($order['price'], 2); ?></td>
          <td>$<?php echo number_format($order['product_total'], 2); ?></td>
        </tr>
      <?php
      } while ($order = $result->fetch_assoc());
      ?>
    </tbody>
  </table>
</div>

<?php require_once('../templat/footer.php'); ?>