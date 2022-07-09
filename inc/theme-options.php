<?php
/**
 * 主题选项
 * @author Seaton Jiang <hi@seatonjiang.com> (Modified by Virace)
 * @site x-item.com
 * @license GPL-3.0 License
 * @software PhpStorm
 * @version 2022.07.09
 */

defined('ABSPATH') || exit;

$prefix = 'kratos_pe_options';

if (!function_exists('kratos_option')) {
    function kratos_option($name, $default = false)
    {
        $options = get_option('kratos_pe_options');
        if (isset($options[$name])) {
            return $options[$name];
        }
        return $default;
    }
}

function getrobots()
{
    $site_url = parse_url(site_url());
    $web_url = get_bloginfo('url');
    $path = (!empty($site_url['path'])) ? $site_url['path'] : '';

    $robots = "User-agent: *\n\n";
    $robots .= "Disallow: $path/wp-admin/\n";
    $robots .= "Disallow: $path/wp-includes/\n";
    $robots .= "Disallow: $path/wp-content/plugins/\n";
    $robots .= "Disallow: $path/wp-content/themes/\n\n";
    $robots .= "Sitemap: $web_url/wp-sitemap.xml\n";

    return $robots;
}

CSF::createOptions($prefix, array(
    'menu_title' => __('主题设置', 'kratos'),
    'menu_slug' => 'kratos-options',
    'show_search' => false,
    'show_all_options' => false,
    'sticky_header' => false,
    'admin_bar_menu_icon' => 'dashicons-admin-generic',
    'framework_title' => '主题设置<small style="margin-left:10px">Kratos-pe v' . THEME_VERSION . '</small>',
    'theme' => 'light',
    'footer_credit' => '感谢使用 <a target="_blank" href="https://github.com/seatonjiang/kratos">Kratos</a> 主题开始创作，欢迎加入交流群：<a target="_blank" href="https://qm.qq.com/cgi-bin/qm/qr?k=jHy4nvMcnurowkL602BTDZzverAqfTpI&jump_from=webapi">734508</a>',
));

CSF::createSection($prefix, array(
    'id' => 'global_fields',
    'title' => __('全站配置', 'kratos'),
    'icon' => 'fas fa-rocket',
));

CSF::createSection($prefix, array(
    'parent' => 'global_fields',
    'title' => __('功能配置', 'kratos'),
    'icon' => 'fas fa-arrow-right',
    'fields' => array(
        array(
            'id' => 'g_adminbar',
            'type' => 'switcher',
            'title' => __('前台管理员导航', 'kratos'),
            'subtitle' => __('启用/禁用前台管理员导航', 'kratos'),
            'default' => true,
        ),
        array(
            'id' => 'g_search',
            'type' => 'switcher',
            'title' => __('搜索增强', 'kratos'),
            'subtitle' => __('启用/禁用仅搜索文章标题', 'kratos'),
            'default' => false,
        ),
        array(
            'id' => 'g_thumbnail',
            'type' => 'switcher',
            'title' => __('特色图片', 'kratos'),
            'subtitle' => __('启用/禁用文章特色图片', 'kratos'),
            'default' => true,
        ),
        array(
            'id' => 'g_rip',
            'type' => 'switcher',
            'title' => __('哀悼功能', 'kratos'),
            'subtitle' => __('启用/禁用站点首页黑白功能', 'kratos'),
            'default' => false,
        ),
        array(
            'id' => 'g_cdn',
            'type' => 'switcher',
            'title' => __('静态资源加速', 'kratos'),
            'subtitle' => __('启用/禁用静态资源加速', 'kratos'),
            'default' => false,
        ),
        array(
            'id' => 'g_removeimgsize',
            'type' => 'switcher',
            'title' => __('禁止生成缩略图', 'kratos'),
            'subtitle' => __('启用/禁用生成多种尺寸图片资源', 'kratos'),
            'default' => false,
        ),
        array(
            'id' => 'g_gutenberg',
            'type' => 'switcher',
            'title' => __('Gutenberg 编辑器', 'kratos'),
            'subtitle' => __('启用/禁用 Gutenberg 编辑器', 'kratos'),
            'default' => false,
        ),
        array(
            'id' => 'g_excerpt_length',
            'type' => 'text',
            'title' => __('文章简介缩略', 'kratos'),
            'subtitle' => __('文章简介显示的字符数量', 'kratos'),
            'default' => '260',
        ),
        array(
            'id' => 'g_replace_gravatar_url_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => __('Gravatar 加速服务', 'kratos'),
                ),
                array(
                    'id' => 'g_replace_gravatar_url',
                    'type' => 'switcher',
                    'title' => __('功能开关', 'kratos'),
                    'subtitle' => __('开启/关闭 Gravatar 加速服务功能', 'kratos'),
                ),
                array(
                    'id' => 'g_select_gravatar_server',
                    'type' => 'select',
                    'title' => __('Gravatar 加速服务地址', 'kratos'),
                    'subtitle' => __('请选择 Gravatar 加速服务地址', 'kratos'),
                    'options' => array(
                        'loli' => __('Loli 加速服务', 'kratos'),
                        'geekzu' => __('极客族加速服务', 'kratos'),
                        'other' => __('自定义加速服务', 'kratos'),
                    ),
                    'desc' => __('国内用户推荐「极客族加速服务」，海外用户推荐「Loli 加速服务」。', 'kratos'),
                    'dependency' => array('g_replace_gravatar_url', '==', 'true'),
                ),
                array(
                    'id' => 'g_custom_gravatar_server',
                    'type' => 'text',
                    'title' => __('自定义 Gravatar 加速服务地址', 'kratos'),
                    'subtitle' => __('请输入 Gravatar 加速服务地址', 'kratos'),
                    'desc' => __('直接输入网址即可，不需要协议头和最后的斜杠。', 'kratos'),
                    'placeholder' => 'secure.gravatar.com',
                    'dependency' => array('g_replace_gravatar_url|g_select_gravatar_server', '==|==', 'true|other'),
                ),
            ),
            'default' => array(
                'g_replace_gravatar_url' => 1,
                'g_select_gravatar_server' => 'geekzu',
            )
        ),
        array(
            'id' => 'g_wechat_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => __('微信二维码', 'kratos'),
                ),
                array(
                    'id' => 'g_wechat',
                    'type' => 'switcher',
                    'title' => __('功能开关', 'kratos'),
                    'subtitle' => __('开启/关闭微信二维码', 'kratos'),
                    'text_on' => __('开启', 'kratos'),
                    'text_off' => __('关闭', 'kratos'),
                ),
                array(
                    'id' => 'g_wechat_img',
                    'type' => 'upload',
                    'title' => __('二维码图片', 'kratos'),
                    'library' => 'image',
                    'preview' => true,
                    'subtitle' => __('浮动显示在页面右下角', 'kratos'),
                ),
            ),
            'default' => array(
                'g_wechat' => false,
                'g_wechat_img' => get_template_directory_uri() . '/assets/img/donate.png',
            ),
        ),
    ),
));

