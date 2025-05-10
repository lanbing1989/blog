<?php
session_start();
require_once '../config.php';

if (empty($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}
$id = $_GET['id'] ?? '';
$file = ARTICLE_DIR . basename($id) . '.md';
if ($id && is_file($file)) {
    unlink($file);
}
header("Location: manage.php");
exit;