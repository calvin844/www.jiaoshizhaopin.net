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
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$mem = new Memcache;
$mem->connect("localhost", 11111);
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    echo '<script language="javascript" type="text/javascript">alert("参数错误！");window.location.href="/activities/list.php";</script>';
}
$sql = "select * from " . table('activities_20170410') . " where id = " . $id;
$item = $db->getone($sql);
if (empty($item)) {
    echo '<script language="javascript" type="text/javascript">alert("对不起，未找到该作品。");window.location.href="/activities/list.php";</script>';
    exit;
}
if ($item['audit'] == 1) {
    echo '<script language="javascript" type="text/javascript">alert("该作品正在努力审核中，请稍后。");window.location.href="/activities/list.php";</script>';
    exit;
}
if ($item['audit'] == 3) {
    echo '<script language="javascript" type="text/javascript">alert("该作品审核未通过，请按要求重新上传。");window.location.href="/activities/list.php";</script>';
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