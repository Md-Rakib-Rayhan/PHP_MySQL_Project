<?php
session_start();
if (!isset($_SESSION["isValidAdmin"])) {
    header("Location: login.php");
}

include_once('../db.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
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
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">

    <!-- Sidebar Menu -->
    <?php include("inc/menu_left.php"); ?>

    <!-- Layout page -->
    <div class="layout-page">
      <!-- Navbar -->
      <?php include("inc/nav.php"); ?>

      <!-- Content wrapper -->
      <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
          <h4 class="fw-bold py-3 mb-4">Client Requests</h4>

          <div class="card">
            <h5 class="card-header">All Requests</h5>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-borderless border-bottom">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Client Name</th>
                      <th>Service</th>
                      <th>Price</th>
                      <th>Status</th>
                      <th>Date Created</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT sr.id, u.name AS client_name, 
                                   CASE WHEN sr.custom_service=1 THEN sr.custom_service_description ELSE s.service_name END AS service_name,
                                   sr.price, sr.status, sr.created_at
                            FROM service_requests sr
                            LEFT JOIN user u ON sr.user_id = u.id
                            LEFT JOIN services s ON sr.service_id = s.id
                            ORDER BY sr.created_at DESC";
                    $result = $mydb->query($sql);
                    while ($req = $result->fetch_object()) {
                        echo '<tr>
                                <td>'.$req->id.'</td>
                                <td>'.$req->client_name.'</td>
                                <td>'.$req->service_name.'</td>
                                <td>$'.number_format($req->price,2).'</td>
                                <td>';
                                    switch($req->status){
                                        case 'pending': echo '<span class="badge bg-warning text-dark">Pending</span>'; break;
                                        case 'in_progress': echo '<span class="badge bg-info text-dark">In Progress</span>'; break;
                                        case 'completed': echo '<span class="badge bg-success">Completed</span>'; break;
                                        case 'cancelled': echo '<span class="badge bg-danger">Cancelled</span>'; break;
                                    }
                        echo    '</td>
                                <td>'.$req->created_at.'</td>
                                <td class="text-center">
                                    <a href="request_view.php?id='.$req->id.'" class="btn btn-sm btn-primary me-2">View</a>
                                    <button class="btn btn-sm btn-danger" onclick="deleteRequest('.$req->id.')">Delete</button>
                                </td>
                              </tr>';
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- / Content -->

        <!-- Footer -->
        <?php include("inc/footer.php"); ?>
      </div>
      <!-- / Content wrapper -->
    </div>
    <!-- / Layout page -->
  </div>
  <!-- Overlay -->
  <div class="layout-overlay layout-menu-toggle"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteRequest(id){
  Swal.fire({
    title: 'Are you sure?',
    text: "This request will be deleted permanently!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'request_delete.php?id=' + id;
    }
  });
}
</script>

<script src="assets/vendor/libs/jquery/jquery.js"></script>
<script src="assets/vendor/libs/popper/popper.js"></script>
<script src="assets/vendor/js/bootstrap.js"></script>
<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="assets/vendor/js/menu.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
