<div style="display: none;">
<?php


$val_id=urlencode($_POST['val_id']);
$store_id=urlencode("decor69596f6831859");
$store_passwd=urlencode("decor69596f6831859@ssl");
$requested_url = ("https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php?val_id=".$val_id."&store_id=".$store_id."&store_passwd=".$store_passwd."&v=1&format=json");

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $requested_url);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC

$result = curl_exec($handle);

$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if($code == 200 && !( curl_errno($handle)))
{

	# TO CONVERT AS ARRAY
	# $result = json_decode($result, true);
	# $status = $result['status'];

	# TO CONVERT AS OBJECT
	$result = json_decode($result);

	# TRANSACTION INFO
	$status = $result->status;
	$tran_date = $result->tran_date;
	$tran_id = $result->tran_id;
	$val_id = $result->val_id;
	$amount = $result->amount;
	$store_amount = $result->store_amount;
	$bank_tran_id = $result->bank_tran_id;
	$card_type = $result->card_type;

	# EMI INFO
	$emi_instalment = $result->emi_instalment;
	$emi_amount = $result->emi_amount;
	$emi_description = $result->emi_description;
	$emi_issuer = $result->emi_issuer;

	# ISSUER INFO
	$card_no = $result->card_no;
	$card_issuer = $result->card_issuer;
	$card_brand = $result->card_brand;
	$card_issuer_country = $result->card_issuer_country;
	$card_issuer_country_code = $result->card_issuer_country_code;

	# API AUTHENTICATION
	$APIConnect = $result->APIConnect;
	$validated_on = $result->validated_on;
	$gw_version = $result->gw_version;

    echo $status. " ". $tran_date. " ". $tran_id. " ". $card_type;

} else {

	echo "Failed to connect with SSLCOMMERZ";
}


?>

</div>







 <!-- Data Update -->
<?php

session_start();

// Remove pending_order session check
// if (!isset($_SESSION['id'])) {
//     header("Location: login.php");
//     exit;
// }

// Use $_SESSION['pending_order'] only if exists
$data = $_SESSION['pending_order'] ?? null;
if (!$data) {
    // echo "Order data missing, maybe session expired!";
    header("Location: cart.php");
    exit;
}


include_once('db.php');

$user_id = $_SESSION['id'];
$data = $_SESSION['pending_order'];

$full_name = $mydb->real_escape_string($data['full_name']);
$phone = $mydb->real_escape_string($data['phone']);
$address = $mydb->real_escape_string($data['address']);
$division = $mydb->real_escape_string($data['division']);
$district = $mydb->real_escape_string($data['district']);
$notes = $mydb->real_escape_string($data['notes']);
$payment_method = $mydb->real_escape_string($data['payment_method']);

$shipping = $data['shipping'];
$finalTotal = $data['finalTotal'];
$items = $data['items'];


// 1️⃣ INSERT ORDER
$mydb->query("
INSERT INTO orders 
(user_id, full_name, phone, address, shipping_division, shipping_district, notes, total_amount, shipping_charge, payment_method, status)
VALUES 
('$user_id','$full_name','$phone','$address','$division','$district','$notes','$finalTotal','$shipping','$payment_method','pending')
");

$order_id = $mydb->insert_id;


// 2️⃣ INSERT ORDER ITEMS
foreach ($items as $item) {
    $pid = $item['product_id'];
    $qty = $item['quantity'];
    $price = $item['price'];

    $mydb->query("
    INSERT INTO order_items (order_id, product_id, quantity, price)
    VALUES ('$order_id','$pid','$qty','$price')
    ");
}


// 3️⃣ INSERT PAYMENT
$mydb->query("
INSERT INTO payments (user_id, total_amount, payment_method, status)
VALUES ('$user_id','$finalTotal','$payment_method','completed')
");


// 4️⃣ CLEAR CART
$mydb->query("DELETE FROM cart WHERE user_id = $user_id");


// 5️⃣ CLEAN SESSION
unset($_SESSION['pending_order']);


?>









<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Successful</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>
body {
    background: #f8f9fa;
}

.message-box {
    animation: fadeInUp 0.8s ease-in-out;
}

._success {
    box-shadow: 0 15px 25px #00000019;
    padding: 45px;
    text-align: center;
    margin: 80px auto;
    border-bottom: solid 4px #28a745;
    background: #fff;
}

._success i {
    font-size: 60px;
    color: #28a745;
    animation: pop 0.6s ease;
}

._success h2 {
    margin-top: 15px;
    font-size: 36px;
}

._success p {
    font-size: 18px;
    color: #495057;
}

.action-btns {
    margin-top: 25px;
}

.action-btns a {
    margin: 6px;
    min-width: 160px;
    transition: all 0.3s ease;
}


@keyframes pop {
    0% { transform: scale(0); }
    70% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>

<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="message-box _success">
                <i class="fa fa-check-circle"></i>
                <h2>Your payment was successful</h2>
                <p>Thank you for your payment.<br>
                We will contact you shortly.</p>
            </div>
        </div>
    </div>
    <div class="text-center">
        <div class="action-btns">
            <a href="index.php" class="btn btn-success">
                <i class="fa fa-home"></i> Back to Home
            </a>
            <a href="track-order.php" class="btn btn-outline-success">
                <i class="fa fa-truck"></i> Track Order
            </a>
        </div>
    </div>
</div>
</body>
</html> 




