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
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('online_course_buy_log') . $orderbysql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $order = get_order($offset, $perpage, $orderbysql);
    foreach ($order as $o) {
        $info = get_info_one($o['course_id']);
        $o['course'] = $info;
        $user = get_user_by_id($o['uid']);
        $o['user'] = $user;
        switch ($o['state']) {
            case 1:
                $o['state_cn'] = "δ֧��";
                break;
            case 2:
                $o['state_cn'] = "��֧��";
                break;
            case 3:
                $o['state_cn'] = "�����쳣";
                break;
            default:
                $o['state_cn'] = "�����쳣";
                break;
        }
        $clist[] = $o;
    }
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('online_course/order_list.htm');
} elseif ($act == "order_del") {
    $id = $_REQUEST['id'];
    if (!$id) {
        adminmsg("��ѡ�񶩵���", 1);
    }
    if (!is_array($id)) {
        $id = array($id);
    }
    foreach ($id as $id) {
        del_order($id);
    }
    adminmsg("ɾ���ɹ���", 2);
} elseif ($act == 'set_state') {
    $id_arr = $_POST['id'];
    if (!is_array($id_arr)) {
        $id_arr = array($id_arr);
    }
    if (!empty($id_arr)) {
        $id_str = implode(',', $id_arr);
    } else {
        adminmsg("��ѡ��γ̣�", 1);
    }
    $state = intval($_POST['set_state']);
    $db->query("UPDATE " . table("online_course_buy_log") . " SET state = " . $state . " WHERE id IN(" . $id_str . ")");
    adminmsg("���³ɹ���", 2);
} elseif ($act == 'order_edit') {
    $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : adminmsg("��ѡ��γ̣�", 1);
    if (empty($_POST['id'])) {
        $order = get_order_one($id);
        $info = get_info_one($order['course_id']);
        $order['course'] = $info;
        $user = get_user_by_id($order['uid']);
        $order['user'] = $user;
        $smarty->assign('order', $order);
        $smarty->display('online_course/order_edit.htm');
    } else {
        $note = trim($_POST['note']);
        $db->query("UPDATE " . table("online_course_buy_log") . " SET note = " . $note . " WHERE id =" . $id);
        adminmsg("���³ɹ���", 2);
    }
} elseif ($act == 'edit_save') {
    $id = intval($_POST['id']);
    if (!$id) {
        adminmsg("��ѡ��γ̣�", 1);
    }
    $order = get_order_one($id);
    $setsqlarr['price'] = $_POST['price'];
    $setsqlarr['note'] = !empty($_POST['note']) ? trim($_POST['note']) : "";
    updatetable(table('online_course_buy_log'), $setsqlarr, " id=" . $id);
    $link[0]['text'] = "�����б�";
    $link[0]['href'] = "/jaosen/admin_online_course_order.php";
    $link[1]['text'] = "�鿴�޸Ľ��";
    $link[1]['href'] = "?act=order_edit&id=" . $id;
    adminmsg('�޸ĳɹ���', 2, $link);
}
?>