<?php include("index/connect.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>About - Haizimen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #081b2d;
            color: white;
        }

        .container {
            max-width: 1000px;
            margin: 60px auto;
            padding: 30px;
            background: #10263d;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        h1 {
            color: #ff5a3c;
            margin-bottom: 20px;
        }

        h2 {
            margin-top: 30px;
            color: #ffffff;
        }

        p {
            line-height: 1.8;
            color: #dbe6f2;
        }

        ul {
            margin-top: 15px;
        }

        li {
            margin: 10px 0;
            color: #dbe6f2;
        }

        .section {
            margin-bottom: 40px;
        }

        .highlight {
            background: #081b2d;
            padding: 20px;
            border-radius: 10px;
            margin-top: 15px;
        }

        a {
            color: #ff5a3c;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 25px;
            background: #ff5a3c;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- AIM -->
    <div class="section">
        <h1>AIM</h1>
        <div class="highlight">
            <ul>
                <li>To create an application to track juvenile major and minor details.</li>
                <li>Haizimen Center is a web-based application.</li>
                <li>This platform allows users to search and book vaccinations easily.</li>
                <li>Children and parents can consult doctors online.</li>
                <li>Parents can book doctor appointments for children.</li>
                <li>All important child-care instructions and information are available in one place.</li>
            </ul>
        </div>
    </div>

    <!-- PROPOSED SYSTEM -->
    <div class="section">
        <h1>PROPOSED SYSTEM</h1>
        <p>
            Haizimen Center is a modern social healthcare web application designed for youth care and child development.
            It provides a platform where parents can manage child health records, consult doctors, book vaccinations,
            and access daycare and caretaker services.
        </p>

        <p>
            The system also helps parents by providing vaccination reminders, child growth guidelines,
            nutritional advice, and brain development activities. It simplifies parenting by keeping all
            essential services and information in one place.
        </p>

        <h2>Advantages</h2>
        <div class="highlight">
            <ul>
                <li>Easy booking and searching of vaccinations.</li>
                <li>Doctor consultation and appointment scheduling.</li>
                <li>Vaccination reminders for children.</li>
                <li>Guidelines and instructions for children up to 16 years.</li>
                <li>Access to daycare and caretaker services.</li>
                <li>Child growth and nutrition tracking support.</li>
            </ul>
        </div>
    </div>

    <!-- MODULES -->
    <div class="section">
        <h1>MODULES</h1>

        <h2>Admin Module</h2>
        <div class="highlight">
            <ul>
                <li>Manage all aspects of the application and database.</li>
                <li>Highest level access to the system.</li>
                <li>Assign and manage user roles.</li>
                <li>Access to all services and data.</li>
            </ul>
        </div>

        <h2>User Module</h2>
        <div class="highlight">
            <ul>
                <li>Create and manage user profile.</li>
                <li>Search vaccination details.</li>
                <li>Rate and review the application.</li>
                <li>Report issues and problems.</li>
                <li>Book vaccination and doctor appointments.</li>
            </ul>
        </div>

        <h2>Services Module</h2>
        <div class="highlight">
            <ul>
                <li>Book vaccines and doctor appointments.</li>
                <li>Search vaccination details.</li>
                <li>Chat with doctors and parents.</li>
                <li>Parents can post updates.</li>
                <li>Provide instructions and guidance for children.</li>
            </ul>
        </div>
    </div>

    <!-- BACK BUTTON -->
    <a href="index.php" class="btn">← Back to Home</a>

</div>

</body>
</html>