<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

function sendOtpMail(string $toEmail, string $otp): array
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'fe17bf23dfd4f2';
        $mail->Password = '24faae40df09d4';
        $mail->Port = 2525;

        $mail->CharSet = 'UTF-8';
        $mail->setFrom('test@haizimen.com', 'Haizimen Center');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Haizimen Password Reset OTP';
        $mail->Body = "
            <div style='font-family:Arial,sans-serif;padding:20px;'>
                <h2 style='color:#1b6ec2;'>Password Reset OTP</h2>
                <p>Your OTP for resetting the password is:</p>
                <h1 style='color:#1b6ec2;letter-spacing:2px;'>$otp</h1>
                <p>This OTP is valid for 10 minutes.</p>
            </div>
        ";
        $mail->AltBody = "Your OTP for resetting the password is: $otp. This OTP is valid for 10 minutes.";

        $mail->send();

        return [
            'success' => true,
            'error' => ''
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $mail->ErrorInfo
        ];
    }
}