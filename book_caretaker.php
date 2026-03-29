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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_date = trim($_POST['request_date'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($request_date === '') {
        $errors[] = 'Request date is required.';
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