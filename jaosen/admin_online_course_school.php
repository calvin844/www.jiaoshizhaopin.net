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
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('online_course_school') . $orderbysql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $clist = get_school($offset, $perpage, $orderbysql);
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('online_course/school_list.htm');
} elseif ($act == "school_del") {
    $id = $_REQUEST['id'];
    if (!$id) {
        adminmsg("��ѡ�������", 1);
    }
    if (!is_array($id)) {
        $id = array($id);
    }
    foreach ($id as $id) {
        del_school($id);
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
        adminmsg("��ѡ�������", 1);
    }
    $audit = intval($_POST['set_audit']);
    $db->query("UPDATE " . table("online_course_school") . " SET audit = " . $audit . " WHERE id IN(" . $id_str . ")");
    adminmsg("���³ɹ���", 2);
}
?>