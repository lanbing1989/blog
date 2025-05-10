<?php
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

if ($_FILES && isset($_FILES['editormd-image-file'])) {
    $file = $_FILES['editormd-image-file'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
            $name = date('YmdHis') . '_' . mt_rand(1000,9999) . '.' . $ext;
            $path = $uploadDir . $name;
            move_uploaded_file($file['tmp_name'], $path);
            $url = '/uploads/' . $name;
            echo json_encode([
                'success' => 1,
                'message' => '上传成功!',
                'url' => $url
            ]);
            exit;
        }
    }
}
echo json_encode([
    'success' => 0,
    'message' => '上传失败！'
]);