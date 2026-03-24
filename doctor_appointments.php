<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'doctor') {
    die('Access denied.');
}

/* ✅ ADD HERE (before fetching doctor + appointments) */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = (int)($_POST['appointment_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($appointment_id > 0 && in_array($status, ['approved', 'rejected', 'completed'])) {
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $appointment_id);
        $stmt->execute();
    }
}

/* then continue your existing code */
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

/* Status badges */
.status {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: bold;
}

.status.pending { background: #fff3cd; color: #856404; }
.status.approved { background: #d4edda; color: #155724; }
.status.rejected { background: #f8d7da; color: #721c24; }
.status.completed { background: #d1ecf1; color: #0c5460; }

.actions {
    margin-top: 10px;
}

button {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-right: 6px;
    font-size: 13px;
}

.btn-approve { background: #28a745; color: white; }
.btn-reject { background: #dc3545; color: white; }
.btn-complete { background: #17a2b8; color: white; }

button:hover {
    opacity: 0.9;
}

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
        
        <h1>Incoming Appointments</h1>

        <?php if ($result->num_rows === 0): ?>
            <p>No appointments found.</p>
        <?php endif; ?>

        <?php while ($row = $result->fetch_assoc()): ?>
            
            <div class="item">
                <h3>Parent: <?php echo e($row['first_name'] . ' ' . $row['last_name']); ?></h3>

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

                <!-- Action Buttons -->
                <div class="actions">
                    <?php if ($row['status'] === 'pending'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="appointment_id" value="<?php echo (int)$row['id']; ?>">
                            <input type="hidden" name="status" value="approved">
                            <button class="btn-approve">Approve</button>
                        </form>

                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="appointment_id" value="<?php echo (int)$row['id']; ?>">
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn-reject">Reject</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($row['status'] === 'approved'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="appointment_id" value="<?php echo (int)$row['id']; ?>">
                            <input type="hidden" name="status" value="completed">
                            <button class="btn-complete">Mark Completed</button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        <?php endwhile; ?>
    </div>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>