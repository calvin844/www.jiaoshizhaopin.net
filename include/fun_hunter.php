<?php

/*
 * 74cms 猎头会员中心函数
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

function get_user_limit_points($uid) {
    global $db;
    $uid = intval($uid);
    $points = $db->getone("select * from " . table('members_points_limit') . " where uid ='{$uid}' LIMIT 1");
    if(!empty($points['id'])){
        $up_limit['points'] = $points['endtime'] > time() ? $points['points'] : 0;
        updatetable(table('members_points_limit'), $up_limit, " uid='{$uid}' LIMIT 1 ");
        $points['points'] = $up_limit['points'];
    }else{
        $points = array();
    }
    return $points;
}

function set_members_limit_points($uid, $i_type = 1, $points = 0, $days = 0) {
    global $db, $timestamp;
    $p = 0;
    $points = intval($points);
    $uid = intval($uid);
    $limit_info = get_user_limit_points($uid);
    $up_limit = array();
    if (!empty($limit_info['id'])) {
        if ($limit_info['endtime'] > 0) {
            if ($i_type == 1) {
                $up_limit['points'] = $limit_info['points'] + $points;
            } elseif ($i_type == 2) {
                if ($limit_info['endtime'] > time() && $limit_info['endtime'] > 0) {
                    $up_limit['points'] = $limit_info['points'] - $points;
                    $p = $up_limit['points'] < 0 ? abs($up_limit['points']) : 0;
                    $up_limit['points'] = $up_limit['points'] < 0 ? 0 : $up_limit['points'];
                }
            }
            $up_limit['endtime'] = $limit_info['endtime'] > time() ? $limit_info['endtime'] + ($days * 86400) : time() + ($days * 86400);
            $up_limit['points'] = $up_limit['endtime'] > time() ? $up_limit['points'] : 0;
            updatetable(table('members_points_limit'), $up_limit, " uid='{$uid}' LIMIT 1 ");
        } else {
            $up_limit['points'] = 0;
            updatetable(table('members_points_limit'), $up_limit, " uid='{$uid}' LIMIT 1 ");
        }
    } elseif($days > 0) {
        $up_limit['uid'] = $uid;
        $up_limit['points'] = $points;
        $up_limit['addtime'] = time();
        $up_limit['endtime'] = time() + ($days * 86400);
        inserttable(table('members_points_limit'), $up_limit);
    }
    return $p;
}

function report_deal($uid, $i_type = 1, $points = 0) {
    global $db, $timestamp;
    $points = intval($points);
    $uid = intval($uid);
    if ($i_type == 2) {
        $points = set_members_limit_points($uid, 2, $points);
    }
    $points_val = get_user_points($uid);
    pay_test_log("更新状态（1为增加积分，2为减少积分）：" . $i_type);
    if ($i_type == 1) {
        $points_val = $points_val + $points;
    }
    if ($i_type == 2) {
        $points_val = $points_val - $points;
        $points_val = $points_val < 0 ? 0 : $points_val;
    }
    pay_test_log("更新后积分为：" . $points_val);
    $sql = "UPDATE " . table('members_points') . " SET points= '{$points_val}' WHERE uid='{$uid}' LIMIT 1";
    pay_test_log("更新用户积分语句：" . $sql);
    if (!$db->query($sql)) {
        pay_test_log("更新用户积分语句失败！");
        return false;
    }
    return true;
}

function get_user_points($uid) {
    global $db;
    $uid = intval($uid);
    $points = $db->getone("select points from " . table('members_points') . " where uid ='{$uid}' LIMIT 1");
    return $points['points'];
}

function set_members_setmeal($uid, $setmealid) {
    global $db, $timestamp;
    $setmeal = $db->getone("select * from " . table('hunter_setmeal') . " WHERE id = " . intval($setmealid) . " AND display=1 LIMIT 1");
    if (empty($setmeal))
        return false;
    $setsqlarr['effective'] = 1;
    $setsqlarr['setmeal_id'] = $setmeal['id'];
    $setsqlarr['setmeal_name'] = $setmeal['setmeal_name'];
    $setsqlarr['days'] = $setmeal['days'];
    $setsqlarr['starttime'] = $timestamp;
    if ($setmeal['days'] > 0) {
        $setsqlarr['endtime'] = strtotime("" . $setmeal['days'] . " days");
    } else {
        $setsqlarr['endtime'] = "0";
    }
    $setsqlarr['expense'] = $setmeal['expense'];
    $setsqlarr['jobs_add'] = $setmeal['jobs_add'];
    $setsqlarr['download_resume_talent'] = $setmeal['download_resume_talent'];
    $setsqlarr['interview_manager'] = $setmeal['interview_manager'];
    $setsqlarr['added'] = $setmeal['added'];
    $setsqlarr['hunter_refresh_jobs_space'] = $setmeal['hunter_refresh_jobs_space'];
    $setsqlarr['hunter_refresh_jobs_time'] = $setmeal['hunter_refresh_jobs_time'];
    if (!updatetable(table('members_hunter_setmeal'), $setsqlarr, " uid='{$uid}'"))
        return false;
    $setmeal_jobs['setmeal_deadline'] = $setsqlarr['endtime'];
    $setmeal_jobs['setmeal_id'] = $setsqlarr['setmeal_id'];
    $setmeal_jobs['setmeal_name'] = $setsqlarr['setmeal_name'];
    if (!updatetable(table('hunter_jobs'), $setmeal_jobs, " uid='{$uid}' AND add_mode='2' "))
        return false;
    return true;
}

function get_setmeal_one($id) {
    global $db;
    $id = intval($id);
    $sql = "select * from " . table('hunter_setmeal') . "  WHERE id='{$id}'  LIMIT 1";
    return $db->getone($sql);
}

function get_concern_id($uid) {
    global $db;
    $uid = intval($uid);
    $info = $db->getall("select id,category,subclass from " . table('hunter_jobs') . " where uid='{$uid}' LIMIT 10");
    if (!empty($info) && is_array($info)) {
        foreach ($info as $s) {
            $str[] = $s['category'];
        }
        return implode("-", array_unique($str));
    }
    return "";
}

function get_hunter($uid) {
    global $db;
    $sql = "select * from " . table('hunter_profile') . " where uid=" . intval($uid) . " LIMIT 1 ";
    $data = $db->getone($sql);
    if ($data) {
        $arr = explode('-', $data['companytelephone']);
        $data['code'] = $arr[0];
        $data['companytelephone'] = $arr[1];
        $data['workyears'] = date('Y') - $data['worktime_start'];
    }
    return $data;
}

function get_userprofile($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('members_info') . " where uid ='{$uid}' LIMIT 1";
    return $db->getone($sql);
}

function get_user_report($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT * FROM " . table('members_log') . " " . $get_sql . " ORDER BY log_id DESC " . $limit);
    while ($row = $db->fetch_array($result)) {
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_points_rule() {
    global $db;
    $sql = "select * from " . table('members_points_rule') . " WHERE utype='3' ORDER BY id asc";
    return $db->getall($sql);
}

function get_payment() {
    global $db;
    $sql = "select * from " . table('payment') . " where p_install='2' ORDER BY listorder desc";
    $list = $db->getall($sql);
    return $list;
}

//获取指点会员订单
function get_user_order($uid, $is_paid) {
    global $db;
    $sql = "select * from " . table('order') . " WHERE uid=" . intval($uid) . " AND  is_paid='" . intval($is_paid) . "' ORDER BY id desc";
    return $db->getall($sql);
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

//增加订单
function add_order($uid, $oid, $amount, $payment_name, $description, $addtime, $points = '', $setmeal = '', $utype = '3') {
    global $db;
    $setsqlarr['uid'] = intval($uid);
    $setsqlarr['oid'] = $oid;
    $setsqlarr['amount'] = $amount;
    $setsqlarr['payment_name'] = $payment_name;
    $setsqlarr['description'] = $description;
    $setsqlarr['addtime'] = $addtime;
    $setsqlarr['points'] = $points;
    $setsqlarr['setmeal'] = $setmeal;
    $setsqlarr['utype'] = $utype;
    write_memberslog($uid, 3, 3201, $_SESSION['username'], "添加订单，编号{$oid}，金额{$amount}元");
    $userinfo = get_user_info($uid);
    //sendemail
    $mailconfig = get_cache('mailconfig');
    if ($mailconfig['set_order'] == "1" && $userinfo['email_audit'] == "1" && $amount > 0) {
        global $_CFG;
        $paymenttpye = get_payment_info($payment_name);
        dfopen("{$_CFG['site_domain']}{$_CFG['site_dir']}plus/asyn_mail.php?uid={$uid}&key=" . asyn_userkey($uid) . "&act=set_order&oid={$oid}&amount={$amount}&paymenttpye={$paymenttpye['byname']}");
    }
    //sendemail
    //sms
    $sms = get_cache('sms_config');
    if ($sms['open'] == "1" && $sms['set_order'] == "1" && $userinfo['mobile_audit'] == "1" && $amount > 0) {
        global $_CFG;
        $paymenttpye = get_payment_info($payment_name);
        dfopen("{$_CFG['site_domain']}{$_CFG['site_dir']}plus/asyn_sms.php?uid={$uid}&key=" . asyn_userkey($uid) . "&act=set_order&oid={$oid}&amount={$amount}&paymenttpye={$paymenttpye['byname']}");
    }
    //sms
    return inserttable(table('order'), $setsqlarr, true);
}

//获取单条订单
function get_order_one($uid, $id) {
    global $db;
    $sql = "select * from " . table('order') . " where id =" . intval($id) . " AND uid = " . intval($uid) . "  AND is_paid =1  LIMIT 1 ";
    return $db->getone($sql);
}

//获取充值记录列表
function get_order_all($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT " . $offset . ',' . $perpage;
    }
    $result = $db->query("SELECT * FROM " . table('order') . " " . $get_sql . " ORDER BY id DESC " . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['payment_name'] = get_payment_info($row['payment_name'], true);
        $row_arr[] = $row;
    }
    return $row_arr;
}

//取消订单
function del_order($uid, $id) {
    global $db;
    write_memberslog($_SESSION['uid'], 3, 3202, $_SESSION['username'], "取消订单，订单id{$id}");
    return $db->query("Delete from " . table('order') . " WHERE id='" . intval($id) . "' AND uid=" . intval($uid) . " AND is_paid=1  LIMIT 1 ");
}

function get_setmeal($apply = false) {
    global $db;
    if ($apply) {
        $wheresql = " AND apply=1";
    }
    $sql = "select * from " . table('hunter_setmeal') . " WHERE display=1 {$wheresql} ORDER BY show_order desc";
    return $db->getall($sql);
}

//付款后开通
function order_paid($v_oid) {
    global $db, $timestamp, $_CFG;
    $order = $db->getone("select * from " . table('order') . " WHERE oid ='{$v_oid}' AND is_paid= '1' LIMIT 1 ");
    if ($order) {
        $user = get_user_info($order['uid']);
        $sql = "UPDATE " . table('order') . " SET is_paid= '2',payment_time='{$timestamp}' WHERE oid='{$v_oid}' LIMIT 1 ";
        if (!$db->query($sql))
            return false;
        if ($order['amount'] == '0.00') {
            $ismoney = 1;
        } else {
            $ismoney = 2;
        }
        if ($order['points'] > 0) {
            report_deal($order['uid'], 1, $order['points']);
            $user_points = get_user_points($order['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $notes = date('Y-m-d H:i', time()) . "通过：" . get_payment_info($order['payment_name'], true) . " 成功充值 " . $order['amount'] . "元，(+{$order['points']})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points}),订单:{$v_oid}";
            write_memberslog($order['uid'], 3, 9201, $user['username'], $notes);
            //会员套餐变更记录。会员购买成功。2表示：会员自己购买
            write_setmeallog($order['uid'], $user['username'], $notes, 2, $order['amount'], $ismoney, 1, 3);
        } elseif ($order['setmeal'] > 0) {
            set_members_setmeal($order['uid'], $order['setmeal']);
            $setmeal = get_setmeal_one($order['setmeal']);
            $notes = date('Y-m-d H:i', time()) . "通过：" . get_payment_info($order['payment_name'], true) . " 成功充值 " . $order['amount'] . "元并开通{$setmeal['setmeal_name']}";
            write_memberslog($order['uid'], 3, 9202, $user['username'], $notes);
            //会员套餐变更记录。会员购买成功。2表示：会员自己购买
            write_setmeallog($order['uid'], $user['username'], $notes, 2, $order['amount'], $ismoney, 2, 1, 3);
        }
        //sendemail
        $mailconfig = get_cache('mailconfig');
        if ($mailconfig['set_payment'] == "1" && $user['email_audit'] == "1" && $order['amount'] > 0) {
            dfopen("{$_CFG['site_domain']}{$_CFG['site_dir']}plus/asyn_mail.php?uid={$order['uid']}&key=" . asyn_userkey($order['uid']) . "&act=set_payment");
        }
        //sendemail
        //sms
        $sms = get_cache('sms_config');
        if ($sms['open'] == "1" && $sms['set_payment'] == "1" && $user['mobile_audit'] == "1" && $order['amount'] > 0) {
            dfopen("{$_CFG['site_domain']}{$_CFG['site_dir']}plus/asyn_sms.php?uid={$order['uid']}&key=" . asyn_userkey($order['uid']) . "&act=set_payment");
        }
        //sms
        return true;
    }
    return true;
}

function get_user_setmeal($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('members_hunter_setmeal') . "  WHERE uid='{$uid}' AND  effective=1 LIMIT 1";
    return $db->getone($sql);
}

function action_user_setmeal($uid, $action) {
    global $db;
    $sql = "update " . table('members_hunter_setmeal') . " set `" . $action . "`=" . $action . "-1  WHERE uid=" . intval($uid) . "  AND  effective=1 LIMIT 1";
    return $db->query($sql);
}

function get_hunterjobs($offset, $perpage, $get_sql = '', $countapply = false) {
    global $db, $timestamp;
    $row_arr = array();
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT {$offset},{$perpage}";
    }
    $result = $db->query($get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['jobs_name_'] = $row['jobs_name'];
        $row['jobs_name'] = cut_str($row['jobs_name'], 10, 0, "...");
        $row['jobs_url'] = url_rewrite('QS_hunter_jobsshow', array('id' => $row['id']), false);
        if ($row['audit'] == 3) {
            $row['status'] = 4;
            $row['status_cn'] = '未通过';
        } elseif ($row['audit'] == 2) {
            $row['status'] = 3;
            $row['status_cn'] = '审核中';
        } elseif ($row['deadline'] < time()) {
            $row['status'] = 5;
            $row['status_cn'] = '已过期';
        } elseif ($row['display'] == 2) {
            $row['status'] = 2;
            $row['status_cn'] = '暂停中';
        } else {
            $row['status'] = 1;
            $row['status_cn'] = '发布中';
        }
        $row_arr[] = $row;
    }
    return $row_arr;
}

function refresh_jobs($id, $uid) {
    global $db;
    $uid = intval($uid);
    $utype = intval($_SESSION['utype']);
    if (!is_array($id))
        $id = array($id);
    $time = time();
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('hunter_profile') . "  SET refreshtime='{$time}' WHERE uid='{$uid}' LIMIT 1 "))
            return false;
        if (!$db->query("update  " . table('hunter_jobs') . "  SET refreshtime='{$time}' WHERE uid='{$uid}' and utype='{$utype}'"))
            return false;
        return true;
    }
    return false;
}

//删除职位
function del_jobs($del_id, $uid) {
    global $db;
    $return = 0;
    $uidsql = " AND uid=" . intval($uid) . "";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("Delete from " . table('hunter_jobs') . " WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        $return = $return + $db->affected_rows();
        write_memberslog($_SESSION['uid'], 3, 8505, $_SESSION['username'], "删除职位({$sqlin})");
    }
    return $return;
}

//激活或者暂停职位
function activate_jobs($idarr, $display, $uid) {
    global $db;
    $display = intval($display);
    $uid = intval($uid);
    $uidsql = " AND uid='{$uid}'";
    if (!is_array($idarr))
        $idarr = array($idarr);
    $sqlin = implode(",", $idarr);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update " . table('hunter_jobs') . "  SET display='{$display}' WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        write_memberslog($_SESSION['uid'], 3, 8506, $_SESSION['username'], "将职位激活状态设为:{$display},职位ID为：{$sqlin}");
        return true;
    }
    return false;
}

//获取单条职位
function get_jobs_one($id, $uid = '') {
    global $db, $timestamp;
    $id = intval($id);
    if (!empty($uid))
        $wheresql = " AND uid=" . intval($uid);
    $val = $db->getone("select * from " . table('hunter_jobs') . " where id='{$id}' {$wheresql} LIMIT 1");
    if (empty($val))
        return false;
    $val['deadline_days'] = ($val['deadline'] - $timestamp) > 0 ? "距到期时间还有<strong style=\"color:#FF0000\">" . sub_day($val['deadline'], $timestamp) . "</strong>" : "<span style=\"color:#FF6600\">目前已过期</span>";
    return $val;
}

function get_pms($offset, $perpage, $get_sql = '') {
    global $db;
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT {$offset},{$perpage}";
    }
    $result = $db->query($get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['message'] = cut_str($row['message'], 100, 0, "...");
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_pms_one($pmid, $uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select p.* from " . table('pms') . " AS p  LEFT JOIN  " . table('members') . " AS i  ON p.msgfromuid=i.uid WHERE p.pmid='{$pmid}' AND (p.msgfromuid='{$uid}' OR p.msgtouid='{$uid}') LIMIT 1";
    return $db->getone($sql);
}

function get_pms_reply($pmid) {
    global $db;
    $pmid = intval($pmid);
    $sql = "select r.* from " . table('pms_reply') . " AS r  LEFT JOIN  " . table('members') . " AS i  ON  r.replyuid=i.uid WHERE r.pmid='{$pmid}' ORDER BY r.rid ASC";
    $list = $db->getall($sql);
    return $list;
}

function get_buddy($offset, $perpage, $get_sql = '') {
    global $db;
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT {$offset},{$perpage}";
    }
    $result = $db->query($get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row_arr[] = $row;
    }
    return $row_arr;
}

function set_user_status($status, $uid) {
    global $db;
    $status = intval($status);
    $uid = intval($uid);
    if (!$db->query("UPDATE " . table('members') . " SET status= {$status} WHERE uid={$uid} LIMIT 1"))
        return false;
    write_memberslog($_SESSION['uid'], 3, 1003, $_SESSION['username'], "修改帐号状态");
    return true;
}

function get_user_info($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('members') . " where uid = '{$uid}' LIMIT 1";
    return $db->getone($sql);
}

//把经理人简历添加到已下载中
function add_hun_down_talent_resume($resume_id, $user_uid, $resume_uid, $resume_name) {
    global $db, $timestamp;
    $resume_id = intval($resume_id);
    $user_uid = intval($user_uid);
    $resume_uid = intval($resume_uid);
    $resume_name = trim($resume_name);
    $hunter = get_hunter($user_uid);
    $sql = "INSERT INTO " . table('user_down_talent_resume') . " (resume_id,resume_uid,resume_name,user_uid,hunter_name,hunter_id,utype,down_addtime) VALUES ('{$resume_id}','{$resume_uid}','{$resume_name}','{$user_uid}','{$hunter['huntername']}','{$hunter['id']}','3','{$timestamp}')";
    return $db->query($sql);
}

function get_hun_audit_jobs($uid) {
    global $db, $timestamp, $_CFG;
    $uid = intval($uid);
    if ($_CFG['operation_hunter_mode'] == '1') {
        return $db->getall("select id from " . table('hunter_jobs') . " WHERE uid={$uid} and audit=1 and display=1 and deadline>{$timestamp} and add_mode=1");
    } elseif ($_CFG['operation_hunter_mode'] == '2') {
        return $db->getall("select id from " . table('hunter_jobs') . " WHERE uid={$uid} and audit=1 and display=1 and deadline>{$timestamp} AND add_mode=2 AND setmeal_id>0 AND (setmeal_deadline>{$timestamp} OR setmeal_deadline=0)");
    }
}

function check_hun_down_talent_resumeid($resume_id, $user_uid) {
    global $db;
    $user_uid = intval($user_uid);
    $resume_id = intval($resume_id);
    $sql = "select did from " . table('user_down_talent_resume') . " WHERE user_uid = '{$user_uid}' AND resume_id='{$resume_id}' LIMIT 1";
    $info = $db->getone($sql);
    if (empty($info)) {
        return false;
    } else {
        return true;
    }
}

function get_manager_resume_basic($id) {
    global $db;
    $id = intval($id);
    $val = $db->getone("select * from " . table('manager_resume') . " where id='{$id}' LIMIT 1 ");
    if ($val['display_name'] == "2") {
        $val['fullname'] = "N" . str_pad($val['id'], 7, "0", STR_PAD_LEFT);
    } elseif ($val['display_name'] == "3") {
        $val['fullname'] = cut_str($val['fullname'], 1, 0, "**");
    }
    return $val;
}

function get_down_manager_resume($offset, $perpage, $get_sql = '') {
    global $db;
    $limit = " LIMIT " . intval($offset) . ',' . intval($perpage);
    $selectstr = " d.*,r.subsite_id,r.sex_cn,r.fullname,r.display_name,r.experience_cn,r.district_cn,r.education_cn,r.intention_jobs,r.addtime,r.refreshtime ";
    $result = $db->query("SELECT " . $selectstr . " FROM " . table('user_down_talent_resume') . " as d {$get_sql} AND r.talent=2 ORDER BY d.down_addtime DESC " . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['resume_url'] = url_rewrite('QS_resumeshow', array('id' => $row['resume_id']), true, $row['subsite_id']);
        $row['intention_jobs'] = cut_str($row['intention_jobs'], 30, 0, "...");
        if ($row['display_name'] == "2") {
            $row['fullname'] = "N" . str_pad($row['resume_id'], 7, "0", STR_PAD_LEFT);
        } elseif ($row['display_name'] == "3") {
            $row['fullname'] = cut_str($row['fullname'], 1, 0, "**");
        }
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_down_manager($del_id, $uid) {
    global $db;
    $uid = intval($uid);
    $uidsql = " AND user_uid='{$uid}'";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    $return = 0;
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("Delete from " . table('user_down_manager_resume') . " WHERE did IN ({$sqlin}) {$uidsql}"))
            return false;
        $return = $return + $db->affected_rows();
        write_memberslog($_SESSION['uid'], $_SESSION['utype'], 4002, $_SESSION['username'], "删除经理人简历下载记录({$sqlin})");
        return $return;
    }
}

function get_subsite_list() {
    global $db;
    $sql = "select * from " . table('subsite');
    return $db->getall($sql);
}

?>