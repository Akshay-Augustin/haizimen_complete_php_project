<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

if (($_SESSION['auth']['role'] ?? '') !== 'admin') {
    die('Access denied.');
}

$statusFilter = trim($_GET['status'] ?? '');
$allowedStatuses = ['pending', 'approved', 'rejected', 'completed', 'cancelled'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = (int)($_POST['appointment_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    if ($appointment_id > 0 && in_array($status, $allowedStatuses, true)) {
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $appointment_id);
        $stmt->execute();

        $redirect = "admin_appointments.php";
        if ($statusFilter !== '' && in_array($statusFilter, $allowedStatuses, true)) {
            $redirect .= "?status=" . urlencode($statusFilter);
        }

        header("Location: " . $redirect);
        exit;
    }
}

if ($statusFilter !== '' && in_array($statusFilter, $allowedStatuses, true)) {
    $stmt = $conn->prepare("
        SELECT 
            a.id,
            a.appointment_date,
            a.appointment_time,
            a.status,
            a.notes,
            u.first_name,
            u.last_name,
            d.doctor_name,
            d.department
        FROM appointments a
        INNER JOIN users u ON a.parent_user_id = u.id
        INNER JOIN doctors d ON a.doctor_id = d.id
        WHERE a.status = ?
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    $stmt->bind_param("s", $statusFilter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("
        SELECT 
            a.id,
            a.appointment_date,
            a.appointment_time,
            a.status,
            a.notes,
            u.first_name,
            u.last_name,
            d.doctor_name,
            d.department
        FROM appointments a
        INNER JOIN users u ON a.parent_user_id = u.id
        INNER JOIN doctors d ON a.doctor_id = d.id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Appointments</title>
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
            max-width: 1200px;
            margin: auto;
        }

        .card {
            background: #ffffff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        h1 {
            margin-top: 0;
            color: #1b6ec2;
        }

        .filter-bar {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        select, button {
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #cfe3f7;
            font-size: 14px;
            box-sizing: border-box;
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
            background: #f4f9ff;
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

        .inline-form select {
            min-width: 130px;
            margin: 0;
        }

        .inline-form button {
            margin: 0;
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
            margin-top: 14px;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>All Appointments</h1>

        <form method="GET" class="filter-bar">
            <label for="status"><strong>Filter by Status:</strong></label>
            <select name="status" id="status">
                <option value="">All</option>
                <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="approved" <?php echo $statusFilter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                <option value="rejected" <?php echo $statusFilter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                <option value="completed" <?php echo $statusFilter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="cancelled" <?php echo $statusFilter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
            <button type="submit">Apply Filter</button>
        </form>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Parent</th>
                    <th>Doctor</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Notes</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>

                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo (int)$row['id']; ?></td>
                        <td><?php echo e($row['first_name'] . ' ' . $row['last_name']); ?></td>
                        <td><?php echo e($row['doctor_name']); ?></td>
                        <td><?php echo e($row['department']); ?></td>
                        <td><?php echo e($row['appointment_date']); ?></td>
                        <td><?php echo e($row['appointment_time']); ?></td>
                        <td><?php echo e($row['notes']); ?></td>
                        <td>
                            <span class="status <?php echo e($row['status']); ?>">
                                <?php echo e($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="appointment_id" value="<?php echo (int)$row['id']; ?>">
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
                        <td colspan="9">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <a class="back" href="admin_dashboard.php">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>