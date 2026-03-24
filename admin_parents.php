<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

if (($_SESSION['auth']['role'] ?? '') !== 'admin') {
    die('Access denied.');
}

$result = $conn->query("SELECT id, first_name, last_name, email, phone, username, created_at FROM users WHERE role = 'parent' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Parents</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{margin:0;font-family:Arial;background:linear-gradient(to right,#081b2d,#0b1c2c);color:white;padding:30px}
        .wrap{max-width:1100px;margin:auto}
        .card{background:#10263d;padding:25px;border-radius:14px}
        table{width:100%;border-collapse:collapse;background:#0b1c2c;border-radius:10px;overflow:hidden}
        th,td{padding:14px;border-bottom:1px solid rgba(255,255,255,0.08);text-align:left}
        th{background:#0d2236}
        a{color:#ff5a3c;text-decoration:none}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Registered Parents</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Username</th>
                <th>Created</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo (int)$row['id']; ?></td>
                <td><?php echo e($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td><?php echo e($row['email']); ?></td>
                <td><?php echo e($row['phone']); ?></td>
                <td><?php echo e($row['username']); ?></td>
                <td><?php echo e($row['created_at']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <p><a href="admin_dashboard.php">Back to Admin Dashboard</a></p>
    </div>
</div>
</body>
</html>