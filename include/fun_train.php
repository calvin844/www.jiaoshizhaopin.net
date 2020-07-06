<?php

/*
 * 74cms 培训机构会员中心函数
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

function get_user_points($uid) {
    global $db;
    $uid = intval($uid);
    $points = $db->getone("select points from " . table('members_points') . " where uid ='{$uid}' LIMIT 1");
    return $points['points'];
}

function get_user_setmeal($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('members_train_setmeal') . "  WHERE uid='{$uid}' AND  effective=1 LIMIT 1";
    return $db->getone($sql);
}

function get_train_news($offset, $perpage, $uid) {
    global $db;
    $uid = intval($uid);
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT {$offset},{$perpage}";
    }
    $result = $db->query("select * from " . table('train_news') . " WHERE uid ='{$uid}'  ORDER BY `order` DESC,`id` DESC {$limit}");
    while ($row = $db->fetch_array($result)) {
        $row['news_url'] = url_rewrite('QS_train_newsshow', array('id' => $row['id']));
        $row['title'] = cut_str($row['title'], 20, 0, "...");
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_train_news($del_id, $uid) {
    global $db;
    $uidsql = " AND uid=" . intval($uid) . "";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('train_news') . " WHERE id IN ({$sqlin}) {$uidsql}"))
        return false;
    return $db->affected_rows();
    ;
}

function get_train_img($offset, $perpage, $uid) {
    global $db;
    $uid = intval($uid);
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT {$offset},{$perpage}";
    }
    $result = $db->query("select * from " . table('train_img') . " WHERE uid ='{$uid}'  ORDER BY `id` DESC {$limit}");
    while ($row = $db->fetch_array($result)) {
        $row['title'] = cut_str($row['title'], 15, 0, "...");
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_userprofile($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('members_info') . " where uid ='{$uid}' LIMIT 1";
    return $db->getone($sql);
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

function get_points_rule() {
    global $db;
    $sql = "select * from " . table('members_points_rule') . " WHERE utype='4'  ORDER BY id asc";
    return $db->getall($sql);
}

function get_payment() {
    global $db;
    $sql = "select * from " . table('payment') . " where p_install='2' ORDER BY listorder desc";
    $list = $db->getall($sql);
    return $list;
}

function get_user_order($uid, $is_paid) {
    global $db;
    $sql = "select * from " . table('order') . " WHERE uid=" . intval($uid) . " AND  is_paid='" . intval($is_paid) . "' ORDER BY id desc";
    return $db->getall($sql);
}

//增加订单
function add_order($uid, $oid, $amount, $payment_name, $description, $addtime, $points = '', $setmeal = '', $utype = '4') {
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
    write_memberslog($uid, $utype, 3101, $_SESSION['username'], "添加订单，编号{$oid}，金额{$amount}元");
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

function get_order_one($uid, $id) {
    global $db;
    $sql = "select * from " . table('order') . " where id =" . intval($id) . " AND uid = " . intval($uid) . "  AND is_paid =1  LIMIT 1 ";
    return $db->getone($sql);
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

function del_order($uid, $id) {
    global $db;
    write_memberslog($_SESSION['uid'], 4, 3102, $_SESSION['username'], "取消订单，订单id{$id}");
    return $db->query("Delete from " . table('order') . " WHERE id='" . intval($id) . "' AND uid=" . intval($uid) . " AND is_paid=1  LIMIT 1 ");
}

function get_setmeal_one($id) {
    global $db;
    $id = intval($id);
    $sql = "select * from " . table('train_setmeal') . "  WHERE id='{$id}'  LIMIT 1";
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

function get_teachers($offset, $perpage, $get_sql = '') {
    global $db, $timestamp;
    $row_arr = array();
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT {$offset},{$perpage}";
    }
    $result = $db->query($get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['teachers_url'] = url_rewrite('QS_train_lecturershow', array('id' => $row['id']), false);
        $row['speciality_'] = $row['speciality'];
        $row['speciality'] = cut_str($row['speciality'], 13, 0, '...');
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_teachers_one($id, $uid = '') {
    global $db, $timestamp;
    $id = intval($id);
    if (!empty($uid))
        $wheresql = " AND uid=" . intval($uid);
    $val = $db->getone("select * from " . table('train_teachers') . " where id='{$id}' {$wheresql} LIMIT 1");
    if (empty($val))
        return false;
    $val['teachers_url'] = url_rewrite('QS_train_lecturershow', array('id' => $val['id']), false);
    $val['speciality_'] = $val['speciality'];
    $val['speciality'] = cut_str($val['speciality'], 13, 0, '...');
    $val['age'] = date('Y') - $val['birthdate'];
    return $val;
}

function action_user_setmeal($uid, $action) {
    global $db;
    $sql = "update " . table('members_train_setmeal') . " set `" . $action . "`=" . $action . "-1  WHERE uid=" . intval($uid) . "  AND  effective=1 LIMIT 1";
    return $db->query($sql);
}

function del_teachers($del_id, $uid) {
    global $db;
    $return = 0;
    $uidsql = " AND uid=" . intval($uid) . "";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("Delete from " . table('train_teachers') . " WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        $return = $return + $db->affected_rows();
        write_memberslog($_SESSION['uid'], 4, 8109, $_SESSION['username'], "删除讲师({$sqlin})");
    }
    return $return;
}

function get_course($offset, $perpage, $get_sql = '', $countapply = false) {
    global $db, $timestamp;
    $row_arr = array();
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT {$offset},{$perpage}";
    }
    $result = $db->query($get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['course_name_'] = $row['course_name'];
        $row['course_name'] = cut_str($row['course_name'], 10, 0, "...");
        $row['course_url'] = url_rewrite('QS_train_curriculumshow', array('id' => $row['id']), false);
        $row['teacher_url'] = url_rewrite('QS_train_lecturershow', array('id' => $row['teacher_id']), false);
        if ($countapply) {
            $wheresql = " WHERE train_uid='{$row['uid']}' AND course_id= '{$row['id']}'";
            $row['countcourse'] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('personal_course_apply') . $wheresql);
        }
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_audit_teachers($uid, $train_id) {
    global $db, $timestamp;
    $row_arr = array();
    $data = $db->getall('select id,teachername from ' . table('train_teachers') . ' WHERE uid=' . intval($uid) . ' AND train_id=' . intval($train_id) . ' AND audit=1 ');
    return $data;
}

function refresh_course($id, $uid) {
    global $db;
    $uid = intval($uid);
    if (!is_array($id))
        $id = array($id);
    $time = time();
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('train_profile') . "  SET refreshtime='{$time}' WHERE uid='{$uid}' LIMIT 1 "))
            return false;
        if (!$db->query("update  " . table('course') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})  AND uid='{$uid}'"))
            return false;
        return true;
    }
    return false;
}

function del_course($del_id, $uid) {
    global $db;
    $return = 0;
    $uidsql = " AND uid=" . intval($uid) . "";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("Delete from " . table('course') . " WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        $return = $return + $db->affected_rows();
        if (!$db->query("Delete from " . table('course_contact') . " WHERE pid IN ({$sqlin}) "))
            return false;
        write_memberslog($_SESSION['uid'], 4, 8204, $_SESSION['username'], "删除课程({$sqlin})");
    }
    return $return;
}

function activate_course($idarr, $display, $uid) {
    global $db;
    $display = intval($display);
    $uid = intval($uid);
    $uidsql = " AND uid='{$uid}'";
    if (!is_array($idarr))
        $idarr = array($idarr);
    $sqlin = implode(",", $idarr);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update " . table('course') . "  SET display='{$display}' WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        write_memberslog($_SESSION['uid'], 4, 8205, $_SESSION['username'], "将课程激活状态设为:{$display},课程ID为：{$sqlin}");
        return true;
    }
    return false;
}

function set_user_status($status, $uid) {
    global $db;
    $status = intval($status);
    $uid = intval($uid);
    if (!$db->query("UPDATE " . table('members') . " SET status= {$status} WHERE uid={$uid} LIMIT 1"))
        return false;
    if (!$db->query("UPDATE " . table('train_profile') . " SET user_status= {$status} WHERE uid={$uid} "))
        return false;
    write_memberslog($_SESSION['uid'], 4, 1003, $_SESSION['username'], "修改帐号状态");
    return true;
}

function get_course_one($id, $uid = '') {
    global $db, $timestamp;
    $id = intval($id);
    if (!empty($uid))
        $wheresql = " AND uid=" . intval($uid);
    $val = $db->getone("select * from " . table('course') . " where id='{$id}' {$wheresql} LIMIT 1");
    if (empty($val))
        return false;
    $val['contact'] = $db->getone("select * from " . table('course_contact') . " where pid='{$val['id']}' LIMIT 1 ");
    $val['deadline_days'] = ($val['deadline'] - $timestamp) > 0 ? "距到期时间还有<strong style=\"color:#FF0000\">" . sub_day($val['deadline'], $timestamp) . "</strong>" : "<span style=\"color:#FF6600\">目前已过期</span>";
    return $val;
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

function get_pms_reply($pmid) {
    global $db;
    $pmid = intval($pmid);
    $sql = "select r.* from " . table('pms_reply') . " AS r  LEFT JOIN  " . table('members') . " AS i  ON  r.replyuid=i.uid WHERE r.pmid='{$pmid}' ORDER BY r.rid ASC";
    $list = $db->getall($sql);
    return $list;
}

function get_train($uid) {
    global $db;
    $sql = "select * from " . table('train_profile') . " where uid=" . intval($uid) . " LIMIT 1 ";
    $data = $db->getone($sql);
    return $data;
}

function get_user_info($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('members') . " where uid = '{$uid}' LIMIT 1";
    return $db->getone($sql);
}

function get_user_limit_points($uid) {
    global $db;
    $uid = intval($uid);
    $points = $db->getone("select * from " . table('members_points_limit') . " where uid ='{$uid}' LIMIT 1");
    if (!empty($points['id'])) {
        $up_limit['points'] = $points['endtime'] > time() ? $points['points'] : 0;
        updatetable(table('members_points_limit'), $up_limit, " uid='{$uid}' LIMIT 1 ");
        $points['points'] = $up_limit['points'];
    } else {
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

function set_members_setmeal($uid, $setmealid) {
    global $db, $timestamp;
    $setmeal = $db->getone("select * from " . table('train_setmeal') . " WHERE id = " . intval($setmealid) . " AND display=1 LIMIT 1");
    if (empty($setmeal))
        return false;
    $setsqlarr['effective'] = 1;
    $setsqlarr['setmeal_id'] = $setmeal['id'];
    $setsqlarr['setmeal_name'] = $setmeal['setmeal_name'];
    $setsqlarr['days'] = $setmeal['days'];
    $setsqlarr['starttime'] = $timestamp;
    $setsqlarr['refresh_course_space'] = $setmeal['refresh_course_space'];
    $setsqlarr['refresh_course_time'] = $setmeal['refresh_course_time'];
    if ($setmeal['days'] > 0) {
        $setsqlarr['endtime'] = strtotime("" . $setmeal['days'] . " days");
    } else {
        $setsqlarr['endtime'] = "0";
    }
    $setsqlarr['expense'] = $setmeal['expense'];
    $setsqlarr['teachers_num'] = $setmeal['teachers_num'];
    $setsqlarr['course_num'] = $setmeal['course_num'];
    $setsqlarr['down_apply'] = $setmeal['down_apply'];
    $setsqlarr['change_templates'] = $setmeal['change_templates'];
    $setsqlarr['map_open'] = $setmeal['map_open'];
    $setsqlarr['added'] = $setmeal['added'];
    if (!updatetable(table('members_train_setmeal'), $setsqlarr, " uid='{$uid}'"))
        return false;
    $setmeal_course['setmeal_deadline'] = $setsqlarr['endtime'];
    $setmeal_course['setmeal_id'] = $setsqlarr['setmeal_id'];
    $setmeal_course['setmeal_name'] = $setsqlarr['setmeal_name'];
    if (!updatetable(table('course'), $setmeal_course, " uid='{$uid}' AND add_mode='2' "))
        return false;
    return true;
}

function get_setmeal($apply = false) {
    global $db;
    if ($apply) {
        $wheresql = " AND apply=1";
    }
    $sql = "select * from " . table('train_setmeal') . " WHERE display=1 {$wheresql} ORDER BY show_order desc";
    return $db->getall($sql);
}

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
            write_memberslog($order['uid'], 4, 9101, $user['username'], $notes);
            write_setmeallog($order['uid'], $user['username'], $notes, 2, $order['amount'], $ismoney, 4);
        } elseif ($order['setmeal'] > 0) {
            set_members_setmeal($order['uid'], $order['setmeal']);
            $setmeal = get_setmeal_one($order['setmeal']);
            $notes = date('Y-m-d H:i', time()) . "通过：" . get_payment_info($order['payment_name'], true) . " 成功充值 " . $order['amount'] . "元并开通{$setmeal['setmeal_name']}";
            write_memberslog($order['uid'], 4, 9102, $user['username'], $notes);
            write_setmeallog($order['uid'], $user['username'], $notes, 2, $order['amount'], $ismoney, 2, 4);
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

function get_apply_course($offset, $perpage, $get_sql = '') {
    global $db;
    $limit = " LIMIT {$offset},{$perpage}";
    $result = $db->query("SELECT * FROM " . table('personal_course_apply') . " as a {$get_sql} ORDER BY a.did DESC {$limit}");
    while ($row = $db->fetch_array($result)) {
        $row['course_url'] = url_rewrite('QS_train_curriculumshow', array('id' => $row['course_id']), false);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function count_course_apply($uid, $look = '') {
    global $db;
    $uid = intval($uid);
    $look = intval($look);
    $wheresql = "";
    if ($look > 0) {
        $wheresql.=" AND personal_look='{$look}' ";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_course_apply') . " WHERE train_uid='{$uid}' {$wheresql}";
    return $db->get_total($total_sql);
}

function get_auditcourse($uid) {
    global $db;
    $uid = intval($uid);
    return $db->getall("select id,course_name from " . table('course') . " WHERE uid={$uid}");
}

function check_down_applyid($applyid, $train_uid) {
    global $db;
    $train_uid = intval($train_uid);
    $applyid = intval($applyid);
    $sql = "select did from " . table('personal_course_apply') . " WHERE train_uid = '{$train_uid}' AND did='{$applyid}' LIMIT 1";
    $info = $db->getone($sql);
    if (empty($info)) {
        return false;
    } else {
        return true;
    }
}

function get_apply_one($applyid) {
    global $db;
    $applyid = intval($applyid);
    $sql = "select * from " . table('personal_course_apply') . " WHERE did='{$applyid}' LIMIT 1";
    $info = $db->getone($sql);
    return $info;
}

function add_down_apply($id, $train_uid) {
    global $db, $timestamp;
    $id = intval($id);
    $train_uid = intval($train_uid);
    $sql = "update " . table('personal_course_apply') . " set download_type=1,personal_look=2 where did={$id} and train_uid={$train_uid} ";
    return $db->query($sql);
}

function set_apply($id, $uid, $setlook) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    $setsqlarr['personal_look'] = intval($setlook);
    $wheresql = " did IN (" . $sqlin . ") AND train_uid=" . intval($uid) . "";
    foreach ($id as $aid) {
        $sql = "select m.username from " . table('personal_course_apply') . " AS a JOIN " . table('members') . " AS m ON a.personal_uid=m.uid WHERE a.did='{$aid}' LIMIT 1";
        $user = $db->getone($sql);
        write_memberslog($_SESSION['uid'], 4, 8206, $_SESSION['username'], "查看了 {$user['username']} 的课程申请联系方式");
    }
    return updatetable(table('personal_course_apply'), $setsqlarr, $wheresql);
}

function del_apply($del_id, $uid) {
    global $db;
    $uidsql = " AND train_uid=" . intval($uid) . "";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('personal_course_apply') . " WHERE did IN ({$sqlin}) {$uidsql}"))
        return false;
    write_memberslog($_SESSION['uid'], 4, 8207, $_SESSION['username'], "删除课程申请({$sqlin})");
    return true;
}

function get_subsite_list() {
    global $db;
    $sql = "select * from " . table('subsite');
    return $db->getall($sql);
}

?>