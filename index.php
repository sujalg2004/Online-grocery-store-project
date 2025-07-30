<?php
require_once 'db.php';
include 'header.php';
?>
<style>
    .category-section {
        background-color: #f8f9fa;
        padding: 40px 0;
        border-radius: 10px;
    }

    .category-item {
        background: #ffffff;
        border-radius: 10px;
        padding: 10px;
        transition: 0.3s;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }

    .category-item img {
        width: 100%;
        height: 120px;
        object-fit: contain;
        border-radius: 8px;
    }

    .category-item p {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        margin-top: 10px;
    }

    .category-item:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
    }

    .product-image-wrapper {
    position: relative;
    height: 220px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
}

.product-image {
    width: 100%;
    max-width: 180px;
    max-height: 180px;
    object-fit: contain;
    object-position: center;
}


    .discount-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #dc3545;
        color: white;
        font-size: 0.85rem;
        padding: 5px 10px;
        font-weight: bold;
        border-radius: 5px;
        z-index: 2;
    }
</style>

<div id="offerCarousel" class="carousel slide banner" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <a href="offer1.php?offer=1">
                <img src="assets/images/f4.webp" class="d-block w-100" alt="Offer 1">
            </a>
        </div>
        <div class="carousel-item">
            <a href="offer2.php?offer=2">
                <img src="assets/images/f5.webp" class="d-block w-100" alt="Offer 2">
            </a>
        </div>
        <div class="carousel-item">
            <a href="offer3.php?offer=3">
                <img src="assets/images/as1.webp" class="d-block w-100" alt="Offer 3">
            </a>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#offerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#offerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<section class="category-section mt-5">
    <div class="container">
        <h2 class="text-center mb-4">Shop By Categories</h2>
        <div class="row">
            <?php
            $categories = [
                ["name" => "Atta, Rice & Dals", "image" => "assets/images/s1.webp"],
                ["name" => "Breakfast, Dips & Spreads", "image" => "assets/images/s2.webp"],
                ["name" => "Masalas, Oils & Dry Fruits", "image" => "assets/images/s3.webp"],
                ["name" => "Chips, Biscuits & Namkeens", "image" => "assets/images/s4.webp"],
                ["name" => "Hot & Cold Beverages", "image" => "assets/images/s5.webp"],
                ["name" => "Instant & Frozen Foods", "image" => "assets/images/s6.webp"]
            ];

            foreach ($categories as $category) {
                echo "
                <div class='col-md-2'>
                    <div class='category-item text-center'>
                        <a href='category.php?category=" . urlencode($category['name']) . "'>
                            <img src='{$category['image']}' width='100%' alt='{$category['name']}'>
                        </a>
                        <p>{$category['name']}</p>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
</section>

<section class="featured-products mt-5">
    <div class="container">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row">
            <?php
            $stmt = $pdo->query("SELECT * FROM products ORDER BY RAND() LIMIT 6");
            $products = $stmt->fetchAll();

            if (count($products) > 0) {
                foreach ($products as $product) {
                    $original_price = $product['price'];
                    $discount = $product['discount'] ?? 0;
                    $discounted_price = $original_price - ($original_price * $discount / 100);

                    echo "
                    <div class='col-md-4 mb-4'>
                        <div class='card product-card shadow-sm text-center'>
                            <div class='product-image-wrapper'>
                                <a href='product.php?id={$product['id']}'>
                                    <img src='assets/images/{$product['image']}' class='product-image p-3' alt='{$product['name']}'>
                                </a>";
                    if ($discount > 0) {
                        echo "<div class='discount-badge'>{$discount}% OFF</div>";
                    }
                    echo "</div>
                            <div class='card-body'>
                                <h5 class='card-title'>
                                    <a href='product_detail.php?id={$product['id']}' class='text-decoration-none text-dark'>
                                        {$product['name']}
                                    </a>
                                </h5>";

                    if ($discount > 0) {
                        echo "
                                <p class='card-text'>
                                    <span class='text-muted text-decoration-line-through'>₹" . number_format($original_price, 2) . "</span>
                                    <span class='text-success fw-bold ms-2'>₹" . number_format($discounted_price, 2) . "</span>
                                </p>";
                    } else {
                        echo "<p class='card-text text-success'><strong>₹" . number_format($original_price, 2) . "</strong></p>";
                    }

                    echo "
                                <form method='POST' action='cart.php'>
                                    <input type='hidden' name='product_id' value='{$product['id']}'>
                                    <button type='submit' class='btn btn-success w-100'>
                                        <i class='fas fa-shopping-cart'></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p class='text-center text-danger'>No featured products available.</p>";
            }
            ?>
        </div>
    </div>
</section>

<?php
include 'footer.php';
?>
