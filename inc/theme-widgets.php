<?php
/**
 * 侧栏小工具
 * @author Seaton Jiang <seaton@vtrois.com> (Modified by Virace)
 * @site x-item.com
 * @license GPL-3.0 License
 * @software PhpStorm
 * @version 2021.11.24
 */

//取最后一次活动时间. 徒增功耗
function last_login()
{
    global $wpdb;
    $date1 = $wpdb->get_var("SELECT comment_date FROM $wpdb->comments WHERE user_id = 1 ORDER BY comment_date DESC");
    $date2 = $wpdb->get_var("SELECT post_modified FROM $wpdb->posts WHERE post_author = 1 ORDER BY post_modified DESC");
    $date1 = strtotime(empty($date1) ? 0 : $date1);
    $date2 = strtotime(empty($date2) ? 0 : $date2);
    $to = strtotime(wp_date('Y-m-d H:i:s'));
    echo $date1 < $date2 ? human_time_diff($date2, $to) : human_time_diff($date1, $to);

}

// 格式化时间
function timeago($ptime): string
{
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if ($etime < 1) return __('刚刚', 'kratos');
    $interval = array(
        12 * 30 * 24 * 60 * 60 => __(' 年前', 'kratos'),
        30 * 24 * 60 * 60 => __(' 个月前', 'kratos'),
        7 * 24 * 60 * 60 => __(' 周前', 'kratos'),
        24 * 60 * 60 => __(' 天前', 'kratos'),
        60 * 60 => __(' 小时前', 'kratos'),
        60 => __(' 分钟前', 'kratos'),
        1 => __(' 秒前', 'kratos'),
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str . ' (' . date(__('m月d日', 'kratos'), $ptime) . ')';
        }
    };
}


// 添加小工具
function widgets_init()
{
    register_sidebar(array(
        'name' => __('主页侧边栏', 'kratos'),
        'id' => 'home_sidebar',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="title">',
        'after_title' => '</div>',
    ));
    register_sidebar(array(
        'name' => __('文章侧边栏', 'kratos'),
        'id' => 'single_sidebar',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="title">',
        'after_title' => '</div>',
    ));

    // 去掉默认小工具
    $wp_widget = array(
        'WP_Widget_Archives',
        'WP_Widget_Categories',
        'WP_Widget_Calendar',
        'WP_Widget_Pages',
        'WP_Widget_Meta',
        'WP_Widget_Recent_Posts',
        'WP_Widget_Recent_Comments',
        'WP_Widget_RSS',
        'WP_Widget_Tag_Cloud',
        'WP_Nav_Menu_Widget',
    );

    foreach ($wp_widget as $wp_widget) {
        unregister_widget($wp_widget);
    }
}

add_action('widgets_init', 'widgets_init');

// 分类目录计数
function cat_count_span($links)
{
    $links = str_replace('</a> (', '<span> / ', $links);
    $links = str_replace(')', __('篇', 'kratos') . '</span></a>', $links);
    return $links;
}

add_filter('wp_list_categories', 'cat_count_span');

// 文章归档计数
function archive_count_span($links)
{
    $links = str_replace('</a>&nbsp;(', '<span> / ', $links);
    $links = str_replace(')', __('篇', 'kratos') . '</span></a>', $links);
    return $links;
}

add_filter('get_archives_link', 'archive_count_span');


// 小工具文章聚合 - 热点文章
function most_comm_posts($days = 30, $nums = 6)
{
    global $wpdb;
    date_default_timezone_set("PRC");
    $today = date("Y-m-d H:i:s");
    $daysago = date("Y-m-d H:i:s", strtotime($today) - ($days * 24 * 60 * 60));
    $result = $wpdb->get_results($wpdb->prepare("SELECT comment_count, ID, post_title, post_date FROM $wpdb->posts WHERE post_date BETWEEN %s AND %s and post_type = 'post' AND post_status = 'publish' ORDER BY comment_count DESC LIMIT 0, %d", $daysago, $today, $nums));
    $output = '';
    if (!empty($result)) {
        foreach ($result as $topten) {
            $postid = $topten->ID;
            $title = esc_attr(strip_tags($topten->post_title));
            $commentcount = $topten->comment_count;
            if ($commentcount >= 0) {
                $output .= '<a class="bookmark-item" title="' . $title . '" href="' . get_permalink($postid) . '" rel="bookmark"><i class="vicon i-book"></i>';
                $output .= $title;
                $output .= '</a>';
            }
        }
    }
    echo $output;
}

