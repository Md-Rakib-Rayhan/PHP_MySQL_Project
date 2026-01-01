<?php
session_start();
if (!isset($_SESSION["isValidAdmin"])){
    header("Location: login.php");
}

include_once('../db.php');

// Determine if we are editing or adding
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';


// If editing, fetch user data
if ($action == 'edit' && $id != '') {
    $sql = "SELECT * FROM services WHERE id = $id";
    $result = $mydb->query($sql);
    $user_data = $result->fetch_object();
}
?>


<!-- INSERT New Entry -->
<?php
if (isset($_POST['submit']) && $action != 'edit' && $_POST['service_name']!="" && $_POST['avg_price']!="" && $_POST['description']!="" && $_FILES["image"]["name"]!="") {


    $service_name = $_POST['service_name'];
    $avg_price = $_POST['avg_price'];
    $description = $_POST['description'];
    

    $photo_name = $_FILES["image"]["name"];
    $tmp_name = $_FILES["image"]["tmp_name"];
    $upload_path = "img/services/";
    $full_path = $upload_path . $photo_name; // for sql

    // move the photo from temp server
    move_uploaded_file($tmp_name, "../img/services/". $photo_name);

    $service_pic = $full_path;


 


    $sql = "INSERT INTO services 
        ( `service_name`, `description`, `avg_price`, `image_url`)
        VALUES 
        ('$service_name','$description','$avg_price','$service_pic')";

    $mydb->query($sql);

    header("Location: service_list.php?status=success");
    exit;
}elseif(isset($_POST['submit']) && $action != 'edit')
{
  echo '
        <script>
            window.onload = function() {
            problem = "<strong>Please Fillup Required Once";
            notify = document.getElementById("wronginput");
            notify.classList.add("alert-danger");
            notify.innerHTML = problem;

            notify.style.display = "block";
            setTimeout(function() {
                        notify.style.display = "none";
            }, 5000)
            }
        </script>
        ';
}

?>





<!-- UPDATE -->
<?php

if (isset($_POST['submit']) && $action == 'edit' && $id != '') {


    $service_name = $_POST['service_name'];
    $avg_price = $_POST['avg_price'];
    $description = $_POST['description'];

    


    // Image upload (optional)
    if (!empty($_FILES['image']['name'])) {
        $photo_name = $_FILES["image"]["name"];
        $tmp_name = $_FILES["image"]["tmp_name"];
        $upload_path = "img/services/";
        $full_path = $upload_path . $photo_name; // for sql

        // move the photo from temp server
        move_uploaded_file($tmp_name, "../img/services/". $photo_name);

        $service_pic = ", image_url ='" . $full_path ."'";

    } else {
        $service_pic = "";
    }





    $sql = "UPDATE services SET
        service_name='$service_name',
        description='$description',
        avg_price='$avg_price'
        $service_pic
        WHERE id='$id'";

    $mydb->query($sql);

    header("Location: service_list.php?status=updated");
    exit;
}


?>




<!-- Delete Account -->
 <?php
 
 if (isset($_POST['del_submit'])) {

    $sql = "DELETE FROM services WHERE id='$id'";
    $mydb->query($sql);

    header("Location: service_list.php?status=deleted");
    exit;
}

 
 ?>











<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free"
>
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


<!-- Include SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
<!-- Menu -->
<?php
  include("inc/menu_left.php");
?>
<!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">


<!-- Navbar -->

<?php
      include("inc/nav.php");
?>

