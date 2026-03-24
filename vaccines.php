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

        input[type="text"]::placeholder {
            color: #6c7c8c;
        }

        button,
        .btn {
            padding: 11px 18px;
            background: #1b6ec2;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            display: inline-block;
        }

        button:hover,
        .btn:hover {
            background: #155a9c;
        }

        .vaccine {
            background: #f9fcff;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 15px;
            border: 1px solid #d9ebfb;
        }

        .vaccine h3 {
            margin-top: 0;
            margin-bottom: 12px;
            color: #1b6ec2;
        }

        .muted {
            color: #4a5c6b;
        }

        .back-link {
            display: inline-block;
            margin-top: 4px;
            color: #1b6ec2;
            text-decoration: none;
            font-weight: bold;
        }

        .empty {
            color: #4a5c6b;
            text-align: center;
            padding: 10px 0;
        }

        @media (max-width: 768px) {
            body {
                padding: 25px 14px;
            }

            .search-bar {
                flex-direction: column;
            }

            input[type="text"] {
                width: 100%;
                min-width: 100%;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Vaccination Details</h1>
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search vaccine name, age group, or description" value="<?php echo e($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="card">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="vaccine">
                    <h3><?php echo e($row['vaccine_name']); ?></h3>
                    <p><strong>Age Group:</strong> <span class="muted"><?php echo e($row['age_group']); ?></span></p>
                    <p><strong>Description:</strong> <span class="muted"><?php echo e($row['description']); ?></span></p>
                    <p><strong>Status:</strong> <span class="muted"><?php echo e($row['status'] ?? 'active'); ?></span></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="empty">No vaccines found.</p>
        <?php endif; ?>
    </div>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>