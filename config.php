<?php
// 数据库配置
define('DB_HOST', 'localhost');
define('DB_NAME', 'blog');
define('DB_USER', 'root');
define('DB_PASS', '');

// 创建数据库连接
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 检查连接
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 设置字符集
$conn->set_charset("utf8mb4");

// 获取博客配置
function getBlogConfig() {
    global $conn;
    $query = "SELECT * FROM config LIMIT 1";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    // 返回默认配置
    return [
        'blog_title' => '我的博客',
        'blog_description' => '这是我的个人博客',
        'copyright' => '© ' . date('Y') . ' 版权所有',
        'icp_record' => ''
    ];
}

// 更新博客配置
function updateBlogConfig($title, $description, $copyright, $icp_record) {
    global $conn;
    
    // 检查是否已有配置记录
    $checkQuery = "SELECT id FROM config LIMIT 1";
    $result = $conn->query($checkQuery);
    
    $title = $conn->real_escape_string($title);
    $description = $conn->real_escape_string($description);
    $copyright = $conn->real_escape_string($copyright);
    $icp_record = $conn->real_escape_string($icp_record);
    
    if ($result->num_rows > 0) {
        // 更新现有记录
        $query = "UPDATE config SET 
                  blog_title = '$title', 
                  blog_description = '$description',
                  copyright = '$copyright',
                  icp_record = '$icp_record'";
    } else {
        // 插入新记录
        $query = "INSERT INTO config (blog_title, blog_description, copyright, icp_record) 
                  VALUES ('$title', '$description', '$copyright', '$icp_record')";
    }
    
    return $conn->query($query);
}

// 验证用户登录状态
function isUserLoggedIn() {
    session_start();
    return isset($_SESSION['user_id']);
}

// 登录函数
function login($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            return true;
        }
    }
    return false;
}

// 获取当前用户信息
function getCurrentUser() {
    if (!isUserLoggedIn()) {
        return null;
    }
    
    global $conn;
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

// 登出函数
function logout() {
    session_start();
    $_SESSION = [];
    session_destroy();
    header("Location: index.php");
    exit();
}
?>    