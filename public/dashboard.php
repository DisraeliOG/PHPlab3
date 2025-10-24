<?php
require 'bootstrap.php';
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$bg = isset($_SESSION['bg_color']) ? $_SESSION['bg_color'] : '#fff';
$font = isset($_SESSION['font_color']) ? $_SESSION['font_color'] : '#000';

// Применяем настройки — также отправим cookie чтобы клиент видел текущие настройки
setcookie('bg_color', $bg, time()+60*60*24*30, '/');
setcookie('font_color', $font, time()+60*60*24*30, '/');
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Панель</title></head>
<body style="background: <?=htmlspecialchars($bg)?>; color: <?=htmlspecialchars($font)?>;">
<h1>Привет, <?=htmlspecialchars($_SESSION['username'])?></h1>
<p>Настройки цвета применены и сохранены в cookie.</p>
<p><a href="settings.php">Изменить настройки</a> | <a href="logout.php">Выйти</a></p>
</body>
</html>
