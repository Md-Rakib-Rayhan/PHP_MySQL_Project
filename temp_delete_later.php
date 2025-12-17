<?php
session_start();
if (!isset($_SESSION["isValidAdmin"])) {
    header("Location: login.php");
}

$mydb = new mysqli("localhost", "root", "", "decora");

// Determine if we are adding or editing
$action = isset($_GET['action']) ? $_GET['action'] : '';
$user_id = isset($_GET['id']) ? $_GET['id'] : '';

// If editing, fetch user data
if ($action == 'edit' && $user_id != '') {
    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = $mydb->query($sql);
    $user_data = $result->fetch_object();
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
  <head>
    <!-- Add your head section here -->
  </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <?php include("inc/menu_left.php"); ?>
        <!-- / Menu -->

        <div class="layout-page">
          <!-- Navbar -->
          <?php include("inc/nav.php"); ?>
          <!-- / Navbar -->

          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Account Settings /</span> Account
              </h4>

              <!-- Form to Add/Edit Account -->
              <div class="card mb-4">
                <h5 class="card-header"><?php echo ($action == 'edit') ? 'Edit Account' : 'Add New Member'; ?></h5>

                <div class="card-body">
                  <form id="formAccountSettings" method="POST" action="process_account.php">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                      <img src="assets/img/avatars/1.png" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                      <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                          <span class="d-none d-sm-block">Upload new photo</span>
                          <i class="bx bx-upload d-block d-sm-none"></i>
                          <input type="file" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg" />
                        </label>
                        <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                          <i class="bx bx-reset d-block d-sm-none"></i>
                          <span class="d-none d-sm-block">Reset</span>
                        </button>

                        <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                      </div>
                    </div>

                    <hr class="my-0" />

                    <div class="card-body">
                      <div class="row">
                        <div class="mb-3 col-md-6">
                          <label for="Name" class="form-label">User Name</label>
                          <input class="form-control" type="text" id="Name" name="Name" value="<?php echo ($action == 'edit') ? $user_data->name : ''; ?>" autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="email" class="form-label">E-mail</label>
                          <input class="form-control" type="text" id="email" name="email" value="<?php echo ($action == 'edit') ? $user_data->email : ''; ?>" placeholder="john.doe@example.com" />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="firstName" class="form-label">First Name</label>
                          <input class="form-control" type="text" id="firstName" name="firstName" value="<?php echo ($action == 'edit') ? $user_data->first_name : ''; ?>" autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="lastName" class="form-label">Last Name</label>
                          <input class="form-control" type="text" name="lastName" id="lastName" value="<?php echo ($action == 'edit') ? $user_data->last_name : ''; ?>" />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label class="form-label" for="phoneNumber">Phone Number</label>
                          <div class="input-group input-group-merge">
                            <span class="input-group-text">US (+1)</span>
                            <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="202 555 0111" value="<?php echo ($action == 'edit') ? $user_data->phone : ''; ?>" />
                          </div>
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="address" class="form-label">Address</label>
                          <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php echo ($action == 'edit') ? $user_data->address : ''; ?>" />
                        </div>

                        <!-- Add more fields as necessary -->

                        <div class="mt-2">
                          <button type="submit" class="btn btn-primary me-2">
                            <?php echo ($action == 'edit') ? 'Save Changes' : 'Add New Member'; ?>
                          </button>
                          <button type="reset" class="btn btn-outline-secondary">
                            <?php echo ($action == 'edit') ? 'Cancel' : 'Reset'; ?>
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
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
                      <form id="formAccountDeactivation" onsubmit="return false">
                        <div class="form-check mb-3">
                          <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
                          <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
                      </form>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
