<?php
// Simple email test script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Email Configuration Test</h2>";

// Check PHP version
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";

// Check if mail function exists
if (function_exists('mail')) {
    echo "<p style='color: green;'>✓ PHP mail() function is available</p>";
} else {
    echo "<p style='color: red;'>✗ PHP mail() function is NOT available</p>";
    exit;
}

// Check mail configuration
$mail_config = ini_get('sendmail_path');
echo "<p><strong>Sendmail Path:</strong> " . ($mail_config ? $mail_config : 'Not set') . "</p>";

$smtp = ini_get('SMTP');
echo "<p><strong>SMTP:</strong> " . ($smtp ? $smtp : 'Not set') . "</p>";

$smtp_port = ini_get('smtp_port');
echo "<p><strong>SMTP Port:</strong> " . ($smtp_port ? $smtp_port : 'Not set') . "</p>";

// Test basic email sending
echo "<h3>Testing Email Sending</h3>";

$to = 'diwakar.l@softcons.net';
$subject = 'Test Email from ' . $_SERVER['HTTP_HOST'];
$message = 'This is a test email to verify email functionality.';
$headers = 'From: test@' . $_SERVER['HTTP_HOST'] . "\r\n" .
           'Reply-To: test@' . $_SERVER['HTTP_HOST'] . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

echo "<p>Attempting to send email to: <strong>$to</strong></p>";
echo "<p>From: test@" . $_SERVER['HTTP_HOST'] . "</p>";

$result = mail($to, $subject, $message, $headers);

if ($result) {
    echo "<p style='color: green;'>✓ Email sent successfully!</p>";
    echo "<p>Please check the inbox for <strong>$to</strong></p>";
} else {
    echo "<p style='color: red;'>✗ Failed to send email</p>";
    
    // Get last error
    $last_error = error_get_last();
    if ($last_error) {
        echo "<p><strong>Last Error:</strong> " . $last_error['message'] . "</p>";
    }
    
    // Check common issues
    echo "<h4>Common Issues:</h4>";
    echo "<ul>";
    echo "<li>Server may not have mail server configured</li>";
    echo "<li>SMTP settings may be incorrect</li>";
    echo "<li>Firewall may be blocking outbound email</li>";
    echo "<li>Email may be going to spam folder</li>";
    echo "</ul>";
}

// Test with different headers
echo "<h3>Testing with HTML Email</h3>";

$html_message = "
<html>
<head><title>Test HTML Email</title></head>
<body>
    <h2>Test HTML Email</h2>
    <p>This is a test HTML email.</p>
    <p><strong>Time:</strong> " . date('Y-m-d H:i:s') . "</p>
    <p><strong>Server:</strong> " . $_SERVER['HTTP_HOST'] . "</p>
</body>
</html>
";

$html_headers = 'MIME-Version: 1.0' . "\r\n" .
                'Content-type: text/html; charset=UTF-8' . "\r\n" .
                'From: test@' . $_SERVER['HTTP_HOST'] . "\r\n" .
                'Reply-To: test@' . $_SERVER['HTTP_HOST'] . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

$html_result = mail($to, 'Test HTML Email', $html_message, $html_headers);

if ($html_result) {
    echo "<p style='color: green;'>✓ HTML email sent successfully!</p>";
} else {
    echo "<p style='color: red;'>✗ Failed to send HTML email</p>";
}

echo "<hr>";
echo "<p><a href='contact.html'>← Back to Contact Form</a> | <a href='debug-contact.html'>Debug Page</a></p>";
?>