class widget_ad extends WP_Widget
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'scripts'));

        $widget_ops = array(
            'name' => __('图片广告', 'kratos'),
            'description' => __('显示自定义图片广告的工具', 'kratos'),
            'classname' => 'w-ad'
        );

        parent::__construct(false, false, $widget_ops);
    }

    public function scripts()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
        wp_enqueue_script('widget_scripts', ASSET_PATH . '/assets/js/widget.min.js', array('jquery'));
        wp_enqueue_style('widget_css', ASSET_PATH . '/assets/css/widget.min.css', array());
    }

    public function widget($args, $instance)
    {
        extract($args);
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : __('广告', 'kratos');
        $image = !empty($instance['image']) ? $instance['image'] : '';
        $url = !empty($instance['url']) ? $instance['url'] : '';

        echo $before_widget;
        echo '<a href="' . $url . '" target="_blank" rel="noreferrer"><img src="' . $image . '"><div class="prompt">' . $subtitle . '</div></a>';
        echo '<!-- .w-ad -->';
        echo $after_widget;
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();

        $instance['subtitle'] = (!empty($new_instance['subtitle'])) ? $new_instance['subtitle'] : '';
        $instance['image'] = (!empty($new_instance['image'])) ? $new_instance['image'] : '';
        $instance['url'] = (!empty($new_instance['url'])) ? $new_instance['url'] : '';

        return $instance;
    }

    public function form($instance)
    {
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : __('广告', 'kratos');
        $image = !empty($instance['image']) ? $instance['image'] : '';
        $url = !empty($instance['url']) ? $instance['url'] : '';
        ?>
        <div class="media-widget-control">
            <p>
                <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('副标题：', 'kratos'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>"
                       name="<?php echo $this->get_field_name('subtitle'); ?>" type="text"
                       value="<?php echo esc_attr($subtitle); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('链接地址：', 'kratos'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>"
                       name="<?php echo $this->get_field_name('url'); ?>" type="text"
                       value="<?php echo esc_attr($url); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('广告图片:', 'kratos'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>"
                       name="<?php echo $this->get_field_name('image'); ?>" type="text"
                       value="<?php echo esc_url($image); ?>"/>
                <button type="button" class="button-update-media upload_ad"><?php _e('选择图片', 'kratos'); ?></button>
            </p>
        </div>
        <?php
    }
}

class widget_search extends WP_Widget
{

    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget_search',
            'description' => __('一个搜索框', 'kratos'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('search', __('搜索', 'kratos'), $widget_ops);
    }

    public function widget($args, $instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        echo '<div class="widget w-search">';
        if ($title) {
            echo '<div class="title">' . $title . '</div>';
        }
        echo '<div class="item"> <form role="search" method="get" id="searchform" class="searchform" action="' . home_url('/') . '"> <div class="input-group mt-2 mb-2"> <input type="text" name="s" id="search-widgets" class="form-control" placeholder="' . __('搜点什么呢?', 'kratos') . '"> <div class="input-group-append"> <button class="btn btn-primary btn-search" type="submit" id="searchsubmit">' . __('搜索', 'kratos') . '</button> </div> </div> </form>';
        echo '</div></div>';
    }

    public function form($instance)
    {
        $instance = wp_parse_args((array)$instance, array('title' => ''));
        $title = $instance['title'];
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题:', 'kratos'); ?> <input class="widefat"
                                                                                                         id="<?php echo $this->get_field_id('title'); ?>"
                                                                                                         name="<?php echo $this->get_field_name('title'); ?>"
                                                                                                         type="text"
                                                                                                         value="<?php echo esc_attr($title); ?>"/></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $new_instance = wp_parse_args((array)$new_instance, array('title' => ''));
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }

}

