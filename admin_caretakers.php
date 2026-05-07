<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

if (($_SESSION['auth']['role'] ?? '') !== 'admin') {
    die('Access denied.');
}

$result = $conn->query("
    SELECT id, caretaker_name, experience_years, skills, availability, fee, preferred_location, email, phone
    FROM caretakers
    ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Caretakers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin:0; font-family:Arial; background:#f4f9ff; color:#1b2b3a; padding:30px; }
        .wrap { max-width:1200px; margin:auto; }
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
        <h1>Registered Caretakers</h1>
        <table>
            <tr>
                <!-- <th>ID</th> -->
                <th>Name</th>
                <th>Experience</th>
                <th>Skills</th>
                <th>Availability</th>
                <th>Fee</th>
                <th>Location</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <!-- <td><?php echo (int)$row['id']; ?></td> -->
                <td><?php echo e($row['caretaker_name']); ?></td>
                <td><?php echo e($row['experience_years']); ?></td>
                <td><?php echo e($row['skills']); ?></td>
                <td><?php echo e($row['availability']); ?></td>
                <td><?php echo e($row['fee']); ?></td>
                <td><?php echo e($row['preferred_location']); ?></td>
                <td><?php echo e($row['email']); ?></td>
                <td><?php echo e($row['phone']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <p><a href="admin_dashboard.php">← Back to Admin Dashboard</a></p>
    </div>
</div>
</body>
</html>