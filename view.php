<?php
require_once __DIR__ . '/lib/Parsedown.php';

$file = $_GET['file'] ?? '';
if (!$file && preg_match('#/post/([a-zA-Z0-9\-_]+)#', $_SERVER['REQUEST_URI'], $m)) {
    $file = $m[1] . '.md';
}
$path = __DIR__ . '/data/articles/' . basename($file);

if (!is_file($path)) {
    http_response_code(404);
    echo '文章不存在';
    exit;
}

$text = file_get_contents($path);
if (preg_match('/---\s*title:\s*(.*?)\s*date:\s*(.*?)\s*---\s*(.*)/s', $text, $m)) {
    $title = $m[1];
    $date = $m[2];
    $content_md = $m[3];
} else {
    $title = $file;
    $date = '';
    $content_md = $text;
}
$Parsedown = new Parsedown();
$content_html = $Parsedown->text($content_md);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?=htmlspecialchars($title)?> - 我的博客</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/style.css">
    <style>
    .article-content {margin: 28px 0;}
    .article-content img {max-width:100%;}
    </style>
</head>
<body>
<div class="container">
    <header>
        <a href="/" style="text-decoration:none;color:inherit;">
            <h1>我的博客</h1>
        </a>
    </header>
    <main>
        <h2><?=htmlspecialchars($title)?></h2>
        <div class="article-date"><?=htmlspecialchars($date)?></div>
        <div class="article-content"><?= $content_html ?></div>
        <div class="back"><a href="/">← 返回首页</a></div>
    </main>
    <footer>
        &copy; <?=date('Y')?> 我的博客 | Powered by PHP+Markdown
    </footer>
</div>
</body>
</html>