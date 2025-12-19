<?php
session_start();
$mydb = new mysqli("localhost", "root", "", "decora");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// Fetch all service requests for this client
$sql = "SELECT sr.*, s.service_name 
        FROM service_requests sr
        LEFT JOIN services s ON sr.service_id = s.id
        WHERE sr.user_id = $user_id
        ORDER BY sr.created_at DESC";

$result = $mydb->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Project Status - Decora</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
</head>

<body>

<?php include("inc/nav.php"); ?>

<!-- Hero Section -->
<div class="container-fluid pb-5 bg-primary hero-header">
    <div class="container py-5">
        <div class="row g-3 align-items-center">
            <div class="col-lg-6 text-center text-lg-start">
                <h1 class="display-1 mb-0 animated slideInLeft">Project Status</h1>
            </div>
            <div class="col-lg-6 animated slideInRight">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center justify-content-lg-end mb-0">
                        <li class="breadcrumb-item"><a class="text-primary" href="index.php">Home</a></li>
                        <li class="breadcrumb-item text-secondary active" aria-current="page">Project Status</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Requests Section -->
<div class="py-5">
    <div class="container">
        <h1 class="text-center mb-5 wow fadeIn" data-wow-delay="0.1s">Your Design Requests</h1>

        <?php if($result->num_rows > 0): ?>
            <div class="row g-4 wow fadeIn" data-wow-delay="0.3s">
                <?php while($req = $result->fetch_object()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= $req->service_name ?? $req->custom_service_description; ?></h5>
                            <p class="card-text"><?= nl2br($req->describe); ?></p>

                            <p>
                                <?php if($req->price > 0): ?>
                                    <strong>Final Price: </strong>$<?= number_format($req->price, 2); ?><br>
                                    <strong>Advance Paid: </strong>$<?= number_format($req->advance_price, 2); ?><br>
                                    <strong>Due: </strong>$<?= number_format($req->due_price, 2); ?><br>
                                <?php else: ?>
                                    <strong>Expected Price: </strong>$<?= number_format($req->expected_price, 2); ?><br>
                                    <small class="text-muted">Final price will be updated once the project is in progress.</small><br>
                                <?php endif; ?>
                                <small class="text-muted">Expected Duration: <?= $req->expected_duration ?> days | Actual: <?= $req->duration ?> days</small>
                            </p>

                            <p>
                                <strong>Status: </strong>
                                <?php 
                                    switch($req->status) {
                                        case 'pending': echo '<span class="badge bg-warning text-dark">Pending</span>'; break;
                                        case 'in_progress': echo '<span class="badge bg-info text-dark">In Progress</span>'; break;
                                        case 'completed': echo '<span class="badge bg-success">Completed</span>'; break;
                                        case 'cancelled': echo '<span class="badge bg-danger">Cancelled</span>'; break;
                                    }
                                ?>
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <?php 
                            $reviewCheck = $mydb->query("SELECT id FROM reviews WHERE service_request_id=".$req->id." LIMIT 1");
                            $reviewExists = $reviewCheck->num_rows > 0;
                            ?>

                            <?php if($req->status == 'completed'): ?>
                                <?php if($reviewExists): ?>
                                    <button class="btn btn-sm btn-secondary w-100" disabled>Reviewed</button>
                                <?php else: ?>
                                    <a href="review.php?rid=<?= $req->id; ?>" class="btn btn-sm btn-primary w-100">Leave Review</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">No actions</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5 wow fadeIn" data-wow-delay="0.3s">
                <h4 class="mb-3">You haven't submitted any design requests yet.</h4>
                <a href="service.php" class="btn btn-success btn-lg">Request a Service</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include("inc/footer.php"); ?>

<!-- Back to Top -->
<a href="#!" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

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
