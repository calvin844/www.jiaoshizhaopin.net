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
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'add';
if ($act == 'add') {
    $smarty->display('online_course/type_add.htm');
} elseif ($act == 'save') {
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg('请填写类型名称！', 1);
    $setsqlarr['en'] = !empty($_POST['en']) ? trim($_POST['en']) : adminmsg('请填写类型英文缩写！', 1);
    $setsqlarr['addtime'] = time();
    $insert_id = inserttable(table('online_course_type'), $setsqlarr, true);
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "/jaosen/admin_online_course_type.php";
    $link[1]['text'] = "继续添加";
    $link[1]['href'] = "?act=add";
    adminmsg('添加成功！', 2, $link);
} elseif ($act == 'edit') {
    $id = intval($_GET['id']);
    if (!$id) {
        adminmsg("请选择类型！", 1);
    }
    $type = get_type_one($id);
    $smarty->assign('type', $type);
    $smarty->display('online_course/type_edit.htm');
} elseif ($act == 'edit_save') {
    $id = intval($_POST['id']);
    if (!$id) {
        adminmsg("请选择类型！", 1);
    }
    $type = get_type_one($id);
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg('请填写类型名称！', 1);
    $setsqlarr['en'] = !empty($_POST['en']) ? trim($_POST['en']) : adminmsg('请填写类型英文缩写！', 1);
    updatetable(table('online_course_type'), $setsqlarr, " id=" . $id);
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "/jaosen/admin_online_course_type.php";
    $link[1]['text'] = "查看修改结果";
    $link[1]['href'] = "?act=edit&id=" . $id;
    adminmsg('修改成功！', 2, $link);
}
?>