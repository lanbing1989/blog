<?php
session_start();
require_once '../config.php';

if (empty($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

$files = glob(ARTICLE_DIR . '*.md');
$articles = [];
foreach ($files as $file) {
    $text = file_get_contents($file);
    if (preg_match('/---\s*title:\s*(.*?)\s*date:\s*(.*?)\s*---/s', $text, $m)) {
        $id = basename($file, '.md');
        $articles[] = [
            'id'    => $id,
            'title' => $m[1],
            'date'  => $m[2]
        ];
    }
}
usort($articles, fn($a,$b)=>strcmp($b['date'],$a['date']));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>文章管理</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
<div class="admin-container">
    <div class="admin-header">后台管理</div>
    <div class="admin-navbar">
        <a href="manage.php">文章管理</a>
        <a href="write.php">写文章</a>
        <a href="images.php">图片管理</a>
        <a href="logout.php">退出</a>
    </div>
    <table class="admin-table">
        <tr><th>标题</th><th>日期</th><th>操作</th></tr>
        <?php foreach($articles as $a): ?>
        <tr>
            <td><?=htmlspecialchars($a['title'])?></td>
            <td><?=htmlspecialchars($a['date'])?></td>
            <td>
                <a href="write.php?edit=<?=urlencode($a['id'])?>">编辑</a>
                <a href="delete.php?id=<?=urlencode($a['id'])?>" onclick="return confirm('确认删除？')">删除</a>
                <a href="/post/<?=urlencode($a['id'])?>" target="_blank">预览</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <div class="admin-footer">
        &copy; <?=date('Y')?> 博客后台
    </div>
</div>
</body>
</html>