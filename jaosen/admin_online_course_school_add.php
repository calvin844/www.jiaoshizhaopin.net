<?php

/*
 * 74cms ����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../data/config.php');
require_once(dirname(__FILE__) . '/include/admin_common.inc.php');
require_once(ADMIN_ROOT_PATH . 'include/admin_online_course_fun.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'add';
if ($act == 'add') {
    $smarty->display('online_course/school_add.htm');
} elseif ($act == 'save') {
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg('����д�������ƣ�', 1);
    $setsqlarr['des'] = !empty($_POST['des']) ? trim($_POST['des']) : adminmsg('����д������飡', 1);
    !$_FILES['logo']['name'] ? adminmsg('���ϴ�����LOGO��', 1) : "";
    $upload_image_dir = "../data/online_course_file/school/" . date("Y/m/d/");
    make_dir($upload_image_dir);
    require_once(dirname(__FILE__) . '/include/upload.php');
    $setsqlarr['logo'] = _asUpFiles($upload_image_dir, "logo", "2048", 'gif/jpg/bmp/png', true);
    $setsqlarr['logo'] = "/data/online_course_file/school/" . date("Y/m/d/") . $setsqlarr['logo'];
    $setsqlarr['audit'] = 2;
    $setsqlarr['addtime'] = time();
    $insert_id = inserttable(table('online_course_school'), $setsqlarr, true);
    $link[0]['text'] = "�����б�";
    $link[0]['href'] = "/jaosen/admin_online_course_school.php";
    $link[1]['text'] = "�������";
    $link[1]['href'] = "?act=add";
    adminmsg('��ӳɹ���', 2, $link);
} elseif ($act == 'edit') {
    $id = intval($_GET['id']);
    if (!$id) {
        adminmsg("��ѡ�������", 1);
    }
    $school = get_school_one($id);
    $smarty->assign('school', $school);
    $smarty->display('online_course/school_edit.htm');
} elseif ($act == 'edit_save') {
    $id = intval($_POST['id']);
    if (!$id) {
        adminmsg("��ѡ�������", 1);
    }
    $school = get_school_one($id);
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg('����д�������ƣ�', 1);
    $setsqlarr['des'] = !empty($_POST['des']) ? trim($_POST['des']) : adminmsg('����д������飡', 1);
    if ($_FILES['logo']['name']) {
        $upload_image_dir = "../data/online_course_file/school/" . date("Y/m/d/");
        make_dir($upload_image_dir);
        require_once(dirname(__FILE__) . '/include/upload.php');
        $setsqlarr['logo'] = _asUpFiles($upload_image_dir, "logo", "2048", 'gif/jpg/bmp/png', true);
        $setsqlarr['logo'] = "/data/online_course_file/school/" . date("Y/m/d/") . $setsqlarr['logo'];
        @unlink(".." . $school['logo']);
    }
    updatetable(table('online_course_school'), $setsqlarr, " id=" . $id);
    $link[0]['text'] = "�����б�";
    $link[0]['href'] = "/jaosen/admin_online_course_school.php";
    $link[1]['text'] = "�鿴�޸Ľ��";
    $link[1]['href'] = "?act=edit&id=" . $id;
    adminmsg('�޸ĳɹ���', 2, $link);
}
?>