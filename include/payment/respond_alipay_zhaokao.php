<?php

/*
 * 74cms ֧����Ӧҳ��
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
require_once(QISHI_ROOT_PATH . 'include/fun_zhaokao.php');
require_once(QISHI_ROOT_PATH . "include/payment/alipay.php");
if (respond()) {
    $link[0]['text'] = "�鿴�γ�";
    $link[0]['href'] = "/user/personal/personal_user.php?act=online_course";
    showmsg("����ɹ���", 1, $link);
} else {
    $link[0]['text'] = "������ҳ";
    $link[0]['href'] = "/zhaokao";
    showmsg("����ʧ�ܣ�����ϵ��վ����Ա", 0, $link);
}
?>
