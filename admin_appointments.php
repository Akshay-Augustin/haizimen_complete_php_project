<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

if (($_SESSION['auth']['role'] ?? '') !== 'admin') {
    die('Access denied.');
}

$result = $conn->query("
    SELECT 
        a.id,
        a.appointment_date,
        a.appointment_time,
        a.status,
        a.notes,
        u.first_name,
        u.last_name,
        d.doctor_name,
        d.department
    FROM appointments a
    INNER JOIN users u ON a.parent_user_id = u.id
    INNER JOIN doctors d ON a.doctor_id = d.id
    ORDER BY a.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Appointments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{margin:0;font-family:Arial;background:linear-gradient(to right,#081b2d,#0b1c2c);color:white;padding:30px}
        .wrap{max-width:1200px;margin:auto}
        .card{background:#10263d;padding:25px;border-radius:14px}
        table{width:100%;border-collapse:collapse;background:#0b1c2c;border-radius:10px;overflow:hidden}
        th,td{padding:14px;border-bottom:1px solid rgba(255,255,255,0.08);text-align:left;vertical-align:top}
        th{background:#0d2236}
        a{color:#ff5a3c;text-decoration:none}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>All Appointments</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Parent</th>
                <th>Doctor</th>
                <th>Department</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo (int)$row['id']; ?></td>
                <td><?php echo e($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td><?php echo e($row['doctor_name']); ?></td>
                <td><?php echo e($row['department']); ?></td>
                <td><?php echo e($row['appointment_date']); ?></td>
                <td><?php echo e($row['appointment_time']); ?></td>
                <td><?php echo e($row['status']); ?></td>
                <td><?php echo e($row['notes']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <p><a href="admin_dashboard.php">Back to Admin Dashboard</a></p>
    </div>
</div>
</body>
</html>