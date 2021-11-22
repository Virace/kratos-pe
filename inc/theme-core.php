<?php
/**
 * 核心函数
 * @author Seaton Jiang <seaton@vtrois.com> (Modified by Virace)
 * @site x-item.com
 * @license GPL-3.0 License
 * @software PhpStorm
 * @version 2021.11.23
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
        wp_redirect(admin_url('admin.php?page=kratos-options'));
        exit;
    }
}

add_action('load-themes.php', 'init_theme');

// 语言国际化
//function theme_languages()
//{
//    load_theme_textdomain('kratos', get_template_directory() . '/languages');
//}
//
//add_action('after_setup_theme', 'theme_languages');

// 资源加载
function theme_autoload()
{
    if (!is_admin()) {
        // css
        wp_enqueue_style('kratos-common', ASSET_PATH . '/assets/css/common.min.css', array(), THEME_VERSION);

        wp_enqueue_style('vicon', ASSET_PATH . '/assets/css/iconfont.min.css', array(), THEME_VERSION);

        wp_enqueue_style('kratos', ASSET_PATH . '/assets/css/kratos.min.css', array(), THEME_VERSION);


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
        wp_enqueue_script('jquery');

        wp_enqueue_script('kratos', ASSET_PATH . '/assets/js/main.js', array(), THEME_VERSION, true);


        // 在最后加载自定义文件
        $data = array(
            'site' => home_url(),
            'directory' => ASSET_PATH,
            'alipay' => kratos_option('g_donate_alipay', ASSET_PATH . '/assets/img/donate.png'),
            'wechat' => kratos_option('g_donate_wechat', ASSET_PATH . '/assets/img/donate.png'),
            'loading' => kratos_option('g_photo_lazy', ASSET_PATH . '/assets/img/loading.gif'),
            'repeat' => __('您已经赞过了', 'kratos'),
            'thanks' => __('感谢您的支持', 'kratos'),
            'donate' => __('打赏作者', 'kratos'),
            'scan' => __('扫码支付', 'kratos'),
            'ajax_url' => admin_url('admin-ajax.php'),
            'comment_order' => get_option('comment_order'),
            'theme_version' => THEME_VERSION
        );
        wp_localize_script('kratos', 'kratos', $data);
    }

}

add_action('wp_enqueue_scripts', 'theme_autoload');


// 主题更新检测
//$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
//    'https://cdn.jsdelivr.net/gh/virace/kratos-pe/inc/update-checker/update.json',
//    get_template_directory() . '/functions.php',
//    'Kratos'
//);


// 哀悼黑白站点
function mourning()
{
    if (is_home() && kratos_option('g_rip', false)) {
        echo '<style type="text/css">html{filter: grayscale(100%);-webkit-filter: grayscale(100%);-moz-filter: grayscale(100%);-ms-filter: grayscale(100%);-o-filter: grayscale(100%);filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);filter: gray;-webkit-filter: grayscale(1); } </style>';
    }
}