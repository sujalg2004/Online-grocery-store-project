<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - SG MART</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .about-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .about-title {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
        }
        .about-text {
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
        <div class="col-md-10 about-container">
            <h2 class="about-title text-center"><i class="fas fa-store"></i> Welcome to SG MART</h2>
            <p class="about-text text-center">
                Your one-stop online grocery store offering fast delivery, easy online payments, and fresh, quality products straight to your doorstep.
            </p>

            <hr>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="icon-box">
                        <i class="fas fa-shopping-cart"></i>
                        <h5>Shop Online</h5>
                        <p>Browse and add your favorite grocery items to your cart with just a few clicks.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="icon-box">
                        <i class="fas fa-credit-card"></i>
                        <h5>Secure Online Payment</h5>
                        <p>Pay easily and securely online using multiple payment options.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="icon-box">
                        <i class="fas fa-truck"></i>
                        <h5>Fast Home Delivery</h5>
                        <p>Enjoy fast and reliable delivery of your groceries right to your doorstep.</p>
                    </div>
                </div>
            </div>

            <hr>

            <h3 class="text-center mt-4">Why Choose SG MART?</h3>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><i class="fas fa-check-circle text-success"></i> Easy and convenient online shopping experience.</li>
                <li class="list-group-item"><i class="fas fa-check-circle text-success"></i> Multiple secure payment options.</li>
                <li class="list-group-item"><i class="fas fa-check-circle text-success"></i> Fast and safe home delivery service.</li>
                <li class="list-group-item"><i class="fas fa-check-circle text-success"></i> Fresh, high-quality grocery items at affordable prices.</li>
                <li class="list-group-item"><i class="fas fa-check-circle text-success"></i> 24/7 customer support and real-time order tracking.</li>
            </ul>

            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-success btn-lg"><i class="fas fa-cart-arrow-down"></i> Start Shopping Now</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