CSF::createSection($prefix, array(
    'parent' => 'global_fields',
    'title' => __('颜色配置', 'kratos'),
    'icon' => 'fas fa-arrow-right',
    'fields' => array(
        array(
            'id' => 'g_background',
            'type' => 'color',
            'default' => '#f5f5f5',
            'title' => __('全站背景颜色', 'kratos'),
            'subtitle' => __('全站页面的背景颜色', 'kratos'),
        ),
        array(
            'id' => 'g_theme_color1',
            'type' => 'color',
            'default' => '#00a2ff',
            'title' => __('主题色', 'kratos'),
            'subtitle' => __('首页分类标签、分页以及一些按钮的背景色', 'kratos'),
        ),
        array(
            'id' => 'g_theme_color2',
            'type' => 'color',
            'default' => '#0097ee',
            'title' => __('主题辅色', 'kratos'),
            'subtitle' => __('a标签hover等的颜色, 可以自行组合', 'kratos'),
        ),
        array(
            'id' => 'top_color_1',
            'type' => 'color',
            'default' => 'rgba(40, 42, 44, 0.6)',
            'title' => __('导航栏颜色', 'kratos'),
            'subtitle' => __('默认导航栏颜色', 'kratos'),
        ),
        array(
            'id' => 'top_color_2',
            'type' => 'color',
            'default' => '#fff',
            'title' => __('导航栏颜色', 'kratos'),
            'subtitle' => __('滚动条滚动后会改变导航栏颜色', 'kratos'),
        ),
        array(
            'id' => 'mb_sidebar_color',
            'type' => 'color',
            'default' => '#242b31',
            'title' => __('移动端侧边栏颜色', 'kratos'),
            'subtitle' => __('移动页面侧边导航栏背景色', 'kratos'),
        ),
        array(
            'id' => 'g_chrome',
            'type' => 'color',
            'default' => '#282a2c',
            'title' => __('Chrome 导航栏颜色', 'kratos'),
            'subtitle' => __('移动端 Chrome 浏览器导航栏颜色', 'kratos'),
        ),
    ),
));

