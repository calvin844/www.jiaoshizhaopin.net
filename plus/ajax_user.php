<?php

/*
 * 74cms ajax����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(dirname(__FILE__)) . '/include/plus.common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/aliyun_sms.php');
$mem = new Memcache;
$mem->connect("localhost", 11111);
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : '';
$qclogin = !empty($_REQUEST['qclogin']) ? intval($_REQUEST['qclogin']) : 0;
if ($act == 'ajax_login') {
    $username = isset($_GET['username']) ? trim($_GET['username']) : "";
    $password = isset($_GET['password']) ? trim($_GET['password']) : "";
    $tiku_key = time() . rand(100, 999);
    $account_type = 1;
    if (preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $username)) {
        $account_type = 2;
    } elseif (preg_match("/^(1)\d{10}$/", $username)) {
        $account_type = 3;
    }
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    if (!empty($username) && !empty($password)) {
        $login = user_login($username, $password, "", $account_type);
        $freeze = trim($login['freeze']);
        if (!empty($freeze)) {
            exit(gbk_to_utf8("0-�����˻��ѱ����ᣬԭ���ǣ�" . $freeze . "��������������ϵ�ͷ�QQ��6210210��"));
        }
        if ($login['qs_login']) {
            if ($login['utype'] != 2) {
                setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                setcookie("QS[username]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                setcookie("QS[password]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                unset($_SESSION['uid'], $_SESSION['username'], $_SESSION['utype'], $_SESSION['uqqid'], $_SESSION['activate_username'], $_SESSION['activate_email'], $_SESSION["openid"]);
                exit(gbk_to_utf8("0-��ʹ�ø����˺ŵ�¼��"));
            } else {
                $mem = new Memcache;
                $mem->connect("localhost", 11111);
                $mem->set(md5("tiku_" . $tiku_key), get_user_inid($_SESSION['uid']), 0, 3600);
                exit(gbk_to_utf8("1-" . md5("tiku_" . $tiku_key)));
            }
        } else {
            exit(gbk_to_utf8("0-�˺Ż��������"));
        }
    }
} elseif ($act == 'do_login') {
    $mobile = isset($_POST['mobile']) ? intval($_POST['mobile']) : 0;
    $username = isset($_POST['username']) ? trim($_POST['username']) : "";
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";
    $expire = isset($_POST['expire']) ? intval($_POST['expire']) : "";
    $expire = $expire > 0 ? $expire : "";
    $type = isset($_POST['type']) ? trim($_POST['type']) : "";
    $tiku_key = isset($_POST['tiku_key']) ? intval($_POST['tiku_key']) : 0;
    $account_type = 1;
    if (preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $username)) {
        $account_type = 2;
    } elseif (preg_match("/^(1)\d{10}$/", $username)) {
        $account_type = 3;
    } elseif (preg_match("/^(1)\d{10}$/", $mobile) && empty($password)) {
        $account_type = 3;
    }
    $url = isset($_POST['url']) ? $_POST['url'] : "";
    $act_url = isset($_POST['act_url']) ? $_POST['act_url'] : "";
    if (strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $username = utf8_to_gbk($username);
        $password = utf8_to_gbk($password);
    }
    $captcha = get_cache('captcha');
    if ($captcha['verify_userlogin'] == "1") {
        $postcaptcha = $_POST['postcaptcha'];
        if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
            $postcaptcha = utf8_to_gbk($postcaptcha);
        }
        if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
            unset($_SESSION['imageCaptcha_content']);
            exit("errcaptcha");
        }
    }
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    require_once(QISHI_ROOT_PATH . 'include/fun_company.php');
    if (($username && $password) || (intval($mobile) > 0 && $account_type == 3 && empty($password))) {
        if ($username && $password) {
            $login = user_login($username, $password, $type, $account_type, true, $expire);
        }
        if (intval($mobile) > 0 && $account_type == 3 && empty($password)) {
            $login = user_login_mobile($mobile, $type, true);
        }
        $freeze = trim($login['freeze']);
        if (!empty($freeze)) {
            exit("�����˻��ѱ����ᣬԭ���ǣ�" . $freeze . "��������������ϵ�ͷ�QQ��6210210��");
        }
        $url = $url ? $url : $login['qs_login'];
        if ($login['qs_login']) {
            if ($tiku_key > 0) {
                if ($login['utype'] != 2) {
                    setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                    setcookie("QS[username]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                    setcookie("QS[password]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                    setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
                    unset($_SESSION['uid'], $_SESSION['username'], $_SESSION['utype'], $_SESSION['uqqid'], $_SESSION['activate_username'], $_SESSION['activate_email'], $_SESSION["openid"]);
                    $url = "http://tiku.jiaoshizhaopin.net/";
                    exit('<script type="text/javascript" language="javascript">alert("��ʹ�ø����˺ŵ�¼��");window.location.href="' . $url . '";</script> ');
                } else {
                    $mem = new Memcache;
                    $mem->connect("localhost", 11111);
                    $mem->set(md5("tiku_" . $tiku_key), get_user_inid($_SESSION['uid']), 0, 3600);
                    $qsurl = "http://tiku.jiaoshizhaopin.net/tiku/login?login_key=" . md5("tiku_" . $tiku_key);
                    exit("<script language=\"javascript\" type=\"text/javascript\">window.location.href=\"" . $qsurl . "\";</script>");
                }
            }
            if ($login['utype'] == 2) {
                $resume = get_user_resume($_SESSION['uid']);
                $url = empty($resume['fullname']) ? "/user/user_make_info.php" : $url;
                $url = empty($act_url) ? $url : $act_url;
            }
            if ($login['utype'] == 1) {
                $company_profile = get_company($_SESSION['uid']);
                $url = empty($company_profile['companyname']) ? "/user/company_make_info.php" : $url;
                $url = empty($_SESSION['go_back']) ? $url : $_SESSION['go_back'];
            }
            exit($login['uc_login'] . "<script language=\"javascript\" type=\"text/javascript\">window.location.href=\"" . $url . "\";</script>");
        } else {
            if ($qclogin == 1) {
                exit('<script type="text/javascript" language="javascript">alert("�˺Ż��������");window.location.href="/";</script> ');
            } else {
                exit("err");
            }
        }
    }
    if ($qclogin == 1) {
        exit('<script type="text/javascript" language="javascript">alert("�˺Ż��������");window.location.href="/";</script> ');
    } else {
        exit("err");
    }
} elseif ($act == 'do_reg') {
    $captcha = get_cache('captcha');
    if ($captcha['verify_userreg'] == "1") {
        $postcaptcha = $_POST['postcaptcha'];
        if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
            $postcaptcha = utf8_to_gbk($postcaptcha);
        }
        if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
            exit("err");
        }
    }
    $mobile = empty($_POST['mobile']) ? exit("err") : intval($_POST['mobile']);
    $sql = "UPDATE " . table('yunpian_sms_log') . " SET back = '1' WHERE phone='{$mobile}' and sms_type=1 order by addtime desc LIMIT 1";
    $db->query($sql);
    if ($mem->get($mobile) != intval($_POST['code'])) {
        exit("err");
    }
    $_SESSION['mobile'] = $mobile;
    exit("ok");
} elseif ($act == 'do_reg2') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $username = isset($_POST['username']) ? trim($_POST['username']) : exit("err");
    $password = isset($_POST['password']) ? trim($_POST['password']) : exit("err");
    $member_type = isset($_POST['member_type']) ? intval($_POST['member_type']) : exit("err");
    $email = isset($_POST['email']) ? trim($_POST['email']) : exit("err");
    $email = !strstr($email, '@mailcatch.com') ? $email : exit("err");
    $_SESSION['email'] = $email;
    $mobile = $_SESSION['mobile'];
    $type_label = isset($_POST['type_label']) ? intval($_POST['type_label']) : 0;
    $tiku_key = time() . rand(100, 999);
    if (strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $username = utf8_to_gbk($username);
        $password = utf8_to_gbk($password);
    }
    if (defined('UC_API')) {
        include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
        if (uc_user_checkname($username) < 0) {
            exit("err");
        }
        if (uc_user_checkemail($email) < 0) {
            exit("err");
        }
    }
    $register = user_register($username, $password, $member_type, $email, $mobile);
    if ($register > 0) {
        $login_js = user_login($username, $password);
        $mailconfig = get_cache('mailconfig');
        if ($mailconfig['set_reg'] == "1") {
            dfopen($_CFG['website_dir'] . "plus/asyn_mail.php?uid=" . $_SESSION['uid'] . "&key=" . asyn_userkey($_SESSION['uid']) . "&sendemail=" . $email . "&sendusername=" . $username . "&sendpassword=" . $password . "&act=reg");
        }
        $ucjs = $login_js['uc_login'];
        if ($type_label == 2) {
            $mem = new Memcache;
            $mem->connect("localhost", 11111);
            $mem->set(md5("tiku_" . $tiku_key), get_user_inid($_SESSION['uid']), 0, 3600);
            $qsurl = "http://tiku.jiaoshizhaopin.net/tiku/login?login_key=" . md5("tiku_" . $tiku_key) . "&tiku_index=" . $_POST['tiku_index'];
        } else {
            $qsurl = !empty($_POST['act_url']) ? $_POST['act_url'] : "/user/user_make_info.php";
        }
        //$qsurl = $login_js['qs_login'];
        if ($member_type == 1) {
            $qsurl = "/user/company_make_info.php";
            $qsurl = empty($_SESSION['go_back']) ? $qsurl : $_SESSION['go_back'];
        }
        $qsjs = "<script language=\"javascript\" type=\"text/javascript\">window.location.href=\"" . $qsurl . "\";</script>";
        if ($ucjs || $qsurl) {
            exit($ucjs . $qsjs);
        } else {
            exit("err");
        }
    } else {
        exit("err");
    }
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
} elseif ($act == 'check_usname') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $usname = trim($_POST['usname']);
    if (strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $usname = utf8_to_gbk($usname);
    }
    $user = get_user_inusername($usname);
    if (defined('UC_API')) {
        include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
        if (uc_user_checkname($usname) === 1 && empty($user)) {
            exit("true");
        } else {
            exit("false");
        }
    }
    preg_match("/^[\w]+$/", $usname) && empty($user) ? exit("true") : exit("false");
} elseif ($act == 'check_email') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $email = trim($_POST['email']);
    if (strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $email = utf8_to_gbk($email);
    }
    $user = get_user_inemail($email);
    if (defined('UC_API')) {
        include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
        if (uc_user_checkemail($email) === 1 && empty($user)) {
            exit("true");
        } else {
            exit("false");
        }
    }
    empty($user) ? exit("true") : exit("false");
} elseif ($act == 'check_mobile') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $mobile = trim($_POST['mobile']);
    $user = preg_match("/^(1)\d{10}$/", $mobile) ? get_user_inmobile($mobile) : exit("false");
    empty($user) ? exit("true") : exit("false");
} elseif ($act == "top_loginform") {
    $contents = '';
    if ($_COOKIE['QS']['username'] && $_COOKIE['QS']['password']) {
        $contents = '��ӭ&nbsp;&nbsp;<a href="{#$user_url#}" style="color:#339900">{#$username#}</a> ��¼��&nbsp;&nbsp;{#$pmscount_a#}&nbsp;&nbsp;&nbsp;&nbsp;<a href="{#$user_url#}" style="color:#0066cc">[��Ա����]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{#$logout_url#}" style="color:#0066cc">[�˳�]</a>';
    } elseif ($_SESSION['activate_username'] && defined('UC_API')) {
        $contents = ' &nbsp;&nbsp;�����ʺ� {#$activate_username#} �輤���ſ���ʹ�ã� <a href="{#$activate_url#}" style="color:#339900">��������</a>';
    } else {
        $contents = '��ӭ����{#$site_name#}��&nbsp;&nbsp;&nbsp;&nbsp;<a href="{#$login_url#}" style="color:#0066cc" >[��¼]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{#$reg_url#}" style="color:#0066cc">[���ע��]</a>';
    }
    $contents = str_replace('{#$activate_username#}', $_SESSION['activate_username'], $contents);
    $contents = str_replace('{#$site_name#}', $_CFG['site_name'], $contents);
    $contents = str_replace('{#$username#}', $_COOKIE['QS']['username'], $contents);
    $contents = str_replace('{#$pmscount#}', $_COOKIE['QS']['pmscount'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    if ($_COOKIE['QS']['utype'] == '1') {
        $user_url = $_CFG['main_domain'] . "user/company/company_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/company/company_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '2') {
        $user_url = $_CFG['main_domain'] . "user/personal/personal_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/personal/personal_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '4') {
        $user_url = $_CFG['main_domain'] . "user/train/train_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/train/train_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '3') {
        $user_url = $_CFG['main_domain'] . "user/hunter/hunter_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/hunter/hunter_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    $contents = str_replace('{#$pmscount_a#}', $pmscount_a, $contents);
    $contents = str_replace('{#$user_url#}', $user_url, $contents);
    $contents = str_replace('{#$login_url#}', url_rewrite('QS_login', NULL, false), $contents);
    $contents = str_replace('{#$logout_url#}', url_rewrite('QS_login', NULL, false) . "?act=logout", $contents);
    $contents = str_replace('{#$reg_url#}', $_CFG['main_domain'] . "user/user_reg.php", $contents);
    $contents = str_replace('{#$activate_url#}', $_CFG['main_domain'] . "user/user_reg.php?act=activate", $contents);
    exit($contents);
} elseif ($act == "loginform") {
    $qqtype = $_GET['qqtype'] == 1 ? "connect_qq_client.php" : "connect_qq_server.php";
    $tiku_key = !empty($_GET['tiku_key']) ? $_GET['tiku_key'] : "";
    $tiku_index = !empty($_GET['tiku_index']) ? $_GET['tiku_index'] : "";
    $qq_apiopen = $_GET['qq_apiopen'] == 1 ? '<a href="/user/' . $qqtype . '?tiku_key=' . $tiku_key . '&tiku_index=' . $tiku_index . '" class="third-icon qq f-left"></a>' : "";
    $sina_apiopen = $_GET['sina_apiopen'] == 1 ? '<a href="/user/connect_sina.php?tiku_key=' . $tiku_key . '&tiku_index=' . $tiku_index . '" class="third-icon sina f-left"></a>' : "";
    $taobao_apiopen = $_GET['taobao_apiopen'] == 1 ? '<a href="/user/connect_taobao.php?tiku_key=' . $tiku_key . '&tiku_index=' . $tiku_index . '" class="third-icon taobao f-left"></a>' : "";
    $contents = '';
    if ($_COOKIE['QS']['username'] && $_COOKIE['QS']['password']) {
        $tpl = '../templates/' . $_CFG['template_dir'] . "plus/login_success.htm";
    } elseif ($_SESSION['activate_username'] && defined('UC_API')) {
        $tpl = '../templates/' . $_CFG['template_dir'] . "plus/login_activate.htm";
    } else {
        $tpl = '../templates/' . $_CFG['template_dir'] . "plus/login_form.htm";
    }
    $contents = file_get_contents($tpl);
    $contents = str_replace('{#$activate_username#}', $_SESSION['activate_username'], $contents);
    $contents = str_replace('{#$site_name#}', $_CFG['site_name'], $contents);
    $contents = str_replace('{#$username#}', $_COOKIE['QS']['username'], $contents);
    $contents = str_replace('{#$pmscount#}', $_COOKIE['QS']['pmscount'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    $contents = str_replace('{#$website_dir#}', $_CFG['website_dir'], $contents);
    if ($_COOKIE['QS']['utype'] == '1') {
        $user_url = $_CFG['main_domain'] . "user/company/company_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/company/company_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '2') {
        $user_url = $_CFG['main_domain'] . "user/personal/personal_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/personal/personal_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '4') {
        $user_url = $_CFG['main_domain'] . "user/train/train_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/train/train_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '3') {
        $user_url = $_CFG['main_domain'] . "user/hunter/hunter_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/hunter/hunter_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    $contents = str_replace('{#$pmscount_a#}', $pmscount_a, $contents);
    $contents = str_replace('{#$user_url#}', $user_url, $contents);
    $contents = str_replace('{#$login_url#}', url_rewrite('QS_login', NULL, false), $contents);
    $contents = str_replace('{#$logout_url#}', url_rewrite('QS_login', NULL, false) . "?act=logout", $contents);
    $contents = str_replace('{#$reg_url#}', $_CFG['main_domain'] . "user/user_reg.php", $contents);
    $contents = str_replace('{#$activate_url#}', $_CFG['main_domain'] . "user/user_reg.php?act=activate", $contents);
    $contents = str_replace('{#$qq_apiopen#}', $qq_apiopen, $contents);
    $contents = str_replace('{#$sina_apiopen#}', $sina_apiopen, $contents);
    $contents = str_replace('{#$taobao_apiopen#}', $taobao_apiopen, $contents);
    exit($contents);
}
// �һ�������֤ �û������� �ֻ�
elseif ($act == "get_pass_check") {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $username = $_POST['username'] ? iconv("utf-8", "gbk", trim($_POST['username'])) : exit("false");
    if (preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $username)) {
        $usinfo = get_user_inemail($username);
    } elseif (preg_match("/^(13|14|15|18|17)\d{9}$/", $username)) {
        $usinfo = get_user_inmobile($username);
    } else {
        $usinfo = get_user_inusername($username);
    }
    !empty($usinfo) ? exit("true") : exit("false");
}
// �һ����� �ж��Ƿ� ���ֻ� ���� ΢��
elseif ($act == "get_pass_check_buding") {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $username = $_POST['username'] ? iconv("utf-8", "gbk", trim($_POST['username'])) : exit("");
    if (preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $username)) {
        $usinfo = get_user_inemail($username);
    } elseif (preg_match("/^(13|14|15|18|17)\d{9}$/", $username)) {
        $usinfo = get_user_inmobile($username);
    } else {
        $usinfo = get_user_inusername($username);
    }
    if (($usinfo['mobile'] && $usinfo['mobile_audit'] == 1) || ($usinfo['email'] && $usinfo['email_audit'] == 1)) {
        exit("true");
    } elseif ($usinfo['weixin_openid']) {
        exit('buding_wx');
    } else {
        exit('false');
    }
}
// �һ����뷢���ʼ�
elseif ($act == "getpass_sendemail") {
    global $QS_pwdhash;
    $email = $_POST['email'] ? trim($_POST['email']) : exit("�������");
    $username = $_POST['username'] ? iconv("utf-8", "gbk", trim($_POST['username'])) : exit("û���û���");
    $uid = $_POST['uid'] ? intval($_POST['uid']) : exit("û���û���");
    //��֤����������Ϣ
    $members_info = $db->getone("SELECT * FROM " . table('members') . " WHERE uid=" . $uid . " AND username='" . $username . "' AND email='" . $email . "'");
    if (!preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $email) || !$members_info) {
        exit("�������");
    }
    $time = time();
    $key = substr(md5($username . $QS_pwdhash), 8, 16);
    $email_str.=$username . "���ã�<br>";
    $email_str.="����24Сʱ�ڵ�������������������������룺<br>";
    $email_str.="<a href='" . $_CFG['site_domain'] . $_CFG['site_dir'] . "user/user_getpass.php?act=get_pass_step3_email&uid=$uid&key=$key&time=$time' target='_blank'>" . $_CFG['site_domain'] . $_CFG['site_dir'] . "user/user_getpass.php?act=get_pass_step3_email&uid=$uid&key=$key&time=$time</a><br>";
    $email_str.="��������޷����,�븴��ճ������������ʣ�<br>";
    $email_str.="���ʼ���ϵͳ����,����ظ���<br>";
    $email_str.="�����κ���������ϵ��վ�ٷ���" . $_CFG['top_tel'] . "";

    if (smtp_mail($email, "{$_CFG['site_name']}-�һ�����", $email_str)) {
        exit("success");
    } else {
        exit("�������ó�������ϵ��վ����Ա");
    }
}
// ���Ͷ�����֤��
elseif ($act == 'send_code') {
    $postcaptcha = $_GET['postcaptcha'];
    if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $postcaptcha = utf8_to_gbk($postcaptcha);
    }
    if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        exit("0-ͼƬ��֤�����");
    }
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $mobile = intval($_GET['mobile']);
    $mobile_user = preg_match("/^(1)\d{10}$/", $mobile) ? get_user_inmobile($mobile) : exit("0-�ֻ��Ÿ�ʽ����");
    !empty($mobile_user) ? exit("1-���ֻ����ѱ�ע��") : "";
    if ($mobile > 0) {
        $result = $mem->get($mobile);
        $result = 0;
        if (!$result) {
            $result = rand(1000, 9999);
            $mem->set($mobile, $result, 0, 60);
        }
        /*
        if ($mobile == "13632243614") {
            var_dump($result);
            exit;
        }
         * 
         */
        $response = SmsDemo::sendSms($mobile, $result, "SMS_174655641");
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
?>