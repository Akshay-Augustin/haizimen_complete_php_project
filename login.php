<?php
include('connect.php');
require_once 'app/Controllers/AuthController.php';

$errors = [];
if (isset($_POST['login'])) {
    $controller = new AuthController($conn);
    $result = $controller->login();

if ($result['success']) {
    header('Location: ' . ($result['redirect'] ?? 'dashboard.php'));
    exit;
}

    $errors = $result['errors'] ?? ['Invalid login.'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login - Haizimen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #081b2d, #0b1c2c);
            min-height: 100vh;
            color: white;
        }

        .page-wrap {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            display: flex;
            width: 900px;
            max-width: 100%;
            background: #10263d;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .left {
            width: 50%;
            background:
                linear-gradient(rgba(8,27,45,0.15), rgba(8,27,45,0.35)),
                url('https://images.unsplash.com/photo-1519340241574-2cec6aef0c01?auto=format&fit=crop&w=900&q=80') no-repeat center/cover;
            min-height: 520px;
        }

        .right {
            width: 50%;
            padding: 40px 32px;
            background: #10263d;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #ffffff;
        }

        .subtext {
            text-align: center;
            color: #dbe6f2;
            margin-bottom: 22px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 11px 12px;
            margin: 10px 0;
            border: 1px solid #2c4966;
            border-radius: 6px;
            box-sizing: border-box;
            background: #0b1c2c;
            color: white;
        }

        input::placeholder {
            color: #b7c7d6;
        }

        button {
            width: 100%;
            padding: 11px;
            background: #ff5a3c;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 8px;
        }

        button:hover {
            background: #e14a2f;
        }

        p {
            text-align: center;
            color: #dbe6f2;
        }

        a {
            color: #ff5a3c;
            text-decoration: none;
            font-weight: bold;
        }

        .alert {
            background: rgba(255, 90, 60, 0.12);
            color: #ffd2ca;
            border: 1px solid rgba(255, 90, 60, 0.35);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .success {
            background: rgba(80, 200, 120, 0.12);
            color: #d8ffe5;
            border: 1px solid rgba(80, 200, 120, 0.35);
        }

        .back-home {
            display: block;
            text-align: center;
            margin-top: 12px;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left, .right {
                width: 100%;
            }

            .left {
                min-height: 220px;
            }
        }
    </style>
</head>
<body>
<div class="page-wrap">
    <div class="container">
        <div class="left"></div>

        <div class="right">
            <h2>Login</h2>
            <div class="subtext">Sign in to your Haizimen account</div>

            <?php if ($msg = flash_get('success')): ?>
                <div class="alert success"><?php echo e($msg); ?></div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert">
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo e($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="username" placeholder="Enter Username" required>
                <input type="password" name="password" placeholder="Enter Password" required>
                <button name="login">Login</button>
            </form>

            <p>Don't have an account? <a href="register.php">Register</a></p>
            <a class="back-home" href="index.php">Back to Home</a>
        </div>
    </div>
</div>
</body>
</html>