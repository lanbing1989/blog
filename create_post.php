<?php include 'config.php'; ?>
<?php
// 检查用户是否已登录
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

// 引入SimpleMDE编辑器资源
$editor_enabled = true;
?>
<!DOCTYPE html>
<html>
<head>
    <title>发布文章</title>
    <!-- 引入SimpleMDE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; max-width: 800px; margin: 0 auto; }
        .navigation { margin-bottom: 20px; }
        .navigation a { margin-right: 10px; }
        .editor-toolbar { border-radius: 3px 3px 0 0; }
        .CodeMirror { border-radius: 0 0 3px 3px; margin-bottom: 15px; }
        form label { display: block; margin-bottom: 5px; }
        form input, form textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 3px; }
        form button { background: #333; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; }
        form button:hover { background: #555; }
        .error { color: red; margin-bottom: 10px; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; font-size: 0.9em; }
        .footer a { color: #666; }
    </style>
</head>
<body>
    <h1>发布文章</h1>
    
    <div class="navigation">
        <a href="index.php">首页</a> | 
        <a href="create_post.php">写文章</a> |
        <a href="admin_config.php">博客配置</a> |
        <a href="logout.php">退出登录 (<?php echo getCurrentUser()['username']; ?>)</a>
    </div>

    <?php
    $error = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['title'];
        $content = $_POST['content'];

        if (empty($title)) {
            $error = "标题不能为空";
        } elseif (empty($content)) {
            $error = "内容不能为空";
        } else {
            $title = $conn->real_escape_string($title);
            $content = $conn->real_escape_string($content);
            $user_id = $_SESSION['user_id'];
            $created_at = date('Y-m-d H:i:s');

            $query = "INSERT INTO posts (title, content, user_id, created_at) 
                      VALUES ('$title', '$content', $user_id, '$created_at')";
            if ($conn->query($query) === TRUE) {
                header("Location: index.php");
                exit();
            } else {
                $error = "发布失败: " . $conn->error;
            }
        }
    }
    ?>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="create_post.php">
        <label for="title">标题:</label>
        <input type="text" id="title" name="title" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">

        <label for="content">内容:</label>
        <textarea id="content" name="content"><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>

        <button type="submit">发布文章</button>
    </form>

    <!-- 引入SimpleMDE JS -->
    <script src="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.js"></script>
    <script>
        // 初始化编辑器
        document.addEventListener('DOMContentLoaded', function() {
            var simplemde = new SimpleMDE({
                element: document.getElementById("content"),
                spellChecker: false,
                status: false,
                toolbar: [
                    "heading", "|", "bold", "italic", "strikethrough", "|",
                    "ordered-list", "unordered-list", "|",
                    "link", "image", "code", "quote", "|",
                    "preview", "side-by-side", "fullscreen", "|",
                    "guide"
                ]
            });
        });
    </script>

    <footer class="footer">
        <p><?php echo htmlspecialchars($config['copyright']); ?></p>
        <?php if (!empty($config['icp_record'])): ?>
            <p><a href="https://beian.miit.gov.cn/" target="_blank"><?php echo htmlspecialchars($config['icp_record']); ?></a></p>
        <?php endif; ?>
    </footer>
</body>
</html>    