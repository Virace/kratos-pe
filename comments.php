<?php
/**
 * 评论模板
 * @author Seaton Jiang <seaton@vtrois.com>
 * @license MIT License
 * @version 2020.03.14
 */

if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
    die();
}
require get_template_directory() . '/inc/theme-pages/page-smilies.php';
require get_template_directory() . '/inc/theme-pages/page-comments.php';
if (comments_open()) { ?>
    <div class="comments" id="comments" data-aos="fade">
        <h3 class="title"><?php if (is_single()) {
                _e('文章评论', 'kratos');
            } else {
                _e('评论内容', 'kratos');
            } ?></h3>
        <div class="list">
            <?php wp_list_comments('type=comment&callback=comment_callbacks'); ?>
        </div>
        <?php if (get_comment_pages_count() > 1) { ?>
            <div id="comments-nav">
                <?php paginate_comments_links('prev_text=' . __('上一页', 'kratos') . '&next_text=' . __('下一页', 'kratos')); ?>
            </div>
        <?php } ?>
        <div id="respond" class="comment-respond mt-2">
            <?php if (!comments_open()): elseif (get_option('comment_registration') && !is_user_logged_in()): ?>
                <div class="error text-center">
                    <?php printf(__('您需要 <a href="%s">登录</a> 之后才可以评论', 'kratos'), wp_login_url(get_permalink())); ?>
                </div>
            <?php else: ?>
                <form id="commentform" name="commentform"
                      action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">
                    <div class="comment-form">
                        <?php if (!is_user_logged_in()): ?>
                            <div class="comment-info mb-3 row">
                                <div class="col-md-6 comment-form-author">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="vicon i-user"></i></span>
                                        </div>
                                        <input class="form-control" id="author"
                                               placeholder="<?php _e('昵称', 'kratos'); ?>" name="author" type="text"
                                               value="<?php echo esc_attr($commenter['comment_author']); ?>"
                                               required="required">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0 comment-form-email">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="vicon i-cemail"></i></span>
                                        </div>
                                        <input id="email" class="form-control" name="email"
                                               placeholder="<?php _e('邮箱', 'kratos'); ?>" type="email"
                                               value="<?php echo esc_attr($commenter['comment_author_email']); ?>"
                                               required="required">
                                    </div>
                                </div>
                                <?php // todo: 加入验证码？ ?>
                            </div>
                        <?php endif; ?>
                        <div class="comment-textarea">
                            <textarea class="form-control" id="comment" name="comment" rows="7"
                                      required="required"></textarea>
                            <div class="text-bar clearfix">
                                <div class="tool float-left">
                                    <a class="addbtn" href="javascript:" id="addsmile"><i class="vicon i-face"></i></a>
                                    <div class="smile">
                                        <div class="clearfix">
                                            <?php display_smilies(); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="float-right">
                                    <?php cancel_comment_reply_link(__('取消回复', 'kratos')); ?>
                                    <input name="submit" type="submit" id="submit" class="btn btn-primary"
                                           value="<?php _e('提交评论', 'kratos'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php comment_id_fields(); ?>
                    <?php do_action('comment_form', $post->ID); ?>
                </form>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>