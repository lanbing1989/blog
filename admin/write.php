<?php
session_start();
require_once '../config.php';

if (empty($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

$edit_id = $_GET['edit'] ?? '';
$title = $content = '';
$edit_mode = false;

if ($edit_id) {
    $file = ARTICLE_DIR . basename($edit_id) . '.md';
    if (is_file($file)) {
        $text = file_get_contents($file);
        if (preg_match('/---\s*title:\s*(.*?)\s*date:\s*(.*?)\s*---\s*(.*)/s', $text, $m)) {
            $title = $m[1];
            $content = trim($m[3]);
            $edit_mode = true;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if ($title && $content) {
        $date = date('Y-m-d H:i:s');
        if ($edit_mode && $edit_id) {
            $filename = ARTICLE_DIR . basename($edit_id) . '.md';
            $orig_time = '';
            if (preg_match('/date:\s*(.*?)\s*---/s', file_get_contents($filename), $m)) {
                $orig_time = $m[1];
            }
            $date = $orig_time ?: $date;
        } else {
            $timestamp = date('YmdHis');
            $filename = ARTICLE_DIR . $timestamp . '.md';
        }
        $md = "---\ntitle: {$title}\ndate: {$date}\n---\n\n{$content}\n";
        file_put_contents($filename, $md);
        header("Location: manage.php");
        exit;
    } else {
        $error = "标题和内容不能为空";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?=$edit_mode?'编辑':'写'?>文章</title>
    <link rel="stylesheet" href="/assets/admin.css">
    <link rel="stylesheet" href="/assets/editor.md/css/editormd.min.css" />
</head>
<body>
<div class="admin-container">
    <div class="admin-header">写文章</div>
    <div class="admin-navbar">
        <a href="/" target="_blank">前台首页</a>
        <a href="manage.php">文章管理</a>
        <a href="write.php">写文章</a>
        <a href="images.php">图片管理</a>
        <a href="settings.php">站点设置</a>
        <a href="logout.php">退出</a>
    </div>
    <?php if (!empty($error)) echo "<div class='error-msg'>{$error}</div>"; ?>
    <form method="post" class="admin-form">
        <label for="title">标题：</label>
        <input type="text" name="title" id="title" value="<?=htmlspecialchars($title)?>" required>
        <label for="content">内容（Markdown）：</label>
        <div id="md-editor">
            <textarea style="display:none;" name="content" id="content"><?=htmlspecialchars($content)?></textarea>
        </div>
        <button type="submit"><?=$edit_mode ? '保存修改' : '发布文章'?></button>
        <a href="manage.php">返回管理</a>
    </form>
    <div class="admin-footer">
        &copy; <?=date('Y')?> 博客后台
    </div>
</div>
<script src="/assets/editor.md/lib/jquery.min.js"></script>
<script src="/assets/editor.md/editormd.min.js"></script>
<script>
editormd("md-editor", {
    width: "100%",
    height: 600,
    path : "/assets/editor.md/lib/",
    imageUpload : true,
    imageFormats : ["jpg", "jpeg", "gif", "png", "bmp"],
    imageUploadURL : "/upload.php"
});
</script>
</body>
</html>