<?php
// --- PHP LOGIC FIRST ---
if (session_status() === PHP_SESSION_NONE) { session_start(); }

include __DIR__ . '/../../config/db_connect.php';

$message_sent = false;
$error_message = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Simple validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // In a real project, this is where you would send the email.
        // Example using PHP's mail() function (requires server configuration):
        // $to = "your-email@your-domain.com";
        // $headers = "From: " . $email;
        // mail($to, $subject, $message, $headers);

        // For this example, we'll just set a success flag.
        $message_sent = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Greeny</title>
    
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/includes_css/header.css">
    <link rel="stylesheet" href="../../css/pages_css/contact/contact.css">
</head>
<body>

    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <div class="container-contact">
        <div class="contact-card">
            <div class="title-section">
                <h1>Contact Us</h1>
                <p>We'd love to hear from you! Drop us a line below.</p>
            </div>

            <div class="content-grid">
                <div class="form-column">
                    <h2>Send us a Message</h2>

                    <?php if ($message_sent): ?>
                        <div class="success-message">
                            Thank you for your message! We'll get back to you shortly.
                        </div>
                    <?php else: ?>
                        <?php if (!empty($error_message)): ?>
                            <div class="error-message"><?php echo $error_message; ?></div>
                        <?php endif; ?>

                        <form action="contact.php" method="POST" class="contact-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Your Name *</label>
                                    <input type="text" id="name" name="name" placeholder="Ex. John Doe" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject *</label>
                                <input type="text" id="subject" name="subject" placeholder="Enter Subject" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Your Message *</label>
                                <textarea id="message" name="message" rows="5" placeholder="Enter here..." required></textarea>
                            </div>
                            <div>
                                <button type="submit" class="submit-btn">Send Message</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="info-column">
                    <h2>Get in Touch!</h2>
                    <p>Find us at our store or contact us directly. We're here to help with all your grocery needs.</p>
                    <ul class="info-list">
                        <li>
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <div class="info-details">
                                <h3>Phone</h3>
                                <a href="tel:+911234567890">+91 (123) 456-7890</a>
                            </div>
                        </li>
                        <li>
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="info-details">
                                <h3>Email</h3>
                                <a href="mailto:contact@greeny.com">contact@greeny.com</a>
                            </div>
                        </li>
                        <li>
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <div class="info-details">
                                <h3>Address</h3>
                                <p>123 Grocery Lane, Adajan,<br>Surat, Gujarat 395009</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="map-section">
             <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d238132.2281857938!2d72.6841379492383!3d21.15914251761623!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be04e59411d1563%3A0xfe4558290938b042!2sSurat%2C%20Gujarat%2C%20India!5e0!3m2!1sen!2sus!4v1692861598123!5m2!1sen!2sus" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

    <?php
    // 5. Close the database connection and include the footer
    $conn->close();
    include __DIR__ . '/../../includes/footer.php'; // You can create and include a footer later
    ?>

</body>
</html>