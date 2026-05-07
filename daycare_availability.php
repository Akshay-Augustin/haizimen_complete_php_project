<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'daycare') {
    die('Access denied.');
}

$success = null;
$errors = [];

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($days as $day) {
        $key = strtolower($day);
        $is_available = isset($_POST['is_available'][$key]) ? 1 : 0;
        $start_time = $_POST['start_time'][$key] ?? null;
        $end_time = $_POST['end_time'][$key] ?? null;

        if ($is_available) {
            if (empty($start_time) || empty($end_time)) {
                $errors[] = "$day time is required.";
                continue;
            }

            if (strtotime($start_time) >= strtotime($end_time)) {
                $errors[] = "$day end time must be greater than start time.";
                continue;
            }
        } else {
            $start_time = null;
            $end_time = null;
        }

        $stmt = $conn->prepare("
            INSERT INTO daycare_availability (daycare_user_id, day_name, is_available, start_time, end_time)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                is_available = VALUES(is_available),
                start_time = VALUES(start_time),
                end_time = VALUES(end_time)
        ");
        $stmt->bind_param("isiss", $user['id'], $day, $is_available, $start_time, $end_time);
        $stmt->execute();
    }

    if (!$errors) {
        $success = "Availability updated successfully.";
    }
}

$availabilityMap = [];
$stmt = $conn->prepare("SELECT * FROM daycare_availability WHERE daycare_user_id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $availabilityMap[$row['day_name']] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Daycare Availability</title>
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
            max-width: 900px;
            margin: auto;
        }
        .card {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        h1 {
            margin-top: 0;
            color: #1b6ec2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 8px;
            border-bottom: 1px solid #e5eef8;
            text-align: left;
        }
        input[type="time"] {
            padding: 8px;
            border: 1px solid #cfe3f7;
            border-radius: 6px;
            background: #f9fcff;
        }
        button {
            margin-top: 20px;
            padding: 10px 18px;
            background: #1b6ec2;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #155a9c;
        }
        .alert {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .success {
            background: #e8fff1;
            border: 1px solid #aad7b7;
            color: #1e6c35;
        }
        .error {
            background: #ffecec;
            border: 1px solid #f5c6cb;
            color: #a94442;
        }
        a {
            color: #1b6ec2;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Manage Daycare Availability</h1>

        <?php if ($success): ?>
            <div class="alert success"><?php echo e($success); ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Open</th>
                        <th>From</th>
                        <th>To</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($days as $day): ?>
                    <?php
                        $key = strtolower($day);
                        $row = $availabilityMap[$day] ?? null;
                    ?>
                    <tr>
                        <td><?php echo e($day); ?></td>
                        <td>
                            <input
                                type="checkbox"
                                name="is_available[<?php echo $key; ?>]"
                                value="1"
                                <?php echo (!empty($row) && (int)$row['is_available'] === 1) ? 'checked' : ''; ?>
                            >
                        </td>
                        <td>
                            <input
                                type="time"
                                name="start_time[<?php echo $key; ?>]"
                                value="<?php echo e($row['start_time'] ?? ''); ?>"
                            >
                        </td>
                        <td>
                            <input
                                type="time"
                                name="end_time[<?php echo $key; ?>]"
                                value="<?php echo e($row['end_time'] ?? ''); ?>"
                            >
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit">Save Availability</button>
        </form>

        <p style="margin-top:20px;"><a href="dashboard.php">← Back to Dashboard</a></p>
    </div>
</div>
</body>
</html>