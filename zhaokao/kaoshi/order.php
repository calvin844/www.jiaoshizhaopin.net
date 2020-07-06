<?php

/*
 * 74cms ���ؼ���
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../../include/common.inc.php');
require_once(dirname(__FILE__) . '/../../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$act = empty($_GET['act']) ? "show_order" : $_GET['act'];
if ($act == "show_order") {
    if ($_SESSION['uid'] == '' || $_SESSION['username'] == '' || intval($_SESSION['uid']) === 0) {
        header("Location: /user/login.php?act_url=" . $_SERVER['PHP_SELF']);
        exit();
    } elseif ($_SESSION['utype'] != '2') {
        $link[0]['text'] = "���ϵ�¼";
        $link[0]['href'] = "/user/login.php?act_url=" . $_SERVER['PHP_SELF'];
        showmsg('�����ʵ�ҳ����Ҫ ���˻�Ա ��¼��', 1, $link);
    }
    $order_id = !empty($_GET['oid']) ? intval($_GET['oid']) : showmsg('�Ҳ�����Ӧ������');
    $uid = intval($_SESSION['uid']);
    $sql = "select * from " . table('online_course_buy_log') . " where uid = " . $uid . " and id = " . $order_id;
    $order = $db->getone($sql);
    if (empty($order)) {
        showmsg('�Ҳ�����Ӧ������');
    } else {
        $sql = "select * from " . table('online_course_info') . " where id = " . $order['course_id'];
        $order['course'] = $db->getone($sql);
    }
    $get_function = "get_code_" . $order['payment'];
    $order_form = $get_function($order['id']);
    $channel = array("url" => '/zhaokao', "name" => '�п�');
    $smarty->assign('channel', $channel);
    $smarty->assign('order', $order);
    $smarty->assign('order_form', $order_form);
    $smarty->display('zhaokao/show_order.htm');
} elseif ($act == "add_order") {
    $order_id = 0;
    $course_id = intval($_POST['course_id']);
    $url = $course_id > 0 ? "/zhaokao/kaoshi/" . $course_id . ".htm" : "/zhaokao";
    if ($_SESSION['uid'] == '' || $_SESSION['username'] == '' || intval($_SESSION['uid']) === 0) {
        header("Location: /user/login.php?act_url=" . $url);
        exit();
    } elseif ($_SESSION['utype'] != '2') {
        $link[0]['text'] = "���ϵ�¼";
        $link[0]['href'] = "/user/login.php?act_url=" . $url;
        showmsg('�����ʵ�ҳ����Ҫ ���˻�Ա ��¼��', 1, $link);
    }

    $sql = "select * from " . table('online_course_info') . " where id=" . $course_id;
    $course = $db->getone($sql);
    if ($course['people_limit'] > 0 && (($course['people_num'] > $course['people_limit']) || ($course['people_num'] == $course['people_limit']))) {
        $link[0]['text'] = "�鿴�����γ�";
        $link[0]['href'] = "/zhaokao";
        showmsg('�ÿγ�������', 1, $link);
    }
    $in['uid'] = intval($_SESSION['uid']);
    $in['course_id'] = $course_id;
    $in['price'] = $_POST['price'];
    $in['payment'] = "alipay";
    $in['state'] = $in['price'] > 0 ? 1 : 2;
    $in['price'] > 0 ? "" : $in['pay_time'] = time();
    if ($in['price'] > 0) {
        $in['state'] = 1;
    } else {
        $in['state'] = 2;
        $in['pay_time'] = time();
        $up['people_num'] = $course['people_num'] + 1;
        updatetable(table('online_course_info'), $up, " id='" . $course['id'] . "'");
    }
    $in['addtime'] = time();
    if ($in['uid'] > 0 && $in['course_id'] > 0) {
        $order_id = $db->insert("online_course_buy_log", $in);
    } else {
        $link[0]['text'] = "������ҳ";
        $link[0]['href'] = "/zhaokao";
        showmsg('��������', 1, $link);
    }
    if ($order_id > 0) {
        if ($in['price'] > 0) {
            header("Location: /zhaokao/kaoshi/order.php?oid=" . $order_id);
        } else {
            $link[0]['text'] = "������ҳ";
            $link[0]['href'] = "/zhaokao";
            $link[1]['text'] = "�鿴�γ�";
            $link[1]['href'] = "/user/personal/personal_user.php?act=online_course";
            showmsg('����ɹ�����ȴ����', 1, $link);
        }
    }
} elseif ($act == "pay_order") {
    $order_id = !empty($_GET['oid']) ? intval($_GET['oid']) : showmsg('�Ҳ�����Ӧ������');
    $url = $order_id > 0 ? "/zhaokao/kaoshi/order.php?act=show_order&oid=" . $order_id : "/zhaokao";
    if ($_SESSION['uid'] == '' || $_SESSION['username'] == '' || intval($_SESSION['uid']) === 0) {
        header("Location: /user/login.php?act_url=" . $url);
        exit();
    } elseif ($_SESSION['utype'] != '2') {
        $link[0]['text'] = "���ϵ�¼";
        $link[0]['href'] = "/user/login.php?act_url=" . $url;
        showmsg('�����ʵ�ҳ����Ҫ ���˻�Ա ��¼��', 1, $link);
    }
    $uid = intval($_SESSION['uid']);
    $payment = trim($_POST['payment']);
    $sql = "select * from " . table('online_course_buy_log') . " where uid = " . $uid . " and id = " . $order_id;
    $myorder = $db->getone($sql);
    if (empty($myorder)) {
        showmsg('�Ҳ�����Ӧ������');
    } else {
        $up_order['payment'] = $myorder['payment'] = $payment;
        updatetable(table('online_course_buy_log'), $up_order, " id='" . $order_id . "'");
    }
    $payment = get_payment_info($myorder['payment']);
    if (empty($payment)) {
        showmsg("֧����ʽ����", 0);
    }
    $fee = number_format(($amount / 100) * $payment['fee'], 1, '.', ''); //������
    $order['oid'] = $myorder['id']; //������
    $order['v_amount'] = $myorder['price'] + $fee;
    if ($myorder['payment_name'] != 'remittance') {//�����Ƿ�����֧����
        if (strstr($myorder['payment_name'], 'alipayapi-')) {
            $api_path = "alipayapi/";
            $payment['typename'] = "alipayapi";
            $respond_name = "alipay";
        } else {
            $respond_name = $payment['typename'];
        }
        $order['v_url'] = $_CFG['site_domain'] . $_CFG['site_dir'] . "include/payment/respond_" . $respond_name . "_zhaokao.php";
        require_once(QISHI_ROOT_PATH . "include/payment/" . $api_path . $payment['typename'] . ".php");
        $payment_form = get_code($order, $payment);
        if (empty($payment_form)) {
            showmsg("����֧����������", 0);
        }
        $payment_form = $payment_form . "<script>document.forms['alipaysubmit'].submit();</script>";
        echo $payment_form;
    }
}

function get_payment_info($typename, $name = false) {
    global $db;
    $sql = "select * from " . table('payment') . " where typename ='" . $typename . "' AND p_install='2' LIMIT 1";
    $val = $db->getone($sql);
    if ($name) {
        return $val['byname'];
    } else {
        return $val;
    }
}

function get_code_alipay($order_id = 0) {
    global $db;
    $order_id = $order_id > 0 ? intval($order_id) : showmsg('�Ҳ�����Ӧ������');
    $url = $order_id > 0 ? "/zhaokao/kaoshi/order.php?act=show_order&oid=" . $order_id : "/zhaokao";
    if ($_SESSION['uid'] == '' || $_SESSION['username'] == '' || intval($_SESSION['uid']) === 0) {
        header("Location: /user/login.php?act_url=" . $url);
        exit();
    } elseif ($_SESSION['utype'] != '2') {
        $link[0]['text'] = "���ϵ�¼";
        $link[0]['href'] = "/user/login.php?act_url=" . $url;
        showmsg('�����ʵ�ҳ����Ҫ ���˻�Ա ��¼��', 1, $link);
    }
    $uid = intval($_SESSION['uid']);
    $payment = 'alipay';
    $sql = "select * from " . table('online_course_buy_log') . " where uid = " . $uid . " and id = " . $order_id;
    $myorder = $db->getone($sql);
    if (empty($myorder)) {
        showmsg('�Ҳ�����Ӧ������');
    } else {
        $up_order['payment'] = $myorder['payment'] = $payment;
        updatetable(table('online_course_buy_log'), $up_order, " id='" . $order_id . "'");
    }
    $payment = get_payment_info($myorder['payment']);
    if (empty($payment)) {
        showmsg("֧����ʽ����", 0);
    }
    $fee = number_format(($amount / 100) * $payment['fee'], 1, '.', ''); //������
    $order['oid'] = $myorder['id'] . "-" . time(); //������
    $order['v_amount'] = $myorder['price'] + $fee;
    if ($myorder['payment_name'] != 'remittance') {//�����Ƿ�����֧����
        if (strstr($myorder['payment_name'], 'alipayapi-')) {
            $api_path = "alipayapi/";
            $payment['typename'] = "alipayapi";
            $respond_name = "alipay";
        } else {
            $respond_name = $payment['typename'];
        }
        $order['v_url'] = $_CFG['site_domain'] . $_CFG['site_dir'] . "include/payment/respond_" . $respond_name . "_zhaokao.php";
        require_once(QISHI_ROOT_PATH . "include/payment/" . $api_path . $payment['typename'] . ".php");
        $payment_form = get_code($order, $payment);
        if (empty($payment_form)) {
            showmsg("����֧����������", 0);
        }
        return $payment_form;
    }
}

?>