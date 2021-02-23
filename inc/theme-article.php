<?php
/**
 * 文章相关函数
 * @author Seaton Jiang <seaton@vtrois.com>
 * @license MIT License
 * @version 2020.06.13
 */

// 文章链接添加 target 和 rel
function content_nofollow($content)
{
    $regexp = "<a\s[^>]*href=['\"][^#]([^'\"]*?)\\1[^>]*>";
    if (preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
        if (!empty($matches)) {
            $srcUrl = get_option('siteurl');
            for ($i = 0; $i < count($matches); $i++) {
                $tag = $matches[$i][0];
                $tag2 = $matches[$i][0];
                $url = $matches[$i][0];
                $noFollow = '';
                $pattern = '/target\s*=\s*"\s*_blank\s*"/';
                preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
                if (count($match) < 1) {
                    $noFollow .= ' target="_blank" ';
                }

                $pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
                preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
                if (count($match) < 1) {
                    $noFollow .= ' rel="nofollow" ';
                }

                $pos = strpos($url, $srcUrl);
                if ($pos === false) {
                    $tag = rtrim($tag, '>');
                    $tag .= $noFollow . '>';
                    $content = str_replace($tag2, $tag, $content);
                }
            }
        }
    }
    $content = str_replace(']]>', ']]>', $content);
    return $content;
}

add_filter('the_content', 'content_nofollow');

// 文章点赞
function love()
{
    global $wpdb, $post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ($action == 'love') {
        $raters = get_post_meta($id, 'love', true);
        $expire = time() + 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie('love_' . $id, $id, $expire, '/', $domain, false);
        if (!$raters || !is_numeric($raters)) {
            update_post_meta($id, 'love', 1);
        } else {
            update_post_meta($id, 'love', ($raters + 1));
        }
        echo get_post_meta($id, 'love', true);
    }
    die;
}

add_action('wp_ajax_nopriv_love', 'love');
add_action('wp_ajax_love', 'love');

// 文章阅读次数统计
function set_post_views()
{
    if (is_singular()) {
        global $post;
        $post_ID = $post->ID;
        if ($post_ID) {
            $post_views = (int)get_post_meta($post_ID, 'views', true);
            if (!update_post_meta($post_ID, 'views', ($post_views + 1))) {
                add_post_meta($post_ID, 'views', 1, true);
            }
        }
    }
}

add_action('wp_head', 'set_post_views');

function get_post_views($echo = 1)
{
    global $post;
    $post_ID = $post->ID;
    $views = (int)get_post_meta($post_ID, 'views', true);
    return $views;
}

// 文章列表简介内容
function excerpt_length($length)
{
    return 260;
}

add_filter('excerpt_length', 'excerpt_length');

// 开启特色图
add_theme_support("post-thumbnails");

// 文章特色图片
function post_thumbnail()
{
    global $post;
    $title = get_the_title();
    $img_id = get_post_thumbnail_id();
    $img_url = wp_get_attachment_image_src($img_id, array(720, 435));
    if (is_array($img_url)) {
        $img_url = $img_url[0];
    }
    if (has_post_thumbnail()) {
        echo '<img src="' . $img_url . '" alt="' . $title . '"/>';
    } else {
        $content = $post->post_content;
        $img_preg = "/<img (.*?)src=\"(.+?)\".*?>/";
        preg_match($img_preg, $content, $img_src);
        $img_count = count($img_src) - 1;
        if (isset($img_src[$img_count])) {
            $img_val = $img_src[$img_count];
        }
        if (!empty($img_val)) {
            echo '<img src="' . $img_val . '" alt="' . $title . '" />';
        } else {
            if (!kratos_option('g_postthumbnail')) {
                $img = ASSET_PATH . '/assets/img/default.jpg';
            } else {
                $img = kratos_option('g_postthumbnail', ASSET_PATH . '/assets/img/default.jpg');
            }
            echo '<img src="' . $img . '" alt="' . get_bloginfo('name') . '" />';
        }
    }
}

