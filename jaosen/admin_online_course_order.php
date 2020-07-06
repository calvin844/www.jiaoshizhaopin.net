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
                $o['state_cn'] = "未支付";
                break;
            case 2:
                $o['state_cn'] = "已支付";
                break;
            case 3:
                $o['state_cn'] = "订单异常";
                break;
            default:
                $o['state_cn'] = "订单异常";
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
        adminmsg("请选择订单！", 1);
    }
    if (!is_array($id)) {
        $id = array($id);
    }
    foreach ($id as $id) {
        del_order($id);
    }
    adminmsg("删除成功！", 2);
} elseif ($act == 'set_state') {
    $id_arr = $_POST['id'];
    if (!is_array($id_arr)) {
        $id_arr = array($id_arr);
    }
    if (!empty($id_arr)) {
        $id_str = implode(',', $id_arr);
    } else {
        adminmsg("请选择课程！", 1);
    }
    $state = intval($_POST['set_state']);
    $db->query("UPDATE " . table("online_course_buy_log") . " SET state = " . $state . " WHERE id IN(" . $id_str . ")");
    adminmsg("更新成功！", 2);
} elseif ($act == 'order_edit') {
    $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : adminmsg("请选择课程！", 1);
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
        adminmsg("更新成功！", 2);
    }
} elseif ($act == 'edit_save') {
    $id = intval($_POST['id']);
    if (!$id) {
        adminmsg("请选择课程！", 1);
    }
    $order = get_order_one($id);
    $setsqlarr['price'] = $_POST['price'];
    $setsqlarr['note'] = !empty($_POST['note']) ? trim($_POST['note']) : "";
    updatetable(table('online_course_buy_log'), $setsqlarr, " id=" . $id);
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "/jaosen/admin_online_course_order.php";
    $link[1]['text'] = "查看修改结果";
    $link[1]['href'] = "?act=order_edit&id=" . $id;
    adminmsg('修改成功！', 2, $link);
}
?>