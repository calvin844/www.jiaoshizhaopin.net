<?php

/*
 * 74cms �ƻ����� ÿ������ͳ��
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once('../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
//$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
global $_CFG;
$sql = "select * from " . table("mailconfig");
$mailconfig_tmp = $db->getall($sql);
//var_dump($mailconfig_tmp);exit;
foreach ($mailconfig_tmp as $m) {
    $mailconfig[$m['name']] = $m['value'];
}
$mailconfig['smtpservers'] = explode('|-_-|', $mailconfig['smtpservers']);
$mailconfig['smtpusername'] = explode('|-_-|', $mailconfig['smtpusername']);
$mailconfig['smtppassword'] = explode('|-_-|', $mailconfig['smtppassword']);
$mailconfig['smtpfrom'] = explode('|-_-|', $mailconfig['smtpfrom']);
$mailconfig['smtpport'] = explode('|-_-|', $mailconfig['smtpport']);
$mailconfig['smtpnum'] = explode('|-_-|', $mailconfig['smtpnum']);
$mailconfig['smtpupdatetime'] = explode('|-_-|', $mailconfig['smtpupdatetime']);
for ($i = 0; $i < count($mailconfig['smtpservers']); $i++) {
    $mailconfigarray[] = array('smtpservers' => $mailconfig['smtpservers'][$i], 'smtpusername' => $mailconfig['smtpusername'][$i], 'smtppassword' => $mailconfig['smtppassword'][$i], 'smtpfrom' => $mailconfig['smtpfrom'][$i], 'smtpport' => $mailconfig['smtpport'][$i], 'smtpnum' => $mailconfig['smtpnum'][$i], 'smtpupdatetime' => $mailconfig['smtpupdatetime'][$i]);
}
foreach ($mailconfigarray as $m) {
    if (($m['smtpnum'] - 1) > 0 && empty($mc)) {
        $mc = $m;
    }
}

$today = strtotime(date('Y-m-d'));
$list = $db->getone("select count(*) as num from " . table('members_log') . " where log_addtime > " . $today . " and log_value like '%ˢ��ְλ%'");
$send_flag = @smtp_mail('969078455@qq.com', $_CFG['site_name'] . $list['num'] . '|' . date('Y-m-d') . "��ʦ��Ƹ��ˢ��ְλͳ��", $list['num'], $mc['smtpfrom'], $_CFG['site_name']);
?>