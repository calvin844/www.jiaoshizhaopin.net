<?php

/*
 * 74cms ���ؼ���
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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