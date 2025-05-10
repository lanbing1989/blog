<?php include 'config.php'; ?>
<?php $config = getBlogConfig(); ?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($config['blog_title']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; max-width: 800px; margin: 0 auto; }
        .post { margin-bottom: 20px; }
        .post h2 { margin-bottom: 10px; }
        .meta { color: #666; font-size: 0.9em; margin-bottom: 20px; }
        .content { line-height: 1.6; }
        .back-link { display: inline-block; margin-top: 20px; }
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
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $query = "SELECT posts.*, users.username 
                  FROM posts 
                  JOIN users ON posts.user_id = users.id 
                  WHERE posts.id = $id";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo '<div class="post">';
            echo '<h2>' . $row['title'] . '</h2>';
            echo '<div class="meta">发布于 ' . date('Y-m-d H:i', strtotime($row['created_at'])) . ' 由 ' . $row['username'] . '</div>';
            echo '<div class="content">' . nl2br($row['content']) . '</div>';
            echo '</div>';
        } else {
            echo '<p>文章不存在</p>';
        }
    } else {
        echo '<p>无效的文章ID</p>';
    }
    ?>

    <a href="index.php" class="back-link">返回首页</a>

    <footer class="footer">
        <p><?php echo htmlspecialchars($config['copyright']); ?></p>
        <?php if (!empty($config['icp_record'])): ?>
            <p><a href="https://beian.miit.gov.cn/" target="_blank"><?php echo htmlspecialchars($config['icp_record']); ?></a></p>
        <?php endif; ?>
    </footer>
</body>
</html>    