class widget_about extends WP_Widget
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'scripts'));

        $widget_ops = array(
            'name' => __('个人简介', 'kratos'),
            'description' => __('可跳转后台的个人简介展示工具', 'kratos'),
            'classname' => 'w-about',
        );

        parent::__construct(false, false, $widget_ops);
    }

    public function scripts()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
        wp_enqueue_script('widget_scripts', ASSET_PATH . '/assets/js/widget.min.js', array('jquery'));
        wp_enqueue_style('widget_css', ASSET_PATH . '/assets/css/widget.min.css', array());
    }

    public function widget($args, $instance)
    {
        extract($args);
        $introduce = !empty(get_the_author_meta('description', '1')) ? get_the_author_meta('description', '1') : __('这个人很懒，什么都没留下', 'kratos');
        $username = get_the_author_meta('display_name', '1');
        $avatar = get_avatar_url('1');
        $background = !empty($instance['background']) ? $instance['background'] : ASSET_PATH . '/assets/img/about-background.png';

        echo $before_widget;
        echo '<div class="background" style="background:url(' . $background . ') no-repeat center center;-webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"></div><div class="wrapper text-center">';
        if (current_user_can('manage_options')) {
            echo '<a href="' . admin_url() . '">';
        } else {
            echo '<a href="' . wp_login_url() . '">';
        }
        echo '<img src="' . $avatar . '"></a>';
        echo '</div><div class="textwidget text-center"><p class="username">' . $username . '</p><p class="about">' . $introduce . '</p></div>';
        echo '<!-- .w-about -->';
        echo $after_widget;
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();

        $instance['background'] = (!empty($new_instance['background'])) ? $new_instance['background'] : '';

        return $instance;
    }

    public function form($instance)
    {
        $background = !empty($instance['background']) ? $instance['background'] : ASSET_PATH . '/assets/img/about-background.png';
        ?>
        <div class="media-widget-control">
            <p>
                <label for="<?php echo $this->get_field_id('background'); ?>"><?php _e('背景图片:', 'kratos'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('background'); ?>"
                       name="<?php echo $this->get_field_name('background'); ?>" type="text"
                       value="<?php echo esc_attr($background); ?>">
                <button type="button"
                        class="button-update-media upload_background"><?php _e('选择图片', 'kratos'); ?></button>
            </p>
        </div>
        <?php
    }
}

class widget_tags extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array(
            'name' => __('标签聚合', 'kratos'),
            'description' => __('文章标签的展示工具', 'kratos'),
            'classname' => 'w-tags'
        );

        parent::__construct(false, false, $widget_ops);
    }

    public function widget($args, $instance)
    {
        extract($args);
        $number = !empty($instance['number']) ? $instance['number'] : '8';
        $order = !empty($instance['order']) ? $instance['order'] : 'RAND';
        $tags = wp_tag_cloud(array(
                'unit' => 'px',
                'smallest' => 14,
                'largest' => 14,
                'number' => $number,
                'format' => 'flat',
                'orderby' => 'count',
                'order' => $order,
                'echo' => false,
            )
        );
        echo $before_widget;
        echo '<div class="title">' . __('标签聚合', 'kratos') . '</div>';
        echo '<div class="item">' . $tags . '</div>';
        echo '<!-- .w-tags -->';
        echo $after_widget;
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();

        $instance['number'] = (!empty($new_instance['number'])) ? $new_instance['number'] : '';
        $instance['order'] = (!empty($new_instance['order'])) ? $new_instance['order'] : '';

        return $instance;
    }

    public function form($instance)
    {
        global $wpdb;
        $number = !empty($instance['number']) ? $instance['number'] : '8';
        $order = !empty($instance['order']) ? $instance['order'] : 'RAND';
        ?>
        <div class="media-widget-control">
            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('显示数量：', 'kratos'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>"
                       name="<?php echo $this->get_field_name('number'); ?>" type="text"
                       value="<?php echo esc_attr($number); ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('显示排序：', 'kratos'); ?></label>
                <select name="<?php echo $this->get_field_name("order"); ?>"
                        id='<?php echo $this->get_field_id("order"); ?>'>
                    <option value="DESC" <?php echo ($order == 'DESC') ? 'selected' : ''; ?>><?php _e('降序', 'kratos'); ?></option>
                    <option value="ASC" <?php echo ($order == 'ASC') ? 'selected' : ''; ?>><?php _e('升序', 'kratos'); ?></option>
                    <option value="RAND" <?php echo ($order == 'RAND') ? 'selected' : ''; ?>><?php _e('随机', 'kratos'); ?></option>
                </select>
            </p>
        </div>
        <?php
    }
}

