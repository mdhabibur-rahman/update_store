// Format date and time
function formatDateTime(date) {
  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  const month = months[date.getMonth()];
  const day = date.getDate();
  const year = date.getFullYear();

  let hours = date.getHours();
  const minutes = date.getMinutes().toString().padStart(2, "0");
  const period = hours >= 12 ? "pm" : "am";

  hours = hours % 12 || 12; // Convert to 12-hour format

  return `${month} ${day}, ${year} ${hours}:${minutes}${period}`;
}

// Update the .timeAndDate element with the formatted date
const now = new Date();
const formattedDate = formatDateTime(now);
document.querySelector(".timeAndDate").textContent = formattedDate;

// Create and manage quantity modal dynamically
let selectedProduct = null;
let isModalOpen = false; // Flag to check if modal is already open

// Function to show the quantity modal
function showQuantityModal(product) {
  if (isModalOpen) return; // Prevent opening multiple modals
  isModalOpen = true; // Set the flag to true to prevent further modal openings

  selectedProduct = product;

  // Create modal HTML
  const modalHTML = `
    <div class="modal" id="quantityModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Enter Quantity</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p id="productNameModal">Enter quantity for ${product.name}:</p>
            <input type="number" id="quantityInput" class="form-control" placeholder="Enter quantity" min="1" value="1" />
            <div class="text-danger mt-2" id="error-message" style="display: none;">Invalid quantity. Please try again.</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="confirmQuantity">Confirm</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  `;

  // Append modal to the body
  document.body.insertAdjacentHTML("beforeend", modalHTML);

  // Show the modal
  const modalElement = document.getElementById("quantityModal");
  const modalInstance = new bootstrap.Modal(modalElement);
  modalInstance.show();

  // Handle confirm button click
  document.getElementById("confirmQuantity").addEventListener("click", () => {
    const quantityInput = document.getElementById("quantityInput");
    const quantity = parseInt(quantityInput.value);

    if (isNaN(quantity) || quantity <= 0) {
      // Show error message if quantity is invalid
      document.getElementById("error-message").style.display = "block";
      return;
    }

    // Add product to table
    addProductToTable(selectedProduct, quantity);

    // Hide and remove modal after the quantity is confirmed
    modalInstance.hide();
    modalElement.remove();
    isModalOpen = false; // Reset modal flag
  });

  // Handle cancel button or modal close
  modalElement.addEventListener("hidden.bs.modal", () => {
    modalElement.remove();
    isModalOpen = false; // Reset modal flag when closed
  });
}

// Add product to the POS table
function addProductToTable(product, quantity) {
  // Calculate total for the product
  const amount = (product.price * quantity).toFixed(2);

  // Get tbody
  const tbody = document.querySelector("#pos_item_td tbody");

  // Add product row
  const newRow = document.createElement("tr");
  newRow.innerHTML = `
    <td>${tbody.rows.length + 1}</td>
    <td>${product.name}</td>
    <td>$${product.price.toFixed(2)}</td>
    <td>${quantity}
      <a href="#" style="color:rgb(249, 52, 12);" class="item_minus"><i class="fa fa-minus"></i></a>
      <a href="#" style="color:rgb(56, 246, 31);" class="item_plus"><i class="fa fa-plus"></i></a>
    </td>
    <td>$${amount}</td>
    <td>
      <a href="#" style="color:rgb(6, 225, 17);" class="item_edit"><i class="fa fa-edit"></i></a>
      <a href="#" style="color: #ff5c85;" class="item_delete"><i class="fa fa-trash"></i></a>
    </td>
  `;
  tbody.appendChild(newRow);

  // Update total
  updateTotal();

  // Add event listener to the remove button
  newRow.querySelector(".item_delete").addEventListener("click", () => {
    newRow.remove();
    updateTotal();
  });

  // Add event listener to the edit button
  newRow.querySelector(".item_edit").addEventListener("click", (event) => {
    event.preventDefault();
    showEditProductModal(newRow); // Show edit modal
  });
}

// Function to show the edit modal for product details
function showEditProductModal(row) {
  const productName = row.querySelector("td:nth-child(2)").textContent;
  const currentQuantity = parseInt(
    row.querySelector("td:nth-child(4)").textContent
  );
  const productPrice = parseFloat(
    row.querySelector("td:nth-child(3)").textContent.replace("$", "")
  );

  // Create modal HTML for editing product details
  const modalHTML = `
    <div class="modal" id="editProductModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Product: ${productName}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="editQuantityInput">Quantity:</label>
            <input type="number" id="editQuantityInput" class="form-control" value="${currentQuantity}" min="1" />
            <div class="text-danger mt-2" id="editError-message" style="display: none;">Invalid quantity. Please try again.</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="confirmEditProduct">Confirm</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  `;

  // Append modal to the body
  document.body.insertAdjacentHTML("beforeend", modalHTML);

  // Show the modal
  const modalElement = document.getElementById("editProductModal");
  const modalInstance = new bootstrap.Modal(modalElement);
  modalInstance.show();

  // Handle confirm button click (update product row)
  document
    .getElementById("confirmEditProduct")
    .addEventListener("click", () => {
      const quantityInput = document.getElementById("editQuantityInput");
      const newQuantity = parseInt(quantityInput.value);

      if (isNaN(newQuantity) || newQuantity <= 0) {
        // Show error message if quantity is invalid
        document.getElementById("editError-message").style.display = "block";
        return;
      }

      // Update product row with the new quantity and total amount
      row.querySelector("td:nth-child(4)").textContent = newQuantity;
      const newAmount = (productPrice * newQuantity).toFixed(2);
      row.querySelector("td:nth-child(5)").textContent = `$${newAmount}`;

      // Update total
      updateTotal();

      // Hide and remove modal after the quantity is confirmed
      modalInstance.hide();
      modalElement.remove();
    });

  // Handle cancel button or modal close
  modalElement.addEventListener("hidden.bs.modal", () => {
    modalElement.remove();
  });
}

