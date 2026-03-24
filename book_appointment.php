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
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f9ff;
            color: #1b2b3a;
            padding: 40px 20px;
        }

        .wrap {
            max-width: 900px;
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

        label {
            display: block;
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 6px;
            color: #1b2b3a;
        }

        select,
        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 4px;
            margin-bottom: 14px;
            border-radius: 6px;
            border: 1px solid #cfe3f7;
            background: #f9fcff;
            color: #1b2b3a;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        textarea {
            resize: vertical;
        }

        button {
            padding: 10px 18px;
            background: #1b6ec2;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #155a9c;
        }

        .alert {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .error {
            background: #ffecec;
            border: 1px solid #f5c6cb;
            color: #a94442;
        }

        .success {
            background: #e8fff1;
            border: 1px solid #aad7b7;
            color: #1e6c35;
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

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>