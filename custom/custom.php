<?php
// 请在第三行开始编写代码


function aabb()
{
//    wp_enqueue_style('aa', ASSET_PATH . '/src/css/lightgallery.js/lightgallery.css', array(), THEME_VERSION);
//    wp_enqueue_style('aaq', ASSET_PATH . '/src/css/lightgallery.js/lg-transitions.css', array(), THEME_VERSION);
//    wp_enqueue_script('bb', ASSET_PATH . '/src/js/lightgallery.js/lightgallery.js', array(), '2.8.0', true);
//    wp_enqueue_script('bbq', ASSET_PATH . '/src/js/lightgallery.js/lg-thumbnail.js', array(), '2.8.0', true);


    wp_enqueue_script('custom', get_template_directory_uri() . '/custom/custom.js', array(), THEME_VERSION, true);
}

add_action('wp_enqueue_scripts', 'aabb');
//update_option('image_default_link_type', 'file');

function auto_post_link($content) {
    global $post;
    $content = preg_replace('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', "<li data-src=\"$2\" data-sub-html=\"".$post->post_title."\" ><img src=\"$2\" alt=\"".$post->post_title."\" /></li>", $content);
    return $content;
}
//add_filter ('the_content', 'auto_post_link',0);