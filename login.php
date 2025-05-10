<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>登录管理</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; max-width: 400px; margin: 0 auto; }
        form label { display: block; margin-bottom: 5px; }
        form input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 3px; }
        form button { background: #333; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; }
        form button:hover { background: #555; }
        .error { color: red; margin-bottom: 10px; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; font-size: 0.9em; }
        .footer a { color: #666; }
    </style>
</head>
<body>
    <h1>登录管理</h1>

    <?php
    $error = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (login($username, $password)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "用户名或密码错误";
        }
    }
    ?>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label for="username">用户名:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">密码:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">登录</button>
    </form>

    <a href="index.php">返回首页</a>

    <footer class="footer">
        <p><?php echo htmlspecialchars($config['copyright']); ?></p>
        <?php if (!empty($config['icp_record'])): ?>
            <p><a href="https://beian.miit.gov.cn/" target="_blank"><?php echo htmlspecialchars($config['icp_record']); ?></a></p>
        <?php endif; ?>
    </footer>
</body>
</html>    