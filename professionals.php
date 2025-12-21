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

// Fetch all professionals with their user info
$sql = "
    SELECT p.id as professional_id, p.name as prof_name, p.bio, p.experience_years, p.is_verified, u.email, u.phone, u.profile_pic
    FROM professionals p
    INNER JOIN user u ON p.user_id = u.id
    ORDER BY p.id ASC
";

$result = $mydb->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Our Professionals - Decora</title>
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
                    <h1 class="display-1 mb-0 animated slideInLeft">Professionals</h1>
                </div>
                <div class="col-lg-6 animated slideInRight">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center justify-content-lg-end mb-0">
                            <li class="breadcrumb-item"><a class="text-primary" href="#!">Home</a></li>
                            <li class="breadcrumb-item text-secondary active" aria-current="page">professionals</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

<!-- Professionals List -->
<div class="container py-5">
    <h2 class="mb-2 text-center">Meet Our Professionals</h2>
    <p class="lead mb-5 text-center">Discover our skilled professionals ready to bring your interior design ideas to life.</p>


    <div class="row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($prof = $result->fetch_object()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= $prof->profile_pic ?? 'img/default-profile.png' ?>" class="card-img-top" alt="<?= htmlspecialchars($prof->prof_name) ?>" style="height:200px; object-fit:cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($prof->prof_name) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($prof->bio) ?></p>
                            <p class="mb-1"><strong>Experience:</strong> <?= $prof->experience_years ?> years</p>
                            <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($prof->email) ?></p>
                            <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($prof->phone) ?></p>
                            <p class="mb-0">
                                <strong>Status:</strong>
                                <?= $prof->is_verified ? '<span class="text-success">Verified</span>' : '<span class="text-secondary">Unverified</span>' ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No professionals found.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include("inc/footer.php"); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
