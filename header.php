<?php
/**
 * 主题页眉
 * @author Seaton Jiang <hi@seatonjiang.com> (Modified by Virace)
 * @site x-item.com
 * @license GPL-3.0 License
 * @software PhpStorm
 * @version 2022.07.09
 */
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php wp_title('-', true, 'right'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="format-detection" content="telphone=no, email=no">
    <meta name="keywords" content="<?php echo keywords(); ?>">
    <meta name="description" content="<?php echo description(); ?>">
    <meta name="theme-color" content="<?php echo kratos_option('g_chrome', '#282a2c'); ?>">
    <meta itemprop="image" content="<?php echo share_thumbnail_url(); ?>"/>
    <?php
    if (kratos_option('g_icon')) {
        echo '<link rel="shortcut icon" href="' . kratos_option("g_icon") . '">';
    }
    wp_head();
    wp_print_scripts('jquery');
    mourning();
    if (kratos_option('seo_statistical')) {
        echo kratos_option('seo_statistical');
    }
    ?>
</head>
<?php flush(); ?>
<body>
<header class="k-header">
    <nav class="k-nav navbar navbar-expand-lg fixed-top auto-hiding-navbar navbar-light">
        <div class="container">
            <a class="navbar-brand animate__animated" href="<?php echo get_option('home'); ?>">
                <?php
                if (kratos_option('g_logo')) {
                    echo '<img src="' . kratos_option('g_logo') . '"><h1 style="display:none">' . get_bloginfo('name') . '</h1>';
                } else {
                    echo '<h1>' . get_bloginfo('name') . '</h1>';
                }
                ?>
            </a>
            <?php if (has_nav_menu('header_menu')) { ?>
                <button class="navbar-toggler navbar-toggler-right" id="navbutton">
                    <span class="line first-line"></span>
                    <span class="line second-line"></span>
                    <span class="line third-line"></span>
                </button>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'header_menu',
                    'depth' => 2,
                    'container' => 'div',
                    'container_class' => 'collapse navbar-collapse animate__animated',
                    'container_id' => 'navbarResponsive',
                    'menu_class' => 'navbar-nav ml-auto',
                    'menu_id' => 'navbar',
                    'walker' => new WP_Bootstrap_Navwalker(),
                ));
            }
            ?>
        </div>
    </nav>
    <?php if (kratos_option('top_img_switch', true)) { ?>
        <div class="banner">
            <div class="overlay"></div>
            <div class="content text-center"
                 style="background-image: url(<?php echo kratos_option('top_img', ASSET_PATH . '/assets/img/background.jpg'); ?>);">
                <div class="introduce animate__animated">
                    <?php
                    if (is_category() || is_tag()) {
                        echo '<div class="title">' . single_cat_title('', false) . '</div>';
                        echo '<div class="mate">' . strip_tags(category_description()) . '</div>';
                    } else {
                        echo '<div class="title">' . kratos_option('top_title', 'Kratos') . '</div>';
                        echo '<div class="mate">' . kratos_option('top_describe', __('一款专注于用户阅读体验的响应式博客主题', 'kratos')) . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</header>