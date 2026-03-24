<?php
include("includes/connect.php");
require_once "app/Controllers/AuthController.php";
$controller = new AuthController($conn);
$controller->logout();
header('Location: login_cgt.php');
exit;
