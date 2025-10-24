<?php
global $pdo;
require 'bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $bg = isset($_POST['bg_color']) ? $_POST['bg_color'] : null;
    $font = isset($_POST['font_color']) ? $_POST['font_color'] : null;

    if ($username === '' || $password === '') {
        $error = "Заполните логин и пароль";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, bg_color, font_color) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$username, $hash, $bg, $font]);
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            $error = "Пользователь уже существует или ошибка базы";
        }
    }
}
?>
<!-- HTML форма -->
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Регистрация</title></head>
<body>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Логин: <input name="username"><br>
    Пароль: <input name="password" type="password"><br>
    Цвет фона (например #ffffff): <input name="bg_color"><br>
    Цвет шрифта (например #000000): <input name="font_color"><br>
    <button>Зарегистрироваться</button>
</form>
</body>
</html>
