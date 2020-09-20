<?php
/**
 * 首页模板
 * @author Seaton Jiang <seaton@vtrois.com>
 * @license MIT License
 * @version 2020.04.12
 */

get_header(); ?>
<section id="content" class="k-main <?php echo kratos_option('top_select', 'banner'); ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 board animate__animated animate__fadeInLeft">
                <?php if(is_search()){ ?>
                    <div class="article-panel">
                        <div class="search-title"><?php _e('搜索内容：', 'kratos');the_search_query(); ?></div>
                    </div>
                <?php }
                if ( have_posts() ) {
					while ( have_posts() ){
						the_post();
						get_template_part('/inc/theme-pages/page-content', get_post_format());
					}
				}else{ ?>
                    <div class="article-panel">
                        <div class="nothing">
                            <img src="<?php 
                            if(!kratos_option('g_nothing')){
                                $img = ASSET_PATH . '/assets/img/nothing.svg';
                            } else {
                                $img = kratos_option('g_nothing', ASSET_PATH . '/assets/img/nothing.svg');
                            }
                            echo $img; ?>">
                            <div class="sorry"><?php _e('很抱歉，没有找到任何内容', 'kratos'); ?></div>
                        </div>
                    </div>
                <?php } 
                pagelist();
                wp_reset_query(); ?>
            </div>
            <div class="col-lg-4 sidebar d-none d-lg-block">
                <?php dynamic_sidebar('sidebar_tool'); ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>