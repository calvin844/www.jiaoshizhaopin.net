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