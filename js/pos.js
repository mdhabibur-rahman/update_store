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
  const modalHTML = 
    `<div class="modal" id="quantityModal" tabindex="-1">
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
    </div>`;

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
  newRow.innerHTML = 
    `<td>${tbody.rows.length + 1}</td>
     <td>${product.name}</td>
     <td>$${product.price.toFixed(2)}</td>
     <td>${quantity}
       <a href="#" style="color:rgb(249, 52, 12);" class="item_minus"><i class="fa fa-minus"></i></a>
       <a href="#" style="color:rgb(56, 246, 31);" class="item_plus"><i class="fa fa-plus"></i></a>
     </td>
     <td>$${amount}</td>
     <td>
       <a href="#" style="color: #ff5c85;" class="item_delete"><i class="fa fa-trash"></i></a>
     </td>`;

  tbody.appendChild(newRow);

  // Update total
  updateTotal();

  // Add event listener to the remove button
  newRow.querySelector(".item_delete").addEventListener("click", () => {
    newRow.remove();
    updateTotal();
  });

  // Add event listener for the minus button
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

// Update total amount
function updateTotal() {
  let total = 0;
  document.querySelectorAll("#pos_item_td tbody tr").forEach((row) => {
    const amount = parseFloat(row.children[4].textContent.replace("$", ""));
    total += amount;
  });
  document.querySelector(".item_total--value").textContent = `$${total.toFixed(2)}`;
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








document.querySelector('.create_orderBtn').addEventListener('click', function (e) {
  e.preventDefault();

  // Gather order details
  const customerName = document.querySelector('input[name="name"]').value || "N/A";
  const customerEmail = document.querySelector('input[name="email"]').value || "N/A";
  const customerPhone = document.querySelector('input[name="phone"]').value || "N/A";
  const orderItems = document.querySelectorAll('#pos_item_td tbody tr');
  const totalAmount = document.querySelector('.item_total--value').textContent || "$0.00";

  // Get current date and time
  const now = new Date();
  const formattedDateTime = now.toLocaleString(); // Formats the date & time in the user's locale

  // Build the invoice content
  let invoiceHTML = `
    <p><strong>Invoice Date & Time:</strong> ${formattedDateTime}</p>
    <p><strong>Customer Name:</strong> ${customerName}</p>
    <p><strong>Email:</strong> ${customerEmail}</p>
    <p><strong>Phone:</strong> ${customerPhone}</p>
    <hr>
    <table class="table">
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
    <p><strong>Total:</strong> ${totalAmount}</p>
    <button id="printInvoice" class="btn btn-primary mt-3">Print Invoice</button>
  `;

  // Insert the invoice content into the modal
  document.getElementById('invoiceContent').innerHTML = invoiceHTML;

  // Add print functionality
  document.getElementById('printInvoice').addEventListener('click', function () {
    const invoiceContent = document.getElementById('invoiceContent').innerHTML;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
      <html>
        <head>
          <title>Invoice</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        </head>
        <body>
          ${invoiceContent}
        </body>
      </html>
    `);
    printWindow.document.close();
    printWindow.print();
  });

  // Show the modal (requires Bootstrap's JavaScript)
  const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
  invoiceModal.show();
});
