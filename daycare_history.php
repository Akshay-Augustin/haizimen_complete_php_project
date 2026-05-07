<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'parent') {
    die('Access denied.');
}

$stmt = $conn->prepare("
    SELECT dr.*, d.center_name
    FROM daycare_requests dr
    INNER JOIN daycares d ON dr.daycare_id = d.id
    WHERE dr.parent_user_id = ?
    ORDER BY dr.request_date DESC
");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title>Daycare History</title>
<style>
body { background:#f4f9ff; font-family:Arial; padding:30px; }
.card { background:#fff; padding:25px; border-radius:14px; }
.item { background:#f9fcff; padding:15px; margin-bottom:10px; border-radius:10px; }
</style>
</head>
<body>

<div class="card">
<h1>Daycare History</h1>

<?php while($row = $result->fetch_assoc()): ?>
<div class="item">
    <h3><?php echo e($row['center_name']); ?></h3>
    <p>Date: <?php echo e($row['request_date']); ?></p>
    <p>Status: <?php echo e($row['status']); ?></p>
</div>
<?php endwhile; ?>

</div>

</body>
</html>