function post_thumbnail_url(){
    $img_id = get_post_thumbnail_id();
    $img_url = wp_get_attachment_image_src($img_id, array(720, 435));
    if (is_array($img_url)) {
        $img_url = $img_url[0];
    }
    if (has_post_thumbnail()) {
        return $img_url;
    } else {
        if (!kratos_option('g_postthumbnail')) {
            $img = ASSET_PATH . '/assets/img/default.jpg';
        } else {
            $img = kratos_option('g_postthumbnail', ASSET_PATH . '/assets/img/default.jpg');
        }
        return $img;
    }
}

// 文章列表分页
function pagelist($range = 5)
{
    global $paged, $wp_query, $max_page;
    if (!$max_page) {
        $max_page = $wp_query->max_num_pages;
    }
    if ($max_page > 1) {
        if (!$paged) {
            $paged = 1;
        }
        echo "<div class='paginations'>";
        if ($paged > 1) {
            echo '<a href="' . get_pagenum_link($paged - 1) . '" class="prev" title="上一页"><i class="vicon i-larrows"></i></a>';
        }
        if ($max_page > $range) {
            if ($paged < $range) {
                for ($i = 1; $i <= $range; $i++) {
                    if ($i == $paged) {
                        echo '<span class="page-numbers current">' . $i . '</span>';
                    } else {
                        echo "<a href='" . get_pagenum_link($i) . "'>$i</a>";
                    }
                }
                echo '<span class="page-numbers dots">…</span>';
                echo "<a href='" . get_pagenum_link($max_page) . "'>$max_page</a>";
            } elseif ($paged >= ($max_page - ceil(($range / 2)))) {
                if ($paged != 1) {
                    echo "<a href='" . get_pagenum_link(1) . "' class='extend' title='首页'>1</a>";
                    echo '<span class="page-numbers dots">…</span>';
                }
                for ($i = $max_page - $range + 1; $i <= $max_page; $i++) {
                    if ($i == $paged) {
                        echo '<span class="page-numbers current">' . $i . '</span>';
                    } else {
                        echo "<a href='" . get_pagenum_link($i) . "'>$i</a>";
                    }
                }
            } elseif ($paged >= $range && $paged < ($max_page - ceil(($range / 2)))) {
                if ($paged != 1) {
                    echo "<a href='" . get_pagenum_link(1) . "' class='extend' title='首页'>1</a>";
                    echo '<span class="page-numbers dots">…</span>';
                }
                for ($i = ($paged - ceil($range / 3)); $i <= ($paged + ceil(($range / 3))); $i++) {
                    if ($i == $paged) {
                        echo '<span class="page-numbers current">' . $i . '</span>';
                    } else {
                        echo "<a href='" . get_pagenum_link($i) . "'>$i</a>";
                    }
                }
                echo '<span class="page-numbers dots">…</span>';
                echo "<a href='" . get_pagenum_link($max_page) . "'>$max_page</a>";
            }
        } else {
            for ($i = 1; $i <= $max_page; $i++) {
                if ($i == $paged) {
                    echo '<span class="page-numbers current">' . $i . '</span>';
                } else {
                    echo "<a href='" . get_pagenum_link($i) . "'>$i</a>";
                }
            }
        }
        if ($paged < $max_page) {
            echo '<a href="' . get_pagenum_link($paged + 1) . '" class="next" title="下一页"><i class="vicon i-rarrows"></i></a>';
        }
        echo "</div>";
    }
}


function seo_meta_box()
{
    return new WPMetaBox
    (
        'seo-meta',
        __('SEO 设置', 'kratos'),
        'post',
        [
            array(
                'title' => __('SEO描述', 'kratos'),
                'key' => 'description',
                'type' => 'textarea',
                'desc' => __('搜索引擎抓取的文章描述. 如果留空则使用文章摘要, 如果摘要为空则自动截取文章内容.', 'kratos')
            ),
            array(
                'title' => __('SEO关键词', 'kratos'),
                'key' => 'keywords',
                'type' => 'textarea',
                'desc' => __('搜索引擎抓取的关键词. 英文 , 分隔. 如果留空则使用文章标签.', 'kratos')
            )
        ]
    );
}

add_action('load-post.php', 'seo_meta_box');
add_action('load-post-new.php', 'seo_meta_box');


function toc_meta_box()
{
    return new WPMetaBox
    (
        'toc-meta',
        __('文章目录', 'kratos'),
        'post',
        [
            array(
                'title' => __('是否显示文章目录', 'kratos'),
                'key' => 'switch',
                'type' => 'checkbox'
            )
        ]
    );
}

