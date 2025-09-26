<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$phone = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validate required fields
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

if (empty($subject)) {
    $errors[] = 'Subject is required';
}

if (empty($phone)) {
    $errors[] = 'Phone number is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

// If there are validation errors, return them
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => 'Validation failed', 'errors' => $errors]);
    exit;
}

// Sanitize input data
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// Email configuration
$to = 'diwakar.l@softcons.net';
$email_subject = 'New Contact Form Submission: ' . $subject;
$from_email = 'noreply@' . $_SERVER['HTTP_HOST'];

// Create email content
$email_body = "
<html>
<head>
    <title>New Contact Form Submission</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #7f00ff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #555; }
        .value { margin-top: 5px; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>New Contact Form Submission</h2>
        </div>
        <div class='content'>
            <div class='field'>
                <div class='label'>Name:</div>
                <div class='value'>" . $name . "</div>
            </div>
            <div class='field'>
                <div class='label'>Email:</div>
                <div class='value'>" . $email . "</div>
            </div>
            <div class='field'>
                <div class='label'>Phone Number:</div>
                <div class='value'>" . $phone . "</div>
            </div>
            <div class='field'>
                <div class='label'>Subject:</div>
                <div class='value'>" . $subject . "</div>
            </div>
            <div class='field'>
                <div class='label'>Message:</div>
                <div class='value'>" . nl2br($message) . "</div>
            </div>
        </div>
        <div class='footer'>
            <p>This email was sent from the contact form on your website.</p>
            <p>Submitted on: " . date('Y-m-d H:i:s') . "</p>
        </div>
    </div>
</body>
</html>
";

// Email headers
$headers = [
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: ' . $from_email,
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion()
];

$headers_string = implode("\r\n", $headers);

// Send email
$mail_sent = mail($to, $email_subject, $email_body, $headers_string);

if ($mail_sent) {
    // Send auto-reply to the user
    $auto_reply_subject = 'Thank you for contacting us - ' . $subject;
    $auto_reply_body = "
    <html>
    <head>
        <title>Thank you for contacting us</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #7f00ff; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Thank You for Contacting Us!</h2>
            </div>
            <div class='content'>
                <p>Dear " . $name . ",</p>
                <p>Thank you for reaching out to us. We have received your message and will get back to you as soon as possible.</p>
                <p><strong>Your Message Details:</strong></p>
                <p><strong>Subject:</strong> " . $subject . "</p>
                <p><strong>Message:</strong> " . nl2br($message) . "</p>
                <p>We appreciate your interest in our services and look forward to speaking with you soon.</p>
                <p>Best regards,<br>The AiNext Team</p>
            </div>
            <div class='footer'>
                <p>This is an automated response. Please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $auto_reply_headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . $from_email,
        'X-Mailer: PHP/' . phpversion()
    ];
    
    mail($email, $auto_reply_subject, $auto_reply_body, implode("\r\n", $auto_reply_headers));
    
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully. We will get back to you soon.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your message. Please try again later.']);
}
?>
