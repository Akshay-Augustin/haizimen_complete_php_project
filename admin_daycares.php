<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

if (($_SESSION['auth']['role'] ?? '') !== 'admin') {
    die('Access denied.');
}

$result = $conn->query("
    SELECT id, center_name, manager_name, capacity, opening_time, closing_time, age_group_supported, facilities, description, email, phone, address
    FROM daycares
    ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Daycares</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin:0; font-family:Arial; background:#f4f9ff; color:#1b2b3a; padding:30px; }
        .wrap { max-width:1250px; margin:auto; }
        .card { background:#fff; padding:25px; border-radius:14px; box-shadow:0 10px 25px rgba(0,0,0,0.08); }
        table { width:100%; border-collapse:collapse; background:#fff; }
        th, td { padding:14px; border-bottom:1px solid #e6eef7; text-align:left; vertical-align:top; }
        th { background:#eaf4ff; color:#1b6ec2; }
        a { color:#1b6ec2; text-decoration:none; font-weight:bold; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Registered Daycares</h1>
        <table>
            <tr>
                <!-- <th>ID</th> -->
                <th>Center</th>
                <th>Manager</th>
                <th>Capacity</th>
                <th>Opening</th>
                <th>Closing</th>
                <th>Age Group</th>
                <th>Facilities</th>
                <th>Description</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <!-- <td><?php echo (int)$row['id']; ?></td> -->
                <td><?php echo e($row['center_name']); ?></td>
                <td><?php echo e($row['manager_name']); ?></td>
                <td><?php echo e($row['capacity']); ?></td>
                <td><?php echo e($row['opening_time']); ?></td>
                <td><?php echo e($row['closing_time']); ?></td>
                <td><?php echo e($row['age_group_supported']); ?></td>
                <td><?php echo e($row['facilities']); ?></td>
                <td><?php echo e($row['description']); ?></td>
                <td><?php echo e($row['email']); ?></td>
                <td><?php echo e($row['phone']); ?></td>
                <td><?php echo e($row['address']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <p><a href="admin_dashboard.php">← Back to Admin Dashboard</a></p>
    </div>
</div>
</body>
</html>