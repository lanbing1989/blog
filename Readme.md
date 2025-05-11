这个仓库是一个基于 PHP 和 JavaScript 构建的博客系统，具备在线 Markdown 编辑功能，整体结构清晰，各部分分工明确，以下是详细介绍：

### 1. 整体目录结构
```
.htaccess
config.php
index.php
upload.php
view.php
assets/
 admin.css
 editor.md/
 style.css
data/
 articles/
 site.json
lib/
 Parsedown.php
admin/
 delete.php
 delete_image.php
 images.php
 index.html
 login.php
 logout.php
 manage.php
 write.php
uploads/
 .htaccess
 20250510140139_5404.jpeg
 20250511112924_3977.jpeg
```

### 2. 主要功能模块

#### 2.1 前端部分
- **`assets` 目录**：存放前端资源，包含 CSS 文件和 `editor.md` 编辑器相关文件。
    - **`editor.md`**：开源在线 Markdown 编辑器，版本为 1.5.0。其主要功能如下：
        - **加载资源**：`editormd.js`、`editormd.amd.js` 和 `src/editormd.js` 中的 `loadQueues` 方法用于按顺序加载 CSS 和 JavaScript 文件，如 `codemirror`、`marked`、`prettify` 等，根据配置决定是否加载流程图和序列图相关的脚本。
        - **多语言支持**：`languages/en.js` 提供了英文语言包，包含工具栏、按钮和对话框的文本信息。
        - **插件系统**：包含多个插件，如 `table-dialog`、`code-block-dialog` 和 `help-dialog` 等，用于扩展编辑器功能。
            - **`table-dialog`**：用于插入表格，支持设置行数、列数和对齐方式。
            - **`code-block-dialog`**：支持插入不同语言的代码块，提供了多种编程语言选项。
            - **`help-dialog`**：显示帮助信息，从 `help.md` 文件中获取内容并渲染为 HTML。
- **`uploads` 目录**：存储上传的图片文件，如 `20250510140139_5404.jpeg` 和 `20250511112924_3977.jpeg`。

#### 2.2 后端部分
- **`admin` 目录**：包含博客管理的相关功能，如文章删除、图片管理、登录、注销、文章管理和撰写等。
    - **`delete.php`**：用于删除文章。
    - **`delete_image.php`**：用于删除图片。
    - **`login.php`** 和 `logout.php`**：处理用户的登录和注销操作。
    - **`manage.php`**：用于管理文章。
    - **`write.php`**：用于撰写新文章。
- **`data` 目录**：存储博客的数据，包括文章和网站配置信息。
    - **`articles` 子目录**：存放 Markdown 格式的文章文件，如 `20250510214211.md`。
    - **`site.json`**：可能包含网站的配置信息。
- **`lib` 目录**：包含依赖库，如 `Parsedown.php`，用于解析 Markdown 文本。

#### 2.3 核心文件
- **`config.php`**：可能包含博客系统的配置信息，如数据库连接、文件路径等。
- **`index.php`**：博客系统的入口文件，可能用于显示博客文章列表。
- **`upload.php`**：处理文件上传请求。
- **`view.php`**：用于查看具体的文章内容。

### 3. 依赖库
- **`Parsedown`**：用于解析 Markdown 文本，在 `lib/Parsedown.php` 中引入。
- **`CodeMirror`**：一个用于在浏览器中实现代码编辑的 JavaScript 库，版本为 5.0.0，`editor.md` 编辑器依赖该库实现代码编辑功能。

### 4. 安装步骤
根据 `data/articles/20250510214211.md` 文件中的信息，安装步骤如下：
1. 克隆仓库到 web 目录。
2. 安装依赖库：
```bash
composer require erusev/parsedown
```
3. 设置目录权限：
```bash
chmod -R 755 data/
```
