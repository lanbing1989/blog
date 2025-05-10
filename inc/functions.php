<?php
function get_site_info() {
    $file = __DIR__ . '/../data/site.json';
    if (file_exists($file)) {
        $info = json_decode(file_get_contents($file), true);
        if (is_array($info)) return $info;
    }
    // 默认信息
    return [
        'title' => '我的博客',
        'subtitle' => '',
        'footer' => '© '.date('Y').' 我的博客'
    ];
}