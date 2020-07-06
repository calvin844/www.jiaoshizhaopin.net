<?php

/*
 * 74cms 职位订阅
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../data/config.php');
require_once(dirname(__FILE__) . '/include/admin_common.inc.php');
require_once(ADMIN_ROOT_PATH . 'include/admin_subscribe_fun.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'list';
$smarty->assign('act', $act);
$smarty->assign('pageheader', "职位订阅");
if ($act == 'list') {
    check_permissions($_SESSION['admin_purview'], "subscribe_list");
    get_token();
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    $orderbysql = " order BY `addtime` DESC";
    if ($key && $key_type > 0) {

        if ($key_type == 1)
            $wheresql = " WHERE intention_jobs like '%{$key}%' or search_name like '%{$key}%'";
        if ($key_type == 2)
            $wheresql = " WHERE district_cn like '%{$key}%'";
        if ($key_type == 3)
            $wheresql = " WHERE email ='{$key}'";
    }
    else {
        if (!empty($_GET['addtime'])) {
            $settr = strtotime("-" . intval($_GET['addtime']) . " day");
            $wheresql = empty($wheresql) ? " WHERE addtime> " . $settr : $wheresql . " AND addtime> " . $settr;
        }
    }

    $total_sql = "SELECT COUNT(*) AS num FROM " . table('jobs_subscribe') . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $list = get_subscribe_list($offset, $perpage, $wheresql . $orderbysql);
    $smarty->assign('key', $key);
    $smarty->assign('total', $total_val);
    $smarty->assign('list', $list);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('navlabel', 'list');
    $smarty->display('subscribe/admin_subscribe.htm');
} elseif ($act == 'subscribe_del') {
    check_permissions($_SESSION['admin_purview'], "subscribe_list");
    check_token();
    $id = $_REQUEST['id'];
    if (empty($id)) {
        adminmsg("您没有选择项目！", 1);
    }
    if ($num = subscribe_del($id)) {
        adminmsg("删除成功！共删除" . $num . "行", 2);
    } else {
        adminmsg("删除失败！", 0);
    }
}
?>