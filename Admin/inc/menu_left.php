<?php $page = basename($_SERVER['PHP_SELF']); ?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- Brand -->
  <div class="app-brand demo">
    <a href="index.php" class="app-brand-link">
      <span class="app-brand-logo demo">
        <!-- logo svg unchanged -->
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2">Decora</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    <!-- Dashboard -->
    <li class="menu-item <?= ($page == 'index.php') ? 'active' : '' ?>">
      <a href="index.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>Dashboard</div>
      </a>
    </li>

    <!-- Pages -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Pages</span>
    </li>

    <!-- Account -->
    <li class="menu-item <?= ($page == 'account.php' || $page == 'account_list.php') ? 'active open' : '' ?>">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>Account Settings</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item <?= ($page == 'account.php') ? 'active' : '' ?>">
          <a href="account.php" class="menu-link">Account</a>
        </li>
        <li class="menu-item <?= ($page == 'account_list.php') ? 'active' : '' ?>">
          <a href="account_list.php" class="menu-link">Account List</a>
        </li>
      </ul>
    </li>

    <!-- Services -->
    <li class="menu-item <?= ($page == 'service.php' || $page == 'service_list.php') ? 'active open' : '' ?>">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-briefcase"></i>
        <div>Services</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item <?= ($page == 'service.php') ? 'active' : '' ?>">
          <a href="service.php" class="menu-link">Add Service</a>
        </li>
        <li class="menu-item <?= ($page == 'service_list.php') ? 'active' : '' ?>">
          <a href="service_list.php" class="menu-link">Service List</a>
        </li>
      </ul>
    </li>

    <!-- Products -->
    <li class="menu-item <?= ($page == 'product.php' || $page == 'product_list.php') ? 'active open' : '' ?>">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-package"></i>
        <div>Products</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item <?= ($page == 'product.php') ? 'active' : '' ?>">
          <a href="product.php" class="menu-link">Add Product</a>
        </li>
        <li class="menu-item <?= ($page == 'product_list.php') ? 'active' : '' ?>">
          <a href="product_list.php" class="menu-link">Products List</a>
        </li>
      </ul>
    </li>

    <!-- Cart -->
    <li class="menu-item <?= ($page == 'cart.php') ? 'active' : '' ?>">
      <a href="cart.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-cart"></i>
        <div>Cart</div>
      </a>
    </li>

    <!-- Orders / Demo Payment -->
    <li class="menu-item <?= ($page == 'orders.php') ? 'active' : '' ?>">
      <a href="orders.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-credit-card"></i>
        <div>Orders / Payments</div>
      </a>
    </li>

    <!-- Requests -->
    <li class="menu-item <?= ($page == 'request_list.php') ? 'active' : '' ?>">
      <a href="request_list.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-list-check"></i>
        <div>Requests</div>
      </a>
    </li>

    <!-- Reviews -->
    <li class="menu-item <?= ($page == 'review_list.php') ? 'active' : '' ?>">
      <a href="review_list.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-star"></i>
        <div>Reviews</div>
      </a>
    </li>

  </ul>
</aside>
