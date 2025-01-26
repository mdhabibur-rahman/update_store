<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "astha";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
  $variationId = intval($_GET['id']);

  $sql = "SELECT picture FROM product_variations WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $variationId);
  $stmt->execute();
  $stmt->bind_result($imageData);
  $stmt->fetch();
  $stmt->close();

  if ($imageData) {
    header("Content-Type: image/jpeg"); // Adjust based on the image type stored
    echo $imageData;
  } else {
    http_response_code(404);
    echo "Image not found.";
  }
} else {
  http_response_code(400);
  echo "Invalid request.";
}

$conn->close();
