# 我的博客系统

## 项目概述
轻量级PHP博客系统，基于Markdown文件存储，支持以下功能：
- 文章撰写/编辑/删除
- 自动生成文章目录
- 响应式网页设计
- 内置管理后台
- 无数据库依赖

## 环境要求
- PHP 7.4+
- Web服务器（Apache/Nginx）或PHP内置服务器

## 安装步骤
1. 克隆仓库到web目录
2. 安装依赖库：
```bash
composer require erusev/parsedown
```
3. 设置目录权限：
```bash
chmod -R 755 data/
```

## 编辑器配置建议
推荐使用VSCode + 以下扩展：
- Markdown All in One
- Prettier
- PHP Intelephense

## 运行方法
```bash
php -S localhost:8000 -t .
```
访问 http://localhost:8000

## 管理后台
访问 `/admin.php` 使用默认账号密码：
- 账号：admin
- 密码：123456

## 创建新文章
1. 在管理后台点击"新建文章"
2. 使用Markdown格式编写内容
3. 保存后自动生成文章页面

## 文件结构
```
├── data/           # 文章存储目录
├── assets/         # 静态资源
├── inc/            # 公共函数
├── lib/            # 第三方库
└── view.php        # 文章渲染
```