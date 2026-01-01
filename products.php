<?php
session_start();


// DB connect
$mydb = new mysqli("localhost", "root", "", "decora");
if ($mydb->connect_error) {
    die("Database connection failed");
}

/* ===============================
   GET FILTER VALUES
================================ */
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$min_price = isset($_GET['min']) ? trim($_GET['min']) : '';
$max_price = isset($_GET['max']) ? trim($_GET['max']) : '';

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 9; // show 9 first
$limit = $page * $perPage;

/* ===============================
   BUILD FILTER CONDITION ONCE
================================ */
$where = " WHERE 1=1 ";

if ($q !== '') {
    $escaped = $mydb->real_escape_string($q);
    $where .= " AND (product_name LIKE '%$escaped%' OR description LIKE '%$escaped%')";
}

if ($category !== '') {
    $escapedCat = $mydb->real_escape_string($category);
    $where .= " AND category = '$escapedCat'";
}

if ($min_price !== '' && is_numeric($min_price)) {
    $where .= " AND price >= " . floatval($min_price);
}

if ($max_price !== '' && is_numeric($max_price)) {
    $where .= " AND price <= " . floatval($max_price);
}

/* ===============================
   TOTAL COUNT
================================ */
$countSql = "SELECT COUNT(*) as total FROM products" . $where;
$totalResult = $mydb->query($countSql)->fetch_object();
$totalProducts = (int)$totalResult->total;

/* ===============================
   MAIN DATA QUERY
================================ */
$sql = "
    SELECT *
    FROM products
    $where
    LIMIT $limit
";
$result = $mydb->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Products - Decora</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/favicon.ico" rel="icon">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Space+Grotesk&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/my_style.css" rel="stylesheet">
</head>

<body>
<?php include("inc/nav.php"); ?>

<!-- HERO -->
<div class="container-fluid pb-5 bg-primary hero-header">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <h1 class="display-1">Shop Products</h1>
                <p class="mt-3">Handpicked interior design products for modern spaces.</p>
            </div>
        </div>
    </div>
</div>

<!-- FILTERS -->
<div class="container py-5">
    <h2 class="mb-4">Find the right product</h2>

    <form class="row g-3 mb-5" method="GET">
        <div class="col-md-4">
            <input type="text" class="form-control" name="q" placeholder="Search products..." value="<?= htmlspecialchars($q) ?>">
        </div>

        <div class="col-md-2">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php
                $cats = [];

                $catResult = $mydb->query("SELECT category FROM products");
                
                if ($catResult  && $catResult ->num_rows > 0){
                while ($cat = $catResult->fetch_object()){
                    if (!in_array($cat->category, $cats)) {
                        $cats[] = $cat->category;
                    }
                }
                }

                // $cats = ['Living Room','Bedroom','Kitchen','Office','Dining','Lighting'];
                foreach ($cats as $c):
                ?>
                    <option value="<?= $c ?>" <?= $category==$c?'selected':'' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <input type="number" step="0.01" class="form-control" name="min" placeholder="Min price" value="<?= htmlspecialchars($min_price) ?>">
        </div>

        <div class="col-md-2">
            <input type="number" step="0.01" class="form-control" name="max" placeholder="Max price" value="<?= htmlspecialchars($max_price) ?>">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- PRODUCTS -->
    <div class="row g-4">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($prod = $result->fetch_object()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= $prod->image_url ?: 'img/no-image.png' ?>"
                             class="card-img-top"
                             style="height:220px;object-fit:cover;">

                        <div class="card-body d-flex flex-column">
                            <h5><?= htmlspecialchars($prod->product_name) ?></h5>
                            <p><?= htmlspecialchars($prod->description) ?></p>
                            <strong class="mb-3">৳ <?= number_format($prod->price,2) ?></strong>
                            <a href="product_details.php?id=<?= $prod->id ?>" class="btn btn-outline-primary mt-auto">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">No products found.</div>
        <?php endif; ?>
    </div>

    <!-- VIEW MORE -->
    <?php if ($limit < $totalProducts): ?>
        <div class="text-center mt-5">
            <a class="btn btn-outline-primary btn-lg"
               href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                View More Products
            </a>
        </div>
    <?php endif; ?>

</div>

<?php include("inc/footer.php"); ?>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

</body>
</html>
