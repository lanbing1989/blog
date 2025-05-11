<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';
    if ($user === ADMIN_USER && password_verify($pass, ADMIN_PASS)) {
        $_SESSION['admin_login'] = true;
        header("Location: manage.php");
        exit;
    } else {
        $error = "账号或密码错误";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>登录后台</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
<div class="admin-container" style="max-width:400px;">
    <div class="admin-header">后台登录</div>
    <?php if (!empty($error)) echo "<div class='error-msg'>{$error}</div>"; ?>
    <form method="post" class="admin-form">
        <label for="user">账号：</label>
        <input type="text" name="user" id="user" required>
        <label for="pass">密码：</label>
        <input type="password" name="pass" id="pass" required>
        <button type="submit">登录</button>
    </form>
    <div class="admin-footer">
        &copy; <?=date('Y')?> 博客后台
    </div>
</div>
</body>
</html>