CSF::createSection($prefix, array(
    'parent' => 'global_fields',
    'title' => __('图片配置', 'kratos'),
    'icon' => 'fas fa-arrow-right',
    'fields' => array(
        array(
            'id' => 'g_logo',
            'type' => 'upload',
            'title' => __('站点 Logo', 'kratos'),
            'library' => 'image',
            'preview' => true,
            'subtitle' => __('不上传图片则显示站点标题', 'kratos'),
        ),
        array(
            'id' => 'g_icon',
            'type' => 'upload',
            'title' => __('Favicon 图标', 'kratos'),
            'library' => 'image',
            'preview' => true,
            'subtitle' => __('浏览器收藏夹和地址栏中显示的图标', 'kratos'),
        ),
        array(
            'id' => 'g_photo_lazy',
            'type' => 'upload',
            'title' => __('延迟加载loading图片', 'kratos'),
            'library' => 'image',
            'preview' => true,
            'default' => get_template_directory_uri() . '/assets/img/loading.gif',
            'subtitle' => __('文章内图片延迟加载，首先展示的为loading动画', 'kratos'),
        ),
        array(
            'id' => 'g_404',
            'type' => 'upload',
            'title' => __('404 页面图片', 'kratos'),
            'library' => 'image',
            'preview' => true,
            'default' => get_template_directory_uri() . '/assets/img/404.jpg',
            'subtitle' => __('图片显示出来是 404 的形状', 'kratos'),
        ),
        array(
            'id' => 'g_nothing',
            'type' => 'upload',
            'title' => __('无内容图片', 'kratos'),
            'library' => 'image',
            'preview' => true,
            'default' => get_template_directory_uri() . '/assets/img/nothing.svg',
            'subtitle' => __('当搜索不到文章或分类没有文章时显示', 'kratos'),
        ),
        array(
            'id' => 'g_postthumbnail',
            'type' => 'upload',
            'title' => __('默认特色图', 'kratos'),
            'library' => 'image',
            'preview' => true,
            'default' => get_template_directory_uri() . '/assets/img/default.jpg',
            'subtitle' => __('当文章中没有图片且没有特色图时显示', 'kratos'),
        ),
    ),
));

CSF::createSection($prefix, array(
    'parent' => 'global_fields',
    'title' => __('首页轮播', 'kratos'),
    'icon' => 'fas fa-arrow-right',
    'fields' => array(
        array(
            'id' => 'g_carousel',
            'type' => 'switcher',
            'title' => __('功能开关', 'kratos'),
            'subtitle' => __('开启/关闭首页轮播功能', 'kratos'),
            'text_on' => __('开启', 'kratos'),
            'text_off' => __('关闭', 'kratos'),
            'default' => false,
        ),
        array(
            'id' => 'carousel_group',
            'type' => 'group',
            'title' => '首页轮播',
            'subtitle' => '点击添加轮播内容，最多添加 7 个轮播内容',
            'min' => 1,
            'max' => 7,
            'fields' => array(
                array(
                    'id' => 'c_id',
                    'type' => 'text',
                    'title' => __('唯一标识', 'kratos'),
                    'subtitle' => __('仅用于轮播标识，可以作为备注使用', 'kratos'),
                ),
                array(
                    'id' => 'c_img',
                    'type' => 'upload',
                    'title' => __('轮播图片', 'kratos'),
                    'subtitle' => __('可以直接填写图片链接，也可以上传图片', 'kratos'),
                    'library' => 'image',
                    'preview' => true,
                ),
                array(
                    'id' => 'c_url',
                    'type' => 'text',
                    'title' => __('网址链接', 'kratos'),
                    'subtitle' => __('需要填写完整的链接地址，包含协议头', 'kratos'),
                ),
                array(
                    'id' => 'c_title',
                    'type' => 'text',
                    'title' => __('轮播标题', 'kratos'),
                    'subtitle' => __('选填项目，如果不填则不显示', 'kratos'),
                ),
                array(
                    'id' => 'c_subtitle',
                    'type' => 'textarea',
                    'title' => __('轮播简介', 'kratos'),
                    'subtitle' => __('选填项目，如果不填则不显示', 'kratos'),
                ),
                array(
                    'id' => 'c_color',
                    'type' => 'color',
                    'default' => '#000',
                    'title' => __('文字颜色', 'kratos'),
                    'subtitle' => __('轮播标题和简介的颜色', 'kratos'),
                ),
            ),
        ),
    )
));

