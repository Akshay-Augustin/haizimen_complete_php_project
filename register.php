<?php
include("connect.php");
require_once 'app/Controllers/AuthController.php';

$errors = [];
if (isset($_POST['submit'])) {
    $controller = new AuthController($conn);
    $result = $controller->registerUser();

    if ($result['success']) {
        header('Location: login.php');
        exit;
    }

    $errors = $result['errors'] ?? ['Registration failed.'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register - Haizimen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            color: white;
            background:
                linear-gradient(rgba(8, 27, 45, 0.75), rgba(11, 28, 44, 0.85)),
                url('https://images.unsplash.com/photo-1516627145497-ae6968895b74?auto=format&fit=crop&w=1600&q=80') no-repeat center center fixed;
            background-size: cover;
        }

        .page-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }

        .container {
            width: 460px;
            max-width: 100%;
            background: rgba(16, 38, 61, 0.95);
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(4px);
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 8px;
            color: #ffffff;
        }

        .subtext {
            text-align: center;
            color: #dbe6f2;
            font-size: 14px;
            margin-bottom: 18px;
        }

        table {
            width: 100%;
        }

        td {
            padding: 6px 4px;
            font-size: 13px;
            vertical-align: top;
            color: #dbe6f2;
        }

        select,
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 9px 10px;
            border-radius: 6px;
            border: 1px solid #2c4966;
            font-size: 13px;
            box-sizing: border-box;
            background: #0b1c2c;
            color: white;
        }

        select {
            appearance: none;
        }

        input[type="file"] {
            padding: 7px;
        }

        input[type="radio"] {
            margin-right: 4px;
        }

        .btn {
            width: 100%;
            padding: 11px;
            background: #ff5a3c;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            margin-top: 8px;
        }

        .btn:hover {
            background: #e14a2f;
        }

        .login-link {
            text-align: center;
            font-size: 13px;
            margin-top: 12px;
            color: #dbe6f2;
        }

        a {
            text-decoration: none;
            color: #ff5a3c;
            font-weight: bold;
        }

        .alert {
            background: rgba(255, 90, 60, 0.12);
            color: #ffd2ca;
            border: 1px solid rgba(255, 90, 60, 0.35);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .back-home {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="page-wrap">
    <div class="container">
        <h2>Register</h2>
        <div class="subtext">Create your Haizimen account</div>

        <?php if (!empty($errors)): ?>
            <div class="alert">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Role</td>
                    <td>
                        <select name="role" required>
                            <option value="">Select Role</option>
                            <option value="parent" <?php echo old('role') === 'parent' ? 'selected' : ''; ?>>Parent</option>
                            <option value="doctor" <?php echo old('role') === 'doctor' ? 'selected' : ''; ?>>Doctor</option>
                            <option value="caretaker" <?php echo old('role') === 'caretaker' ? 'selected' : ''; ?>>Caretaker</option>
                            <option value="daycare" <?php echo old('role') === 'daycare' ? 'selected' : ''; ?>>Daycare</option>
                        </select>
                    </td>
                </tr>

                <tr><td>First Name</td><td><input type="text" name="firstname" value="<?php echo e(old('firstname')); ?>" required></td></tr>
                <tr><td>Last Name</td><td><input type="text" name="lastname" value="<?php echo e(old('lastname')); ?>" required></td></tr>

                <tr>
                    <td>Gender</td>
                    <td>
                        <input type="radio" name="gender" value="male" <?php echo old('gender') === 'male' ? 'checked' : ''; ?> required> Male
                        <input type="radio" name="gender" value="female" <?php echo old('gender') === 'female' ? 'checked' : ''; ?>> Female
                        <input type="radio" name="gender" value="others" <?php echo old('gender') === 'others' ? 'checked' : ''; ?>> Other
                    </td>
                </tr>

                <tr><td>DOB</td><td><input type="date" name="DOB" value="<?php echo e(old('DOB')); ?>" required></td></tr>
                <tr><td>Birth Certificate</td><td><input type="file" name="uploadcertificate"></td></tr>
                <tr><td>Blood Group</td><td><input type="text" name="bloodgroup" value="<?php echo e(old('bloodgroup')); ?>"></td></tr>
                <tr><td>Height</td><td><input type="text" name="height" value="<?php echo e(old('height')); ?>"></td></tr>
                <tr><td>Weight</td><td><input type="text" name="weight" value="<?php echo e(old('weight')); ?>"></td></tr>
                <tr><td>Mother Name</td><td><input type="text" name="mothername" value="<?php echo e(old('mothername')); ?>"></td></tr>
                <tr><td>Father Name</td><td><input type="text" name="fathername" value="<?php echo e(old('fathername')); ?>"></td></tr>
                <tr><td>Address</td><td><input type="text" name="address" value="<?php echo e(old('address')); ?>"></td></tr>
                <tr><td>Email</td><td><input type="email" name="emailid" value="<?php echo e(old('emailid')); ?>" required></td></tr>
                <tr><td>Phone</td><td><input type="text" name="phonenumber" value="<?php echo e(old('phonenumber')); ?>" placeholder="+91XXXXXXXXXX" required></td></tr>
                <tr><td>Username</td><td><input type="text" name="username" value="<?php echo e(old('username')); ?>" required></td></tr>
                <tr><td>Password</td><td><input type="password" name="password" required></td></tr>
                <tr><td colspan="2"><input type="submit" name="submit" value="Register" class="btn"></td></tr>
            </table>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </div>

        <a class="back-home" href="index.php">Back to Home</a>
    </div>
</div>
</body>
</html>