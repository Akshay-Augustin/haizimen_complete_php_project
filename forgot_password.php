<?php
include("connect.php");
require_once "app/Helpers/helpers.php";
require_once "app/Helpers/mailer.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    if ($username === '') {
        $errors[] = "Enter username or email.";
    } else {
        $stmt = $conn->prepare("SELECT id, email FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $errors[] = "User not found.";
        } else {
            $user = $result->fetch_assoc();
            $otp = (string) rand(100000, 999999);

            $_SESSION['reset_user_id'] = $user['id'];
            $_SESSION['reset_user_email'] = $user['email'];
            $_SESSION['reset_otp'] = $otp;
            $_SESSION['reset_otp_expires'] = time() + 600; // 10 mins

            $mailResult = sendOtpMail($user['email'], $otp);

            if ($mailResult['success']) {
                header("Location: verify_otp.php");
                exit;
            } else {
                $errors[] = "Failed to send OTP email: " . $mailResult['error'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Forgot Password</title>
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
    <h2>Forgot Password</h2>
    <div class="subtext">Enter your username or email to receive OTP</div>

    <?php foreach ($errors as $e): ?>
        <div class="alert"><?php echo e($e); ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username or Email" required>
        <button type="submit">Send OTP</button>
    </form>

    <a href="login.php">← Back to Login</a>
</div>

</body>
</html>