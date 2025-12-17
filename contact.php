<?php
session_start();
$mydb = new mysqli("localhost", "root", "", "decora");

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>iSTUDIO - Interior Design Website Template Free</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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
</head>

<body>

<?php
if (isset($_POST['submit_request'])) {

    $user_id = $_SESSION['id'];  // Get client ID from session
    $price = $_POST['budget'];
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

    // Fix: Escape `describe` with backticks and allow NULL for service_id
    $sql = "INSERT INTO service_requests (user_id, service_id, price, `describe`, custom_service, custom_service_description)
            VALUES ('$user_id', " . ($service_id ? "'$service_id'" : "NULL") . ", '$price', '$describe', '$custom_service', '$custom_service_description')";

    if ($mydb->query($sql)) {
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
            title: "Thank you for choosing us!",
            text: "Your request has been successfully submitted. We will contact you soon!",
            icon: "success",
            timer: 3000, 
            showConfirmButton: false
            });
        </script>';
    } else {
        echo "Error: " . $mydb->error;
    }
}


?>









    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar Start -->
    <?php
    include("inc/nav.php");
    ?>
    <!-- Navbar End -->


    <!-- Hero Start -->
    <div class="container-fluid pb-5 bg-primary hero-header">
        <div class="container py-5">
            <div class="row g-3 align-items-center">
                <div class="col-lg-6 text-center text-lg-start">
                    <h1 class="display-1 mb-0 animated slideInLeft">Contact</h1>
                </div>
                <div class="col-lg-6 animated slideInRight">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center justify-content-lg-end mb-0">
                            <li class="breadcrumb-item"><a class="text-primary" href="#!">Home</a></li>
                            <li class="breadcrumb-item"><a class="text-primary" href="#!">Pages</a></li>
                            <li class="breadcrumb-item text-secondary active" aria-current="page">Contact</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Contact Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="text-center wow fadeIn" data-wow-delay="0.1s">
            <h1 class="mb-5">
                Hey, <span class="text-primary"><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Design Enthusiast'; ?></span>! 
                <span class="d-block mt-2" style="font-size: 0.6em; font-weight: 300;">How’s your day today? Ready to transform your space?</span>
            </h1>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="wow fadeIn" data-wow-delay="0.3s">
                    <form method="POST">
                        <div class="row g-3">
                            
                            
                            
                            <!-- 1. Service Selection Dropdown -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="service_type" name="service_type" onchange="toggleOtherField()" required>
                                        <option value="" selected disabled>Choose a service...</option>

                                        <?php
                                            $sql = "SELECT * FROM services";
                                            $result = $mydb->query($sql);
                                            while ($service = $result->fetch_object()) {
                                                echo "<option value='" . $service->id . "'>" . $service->service_name . "</option>";
                                            }
                                        ?>

                                        <option value="Others">Others (Please specify)</option>
                                    </select>
                                    <label for="service_type">What service do you need?</label>
                                </div>
                            </div>

                            <!-- Hidden "Other" Service Box -->
                            <div class="col-12" id="other_service_container" style="display: none;">
                                <div class="form-floating border-primary border rounded">
                                    <input type="text" class="form-control" id="other_service_name" name="other_service_name" placeholder="Specify Service">
                                    <label for="other_service_name">Please specify the interior service</label>
                                </div>
                            </div>

                            <!-- 2. Budget/Price Expectation -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="budget" name="budget" placeholder="Your Budget">
                                    <label for="budget">Your Estimated Budget ($)</label>
                                </div>
                            </div>

                            <!-- 3. Project Details -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Describe your vision..." id="message" name="details" style="height: 150px" required></textarea>
                                    <label for="message">Describe the details of what you want to work with...</label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3 text-uppercase" name="submit_request" type="submit" style="font-weight: 700; letter-spacing: 1px;">
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
        otherInput.setAttribute('required', 'required'); // Make it mandatory if visible
        otherInput.focus();
    } else {
        otherContainer.style.display = 'none';
        otherInput.removeAttribute('required'); // Remove mandatory if hidden
        otherInput.value = ''; // Clear the input
    }
}
</script>


    <!-- Contact End -->





    <!-- Footer Start -->
    <?php
    include("inc/footer.php");
    ?>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#!" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


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