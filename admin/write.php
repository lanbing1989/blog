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

// 附件原始名映射
function get_attachment_meta() {
    $metaFile = __DIR__ . '/../uploads/attachments.json';
    if (file_exists($metaFile)) {
        return json_decode(file_get_contents($metaFile), true) ?: [];
    }
    return [];
}
$meta = get_attachment_meta();
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
    <div class="admin-header">后台管理</div>
    <div class="admin-navbar">
        <a href="/" target="_blank">前台首页</a>
        <a href="manage.php">文章管理</a>
        <a href="write.php">写文章</a>
        <a href="images.php">图片管理</a>
        <a href="attachments.php">文件管理</a>
        <a href="settings.php">站点设置</a>
        <a href="logout.php">退出</a>
    </div>
    <?php if (!empty($error)) echo "<div class='error-msg'>{$error}</div>"; ?>
    <form method="post" class="admin-form">
        <label for="title">标题：</label>
        <input type="text" name="title" id="title" value="<?=htmlspecialchars($title)?>" required>
        <label for="content">内容（Markdown）：</label>
        <div style="margin-bottom:10px;">
            <button type="button" id="custom-upload-btn" style="padding:5px 15px;">上传图片</button>
            <input type="file" id="custom-upload-input" accept="image/*" multiple style="display:none;">
            <button type="button" id="custom-attachment-btn" style="padding:5px 15px;">上传附件</button>
            <input type="file" id="custom-attachment-input" multiple style="display:none;">
        </div>
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
window.mdEditor = editormd("md-editor", {
    width: "100%",
    height: 600,
    path : "/assets/editor.md/lib/",
    imageUpload : false
});

// 上传图片
$('#custom-upload-btn').on('click', function() {
    $('#custom-upload-input').val('').click();
});
$('#custom-upload-input').on('change', function() {
    var files = Array.from(this.files);
    if (!files.length) return;
    $('#custom-upload-btn').prop('disabled', true).text('上传中...');
    let completed = 0;
    files.forEach(function(file) {
        var fd = new FormData();
        fd.append('editormd-image-file', file);
        fetch('/upload.php', {
            method: 'POST',
            body: fd
        })
        .then(r=>r.json())
        .then(res=>{
            if(res.success && res.url){
                window.mdEditor.insertValue(`![图片](${res.url})\n`);
            }else{
                alert(res.message || '上传失败');
            }
        })
        .catch(()=>alert('上传出错'))
        .finally(()=>{
            completed++;
            if(completed === files.length){
                $('#custom-upload-btn').prop('disabled', false).text('上传图片');
            }
        });
    });
});

// 上传附件并插入markdown链接，展示原始文件名
$('#custom-attachment-btn').on('click', function() {
    $('#custom-attachment-input').val('').click();
});
$('#custom-attachment-input').on('change', function() {
    var files = Array.from(this.files);
    if (!files.length) return;
    $('#custom-attachment-btn').prop('disabled', true).text('上传中...');
    let completed = 0;
    files.forEach(function(file) {
        var fd = new FormData();
        fd.append('attachment-file', file);
        fetch('/upload_attachment.php', {
            method: 'POST',
            body: fd
        })
        .then(r=>r.json())
        .then(res=>{
            if(res.success && res.url && res.filename){
                // 用原始文件名
                window.mdEditor.insertValue(`[${res.filename}](${res.url})\n`);
            }else{
                alert(res.message || '上传失败');
            }
        })
        .catch(()=>alert('上传出错'))
        .finally(()=>{
            completed++;
            if(completed === files.length){
                $('#custom-attachment-btn').prop('disabled', false).text('上传附件');
            }
        });
    });
});
</script>
</body>
</html>