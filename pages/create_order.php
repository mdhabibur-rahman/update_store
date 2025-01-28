<?php
// Create order PHP script (create_order.php)

// Database connection
$conn = new mysqli("localhost", "root", "", "astha");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$items = json_decode($_POST['items'], true); // Decode JSON data

// Start a transaction
$conn->begin_transaction();

try {
  // Insert order details into the orders table
  $stmt = $conn->prepare("INSERT INTO orders (name, email, phone) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $email, $phone);
  $stmt->execute();
  $orderId = $stmt->insert_id; // Get the order ID

  // Insert each order item into the order_items table
  foreach ($items as $item) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, quantity, amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdis", $orderId, $item['product_name'], $item['price'], $item['quantity'], $item['amount']);
    $stmt->execute();
  }

  // Commit the transaction
  $conn->commit();

  // Return the order ID as a response
  echo json_encode(['order_id' => $orderId]);
} catch (Exception $e) {
  // Rollback the transaction on error
  $conn->rollback();
  echo json_encode(['error' => 'Order creation failed']);
}

$conn->close();
