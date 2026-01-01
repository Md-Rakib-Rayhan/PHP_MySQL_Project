<?php
session_start();


/* DB CONNECT */
$mydb = new mysqli("localhost", "root", "", "decora");
if ($mydb->connect_error) {
    die("Database connection failed");
}

/* LOGIN CHECK */
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['id'];

/* VALIDATE PRODUCT */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product");
}
$product_id = (int) $_GET['id'];

/* FETCH PRODUCT */
$product = $mydb->query("SELECT * FROM products WHERE id=$product_id")->fetch_object();
if (!$product) die("Product not found");

/* CHECK IF ALREADY IN CART */
$checkCart = $mydb->query("SELECT id FROM cart WHERE user_id=$user_id AND product_id=$product_id");
$alreadyInCart = ($checkCart->num_rows > 0);

/* ADD TO CART */
$added = false;
if (isset($_POST['add_to_cart']) && !$alreadyInCart) {
    $mydb->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    $added = true;
    $alreadyInCart = true;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($product->product_name) ?> – Decora</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Space+Grotesk&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/my_services.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<?php include("inc/nav.php"); ?>

<!-- HERO -->
<div class="container-fluid py-5 text-white"
     style="background:linear-gradient(rgba(0,0,0,.6),rgba(0,0,0,.6)),url('<?= $product->image_url ?>') center/cover;">
    <div class="container py-5">
        <span class="badge bg-light text-dark mb-2"><?= htmlspecialchars($product->category) ?></span>
        <h1 class="fw-bold"><?= htmlspecialchars($product->product_name) ?></h1>
        <p class="lead">Premium interior product for modern spaces</p>
    </div>
</div>

<!-- DETAILS -->
<div class="container my-5">
    <div class="row g-5 align-items-center">

        <div class="col-lg-6">
            <img src="<?= $product->image_url ?>" class="img-fluid rounded shadow">
        </div>

        <div class="col-lg-6">
            <h2><?= htmlspecialchars($product->product_name) ?></h2>
            <h3 class="text-success mb-3">৳ <?= number_format($product->price, 2) ?></h3>

            <p><?= nl2br(htmlspecialchars($product->description)) ?></p>

            <!-- BUTTON -->
            <?php if ($alreadyInCart): ?>
                <a href="cart.php" class="btn btn-dark btn-lg mt-3">
                    <i class="bi bi-cart-check"></i> View Cart
                </a>
            <?php else: ?>
                <form method="post" class="mt-3">
                    <button type="submit" name="add_to_cart" class="btn btn-dark btn-lg">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </form>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include("inc/footer.php"); ?>

<!-- SUCCESS ALERT -->
<?php if ($added): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Added to cart!',
    text: 'Product successfully added to your cart',
    showConfirmButton: false,
    timer: 2000
});
</script>
<?php endif; ?>


 <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

</body>
</html>
