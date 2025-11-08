<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';
require './vendor/phpmailer/phpmailer/src/Exception.php';

// Get order details from GET parameters
$orderId = $_GET['order_id'] ?? 'XXXXXX';
$customerEmail = $_GET['email'] ?? 'your.email@example.com';

// Prepare email content
$subject = "Order Confirmation - RupeshMart";
$body = "
    <h2>Order Confirmed!</h2>
    <p>Your order <strong>ORD-$orderId</strong> has been placed successfully.</p>
    <p>Thank you for shopping with us!</p>
    <hr>
    <p>If you have questions,please feel free to contact Rupesh </p>
";

$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // SMTP server (use yours)
    $mail->SMTPAuth = true;
    $mail->Username = 'rupeshkumardash1@gmail.com';        // Your SMTP username
    $mail->Password = 'wcwb dchz ehxi raxs';              // Your SMTP password or app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Encryption (TLS)
    $mail->Port = 587;

    // Sender and recipient info
    $mail->setFrom('rupeshkumardash1@gmail.com', 'RupeshMart');
    $mail->addAddress($customerEmail);                        // Recipient from billing email

    // Email content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->send();
    

} catch (Exception $e) {
    // Optionally log errors (don't show to customer)
    // file_put_contents('mail-errors.log', $mail->ErrorInfo . "\n", FILE_APPEND);
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed! - YourStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg my-5 text-center border-0">
                    <div class="card-body p-5">
                        <div class="success-icon mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check-circle-fill text-success" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </div>
                        
                        <h1 class="card-title display-5">Thank You! 🎉</h1>
                        <p class="lead fw-normal">Your order has been placed successfully.</p>
                        
                        <hr class="my-4">
                        
                        <p class="text-muted">
                            A confirmation email has been sent to <strong id="customer-email"><?= htmlspecialchars($customerEmail) ?></strong>.
                        </p>
                        
                        <div class="alert alert-light mt-4" role="alert">
                           <strong>Your Order ID:</strong> <span id="order-id" class="fw-bold">ORD-<?= htmlspecialchars($orderId) ?></span>
                        </div>
                        
                        <a href="./Shopping.php" class="btn btn-primary btn-lg mt-4">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>