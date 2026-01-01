<?php
session_start();
if (!isset($_SESSION["isValidAdmin"])) {
    header("Location: login.php");
    exit;
}
include_once('../db.php');

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) die("Invalid Order");

$order_id = intval($_GET['id']);
$order_res = $mydb->query("SELECT o.*, u.name AS customer_name, u.email, u.phone 
                           FROM orders o
                           JOIN user u ON o.user_id = u.id
                           WHERE o.id=$order_id");
$order = $order_res->fetch_object();

$items_res = $mydb->query("SELECT oi.*, p.product_name 
                           FROM order_items oi
                           JOIN products p ON oi.product_id = p.id
                           WHERE oi.order_id=$order_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Invoice #<?= $order->id ?></title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { text-align: center; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
table, th, td { border: 1px solid #000; }
th, td { padding: 8px; text-align: left; }
tfoot td { font-weight: bold; }
</style>
</head>
<body>

<h2>Decora Invoice</h2>
<p><strong>Invoice ID:</strong> <?= $order->id ?></p>
<p><strong>Customer:</strong> <?= htmlspecialchars($order->customer_name) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($order->email) ?></p>
<p><strong>Phone:</strong> <?= htmlspecialchars($order->phone) ?></p>
<p><strong>Payment Method:</strong> <?= htmlspecialchars($order->payment_method) ?></p>
<p><strong>Status:</strong> <?= ucfirst($order->status) ?></p>
<p><strong>Date:</strong> <?= $order->created_at ?></p>

<table>
<thead>
<tr>
<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
</tr>
</thead>
<tbody>
<?php
$grand = 0;
while($item = $items_res->fetch_object()):
    $total = $item->quantity * $item->price;
    $grand += $total;
?>
<tr>
<td><?= htmlspecialchars($item->product_name) ?></td>
<td><?= $item->quantity ?></td>
<td>৳ <?= number_format($item->price,2) ?></td>
<td>৳ <?= number_format($total,2) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
<tfoot>
<tr>
<td colspan="3">Grand Total</td>
<td>৳ <?= number_format($grand,2) ?></td>
</tr>
</tfoot>
</table>

<p style="text-align:center; margin-top:30px;">
<button onclick="window.print()">Print Invoice</button>
</p>

</body>
</html>
