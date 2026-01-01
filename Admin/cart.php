<?php 
session_start();
if (!isset($_SESSION["isValidAdmin"])) {
    header("Location: login.php");
}

include_once('../db.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Admin Cart | Decora</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<style>
/* Nested table style */
.sub-cart-table {
    margin: 10px 0 0 0;
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 5px;
    background: #f9f9f9;
}
.sub-cart-table td, .sub-cart-table th {
    padding: 6px 10px;
}
.user-row {
    background: #e9ecef;
    font-weight: 600;
}
</style>
</head>

<body>
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">

    <?php include("inc/menu_left.php"); ?>

    <div class="layout-page">
      <?php include("inc/nav.php"); ?>

      <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
          <h4 class="fw-bold py-3 mb-4">User Carts</h4>

          <div class="card">
            <h5 class="card-header">All Cart Items</h5>
            <div class="card-body table-responsive">

<?php
// Fetch cart items ordered by user
$sql = "SELECT c.id AS cart_id, u.id AS user_id, u.name AS user_name,
               p.id AS product_id, p.product_name, p.price, c.quantity, c.created_at
        FROM cart c
        LEFT JOIN user u ON c.user_id = u.id
        LEFT JOIN products p ON c.product_id = p.id
        ORDER BY u.name, c.created_at DESC";

$result = $mydb->query($sql);

$prev_user_id = null;
$cart_items = [];

while($row = $result->fetch_object()){
    if($prev_user_id !== $row->user_id){
        if($prev_user_id !== null){
            // Display previous user's cart items
            echo '<tr class="user-row">
                    <td><span style="color:#696cff; font-weight: bolder; "> | </span> '.$prev_user_name.'</td>
                    <td> Id: '.$prev_user_id.'</td>
                    <td colspan="4">
                        <table class="sub-cart-table">';
            echo '<thead><tr><th>Product ID</th><th>Name</th><th>Price</th><th>Qty</th><th>Added At</th><th>Action</th></tr></thead><tbody>';
            foreach($cart_items as $item){
                echo '<tr>
                        <td>'.$item->product_id.'</td>
                        <td>'.$item->product_name.'</td>
                        <td>$'.number_format($item->price,2).'</td>
                        <td>'.$item->quantity.'</td>
                        <td>'.$item->created_at.'</td>
                        <td><button class="btn btn-sm btn-danger" onclick="deleteCart('.$item->cart_id.')">Delete</button></td>
                      </tr>';
            }
            echo '</tbody></table>
                    </td>
                  </tr>';
        }
        $prev_user_id = $row->user_id;
        $prev_user_name = $row->user_name;
        $cart_items = [];
    }
    $cart_items[] = $row;
}

// Display last user's cart
if(!empty($cart_items)){
    echo '<br><br>
        <tr class="user-row">
            <td><span style="color:#696cff; font-weight: bolder; "> | </span> '.$prev_user_name.'</td>
            <td>Id: '.$prev_user_id.'</td>
            <td colspan="4">
                <table class="sub-cart-table">';
    echo '<thead><tr><th>Product ID</th><th>Name</th><th>Price</th><th>Qty</th><th>Added At</th><th>Action</th></tr></thead><tbody>';
    foreach($cart_items as $item){
        echo '<tr>
                <td>'.$item->product_id.'</td>
                <td>'.$item->product_name.'</td>
                <td>$'.number_format($item->price,2).'</td>
                <td>'.$item->quantity.'</td>
                <td>'.$item->created_at.'</td>
                <td><button class="btn btn-sm btn-danger" onclick="deleteCart('.$item->cart_id.')">Delete</button></td>
              </tr>';
    }
    echo '</tbody></table>
            </td>
          </tr>';
}
?>

            </div>
          </div>

        </div>

        <?php include("inc/footer.php"); ?>
      </div>
    </div>
  </div>
  <div class="layout-overlay layout-menu-toggle"></div>
</div>

<script>
function deleteCart(id){
  Swal.fire({
    title: 'Are you sure?',
    text: "This cart item will be deleted permanently!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'cart_delete.php?id=' + id;
    }
  });
}
</script>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/dashboards-analytics.js"></script>
</body>
</html>
