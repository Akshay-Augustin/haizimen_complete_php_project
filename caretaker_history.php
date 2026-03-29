<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'parent') {
    die('Access denied.');
}

$stmt = $conn->prepare("
    SELECT cr.*, c.caretaker_name, c.skills
    FROM caretaker_requests cr
    INNER JOIN caretakers c ON cr.caretaker_id = c.id
    WHERE cr.parent_user_id = ?
    ORDER BY cr.request_date DESC
");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Caretaker History</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #f4f9ff;
    padding: 30px;
}

.wrap {
    max-width: 900px;
    margin: auto;
}

.card {
    background: #fff;
    padding: 25px;
    border-radius: 14px;
    margin-bottom: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.item {
    background: #f9fcff;
    padding: 16px;
    border-radius: 10px;
    border: 1px solid #d9ebfb;
    margin-bottom: 12px;
}

.status {
    font-weight: bold;
}

.status.pending { color: orange; }
.status.approved { color: green; }
.status.rejected { color: red; }
.status.completed { color: blue; }

a {
    color: #1b6ec2;
    text-decoration: none;
}
</style>
</head>
<body>

<div class="wrap">
    <div class="card">
        <h1>Caretaker History</h1>

        <?php while($row = $result->fetch_assoc()): ?>
            <div class="item">
                <h3><?php echo e($row['caretaker_name']); ?></h3>
                <p><strong>Skills:</strong> <?php echo e($row['skills']); ?></p>
                <p><strong>Date:</strong> <?php echo e($row['request_date']); ?></p>
                <p><strong>Status:</strong> 
                    <span class="status <?php echo e($row['status']); ?>">
                        <?php echo e($row['status']); ?>
                    </span>
                </p>
            </div>
        <?php endwhile; ?>
    </div>

    <a href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>