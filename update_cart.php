<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];


    if ($quantity <= 0) {
        die("Invalid quantity. Please enter a positive number.");
    }

    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if (isset($_SESSION['cart'][$product_id])) {

        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

header("Location: cart.php");
exit;
