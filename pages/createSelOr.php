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

          // Fetch products and their variations
          $sql = "SELECT pv.id AS variation_id, p.name AS product_name, pv.variation_name, pv.price, pv.picture 
                  FROM products p
                  INNER JOIN product_variations pv ON p.id = pv.product_id";
          $result = $conn->query($sql);

          // Check if products exist
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              // Ensure placeholder image if no picture
              $imagePath = !empty($row['picture']) ? $row['picture'] : '../assets/img/placeholder.png';
          ?>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="productResultContainer">
                  <img
                    src="<?php echo htmlspecialchars($imagePath); ?>"
                    alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                    class="product-img" />
                  <div class="productInfoContainer">
                    <p class="productName"><?php echo htmlspecialchars($row['product_name']); ?></p>
                    <p class="productVariation"><?php echo htmlspecialchars($row['variation_name']); ?></p>
                    <p class="productPrice">$<?php echo number_format($row['price'], 2); ?></p>
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
            <input type="password" class="form-control" name="phone" />
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
            <a href="" class="create_orderBtn" onclick="my_f()">Create order</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
  crossorigin="anonymous"></script>
<!-- Custom JS -->
<script src="../js/pos.js"></script>

<?php require_once('../templat/footer.php'); ?>