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
    $smarty->display('online_course/type_add.htm');
} elseif ($act == 'save') {
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg('����д�������ƣ�', 1);
    $setsqlarr['en'] = !empty($_POST['en']) ? trim($_POST['en']) : adminmsg('����д����Ӣ����д��', 1);
    $setsqlarr['addtime'] = time();
    $insert_id = inserttable(table('online_course_type'), $setsqlarr, true);
    $link[0]['text'] = "�����б�";
    $link[0]['href'] = "/jaosen/admin_online_course_type.php";
    $link[1]['text'] = "�������";
    $link[1]['href'] = "?act=add";
    adminmsg('��ӳɹ���', 2, $link);
} elseif ($act == 'edit') {
    $id = intval($_GET['id']);
    if (!$id) {
        adminmsg("��ѡ�����ͣ�", 1);
    }
    $type = get_type_one($id);
    $smarty->assign('type', $type);
    $smarty->display('online_course/type_edit.htm');
} elseif ($act == 'edit_save') {
    $id = intval($_POST['id']);
    if (!$id) {
        adminmsg("��ѡ�����ͣ�", 1);
    }
    $type = get_type_one($id);
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg('����д�������ƣ�', 1);
    $setsqlarr['en'] = !empty($_POST['en']) ? trim($_POST['en']) : adminmsg('����д����Ӣ����д��', 1);
    updatetable(table('online_course_type'), $setsqlarr, " id=" . $id);
    $link[0]['text'] = "�����б�";
    $link[0]['href'] = "/jaosen/admin_online_course_type.php";
    $link[1]['text'] = "�鿴�޸Ľ��";
    $link[1]['href'] = "?act=edit&id=" . $id;
    adminmsg('�޸ĳɹ���', 2, $link);
}
?>