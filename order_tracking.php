<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// DB connect
$mydb = new mysqli("localhost", "root", "", "decora");
if ($mydb->connect_error) {
    die("Database connection failed");
}
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
                <p class="mt-3">Track your order status and details below.</p>
            </div>
        </div>
    </div>
</div>

<!-- ORDERS -->
<div class="container py-5">
    <h2 class="mb-4">Order History</h2>

    <?php
    // Fetch all orders/payments for this user
    $sql = "SELECT p.id as payment_id, p.total_amount, p.payment_method, p.status as payment_status, 
                   p.order_status, p.created_at,
                   o.id as order_id
            FROM payments p
            JOIN orders o ON o.user_id = p.user_id
            WHERE p.user_id = $user_id
            ORDER BY p.created_at DESC";

    $result = $mydb->query($sql);

    if($result && $result->num_rows > 0):
    ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Products</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Order Date</th>
                        <th>Invoice</th> <!-- NEW COLUMN -->
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; while($order = $result->fetch_object()): 
                    // Fetch products for this order
                    $products_sql = "SELECT oi.quantity, pr.product_name
                                    FROM order_items oi
                                    JOIN products pr ON pr.id = oi.product_id
                                    WHERE oi.order_id = ".$order->order_id;
                    $products_res = $mydb->query($products_sql);
                    $product_list = [];
                    while($prod = $products_res->fetch_object()){
                        $product_list[] = htmlspecialchars($prod->product_name)." (x".$prod->quantity.")";
                    }
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td>#<?= $order->order_id ?></td>
                    <td><?= implode(", ", $product_list) ?></td>
                    <td>৳ <?= number_format($order->total_amount, 2) ?></td>
                    <td><?= htmlspecialchars($order->payment_method) ?></td>
                    <td>
                        <?php if($order->payment_status=='completed'): ?>
                            <span class="badge bg-success">Completed</span>
                        <?php else: ?>
                            <span class="badge bg-warning"><?= ucfirst($order->payment_status) ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                            $status = $order->order_status;
                            $status_color = "secondary";
                            if($status=='pending') $status_color="warning";
                            elseif($status=='accepted') $status_color="info";
                            elseif($status=='shipped') $status_color="primary";
                            elseif($status=='delivered') $status_color="success";
                            elseif($status=='cancelled') $status_color="danger";
                        ?>
                        <span class="badge bg-<?= $status_color ?>"><?= ucfirst($status) ?></span>
                    </td>
                    <td><?= date("d M Y, h:i A", strtotime($order->created_at)) ?></td>
                    <td>
                        <a href="download_invoice.php?order_id=<?= $order->order_id ?>" 
                        class="btn btn-sm btn-outline-primary">
                        Download
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>

            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">No orders found.</div>
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
