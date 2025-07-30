<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - SG MART</title>
    

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .contact-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .contact-title {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
        }
        .contact-info {
            font-size: 18px;
            color: #555;
        }
        .icon-box {
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        .icon-box:hover {
            transform: scale(1.05);
        }
        .icon-box i {
            font-size: 40px;
            color: #28a745;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10 contact-container">
            <h2 class="contact-title text-center mb-3"><i class="fas fa-headset"></i> Contact SG MART</h2>
<p class="contact-info text-center">
    Have questions or need assistance? Our team is here to help you with any inquiries related to our products or services.
</p>


                <hr>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="icon-box">
                            <i class="fas fa-map-marker-alt"></i>
                            <h5>Our Location</h5>
                            <p>Adarsh Nagar, Delhi, India</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="icon-box">
                            <i class="fas fa-envelope"></i>
                            <h5>Email Us</h5>
                            <p>sujal@sgmart.in</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="icon-box">
                            <i class="fas fa-phone"></i>
                            <h5>Call Us</h5>
                            <p>+91 78270 63210</p>
                        </div>
                    </div>
                </div>

                <hr>

                <h3 class="text-center mt-4">Send Us a Message</h3>
                <form method="POST" action="send_message.php">
                    <div class="mb-3">
                        <label class="form-label">Your Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your Message</label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-paper-plane"></i> Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>

</body>
</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success text-center">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); 
}
?>
