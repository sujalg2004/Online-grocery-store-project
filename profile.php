<?php
session_start();
include 'db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT name, email, mobile_number, gender, address FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found!");
    }
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>

  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }

        
        .header-container {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            background: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            width: 280px;
            height: 100vh;
            background: #fff;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 95px; 
            left: 0;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar h4 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }

        .sidebar a {
            display: block;
            color: #333;
            padding: 10px;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background: #f8f9fa;
        }

       
        .profile-container {
            margin-left: 300px;
            padding: 90px 40px 40px; 
            width: calc(100% - 300px);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .profile-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #007bff;
        }

        .profile-info h3 {
            margin: 0;
            font-size: 22px;
        }

        .profile-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .profile-section h4 {
            font-size: 18px;
        }

        .profile-section input, 
        .profile-section textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
        }


        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }


        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
            }

            .profile-container {
                margin-left: 0;
                width: 100%;
                padding: 80px 20px 20px; 
            }
        }
    </style>
</head>
<body>


<div class="header-container">
    <?php include 'header.php'; ?>
</div>

<div class="d-flex">

    <div class="sidebar">
        <div class="profile-header">
            <img src="https://www.w3schools.com/howto/img_avatar.png" alt="Profile" class="profile-img">
            <div class="profile-info">
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <p style="color: gray;">Hello, <?php echo htmlspecialchars($user['name']); ?></p>
            </div>
        </div>
        <h4>MY ORDERS</h4>
        <a href="user_orders.php"><i class="fas fa-box"></i> My Orders</a>
    </div>

   
    <div class="profile-container">
        <div class="profile-section">
            <h4>Personal Information</h4>
            <input type="text" value="<?php echo htmlspecialchars($user['name']); ?>" disabled>
        </div>

        <div class="profile-section">
            <h4>Email Address</h4>
            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
        </div>

        <div class="profile-section">
            <h4>Mobile Number</h4>
            <input type="text" value="<?php echo htmlspecialchars($user['mobile_number']); ?>" disabled>
        </div>

        <div class="profile-section">
            <h4>Gender</h4>
            <input type="text" value="<?php echo htmlspecialchars($user['gender']); ?>" disabled>
        </div>

        <div class="profile-section">
            <h4>Address</h4>
            <textarea class="form-control" disabled><?php echo htmlspecialchars($user['address']); ?></textarea>
        </div>

     
        <div class="btn-container">
            <a href="edit_profile.php" class="btn btn-primary"><i class="fas fa-edit"></i> Edit Profile</a>
            <button onclick="history.back()" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</button>
        </div>
    </div>
</div>

</body>
</html>
