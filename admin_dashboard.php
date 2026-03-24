<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
ensure_auth();

$user = $_SESSION['auth'];

if ($user['role'] !== 'admin') {
    die('Access denied.');
}

$parentCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'parent'")->fetch_assoc()['total'] ?? 0;
$doctorCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'doctor'")->fetch_assoc()['total'] ?? 0;
$appointmentCount = $conn->query("SELECT COUNT(*) AS total FROM appointments")->fetch_assoc()['total'] ?? 0;
$vaccineCount = $conn->query("SELECT COUNT(*) AS total FROM vaccines")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #081b2d, #0b1c2c);
            color: white;
            min-height: 100vh;
        }

        .topbar {
            background: #071a2b;
            padding: 16px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
        }

        .nav a {
            color: white;
            text-decoration: none;
            margin-left: 14px;
            padding: 10px 14px;
            border-radius: 8px;
            background: rgba(255,255,255,0.06);
        }

        .nav a:hover {
            background: #ff5a3c;
        }

        .wrap {
            max-width: 1150px;
            margin: 0 auto;
            padding: 30px 20px 40px;
        }

        .card {
            background: #10263d;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.30);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }

        .stat {
            background: #0b1c2c;
            border-radius: 14px;
            padding: 22px;
        }

        .stat i {
            font-size: 28px;
            color: #ff5a3c;
            margin-bottom: 14px;
        }

        .stat h3 {
            margin: 0 0 8px;
            font-size: 18px;
        }

        .stat .count {
            font-size: 30px;
            font-weight: bold;
        }

        .actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
        }

        .action-box {
            background: #0b1c2c;
            border-radius: 14px;
            padding: 22px;
        }

        .action-box h3 {
            margin-top: 0;
        }

        .action-box p {
            color: #dbe6f2;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            margin-top: 12px;
            background: #ff5a3c;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="brand">Admin Dashboard</div>
        <div class="nav">
            <a href="index.php"><i class="fa-solid fa-house"></i> Home</a>
            <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </div>

    <div class="wrap">
        <div class="card">
            <h1>Welcome, <?php echo e($user['name'] ?: $user['username']); ?></h1>
            <p>Administrator control panel for Haizimen Center.</p>
        </div>

        <div class="card">
            <h2>Overview</h2>
            <div class="grid">
                <div class="stat">
                    <i class="fa-solid fa-people-group"></i>
                    <h3>Parents</h3>
                    <div class="count"><?php echo (int)$parentCount; ?></div>
                </div>
                <div class="stat">
                    <i class="fa-solid fa-user-doctor"></i>
                    <h3>Doctors</h3>
                    <div class="count"><?php echo (int)$doctorCount; ?></div>
                </div>
                <div class="stat">
                    <i class="fa-solid fa-calendar-check"></i>
                    <h3>Appointments</h3>
                    <div class="count"><?php echo (int)$appointmentCount; ?></div>
                </div>
                <div class="stat">
                    <i class="fa-solid fa-syringe"></i>
                    <h3>Vaccines</h3>
                    <div class="count"><?php echo (int)$vaccineCount; ?></div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Admin Actions</h2>
            <div class="actions">
                <div class="action-box">
                    <h3>View Parents</h3>
                    <p>See all registered parent users.</p>
                    <a class="btn" href="admin_parents.php">Open</a>
                </div>

                <div class="action-box">
                    <h3>View Doctors</h3>
                    <p>See all registered doctors and departments.</p>
                    <a class="btn" href="admin_doctors.php">Open</a>
                </div>

                <div class="action-box">
                    <h3>View Appointments</h3>
                    <p>Monitor all appointments booked between parents and doctors.</p>
                    <a class="btn" href="admin_appointments.php">Open</a>
                </div>

                <div class="action-box">
                    <h3>Manage Vaccines</h3>
                    <p>Add, edit, and manage vaccine details centrally.</p>
                    <a class="btn" href="admin_vaccines.php">Open</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>