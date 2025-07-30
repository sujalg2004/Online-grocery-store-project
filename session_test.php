<?php
session_start();
$_SESSION['test'] = 'Session is working';
echo "Session set. Refresh the page.";
?>
