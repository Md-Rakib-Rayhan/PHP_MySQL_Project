<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>About Us - Decora</title>
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

    <!-- Animate & Owl Carousel for animations -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

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
                <h1 class="display-1 mb-0 animated slideInLeft">About Us</h1>
                <p class="lead mt-3 text-dark">Learn more about Decora and our mission to transform spaces into stunning living experiences.</p>
            </div>
            <div class="col-lg-6 animated slideInRight">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center justify-content-lg-end mb-0">
                        <li class="breadcrumb-item"><a class="text-primary" href="index.php">Home</a></li>
                        <li class="breadcrumb-item text-secondary active" aria-current="page">About Us</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- About Us Content -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Who We Are</h2>
        <p class="lead text-center mb-5">
            Decora is a leading interior design service provider committed to creating beautiful, functional, and personalized spaces. 
            Our team of experienced professionals combines creativity with practical solutions to bring your dream home or office to life.
        </p>

        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <i class="fas fa-lightbulb fa-3x mb-3 text-primary"></i>
                <h5>Innovative Designs</h5>
                <p>We provide modern and creative interior solutions tailored to your style and preferences.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                <h5>Expert Professionals</h5>
                <p>Our team consists of verified professionals with years of experience in design and decoration.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-handshake fa-3x mb-3 text-primary"></i>
                <h5>Client Satisfaction</h5>
                <p>We prioritize our clients and ensure every project exceeds expectations from start to finish.</p>
            </div>
        </div>

        <div class="mt-5 text-center">
            <p>Since our inception, Decora has successfully completed hundreds of projects, transforming spaces into inspiring environments for homes, offices, and commercial properties.</p>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <h2 class="text-center mb-5">What Our Clients Say</h2>
        <div class="owl-carousel owl-theme">
            <div class="text-center p-4">
                <p class="lead">"Decora transformed my living room into a modern masterpiece. Their team is amazing!"</p>
                <h5>- Sarah J.</h5>
            </div>
            <div class="text-center p-4">
                <p class="lead">"Professional, creative, and detail-oriented. Highly recommended for any interior project."</p>
                <h5>- Michael R.</h5>
            </div>
            <div class="text-center p-4">
                <p class="lead">"From consultation to completion, Decora exceeded my expectations in every way."</p>
                <h5>- Emma W.</h5>
            </div>
        </div>
    </div>
</section>

<?php include("inc/footer.php"); ?>

<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script>
    $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
            items:1,
            loop:true,
            autoplay:true,
            autoplayTimeout:5000,
            animateOut: 'fadeOut'
        });
    });
</script>
</body>
</html>
