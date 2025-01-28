<?php
// Get the order ID from the query parameter
$orderId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$orderId) {
  echo "No order found with ID: " . htmlspecialchars($orderId);
  exit;  // Exit if ID is not provided
}

// Now, fetch the order details from the database using the orderId
$conn = new mysqli("localhost", "root", "", "astha");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Query to get the order details
$sql = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$orderResult = $stmt->get_result();

if ($orderResult->num_rows > 0) {
  // Order found, display it
  $order = $orderResult->fetch_assoc();
  // Now, you can fetch order items
  $sqlItems = "SELECT * FROM order_items WHERE order_id = ?";
  $stmtItems = $conn->prepare($sqlItems);
  $stmtItems->bind_param("i", $orderId);
  $stmtItems->execute();
  $orderItemsResult = $stmtItems->get_result();

  // Output order details and items here
} else {
  echo "No order found with ID: " . htmlspecialchars($orderId);
}
$conn->close();
