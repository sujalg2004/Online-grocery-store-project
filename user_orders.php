<?php
session_start();
include 'db.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT o.id AS order_id, o.total, o.address, o.created_at, 
               p.name AS product_name, p.price, p.discount, p.image, oi.quantity
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .order-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 25px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .order-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        .order-header {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            padding-bottom: 10px;
            border-bottom: 2px solid #ddd;
        }
        .order-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
        }
        .order-item img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 15px;
            border: 2px solid #ddd;
        }
        .order-details h6 {
            margin: 0;
            font-size: 17px;
            font-weight: 600;
            color: #333;
        }
        .order-details p {
            margin: 4px 0;
            font-size: 14px;
            color: #555;
        }
        .no-orders {
            text-align: center;
            padding: 40px;
            font-size: 20px;
            color: #999;
        }
        .badge-quantity {
            font-size: 13px;
            font-weight: bold;
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="order-container">
        <h3 class="text-center mb-4"><i class="fas fa-shopping-cart"></i> My Orders</h3>

        <?php if (count($orders) > 0) {
            $current_order = null;
            foreach ($orders as $order) {
                if ($current_order !== $order['order_id']) {
                    if ($current_order !== null) echo "</div>";
                    $current_order = $order['order_id'];
                    echo "<div class='order-card'>";
                    echo "<div class='order-header'><i class='fas fa-box'></i> Order ID: #" . $order['order_id'] . "</div>";
                    echo "<p class='mt-2'><strong>Total:</strong> ₹" . number_format($order['total'], 2) . "</p>";
                    echo "<p><strong>Placed On:</strong> " . date("d M Y, h:i A", strtotime($order['created_at'])) . "</p><hr>";
                }

                $price = $order['price'];
                $discount = $order['discount'];
                $quantity = $order['quantity'];

                $final_price = $discount > 0 ? ($price - ($price * $discount / 100)) : $price;
                $subtotal = $final_price * $quantity;
        ?>
            <div class="order-item">
                <img src="assets/images/<?php echo htmlspecialchars($order['image']); ?>" alt="<?php echo htmlspecialchars($order['product_name']); ?>">
                <div class="order-details">
                    <h6><?php echo htmlspecialchars($order['product_name']); ?></h6>
                    <p>
                        ₹<?php echo number_format($final_price, 2); ?>
                        <span class="badge badge-quantity">Qty: <?php echo $quantity; ?></span>
                    </p>
                    <p><strong>Subtotal:</strong> ₹<?php echo number_format($subtotal, 2); ?></p>
                </div>
            </div>
        <?php 
            } echo "</div>";
        } else { ?>
            <p class="no-orders"><i class="fas fa-exclamation-circle"></i> No orders found.</p>
        <?php } ?>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Shop</a>
        </div>
    </div>
</div>

</body>
</html>
