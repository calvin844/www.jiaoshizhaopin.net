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
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$mem = new Memcache;
$mem->connect("localhost", 11111);
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    echo '<script language="javascript" type="text/javascript">alert("��������");window.location.href="/activities/list.php";</script>';
}
$sql = "select * from " . table('activities_20170410') . " where id = " . $id;
$item = $db->getone($sql);
if (empty($item)) {
    echo '<script language="javascript" type="text/javascript">alert("�Բ���δ�ҵ�����Ʒ��");window.location.href="/activities/list.php";</script>';
    exit;
}
if ($item['audit'] == 1) {
    echo '<script language="javascript" type="text/javascript">alert("����Ʒ����Ŭ������У����Ժ�");window.location.href="/activities/list.php";</script>';
    exit;
}
if ($item['audit'] == 3) {
    echo '<script language="javascript" type="text/javascript">alert("����Ʒ���δͨ�����밴Ҫ�������ϴ���");window.location.href="/activities/list.php";</script>';
    exit;
}
if (browser() == "mobile") {
    echo '<script language="javascript" type="text/javascript">window.location.href="http://m.jiaoshizhaopin.net/activities/details?id=' . $item['id'] . '";</script>';
    exit;
}
$item['photo'] = explode("##", $item['photo']);
$item['photo_num'] = count($item['photo']);
$sql = "select * from " . table('category_district') . " where id = " . $item['district'];
$district = $db->getone($sql);
if ($item['sdistrict'] > 0) {
    $sql = "select * from " . table('category_district') . " where id = " . $item['sdistrict'];
    $sdistrict = $db->getone($sql);
}
$sdistrict['categoryname'] = empty($sdistrict) ? "" : "-" . $sdistrict['categoryname'];
$item['district_cn'] = $district['categoryname'] . $sdistrict['categoryname'];
$item['content'] = nl2br($item['content']);
$smarty->assign('item', $item);
$smarty->display('activities/details.htm');
?>