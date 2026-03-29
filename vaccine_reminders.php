<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'parent') {
    die('Access denied.');
}

$stmt = $conn->prepare("
    SELECT vb.*, v.vaccine_name, v.age_group, d.doctor_name
    FROM vaccine_bookings vb
    INNER JOIN vaccines v ON vb.vaccine_id = v.id
    LEFT JOIN doctors d ON vb.doctor_id = d.id
    WHERE vb.parent_user_id = ?
      AND vb.status IN ('pending', 'approved')
      AND vb.reminder_date <= CURDATE() + INTERVAL 2 DAY
    ORDER BY vb.reminder_date ASC
");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Vaccine Reminders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin:0; font-family:Arial,sans-serif; background:#f4f9ff; color:#1b2b3a; padding:40px 20px; }
        .wrap { max-width:1000px; margin:auto; }
        .card { background:#fff; padding:25px; border-radius:14px; box-shadow:0 10px 25px rgba(0,0,0,0.08); }
        .item { background:#f9fcff; border:1px solid #d9ebfb; padding:16px; border-radius:10px; margin-bottom:14px; }
        h1 { color:#1b6ec2; margin-top:0; }
        .back-link { color:#1b6ec2; font-weight:bold; text-decoration:none; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Upcoming Vaccine Reminders</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="item">
                    <h3><?php echo e($row['vaccine_name']); ?></h3>
                    <p><strong>Age Group:</strong> <?php echo e($row['age_group']); ?></p>
                    <p><strong>Booking Date:</strong> <?php echo e($row['booking_date']); ?></p>
                    <p><strong>Reminder Date:</strong> <?php echo e($row['reminder_date']); ?></p>
                    <p><strong>Doctor:</strong> <?php echo e($row['doctor_name'] ?? 'Not assigned'); ?></p>
                    <p><strong>Status:</strong> <?php echo e(ucfirst($row['status'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No upcoming reminders.</p>
        <?php endif; ?>
    </div>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>