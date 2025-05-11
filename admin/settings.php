<?php
session_start();
require_once '../config.php';
if (empty($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}
$site_file = '../data/site.json';
if (!file_exists($site_file)) {
    file_put_contents($site_file, json_encode([
        'title' => '我的博客',
        'subtitle' => '记录美好生活',
        'footer' => '© ' . date('Y') . ' 我的博客 | Powered by PHP'
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
$site = json_decode(file_get_contents($site_file), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site['title'] = trim($_POST['title'] ?? '');
    $site['subtitle'] = trim($_POST['subtitle'] ?? '');
    $site['footer'] = trim($_POST['footer'] ?? '');
    file_put_contents($site_file, json_encode($site, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    $msg = "保存成功！";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>站点设置</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
<div class="admin-container">
    <div class="admin-header">站点设置</div>
    <div class="admin-navbar">
        <a href="/" target="_blank">前台首页</a>
        <a href="manage.php">文章管理</a>
        <a href="write.php">写文章</a>
        <a href="images.php">图片管理</a>
        <a href="attachments.php">文件管理</a>
        <a href="settings.php">站点设置</a>
        <a href="logout.php">退出</a>
    </div>
    <?php if (!empty($msg)) echo "<div class='success-msg'>{$msg}</div>"; ?>
    <form method="post" class="admin-form">
        <label for="title">标题：</label>
        <input type="text" name="title" id="title" value="<?=htmlspecialchars($site['title'] ?? '')?>" required>
        <label for="subtitle">副标题：</label>
        <input type="text" name="subtitle" id="subtitle" value="<?=htmlspecialchars($site['subtitle'] ?? '')?>">
        <label for="footer">页脚信息：</label>
        <input type="text" name="footer" id="footer" value="<?=htmlspecialchars($site['footer'] ?? '')?>">
        <button type="submit">保存设置</button>
    </form>
</div>
</body>
</html>