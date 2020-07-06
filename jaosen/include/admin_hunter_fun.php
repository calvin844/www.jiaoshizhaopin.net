<?php

/*
 * 74cms 管理中心 猎头顾问用户相关函数
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

function get_points_rule() {
    global $db;
    $sql = "select * from " . table('members_points_rule') . " WHERE utype='3' order BY id asc";
    $list = $db->getall($sql);
    return $list;
}

function get_setmeal($apply = true) {
    global $db;
    if ($apply == true) {
        $where = "";
    } else {
        $where = " WHERE display=1 ";
    }
    $sql = "select * from " . table('hunter_setmeal') . $where . "  order BY display desc,show_order desc,id asc";
    return $db->getall($sql);
}

function get_setmeal_one($id) {
    global $db;
    $sql = "select * from " . table('hunter_setmeal') . "  WHERE id=" . intval($id) . "";
    return $db->getone($sql);
}

function del_setmeal_one($id) {
    global $db;
    if (!$db->query("Delete from " . table('hunter_setmeal') . " WHERE id=" . intval($id) . " "))
        return false;
    return true;
}

function get_hunterjobs($offset, $perpage, $get_sql = '') {
    global $db, $timestamp;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query($get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['companyname'] = cut_str($row['companyname'], 18, 0, "...");
        if ($row['utype'] == '1') {
            $company = $db->getone('select id from ' . table('company_profile') . ' where uid = ' . $row['uid'] . ' limit 1 ');
            $row['company_url'] = url_rewrite('QS_companyshow', array('id' => $company['id']), false);
        }
        $row['jobs_url'] = url_rewrite('QS_hunter_jobsshow', array('id' => $row['id']), true, $row['subsite_id']);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_hunterjobs($del_id) {
    global $db;
    $return = 0;
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("Delete from " . table('hunter_jobs') . " WHERE id IN ({$sqlin}) "))
            return false;
        $return = $return + $db->affected_rows();
    }
    return $return;
}

function edit_jobs_audit($id, $audit, $reason, $pms_notice = '1') {
    global $db, $_CFG;
    $audit = intval($audit);
    $reason = trim($reason);
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $return = 0;
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('hunter_jobs') . " SET audit={$audit}  WHERE id IN ({$sqlin})"))
            return false;
        $return = $return + $db->affected_rows();
        //发送站内信
        if ($pms_notice == '1') {
            $result = $db->query("SELECT uid,jobs_name FROM " . table('hunter_jobs') . " WHERE id IN ({$sqlin})");
            $reason = $reason == '' ? '原因：未知' : '原因：' . $reason;
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                $setsqlarr['message'] = $audit == '1' ? "您发布的猎头职位：{$list['jobs_name']},成功通过网站管理员审核！" : "您发布的猎头职位：{$list['jobs_name']},未通过网站管理员审核,{$reason}";
                $setsqlarr['msgtype'] = 1;
                $setsqlarr['msgtouid'] = $user_info['uid'];
                $setsqlarr['msgtoname'] = $user_info['username'];
                $setsqlarr['dateline'] = time();
                $setsqlarr['replytime'] = time();
                $setsqlarr['new'] = 1;
                inserttable(table('pms'), $setsqlarr);
            }
        }
        //发送邮件
        $mailconfig = get_cache('mailconfig');
        $sms = get_cache('sms_config');
        if ($audit == "1" && $mailconfig['set_hunjobsallow'] == "1") {//审核通过
            $result = $db->query("SELECT uid,jobs_name FROM " . table('hunter_jobs') . " WHERE id IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['email_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid=" . $list['uid'] . "&key=" . asyn_userkey($list['uid']) . "&jobs_name=" . $list['jobs_name'] . "&act=set_hunjobsallow");
                }
            }
        }
        if ($audit == "3" && $mailconfig['set_hunjobsnotallow'] == "1") {//审核未通过
            $result = $db->query("SELECT uid,jobs_name FROM " . table('hunter_jobs') . " WHERE id IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['email_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid=" . $list['uid'] . "&key=" . asyn_userkey($list['uid']) . "&jobs_name=" . $list['jobs_name'] . "&act=set_hunjobsnotallow");
                }
            }
        }
        //sms		
        if ($audit == "1" && $sms['open'] == "1" && $sms['set_hunjobsallow'] == "1") {
            $result = $db->query("SELECT uid,jobs_name FROM " . table('hunter_jobs') . " WHERE id IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['mobile_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid=" . $list['uid'] . "&key=" . asyn_userkey($list['uid']) . "&jobs_name=" . $list['jobs_name'] . "&act=set_hunjobsallow");
                }
            }
        }
        //sms
        if ($audit == "3" && $sms['open'] == "1" && $sms['set_hunjobsnotallow'] == "1") {//认证未通过
            $result = $db->query("SELECT uid,jobs_name FROM " . table('hunter_jobs') . " WHERE id IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['mobile_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid=" . $list['uid'] . "&key=" . asyn_userkey($list['uid']) . "&jobs_name=" . $list['jobs_name'] . "&act=set_hunjobsnotallow");
                }
            }
        }
        //sms
        return $return;
    } else {
        return $return;
    }
}

function refresh_jobs($id) {
    global $db;
    $return = 0;
    if (!is_array($id))
        $id = array($id);
    $time = time();
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('hunter_jobs') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})  "))
            return false;
        $return = $return + $db->affected_rows();
    }
    return $return;
}

function delay_jobs($id, $days) {
    global $db;
    $days = intval($days);
    $return = 0;
    if (empty($days))
        return false;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $time = time();
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $result = $db->query("SELECT id,deadline FROM " . table('hunter_jobs') . " WHERE id IN ({$sqlin}) ");
        while ($row = $db->fetch_array($result)) {
            if ($row['deadline'] > $time) {
                $deadline = strtotime("+{$days} day", $row['deadline']);
            } else {
                $deadline = strtotime("+{$days} day");
            }
            if (!$db->query("update  " . table('hunter_jobs') . " SET deadline='{$deadline}'  WHERE id='{$row['id']}'  LIMIT 1"))
                return false;
            $return = $return + $db->affected_rows();
        }
    }
    return $return;
}

function get_jobs_one($id, $uid = '') {
    global $db, $timestamp;
    $id = intval($id);
    if (!empty($uid))
        $wheresql = " AND uid=" . intval($uid);
    $val = $db->getone("select * from " . table('hunter_jobs') . " where id='{$id}' {$wheresql} LIMIT 1");
    if (empty($val))
        return false;
    $val['user'] = get_user($val['uid']);
    $val['jobs_url'] = url_rewrite('QS_hunter_jobsshow', array('id' => $val['id']), true, $row['subsite_id']);
    if ($val['utype'] == '1') {
        $company = $db->getone('select id from ' . table('company_profile') . ' where uid = ' . $val['uid'] . ' limit 1 ');
        $val['company_url'] = url_rewrite('QS_companyshow', array('id' => $company['id']), false);
    }
    $val['deadline_days'] = ($val['deadline'] - $timestamp) > 0 ? "距到期时间还有<strong style=\"color:#FF0000\">" . sub_day($val['deadline'], $timestamp) . "</strong>" : "<span style=\"color:#FF6600\">目前已过期</span>";
    return $val;
}

function get_hunter($offset, $perpage, $get_sql = '', $mode = 1) {
    global $db;
    $colum = $mode == 1 ? 'p.points' : 'p.setmeal_name';
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT c.*,m.username,m.mobile,m.email as memail,{$colum} FROM " . table('hunter_profile') . " AS c " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['hunter_url'] = url_rewrite('QS_huntershow', array('id' => $row['id']), false);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_hunter($uid) {
    global $db, $hunter_dir;
    if (!is_array($uid))
        $uid = array($uid);
    $sqlin = implode(",", $uid);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $result = $db->query("SELECT photo_img FROM " . table('hunter_profile') . " WHERE uid IN ({$sqlin})");
        while ($row = $db->fetch_array($result)) {
            @unlink($hunter_dir . $row['photo_img']);
            @unlink($hunter_dir . 'thumb/' . $row['photo_img']);
        }
        if (!$db->query("Delete from " . table('hunter_profile') . " WHERE uid IN ({$sqlin})"))
            return false;
        return true;
    }
    return false;
}

function del_hunter_alljobs($uid) {
    global $db;
    if (!is_array($uid))
        $uid = array($uid);
    $sqlin = implode(",", $uid);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $db->query("Delete from " . table('hunter_jobs') . " WHERE uid IN ({$sqlin})");
        return true;
    }
    return false;
}

function edit_hunter_audit($uid, $audit, $reason, $pms_notice) {
    global $db, $_CFG;
    $audit = intval($audit);
    $pms_notice = intval($pms_notice);
    $reason = trim($reason);
    if (!is_array($uid))
        $uid = array($uid);
    $sqlin = implode(",", $uid);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('hunter_profile') . " SET audit='{$audit}'  WHERE uid IN ({$sqlin})"))
            return false;
        //发送站内信
        if ($pms_notice == '1') {
            $reason = $reason == '' ? '无' : $reason;
            if ($audit == '1') {
                $note = '成功通过网站管理员审核!';
            } elseif ($audit == '2') {
                $note = '正在审核中!';
            } else {
                $note = '未通过网站管理员审核！';
            }
            $result = $db->query("SELECT huntername,uid FROM " . table('hunter_profile') . " WHERE uid IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                $setsqlarr['message'] = "您注册的猎头顾问：{$list['huntername']}," . $note . '其他说明：' . $reason;
                $setsqlarr['msgtype'] = 1;
                $setsqlarr['msgtouid'] = $user_info['uid'];
                $setsqlarr['msgtoname'] = $user_info['username'];
                $setsqlarr['dateline'] = time();
                $setsqlarr['replytime'] = time();
                $setsqlarr['new'] = 1;
                inserttable(table('pms'), $setsqlarr);
            }
        }
        if ($audit == '1') {
            //3.4升级修改注意,只有积分模式奖励积分
            if ($_CFG['operation_hunter_mode'] == '1') {
                $points_rule = get_cache('points_rule');
                if ($points_rule['hunter_auth']['value'] > 0) {//如果设置了认证赠送积分
                    gift_points($sqlin, 'hunterauth', $points_rule['hunter_auth']['type'], $points_rule['hunter_auth']['value']);
                }
            }
        }
        $mailconfig = get_cache('mailconfig');
        $sms = get_cache('sms_config');
        if ($audit == "1" && $mailconfig['set_hunallow'] == "1") {//认证通过
            $result = $db->query("SELECT huntername,uid FROM " . table('hunter_profile') . " WHERE uid IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['email_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid={$list['uid']}&huntername={$list['huntername']}&key=" . asyn_userkey($list['uid']) . "&act=set_hunallow");
                }
            }
        }
        if ($audit == "3" && $mailconfig['set_hunnotallow'] == "1") {//认证未通过
            $result = $db->query("SELECT huntername,uid FROM " . table('hunter_profile') . " WHERE uid IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['email_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid={$list['uid']}&huntername={$list['huntername']}&key=" . asyn_userkey($list['uid']) . "&act=set_hunnotallow");
                }
            }
        }
        //sms		
        if ($audit == "1" && $sms['open'] == "1" && $sms['set_hunallow'] == "1") {
            $result = $db->query("SELECT huntername,uid FROM " . table('hunter_profile') . " WHERE uid IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['mobile_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid={$list['uid']}&huntername={$list['huntername']}&key=" . asyn_userkey($list['uid']) . "&act=set_hunallow");
                }
            }
        }
        //sms
        if ($audit == "3" && $sms['open'] == "1" && $sms['set_hunnotallow'] == "1") {//认证未通过
            $result = $db->query("SELECT huntername,uid FROM " . table('hunter_profile') . " WHERE uid IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['mobile_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid={$list['uid']}&huntername={$list['huntername']}&key=" . asyn_userkey($list['uid']) . "&act=set_hunnotallow");
                }
            }
        }
        return true;
    }
    return false;
}

function gift_points($uid, $gift, $ptype, $points) {
    global $db;
    $operator = $ptype == "1" ? "+" : "-";
    $time = time();
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $uid)) {
        $uid = explode(',', $uid);
    }
    if (!is_array($uid))
        $uid = array($uid);
    if (!empty($uid) && is_array($uid)) {
        foreach ($uid as $vuid) {
            $vuid = intval($vuid);
            if ($gift == 'companyauth') {
                $com = $db->getone("SELECT uid FROM " . table('members_handsel') . " WHERE uid ='{$vuid}' AND htype='{$gift}'  LIMIT 1");
                if (empty($com)) {
                    report_deal($vuid, $ptype, $points);
                    $user = get_user($vuid);
                    $mypoints = get_user_points($vuid);
                    $user_limit_points = get_user_limit_points($_SESSION['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    write_memberslog($vuid, 3, 9201, $user['username'], " 成为已认证猎头顾问({$operator}{$points})，(剩余积分:{$mypoints}，剩余限时积分:{$user_limit_points})");
                    $db->query("INSERT INTO " . table('members_handsel') . " (uid,htype,addtime) VALUES ('{$vuid}', '{$gift}','{$time}')");
                }
            }
        }
    }
}

function refresh_hunter($uid, $refresjobs = false) {
    global $db;
    $return = 0;
    if (!is_array($uid))
        $uid = array($uid);
    $sqlin = implode(",", $uid);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('hunter_profile') . " SET refreshtime='" . time() . "'  WHERE uid IN ({$sqlin})"))
            return false;
        $return = $return + $db->affected_rows();
        if ($refresjobs) {
            if (!$db->query("update  " . table('hunter_jobs') . " SET refreshtime='" . time() . "'  WHERE uid IN ({$sqlin})"))
                return false;
            $return = $return + $db->affected_rows();
        }
    }
    return $return;
}

function get_hunter_one_id($id) {
    global $db;
    $id = intval($id);
    $sql = "select * from " . table('hunter_profile') . " where id='{$id}'";
    $val = $db->getone($sql);
    $val['user'] = get_user($val['uid']);
    if ($val) {
        $arr = explode('-', $val['companytelephone']);
        $val['code'] = $arr[0];
        $val['companytelephone'] = $arr[1];
        $val['workyears'] = date('Y') - $val['worktime_start'];
    }
    return $val;
}

function get_member_list($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT m.*,c.huntername,c.id,c.addtime FROM " . table('members') . " as m " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['hunter_url'] = url_rewrite('QS_huntershow', array('id' => $row['id']), false);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function delete_hunter_user($uid) {
    global $db;
    if (!is_array($uid))
        $uid = array($uid);
    $sqlin = implode(",", $uid);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (defined('UC_API')) {
            include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
            foreach ($uid as $tuid) {
                $userinfo = get_user($tuid);
                $uc_user = uc_get_user($userinfo['username']);
                $uc_uid_arr[] = $uc_user[0];
            }
            uc_user_delete($uc_uid_arr);
        }
        if (!$db->query("Delete from " . table('members') . " WHERE uid IN (" . $sqlin . ")"))
            return false;
        if (!$db->query("Delete from " . table('members_info') . " WHERE uid IN (" . $sqlin . ")"))
            return false;
        if (!$db->query("Delete from " . table('members_log') . " WHERE log_uid IN (" . $sqlin . ")"))
            return false;
        if (!$db->query("Delete from " . table('members_points') . " WHERE uid IN (" . $sqlin . ")"))
            return false;
        if (!$db->query("Delete from " . table('order') . " WHERE uid IN (" . $sqlin . ")"))
            return false;
        if (!$db->query("Delete from " . table('members_hunter_setmeal') . " WHERE uid IN (" . $sqlin . ")"))
            return false;
        return true;
    }
    return false;
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
    $setmeal_jobs['setmeal_deadline'] = $setsqlarr['endtime'];
    $setmeal_jobs['setmeal_id'] = $setsqlarr['setmeal_id'];
    $setmeal_jobs['setmeal_name'] = $setsqlarr['setmeal_name'];
    if (!updatetable(table('members_hunter_setmeal'), $setsqlarr, " uid='{$uid}'"))
        return false;
    if (!updatetable(table('hunter_jobs'), $setmeal_jobs, " uid=" . intval($uid) . " AND add_mode='2' "))
        return false;
    return true;
}

function get_hunter_one_uid($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('hunter_profile') . " where uid={$uid}";
    $val = $db->getone($sql);
    return $val;
}

function get_user_points($uid) {
    global $db;
    $sql = "select * from " . table('members_points') . " where uid = " . intval($uid) . "  LIMIT 1 ";
    $points = $db->getone($sql);
    return $points['points'];
}

function get_user_setmeal($uid) {
    global $db;
    $sql = "select * from " . table('members_hunter_setmeal') . "  WHERE uid=" . intval($uid) . " AND  effective=1 LIMIT 1";
    return $db->getone($sql);
}

function get_user($uid) {
    global $db;
    $sql = "select * from " . table('members') . " where uid=" . intval($uid) . " LIMIT 1";
    return $db->getone($sql);
}

function edit_setmeal_notes($setarr, $setmeal) {
    $diff_arr = array_diff_assoc($setarr, $setmeal);
    if ($diff_arr) {
        foreach ($diff_arr as $key => $value) {
            if ($key == 'jobs_add') {
                $str.="发布职位：{$setmeal['jobs_add']}-{$setarr['jobs_add']}";
            } elseif ($key == 'download_resume_manager') {
                $str.=",下载经理人才简历：{$setmeal['download_resume_manager']}-{$setarr['download_resume_manager']}";
            } elseif ($key == 'interview_manager') {
                $str.=",邀请经理人才面试数：{$setmeal['interview_manager']}-{$setarr['interview_manager']}";
            } elseif ($key == 'endtime') {
                if ($setarr['endtime'] == '1970-01-01')
                    $setarr['endtime'] = '无限期';
                $str.=",修改套餐到期时间：{$setmeal['endtime']}~{$setarr['endtime']}";
            }elseif ($key == 'log_amount' && $value) {
                $str.=",收取套餐金额：{$value} 元";
            }
        }
        $strend = $str ? "操作人：{$_SESSION['admin_name']}。说明：" . $str : '';
        return $strend;
    } else {
        return '';
    }
}

function get_meal_members_log($offset, $perpage, $get_sql = '', $mode = '1') {
    global $db;
    $colum = $mode == 1 ? 'b.points' : 'b.setmeal_name';
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT a.*,{$colum},c.huntername FROM " . table('members_charge_log') . " as a " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['log_value_'] = $row['log_value'];
        $row['log_value'] = cut_str($row['log_value'], 20, 0, "...");
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_meal_log($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('members_charge_log') . " WHERE log_id IN ({$sqlin})"))
        return false;
    return $db->affected_rows();
}

function get_meal_members_list($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT a.*,b.*,c.huntername FROM " . table('members_hunter_setmeal') . " as a " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row_arr[] = $row;
    }
    return $row_arr;
}

function delay_meal($id, $days) {
    global $db;
    $days = intval($days);
    $return = 0;
    if (empty($days))
        return false;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $time = time();
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $result = $db->query("SELECT id,uid,endtime FROM " . table('members_hunter_setmeal') . " WHERE uid IN ({$sqlin})");
        while ($row = $db->fetch_array($result)) {
            if ($row['endtime'] == "0") {
                continue;
            } else {
                if ($row['endtime'] > $time) {
                    $deadline = strtotime("{$days} day", $row['endtime']);
                } else {
                    $deadline = strtotime("{$days} day");
                }
                if (!$db->query("update  " . table('members_hunter_setmeal') . " SET endtime='{$deadline}'  WHERE id='{$row['id']}'  LIMIT 1"))
                    return false;
                $return = $return + $db->affected_rows();
                if (!$db->query("update  " . table('hunter_jobs') . " SET setmeal_deadline='{$deadline}'  WHERE uid='{$row['uid']}'  LIMIT 1"))
                    return false;
            }
        }
    }
    return $return;
}

function get_order_list($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT o.*,m.username,m.email,c.huntername FROM " . table('order') . " as o " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['payment_name'] = get_payment_info($row['payment_name'], true);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_payment_info($typename, $name = false) {
    global $db;
    $sql = "select * from " . table('payment') . " where typename ='" . $typename . "'";
    $val = $db->getone($sql);
    if ($name) {
        return $val['byname'];
    } else {
        return $val;
    }
}

function get_order_one($id = 0) {
    global $db;
    $sql = "select * from " . table('order') . " where id=" . intval($id) . " LIMIT 1";
    $val = $db->getone($sql);
    $val['payment_name'] = get_payment_info($val['payment_name'], true);
    $val['payment_username'] = get_user($val['uid']);
    return $val;
}

function del_order($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("Delete from " . table('order') . " WHERE id IN (" . $sqlin . ")  AND is_paid=1 "))
            return false;
        return true;
    }
    return false;
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
    if ($i_type == 1) {
        $points_val = $points_val + $points;
    }
    if ($i_type == 2) {
        $points_val = $points_val - $points;
        $points_val = $points_val < 0 ? 0 : $points_val;
    }
    $sql = "UPDATE " . table('members_points') . " SET points= '{$points_val}' WHERE uid='{$uid}' LIMIT 1";
    if (!$db->query($sql)) {
        return false;
    }
    return true;
}

function order_paid($v_oid) {
    global $db, $timestamp, $_CFG;
    $order = $db->getone("select * from " . table('order') . " WHERE oid ='{$v_oid}' AND is_paid= '1' LIMIT 1 ");
    if ($order) {
        $user = get_user($order['uid']);
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
            $notes = "操作人：{$_SESSION['admin_name']},说明：确认收款。收款金额：{$order['amount']} 。" . date('Y-m-d H:i', time()) . "通过：" . get_payment_info($order['payment_name'], true) . " 成功充值 " . $order['amount'] . "元，(+{$order['points']})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points}),订单:{$v_oid}";
            write_memberslog($order['uid'], 3, 9201, $user['username'], $notes);
            //会员套餐变更记录。管理员后台设置会员订单购买成功。4表示：管理员后台开通
            write_setmeallog($order['uid'], $user['username'], $notes, 4, $order['amount'], $ismoney, 1, 3);
        }
        if ($order['setmeal'] > 0) {
            set_members_setmeal($order['uid'], $order['setmeal']);
            $setmeal = get_setmeal_one($order['setmeal']);
            $notes = "操作人：{$_SESSION['admin_name']},说明：确认收款，收款金额：{$order['amount']} 。" . date('Y-m-d H:i', time()) . "通过：" . get_payment_info($order['payment_name'], true) . " 成功充值 " . $order['amount'] . "元并开通{$setmeal['setmeal_name']}";
            write_memberslog($order['uid'], 3, 9202, $user['username'], $notes);
            //会员套餐变更记录。管理员后台设置会员订单购买成功。4表示：管理员后台开通
            write_setmeallog($order['uid'], $user['username'], $notes, 4, $order['amount'], $ismoney, 2, 3);
        }
        //发送邮件
        $mailconfig = get_cache('mailconfig');
        if ($mailconfig['set_payment'] == "1" && $user['email_audit'] == "1") {
            dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid=" . $order['uid'] . "&key=" . asyn_userkey($order['uid']) . "&act=set_payment");
        }
        //发送邮件完毕
        //sms
        $sms = get_cache('sms_config');
        if ($sms['open'] == "1" && $sms['set_payment'] == "1" && $user['mobile_audit'] == "1") {
            dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid=" . $order['uid'] . "&key=" . asyn_userkey($order['uid']) . "&act=set_payment");
        }
        //sms
        return true;
    }
    return true;
}

function recom_hunter_jobs($id, $recommend, $pms_notice, $notice) {
    global $db;
    $return = 0;
    $recommend = intval($recommend);
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $time = time();
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('hunter_jobs') . " SET recommend='{$recommend}'  WHERE id IN ({$sqlin})"))
            return false;
        $return = $return + $db->affected_rows();
        //发送站内信
        if ($pms_notice == '1') {
            $result = $db->query("SELECT jobs_name,uid FROM " . table('hunter_jobs') . " WHERE id IN ({$sqlin})");
            $notice = $notice == '' ? '说明：无' : '说明：' . $notice;
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                $setsqlarr['message'] = $recommend == '1' ? "您发布的职位：{$list['jobs_name']},被管理员设置为(推荐职位)！{$notice}" : "您发布的职位：{$list['jobs_name']},被管理员取消(推荐职位)！{$notice}";
                $setsqlarr['msgtype'] = 1;
                $setsqlarr['msgtouid'] = $user_info['uid'];
                $setsqlarr['msgtoname'] = $user_info['username'];
                $setsqlarr['dateline'] = time();
                $setsqlarr['replytime'] = time();
                $setsqlarr['new'] = 1;
                inserttable(table('pms'), $setsqlarr);
            }
        }
    }
    return $return;
}

?>