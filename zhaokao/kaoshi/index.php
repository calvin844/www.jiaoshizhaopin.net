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
require_once(dirname(__FILE__) . '/../../include/common.inc.php');
require_once(dirname(__FILE__) . '/../../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$act = empty($_GET['act']) ? "index" : $_GET['act'];
if ($act == "index") {
    $type_en = !empty($_GET['type_en']) ? trim($_GET['type_en']) : "";
    $order = !empty($_GET['order']) ? intval($_GET['order']) : 1;
    $key = !empty($_GET['key']) ? trim($_GET['key']) : "";
    $page = !empty($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = 25;
    $where = " WHERE audit = 2 and (end_time > " . time() . " or end_time = 0)";
    $cur_type = "";
    if (!empty($type_en)) {
        $sql = "select * from " . table('online_course_type') . " where en = '" . $type_en . "'";
        $cur_type = $db->getone($sql);
        if (!empty($cur_type)) {
            $type_id = $cur_type['id'];
            $where .=" AND type_id = " . $type_id;
        }
    } else {
        $cur_type['name'] = "全部";
    }
    switch ($order) {
        case 1:
            $orderby = " ORDER BY id DESC";
            break;
        case 2:
            $orderby = " ORDER BY price DESC";
            break;
        case 3:
            $orderby = " ORDER BY price ASC";
            break;
        case 4:
            $orderby = " ORDER BY people_num DESC";
            break;
        case 5:
            $orderby = " ORDER BY people_num ASC";
            break;
        default:
            $orderby = " ORDER BY id DESC";
            break;
    }
    if (!empty($key)) {
        $where .= " AND title LIKE '%" . $key . "%'";
    }
    $sql = "select count(*) as total from " . table('online_course_info') . $where;
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
    $sql = "select * from " . table('online_course_info') . $where . $orderby . " LIMIT " . $offset . "," . $limit;
    $course_data = $db->getall($sql);
    foreach ($course_data as $cd) {
        $sql = "select * from " . table('online_course_school') . " where id=" . $cd['school_id'];
        $school = $db->getone($sql);
        $cd['school'] = !empty($school) ? $school['name'] : "未有所属机构";
        $course_list[] = $cd;
    }
    $sql = "select * from " . table('online_course_type');
    $type_list = $db->getall($sql);
    $channel = array("url" => '/zhaokao', "name" => '招考');
    $smarty->assign('channel', $channel);
    $smarty->assign('page_arr', $page_arr);
    $smarty->assign('cur_type', $cur_type);
    $smarty->assign('course_list', $course_list);
    $smarty->assign('type_list', $type_list);
    $smarty->display('zhaokao/list.htm');
}
?>