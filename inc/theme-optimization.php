<?php
// 禁用 Admin Bar
add_filter('show_admin_bar', '__return_false');

// 移除自动保存、修订版本
remove_action('post_updated', 'wp_save_post_revision');

// 添加友情链接
add_filter('pre_option_link_manager_enabled', '__return_true');

// 禁用转义
$qmr_work_tags = array('the_title', 'the_excerpt', 'single_post_title', 'comment_author', 'comment_text', 'link_description', 'bloginfo', 'wp_title', 'term_description', 'category_description', 'widget_title', 'widget_text');

foreach ($qmr_work_tags as $qmr_work_tag) {
    remove_filter($qmr_work_tag, 'wptexturize');
}

remove_filter('the_content', 'wptexturize');
add_filter('run_wptexturize', '__return_false');

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

// 替换国内 Gravatar 源
function get_https_avatar($avatar)
{
    if (kratos_option('g_gravatar', false)) {
        $cdn = "gravatar.loli.net";
    } else {
        $cdn = "cn.gravatar.com";
    }

    $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com", "3.gravatar.com", "secure.gravatar.com"), $cdn, $avatar);
    $avatar = str_replace("http://", "https://", $avatar);
    return $avatar;
}

add_filter('get_avatar', 'get_https_avatar');
// 禁止生成多种尺寸图片
if (kratos_option('g_removeimgsize', false)) {
    function remove_default_images($sizes)
    {
        unset($sizes['thumbnail']);
        unset($sizes['medium']);
        unset($sizes['large']);
        unset($sizes['medium_large']);
        return $sizes;
    }

    add_filter('intermediate_image_sizes_advanced', 'remove_default_images');
}

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

function get_404()
{
    global $wpdb, $wp_rewrite;

    if (get_query_var('name')) {
        $where = $wpdb->prepare("post_name LIKE %s", like_escape(get_query_var('name')) . '%');

        $post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE $where AND post_status = 'publish'");
        if (!$post_id)
            return false;
        if (get_query_var('feed'))
            return get_post_comments_feed_link($post_id, get_query_var('feed'));
        elseif (get_query_var('page'))
            return trailingslashit(get_permalink($post_id)) . user_trailingslashit(get_query_var('page'), 'single_paged');
        else
            return get_permalink($post_id);
    }

    return false;
}

//解决日志改变 post type 之后跳转错误的问题，
add_action('template_redirect', 'old_slug_redirect');
function old_slug_redirect()
{
    global $wp_query;
    if (is_404() && '' != $wp_query->query_vars['name']) :
        global $wpdb;

        $query = $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_old_slug' AND meta_value = %s", $wp_query->query_vars['name']);

        $id = (int)$wpdb->get_var($query);

        if (!$id) {
            $link = get_404();
        } else {
            $link = get_permalink($id);
        }

        if (!$link)
            return;

        wp_redirect($link, 301);
        exit;
    endif;
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
function get_current_page_url()
{
    return set_url_scheme('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

add_action('template_redirect', function () {
    if (!is_404()) {
        return;
    }

    $request_url = get_current_page_url();

    if (strpos($request_url, 'feed/atom/') !== false) {
        wp_redirect(str_replace('feed/atom/', '', $request_url), 301);
        exit;
    }

    if (strpos($request_url, 'comment-page-') !== false) {
        wp_redirect(preg_replace('/comment-page-(.*)\//', '', $request_url), 301);
        exit;
    }

    if (strpos($request_url, 'page/') !== false) {
        wp_redirect(preg_replace('/page\/(.*)\//', '', $request_url), 301);
        exit;
    }

    if ($_301_redirects = get_option('301-redirects')) {
        foreach ($_301_redirects as $_301_redirect) {
            if ($_301_redirect['request'] == $request_url) {
                wp_redirect($_301_redirect['destination'], 301);
                exit;
            }
        }
    }
}, 99);



