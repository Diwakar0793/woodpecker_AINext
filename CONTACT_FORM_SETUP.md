# Contact Form Setup Instructions

## Overview
The contact form has been successfully configured to send inquiries to `diwakar.l@softcons.net`. The form includes client-side and server-side validation, email notifications, and user feedback.

## Files Modified/Created

### 1. `contact-handler.php` (NEW)
- PHP script that processes form submissions
- Validates all form fields
- Sends HTML email to `diwakar.l@softcons.net`
- Sends auto-reply to the user
- Returns JSON responses for AJAX handling

### 2. `assets/js/ainext.js` (MODIFIED)
- Added contact form submission handler
- Client-side validation
- AJAX form submission
- Error handling and user notifications
- Loading states and form reset

### 3. `assets/css/style.css` (MODIFIED)
- Added error state styles for form fields
- Notification styles for success/error messages
- Loading button states

### 4. `test-contact.php` (NEW - FOR TESTING ONLY)
- Simple test file to verify PHP mail functionality
- **IMPORTANT: Delete this file after testing for security**

## Features

### Form Validation
- **Client-side validation**: Real-time validation as user types
- **Server-side validation**: Double validation on the server
- **Required fields**: Name, Email, Subject, Phone, Message
- **Email format validation**: Proper email format checking
- **Error highlighting**: Visual feedback for invalid fields

### Email Functionality
- **Main notification**: Sends formatted HTML email to `diwakar.l@softcons.net`
- **Auto-reply**: Sends confirmation email to the user
- **Professional formatting**: Clean, branded email templates
- **Security**: Input sanitization and validation

### User Experience
- **Loading states**: Button shows "Sending..." during submission
- **Success notifications**: Green notification on successful submission
- **Error notifications**: Red notification for errors
- **Form reset**: Clears form after successful submission
- **Real-time feedback**: Errors clear when user starts typing

## Setup Requirements

### 1. Web Server with PHP
- PHP 7.0 or higher
- `mail()` function enabled
- Web server (Apache, Nginx, etc.)

### 2. Email Configuration
The form uses PHP's built-in `mail()` function. For production use, consider:
- Configuring SMTP settings
- Using a service like PHPMailer for better deliverability
- Setting up proper SPF/DKIM records

### 3. File Permissions
Ensure the web server can read all files and execute PHP scripts.

## Testing

### 1. Test PHP Mail Function
1. Visit `test-contact.php` in your browser
2. Check if the test email is sent successfully
3. **Delete `test-contact.php` after testing**

### 2. Test Contact Form
1. Open `contact.html` in your browser
2. Fill out the form with test data
3. Submit the form
4. Check for success notification
5. Verify email is received at `diwakar.l@softcons.net`

## Customization

### Change Email Address
Edit `contact-handler.php` line 45:
```php
$to = 'your-email@example.com';
```

### Modify Email Templates
Edit the `$email_body` and `$auto_reply_body` variables in `contact-handler.php` to customize email content and styling.

### Add Additional Fields
1. Add HTML input fields to `contact.html`
2. Update the `formData` object in `ainext.js`
3. Add validation in both JavaScript and PHP
4. Update the email template in `contact-handler.php`

## Security Notes

- All input is sanitized using `htmlspecialchars()`
- Email addresses are validated using `filter_var()`
- CSRF protection can be added if needed
- Consider rate limiting for production use

## Troubleshooting

### Common Issues

1. **"Method not allowed" error**
   - Ensure the form is submitting via POST
   - Check that `contact-handler.php` is accessible

2. **Email not sending**
   - Verify PHP `mail()` function is enabled
   - Check server mail configuration
   - Test with `test-contact.php`

3. **Form not submitting**
   - Check browser console for JavaScript errors
   - Ensure jQuery is loaded
   - Verify form ID matches JavaScript selector

4. **Validation errors not showing**
   - Check that CSS is loaded
   - Verify JavaScript is running
   - Check for console errors

### Debug Mode
To enable debug mode, add this to the top of `contact-handler.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Support
For technical support or customization requests, contact the development team.
