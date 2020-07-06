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
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$act = empty($_GET['act']) ? "share" : $_GET['act'];
if ($_SESSION['uid'] == '' || $_SESSION['username'] == '' || intval($_SESSION['uid']) === 0) {
    $query_string = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : "";
    header("Location: /user/login.php?act_url=" . $_SERVER['PHP_SELF'] . $query_string);
    exit();
} elseif ($_SESSION['utype'] != '2') {
    $query_string = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : "";
    $link[0]['text'] = "马上登录";
    $link[0]['href'] = "/user/login.php?act_url=" . $_SERVER['PHP_SELF'] . $query_string;
    showmsg('您访问的页面需要 个人会员 登录！', 1, $link);
}
if ($act == "share") {
    if (browser() == "mobile") {
        echo '<script language="javascript" type="text/javascript">window.location.href="http://m.jiaoshizhaopin.net/act0601/share";</script>';
        exit;
    }
    $user_resume = array();
    $item = array();
    $user_comment = array();
    $user_team = array();
    $player_resume = array();
    $uid = intval($_SESSION['uid']);
    $sql = "select * from " . table('act_20170601_team') . " where leader = " . $uid;
    $user_team = $db->getall($sql);
    foreach ($user_team as $at) {
        $sql = "select * from " . table('resume') . " WHERE uid = " . $at['uid'] . " LIMIT 1";
        $player_resume_tmp = $db->getone($sql);
        if ($player_resume_tmp['display_name'] == "2") {
            $player_resume_tmp['fullname'] = "N" . str_pad($player_resume_tmp['id'], 7, "0", STR_PAD_LEFT);
        } elseif ($player_resume_tmp['display_name'] == "3") {
            $player_resume_tmp['fullname'] = cut_str($player_resume_tmp['fullname'], 1, 0, "**");
        } else {
            $player_resume_tmp['fullname'] = $player_resume_tmp['fullname'];
        }
        $player_resume[] = $player_resume_tmp;
    }
    $player_num = count($player_resume);
    $empty_num = 10 - $player_num;
    $sql = "select * from " . table('resume') . " WHERE uid = " . $uid . " LIMIT 1";
    $user_resume = $db->getone($sql);
    $sql = "select * from " . table('act_20170601') . " where uid = " . $uid;
    $item = $db->getone($sql);
    $sql = "select * from " . table('act_20170601_comment') . " where uid = " . $uid;
    $comment_arr = $db->getall($sql);
    foreach ($comment_arr as $ca) {
        $sql = "select * from " . table('act_20170601') . " where id = " . $ca['pid'];
        $c_item = $db->getone($sql);
        $sql = "select * from " . table('resume') . " WHERE uid = " . $c_item['uid'] . " LIMIT 1";
        $item_resume = $db->getone($sql);
        if ($item_resume['display_name'] == "2") {
            $item_resume['fullname'] = "N" . str_pad($item_resume['id'], 7, "0", STR_PAD_LEFT);
        } elseif ($item_resume['display_name'] == "3") {
            $item_resume['fullname'] = cut_str($item_resume['fullname'], 1, 0, "**");
        } else {
            $item_resume['fullname'] = $item_resume['fullname'];
        }
        $c_item['fullname'] = $item_resume['fullname'];
        $ca['item'] = $c_item;
        $user_comment[] = $ca;
    }
    $copy_url = "http://" . $_SERVER['HTTP_HOST'] . "/act0601/user.php?act=make_info-" . $user_team[0]['id'];
    $smarty->assign('copy_url', $copy_url);
    $smarty->assign('user_resume', $user_resume);
    $smarty->assign('user_comment', $user_comment);
    $smarty->assign('user_team', $user_team);
    $smarty->assign('item', $item);
    $smarty->assign('uid', $uid);
    $smarty->assign('player_resume', $player_resume);
    $smarty->assign('player_num', $player_num);
    $smarty->assign('empty_num', $empty_num);
    $smarty->display('act0601/share.htm');
} elseif ($act == "share_index") {
    $team_id = !empty($_GET['team_id']) ? intval($_GET['team_id']) : 0;
    $sql = "select * from " . table('act_20170601_team') . " where id = " . $team_id;
    $team = $db->getone($sql);
    if (empty($team)) {
        $link[0]['text'] = "参加活动";
        $link[0]['href'] = "/act0601/index.php";
        showmsg('参数有误！', 1, $link);
    }
    $sql = "select * from " . table('act_20170601_team') . " where id = " . $team_id;
    $team = $db->getone($sql);
    $sql = "select * from " . table('act_20170601') . " where uid = " . $team['leader'];
    $item = $db->getone($sql);
    $smarty->assign('team', $team);
    $smarty->assign('item', $item);
    $smarty->display('act0601/share_index.htm');
}
?>