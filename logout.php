<?php
require_once "app/Helpers/helpers.php";
session_start();

// remove user session
unset($_SESSION['auth']);

// optional: destroy session completely
session_destroy();

// set flash message
flash_set('success', 'Logged out successfully.');

// redirect to login page
header("Location: login.php");
exit;