<?php

/*
 * 74cms 会员注册
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
$alias = "QS_login";
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
require_once(QISHI_ROOT_PATH . 'include/aliyun_sms.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
unset($dbhost, $dbuser, $dbpass, $dbname);
$smarty->cache = false;
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'enter';
$smarty->assign('header_nav', "getpass");
if ($act == 'enter') {
    $smarty->assign('title', '找回密码 - ' . $_CFG['site_name']);
    $token = substr(md5(mt_rand(100000, 999999)), 8, 16);
    $_SESSION['getpass_token'] = $token;
    $smarty->assign('step', 1);
    $smarty->assign('token', $token);
    $smarty->display('user/getpass_mobile.htm');
}
//找回密码第2步
elseif ($act == 'get_pass_step2') {
    if (empty($_POST['token']) || $_POST['token'] != $_SESSION['getpass_token']) {
        $link[0]['text'] = "重新找回密码";
        $link[0]['href'] = "?act=enter";
        showmsg("找回密码失败，非正常链接", 0, $link);
    }
    $captcha = get_cache('captcha');
    $postcaptcha = $_POST['postcaptcha'];
    if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $postcaptcha = utf8_to_gbk($postcaptcha);
    }
    if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        showmsg('图片验证码错误！', 1);
    }
    $mobile = empty($_POST['mobile']) ? showmsg('请填写手机号！', 1) : intval($_POST['mobile']);
    $sql = "UPDATE " . table('yunpian_sms_log') . " SET back = '1' WHERE phone='{$mobile}' and sms_type=1 order by addtime desc LIMIT 1";
    $db->query($sql);
    if ($mem->get($mobile) != intval($_POST['code'])) {
        showmsg('短信验证码错误！', 1);
    }
    $_SESSION['get_pass_mobile'] = $mobile;
    $userinfo = get_user_inmobile($mobile);
    if (empty($userinfo)) {
        showmsg("没有找到相关用户");
    }
    $token = substr(md5(mt_rand(100000, 999999)), 8, 16);
    $_SESSION['getpass_token'] = $token;
    $smarty->assign('token', $token);
    $smarty->assign('step', 2);
    $smarty->assign('userinfo', $userinfo);
    $smarty->assign('title', '找回密码 - 设置新密码-' . $_CFG['site_name']);
    $smarty->display('user/getpass_mobile2.htm');
}
// 保存 密码
elseif ($act == "get_pass_save") {
    global $QS_pwdhash;
    if (empty($_POST['token']) || $_POST['token'] != $_SESSION['getpass_token']) {
        $link[0]['text'] = "重新找回密码";
        $link[0]['href'] = "?act=enter";
        showmsg("找回密码失败，非正常链接", 0, $link);
    }
    $mobile = empty($_SESSION['get_pass_mobile']) ? showmsg('请填写手机号！', 1) : intval($_SESSION['get_pass_mobile']);
    $userinfo = preg_match("/^(1)\d{10}$/", $mobile) ? get_user_inmobile($mobile) : showmsg("手机号格式错误", 1);
    $password = !empty($_POST['password']) ? trim($_POST['password']) : showmsg("请输入密码！", 1);
    if (empty($userinfo)) {
        $link[0]['text'] = "重新找回密码";
        $link[0]['href'] = "?act=enter";
        showmsg("没有找到相关用户", 0, $link);
    }
    $password_hash = md5(md5($password) . $userinfo['pwd_hash'] . $QS_pwdhash);
    $setsqlarr['password'] = $password_hash;
    $rst = updatetable(table('members'), $setsqlarr, " uid = " . $userinfo['uid']);
    if ($rst) {
        $link[0]['text'] = "马上登录";
        $link[0]['href'] = "/user/login.php";
        showmsg("设置新密码成功！", 0, $link);
    } else {
        showmsg("设置新密码失败！", 1);
    }
} elseif ($act == 'check_mobile') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $mobile = trim($_POST['mobile']);
    $user = preg_match("/^(1)\d{10}$/", $mobile) ? get_user_inmobile($mobile) : exit("false");
    !empty($user) ? exit("true") : exit("false");
} elseif ($act == 'check_code') {
    $mobile = empty($_POST['mobile']) ? exit("false") : intval($_POST['mobile']);
    $sql = "UPDATE " . table('yunpian_sms_log') . " SET back = '1' WHERE phone='{$mobile}' and sms_type=1 order by addtime desc LIMIT 1";
    $db->query($sql);
    if ($mem->get($mobile) != intval($_POST['code'])) {
        exit("false");
    }
    exit("true");
} elseif ($act == 'check_postcaptcha') {
    $postcaptcha = $_POST['postcaptcha'];
    if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $postcaptcha = utf8_to_gbk($postcaptcha);
    }
    if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        exit("false");
    }
    exit("true");
}// 发送短信验证码
elseif ($act == 'send_code') {
    $postcaptcha = $_GET['postcaptcha'];
    if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $postcaptcha = utf8_to_gbk($postcaptcha);
    }
    if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        exit("0-图片验证码错误");
    }
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $mobile = intval($_GET['mobile']);
    $mobile_user = preg_match("/^(1)\d{10}$/", $mobile) ? get_user_inmobile($mobile) : exit("0-手机号格式错误");
    empty($mobile_user) ? exit("1-没有找到相关用户") : "";
    if ($mobile > 0) {
        $result = $mem->get($mobile);
        $result = 0;
        if (!$result) {
            $result = rand(1000, 9999);
            $mem->set($mobile, $result, 0, 60);
        }
        $response = SmsDemo::sendSms($mobile, $result,"SMS_174655634");
        /*
          $text = iconv("GB2312", "UTF-8//IGNORE", "【教师网】您的验证码是" . $result);
          //$text = "【教师网】您的验证码是" . $result;
          $ch = curl_init();
          $apikey = "732be982abc9313b64561224601f70a3";
          $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
          curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $json_data = curl_exec($ch);
          $json_data1 = json_decode($json_data, TRUE);
          if ($json_data1['code'] == 22) {
          exit("0-您验证太频繁了，请1小时后再试");
          }
          $sql = "select * from " . table('members') . " where mobile = " . $mobile;
          $sms_to_user = $db->getone($sql);
          $in['phone'] = $mobile;
          $in['utype'] = !empty($sms_to_user) ? $sms_to_user['utype'] : 0;
          $in['sms_type'] = 1;
          $in['back'] = 0;
          $in['addtime'] = time();
          $db->insert('yunpian_sms_log', $in);
         * 
         */
    }
}
unset($smarty);
?>