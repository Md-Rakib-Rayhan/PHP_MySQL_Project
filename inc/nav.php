<div class="container-fluid sticky-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light border-bottom border-2 border-white">
                <a href="index.php" class="navbar-brand">
                    <h1>Decora</h1>
                </a>
                <button type="button" class="navbar-toggler ms-auto me-0" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="index.php" class="nav-item nav-link active">Home</a>
                        <a href="about.html" class="nav-item nav-link">About</a>
                        <a href="service.html" class="nav-item nav-link">Services</a>
                        <a href="project.html" class="nav-item nav-link">Projects</a>
                        <div class="nav-item dropdown">
                            <a href="#!" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu bg-light mt-2">
                                <a href="feature.html" class="dropdown-item">Features</a>
                                <a href="team.html" class="dropdown-item">Our Team</a>
                                <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                                <a href="404.html" class="dropdown-item">404 Page</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Contact</a>
                        
                        
                        <?php
                        if (!isset($_SESSION["isvalid"])){
                        ?>
                            <a href="login.php" class="nav-item nav-link d-flex align-items-center">
                            <button class="btn btn-outline-success me-2" type="button">Log in</button>
                            </a>

                        <?php
                        }else{
                        ?>


                        
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                <div class="avatar avatar-online">
                                <img src="admin/assets/img/avatars/1.png" alt="Profile" 
                
                 width="35" height="35"
                   class="w-px-35 h-auto rounded-circle" />
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                        <img src="admin/assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block">John Doe</span>
                                        <small class="text-muted">Admin</small>
                                    </div>
                                    </div>
                                </a>
                                </li>
                                <li>
                                <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bx bx-user me-2"></i>
                                    <span class="align-middle">My Profile</span>
                                </a>
                                </li>
                                <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bx bx-cog me-2"></i>
                                    <span class="align-middle">Settings</span>
                                </a>
                                </li>
                                <li>
                                <a class="dropdown-item" href="#">
                                    <span class="d-flex align-items-center align-middle">
                                    <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                    <span class="flex-grow-1 align-middle">Billing</span>
                                    <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                                    </span>
                                </a>
                                </li>
                                <li>
                                <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                <a class="dropdown-item" href="auth-login-basic.html">
                                    <i class="bx bx-power-off me-2"></i>
                                    <span class="align-middle">Log Out</span>
                                </a>
                                </li>
                            </ul>

                           
                

                        <?php
                        }
                        ?> 

                        <!-- <?php
//if (!isset($_SESSION["isvalid"])) {
?>
    <a href="login.php" class="nav-item nav-link d-flex align-items-center">
        <button class="btn btn-outline-success me-2" type="button">Log in</button>
    </a>
<?php
//} else {
?>
  
    <div class="nav-item dropdown d-flex align-items-center ms-3">
        <a href="#" class="d-flex align-items-center nav-link dropdown-toggle" data-bs-toggle="dropdown">
            <img src="image.png" 
                 alt="Profile" 
                 class="rounded-circle" 
                 width="35" height="35"
                 style="object-fit: cover;">
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
        </ul>
    </div>
<?php
//}
?> -->

                        
                        
                    </div>
                </div>
            </nav>
        </div>
    </div>