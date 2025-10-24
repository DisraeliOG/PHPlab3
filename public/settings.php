<?php
global $pdo;
require 'bootstrap.php';
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bg = isset($_POST['bg_color']) ? $_POST['bg_color'] : null;
    $font = isset($_POST['font_color']) ? $_POST['font_color'] : null;
    $stmt = $pdo->prepare("UPDATE users SET bg_color = ?, font_color = ? WHERE id = ?");
    $stmt->execute([$bg, $font, $_SESSION['user_id']]);
    // обновим сессию и cookie
    $_SESSION['bg_color'] = $bg;
    $_SESSION['font_color'] = $font;
    setcookie('bg_color', $bg, time()+60*60*24*30, '/');
    setcookie('font_color', $font, time()+60*60*24*30, '/');
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Настройки</title></head><body>
<form method="post">
    Цвет фона: <input name="bg_color" value="<?=htmlspecialchars($_SESSION['bg_color'] ?? '')?>"><br>
    Цвет шрифта: <input name="font_color" value="<?=htmlspecialchars($_SESSION['font_color'] ?? '')?>"><br>
    <button>Сохранить</button>
</form>
</body></html>
