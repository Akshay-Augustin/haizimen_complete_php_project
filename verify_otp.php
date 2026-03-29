<?php
include("connect.php");
require_once "app/Helpers/helpers.php";

$errors = [];

if (!isset($_SESSION['reset_user_id'], $_SESSION['reset_otp'], $_SESSION['reset_otp_expires'])) {
    header("Location: forgot_password.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp'] ?? '');

    if ($otp === '') {
        $errors[] = "Enter OTP.";
    } elseif (time() > $_SESSION['reset_otp_expires']) {
        $errors[] = "OTP expired. Please request a new one.";
    } elseif ($otp !== $_SESSION['reset_otp']) {
        $errors[] = "Invalid OTP.";
    } else {
        $_SESSION['otp_verified'] = true;
        header("Location: reset_password.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Verify OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f9ff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 400px;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            color: #1b6ec2;
            margin-bottom: 8px;
        }

        .subtext {
            text-align: center;
            color: #4a5c6b;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #cfe3f7;
            background: #f4f9ff;
            margin-bottom: 12px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
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
            background: #ffecec;
            color: #a94442;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #1b6ec2;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Verify OTP</h2>
    <div class="subtext">Enter the OTP sent to <?php echo e($_SESSION['reset_user_email']); ?></div>

    <?php foreach ($errors as $e): ?>
        <div class="alert"><?php echo e($e); ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <input type="text" name="otp" placeholder="Enter 6-digit OTP" required>
        <button type="submit">Verify OTP</button>
    </form>

    <a href="forgot_password.php">← Back</a>
</div>

</body>
</html>