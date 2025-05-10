<?php
session_start();
if (empty($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}
$sitefile = '../data/site.json';
$info = file_exists($sitefile) ? json_decode(file_get_contents($sitefile), true) : ['title'=>'','subtitle'=>'','footer'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $info['title'] = trim($_POST['title'] ?? '');
    $info['subtitle'] = trim($_POST['subtitle'] ?? '');
    $info['footer'] = trim($_POST['footer'] ?? '');
    file_put_contents($sitefile, json_encode($info, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    $msg = "保存成功！";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>网站信息设置</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
<div class="admin-container" style="max-width:500px;">
    <div class="admin-header">网站信息设置</div>
    <div class="admin-navbar">
        <a href="manage.php">文章管理</a>
        <a href="write.php">写文章</a>
        <a href="images.php">图片管理</a>
        <a href="site.php">网站信息</a>
        <a href="logout.php">退出</a>
    </div>
    <?php if (!empty($msg)) echo "<div class='success-msg'>{$msg}</div>"; ?>
    <form method="post" class="admin-form">
        <label for="title">网站标题（title）：</label>
        <input type="text" name="title" id="title" value="<?=htmlspecialchars($info['title'])?>" required>
        <label for="subtitle">副标题（subtitle）：</label>
        <input type="text" name="subtitle" id="subtitle" value="<?=htmlspecialchars($info['subtitle'])?>">
        <label for="footer">页脚信息（footer）：</label>
        <input type="text" name="footer" id="footer" value="<?=htmlspecialchars($info['footer'])?>">
        <button type="submit">保存设置</button>
    </form>
    <div class="admin-footer">
        &copy; <?=date('Y')?> 博客后台
    </div>
</div>
</body>
</html>