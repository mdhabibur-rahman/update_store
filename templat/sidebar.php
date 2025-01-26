    <!--begin::Sidebar-->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="light">
      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./index.html" class="brand-link">
          <!--begin::Brand Image-->
          <p style="font-size: 40px; color:#333; margin-top:10px"><strong>Astha</strong> <img src="../assets/img/logo2.jpg" width="50px" alt=""></p>
          <!--end::Brand Image-->
          <!--begin::Brand Text-->

          <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
      </div>
      <!--end::Sidebar Brand-->
      <!--begin::Sidebar Wrapper-->
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <!--begin::Sidebar Menu-->
          <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="menu"
            data-accordion="false">
            <li class="nav-item menu-open">
              <a href="#" class="nav-link active">
                <i class="nav-icon bi bi-speedometer"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-clipboard-fill"></i>
                <p>
                  Products
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="viewProducts.php" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Products</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="addProducts.php" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add Products</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="viewExpiredVariations.php" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Products Expiry </p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-tree-fill"></i>
                <p>
                  Categories
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./UI/general.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Categories</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./UI/icons.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Sub-Categories</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./UI/timeline.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add Categories</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./UI/timeline.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add Sub-Categories</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-pencil-square"></i>
                <p>
                  Brands
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./forms/general.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Brands</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./forms/general.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add Brands</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./forms/general.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Restore Brands</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-table"></i>
                <p>
                  Units
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="viewUnit.php" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Units</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="addUnits.php" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add Units</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="restoreUnit.php" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Restore Units</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-table"></i>
                <p>
                  Sales
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./tables/simple.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Sales Order</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="createSelOr.php" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Create Sales Order</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./tables/simple.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Payment Management</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-table"></i>
                <p>
                  Purchase
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./tables/simple.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Purchase Order</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./tables/simple.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Create Purchase Order</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./tables/simple.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Payment Management</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-table"></i>
                <p>
                  Returns
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./tables/simple.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Sales Returns</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./tables/simple.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Purchase Returns</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-table"></i>
                <p>
                  Suppliers
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="../pages/viewSuppliers.php" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Suppliers</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../pages/addSuppliers.php" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add Suppliers</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../pages/restoreSup.php" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Restore Supplier</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-table"></i>
                <p>
                  Users
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./tables/simple.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Users</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./tables/simple.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add Users</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-table"></i>
                <p>
                  Roles
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./tables/simple.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Roles</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./tables/simple.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add roles</p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
          <!--end::Sidebar Menu-->
        </nav>
      </div>
      <!--end::Sidebar Wrapper-->
    </aside>
    <!--end::Sidebar-->