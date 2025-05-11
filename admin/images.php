<?php
session_start();
require_once '../config.php';
if (empty($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}
$dir = '../uploads/';
$imgs = [];
foreach (glob($dir . '*.{jpg,jpeg,png,gif,bmp}', GLOB_BRACE) as $img) {
    $imgs[] = basename($img);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>图片管理</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
<div class="admin-container">
    <div class="admin-header">图片管理</div>
    <div class="admin-navbar">
        <a href="/" target="_blank">前台首页</a>
        <a href="manage.php">文章管理</a>
        <a href="write.php">写文章</a>
        <a href="images.php">图片管理</a>
        <a href="attachments.php">文件管理</a>
        <a href="settings.php">站点设置</a>
        <a href="logout.php">退出</a>
    </div>
    <div class="admin-imgbox">
    <?php foreach($imgs as $img): ?>
        <div class="admin-imgitem">
            <img src="/uploads/<?=htmlspecialchars($img)?>">
            <div class="img-name"><?=htmlspecialchars($img)?></div>
            <form action="delete_image.php" method="post" onsubmit="return confirm('确定要删除这张图片吗？');">
                <input type="hidden" name="img" value="<?=htmlspecialchars($img)?>">
                <button type="submit">删除</button>
            </form>
        </div>
    <?php endforeach; ?>
    </div>
    <div class="admin-footer">
        &copy; <?=date('Y')?> 博客后台
    </div>
</div>
</body>
</html>