<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'hospital') {
    die('Access denied.');
}

$hospitalStmt = $conn->prepare("SELECT * FROM hospitals WHERE user_id = ? LIMIT 1");
$hospitalStmt->bind_param("i", $user['id']);
$hospitalStmt->execute();
$hospital = $hospitalStmt->get_result()->fetch_assoc();

if (!$hospital) {
    die('Hospital profile not found.');
}

$stmt = $conn->prepare("
    SELECT * FROM doctors
    WHERE hospital_id = ?
    ORDER BY doctor_name ASC
");
$stmt->bind_param("i", $hospital['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Hospital Doctors</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin:0; font-family:Arial,sans-serif; background:#f4f9ff; color:#1b2b3a; padding:40px 20px; }
        .wrap { max-width:900px; margin:auto; }
        .card { background:#fff; padding:25px; border-radius:14px; box-shadow:0 10px 25px rgba(0,0,0,0.08); }
        h1 { color:#1b6ec2; margin-top:0; }
        .item { background:#f9fcff; padding:16px; border-radius:10px; margin-bottom:12px; border:1px solid #d9ebfb; }
        .back-link { color:#1b6ec2; text-decoration:none; font-weight:bold; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Doctors in <?php echo e($hospital['hospital_name']); ?></h1>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="item">
                    <p><strong>Name:</strong> <?php echo e($row['doctor_name']); ?></p>
                    <p><strong>Department:</strong> <?php echo e($row['department']); ?></p>
                    <p><strong>Qualification:</strong> <?php echo e($row['qualification']); ?></p>
                    <p><strong>Phone:</strong> <?php echo e($row['phone']); ?></p>
                    <p><strong>Email:</strong> <?php echo e($row['email']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No doctors linked to this hospital.</p>
        <?php endif; ?>
    </div>

    <p style="margin-top:15px;"><a class="back-link" href="dashboard.php">← Back to Dashboard</a></p>
</div>
</body>
</html>