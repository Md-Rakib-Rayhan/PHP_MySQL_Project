<?php
session_start();
if (!isset($_SESSION["isValidAdmin"])) {
    header("Location: login.php");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: request_list.php");
    exit;
}

$id = intval($_GET['id']);
$mydb = new mysqli("localhost", "root", "", "decora");

// Delete the request
$delete = $mydb->query("DELETE FROM service_requests WHERE id = $id");

if ($delete) {
    header("Location: request_list.php?msg=deleted");
} else {
    header("Location: request_list.php?msg=error");
}
?>