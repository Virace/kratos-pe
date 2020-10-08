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
    <li class="comment cleanfix" id="comment-<?php echo esc_attr(comment_ID()); ?>" data-aos="fade">
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
    <li class="comment cleanfix" id="comment-<?php echo esc_attr(comment_ID()); ?>" data-aos="fade">
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
                <div class="tool reply ml-2 d-inline-block float-right">
                    <?php
                    $defaults = array('add_below' => 'comment', 'respond_id' => 'respond', 'reply_text' => '<i class="vicon i-reply"></i><span class="ml-1">' . __('回复', 'kratos') . '</span>');
                    comment_reply_link(array_merge($defaults, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
                    ?>
                </div>
            </div>
        </div>
    </li>
    <?php
}

// 文章评论表情
function custom_smilies_src($img_src, $img, $siteurl)
{
    return ASSET_PATH . '/assets/img/smilies/' . $img;
}

add_filter('smilies_src', 'custom_smilies_src', 1, 10);

function disable_emojis_tinymce($plugins)
{
    return array_diff($plugins, array('wpemoji'));
}

function smilies_reset()
{
    global $wpsmiliestrans, $wp_smiliessearch, $wp_version;
    if (!get_option('use_smilies') || $wp_version < 4.2) {
        return;
    }

    $wpsmiliestrans = array(
        ':mrgreen:' => 'mrgreen.png',
        ':exclaim:' => 'exclaim.png',
        ':neutral:' => 'neutral.png',
        ':twisted:' => 'twisted.png',
        ':arrow:' => 'arrow.png',
        ':eek:' => 'eek.png',
        ':smile:' => 'smile.png',
        ':confused:' => 'confused.png',
        ':cool:' => 'cool.png',
        ':evil:' => 'evil.png',
        ':biggrin:' => 'biggrin.png',
        ':idea:' => 'idea.png',
        ':redface:' => 'redface.png',
        ':razz:' => 'razz.png',
        ':rolleyes:' => 'rolleyes.png',
        ':wink:' => 'wink.png',
        ':cry:' => 'cry.png',
        ':lol:' => 'lol.png',
        ':mad:' => 'mad.png',
        ':drooling:' => 'drooling.png',
        ':persevering:' => 'persevering.png',
    );
}

smilies_reset();

function smilies_custom_button()
{
    printf('<style>.smilies-wrap{background:#fff;border: 1px solid #ccc;box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.24);padding: 10px;position: absolute;top: 60px;width: 400px;display:none}.smilies-wrap img{height:24px;width:24px;cursor:pointer;margin-bottom:5px} .is-active.smilies-wrap{display:block}@media screen and (max-width: 782px){ #wp-content-media-buttons a { font-size: 14px; padding: 0 14px; }}</style><a id="insert-media-button" style="position:relative" class="button insert-smilies add_smilies" data-editor="content" href="javascript:;"><span class="dashicons dashicons-smiley" style="line-height: 26px;"></span>' . __('添加表情', 'kratos') . '</a><div class="smilies-wrap">' . get_wpsmiliestrans() . '</div><script>jQuery(document).ready(function(){jQuery(document).on("click", ".insert-smilies",function() { if(jQuery(".smilies-wrap").hasClass("is-active")){jQuery(".smilies-wrap").removeClass("is-active");}else{jQuery(".smilies-wrap").addClass("is-active");}});jQuery(document).on("click", ".add-smily",function() { send_to_editor(" " + jQuery(this).data("smilies") + " ");jQuery(".smilies-wrap").removeClass("is-active");return false;});});</script>');
}

add_action('media_buttons', 'smilies_custom_button');

function get_wpsmiliestrans()
{
    global $wpsmiliestrans;
    global $output;
    $wpsmilies = array_unique($wpsmiliestrans);
    foreach ($wpsmilies as $alt => $src_path) {
        $output .= '<a class="add-smily" data-smilies="' . $alt . '"><img class="wp-smiley" src="' . ASSET_PATH . '/assets/img/smilies/' . rtrim($src_path, "png") . 'png" /></a>';
    }
    return $output;
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

