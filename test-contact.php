<?php
// Simple test file to verify PHP mail functionality
// This file can be deleted after testing

echo "<h2>PHP Mail Test</h2>";

// Check if mail function is available
if (function_exists('mail')) {
    echo "<p style='color: green;'>✓ PHP mail() function is available</p>";
    
    // Test basic mail functionality
    $to = 'diwakar.l@softcons.net';
    $subject = 'Test Email from Contact Form';
    $message = 'This is a test email to verify that the contact form can send emails.';
    $headers = 'From: test@' . $_SERVER['HTTP_HOST'];
    
    if (mail($to, $subject, $message, $headers)) {
        echo "<p style='color: green;'>✓ Test email sent successfully to $to</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to send test email. Check your server's mail configuration.</p>";
    }
} else {
    echo "<p style='color: red;'>✗ PHP mail() function is not available</p>";
}

// Check PHP version
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check if we can write to the directory
if (is_writable('.')) {
    echo "<p style='color: green;'>✓ Directory is writable</p>";
} else {
    echo "<p style='color: red;'>✗ Directory is not writable</p>";
}

echo "<hr>";
echo "<p><strong>Note:</strong> This test file should be deleted after testing for security reasons.</p>";
echo "<p><a href='contact.html'>← Back to Contact Form</a></p>";
?>
