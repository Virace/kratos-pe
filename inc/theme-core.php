<?php
/**
 * 核心函数
 * @author Seaton Jiang <seaton@vtrois.com>
 * @license MIT License
 * @version 2020.07.29
 */

if (kratos_option('g_cdn', false)) {
    $asset_path = 'https://cdn.jsdelivr.net/gh/Virace/kratos-pe@' . THEME_VERSION;
} else {
    $asset_path = get_template_directory_uri();
}
define('ASSET_PATH', $asset_path);

// 自动跳转主题设置
function init_theme()
{
    global $pagenow;
    if ('themes.php' == $pagenow && isset($_GET['activated'])) {
        wp_redirect(admin_url('admin.php?page=kratos_options'));
        exit;
    }
}

add_action('load-themes.php', 'init_theme');

// 语言国际化
function theme_languages()
{
    load_theme_textdomain('kratos', get_template_directory() . '/languages');
}

add_action('after_setup_theme', 'theme_languages');

// 资源加载
function theme_autoload()
{
    if (!is_admin()) {
        // css
        wp_enqueue_style('kratos-common', ASSET_PATH . '/assets/css/common.min.css', array(), THEME_VERSION);

        wp_enqueue_style('vicon', ASSET_PATH . '/assets/css/iconfont.min.css', array(), THEME_VERSION);

        wp_enqueue_style('kratos', ASSET_PATH . '/assets/css/kratos.min.css', array(), THEME_VERSION);

        wp_enqueue_style('custom', ASSET_PATH . '/custom/custom.css', array(), THEME_VERSION);


        $bg_color = kratos_option('g_background', '#f5f5f5');
        $theme_color1 = kratos_option('g_theme_color1', '#00a2ff');
        $theme_color2 = kratos_option('g_theme_color2', '#0097ee');
        $top_color_1 = kratos_option('top_color_1', 'rgba(40, 42, 44, 0.6)');
        $top_color_2 = kratos_option('top_color_2', '#fff');
        $mb_sidebar_color = kratos_option('mb_sidebar_color', '#242b31');

        $root = "body{--bg-color:{$bg_color};--theme-color-1:{$theme_color1}; --theme-color-2:{$theme_color2};--navbar-color-1:{$top_color_1}; --navbar-color-2:{$top_color_2};--mb-sidebar-color:{$mb_sidebar_color}}";
        wp_add_inline_style('kratos', $root);


        // js
        wp_deregister_script('jquery');
        wp_register_script('jquery', ASSET_PATH . '/assets/js/jquery.min.js', array(), '3.4.1', false);
        wp_enqueue_script( 'jquery' );

        wp_enqueue_script('kratos', ASSET_PATH . '/assets/js/bundle.js', array(), THEME_VERSION, true);
        wp_enqueue_script('custom', get_template_directory_uri() . '/custom/custom.js', array(), THEME_VERSION, true);


        // 在最后加载自定义文件
        $data = array(
            'site' => home_url(),
            'directory' => ASSET_PATH,
            'alipay' => kratos_option('g_donate_alipay', ASSET_PATH . '/assets/img/donate.png'),
            'wechat' => kratos_option('g_donate_wechat', ASSET_PATH . '/assets/img/donate.png'),
            'repeat' => __('您已经赞过了', 'kratos'),
            'thanks' => __('感谢您的支持', 'kratos'),
            'donate' => __('打赏作者', 'kratos'),
            'scan' => __('扫码支付', 'kratos'),
        );
        wp_localize_script('kratos', 'kratos', $data);
    }


    if (is_page() || is_single()) {
        /* 代码高亮 */
        wp_enqueue_style('highlight', get_template_directory_uri() . '/assets/css/highlight/style.min.css', array(), '10.2.0');
        wp_enqueue_script('highlight', ASSET_PATH . '/assets/js/highlight/highlight.pack.js', array(), '10.2.0', true);
        wp_enqueue_script('highlight-ln', ASSET_PATH . '/assets/js/highlight/highlightjs-line-numbers.min.js', array(), '2.8.0', true);
        wp_enqueue_script('highlight-copy', ASSET_PATH . '/assets/js/highlight/highlightjs-copy-button.min.js', array(), '1.0.5', true);

        /* 图片灯箱 */
        wp_enqueue_style('lightgallery', ASSET_PATH . '/assets/css/lightgallery.js/lightgallery.css', array(), '1.2.0');
        wp_enqueue_style('lightgallery-lg', ASSET_PATH . '/assets/css/lightgallery.js/lg-transitions.css', array(), '1.2.0');
        wp_enqueue_script('lightgallery', ASSET_PATH . '/assets/js/lightgallery.js/lightgallery.js', array(), '1.2.0', true);
        wp_enqueue_script('lightgallery-lg', ASSET_PATH . '/assets/js/lightgallery.js/lg-thumbnail.js', array(), '1.2.0', true);
    }

    // 哀悼黑白站点
    if (is_home() && kratos_option('g_rip', false)) {
        $data = 'html{filter: grayscale(100%);-webkit-filter: grayscale(100%);-moz-filter: grayscale(100%);-ms-filter: grayscale(100%);-o-filter: grayscale(100%);filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);filter: gray;-webkit-filter: grayscale(1); }';
        wp_add_inline_style('kratos', $data);
    }
    if (kratos_option('seo_statistical')) add_action('wp_footer', function () {
        echo '<script id="other" type="text/javascript">' .kratos_option('seo_statistical'). '</script>';

    }, 50);

}

add_action('wp_enqueue_scripts', 'theme_autoload');


// 主题更新检测
//$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
//    'https://github.com/vtrois/kratos/',
//    get_template_directory() . '/functions.php',
//    'Kratos'
//);

//Banner 设置
function get_background()
{
    if (!kratos_option('top_img')) {
        $id = rand(2, 9);
        return ASSET_PATH . '/assets/img/background-' . $id . '.jpg';
    } else {
        return kratos_option('top_img', ASSET_PATH . '/assets/img/background-1.jpg');
    }

}
