<?php
require_once __DIR__ . '/../app/Helpers/helpers.php';

$config = require __DIR__ . '/../config/database.php';

$conn = mysqli_connect(
    $config['host'],
    $config['username'],
    $config['password'],
    $config['database'],
    (int)$config['port']
);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, $config['charset']);
