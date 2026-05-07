<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'caretaker') {
    die('Access denied.');
}

// get caretaker profile
$stmt = $conn->prepare("SELECT * FROM caretakers WHERE user_id = ? LIMIT 1");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$caretaker = $stmt->get_result()->fetch_assoc();

if (!$caretaker) {
    die('Caretaker profile not found.');
}

// HANDLE STATUS UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = (int)($_POST['request_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($request_id > 0 && in_array($status, ['approved', 'rejected', 'completed'], true)) {
        $update = $conn->prepare("
            UPDATE caretaker_requests
            SET status = ?
            WHERE id = ? AND caretaker_id = ?
        ");
        $update->bind_param("sii", $status, $request_id, $caretaker['id']);
        $update->execute();

        header("Location: caretaker_requests.php");
        exit;
    }
}

// FETCH REQUESTS
$stmt = $conn->prepare("
    SELECT cr.*, u.first_name, u.last_name
    FROM caretaker_requests cr
    INNER JOIN users u ON cr.parent_user_id = u.id
    WHERE cr.caretaker_id = ?
    ORDER BY cr.request_date DESC
");
$stmt->bind_param("i", $caretaker['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Caretaker Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            background: #f4f9ff;
            font-family: Arial, sans-serif;
            padding: 40px 20px;
            color: #1b2b3a;
        }

        .wrap {
            max-width: 900px;
            margin: auto;
        }

        h2 {
            color: #1b6ec2;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            border: 1px solid #d9ebfb;
        }

        .empty-card {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-card h3 {
            color: #1b6ec2;
            margin-bottom: 8px;
        }

        .empty-card p {
            color: #4a5c6b;
            margin: 0;
        }

        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.approved {
            background: #d4edda;
            color: #155724;
        }

        .status.rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .status.completed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .actions {
            margin-top: 12px;
        }

        .actions form {
            display: inline-block;
            margin-right: 6px;
            margin-top: 6px;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            color: #fff;
            font-weight: bold;
        }

        .approve {
            background: #28a745;
        }

        .reject {
            background: #dc3545;
        }

        .complete {
            background: #1b6ec2;
        }

        .back-link {
            display: inline-block;
            margin-top: 10px;
            color: #1b6ec2;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        p {
            margin: 8px 0;
        }
    </style>
</head>
<body>
<div class="wrap">
    <h2>Caretaker Requests</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <h3><?php echo e($row['first_name'] . ' ' . $row['last_name']); ?></h3>
                <p><strong>Date:</strong> <?php echo e($row['request_date']); ?></p>
                <p><strong>Notes:</strong> <?php echo e($row['notes'] ?: '-'); ?></p>
                <p>
                    <strong>Status:</strong>
                    <span class="status <?php echo e($row['status']); ?>">
                        <?php echo e($row['status']); ?>
                    </span>
                </p>

                <div class="actions">
                    <?php if ($row['status'] === 'pending'): ?>
                        <form method="POST">
                            <input type="hidden" name="request_id" value="<?php echo (int)$row['id']; ?>">
                            <button type="submit" name="status" value="approved" class="btn approve">Approve</button>
                        </form>

                        <form method="POST">
                            <input type="hidden" name="request_id" value="<?php echo (int)$row['id']; ?>">
                            <button type="submit" name="status" value="rejected" class="btn reject">Reject</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($row['status'] === 'approved'): ?>
                        <form method="POST">
                            <input type="hidden" name="request_id" value="<?php echo (int)$row['id']; ?>">
                            <button type="submit" name="status" value="completed" class="btn complete">Complete</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="card empty-card">
            <h3>No caretaker requests found</h3>
            <p>You do not have any requests yet.</p>
        </div>
    <?php endif; ?>

    <a href="dashboard.php" class="back-link">← Back to dashboard</a>
</div>
</body>
</html>