class widget_posts extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array(
            'name' => __('文章聚合', 'kratos'),
            'description' => __('展示最热、随机、最新文章的工具', 'kratos'),
            'classname' => 'w-recommended'
        );

        parent::__construct(false, false, $widget_ops);
    }

    public function widget($args, $instance)
    {
        extract($args);
        $number = !empty($instance['number']) ? $instance['number'] : '6';
        $days = !empty($instance['days']) ? $instance['days'] : '30';

        echo $before_widget;
        ?>
        <div class="nav nav-tabs d-none d-xl-flex" id="nav-tab" role="tablist">
            <a class="nav-item nav-link" id="nav-new-tab" data-toggle="tab" href="#nav-new" role="tab"
               aria-controls="nav-new" aria-selected="false"><i class="vicon i-tab-new"></i><?php _e('最新', 'kratos'); ?>
            </a>
            <a class="nav-item nav-link active" id="nav-hot-tab" data-toggle="tab" href="#nav-hot" role="tab"
               aria-controls="nav-hot" aria-selected="true"><i
                        class="vicon i-tab-fire-fill"></i><?php _e('热点', 'kratos'); ?>
            </a>
            <a class="nav-item nav-link" id="nav-random-tab" data-toggle="tab" href="#nav-random" role="tab"
               aria-controls="nav-random" aria-selected="false"><i
                        class="vicon i-tab-random"></i><?php _e('随机', 'kratos'); ?></a>
        </div>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab">
                <?php $myposts = get_posts('numberposts=' . $number . ' & offset=0');
                foreach ($myposts as $post): ?>
                    <a class="bookmark-item" title="<?php echo $post->post_title; ?>"
                       href="<?php echo get_permalink($post->ID); ?>" rel="bookmark"><i
                                class="vicon i-book"></i><?php echo strip_tags($post->post_title) ?></a>
                <?php endforeach; ?>
            </div>
            <div class="tab-pane fade show active" id="nav-hot" role="tabpanel" aria-labelledby="nav-hot-tab">
                <?php if (function_exists('most_comm_posts')) {
                    most_comm_posts($days, $number);
                } ?>
            </div>
            <div class="tab-pane fade" id="nav-random" role="tabpanel" aria-labelledby="nav-random-tab">
                <?php $myposts = get_posts('numberposts=' . $number . ' & offset=0 & orderby=rand');
                foreach ($myposts as $post): ?>
                    <a class="bookmark-item" title="<?php echo $post->post_title; ?>"
                       href="<?php echo get_permalink($post->ID); ?>" rel="bookmark"><i
                                class="vicon i-book"></i><?php echo strip_tags($post->post_title) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php echo '<!-- .w-recommended -->';
        echo $after_widget;
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();

        $instance['number'] = (!empty($new_instance['number'])) ? $new_instance['number'] : '';
        $instance['days'] = (!empty($new_instance['days'])) ? $new_instance['days'] : '';

        return $instance;
    }

    public function form($instance)
    {
        global $wpdb;
        $number = !empty($instance['number']) ? $instance['number'] : '6';
        $days = !empty($instance['days']) ? $instance['days'] : '30';
        ?>
        <div class="media-widget-control">
            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('展示数量：', 'kratos'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>"
                       name="<?php echo $this->get_field_name('number'); ?>" type="text"
                       value="<?php echo esc_attr($number); ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('days'); ?>"><?php _e('统计天数：', 'kratos'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('days'); ?>"
                       name="<?php echo $this->get_field_name('days'); ?>" type="text"
                       value="<?php echo esc_attr($days); ?>"/>
            </p>
        </div>
        <?php
    }
}

class widget_about_detailed extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array(
            'classname' => 'w-about-detailed',
            'name' => __('个人简介 - 详细版', 'kratos'),
            'description' => __('个人简介 - 详细版', 'kratos')
        );
        parent::__construct(false, false, $widget_ops);
    }

    function widget($args, $instance)
    {
        global $wpdb;
        extract($args);
        $admin = get_userdata(1);
        $author_title = kratos_option('g_widgets_fieldset')['g_widgets_about'];
        $author = kratos_option('g_widgets_fieldset')['g_widgets_nickname'];
        $location = kratos_option('g_widgets_fieldset')['g_widgets_location'];
        $avatar = kratos_option('g_widgets_fieldset')['g_widgets_gravatar'];
        echo $before_widget;
        if (!is_home()) $redirect = get_permalink(); else $redirect = home_url(); ?>
        <div class="author">
            <img src="<?php echo $avatar ?>" alt="<?php echo $author ?>"/>
            <div class="in_box">
                <?php if ($author == true) { ?>
                    <span class="name"><?php echo $author ?></span>
                <?php } ?>
                <?php if ($location == true) { ?>
                    <span class="location"><i class="fas fa-map-marker-alt"></i><?php echo $location ?></span>
                <?php } ?>
            </div>
        </div>
        <?php if ($author_title == true) { ?>
        <p class="author_title"><?php echo $author_title ?></p>
    <?php } ?>
        <ul class="items">
            <li><span>POSTS</span><?php echo wp_count_posts()->publish; ?></li>
            <li>
                <span>FRIENDS</span><?php echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->links WHERE link_visible = 'Y'"); ?>
            </li>
            <li>
                <span>VISITS</span><?php echo $wpdb->get_var("SELECT SUM(meta_value+0) FROM $wpdb->postmeta WHERE meta_key = 'views'"); ?>
            </li>
        </ul>
        <ul class="info">
            <li><i></i><a href="javascript:"><?php echo site_url(); ?></a></li>
            <li>
                <i></i>网站已艰难存活 <?php echo floor((time() - strtotime(kratos_option('g_widgets_fieldset')['g_widgets_create_time'])) / 86400); ?>
                天
            </li>
            <li><i></i>作者活跃于 <?php last_login(); ?> 前</li>
        </ul>
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        return $new_instance;
    }

    function form($instance)
    {
    }
}

