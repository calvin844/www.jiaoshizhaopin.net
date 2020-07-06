<?php

define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
require_once(dirname(__FILE__) . '/../include/fun_personal.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$vote_str = !empty($_POST['vote_str']) ? trim($_POST['vote_str']) : "";
$pid = !empty($_POST['pid']) ? intval($_POST['pid']) : 0;
if (empty($vote_str)) {
    exit('1');
}
if ($pid < 1) {
    exit('2');
}
$vote_arr = explode("-", $vote_str);
$vote_type = trim($vote_arr[0]);
$vote_count = intval($vote_arr[1]);
if (empty($vote_type) || $vote_count < 1) {
    exit('3');
}
$vote_time = time();
$vote_ip = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
$vote_ip = ($vote_ip) ? $vote_ip : $_SERVER["REMOTE_ADDR"];

if ($vote_type == 'love') {
    if (!empty($_COOKIE["vote_love_" . $pid])) {
        exit('4');
    } else {
        setcookie("vote_love_" . $pid, "1", $vote_time + 84600);
    }
    $vote_log['pid'] = $pid;
    $vote_log['ip'] = $vote_ip;
    $vote_log['count'] = $vote_count;
    $vote_log['addtime'] = $vote_time;
    $in_id = $db->insert('act_20170601_love_vote', $vote_log);
    if ($in_id) {
        $sql = "select sum(count) as total_love from " . table('act_20170601_love_vote') . " WHERE  pid = " . $pid;
        $total_love = $db->getone($sql);
        $total_love = $total_love['total_love'];
        $db->query("UPDATE " . table('act_20170601') . " SET `love_total` = " . $total_love . " WHERE id = " . $pid);
        exit('5');
    } else {
        exit('6');
    }
}
if ($vote_type == 'star') {
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
    $uid = intval($_SESSION['uid']);
    if (!empty($_COOKIE["vote_star_" . $pid])) {
        exit('7');
    } else {
        setcookie("vote_star_" . $pid, "1", $vote_time + 84600);
    }
    $sql = "select * from " . table('act_20170601_star_vote') . " WHERE uid = " . $uid . " AND  pid = " . $pid . " ORDER BY addtime desc LIMIT 1";
    $last_vote = $db->getone($sql);
    if (!empty($last_vote)) {
        $last_vote_time = strtotime(date('Y-m-d', $last_vote['addtime']));
        $now_vote_time = strtotime(date('Y-m-d', $vote_time));
        if (($now_vote_time - $last_vote_time) < 84600) {
            exit('8');
        }
    }
    if ($uid > 0 && $pid > 0 && !empty($vote_type)) {
        $vote_in['pid'] = $pid;
        $vote_in['uid'] = $uid;
        $vote_in['ip'] = $vote_ip;
        $vote_in['count'] = $vote_count * 2;
        $vote_in['addtime'] = $vote_time;
        $in_id = $db->insert('act_20170601_star_vote', $vote_in);
        if ($in_id) {
            $sql = "select sum(count) as total_star,count(*) as vote_count from " . table('act_20170601_star_vote') . " WHERE  pid = " . $pid;
            $total_star = $db->getone($sql);
            $total_star = round($total_star['total_star'] / $total_star['vote_count'], 1);
            $db->query("UPDATE " . table('act_20170601') . " SET `star_total` = " . $total_star . " WHERE id = " . $pid);
            exit('9');
        } else {
            exit('10');
        }
    }
}
?>