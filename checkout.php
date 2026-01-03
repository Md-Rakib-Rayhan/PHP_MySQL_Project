<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include_once('db.php');

$user_id = $_SESSION['id'];

/* Fetch Cart */
$result = $mydb->query("
SELECT c.quantity, p.id AS product_id, p.product_name, p.price
FROM cart c
JOIN products p ON c.product_id = p.id
WHERE c.user_id = $user_id
");

if (!$result || $result->num_rows == 0) {
    header("Location: cart.php");
    exit;
}

$items = [];
$subTotal = 0;
$totalQty = 0;

while ($row = $result->fetch_assoc()) {
    $row['total'] = $row['price'] * $row['quantity'];
    $subTotal += $row['total'];
    $totalQty += $row['quantity'];
    $items[] = $row;
}

/* Shipping Logic */
function calculateShipping($division, $qty) {
    $inside = ['Dhaka','Chattogram'];
    $base = in_array($division, $inside) ? 300 : 500;

    if ($qty > 6) return $base * 3;
    if ($qty > 3) return $base * 2;
    return $base;
}

/* Place Order */
// if (isset($_POST['place_order'])) {

//     $division = $mydb->real_escape_string($_POST['division']);
//     $shippingCharge = calculateShipping($division, $totalQty);
//     $finalTotal = $subTotal + $shippingCharge;
//     header("Location: SSL_payment.php?price=$finalTotal");

    // $full_name = $mydb->real_escape_string($_POST['full_name']);
    // $phone = $mydb->real_escape_string($_POST['phone']);
    // $address = $mydb->real_escape_string($_POST['address']);
    // $division = $mydb->real_escape_string($_POST['division']);
    // $district = $mydb->real_escape_string($_POST['district']);
    // $notes = $mydb->real_escape_string($_POST['notes']);
    // $payment_method = $mydb->real_escape_string($_POST['payment_method']);

    // $shippingCharge = calculateShipping($division, $totalQty);
    // $finalTotal = $subTotal + $shippingCharge;

    // // Order
    // $mydb->query("
    //     INSERT INTO orders 
    //     (user_id, full_name, phone, address, shipping_division, shipping_district, notes, total_amount, shipping_charge, payment_method, status)
    //     VALUES 
    //     ('$user_id','$full_name','$phone','$address','$division','$district','$notes','$finalTotal','$shippingCharge','$payment_method','pending')
    // ");

    // $order_id = $mydb->insert_id;

    // // Order items
    // foreach ($items as $item) {
    //     $mydb->query("
    //         INSERT INTO order_items (order_id, product_id, quantity, price)
    //         VALUES ('$order_id','{$item['product_id']}','{$item['quantity']}','{$item['price']}')
    //     ");
    // }

    // // Payment
    // $mydb->query("
    //     INSERT INTO payments (user_id, total_amount, payment_method, status)
    //     VALUES ('$user_id','$finalTotal','$payment_method','completed')
    // ");

    // // Clear cart
    // $mydb->query("DELETE FROM cart WHERE user_id=$user_id");


// }

if (isset($_POST['place_order'])) {

    $_SESSION['pending_order'] = [
        'full_name' => $_POST['full_name'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'division' => $_POST['division'],
        'district' => $_POST['district'],
        'notes' => $_POST['notes'],
        'payment_method' => $_POST['payment_method'],
        'items' => $items,
        'subTotal' => $subTotal,
        'totalQty' => $totalQty
    ];

    $shippingCharge = calculateShipping($_POST['division'], $totalQty);
    $finalTotal = $subTotal + $shippingCharge;

    $_SESSION['pending_order']['shipping'] = $shippingCharge;
    $_SESSION['pending_order']['finalTotal'] = $finalTotal;

    header("Location: SSL_payment.php?price=$finalTotal");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Checkout – Decora</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Space+Grotesk&display=swap" rel="stylesheet">

    <!-- Icon Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="css/style.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<?php include("inc/nav.php"); ?>

<div class="container-fluid py-5 bg-primary text-white">
    <div class="container">
        <h1 class="fw-bold">Checkout</h1>
        <p>Confirm your order details</p>
    </div>
</div>
<div class="container my-5">



    <form method="post">
    <div class="row g-4">

    <div class="col-lg-7">
    <div class="card"><div class="card-body">

    <input class="form-control mb-3" name="full_name" required placeholder="Full Name">
    <input class="form-control mb-3" name="phone" required placeholder="Phone">
    <input class="form-control mb-3" name="address" required placeholder="Address">

    <select class="form-select mb-3" name="division" id="division" required>
    <option value="">Select Division</option>
    <option>Dhaka</option>
    <option>Chattogram</option>
    <option>Rajshahi</option>
    <option>Khulna</option>
    <option>Barishal</option>
    <option>Sylhet</option>
    <option>Rangpur</option>
    <option>Mymensingh</option>
    </select>

    <select class="form-select mb-3" name="district" id="district" required>
    <option value="">Select District</option>
    </select>

    <textarea class="form-control mb-3" name="notes" placeholder="Notes"></textarea>

    <select class="form-select" name="payment_method" required>
    <option value="">Payment Method</option>
    <!-- <option value="cod">Cash on Delivery</option> -->
    <option value="card">Card</option>
    <option value="paypal">PayPal</option>
    </select>

    </div></div>
    </div>

    <div class="col-lg-5">
    <div class="card"><div class="card-body">

    <?php foreach ($items as $i): ?>
    <div class="d-flex justify-content-between">
    <span><?= $i['product_name'] ?> × <?= $i['quantity'] ?></span>
    <strong>৳<?= number_format($i['total'],2) ?></strong>
    </div>
    <?php endforeach; ?>

    <hr>

    <div class="d-flex justify-content-between">
    <span>Subtotal</span>
    <strong>৳<?= number_format($subTotal,2) ?></strong>
    </div>

    <div class="d-flex justify-content-between">
    <span>Shipping</span>
    <strong id="shipping">৳0.00</strong>
    </div>

    <hr>

    <div class="d-flex justify-content-between fs-5">
    <strong>Total</strong>
    <strong id="finalTotal">৳<?= number_format($subTotal,2) ?></strong>
    </div>

    <button class="btn btn-dark w-100 mt-4" name="place_order">Place Order</button>

    </div></div>
    </div>

    </div>
</form>

<script>
const districts = {
Dhaka:["Dhaka","Gazipur","Narayanganj"],
Chattogram:["Chattogram","Cox's Bazar","Comilla"],
Rajshahi:["Rajshahi","Bogra"],
Khulna:["Khulna","Jessore"],
Barishal:["Barishal","Bhola"],
Sylhet:["Sylhet","Habiganj"],
Rangpur:["Rangpur","Dinajpur"],
Mymensingh:["Mymensingh","Jamalpur"]
};

const division = document.getElementById('division');
const district = document.getElementById('district');
const shippingEl = document.getElementById('shipping');
const finalEl = document.getElementById('finalTotal');

const subTotal = <?= $subTotal ?>;
const qty = <?= $totalQty ?>;

division.addEventListener('change', () => {
district.innerHTML = '<option value="">Select District</option>';

districts[division.value]?.forEach(d=>{
district.innerHTML += `<option>${d}</option>`;
});

let base = ['Dhaka','Chattogram'].includes(division.value) ? 300 : 500;
let mult = qty > 6 ? 3 : qty > 3 ? 2 : 1;
let ship = base * mult;

shippingEl.innerText = '৳' + ship.toFixed(2);
finalEl.innerText = '৳' + (subTotal + ship).toFixed(2);
});
</script>
</div>

<?php include("inc/footer.php"); ?>

<!-- JS Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>

