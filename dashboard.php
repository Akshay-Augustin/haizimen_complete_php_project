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

        h2 {
            margin-top: 0;
            color: #1b6ec2;
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
            <a class="nav-btn" href="logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
        </div>
    </div>
</div>

<div class="wrap">

    <?php if ($role === 'parent'): ?>
        <div class="card">
            <h2>Parent Dashboard</h2>
            <div class="grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-syringe"></i>
                    </div>
                    <h3>Vaccination Details</h3>
                    <p>View the vaccination chart, age groups, diseases covered, and vaccine information.</p>
                    <a class="btn" href="vaccines.php">View Vaccines</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-notes-medical"></i>
                    </div>
                    <h3>Book Vaccine</h3>
                    <p>Choose a vaccine and schedule a vaccination booking with an available doctor.</p>
                    <a class="btn" href="vaccines.php">Book Vaccine</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <h3>Vaccine Reminders</h3>
                    <p>Check upcoming vaccine reminders and see which dose is due in the next few days.</p>
                    <a class="btn" href="vaccine_reminders.php">View Reminders</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <h3>Vaccine History</h3>
                    <p>See your previous vaccine bookings, statuses, dates, and assigned doctors.</p>
                    <a class="btn" href="vaccine_history.php">View History</a>
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
                    <p>Track all your booked doctor appointments and see their current status.</p>
                    <a class="btn" href="my_appointments.php">View Appointments</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-user-nurse"></i>
                    </div>
                    <h3>View Caretakers</h3>
                    <p>Browse available caretakers with their experience, skills, location, and rates.</p>
                    <a class="btn" href="caretakers.php">View Caretakers</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                    <h3>Caretaker History</h3>
                    <p>Track your caretaker requests and see whether they are pending, approved, rejected, or completed.</p>
                    <a class="btn" href="caretaker_history.php">View History</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-school"></i>
                    </div>
                    <h3>View Daycares</h3>
                    <p>Explore daycare centers with timings, facilities, capacity, and contact details.</p>
                    <a class="btn" href="daycares.php">View Daycares</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <h3>Daycare History</h3>
                    <p>See your daycare requests, request dates, child age details, and current status.</p>
                    <a class="btn" href="daycare_history.php">View History</a>
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
                    <p>View all appointment requests booked by parents and update their status.</p>
                    <a class="btn" href="doctor_appointments.php">View Appointments</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-syringe"></i>
                    </div>
                    <h3>Vaccine Bookings</h3>
                    <p>See vaccine bookings assigned to you and manage vaccination requests.</p>
                    <a class="btn" href="doctor_vaccine_bookings.php">View Vaccine Bookings</a>
                </div>
            </div>
        </div>

    <?php elseif ($role === 'caretaker'): ?>
        <div class="card">
            <h2>Caretaker Dashboard</h2>
            <div class="grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-id-badge"></i>
                    </div>
                    <h3>My Profile</h3>
                    <p>View your caretaker profile, experience, skills, and contact details.</p>
                    <a class="btn" href="caretaker_profile.php">Open</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <h3>Requests</h3>
                    <p>See upcoming caretaker requests and manage assignments.</p>
                    <a class="btn" href="caretaker_requests.php">Open</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <h3>Availability</h3>
                    <p>Manage working hours, availability, and preferred locations.</p>
                    <a class="btn" href="caretaker_availability.php">Open</a>
                </div>
            </div>
        </div>

    <?php elseif ($role === 'daycare'): ?>
        <div class="card">
            <h2>Daycare Dashboard</h2>
            <div class="grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-school"></i>
                    </div>
                    <h3>Center Profile</h3>
                    <p>View and manage daycare center details, timings, and facilities.</p>
                    <a class="btn" href="daycare_profile.php">Open</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                    <h3>Requests</h3>
                    <p>See parent enrollment requests and manage daycare entries.</p>
                    <a class="btn" href="daycare_requests.php">Open</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-building-circle-check"></i>
                    </div>
                    <h3>Capacity</h3>
                    <p>Track daycare capacity, opening hours, and current occupancy details.</p>
                    <a class="btn" href="daycare_capacity.php">Open</a>
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