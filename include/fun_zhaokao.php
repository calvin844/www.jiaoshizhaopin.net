<?php

/*
 * 74cms 招考课程函数
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if (!defined('IN_QISHI')) {
    die('Access Denied!');
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

function pay_test_log($note) {
    $uid = $_SESSION['uid'];
    $time = date("Y-m-d H:i:s", time());
    $from = '';
    if (isset($_SERVER['HTTP_REFERER'])) {
        $from = $_SERVER['HTTP_REFERER'];
    }
    $myfile = '/data2/log/jiaoshizhaopin_pay_log.txt';
    $str = "uid='" . $uid . "' from='" . $from . "' time='" . $time . "' note=" . $note . " \r\n";
    $file_pointer = fopen($myfile, "a");
    fwrite($file_pointer, $str);
    fclose($file_pointer);
}

//付款后开通
function order_paid($v_oid) {
    global $db, $timestamp, $_CFG;
    $v_oid = explode("-", $v_oid);
    $v_oid = $v_oid[0];
    $order = $db->getone("select * from " . table('online_course_buy_log') . " WHERE id ='{$v_oid}' AND state= '1' LIMIT 1 ");
    if ($order) {
        $sql = "UPDATE " . table('online_course_info') . " SET people_num= people_num+1 WHERE id='" . $v_oid . "' LIMIT 1 ";
        $db->query($sql);
        $sql = "UPDATE " . table('online_course_buy_log') . " SET state= '2',pay_time='" . time() . "' WHERE id='" . $v_oid . "' LIMIT 1 ";
        if (!$db->query($sql)) {
            return false;
        }
        return true;
    } else {
        return false;
    }
}

?>