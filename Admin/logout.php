<?php
session_start();
// session_destroy();
unset($_SESSION["isValidAdmin"]);
header("Location: login.php");
?>