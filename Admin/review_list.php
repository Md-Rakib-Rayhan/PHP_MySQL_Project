<?php
session_start();
if (!isset($_SESSION["isValidAdmin"])) {
    header("Location: login.php");
    exit;
}

$mydb = new mysqli("localhost", "root", "", "decora");
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Client Reviews | Decora Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
</head>

<body>
<div class="layout-wrapper layout-content-navbar">
<div class="layout-container">

    <!-- LEFT MENU -->
    <?php include("inc/menu_left.php"); ?>

    <!-- PAGE -->
    <div class="layout-page">

        <!-- TOP NAV -->
        <?php include("inc/nav.php"); ?>

        <!-- CONTENT -->
        <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <h4 class="fw-bold py-3 mb-4">Client Reviews</h4>

            <div class="card">
                <h5 class="card-header">All Reviews</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped border-bottom">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Service</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "
                                SELECT 
                                    r.id,
                                    u.name AS client_name,
                                    CASE 
                                        WHEN sr.custom_service = 1 
                                        THEN sr.custom_service_description 
                                        ELSE s.service_name 
                                    END AS service_name,
                                    r.rating,
                                    r.review_text,
                                    r.created_at
                                FROM reviews r
                                LEFT JOIN service_requests sr ON r.service_request_id = sr.id
                                LEFT JOIN user u ON sr.user_id = u.id
                                LEFT JOIN services s ON sr.service_id = s.id
                                ORDER BY r.created_at DESC
                            ";

                            $result = $mydb->query($sql);

                            while ($row = $result->fetch_object()) {
                                echo "<tr>
                                        <td>{$row->id}</td>
                                        <td>{$row->client_name}</td>
                                        <td>{$row->service_name}</td>
                                        <td>
                                            <span class='badge bg-success'>{$row->rating} / 5</span>
                                        </td>
                                        <td style='max-width:300px'>{$row->review_text}</td>
                                        <td>{$row->created_at}</td>
                                      </tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- FOOTER -->
        <?php include("inc/footer.php"); ?>

        </div>
    </div>
</div>
<div class="layout-overlay layout-menu-toggle"></div>
</div>

<script src="assets/vendor/libs/jquery/jquery.js"></script>
<script src="assets/vendor/libs/popper/popper.js"></script>
<script src="assets/vendor/js/bootstrap.js"></script>
<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="assets/vendor/js/menu.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
