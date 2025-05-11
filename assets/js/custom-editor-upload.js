// 需要在 write.php 页面引入此 JS，并在 editormd 初始化时指定 imageUploadFunction
window.initCustomEditor = function() {
  editormd("md-editor", {
    width: "100%",
    height: 600,
    path : "/assets/editor.md/lib/",
    imageUpload : true,
    imageFormats : ["jpg", "jpeg", "gif", "png", "bmp"],
    imageUploadURL : "/upload.php",
    imageUploadFunction: function (file, callback) {
      // file 只会传单个文件, 但我们自定义 input 可支持多文件
      // 这里什么都不做，交给我们自定义input
    }
  });

  // 替换编辑器图片上传按钮事件，绑定我们自己的多图上传逻辑
  setTimeout(function() {
    // 找到编辑器图片上传按钮
    var $btn = $('.editormd-toolbar li[data-name="image"]');
    if ($btn.length) {
      // 替换点击事件
      $btn.off('click').on('click', function() {
        if ($('#custom-img-upload').length) $('#custom-img-upload').remove();
        var $input = $('<input type="file" id="custom-img-upload" multiple accept="image/*" style="display:none">');
        $('body').append($input);

        $input.on('change', function(e) {
          var files = Array.from(this.files);
          if (!files.length) return;
          $btn.addClass('disabled'); // 禁用按钮
          var uploaded = 0;
          files.forEach(function(file) {
            var fd = new FormData();
            fd.append('editormd-image-file', file);

            fetch('/upload.php', {
              method: 'POST',
              body: fd
            })
            .then(r => r.json())
            .then(res => {
              if (res.success) {
                // 插入图片到编辑器
                window.mdEditor.insertValue(`![图片](${res.url})\n`);
              } else {
                alert(res.message || '上传失败');
              }
            })
            .catch(() => alert('上传出错'))
            .finally(() => {
              uploaded++;
              if (uploaded === files.length) {
                $btn.removeClass('disabled');
                $input.remove();
              }
            });
          });
        });

        $input.trigger('click');
      });
    }
  }, 1000);
};