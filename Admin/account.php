<?php
session_start();
if (!isset($_SESSION["isValidAdmin"])){
    header("Location: login.php");
}

$mydb = new mysqli("localhost", "root", "", "decora");

// Determine if we are editing or adding
$action = isset($_GET['action']) ? $_GET['action'] : '';
$user_id = isset($_GET['id']) ? $_GET['id'] : '';


// If editing, fetch user data
if ($action == 'edit' && $user_id != '') {
    $sql = "SELECT * FROM user WHERE id = $user_id";
    $result = $mydb->query($sql);
    $user_data = $result->fetch_object();
}
?>


<!-- INSERT New Entry -->
<?php
if (isset($_POST['submit']) && $action != 'edit' && $_POST['name']!="" && $_POST['email']!="" && $_POST['password']!="") {

    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $first_name = $_POST['firstName'];
    $last_name  = $_POST['lastName'];
    $phone      = $_POST['phoneNumber'];
    $address    = $_POST['address'];
    $company    = $_POST['company_individual'];
    $profession = $_POST['profession'];
    $birthday   = $_POST['dob'];
    $password   = md5($_POST['password']);
    $role       = $_POST['role'];
    

    // Image upload
    $profile_pic = '';
    if($_FILES["image"]["name"]!=""){
        $photo_name = $_FILES["image"]["name"];
        $tmp_name = $_FILES["image"]["tmp_name"];
        $upload_path = "img/users/";
        $full_path = $upload_path . $photo_name; // for sql

        // move the photo from temp server
        move_uploaded_file($tmp_name, "../img/users/". $photo_name);

        $profile_pic = $full_path;
    }




    $sql = "INSERT INTO user 
        (name, email, first_name, last_name, phone, address, company_or_individual, profession, birthday, password, profile_pic, role)
        VALUES 
        ('$name','$email','$first_name','$last_name','$phone','$address','$company','$profession','$birthday','$password','$profile_pic', '$role')";

    $mydb->query($sql);

    header("Location: account_list.php?status=success");
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

if (isset($_POST['submit']) && $action == 'edit' && $user_id != '') {

    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $first_name = $_POST['firstName'];
    $last_name  = $_POST['lastName'];
    $phone      = $_POST['phoneNumber'];
    $address    = $_POST['address'];
    $company    = $_POST['company_individual'];
    $profession = $_POST['profession'];
    $birthday   = $_POST['dob'];
    $role       = $_POST['role'];

    // Password update (only if changed)
    if (!empty($_POST['password'])) {
        $password_sql = ", password='" . md5($_POST['password']) . "'";
    } else {
        $password_sql = "";
    }

    // Image upload (optional)
    if (!empty($_FILES['image']['name'])) {
        $photo_name = $_FILES["image"]["name"];
        $tmp_name = $_FILES["image"]["tmp_name"];
        $upload_path = "img/users/";
        $full_path = $upload_path . $photo_name; // for sql
        // move the photo from temp server
        move_uploaded_file($tmp_name, "../img/users/". $photo_name);
        $profile_pic_sql = ", profile_pic ='" . $full_path ."'";

    } else {
        $profile_pic_sql = "";
    }





    $sql = "UPDATE user SET
        name='$name',
        email='$email',
        first_name='$first_name',
        last_name='$last_name',
        phone='$phone',
        address='$address',
        company_or_individual='$company',
        profession='$profession',
        birthday='$birthday',
        role='$role'
        $password_sql
        $profile_pic_sql
        WHERE id='$user_id'";

    $mydb->query($sql);

    header("Location: account_list.php?status=updated");
    exit;
}


?>




<!-- Delete Account -->
 <?php
 
 if (isset($_POST['del_submit']) && $action == 'edit' && $user_id != '') {

    $sql = "DELETE FROM user WHERE id='$user_id'";
    $mydb->query($sql);

    header("Location: account_list.php?status=deleted");
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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="account_list.php"
                        ><i class="bx bx-bell me-1"></i>List of Accounts</a
                      >
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="pages-account-settings-connections.html"
                        ><i class="bx bx-link-alt me-1"></i> Connections</a
                      >
                    </li>
                  </ul>
                  <div class="card mb-4">
                    <h5 class="card-header"><?php echo ($action == 'edit') ? 'Profile Details' : 'Add New Member'; ?></h5>


 <!-- Account -->
  <form id="formAccountSettings" method="POST" enctype="multipart/form-data"> 
                    <div class="card-body">
                      <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img
                          src="<?php echo ($action == 'edit' && $user_data->profile_pic!="") ? '../' . $user_data->profile_pic : '../img/no_profile.jpg'; ?>"
                          alt="user-avatar"
                          class="d-block rounded"
                          height="100"
                          width="100"
                          id="uploadedAvatar"
                        />
                        <div class="button-wrapper">
                          <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Upload new photo</span>
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
                          <!-- <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                            <i class="bx bx-reset d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Reset</span>
                          </button> -->

                          <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        </div>
                      </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">




                        <div class="row">
                          <div class="mb-3 col-md-6">
                            <label for="Name" class="form-label">User Name *</label>
                            <input
                              class="form-control"
                              type="text"
                              id="Name"
                              name="name"
                              value="<?php echo ($action == 'edit') ?  $user_data->name : ''; ?>"
                              autofocus
                              placeholder="rayhan2020"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail *</label>
                            <input
                              class="form-control"
                              type="text"
                              id="email"
                              name="email"
                              value="<?php echo ($action == 'edit') ? $user_data->email : ''; ?>"
                              placeholder="john.doe@example.com"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">First Name</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="firstName"
                              value="<?php echo ($action == 'edit') ? $user_data->first_name : ''; ?>"
                              autofocus
                              placeholder="Rakib"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input class="form-control" type="text" name="lastName" id="lastName" value="<?php echo ($action == 'edit') ? $user_data->last_name : ''; ?>" placeholder="Rayhan"/>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="phoneNumber">Phone Number</label>
                            <div class="input-group input-group-merge">
                              <span class="input-group-text">US (+1)</span>
                              <input
                                type="text"
                                id="phoneNumber"
                                name="phoneNumber"
                                class="form-control"
                                value="<?php echo ($action == 'edit') ? $user_data->phone : ''; ?>"
                                placeholder="202 555 0111"
                              />
                            </div>
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" value="<?php echo ($action == 'edit') ? $user_data->address : ''; ?>" class="form-control" id="address" name="address" placeholder="Dhaka, Bangladesh" />
                          </div>
                          
                          <div class="mb-3 col-md-6">
                            <label for="company_or_individual" class="form-label">Company/Individual</label>
                            <select name="company_individual" class="form-select form-select-lg">
                                <option value="individual"
                                    <?php if(isset($user_data->company_or_individual) && $user_data->company_or_individual == 'individual') echo 'selected'; ?>>
                                    Individual
                                </option>

                                <option value="company"
                                    <?php if(isset($user_data->company_or_individual) && $user_data->company_or_individual == 'company') echo 'selected'; ?>>
                                    Company
                                </option>

                            </select>
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="profession" class="form-label">profession</label>
                            <input class="form-control" type="text" value="<?php echo ($action == 'edit') ? $user_data->profession : ''; ?>" id="profession" name="profession" placeholder="Teacher" />
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="birthday" class="form-label">Death of birth</label>
                            <input class="form-control" value="<?php echo ($action == 'edit') ? $user_data->birthday : ''; ?>" type="date" id="birthday" name="dob" placeholder="16-12-2002" />
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="password" class="form-label">Password *</label>
                            <input class="form-control" value="<?php echo ($action == 'edit') ? $user_data->password : ''; ?>" type="text" id="password" name="password"/>
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" class="form-select form-select-lg">
                                <option value="Client"
                                    <?php if(isset($user_data->role) && $user_data->role == 'Client') echo 'selected'; ?>>
                                    Client
                                </option>

                                <option value="Professionals"
                                    <?php if(isset($user_data->role) && $user_data->role == 'Professionals') echo 'selected'; ?>>
                                    Professionals
                                </option>

                                <option value="Admin"
                                    <?php if(isset($user_data->role) && $user_data->role == 'Admin') echo 'selected'; ?>>
                                    Admin
                                </option>

                            </select>
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="created_at" class="form-label">Created at</label>
                            <input class="form-control" disabled value="<?php echo ($action == 'edit') ? $user_data->created_at : ''; ?>" type="text" id="created_at" name="created_at"/>
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="updated_at" class="form-label">Updated at</label>
                            <input class="form-control" disabled value="<?php echo ($action == 'edit') ? $user_data->updated_at : ''; ?>" type="text" id="updated_at" name="updated_at"/>
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="last_login" class="form-label">Last login</label>
                            <input class="form-control" disabled value="<?php echo ($action == 'edit') ? $user_data->last_login : ''; ?>" type="text" id="last_login" name="last_login"/>
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
                          <form id="formAccountDeactivation" method="POST" action="account.php?action=edit&id=<?php echo $user_id; ?>">
                            <button type="button" id="deleteAccountButton" class="btn btn-danger deactivate-account">Delete Account</button>
                            <input type="hidden" name="del_submit" value="1"> <!-- Hidden input for PHP to detect delete submission -->
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

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
