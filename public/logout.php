<?php
global $pdo;
require 'bootstrap.php';
if (!empty($_SESSION['user_id'])) {
    // удалим remember_token в БД
    $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}
// разлогиним
$_SESSION = [];
session_destroy();
// удалим cookie
setcookie('remember', '', time() - 3600, '/');
setcookie('bg_color', '', time() - 3600, '/');
setcookie('font_color', '', time() - 3600, '/');
header('Location: login.php');
exit;
