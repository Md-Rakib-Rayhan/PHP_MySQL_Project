<?php
session_start();
if (!isset($_SESSION["isValidAdmin"])) {
    header("Location: login.php");
}

include_once('../db.php');
?>

<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free"
>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>Products | Decora Admin</title>

<link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

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

<!-- STATUS ALERT -->
<?php
if (isset($_GET["status"])) {
    if ($_GET["status"] == "success") {
        echo "<script>Swal.fire({icon:'success',title:'Done!',text:'Product added successfully',timer:3000,showConfirmButton:false});</script>";
    } elseif ($_GET["status"] == "updated") {
        echo "<script>Swal.fire({icon:'success',title:'Updated!',text:'Product updated successfully',timer:3000,showConfirmButton:false});</script>";
    } elseif ($_GET["status"] == "deleted") {
        echo "<script>Swal.fire({icon:'success',title:'Deleted!',text:'Product deleted successfully',timer:3000,showConfirmButton:false});</script>";
    }
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
<span class="text-muted fw-light">Products</span>
</h4>

<div class="row">
<div class="col-md-12">

<ul class="nav nav-pills flex-column flex-md-row mb-3">
<li class="nav-item">
<a class="nav-link" href="product.php">
<i class="bx bx-box me-1"></i> Add Product
</a>
</li>
<li class="nav-item">
<a class="nav-link active" href="javascript:void(0);">
<i class="bx bx-list-ul me-1"></i> Product List
</a>
</li>
</ul>

<div class="card">
<h5 class="card-header">All Products</h5>

<div class="card-body">
<div class="table-responsive">
<table class="table table-striped table-borderless border-bottom">

<thead>
<tr>
<th>ID</th>
<th>Product Name</th>
<th>Category</th>
<th>Description</th>
<th>Price</th>
<th>Last Update</th>
<th class="text-center">Action</th>
</tr>
</thead>

<tbody>
<?php
$sql = "SELECT * FROM products";
$result = $mydb->query($sql);

while ($data = $result->fetch_object()) {
?>
<tr>
<td><?= $data->id ?></td>

<td class="text-nowrap">
<?= htmlspecialchars($data->product_name) ?>
</td>

<td class="text-nowrap">
<?= htmlspecialchars($data->category) ?>
</td>

<td style="max-width:400px; white-space:normal;">
<?= htmlspecialchars($data->description) ?>
</td>

<td class="text-nowrap">
৳ <?= number_format($data->price, 2) ?>
</td>

<td class="text-nowrap">
<?= $data->updated_at ?>
</td>

<td class="text-center">
<a href="product.php?action=edit&id=<?= $data->id ?>" class="btn btn-primary btn-sm">
Update
</a>
</td>
</tr>
<?php } ?>
</tbody>

</table>
</div>
</div>

<div class="card-body">
<a href="product.php" class="btn btn-primary">
Add New Product
</a>
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
