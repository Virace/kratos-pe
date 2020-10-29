<?php
function set_smilies ($smilies) {
    /* 因为translate_smiley函数内并没有对svg格式进行支持
     * 所以这里后缀暂时改为png或$image_exts = array( 'jpg', 'jpeg', 'jpe', 'gif', 'png' );
     * 只是为了通过验证
     * 下面hook smilies_src 修改地址时候将后缀名改掉
     */
    $new = array(
        ':catcry:' => 'cat-cry.png',
        ':shock:' => "surprised.png",
        ':smile:' => "happy.png",
        ':???:' => "thinking.png",
        ':cool:' => "cool.png",
        ':evil:' => "devil.png",
        ':grin:' => "happy-3.png",
        ':angel:' => "angel.png",
        ':oops:' => "embarrassed.png",
        ':razz:' => "tongue-1.png",
        ':roll:' => "thinking-1.png",
        ':wink:' => "wink.png",
        ':cry:' => "crying.png",
        ':eek:' => "shocked-1.png",
        ':lol:' => "laughing.png",
        ':mad:' => "angry-1.png",
        ':fuk:' => "angry-2.png",
        ':sad:' => "sad-1.png",
        ':sick:' => "sick.png",
        ':silent:' => "silent.png",
        ':skull:' => "skull.png",
        ':sleep:' => "sleep.png",
        ':smart:' => "smart.png",
        ':rich:' => "rich.png",
        ':poo:' => "poo.png",
        ':gig:' => "laughing-1.png",
        ':kiss:' => "kiss.png",
        ':love:' => "in-love.png",
        '8-)' => "cool.png",
        '8-O' => "shocked-1.png",
        ':-(' => "sad-1.png",
        ':-)' => "happy.png",
        ':-D' => "happy-3.png",
        ':-P' => "tongue.png",
        ':-o' => "shocked-1.png",
        ':-x' => "angry.png",
        '8O' => "shocked-2.png",
        ':(' => "sad.png",
        ':)' => "happy-2.png",
        ':D' => "cat.png",
        ':P' => "surprised-2.png",
        ':o' => "shocked-1.png",
        ':x' => "secret.png",
    );

    $smilies = array_merge($smilies, $new);
    return $new;
}
add_filter('smilies', 'set_smilies');

//

function custom_smilies_src($img_src, $img, $siteurl)
{
    // 将链接替换为主题内资源, 并且将后缀名改回svg
    return ASSET_PATH . '/assets/img/smilies/' . str_replace('.png', '.svg', $img);
}

add_filter('smilies_src', 'custom_smilies_src', 1, 10);

function display_smilies()
{
    global $wpsmiliestrans;
    $temp = array();
    $ignore = array(':mrgreen:');
    foreach ($wpsmiliestrans as $k => $v) {
        if (!in_array($v, $temp) and substr($v, -1)==='g' and !in_array($k, $ignore)) {
            array_push($temp, $v);
            echo sprintf('<a href="javascript:grin(\'%s\')"><img src="' . ASSET_PATH . '/assets/img/smilies/%s" alt="" class="d-block"/></a>', $k, str_replace('.png', '.svg', $v));
        }

    }
}