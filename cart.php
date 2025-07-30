<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $stock_quantity = $product['quantity'];
        $original_price = $product['price'];
        $discount = $product['discount'] ?? 0;
        $discounted_price = $original_price - ($original_price * $discount / 100);

        $current_cart_qty = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id]['quantity'] : 0;

        if ($current_cart_qty + 1 <= $stock_quantity) {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'] ?? 'No Name',
                'price' => $discounted_price,
                'original_price' => $original_price,
                'discount' => $discount,
                'image' => $product['image'] ?? 'default.jpg',
                'quantity' => $current_cart_qty + 1
            ];
        } else {
            $_SESSION['cart_error'] = "Only $stock_quantity item(s) of '{$product['name']}' available in stock.";
        }
    }
}

if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $id => $quantity) {
        $stmt = $pdo->prepare("SELECT quantity FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $available_quantity = $product['quantity'];
            if ($quantity > 0 && $quantity <= $available_quantity) {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            } elseif ($quantity > $available_quantity) {
                $_SESSION['cart_error'] = "Only $available_quantity item(s) available for product ID: $id.";
            } else {
                unset($_SESSION['cart'][$id]); 
            }
        }
    }
}

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</head>
<body>

<?php include 'header.php'; ?>

<div class="container my-5">
    <h2 class="text-center mb-4">Your Shopping Cart <i class="fas fa-shopping-cart"></i></h2>

    <?php if (isset($_SESSION['cart_error'])): ?>
        <div class="alert alert-danger text-center">
            <?php 
                echo $_SESSION['cart_error']; 
                unset($_SESSION['cart_error']); 
            ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['cart'])) { ?>
        <form method="POST">
            <div class="table-responsive">
                <table class="table table-bordered shadow">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Original Price</th>
                            <th>Discount</th>
                            <th>Discounted Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php foreach ($_SESSION['cart'] as $id => $item) :
                            $name = $item['name'] ?? 'No Name';
                            $price = $item['price'] ?? 0;
                            $original_price = $item['original_price'] ?? $price;
                            $discount = $item['discount'] ?? 0;
                            $image = $item['image'] ?? 'default.jpg';
                            $quantity = $item['quantity'] ?? 1;
                            $total = $price * $quantity;
                            $total_price += $total;

                         
                            $stmt = $pdo->prepare("SELECT quantity FROM products WHERE id = ?");
                            $stmt->execute([$id]);
                            $stock = $stmt->fetch(PDO::FETCH_ASSOC)['quantity'] ?? 0;
                        ?>
                        <tr>
                            <td><img src="assets/images/<?php echo htmlspecialchars($image); ?>" width="80" height="80"></td>
                            <td><?php echo htmlspecialchars($name); ?></td>
                            <td>₹<?php echo number_format($original_price, 2); ?></td>
                            <td><?php echo $discount ?>%</td>
                            <td class="text-success fw-bold">₹<?php echo number_format($price, 2); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $id; ?>]" 
                                       value="<?php echo $quantity; ?>" 
                                       min="1" max="<?php echo $stock; ?>" 
                                       class="form-control w-50 mx-auto">
                                <small class="text-muted">In Stock: <?php echo $stock; ?></small>
                            </td>
                            <td>₹<?php echo number_format($total, 2); ?></td>
                            <td>
                                <a href="remove_from_cart.php?id=<?php echo $id; ?>" class="btn btn-danger btn-sm">Remove</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-end">
                <h4>Total Price: <strong class="text-success">₹<?php echo number_format($total_price, 2); ?></strong></h4>
                
                <a href="javascript:history.back()" class="btn btn-secondary">⬅ Back</a>
                <button type="submit" name="update_cart" class="btn btn-primary">Update Cart</button>
                <a href="checkout.php" class="btn btn-lg btn-success">Proceed to Checkout</a>
            </div>
        </form>

    <?php } else { ?>
        <div class="alert alert-warning text-center">
            <h4>Your Cart is Empty <i class="fas fa-exclamation-circle"></i></h4>
            <a href="index.php" class="btn btn-primary mt-3">Continue Shopping</a>
        </div>
    <?php } ?>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SG MART.com All Rights Reserved.</p>
</footer>

</body>
</html>
