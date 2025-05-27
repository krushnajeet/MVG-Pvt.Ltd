<?php
session_start();
$investLink = isset($_SESSION['user_id']) ? 'moneyinvest.php' : 'login.php';

$html = file_get_contents('index.html');
$html = str_replace('{{investLink}}', $investLink, $html);
echo $html;
?>
