<?php
include("connect.php");
require_once "app/Helpers/helpers.php";

$errors = [];

if (
    !isset($_SESSION['reset_user_id']) ||
    !isset($_SESSION['otp_verified']) ||
    $_SESSION['otp_verified'] !== true
) {
    header("Location: forgot_password.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $_SESSION['reset_user_id']);
        $stmt->execute();

        unset($_SESSION['reset_user_id']);
        unset($_SESSION['reset_user_email']);
        unset($_SESSION['reset_otp']);
        unset($_SESSION['reset_otp_expires']);
        unset($_SESSION['otp_verified']);

        flash_set('success', 'Password updated successfully. Please login.');
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
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
    <h2>Reset Password</h2>
    <div class="subtext">Enter your new password</div>

    <?php foreach ($errors as $e): ?>
        <div class="alert"><?php echo e($e); ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Update Password</button>
    </form>

    <a href="login.php">← Back to Login</a>
</div>

</body>
</html>