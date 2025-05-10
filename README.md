# 蓝冰博客 V7

**蓝冰博客 V7** 是一个极简、纯 PHP 实现的 Markdown 博客系统，专为移动端和桌面端自适应优化。  
后台配有 Markdown 实时预览编辑器和图片管理功能，适合个人技术博客与知识分享。

---

## ✨ 主要特性

- **极简设计**：无多余依赖，目录清晰，代码易维护
- **Markdown 写作**：后台支持实时预览，前台自动渲染
- **图片管理**：支持图片上传、一键复制路径、在线删除
- **响应式布局**：前后台均适配手机/平板/电脑
- **账号密码登录**：安全简洁，默认密码可自定义
- **无需数据库**：所有文章及图片均本地文件存储
- **便捷部署**：上传即用，支持主流 PHP 环境

---

## 📁 目录结构

```
/
├── index.php                # 前台首页
├── view.php                 # 前台文章页
├── articles/                # 存放 Markdown 文章
├── uploads/                 # 存放上传图片
├── assets/
│   ├── style.css            # 前台样式
│   └── admin.css            # 后台样式
├── inc/
│   └── functions.php        # 公共函数与配置
└── admin/
    ├── login.php            # 后台登录
    ├── logout.php           # 退出
    ├── manage.php           # 文章管理
    ├── write.php            # 写/编辑文章
    ├── upload.php           # 图片上传
    ├── images.php           # 图片管理
    ├── delimg.php           # 图片删除
    └── delete.php           # 文章删除
```

---

## 🚀 快速开始

1. **上传全部文件与目录** 到 PHP 服务器（建议 PHP 7.2+）
2. **确保 `articles` 和 `uploads` 目录有写权限**
3. **访问 `/admin/login.php`**  
   - 默认账号：`admin`，默认密码：`123456`（可在 `inc/functions.php` 中修改）
4. **开始写作和管理**！

---

## 📝 文章格式

每篇文章为一个 Markdown 文件，头部需包含如下信息：

```
---
title: 你的标题
date: 2025-05-10 12:00:00
---
正文内容支持 Markdown 语法
```

---

## 🖼️ 图片插入

1. 在后台“图片管理”或“图片上传”页面上传图片
2. 复制图片URL，插入 Markdown 内容  
   例如：  
   ```markdown
   ![](/uploads/xxxxxx.png)
   ```

---

## 🔒 修改后台账号密码

编辑 `inc/functions.php`，修改以下内容：

```php
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', '你的新密码');
```

---

## 📱 响应式优化

已内置自适应 CSS，移动端阅读与后台管理体验友好。

---

## 💡 常见问题

- **首页/后台空白或报错？**
  - 检查 PHP 版本，确保 `articles` 和 `uploads` 目录有写权限
- **上传图片失败？**
  - 检查 `uploads` 文件夹权限，确保可写

---

## 📄 License

MIT License （可自由使用和二次开发，保留原署名即可）

---

蓝冰博客 V7 —— 分享技术，分享爱。
