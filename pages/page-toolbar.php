<?php
/**
 * 文章工具栏
 * @author Seaton Jiang <hi@seatonjiang.com> (Modified by Virace)
 * @site x-item.com
 * @license GPL-3.0 License
 * @software PhpStorm
 * @version 2022.07.09
 */
?>
<aside class="toolbar clearfix">
    <div class="meta float-md-left">
        <img src="<?php echo get_avatar_url(get_the_author_meta('user_email')); ?>">
        <p class="name"><?php echo get_the_author_meta('display_name'); ?></p>
        <p class="motto mb-0"><?php echo $description = !empty(get_the_author_meta('description')) ? get_the_author_meta('description') : __('这个人很懒，什么都没留下', 'kratos'); ?></p>
    </div>
    <div class="share float-md-right text-center">
        <?php if (kratos_option('g_donate_fieldset')['g_donate']) { ?>
            <a href="javascript:;" id="donate" class="btn btn-donate mr-3" role="button" data-toggle="modal"
               data-target="#modal-container"><i
                        class="vicon i-donate"></i> <?php _e('打赏', 'kratos'); ?></a>
        <?php } ?>
        <a href="javascript:;" id="thumbs" data-action="love" data-id="<?php the_ID(); ?>" role="button"
           class="btn btn-thumbs <?php if (isset($_COOKIE['love_' . $post->ID])) echo 'done'; ?>"><i
                    class="vicon i-like"></i><span class="ml-1"><?php _e('点赞', 'kratos'); ?></span></a>
    </div>
</aside>

