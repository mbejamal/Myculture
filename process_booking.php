<?php
// Set email recipient
$recipient = "mjamaldeen91@gmail.com";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'] ?? '', FILTER_SANITIZE_STRING);
    $service = filter_var($_POST['service'] ?? '', FILTER_SANITIZE_STRING);
    $eventDate = filter_var($_POST['event_date'] ?? '', FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_STRING);
    
    // Validate data
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($service)) {
        $errors[] = "Service type is required";
    }
    
    // If there are no errors, send the email
    if (empty($errors)) {
        // Set email subject
        $subject = "New Booking Request: $service";
        
        // Build email content
        $email_content = "New Booking Request\n\n";
        $email_content .= "Name: $name\n";
        $email_content .= "Email: $email\n";
        $email_content .= "Phone: $phone\n";
        $email_content .= "Service: $service\n";
        $email_content .= "Event Date: $eventDate\n\n";
        $email_content .= "Message:\n$message\n";
        
        // Build email headers
        $email_headers = "From: $name <$email>";
        
        // Send the email
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a success message
            $success = "Thank you for your booking request! We will get back to you shortly.";
            
            // Redirect back to the form page with success parameter
            header("Location: index.html?booking=success");
            exit;
        } else {
            $errors[] = "Oops! Something went wrong and we couldn't send your message.";
            header("Location: index.html?booking=error");
            exit;
        }
    } else {
        // Redirect back to the form page with errors
        $error_string = implode(',', $errors);
        header("Location: index.html?booking=error&errors=" . urlencode($error_string));
        exit;
    }
} else {
    // Not a POST request, redirect to the form page
    header("Location: index.html");
    exit;
}
?>