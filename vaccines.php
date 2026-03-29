<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];
$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $conn->prepare("
        SELECT * FROM vaccines 
        WHERE vaccine_name LIKE ? 
           OR age_group LIKE ? 
           OR description LIKE ? 
           OR protects_against LIKE ?
        ORDER BY age_group ASC, vaccine_name ASC
    ");
    $like = "%{$search}%";
    $stmt->bind_param("ssss", $like, $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM vaccines ORDER BY age_group ASC, vaccine_name ASC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Vaccination Details</title>
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
            max-width: 1200px;
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

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border-bottom: 1px solid #d9ebfb;
            padding: 14px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #eaf4ff;
            color: #1b6ec2;
        }

        tr:hover {
            background: #f9fcff;
        }

        .back-link {
            display: inline-block;
            margin-top: 6px;
            color: #1b6ec2;
            font-weight: bold;
            text-decoration: none;
        }

        .empty {
            color: #4a5c6b;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Vaccination Details</h1>
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search vaccine, age group, description, or disease" value="<?php echo e($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <tr>
                    <th>Age Group</th>
                    <th>Vaccine</th>
                    <th>Protects Against</th>
                    <th>Description</th>
                    <?php if ($user['role'] === 'parent'): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>

                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo e($row['age_group']); ?></td>
                            <td><?php echo e($row['vaccine_name']); ?></td>
                            <td><?php echo e($row['protects_against']); ?></td>
                            <td><?php echo e($row['description']); ?></td>
                            <?php if ($user['role'] === 'parent'): ?>
                                <td>
                                    <a class="btn" href="book_vaccine.php?vaccine_id=<?php echo (int)$row['id']; ?>">Book</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo $user['role'] === 'parent' ? '5' : '4'; ?>" class="empty">No vaccines found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>