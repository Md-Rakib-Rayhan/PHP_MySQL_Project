<?php
session_start();
if (!isset($_SESSION["isValidAdmin"])){
    header("Location: login.php");
}

include_once('../db.php');

/* Detect action */
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

/* Fetch product for edit */
if ($action == 'edit' && $id != '') {
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $mydb->query($sql);
    $user_data = $result->fetch_object();
}
?>

<!-- INSERT -->
<?php
if (
    isset($_POST['submit']) &&
    $action != 'edit' &&
    $_POST['product_name'] != "" &&
    $_POST['category'] != "" &&
    $_POST['price'] != "" &&
    $_POST['description'] != "" &&
    $_FILES["image"]["name"] != ""
) {

    $product_name = $_POST['product_name'];
    $category     = $_POST['category'];
    $price        = $_POST['price'];
    $description  = $_POST['description'];

    $photo_name = $_FILES["image"]["name"];
    $tmp_name   = $_FILES["image"]["tmp_name"];
    $upload_path = "img/products/";
    $full_path   = $upload_path . $photo_name;

    move_uploaded_file($tmp_name, "../img/products/" . $photo_name);

    $sql = "INSERT INTO products
        (`product_name`,`description`,`category`,`price`,`image_url`)
        VALUES
        ('$product_name','$description','$category','$price','$full_path')";

    $mydb->query($sql);
    header("Location: product_list.php?status=success");
    exit;

} elseif (isset($_POST['submit']) && $action != 'edit') {
    echo '
    <script>
    window.onload = function(){
        let notify = document.getElementById("wronginput");
        notify.classList.add("alert-danger");
        notify.innerHTML = "<strong>Please fill up all required fields</strong>";
        notify.style.display = "block";
        setTimeout(()=>{ notify.style.display="none"; },5000);
    }
    </script>';
}
?>

<!-- UPDATE -->
<?php
if (isset($_POST['submit']) && $action == 'edit' && $id != '') {

    $product_name = $_POST['product_name'];
    $category     = $_POST['category'];
    $price        = $_POST['price'];
    $description  = $_POST['description'];

    if (!empty($_FILES['image']['name'])) {
        $photo_name = $_FILES["image"]["name"];
        $tmp_name   = $_FILES["image"]["tmp_name"];
        $upload_path = "img/products/";
        $full_path   = $upload_path . $photo_name;

        move_uploaded_file($tmp_name, "../img/products/" . $photo_name);
        $product_pic = ", image_url='$full_path'";
    } else {
        $product_pic = "";
    }

    $sql = "UPDATE products SET
        product_name='$product_name',
        description='$description',
        category='$category',
        price='$price'
        $product_pic
        WHERE id='$id'";

    $mydb->query($sql);
    header("Location: product_list.php?status=updated");
    exit;
}
?>

