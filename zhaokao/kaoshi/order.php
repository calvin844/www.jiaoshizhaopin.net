<?php

/*
 * 74cms 下载简历
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
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
        $link[0]['text'] = "马上登录";
        $link[0]['href'] = "/user/login.php?act_url=" . $_SERVER['PHP_SELF'];
        showmsg('您访问的页面需要 个人会员 登录！', 1, $link);
    }
    $order_id = !empty($_GET['oid']) ? intval($_GET['oid']) : showmsg('找不到相应订单！');
    $uid = intval($_SESSION['uid']);
    $sql = "select * from " . table('online_course_buy_log') . " where uid = " . $uid . " and id = " . $order_id;
    $order = $db->getone($sql);
    if (empty($order)) {
        showmsg('找不到相应订单！');
    } else {
        $sql = "select * from " . table('online_course_info') . " where id = " . $order['course_id'];
        $order['course'] = $db->getone($sql);
    }
    $get_function = "get_code_" . $order['payment'];
    $order_form = $get_function($order['id']);
    $channel = array("url" => '/zhaokao', "name" => '招考');
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
        $link[0]['text'] = "马上登录";
        $link[0]['href'] = "/user/login.php?act_url=" . $url;
        showmsg('您访问的页面需要 个人会员 登录！', 1, $link);
    }

    $sql = "select * from " . table('online_course_info') . " where id=" . $course_id;
    $course = $db->getone($sql);
    if ($course['people_limit'] > 0 && (($course['people_num'] > $course['people_limit']) || ($course['people_num'] == $course['people_limit']))) {
        $link[0]['text'] = "查看其他课程";
        $link[0]['href'] = "/zhaokao";
        showmsg('该课程已满人', 1, $link);
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
        $link[0]['text'] = "返回首页";
        $link[0]['href'] = "/zhaokao";
        showmsg('参数错误', 1, $link);
    }
    if ($order_id > 0) {
        if ($in['price'] > 0) {
            header("Location: /zhaokao/kaoshi/order.php?oid=" . $order_id);
        } else {
            $link[0]['text'] = "返回首页";
            $link[0]['href'] = "/zhaokao";
            $link[1]['text'] = "查看课程";
            $link[1]['href'] = "/user/personal/personal_user.php?act=online_course";
            showmsg('购买成功，请等待审核', 1, $link);
        }
    }
} elseif ($act == "pay_order") {
    $order_id = !empty($_GET['oid']) ? intval($_GET['oid']) : showmsg('找不到相应订单！');
    $url = $order_id > 0 ? "/zhaokao/kaoshi/order.php?act=show_order&oid=" . $order_id : "/zhaokao";
    if ($_SESSION['uid'] == '' || $_SESSION['username'] == '' || intval($_SESSION['uid']) === 0) {
        header("Location: /user/login.php?act_url=" . $url);
        exit();
    } elseif ($_SESSION['utype'] != '2') {
        $link[0]['text'] = "马上登录";
        $link[0]['href'] = "/user/login.php?act_url=" . $url;
        showmsg('您访问的页面需要 个人会员 登录！', 1, $link);
    }
    $uid = intval($_SESSION['uid']);
    $payment = trim($_POST['payment']);
    $sql = "select * from " . table('online_course_buy_log') . " where uid = " . $uid . " and id = " . $order_id;
    $myorder = $db->getone($sql);
    if (empty($myorder)) {
        showmsg('找不到相应订单！');
    } else {
        $up_order['payment'] = $myorder['payment'] = $payment;
        updatetable(table('online_course_buy_log'), $up_order, " id='" . $order_id . "'");
    }
    $payment = get_payment_info($myorder['payment']);
    if (empty($payment)) {
        showmsg("支付方式错误！", 0);
    }
    $fee = number_format(($amount / 100) * $payment['fee'], 1, '.', ''); //手续费
    $order['oid'] = $myorder['id']; //订单号
    $order['v_amount'] = $myorder['price'] + $fee;
    if ($myorder['payment_name'] != 'remittance') {//假如是非线下支付，
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
            showmsg("在线支付参数错误！", 0);
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
    $order_id = $order_id > 0 ? intval($order_id) : showmsg('找不到相应订单！');
    $url = $order_id > 0 ? "/zhaokao/kaoshi/order.php?act=show_order&oid=" . $order_id : "/zhaokao";
    if ($_SESSION['uid'] == '' || $_SESSION['username'] == '' || intval($_SESSION['uid']) === 0) {
        header("Location: /user/login.php?act_url=" . $url);
        exit();
    } elseif ($_SESSION['utype'] != '2') {
        $link[0]['text'] = "马上登录";
        $link[0]['href'] = "/user/login.php?act_url=" . $url;
        showmsg('您访问的页面需要 个人会员 登录！', 1, $link);
    }
    $uid = intval($_SESSION['uid']);
    $payment = 'alipay';
    $sql = "select * from " . table('online_course_buy_log') . " where uid = " . $uid . " and id = " . $order_id;
    $myorder = $db->getone($sql);
    if (empty($myorder)) {
        showmsg('找不到相应订单！');
    } else {
        $up_order['payment'] = $myorder['payment'] = $payment;
        updatetable(table('online_course_buy_log'), $up_order, " id='" . $order_id . "'");
    }
    $payment = get_payment_info($myorder['payment']);
    if (empty($payment)) {
        showmsg("支付方式错误！", 0);
    }
    $fee = number_format(($amount / 100) * $payment['fee'], 1, '.', ''); //手续费
    $order['oid'] = $myorder['id'] . "-" . time(); //订单号
    $order['v_amount'] = $myorder['price'] + $fee;
    if ($myorder['payment_name'] != 'remittance') {//假如是非线下支付，
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
            showmsg("在线支付参数错误！", 0);
        }
        return $payment_form;
    }
}

?>