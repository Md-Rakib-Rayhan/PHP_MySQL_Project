<?php
session_start();

if (!isset($_SESSION["isValidAdmin"])) {
    header("Location: login.php");
    exit;
}

include_once('../db.php');

// Make sure $user_id is an integer to avoid SQL errors
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id <= 0) {
    header("Location: account_list.php");
    exit;
}

/* ===============================
   FETCH USER (FOR NAME DISPLAY)
================================ */
$user_sql = "SELECT name, role FROM `user` WHERE id = $user_id";
$user_res = $mydb->query($user_sql);
$user = $user_res->fetch_object();

// If user is not a professional, redirect back
if (!$user || $user->role !== 'Professionals') {
    header("Location: account.php?action=edit&id=".$user_id);
    exit;
}

/* ===============================
   FETCH PROFESSIONAL DATA
================================ */
$prof_sql = "SELECT * FROM professionals WHERE user_id = $user_id";
$prof_res = $mydb->query($prof_sql);
$professional = $prof_res->fetch_object();

/* ===============================
   INSERT / UPDATE LOGIC
================================ */
if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $experience = $_POST['experience_years'];
    $is_verified = isset($_POST['is_verified']) ? 1 : 0;

    if ($professional) {
        // UPDATE
        $sql = "UPDATE professionals SET
                name = '$name',
                bio = '$bio',
                experience_years = '$experience',
                is_verified = '$is_verified'
                WHERE user_id = '$user_id'";
    } else {
        // INSERT
        $sql = "INSERT INTO professionals
                (user_id, name, bio, experience_years, is_verified)
                VALUES
                ('$user_id', '$name', '$bio', '$experience', '$is_verified')";
    }

    $mydb->query($sql);

    header("Location: professional_profile.php?id=$user_id&status=success");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Account settings - Account | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
  </head>

<body>
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">

    <?php include("inc/menu_left.php"); ?>

    <div class="layout-page">
      <?php include("inc/nav.php"); ?>

      <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

          <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Professional /</span> Profile
          </h4>

          <?php if (isset($_GET['status'])) { ?>
            <div class="alert alert-success">Professional profile saved successfully</div>
          <?php } ?>

          <div class="card">
            <h5 class="card-header">Professional Details</h5>

            <form method="POST">
              <div class="card-body">
                <div class="row">

                  <div class="mb-3 col-md-6">
                    <label class="form-label">Professional Name *</label>
                    <input type="text"
                           class="form-control"
                           name="name"
                           value="<?= htmlspecialchars($professional->name ?? $user->name) ?>"
                           required>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label class="form-label">Experience (Years) *</label>
                    <input type="number"
                           class="form-control"
                           name="experience_years"
                           value="<?= htmlspecialchars($professional->experience_years ?? '') ?>"
                           required>
                  </div>

                  <div class="mb-3 col-md-12">
                    <label class="form-label">Professional Bio *</label>
                    <textarea class="form-control"
                              name="bio"
                              rows="4"
                              required><?= htmlspecialchars($professional->bio ?? '') ?></textarea>
                  </div>

                  <div class="mb-3 col-md-6">
                    <div class="form-check mt-3">
                      <input class="form-check-input"
                             type="checkbox"
                             name="is_verified"
                             <?= (isset($professional->is_verified) && $professional->is_verified) ? 'checked' : '' ?>>
                      <label class="form-check-label">
                        Verified Professional
                      </label>
                    </div>
                  </div>

                </div>

                <div class="mt-3">
                  <button type="submit" name="submit" class="btn btn-primary me-2">
                    Save Professional Profile
                  </button>

                  <a href="account.php?action=edit&id=<?= $user_id ?>"
                     class="btn btn-outline-secondary">
                     Back to Account
                  </a>
                </div>

              </div>
            </form>
          </div>

        </div>

        <?php include("inc/footer.php"); ?>

      </div>
    </div>
  </div>
</div>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/pages-account-settings-account.js"></script>


</body>
</html>