<!-- / Navbar -->



          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="alert text-center" id="wronginput" style="display:none;"></div>
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Services</h4>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Add Service</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="service_list.php"
                        ><i class="bx bx-bell me-1"></i>List of Service</a
                      >
                    </li>
                    
                  </ul>
                  <div class="card mb-4">
                    <h5 class="card-header"><?php echo ($action == 'edit') ? 'Update Service' : 'Add Service'; ?></h5>


 <!-- Account -->
                  <form id="formAccountSettings" method="POST" enctype="multipart/form-data"> 
                    <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img
                          src="<?php echo ($action == 'edit' && $user_data->image_url!="") ? '../' . $user_data->image_url : '../img/no_profile.jpg'; ?>"
                          alt="service-image"
                          class="d-block rounded border" 
                          id="uploadedAvatar"
                          style="
                            width: 320px; 
                            height: 180px; 
                            object-fit: cover; 
                            border: 1px solid #d9dee3; 
                            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                          "
                        />
                        <div class="button-wrapper">
                          <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Upload Service Photo</span>
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input
                              type="file"
                              name="image"
                              id="upload"
                              class="account-file-input"
                              hidden
                              accept="image/png, image/jpeg"
                            />
                          </label>
                          <p class="text-muted mb-0">Recommended: 16:9 Interior Shot (e.g. 1920x1080).</p>
                          <p class="text-muted mb-0">Max size of 2MB.</p>
                        </div>
                    </div>

                    </div>
                    <hr class="my-0" />
                    <div class="card-body">



                        

                        <div class="row">
                          <div class="mb-3 col-md-6">
                            <label for="last_login" class="form-label">Service Id</label>
                            <input class="form-control" disabled value="<?php echo ($action == 'edit') ? $user_data->id : ''; ?>" type="text" id="id" name="id"/>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="Name" class="form-label">Service Name *</label>
                            <input
                              class="form-control"
                              type="text"
                              id="Name"
                              name="service_name"
                              value="<?php echo ($action == 'edit') ?  $user_data->service_name : ''; ?>"
                              autofocus
                              placeholder="Wall Art"
                            />
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">Avg price *</label>
                            <input
                              class="form-control"
                              type="text"
                              id="avg_price"
                              name="avg_price"
                              value="<?php echo ($action == 'edit') ? $user_data->avg_price : ''; ?>"
                              autofocus
                              placeholder="1500"
                            />
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo ($action == 'edit') ? $user_data->description : ''; ?></textarea>
                          </div>

                          
                          
                          <div class="mb-3 col-md-6">
                            <label for="created_at" class="form-label">Created at</label>
                            <input class="form-control" disabled value="<?php echo ($action == 'edit') ? $user_data->created_at : ''; ?>" type="text" id="created_at" name="created_at"/>
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="updated_at" class="form-label">Updated at</label>
                            <input class="form-control" disabled value="<?php echo ($action == 'edit') ? $user_data->updated_at : ''; ?>" type="text" id="updated_at" name="updated_at"/>
                          </div>

                          



               
                        <div class="mt-2">
                          <button type="submit" name="submit" class="btn btn-primary me-2">
                            <?php echo ($action == 'edit') ? 'Save Changes' : 'Add New Member'; ?>
                          </button>
                          <a href="index.php" class="btn btn-outline-secondary" style=":hover {color:white;}">Cancel</a>
                        </div>
                  </form>



                    </div>
<!-- /Account -->
                  </div>

                  <!-- Delete Account Section (only when editing) -->
                  <?php if ($action == 'edit') { ?>
                      <div class="card">
                        <h5 class="card-header">Delete Account</h5>
                        <div class="card-body">
                          <div class="mb-3 col-12 mb-0">
                            <div class="alert alert-warning">
                              <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete this account?</h6>
                              <p class="mb-0">Once you delete the account, there is no going back. Please be certain.</p>
                            </div>
                          </div>
                          <form id="formAccountDeactivation" method="POST">
                            <button type="submit" name="del_submit" id="deleteAccountButton" class="btn btn-danger deactivate-account">Delete Account</button>
                            <input type="hidden" value="1"> <!-- Hidden input for PHP to detect delete submission -->
                          </form>
                        </div>
                      </div>
                    <?php } ?>



      <script>
        document.getElementById('deleteAccountButton').addEventListener('click', function () {
          Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
          }).then((result) => {
            if (result.isConfirmed) {
              // Submit the form after confirmation
              document.getElementById('formAccountDeactivation').submit();
            }
          });
        });
      </script>



                </div>
              </div>
            </div>
            <!-- / Content -->

<!-- Footer -->
<?php
  include("inc/footer.php");
?>
<!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->



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
