<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com';
    $mail->Password = 'your_app_password';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('your_email@gmail.com', 'The Pearl Hotel');
    $mail->addAddress('recipient@gmail.com', 'Huwaidah');

    $mail->isHTML(true);
    $mail->Subject = 'Test Email - The Pearl Hotel';
    $mail->Body    = '<h3>Hello Huwaidah 💙</h3><p>This is a test email from The Pearl Hotel system.</p>';

    $mail->send();
    echo '✅ Email sent successfully!';
} catch (Exception $e) {
    echo "❌ Email not sent. Error: {$mail->ErrorInfo}";
}