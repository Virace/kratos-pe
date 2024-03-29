<?php
/**
 * 文章列表
 * @author Seaton Jiang <hi@seatonjiang.com> (Modified by Virace)
 * @site x-item.com
 * @license GPL-3.0 License
 * @software PhpStorm
 * @version 2022.07.09
 */
?>
<article class="article-panel">
    <!-- HOT标签 -->
    <span class="a-card d-none d-md-block d-lg-block">
        <?php $article_comment = kratos_option('g_article_fieldset')['g_article_comment'] ?? '20';
        $article_love = kratos_option('g_article_fieldset')['g_article_love'] ?? '200';
        if (is_sticky()) { ?>
            <i class="vicon i-card-top"></i>
        <?php } elseif (findSinglecomments($post->ID) >= $article_comment || get_post_meta($post->ID, 'love', true) >= $article_love) { ?>
            <i class="vicon i-card-hot"></i>
        <?php } ?>
    </span>
    <?php if (kratos_option('g_thumbnail', true)) { ?>
        <div class="a-thumb">
            <a href="<?php the_permalink(); ?>">
                <?php post_thumbnail(); ?>
            </a>
        </div>
    <?php } ?>
    <div class="a-right">
        <div class="a-post <?php echo kratos_option('g_thumbnail', true) ?: 'a-none'; ?>">
            <div class="header">
                <?php
                $category = get_the_category();
                if ($category) {
                    echo '<a class="label" href="' . get_category_link($category[0]->term_id) . '">' . $category[0]->cat_name . '<i class="label-arrow"></i></a>';
                } else {
                    echo '<span class="label">' . __('页面', 'kratos') . '<i class="label-arrow"></i></span>';
                }
                ?>
                <h3 class="title"><a href="<?php the_permalink(); ?>"><?php
                        if (is_sticky()) echo '<span class="sticky-post">[置顶] </span>';
                        the_title(); ?></a></h3>
            </div>
            <div class="content">
                <p><?php echo wp_trim_words(get_the_excerpt(), 85); ?></p>
            </div>
        </div>
        <div class="a-meta">
        <span class="float-left d-block">
            <span class="mr-2"><i class="vicon i-calendar"></i><?php echo get_the_date('Y-m-d'); ?></span>
            <span class="mr-2"><i class="vicon i-comments"></i><?php comments_number('0', '1', '%');
                _e('条评论', 'kratos'); ?></span>
        </span>
            <span class="float-left d-none d-md-block">
            <span class="mr-2"><i class="vicon i-hot"></i><?php echo get_post_views();
                _e('点热度', 'kratos'); ?></span>
            <span class="mr-2"><i class="vicon i-good"></i><?php if (get_post_meta($post->ID, 'love', true)) {
                    echo get_post_meta($post->ID, 'love', true);
                } else {
                    echo '0';
                }
                _e('人点赞', 'kratos'); ?></span>
                <span class="mr-2"><i
                            class="vicon i-author"></i><?php echo get_the_author_meta('display_name'); ?></span>
        </span>
            <span class="float-right">
            <a href="<?php the_permalink(); ?>"><?php _e('阅读全文', 'kratos'); ?><i class="vicon i-rightbutton"></i></a>
        </span>
        </div>
    </div>
</article>