<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'header.php';
?>

<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    .container {
        flex: 1;
    }
    footer {
        background-color: #343a40;
        color: white;
        text-align: center;
        padding: 15px 0;
        margin-top: auto;
    }
</style>

<div class="container my-5 text-center">
    <h2 class="text-success">Order Placed Successfully! <i class="fas fa-check-circle"></i></h2>
    <p class="lead">Thank you for your order.</p>
    <a href="index.php" class="btn btn-primary mt-3">Back to Shopping</a>
</div>


<footer>
    <p>&copy; <?php echo date("Y"); ?> SG MART.com All Rights Reserved.</p>
</footer>

</body>
</html>
