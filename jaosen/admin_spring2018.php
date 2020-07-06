<?php

/*
 * 74cms 个人
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
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'index';
if ($act == 'index') {
    $limit = 20;
    $page = intval($_GET['p']) > 0 ? intval($_GET['p']) : 1;
    $id = !empty($_GET['id']) ? intval($_GET['id']) : 0;
    $uid = !empty($_GET['uid']) ? intval($_GET['uid']) : 0;
    $utype = !empty($_GET['utype']) ? intval($_GET['utype']) : 0;
    $coupons_type = !empty($_GET['coupons_type']) ? intval($_GET['coupons_type']) : 0;
    $state = !empty($_GET['state']) ? intval($_GET['state']) : 0;
    $list = array();
    if ($state > 0) {
        $where = " WHERE state = " . $state;
    } else {
        $where = " WHERE 1 ";
    }
    if ($id > 0) {
        $where .= " AND id = " . $id;
    }
    if ($coupons_type > 0) {
        $where .= " AND coupons_type = " . $coupons_type;
    }
    if ($uid > 0) {
        $where .= " AND uid = " . $uid;
    }
    if ($utype > 0) {
        $where .= " AND utype = " . $utype;
    }
    $sql = "select count(*) as total from " . table('act_spring2018') . $where;
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
    $sql = "select * from " . table('act_spring2018') . $where . " LIMIT " . $offset . "," . $limit;
    $ilist = $db->getall($sql);
    foreach ($ilist as $ilist) {
        $sql = "select * from " . table('act_spring2018_img') . " where uid = " . $ilist['uid'];
        $ilist['img'] = $db->getone($sql);
        $user = $db->getone("select * from " . table('members') . " where uid=" . $ilist['uid']);
        $ilist['user'] = $user;
        $data_list[] = $ilist;
    }
    $smarty->assign('state', $state);
    $smarty->assign('id', $id);
    $smarty->assign('coupons_type', $coupons_type);
    $smarty->assign('uid', $uid);
    $smarty->assign('utype', $utype);
    $smarty->assign('data_list', $data_list);
    $smarty->assign('page_arr', $page_arr);
    $smarty->assign('step', $step);
    $smarty->display('spring2018/list.htm');
} elseif ($act == 'set_state') {
    $id_arr = $_POST['id'];
    if (!empty($id_arr)) {
        $id_str = implode(',', $id_arr);
    }
    $state = intval($_POST['state']);
    $db->query("UPDATE " . table('act_spring2018') . " SET state = " . $state . " WHERE id IN(" . $id_str . ")");
    echo '<script language="javascript" type="text/javascript">alert("更新成功！")</script>';
}
?>