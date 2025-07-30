<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);


    session_start();
    $_SESSION['success_message'] = "Your message has been sent successfully!";
    
  
    header("Location: contact.php");
    exit();
} else {
    header("Location: contact.php");
    exit();
}
?>
