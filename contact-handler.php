<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Log function for debugging
function log_debug($message) {
    $log_file = 'contact_debug.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND | LOCK_EX);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    log_debug("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

log_debug("Contact form submission started");

// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$phone = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Log received data
log_debug("Received form data - Name: $name, Email: $email, Subject: $subject, Phone: $phone, Message: " . substr($message, 0, 50) . "...");

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
    log_debug("Validation errors: " . implode(', ', $errors));
    echo json_encode(['success' => false, 'message' => 'Validation failed', 'errors' => $errors]);
    exit;
}

log_debug("Validation passed, proceeding with email sending");

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

// Check if mail function is available
if (!function_exists('mail')) {
    log_debug("ERROR: PHP mail() function is not available");
    echo json_encode(['success' => false, 'message' => 'Email service is not configured on this server. Please contact the administrator.']);
    exit;
}

log_debug("Email configuration - To: $to, From: $from_email, Subject: $email_subject");

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

log_debug("Attempting to send email to: $to");

// Send email
$mail_sent = mail($to, $email_subject, $email_body, $headers_string);

log_debug("Mail function returned: " . ($mail_sent ? 'TRUE' : 'FALSE'));

if ($mail_sent) {
    log_debug("Main email sent successfully to: $to");
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
    
    $auto_reply_sent = mail($email, $auto_reply_subject, $auto_reply_body, implode("\r\n", $auto_reply_headers));
    log_debug("Auto-reply sent: " . ($auto_reply_sent ? 'TRUE' : 'FALSE'));
    
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully. We will get back to you soon.']);
} else {
    log_debug("ERROR: Failed to send main email");
    
    // Get the last error
    $last_error = error_get_last();
    $error_message = 'Sorry, there was an error sending your message. Please try again later.';
    
    if ($last_error) {
        log_debug("Last PHP error: " . $last_error['message']);
        $error_message .= ' Error details: ' . $last_error['message'];
    }
    
    echo json_encode(['success' => false, 'message' => $error_message]);
}
?>
