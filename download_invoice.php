<?php
session_start();
if(!isset($_SESSION['id'])) die("Unauthorized");

$user_id = $_SESSION['id'];
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if(!$order_id) die("Invalid order");

// DB connect
$mydb = new mysqli("localhost", "root", "", "decora");
if($mydb->connect_error) die("Database connection failed");

// Fetch order
$order_sql = "SELECT * FROM orders WHERE id=$order_id AND user_id=$user_id";
$order_res = $mydb->query($order_sql);
if($order_res->num_rows == 0) die("Order not found");
$order = $order_res->fetch_object();

// Fetch products
$products_sql = "SELECT oi.quantity, pr.product_name, oi.price
                 FROM order_items oi
                 JOIN products pr ON pr.id = oi.product_id
                 WHERE oi.order_id = $order_id";
$products_res = $mydb->query($products_sql);

// Include FPDF
require_once('lib/fpdf186/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();

// Header
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Decora - Invoice',0,1,'C');
$pdf->Ln(5);

// Order Info
$pdf->SetFont('Arial','',12);
$pdf->Cell(100,8,"Invoice #: $order_id",0,0);
$pdf->Cell(0,8,"Date: ".date("d M Y", strtotime($order->created_at)),0,1);
$pdf->Cell(0,8,"Customer: $order->full_name",0,1);
$pdf->Cell(0,8,"Phone: $order->phone",0,1);
$pdf->MultiCell(0,8,"Address: $order->address",0,1);
$pdf->Ln(5);

// Table header
$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,8,'Product',1);
$pdf->Cell(30,8,'Quantity',1,0,'C');
$pdf->Cell(40,8,'Price (৳)',1,1,'R');

// Table content
$pdf->SetFont('Arial','',12);
while($prod = $products_res->fetch_object()){
    $pdf->Cell(100,8,$prod->product_name,1);
    $pdf->Cell(30,8,$prod->quantity,1,0,'C');
    $pdf->Cell(40,8,number_format($prod->price,2),1,1,'R');
}

// Total
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130,8,'Total',1);
$pdf->Cell(40,8,'৳ '.number_format($order->total_amount,2),1,1,'R');

// Footer
$pdf->Ln(10);
$pdf->SetFont('Arial','I',10);
$pdf->Cell(0,10,'Thank you for shopping with Decora!',0,1,'C');

// Output PDF
$pdf->Output('D', "invoice_order_$order_id.pdf");
?>