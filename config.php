<?php
/**
 * Email Configuration
 *
 * IMPORTANT: You MUST edit these values before deploying!
 * Replace all placeholder values with your actual email credentials.
 */

return [
    // SMTP Settings
    'smtp_host' => 'smtp.gmail.com',           // Gmail SMTP host (change if using different provider)
    'smtp_port' => 587,                        // SMTP port (587 for TLS, 465 for SSL)
    'smtp_encryption' => 'tls',                // 'tls' or 'ssl'

    // Email Credentials
    'smtp_username' => 'YOUR_EMAIL@gmail.com', // CHANGE THIS: Your email address
    'smtp_password' => 'YOUR_APP_PASSWORD',    // CHANGE THIS: Your Gmail App Password (16 characters)

    // Sender Information
    'from_email' => 'YOUR_EMAIL@gmail.com',    // CHANGE THIS: Sender email (usually same as smtp_username)
    'from_name' => 'Монголын Гааль Систем',    // Sender display name

    // Receiver Email
    'to_email' => 'RECIPIENT@example.com',     // CHANGE THIS: Where to receive form submissions
];
