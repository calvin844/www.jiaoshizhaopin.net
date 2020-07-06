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
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'index';
if ($act == 'index') {
    $cid = intval($_GET['cid']) > 0 ? intval($_GET['cid']) : adminmsg("δѡ�������γ̣�", 2);
    $_SESSION['cid'] = $cid;
    $smarty->assign('course', get_info_one($cid));
    $smarty->assign('c_index', get_course_index($cid));
    $smarty->display('online_course/index_list.htm');
} elseif ($act == 'index_all_save') {
    if (is_array($_POST['save_id']) && count($_POST['save_id']) > 0) {
        foreach ($_POST['save_id'] as $k => $v) {
            $setsqlarr['name'] = trim($_POST['name'][$k]);
            !updatetable(table('online_course_index'), $setsqlarr, " id=" . intval($_POST['save_id'][$k])) ? adminmsg("����ʧ�ܣ�", 0) : "";
            $num = $num + $db->affected_rows();
        }
    }
    //���������
    if (is_array($_POST['add_pid']) && count($_POST['add_pid']) > 0) {
        for ($i = 0; $i < count($_POST['add_pid']); $i++) {
            if (!empty($_POST['add_name'][$i])) {
                $setsqlarr['name'] = trim($_POST['add_name'][$i]);
                $setsqlarr['parent_id'] = intval($_POST['add_pid'][$i]);
                $setsqlarr['course_id'] = intval($_SESSION['cid']);
                !inserttable(table('online_course_index'), $setsqlarr) ? adminmsg("����ʧ�ܣ�", 0) : "";
                $num = $num + $db->affected_rows();
            }
        }
    }
    adminmsg("����ɹ���", 2);
} elseif ($act == 'del_index') {
    $id = $_REQUEST['id'];
    if ($num = del_index($id)) {
        adminmsg("ɾ���ɹ�����ɾ��" . $num . "��", 2);
    } else {
        adminmsg("ɾ��ʧ�ܣ�", 1);
    }
} elseif ($act == 'edit_index') {
    $smarty->assign('c_index', get_course_index($_SESSION['cid']));
    $smarty->assign('cindex', get_index_one($_GET['id']));
    $smarty->display('online_course/index_edit.htm');
} elseif ($act == 'edit_index_save') {
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg("����д����", 1);
    $setsqlarr['parent_id'] = intval($_POST['parent_id']);
    !updatetable(table('online_course_index'), $setsqlarr, " id=" . intval($_POST['id'])) ? adminmsg("�޸�ʧ�ܣ�", 0) : "";
    $link[0]['text'] = "�����б�";
    $link[0]['href'] = '?act=index';
    adminmsg("����ɹ���", 2, $link);
} elseif ($act == 'add_index') {
    $smarty->assign('c_index', get_course_index($_SESSION['cid']));
    $smarty->display('online_course/index_add.htm');
} elseif ($act == 'add_index_save') {
    //���������
    if (is_array($_POST['name']) && count($_POST['name']) > 0) {
        for ($i = 0; $i < count($_POST['name']); $i++) {
            if (!empty($_POST['name'][$i])) {
                $setsqlarr['name'] = trim($_POST['name'][$i]);
                $setsqlarr['parent_id'] = intval($_POST['parent_id'][$i]);
                $setsqlarr['course_id'] = intval($_SESSION['cid']);
                !inserttable(table('online_course_index'), $setsqlarr) ? adminmsg("����ʧ�ܣ�", 0) : "";
                $num = $num + $db->affected_rows();
            }
        }
    }
    $link[0]['text'] = "�����б�";
    $link[0]['href'] = '?act=index&cid=' . intval($_SESSION['cid']);
    adminmsg("��ӳɹ������������{$num}��Ŀ¼", 2, $link);
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