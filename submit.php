<?php
header('Content-Type: application/json');

// Load configuration
$config = require 'config.php';

// Load Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (!isset($data['type']) || !isset($data['message'])) {
    echo json_encode(['success' => false, 'error' => 'Хүсэлтийн өгөгдөл дутуу байна']);
    exit;
}

$type = trim($data['type']);
$message = trim($data['message']);

// Validate type
$validTypes = ['Санал', 'Гомдол', 'Өргөдөл'];
if (!in_array($type, $validTypes)) {
    echo json_encode(['success' => false, 'error' => 'Төрөл буруу байна']);
    exit;
}

// Validate message
if (empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Мессеж хоосон байна']);
    exit;
}

// Create PHPMailer instance
$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_username'];
    $mail->Password = $config['smtp_password'];
    $mail->SMTPSecure = $config['smtp_encryption'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['smtp_port'];
    $mail->CharSet = 'UTF-8';

    // Recipients
    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($config['to_email']);

    // Email content
    $mail->isHTML(false);
    $mail->Subject = '[' . $type . '] Шинэ хүсэлт ирлээ';

    $emailBody = "Төрөл: " . $type . "\n";
    $emailBody .= "Огноо: " . date('Y-m-d H:i:s') . "\n";
    $emailBody .= "Мессеж:\n";
    $emailBody .= $message;

    $mail->Body = $emailBody;

    // Send email
    $mail->send();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log('Email error: ' . $mail->ErrorInfo);
    echo json_encode(['success' => false, 'error' => 'Имэйл илгээхэд алдаа гарлаа']);
}
?>
