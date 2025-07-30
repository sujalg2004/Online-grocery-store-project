<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; 

    if ($role === 'admin' && $email === 'admin@gmail.com' && $password === 'admin@123') {
        
        $_SESSION['user_id'] = 1; 
        $_SESSION['user_name'] = 'Admin';
        $_SESSION['role'] = 'admin';

        header("Location: admin_dashboard.php"); 
        exit();
    } else {
       
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = isset($user['full_name']) ? $user['full_name'] : $user['name'];
            $_SESSION['role'] = 'user';

            header("Location: index.php"); 
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body.auth-bg {
            background: #f8f9fa;
        }
        .card {
            border-radius: 15px;
            border: none;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
        .text-center a {
            color: #007bff;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="auth-bg">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 400px;">
            <h3 class="text-center mb-3">Login</h3>
            
            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-envelope"></i> Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock"></i> Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user-tag"></i> Login as:</label>
                    <select name="role" class="form-select" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <button type="button" class="btn btn-secondary w-100 mt-2" onclick="history.back()">Back</button>
                <p class="mt-3 text-center">
                    Don't have an account? <a href="register.php">Register</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
