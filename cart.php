<?php
session_start();

// Login check
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

/* DB CONNECT */
$mydb = new mysqli("localhost", "root", "", "decora");
if ($mydb->connect_error) {
    die("Database connection failed");
}

$user_id = $_SESSION['id'];

/* REMOVE ITEM */
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $cart_id = (int) $_GET['remove'];
    $mydb->query("DELETE FROM cart WHERE id=$cart_id AND user_id=$user_id");
    header("Location: cart.php");
    exit;
}

/* UPDATE QUANTITY */
if (isset($_POST['update_qty'])) {
    foreach ($_POST['qty'] as $cart_id => $qty) {
        $qty = max(1, (int)$qty);
        $mydb->query("UPDATE cart SET quantity=$qty WHERE id=$cart_id AND user_id=$user_id");
    }
    header("Location: cart.php");
    exit;
}

/* FETCH CART ITEMS */
$sql = "
    SELECT c.id AS cart_id, c.quantity,
           p.product_name, p.price, p.image_url
    FROM cart c
    INNER JOIN products p ON c.product_id = p.id
    WHERE c.user_id = $user_id
";
$result = $mydb->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>My Cart – Decora</title>
<meta name="viewport" content="width=device-width, initial-scale=1">


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

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
    <link href="css/my_services.css" rel="stylesheet">

<style>
    input.qty-input { width: 60px; text-align: center; }
</style>
</head>

<body>

<?php include("inc/nav.php"); ?>

<!-- HERO -->
<div class="container-fluid py-5 bg-dark text-white">
    <div class="container">
        <h1 class="fw-bold">My Shopping Cart</h1>
        <p class="mb-0">Review your selected products</p>
    </div>
</div>

<!-- CART -->
<div class="container my-5">

<?php if ($result && $result->num_rows > 0): ?>

<form method="post" id="cartForm">

<div class="table-responsive">
<table class="table align-middle">
<thead class="table-light">
<tr>
    <th>Product</th>
    <th>Price</th>
    <th width="120">Quantity</th>
    <th>Total</th>
    <th></th>
</tr>
</thead>

<tbody>
<?php
$grandTotal = 0;
while ($item = $result->fetch_object()):
    $itemTotal = $item->price * $item->quantity;
    $grandTotal += $itemTotal;
?>
<tr data-price="<?= $item->price ?>">
    <td>
        <div class="d-flex align-items-center">
            <img src="<?= $item->image_url ?>" width="70" height="60" class="rounded me-3" style="object-fit:cover;">
            <strong><?= htmlspecialchars($item->product_name) ?></strong>
        </div>
    </td>

    <td>৳ <?= number_format($item->price, 2) ?></td>

    <td>
        <input type="number"
               name="qty[<?= $item->cart_id ?>]"
               value="<?= $item->quantity ?>"
               min="1"
               class="form-control form-control-sm qty-input">
    </td>

    <td class="item-total">৳ <?= number_format($itemTotal, 2) ?></td>

    <td>
        <a href="?remove=<?= $item->cart_id ?>"
           class="btn btn-sm btn-outline-danger"
           onclick="return confirm('Remove this product?')">
           <i class="bi bi-trash"></i>
        </a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<div class="row justify-content-end mt-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Cart Summary</h5>
                <p class="d-flex justify-content-between">
                    <span>Total</span>
                    <strong id="grandTotal">৳ <?= number_format($grandTotal, 2) ?></strong>
                </p>

                <button type="submit" name="update_qty" class="btn btn-outline-dark w-100 mb-2">
                    Update Cart
                </button>

                <a href="checkout.php" class="btn btn-dark w-100">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    </div>
</div>

</form>

<?php else: ?>

<div class="text-center py-5">
    <i class="bi bi-cart-x fs-1 text-muted"></i>
    <h4 class="mt-3">Your cart is empty</h4>
    <a href="products.php" class="btn btn-dark mt-3">Browse Products</a>
</div>

<?php endif; ?>

</div>

<?php include("inc/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- LIVE TOTAL JS -->
<script>
const qtyInputs = document.querySelectorAll('.qty-input');
const grandTotalEl = document.getElementById('grandTotal');

function updateTotals() {
    let grandTotal = 0;
    document.querySelectorAll('tbody tr').forEach(row => {
        const price = parseFloat(row.dataset.price);
        const qty = parseInt(row.querySelector('.qty-input').value);
        const total = price * qty;
        row.querySelector('.item-total').textContent = '৳ ' + total.toFixed(2);
        grandTotal += total;
    });
    grandTotalEl.textContent = '৳ ' + grandTotal.toFixed(2);
}

qtyInputs.forEach(input => {
    input.addEventListener('input', updateTotals);
});

qtyInputs.forEach(input => {
    input.addEventListener('input', function() {
        updateTotals();

        const cartId = this.name.match(/\d+/)[0];
        const qty = this.value;

        // Send AJAX request to update quantity in DB
        fetch('update_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `cart_id=${cartId}&quantity=${qty}`
        })
        .then(res => res.text())
        .then(data => {
            console.log(data); // optional, for debug
        });
    });
});

</script>

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
