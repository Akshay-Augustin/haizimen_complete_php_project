<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM vaccines WHERE vaccine_name LIKE ? OR age_group LIKE ? OR description LIKE ? ORDER BY vaccine_name ASC");
    $like = "%{$search}%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM vaccines ORDER BY vaccine_name ASC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Vaccines</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin:0; font-family:Arial,sans-serif; background:linear-gradient(to right,#081b2d,#0b1c2c); color:white; padding:40px 20px; }
        .wrap { max-width:1100px; margin:auto; }
        .card { background:#10263d; padding:25px; border-radius:14px; margin-bottom:20px; }
        input[type="text"] { width:75%; padding:10px; border-radius:6px; border:1px solid #2c4966; background:#0b1c2c; color:white; }
        button, .btn { padding:10px 18px; background:#ff5a3c; color:white; border:none; border-radius:6px; text-decoration:none; font-weight:bold; cursor:pointer; }
        .vaccine { background:#0b1c2c; padding:18px; border-radius:10px; margin-bottom:15px; }
        .vaccine h3 { margin-top:0; }
        .muted { color:#dbe6f2; }
        a { color:#ff5a3c; text-decoration:none; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Vaccination Details</h1>
        <form method="GET">
            <input type="text" name="search" placeholder="Search vaccine name, age group, or description" value="<?php echo e($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="card">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="vaccine">
                <h3><?php echo e($row['vaccine_name']); ?></h3>
                <p><strong>Age Group:</strong> <span class="muted"><?php echo e($row['age_group']); ?></span></p>
                <p><strong>Description:</strong> <span class="muted"><?php echo e($row['description']); ?></span></p>
            </div>
        <?php endwhile; ?>
    </div>

    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>