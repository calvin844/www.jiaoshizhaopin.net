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
$limit = 20;
$page = intval($_GET['p']) > 0 ? intval($_GET['p']) : 1;
if (browser() == "mobile") {
    echo '<script language="javascript" type="text/javascript">window.location.href="http://m.jiaoshizhaopin.net/act0601/ilist?p=' . $page . '";</script>';
    exit;
}
$type = !empty($_GET['type']) ? intval($_GET['type']) : 0;
$_GET['type'] = !empty($_GET['type']) ? intval($_GET['type']) : 0;
$order = !empty($_GET['order']) ? intval($_GET['order']) : 1;
$_GET['order'] = !empty($_GET['order']) ? intval($_GET['order']) : 1;
$list = array();
$where = " WHERE audit = 2";
if ($type > 0) {
    $where .= " AND item_type=" . $type;
}
if ($order == 1) {
    $orderby = " ORDER BY addtime desc";
} else {
    $orderby = " ORDER BY click desc";
}
$sql = "select count(*) as total from " . table('act_20170601') . $where;
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
$sql = "select * from " . table('act_20170601') . $where . $orderby . " LIMIT " . $offset . "," . $limit;
$data_list = $db->getall($sql);
$smarty->assign('type', $type);
$smarty->assign('order', $order);
$smarty->assign('data_list', $data_list);
$smarty->assign('page_arr', $page_arr);
$smarty->display('act0601/ilist.htm');
?>