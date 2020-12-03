<?php
/*
Plugin Name: copy image
Plugin URI: http://wordpress.org/plugins/copy-image/
Description: 复制剪贴板图片到媒体库
Author: iwowen
Version: 1.7.2
Author URI: https://www.iwowen.cn/
*/

function iwowen_copy() {
    ?>
        <script type="text/javascript">
        document.addEventListener('paste', function (event) {
            var items = event.clipboardData && event.clipboardData.items;
            var file = null;
            if (items && items.length) {
                // 检索剪切板items
                for (var i = 0; i < items.length; i++) {
                    if (items[i].type.indexOf('image') !== -1) {
                        file = items[i].getAsFile();
                        break;
                    }
                }
            }
            if (!file) return
            try {
                // 文章编辑页面
                // 如果窗口是打开状态
                if (wp.media.frame.modal.$el.is(':visible')) {
                    wp.media.frame.uploader.uploader.uploader.addFile(file)
                }
            } catch (error) {}
            try {
                // 媒体新增页面
                uploader.addFile(file)
            } catch (error) {}
            try {
                // 媒体列表页面
                wp.media.frames.browse.uploader.uploader.uploader.addFile(file)
            } catch (error) {}
            // 此时file就是剪切板中的图片文件
        });
        </script>
    <?php
};

add_action('admin_footer','iwowen_copy');