CSF::createSection($prefix, array(
    'title' => __('收录配置', 'kratos'),
    'icon' => 'fas fa-camera',
    'fields' => array(
        array(
            'id' => 'seo_shareimg',
            'type' => 'upload',
            'title' => __('分享图片', 'kratos'),
            'library' => 'image',
            'preview' => true,
            'default' => get_template_directory_uri() . '/assets/img/default.jpg',
            'subtitle' => __('用于搜索引擎或社交工具抓取时使用', 'kratos'),
        ),
        array(
            'id' => 'seo_keywords',
            'type' => 'text',
            'title' => __('关键词', 'kratos'),
            'subtitle' => __('每个关键词之间需要用 , 分割', 'kratos'),
        ),
        array(
            'id' => 'seo_description',
            'type' => 'textarea',
            'title' => __('站点描述', 'kratos'),
            'subtitle' => __('网站首页的描述信息', 'kratos'),
        ),
        array(
            'id' => 'seo_statistical',
            'title' => __('统计代码', 'kratos'),
            'subtitle' => __('<span style="color:red">输入代码时请注意辨别代码安全性,无需增加script头尾</span>', 'kratos'),
            'type' => 'code_editor',
            'settings' => array(
                'theme' => 'default',
                'mode' => 'html',
            ),
            'sanitize' => false,
            'default' => '',
        ),
        array(
            'id' => 'seo_robots_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => __('robots.txt 配置', 'kratos'),
                ),
                array(
                    'type' => 'content',
                    'content' => '<ul> <li>' . __('- 需要 ', 'kratos') . '<a href="' . admin_url('options-reading.php') . '" target="_blank">' . __('设置-阅读-对搜索引擎的可见性', 'kratos') . '</a>' . __(' 是开启的状态，以下配置才会生效', 'kratos') . '</li><li>' . __('- 如果网站根目录下已经有 robots.txt 文件，下面的配置不会生效', 'kratos') . '</li><li>' . __('- 点击 ', 'kratos') . '<a href="' . home_url() . '/robots.txt" target="_blank">robots.txt</a>' . __(' 查看配置是否生效，如果网站开启了 CDN，可能需要刷新缓存才会生效', 'kratos') . '</li></ul>',
                ),
                array(
                    'id' => 'seo_robots',
                    'type' => 'textarea',
                ),
            ),
            'default' => array(
                'seo_robots' => getrobots(),
            ),
        ),
    ),
));

CSF::createSection($prefix, array(
    'title' => __('文章配置', 'kratos'),
    'icon' => 'fas fa-file-alt',
    'fields' => array(
        array(
            'id' => 'g_163mic',
            'type' => 'switcher',
            'title' => __('网易云音乐', 'kratos'),
            'subtitle' => __('启用/禁用网易云音乐自动播放功能', 'kratos'),
            'default' => false,
        ),
        array(
            'id' => 'g_duplicate_page',
            'type' => 'switcher',
            'title' => __('文章复制', 'kratos'),
            'subtitle' => __('开启后可以在后台快速复制文章', 'kratos'),
            'default' => true,
        ),
        array(
            'id' => 'g_post_revision',
            'type' => 'switcher',
            'title' => __('附加功能', 'kratos'),
            'subtitle' => __('启用/禁用文章自动保存、修订版本功能', 'kratos'),
            'default' => true,
        ),
        array(
            'id' => 'g_article_widgets',
            'type' => 'image_select',
            'title' => __('页面布局', 'kratos'),
            'subtitle' => __('差异在于侧边栏小工具，仅在文章页面生效', 'kratos'),
            'options' => array(
                'one_side' => get_template_directory_uri() . '/assets/img/options/col-12.png',
                'two_side' => get_template_directory_uri() . '/assets/img/options/col-8.png',
            ),
            'default' => 'two_side',
        ),
        array(
            'id' => 'g_post_link_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'id' => 'g_jump',
                    'type' => 'switcher',
                    'title' => __('外链跳转', 'kratos'),
                    'subtitle' => __('启用/禁用文章和评论区连接跳转提示', 'kratos'),
                    'default' => true,
                ),
                array(
                    'id' => 'g_param',
                    'type' => 'text',
                    'title' => __('跳转参数或路径', 'kratos'),
                    'subtitle' => __('例如: x-item.cc/<span style="color: red">goto</span>/xx , x-item.cc/?<span style="color: red">goto</span>=xx , 红色部分. 切勿乱调整。<br> 该选项调整后需要到 <strong>设置 -> 固定连接</strong> 中点击保存用来刷新数据库。', 'kratos'),
                    'default' => 'target'
                ),

            ),
        ),
        array(
            'id' => 'g_cc_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => __('知识共享协议', 'kratos'),
                ),
                array(
                    'id' => 'g_cc_switch',
                    'type' => 'switcher',
                    'title' => __('功能开关', 'kratos'),
                    'subtitle' => __('开启/关闭 知识共享协议', 'kratos'),
                    'text_on' => __('开启', 'kratos'),
                    'text_off' => __('关闭', 'kratos'),
                ),
                array(
                    'id' => 'g_cc',
                    'type' => 'select',
                    'title' => __('协议名称', 'kratos'),
                    'subtitle' => __('选择文章的知识共享协议', 'kratos'),
                    'options' => array(
                        'one' => __('知识共享署名 4.0 国际许可协议', 'kratos'),
                        'two' => __('知识共享署名-非商业性使用 4.0 国际许可协议', 'kratos'),
                        'three' => __('知识共享署名-禁止演绎 4.0 国际许可协议', 'kratos'),
                        'four' => __('知识共享署名-非商业性使用-禁止演绎 4.0 国际许可协议', 'kratos'),
                        'five' => __('知识共享署名-相同方式共享 4.0 国际许可协议', 'kratos'),
                        'six' => __('知识共享署名-非商业性使用-相同方式共享 4.0 国际许可协议', 'kratos'),
                    ),
                ),
            ),
            'default' => array(
                'g_cc_switch' => false,
                'g_cc' => 'one',
            ),
        ),
        array(
            'id' => 'g_article_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => __('文章 HOT 标签', 'kratos'),
                ),
                array(
                    'id' => 'g_article_comment',
                    'type' => 'text',
                    'title' => __('评论数', 'kratos'),
                    'subtitle' => __('填写显示 HOT 标签需要的评论人数', 'kratos'),
                ),
                array(
                    'id' => 'g_article_love',
                    'type' => 'text',
                    'title' => __('点赞数', 'kratos'),
                    'subtitle' => __('填写显示 HOT 标签需要的点赞数', 'kratos'),
                ),
            ),
            'default' => array(
                'g_article_comment' => '20',
                'g_article_love' => '200',
            ),
        ),
        array(
            'id' => 'g_donate_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => __('文章打赏', 'kratos'),
                ),
                array(
                    'id' => 'g_donate',
                    'type' => 'switcher',
                    'title' => __('功能开关', 'kratos'),
                    'subtitle' => __('开启/关闭 文章打赏', 'kratos'),
                    'text_on' => __('开启', 'kratos'),
                    'text_off' => __('关闭', 'kratos'),
                ),
                array(
                    'id' => 'g_donate_wechat',
                    'type' => 'upload',
                    'title' => __('微信二维码', 'kratos'),
                    'library' => 'image',
                    'preview' => true,
                ),
                array(
                    'id' => 'g_donate_alipay',
                    'type' => 'upload',
                    'title' => __('支付宝二维码', 'kratos'),
                    'library' => 'image',
                    'preview' => true,
                ),
            ),
            'default' => array(
                'g_donate' => false,
                'g_donate_wechat' => get_template_directory_uri() . '/assets/img/donate.png',
                'g_donate_alipay' => get_template_directory_uri() . '/assets/img/donate.png',
            ),
        ),
        array(
            'id' => 'g_widgets_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => __('小工具相关', 'kratos'),
                ),
                array(
                    'id' => 'g_widgets_nickname',
                    'type' => 'text',
                    'title' => __('昵称', 'kratos'),
                    'subtitle' => __('个人简介详细版小工具所显示的', 'kratos'),
                ),
                array(
                    'id' => 'g_widgets_about',
                    'type' => 'text',
                    'title' => __('简介', 'kratos'),
                    'subtitle' => __('同上，一句话', 'kratos'),
                ),
                array(
                    'id' => 'g_widgets_location',
                    'type' => 'text',
                    'title' => __('地点', 'kratos'),
                    'subtitle' => __('同上，地理位置', 'kratos'),
                ),
                array(
                    'id' => 'g_widgets_gravatar',
                    'type' => 'upload',
                    'title' => __('头像', 'kratos'),
                    'subtitle' => __('同上，显示的头像', 'kratos'),
                    'library' => 'image',
                    'preview' => true,
                ),
                array(
                    'id' => 'g_widgets_create_time',
                    'type' => 'date',
                    'title' => __('创建时间', 'kratos'),
                    'subtitle' => __('同上，网站创建的时间，默认为ID为1的用户注册时间', 'kratos'),
                ),
            ),
            'default' => array(
                'g_widgets_nickname' => get_userdata(1)->nickname,
                'g_widgets_about' => '有趣的人，做有趣的事。',
                'g_widgets_location' => 'Mar.(火星)',
                'g_widgets_gravatar' => get_template_directory_uri() . '/assets/img/gravatar.png',
                'g_widgets_create_time' => get_userdata(1)->user_registered,
            ),
        ),
    ),
));

