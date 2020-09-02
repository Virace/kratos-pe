<?php
// 请在第三行开始编写代码

// 获取浏览次数
function get_totalviews($echo = 1)
{
    global $wpdb;
    $total_views = $wpdb->get_var("SELECT SUM(meta_value+0) FROM $wpdb->postmeta WHERE meta_key = 'views'");
    if ($echo) echo $total_views;
    else return $total_views;
}


//取最后一次活动时间. 徒增功耗
function last_login()
{
    global $wpdb;
    $date1 = $wpdb->get_var("SELECT comment_date FROM $wpdb->comments WHERE user_id = 1");
    $date2 = $wpdb->get_var("SELECT post_modified FROM $wpdb->posts WHERE post_author = 1");
    $date1 = strtotime(empty($date1) ? 0 : $date1);
    $date2 = strtotime(empty($date2) ? 0 : $date2);

    echo $date1 < $date2 ? human_time_diff($date2) : human_time_diff($date1);


}

function timeago($ptime)
{
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if ($etime < 1) return __('刚刚');
    $interval = array(
        12 * 30 * 24 * 60 * 60 => __(' 年前') . ' (' . date(__('m月d日'), $ptime) . ')',
        30 * 24 * 60 * 60 => __(' 个月前') . ' (' . date(__('m月d日'), $ptime) . ')',
        7 * 24 * 60 * 60 => __(' 周前') . ' (' . date(__('m月d日'), $ptime) . ')',
        24 * 60 * 60 => __(' 天前') . ' (' . date(__('m月d日'), $ptime) . ')',
        60 * 60 => __(' 小时前') . ' (' . date(__('m月d日'), $ptime) . ')',
        60 => __(' 分钟前') . ' (' . date(__('m月d日'), $ptime) . ')',
        1 => __(' 秒前') . ' (' . date(__('m月d日'), $ptime) . ')',
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}

//string cut
function string_cut($string, $sublen, $start = 0, $code = 'UTF-8')
{
    if ($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        if (count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)) . "...";
        return join('', array_slice($t_string[0], $start, $sublen));
    } else {
        $start = $start * 2;
        $sublen = $sublen * 2;
        $strlen = strlen($string);
        $tmpstr = '';
        for ($i = 0; $i < $strlen; $i++) {
            if ($i >= $start && $i < ($start + $sublen)) {
                if (ord(substr($string, $i, 1)) > 129) $tmpstr .= substr($string, $i, 2);
                else $tmpstr .= substr($string, $i, 1);
            }
            if (ord(substr($string, $i, 1)) > 129) $i++;
        }
        return $tmpstr;
    }
}

add_filter('the_content', function ($content) {
    $post_id = get_the_ID();
    if (doing_filter('get_the_excerpt') || !is_singular() || $post_id != get_queried_object_id()) {
        return $content;
    }

    global $toc_count, $toc_items;

    $toc_items = [];
    $toc_count = 0;

    // 取锚点, 正常设置到h3即可
    $regex = '#<h([1-6])(.*?)>(.*?)</h\1>#';

    $content = preg_replace_callback($regex, function ($matches) {
        global $toc_count, $toc_items;

        $toc_count++;
        $toc_items[] = ['text' => trim(strip_tags($matches[3])), 'depth' => $matches[1], 'count' => $toc_count];

//        return "<h{$matches[1]} {$matches[2]}><a name=\"toc-{$toc_count}\"></a>{$matches[3]}</h{$matches[1]}>";
        return "<h{$matches[1]} {$matches[2]}><a id=\"toc-{$toc_count}\"></a>{$matches[3]}</h{$matches[1]}>";
//        return "<h{$matches[1]} id=\"toc-{$toc_count}\" {$matches[2]}>{$matches[3]}</h{$matches[1]}>";
    }, $content);


    return $content;
});


// 根据 $TOC 数组输出文章目录 HTML 代码
function wpjam_get_toc()
{
    global $toc_items;

    if (empty($toc_items)) {
        return '';
    }

    $index = '<ul class="nav flex-column">' . "\n";
    $prev_depth = 0;
    $to_depth = 0;
    foreach ($toc_items as $toc_item) {
        $toc_depth = $toc_item['depth'];

        if ($prev_depth) {
            if ($toc_depth == $prev_depth) {
                $index .= '</li>' . "\n";
            } elseif ($toc_depth > $prev_depth) {
                $to_depth++;
                $index .= '<ul role="tablist">' . "\n";
            } else {
                $to_depth2 = ($to_depth > ($prev_depth - $toc_depth)) ? ($prev_depth - $toc_depth) : $to_depth;

                if ($to_depth2) {
                    for ($i = 0; $i < $to_depth2; $i++) {
                        $index .= '</li>' . "\n" . '</ul>' . "\n";
                        $to_depth--;
                    }
                }

                $index .= '</li>';
            }
        }

        $prev_depth = $toc_depth;

        $index .= '<li class="nav-item"><a class="nav-link" href="#toc-' . $toc_item['count'] . '">' . $toc_item['text'] . '</a>';
    }

    for ($i = 0; $i <= $to_depth; $i++) {
        $index .= '</li>' . "\n" . '</ul>' . "\n";
    }

    return $index;
}


function get_background()
{
    if (!kratos_option('top_img')) {
        $id = rand(2, 9);
        return ASSET_PATH . '/assets/img/background-' . $id . '.png';
    } else {
        return kratos_option('top_img', ASSET_PATH . '/assets/img/background.png');
    }

}

function post_get($str){
    return !empty($_POST[$str]) ? $_POST[$str] : null;
}
