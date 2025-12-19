<?php
session_start();
if (!isset($_SESSION["isValidAdmin"])) {
    header("Location: login.php");
    exit;
}

$mydb = new mysqli("localhost", "root", "", "decora");

// Validate request ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: request_list.php");
    exit;
}

$id = intval($_GET['id']);

// Initialize update flags
$statusUpdated = false;
$priceUpdated = false;
$expectedUpdated = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // STATUS UPDATE
    if (isset($_POST['status'])) {
        $status = $mydb->real_escape_string($_POST['status']);
        if ($status === 'in_progress') {
            $mydb->query("UPDATE service_requests SET status='$status', start_date=NOW(), updated_at=NOW() WHERE id=$id");
        } elseif ($status === 'completed') {
            $mydb->query("UPDATE service_requests 
                          SET status='$status', end_date=NOW(), duration=DATEDIFF(NOW(), start_date), updated_at=NOW() 
                          WHERE id=$id");
        } else {
            $mydb->query("UPDATE service_requests SET status='$status', updated_at=NOW() WHERE id=$id");
        }
        $statusUpdated = true;
    }

    // PRICE UPDATE
    if (isset($_POST['price'], $_POST['advance_price'])) {
        $price = (float) $_POST['price'];
        $advance = (float) $_POST['advance_price'];
        $due = $price - $advance;

        // Update price and remove expected_price if set
        $mydb->query("UPDATE service_requests 
                      SET price=$price, advance_price=$advance, due_price=$due, expected_price=NULL, updated_at=NOW() 
                      WHERE id=$id");
        $priceUpdated = true;
    }

    // EXPECTED DURATION UPDATE
    if (isset($_POST['expected_duration'])) {
        $expected = (int) $_POST['expected_duration'];
        $mydb->query("UPDATE service_requests SET expected_duration=$expected, updated_at=NOW() WHERE id=$id");
        $expectedUpdated = true;
    }
}

// Fetch request with client info
$sql = "SELECT sr.*, u.name AS client_name, u.email, u.phone, 
               CASE WHEN sr.custom_service=1 THEN sr.custom_service_description ELSE s.service_name END AS service_name
        FROM service_requests sr
        LEFT JOIN user u ON sr.user_id = u.id
        LEFT JOIN services s ON sr.service_id = s.id
        WHERE sr.id = $id
        LIMIT 1";
$result = $mydb->query($sql);
$request = $result->fetch_object();

if (!$request) {
    header("Location: request_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Request Details | Decora</title>
<link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
<link rel="stylesheet" href="assets/vendor/css/core.css" />
<link rel="stylesheet" href="assets/vendor/css/theme-default.css" />
<link rel="stylesheet" href="assets/css/demo.css" />
<link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/vendor/js/helpers.js"></script>
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
          <h4 class="fw-bold py-3 mb-4">Request Details</h4>

          <div class="card mb-4">
            <h5 class="card-header">Request ID: <?= $request->id ?></h5>
            <div class="card-body">
              <p><strong>Client Name:</strong> <?= $request->client_name ?></p>
              <p><strong>Email:</strong> <?= $request->email ?></p>
              <p><strong>Phone:</strong> <?= $request->phone ?></p>
              <p><strong>Service:</strong> <?= $request->service_name ?></p>
              <p><strong>Description:</strong> <?= $request->describe ?></p>

              <!-- Show final price if set, else expected price -->
              <?php if($request->price > 0): ?>
                  <p><strong>Total Price:</strong> $<?= number_format($request->price,2) ?></p>
                  <p><strong>Advance:</strong> $<?= number_format($request->advance_price,2) ?></p>
                  <p><strong>Due:</strong> $<?= number_format($request->due_price,2) ?></p>
              <?php elseif($request->expected_price > 0): ?>
                  <p><strong>Expected Price:</strong> $<?= number_format($request->expected_price,2) ?></p>
                  <small class="text-muted">Final price will be updated once admin sets it.</small>
              <?php else: ?>
                  <p><strong>Total Price:</strong> Not set</p>
              <?php endif; ?>

              <p><strong>Status:</strong> <?= ucfirst($request->status) ?></p>
              <p><strong>Expected Duration:</strong> <?= $request->expected_duration ?> days</p>
              <p><strong>Actual Duration:</strong> <?= $request->duration ?> days</p>
              <p><strong>Start Date:</strong> <?= $request->start_date ?></p>
              <p><strong>End Date:</strong> <?= $request->end_date ?></p>
              <p><strong>Created At:</strong> <?= $request->created_at ?></p>
              <p><strong>Updated At:</strong> <?= $request->updated_at ?></p>
            </div>
          </div>

          <!-- Update Status, Price & Expected Duration -->
          <div class="card mb-4">
            <h5 class="card-header">Update Request</h5>
            <div class="card-body">
              <form method="post" class="row g-3">

                <!-- Status -->
                <div class="col-md-4">
                  <label>Status</label>
                  <select name="status" class="form-select mb-3">
                    <option value="pending" <?= $request->status=='pending'?'selected':'' ?>>Pending</option>
                    <option value="in_progress" <?= $request->status=='in_progress'?'selected':'' ?>>In Progress</option>
                    <option value="completed" <?= $request->status=='completed'?'selected':'' ?>>Completed</option>
                    <option value="cancelled" <?= $request->status=='cancelled'?'selected':'' ?>>Cancelled</option>
                  </select>
                  <button type="submit" class="btn btn-success">Update Status</button>
                </div>

                <!-- Price -->
                <div class="col-md-4">
                  <label>Total Price ($)</label>
                  <input type="number" step="0.01" name="price" class="form-control mb-2" value="<?= $request->price ?? $request->expected_price ?>" required>
                  <label>Advance ($)</label>
                  <input type="number" step="0.01" name="advance_price" class="form-control mb-2" value="<?= $request->advance_price ?? 0 ?>">
                  <button type="submit" class="btn btn-warning">Update Price</button>
                </div>

                <!-- Expected Duration -->
                <div class="col-md-4">
                  <label>Expected Duration (days)</label>
                  <input type="number" name="expected_duration" class="form-control mb-2" value="<?= $request->expected_duration ?>" min="1">
                  <button type="submit" class="btn btn-primary">Update Duration</button>
                </div>

              </form>
            </div>
          </div>

          <a href="request_list.php" class="btn btn-secondary">Back to Requests</a>
        </div>

        <?php include("inc/footer.php"); ?>
      </div>
    </div>
  </div>
</div>

<script src="assets/vendor/libs/jquery/jquery.js"></script>
<script src="assets/vendor/libs/popper/popper.js"></script>
<script src="assets/vendor/js/bootstrap.js"></script>
<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="assets/vendor/js/menu.js"></script>
<script src="assets/js/main.js"></script>

<?php if($statusUpdated): ?>
<script>
Swal.fire('Updated!', 'Request status has been updated.', 'success');
</script>
<?php endif; ?>

<?php if($priceUpdated): ?>
<script>
Swal.fire('Updated!', 'Price & advance have been updated.', 'success');
</script>
<?php endif; ?>

<?php if($expectedUpdated): ?>
<script>
Swal.fire('Updated!', 'Expected duration has been updated.', 'success');
</script>
<?php endif; ?>

</body>
</html>