CSF::createSection($prefix, array(
    'title' => __('邮件配置', 'kratos'),
    'icon' => 'fas fa-envelope',
    'fields' => array(
        array(
            'id' => 'm_smtp',
            'type' => 'switcher',
            'title' => __('SMTP 服务', 'kratos'),
            'subtitle' => __('启用/禁用 SMTP 服务', 'kratos'),
            'default' => false,
        ),
        array(
            'id' => 'm_host',
            'type' => 'text',
            'title' => __('邮件服务器', 'kratos'),
            'subtitle' => __('填写发件服务器地址', 'kratos'),
            'placeholder' => __('smtp.example.com', 'kratos'),
        ),
        array(
            'id' => 'm_port',
            'type' => 'text',
            'title' => __('服务器端口', 'kratos'),
            'subtitle' => __('填写发件服务器端口', 'kratos'),
            'placeholder' => __('465', 'kratos'),
        ),
        array(
            'id' => 'm_sec',
            'type' => 'text',
            'title' => __('授权方式', 'kratos'),
            'subtitle' => __('填写登录鉴权的方式', 'kratos'),
            'placeholder' => __('ssl', 'kratos'),
        ),
        array(
            'id' => 'm_username',
            'type' => 'text',
            'title' => __('邮箱帐号', 'kratos'),
            'subtitle' => __('填写邮箱账号', 'kratos'),
            'placeholder' => __('user@example.com', 'kratos'),
        ),
        array(
            'id' => 'm_passwd',
            'type' => 'text',
            'title' => __('邮箱密码', 'kratos'),
            'subtitle' => __('填写邮箱密码', 'kratos'),
            'attributes' => array(
                'type' => 'password',
            ),
        ),
    ),
));