// Update total amount
function updateTotal() {
  let total = 0;
  document.querySelectorAll("#pos_item_td tbody tr").forEach((row) => {
    const amount = parseFloat(row.children[4].textContent.replace("$", ""));
    total += amount;
  });
  document.querySelector(".item_total--value").textContent = `$${total.toFixed(
    2
  )}`;
}

// Handle product clicks
document.querySelectorAll(".productResultContainer").forEach((container) => {
  container.addEventListener("click", () => {
    const product = {
      name: container.querySelector(".productName").textContent,
      price: parseFloat(
        container.querySelector(".productPrice").textContent.replace("$", "")
      ),
    };
    showQuantityModal(product);
  });
});
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.querySelector(".searchInputContainer input");
  const productContainers = Array.from(
    document.querySelectorAll(".productResultContainer")
  );
  const productWrapper = document.querySelector(".searchResultContainer .row");

  // Function to display all products
  function displayAllProducts() {
    // Clear current displayed products
    productWrapper.innerHTML = "";

    // Append all products to the container
    productContainers.forEach((container) => {
      const col = document.createElement("div");
      col.classList.add("col-md-4"); // Ensures each product takes up 4 columns (3 products per row)
      col.appendChild(container);
      productWrapper.appendChild(col);
    });
  }

  // Function to filter and sort products based on the search term
  function filterAndSortProducts() {
    const searchTerm = searchInput.value.toLowerCase(); // Get the input value in lowercase

    // If there's no search term, display all products
    if (!searchTerm) {
      displayAllProducts();
      return;
    }

    const filteredProducts = productContainers.filter((container) => {
      const productName = container
        .querySelector(".productName")
        .textContent.toLowerCase(); // Get product name in lowercase
      return productName.includes(searchTerm); // Include products where name matches the search term
    });

    // Sort the filtered products based on the product name (you can also sort by price, etc.)
    filteredProducts.sort((a, b) => {
      const nameA = a.querySelector(".productName").textContent.toLowerCase();
      const nameB = b.querySelector(".productName").textContent.toLowerCase();
      return nameA.localeCompare(nameB); // Sorting alphabetically by product name
    });

    // Clear the current displayed products
    productWrapper.innerHTML = "";

    // Append sorted filtered products to the container
    filteredProducts.forEach((container) => {
      const col = document.createElement("div");
      col.classList.add("col-md-4"); // Ensures each product takes up 4 columns (3 products per row)
      col.appendChild(container);
      productWrapper.appendChild(col);
    });
  }

  // Add event listener to trigger filter when user types in the search input
  searchInput.addEventListener("input", filterAndSortProducts);

  // Initially display all products when the page loads
  displayAllProducts();
});

// Add product to the POS table
function addProductToTable(product, quantity) {
  // Calculate total for the product
  const amount = (product.price * quantity).toFixed(2);

  // Get tbody
  const tbody = document.querySelector("#pos_item_td tbody");

  // Add product row
  const newRow = document.createElement("tr");
  newRow.innerHTML = `
    <td>${tbody.rows.length + 1}</td>
    <td>
    <span class="price-display">${product.name}</span>
    <input type="hidden" name="product_name" value="${product.name}">
    </td>
    <td>
    <span class="price-display">$${product.price.toFixed(2)}</span>
    <input type="hidden" name="product_price" value="${product.price.toFixed(
      2
    )}">
    </td>
    <td>
      <span class="quantity">${quantity}</span>
      <input type="hidden" name="product_quantity" value="${quantity}">
      <a href="#" style="color:rgb(249, 52, 12);" class="item_minus"><i class="fa fa-minus"></i></a>
      <a href="#" style="color:rgb(56, 246, 31);" class="item_plus"><i class="fa fa-plus"></i></a>
    </td>
    <td>
    $${amount}
    <input type="hidden" name="product_amount" value="${amount}">
    </td>
    <td>
      <a href="#" style="color:rgb(6, 225, 17);" class="item_edit"><i class="fa fa-edit"></i></a>
      <a href="#" style="color: #ff5c85;" class="item_delete"><i class="fa fa-trash"></i></a>
    </td>
  `;
  tbody.appendChild(newRow);

  // Update total
  updateTotal();

  // Add event listener to the remove button
  newRow.querySelector(".item_delete").addEventListener("click", () => {
    newRow.remove();
    updateTotal();
  });

  // Add event listener to the edit button
  newRow.querySelector(".item_edit").addEventListener("click", (event) => {
    event.preventDefault();
    showEditProductModal(newRow); // Show edit modal
  });

  // Event listener for the minus button
  newRow.querySelector(".item_minus").addEventListener("click", () => {
    let quantityElement = newRow.querySelector(".quantity");
    let currentQuantity = parseInt(quantityElement.textContent);

    if (currentQuantity > 1) {
      currentQuantity--;
      quantityElement.textContent = currentQuantity;

      // Update the amount
      const newAmount = (product.price * currentQuantity).toFixed(2);
      newRow.querySelector("td:nth-child(5)").textContent = `$${newAmount}`;

      // Update total
      updateTotal();
    }
  });

  // Event listener for the plus button
  newRow.querySelector(".item_plus").addEventListener("click", () => {
    let quantityElement = newRow.querySelector(".quantity");
    let currentQuantity = parseInt(quantityElement.textContent);

    currentQuantity++;
    quantityElement.textContent = currentQuantity;

    // Update the amount
    const newAmount = (product.price * currentQuantity).toFixed(2);
    newRow.querySelector("td:nth-child(5)").textContent = `$${newAmount}`;

    // Update total
    updateTotal();
  });
}

