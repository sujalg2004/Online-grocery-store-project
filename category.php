<?php
require_once 'db.php';
include 'header.php';

if (!isset($_GET['category'])) {
    echo "<div class='container mt-5'><h3 class='text-danger'>Category not found!</h3></div>";
    include 'footer.php';
    exit();
}

$category = $_GET['category'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
$stmt->execute([$category]);
$products = $stmt->fetchAll();
?>

<style>
    .product-image-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
        border-radius: 10px;
    }

    .product-image {
        height: 100%;
        width: 100%;
        object-fit: contain;
        padding: 10px;
        border-radius: 10px;
    }

    .discount-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background-color: #ff3e3e;
        color: white;
        padding: 3px 7px;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 4px;
        z-index: 10;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        min-height: 48px;
    }

    .card-text .text-success {
        font-weight: bold;
    }
</style>

<div class="container mt-5">
    <h2 class="text-center text-primary"><?php echo htmlspecialchars($category); ?></h2>
    <div class="row">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <?php
                    $original_price = $product['price'];
                    $discount = $product['discount'] ?? 0;
                    $discounted_price = $original_price - ($original_price * $discount / 100);
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg border-0 text-center">
                        <div class="product-image-wrapper">
                            <?php if ($discount > 0): ?>
                                <div class="discount-badge"><?php echo $discount; ?>% OFF</div>
                            <?php endif; ?>
                            <a href="product.php?id=<?php echo $product['id']; ?>">
                                <img src="assets/images/<?php echo $product['image']; ?>" 
                                     class="product-image" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-dark"><?php echo htmlspecialchars($product['name']); ?></h5>
                            
                            <?php if ($discount > 0): ?>
                                <p class="card-text">
                                    <span class="text-muted text-decoration-line-through">₹<?php echo number_format($original_price, 2); ?></span>
                                    <span class="text-success ms-2">₹<?php echo number_format($discounted_price, 2); ?></span>
                                </p>
                            <?php else: ?>
                                <p class="card-text text-success">₹<?php echo number_format($original_price, 2); ?></p>
                            <?php endif; ?>

                            <form action="cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-danger">No products found in this category.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
