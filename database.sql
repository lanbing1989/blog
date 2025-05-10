-- 创建博客数据库
CREATE DATABASE IF NOT EXISTS blog;
USE blog;

-- 创建配置表
CREATE TABLE IF NOT EXISTS config (
    id INT(11) NOT NULL AUTO_INCREMENT,
    blog_title VARCHAR(255) NOT NULL,
    blog_description TEXT,
    copyright VARCHAR(255) DEFAULT NULL,
    icp_record VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 插入默认配置
INSERT INTO config (blog_title, blog_description, copyright, icp_record) VALUES
('我的博客', '这是我的个人博客', '© ' . NOW(), '');

-- 创建用户表
CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 插入管理员用户（密码：admin123）
INSERT INTO users (username, password_hash, created_at) VALUES
('admin', '$2y$10$zHdFyJdN4kqWbXtLmQpYuO.7Bz4FZJQjYhNnqGfYkK8Z6yMlWcG.', NOW());

-- 创建文章表
CREATE TABLE IF NOT EXISTS posts (
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    user_id INT(11) NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 插入示例文章
INSERT INTO posts (title, content, user_id, created_at) VALUES
('第一篇文章', '这是我的第一篇博客文章内容...', 1, NOW()),
('第二篇文章', '这是第二篇博客文章的内容...', 1, NOW());    