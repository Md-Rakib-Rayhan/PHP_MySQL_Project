<?php
session_start();

// Login check
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$mydb = new mysqli("localhost", "root", "", "decora");
if ($mydb->connect_error) die("DB connection failed");

$user_id = $_SESSION['id'];

/* Fetch Cart Items */
$result = $mydb->query("
SELECT c.quantity, p.id AS product_id, p.product_name, p.price
FROM cart c
JOIN products p ON c.product_id = p.id
WHERE c.user_id = $user_id
");

if (!isset($_SESSION['order_success'])){
    if (!$result || $result->num_rows == 0) {
    header("Location: cart.php");
    exit;
}
}


$grandTotal = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
    $row['total'] = $row['price'] * $row['quantity'];
    $grandTotal += $row['total'];
    $items[] = $row;
}

/* Place Order */
if (isset($_POST['place_order'])) {
    $full_name = $mydb->real_escape_string($_POST['full_name']);
    $phone = $mydb->real_escape_string($_POST['phone']);
    $address = $mydb->real_escape_string($_POST['address']);
    $notes = $mydb->real_escape_string($_POST['notes']);
    $payment_method = $mydb->real_escape_string($_POST['payment_method']);

    // 1️⃣ Insert order
    $mydb->query("INSERT INTO orders (user_id, full_name, phone, address, notes, total_amount, payment_method, status)
                  VALUES ('$user_id','$full_name','$phone','$address','$notes','$grandTotal','$payment_method','pending')");
    $order_id = $mydb->insert_id;

    // 2️⃣ Insert order items
    foreach ($items as $item) {
        $pid = $item['product_id'];
        $qty = $item['quantity'];
        $price = $item['price'];
        $mydb->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                      VALUES ('$order_id','$pid','$qty','$price')");
    }

    // 3️⃣ Insert payment record
    $mydb->query("INSERT INTO payments (user_id, total_amount, payment_method, status) 
                  VALUES ('$user_id','$grandTotal','$payment_method','completed')");

    // 4️⃣ Clear cart
    $mydb->query("DELETE FROM cart WHERE user_id=$user_id");

    $_SESSION['order_success'] = true;
    header("Location: checkout.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Checkout – Decora</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Space+Grotesk&display=swap" rel="stylesheet">

    <!-- Icon Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="css/style.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<?php include("inc/nav.php"); ?>

<div class="container-fluid py-5 bg-primary text-white">
    <div class="container">
        <h1 class="fw-bold">Checkout</h1>
        <p>Confirm your order details</p>
    </div>
</div>

<div class="container my-5">

<?php if (isset($_SESSION['order_success'])): ?>
    <?php unset($_SESSION['order_success']); ?>
    <div class="alert alert-success text-center">
        <h4>🎉 Order placed successfully!</h4>
        <a href="products.php" class="btn btn-dark mt-3">Continue Shopping</a>
        <a href="order_tracking.php" class="btn btn-dark mt-3">See My Order</a>
    </div>
<?php else: ?>

<form method="post">
<div class="row g-4">

<!-- LEFT: Billing -->
<div class="col-lg-7">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">Billing Details</h5>

            <input class="form-control mb-3" name="full_name" required placeholder="Full Name">
            <input class="form-control mb-3" name="phone" required placeholder="Phone Number">
            <input class="form-control mb-3" name="address" required placeholder="Address">
            <textarea class="form-control mb-3" name="notes" rows="3" placeholder="Order notes (optional)"></textarea>

            <h5 class="mb-3 mt-4">Payment Method</h5>
            <select class="form-select" name="payment_method" required>
                <option value="">Select a method</option>
                <option value="card">Card</option>
                <option value="paypal">PayPal</option>
                <option value="cod">Cash on Delivery</option>
            </select>
        </div>
    </div>
</div>

<!-- RIGHT: Order Summary -->
<div class="col-lg-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Order Summary</h5>

            <?php foreach ($items as $item): ?>
            <div class="d-flex justify-content-between mb-2">
                <span><?= htmlspecialchars($item['product_name']) ?> × <?= $item['quantity'] ?></span>
                <strong>৳ <?= number_format($item['total'],2) ?></strong>
            </div>
            <?php endforeach; ?>

            <hr>

            <div class="d-flex justify-content-between fs-5 mb-3">
                <strong>Total</strong>
                <strong>৳ <?= number_format($grandTotal,2) ?></strong>
            </div>

            <button type="submit" name="place_order" class="btn btn-dark w-100 mt-4">
                Place Order
            </button>
        </div>
    </div>
</div>

</div>
</form>
<?php endif; ?>

</div>

<?php include("inc/footer.php"); ?>


<!-- JS Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>







<!-- https://www.youtube.com/watch?v=1KxD8J8CAFg -->