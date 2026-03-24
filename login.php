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
    min-height: 100vh;
    color: #1b2b3a;
    background:
        linear-gradient(rgba(234, 244, 255, 0.9), rgba(214, 236, 255, 0.95)),
        url('https://images.unsplash.com/photo-1519340241574-2cec6aef0c01?auto=format&fit=crop&w=900&q=80') no-repeat center/cover;
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
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* LEFT IMAGE */
.left {
    width: 50%;
    background:
        linear-gradient(rgba(255,255,255,0.2), rgba(255,255,255,0.3)),
        url('https://images.unsplash.com/photo-1516627145497-ae6968895b74?auto=format&fit=crop&w=900&q=80') no-repeat center/cover;
    min-height: 520px;
}

/* RIGHT FORM */
.right {
    width: 50%;
    padding: 40px 32px;
}

h2 {
    text-align: center;
    margin-bottom: 8px;
    color: #1b6ec2;
    font-size: 28px;
}

.subtext {
    text-align: center;
    color: #4a5c6b;
    margin-bottom: 22px;
    font-size: 14px;
}

/* INPUTS */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #cfe3f7;
    border-radius: 6px;
    box-sizing: border-box;
    background: #f4f9ff;
    color: #1b2b3a;
}

input::placeholder {
    color: #6c7c8c;
}

/* BUTTON */
button {
    width: 100%;
    padding: 12px;
    background: #1b6ec2;
    border: none;
    color: white;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    margin-top: 10px;
}

button:hover {
    background: #155a9c;
}

/* LINKS */
p {
    text-align: center;
    color: #4a5c6b;
}

a {
    color: #1b6ec2;
    text-decoration: none;
    font-weight: bold;
}

/* ALERTS */
.alert {
    background: #ffecec;
    color: #a94442;
    border: 1px solid #f5c6cb;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.success {
    background: #e8fff1;
    color: #1e6c35;
    border: 1px solid #aad7b7;
}

.back-home {
    display: block;
    text-align: center;
    margin-top: 12px;
    font-size: 13px;
}

/* MOBILE */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
    }

    .left, .right {
        width: 100%;
    }

    .left {
        min-height: 200px;
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