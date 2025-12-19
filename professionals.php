<?php
session_start();
$mydb = new mysqli("localhost", "root", "", "decora");

// Redirect if not logged in or not a client
// if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Client') {
//     header("Location: login.php");
//     exit;
// }

// Fetch all professionals with their services
$sql = "SELECT p.id AS professional_id, p.name AS professional_name, p.bio, p.experience_years, p.is_verified, u.profile_pic,
               GROUP_CONCAT(s.service_name SEPARATOR ', ') AS services
        FROM professionals p
        LEFT JOIN user u ON p.user_id = u.id
        LEFT JOIN professional_services ps ON p.id = ps.professional_id
        LEFT JOIN services s ON ps.service_id = s.id
        GROUP BY p.id
        ORDER BY p.is_verified DESC, p.name ASC";

$result = $mydb->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Our Professionals - Decora</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<?php include("inc/nav.php"); ?>

<div class="container py-5">
    <h1 class="text-center mb-5">Meet Our Professionals</h1>
    <?php if($result->num_rows > 0): ?>
        <div class="row g-4">
            <?php while($prof = $result->fetch_object()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <?php if($prof->profile_pic): ?>
                            <img src="<?= $prof->profile_pic ?>" class="card-img-top" alt="<?= $prof->professional_name ?>" style="height:250px; object-fit:cover;">
                        <?php else: ?>
                            <img src="img/default_profile.png" class="card-img-top" alt="No Image" style="height:250px; object-fit:cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= $prof->professional_name ?>
                                <?php if($prof->is_verified): ?>
                                    <i class="fas fa-check-circle text-primary" title="Verified"></i>
                                <?php endif; ?>
                            </h5>
                            <p class="card-text"><?= nl2br($prof->bio) ?></p>
                            <p><strong>Experience:</strong> <?= $prof->experience_years ?> years</p>
                            <p><strong>Services:</strong> <?= $prof->services ? $prof->services : 'N/A' ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="contact_professional.php?pid=<?= $prof->professional_id ?>" class="btn btn-primary w-100">Contact</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <h4>No professionals available at the moment.</h4>
        </div>
    <?php endif; ?>
</div>

<?php include("inc/footer.php"); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
