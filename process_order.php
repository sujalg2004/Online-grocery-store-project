<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: checkout.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_price = $_POST['total_price'] ?? 0;
$address = "No Address Required"; 


$stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
$stmt->execute([$user_id]);

if ($stmt->rowCount() == 0) {
    die("Error: User ID does not exist in the database.");
}


try {
    
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, address) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $total_price, $address]);
    $order_id = $pdo->lastInsertId(); 
   
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

    foreach ($_SESSION['cart'] as $product_id => $item) {
        $stmt->execute([$order_id, $product_id, $item['quantity'], $item['price']]);
    }

   
    $pdo->commit();

    
    unset($_SESSION['cart']);

   
    header("Location: order_success.php");
    exit();
} catch (PDOException $e) {
  
    $pdo->rollBack();
    die("Error processing order: " . $e->getMessage());
}
?>
