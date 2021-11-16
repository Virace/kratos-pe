<?php
/**
 *  优化处理
 * @author  Virace
 * @site x-item.com
 * @license GPL-3.0 License
 * @software PhpStorm
 * @version 2021.11.16
 */


// 禁用 Admin Bar
if (!kratos_option('g_adminbar', true)) {
    add_filter('show_admin_bar', '__return_false');
}

// 移除自动保存、修订版本
if (kratos_option('g_post_revision', true)) {
    remove_action('post_updated', 'wp_save_post_revision');
}
// 添加友情链接
add_filter('pre_option_link_manager_enabled', '__return_true');

// 禁用转义
$qmr_work_tags = array('the_title', 'the_excerpt', 'single_post_title', 'comment_author', 'comment_text', 'link_description', 'bloginfo', 'wp_title', 'term_description', 'category_description', 'widget_title', 'widget_text');

foreach ($qmr_work_tags as $qmr_work_tag) {
    remove_filter($qmr_work_tag, 'wptexturize');
}

remove_filter('the_content', 'wptexturize');

//add_filter('run_wptexturize', '__return_false');
/**
 * WordPress 标题中的横线“-”被转义成“–”的问题 - 龙笑天下
 * https://www.ilxtx.com/wordpress-html-entity-decode-title.html
 * 20181213 更新：wp_title、single_post_title、single_cat_title、get_the_title、single_tag_title
 */
remove_filter('the_title', 'wptexturize');
remove_filter('wp_title', 'wptexturize');
remove_filter('single_post_title', 'wptexturize');

// 禁用 Emoji
add_filter('emoji_svg_url', '__return_false');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content', 'wptexturize');
remove_filter('comment_text', 'wptexturize');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('embed_head', 'print_emoji_detection_script');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

// 禁用 Trackbacks
add_filter('xmlrpc_methods', function ($methods) {
    $methods['pingback.ping'] = '__return_false';
    $methods['pingback.extensions.getPingbacks'] = '__return_false';
    return $methods;
});
remove_action('do_pings', 'do_all_pings', 10);
remove_action('publish_post', '_publish_post_hook', 5);

// 优化 wp_head() 内容
foreach (array('rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head') as $action) {
    remove_action($action, 'the_generator');
}
remove_action('wp_head', 'wp_print_head_scripts', 9);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10);
remove_action('wp_head', 'start_post_rel_link', 10);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
remove_action('wp_head', 'wp_shortlink_wp_head', 10);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('template_redirect', 'wp_shortlink_header', 11);
remove_action('template_redirect', 'rest_output_link_header', 11);

// 禁用 WordPress 拼写修正
remove_filter('the_title', 'capital_P_dangit', 11);
remove_filter('the_content', 'capital_P_dangit', 11);
remove_filter('comment_text', 'capital_P_dangit', 31);

// 禁用后台 Google Fonts
add_filter('style_loader_src', function ($href) {
    if (strpos($href, "fonts.googleapis.com") === false) {
        return $href;
    }
    return false;
});

// 禁用 Auto Embeds
remove_filter('the_content', array($GLOBALS['wp_embed'], 'autoembed'), 8);


if (kratos_option('g_gravatar', false)) {
    // 替换 Gravatar 头像源
    function get_https_avatar($avatar)
    {
        $cdn = "gravatar.loli.net";
        $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com", "3.gravatar.com", "secure.gravatar.com", "cn.gravatar.com"), $cdn, $avatar);
        $avatar = str_replace("http://", "https://", $avatar);
        return $avatar;
    }

    add_filter('get_avatar', 'get_https_avatar');
    add_filter('get_avatar_url', 'get_https_avatar');
}

// 禁用自动生成的图片尺寸
add_filter('big_image_size_threshold', '__return_false');
if (kratos_option('g_removeimgsize', false)) {
    function remove_default_images($sizes)
    {
        unset($sizes['thumbnail']);
        unset($sizes['medium']);
        unset($sizes['large']);
        unset($sizes['full']);
        unset($sizes['medium_large']);
        unset($sizes['1536x1536']);
        unset($sizes['2048x2048']);
        return $sizes;
    }

    add_filter('intermediate_image_sizes_advanced', 'remove_default_images');

    remove_image_size('1536x1536');
    remove_image_size('2048x2048');
}
add_filter('big_image_size_threshold', '__return_false');

// 禁用其他图片尺寸
function shapeSpace_disable_other_image_sizes()
{

    remove_image_size('post-thumbnail'); // disable images added via set_post_thumbnail_size()
//    remove_image_size('another-size');   // disable any other added image sizes

}

add_action('init', 'shapeSpace_disable_other_image_sizes');

// 重定向优化
add_action('template_redirect', 'redirect_single_post');
function redirect_single_post()
{
    if (is_search()) {
        global $wp_query;
        if ($wp_query->post_count == 1) {
            wp_redirect(get_permalink($wp_query->posts['0']->ID));
        }
    }
}


// 禁用admin登录,
if (kratos_option('g_no_admin', false)) {
    add_filter('wp_authenticate', 'no_admin_user');
    function no_admin_user($user)
    {
        if ($user == 'admin') {
            exit;
        }
    }

    add_filter('sanitize_user', 'sanitize_user_no_admin', 10, 3);
    function sanitize_user_no_admin($username, $raw_username, $strict)
    {
        if ($raw_username == 'admin' || $username == 'admin') {
            exit;
        }
        return $username;
    }
}

if (kratos_option('g_gutenberg', false)) {
    // 禁用 Gutenberg 编辑器
    add_filter('use_block_editor_for_post', '__return_false');
    remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles');

    // 删除前端的block library的css资源，
    add_action('wp_enqueue_scripts', 'remove_block_library_css', 100);
    function remove_block_library_css()
    {
        wp_dequeue_style('wp-block-library');
    }
}

#######################################################

//// 屏蔽 REST API
//if ( version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
//    function disable_rest_api( $access ) {
//        return new WP_Error( 'rest_cannot_acess', '无访问权限', array( 'status' => 403 ) );
//    }
//    add_filter( 'rest_authentication_errors', 'disable_rest_api' );
//} else {
//    // Filters for WP-API version 1.x
//    add_filter( 'json_enabled', '__return_false' );
//    add_filter( 'json_jsonp_enabled', '__return_false' );
//    // Filters for WP-API version 2.x
//    add_filter( 'rest_enabled', '__return_false' );
//    add_filter( 'rest_jsonp_enabled', '__return_false' );
//}
//// 移除头部 wp-json 标签和 HTTP header 中的 link
//remove_action('wp_head', 'rest_output_link_wp_head', 10 );
//remove_action('template_redirect', 'rest_output_link_header', 11 );

// 仅搜索文章标题
if (kratos_option('g_search', false)) {
    add_filter('posts_search', 'search_enhancement', 10, 2);

    function search_enhancement($search, $wp_query)
    {
        if (!empty($search) && !empty($wp_query->query_vars['search_terms'])) {
            global $wpdb;

            $q = $wp_query->query_vars;
            $n = !empty($q['exact']) ? '' : '%';

            $search = array();

            foreach ((array)$q['search_terms'] as $term) {
                $search[] = $wpdb->prepare("$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like($term) . $n);
            }

            if (!is_user_logged_in()) {
                $search[] = "$wpdb->posts.post_password = ''";
            }

            $search = ' AND ' . implode(' AND ', $search);
        }

        return $search;
    }
}