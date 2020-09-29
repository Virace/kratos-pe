<?php
/**
 * template name: 文章归档模板
 */

$arc_tags = wp_tag_cloud(array(
        'unit' => 'px',
        'smallest' => 14,
        'largest' => 14,
        'number' => 25,
        'format' => 'flat',
        'orderby' => 'count',
        'order' => 'cont',
        'echo' => false,
    )
);
$the_query = new WP_Query('posts_per_page=-1&ignore_sticky_posts=1');
$year = 0;
$output = '';
while ($the_query->have_posts()):
    $the_query->the_post();
    $year_tmp = get_the_time('Y');
    if ($year != $year_tmp) {
        $year = $year_tmp;
        $output .= '<div class="collection-title"><h2 class="archive-year" id="archive-year-' . $year . '">' . $year . '</h2></div>';
    }
    $output .= '<article class="post post-type-normal" itemtype="http://schema.org/Article"><header class="post-header"><h2 class="post-title"><a class="post-title-link" href="' . get_permalink() . '" itemprop="url"><span itemprop="name">' . get_the_title() . '</span></a></h2><div class="post-meta"><time class="post-time" itemprop="dateCreated">' . get_the_time('m-d') . '</time></div></header></article>';
endwhile;
get_header(); ?>
<main id="content" class="k-main <?php echo kratos_option('top_select', 'banner'); ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 details">
                <?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
                    <article class="article py-4" data-aos="fade">
                        <div class="header text-center">
                            <h1 class="title m-0"><?php the_title(); ?></h1>
                        </div>
                        <div class="page-header">
                            <?php _e('当前共有', 'kratos');
                                    echo wp_count_posts()->publish;
                                    _e('篇公开日志，', 'kratos');
                                    echo wp_count_posts('page')->publish;
                                    _e('个公开页面。 (゜-゜)つロ 干杯~', 'kratos'); ?>
                            <hr/>
                        </div>
                        <div class="content"><?php the_content() ?></div>
                        <div class="page-content">
                            <h4><?php _e('标签', 'kratos'); ?></h4>
                            <div class="arc-tag">
                                <?php echo $arc_tags; ?>
                            </div>
                            <hr/>
                            <h4><?php _e('时间线', 'kratos'); ?></h4>
                            <div id="posts" class="posts-collapse">
                                <?php echo $output; ?>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>
                <?php comments_template(); ?>
            </div>
            <div class="col-lg-4 sidebar d-none d-lg-block">
                <?php dynamic_sidebar('sidebar_tool_post'); ?>
            </div>
        </div>
    </div>
</>
<?php get_footer(); ?>
