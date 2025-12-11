<?php
$mydb = new mysqli("localhost","root","","decora");
session_start();
$name = $_SESSION['name'];

$sql = "SELECT * FROM user WHERE `name`='$name'";
$result = $mydb->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row["name"];
    $fname = $row["first_name"];
    $lname = $row["last_name"];
    $email = $row["email"];
    $phone = $row["phone"];
    $address = $row["address"];
    $company_or_individual = $row["company_or_individual"];
    $profession = $row["profession"];
    $dob = $row["birthday"];
    $pic = $row["profile_pic"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Decora</title>
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

    <!-- New Icon From admin -->
    <link rel="stylesheet" href="admin/assets/vendor/fonts/boxicons.css" />

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .form-control{
           color: #0c0c0cff;
        }
    </style>
</head>

<body>
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





    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="text-center wow fadeIn" data-wow-delay="0.1s">
                <h1 class="mb-5">Edit Profile <span class="text-uppercase text-primary bg-light px-2"><?php echo $_SESSION["name"]; ?></span></h1>
            </div>
            <div class="row justify-content-center">
                 
                    




            <div class=" card-body p-4">
            <form method="post">
                <div class="row g-5">
                <!-- Column 1: Profile Image -->
                <div class="col-md-3 d-flex flex-column align-items-center text-center">
                    <img src="img/no_profile.jpg" alt="Profile" id="profilePreview"
                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 2px solid #ccc;">
                    <input type="file" class="form-control mt-3" accept="image/*"
                        onchange="document.getElementById('profilePreview').src = window.URL.createObjectURL(this.files[0])">
                    <small class="text-muted mt-1">Change Photo</small>
                </div>

                <!-- Column 2: Personal Info -->
                <div class="col-md-4">
                    <div class="mb-4">
                    <label class="form-label fw-semibold">First Name</label>
                    <input type="text" name="fname" class="form-control form-control-lg" placeholder="ex: John" <?php if(isset($fname)){echo "value='$fname'";} ?>>
                    </div>
                    <div class="mb-4">
                    <label class="form-label fw-semibold">Last Name</label>
                    <input type="text" name="lname" class="form-control form-control-lg" placeholder="ex: Doe"<?php if(isset($lname)){echo "value='$lname'";} ?>>
                    </div>
                    <div class="mb-4">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="ex: john.doe@example.com"<?php if(isset($email)){echo "value='$email'";} ?>>
                    </div>
                    <div class="mb-4">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" name="phone" class="form-control form-control-lg" placeholder="ex: 658 799 8941"<?php if(isset($phone)){echo "value='$phone'";} ?>>
                    </div>
                </div>

                <!-- Column 3: Work / Extra Info -->
                <div class="col-md-5">
                    <div class="mb-4">
                    <label class="form-label fw-semibold">Address</label>
                    <input type="text" name="address" class="form-control form-control-lg" placeholder="ex: 123 Main St, City, Country"<?php if(isset($address)){echo "value='$address'";} ?>>
                    </div>
                    <div class="mb-4">
                    <label class="form-label fw-semibold">Company / Individual</label>
                    <select name="company_individual" class="form-select form-select-lg">
                        <option value="individual">Individual</option>
                        <option value="company">Company</option>
                    </select>
                    </div>
                    <div class="mb-4">
                    <label class="form-label fw-semibold">Profession</label>
                    <input type="text" name="profession" class="form-control form-control-lg" placeholder="ex: Business Person"<?php if(isset($profession)){echo "value='$profession'";} ?>>
                    </div>
                    <div class="mb-4">
                    <label class="form-label fw-semibold">Birthday</label>
                    <input type="date" name="dob" class="form-control form-control-lg"<?php if(isset($dob)){echo "value='$dob'";} ?>>
                    </div>
                </div>
                </div>

                <!-- Submit -->
                <div class="mt-4 text-end">
                <button name="submit" type="submit" class="btn btn-primary btn-lg px-5">Update Profile</button>
                </div>
            </form>
            </div>



            <?php

                if(isset($_POST['submit'])){
                    $fname = $_POST['fname'];
                    $lname = $_POST['lname'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];
                    $address = $_POST['address'];
                    $company_individual = $_POST['company_individual'];
                    $profession = $_POST['profession'];
                    $dob = $_POST['dob'];

                    echo "
                    $fname"."<br>"
                    ."$fname"."<br>"
                    ."$lname"."<br>"
                    ."$email"."<br>"
                    ."$phone"."<br>"
                    ."$address"."<br>"
                    ."$company_individual"."<br>"
                    ."$profession"."<br>"
                    ."$dob"."<br>"
                    ;
                }
            
            
            ?>









                
            </div>
        </div>
    </div>
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