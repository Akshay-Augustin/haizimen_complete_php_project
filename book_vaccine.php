<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'parent') {
    die('Access denied.');
}

$vaccine_id = (int)($_GET['vaccine_id'] ?? $_POST['vaccine_id'] ?? 0);
$errors = [];
$success = null;

$vaccineStmt = $conn->prepare("SELECT * FROM vaccines WHERE id = ? LIMIT 1");
$vaccineStmt->bind_param("i", $vaccine_id);
$vaccineStmt->execute();
$vaccine = $vaccineStmt->get_result()->fetch_assoc();

if (!$vaccine) {
    die('Vaccine not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = (int)($_POST['doctor_id'] ?? 0);
    $booking_date = trim($_POST['booking_date'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($doctor_id <= 0) $errors[] = 'Please select a doctor.';
    if ($booking_date === '') $errors[] = 'Booking date is required.';

    if (!$errors) {
        $reminder_date = date('Y-m-d', strtotime($booking_date . ' -2 days'));

        $stmt = $conn->prepare("
            INSERT INTO vaccine_bookings (parent_user_id, vaccine_id, doctor_id, booking_date, notes, status, reminder_date)
            VALUES (?, ?, ?, ?, ?, 'pending', ?)
        ");
        $stmt->bind_param("iiisss", $user['id'], $vaccine_id, $doctor_id, $booking_date, $notes, $reminder_date);
        $stmt->execute();

        $success = 'Vaccine booking created successfully.';
    }
}

$doctors = $conn->query("SELECT * FROM doctors WHERE user_id IS NOT NULL ORDER BY doctor_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Book Vaccine</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f9ff;
            color: #1b2b3a;
            padding: 40px 20px;
        }
        .wrap { max-width: 900px; margin: auto; }
        .card {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        h1 { color: #1b6ec2; margin-top: 0; }
        label { display:block; margin:10px 0 6px; font-weight:bold; }
        select, input, textarea {
            width:100%;
            padding:10px;
            border-radius:6px;
            border:1px solid #cfe3f7;
            background:#f9fcff;
            box-sizing:border-box;
        }
        textarea { resize:vertical; }
        button {
            padding:10px 18px;
            background:#1b6ec2;
            color:#fff;
            border:none;
            border-radius:6px;
            font-weight:bold;
            cursor:pointer;
        }
        .alert { padding:10px; border-radius:8px; margin-bottom:15px; }
        .error { background:#ffecec; border:1px solid #f5c6cb; color:#a94442; }
        .success { background:#e8fff1; border:1px solid #aad7b7; color:#1e6c35; }
        .back-link { color:#1b6ec2; text-decoration:none; font-weight:bold; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Book Vaccine</h1>
        <p><strong>Vaccine:</strong> <?php echo e($vaccine['vaccine_name']); ?></p>
        <p><strong>Age Group:</strong> <?php echo e($vaccine['age_group']); ?></p>
        <p><strong>Protects Against:</strong> <?php echo e($vaccine['protects_against']); ?></p>

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
            <input type="hidden" name="vaccine_id" value="<?php echo (int)$vaccine_id; ?>">

            <label>Select Doctor</label>
            <select name="doctor_id" required>
                <option value="">Choose doctor</option>
                <?php while ($doctor = $doctors->fetch_assoc()): ?>
                    <option value="<?php echo (int)$doctor['id']; ?>">
                        <?php echo e($doctor['doctor_name']); ?> - <?php echo e($doctor['department']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Booking Date</label>
            <input type="date" name="booking_date" required>

            <label>Notes</label>
            <textarea name="notes" rows="4" placeholder="Optional notes"></textarea>

            <button type="submit">Book Vaccine</button>
        </form>
    </div>

    <a class="back-link" href="vaccines.php">← Back to Vaccines</a>
</div>
</body>
</html>