<?php

/*
 * 74cms ��վ��ҳ
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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