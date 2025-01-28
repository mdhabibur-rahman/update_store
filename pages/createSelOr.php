<?php require_once('../templat/header.php'); ?>
<?php require_once('../templat/sidebar.php'); ?>

<div class="container-fluid my-5">
  <div class="row">
    <div class="col-lg-8 col-md-12">
      <!-- Search Input -->
      <div class="searchInputContainer mb-4">
        <input
          type="text"
          class="form-control"
          placeholder="Search product..." />
      </div>

      <!-- Product Results -->
      <div class="searchResultContainer">
        <div class="row g-4">
          <?php
          // Database connection
          $conn = new mysqli("localhost", "root", "", "astha");

          // Check connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          // Fetch products and their variations where is_visible is 1
          $sql = "SELECT pv.id AS variation_id, p.name AS product_name, pv.variation_name, pv.price, pv.picture 
                  FROM products p
                  INNER JOIN product_variations pv ON p.id = pv.product_id
                  WHERE pv.is_visible = 1"; // Filter out invisible products
          $result = $conn->query($sql);

          // Check if products exist
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              // Convert the binary image data to base64 if available
              if (!empty($row['picture'])) {
                $imageData = base64_encode($row['picture']);
                $imageSrc = "data:image/jpeg;base64,{$imageData}";
              } else {
                $imageSrc = '../assets/img/placeholder.png'; // Placeholder image if no picture
              }
          ?>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="productResultContainer card">
                  <img
                    src="<?php echo $imageSrc; ?>"
                    alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                    class="card-img-top product-img" />
                  <div class="card-body productInfoContainer">
                    <h5 class="card-title productName"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                    <p class="card-text productVariation"><?php echo htmlspecialchars($row['variation_name']); ?></p>
                    <p class="card-text productPrice">$<?php echo number_format($row['price'], 2); ?></p>
                  </div>
                </div>
              </div>
          <?php
            }
          } else {
            echo "<p class='text-center'>No products found.</p>";
          }

          $conn->close();
          ?>
        </div>
      </div>
    </div>

    <!-- POS Order Summary -->
    <div class="col-lg-4 col-md-12 posOrderContainer">
      <div class="pos_header">
        <div class="setting alignRight">
          <a href=""><i class="fa-sharp fa-solid fa-gear"></i></a>
        </div>
        <p class="logo">Order Product</p>
        <p class="timeAndDate"><?php echo date("F j, Y h:i A"); ?></p>
      </div>

      <!-- Customer Details -->
      <div class="mt-4">
        <form name="order">
          <div class="mb-3">
            <label class="form-label">Customer name</label>
            <input type="text" class="form-control" name="name" />
          </div>
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" />
          </div>
          <div class="mb-3">
            <label class="form-label">Phone number</label>
            <input type="text" class="form-control" name="phone" />
          </div>

          <!-- POS Item Table -->
          <div class="pos_item_cotainer">
            <table class="table table-striped" id="pos_item_td">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Qty</th>
                  <th>Amount</th>
                  <th></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>

            <!-- Item Total -->
            <div class="item_total_container">
              <p class="item_total">
                <span class="item_total--label">TOTAL</span>
                <span class="item_total--value">$0.00</span>
              </p>
            </div>
          </div>

          <!-- Checkout Button -->
          <div class="create_orderBtnConteiner">
            <a href="" class="create_orderBtn">Create order</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
  .productResultContainer.card {
    color: white;
  }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {
        $(".create_orderBtn").click(function(e) {
              e.preventDefault(); // Prevent the default link behavior

              // Get customer details
              var customerName = $("input[name='name']").val();
              var customerEmail = $("input[name='email']").val();
              var customerPhone = $("input[name='phone']").val();

              // Get order items
              var orderItems = [];
              $("#pos_item_td tbody tr").each(function() {
                var productName = $(this).find("td:nth-child(2)").text();
                var productPrice = parseFloat($(this).find("td:nth-child(3)").text().replace('$', ''));
                var productQty = parseInt($(this).find("td:nth-child(4)").text());
                var productAmount = parseFloat($(this).find("td:nth-child(5)").text().replace('$', ''));

                orderItems.push({
                  product_name: productName,
                  price: productPrice,
                  quantity: productQty,
                  amount: productAmount,
                });
              });

              // Send data to the server (AJAX)
              $.ajax({
                url: 'create_order.php', // The PHP file to handle the order creation
                type: 'POST',
                data: {
                  name: customerName,
                  email: customerEmail,
                  phone: customerPhone,
                  items: JSON.stringify(orderItems), // Send items as JSON
                },
                success: function(response) {
                  // Parse the JSON response
                  var data = JSON.parse(response);
                  if (data.order_id) {
                    // Redirect to the invoice page with the order ID in the URL
                    window.location.href = "invoice.php?id=" + data.order_id;
                  } else {
                    alert("Failed to create order.");
                  }
                },
                error: function() {
                  alert("There was an error creating the order. Please try again.");
                },
              });
</script>


<?php require_once('../templat/footer.php'); ?>