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
    $cid = intval($_GET['cid']) > 0 ? intval($_GET['cid']) : adminmsg("未选择所属课程！", 2);
    $_SESSION['cid'] = $cid;
    $smarty->assign('course', get_info_one($cid));
    $smarty->assign('c_index', get_course_index($cid));
    $smarty->display('online_course/index_list.htm');
} elseif ($act == 'index_all_save') {
    if (is_array($_POST['save_id']) && count($_POST['save_id']) > 0) {
        foreach ($_POST['save_id'] as $k => $v) {
            $setsqlarr['name'] = trim($_POST['name'][$k]);
            !updatetable(table('online_course_index'), $setsqlarr, " id=" . intval($_POST['save_id'][$k])) ? adminmsg("保存失败！", 0) : "";
            $num = $num + $db->affected_rows();
        }
    }
    //新增的入库
    if (is_array($_POST['add_pid']) && count($_POST['add_pid']) > 0) {
        for ($i = 0; $i < count($_POST['add_pid']); $i++) {
            if (!empty($_POST['add_name'][$i])) {
                $setsqlarr['name'] = trim($_POST['add_name'][$i]);
                $setsqlarr['parent_id'] = intval($_POST['add_pid'][$i]);
                $setsqlarr['course_id'] = intval($_SESSION['cid']);
                !inserttable(table('online_course_index'), $setsqlarr) ? adminmsg("保存失败！", 0) : "";
                $num = $num + $db->affected_rows();
            }
        }
    }
    adminmsg("保存成功！", 2);
} elseif ($act == 'del_index') {
    $id = $_REQUEST['id'];
    if ($num = del_index($id)) {
        adminmsg("删除成功！共删除" . $num . "行", 2);
    } else {
        adminmsg("删除失败！", 1);
    }
} elseif ($act == 'edit_index') {
    $smarty->assign('c_index', get_course_index($_SESSION['cid']));
    $smarty->assign('cindex', get_index_one($_GET['id']));
    $smarty->display('online_course/index_edit.htm');
} elseif ($act == 'edit_index_save') {
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg("请填写名称", 1);
    $setsqlarr['parent_id'] = intval($_POST['parent_id']);
    !updatetable(table('online_course_index'), $setsqlarr, " id=" . intval($_POST['id'])) ? adminmsg("修改失败！", 0) : "";
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = '?act=index';
    adminmsg("保存成功！", 2, $link);
} elseif ($act == 'add_index') {
    $smarty->assign('c_index', get_course_index($_SESSION['cid']));
    $smarty->display('online_course/index_add.htm');
} elseif ($act == 'add_index_save') {
    //新增的入库
    if (is_array($_POST['name']) && count($_POST['name']) > 0) {
        for ($i = 0; $i < count($_POST['name']); $i++) {
            if (!empty($_POST['name'][$i])) {
                $setsqlarr['name'] = trim($_POST['name'][$i]);
                $setsqlarr['parent_id'] = intval($_POST['parent_id'][$i]);
                $setsqlarr['course_id'] = intval($_SESSION['cid']);
                !inserttable(table('online_course_index'), $setsqlarr) ? adminmsg("保存失败！", 0) : "";
                $num = $num + $db->affected_rows();
            }
        }
    }
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = '?act=index&cid=' . intval($_SESSION['cid']);
    adminmsg("添加成功！本次添加了{$num}个目录", 2, $link);
} elseif ($act == 'get_index_son') {
    $pid = intval($_GET['pid']);
    $sql = "select * from " . table('online_course_index') . " where parent_id=" . $pid . "  order BY id asc";
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        $cat[] = $row['id'] . "," . $row['name'];
    }
    if (!empty($cat)) {
        exit(implode('|', $cat));
    }
}
?>