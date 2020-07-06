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
require_once(dirname(__FILE__) . '/../include/fun_personal.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$mem = new Memcache;
$mem->connect("localhost", 11111);
$act = empty($_GET['act']) ? "details" : $_GET['act'];
$admin = empty($_GET['admin']) ? 0 : intval($_GET['admin']);
if ($act == "details") {
    if (browser() == "mobile") {
        echo '<script language="javascript" type="text/javascript">window.location.href="http://m.jiaoshizhaopin.net/act0601/details?id=' . $_GET['id'] . '";</script>';
        exit;
    }
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id == 0) {
        $link[0]['text'] = "活动首页";
        $link[0]['href'] = "/act0601/index.php";
        showmsg('参数错误！', 1, $link);
    }
    $sql = "select * from " . table('act_20170601') . " where id = " . $id;
    $item = $db->getone($sql);
    if (empty($item)) {
        $link[0]['text'] = "活动首页";
        $link[0]['href'] = "/act0601/index.php";
        showmsg('对不起，未找到该作品！', 1, $link);
    }
    if ($item['audit'] == 1 && $admin != 1) {
        $link[0]['text'] = "活动首页";
        $link[0]['href'] = "/act0601/index.php";
        showmsg('该作品正在努力审核中，请稍后。', 1, $link);
    }
    if ($item['audit'] == 3) {
        $link[0]['text'] = "活动首页";
        $link[0]['href'] = "/act0601/index.php";
        showmsg('该作品审核未通过，请按要求重新上传。', 1, $link);
    }
    if ($item['audit'] == 4) {
        $link[0]['text'] = "活动首页";
        $link[0]['href'] = "/act0601/index.php";
        showmsg('该作品涉嫌违规行为，已取消票选资格，请维护公正的票选环境。', 1, $link);
    }
    $sql = "select * from " . table('resume') . " WHERE uid = " . $item['uid'] . " LIMIT 1";
    $leader_resume = $db->getone($sql);
    $sql = "select * from " . table('act_20170601_team') . " where leader = " . $item['uid'];
    $player_team = $db->getall($sql);
    foreach ($player_team as $pt) {
        $sql = "select * from " . table('resume') . " WHERE uid = " . $pt['uid'] . " LIMIT 1";
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
    $sql = "select * from " . table('act_20170601_comment') . " where pid = " . $item['id'] . " and audit = 2";
    $comment_tmp = $db->getall($sql);
    foreach ($comment_tmp as $ct) {
        $resume_basic = get_resume_basic(intval($ct['uid']));
        if ($resume_basic['display_name'] == "2") {
            $ct['fullname'] = "N" . str_pad($resume_basic['id'], 7, "0", STR_PAD_LEFT);
        } elseif ($resume_basic['display_name'] == "3") {
            $ct['fullname'] = cut_str($resume_basic['fullname'], 1, 0, "**");
        } else {
            $ct['fullname'] = $resume_basic['fullname'];
        }
        $ct['photo_img'] = $resume_basic['photo_img'];
        $ct['content'] = nl2br($ct['content']);
        $comment[] = $ct;
    }
    $uid = intval($_SESSION['uid']);
    $judges = 0;
    $star_show = 0;
    if ($uid > 0) {
        $sql = "select * from " . table('act_20170601_comment') . " where pid = " . $item['id'] . " and uid = " . $uid . " limit 1";
        $old_comment = $db->getone($sql);
        $sql = "select * from " . table('act_20170601_team') . " where uid = " . $uid;
        $team = $db->getone($sql);
        if (($team['leader'] == $item['uid']) && (empty($old_comment)) && $team['audit'] == 2) {
            $judges = 1;
        }
        $sql = "select * from " . table('act_20170601_team') . " where uid = " . $uid . " and audit = 2";
        $item_team = $db->getone($sql);
        if (!empty($item_team)) {
            $star_show = 1;
        }
        if (!empty($_COOKIE["vote_star_" . $pid])) {
            $star_show = 0;
        }
        $sql = "select * from " . table('act_20170601_star_vote') . " WHERE uid = " . $uid . " AND  pid = " . $item['id'] . " ORDER BY addtime desc LIMIT 1";
        $last_vote = $db->getone($sql);
        if (!empty($last_vote)) {
            $last_vote_time = strtotime(date('Y-m-d', $last_vote['addtime']));
            $now_vote_time = strtotime(date('Y-m-d', time()));
            if (($now_vote_time - $last_vote_time) < 84600) {
                $star_show = 0;
            }
        }
    }
    $db->query("UPDATE " . table('act_20170601') . " SET click=click+1 WHERE id=" . $item['id']);
    $sql = "select count(*) as star_count from " . table('act_20170601_star_vote') . " WHERE  pid = " . $item['id'];
    $star_count = $db->getone($sql);
    $star_count = $star_count['star_count'];
    $sql = "select count(*) as love_count from " . table('act_20170601_love_vote') . " WHERE  pid = " . $item['id'];
    $love_count = $db->getone($sql);
    $love_count = $love_count['love_count'];
    $smarty->assign('comment_list', $comment);
    $smarty->assign('star_count', $star_count);
    $smarty->assign('love_count', $love_count);
    $smarty->assign('leader_resume', $leader_resume);
    $smarty->assign('player_resume', $player_resume);
    $smarty->assign('uid', $uid);
    $smarty->assign('judges', $judges);
    $smarty->assign('star_show', $star_show);
    $smarty->assign('item', $item);
    $smarty->display('act0601/details.htm');
} elseif ($act == "judges_comment") {
    $uid = intval($_POST['uid']);
    $pid = intval($_POST['pid']);
    if ($uid == 0 || $pid == 0) {
        $link[0]['text'] = "活动首页";
        $link[0]['href'] = "/act0601/index.php";
        showmsg('非法操作！', 1, $link);
    }
    $sql = "select * from " . table('act_20170601') . " where id = " . $pid;
    $item = $db->getone($sql);
    $sql = "select * from " . table('act_20170601_team') . " where uid = " . $uid;
    $team = $db->getone($sql);
    if ($team['leader'] != $item['uid']) {
        $link[0]['text'] = "活动首页";
        $link[0]['href'] = "/act0601/index.php";
        showmsg('您不能点评该作品！', 1, $link);
    } else {
        $in_comment['uid'] = $uid;
        $in_comment['pid'] = $pid;
        $in_comment['content'] = trim($_POST['judges_comment']);
        $in_comment['addtime'] = time();
        $comment_id = $db->insert("act_20170601_comment", $in_comment);
        if ($comment_id > 0) {
            $link[0]['text'] = "活动首页";
            $link[0]['href'] = "/act0601/index.php";
            $link[1]['text'] = "作品详情";
            $link[1]['href'] = "/act0601/details.php?id=" . $pid;
            showmsg('点评成功！', 1, $link);
        }
    }
}
?>