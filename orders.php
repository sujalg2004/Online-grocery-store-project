<?php
session_start();
require_once 'db.php'; 

$query = "SELECT orders.id, users.name AS username, users.email, users.mobile_number, users.gender, users.address, orders.total, orders.created_at 
          FROM orders 
          JOIN users ON orders.user_id = users.id 
          ORDER BY orders.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Admin Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            color: white;
            padding: 15px;
            position: fixed;
        }
        .sidebar h4 {
            font-size: 22px;
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .table img {
            border-radius: 5px;
        }
        .order-details {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="add_product.php"><i class="fas fa-plus"></i> Add Product</a>
        <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <h2 class="mb-4">Order History</h2>

        <?php foreach ($orders as $order) : ?>
            <div class="order-details p-3">
                <h5>Order ID: <?= $order['id'] ?> </h5>
                <p><strong>Customer:</strong> <?= htmlspecialchars($order['username']) ?> (<?= htmlspecialchars($order['email']) ?>)</p>
                <p><strong>Mobile Number:</strong> <?= htmlspecialchars($order['mobile_number']) ?></p>
                <p><strong>Gender:</strong> <?= htmlspecialchars($order['gender']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
                <p><strong>Total Amount:</strong> ₹<?= number_format($order['total'], 2) ?></p>
                <p><strong>Order Date:</strong> <?= $order['created_at'] ?></p>

                <?php
                $order_id = $order['id'];
                $stmt_items = $pdo->prepare("SELECT order_items.quantity, products.price, products.discount, products.name, products.image 
                                             FROM order_items 
                                             JOIN products ON order_items.product_id = products.id 
                                             WHERE order_items.order_id = ?");
                $stmt_items->execute([$order_id]);
                $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Image</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item) : ?>
                                <?php
                                    $original_price = $item['price'];
                                    $discount = $item['discount'];
                                    $discounted_price = $original_price - ($original_price * $discount / 100);
                                    $subtotal = $discounted_price * $item['quantity'];
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td><img src="assets/images/<?= htmlspecialchars($item['image']) ?>" width="50"></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>₹<?= number_format($discounted_price, 2) ?></td>
                                    <td>₹<?= number_format($subtotal, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