CSF::createSection($prefix, array(
    'id' => 'top_fields',
    'title' => __('顶部配置', 'kratos'),
    'icon' => 'fas fa-window-maximize',
));

CSF::createSection($prefix, array(
    'parent' => 'top_fields',
    'title' => __('图片导航', 'kratos'),
    'icon' => 'fas fa-arrow-right',
    'fields' => array(
        array(
            'id' => 'top_img_switch',
            'type' => 'switcher',
            'title' => __('图片导航', 'kratos'),
            'subtitle' => __('启用/禁用 图片导航', 'kratos'),
            'default' => true,
        ),
        array(
            'id' => 'top_img',
            'type' => 'upload',
            'title' => __('顶部图片', 'kratos'),
            'library' => 'image',
            'preview' => true,
            'default' => get_template_directory_uri() . '/assets/img/background.jpg',
        ),
        array(
            'id' => 'top_title',
            'type' => 'text',
            'title' => __('图片标题', 'kratos'),
            'default' => __('Kratos', 'kratos'),
        ),
        array(
            'id' => 'top_describe',
            'type' => 'text',
            'title' => __('标题描述', 'kratos'),
            'default' => __('一款专注于用户阅读体验的响应式博客主题', 'kratos'),
        ),
    ),
));

CSF::createSection($prefix, array(
    'parent' => 'top_fields',
    'title' => __('颜色导航', 'kratos'),
    'icon' => 'fas fa-arrow-right',
    'fields' => array(
        array(
            'id' => 'top_color',
            'type' => 'color',
            'default' => '#24292e',
            'title' => __('颜色导航', 'kratos'),
        ),
    ),
));

CSF::createSection($prefix, array(
    'id' => 'footer_fields',
    'title' => __('页脚配置', 'kratos'),
    'icon' => 'far fa-window-maximize',
));

CSF::createSection($prefix, array(
    'parent' => 'footer_fields',
    'title' => __('社交图标', 'kratos'),
    'icon' => 'fas fa-arrow-right',
    'fields' => array(
        array(
            'id' => 's_social_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => __('国内平台', 'kratos'),
                ),
                array(
                    'id' => 's_sina_url',
                    'type' => 'text',
                    'title' => __('新浪微博', 'kratos'),
                    'placeholder' => __('https://weibo.com/xxxxx', 'kratos'),
                ),
                array(
                    'id' => 's_bilibili_url',
                    'type' => 'text',
                    'title' => __('哔哩哔哩', 'kratos'),
                    'placeholder' => __('https://space.bilibili.com/xxxxx', 'kratos'),
                ),
                array(
                    'id' => 's_coding_url',
                    'type' => 'text',
                    'title' => __('CODING', 'kratos'),
                    'placeholder' => __('https://xxxxx.coding.net/u/xxxxx', 'kratos'),
                ),
                array(
                    'id' => 's_gitee_url',
                    'type' => 'text',
                    'title' => __('码云', 'kratos'),
                    'placeholder' => __('https://gitee.com/xxxxx', 'kratos'),
                ),
                array(
                    'id' => 's_douban_url',
                    'type' => 'text',
                    'title' => __('豆瓣', 'kratos'),
                    'placeholder' => __('https://www.douban.com/people/xxxxx', 'kratos'),
                ),
            ),
        ),
        array(
            'id' => 's_social_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => __('海外平台', 'kratos'),
                ),
                array(
                    'id' => 's_twitter_url',
                    'type' => 'text',
                    'title' => __('Twitter', 'kratos'),
                    'placeholder' => __('https://twitter.com/xxxxx', 'kratos'),
                ),
                array(
                    'id' => 's_telegram_url',
                    'type' => 'text',
                    'title' => __('Telegram', 'kratos'),
                    'placeholder' => __('https://t.me/xxxxx', 'kratos'),
                ),
                array(
                    'id' => 's_linkedin_url',
                    'type' => 'text',
                    'title' => __('LinkedIn', 'kratos'),
                    'placeholder' => __('https://www.linkedin.com/in/xxxxx', 'kratos'),
                ),
                array(
                    'id' => 's_youtube_url',
                    'type' => 'text',
                    'title' => __('YouTube', 'kratos'),
                    'placeholder' => __('https://www.youtube.com/channel/xxxxx', 'kratos'),
                ),
                array(
                    'id' => 's_github_url',
                    'type' => 'text',
                    'title' => __('Github', 'kratos'),
                    'placeholder' => __('https://github.com/xxxxx', 'kratos'),
                ),
                array(
                    'id' => 's_stackflow_url',
                    'type' => 'text',
                    'title' => __('Stack Overflow', 'kratos'),
                    'placeholder' => __('https://stackoverflow.com/users/xxxxx', 'kratos'),
                ),
            ),
        ),
        array(
            'id' => 's_social_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => __('其他', 'kratos'),
                ),
                array(
                    'id' => 's_email_url',
                    'type' => 'text',
                    'title' => __('电子邮箱', 'kratos'),
                    'placeholder' => __('mailto:xxxxx@example.com', 'kratos'),
                ),
            ),
            'default' => array(
                "s_sina_url" => "",
                "s_bilibili_url" => "",
                "s_coding_url" => "",
                "s_gitee_url" => "",
                "s_douban_url" => "",
                "s_twitter_url" => "",
                "s_telegram_url" => "",
                "s_linkedin_url" => "",
                "s_youtube_url" => "",
                "s_github_url" => "",
                "s_stackflow_url" => "",
                "s_email_url" => ""
            ),
        ),
    ),
));

