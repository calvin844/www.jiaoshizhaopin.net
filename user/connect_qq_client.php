<?php

/*
 * 74cms QQ互联 client-side模式
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/plus.common.inc.php');
require_once(dirname(__FILE__) . '/../include/common.inc.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'QQlogin';
if ($act == 'QQlogin') {
    $url = "https://graph.qq.com/oauth2.0/authorize?response_type=token&client_id={$_CFG['qq_appid']}&redirect_uri={$_CFG['main_domain']}user/connect_qq_client.php" . urlencode('?act=login_check&tiku_key=' . $_GET['tiku_key'] . '&tiku_index=' . $_GET['tiku_index']);
    header("Location:{$url}");
} elseif ($act == 'login_check') {
    $html = "<script type=\"text/javascript\" src=\"http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js\" charset=\"utf-8\" data-callback=\"true\"></script> ";
    $html.="<script type=\"text/javascript\">";
    $html.="if(QC.Login.check())";
    $html.="{";
    $html.="QC.Login.getMe(function(openId, accessToken)";
    $html.="{";
    $html.="window.location.href = '?act=login_go&openid='+openId+'&accessToken='+accessToken+'&tiku_key=" . $_GET['tiku_key'] . "&tiku_index=" . $_GET['tiku_index'] . "';";
    $html.="});";
    $html.="}";
    $html.="</script>";
    exit($html);
} elseif ($act == 'ajax_login') {
    $captcha = get_cache('captcha');
    $smarty->assign('verify_userlogin', $captcha['verify_userlogin']);
    $smarty->assign('qqopenid', $_SESSION["openid"]);
    $smarty->display('plus/ajax_login.htm');
    exit();
} elseif ($act == 'login_go') {
    $_SESSION["openid"] = trim($_GET['openid']);
    $_SESSION["accessToken"] = trim($_GET['accessToken']);
    $tiku_key = isset($_GET['tiku_key']) ? intval($_GET['tiku_key']) : 0;
    if (empty($_SESSION["openid"])) {
        showmsg('登录失败！openid获取不到', 0);
    } else {
        require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
        $db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
        unset($dbhost, $dbuser, $dbpass, $dbname);
        require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
        $user = get_user_inqqopenid($_SESSION["openid"]);
        if (!empty($user)) {
            update_user_info($user['uid']);
            $userurl = get_member_url($_SESSION['utype']);
            if ($tiku_key > 0) {
                if ($_SESSION['utype'] != 2) {
                    setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                    setcookie("QS[username]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                    setcookie("QS[password]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                    setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                    unset($_SESSION['uid'], $_SESSION['username'], $_SESSION['utype'], $_SESSION['uqqid'], $_SESSION['activate_username'], $_SESSION['activate_email'], $_SESSION["openid"]);
                    $url = "http://tiku.jiaoshizhaopin.net/tiku";
                    exit('<script type="text/javascript" language="javascript">alert("请使用个人账号登录！");window.location.href="' . $url . '";</script> ');
                } else {
                    $mem = new Memcache;
                    $mem->connect("localhost", 11111);
                    $mem->set(md5("tiku_" . $tiku_key), get_user_inid($_SESSION['uid']), 0, 3600);
                    $qsurl = "http://tiku.jiaoshizhaopin.net/tiku/login?login_key=" . md5("tiku_" . $tiku_key) . "&&tiku_index=" . $_GET['tiku_index'];
                    exit("<script language=\"javascript\" type=\"text/javascript\">window.location.href=\"" . $qsurl . "\";</script>");
                }
            } else {
                header("Location:{$userurl}");
            }
        } else {
            if (!empty($_SESSION['uid']) && !empty($_SESSION['utype']) && !empty($_SESSION['openid'])) {
                require_once(QISHI_ROOT_PATH . 'include/tpl.inc.php');
                $db->query("UPDATE " . table('members') . " SET qq_openid = '{$_SESSION['openid']}'  WHERE uid='{$_SESSION[uid]}' AND qq_openid='' LIMIT 1");
                $_SESSION['uqqid'] = $_SESSION['openid'];
                if ($tiku_key > 0) {
                    if ($_SESSION['utype'] != 2) {
                        setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                        setcookie("QS[username]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                        setcookie("QS[password]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                        setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                        unset($_SESSION['uid'], $_SESSION['username'], $_SESSION['utype'], $_SESSION['uqqid'], $_SESSION['activate_username'], $_SESSION['activate_email'], $_SESSION["openid"]);
                        $url = "http://tiku.jiaoshizhaopin.net/tiku";
                        exit('<script type="text/javascript" language="javascript">alert("请使用个人账号登录！");window.location.href="' . $url . '";</script> ');
                    } else {
                        $mem = new Memcache;
                        $mem->connect("localhost", 11111);
                        $mem->set(md5("tiku_" . $tiku_key), get_user_inid($_SESSION['uid']), 0, 3600);
                        $qsurl = "http://tiku.jiaoshizhaopin.net/tiku/login?login_key=" . md5("tiku_" . $tiku_key) . "&&tiku_index=" . $_GET['tiku_index'];
                        exit('<script type="text/javascript" language="javascript">alert("绑定QQ帐号成功！");window.location.href="' . $qsurl . '";</script> ');
                    }
                } else {
                    $link[0]['text'] = "进入会员中心";
                    $link[0]['href'] = get_member_url($_SESSION['utype']);
                    showmsg('绑定QQ帐号成功！', 2, $link);
                }
            } else {
                header("Location:?act=reg&tiku_key=" . $_GET['tiku_key'] . "&tiku_index=" . $_GET['tiku_index']);
            }
        }
    }
} elseif ($act == 'reg') {
    if (empty($_SESSION["openid"])) {
        exit("openid is empty");
    } else {
        $url = "https://graph.qq.com/user/get_user_info?access_token=" . $_SESSION['accessToken'] . "&oauth_consumer_key={$_CFG['qq_appid']}&openid=" . $_SESSION["openid"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $jsoninfo = json_decode($output, true);
        $nickname = iconv("utf-8", "gbk", $jsoninfo["nickname"]);
        $photo = $jsoninfo["figureurl_qq_2"];
        require_once(QISHI_ROOT_PATH . 'include/tpl.inc.php');
        $smarty->assign('title', '补充信息 - ' . $_CFG['site_name']);
        $smarty->assign('qqurl', "?act=");
        $smarty->assign('nickname', $nickname);
        $smarty->assign('photo', $photo);
        $smarty->display('user/connect-qq.htm');
    }
} elseif ($act == 'reg_save') {
    if (empty($_SESSION["openid"])) {
        exit("openid is empty");
    }
    $postcaptcha = $_POST['postcaptcha'];
    if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $postcaptcha = utf8_to_gbk($postcaptcha);
    }
    if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        showmsg('图片验证码错误！', 0, $link);
    }
    $mobile = empty($_POST['mobile']) ? exit("err") : intval($_POST['mobile']);
    $sql = "UPDATE " . table('yunpian_sms_log') . " SET back = '1' WHERE phone='{$mobile}' and sms_type=1 order by addtime desc LIMIT 1";
    $db->query($sql);
    if ($mem->get($mobile) != intval($_POST['code'])) {
        showmsg('短信验证码错误！', 0, $link);
    }
    $_SESSION['mobile'] = $mobile;
    $_SESSION['member_type'] = $_POST['member_type'];
    $smarty->assign('nickname', $_POST['nickname']);
    $smarty->assign('photo', $_POST['photo']);
    $smarty->display('user/connect-qq2.htm');
} elseif ($act == 'reg_save2') {
    if (empty($_SESSION["openid"])) {
        exit("openid is empty");
    }
    $val['username'] = !empty($_POST['username']) ? trim($_POST['username']) : exit("err");
    $val['email'] = !empty($_POST['email']) ? trim($_POST['email']) : exit("err");
    $val['member_type'] = intval($_SESSION['member_type']);
    $val['password'] = !empty($_POST['password']) ? trim($_POST['password']) : exit("err");
    require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
    $db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
    unset($dbhost, $dbuser, $dbpass, $dbname);
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $userid = user_register($val['username'], $val['password'], $val['member_type'], $val['email'], $_SESSION['mobile']);
    if ($userid) {
        $db->query("UPDATE " . table('members') . " SET qq_openid = '{$_SESSION['openid']}',mobile = '{$_SESSION['mobile']}',mobile_audit = 1  WHERE uid='{$userid}' AND qq_openid='' LIMIT 1");
        update_user_info($userid);
        $userurl = $val['member_type'] == 1 ? "/user/company_make_info.php" : "/user/user_make_info.php";
        header("Location:{$userurl}");
    } else {
        require_once(QISHI_ROOT_PATH . 'include/tpl.inc.php');
        $link[0]['text'] = "返回首页";
        $link[0]['href'] = "{$_CFG['main_domain']}";
        showmsg('注册失败！', 0, $link);
    }
} elseif ($act == 'binding') {
    $url = "https://graph.qq.com/oauth2.0/authorize?response_type=token&client_id={$_CFG['qq_appid']}&redirect_uri={$_CFG['main_domain']}user/connect_qq_client.php" . urlencode('?act=binding_check');
    header("Location:{$url}");
} elseif ($act == 'binding_check') {
    $html = "<script type=\"text/javascript\" src=\"http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js\" charset=\"utf-8\" data-callback=\"true\"></script> ";
    $html.="<script type=\"text/javascript\">";
    $html.="if(QC.Login.check())";
    $html.="{";
    $html.="QC.Login.getMe(function(openId, accessToken)";
    $html.="{";
    $html.="window.location.href = '?act=binding_callback&openid='+openId;";
    $html.="});";
    $html.="}";
    $html.="</script>";
    exit($html);
} elseif ($act == 'binding_callback') {
    if (empty($_SESSION['uid']) || empty($_SESSION['utype'])) {
        exit("error");
    }
    $_SESSION["openid"] = trim($_GET['openid']);
    if (empty($_SESSION['openid'])) {
        exit("error");
    }
    require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
    $db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
    unset($dbhost, $dbuser, $dbpass, $dbname);
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $user = get_user_inqqopenid($_SESSION["openid"]);
    require_once(QISHI_ROOT_PATH . 'include/tpl.inc.php');
    if (!empty($user)) {
        $link[0]['text'] = "用别的QQ帐号绑定";
        $link[0]['href'] = "?act=binding";
        $link[1]['text'] = "进入会员中心";
        $link[1]['href'] = get_member_url($_SESSION['utype']);
        showmsg('此QQ帐号已经绑定了其他会员,请换一个QQ帐号！', 2, $link);
    } else {
        $db->query("UPDATE " . table('members') . " SET qq_openid = '{$_SESSION['openid']}'  WHERE uid='{$_SESSION[uid]}' AND qq_openid='' LIMIT 1");
        $link[0]['text'] = "进入会员中心";
        $link[0]['href'] = get_member_url($_SESSION['utype']);
        $_SESSION['uqqid'] = $_SESSION['openid'];
        showmsg('绑定QQ帐号成功！', 2, $link);
    }
} elseif ($act == 'del_qq_binding') {
    $db->query("UPDATE " . table('members') . " SET qq_openid = ''  WHERE uid='" . $_SESSION['uid'] . "' LIMIT 1");
    showmsg('解除绑定成功！', 2);
}
?>