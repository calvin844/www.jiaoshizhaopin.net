<?php

/*
 * 74cms QQ���� client-sideģʽ
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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
        showmsg('��¼ʧ�ܣ�openid��ȡ����', 0);
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
                    exit('<script type="text/javascript" language="javascript">alert("��ʹ�ø����˺ŵ�¼��");window.location.href="' . $url . '";</script> ');
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
                        exit('<script type="text/javascript" language="javascript">alert("��ʹ�ø����˺ŵ�¼��");window.location.href="' . $url . '";</script> ');
                    } else {
                        $mem = new Memcache;
                        $mem->connect("localhost", 11111);
                        $mem->set(md5("tiku_" . $tiku_key), get_user_inid($_SESSION['uid']), 0, 3600);
                        $qsurl = "http://tiku.jiaoshizhaopin.net/tiku/login?login_key=" . md5("tiku_" . $tiku_key) . "&&tiku_index=" . $_GET['tiku_index'];
                        exit('<script type="text/javascript" language="javascript">alert("��QQ�ʺųɹ���");window.location.href="' . $qsurl . '";</script> ');
                    }
                } else {
                    $link[0]['text'] = "�����Ա����";
                    $link[0]['href'] = get_member_url($_SESSION['utype']);
                    showmsg('��QQ�ʺųɹ���', 2, $link);
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
        $smarty->assign('title', '������Ϣ - ' . $_CFG['site_name']);
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
        showmsg('ͼƬ��֤�����', 0, $link);
    }
    $mobile = empty($_POST['mobile']) ? exit("err") : intval($_POST['mobile']);
    $sql = "UPDATE " . table('yunpian_sms_log') . " SET back = '1' WHERE phone='{$mobile}' and sms_type=1 order by addtime desc LIMIT 1";
    $db->query($sql);
    if ($mem->get($mobile) != intval($_POST['code'])) {
        showmsg('������֤�����', 0, $link);
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
        $link[0]['text'] = "������ҳ";
        $link[0]['href'] = "{$_CFG['main_domain']}";
        showmsg('ע��ʧ�ܣ�', 0, $link);
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
        $link[0]['text'] = "�ñ��QQ�ʺŰ�";
        $link[0]['href'] = "?act=binding";
        $link[1]['text'] = "�����Ա����";
        $link[1]['href'] = get_member_url($_SESSION['utype']);
        showmsg('��QQ�ʺ��Ѿ�����������Ա,�뻻һ��QQ�ʺţ�', 2, $link);
    } else {
        $db->query("UPDATE " . table('members') . " SET qq_openid = '{$_SESSION['openid']}'  WHERE uid='{$_SESSION[uid]}' AND qq_openid='' LIMIT 1");
        $link[0]['text'] = "�����Ա����";
        $link[0]['href'] = get_member_url($_SESSION['utype']);
        $_SESSION['uqqid'] = $_SESSION['openid'];
        showmsg('��QQ�ʺųɹ���', 2, $link);
    }
} elseif ($act == 'del_qq_binding') {
    $db->query("UPDATE " . table('members') . " SET qq_openid = ''  WHERE uid='" . $_SESSION['uid'] . "' LIMIT 1");
    showmsg('����󶨳ɹ���', 2);
}
?>