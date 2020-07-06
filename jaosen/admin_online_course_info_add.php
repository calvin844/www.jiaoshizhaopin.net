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
    $teacher = get_teacher_list();
    $type = get_type_list();
    $smarty->assign('school', $school);
    $smarty->assign('teacher', $teacher);
    $smarty->assign('type', $type);
    $smarty->display('online_course/info_add.htm');
} elseif ($act == 'save') {
    $setsqlarr['school_id'] = intval($_POST['school_id']);
    $setsqlarr['teacher_id'] = intval($_POST['teacher_id']);
    $setsqlarr['type_id'] = intval($_POST['type_id']);
    $setsqlarr['title'] = !empty($_POST['title']) ? trim($_POST['title']) : adminmsg('请填写标题！', 1);
    $setsqlarr['des'] = !empty($_POST['des']) ? trim($_POST['des']) : adminmsg('请填写正文！', 1);
    !$_FILES['thumb']['name'] ? adminmsg('请上传缩略图！', 1) : "";
    $upload_image_dir = "../data/online_course_file/info_thumb/" . date("Y/m/d/");
    make_dir($upload_image_dir);
    require_once(dirname(__FILE__) . '/include/upload.php');
    $setsqlarr['thumb'] = _asUpFiles($upload_image_dir, "thumb", "2048", 'gif/jpg/bmp/png', true);
    $setsqlarr['thumb'] = "/data/online_course_file/info_thumb/" . date("Y/m/d/") . $setsqlarr['thumb'];
    $setsqlarr['price'] = $_POST['price'] == 0 ? 0 : number_format($_POST['price'], 2, '.', '');
    $setsqlarr['old_price'] = $_POST['old_price'] == 0 ? 0 : number_format($_POST['old_price'], 2, '.', '');
    $setsqlarr['people_limit'] = intval($_POST['people_limit']);
    $setsqlarr['people_num'] = intval($_POST['people_num']);
    $setsqlarr['start_time'] = strtotime($_POST['start_time']);
    $setsqlarr['end_time'] = strtotime($_POST['end_time']);
    $setsqlarr['qq'] = trim($_POST['qq']);
    if ($_FILES['wechat_img']['name']) {
        $upload_image_dir = "../data/online_course_file/info_wechat_img/" . date("Y/m/d/");
        make_dir($upload_image_dir);
        $setsqlarr['wechat_img'] = _asUpFiles($upload_image_dir, "wechat_img", "2048", 'gif/jpg/bmp/png', true);
        $setsqlarr['wechat_img'] = "/data/online_course_file/info_wechat_img/" . date("Y/m/d/") . $setsqlarr['wechat_img'];
    }
    $setsqlarr['audit'] = 2;
    $setsqlarr['click'] = 0;
    $setsqlarr['addtime'] = time();
    $insert_id = inserttable(table('online_course_info'), $setsqlarr, true);
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "/jaosen/admin_online_course_info.php";
    $link[1]['text'] = "添加目录";
    $link[1]['href'] = "/jaosen/admin_online_course_index.php?cid=" . $insert_id;
    adminmsg('添加成功！', 2, $link);
} elseif ($act == 'edit') {
    $id = intval($_GET['id']);
    if (!$id) {
        adminmsg("请选择课程！", 1);
    }
    $school = get_school_list();
    $teacher = get_teacher_list();
    $type = get_type_list();
    $smarty->assign('school', $school);
    $smarty->assign('teacher', $teacher);
    $smarty->assign('type', $type);
    $info = get_info_one($id);
    $smarty->assign('info', $info);
    $smarty->display('online_course/info_edit.htm');
} elseif ($act == 'edit_save') {
    $id = intval($_POST['id']);
    if (!$id) {
        adminmsg("请选择课程！", 1);
    }
    $setsqlarr['school_id'] = intval($_POST['school_id']);
    $setsqlarr['teacher_id'] = intval($_POST['teacher_id']);
    $setsqlarr['type_id'] = intval($_POST['type_id']);
    $setsqlarr['title'] = !empty($_POST['title']) ? trim($_POST['title']) : adminmsg('请填写标题！', 1);
    $setsqlarr['des'] = !empty($_POST['des']) ? trim($_POST['des']) : adminmsg('请填写正文！', 1);
    if ($_FILES['thumb']['name']) {
        $upload_image_dir = "../data/online_course_file/info_thumb/" . date("Y/m/d/");
        make_dir($upload_image_dir);
        require_once(dirname(__FILE__) . '/include/upload.php');
        $setsqlarr['thumb'] = _asUpFiles($upload_image_dir, "thumb", "2048", 'gif/jpg/bmp/png', true);
        $setsqlarr['thumb'] = "/data/online_course_file/info_thumb/" . date("Y/m/d/") . $setsqlarr['thumb'];
    }
    $setsqlarr['price'] = $_POST['price'] == 0 ? 0 : number_format($_POST['price'], 2, '.', '');
    $setsqlarr['old_price'] = $_POST['old_price'] == 0 ? 0 : number_format($_POST['old_price'], 2, '.', '');
    $setsqlarr['people_limit'] = intval($_POST['people_limit']);
    $setsqlarr['people_num'] = intval($_POST['people_num']);
    $setsqlarr['start_time'] = strtotime($_POST['start_time']);
    $setsqlarr['end_time'] = strtotime($_POST['end_time']);
    $setsqlarr['qq'] = trim($_POST['qq']);
    if ($_FILES['wechat_img']['name']) {
        $upload_image_dir = "../data/online_course_file/info_wechat_img/" . date("Y/m/d/");
        make_dir($upload_image_dir);
        require_once(dirname(__FILE__) . '/include/upload.php');
        $setsqlarr['wechat_img'] = _asUpFiles($upload_image_dir, "wechat_img", "2048", 'gif/jpg/bmp/png', true);
        $setsqlarr['wechat_img'] = "/data/online_course_file/info_wechat_img/" . date("Y/m/d/") . $setsqlarr['wechat_img'];
    }
    updatetable(table('online_course_info'), $setsqlarr, " id=" . $id);
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "/jaosen/admin_online_course_info.php";
    $link[1]['text'] = "查看修改结果";
    $link[1]['href'] = "?act=edit&id=" . $id;
    adminmsg('修改成功！', 2, $link);
} elseif ($act == 'get_teacher_ajax') {
    $school_id = intval($_GET['school_id']);
    $teacher_data = get_teacher_list_by_school_id($school_id);
    $teacher_str = "";
    if (!empty($teacher_data)) {
        foreach ($teacher_data as $td) {
            $teacher_str .= $td['id'] . "--" . $td['name'] . "|";
        }
    }
    $teacher_str = trim($teacher_str, "|");
    echo $teacher_str;
}
?>