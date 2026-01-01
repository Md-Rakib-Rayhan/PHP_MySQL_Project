<?php
session_start();

// Login check
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$mydb = new mysqli("localhost","root","","decora");
if ($mydb->connect_error) exit;

$user_id = $_SESSION['id'];

if (isset($_POST['cart_id'], $_POST['quantity'])) {
    $cart_id = (int)$_POST['cart_id'];
    $quantity = max(1,(int)$_POST['quantity']);
    $mydb->query("UPDATE cart SET quantity=$quantity WHERE id=$cart_id AND user_id=$user_id");
    echo "success";
}
?>
