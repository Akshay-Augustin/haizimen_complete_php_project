<?php
include("connect.php");

$username = "admin";
$password = "admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO users (
        role, first_name, last_name, gender, dob, email, phone, username, password_hash
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$role = "admin";
$first = "System";
$last = "Admin";
$gender = "others";
$dob = "2000-01-01";
$email = "admin@haizimen.com";
$phone = "9999999999";

$stmt->bind_param(
    "sssssssss",
    $role,
    $first,
    $last,
    $gender,
    $dob,
    $email,
    $phone,
    $username,
    $hash
);

if ($stmt->execute()) {
    echo "Admin created successfully.<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "Hash: " . $hash;
} else {
    echo "Error: " . $stmt->error;
}