<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'parent') {
    die('Access denied.');
}

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $conn->prepare("
        SELECT *
        FROM daycares
        WHERE center_name LIKE ?
           OR facilities LIKE ?
           OR age_group_supported LIKE ?
           OR description LIKE ?
        ORDER BY center_name ASC
    ");
    $like = "%{$search}%";
    $stmt->bind_param("ssss", $like, $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("
        SELECT *
        FROM daycares
        ORDER BY center_name ASC
    ");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Daycares</title>
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
            max-width: 1100px;
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
        .search-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        input[type="text"] {
            flex: 1;
            min-width: 260px;
            padding: 11px 12px;
            border-radius: 6px;
            border: 1px solid #cfe3f7;
            background: #f9fcff;
            color: #1b2b3a;
            box-sizing: border-box;
        }
        button, .btn {
            padding: 10px 16px;
            background: #1b6ec2;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            display: inline-block;
        }
        button:hover, .btn:hover {
            background: #155a9c;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 18px;
        }
        .item {
            background: #f9fcff;
            padding: 18px;
            border-radius: 12px;
            border: 1px solid #d9ebfb;
        }
        .item h3 {
            margin-top: 0;
            color: #1b6ec2;
        }
        .muted {
            color: #4a5c6b;
        }
        .empty {
            text-align: center;
            color: #4a5c6b;
        }
        .back-link {
            display: inline-block;
            margin-top: 6px;
            color: #1b6ec2;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Available Daycares</h1>
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search by center, facilities, age group, or description" value="<?php echo e($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="card">
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="item">
                        <h3><?php echo e($row['center_name']); ?></h3>
                        <p><strong>Manager:</strong> <span class="muted"><?php echo e($row['manager_name'] ?: '-'); ?></span></p>
                        <p><strong>Capacity:</strong> <span class="muted"><?php echo e($row['capacity'] ?: '-'); ?></span></p>
                        <p><strong>Opening Time:</strong> <span class="muted"><?php echo e($row['opening_time'] ?: '-'); ?></span></p>
                        <p><strong>Closing Time:</strong> <span class="muted"><?php echo e($row['closing_time'] ?: '-'); ?></span></p>
                        <p><strong>Age Group Supported:</strong> <span class="muted"><?php echo e($row['age_group_supported'] ?: '-'); ?></span></p>
                        <p><strong>Facilities:</strong> <span class="muted"><?php echo e($row['facilities'] ?: '-'); ?></span></p>
                        <p><strong>Description:</strong> <span class="muted"><?php echo e($row['description'] ?: '-'); ?></span></p>
                        <p><strong>Email:</strong> <span class="muted"><?php echo e($row['email'] ?: '-'); ?></span></p>
                        <p><strong>Phone:</strong> <span class="muted"><?php echo e($row['phone'] ?: '-'); ?></span></p>
                        <a class="btn" href="book_daycare.php?daycare_id=<?php echo (int)$row['id']; ?>">Send Request</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="empty">No daycares found.</p>
        <?php endif; ?>
    </div>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>