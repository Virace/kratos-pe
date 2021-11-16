<?php
// 腾讯云验证码
if (kratos_option('g_007', false)) {
    add_action('login_head', 'add_login_head');
    function add_login_head()
    {
        echo '<script src="https://ssl.captcha.qq.com/TCaptcha.js"></script>';
    }

    add_action('login_form', 'add_captcha_body');
    function add_captcha_body(){ ?>
        <label for="codeVerifyButton">人机验证</label>
        <input type="button" name="TencentCaptcha" id="TencentCaptcha" data-appid="<?php echo kratos_option('g_007_appid'); ?>" data-cbfn="callback" class="button" value="验证" style="width: 100%;margin-bottom: 16px;height:40px;" />
        <input type="hidden" id="codeCaptcha" name="codeCaptcha" value="" />
        <input type="hidden" id="codeVerifyTicket" name="codeVerifyTicket" value="" />
        <input type="hidden" id="codeVerifyRandstr" name="codeVerifyRandstr" value="" />
        <script>
            window.callback = function(res){
                if(res.ret === 0)
                {
                    var verifybutton = document.getElementById("TencentCaptcha");
                    document.getElementById("codeVerifyTicket").value = res.ticket;
                    document.getElementById("codeVerifyRandstr").value = res.randstr;
                    document.getElementById("codeCaptcha").value = 1;
                    verifybutton.setAttribute("disabled", "disabled");
                    verifybutton.style.cssText = "background-color:#4fb845!important; color:#fff!important; width:100%; margin-bottom:16px; height: 40px;pointer-events:none;";
                    verifybutton.value = "验证成功";
                }
            }
        </script>
        <?php
    }

    add_filter('wp_authenticate_user', 'validate_tcaptcha_login', 100, 1);
    function validate_tcaptcha_login($user) {
        $slide = $_POST['codeCaptcha'];
        if($slide == '')
        {
            return  new WP_Error('broke', __("错误：请进行真人验证"));
        }else{
            $result = validate_login($_POST['codeVerifyTicket'], $_POST['codeVerifyRandstr']);
            if($result['result'])
            {
                return $user;
            }else{
                return new WP_Error('broke', $result['message']);
            }
        }
    }

    function validate_login($ticket,$randstr){
        $appid = kratos_option('g_007_appid');
        $appsecretkey = kratos_option('g_007_appsecretkey');
        $userip = $_SERVER["REMOTE_ADDR"];
        $url = "https://ssl.captcha.qq.com/ticket/verify";
        $params = array(
            "aid"          => $appid,
            "AppSecretKey" => $appsecretkey,
            "Ticket"       => $ticket,
            "Randstr"      => $randstr,
            "UserIP"       => $userip
        );
        $paramstring = http_build_query($params);
        $content = txcurl($url, $paramstring);
        $result = json_decode($content, true);

        if($result){
            if($result['response'] == 1){
                return array(
                    'result'  => 1,
                    'message' => ''
                );
            }else{
                return array(
                    'result'  => 0,
                    'message' => $result['err_msg']
                );
            }
        }else{
            return array(
                'result'  => 0,
                'message' => '错误：请求异常，请稍后再试'
            );
        }
    }

    function txcurl($url, $params=false, $ispost=0)
    {
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if($ispost)
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        }else{
            if($params)
            {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            }else{
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        if ($response === FALSE)
        {
            return false;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));

        curl_close($ch);
        return $response;
    }
}