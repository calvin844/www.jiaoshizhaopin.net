<?php

define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
if (!empty($_POST['bd_name'])) {
    $data['bd_name'] = trim($_POST['bd_name']);
    $data['item1'] = trim($_POST['item1']);
    $data['item2'] = trim($_POST['item2']);
    $data['item3'] = trim($_POST['item3']);
    $data['note'] = trim($_POST['note']);
    $data['add_time'] = time();
    $db->insert("bd_log", $data);
    $_CFG = get_cache('config');
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
    $html = "����<a target='_blank' href='http://www.jiaoshizhaopin.net/'>��ʦ��Ƹ��</a>��������Ŀ�����������ύ���������£�<br/>������" . $data['item1'] . "<br/>" . "�ֻ��ţ�" . $data['item2'] . "<br/>" . "΢�źţ�" . $data['item3'] . "<br/><br/>�������飬��<a target='_blank' href='http://www.jiaoshizhaopin.net/bd001/list.php'>�������</a>";
    smtp_mail('waitruan@163.com', $_CFG['site_name'] . "��Ŀ����������", $html, $mc['smtpfrom'], $_CFG['site_name']);
    echo "<script>alert('�ύ�ɹ���');history.back(-2);</script>";
    exit;
}
?>