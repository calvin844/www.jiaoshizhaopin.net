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
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $orderbysql = " order BY id DESC ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('online_course_info') . $orderbysql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $info = get_info($offset, $perpage, $orderbysql);
    foreach ($info as $i) {
        $school = get_school_one($i['school_id']);
        $i['school'] = !empty($school) ? $school['name'] : "δ����������";
        $teacher = get_teacher_one($i['teacher_id']);
        $i['teacher'] = !empty($teacher) ? $teacher['name'] : "������ʦ";
        $type = get_type_one($i['type_id']);
        $i['type'] = !empty($type) ? $type['name'] : "�÷��಻����";
        $clist[] = $i;
    }
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('online_course/info_list.htm');
} elseif ($act == "info_del") {
    $id = $_REQUEST['id'];
    if (!$id) {
        adminmsg("��ѡ��γ̣�", 1);
    }
    if (!is_array($id)) {
        $id = array($id);
    }
    foreach ($id as $id) {
        del_info($id);
    }
    adminmsg("ɾ���ɹ���", 2);
} elseif ($act == 'set_audit') {
    $id_arr = $_POST['id'];
    if (!is_array($id_arr)) {
        $id_arr = array($id_arr);
    }
    if (!empty($id_arr)) {
        $id_str = implode(',', $id_arr);
    } else {
        adminmsg("��ѡ��γ̣�", 1);
    }
    $audit = intval($_POST['set_audit']);
    $db->query("UPDATE " . table("online_course_info") . " SET audit = " . $audit . " WHERE id IN(" . $id_str . ")");
    adminmsg("���³ɹ���", 2);
}
?>