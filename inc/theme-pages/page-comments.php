<?php
/**
 * @version $data
 */


// 文章评论
function comment_scripts()
{
    wp_enqueue_script('comment', ASSET_PATH . '/assets/js/comments.min.js', array(), THEME_VERSION);
    wp_localize_script('comment', 'ajaxcomment', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'order' => get_option('comment_order')
    ));
}

add_action('wp_enqueue_scripts', 'comment_scripts');

function comment_err($a)
{
    header('HTTP/1.0 500 Internal Server Error');
    header('Content-Type: text/plain;charset=UTF-8');
    echo $a;
    exit;
}

function comment_callback()
{
    $comment = wp_handle_comment_submission(wp_unslash($_POST));
    if (is_wp_error($comment)) {
        $data = $comment->get_error_data();
        if (!empty($data)) {
            comment_err($comment->get_error_message());
        } else {
            exit;
        }
    }
    $user = wp_get_current_user();
    do_action('set_comment_cookies', $comment, $user);
    $GLOBALS['comment'] = $comment;
    ?>
    <li class="comment cleanfix" id="comment-<?php echo esc_attr(comment_ID()); ?>">
        <div class="avatar float-left d-inline-block mr-2">
            <?php if (function_exists('get_avatar') && get_option('show_avatars')) {
                echo get_avatar($comment, 50);
            } ?>
        </div>
        <div class="info clearfix">
            <cite class="author_name"><?php echo get_comment_author_link(); ?></cite>
            <div class="content pb-2">
                <?php comment_text(); ?>
            </div>
            <div class="meta clearfix">
                <div class="date d-inline-block float-left"><?php echo get_comment_date('Y年m月d日'); ?><?php if (current_user_can('edit_posts')) {
                        echo '<span class="ml-2">';
                        edit_comment_link(__('编辑', 'kratos'));
                        echo '</span>';
                    }; ?>
                </div>
            </div>
        </div>
    </li>
    <?php die();
}

add_action('wp_ajax_nopriv_ajax_comment', 'comment_callback');
add_action('wp_ajax_ajax_comment', 'comment_callback');

function comment_post($incoming_comment)
{
    $incoming_comment['comment_content'] = htmlspecialchars($incoming_comment['comment_content']);
    $incoming_comment['comment_content'] = str_replace("'", '&apos;', $incoming_comment['comment_content']);
    return ($incoming_comment);
}

add_filter('preprocess_comment', 'comment_post', '', 1);

function comment_display($comment_to_display)
{
    $comment_to_display = str_replace('&apos;', "'", $comment_to_display);
    return $comment_to_display;
}

add_filter('comment_text', 'comment_display', '', 1);

function comment_callbacks($comment, $args, $depth = 2)
{
    $GLOBALS['comment'] = $comment; ?>
    <li class="comment cleanfix" id="comment-<?php echo esc_attr(comment_ID()); ?>">
        <div class="avatar float-left d-inline-block mr-2">
            <?php if (function_exists('get_avatar') && get_option('show_avatars')) {
                echo get_avatar($comment, 50);
            } ?>
        </div>
        <div class="info clearfix">
            <cite class="author_name"><?php echo get_comment_author_link(); ?></cite>
            <div class="meta clearfix">
                <div class="date d-inline-block"><?php echo get_comment_date('Y年m月d日'); ?><?php if (current_user_can('edit_posts')) {
                        echo '<span class="ml-2">';
                        edit_comment_link(__('编辑', 'kratos'));
                        echo '</span>';
                    }; ?>
                </div>
                <div class="tool reply d-inline-block float-right">
                    <?php
                    $defaults = array('add_below' => 'comment', 'respond_id' => 'respond', 'reply_text' => '<i class="vicon i-reply"></i><span class="ml-1">' . __('回复', 'kratos') . '</span>');
                    comment_reply_link(array_merge($defaults, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
                    ?>
                </div>
            </div>
            <div class="content pb-2">
                <?php comment_text(); ?>
            </div>

        </div>
    </li>
    <?php
//    li标签添加结尾是为了 子评论与li同级, 适应aos插件
}


// 文章评论增强
function comment_add_at($comment_text, $comment = '')
{
    if ($comment->comment_parent > 0) {
        $comment_text = '<span>@' . get_comment_author($comment->comment_parent) . '</span> ' . $comment_text;
    }

    return $comment_text;
}

add_filter('comment_text', 'comment_add_at', 20, 2);

function recover_comment_fields($comment_fields)
{
    $comment = array_shift($comment_fields);
    $comment_fields = array_merge($comment_fields, array('comment' => $comment));
    return $comment_fields;
}

add_filter('comment_form_fields', 'recover_comment_fields');

