<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Grocery Store</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="assets/css/styles.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        
        #searchResults {
            position: absolute;
            width: 100%;
            background: white;
            border: 1px solid #ddd;
            z-index: 1000;
            display: none;
        }
        .search-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
        }
        .search-item:hover {
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    
  
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
         
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/l3.png" alt="Logo" width="120">
            </a>

            <div class="input-group w-50 position-relative">
                <input type="text" id="searchBox" class="form-control" placeholder="Search for products..." autocomplete="off">
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-search"></i>
                </button>
                <div id="searchResults" class="list-group mt-1"></div>
            </div>

        
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])) { ?>
                 
                    <div class="dropdown me-3">
                        <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user"></i> My Profile</a></li>
                            <li><a class="dropdown-item" href="user_orders.php"><i class="fas fa-box"></i> Order History</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <a href="login.php" class="btn btn-light me-2">
                        <i class="fas fa-user"></i> Login
                    </a>
                    <a href="register.php" class="btn btn-primary">Register</a>
                <?php } ?>
                
             
                <a href="cart.php" class="btn btn-light ms-2">
                    <i class="fas fa-shopping-cart"></i> Cart 
                    (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)
                </a>
            </div>
        </div>
    </nav>

   
    <script>
        document.getElementById("searchBox").addEventListener("input", function() {
            let query = this.value.trim();
            let searchResults = document.getElementById("searchResults");

            if (query.length > 1) {
                fetch("search.php?q=" + query)
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() !== "") {
                            searchResults.innerHTML = data;
                            searchResults.style.display = "block";
                        } else {
                            searchResults.style.display = "none";
                        }
                    });
            } else {
                searchResults.style.display = "none";
            }
        });

        
        document.addEventListener("click", function(e) {
            if (!document.getElementById("searchBox").contains(e.target)) {
                document.getElementById("searchResults").style.display = "none";
            }
        });
    </script>

</body>
</html>
