<?php
session_start();
if (empty($_SESSION['admin_login'])) {
    echo json_encode(['success'=>0,'message'=>'无权限']);
    exit;
}
$file = $_POST['file'] ?? '';
$path = realpath(__DIR__ . '/../uploads/' . $file);
$uploads = realpath(__DIR__ . '/../uploads/');
$metaFile = __DIR__ . '/../uploads/attachments.json';
if ($file && $path && strpos($path, $uploads)===0 && is_file($path)) {
    unlink($path);
    // 删除映射
    if (file_exists($metaFile)) {
        $list = json_decode(file_get_contents($metaFile), true) ?: [];
        unset($list[$file]);
        file_put_contents($metaFile, json_encode($list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
    echo json_encode(['success'=>1]);
} else {
    echo json_encode(['success'=>0,'message'=>'删除失败']);
}