<?php
/**
 * 文章目录
 * @author Virace
 * @license MIT License
 * @version 2020.08.04
 */

if (get_post_meta($post->ID, 'toc-meta-switch', true)) {
    ?>
    <aside class="widget widget_toc animate__animated animate__fadeInRight">
        <div class="title">文章目录</div>
        <div class="textwidget">
            <nav id="toc" class="navbar">
                <?php echo get_toc() ?>
            </nav>
        </div>
    </aside>
<?php } ?>
