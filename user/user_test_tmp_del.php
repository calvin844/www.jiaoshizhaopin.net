<?php

/*
 * 74cms 下载简历
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$act = empty($_GET['act']) ? "form" : $_GET['act'];
if (!empty($_POST)) {
    $in['uid'] = $_SESSION['uid'] > 0 ? $_SESSION['uid'] : 0;
    $in['q1'] = intval($_POST['q1']);
    $in['q2'] = intval($_POST['q2']);
    $in['q3'] = intval($_POST['q3']);
    $in['addtime'] = time();
    $db->insert('test_tmp', $in);
    setcookie("QS[test_tmp]", "1", time() + 2 * 7 * 24 * 3600, $QS_cookiepath, $QS_cookiedomain);
    header("Location: /");
} else {
    setcookie("QS[test_tmp]", "1", time() + 24 * 3600, $QS_cookiepath, $QS_cookiedomain);
    return $_COOKIE['QS']['test_tmp'];
}
?>