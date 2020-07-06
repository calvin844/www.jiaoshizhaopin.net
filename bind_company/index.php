<?php

/*
 * 74cms 网站首页
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
set_time_limit(0);
define('IN_QISHI', true);
require_once('../include/common.inc.tmp.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$_SESSION['bind_company_id'] = $_GET['company_id'];
$_SESSION['go_back'] = "/bind_company/do.php";
setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
setcookie("QS[username]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
setcookie("QS[password]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
unset($_SESSION['uid'], $_SESSION['username'], $_SESSION['utype'], $_SESSION['uqqid'], $_SESSION['activate_username'], $_SESSION['activate_email'], $_SESSION["openid"]);
header("Location:/user/login.php");
?>