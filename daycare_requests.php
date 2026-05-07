<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'daycare') {
    die('Access denied.');
}

// get daycare profile
$stmt = $conn->prepare("SELECT * FROM daycares WHERE user_id = ? LIMIT 1");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$daycare = $stmt->get_result()->fetch_assoc();

if (!$daycare) {
    die('Daycare profile not found.');
}

// UPDATE STATUS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = (int)($_POST['request_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($request_id > 0 && in_array($status, ['approved', 'rejected', 'completed'])) {
        $update = $conn->prepare("UPDATE daycare_requests SET status = ? WHERE id = ?");
        $update->bind_param("si", $status, $request_id);
        $update->execute();
    }
}

// FETCH REQUESTS
$stmt = $conn->prepare("
    SELECT dr.*, u.first_name, u.last_name
    FROM daycare_requests dr
    INNER JOIN users u ON dr.parent_user_id = u.id
    WHERE dr.daycare_id = ?
    ORDER BY dr.request_date DESC
");
$stmt->bind_param("i", $daycare['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daycare Requests</title>
    <style>
        body { background:#f4f9ff; font-family:Arial; padding:40px; }
        .card { background:#fff; padding:20px; border-radius:12px; margin-bottom:15px; }
        h2 { color:#1b6ec2; }
        .btn { padding:6px 12px; margin-right:6px; border:none; border-radius:6px; cursor:pointer; }
        .approve { background:#28a745; color:#fff; }
        .reject { background:#dc3545; color:#fff; }
        .complete { background:#1b6ec2; color:#fff; }
    </style>
</head>
<body>

<h2>Daycare Requests</h2>

<?php while ($row = $result->fetch_assoc()): ?>
<div class="card">
    <h3><?php echo e($row['first_name'].' '.$row['last_name']); ?></h3>
    <p><strong>Date:</strong> <?php echo e($row['request_date']); ?></p>
    <p><strong>Child Age:</strong> <?php echo e($row['child_age']); ?></p>
    <p><strong>Notes:</strong> <?php echo e($row['notes']); ?></p>
    <p><strong>Status:</strong> <?php echo e($row['status']); ?></p>

    <form method="POST">
        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
        <button name="status" value="approved" class="btn approve">Approve</button>
        <button name="status" value="rejected" class="btn reject">Reject</button>
        <button name="status" value="completed" class="btn complete">Complete</button>
    </form>
</div>
<?php endwhile; ?>

<a href="dashboard.php">← Back</a>

</body>
</html>