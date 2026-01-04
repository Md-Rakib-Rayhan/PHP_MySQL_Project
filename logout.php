<?php
session_start();
// session_destroy();
unset($_SESSION["isvalid"]);
// unset($_SESSION["id"]);
// unset($_SESSION["name"]);
// unset($_SESSION["email"]);
// unset($_SESSION["pic"]);
header("Location: index.php");
?>