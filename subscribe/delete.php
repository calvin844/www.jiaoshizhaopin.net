<?php

/*
 * 74cms ְλ����
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
require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
unset($dbhost, $dbuser, $dbpass, $dbname);
$email = trim($_GET['email']) ? strtolower($_GET['email']) : showmsg("����д�������䣡", 1);
$ck_email = get_user_email($email);
if ($ck_email) {
    $where = " email='" . $email . "' ";
    deletetable(table("jobs_subscribe"), $where, 1);
    $href[] = array('text'=>"������վ",'href'=>$_CFG["site_domain"] . $_CFG["site_dir"]);
    showmsg("�˶��ɹ���", 1,$href);
    //echo "<script type='text/javascript' language='javascript'> window.location.href='". $_CFG["site_domain"] . $_CFG["site_dir"] ."'</script>";
}else{
    showmsg("û�ҵ���ض��ļ�¼��", 1);
}

function get_user_email($email) {
    global $db;
    $ck_email = $db->getone("select 1 from " . table('jobs_subscribe') . " where email='" . $email . "'");
    return $ck_email;
}

?>