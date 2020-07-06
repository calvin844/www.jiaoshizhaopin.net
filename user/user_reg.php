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
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
unset($dbhost, $dbuser, $dbpass, $dbname);
$smarty->cache = false;
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'reg_index';
if (!$_SESSION['uid'] && !$_SESSION['username'] && !$_SESSION['utype'] && $_COOKIE['QS']['username'] && $_COOKIE['QS']['password']) {
    if (check_cookie($_COOKIE['QS']['uid'], $_COOKIE['QS']['username'], $_COOKIE['QS']['password'])) {
        update_user_info($_COOKIE['QS']['uid'], false, false);
        header("Location:" . get_member_url($_SESSION['utype']));
    } else {
        setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[username]', "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[password]', "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        header("Location:" . url_rewrite('QS_login'));
    }
}
//激活账户
elseif ($act == 'activate') {
    if (defined('UC_API')) {
        include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
        if ($data = uc_get_user($_SESSION['activate_username'])) {
            unset($_SESSION['uid']);
            unset($_SESSION['username']);
            unset($_SESSION['utype']);
            unset($_SESSION['uqqid']);
            setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
            setcookie("QS[username]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
            setcookie("QS[password]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
            setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
            $smarty->assign('activate_email', $data[2]);
            $smarty->assign('activate_username', $_SESSION['activate_username']);
        } else {
            showmsg('激活失败，用户名错误！', 0);
        }
        $smarty->display('user/activate.htm');
    }
} elseif ($act == 'activate_save') {
    $activateinfo = activate_user($_SESSION['activate_username'], $_POST['pwd'], $_POST['act_email'], $_POST['member_type']);
    if ($activateinfo > 0) {
        $login_url = user_login($_SESSION['activate_username'], $_POST['pwd'], 1, false);
        $link[0]['text'] = "进入会员中心";
        $link[0]['href'] = $login_url['qs_login'];
        $link[1]['text'] = "网站首页";
        $link[1]['href'] = $_CFG['site_dir'];
        $_SESSION['activate_username'] = "";
        showmsg('激活成功，即将进入会员中心！', 2, $link);
        exit();
    } else {
        if ($activateinfo == -10) {
            $html = "密码输入错误";
        } elseif ($activateinfo == -1) {
            $html = "激活会员类型丢失";
        } elseif ($activateinfo == -2) {
            $html = "用户名有重复";
        } elseif ($activateinfo == -3) {
            $html = "电子邮箱有重复";
        } else {
            $html = "原因未知";
        }
        unset($_SESSION['uid']);
        unset($_SESSION['username']);
        unset($_SESSION['utype']);
        unset($_SESSION['uqqid']);
        setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie("QS[username]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie("QS[password]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        unset($_SESSION['activate_username']);
        unset($_SESSION['activate_email']);
        unset($_SESSION["openid"]);
        $link[0]['text'] = "重新登陆";
        $link[0]['href'] = url_rewrite('QS_login');
        showmsg("激活失败，原因：{$html}", 0, $link);
        exit();
    }
} elseif ($_SESSION['username'] && $_SESSION['utype'] && $_COOKIE['QS']['username'] && $_COOKIE['QS']['password']) {
    if (!empty($_GET['tiku_key']) && !empty($_GET['tiku_index'])) {
        $tiku_key = $_GET['tiku_key'];
        $mem = new Memcache;
        $mem->connect("localhost", 11111);
        $sql = "select * from " . table('members') . " where uid = '" . $_SESSION['uid'] . "' LIMIT 1";
        $login = $db->getone($sql);
        $mem->set(md5("tiku_" . $tiku_key), $login, 0, 3600);
        $url = "http://tiku.jiaoshizhaopin.net/tiku/login?login_key=" . md5("tiku_" . $tiku_key) . "&&tiku_index=" . $_GET['tiku_index'];
        echo '<script type="text/javascript" language="javascript">window.location.href="' . $url . '";</script> ';
    } else {
        header("Location:" . get_member_url($_SESSION['utype']));
    }
} elseif ($act == 'reg_index') {
    if ($_CFG['closereg'] == '1')
        showmsg("网站暂停会员注册，请稍后再次尝试！", 1);
    if (intval($_GET['type']) == 3 && $_PLUG['hunter']['p_install'] == 1) {
        showmsg("管理员已关闭猎头模块,禁止注册！", 1);
    }
    if (intval($_GET['type']) == 4 && $_PLUG['train']['p_install'] == 1) {
        showmsg("管理员已关闭培训模块,禁止注册！", 1);
    }
    $smarty->assign('title', '会员注册 - ' . $_CFG['site_name']);
    $smarty->display('user/reg_index.htm');
} elseif ($act == 'reg') {
    if ($_CFG['closereg'] == '1')
        showmsg("网站暂停会员注册，请稍后再次尝试！", 1);
    if (intval($_GET['type']) == 3 && $_PLUG['hunter']['p_install'] == 1) {
        showmsg("管理员已关闭猎头模块,禁止注册！", 1);
    }
    if (intval($_GET['type']) == 4 && $_PLUG['train']['p_install'] == 1) {
        showmsg("管理员已关闭培训模块,禁止注册！", 1);
    }
    $smarty->assign('title', '会员注册 - ' . $_CFG['site_name']);
    $captcha = get_cache('captcha');
    $smarty->assign('verify_userreg', $captcha['verify_userreg']);
    $smarty->display('user/reg.htm');
} elseif ($act == 'reg2') {
    if ($_CFG['closereg'] == '1')
        showmsg("网站暂停会员注册，请稍后再次尝试！", 1);
    if (intval($_GET['type']) == 3 && $_PLUG['hunter']['p_install'] == 1) {
        showmsg("管理员已关闭猎头模块,禁止注册！", 1);
    }
    if (intval($_GET['type']) == 4 && $_PLUG['train']['p_install'] == 1) {
        showmsg("管理员已关闭培训模块,禁止注册！", 1);
    }
    if (empty($_SESSION['mobile'])) {
        header("Location:/user/user_reg.php");
    }
    $smarty->assign('title', '会员注册 - ' . $_CFG['site_name']);
    $smarty->display('user/reg2.htm');
}
unset($smarty);
?>