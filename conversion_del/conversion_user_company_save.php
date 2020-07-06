<?php

/*
 * 74cms 企业用户转换
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
set_time_limit(0);
define('IN_QISHI', true);
require_once('../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$t1 = exectime();
require_once('conversion_cngfig.php');
$id = intval($_GET['id']);

//var_dump($id);exit;

$sql = "select top 1 * from pH_Company_Base  where NCID = " . $id;
//var_dump($sql);exit;
$rs = ms_getone($sql);
;   //执行搜索语句
$sql = "select uid from " . table('members') . "  where username ='" . escape_str($rs['UserName']) . "' limit 1";
$user_result = $db->getone($sql);
//var_dump($user_result);exit;
if (!empty($user_result['uid'])) {
    $save_type = "update";
}
$pwd = $rs['Password'];
$timestamp = strtotime($rs['RegDate']);
$timestamp1 = $rs['LastDate'];
$timestamp1 = explode(' ', $timestamp1);
$timestamp1 = strtotime($timestamp1[0]);


$mobile = $rs['Phone'];
$online_ip = $rs['LastIp'];

$thisid = $rs['NCID'];

conversion_user_register(escape_str($rs['UserName']), $pwd, 1, escape_str($rs['Email']), $save_type);

function conversion_user_register($username, $password, $member_type = 0, $email, $save_type = "", $uc_reg = true) {
    global $db, $timestamp, $timestamp1, $_CFG, $online_ip, $QS_pwdhash, $mobile;
    $member_type = intval($member_type);
    $ck_username = get_user_inusername($username);
    $ck_email = get_user_inemail($email);
    if ($member_type == 0) {
        return -1;
    }
    $pwd_hash = randstr();
    $setsqlarr['username'] = $username;
    $setsqlarr['password'] = $password;
    //var_dump($password);exit;
    $setsqlarr['pwd_hash'] = $pwd_hash;
    $setsqlarr['email'] = $email;
    $setsqlarr['utype'] = intval($member_type);
    $setsqlarr['reg_time'] = $timestamp;
    $setsqlarr['last_login_time'] = $timestamp1;
    $setsqlarr['reg_ip'] = $online_ip;
    $setsqlarr['last_login_ip'] = $online_ip;
    $setsqlarr['mobile'] = $mobile;
    if ($_SESSION["openid"] && $_CFG['qq_apiopen'] == "1") {
        $setsqlarr['qq_openid'] = $_SESSION["openid"];
    }
    if ($save_type == "update") {
        $wheresqlarr = " username = '" . $username . "'";
    //var_dump($setsqlarr);
        updatetable(table('members'), $setsqlarr, $wheresqlarr);
        $sql = "select uid from " . table('members') . "  where username = '" . $username . "' limit 1";
        $member_uid = $db->getone($sql);
        $insert_id = $member_uid['uid'];
    } else {
        $insert_id = inserttable(table('members'), $setsqlarr, true);
    }
    if ($member_type == "1") {
        $sql = "select uid from " . table('members_points') . "  where uid ='" . $insert_id . "' limit 1";
        $p_has = $db->getone($sql);
        if (empty($p_has['uid'])) {
            $db->query("INSERT INTO " . table('members_points') . " (uid) VALUES ('" . $insert_id . "')");
        } else {
            $insert_id = $p_has['uid'];
        }
        $sql = "select uid from " . table('members_setmeal') . "  where uid ='" . $insert_id . "' limit 1";
        $s_has = $db->getone($sql);
        if (empty($s_has['uid'])) {
            $db->query("INSERT INTO " . table('members_setmeal') . " (uid) VALUES ('" . $insert_id . "')");
        } else {
            $insert_id = $s_has['uid'];
        }
        $points = get_cache('points_rule');
        if ($points['reg_points']['value'] > 0) {
            include_once(QISHI_ROOT_PATH . 'include/fun_company.php');
            report_deal($insert_id, $points['reg_points']['type'], $points['reg_points']['value']);
            $operator = $points['reg_points']['type'] == "1" ? "+" : "-";
            write_memberslog($insert_id, 1, 9001, $username, "新注册会员,({$operator}{$points['reg_points']['value']}),(剩余:{$points['reg_points']['value']})");
        }
        if ($_CFG['reg_service'] > 0) {
            include_once(QISHI_ROOT_PATH . 'include/fun_company.php');
            set_members_setmeal($insert_id, $_CFG['reg_service']);
            $setmeal = get_setmeal_one($_CFG['reg_service']);
            write_memberslog($insert_id, 1, 9002, $username, "注册会员系统自动赠送：{$setmeal['setmeal_name']}");
        }
    }

    //write_memberslog($insert_id,$member_type,1000,$username,"注册成为会员");
    return $insert_id;
}

?>