<!-- DELETE -->
<?php
if (isset($_POST['del_submit'])) {
    $mydb->query("DELETE FROM products WHERE id='$id'");
    header("Location: product_list.php?status=deleted");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
<head>
<meta charset="utf-8">
<title>Product | Decora</title>

<link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />
<link rel="stylesheet" href="assets/vendor/css/core.css" />
<link rel="stylesheet" href="assets/vendor/css/theme-default.css" />
<link rel="stylesheet" href="assets/css/demo.css" />
<link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

<script src="assets/vendor/js/helpers.js"></script>
<script src="assets/js/config.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

  



</head>

<body>

<div class="layout-wrapper layout-content-navbar">
<div class="layout-container">

<?php include("inc/menu_left.php"); ?>

<div class="layout-page">
<?php include("inc/nav.php"); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<div class="alert text-center" id="wronginput" style="display:none;"></div>

<h4 class="fw-bold py-3 mb-4">
<span class="text-muted fw-light">Products</span>
</h4>

<ul class="nav nav-pills flex-column flex-md-row mb-3">
<li class="nav-item">
<a class="nav-link active" href="#">Add Product</a>
</li>
<li class="nav-item">
<a class="nav-link" href="product_list.php">Product List</a>
</li>
</ul>

<div class="card mb-4">
<h5 class="card-header"><?= ($action=='edit')?'Update Product':'Add Product'; ?></h5>

<form method="POST" enctype="multipart/form-data">
<div class="card-body">

<div class="d-flex align-items-start gap-4">
<img
id="uploadedAvatar"
src="<?= ($action=='edit' && $user_data->image_url!="") ? '../'.$user_data->image_url : '../img/no_profile.jpg'; ?>"
class="rounded border"
style="width:320px;height:180px;object-fit:cover;"
>
<div>
<label class="btn btn-primary mb-2">
Upload Product Image
<input type="file" name="image" id="upload" hidden accept="image/png,image/jpeg">
</label>
<p class="text-muted">Recommended 16:9 image</p>
</div>
</div>

<hr>

<div class="row">
<div class="mb-3 col-md-6">
<label>Product Name *</label>
<input class="form-control" name="product_name"
value="<?= ($action=='edit')?$user_data->product_name:''; ?>">
</div>

<div class="mb-3 col-md-6">
<label>Category *</label>
<input class="form-control" name="category"
value="<?= ($action=='edit')?$user_data->category:''; ?>"
placeholder="Furniture / Decor">
</div>

<div class="mb-3 col-md-6">
<label>Price *</label>
<input class="form-control" name="price"
value="<?= ($action=='edit')?$user_data->price:''; ?>">
</div>

<div class="mb-3 col-md-6">
<label>Description *</label>
<textarea class="form-control" name="description"><?= ($action=='edit')?$user_data->description:''; ?></textarea>
</div>

<div class="mb-3 col-md-6">
<label>Created at</label>
<input class="form-control" disabled value="<?= ($action=='edit')?$user_data->created_at:''; ?>">
</div>

<div class="mb-3 col-md-6">
<label>Updated at</label>
<input class="form-control" disabled value="<?= ($action=='edit')?$user_data->updated_at:''; ?>">
</div>
</div>

<button class="btn btn-primary" name="submit">
<?= ($action=='edit')?'Save Changes':'Add Product'; ?>
</button>
<a href="product_list.php" class="btn btn-outline-secondary">Cancel</a>

</div>
</form>
</div>

<?php if ($action=='edit'){ ?>
<div class="card">
<h5 class="card-header">Delete Product</h5>
<div class="card-body">
<form method="POST" id="deleteForm">
<button type="button" id="deleteBtn" class="btn btn-danger">Delete Product</button>
<input type="hidden" name="del_submit">
</form>
</div>
</div>
<?php } ?>

<!-- ===== IMAGE PREVIEW SCRIPT ===== -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const uploadInput = document.getElementById("upload");
    const previewImg = document.getElementById("uploadedAvatar");

    if(uploadInput && previewImg){
        uploadInput.addEventListener("change", function(){
            const file = this.files[0];
            if(!file) return;
            if(!file.type.startsWith("image/")){
                alert("Please select a valid image");
                this.value = "";
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e){
                previewImg.src = e.target.result;
            }
            reader.readAsDataURL(file);
        });
    }
});

// DELETE CONFIRMATION
document.getElementById('deleteBtn')?.addEventListener('click',()=>{
Swal.fire({
title:"Are you sure?",
text:"You won't be able to revert this!",
icon:"warning",
showCancelButton:true,
confirmButtonText:"Yes, delete it!"
}).then((r)=>{
if(r.isConfirmed){
document.getElementById('deleteForm').submit();
}
});
});
</script>

<script src="assets/vendor/libs/jquery/jquery.js"></script>
<script src="assets/vendor/libs/popper/popper.js"></script>
<script src="assets/vendor/js/bootstrap.js"></script>
<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="assets/vendor/js/menu.js"></script>
<script src="assets/js/main.js"></script>


</div>
<?php include("inc/footer.php"); ?>
</div>
</div>
</div>
</div>
</body>
</html>
