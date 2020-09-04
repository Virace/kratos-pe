<?php
/* Fire our meta box setup function on the post editor screen. */
add_action('load-post.php', 'sola_post_meta_boxes_setup');
add_action('load-post-new.php', 'sola_post_meta_boxes_setup');
/* 这是需要修改的两处之一，本功能只需要一个checkbox，将checkbox的title、id等属性填充到$fields数组中,
后面的代码会自动根据数组填充的内容创建Post Meta Box */
$fields = array(
    array(
        'name' => __('是否关闭显示文章目录'),
        'desc' => '',
        'id' => 'post_toc',
        'type' => 'checkbox',
        'default' => ''
    )
);
/* Meta box setup function. */
function sola_post_meta_boxes_setup()
{
    /* Add meta boxes on the 'add_meta_boxes' hook. */
    add_action('add_meta_boxes', 'sola_add_post_meta_boxes');
    add_action('save_post', 'sola_save_post_meta_boxes', 10, 2);
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
/* 这里也需要改一下，设置需要创建的Post Meta Box叫什么名字，显示在什么位置 */
function sola_add_post_meta_boxes()
{
    add_meta_box(
        'sola-post-slider-class',   // Unique ID
        __('文章目录'),        // Title
        'sola_box_format',      // Callback function
        'post',             // Admin page (or post type)
        'side',             // Context
        'high'           // Priority
    );
}

function sola_box_format()
{
    global $fields, $post;
    // Use nonce for verification
    echo '<input type="hidden" name="sola_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    echo '<table class="form-table">';
    foreach ($fields as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        echo '<tr>' .
            '<th><label for="' . $field['id'] . '">' . $field['name'] . '</strong></label></th>' .
            '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . ($meta ? $meta : $field['default']) . '" size="30" style="width:97%" />' . '
' . $field['desc'];
                break;
            case 'textarea':
                echo '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '" cols="60" rows="4" style="width:97%">' . ($meta ? $meta : $field['default']) . '' . '
' . $field['desc'];
                break;
            case 'select':
                echo '<select name="' . $field['id'] . '" id="' . $field['id'] . '">';
                foreach ($field['options'] as $option) {
                    echo '<option ' . ($meta == $option ? ' selected="selected"' : '') . '>' . $option . '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="' . $field['id'] . '" value="' . $option['value'] . '"' . ($meta == $option['value'] ? ' checked="checked"' : '') . ' />' . $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '"' . ($meta ? ' checked="checked"' : '') . ' />';
                break;
        }
        echo '<td>' . '</tr>';
    }
    echo '</table>';
}

function sola_save_post_meta_boxes($post_id)
{
    global $fields, $post;
    //Verify nonce
    if (!wp_verify_nonce(post_get('sola_meta_box_nonce'), basename(__FILE__))) {
        return $post_id;
    }
    //Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    //Get the post type object.
    $post_type = get_post_type_object($post->post_type);
    //Check permissions
    if (!current_user_can($post_type->cap->edit_post, $post_id))
        return $post_id;
    foreach ($fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}
