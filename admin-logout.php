<?php
session_start();
$_SESSION = []; // Clear session variables
session_destroy();
header("Location: admin-login.php");
exit();
?>
