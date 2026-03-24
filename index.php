<?php include("index/connect.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Haizimen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">

    <style>
       html {
    scroll-behavior: smooth;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f9ff;
    color: #1b2b3a;
}

/* ===== HEADER ===== */
.header {
    background: #ffffff;
    padding: 10px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.navbar-nav .nav-link {
    color: #1b2b3a !important;
    margin-right: 15px;
    font-weight: 500;
}

.navbar-nav .nav-link:hover {
    color: #1b6ec2 !important;
}

.logo a {
    color: #1b6ec2;
    font-weight: bold;
    font-size: 20px;
    text-decoration: none;
}

/* ===== HERO ===== */
.hero {
    min-height: 100vh;
    background: linear-gradient(to right, #eaf4ff, #d6ecff);
    color: #1b2b3a;
    display: flex;
    align-items: center;
    padding: 60px 0;
}

.hero .container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 40px;
}

.hero-text h1 {
    font-size: 70px;
    font-weight: bold;
    line-height: 1.1;
    color: #1b2b3a;
}

.hero-text p {
    width: 400px;
    margin-top: 20px;
    font-size: 20px;
    line-height: 1.6;
    color: #4a5c6b;
}

.hero-text a {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 28px;
    background: #1b6ec2;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
}

.hero-img img {
    width: 420px;
    max-width: 100%;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* ===== ABOUT ===== */
.about {
    padding: 80px 0;
    background: #ffffff;
}

.about h2,
.service h2,
.contact h2 {
    font-size: 42px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #1b6ec2;
}

.about p,
.contact p {
    font-size: 18px;
    line-height: 1.8;
    color: #4a5c6b;
}

.about .btn-primary,
.contact .btn-primary {
    background: #1b6ec2;
    border: none;
    padding: 10px 24px;
    border-radius: 6px;
    margin-top: 10px;
}

.about-gallery img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

/* ===== SERVICES ===== */
.service {
    padding: 80px 0;
    background: #f4f9ff;
}

.service-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 30px 20px;
    text-align: center;
    transition: 0.3s ease;
    height: 100%;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.service-card:hover {
    transform: translateY(-8px);
    background: #eaf4ff;
}

.service-card i {
    font-size: 40px;
    color: #1b6ec2;
    margin-bottom: 15px;
}

.service-card h4 {
    color: #1b2b3a;
    margin-bottom: 10px;
    font-weight: bold;
}

.service-card p {
    color: #4a5c6b;
    margin: 0;
}

/* ===== CONTACT ===== */
.contact {
    padding: 80px 0;
    background: #ffffff;
    text-align: center;
}

/* ===== FOOTER ===== */
footer {
    background: #1b6ec2;
    color: white;
    text-align: center;
    padding: 18px;
}

/* ===== MOBILE ===== */
@media (max-width: 768px) {
    .hero .container {
        flex-direction: column;
        text-align: center;
    }

    .hero-text h1 {
        font-size: 42px;
    }

    .hero-text p {
        width: 100%;
        font-size: 17px;
    }

    .hero-img img {
        width: 260px;
        margin-top: 20px;
    }

    .about h2,
    .service h2,
    .contact h2 {
        font-size: 32px;
    }

    .navbar-nav .nav-link.active {
        color: #1b6ec2 !important;
    }
}
    </style>
</head>
<body>

<div class="header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-3 col-sm-4 logo">
                <a href="#home">HAIZIMEN CENTER</a>
            </div>

            <div class="col-lg-9 col-sm-8">
                <nav class="navbar navbar-expand-md navbar-dark">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                            <li class="nav-item"><a class="nav-link" href="#services">Service</a></li>
                            <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>

                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="hero" id="home">
    <div class="container">
        <div class="hero-text">
            <h1>YOUTH<br>CARE</h1>
            <p>
                There are many variations of passages available, but the majority have suffered alteration.
            </p>
            <a href="#about">READ MORE</a>
        </div>

        <div class="hero-img">
            <img src="https://images.unsplash.com/photo-1516627145497-ae6968895b74?auto=format&fit=crop&w=900&q=80" alt="baby care">
        </div>
    </div>
</section>

<section class="about" id="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="row about-gallery">
                    <div class="col-12 mb-3">
                        <img src="https://images.unsplash.com/photo-1519340241574-2cec6aef0c01?auto=format&fit=crop&w=900&q=80" alt="child care">
                    </div>
                    <div class="col-6">
                        <img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=900&q=80" alt="mother with baby">
                    </div>
                    <div class="col-6">
                        <img src="https://images.unsplash.com/photo-1544126592-807ade215a0b?auto=format&fit=crop&w=900&q=80" alt="happy child">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <h2>About</h2>
                <p>
                    Haizimen Center is a child healthcare and guidance application designed to help parents manage
                    important services in one place. It supports vaccination search and booking, doctor consultation,
                    appointment scheduling, daycare support, caretaker access, and useful child-care instructions.
                </p>
                <p>
                    The application is built to make child health management easier, safer, and more organized for families.
                </p>
                <a href="#services" class="btn btn-primary">Explore Services</a>
            </div>
        </div>
    </div>
</section>

<section class="service" id="services">
    <div class="container text-center">
        <h2>Services</h2>

        <div class="row mt-5">
            <div class="col-md-3 mb-4">
                <div class="service-card">
                    <i class="fas fa-syringe"></i>
                    <h4>Vaccination</h4>
                    <p>Track immunizations and manage vaccine reminders easily.</p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="service-card">
                    <i class="fas fa-user-nurse"></i>
                    <h4>Caretaker</h4>
                    <p>Find reliable caretakers for better child support and care.</p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="service-card">
                    <i class="fas fa-user-md"></i>
                    <h4>Doctor</h4>
                    <p>Book doctors and manage consultation appointments quickly.</p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="service-card">
                    <i class="fas fa-school"></i>
                    <h4>Daycare</h4>
                    <p>Manage daycare details and support your children safely.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact" id="contact">
    <div class="container">
        <h2>Contact</h2>
        <p>Email: hizimencenter4@gmail.com</p>
        <a href="mailto:hizimencenter4@gmail.com" class="btn btn-primary">Send Email</a>
    </div>
</section>

<footer>
    <p>© 2026 Haizimen Center. All Rights Reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>