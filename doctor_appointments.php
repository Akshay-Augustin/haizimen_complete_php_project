<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'doctor') {
    die('Access denied.');
}

/*
For now this assumes doctor username matches doctor email/user mapping later.
If you later connect doctors.user_id to users.id properly,
change the query to fetch doctor by user_id.
*/

$doctorStmt = $conn->prepare("SELECT * FROM doctors WHERE user_id = ? LIMIT 1");
$doctorStmt->bind_param("i", $user['id']);
$doctorStmt->execute();
$doctorResult = $doctorStmt->get_result();
$doctor = $doctorResult->fetch_assoc();

if (!$doctor) {
    die('Doctor profile not found.');
}

$stmt = $conn->prepare("
    SELECT a.*, u.first_name, u.last_name
    FROM appointments a
    INNER JOIN users u ON a.parent_user_id = u.id
    WHERE a.doctor_id = ?
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
");
$stmt->bind_param("i", $doctor['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Doctor Appointments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin:0; font-family:Arial,sans-serif; background:linear-gradient(to right,#081b2d,#0b1c2c); color:white; padding:40px 20px; }
        .wrap { max-width:1000px; margin:auto; }
        .card { background:#10263d; padding:25px; border-radius:14px; margin-bottom:20px; }
        .item { background:#0b1c2c; padding:16px; border-radius:10px; margin-bottom:14px; }
        .muted { color:#dbe6f2; }
        a { color:#ff5a3c; text-decoration:none; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Incoming Appointments</h1>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="item">
                <h3>Parent: <?php echo e($row['first_name'] . ' ' . $row['last_name']); ?></h3>
                <p><strong>Date:</strong> <span class="muted"><?php echo e($row['appointment_date']); ?></span></p>
                <p><strong>Time:</strong> <span class="muted"><?php echo e($row['appointment_time']); ?></span></p>
                <p><strong>Status:</strong> <span class="muted"><?php echo e($row['status']); ?></span></p>
                <p><strong>Notes:</strong> <span class="muted"><?php echo e($row['notes']); ?></span></p>
            </div>
        <?php endwhile; ?>
    </div>

    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>