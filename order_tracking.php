<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// DB connect
include_once('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order Tracking - Decora</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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
    <link href="css/my_style.css" rel="stylesheet">

</head>
<body>
<?php include("inc/nav.php"); ?>

<!-- HERO -->
<div class="container-fluid pb-5 bg-primary hero-header">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <h1 class="display-1">My Orders</h1>
                <p class="mt-3 text-dark">Track your order status and details below.</p>
            </div>
        </div>
    </div>
</div>

<!-- ORDERS -->
<div class="container py-5">
    
    <h2>Order History</h2>

    <?php
    $sql = "
    SELECT 
        o.id AS order_id,
        o.created_at AS order_created,
        o.total_amount,
        o.payment_method,
        o.status AS order_status,
        p.status AS payment_status
    FROM orders o
    LEFT JOIN payments p 
        ON p.user_id = o.user_id
    AND p.total_amount = o.total_amount
    AND ABS(TIMESTAMPDIFF(SECOND, o.created_at, p.created_at)) <= 3
    WHERE o.user_id = $user_id
    ORDER BY o.created_at DESC
    ";

    $result = $mydb->query($sql);

    if($result && $result->num_rows > 0):
    ?>

    <table class="table table-bordered">
    <thead class="table-primary">
    <tr>
    <th>#</th>
    <th>Order ID</th>
    <th>Products</th>
    <th>Total</th>
    <th>Payment</th>
    <th>Payment Status</th>
    <th>Order Status</th>
    <th>Date</th>
    <th>Invoice</th>
    </tr>
    </thead>
    <tbody>

    <?php $i=1; while($row = $result->fetch_object()): ?>

    <?php
    $products = [];
    $q = $mydb->query("
        SELECT pr.product_name, oi.quantity
        FROM order_items oi
        JOIN products pr ON pr.id = oi.product_id
        WHERE oi.order_id = $row->order_id
    ");
    while($p = $q->fetch_object()){
        $products[] = $p->product_name." (x".$p->quantity.")";
    }
    ?>

    <tr>
    <td><?= $i++ ?></td>
    <td>#<?= $row->order_id ?></td>
    <td><?= implode(", ", $products) ?></td>
    <td>৳ <?= number_format($row->total_amount,2) ?></td>
    <td><?= ucfirst($row->payment_method) ?></td>
    <td>
    <span class="badge bg-success">
    <?= ucfirst($row->payment_status ?? 'pending') ?>
    </span>
    </td>
    <td>
    <span class="badge bg-info"><?= ucfirst($row->order_status) ?></span>
    </td>
    <td><?= date("d M Y, h:i A", strtotime($row->order_created)) ?></td>
    <td>
    <a href="download_invoice.php?order_id=<?= $row->order_id ?>"
    class="btn btn-sm btn-outline-primary">Download</a>
    </td>
    </tr>

    <?php endwhile; ?>

    </tbody>
    </table>

    <?php else: ?>
    <div class="alert alert-info">No orders found.</div>
    <?php endif; ?>

    </div>

<?php include("inc/footer.php"); ?>
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
