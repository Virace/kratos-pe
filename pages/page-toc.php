<?php
/**
 * 文章目录
 * @author Virace
 * @license MIT License
 * @version 2020.08.04
 */

//$toc = wpjam_get_toc();

if (!get_post_meta($post->ID, 'post_toc', true)) {
    ?>
    <aside class="widget widget_toc">
        <div class="title">文章目录</div>
        <div class="textwidget">
            <nav id="toc" class="navbar">
                <?php echo get_toc() ?>
            </nav>
        </div>
    </aside>
<?php } ?>
