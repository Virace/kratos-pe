<?php

// 标题配置
function title($title, $sep): string
{
    global $paged, $page;
    if (is_feed()) {
        return $title;
    }
    $title .= get_bloginfo('name');
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        $title = "{$title} {$sep} {$site_description}";
    }
    if ($paged >= 2 || $page >= 2) {
        $title = "{$title} {$sep} " . sprintf(__('第 %s 页', 'kratos'), max($paged, $page));
    }
    return $title;
}
add_filter('wp_title', 'title', 10, 2);

// Keywords 配置
function keywords(): string
{
    global $post;
    if (is_home()) {
        $keywords = kratos_option('seo_keywords');
    } elseif (is_single()) {
        $keywords = get_post_meta($post->ID, "seo-meta-keywords", true);
        if($keywords == '') {

            $tags = wp_get_post_tags($post->ID);
            foreach ($tags as $tag ) {
                $keywords = $keywords . $tag->name . ", ";
            }
            $keywords = rtrim($keywords, ', ');
        }
    } elseif (is_page()) {
        $keywords = get_post_meta($post->ID, "seo-meta-keywords", true);
        if($keywords == '') {
            $keywords = kratos_option('seo_keywords');
        }
    } else {
        $keywords = single_tag_title('', false);
    }
    return trim(strip_tags($keywords));
}

// Description 配置
function description(): string
{
    global $post;
    if (is_home()) {
        $description = kratos_option('seo_description');
    } elseif (is_single()) {
        $description = get_post_meta($post->ID, "seo-meta-description", true);
        if ($description == '') {
            $description = get_the_excerpt();
        }
        if ($description == '') {
            $description = str_replace("\n","",mb_strimwidth(strip_tags($post->post_content), 0, 200, "…", 'utf-8'));
        }
    } elseif (is_category()) {
        $description = category_description();
    } elseif (is_tag()) {
        $description = tag_description();
    } elseif (is_page()) {
        $description = get_post_meta($post->ID, "seo-meta-description", true);
        if ($description == '') {
            $description = kratos_option('seo_description');
        }
    }
    return trim(strip_tags($description));
}

// robots.txt 配置
add_filter('robots_txt', function ($output, $public) {
    if ('0' == $public) {
        return "User-agent: *\nDisallow: /\n";
    } else {
        if (!empty(kratos_option('seo_robots'))) {
            $output = esc_attr(strip_tags(kratos_option('seo_robots')));
        }
        return $output;
    }
}, 10, 2);


// 抓取图片链接（搜索引擎或者社交工具分享时抓取图片的链接）
function share_thumbnail_url()
{
    global $post;
    if(!is_object($post))
        return;
    if (has_post_thumbnail($post->ID)) {
        $post_thumbnail_id = get_post_thumbnail_id($post);
        $img = wp_get_attachment_image_src($post_thumbnail_id, 'full');
        $img = $img[0];
    } else {
        $content = $post->post_content;
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?); ?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
        if (!empty($strResult[1])) {
            $img = $strResult[1][0];
        } else {
            $img = kratos_option('seo_shareimg', ASSET_PATH . '/assets/img/default.jpg');
        }
    }
    return $img;
}