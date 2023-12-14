<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

// Function to send an email notification
function sendEmailNotification($recipientEmail)
{
    // Get user's IP address
    $userIpAddress = $_SERVER['REMOTE_ADDR'];

    // Use ipinfo.io to get location details based on the IP address
    if ($userIpAddress == '::1' || $userIpAddress == '127.0.0.1') {
        $userLocation = 'Localhost';
    } else {
        // Retrieve the API key from an environment variable
        $ipInfoApiKey = getenv('IPINFO_API_KEY');
        var_dump(getenv('IPINFO_API_KEY'));


        $ipInfoUrl = "http://ipinfo.io/{$userIpAddress}?token={$ipInfoApiKey}";
        $ipInfoJson = file_get_contents($ipInfoUrl);
        $ipInfo = json_decode($ipInfoJson, true);

        // Extract relevant information from the ipinfo.io response
        $userLocation = $ipInfo['city'] . ', ' . $ipInfo['region'] . ', ' . $ipInfo['country'];
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ojt.rms.group.4@gmail.com';
        $mail->Password   = 'hbpezpowjedwoctl';
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587; // TCP port to connect to

        $mail->setFrom('ojt.rms.group.4@gmail.com', 'Library System');
        $mail->addAddress($recipientEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Successful Login Notification';
        $mail->Body = "
    <html>
    <head>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f5f5f5;
                color: #333;
            }
            .container {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            h2 {
                color: #007bff;
            }
            p {
                line-height: 1.6;
            }
            .note {
                color: #666;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Hello, {$recipientEmail}</h2>
            <p>You have successfully logged in to your account.</p>
            <p><strong>Location:</strong> {$userLocation}</p>
            <p><strong>IP Address:</strong> {$userIpAddress}</p>
            <p>This email serves as a notification that your account was accessed from the following location and IP address. If you did not initiate this login, please contact support immediately.</p>
        
        </div>
    </body>
    </html>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}

// Get recipient email from POST data
if (isset($_POST['email'])) {
    $recipientEmail = $_POST['email'];

    // Send email notification
    $result = sendEmailNotification($recipientEmail);

    if ($result === true) {
        echo 'Email notification sent successfully';
    } else {
        echo 'Error: ' . $result;
    }
} else {
    echo 'Error: Email address not provided';
}
