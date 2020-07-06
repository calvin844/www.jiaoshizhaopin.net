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
$act = empty($_GET['act']) ? "list" : $_GET['act'];
$show = 1;
$limit = 20;
$page = intval($_GET['p']) > 0 ? intval($_GET['p']) : 1;
$key = !empty($_GET['key']) ? intval($_GET['key']) : 0;
$value = !empty($_GET['value']) ? trim($_GET['value']) : "";
$list = array();
$mem = new Memcache;
$mem->connect("localhost", 11111);
if (browser() == "mobile") {
    echo '<script language="javascript" type="text/javascript">window.location.href="http://m.jiaoshizhaopin.net/activities/rules";</script>';
}
$now = time();
$step = 0;
if ($now > 1492185600 && $now < 1493136000) {
    $step = 1;
} elseif ($now > 1493136000 && $now < 1493568000) {
    $step = 2;
} elseif ($now > 1493568000 && $now < 1494172800) {
    $step = 3;
}
$where = " WHERE audit = 2";
if ($key > 0 && !empty($value)) {
    if ($key == 1) {
        $where .= " AND id = " . $value;
    } else if ($key == 2) {
        $where .= " AND name LIKE '%" . $value . "%'";
    } else if ($key == 3) {
        $where .= " AND title LIKE '%" . $value . "%'";
    }
}
if ($step == 1) {
    $orderby = " order by addtime desc";
} else {
    $orderby = " order by count desc";
}
$sql = "select count(*) as total from " . table('activities_20170410') . $where;
$total = $db->getone($sql);
$page_arr['total'] = ceil($total['total'] / $limit);
$page = ($page > $page_arr['total']) ? $page_arr['total'] : $page;
$offset = $page > 1 ? ($page - 1) * $limit : 0;
$page_arr['cur_page'] = $page;
$page_arr['per_page'] = ($page - 1) < 1 ? 1 : intval($page - 1);
$page_arr['next_page'] = ($page + 1) > $page_arr['total'] ? $page_arr['total'] : intval($page + 1);
$i = ($page - 2) < 1 ? 1 : ($page - 2);
$i = $i > $page_arr['total'] ? $page_arr['total'] : $i;
$end = ($page + 3) > $page_arr['total'] ? intval($page_arr['total']) + 1 : ($page + 3);
for ($i = $page - 2; $i < $end; $i++) {
    $i = $i < 1 ? 1 : $i;
    $i = $i > $page_arr['total'] ? $page_arr['total'] : $i;
    $page_arr['page_arr'][] = $i;
}
$sql = "select * from " . table('activities_20170410') . $where . $orderby . " LIMIT " . $offset . "," . $limit;
$ilist = $db->getall($sql);
foreach ($ilist as $ilist) {
    $arr = explode("##", $ilist['photo']);
    $ilist['thumbnail'] = $arr[0];
    $sql = "select * from " . table('category_district') . " where id = " . $ilist['district'];
    $district = $db->getone($sql);
    $ilist['district_cn'] = $district['categoryname'];
    if ($ilist['sdistrict'] > 0) {
        $sql = "select * from " . table('category_district') . " where id = " . $ilist['sdistrict'];
        $sdistrict = $db->getone($sql);
        $ilist['district_cn'] .= "-" . $sdistrict['categoryname'];
    }
    $data_list[] = $ilist;
}
$smarty->assign('show', $show);
$smarty->assign('key', $key);
$smarty->assign('value', $value);
$smarty->assign('data_list', $data_list);
$smarty->assign('page_arr', $page_arr);
$smarty->assign('step', $step);
$smarty->display('activities/list.htm');
?>