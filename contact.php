<?php
session_start();
$mydb = new mysqli("localhost", "root", "", "decora");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$selected_service_id = isset($_GET['sid']) ? $_GET['sid'] : '';
$success = false; // Flag to trigger SweetAlert

if (isset($_POST['submit_request'])) {

    $user_id = $_SESSION['id'];  // Client ID
    $expected_price = $_POST['budget']; // Client sets expected price
    $describe = $_POST['details'];

    // Set service_id to NULL for custom services (Others)
    $service_id = NULL;
    $custom_service = 0;
    $custom_service_description = '';

    if ($_POST['service_type'] != "Others") {
        $service_id = $_POST['service_type'];
    } else {
        $custom_service = 1;
        $custom_service_description = $_POST['other_service_name'];
    }

    // Insert request into database
    $sql = "INSERT INTO service_requests 
            (user_id, service_id, expected_price, `describe`, custom_service, custom_service_description)
            VALUES ('$user_id', " . ($service_id ? "'$service_id'" : "NULL") . ", '$expected_price', '$describe', '$custom_service', '$custom_service_description')";

    if ($mydb->query($sql)) {
        $success = true; // Set flag to trigger SweetAlert after HTML loads
    } else {
        $error = $mydb->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contact - Decora</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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

<!-- Hero Section -->
<div class="container-fluid pb-5 bg-primary hero-header">
    <div class="container py-5">
        <div class="row g-3 align-items-center">
            <div class="col-lg-6 text-center text-lg-start">
                <h1 class="display-1 mb-0 animated slideInLeft">Contact</h1>
            </div>
            <div class="col-lg-6 animated slideInRight">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center justify-content-lg-end mb-0">
                        <li class="breadcrumb-item"><a class="text-primary" href="index.php">Home</a></li>
                        <li class="breadcrumb-item text-secondary active" aria-current="page">Contact</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Contact Form -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="text-center wow fadeIn" data-wow-delay="0.1s">
            <h1 class="mb-5">
                Hey, <span class="text-primary"><?php echo $_SESSION['name'] ?? 'Design Enthusiast'; ?></span>!
                <span class="d-block mt-2" style="font-size: 0.6em; font-weight: 300;">How’s your day today? Ready to transform your space?</span>
            </h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="wow fadeIn" data-wow-delay="0.3s">
                    <form method="POST">
                        <div class="row g-3">

                            <!-- Service Selection -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="service_type" name="service_type" onchange="toggleOtherField()" required>
                                        <option value="" disabled <?php echo empty($selected_service_id) ? 'selected' : ''; ?>>Choose a service...</option>
                                        <?php
                                        $services = $mydb->query("SELECT * FROM services");
                                        while ($service = $services->fetch_object()) {
                                            $selected = ($service->id == $selected_service_id) ? 'selected' : '';
                                            echo "<option value='{$service->id}' {$selected}>{$service->service_name}</option>";
                                        }
                                        ?>
                                        <option value="Others">Others (Please specify)</option>
                                    </select>
                                    <label for="service_type">What service do you need?</label>
                                </div>
                            </div>

                            <!-- Other Service Field -->
                            <div class="col-12" id="other_service_container" style="display: none;">
                                <div class="form-floating border-primary border rounded">
                                    <input type="text" class="form-control" id="other_service_name" name="other_service_name" placeholder="Specify Service">
                                    <label for="other_service_name">Please specify the interior service</label>
                                </div>
                            </div>

                            <!-- Expected Budget -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="budget" name="budget" placeholder="Your Budget" required>
                                    <label for="budget">Your Expected Budget ($)</label>
                                </div>
                            </div>

                            <!-- Project Details -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Describe your vision..." id="message" name="details" style="height: 150px" required></textarea>
                                    <label for="message">Describe the details of what you want to work with...</label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3 text-uppercase" name="submit_request" type="submit">
                                    Start My Design Journey
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleOtherField() {
    const serviceSelect = document.getElementById('service_type');
    const otherContainer = document.getElementById('other_service_container');
    const otherInput = document.getElementById('other_service_name');

    if (serviceSelect.value === 'Others') {
        otherContainer.style.display = 'block';
        otherInput.setAttribute('required', 'required');
        otherInput.focus();
    } else {
        otherContainer.style.display = 'none';
        otherInput.removeAttribute('required');
        otherInput.value = '';
    }
}

// SweetAlert redirect if success
<?php if($success): ?>
Swal.fire({
    title: "Thank you for choosing us!",
    text: "Your request has been successfully submitted. Redirecting to Project Status...",
    icon: "success",
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    window.location.href = "project_status.php";
});
<?php endif; ?>
</script>

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
