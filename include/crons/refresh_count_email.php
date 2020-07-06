<?php

/*
 * 74cms 计划任务 每日数据统计
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
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
$list = $db->getone("select count(*) as num from " . table('members_log') . " where log_addtime > " . $today . " and log_value like '%刷新职位%'");
$send_flag = @smtp_mail('969078455@qq.com', $_CFG['site_name'] . $list['num'] . '|' . date('Y-m-d') . "教师招聘网刷新职位统计", $list['num'], $mc['smtpfrom'], $_CFG['site_name']);
?>