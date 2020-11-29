<?php
/**
 * SMTP 配置
 * @author Seaton Jiang <seaton@vtrois.com>
 * @license MIT License
 * @version 2020.02.15
 */

if (kratos_option('m_smtp', false)) {
    function mail_smtp($phpmailer)
    {
        $phpmailer->isSMTP();
        $phpmailer->SMTPAuth = true;
        $phpmailer->CharSet = "utf-8";
        $phpmailer->SMTPSecure = kratos_option('m_sec');
        $phpmailer->Port = kratos_option('m_port');
        $phpmailer->Host = kratos_option('m_host');
        $phpmailer->From = kratos_option('m_username');
        $phpmailer->Username = kratos_option('m_username');
        $phpmailer->Password = kratos_option('m_passwd');
    }

    add_action('phpmailer_init', 'mail_smtp');
}

// Debug
function wp_mail_debug($wp_error)
{
    return error_log(print_r($wp_error, true));
}

// add_action('wp_mail_failed', 'wp_mail_debug', 10, 1);

function comment_approved($comment)
{
    if (is_email($comment->comment_author_email)) {
        $wp_email = kratos_option('m_username');
        $to = trim($comment->comment_author_email);
        $post_link = get_permalink($comment->comment_post_ID);
        $subject = __('[通知]您的留言已经通过审核', 'kratos');

        $body = __('您在', 'kratos') . '《<a href="' . $post_link . '" target="_blank" >' . get_the_title($comment->comment_post_ID) . '</a>》' . __('中发表的评论已通过审核！', 'kratos') . '<br /><br />';
        $body .= '<strong>' . __('您的评论', 'kratos') . ':</strong><br />';
        $body .= strip_tags($comment->comment_content) . '<br /><br />';
        $body .= __('您可以', 'kratos') . ':<a href="' . get_comment_link($comment->comment_ID) . '" target="_blank">' . __('查看您的评论', 'kratos') . '</a>  |  <a href="' . $post_link . '#comments" target="_blank">' . __('查看其他评论', 'kratos') . '</a>  |  <a href="' . $post_link . '" target="_blank">' . __('再次阅读文章', 'kratos') . '</a><br /><br />';
        $body .= __('欢迎再次光临', 'c') . '【<a href="' . get_bloginfo('url') . '" target="_blank" title="' . get_bloginfo('description') . '">' . get_bloginfo('name') . '</a>】。';
        $body .= __('该邮件由系统自动发出，如果不是您本人操作，请忽略此邮件。', 'kratos');
        $from = "From: \"" . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $body, $headers);
    }
}

add_action('comment_unapproved_to_approved', 'comment_approved');

function comment_notify($comment_id)
{
    $comment = get_comment($comment_id);
    $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
    $spam_confirmed = $comment->comment_approved;
    if (($parent_id != '') && ($spam_confirmed != 'spam')) {
        $wp_email = kratos_option('m_username');
        $to = trim(get_comment($parent_id)->comment_author_email);
        $subject = __('[通知]您的留言有了新的回复', 'kratos');

        $message = '<table border="1" cellpadding="0" cellspacing="0" width="600" align="center" style="border-collapse: collapse; border-style: solid; border-width: 1;border-color:#ddd;">
	<tbody>
          <tr>
            <td>
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" height="48" >
                    <tbody><tr>
                        <td width="100" align="center" style="border-right:1px solid #ddd;">
                            <a href="' . home_url() . '/" target="_blank">' . get_option("blogname") . '</a></td>
                        <td width="300" style="padding-left:20px;"><strong>' . __('您有一条来自', 'kratos') . ' <a href="' . home_url() . '" target="_blank" style="color:#F37474;text-decoration:none;">' . get_option("blogname") . '</a> ' . __('的回复', 'kratos') . '</strong></td>
						</tr>
					</tbody>
				</table>
			</td>
          </tr>
          <tr>
            <td  style="padding:15px;">
			<h1 style="font-weight: normal; color: #fff; text-align: center; margin-bottom: 65px;font-size: 20px; letter-spacing: 6px;font-weight: normal ; padding: 15px; background: #EA5A5A;">THANKS FOR YOUR REPLY.</h1>
			<p><strong>' . trim(get_comment($parent_id)->comment_author) . '</strong>, ' . __('你好!', 'kratos') . '</span>
              <p>' . __('你在', 'kratos') . '《' . get_the_title($comment->comment_post_ID) . '》' . __('的留言', 'kratos') . ':</p><p style="border-left:3px solid #ddd;padding-left:1rem;color:#999;">'
            . trim(get_comment($parent_id)->comment_content) . '</p><p>
              ' . trim($comment->comment_author) . ' ' . __('给你的回复', 'kratos') . ':</p><p style="border-left:3px solid #ddd;padding-left:1rem;color:#999;">'
            . trim($comment->comment_content) . '</p>
        <center style="padding:40px 0" ><a href="' . htmlspecialchars(get_comment_link($parent_id)) . '" target="_blank" style="background-color:#474A58; border-radius:0px; display:inline-block; color:#fff; padding:15px 20px 15px 20px; text-decoration:none;margin-top:20px; margin-bottom:20px;">' . __('点击查看完整内容', 'kratos') . '</a></center>
</td>
          </tr>
          <tr>
            <td align="center" valign="center" height="60" style="font-size:0.8rem; color:#999;">Copyright © ' . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . '</td>
            <td align="center" valign="center" height="60" style="font-size:0.8rem; color:#999;">' . date("Y年m月d日", time()) . '</td>
          </tr>
		  </tbody>
  </table>';
        $from = "From: \"" . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $message, $headers);
    }
}

add_action('comment_post', 'comment_notify');
