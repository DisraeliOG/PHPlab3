<?php
require 'bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['bg_color'] = $user['bg_color'];
        $_SESSION['font_color'] = $user['font_color'];

        if ($remember) {
            // создаём токен и сохраняем в БД + cookie
            $token = bin2hex(random_bytes(32));
            $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
            $stmt->execute([$token, $user['id']]);
            // cookie на 30 дней
            setcookie('remember', $token, time() + 60*60*24*30, '/');
        }
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Неверный логин/пароль";
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Вход</title></head><body>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Логин: <input name="username"><br>
    Пароль: <input name="password" type="password"><br>
    Запомнить меня: <input type="checkbox" name="remember"><br>
    <button>Войти</button>
</form>
</body></html>
