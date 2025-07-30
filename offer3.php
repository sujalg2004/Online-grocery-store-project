<?php
require_once 'db.php';
include 'header.php';

$stmt = $pdo->prepare("SELECT * FROM products WHERE LOWER(category) = LOWER('Offers3')");
$stmt->execute();
$offerProducts = $stmt->fetchAll();
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
    .card-title {
        font-size: 1.1rem;
        min-height: 48px;
    }
    .product-image-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-image {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        padding: 10px;
    }
    .discount-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #ff4d4d;
        color: #fff;
        padding: 4px 8px;
        font-size: 0.85rem;
        font-weight: bold;
        border-radius: 4px;
        z-index: 1;
    }
    .price {
        font-size: 1.1rem;
    }
    .text-decoration-line-through {
        text-decoration: line-through;
    }
</style>

<div class="container mt-5">
    <h2 class="text-center mb-4 text-primary">Special Offers</h2>

    <?php if (empty($offerProducts)): ?>
        <p class='text-center text-danger'>No products found for 'Offers3'. Check database entries.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($offerProducts as $product): ?>
                <?php
                    $originalPrice = $product['price'];
                    $discount = $product['discount'];
                    $discountedPrice = $originalPrice;

                    if ($discount > 0) {
                        $discountedPrice = $originalPrice - ($originalPrice * $discount / 100);
                    }
                ?>
                <div class='col-md-4 mb-4'>
                    <div class='card product-card shadow-sm text-center'>
                        <div class="product-image-wrapper">
                            <?php if ($discount > 0): ?>
                                <div class="discount-badge"><?= $discount ?>% OFF</div>
                            <?php endif; ?>
                            <img src='assets/images/<?= $product['image'] ?>' class='product-image' alt='<?= htmlspecialchars($product['name']) ?>'>
                        </div>
                        <div class='card-body'>
                            <h5 class='card-title'><?= htmlspecialchars($product['name']) ?></h5>

                            <?php if ($discount > 0): ?>
                                <p class='card-text price mb-1'>
                                    <span class='text-muted text-decoration-line-through'>₹<?= number_format($originalPrice, 2) ?></span>
                                    <span class='text-success fw-bold ms-2'>₹<?= number_format($discountedPrice, 2) ?></span>
                                </p>
                            <?php else: ?>
                                <p class='card-text text-success fw-bold price'>₹<?= number_format($originalPrice, 2) ?></p>
                            <?php endif; ?>

                            <form method='POST' action='cart.php' class="mt-3">
                                <input type='hidden' name='product_id' value='<?= $product['id'] ?>'>
                                <button type='submit' class='btn btn-success w-100'>
                                    <i class='fas fa-shopping-cart'></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; <?= date("Y") ?> SG MART.com All Rights Reserved.</p>
</footer>
