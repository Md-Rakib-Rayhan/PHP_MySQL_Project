<?php 
session_start();
if (!isset($_SESSION["isValidAdmin"])) {
    header("Location: login.php");
    exit;
}

include_once('../db.php');

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['order_status'];

    $stmt = $mydb->prepare("UPDATE payments SET order_status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();

    header("Location: orders.php?status=updated");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Orders | Decora Admin</title>

<link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
<link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />
<link rel="stylesheet" href="assets/vendor/css/core.css" />
<link rel="stylesheet" href="assets/vendor/css/theme-default.css" />
<link rel="stylesheet" href="assets/css/demo.css" />
<link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

<script src="assets/vendor/js/helpers.js"></script>
<script src="assets/js/config.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<?php
if (isset($_GET["status"]) && $_GET["status"]=="updated") {
    echo "<script>Swal.fire({icon:'success',title:'Updated!',text:'Order status updated successfully',timer:3000,showConfirmButton:false});</script>";
}
?>

<div class="layout-wrapper layout-content-navbar">
<div class="layout-container">
<?php include("inc/menu_left.php"); ?>
<div class="layout-page">
<?php include("inc/nav.php"); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<h4 class="fw-bold py-3 mb-4">
<span class="text-muted fw-light">Orders</span>
</h4>

<div class="row">
<div class="col-md-12">

<div class="card">
<h5 class="card-header">All Orders</h5>

<div class="card-body">
<div class="table-responsive">
<table class="table table-striped table-borderless border-bottom">
<thead>
<tr>
<th>ID</th>
<th>User</th>
<th>Products</th>
<th>Total</th>
<th>Payment Status</th>
<th>Order Status</th>
<th>Last Update</th>
<th class="text-center">Actions</th>
</tr>
</thead>

<tbody>
<?php
$sql = "SELECT p.id as payment_id, p.user_id, p.total_amount, p.payment_method, p.status as payment_status, p.order_status, p.created_at, u.name as user_name
        FROM payments p
        JOIN user u ON u.id = p.user_id
        ORDER BY p.created_at DESC";

$result = $mydb->query($sql);

while ($order = $result->fetch_object()) {

    // Get order id(s) corresponding to this payment
    $order_sql = "SELECT id FROM orders WHERE user_id = ".$order->user_id." ORDER BY created_at DESC LIMIT 1";
    $order_res = $mydb->query($order_sql);
    $order_row = $order_res->fetch_object();
    $order_id = $order_row ? $order_row->id : 0;

    // Fetch products for this order
    $products_sql = "SELECT oi.quantity, pr.product_name 
                     FROM order_items oi 
                     JOIN products pr ON pr.id = oi.product_id 
                     WHERE oi.order_id=".$order_id;

    $products_res = $mydb->query($products_sql);
    $product_list = [];
    while($prod = $products_res->fetch_object()){
        $product_list[] = $prod->product_name." (x".$prod->quantity.")";
    }
?>
<tr>
<td><?= $order->payment_id ?></td>
<td><?= htmlspecialchars($order->user_name) ?></td>
<td style="max-width:300px; white-space:normal;"><?= implode(", ", $product_list) ?></td>
<td>৳ <?= number_format($order->total_amount, 2) ?></td>
<td class="text-nowrap"><?= ucfirst($order->payment_status) ?></td>
<td>
<form method="POST" style="display:flex; gap:5px; align-items:center;">
<input type="hidden" name="order_id" value="<?= $order->payment_id ?>">
<select name="order_status" class="form-select form-select-sm">
    <?php
    $statuses = ['pending','accepted','shipped','delivered','cancelled'];
    foreach($statuses as $status){
        $selected = ($order->order_status==$status) ? "selected" : "";
        echo "<option value='$status' $selected>".ucfirst($status)."</option>";
    }
    ?>
</select>
<button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
</form>
</td>
<td><?= $order->created_at ?></td>
<td class="text-center">
    <?php
        $order_sql = "SELECT id FROM orders WHERE user_id = ".$order->user_id." ORDER BY created_at DESC LIMIT 1";
        $order_res = $mydb->query($order_sql);
        $order_row = $order_res->fetch_object();
        $order_id = $order_row ? $order_row->id : 0;
    ?>
<a href="invoice.php?id=<?= $order_id ?>" target="_blank" class="btn btn-success btn-sm">Invoice</a>
</td>
</tr>
<?php } ?>
</tbody>

</table>
</div>
</div>
</div>

</div>
</div>
</div>

</div>
<?php include("inc/footer.php"); ?>
<div class="content-backdrop fade"></div>
</div>
</div>
</div>

<script src="assets/vendor/libs/jquery/jquery.js"></script>
<script src="assets/vendor/libs/popper/popper.js"></script>
<script src="assets/vendor/js/bootstrap.js"></script>
<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="assets/vendor/js/menu.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