CSF::createSection($prefix, array(
    'parent' => 'footer_fields',
    'title' => __('备案信息', 'kratos'),
    'icon' => 'fas fa-arrow-right',
    'fields' => array(
        array(
            'id' => 's_icp',
            'type' => 'text',
            'title' => __('工信部备案信息', 'kratos'),
            'subtitle' => __('由<a target="_blank" href="https://beian.miit.gov.cn/">工业和信息化部政务服务平台</a>提供', 'kratos'),
            'placeholder' => __('冀ICP证XXXXXX号', 'kratos'),
        ),
        array(
            'id' => 's_gov',
            'type' => 'text',
            'title' => __('公安备案信息', 'kratos'),
            'subtitle' => __('由<a target="_blank" href="http://www.beian.gov.cn/">全国互联网安全管理服务平台</a>提供', 'kratos'),
            'placeholder' => __('冀公网安备 XXXXXXXXXXXXX 号', 'kratos'),
        ),
        array(
            'id' => 's_gov_link',
            'type' => 'text',
            'title' => __('公安备案链接', 'kratos'),
            'subtitle' => __('由<a target="_blank" href="http://www.beian.gov.cn/">全国互联网安全管理服务平台</a>提供', 'kratos'),
            'placeholder' => __('http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=xxxxx', 'kratos'),
        ),
    ),
));

CSF::createSection($prefix, array(
    'parent' => 'footer_fields',
    'title' => __('版权信息', 'kratos'),
    'icon' => 'fas fa-arrow-right',
    'fields' => array(
        array(
            'id' => 's_copyright',
            'type' => 'textarea',
            'title' => __('版权信息', 'kratos'),
            'default' => 'COPYRIGHT © ' .  wp_date('Y') . ' ' . get_bloginfo('name') . '. ALL RIGHTS RESERVED.',
        ),
    ),
));

CSF::createSection($prefix, array(
    'id' => 'ad_fields',
    'title' => __('广告配置', 'kratos'),
    'icon' => 'fas fa-ad',
));

CSF::createSection($prefix, array(
    'parent' => 'ad_fields',
    'title' => __('文章广告', 'kratos'),
    'icon' => 'fas fa-arrow-right',
    'fields' => array(
        array(
            'id' => 'single_ad_top_group',
            'type' => 'group',
            'title' => '文章顶部广告',
            'subtitle' => '点击添加广告，最多添加 3 个顶部广告',
            'min' => 1,
            'max' => 3,
            'fields' => array(
                array(
                    'id' => 'ad_id',
                    'type' => 'text',
                    'title' => __('唯一标识', 'kratos'),
                    'subtitle' => __('仅用于识别广告内容，可以作为备注使用', 'kratos'),
                ),
                array(
                    'id' => 'ad_img',
                    'type' => 'upload',
                    'title' => __('轮播图片', 'kratos'),
                    'subtitle' => __('可以直接填写图片链接，也可以上传图片', 'kratos'),
                    'library' => 'image',
                    'preview' => true,
                ),
                array(
                    'id' => 'ad_url',
                    'type' => 'text',
                    'title' => __('网址链接', 'kratos'),
                    'subtitle' => __('需要填写完整的链接地址，包含协议头', 'kratos'),
                ),
                array(
                    'id' => 'ad_switcher',
                    'type' => 'switcher',
                    'title' => __('功能开关', 'kratos'),
                    'subtitle' => __('开启/关闭此条广告', 'kratos'),
                    'text_on' => __('开启', 'kratos'),
                    'text_off' => __('关闭', 'kratos'),
                    'default' => true
                ),
            ),
        ),
        array(
            'id' => 'single_ad_bottom_group',
            'type' => 'group',
            'title' => '文章底部广告',
            'subtitle' => '点击添加广告，最多添加 3 个底部广告',
            'min' => 1,
            'max' => 3,
            'fields' => array(
                array(
                    'id' => 'ad_id',
                    'type' => 'text',
                    'title' => __('唯一标识', 'kratos'),
                    'subtitle' => __('仅用于识别广告内容，可以作为备注使用', 'kratos'),
                ),
                array(
                    'id' => 'ad_img',
                    'type' => 'upload',
                    'title' => __('轮播图片', 'kratos'),
                    'subtitle' => __('可以直接填写图片链接，也可以上传图片', 'kratos'),
                    'library' => 'image',
                    'preview' => true,
                ),
                array(
                    'id' => 'ad_url',
                    'type' => 'text',
                    'title' => __('网址链接', 'kratos'),
                    'subtitle' => __('需要填写完整的链接地址，包含协议头', 'kratos'),
                ),
                array(
                    'id' => 'ad_switcher',
                    'type' => 'switcher',
                    'title' => __('功能开关', 'kratos'),
                    'subtitle' => __('开启/关闭此条广告', 'kratos'),
                    'text_on' => __('开启', 'kratos'),
                    'text_off' => __('关闭', 'kratos'),
                    'default' => true
                ),
            ),
        ),
    ),
));