// Function to calculate the total amount from the POS table
function getTotalAmount() {
  let totalAmount = 0;
  document.querySelectorAll("#pos_item_td tbody tr").forEach((row) => {
    const amount = parseFloat(row.children[4].textContent.replace("$", ""));
    totalAmount += amount;
  });
  return totalAmount.toFixed(2);
}

// Main function to display data in a new document
function my_f() {
  var name = document.order.name.value;
  var email = document.order.email.value;
  var phone = document.order.phone.value;

  var product_name = document.order.product_name.value;
  var product_price = document.order.product_price.value;
  var product_quantity = document.order.product_quantity.value;
  var product_amount = document.order.product_amount.value;

  // Get the total amount from the table
  var totalAmount = getTotalAmount();

  // Get the current date and time
  const now = new Date();
  const formattedDate = formatDateTime(now);

  // Open a new window and write the data
  var doc = open("", "", "width=800px,height=700px");
  with (doc.document) {
    write(`
      <html>
        <head>
          <title>Order Summary</title>
          <style>
            body {
              font-family: Arial, sans-serif;
              padding: 20px;
              line-height: 1.6;
            }
            h1 {
              text-align: center;
              color: #333;
            }
            table {
              width: 100%;
              border-collapse: collapse;
              margin: 20px 0;
            }
            table, th, td {
              border: 1px solid #ccc;
            }
            th, td {
              padding: 10px;
              text-align: left;
            }
            .total {
              font-weight: bold;
              text-align: right;
            }
            .button-container {
              margin-top: 20px;
              text-align: center;
            }
            .action-btn {
              display: inline-block;
              margin: 10px;
              padding: 10px 20px;
              background: #007bff;
              color: white;
              text-align: center;
              text-decoration: none;
              border-radius: 5px;
              cursor: pointer;
              font-size: 16px;
              transition: background 0.3s;
              border: none;
            }
            .action-btn:hover {
              background: #0056b3;
            }
          </style>
        </head>
        <body>
          <h1>Astha  <img src="../assets/img/logo2.jpg" width="50px" alt=""></h1>
          <p style="font-size:35px; text-align: center; position: relative; bottom: 30px; right:11px">Order Invoice</p>
          <p>Date & Time: ${formattedDate}</p>
          <div style="overflow: hidden; margin-bottom: 20px;">
            <div style="float: left; width: 50%;">
              <p><strong>Astha</strong></p>
              <p style="position: relative; bottom: 15px">Lalbagh, Dhaka-1211. Phone: 01888888888</p>
            </div>
            <div style="float: right; width: 50%; text-align: right;">
              <p><strong>Billed to:</strong></p>
              <p style="font-size: 12px; position: relative; bottom: 10px">${name}</p>
              <p style="font-size: 12px; position: relative; bottom: 20px">${email}</p>
              <p style="font-size: 12px; position: relative; bottom: 30px">${phone}</p>
            </div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>${product_name}</td>
                <td>$${product_price}</td>
                <td>${product_quantity}</td>
                <td>$${product_amount}</td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="total">Total Amount:</td>
                <td class="total">$${totalAmount}</td>
              </tr>
            </tfoot>
          </table>
          <div class="button-container">
            <button class="action-btn" onclick="window.print()">Print</button>
            <button class="action-btn" onclick="saveInvoice()">Save</button>
          </div>
          <script>
            function saveInvoice() {
              const blob = new Blob([document.documentElement.outerHTML], { type: "text/html" });
              const link = document.createElement("a");
              link.href = URL.createObjectURL(blob);
              link.download = "order_invoice.html";
              link.click();
            }
          </script>
        </body>
      </html>
    `);
    close(); // Close the document writing stream
  }
}
