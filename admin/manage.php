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

// 分页参数
$page = max(1, intval($_GET['page'] ?? 1));
$pageSize = 10; // 每页10条
$total = count($articles);
$totalPages = ceil($total / $pageSize);
$articlesPaged = array_slice($articles, ($page-1)*$pageSize, $pageSize);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>文章管理</title>
    <link rel="stylesheet" href="/assets/admin.css">
    <style>
    .pagination {
        margin: 16px 0 0 0;
        text-align: center;
    }
    .pagination a, .pagination strong {
        display: inline-block;
        margin: 0 4px;
        padding: 4px 10px;
        border: 1px solid #ddd;
        border-radius: 3px;
        text-decoration: none;
        color: #333;
    }
    .pagination strong {
        background: #337ab7;
        color: #fff;
    }
    .pagination a:hover {
        background: #f5f5f5;
        border-color: #337ab7;
    }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">文章管理</div>
    <div class="admin-navbar">
        <a href="/" target="_blank">前台首页</a>
        <a href="manage.php">文章管理</a>
        <a href="write.php">写文章</a>
        <a href="images.php">图片管理</a>
        <a href="attachments.php">文件管理</a>
        <a href="settings.php">站点设置</a>
        <a href="logout.php">退出</a>
    </div>
    <table class="admin-table">
        <tr><th>标题</th><th>日期</th><th>操作</th></tr>
        <?php foreach($articlesPaged as $a): ?>
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
    <!-- 分页控件 -->
    <div class="pagination">
        <?php if($page > 1): ?>
            <a href="?page=<?=($page-1)?>">上一页</a>
        <?php endif; ?>
        <?php for($i=1; $i<=$totalPages; $i++): ?>
            <?php if($i == $page): ?>
                <strong><?=$i?></strong>
            <?php else: ?>
                <a href="?page=<?=$i?>"><?=$i?></a>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if($page < $totalPages): ?>
            <a href="?page=<?=($page+1)?>">下一页</a>
        <?php endif; ?>
    </div>
    <div class="admin-footer">
        &copy; <?=date('Y')?> 博客后台
    </div>
</div>
</body>
</html>