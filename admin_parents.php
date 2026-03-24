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
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f9ff;
    color: #1b2b3a;
    padding: 30px;
}

.wrap {
    max-width: 1100px;
    margin: auto;
}

.card {
    background: #ffffff;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

h1 {
    margin-top: 0;
    color: #1b6ec2;
}

/* TABLE */
.table-wrap {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 14px;
    text-align: left;
    border-bottom: 1px solid #e6eef7;
    font-size: 14px;
}

th {
    background: #eaf4ff;
    color: #1b6ec2;
    font-weight: bold;
}

/* ROW HOVER */
tr:hover {
    background: #f4f9ff;
}

/* LINKS */
a {
    color: #1b6ec2;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

/* BACK BUTTON */
.back {
    margin-top: 15px;
    display: inline-block;
}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Registered Parents</h1>
     <div class="table-wrap"></div>
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
    </div>
        <p><a class="back" href="admin_dashboard.php">← Back to Dashboard</a></p>
    </div>
</div>
</body>
</html>