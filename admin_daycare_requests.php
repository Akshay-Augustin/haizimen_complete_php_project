<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

if (($_SESSION['auth']['role'] ?? '') !== 'admin') {
    die('Access denied.');
}

$allowedStatuses = ['pending', 'approved', 'rejected', 'completed', 'cancelled'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = (int)($_POST['request_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    if ($requestId > 0 && in_array($status, $allowedStatuses, true)) {
        $stmt = $conn->prepare("UPDATE daycare_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $requestId);
        $stmt->execute();
        header("Location: admin_daycare_requests.php");
        exit;
    }
}

$result = $conn->query("
    SELECT 
        dr.id,
        dr.request_date,
        dr.child_age,
        dr.notes,
        dr.status,
        dr.created_at,
        u.first_name,
        u.last_name,
        d.center_name,
        d.manager_name,
        d.capacity,
        d.opening_time,
        d.closing_time,
        d.age_group_supported
    FROM daycare_requests dr
    INNER JOIN users u ON dr.parent_user_id = u.id
    INNER JOIN daycares d ON dr.daycare_id = d.id
    ORDER BY dr.request_date DESC, dr.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Daycare Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f9ff;
            color: #1b2b3a;
            padding: 30px;
        }

        .wrap {
            max-width: 1350px;
            margin: auto;
        }

        .card {
            background: #ffffff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
            color: #1b6ec2;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #e6eef7;
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            background: #eaf4ff;
            color: #1b6ec2;
            font-weight: bold;
        }

        tr:hover {
            background: #f9fcff;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.pending { background: #fff3cd; color: #856404; }
        .status.approved { background: #d4edda; color: #155724; }
        .status.rejected { background: #f8d7da; color: #721c24; }
        .status.completed { background: #d1ecf1; color: #0c5460; }
        .status.cancelled { background: #ececec; color: #444; }

        .inline-form {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        select, button {
            padding: 9px 10px;
            border-radius: 6px;
            border: 1px solid #cfe3f7;
            font-size: 13px;
        }

        select {
            background: #f9fcff;
            color: #1b2b3a;
        }

        button {
            background: #1b6ec2;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #155a9c;
        }

        a {
            color: #1b6ec2;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .back {
            display: inline-block;
            margin-top: 16px;
        }

        .empty {
            text-align: center;
            color: #4a5c6b;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Daycare Requests</h1>

        <div class="table-wrap">
            <table>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Parent</th>
                    <th>Daycare</th>
                    <th>Manager</th>
                    <th>Capacity</th>
                    <th>Timings</th>
                    <th>Supported Age</th>
                    <th>Request Date</th>
                    <th>Child Age</th>
                    <th>Notes</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>

                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <!-- <td><?php echo (int)$row['id']; ?></td> -->
                            <td><?php echo e(trim($row['first_name'] . ' ' . $row['last_name'])); ?></td>
                            <td><?php echo e($row['center_name']); ?></td>
                            <td><?php echo e($row['manager_name'] ?: '-'); ?></td>
                            <td><?php echo e($row['capacity'] ?: '-'); ?></td>
                            <td><?php echo e(($row['opening_time'] ?: '-') . ' - ' . ($row['closing_time'] ?: '-')); ?></td>
                            <td><?php echo e($row['age_group_supported'] ?: '-'); ?></td>
                            <td><?php echo e($row['request_date']); ?></td>
                            <td><?php echo e($row['child_age'] ?: '-'); ?></td>
                            <td><?php echo e($row['notes'] ?: '-'); ?></td>
                            <td>
                                <span class="status <?php echo e($row['status']); ?>">
                                    <?php echo e($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="inline-form">
                                    <input type="hidden" name="request_id" value="<?php echo (int)$row['id']; ?>">
                                    <select name="status">
                                        <option value="pending" <?php echo $row['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="approved" <?php echo $row['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                        <option value="rejected" <?php echo $row['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                        <option value="completed" <?php echo $row['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $row['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12" class="empty">No daycare requests found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <a class="back" href="admin_dashboard.php">← Back to Admin Dashboard</a>
    </div>
</div>
</body>
</html>