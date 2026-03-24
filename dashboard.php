<?php
require_once "app/Helpers/helpers.php";
ensure_auth();
$user = $_SESSION['auth'];
$role = $user['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
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
            border-bottom: 1px solid rgba(0,0,0,0.06);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .topbar-inner {
            max-width: 1150px;
            margin: 0 auto;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            color: #1b6ec2;
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 0.4px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: #1b2b3a;
            background: #eaf4ff;
            border: 1px solid #d9ebfb;
            font-weight: bold;
            transition: 0.2s ease;
        }

        .nav-btn:hover {
            background: #1b6ec2;
            color: white;
        }

        .nav-btn.logout {
            background: #eaf4ff;
            border-color: #d9ebfb;
        }

        .wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 35px 20px 40px;
        }

        .card {
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .msg {
            padding: 12px 14px;
            background: #e8fff1;
            border: 1px solid #aad7b7;
            color: #1e6c35;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        h1, h2 {
            margin-top: 0;
            color: #1b6ec2;
        }

        .user-meta {
            display: grid;
            gap: 10px;
            margin-top: 18px;
        }

        .meta-item {
            color: #4a5c6b;
            font-size: 17px;
        }

        .meta-item i {
            color: #1b6ec2;
            width: 24px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .feature-card {
            background: #f9fcff;
            padding: 24px;
            border-radius: 14px;
            border: 1px solid #d9ebfb;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
            background: #eef7ff;
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eaf4ff;
            color: #1b6ec2;
            font-size: 24px;
            margin-bottom: 18px;
        }

        .feature-card h3 {
            margin-top: 0;
            color: #1b2b3a;
            margin-bottom: 12px;
        }

        .feature-card p {
            color: #4a5c6b;
            line-height: 1.7;
            margin-bottom: 0;
        }

        .btn {
            display: inline-block;
            margin-top: 18px;
            padding: 10px 18px;
            background: #1b6ec2;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn:hover {
            background: #155a9c;
        }

        @media (max-width: 768px) {
            .topbar-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                width: 100%;
            }

            .wrap {
                padding-top: 25px;
            }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-inner">
        <a href="dashboard.php" class="brand">Haizimen</a>

        <div class="nav-links">
            <!-- <a class="nav-btn" href="index.php">
                <i class="fa-solid fa-house"></i>
                Home
            </a> -->
            <a class="nav-btn logout" href="logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
        </div>
    </div>
</div>

<div class="wrap">
    <?php if ($role === 'parent'): ?>
        <div class="card">
            <h2>Dashboard</h2>
            <div class="grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-syringe"></i>
                    </div>
                    <h3>Vaccination Details</h3>
                    <p>Search vaccines, read descriptions, and choose the right vaccine information for your child.</p>
                    <a class="btn" href="vaccines.php">View Vaccines</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <h3>Book Appointment</h3>
                    <p>See available doctors and departments, then book an appointment easily.</p>
                    <a class="btn" href="book_appointment.php">Book Now</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <h3>My Appointments</h3>
                    <p>Track all your booked appointments and see their current status.</p>
                    <a class="btn" href="my_appointments.php">View Appointments</a>
                </div>
            </div>
        </div>
    <?php elseif ($role === 'doctor'): ?>
        <div class="card">
            <h2>Doctor Dashboard</h2>
            <div class="grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-stethoscope"></i>
                    </div>
                    <h3>Incoming Appointments</h3>
                    <p>View all appointment requests booked by parents for you.</p>
                    <a class="btn" href="doctor_appointments.php">View Appointments</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <h2><?php echo ucfirst(e($role)); ?> Dashboard</h2>
            <div class="grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <h3>Dashboard Module</h3>
                    <p>This role dashboard can be extended next based on your project requirements.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>