<?php
require_once 'db.php';

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $query = "%" . $_GET['q'] . "%";
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? LIMIT 5");
    $stmt->execute([$query]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        foreach ($results as $row) {
            echo '<a href="product.php?id=' . $row['id'] . '" class="list-group-item list-group-item-action d-flex align-items-center">';
            echo '<img src="assets/images/' . htmlspecialchars($row['image']) . '" width="40" height="40" class="me-2">';
            echo '<div><strong>' . htmlspecialchars($row['name']) . '</strong><br>₹' . number_format($row['price'], 2) . '</div>';
            echo '</a>';
        }
    } else {
        echo '<div class="list-group-item">No results found</div>';
    }
}
?>
