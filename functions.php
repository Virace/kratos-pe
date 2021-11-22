<?php
/**
 * 模板函数
 * @author Seaton Jiang <seaton@vtrois.com> (Modified by Virace)
 * @site x-item.com
 * @license GPL-3.0 License
 * @software PhpStorm
 * @version 2021.11.23
 */

define('THEME_VERSION', wp_get_theme()->get('Version'));
define('THEME_DIR', get_template_directory());

// 主题配置
require THEME_DIR . '/inc/codestar-framework/autoload.php';

// 更新配置
//require THEME_DIR . '/inc/update-checker/autoload.php';

// 核心配置
require THEME_DIR . '/inc/theme-core.php';

// 工具
require THEME_DIR . '/inc/theme-utils.php';

// SEO
require THEME_DIR . '/inc/theme-seo.php';

// 文章配置
require THEME_DIR . '/inc/theme-article.php';
require THEME_DIR . '/inc/theme-comments.php';

// 小工具配置
require THEME_DIR . '/inc/theme-widgets.php';

// 文章增强
require THEME_DIR . '/inc/theme-shortcode.php';
require THEME_DIR . '/inc/theme-gutenberg.php';

// 表情配置
require THEME_DIR . '/inc/theme-smilies.php';

// 添加导航目录
require THEME_DIR . '/inc/theme-navwalker.php';

// 对象存储配置
require THEME_DIR . '/inc/theme-dogecloud.php';

// SMTP 配置
require THEME_DIR . '/inc/theme-smtp.php';

// 验证码(未完成)
require THEME_DIR . '/inc/theme-recaptcha.php';

// 媒体相关
require THEME_DIR . '/inc/theme-media.php';

require THEME_DIR . '/inc/theme-optimization.php';