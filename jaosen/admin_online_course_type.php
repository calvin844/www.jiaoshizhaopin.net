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
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('online_course_type') . $orderbysql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $clist = get_type($offset, $perpage, $orderbysql);
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('online_course/type_list.htm');
} elseif ($act == "type_del") {
    $id = $_REQUEST['id'];
    if (!$id) {
        adminmsg("��ѡ�����ͣ�", 1);
    }
    if (!is_array($id)) {
        $id = array($id);
    }
    foreach ($id as $id) {
        del_type($id);
    }
    adminmsg("ɾ���ɹ���", 2);
}
?>