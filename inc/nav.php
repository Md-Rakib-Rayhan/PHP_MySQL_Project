<?php

$mydb = new mysqli("localhost","root","","decora");

$order_count = 0;
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $res = $mydb->query("SELECT COUNT(*) AS total FROM service_requests WHERE user_id=$user_id");
    $row = $res->fetch_assoc();
    $order_count = $row['total'];
}

$cart_count = 0;
if (isset($_SESSION['id'])) {
    $uid = $_SESSION['id'];
    $resCart = $mydb->query("SELECT SUM(quantity) AS total FROM cart WHERE user_id=$uid");
    if ($resCart) {
        $cartRow = $resCart->fetch_assoc();
        $cart_count = $cartRow['total'] ?? 0;
    }
}


?>

<div class="container-fluid sticky-top">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light border-bottom border-2 border-white">

            <!-- LEFT: Hamburger -->
            <button class="navbar-toggler me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- BRAND (center-ish) -->
            <a href="index.php" class="navbar-brand">
                <h1 class="m-0">Decora</h1>
            </a>

            <!-- RIGHT SIDE: Profile OR Login -->
            <div class="d-flex ms-auto d-lg-none">
                <?php if (!isset($_SESSION["isvalid"])) { ?>
                    <a href="login.php" class="nav-link">
                        <button class="btn btn-outline-success btn-sm">Log in</button>
                    </a>
                <?php } else { ?>
                    <a class="nav-link" href="#" data-bs-toggle="dropdown">
                        <img src="<?php if(isset($_SESSION["pic"])){echo $_SESSION["pic"];}else{echo 'img/no_profile.jpg';} ?>" width="36" height="36" class="rounded-circle">
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow p-3 my_profile_dropdown" style="width:260px; border-radius:12px;">
                        <li class="mb-3">
                            <div class="d-flex align-items-center">
                                <img src="<?php if(isset($_SESSION["pic"])){echo $_SESSION["pic"];}else{echo 'img/no_profile.jpg';} ?>" width="48" height="48" class="rounded-circle me-3">
                                <div>
                                    <strong class="d-block"><?php echo $_SESSION["name"]; ?></strong>
                                    <small class="text-muted">User</small>
                                </div>
                            </div>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="profile.php">
                                <i class="bi bi-person me-3 fs-5 text-secondary"></i> My Profile
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="order_tracking.php">
                                <i class="bi bi-person me-3 fs-5 text-secondary"></i> Track Orders
                            </a>
                        </li>


                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="project_status.php">
                                <i class="bi bi-card-list me-3 fs-5 text-secondary"></i>
                                Project Status <?php if($order_count > 0){ ?>
                                                    <span class="badge bg-danger ms-auto"><?= $order_count ?></span>
                                                <?php } ?>
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="logout.php">
                                <i class="bi bi-power text-danger me-3 fs-5"></i> Log Out
                            </a>
                        </li>
                    </ul>
                <?php } ?>
                <a href="cart.php" class="nav-item nav-link position-relative">
                    <i class="bi bi-cart fs-5"></i>
                    <?php if ($cart_count > 0) { ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $cart_count ?>
                        </span>
                    <?php } ?>
                </a>
            </div>

            <!-- MIDDLE: Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.php" class="nav-item nav-link active">Home</a>
                    <a href="service.php" class="nav-item nav-link">Services</a>
                    <a href="products.php" class="nav-item nav-link">Products</a>
                    <a href="professionals.php" class="nav-item nav-link">Our Team</a>
                    <a href="about.php" class="nav-item nav-link">About</a>

                    
                    <a href="contact.php" class="nav-item nav-link">Book Appointment</a>
                </div>
            </div>

            <!-- RIGHT: Profile (desktop) -->
            <div class="d-none d-lg-flex ms-3 align-items-center">

                <?php if (!isset($_SESSION["isvalid"])) { ?>

                    <a href="login.php" class="nav-link">
                        <button class="btn btn-outline-success">Log in</button>
                    </a>

                <?php } else { ?>

                    <div class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <img src="<?php if(isset($_SESSION["pic"])){echo $_SESSION["pic"];}else{echo 'img/no_profile.jpg';} ?>" width="38" height="38" class="rounded-circle me-2">
                            <span class="fw-semibold"><?php echo $_SESSION["name"]; ?></span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow p-3 my_profile_dropdown" style="width:260px; border-radius:12px;">

                            <li class="mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="<?php if(isset($_SESSION["pic"])){echo $_SESSION["pic"];}else{echo 'img/no_profile.jpg';} ?>" width="48" height="48" class="rounded-circle me-3">
                                    <div>
                                        <strong class="d-block"><?php echo $_SESSION["name"]; ?></strong>
                                        <small class="text-muted">User</small>
                                    </div>
                                </div>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="profile.php">
                                    <i class="bi bi-person me-3 fs-5 text-secondary"></i> My Profile
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="order_tracking.php">
                                    <i class="bi bi-person me-3 fs-5 text-secondary"></i> Track Orders
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="project_status.php">
                                    <i class="bi bi-card-list me-3 fs-5 text-secondary"></i>
                                    Project Status <?php if($order_count > 0){ ?>
                                        <span class="badge bg-danger ms-auto"><?= $order_count ?></span>
                                    <?php } ?>
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="logout.php">
                                    <i class="bi bi-power text-danger me-3 fs-5"></i> Log Out
                                </a>
                            </li>

                        </ul>
                    </div>
                <?php } ?>
                <a href="cart.php" class="nav-item nav-link position-relative">
                    <i class="bi bi-cart fs-5"></i>
                    <?php if ($cart_count > 0) { ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $cart_count ?>
                        </span>
                    <?php } ?>
                </a>

            </div>

        </nav>
    </div>
</div>
