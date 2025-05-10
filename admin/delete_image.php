<?php
session_start();
if (empty($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}
$img = $_POST['img'] ?? '';
if ($img) {
    $file = realpath(__DIR__ . '/../uploads/' . $img);
    $uploads_dir = realpath(__DIR__ . '/../uploads/');
    // 防止任意文件删除，仅允许删除 uploads 目录下的图片
    if ($file && strpos($file, $uploads_dir) === 0 && is_file($file)) {
        unlink($file);
    }
}
header("Location: images.php");
exit;