<?php
/**
* Plugin Name: Screenshot To Media
* Plugin URI: https://github.com/iwowen/Screenshot-to-Media
* Description: 使你的截图能从剪贴板粘贴到媒体库（Enable your screenshots to be pasted from the dialysis to the media library）
* Author: iwowen
* Version: 1.0.0
* Author URI: https://www.iwowen.cn/
* License: GPLv3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
if ( !function_exists( 'screenshot_to_media_is_media' ) ) { 
    function screenshot_to_media_is_media() {
        return in_array($GLOBALS['pagenow'], array('media-new.php', 'upload.php', 'post-new.php', 'post.php'));
    }
}


if ( !function_exists( 'screenshot_to_media_paste_image_to_media' ) ) {
    function screenshot_to_media_paste_image_to_media() {
        if (function_exists('screenshot_to_media_is_media') && screenshot_to_media_is_media()) {
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
                let successed = false
                try {
                    // 文章编辑页面
                    // 如果窗口是打开状态
                    if (wp.media.frame.modal.$el.is(':visible')) {
                        wp.media.frame.uploader.uploader.uploader.addFile(file)
                        successed = true
                    }
                } catch (error) {}
                try {
                    // 媒体新增页面
                    uploader.addFile(file)
                    successed = true
                } catch (error) {}
                try {
                    // 媒体列表页面
                    wp.media.frames.browse.uploader.uploader.uploader.addFile(file)
                    successed = true
                } catch (error) {}
                // 执行成功，清空剪贴板
                if (successed) {
                    function handler(e) {
                        e.clipboardData.setData('text/plain', '');
                        e.preventDefault();
                    }
                    document.addEventListener('copy', handler);   // 增加copy监听
                    document.execCommand('copy');   // 执行copy命令触发监听
                    document.removeEventListener('copy', handler);   // 移除copy监听，不产生影响
                }   
            });
            </script>
        <?php
        }
    };
    
    add_action('admin_footer','screenshot_to_media_paste_image_to_media');
}




