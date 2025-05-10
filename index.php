<?php
define('ARTICLES_PER_PAGE', 5);
$dir = __DIR__ . '/data/articles/';
$files = glob($dir . '*.md');

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
usort($articles, fn($a, $b) => strcmp($b['date'], $a['date']));

$total = count($articles);
$page = max(1, intval($_GET['page'] ?? 1));
$pages = max(1, ceil($total / ARTICLES_PER_PAGE));
$start = ($page - 1) * ARTICLES_PER_PAGE;
$articles_page = array_slice($articles, $start, ARTICLES_PER_PAGE);

function pagination($page, $pages) {
    $html = '<div class="pagination">';
    if ($page > 1) {
        $html .= '<a href="/page/'.($page-1).'">上一页</a>';
    }
    for ($i = 1; $i <= $pages; $i++) {
        if ($i == $page) {
            $html .= "<span class='current'>{$i}</span>";
        } else {
            $html .= '<a href="/page/'.$i.'">'.$i.'</a>';
        }
    }
    if ($page < $pages) {
        $html .= '<a href="/page/'.($page+1).'">下一页</a>';
    }
    $html .= '</div>';
    return $html;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>我的博客</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>我的博客</h1>
        <p class="subtitle">—— 记录生活和技术</p>
    </header>
    <main>
        <ul class="article-list">
        <?php foreach ($articles_page as $art): ?>
            <li>
                <a class="article-title" href="/post/<?=htmlspecialchars($art['id'])?>">
                    <?=htmlspecialchars($art['title'])?>
                </a>
                <span class="article-date"><?=htmlspecialchars($art['date'])?></span>
            </li>
        <?php endforeach; ?>
        </ul>
        <?=pagination($page, $pages)?>
    </main>
    <footer>
        &copy; <?=date('Y')?> 我的博客 | Powered by PHP+Markdown
    </footer>
</div>
</body>
</html>