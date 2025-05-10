<?php include 'config.php'; ?>
<?php
// 检查用户是否已登录
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

$config = getBlogConfig();
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['blog_title'];
    $description = $_POST['blog_description'];
    $copyright = $_POST['copyright'];
    $icp_record = $_POST['icp_record'];

    if (empty($title)) {
        $error = "博客标题不能为空";
    } else {
        if (updateBlogConfig($title, $description, $copyright, $icp_record)) {
            $success = "配置已更新";
            // 刷新配置
            $config = getBlogConfig();
        } else {
            $error = "更新失败: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>博客配置</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; max-width: 800px; margin: 0 auto; }
        .navigation { margin-bottom: 20px; }
        .navigation a { margin-right: 10px; }
        form label { display: block; margin-bottom: 5px; }
        form input, form textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 3px; }
        form textarea { height: 100px; }
        form button { background: #333; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; }
        form button:hover { background: #555; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; font-size: 0.9em; }
        .footer a { color: #666; }
    </style>
</head>
<body>
    <h1>博客配置</h1>
    
    <div class="navigation">
        <a href="index.php">首页</a> | 
        <a href="create_post.php">写文章</a> |
        <a href="admin_config.php">博客配置</a> |
        <a href="logout.php">退出登录 (<?php echo getCurrentUser()['username']; ?>)</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" action="admin_config.php">
        <label for="blog_title">博客标题:</label>
        <input type="text" id="blog_title" name="blog_title" value="<?php echo htmlspecialchars($config['blog_title']); ?>">

        <label for="blog_description">博客描述:</label>
        <textarea id="blog_description" name="blog_description"><?php echo htmlspecialchars($config['blog_description']); ?></textarea>

        <label for="copyright">版权信息:</label>
        <input type="text" id="copyright" name="copyright" value="<?php echo htmlspecialchars($config['copyright']); ?>">

        <label for="icp_record">备案信息:</label>
        <input type="text" id="icp_record" name="icp_record" value="<?php echo htmlspecialchars($config['icp_record']); ?>">

        <button type="submit">保存配置</button>
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