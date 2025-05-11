<?php
session_start();
require_once '../config.php';

if (empty($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

function get_attachments($dir) {
    $files = [];
    if (!is_dir($dir)) return $files;
    $dh = opendir($dir);
    while (($file = readdir($dh)) !== false) {
        if ($file == '.' || $file == '..' || $file == 'attachments.json') continue; // 跳过映射文件
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
            $full = $dir.'/'.$file;
            $files[] = [
                'name' => $file,
                'ext'  => $ext,
                'url'  => '/uploads/' . $file,
                'size' => filesize($full),
                'mtime'=> filemtime($full)
            ];
        }
    }
    closedir($dh);
    // 按上传时间倒序
    usort($files, function($a, $b){return $b['mtime'] - $a['mtime'];});
    return $files;
}

function get_attachment_meta() {
    $metaFile = __DIR__ . '/../uploads/attachments.json';
    if (file_exists($metaFile)) {
        return json_decode(file_get_contents($metaFile), true) ?: [];
    }
    return [];
}
$attachment_files = get_attachments(__DIR__ . '/../uploads/');
$meta = get_attachment_meta();
function formatSize($size) {
    if ($size >= 1073741824) return round($size / 1073741824,2).' GB';
    if ($size >= 1048576) return round($size / 1048576,2).' MB';
    if ($size >= 1024) return round($size / 1024,2).' KB';
    return $size . ' B';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>附件管理</title>
    <link rel="stylesheet" href="/assets/admin.css">
    <style>
        .attach-container {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            margin: 20px 0 10px 0;
        }
        .attach-card {
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 1px 4px #ddd;
            width: 320px;
            padding: 18px 16px 12px 16px;
            margin-bottom: 8px;
            position: relative;
            transition: box-shadow .2s;
        }
        .attach-card:hover { box-shadow: 0 2px 10px #bbb; }
        .attach-name {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            margin-bottom: 4px;
            display: block;
            word-break: break-all;
        }
        .attach-meta {
            color: #888;
            font-size: 13px;
            margin-bottom: 6px;
        }
        .attach-actions {
            margin-top: 8px;
        }
        .attach-link, .pdf-preview, .attach-del {
            display: inline-block;
            margin-right: 13px;
            color: #2176d2;
            cursor: pointer;
            text-decoration: underline;
            background: none;
            border: none;
            font-size: 14px;
            padding: 0;
        }
        .attach-del { color: #e74c3c; }
        .attach-del:hover { text-decoration: underline; }
        .pdf-preview { color: #009688; }
        #pdfDialog {display:none;position:fixed;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);z-index:999;}
        #pdfDialog .pdf-content {position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);background:#fff;padding:10px;box-shadow:0 0 10px #333;}
        #pdfDialog .pdf-close {float:right;cursor:pointer;color:#888;font-size:20px;}
        #pdfDialog .pdf-iframe {width:80vw;max-width:800px;height:80vh;border:none;}
        .admin-navbar {margin-bottom: 20px;}
        #custom-attachment-btn {padding:6px 18px;font-size:15px;background:#2176d2;color:#fff;border:none;border-radius:4px;cursor:pointer;}
        #custom-attachment-btn:disabled {background:#aaa;}
    </style>
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
    <div style="margin-bottom:14px;">
        <button type="button" id="custom-attachment-btn">上传附件</button>
        <input type="file" id="custom-attachment-input" multiple style="display:none;">
    </div>
    <div class="attach-container">
        <?php if (!$attachment_files): ?>
        <div style="color:#888;padding:18px;font-size:15px;">暂无附件</div>
        <?php else: foreach ($attachment_files as $file):
            $origin = isset($meta[$file['name']]) ? $meta[$file['name']] : $file['name'];
        ?>
        <div class="attach-card">
            <div class="attach-name" title="<?=htmlspecialchars($origin)?>"><?=htmlspecialchars($origin)?></div>
            <div class="attach-meta">
                <?=formatSize($file['size'])?>
                &nbsp;|&nbsp; <?=date('Y-m-d H:i', $file['mtime'])?>
                &nbsp;|&nbsp; <?=strtoupper($file['ext'])?>
            </div>
            <div class="attach-actions">
                <a class="attach-link" href="<?=$file['url']?>" download="<?=htmlspecialchars($origin)?>">下载</a>
                <?php if ($file['ext'] === 'pdf'): ?>
                    <span class="pdf-preview" data-url="<?=$file['url']?>">PDF预览</span>
                <?php endif;?>
                <span class="attach-del" data-file="<?=$file['name']?>">删除</span>
            </div>
        </div>
        <?php endforeach; endif;?>
    </div>
    <div class="admin-footer" style="margin-top:32px;">
        &copy; <?=date('Y')?> 博客后台
    </div>
</div>
<!-- PDF 预览弹窗 -->
<div id="pdfDialog">
    <div class="pdf-content">
        <span class="pdf-close" onclick="$('#pdfDialog').hide();">&times;</span>
        <iframe class="pdf-iframe" src="" frameborder="0"></iframe>
    </div>
</div>
<script src="/assets/editor.md/lib/jquery.min.js"></script>
<script>
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
                location.reload();
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

// PDF 预览美化
$('.pdf-preview').on('click', function(){
    var url = $(this).attr('data-url');
    $('#pdfDialog .pdf-iframe').attr('src', url);
    $('#pdfDialog').show();
});
$('#pdfDialog').on('click', function(e){
    if(e.target.id === 'pdfDialog') $(this).hide();
});
$('.pdf-close').on('click', function(){
    $('#pdfDialog').hide();
});

// 附件删除
$('.attach-del').on('click', function(){
    if(!confirm('确定删除该附件吗？')) return;
    var $card = $(this).closest('.attach-card');
    var file = $(this).attr('data-file');
    $.post('delete_attachment.php', {file:file}, function(res){
        if(res.success){
            $card.fadeOut(200, function(){$(this).remove();});
        }else{
            alert(res.message || '删除失败');
        }
    }, 'json');
});
</script>
</body>
</html>