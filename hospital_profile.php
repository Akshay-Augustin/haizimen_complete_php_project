<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'hospital') {
    die('Access denied.');
}

$stmt = $conn->prepare("SELECT * FROM hospitals WHERE user_id = ? LIMIT 1");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$hospital = $stmt->get_result()->fetch_assoc();

if (!$hospital) {
    die('Hospital profile not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Hospital Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin:0; font-family:Arial,sans-serif; background:#f4f9ff; color:#1b2b3a; padding:40px 20px; }
        .wrap { max-width:900px; margin:auto; }
        .card { background:#fff; padding:25px; border-radius:14px; box-shadow:0 10px 25px rgba(0,0,0,0.08); }
        h1 { color:#1b6ec2; margin-top:0; }
        .back-link { color:#1b6ec2; text-decoration:none; font-weight:bold; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1><?php echo e($hospital['hospital_name']); ?></h1>
        <p><strong>Registration Number:</strong> <?php echo e($hospital['registration_number'] ?: '-'); ?></p>
        <p><strong>Type:</strong> <?php echo e($hospital['hospital_type'] ?: '-'); ?></p>
        <p><strong>Contact Person:</strong> <?php echo e($hospital['contact_person'] ?: '-'); ?></p>
        <p><strong>Email:</strong> <?php echo e($hospital['email'] ?: '-'); ?></p>
        <p><strong>Phone:</strong> <?php echo e($hospital['phone'] ?: '-'); ?></p>
        <p><strong>Address:</strong> <?php echo e($hospital['address'] ?: '-'); ?></p>
        <p><strong>City:</strong> <?php echo e($hospital['city'] ?: '-'); ?></p>
        <p><strong>State:</strong> <?php echo e($hospital['state'] ?: '-'); ?></p>
        <p><strong>Pincode:</strong> <?php echo e($hospital['pincode'] ?: '-'); ?></p>
        <p><strong>Timings:</strong> <?php echo e(($hospital['opening_time'] ?: '-') . ' - ' . ($hospital['closing_time'] ?: '-')); ?></p>
        <p><strong>Description:</strong> <?php echo e($hospital['description'] ?: '-'); ?></p>
    </div>

    <p style="margin-top:15px;"><a class="back-link" href="dashboard.php">← Back to Dashboard</a></p>
</div>
</body>
</html>