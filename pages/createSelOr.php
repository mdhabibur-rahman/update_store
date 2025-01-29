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

<!-- Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="invoiceModalLabel">Invoice</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="invoiceContent">
        <!-- Invoice content will be inserted here dynamically -->
      </div>
    </div>
  </div>
</div>

<script>
  document.querySelector('.create_orderBtn').addEventListener('click', function(e) {
    e.preventDefault();

    // Generate a static or dynamic invoice number
    const invoiceNumber = "#INV" + Math.floor(Math.random() * 100000);

    // Set the invoice number in the modal label
    document.getElementById('invoiceModalLabel').textContent = `Invoice ${invoiceNumber}`;

    // Gather order details
    const customerName = document.querySelector('input[name="name"]').value || "N/A";
    const customerEmail = document.querySelector('input[name="email"]').value || "N/A";
    const customerPhone = document.querySelector('input[name="phone"]').value || "N/A";
    const orderItems = document.querySelectorAll('#pos_item_td tbody tr');
    const totalAmount = document.querySelector('.item_total--value').textContent || "$0.00";

    // Get current date and time
    const now = new Date();
    const formattedDateTime = now.toLocaleString();

    // Build the invoice content
    let invoiceHTML = `
    <div class="invoice-header" style="text-align: center; margin-bottom: 20px;">
      <h4 style="display: inline; vertical-align: middle; margin-left:20px">Astha</h4>
      <img src="https://t4.ftcdn.net/jpg/02/66/71/71/360_F_266717164_J8Fqw4OcXRkKtNwFyHD02zIEsxPI7qHH.jpg" alt="Astha Logo" style="height: 40px; vertical-align: middle; margin-right: 10px;"><br><br>
      <p>Lalbagh, Dhaka-1211.Phone: 01xxxxxxxxx</p>
    </div>

    <div class="invoice-details" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
      <div style="text-align: left;">
        <p><strong>Invoice Number:</strong> ${invoiceNumber}</p>
        <p><strong>Date & Time:</strong> ${formattedDateTime}</p>
        <p><strong>Customer Name:</strong> ${customerName}</p>
        <p><strong>Email:</strong> ${customerEmail}</p>
        <p><strong>Phone:</strong> ${customerPhone}</p>
      </div>
    </div>

    <hr>

    <table class="table table-bordered" style="width: 100%; text-align: center;">
      <thead>
        <tr>
          <th>#</th>
          <th>Product</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
  `;

    orderItems.forEach((row, index) => {
      const columns = row.querySelectorAll('td');
      invoiceHTML += `
      <tr>
        <td>${index + 1}</td>
        <td>${columns[1].textContent}</td>
        <td>${columns[2].textContent}</td>
        <td>${columns[3].textContent}</td>
        <td>${columns[4].textContent}</td>
      </tr>
    `;
    });

    invoiceHTML += `
      </tbody>
    </table>

    <div style="text-align: right; font-weight: bold;">
      <p><strong>Total:</strong> ${totalAmount}</p>
    </div>

    <button id="printInvoice" class="btn btn-primary mt-3">Print Invoice</button>
  `;

    // Insert the invoice content into the modal
    document.getElementById('invoiceContent').innerHTML = invoiceHTML;

    // Add print functionality
    document.getElementById('printInvoice').addEventListener('click', function() {
      const invoiceContent = document.getElementById('invoiceContent').innerHTML;

      const printWindow = window.open('', '_blank');
      printWindow.document.write(`
    <html>
      <head>
        <title>Invoice</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <style>
          body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
          }
          .invoice-header {
            text-align: center;
            margin-bottom: 20px;
          }
          .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            text-align: left;
          }
          table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
          }
          th, td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
          }
          th {
            background-color: #f4f4f4;
          }
          .invoice-footer {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
          }
          @media print {
            #printInvoice {
              display: none; /* Hide the print button */
            }
            body {
              margin: 0;
            }
            .invoice-header {
              text-align: center;
            }
            .invoice-details {
              display: flex;
              justify-content: space-between;
              text-align: left;
            }
          }
        </style>
      </head>
      <body>
        <div class="invoice-content">
          ${invoiceContent}
        </div>
      </body>
    </html>
    `);
      printWindow.document.close();
      printWindow.print();
    });

    // Send invoice data to the server (AJAX request)
    const invoiceData = {
      invoiceNumber: invoiceNumber,
      dateTime: formattedDateTime,
      customerName: customerName,
      customerEmail: customerEmail,
      customerPhone: customerPhone,
      items: [],
      totalAmount: totalAmount
    };

    orderItems.forEach((row) => {
      const columns = row.querySelectorAll('td');
      invoiceData.items.push({
        product: columns[1].textContent,
        price: columns[2].textContent,
        quantity: columns[3].textContent,
        amount: columns[4].textContent
      });
    });

    // Send the data to the server using AJAX
    fetch('save_invoice.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(invoiceData)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          console.log('Invoice saved successfully!');
        } else {
          console.error('Failed to save invoice!');
        }
      })
      .catch(error => {
        console.error('Error saving invoice:', error);
        alert('Error saving invoice!');
      });

    // Show the modal (requires Bootstrap's JavaScript)
    const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
    invoiceModal.show();
  });
</script>


<style>
  /* Hide elements during printing */
  @media print {
    #printInvoice {
      display: none;
      /* Hide the print button */
    }

    .modal-header,
    .modal-footer {
      display: none;
      /* Optionally hide modal header and footer */
    }

    /* Adjust body or content for printing */
    body {
      margin: 0;
      padding: 0;
      color: black;
      background-color: white;
    }

    .modal-body {
      padding: 0;
    }
  }
</style>
<?php require_once '../templat/footer.php' ?>