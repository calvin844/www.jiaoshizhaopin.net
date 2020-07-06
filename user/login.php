<?php

/*
 * 74cms ��Ա��¼
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'login';
if ($act == 'logout') {
    setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
    setcookie("QS[username]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
    setcookie("QS[password]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
    setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
    unset($_SESSION['uid'], $_SESSION['username'], $_SESSION['utype'], $_SESSION['uqqid'], $_SESSION['activate_username'], $_SESSION['activate_email'], $_SESSION["openid"]);
    if (defined('UC_API')) {
        include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
        $logoutjs = uc_user_synlogout();
    }
    if ($_GET['tiku_logout'] > 0) {
        $u = "http://tiku.jiaoshizhaopin.net/tiku";
    } else {
        $u = url_rewrite('QS_login');
    }
    $logoutjs.="<script language=\"javascript\" type=\"text/javascript\">window.location.href=\"" . $u . "\";</script>";
    exit($logoutjs);
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
} elseif ($act == 'check_mobile') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $mobile = trim($_POST['mobile']);
    $user = preg_match("/^(1)\d{10}$/", $mobile) ? get_user_inmobile($mobile) : exit("false");
    !empty($user) ? exit("true") : exit("false");
} elseif ($act == 'check_online') {
    if ($_SESSION['username'] && $_SESSION['uid']) {
        if ($_SESSION['utype'] == 2) {
            $sql = "select * from " . table("resume") . " where uid = " . $_SESSION['uid'] . " and audit = 1 limit 1";
            $resume_res = $db->getone($sql);
            if (!empty($resume_res['id'])) {
                exit("y");
            } else {
                exit("person_login");
            }
        } elseif ($_SESSION['utype'] == 1) {
            $sql = "select * from " . table("company_profile") . " where uid = " . $_SESSION['uid'] . " limit 1";
            $company_res = $db->getone($sql);
            exit($company_res['companyname'] . "-" . $company_res['audit']);
        }
    } elseif ($_GET['type'] == "ajax_login") {
        $captcha = get_cache('captcha');
        $smarty->assign('verify_userlogin', $captcha['verify_userlogin']);
        $smarty->display('plus/ajax_login.htm');
        exit();
    } else {
        exit("no_login");
    }
} elseif ((empty($_SESSION['uid']) || empty($_SESSION['username']) || empty($_SESSION['utype'])) && $_COOKIE['QS']['username'] && $_COOKIE['QS']['password'] && $_COOKIE['QS']['uid']) {
    if (check_cookie($_COOKIE['QS']['uid'], $_COOKIE['QS']['username'], $_COOKIE['QS']['password'])) {
        if (!empty($_GET['tiku_key']) && !empty($_GET['tiku_index'])) {
            update_user_info($_COOKIE['QS']['uid'], true, false);
            if ($_SESSION['utype'] != 2) {
                $url = "http://tiku.jiaoshizhaopin.net/tiku";
                echo '<script type="text/javascript" language="javascript">alert("��ʹ�ø����˺ŵ�¼��");window.location.href="' . $url . '";</script> ';
            } else {
                $tiku_key = $_GET['tiku_key'];
                $mem = new Memcache;
                $mem->connect("localhost", 11111);
                $sql = "select * from " . table('members') . " where uid = '" . $_COOKIE['QS']['uid'] . "' LIMIT 1";
                $login = $db->getone($sql);
                $mem->set(md5("tiku_" . $tiku_key), $login, 0, 3600);
                $url = "http://tiku.jiaoshizhaopin.net/tiku/login?login_key=" . md5("tiku_" . $tiku_key) . "&&tiku_index=" . $_GET['tiku_index'];
                echo '<script type="text/javascript" language="javascript">window.location.href="' . $url . '";</script> ';
            }
        } else {
            update_user_info($_COOKIE['QS']['uid'], true, false);
            header("Location:" . get_member_url($_SESSION['utype']));
        }
    } else {
        unset($_SESSION['uid'], $_SESSION['username'], $_SESSION['utype'], $_SESSION['uqqid'], $_SESSION['activate_username'], $_SESSION['activate_email'], $_SESSION["openid"]);
        setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[username]', "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[password]', "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        header("Location:" . url_rewrite('QS_login'));
    }
} elseif ($_SESSION['username'] && $_SESSION['utype'] && $_SESSION['uid'] && $_COOKIE['QS']['username'] && $_COOKIE['QS']['password']) {
    if (!empty($_GET['tiku_key']) && !empty($_GET['tiku_index'])) {
        update_user_info($_SESSION['uid'], true, false);
        if ($_SESSION['utype'] != 2) {
            $url = "http://tiku.jiaoshizhaopin.net/tiku";
            echo '<script type="text/javascript" language="javascript">alert("��ʹ�ø����˺ŵ�¼��");window.location.href="' . $url . '";</script> ';
        } else {
            $tiku_key = $_GET['tiku_key'];
            $mem = new Memcache;
            $mem->connect("localhost", 11111);
            $sql = "select * from " . table('members') . " where uid = '" . $_SESSION['uid'] . "' LIMIT 1";
            $login = $db->getone($sql);
            $mem->set(md5("tiku_" . $tiku_key), $login, 0, 3600);
            $url = "http://tiku.jiaoshizhaopin.net/tiku/login?login_key=" . md5("tiku_" . $tiku_key) . "&&tiku_index=" . $_GET['tiku_index'];
            echo '<script type="text/javascript" language="javascript">window.location.href="' . $url . '";</script> ';
        }
    } else {
        update_user_info($_SESSION['uid'], true, false);
        header("Location:" . get_member_url($_SESSION['utype']));
    }
} elseif ($act == 'wechat_login') {
    $smarty->assign('title', '��Ա��¼ - ' . $_CFG['site_name']);
    $url = "https://m.qingkao.net/ov2/get_component_login_code_data?sales_id=86&access_token=gSJH9WUQOX8ra8CuXtjeU7Qgn3R1I";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    curl_close($ch);
    $login_data = json_decode($result);
    $smarty->assign('code_img', $login_data->qrcode);
    $smarty->assign('login_code', $login_data->login_code);
    $smarty->display('user/wechat_login.htm');
} elseif ($act == 'login_mobile') {
    $smarty->assign('title', '��Ա��¼ - ' . $_CFG['site_name']);
    $smarty->assign('error', $_GET['error']);
    $smarty->assign('url', $_GET['url']);
    $smarty->assign('header_nav', 'login');
    $captcha = get_cache('captcha');
    $smarty->assign('verify_userlogin', $captcha['verify_userlogin']);
    $smarty->display('user/login_mobile.htm');
} elseif ($act == 'login') {
    $smarty->assign('title', '��Ա��¼ - ' . $_CFG['site_name']);
    $smarty->assign('error', $_GET['error']);
    $smarty->assign('url', $_GET['url']);
    $smarty->assign('header_nav', 'login');
    $captcha = get_cache('captcha');
    $smarty->assign('verify_userlogin', $captcha['verify_userlogin']);
    $smarty->display($mypage['tpl']);
} elseif ($act == 'login_index') {
    $smarty->assign('title', '��Ա��¼ - ' . $_CFG['site_name']);
    $smarty->assign('error', $_GET['error']);
    $smarty->assign('url', $_GET['url']);
    $smarty->assign('header_nav', 'login');
    $captcha = get_cache('captcha');
    $smarty->assign('verify_userlogin', $captcha['verify_userlogin']);
    $smarty->display('user/login_index.htm');
}// ���Ͷ�����֤��
elseif ($act == 'send_code') {
    $postcaptcha = $_GET['postcaptcha'];
    $login = isset($_GET['login']) ? intval($_GET['login']) : 0;
    if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $postcaptcha = utf8_to_gbk($postcaptcha);
    }
    if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        exit("0-ͼƬ��֤�����");
    }
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $mobile = intval($_GET['mobile']);
    preg_match("/^(1)\d{10}$/", $mobile) ? "" : exit("0-�ֻ��Ÿ�ʽ����");
    if ($login == 1) {
        $sql = "select * from " . table('members') . " where mobile = " . $mobile;
        $sms_to_user = $db->getone($sql);
        !empty($sms_to_user) ? "" : exit("0-û���ҵ����û�");
    }
    if ($mobile > 0) {
        $result = $mem->get($mobile);
        $result = 0;
        if (!$result) {
            $result = rand(1000, 9999);
            $mem->set($mobile, $result, 0, 60);
        }
        $response = SmsDemo::sendSms($mobile, $result,"SMS_174655637");
        /*
          $text = iconv("GB2312", "UTF-8//IGNORE", "����ʦ����������֤����" . $result);
          //$text = "����ʦ����������֤����" . $result;
          $ch = curl_init();
          $apikey = "732be982abc9313b64561224601f70a3";
          $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
          curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $json_data = curl_exec($ch);
          $json_data1 = json_decode($json_data, TRUE);
          if ($json_data1['code'] == 22) {
          exit("0-����֤̫Ƶ���ˣ���1Сʱ������");
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