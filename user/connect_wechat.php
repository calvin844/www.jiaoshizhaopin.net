<?php

/*
 * 74cms QQ互联 server-side模式
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
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'WXlogin';
if (!function_exists('json_decode')) {
    exit('您的php不支持json_decode');
}
if ($_REQUEST['state'] != $_SESSION['state']) {
//exit("The state does not match. You may be a victim of CSRF.");
}

$login_allback = $_CFG['main_domain'] . "user/connect_wechat.php?act=login_allback";
$binding_callback = $_CFG['main_domain'] . "user/connect_wechat.php?act=binding_callback";
if ($_CFG['wx_login_apiopen'] == "0" || empty($_CFG['wx_login_appid']) || empty($_CFG['wx_login_secret'])) {
    header("Location:{$_SERVER['HTTP_REFERER']}");
} elseif ($act == 'ajax_info') {
    $_SESSION['state'] = md5(uniqid(rand(), TRUE));
    $data['appid'] = $_CFG['wx_login_appid'];
    $data['url'] = urlencode($login_allback);
    $data['state'] = $_SESSION['state'];
    exit(json_encode($data));
} elseif ($act == 'ajax_login') {
    $captcha = get_cache('captcha');
    $smarty->assign('verify_userlogin', $captcha['verify_userlogin']);
    $smarty->assign('wxopenid', $_SESSION["openid"]);
    $smarty->display('plus/ajax_login.htm');
    exit();
} elseif ($act == 'WXlogin') {
    $_SESSION['state'] = md5(uniqid(rand(), TRUE));
    $login_url = "https://open.weixin.qq.com/connect/qrconnect?appid=" . $_CFG['wx_login_appid'] . "&redirect_uri=" . urlencode($login_allback) . "&response_type=code&scope=snsapi_login&state=" . $_SESSION['state'] . "#wechat_redirect";
    header("Location:" . $login_url);
} elseif ($act == 'login_allback') {
    $login_code = $_REQUEST['login_code'];
    $get_userinfo_url = "https://m.qingkao.net/ov2/get_component_login_state_data?sales_id=86&access_token=gSJH9WUQOX8ra8CuXtjeU7Qgn3R1I&login_code=" . $login_code;
    $response_userinfo = get_url_contents($get_userinfo_url);
    //$response_userinfo = $_REQUEST['login_code'];
    $user_ = json_decode($response_userinfo);
    $openid = $user_->component_user->openid;
    if (!empty($openid)) {
        require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
        $_SESSION['wechat_openid'] = $openid;
        $_SESSION['nickname'] = $user_->component_user->nickname;
        $_SESSION['headimgurl'] = $user_->component_user->headimgurl;
        $_SESSION['province'] = $user_->component_user->province;
        $_SESSION['city'] = $user_->component_user->city;
        $_SESSION['country'] = $user_->component_user->country;
        $user = get_user_in_wechat_openid($openid);
        if (!empty($user)) {
            $db->query("UPDATE " . table('members') . " SET wechat_openid='" . $openid . "', wechat_district='" . iconv("utf-8", "gbk", $_SESSION['country']) . "/" . iconv("utf-8", "gbk", $_SESSION['province']) . "/" . iconv("utf-8", "gbk", $_SESSION['city']) . "'  WHERE uid='" . $user['uid'] . "' LIMIT 1");
            update_user_info($user['uid'], 1, 1, 7);
            $userurl = get_member_url($user['utype']);
            header("Location:" . $userurl);
        } else {
            if (!empty($_SESSION['uid']) && !empty($_SESSION['utype']) && !empty($openid)) {
                $db->query("UPDATE " . table('members') . " SET wechat_openid='" . $openid . "'  WHERE uid='" . $_SESSION['uid'] . "' AND wechat_openid='' LIMIT 1");
                $_SESSION['uwxid'] = $openid;
                $link = get_member_url($_SESSION['utype']);
                header("Location:" . $link);
            } else {
                header("Location:/user/connect_wechat.php?act=reg");
            }
        }
    }
    /*
      $get_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $_CFG['wx_login_appid'] . "&secret=" . $_CFG['wx_login_secret'] . "&code=" . $_REQUEST['code'] . "&grant_type=authorization_code&state=" . $_SESSION['state'];
      $response_token = get_url_contents($get_token_url);
      $msg = json_decode($response_token);
      if (isset($msg->error)) {
      echo "<h3>error:</h3>" . $msg->error;
      echo "<h3>msg  :</h3>" . $msg->error_description;
      exit;
      }
      $_SESSION['accessToken'] = $msg->access_token;
      $get_userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $msg->access_token . "&openid=" . $msg->openid;
      $response_userinfo = get_url_contents($get_userinfo_url);
      $user = json_decode($response_userinfo);
      if (isset($user->errcode) && $user->errcode == 41001) {
      $refresh_token_url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $_CFG['wx_login_appid'] . "&grant_type=refresh_token&refresh_token=" . $msg->access_token;
      $response_token = get_url_contents($refresh_token_url);
      $refresh_msg = json_decode($response_token);
      if (isset($refresh_msg->error)) {
      echo "<h3>error:</h3>" . $refresh_msg->error;
      echo "<h3>msg  :</h3>" . $refresh_msg->error_description;
      exit;
      } else {
      $user = $refresh_msg;
      }
      }
      $unionid_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $msg->access_token . "&openid=" . $user->openid;
      $unionid_result = get_url_contents($unionid_url);
      $unionid_msg = json_decode($unionid_result);
      $_SESSION["unionid_id"] = $unionid_msg->unionid;
      $_SESSION["openid"] = $user->openid;
      if (!empty($_SESSION["unionid_id"])) {
      require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
      $db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
      unset($dbhost, $dbuser, $dbpass, $dbname);
      require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
      $user = get_user_inwxunionid($_SESSION["unionid_id"]);
      if (empty($user)) {
      $user = get_user_inwxopenid($_SESSION["openid"]);
      }
      if (!empty($user)) {
      $db->query("UPDATE " . table('members') . " SET wx_unionid = '" . $_SESSION["unionid_id"] . "',wx_openid='" . $_SESSION["openid"] . "'  WHERE uid='" . $user['uid'] . "' AND `wx_unionid` is null LIMIT 1");
      update_user_info($user['uid']);
      $userurl = get_member_url($_SESSION['utype']);
      header("Location:{$userurl}");
      } else {
      if (!empty($_SESSION['uid']) && !empty($_SESSION['utype']) && !empty($_SESSION['openid']) && !empty($_SESSION["unionid_id"])) {
      require_once(QISHI_ROOT_PATH . 'include/tpl.inc.php');
      $db->query("UPDATE " . table('members') . " SET wx_unionid = '" . $_SESSION["unionid_id"] . "',wx_openid='" . $_SESSION["openid"] . "'  WHERE uid='" . $_SESSION['uid'] . "' AND (`wx_unionid` is null OR wx_openid='') LIMIT 1");
      $link[0]['text'] = "进入会员中心";
      $link[0]['href'] = get_member_url($_SESSION['utype']);
      $_SESSION['uwxid'] = $_SESSION['openid'];
      showmsg('绑定微信帐号成功！', 2, $link);
      } else {
      header("Location:?act=reg");
      }
      }
      }
     */
} elseif ($act == 'reg') {
    /*
      if (empty($_SESSION["unionid_id"]) || empty($_SESSION["openid"])) {
      exit("openid or unionid_id is empty");
      } else {
      $get_userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $_SESSION['accessToken'] . "&openid=" . $_SESSION['openid'];
      $response_userinfo = get_url_contents($get_userinfo_url);
      $user = json_decode($response_userinfo);
      if (isset($user->errcode) && $user->errcode == 41001) {
      $refresh_token_url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $_CFG['wx_login_appid'] . "&grant_type=refresh_token&refresh_token=" . $_SESSION['accessToken'];
      $response_token = get_url_contents($refresh_token_url);
      $refresh_msg = json_decode($response_token);
      if (isset($refresh_msg->error)) {
      echo "<h3>error:</h3>" . $refresh_msg->error;
      echo "<h3>msg  :</h3>" . $refresh_msg->error_description;
      exit;
      } else {
      $user = $refresh_msg;
      }
      }
      $nickname = iconv("utf-8", "gbk", $user->nickname);
      $photo = $user->headimgurl;
      require_once(QISHI_ROOT_PATH . 'include/tpl.inc.php');
      $smarty->assign('title', '补充信息 - ' . $_CFG['site_name']);
      $smarty->assign('wxurl', "?act=");
      $smarty->assign('nickname', $nickname);
      $smarty->assign('photo', $photo);
      $smarty->display('user/connect-qq.htm');
      }
     * 
     */
    $nickname = iconv("utf-8", "gbk", $_SESSION['nickname']);
    require_once(QISHI_ROOT_PATH . 'include/tpl.inc.php');
    $smarty->assign('title', '补充信息 - ' . $_CFG['site_name']);
    $smarty->assign('wxurl', "?act=");
    $smarty->assign('nickname', $nickname);
    $smarty->assign('photo', $_SESSION['headimgurl']);
    $smarty->display('user/connect-qq.htm');
} elseif ($act == 'reg_save') {
    /**
      if (empty($_SESSION["unionid_id"]) || empty($_SESSION["openid"])) {
      exit("openid or unionid_id is empty");
      }
     * 
     */
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
    /*
      if (empty($_SESSION["unionid_id"]) || empty($_SESSION["openid"])) {
      exit("openid or unionid_id is empty");
      }
     * 
     */
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
        $db->query("UPDATE " . table('members') . " SET `wechat_openid` = '" . $_SESSION['wechat_openid'] . "',`mobile` = '" . $_SESSION['mobile'] . "',`mobile_audit` = 1  WHERE `uid`='" . $userid . "' LIMIT 1");
        update_user_info($userid, 1, 1, 7);
        $userurl = $val['member_type'] == 1 ? "/user/company_make_info.php" : "/user/user_make_info.php";
        header("Location:{$userurl}");
    } else {
        require_once(QISHI_ROOT_PATH . 'include/tpl.inc.php');
        $link[0]['text'] = "返回首页";
        $link[0]['href'] = "{$_CFG['main_domain']}";
        showmsg('注册失败！', 0, $link);
    }
} elseif ($act == 'binding') {
    if (empty($_SESSION['uid']) || empty($_SESSION['utype'])) {
        exit("error");
    }
    /**
      $_SESSION['state'] = md5(uniqid(rand(), TRUE));
      $binding_url = "https://open.weixin.qq.com/connect/qrconnect?appid=" . $_CFG['wx_login_appid'] . "&redirect_uri=" . urlencode($binding_callback) . "&response_type=code&scope=snsapi_login&state=" . $_SESSION['state'] . "#wechat_redirect";
      header("Location:" . $binding_url);
     * 
     */
    $url = "https://m.qingkao.net/ov2/get_component_login_code_data?sales_id=86&access_token=gSJH9WUQOX8ra8CuXtjeU7Qgn3R1I";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    curl_close($ch);
    $login_data = json_decode($result);
    $smarty->assign('code_img', $login_data->qrcode);
    $smarty->assign('login_code', $login_data->login_code);
    $smarty->display('user/wechat_binding.htm');
} elseif ($act == 'binding_callback') {
    if (empty($_SESSION['uid']) || empty($_SESSION['utype'])) {
        exit("error");
    }
    $login_code = $_REQUEST['login_code'];
    $get_userinfo_url = "https://m.qingkao.net/ov2/get_component_login_state_data?sales_id=86&access_token=gSJH9WUQOX8ra8CuXtjeU7Qgn3R1I&login_code=" . $login_code;
    $response_userinfo = get_url_contents($get_userinfo_url);
    $user_ = json_decode($response_userinfo);
    $openid = $user_->component_user->openid;
    if (!empty($openid)) {
        require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
        $user = get_user_in_wechat_openid($openid);
        require_once(QISHI_ROOT_PATH . 'include/tpl.inc.php');
        if (!empty($user)) {
            $link[0]['text'] = "用别的微信帐号绑定";
            $link[0]['href'] = "?act=binding";
            $link[1]['text'] = "进入会员中心";
            $link[1]['href'] = get_member_url($_SESSION['utype']);
            showmsg('此微信帐号已经绑定了其他会员, 请换一个微信帐号！', 2, $link);
        } else {
            $db->query("UPDATE " . table('members') . " SET `wechat_openid` = '" . $openid . "' WHERE `uid`='" . $_SESSION['uid'] . "'");
            $link[0]['text'] = "进入会员中心";
            $link[0]['href'] = get_member_url($_SESSION['utype']);
            $_SESSION['uwxid'] = $_SESSION['openid'];
            showmsg('绑定微信帐号成功！', 2, $link);
        }
    }
    /*
      if ($_REQUEST['state'] != $_SESSION['state']) {
      exit("The state does not match. You may be a victim of CSRF.");
      }
      $get_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $_CFG['wx_login_appid'] . "&secret=" . $_CFG['wx_login_secret'] . "&code=" . $_REQUEST['code'] . "&grant_type=authorization_code";
      $response_token = get_url_contents($get_token_url);
      $msg = json_decode($response_token);
      if (isset($msg->error)) {
      echo "<h3>error:</h3>" . $msg->error;
      echo "<h3>msg  :</h3>" . $msg->error_description;
      exit;
      }
      $unionid_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $msg->access_token . "&openid=" . $msg->openid;
      $unionid_result = get_url_contents($unionid_url);
      $unionid_msg = json_decode($unionid_result);
      $_SESSION["unionid_id"] = $unionid_msg->unionid;
      if (empty($_SESSION['unionid_id'])) {
      exit("unionid_id error");
      }
      $_SESSION['accessToken'] = $msg->access_token;
      $get_userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $msg->access_token . "&openid=" . $msg->openid;
      $response_userinfo = get_url_contents($get_userinfo_url);
      $user = json_decode($response_userinfo);
      if (isset($user->errcode) && $user->errcode == 41001) {
      $refresh_token_url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $_CFG['wx_login_appid'] . "&grant_type=refresh_token&refresh_token=" . $msg->access_token;
      $response_token = get_url_contents($refresh_token_url);
      $refresh_msg = json_decode($response_token);
      if (isset($refresh_msg->error)) {
      echo "<h3>error:</h3>" . $refresh_msg->error;
      echo "<h3>msg  :</h3>" . $refresh_msg->error_description;
      exit;
      } else {
      $user = $refresh_msg;
      }
      }
      $_SESSION["openid"] = $user->openid;
      if (empty($_SESSION['openid'])) {
      exit("error");
      }
      require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
      $db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
      unset($dbhost, $dbuser, $dbpass, $dbname);
      require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
      $user = get_user_inwxunionid($_SESSION["unionid_id"]);
      $user = empty($user) ? get_user_inwxopenid($_SESSION["openid"]) : $user;
      require_once(QISHI_ROOT_PATH . 'include/tpl.inc.php');
      if (!empty($user)) {
      $db->query("UPDATE " . table('members') . " SET wx_unionid = ' " . $_SESSION['unionid_id'] . "' WHERE wx_openid=' " . $_SESSION['openid'] . "' AND `wx_unionid` is null LIMIT 1");
      $link[0]['text'] = "用别的微信帐号绑定";
      $link[0]['href'] = "?act=binding";
      $link[1]['text'] = "进入会员中心";
      $link[1]['href'] = get_member_url($_SESSION['utype']);
      showmsg('此微信帐号已经绑定了其他会员, 请换一个微信帐号！', 2, $link);
      } else {
      $db->query("UPDATE " . table('members') . " SET `wx_unionid` = '" . $_SESSION['unionid_id'] . "',`wx_openid` = '" . $_SESSION['openid'] . "' WHERE `uid`='" . $_SESSION['uid'] . "' AND `wx_unionid` =''");
      $link[0]['text'] = "进入会员中心";
      $link[0]['href'] = get_member_url($_SESSION['utype']);
      $_SESSION['uwxid'] = $_SESSION['openid'];
      showmsg('绑定微信帐号成功！', 2, $link);
      }
     * 
     */
} elseif ($act == 'del_wx_binding') {
    $db->query("UPDATE " . table('members') . " SET wx_openid = '',wechat_openid = '',wx_unionid=''  WHERE uid='" . $_SESSION['uid'] . "' LIMIT 1");
    require_once(QISHI_ROOT_PATH . 'include/tpl.inc.php');
    $link[0]['text'] = "进入会员中心";
    $link[0]['href'] = get_member_url($_SESSION['utype']);
    showmsg('解除绑定成功！', 2, $link);
}

function get_url_contents($url) {
    if (ini_get("allow_url_fopen") == "1") {
        return file_get_contents($url);
    } elseif (function_exists(curl_init)) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    } else {
        exit("请把allow_url_fopen设为On或打开CURL扩展");
    }
}

?>