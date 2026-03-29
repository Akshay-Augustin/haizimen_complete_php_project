<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'doctor') {
    die('Access denied.');
}

$doctorStmt = $conn->prepare("SELECT * FROM doctors WHERE user_id = ? LIMIT 1");
$doctorStmt->bind_param("i", $user['id']);
$doctorStmt->execute();
$doctor = $doctorStmt->get_result()->fetch_assoc();

if (!$doctor) {
    die('Doctor profile not found.');
}

/* Handle status update */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = (int)($_POST['booking_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    if ($booking_id > 0 && in_array($status, ['approved', 'rejected', 'completed'], true)) {
        $updateStmt = $conn->prepare("
            UPDATE vaccine_bookings
            SET status = ?
            WHERE id = ? AND doctor_id = ?
        ");
        $updateStmt->bind_param("sii", $status, $booking_id, $doctor['id']);
        $updateStmt->execute();

        header("Location: doctor_vaccine_bookings.php");
        exit;
    }
}

$stmt = $conn->prepare("
    SELECT vb.*, u.first_name, u.last_name, v.vaccine_name, v.age_group
    FROM vaccine_bookings vb
    INNER JOIN users u ON vb.parent_user_id = u.id
    INNER JOIN vaccines v ON vb.vaccine_id = v.id
    WHERE vb.doctor_id = ?
    ORDER BY vb.booking_date DESC
");
$stmt->bind_param("i", $doctor['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Doctor Vaccine Bookings</title>
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
            margin-top: 0;
            color: #1b6ec2;
        }

        .item {
            background: #f9fcff;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 14px;
            border: 1px solid #d9ebfb;
        }

        .item h3 {
            margin-top: 0;
            margin-bottom: 12px;
            color: #1b6ec2;
        }

        .muted {
            color: #4a5c6b;
        }

        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.pending { background: #fff3cd; color: #856404; }
        .status.approved { background: #d4edda; color: #155724; }
        .status.rejected { background: #f8d7da; color: #721c24; }
        .status.completed { background: #d1ecf1; color: #0c5460; }
        .status.cancelled { background: #ececec; color: #444; }

        .actions {
            margin-top: 12px;
        }

        .actions form {
            display: inline-block;
            margin-right: 8px;
            margin-top: 6px;
        }

        button {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            color: white;
        }

        .btn-approve { background: #28a745; }
        .btn-reject { background: #dc3545; }
        .btn-complete { background: #17a2b8; }

        .back-link {
            display: inline-block;
            margin-top: 6px;
            color: #1b6ec2;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .empty {
            color: #4a5c6b;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Doctor Vaccine Bookings</h1>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="item">
                    <h3><?php echo e($row['vaccine_name']); ?> (<?php echo e($row['age_group']); ?>)</h3>
                    <p><strong>Parent:</strong> <span class="muted"><?php echo e($row['first_name'] . ' ' . $row['last_name']); ?></span></p>
                    <p><strong>Booking Date:</strong> <span class="muted"><?php echo e($row['booking_date']); ?></span></p>
                    <p>
                        <strong>Status:</strong>
                        <span class="status <?php echo e($row['status']); ?>">
                            <?php echo e($row['status']); ?>
                        </span>
                    </p>
                    <?php if (!empty($row['notes'])): ?>
                        <p><strong>Notes:</strong> <span class="muted"><?php echo e($row['notes']); ?></span></p>
                    <?php endif; ?>

                    <div class="actions">
                        <?php if ($row['status'] === 'pending'): ?>
                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo (int)$row['id']; ?>">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn-approve">Approve</button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo (int)$row['id']; ?>">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn-reject">Reject</button>
                            </form>
                        <?php endif; ?>

                        <?php if ($row['status'] === 'approved'): ?>
                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo (int)$row['id']; ?>">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn-complete">Mark Completed</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="empty">No vaccine bookings found.</p>
        <?php endif; ?>
    </div>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>