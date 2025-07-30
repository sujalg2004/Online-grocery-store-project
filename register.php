<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $mobile_number = $_POST['mobile_number'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];


    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);

    if ($checkStmt->rowCount() > 0) {
        $error = "Email already registered. Please use another email.";
    } else {

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, mobile_number, gender, address) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $password, $mobile_number, $gender, $address])) {
            $_SESSION['success'] = "Registration successful. You can now login.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="auth-bg">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 400px;">
            <h3 class="text-center">Register</h3>
            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>
            <form method="POST">
                 <div class="mb-3">
        <label class="form-label">Name:</label>
        <input type="text" name="name" class="form-control" required pattern="[A-Za-z\s]+" title="Only letters and spaces allowed.">
    </div>

    <div class="mb-3">
        <label class="form-label">Email:</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password:</label>
        <input type="password" name="password" class="form-control" required minlength="6" title="Password must be at least 6 characters.">
    </div>

    <div class="mb-3">
        <label class="form-label">Mobile Number:</label>
        <input type="text" name="mobile_number" class="form-control" required pattern="[0-9]{10}" title="Enter a valid 10-digit mobile number.">
    </div>
                <div class="mb-3">
                    <label class="form-label">Gender:</label>
                    <select name="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address:</label>
                    <textarea name="address" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-success w-100">Register</button>
                <button type="button" class="btn btn-secondary w-100 mt-2" onclick="history.back()">Back</button>
                <p class="mt-3 text-center">
                    Already have an account? <a href="login.php" class="text-decoration-none">Login</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
