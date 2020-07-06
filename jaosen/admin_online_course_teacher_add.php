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
    $school = get_school_list();
    $smarty->assign('school', $school);
    $smarty->display('online_course/teacher_add.htm');
} elseif ($act == 'save') {
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg('请填写讲师名称！', 1);
    $setsqlarr['school_id'] = intval($_POST['school_id']);
    $setsqlarr['des'] = trim($_POST['des']);
    !$_FILES['photo']['name'] ? adminmsg('请上传讲师照片！', 1) : "";
    $upload_image_dir = "../data/online_course_file/teacher/" . date("Y/m/d/");
    make_dir($upload_image_dir);
    require_once(dirname(__FILE__) . '/include/upload.php');
    $setsqlarr['photo'] = _asUpFiles($upload_image_dir, "photo", "2048", 'gif/jpg/bmp/png', true);
    $setsqlarr['photo'] = "/data/online_course_file/teacher/" . date("Y/m/d/") . $setsqlarr['photo'];
    $setsqlarr['audit'] = 2;
    $setsqlarr['addtime'] = time();
    $insert_id = inserttable(table('online_course_teacher'), $setsqlarr, true);
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "/jaosen/admin_online_course_teacher.php";
    $link[1]['text'] = "继续添加";
    $link[1]['href'] = "?act=add";
    adminmsg('添加成功！', 2, $link);
} elseif ($act == 'edit') {
    $id = intval($_GET['id']);
    if (!$id) {
        adminmsg("请选择讲师！", 1);
    }
    $school = get_school_list();
    $smarty->assign('school', $school);
    $teacher = get_teacher_one($id);
    $smarty->assign('teacher', $teacher);
    $smarty->display('online_course/teacher_edit.htm');
} elseif ($act == 'edit_save') {
    $id = intval($_POST['id']);
    if (!$id) {
        adminmsg("请选择讲师！", 1);
    }
    $school = get_school_one($id);
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg('请填写讲师名称！', 1);
    $setsqlarr['school_id'] = intval($_POST['school_id']);
    $setsqlarr['des'] = trim($_POST['des']);
    if ($_FILES['photo']['name']) {
        $upload_image_dir = "../data/online_course_file/teacher/" . date("Y/m/d/");
        make_dir($upload_image_dir);
        require_once(dirname(__FILE__) . '/include/upload.php');
        $setsqlarr['photo'] = _asUpFiles($upload_image_dir, "photo", "2048", 'gif/jpg/bmp/png', true);
        $setsqlarr['photo'] = "/data/online_course_file/teacher/" . date("Y/m/d/") . $setsqlarr['photo'];
        @unlink(".." . $teacher['photo']);
    }
    updatetable(table('online_course_teacher'), $setsqlarr, " id=" . $id);
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "/jaosen/admin_online_course_teacher.php";
    $link[1]['text'] = "查看修改结果";
    $link[1]['href'] = "?act=edit&id=" . $id;
    adminmsg('修改成功！', 2, $link);
}
?>