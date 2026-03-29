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
$caretakerCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'caretaker'")->fetch_assoc()['total'] ?? 0;
$daycareCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'daycare'")->fetch_assoc()['total'] ?? 0;

$appointmentCount = $conn->query("SELECT COUNT(*) AS total FROM appointments")->fetch_assoc()['total'] ?? 0;
$vaccineCount = $conn->query("SELECT COUNT(*) AS total FROM vaccines")->fetch_assoc()['total'] ?? 0;
$vaccineBookingCount = $conn->query("SELECT COUNT(*) AS total FROM vaccine_bookings")->fetch_assoc()['total'] ?? 0;

$caretakerRequestCount = $conn->query("SELECT COUNT(*) AS total FROM caretaker_requests")->fetch_assoc()['total'] ?? 0;
$daycareRequestCount = $conn->query("SELECT COUNT(*) AS total FROM daycare_requests")->fetch_assoc()['total'] ?? 0;
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
            background: #f4f9ff;
            color: #1b2b3a;
            min-height: 100vh;
        }

        .topbar {
            background: #ffffff;
            padding: 16px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            color: #1b6ec2;
        }

        .nav {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nav a {
            color: #1b2b3a;
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 8px;
            background: #eaf4ff;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .nav a:hover {
            background: #1b6ec2;
            color: white;
        }

        .wrap {
            max-width: 1150px;
            margin: 0 auto;
            padding: 30px 20px 40px;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .card h1,
        .card h2 {
            margin-top: 0;
            color: #1b6ec2;
        }

        .card p {
            color: #4a5c6b;
            line-height: 1.7;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }

        .stat {
            background: #f4f9ff;
            border-radius: 14px;
            padding: 22px;
            border: 1px solid #d9ebfb;
        }

        .stat i {
            font-size: 28px;
            color: #1b6ec2;
            margin-bottom: 14px;
        }

        .stat h3 {
            margin: 0 0 8px;
            font-size: 18px;
            color: #1b2b3a;
        }

        .stat .count {
            font-size: 30px;
            font-weight: bold;
            color: #1b2b3a;
        }

        .actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
        }

        .action-box {
            background: #f4f9ff;
            border-radius: 14px;
            padding: 22px;
            border: 1px solid #d9ebfb;
            transition: 0.3s ease;
        }

        .action-box:hover {
            transform: translateY(-4px);
            background: #eaf4ff;
        }

        .action-box h3 {
            margin-top: 0;
            color: #1b2b3a;
        }

        .action-box p {
            color: #4a5c6b;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            margin-top: 12px;
            background: #1b6ec2;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: bold;
        }

        .btn:hover {
            background: #155a9c;
        }

        @media (max-width: 768px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .nav {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="brand">Admin Dashboard</div>
        <div class="nav">
            <!-- <a href="index.php"><i class="fa-solid fa-house"></i> Home</a> -->
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
                    <i class="fa-solid fa-user-nurse"></i>
                    <h3>Caretakers</h3>
                    <div class="count"><?php echo (int)$caretakerCount; ?></div>
                </div>

                <div class="stat">
                    <i class="fa-solid fa-school"></i>
                    <h3>Daycares</h3>
                    <div class="count"><?php echo (int)$daycareCount; ?></div>
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

                <div class="stat">
                    <i class="fa-solid fa-notes-medical"></i>
                    <h3>Vaccine Bookings</h3>
                    <div class="count"><?php echo (int)$vaccineBookingCount; ?></div>
                </div>

                <div class="stat">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                    <h3>Caretaker Requests</h3>
                    <div class="count"><?php echo (int)$caretakerRequestCount; ?></div>
                </div>

                <div class="stat">
                    <i class="fa-solid fa-building-circle-check"></i>
                    <h3>Daycare Requests</h3>
                    <div class="count"><?php echo (int)$daycareRequestCount; ?></div>
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
                    <h3>View Caretakers</h3>
                    <p>See all registered caretakers with their experience, skills, fee, and location.</p>
                    <a class="btn" href="admin_caretakers.php">Open</a>
                </div>

                <div class="action-box">
                    <h3>View Daycares</h3>
                    <p>See all daycare centers, timings, capacity, and facilities.</p>
                    <a class="btn" href="admin_daycares.php">Open</a>
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

                <div class="action-box">
                    <h3>Vaccine Bookings</h3>
                    <p>Monitor all vaccine bookings made by parents and assigned to doctors.</p>
                    <a class="btn" href="admin_vaccine_bookings.php">Open</a>
                </div>

                <div class="action-box">
                    <h3>Caretaker Requests</h3>
                    <p>Monitor caretaker service requests made by parents.</p>
                    <a class="btn" href="admin_caretaker_requests.php">Open</a>
                </div>

                <div class="action-box">
                    <h3>Daycare Requests</h3>
                    <p>Monitor daycare requests and enrollment actions.</p>
                    <a class="btn" href="admin_daycare_requests.php">Open</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>