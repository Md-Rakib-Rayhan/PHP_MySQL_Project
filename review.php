<?php
session_start();
include_once('db.php');

// DB check
if ($mydb->connect_error) {
    die("Database connection failed");
}

// Login check
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// Validate project ID
if (!isset($_GET['rid']) || !is_numeric($_GET['rid'])) {
    header("Location: project_status.php");
    exit;
}

$rid = (int) $_GET['rid'];

// Check project ownership + completed
$projectSql = "
    SELECT sr.id, sr.custom_service, sr.custom_service_description, s.service_name
    FROM service_requests sr
    LEFT JOIN services s ON sr.service_id = s.id
    WHERE sr.id = $rid
      AND sr.user_id = $user_id
      AND sr.status = 'completed'
    LIMIT 1
";
$projectCheck = $mydb->query($projectSql);

if ($projectCheck->num_rows === 0) {
    header("Location: project_status.php");
    exit;
}

$project = $projectCheck->fetch_object();

// Check existing review
$reviewCheck = $mydb->query("
    SELECT id FROM reviews WHERE service_request_id = $rid LIMIT 1
");

if ($reviewCheck->num_rows > 0) {
    $_SESSION['review_already'] = true;
    header("Location: project_status.php");
    exit;
}

// Handle submit (POST → REDIRECT → GET)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $rating = (int) $_POST['rating'];
    $review_text = $mydb->real_escape_string($_POST['review_text']);

    $insertSql = "
        INSERT INTO reviews (service_request_id, rating, review_text, created_at)
        VALUES ($rid, $rating, '$review_text', NOW())
    ";

    if ($mydb->query($insertSql)) {
        $_SESSION['review_success'] = true;
        header("Location: project_status.php");
        exit;
    } else {
        $error = "Something went wrong. Try again.";
    }
}
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

<div class="container py-5">
    <h2 class="mb-4">Leave a Review</h2>

    <div class="card">
        <div class="card-body">
            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php } ?>

            <form method="POST">

                <h5 class="mb-3">
                    Project:
                    <?= $project->custom_service
                        ? $project->custom_service_description
                        : $project->service_name ?>
                </h5>

                <div class="mb-3">
                    <label class="form-label">Rating</label>
                    <select name="rating" class="form-select" required>
                        <option value="" disabled selected>Rate 1–5</option>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Your Review</label>
                    <textarea name="review_text" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Submit Review
                </button>

            </form>
        </div>
    </div>
</div>

<?php include("inc/footer.php"); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
