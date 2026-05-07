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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = (int)($_POST['booking_id'] ?? 0);
    $hospital_status = trim($_POST['hospital_status'] ?? '');

    if ($booking_id > 0 && in_array($hospital_status, ['approved', 'rejected', 'completed'], true)) {
        $overall_status = $hospital_status === 'approved' ? 'approved' : ($hospital_status === 'rejected' ? 'rejected' : 'completed');

        $updateStmt = $conn->prepare("
            UPDATE vaccine_bookings
            SET hospital_status = ?, status = ?
            WHERE id = ? AND hospital_id = ?
        ");
        $updateStmt->bind_param("ssii", $hospital_status, $overall_status, $booking_id, $hospital['id']);
        $updateStmt->execute();

        header("Location: hospital_vaccine_requests.php");
        exit;
    }
}

$stmt = $conn->prepare("
    SELECT 
        vb.*, 
        u.first_name, 
        u.last_name, 
        v.vaccine_name, 
        v.age_group,
        d.doctor_name
    FROM vaccine_bookings vb
    INNER JOIN users u ON vb.parent_user_id = u.id
    INNER JOIN vaccines v ON vb.vaccine_id = v.id
    INNER JOIN doctors d ON vb.doctor_id = d.id
    WHERE vb.hospital_id = ?
    ORDER BY vb.booking_date DESC
");
$stmt->bind_param("i", $hospital['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Hospital Vaccine Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin:0; font-family:Arial,sans-serif; background:#f4f9ff; color:#1b2b3a; padding:40px 20px; }
        .wrap { max-width:1000px; margin:auto; }
        .card { background:#fff; padding:25px; border-radius:14px; margin-bottom:20px; box-shadow:0 10px 25px rgba(0,0,0,0.08); }
        h1 { margin-top:0; color:#1b6ec2; }
        .item { background:#f9fcff; padding:18px; border-radius:10px; margin-bottom:14px; border:1px solid #d9ebfb; }
        .item h3 { margin-top:0; margin-bottom:12px; color:#1b6ec2; }
        .muted { color:#4a5c6b; }
        .status {
            display:inline-block; padding:4px 10px; border-radius:6px;
            font-size:13px; font-weight:bold; text-transform:capitalize;
        }
        .status.pending { background:#fff3cd; color:#856404; }
        .status.approved { background:#d4edda; color:#155724; }
        .status.rejected { background:#f8d7da; color:#721c24; }
        .status.completed { background:#d1ecf1; color:#0c5460; }
        .status.not_sent { background:#ececec; color:#444; }
        .actions form { display:inline-block; margin-right:8px; margin-top:8px; }
        button {
            padding:8px 12px; border:none; border-radius:6px;
            font-weight:bold; cursor:pointer; color:#fff;
        }
        .btn-approve { background:#28a745; }
        .btn-reject { background:#dc3545; }
        .btn-complete { background:#17a2b8; }
        .back-link {
            display:inline-block; margin-top:6px; color:#1b6ec2;
            text-decoration:none; font-weight:bold;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Hospital Vaccine Requests</h1>
        <p><strong>Hospital:</strong> <?php echo e($hospital['hospital_name']); ?></p>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="item">
                    <h3><?php echo e($row['vaccine_name']); ?> (<?php echo e($row['age_group']); ?>)</h3>
                    <p><strong>Parent:</strong> <span class="muted"><?php echo e($row['first_name'] . ' ' . $row['last_name']); ?></span></p>
                    <p><strong>Doctor:</strong> <span class="muted"><?php echo e($row['doctor_name']); ?></span></p>
                    <p><strong>Booking Date:</strong> <span class="muted"><?php echo e($row['booking_date']); ?></span></p>

                    <p><strong>Overall Status:</strong>
                        <span class="status <?php echo e($row['status']); ?>"><?php echo e($row['status']); ?></span>
                    </p>
                    <p><strong>Hospital Status:</strong>
                        <span class="status <?php echo e($row['hospital_status']); ?>"><?php echo e($row['hospital_status']); ?></span>
                    </p>

                    <?php if (!empty($row['notes'])): ?>
                        <p><strong>Notes:</strong> <span class="muted"><?php echo e($row['notes']); ?></span></p>
                    <?php endif; ?>

                    <div class="actions">
                        <?php if ($row['hospital_status'] === 'pending'): ?>
                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo (int)$row['id']; ?>">
                                <input type="hidden" name="hospital_status" value="approved">
                                <button type="submit" class="btn-approve">Approve</button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo (int)$row['id']; ?>">
                                <input type="hidden" name="hospital_status" value="rejected">
                                <button type="submit" class="btn-reject">Reject</button>
                            </form>
                        <?php endif; ?>

                        <?php if ($row['hospital_status'] === 'approved'): ?>
                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo (int)$row['id']; ?>">
                                <input type="hidden" name="hospital_status" value="completed">
                                <button type="submit" class="btn-complete">Mark Completed</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hospital vaccine requests found.</p>
        <?php endif; ?>
    </div>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>