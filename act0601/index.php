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
$act = empty($_GET['act']) ? "index" : $_GET['act'];
$mem = new Memcache;
$mem->connect("localhost", 11111);
if (browser() == "mobile") {
    echo '<script language="javascript" type="text/javascript">window.location.href="http://m.jiaoshizhaopin.net/act0601/";</script>';
    exit;
}
if ($act == "index") {
    $uteam = array();
    if (!empty($_SESSION['uid'])) {
        $sql = "select * from " . table('act_20170601_team') . " where leader = " . $_SESSION['uid'];
        $team = $db->getone($sql);
        if ($team['id'] > 0) {
            $uteam = $team;
        }
    }
    $total_show = 1;
    $all_total = 0;
    for ($i = 1; $i < 7; $i++) {
        $type_total = 0;
        $sql = "select count(*) as type_total from " . table('act_20170601') . " where item_type = " . $i;
        $type_total = $db->getone($sql);
        $type_total = $type_total['type_total'];
        if ($type_total == 0) {
            $total_show = 0;
        }
        $t_total[$i] = $type_total;
        $all_total = $all_total + $type_total;
    }
    
    $smarty->assign('total_show', $total_show);
    $smarty->assign('t_total', $t_total);
    $smarty->assign('all_total', $all_total);
    $smarty->assign('uteam', $uteam);
    $smarty->display('act0601/index.htm');
}
?>