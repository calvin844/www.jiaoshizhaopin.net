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
    $key = !empty($_GET['key']) ? intval($_GET['key']) : 0;
    $value = !empty($_GET['value']) ? trim($_GET['value']) : "";
    $audit = !empty($_GET['audit']) ? intval($_GET['audit']) : 0;
    $list = array();
    if ($audit > 0) {
        $where = " WHERE audit = " . $audit;
    } else {
        $where = " WHERE 1 ";
    }
    if ($key > 0 && !empty($value)) {
        if ($key == 1) {
            $where .= " AND id = " . $value;
        } else if ($key == 2) {
            $where .= " AND teacher_name LIKE '%" . $value . "%'";
        } else if ($key == 3) {
            $where .= " AND title LIKE '%" . $value . "%'";
        } else if ($key == 4) {
            $where .= " AND audit LIKE '%" . $value . "%'";
        }
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
    $sql = "select * from " . table('act_20170601') . $where . " LIMIT " . $offset . "," . $limit;
    $ilist = $db->getall($sql);
    foreach ($ilist as $ilist) {
        $sql = "select * from " . table('resume') . " where uid = " . $ilist['uid'];
        $resume = $db->getone($sql);
        $ilist['resume'] = $resume;
        $data_list[] = $ilist;
    }
    $smarty->assign('audit', $audit);
    $smarty->assign('key', $key);
    $smarty->assign('value', $value);
    $smarty->assign('data_list', $data_list);
    $smarty->assign('page_arr', $page_arr);
    $smarty->assign('step', $step);
    $smarty->display('act0601/list.htm');
} elseif ($act == 'set_audit') {
    $id_arr = $_POST['id'];
    if (!empty($id_arr)) {
        $id_str = implode(',', $id_arr);
    }
    $audit = intval($_POST['audit']);
    $db->query("UPDATE " . table("act_20170601") . " SET audit = " . $audit . " WHERE id IN(" . $id_str . ")");
    echo '<script language="javascript" type="text/javascript">alert("更新成功！")</script>';
}
?>