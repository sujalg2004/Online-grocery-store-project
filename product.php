<?php
require_once 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='container text-center my-5'><h4 class='text-danger'>Invalid product!</h4></div>";
    exit();
}

$product_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<div class='container text-center my-5'><h4 class='text-danger'>Product not found!</h4></div>";
    exit();
}

$description = isset($product['description']) ? $product['description'] : "No description available.";
$price = $product['price'];
$discount = isset($product['discount']) ? (float)$product['discount'] : 0;
$discounted_price = $discount > 0 ? $price - ($price * $discount / 100) : $price;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - Details</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .product-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .product-container:hover {
            transform: scale(1.02);
        }
        .product-img {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
        .product-title {
            font-size: 26px;
            font-weight: bold;
            color: #333;
        }
        .product-price {
            font-size: 22px;
            font-weight: bold;
        }
        .product-description {
            font-size: 16px;
            color: #555;
        }
        .btn-add-cart {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            transition: 0.3s ease;
        }
        .btn-add-cart:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .badge {
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 product-container">
                <div class="row">
                    <div class="col-md-5 text-center">
                        <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid product-img">
                    </div>

                    <div class="col-md-7">
                        <h2 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h2>

                        <?php if ($discount > 0): ?>
                            <p class="product-price text-danger">
                                <del>₹<?php echo number_format($price, 2); ?></del>
                                <span class="ms-2 text-success fw-bold">₹<?php echo number_format($discounted_price, 2); ?></span>
                                <span class="badge bg-warning text-dark ms-2"><?php echo $discount; ?>% OFF</span>
                            </p>
                        <?php else: ?>
                            <p class="product-price text-success">₹<?php echo number_format($price, 2); ?></p>
                        <?php endif; ?>

                        <p class="product-description"><?php echo htmlspecialchars($description); ?></p>

                        <form method="POST" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn btn-add-cart"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