class widget_comments extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array(
            'classname' => 'w-comments',
            'name' => __('最近评论', 'kratos'),
            'description' => __('最近评论', 'kratos')
        );
        parent::__construct(false, false, $widget_ops);
    }

    function widget($args, $instance)
    {
        if (!isset($args['widget_id'])) $args['widget_id'] = $this->id;
        $output = '';
        $title = isset($instance['title']) ? $instance['title'] : __('最近评论', 'kratos');
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $show_admin = !empty($instance['show_admin']) ? '1' : '0';
        $comments = get_comments(apply_filters('widget_comments_args', array(
            'number' => $number,
            'author__not_in' => $show_admin,
            'status' => 'approve',
            'type' => 'comment',
            'post_status' => 'publish'
        )));
        $output = $args['before_widget'];
        if ($title) $output .= $args['before_title'] . $title . $args['after_title'];
        $output .= '<ul class="recentcomments">';
        if (is_array($comments) && $comments) {
            foreach ($comments as $comment) {
                $output .= '<li class="comment-listitem">';
                $output .= '<div class="comment-user">';
                $output .= '<span class="comment-avatar">' . get_avatar($comment, 50, null) . '</span>';
                $output .= '<div class="comment-author" title="' . $comment->comment_author . '">' . $comment->comment_author . '</div>';
                $output .= '<span class="comment-date">' . timeago($comment->comment_date_gmt) . '</span>';
                $output .= '</div>';
                $output .= '<div class="comment-content-link"><a href="' . get_comment_link($comment->comment_ID) . '"><div class="comment-content">' . convert_smilies(wp_trim_words(strip_tags(get_comment_excerpt($comment->comment_ID)), 30)) . '</div></a></div>';
                $output .= '</li>';
            }
        }
        $output .= '</ul>';
        $output .= $args['after_widget'];
        echo $output;
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        $instance['show_admin'] = !empty($new_instance['show_admin']) ? 1 : 0;
        return $instance;
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('最近评论', 'kratos');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_admin = isset($instance['show_admin']) ? (bool)$instance['show_admin'] : false; ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题：', 'kratos'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                       name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('显示数量：', 'kratos'); ?>
                <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>"
                       name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" max="99"
                       value="<?php echo $number; ?>" size="3"/>
            </label>
        </p>
        <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_admin'); ?>"
               name="<?php echo $this->get_field_name('show_admin'); ?>"<?php checked($show_admin); ?> />
        <label for="<?php echo $this->get_field_id('show_admin'); ?>"><?php _e('不显示管理员(用户ID为1)评论', 'kratos'); ?></label>
        </p><?php
    }
}


function register_widgets()
{
    register_widget('widget_ad');
    register_widget('widget_about');
    register_widget('widget_about_detailed');
    register_widget('widget_tags');
    register_widget('widget_posts');
    register_widget('widget_comments');
    register_widget('widget_search');
}

add_action('widgets_init', 'register_widgets');