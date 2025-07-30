<?php
require_once 'db.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    try {
   
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        
        
        header("Location: admin_dashboard.php?message=Product deleted successfully");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {

    header("Location: admin_dashboard.php?error=Invalid product ID");
    exit();
}
?>
