<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];
$errors = [];
$success = null;

if ($user['role'] !== 'parent') {
    die('Access denied.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = (int)($_POST['doctor_id'] ?? 0);
    $appointment_date = trim($_POST['appointment_date'] ?? '');
    $appointment_time = trim($_POST['appointment_time'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($doctor_id <= 0) $errors[] = 'Please select a doctor.';
    if ($appointment_date === '') $errors[] = 'Appointment date is required.';
    if ($appointment_time === '') $errors[] = 'Appointment time is required.';

    if (!$errors) {
        $stmt = $conn->prepare("INSERT INTO appointments (parent_user_id, doctor_id, appointment_date, appointment_time, notes, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("iisss", $user['id'], $doctor_id, $appointment_date, $appointment_time, $notes);
        $stmt->execute();
        $success = 'Appointment booked successfully.';
    }
}

$doctors = $conn->query("SELECT * FROM doctors ORDER BY doctor_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Book Appointment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin:0; font-family:Arial,sans-serif; background:linear-gradient(to right,#081b2d,#0b1c2c); color:white; padding:40px 20px; }
        .wrap { max-width:900px; margin:auto; }
        .card { background:#10263d; padding:25px; border-radius:14px; margin-bottom:20px; }
        select, input, textarea { width:100%; padding:10px; margin-top:8px; margin-bottom:14px; border-radius:6px; border:1px solid #2c4966; background:#0b1c2c; color:white; box-sizing:border-box; }
        button { padding:10px 18px; background:#ff5a3c; color:white; border:none; border-radius:6px; font-weight:bold; cursor:pointer; }
        .alert { padding:10px; border-radius:8px; margin-bottom:15px; }
        .error { background:rgba(255,90,60,.12); border:1px solid rgba(255,90,60,.35); color:#ffd2ca; }
        .success { background:rgba(80,200,120,.12); border:1px solid rgba(80,200,120,.35); color:#d8ffe5; }
        a { color:#ff5a3c; text-decoration:none; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Book Doctor Appointment</h1>

        <?php if ($success): ?>
            <div class="alert success"><?php echo e($success); ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>Select Doctor</label>
            <select name="doctor_id" required>
                <option value="">Choose doctor</option>
                <?php while ($doctor = $doctors->fetch_assoc()): ?>
                    <option value="<?php echo (int)$doctor['id']; ?>">
                        <?php echo e($doctor['doctor_name']); ?> - <?php echo e($doctor['department']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Appointment Date</label>
            <input type="date" name="appointment_date" required>

            <label>Appointment Time</label>
            <input type="time" name="appointment_time" required>

            <label>Notes</label>
            <textarea name="notes" rows="4" placeholder="Reason for consultation"></textarea>

            <button type="submit">Book Appointment</button>
        </form>
    </div>

    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>