add_action('load-post.php', 'toc_meta_box');
add_action('load-post-new.php', 'toc_meta_box');


/*
Plugin Name: Duplicate Page
Plugin URI: https://wordpress.org/plugins/duplicate-page/
Description: Duplicate Posts, Pages and Custom Posts using single click.
Author: mndpsingh287
Version: 4.3
Author URI: https://profiles.wordpress.org/mndpsingh287/
License: GPLv2
Text Domain: duplicate-page
*/
// 代码来自duplicate-page插件, 作为文章复制
if (!class_exists('duplicate_page') && kratos_option('g_duplicate_page', false)):
    class duplicate_page
    {
        /*
        * AutoLoad Hooks
        */
        public function __construct()
        {
            register_activation_hook(__FILE__, array(&$this, 'duplicate_page_install'));

            add_action('admin_action_dt_duplicate_post_as_draft', array(&$this, 'dt_duplicate_post_as_draft'));
            add_filter('post_row_actions', array(&$this, 'dt_duplicate_post_link'), 10, 2);
            add_filter('page_row_actions', array(&$this, 'dt_duplicate_post_link'), 10, 2);

        }

        /*
        * Main function
        */
        public function dt_duplicate_post_as_draft()
        {
            /*
            * get Nonce value
            */
            $nonce = $_REQUEST['nonce'];
            /*
            * get the original post id
            */
            $post_id = (isset($_GET['post']) ? intval($_GET['post']) : intval($_POST['post']));

            if (wp_verify_nonce($nonce, 'dt-duplicate-page-' . $post_id) && current_user_can('edit_posts')) {
                // verify Nonce
                global $wpdb;
                $suffix = 'copy';
                $post_status = 'draft';
                $redirectit = 'to_list';
                if (!(isset($_GET['post']) || isset($_POST['post']) || (isset($_REQUEST['action']) && 'dt_duplicate_post_as_draft' == $_REQUEST['action']))) {
                    wp_die('No post to duplicate has been supplied!');
                }
                $returnpage = '';
                /*
                * and all the original post data then
                */
                $post = get_post($post_id);
                /*
                * if you don't want current user to be the new post author,
                * then change next couple of lines to this: $new_post_author = $post->post_author;
                */
                $current_user = wp_get_current_user();
                $new_post_author = $current_user->ID;
                /*
                * if post data exists, create the post duplicate
                */
                if (isset($post) && $post != null) {
                    /*
                    * new post data array
                    */
                    $args = array(
                        'comment_status' => $post->comment_status,
                        'ping_status' => $post->ping_status,
                        'post_author' => $new_post_author,
                        'post_content' => wp_slash($post->post_content),
                        'post_excerpt' => $post->post_excerpt,
                        //'post_name' => $post->post_name,
                        'post_parent' => $post->post_parent,
                        'post_password' => $post->post_password,
                        'post_status' => $post_status,
                        'post_title' => $post->post_title . $suffix,
                        'post_type' => $post->post_type,
                        'to_ping' => $post->to_ping,
                        'menu_order' => $post->menu_order,
                    );
                    /*
                    * insert the post by wp_insert_post() function
                    */
                    $new_post_id = wp_insert_post($args);
                    /*
                    * get all current post terms ad set them to the new post draft
                    */
                    $taxonomies = get_object_taxonomies($post->post_type);
                    if (!empty($taxonomies) && is_array($taxonomies)):
                        foreach ($taxonomies as $taxonomy) {
                            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
                        }
                    endif;
                    /*
                    * duplicate all post meta
                    */
                    $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
                    if (count($post_meta_infos) != 0) {
                        $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                        foreach ($post_meta_infos as $meta_info) {
                            $meta_key = sanitize_text_field($meta_info->meta_key);
                            $meta_value = addslashes($meta_info->meta_value);
                            $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
                        }
                        $sql_query .= implode(" UNION ALL ", $sql_query_sel);
                        $wpdb->query($sql_query);
                    }
                    /*
                    * finally, redirecting to your choice
                    */
                    if ($post->post_type != 'post'):
                        $returnpage = '?post_type=' . $post->post_type;
                    endif;
                    if (!empty($redirectit) && $redirectit == 'to_list'):
                        wp_redirect(admin_url('edit.php' . $returnpage));
                    elseif (!empty($redirectit) && $redirectit == 'to_page'):
                        wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
                    else:
                        wp_redirect(admin_url('edit.php' . $returnpage));
                    endif;
                    exit;
                } else {
                    wp_die('Error! Post creation failed, could not find original post: ' . $post_id);
                }
            } else {
                wp_die('Security check issue, Please try again.');
            }
        }

        /*
         * Add the duplicate link to action list for post_row_actions
         */
        public function dt_duplicate_post_link($actions, $post)
        {
            if (current_user_can('edit_posts')) {
                $actions['duplicate'] = '<a href="admin.php?action=dt_duplicate_post_as_draft&amp;post=' . $post->ID . '&amp;nonce=' . wp_create_nonce('dt-duplicate-page-' . $post->ID) . '" title="' . __('复制文章到草稿') . '" rel="permalink">' . __('复制', 'kratos') . '</a>';
            }

            return $actions;
        }


        /*
         * Redirect function
        */
        public static function dp_redirect($url)
        {
            echo '<script>window.location.href="' . $url . '"</script>';
        }


    }

    new duplicate_page();
