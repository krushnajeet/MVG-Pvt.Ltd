<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEmail = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $userMessage = trim($_POST['message']);

    if (!$userEmail) {
        echo "Invalid email address.";
        exit;
    }

    if (empty($userMessage)) {
        echo "Message cannot be empty.";
        exit;
    }

    $to = "Mahashtravikasgroup111@gmail.com";  // Replace with your actual admin email
    $subject = "New Contact Message from MVG Pvt.Ltd";
    $headers = "From: " . $userEmail . "\r\n" .
               "Reply-To: " . $userEmail . "\r\n" .
               "Content-Type: text/plain; charset=UTF-8\r\n";

    $body = "You have received a new message from the MVG Pvt.Ltd contact form.\n\n";
    $body .= "Email: $userEmail\n\n";
    $body .= "Message:\n$userMessage\n";

    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Message sent successfully! Thank you for contacting us.');window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Sorry, there was an error sending your message. Please try again later.');window.location.href='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit;
}
?>
