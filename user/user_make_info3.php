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
require_once(dirname(__FILE__) . '/personal/personal_common.php');
$act = empty($_GET['act']) ? "form" : $_GET['act'];
if ($act == "form") {
    $smarty->display('user/make_info3.htm');
} elseif ($act == "save") {
    /*
    $up['uid'] = $_SESSION['uid'];
    $education_arr = explode("-", trim($_POST['education']));
    $up['education'] = $education_arr[0];
    $up['education_cn'] = $education_arr[1];
    $experience_arr = explode("||", trim($_POST['experience']));
    $up['experience'] = $experience_arr[0];
    $up['experience_cn'] = $experience_arr[1];
    !empty($_POST['specialty']) ? $up['specialty'] = trim($_POST['specialty']) : "";
    updatetable(table('resume'), $up, " uid='" . $up['uid'] . "'");
    $sql = "select * from " . table('resume') . " where uid = " . $_SESSION['uid'];
    $resume = $db->getone($sql);
    check_resume($_SESSION['uid'], $resume['id']);

    $infoarr['education'] = $up['education'];
    $infoarr['education_cn'] = $up['education_cn'];
    $infoarr['experience'] = $up['experience'];
    $infoarr['experience_cn'] = $up['experience_cn'];

    $infoarr['uid'] = intval($_SESSION['uid']);
    header("Location: /user/personal/personal_index.php");
     * 
     */
}
?>