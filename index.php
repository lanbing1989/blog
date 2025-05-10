<?php include 'config.php'; ?>
<?php $config = getBlogConfig(); ?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($config['blog_title']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; max-width: 800px; margin: 0 auto; }
        .post { margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .post h2 a { color: #333; text-decoration: none; }
        .post h2 a:hover { text-decoration: underline; }
        .meta { color: #666; font-size: 0.9em; margin-bottom: 10px; }
        .read-more { display: inline-block; background: #333; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .read-more:hover { background: #555; }
        .pagination { margin-top: 20px; }
        .pagination a { display: inline-block; padding: 5px 10px; border: 1px solid #ddd; margin-right: 5px; text-decoration: none; }
        .pagination a.active { background: #333; color: white; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; font-size: 0.9em; }
        .footer a { color: #666; }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($config['blog_title']); ?></h1>
    <p><?php echo htmlspecialchars($config['blog_description']); ?></p>
    
    <div class="navigation">
        <a href="index.php">首页</a> | 
        <?php if (isUserLoggedIn()): ?>
            <a href="create_post.php">写文章</a> |
            <a href="admin_config.php">博客配置</a> |
            <a href="logout.php">退出登录 (<?php echo getCurrentUser()['username']; ?>)</a>
        <?php else: ?>
            <a href="login.php">管理登录</a>
        <?php endif; ?>
    </div>

    <?php
    // 分页逻辑
    $posts_per_page = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $posts_per_page;

    // 获取文章总数
    $total_query = "SELECT COUNT(id) as total FROM posts";
    $total_result = $conn->query($total_query);
    $total_row = $total_result->fetch_assoc();
    $total_posts = $total_row['total'];
    $total_pages = ceil($total_posts / $posts_per_page);

    // 获取当前页文章
    $query = "SELECT posts.*, users.username 
              FROM posts 
              JOIN users ON posts.user_id = users.id 
              ORDER BY posts.created_at DESC 
              LIMIT $offset, $posts_per_page";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="post">';
            echo '<h2><a href="post/' . $row['id'] . '/">' . $row['title'] . '</a></h2>';
            echo '<div class="meta">发布于 ' . date('Y-m-d H:i', strtotime($row['created_at'])) . ' 由 ' . $row['username'] . '</div>';
            echo '<p>' . substr($row['content'], 0, 300) . '...</p>';
            echo '<a href="post/' . $row['id'] . '/" class="read-more">阅读更多</a>';
            echo '</div>';
        }
    } else {
        echo '<p>暂无文章</p>';
    }

    // 分页链接
    echo '<div class="pagination">';
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $page) ? 'class="active"' : '';
        echo '<a href="page/' . $i . '/" ' . $active . '>' . $i . '</a>';
    }
    echo '</div>';
    ?>

    <footer class="footer">
        <p><?php echo htmlspecialchars($config['copyright']); ?></p>
        <?php if (!empty($config['icp_record'])): ?>
            <p><a href="https://beian.miit.gov.cn/" target="_blank"><?php echo htmlspecialchars($config['icp_record']); ?></a></p>
        <?php endif; ?>
    </footer>
</body>
</html>    