CSF::createSection($prefix, array(
    'title' => __('备份恢复', 'kratos'),
    'icon' => 'fas fa-undo',
    'fields' => array(
        array(
            'type' => 'backup',
        ),
    ),
));

CSF::createSection($prefix, array(
    'title' => __('关于主题', 'kratos'),
    'icon' => 'fas fa-question-circle',
    'fields' => array(
        array(
            'type' => 'subheading',
            'content' => __('基础信息', 'kratos'),
        ),
        array(
            'type' => 'submessage',
            'style' => 'info',
            'content' => __('提示：在反馈主题相关的问题时，请同时复制并提交下面的内容。', 'kratos'),
        ),
        array(
            'type' => 'content',
            'content' => '<ul style="margin: 0 auto;"> <li>' . __('PHP 版本：', 'kratos') . PHP_VERSION . '</li> <li>' . __('Kratos 版本：', 'kratos') . THEME_VERSION . '</li> <li>' . __('WordPress 版本：', 'kratos') . $wp_version . '</li> <li>' . __('User Agent 信息：', 'kratos') . $_SERVER['HTTP_USER_AGENT'] . '</li> </ul>',
        ),

        array(
            'type' => 'subheading',
            'content' => __('资料文档', 'kratos'),
        ),
        array(
            'type' => 'content',
            'content' => '<ul style="margin: 0 auto;"> <li>' . __('说明文档：', 'kratos') . '<a href="https://www.vtrois.com/" target="_blank">https://www.vtrois.com/</a></li> <li>' . __('讨论反馈：', 'kratos') . '<a href="https://github.com/seatonjiang/kratos/discussions" target="_blank">https://github.com/seatonjiang/kratos/discussions</a></li> <li>' . __('常见问题：', 'kratos') . '<a href="https://github.com/seatonjiang/kratos/wiki/%E5%B8%B8%E8%A7%81%E9%97%AE%E9%A2%98" target="_blank">https://github.com/seatonjiang/kratos/wiki</a></li> <li>' . __('更新日志：', 'kratos') . '<a href="https://github.com/seatonjiang/kratos/releases" target="_blank">https://github.com/seatonjiang/kratos/releases</a></li> <li>' . __('捐赠记录：', 'kratos') . '<a href="https://docs.qq.com/sheet/DV0NwVnNoYWxGUmlD" target="_blank">https://docs.qq.com/sheet/DV0NwVnNoYWxGUmlD</a></li> </ul>',
        ),
        array(
            'type' => 'subheading',
            'content' => __('版权声明', 'kratos'),
        ),
        array(
            'type' => 'content',
            'content' => __('主题源码使用 <a href="https://github.com/seatonjiang/kratos/blob/main/LICENSE" target="_blank">GPL-3.0 协议</a> 进行许可，说明文档使用 <a href="https://creativecommons.org/licenses/by-nc-nd/4.0/" target="_blank">CC BY-NC-ND 4.0</a> 进行许可。', 'kratos'),
        ),
        array(
            'type' => 'subheading',
            'content' => __('版本说明', 'kratos'),
        ),
        array(
            'type' => 'content',
            'content' => __('你看到此提示说明该主题非原版kratos主题，为Virace修改自用主题，修改详情访问：<a href="https://github.com/Virace/kratos-pe" target="_blank">kratos-pe</a>', 'kratos'),
        ),
        array(
            'type' => 'subheading',
            'content' => __('讨论交流', 'kratos'),
        ),
        array(
            'type' => 'content',
            'content' => '<div style="max-width:800px;"><img style="width: 100%;height: auto;" src="' . get_template_directory_uri() . '/assets/img/options/discuss.png"></div>',
        ),
        array(
            'type' => 'subheading',
            'content' => __('打赏支持', 'kratos'),
        ),
        array(
            'type' => 'content',
            'content' => '<div style="max-width:800px;"><img style="width: 100%;height: auto;" src="' . get_template_directory_uri() . '/assets/img/options/donate.png"></div>',
        ),
    ),
));
