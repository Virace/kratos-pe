<?php
/**
 * 首页模板
 * @author Seaton Jiang <hi@seatonjiang.com> (Modified by Virace)
 * @site x-item.com
 * @license GPL-3.0 License
 * @software PhpStorm
 * @version 2022.07.09
 */

get_header(); ?>
    <main id="content" class="k-main <?php echo kratos_option('top_img_switch', true) ? 'banner' : 'color' ?>"
          style="background:<?php echo kratos_option('g_background', '#f5f5f5'); ?>">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 board animate__animated">
                    <?php if (is_home() && kratos_option('g_carousel', false)) {
                        kratos_carousel();
                    }
                    if (is_search()) { ?>
                        <div class="article-panel">
                            <div class="search-title"><?php _e('搜索内容：', 'kratos');
                                the_search_query(); ?></div>
                        </div>
                    <?php }
                    if (have_posts()) {
                        while (have_posts()) {
                            the_post();
                            get_template_part('/pages/page-content', get_post_format());
                        }
                    } else { ?>
                        <div class="article-panel">
                            <div class="nothing">
                                <img src="<?php echo kratos_option('g_nothing', ASSET_PATH . '/assets/img/nothing.svg'); ?>">
                                <div class="sorry"><?php _e('很抱歉，没有找到任何内容', 'kratos'); ?></div>
                            </div>
                        </div>
                    <?php }
                    pagelist();
                    wp_reset_query(); ?>
                </div>
                <div class="col-lg-4 sidebar d-none d-lg-block animate__animated">
                    <!--todo: 侧边栏修改-->
                    <?php dynamic_sidebar('home_sidebar'); ?>
                </div>
            </div>
        </div>
    </main>
<?php get_footer(); ?>