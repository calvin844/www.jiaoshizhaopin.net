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
require_once(ADMIN_ROOT_PATH . 'include/admin_online_course_fun.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'index';
if ($act == 'index') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $orderbysql = " order BY id DESC ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('online_course_teacher') . $orderbysql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $teacher = get_teacher($offset, $perpage, $orderbysql);
    foreach ($teacher as $t) {
        $school = get_school_one($t['school_id']);
        $t['school'] = !empty($school) ? $school['name'] : "未有所属机构";
        $clist[] = $t;
    }
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('online_course/teacher_list.htm');
} elseif ($act == "teacher_del") {
    $id = $_REQUEST['id'];
    if (!$id) {
        adminmsg("请选择讲师！", 1);
    }
    if (!is_array($id)) {
        $id = array($id);
    }
    foreach ($id as $id) {
        del_teacher($id);
    }
    adminmsg("删除成功！", 2);
} elseif ($act == 'set_audit') {
    $id_arr = $_POST['id'];
    if (!is_array($id_arr)) {
        $id_arr = array($id_arr);
    }
    if (!empty($id_arr)) {
        $id_str = implode(',', $id_arr);
    } else {
        adminmsg("请选择讲师！", 1);
    }
    $audit = intval($_POST['set_audit']);
    $db->query("UPDATE " . table("online_course_teacher") . " SET audit = " . $audit . " WHERE id IN(" . $id_str . ")");
    adminmsg("更新成功！", 2);
}
?>