endif;

// 文章目录
function toc_set_anchor($content)
{
    $post_id = get_the_ID();
    if (doing_filter('get_the_excerpt') || !is_singular() || $post_id != get_queried_object_id()) {
        return $content;
    }

    global $toc_count, $toc_items;

    $toc_items = [];
    $toc_count = 0;

    // 取锚点, 正常设置到h3即可
    $regex = '#<h([1-6])(.*?)>(.*?)</h\1>#';

    $content = preg_replace_callback($regex, function ($matches) {
        global $toc_count, $toc_items;

        $toc_count++;
        $toc_items[] = ['text' => trim(strip_tags($matches[3])), 'depth' => $matches[1], 'count' => $toc_count];

        return "<h{$matches[1]} {$matches[2]}><a id=\"toc-{$toc_count}\"></a>{$matches[3]}</h{$matches[1]}>";
    }, $content);


    return $content;
}

add_filter('the_content', 'toc_set_anchor');


if (!function_exists('get_toc')):
    /**
     * 生成文章目录
     * @return string
     */
    function get_toc()
    {
        global $toc_items;

        if (empty($toc_items)) {
            return '';
        }

        $index = '<ul class="nav flex-column">' . "\n";
        $prev_depth = 0;
        $to_depth = 0;
        foreach ($toc_items as $toc_item) {
            $toc_depth = $toc_item['depth'];

            if ($prev_depth) {
                if ($toc_depth == $prev_depth) {
                    $index .= '</li>' . "\n";
                } elseif ($toc_depth > $prev_depth) {
                    $to_depth++;
                    $index .= '<ul role="tablist">' . "\n";
                } else {
                    $to_depth2 = ($to_depth > ($prev_depth - $toc_depth)) ? ($prev_depth - $toc_depth) : $to_depth;

                    if ($to_depth2) {
                        for ($i = 0; $i < $to_depth2; $i++) {
                            $index .= '</li>' . "\n" . '</ul>' . "\n";
                            $to_depth--;
                        }
                    }

                    $index .= '</li>';
                }
            }

            $prev_depth = $toc_depth;

            $index .= '<li class="nav-item"><a class="nav-link" href="#toc-' . $toc_item['count'] . '">' . $toc_item['text'] . '</a>';
        }

        for ($i = 0; $i <= $to_depth; $i++) {
            $index .= '</li>' . "\n" . '</ul>' . "\n";
        }

        return $index;
    }
endif;


// 添加修订次数统计
add_action('publish_page', 'add_revision_times', 10, 2);
add_action('publish_post', 'add_revision_times', 10, 2);
function add_revision_times($post_ID){
    // 判断一下请求来源, rest api排除, 否则会触发两次
    if (!(defined('REST_REQUEST') && REST_REQUEST)) {
        $key = 'revision_times';
        if (!(wp_is_post_revision($post_ID) || wp_is_post_autosave($post_ID))) {
            $times = get_post_meta($post_ID, $key, true);;
            if ($times) {
                update_post_meta($post_ID, $key, intval($times) + 1);
            } else {
                add_post_meta($post_ID, $key, 1, true);
            }
        }
    }
}
