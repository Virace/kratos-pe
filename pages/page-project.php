<?php
/**
 * template name: 项目展示模板
 */
get_header(); ?>
<main id="content" class="k-main <?php echo kratos_option('top_select', 'banner'); ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 details">
                <?php if (have_posts()) : the_post();
                    update_post_caches($posts); ?>
                    <article class="article py-4" data-aos="fade">
                        <div class="header text-center">
                            <h1 class="title m-0"><?php the_title();?></h1>
                        </div>
                        <div class="linkpage">
                            <hr/>
                            <ul><?php
                                $bookmarks = get_bookmarks();
                                if (!empty($bookmarks)) {
                                    foreach ($bookmarks as $bookmark) {
                                        if($bookmark->link_rel != 'me') continue;
                                        $friendimg = $bookmark->link_image;
                                        if (empty($friendimg)) $friendimg = ASSET_PATH . '/assets/img/gravatar.png';
                                        echo '<li><a href="' . $bookmark->link_url . '" target="_blank"><img src="' . $friendimg . '"><h4>' . $bookmark->link_name . '</h4><p>' . $bookmark->link_description . '</p></a></li>';
                                    }
                                } ?>
                            </ul>
                            <hr/>
                        </div>
                        <div class="content"><?php the_content() ?></div>
                    </article>
                <?php endif; ?>
                <?php comments_template(); ?>
            </div>
            <div class="col-lg-4 sidebar d-none d-lg-block">
                <?php dynamic_sidebar('sidebar_tool_post'); ?>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>
