<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'parent') {
    die('Access denied.');
}

$caretaker_id = (int)($_GET['caretaker_id'] ?? $_POST['caretaker_id'] ?? 0);
$errors = [];
$success = null;

$stmt = $conn->prepare("SELECT * FROM caretakers WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $caretaker_id);
$stmt->execute();
$caretaker = $stmt->get_result()->fetch_assoc();

if (!$caretaker) {
    die('Caretaker not found.');
}

/*
Assumption:
caretaker_availability.caretaker_user_id stores users.id
and caretakers.user_id links to that user id
*/
$caretakerUserId = (int)($caretaker['user_id'] ?? 0);

$availabilityLines = [];
if ($caretakerUserId > 0) {
    $stmt = $conn->prepare("
        SELECT day_name, is_available, start_time, end_time
        FROM caretaker_availability
        WHERE caretaker_user_id = ?
        ORDER BY FIELD(day_name, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
    ");
    $stmt->bind_param("i", $caretakerUserId);
    $stmt->execute();
    $availabilityResult = $stmt->get_result();

    while ($row = $availabilityResult->fetch_assoc()) {
        if ((int)$row['is_available'] === 1) {
            $availabilityLines[] = $row['day_name'] . ': ' .
                date('g:i A', strtotime($row['start_time'])) . ' to ' .
                date('g:i A', strtotime($row['end_time']));
        } else {
            $availabilityLines[] = $row['day_name'] . ': Not Available';
        }
    }
}

$availabilityText = !empty($availabilityLines)
    ? implode("\n", $availabilityLines)
    : 'Availability not updated yet';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_date = trim($_POST['request_date'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($request_date === '') {
        $errors[] = 'Request date is required.';
    }

    if (!$errors && $caretakerUserId > 0) {
        $day_name = date('l', strtotime($request_date));

        $stmt = $conn->prepare("
            SELECT is_available, start_time, end_time
            FROM caretaker_availability
            WHERE caretaker_user_id = ? AND day_name = ?
            LIMIT 1
        ");
        $stmt->bind_param("is", $caretakerUserId, $day_name);
        $stmt->execute();
        $availabilityRow = $stmt->get_result()->fetch_assoc();

        if (!$availabilityRow || (int)$availabilityRow['is_available'] !== 1) {
            $errors[] = "Caretaker is not available on $day_name.";
        }
    }

    if (!$errors) {
        $insert = $conn->prepare("
            INSERT INTO caretaker_requests (parent_user_id, caretaker_id, request_date, notes, status)
            VALUES (?, ?, ?, ?, 'pending')
        ");
        $insert->bind_param("iiss", $user['id'], $caretaker_id, $request_date, $notes);
        $insert->execute();
        $success = 'Caretaker request sent successfully.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Book Caretaker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin:0; font-family:Arial,sans-serif; background:#f4f9ff; color:#1b2b3a; padding:40px 20px; }
        .wrap { max-width:900px; margin:auto; }
        .card { background:#fff; padding:25px; border-radius:14px; margin-bottom:20px; box-shadow:0 10px 25px rgba(0,0,0,0.08); }
        h1 { color:#1b6ec2; margin-top:0; }
        label { display:block; margin:10px 0 6px; font-weight:bold; }
        input, textarea { width:100%; padding:10px; border-radius:6px; border:1px solid #cfe3f7; background:#f9fcff; box-sizing:border-box; }
        textarea { resize:vertical; }
        button { padding:10px 18px; background:#1b6ec2; color:#fff; border:none; border-radius:6px; font-weight:bold; cursor:pointer; }
        .alert { padding:10px; border-radius:8px; margin-bottom:15px; }
        .error { background:#ffecec; border:1px solid #f5c6cb; color:#a94442; }
        .success { background:#e8fff1; border:1px solid #aad7b7; color:#1e6c35; }
        .back-link { color:#1b6ec2; text-decoration:none; font-weight:bold; }
        .availability-note {
            margin-top: 12px;
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 8px;
            background: #fff9e8;
            border: 1px solid #f0d98a;
            color: #6a5800;
            line-height: 1.7;
            white-space: pre-line;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Request Caretaker</h1>
        <p><strong>Name:</strong> <?php echo e($caretaker['caretaker_name']); ?></p>
        <p><strong>Experience:</strong> <?php echo e($caretaker['experience_years'] ?: '-'); ?></p>
        <p><strong>Fee:</strong> <?php echo e($caretaker['fee'] ?: '-'); ?></p>
        <p><strong>Availability:</strong> <?php echo e($caretaker['availability'] ?: '-'); ?></p>

        <div class="availability-note">
            <strong>Weekly Availability:</strong><br>
            <?php echo nl2br(e($availabilityText)); ?>
        </div>

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
            <input type="hidden" name="caretaker_id" value="<?php echo (int)$caretaker_id; ?>">

            <label>Request Date</label>
            <input type="date" name="request_date" required>

            <label>Notes</label>
            <textarea name="notes" rows="4" placeholder="Optional notes"></textarea>

            <button type="submit">Send Request</button>
        </form>
    </div>

    <a class="back-link" href="caretakers.php">← Back to Caretakers</a>
</div>
</body>
</html>