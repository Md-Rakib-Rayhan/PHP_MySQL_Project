<?php
session_start();
if (!isset($_SESSION["isValidAdmin"])){
    header("Location: login.php");
  }

include_once('../db.php');
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

    <title>Account settings - Pages | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

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

  <!-- Success Message after new entry -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <?php
     if(isset($_GET["status"]))
      {
        if($_GET["status"]=="success"){
            echo '
        <script>
            Swal.fire({
            title: "Done!",
            text: "New Account Created Successfully",
            icon: "success",
            timer: 3000, 
            showConfirmButton: false
            });
        </script>
        ';
        }
        elseif($_GET["status"]=="updated"){
            echo '
        <script>
            Swal.fire({
            title: "Done!",
            text: "Updated Successfully",
            icon: "success",
            timer: 3000, 
            showConfirmButton: false
            });
        </script>
        ';
        }
        elseif($_GET["status"]=="deleted"){
            echo '
        <script>
            Swal.fire({
            title: "Deleted!",
            text: "Account has Deleted Successfully",
            icon: "success",
            timer: 3000, 
            showConfirmButton: false
            });
        </script>
        ';
        }
      }

     ?>



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
              <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Services
              </h4>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link" href="service.php"
                        ><i class="bx bx-user me-1"></i> Add Service</a
                      >
                    </li>
                    <li class="nav-item">
                      <a class="nav-link active" href="javascript:void(0);"
                        ><i class="bx bx-bell me-1"></i> List of Service</a
                      >
                    </li>
                    <!-- <li class="nav-item">
                      <a class="nav-link" href="pages-account-settings-connections.html"
                        ><i class="bx bx-link-alt me-1"></i> Connections</a
                      >
                    </li> -->
                  </ul>
                  <div class="card">


                    <!-- List of Accounts -->
                    <h5 class="card-header">Running Services</h5>

                    <div class="card-body">
                            <br>
                            <div class="role-section">

                                <!-- Display role title -->
                                <h5 class="role-title" style="color:#000000; font-weight: 900;">All Services <hr></h5> 
                                <div class="error"></div>
                                
                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless border-bottom">
                                        <thead>
                                            <tr>
                                                <th class="text-nowrap">Id</th>
                                                <th class="text-nowrap">Service Name</th>
                                                <th class="text-nowrap">Description</th>
                                                <th class="text-nowrap">Price</th>
                                                <th class="text-nowrap">Last Update</th>
                                                <th class="text-nowrap text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        $sql = "SELECT * FROM services"; 
                                        $row_data = $mydb->query($sql);

                                        while ($data = $row_data->fetch_object()) {
                                        ?>

                                            <tr>
                                                <td class="text-nowrap"><?php echo "$data->id";?></td>
                                                <td class="text-nowrap"><?php echo "$data->service_name";?></td>
                                                <td style="max-width: 400px; word-wrap: break-word; white-space: normal;">
                                                    <?php echo "$data->description";?>
                                                </td>
                                                <td class="text-nowrap"><?php echo "$data->avg_price";?></td>
                                                <td class="text-nowrap"><?php echo "$data->updated_at";?></td>

                                                
                                                <td class="text-nowrap text-center">
                                                      <?php
                                                      echo '<a href="service.php?action=edit&id=' . $data->id . '" class="btn btn-primary me-2">Update</a>';
                                                      ?>
                                                </td>
                                            </tr>

                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        
                    </div>



                    
                    <div class="card-body">
                      
                      <form action="javascript:void(0);">
                        <div class="row">
                          <div class="mt-4">
                            <a href="service.php" class="btn btn-primary me-2">Add Member</a>
                          </div>
                        </div>
                      </form>
                    </div>

                    <!-- List of Account -->


                  </div>
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


  </body>
</html>
