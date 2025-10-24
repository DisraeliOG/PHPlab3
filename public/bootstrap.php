<?php
// public/bootstrap.php
session_start();

$host = '127.0.0.1';
$db   = 'lab3_php';
$user = 'root';
$pass = ''; // если в MAMP/локально есть пароль — укажите
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// helper: авторизация через remember cookie
function tryRememberLogin(PDO $pdo) {
    if (isset($_SESSION['user_id'])) {
        return;
    }
    if (!empty($_COOKIE['remember'])) {
        $token = $_COOKIE['remember'];
        $stmt = $pdo->prepare("SELECT id, username, bg_color, font_color FROM users WHERE remember_token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        if ($user) {
            // восстановим сессию
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['bg_color'] = $user['bg_color'];
            $_SESSION['font_color'] = $user['font_color'];
        } else {
            // невалидный токен — удалим cookie
            setcookie('remember', '', time() - 3600, '/');
        }
    }
}
tryRememberLogin($pdo);
