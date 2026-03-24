<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'parent') {
    die('Access denied.');
}

$stmt = $conn->prepare("
    SELECT a.*, d.doctor_name, d.department
    FROM appointments a
    INNER JOIN doctors d ON a.doctor_id = d.id
    WHERE a.parent_user_id = ?
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>My Appointments</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f9ff;
    color: #1b2b3a;
    padding: 40px 20px;
}

.wrap {
    max-width: 1000px;
    margin: auto;
}

.card {
    background: #ffffff;
    padding: 25px;
    border-radius: 14px;
    margin-bottom: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

h1 {
    color: #1b6ec2;
    margin-top: 0;
}

.item {
    background: #f9fcff;
    padding: 18px;
    border-radius: 10px;
    margin-bottom: 14px;
    border: 1px solid #e1eefc;
}

.item h3 {
    margin: 0 0 10px;
    color: #1b6ec2;
}

.muted {
    color: #555;
}

.status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: bold;
}

.status.pending { background: #fff3cd; color: #856404; }
.status.approved { background: #d4edda; color: #155724; }
.status.rejected { background: #f8d7da; color: #721c24; }

.back-link {
    color: #1b6ec2;
    text-decoration: none;
    font-weight: bold;
}

.back-link:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="wrap">
    <div class="card">
        <h1>My Appointments</h1>

        <?php if ($result->num_rows === 0): ?>
            <p>No appointments found.</p>
        <?php endif; ?>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="item">
                <h3><?php echo e($row['doctor_name']); ?> - <?php echo e($row['department']); ?></h3>

                <p><strong>Date:</strong> <span class="muted"><?php echo e($row['appointment_date']); ?></span></p>
                <p><strong>Time:</strong> <span class="muted"><?php echo e($row['appointment_time']); ?></span></p>

                <p>
                    <strong>Status:</strong>
                    <span class="status <?php echo e($row['status']); ?>">
                        <?php echo e(ucfirst($row['status'])); ?>
                    </span>
                </p>

                <?php if (!empty($row['notes'])): ?>
                    <p><strong>Notes:</strong> <span class="muted"><?php echo e($row['notes']); ?></span></p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>