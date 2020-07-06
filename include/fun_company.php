<?php

/*
 * 74cms 企业会员中心函数
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

//获取职位列表
function get_jobs($offset, $perpage, $get_sql = '', $countresume = false) {
    global $db, $timestamp;
    $row_arr = array();
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT {$offset},{$perpage}";
    }
    $result = $db->query($get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['jobs_name_'] = $row['jobs_name'];
        $row['jobs_name'] = cut_str($row['jobs_name'], 6, 0, "...");
        if (!empty($row['highlight'])) {
            $row['jobs_name'] = "<span style=\"color:{$row['highlight']}\">{$row['jobs_name']}</span>";
        }
        $row['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $row['id']), true, $row['subsite_id']);
        $row['jobcategory'] = intval($row['topclass']) . "." . intval($row['category']) . "." . intval($row['subclass']);
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
        if ($countresume) {
            $wheresql = " WHERE company_uid='{$row['uid']}' AND jobs_id= '{$row['id']}'";
            $row['countresume'] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . $wheresql);
        }
        $row_arr[] = $row;
    }
    return $row_arr;
}

//获取单条职位
function get_jobs_one($id, $uid = '') {
    global $db, $timestamp;
    $id = intval($id);
    if (!empty($uid)) {
        $wheresql = " AND uid=" . intval($uid);
    }
    $tb1 = $db->getone("select * from " . table('jobs') . " where id='{$id}' {$wheresql} LIMIT 1");
    $tb2 = $db->getone("select * from " . table('jobs_tmp') . " where id='{$id}' {$wheresql} LIMIT 1");
    $val = !empty($tb1) ? $tb1 : $tb2;
    if (empty($val)) {
        return false;
    }
    $val['contact'] = $db->getone("select * from " . table('jobs_contact') . " where pid='{$val['id']}' LIMIT 1 ");
    $val['deadline_days'] = ($val['deadline'] - $timestamp) > 0 ? "距到期时间还有<strong style=\"color:#FF0000\">" . sub_day($val['deadline'], $timestamp) . "</strong>" : "<span style=\"color:#FF6600\">目前已过期</span>";
    return $val;
}

//根据id组获取职位
function get_jobs_by_idstr($sqlin, $uid = '') {
    global $db, $timestamp;
    $sqlin = trim($sqlin);
    if (!empty($uid))
        $wheresql = " AND uid=" . intval($uid);
    $val = $db->getall("select * from " . table('jobs') . " where id in ('" . $sqlin . "') {$wheresql} UNION ALL select * from " . table('jobs_tmp') . " where id in ('" . $sqlin . "') {$wheresql}");
    if (empty($val))
        return false;
    return $val;
}

//删除职位
function del_jobs($del_id, $uid) {
    global $db;
    $return = 0;
    $uidsql = " AND uid=" . intval($uid) . "";
    if (!is_array($del_id)) {
        $del_id = array($del_id);
    }
    $sqlin = implode(",", $del_id);
    $id_str = "";
    $sql = "select id from " . table('jobs') . " WHERE id IN ({$sqlin}) {$uidsql}";
    $del_id_arr = $db->getall($sql);
    foreach ($del_id_arr as $da) {
        $id_str .= $da['id'] . ",";
    }
    $id_str = trim($id_str, ",");
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if ($db->query("Delete from " . table('jobs') . " WHERE id IN ({$sqlin}) {$uidsql}")) {
            if (!empty($id_str)) {
                $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id IN(" . $id_str . ") and type = 'jobs' ");
            }
        } else {
            return false;
        }
        $return = $return + $db->affected_rows();
        if ($db->query("Delete from " . table('jobs_tmp') . " WHERE id IN ({$sqlin}) {$uidsql}")) {
            if (!empty($id_str)) {
                $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id IN(" . $id_str . ") and type = 'jobs' ");
            }
        } else {
            return false;
        }
        $return = $return + $db->affected_rows();
        if (!$db->query("Delete from " . table('jobs_contact') . " WHERE pid IN ({$sqlin}) "))
            return false;
        if (!$db->query("Delete from " . table('promotion') . " WHERE cp_jobid IN ({$sqlin}) "))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_hot') . " WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_key') . " WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_rtime') . " WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_scale') . " WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_stickrtime') . " WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_wage') . " WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_tag') . " WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        if (!$db->query("Delete from " . table('view_jobs') . " WHERE jobsid IN ({$sqlin})"))
            return false;
        write_memberslog($_SESSION['uid'], 1, 2003, $_SESSION['username'], "删除职位({$sqlin})");
    }
    return $return;
}

//激活或者暂停职位
function activate_jobs($idarr, $display, $uid) {
    global $db;
    $display = intval($display);
    $uid = intval($uid);
    $uidsql = " AND uid='{$uid}'";
    if (!is_array($idarr)) {
        $idarr = array($idarr);
    }
    $sqlin = implode(",", $idarr);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update " . table('jobs') . "  SET display='{$display}' WHERE id IN ({$sqlin}) {$uidsql}")) {
            return false;
        }
        if (!$db->query("update " . table('jobs_tmp') . "  SET display='{$display}' WHERE id IN ({$sqlin}) {$uidsql}")) {
            return false;
        }
        distribution_jobs($idarr, $uid);
        write_memberslog($_SESSION['uid'], 1, 2005, $_SESSION['username'], "将职位激活状态设为:{$display},职位ID为：{$sqlin}");
        return true;
    }
    return false;
}

function refresh_jobs($id, $uid) {
    global $db;
    $uid = intval($uid);
    if (!is_array($id))
        $id = array($id);
    $time = time();
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $id_str = "";
        $sql = "select id from " . table('jobs') . " WHERE id IN ({$sqlin})  AND uid='{$uid}'";
        $del_id_arr = $db->getall($sql);
        foreach ($del_id_arr as $da) {
            $id_str .= $da['id'] . ",";
        }
        $id_str = trim($id_str, ",");
        if (!$db->query("update  " . table('company_profile') . "  SET refreshtime='{$time}' WHERE uid='{$uid}' LIMIT 1 ")) {
            return false;
        }
        if ($db->query("update  " . table('jobs') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})  AND uid='{$uid}'")) {
            if (!empty($id_str)) {
                $db->query("update  " . table('jiaoshi_article_jobs_index') . " SET refreshtime='" . $time . "'  WHERE p_id IN(" . $id_str . ") and type = 'jobs' ");
            }
        } else {
            return false;
        }
        if (!$db->query("update  " . table('jobs_tmp') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})  AND uid='{$uid}'"))
            return false;
        if (!$db->query("update  " . table('jobs_search_hot') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})  AND uid='{$uid}'"))
            return false;
        if (!$db->query("update  " . table('jobs_search_key') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})  AND uid='{$uid}'"))
            return false;
        if (!$db->query("update  " . table('jobs_search_rtime') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})  AND uid='{$uid}'"))
            return false;
        if (!$db->query("update  " . table('jobs_search_scale') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})  AND uid='{$uid}'"))
            return false;
        if (!$db->query("update  " . table('jobs_search_stickrtime') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})  AND uid='{$uid}'"))
            return false;
        if (!$db->query("update  " . table('jobs_search_wage') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})  AND uid='{$uid}'"))
            return false;
        return true;
    }
    return false;
}

//获取企业资料
function get_company($uid) {
    global $db;
    $sql = "select * from " . table('company_profile') . " where uid=" . intval($uid) . " LIMIT 1 ";
    $result = $db->getone($sql);
    return $result;
}

function distribution_jobs($id, $uid) {
    global $db, $_CFG;
    $uid = intval($uid);
    $uidsql = " AND uid='{$uid}' ";
    if (!is_array($id)) {
        $id = array($id);
    }
    $time = time();
    foreach ($id as $v) {
        $v = intval($v);
        $t1 = $db->getone("select * from " . table('jobs') . " where id='{$v}' {$uidsql} LIMIT 1");
        $t2 = $db->getone("select * from " . table('jobs_tmp') . " where id='{$v}' {$uidsql} LIMIT 1");
        if ((empty($t1) && empty($t2)) || (!empty($t1) && !empty($t2))) {
            continue;
        } else {
            $j = !empty($t1) ? $t1 : $t2;
            if (!empty($t1) && $j['audit'] == "1" && $j['display'] == "1" && $j['user_status'] == "1") {
                if ($_CFG['outdated_jobs'] == "1") {
                    if ($j['deadline'] > $time && ($j['setmeal_deadline'] == "0" || $j['setmeal_deadline'] > $time)) {
                        continue;
                    }
                } else {
                    continue;
                }
            } elseif (!empty($t2)) {
                if ($j['audit'] != "1" || $j['display'] != "1" || $j['user_status'] != "1") {
                    continue;
                } else {
                    if ($_CFG['outdated_jobs'] == "1" && ($j['deadline'] < $time || ($j['setmeal_deadline'] < $time && $j['setmeal_deadline'] != "0"))) {
                        continue;
                    }
                }
            }
            //检测完毕
            $j = array_map('addslashes', $j);
            if (!empty($t1)) {
                $sql = "select id from " . table('jobs') . " WHERE id='{$v}' {$uidsql}  LIMIT 1";
                $del_id_arr = $db->getone($sql);
                $db->query("Delete from " . table('jobs_tmp') . " WHERE id='{$v}' {$uidsql}  LIMIT 1");
                $db->query("Delete from " . table('jobs') . " WHERE id='{$v}' {$uidsql}  LIMIT 1");
                if (!empty($del_id_arr['id'])) {
                    $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id IN(" . $del_id_arr['id'] . ") and type = 'jobs'  LIMIT 1 ");
                }
                if (inserttable(table('jobs_tmp'), $j)) {
                    $db->query("Delete from " . table('jobs_search_hot') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_key') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_rtime') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_scale') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_stickrtime') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_wage') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_tag') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                }
            } else {
                $sql = "select id from " . table('jobs_tmp') . " WHERE id='{$v}' {$uidsql}  LIMIT 1";
                $del_id_arr = $db->getone($sql);
                $db->query("Delete from " . table('jobs') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                $db->query("Delete from " . table('jobs_tmp') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                if (!empty($del_id_arr['id'])) {
                    $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id = " . $del_id_arr['id'] . " and type = 'jobs'  LIMIT 1 ");
                }
                if (inserttable(table('jobs'), $j)) {
                    $searchtab['id'] = $j['id'];
                    $searchtab['uid'] = $j['uid'];
                    $searchtab['subsite_id'] = $j['subsite_id'];
                    $searchtab['recommend'] = $j['recommend'];
                    $searchtab['emergency'] = $j['emergency'];
                    $searchtab['nature'] = $j['nature'];
                    $searchtab['sex'] = $j['sex'];
                    $searchtab['topclass'] = $j['topclass'];
                    $searchtab['category'] = $j['category'];
                    $searchtab['subclass'] = $j['subclass'];
                    $searchtab['trade'] = $j['trade'];
                    $searchtab['district'] = $j['district'];
                    $searchtab['sdistrict'] = $j['sdistrict'];
                    $searchtab['street'] = $j['street'];
                    $searchtab['education'] = $j['education'];
                    $searchtab['experience'] = $j['experience'];
                    $searchtab['wage'] = $j['wage'];
                    $searchtab['refreshtime'] = $j['refreshtime'];
                    $searchtab['scale'] = $j['scale'];
                    //--
                    inserttable(table('jobs_search_wage'), $searchtab);
                    inserttable(table('jobs_search_scale'), $searchtab);
                    //--
                    $searchtab['map_x'] = $j['map_x'];
                    $searchtab['map_y'] = $j['map_y'];
                    inserttable(table('jobs_search_rtime'), $searchtab);
                    unset($searchtab['map_x'], $searchtab['map_y']);
                    //--
                    $searchtab['stick'] = $j['stick'];
                    inserttable(table('jobs_search_stickrtime'), $searchtab);
                    unset($searchtab['stick']);
                    //--
                    $searchtab['click'] = $j['click'];
                    inserttable(table('jobs_search_hot'), $searchtab);
                    unset($searchtab['click']);
                    //--
                    $searchtab['key'] = $j['key'];
                    $searchtab['map_x'] = $j['map_x'];
                    $searchtab['map_y'] = $j['map_y'];
                    $searchtab['likekey'] = $j['jobs_name'] . ',' . $j['companyname'];
                    inserttable(table('jobs_search_key'), $searchtab);
                    unset($searchtab);
                    $tag = explode('|', $j['tag']);
                    $tagindex = 1;
                    $tagsql['tag1'] = $tagsql['tag2'] = $tagsql['tag3'] = $tagsql['tag4'] = $tagsql['tag5'] = 0;
                    if (!empty($tag) && is_array($tag)) {
                        foreach ($tag as $k => $v) {
                            if ($k < 5) {
                                $vid = explode(',', $v);
                                $tagsql['tag' . $tagindex] = intval($vid[0]);
                                $tagindex++;
                            }
                        }
                    }
                    $tagsql['id'] = $j['id'];
                    $tagsql['uid'] = $j['uid'];
                    $tagsql['subsite_id'] = $j['subsite_id'];
                    $tagsql['topclass'] = $j['topclass'];
                    $tagsql['category'] = $j['category'];
                    $tagsql['subclass'] = $j['subclass'];
                    $tagsql['district'] = $j['district'];
                    $tagsql['sdistrict'] = $j['sdistrict'];
                    inserttable(table('jobs_search_tag'), $tagsql);
                }
            }
        }
    }
}

function distribution_jobs_uid($uid) {
    global $db;
    $uid = intval($uid);
    $result = $db->query("select id from " . table('jobs') . " where uid={$uid} UNION ALL select id from " . table('jobs_tmp') . " where uid={$uid} ");
    while ($row = $db->fetch_array($result)) {
        $id[] = $row['id'];
    }
    if (!empty($id)) {
        return distribution_jobs($id, $uid);
    }
}

function get_points_rule() {
    global $db;
    $sql = "select * from " . table('members_points_rule') . " WHERE utype='1' ORDER BY id asc";
    return $db->getall($sql);
}

function get_members_setmeal_rule($id) {
    global $db;
    $sql = "select * from " . table('setmeal') . " WHERE id='{$id}' limit 1";
    return $db->getone($sql);
}

function get_user_points($uid) {
    global $db;
    $uid = intval($uid);
    $points = $db->getone("select points from " . table('members_points') . " where uid ='{$uid}' LIMIT 1");
    return $points['points'];
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

function get_payment() {
    global $db;
    $sql = "select * from " . table('payment') . " where p_install='2' ORDER BY listorder desc";
    $list = $db->getall($sql);
    return $list;
}

function get_points_setmeal($id = 0) {
    global $db;
    $where = $id > 0 ? " where id = " . $id : "";
    $sql = "select * from " . table('jiaoshi_setmeal') . $where;
    if ($id > 0) {
        $list = $db->getone($sql);
    } else {
        $list = $db->getall($sql);
    }
    return $list;
}

function get_setmeal_activity($id = 0) {
    global $db;
    $where = $id > 0 ? " where id = " . $id : "";
    $order_by = " order by setmeal_id desc,activity_order desc";
    $sql = "select * from " . table('jiaoshi_setmeal_activity') . $where . $order_by;
    if ($id > 0) {
        $list = $db->getone($sql);
    } else {
        $list = $db->getall($sql);
    }
    return $list;
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
function add_order($uid, $oid, $amount, $days = 0, $payment_name, $description, $addtime, $points = '', $setmeal = '', $activity_info = array(), $utype = '1') {
    global $db;
    $setsqlarr['uid'] = intval($uid);
    $setsqlarr['oid'] = $oid;
    $setsqlarr['amount'] = $amount;
    $setsqlarr['days'] = $days;
    $setsqlarr['payment_name'] = $payment_name;
    $setsqlarr['description'] = $description;
    $setsqlarr['addtime'] = $addtime;
    $setsqlarr['points'] = $points;
    $setsqlarr['setmeal'] = $setmeal;
    if (!empty($activity_info['id'])) {
        $setsqlarr['activity_id'] = $activity_info['id'];
        $setsqlarr['activity'] = $activity_info['activity_name'];
    }
    $setsqlarr['utype'] = $utype;
    write_memberslog($uid, 1, 3001, $_SESSION['username'], "添加订单，编号{$oid}，金额{$amount}元");
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

//增加广告位订单
function add_adv_order($uid, $oid, $amount, $time, $time_unit, $payment_name, $description, $addtime, $points, $contact, $phone) {
    global $db;
    if ($points == "1") {
        $setsqlarr['is_points'] = 1;
    }
    $setsqlarr['uid'] = intval($uid);
    $setsqlarr['oid'] = $oid;
    $setsqlarr['amount'] = $amount;
    switch ($time_unit) {
        case "expense":
            $setsqlarr['week'] = $time;
            break;
        case "expense_month":
            $setsqlarr['month'] = $time;
            break;
        case "expense_year":
            $setsqlarr['year'] = $time;
            break;
        case "expense_piece":
            $setsqlarr['piece'] = $time;
            break;
        default:
            $setsqlarr['week'] = $time;
            break;
    }
    $setsqlarr['payment_name'] = $payment_name;
    $setsqlarr['description'] = $description;
    $setsqlarr['is_points'] = $points;
    $setsqlarr['addtime'] = $addtime;
    $setsqlarr['contact'] = $contact;
    $setsqlarr['phone'] = $phone;
    write_memberslog($uid, 1, 3001, $_SESSION['username'], "添加订单，编号{$oid}，金额{$amount}元");
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
    return inserttable(table('adv_order'), $setsqlarr, true);
}

//取消订单
function del_order($uid, $id) {
    global $db;
    write_memberslog($_SESSION['uid'], 1, 3002, $_SESSION['username'], "取消订单，订单id{$id}");
    return $db->query("Delete from " . table('order') . " WHERE id='" . intval($id) . "' AND uid=" . intval($uid) . " AND is_paid=1  LIMIT 1 ");
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

//获取指点会员订单
function get_user_order($uid, $is_paid) {
    global $db;
    $sql = "select * from " . table('order') . " WHERE uid=" . intval($uid) . " AND  is_paid='" . intval($is_paid) . "' ORDER BY id desc";
    return $db->getall($sql);
}

//取消订单
function del_adv_order($uid, $id) {
    global $db;
    write_memberslog($_SESSION['uid'], 1, 3002, $_SESSION['username'], "取消广告订单，广告订单id{$id}");
    return $db->query("Delete from " . table('adv_order') . " WHERE id='" . intval($id) . "' AND uid=" . intval($uid) . " AND is_paid=1  LIMIT 1 ");
}

//获取充值记录列表
function get_adv_order_all($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT " . $offset . ',' . $perpage;
    }
    $result = $db->query("SELECT * FROM " . table('adv_order') . " " . $get_sql . " ORDER BY id DESC " . $limit);
    while ($row = $db->fetch_array($result)) {
        if ($row['is_points'] == "0") {
            $row['payment_name'] = get_payment_info($row['payment_name'], true);
        }
        if ($row['is_points'] == "1") {
            $row['amount'] = intval($row['amount']);
        }
        $row_arr[] = $row;
    }
    return $row_arr;
}

//获取指点会员订单
function get_user_adv_order($uid) {
    global $db;
    $sql = "select * from " . table('adv_order') . " WHERE uid=" . intval($uid) . " ORDER BY id desc";
    return $db->getall($sql);
}

//获取单条订单
function get_order_one($uid, $id) {
    global $db;
    $sql = "select * from " . table('order') . " where id =" . intval($id) . " AND uid = " . intval($uid) . "  AND is_paid =1  LIMIT 1 ";
    return $db->getone($sql);
}

//付款后开通
function order_paid($v_oid) {
    global $db, $timestamp, $_CFG;
    $order = $db->getone("select * from " . table('order') . " WHERE oid ='{$v_oid}' AND is_paid= '1' LIMIT 1 ");
    pay_test_log("查询订单信息id：" . $order['id']);
    if ($order) {
        $user = get_user_info($order['uid']);
        pay_test_log("查询用户id：" . $user['id']);
        $sql = "UPDATE " . table('order') . " SET is_paid= '2',payment_time='{$timestamp}' WHERE oid='{$v_oid}' LIMIT 1 ";
        pay_test_log("执行更新支付状态语句：" . $sql);
        if (!$db->query($sql)) {
            pay_test_log("执行更新支付状态语句失败");
            return false;
        }
        if ($order['amount'] == '0.00') {
            $ismoney = 1;
        } else {
            $ismoney = 2;
        }
        pay_test_log("本次订单积分：" . $order['points']);
        pay_test_log("本次订单套餐：" . $order['setmeal']);
        if ($order['days'] > 0) {
            set_members_limit_points($order['uid'], 1, $order['points'], $order['days']);
            $user_points = get_user_limit_points($order['uid']);
            $notes = date('Y-m-d H:i', time()) . "通过：" . get_payment_info($order['payment_name'], true) . " 成功充值 " . $order['amount'] . "元，(+{$order['points']})，(限期积分剩余:{$user_points['points']})，有效期至" . date("Y-m-d", $user_points['endtime']) . "，订单:{$v_oid}";
            pay_test_log("写入系统日志：" . $notes . " \r\n");
            write_memberslog($order['uid'], 1, 9001, $user['username'], $notes);
            //会员套餐变更记录。会员购买成功。2表示：会员自己购买
            write_setmeallog($order['uid'], $user['username'], $notes, 2, $order['amount'], $ismoney, 1);
        } elseif ($order['points'] > 0) {
            report_deal($order['uid'], 1, $order['points']);
            $user_points = get_user_points($order['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $notes = date('Y-m-d H:i', time()) . "通过：" . get_payment_info($order['payment_name'], true) . " 成功充值 " . $order['amount'] . "元，(+{$order['points']})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points}),订单:{$v_oid}";
            pay_test_log("写入系统日志：" . $notes . " \r\n");
            write_memberslog($order['uid'], 1, 9001, $user['username'], $notes);
            //会员套餐变更记录。会员购买成功。2表示：会员自己购买
            write_setmeallog($order['uid'], $user['username'], $notes, 2, $order['amount'], $ismoney, 1);
        } elseif ($order['setmeal'] > 0) {
            set_members_setmeal($order['uid'], $order['setmeal']);
            $setmeal = get_setmeal_one($order['setmeal']);
            $notes = date('Y-m-d H:i', time()) . "通过：" . get_payment_info($order['payment_name'], true) . " 成功充值 " . $order['amount'] . "元并开通{$setmeal['setmeal_name']}";
            write_memberslog($order['uid'], 1, 9002, $user['username'], $notes);
            //会员套餐变更记录。会员购买成功。2表示：会员自己购买
            write_setmeallog($order['uid'], $user['username'], $notes, 2, $order['amount'], $ismoney, 2, 1);
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

function get_adv_order_one($uid, $id) {
    global $db;
    $sql = "select * from " . table('adv_order') . " where id =" . intval($id) . " AND uid = " . intval($uid) . "  AND is_paid =1  LIMIT 1 ";
    return $db->getone($sql);
}

//申请广告位付款后开通
function adv_order_paid($v_oid) {
    global $db, $timestamp, $_CFG;
    $order = $db->getone("select * from " . table('adv_order') . " WHERE oid ='{$v_oid}' AND is_paid= '1' LIMIT 1 ");
    if ($order) {
        $user = get_user_info($order['uid']);
        $sql = "UPDATE " . table('adv_order') . " SET is_paid= '2',payment_time='{$timestamp}' WHERE oid='{$v_oid}' LIMIT 1 ";
        if (!$db->query($sql))
            return false;
        if ($order['is_points'] == "1" && $order['amount'] > 0) {
            report_deal($order['uid'], 2, intval($order['amount']));
            $user_points = get_user_points($order['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "申请广告位：<strong>{$order['description']}</strong>，(- {$order['amount']})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})", 1, 1020, "申请广告位", "- {$order['amount']}", "{$user_points}");
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

/////*****************************招聘管理部分
function get_auditjobs($uid) {
    global $db;
    $uid = intval($uid);
    return $db->getall("select * from " . table('jobs') . " WHERE uid={$uid}");
}

//把简历添加到已下载中
function add_down_resume($resume_id, $company_uid, $resume_uid, $resume_name) {
    global $db, $timestamp;
    $resume_id = intval($resume_id);
    $company_uid = intval($company_uid);
    $resume_uid = intval($resume_uid);
    $resume_name = trim($resume_name);
    $company = get_company($company_uid);
    $sql = "INSERT INTO " . table('company_down_resume') . " (resume_id,resume_uid,resume_name,company_uid,company_name,down_addtime) VALUES ('{$resume_id}','{$resume_uid}','{$resume_name}','{$company_uid}','{$company['companyname']}','{$timestamp}')";
    return $db->query($sql);
}

//已下载的简历列表
function get_down_resume($offset, $perpage, $get_sql = '') {
    global $db;
    $limit = " LIMIT " . intval($offset) . ',' . intval($perpage);
    $selectstr = " d.*,r.subsite_id,r.sex_cn,r.fullname,r.talent,r.birthdate,r.wage_cn,r.display_name,r.experience_cn,r.district_cn,r.education_cn,r.intention_jobs,r.talent,r.addtime,r.refreshtime,r.attachment_resume ";
    $result = $db->query("SELECT " . $selectstr . " FROM " . table('company_down_resume') . " as d  " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['age'] = date("Y") - $row['birthdate'];
        $row['resume_url'] = url_rewrite('QS_resumeshow', array('id' => $row['resume_id']), true, $row['subsite_id']);
        $row['intention_jobs_'] = $row['intention_jobs'];
        $row['intention_jobs'] = cut_str($row['intention_jobs'], 30, 0, "...");
        if ($row['display_name'] == "2") {
            $row['resume_name'] = "N" . str_pad($row['resume_id'], 7, "0", STR_PAD_LEFT);
        } elseif ($row['display_name'] == "3") {
            $row['resume_name'] = cut_str($row['fullname'], 1, 0, "**");
        }
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_down_resume($id, $uid) {
    global $db;
    $wheresql = " AND company_uid={$uid}";
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        write_memberslog($_SESSION['uid'], 1, 4002, $_SESSION['username'], "删除已下载简历({$sqlin})");
        $return = 0;
        $db->query("Delete from " . table('company_down_resume') . " WHERE did IN ({$sqlin}) {$wheresql}");
        $return = $return + $db->affected_rows();
        return $return;
    }
}

function del_apply_jobs($id, $uid) {
    global $db;
    $wheresql = " AND company_uid={$uid}";
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        write_memberslog($_SESSION['uid'], 1, 4002, $_SESSION['username'], "删除职位申请({$sqlin})");
        $return = 0;
        $sql = "select resume_id from " . table('personal_jobs_apply') . " WHERE did IN ({$sqlin}) ";
        $resumeid = $db->getall($sql);
        foreach ($resumeid as $r) {
            $db->query("Delete from " . table('view_resume') . " WHERE resumeid = {$r['resume_id']} {$wheresql}");
        }
        $db->query("Delete from " . table('personal_jobs_apply') . " WHERE did IN ({$sqlin}) {$wheresql}");
        $return = $return + $db->affected_rows();
        return $return;
    }
}

//下载简历转移到人才库
function down_to_favorites($did, $company_uid) {
    global $db;
    if (!is_array($did))
        $did = array($did);
    $sqlin = implode(",", $did);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    $sql = "select resume_id from " . table('company_down_resume') . " WHERE did IN ({$sqlin}) ";
    $resumeid = $db->getall($sql);
    if ($resumeid) {
        foreach ($resumeid as $rid) {
            $arrid[] = $rid['resume_id'];
        }
        return add_favorites($arrid, $company_uid);
    }
}

//已查看的简历转移到人才库
function attention_to_favorites($did, $company_uid) {
    global $db;
    if (!is_array($did))
        $did = array($did);
    $sqlin = implode(",", $did);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    $sql = "select resumeid from " . table('view_resume') . " WHERE id IN ({$sqlin}) ";
    $resumeid = $db->getall($sql);
    if ($resumeid) {
        foreach ($resumeid as $rid) {
            $arrid[] = $rid['resumeid'];
        }
        return add_favorites($arrid, $company_uid);
    }
}

function add_favorites($id, $company_uid) {
    global $db, $_CFG;
    $timestamp = time();
    $setmeal = get_user_setmeal($company_uid);
    $count = count_favorites($company_uid);
    if (strpos($id, "-"))
        $id = explode("-", $id);
    if (!is_array($id))
        $id = array($id);
    $i = 0;
    foreach ($id as $v) {
        $v = intval($v);
        if ($_CFG['operation_mode'] == "2" || $_CFG['operation_mode'] == "3") {
            if ($count >= $setmeal['talent_pool']) {
                return "full";
            }
        }
        if (!check_favorites($v, $company_uid)) {
            $db->query("INSERT INTO " . table('company_favorites') . " (resume_id,company_uid,favoritesa_ddtime) VALUES ('{$v}','{$company_uid}','{$timestamp}')");
            write_memberslog($_SESSION['uid'], 1, 5001, $_SESSION['username'], "将简历({$v})添加至人才库");
            $i++;
        }
    }
    write_memberslog($_SESSION['uid'], 1, 9002, $_SESSION['username'], "添加简历至人才库", 2, 1017, "添加人才库", $i, $count - $i);
    return $i;
}

//检测人才库中是否已经存在
function check_favorites($resume_id, $company_uid) {
    global $db;
    $company_uid = intval($company_uid);
    $resume_id = intval($resume_id);
    $sql = "select * from " . table('company_favorites') . " WHERE company_uid ='{$company_uid}' AND resume_id='{$resume_id}' LIMIT 1";
    $info = $db->getone($sql);
    if (empty($info)) {
        return false;
    } else {
        return true;
    }
}

//获取企业人才库
function get_favorites($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    if (isset($offset) && !empty($perpage))
        $limit = " LIMIT " . $offset . ',' . $perpage;
    $selectstr = "f.*,r.subsite_id,r.fullname,r.wage_cn,r.display_name,r.sex_cn,r.birthdate,r.education_cn,r.experience_cn,r.intention_jobs,r.talent,r.district_cn,r.addtime,r.refreshtime,r.attachment_resume";
    $result = $db->query("SELECT " . $selectstr . "  FROM " . table('company_favorites') . " AS f " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['age'] = date("Y") - $row['birthdate'];
        $row['intention_jobs_'] = $row['intention_jobs'];
        $row['intention_jobs'] = cut_str($row['intention_jobs'], 30, 0, "...");
        $row['resume_url'] = url_rewrite('QS_resumeshow', array('id' => $row['resume_id']), true, $row['subsite_id']);
        if ($row['display_name'] == "2") {
            $row['fullname'] = "N" . str_pad($row['resume_id'], 7, "0", STR_PAD_LEFT);
        } elseif ($row['display_name'] == "3") {
            $row['fullname'] = cut_str($row['fullname'], 1, 0, "**");
        }
        $row_arr[] = $row;
    }
    return $row_arr;
}

//删除 -人才库中的简历
function del_favorites($del_id, $uid) {
    global $db;
    $uid = intval($uid);
    $uidsql = " AND company_uid='{$uid}'";
    if (!is_array($del_id)) {
        $del_id = array($del_id);
    }
    $sqlin = implode(",", $del_id);
    $return = 0;
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("Delete from " . table('company_favorites') . " WHERE did IN ({$sqlin}) {$uidsql}"))
            return false;
        $return = $return + $db->affected_rows();
        write_memberslog($_SESSION['uid'], $_SESSION['utype'], 5002, $_SESSION['username'], "删除人才库人才({$sqlin})");
        return $return;
    }
}

function check_down_resumeid($resume_id, $company_uid) {
    global $db;
    $company_uid = intval($company_uid);
    $resume_id = intval($resume_id);
    $sql = "select did from " . table('company_down_resume') . " WHERE company_uid = '{$company_uid}' AND resume_id='{$resume_id}' LIMIT 1";
    $info = $db->getone($sql);
    if (empty($info)) {
        return false;
    } else {
        return true;
    }
}

function check_interview($resume_id, $jobs_id, $company_uid) {
    global $db;
    $resume_id = intval($resume_id);
    $jobs_id = intval($jobs_id);
    $company_uid = intval($company_uid);
    $sql = "select * from " . table('company_interview') . " WHERE company_uid ='{$company_uid}' AND resume_id='{$resume_id}' AND jobs_id='{$jobs_id}' LIMIT 1";
    return $db->getone($sql);
}

//删除 -邀请记录
function del_interview($del_id, $uid) {
    global $db;
    $uidsql = " AND company_uid=" . intval($uid) . "";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('company_interview') . " WHERE did IN ({$sqlin}) {$uidsql}"))
        return false;
    write_memberslog($_SESSION['uid'], 1, 6002, $_SESSION['username'], "删除面试邀请({$sqlin})");
    return true;
}

//邀请记录列表
function get_interview($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    if (isset($offset) && !empty($perpage))
        $limit = " LIMIT " . $offset . ',' . $perpage;
    $selectstr = "i.*,r.subsite_id,r.fullname,r.talent,r.birthdate,r.display_name,r.sex_cn,r.education_cn,r.experience_cn,r.intention_jobs,r.district_cn,r.refreshtime";
    $result = $db->query("SELECT  {$selectstr}  FROM " . table('company_interview') . " as i " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['age'] = date("Y") - $row['birthdate'];
        $row['resume_url'] = url_rewrite('QS_resumeshow', array('id' => $row['resume_id']), true, $row['subsite_id']);
        $row['intention_jobs'] = cut_str($row['intention_jobs'], 30, 0, "...");
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_apply_jobs($offset, $perpage, $get_sql = '') {
    global $db;
    $limit = " LIMIT {$offset},{$perpage}";
    $selectstr = " a.*,r.subsite_id,r.sex_cn,r.talent,r.birthdate,r.wage_cn,r.fullname,r.experience_cn,r.district_cn,r.education_cn,r.intention_jobs,r.specialty,r.click,r.refreshtime,r.attachment_resume,r.addtime as  resume_addtime";
    $result = $db->query("SELECT {$selectstr} FROM " . table('personal_jobs_apply') . " as a " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['age'] = date("Y") - $row['birthdate'];
        $row['specialty_'] = $row['specialty'];
        $row['specialty'] = cut_str($row['specialty'], 30, 0, "...");
        $row['resume_url'] = url_rewrite('QS_resumeshow', array('id' => $row['resume_id'], 'apply' => 1), true, $row['subsite_id']);
        $row['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $row['jobs_id']), false);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function set_apply($id, $uid, $setlook) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    $setsqlarr['personal_look'] = intval($setlook);
    $wheresql = " did IN (" . $sqlin . ") AND company_uid=" . intval($uid) . "";
    foreach ($id as $aid) {
        $sql = "select m.username from " . table('personal_jobs_apply') . " AS a JOIN " . table('members') . " AS m ON a.personal_uid=m.uid WHERE a.did='{$aid}' LIMIT 1";
        $user = $db->getone($sql);
        write_memberslog($_SESSION['uid'], 1, 2006, $_SESSION['username'], "查看了 {$user['username']} 的职位申请");
    }
    return updatetable(table('personal_jobs_apply'), $setsqlarr, $wheresql);
}


//已发布职位总数
function count_jobs($uid) {
    global $db;
    $uid = intval($uid);
    $wheresql = " WHERE uid='{$uid}' ";
    ;
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('jobs') . $wheresql . " UNION ALL  SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . $wheresql;
    return $db->get_total($total_sql);
}

//已下载简历总数
function count_down_resume($uid) {
    global $db;
    $uid = intval($uid);
    $wheresql = " where d.company_uid='{$uid}' ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_down_resume') . " AS d " . $wheresql;
    return $db->get_total($total_sql);
}

//收到的职位申请总数
function count_jobs_apply($uid, $look = '') {
    global $db;
    $uid = intval($uid);
    $look = intval($look);
    $wheresql = "";
    if ($look > 0) {
        $wheresql.=" AND a.personal_look='{$look}' ";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . " AS a WHERE a.company_uid='{$uid}' {$wheresql}";
    return $db->get_total($total_sql);
}

function count_interview($uid) {
    global $db;
    $wheresql = " WHERE company_uid=" . intval($uid) . " ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_interview') . $wheresql;
    return $db->get_total($total_sql);
}

function count_favorites($uid) {
    global $db;
    $$uid = intval($uid);
    $wheresql = " WHERE f.company_uid='{$uid}'";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_favorites') . " AS f " . $wheresql;
    return $db->get_total($total_sql);
}

function get_user_info($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('members') . " where uid = '{$uid}' LIMIT 1";
    return $db->getone($sql);
}

function get_userprofile($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('members_info') . " where uid ='{$uid}' LIMIT 1";
    return $db->getone($sql);
}

function set_user_status($status, $uid) {
    global $db;
    $status = intval($status);
    $uid = intval($uid);
    if (!$db->query("UPDATE " . table('members') . " SET status= {$status} WHERE uid={$uid} LIMIT 1"))
        return false;
    if (!$db->query("UPDATE " . table('company_profile') . " SET user_status= {$status} WHERE uid={$uid} "))
        return false;
    if (!$db->query("UPDATE " . table('jobs') . " SET user_status= {$status} WHERE uid={$uid} "))
        return false;
    if (!$db->query("UPDATE " . table('jobs_tmp') . " SET user_status= {$status} WHERE uid={$uid} "))
        return false;
    distribution_jobs_uid($uid);
    write_memberslog($_SESSION['uid'], 1, 1003, $_SESSION['username'], "修改帐号状态");
    return true;
}

function get_feedback($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('feedback') . " where uid='{$uid}' ORDER BY id desc";
    $list = $db->getall($sql);
    return $list;
}

function del_feedback($id, $uid) {
    global $db;
    if (!$db->query("Delete from " . table('feedback') . " WHERE id='" . intval($id) . "' AND uid='" . intval($uid) . "'  "))
        return false;
    write_memberslog($_SESSION['uid'], 1, 7002, $_SESSION['username'], "删除反馈信息:({$del_id})");
    return true;
}

function set_consultant($uid) {
    global $db;
    $consultant = $db->getall("select id from " . table('consultant'));
    if ($consultant) {
        foreach ($consultant as $key => $value) {
            $arr[$value['id']] = $value['id'];
        }
        $rand = array_rand($arr, 1);
        updatetable(table('members'), array("consultant" => $rand), " uid=" . $uid . " ");
    }
    return true;
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
    } elseif ($days > 0) {
        $up_limit['uid'] = $uid;
        $up_limit['points'] = $points;
        $up_limit['addtime'] = time();
        $up_limit['endtime'] = time() + ($days * 86400);
        inserttable(table('members_points_limit'), $up_limit);
    } else {
        $p = $points;
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

//++++++++++++++++++++++++++++套餐模式
function set_members_setmeal($uid, $setmealid) {
    global $db, $timestamp;
    $setmeal = $db->getone("select * from " . table('setmeal') . " WHERE id = " . intval($setmealid) . " AND display=1 LIMIT 1");
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
    $setsqlarr['jobs_ordinary'] = $setmeal['jobs_ordinary'];
    $setsqlarr['download_resume_ordinary'] = $setmeal['download_resume_ordinary'];
    $setsqlarr['download_resume_senior'] = $setmeal['download_resume_senior'];
    $setsqlarr['interview_ordinary'] = $setmeal['interview_ordinary'];
    $setsqlarr['interview_senior'] = $setmeal['interview_senior'];
    $setsqlarr['talent_pool'] = $setmeal['talent_pool'];

    $setsqlarr['recommend_num'] = $setmeal['recommend_num'];
    $setsqlarr['recommend_days'] = $setmeal['recommend_days'];
    $setsqlarr['stick_num'] = $setmeal['stick_num'];
    $setsqlarr['stick_days'] = $setmeal['stick_days'];
    $setsqlarr['emergency_num'] = $setmeal['emergency_num'];
    $setsqlarr['emergency_days'] = $setmeal['emergency_days'];
    $setsqlarr['highlight_num'] = $setmeal['highlight_num'];
    $setsqlarr['highlight_days'] = $setmeal['highlight_days'];
    $setsqlarr['change_templates'] = $setmeal['change_templates'];
    $setsqlarr['jobsfair_num'] = $setmeal['jobsfair_num'];
    $setsqlarr['map_open'] = $setmeal['map_open'];

    $setsqlarr['added'] = $setmeal['added'];
    $setsqlarr['refresh_jobs_space'] = $setmeal['refresh_jobs_space'];
    $setsqlarr['refresh_jobs_time'] = $setmeal['refresh_jobs_time'];
    if (!updatetable(table('members_setmeal'), $setsqlarr, " uid='{$uid}'"))
        return false;
    $setmeal_jobs['setmeal_deadline'] = $setsqlarr['endtime'];
    $setmeal_jobs['setmeal_id'] = $setsqlarr['setmeal_id'];
    $setmeal_jobs['setmeal_name'] = $setsqlarr['setmeal_name'];
    if (!updatetable(table('jobs'), $setmeal_jobs, " uid='{$uid}' AND add_mode='2' "))
        return false;
    if (!updatetable(table('jobs_tmp'), $setmeal_jobs, " uid='{$uid}' AND add_mode='2' "))
        return false;
    if (!updatetable(table('hunter_jobs'), $setmeal_jobs, " uid='{$uid}' AND add_mode='2' "))
        return false;
    distribution_jobs_uid($uid);
    return true;
}

function get_setmeal($apply = false) {
    global $db;
    if ($apply) {
        $wheresql = " AND apply=1";
    }
    $sql = "select * from " . table('setmeal') . " WHERE display=1 {$wheresql} ORDER BY show_order desc";
    return $db->getall($sql);
}

function get_setmeal_one($id) {
    global $db;
    $id = intval($id);
    $sql = "select * from " . table('setmeal') . "  WHERE id='{$id}'  LIMIT 1";
    return $db->getone($sql);
}

function get_jobfair($offset, $perpage, $sql = '', $oederbysql) {
    global $db;
    $row_arr = array();
    $time = time();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT * FROM " . table('jobfair') . " " . $sql . " " . $oederbysql . " " . $limit);
    $week = array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
    while ($row = $db->fetch_array($result)) {
        $color = $row['color'] ? "color:" . $row['color'] . ";" : '';
        $weight = $row['weight'] > 0 ? "font-weight:bold;" : '';
        if ($color || $weight) {
            $row['title'] = "<span style=\"{$color}{$weight}\">{$row['title']}</span>";
        }
        if ($row['predetermined_status'] == "1" && $row['holddates'] > $time && $time > $row['predetermined_start'] && ($row['predetermined_end'] == "0" || $row['predetermined_end'] > $time) && ($row['predetermined_web'] == "1" || $row['predetermined_tel'] == "1")) {
            $row['predetermined_ok'] = 1;
        } else {
            $row['predetermined_ok'] = 0;
        }
        $row['holddates_week'] = $week[date("w", $row['holddates'])];
        $row['url'] = url_rewrite("QS_jobfairshow", array('id' => $row['id']), false);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_jobfair_exhibitors($offset, $perpage, $sql = '') {
    global $db;
    $row_arr = array();
    $time = time();
    $limit = " LIMIT {$offset},{$perpage}";
    $result = $db->query("SELECT e.*,j.subsite_id,j.title,j.holddates,j.addtime FROM " . table('jobfair_exhibitors') . " AS e " . $sql . " " . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['url'] = url_rewrite("QS_jobfairshow", array('id' => $row['jobfairid']), true, $row['subsite_id']);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_user_setmeal($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('members_setmeal') . "  WHERE uid='{$uid}' AND  effective=1 LIMIT 1";
    return $db->getone($sql);
}

function action_user_setmeal($uid, $action) {
    global $db;
    $sql = "update " . table('members_setmeal') . " set `" . $action . "`=" . $action . "-1  WHERE uid=" . intval($uid) . "  AND  effective=1 LIMIT 1";
    return $db->query($sql);
}

function get_resume_basic($id) {
    global $db;
    $id = intval($id);
    $val = $db->getone("select * from " . table('resume') . " where id='{$id}' LIMIT 1 ");
    if ($val['display_name'] == "2") {
        $val['fullname'] = "N" . str_pad($val['id'], 7, "0", STR_PAD_LEFT);
    } elseif ($val['display_name'] == "3") {
        $val['fullname'] = cut_str($val['fullname'], 1, 0, "**");
    }
    return $val;
}

//推荐人才
function get_concern_id($uid) {
    global $db;
    $uid = intval($uid);
    $tb1 = $db->getall("select id,topclass,category from " . table('jobs') . " where uid='{$uid}' LIMIT 10");
    $tb2 = $db->getall("select id,topclass,category from " . table('jobs_tmp') . " where uid='{$uid}' LIMIT 10");
    $info = !empty($tb1) ? $tb1 : $tb2;
    if (!empty($info) && is_array($info)) {
        foreach ($info as $s) {
            $str[] = $s['topclass'];
        }
        return implode("-", array_unique($str));
    }
    return "";
}

function get_comtpl() {
    global $db;
    $sql = "select * from " . table('tpl') . " where tpl_type =1 AND tpl_display=1";
    return $db->getall($sql);
}

function get_comtpl_one($dir) {
    global $db;
    $sql = "select * from " . table('tpl') . " WHERE tpl_dir = '{$dir}' AND tpl_type =1 AND tpl_display=1 LIMIT 1";
    return $db->getone($sql);
}

function get_promotion_category() {
    global $db;
    $sql = "select * from " . table('promotion_category') . " where cat_available =1  ORDER BY cat_order DESC";
    return $db->getall($sql);
}

function get_promotion_category_one($id) {
    global $db;
    $id = intval($id);
    $sql = "select * from " . table('promotion_category') . " where cat_id='{$id}' AND cat_available =1 LIMIT 1";
    return $db->getone($sql);
}

function get_promotion_one($jobid, $uid, $promotionid) {
    global $db;
    $jobid = intval($jobid);
    $sql = "select * from " . table('promotion') . " where cp_jobid='{$jobid}' AND cp_uid='{$uid}' AND cp_promotionid='{$promotionid}'  LIMIT 1";
    return $db->getone($sql);
}

function get_promotion($uid, $promotionid) {
    global $db;
    $promotionid = intval($promotionid);
    if ($promotionid) {
        $sql = "select p.*,j.jobs_name,j.id,j.refreshtime,j.highlight from " . table('promotion') . " AS p JOIN " . table('jobs') . " AS j ON p.cp_jobid=j.id WHERE p.cp_uid='{$uid}' AND p.cp_promotionid='{$promotionid}' ";
    } else {
        $sql = "select p.*,j.jobs_name,j.id,j.refreshtime,j.highlight from " . table('promotion') . " AS p JOIN " . table('jobs') . " AS j ON p.cp_jobid=j.id WHERE p.cp_uid='{$uid}' ";
    }
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        $row['jobs_name'] = cut_str($row['jobs_name'], 12, 0, "...");
        if (empty($row['jobs_name'])) {
            $row['jobs_name'] = "职位已经删除";
        }
        if (!empty($row['highlight'])) {
            $row['jobs_name'] = "<span style=\"color:{$row['highlight']}\">{$row['jobs_name']}</span>";
        }
        $row['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $row['id']), false);
        if (empty($row['jobs_name'])) {
            $row['jobs_url'] = "javascript:void(0)";
        }
        $row_arr[] = $row;
    }

    return $row_arr;
}

function set_job_promotion($jobid, $type, $val = '') {
    global $db;
    $jobid = intval($jobid);
    if ($type == "1") {
        $db->query("UPDATE " . table('jobs') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_tmp') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_hot') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_key') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_rtime') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_scale') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_wage') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        return true;
    } elseif ($type == "2") {
        $db->query("UPDATE " . table('jobs') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_tmp') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_hot') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_key') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_rtime') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_scale') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_wage') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        return true;
    } elseif ($type == "3") {
        $db->query("UPDATE " . table('jobs') . " SET stick=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_tmp') . " SET stick=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET stick=1 WHERE id='{$jobid}' LIMIT 1");
        return true;
    } elseif ($type == "4") {
        $db->query("UPDATE " . table('jobs') . " SET highlight='{$val}' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_tmp') . " SET highlight='{$val}' WHERE id='{$jobid}' LIMIT 1");
        return true;
    }
}

function cancel_promotion($jobid, $promotionid, $uid) {
    global $db;
    if ($promotionid == "1") {
        $db->query("UPDATE " . table('jobs') . " SET recommend='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_tmp') . " SET recommend='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_search_hot') . " SET recommend='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_key') . " SET recommend='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_rtime') . " SET recommend='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_scale') . " SET recommend='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET recommend='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_wage') . " SET recommend='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        return true;
    } elseif ($promotionid == "2") {
        $db->query("UPDATE " . table('jobs') . " SET emergency='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_tmp') . " SET emergency='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_search_hot') . " SET emergency='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_key') . " SET emergency='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_rtime') . " SET emergency='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_scale') . " SET emergency='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET emergency='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_wage') . " SET emergency='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        return true;
    } elseif ($promotionid == "3") {
        $db->query("UPDATE " . table('jobs') . " SET stick='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_tmp') . " SET stick='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET stick='0' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1");
        return true;
    } elseif ($promotionid == "4") {
        $db->query("UPDATE " . table('jobs') . " SET highlight='' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_tmp') . " SET highlight='' WHERE id='{$jobid}' AND uid='{$uid}' LIMIT 1 ");
        return true;
    } 
}

function promotion_del($idarr, $uid) {
    global $db;
    $return = 0;
    if (!is_array($idarr))
        $idarr = array($idarr);
    $sqlin = implode(",", $idarr);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $info = $db->getall("select * from " . table('promotion') . "  WHERE cp_id in ('{$sqlin}')");
        foreach ($info as $key => $value) {
            cancel_promotion($value['cp_jobid'], $value['cp_promotionid'], $uid);
        }
        if (!$db->query("Delete from " . table('promotion') . " WHERE cp_id in ('{$sqlin}') AND cp_uid='{$uid}'"))
            return false;
        $return = $return + $db->affected_rows();
        write_memberslog($_SESSION['uid'], 1, 3006, $_SESSION['username'], "删除职位推广，(id:{$idarr})");
    }
    return $return;
}

function get_gifts($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('members_gifts') . " where uid='{$uid}' ORDER BY id desc";
    $list = $db->getall($sql);
    return $list;
}

function get_pms($offset, $perpage, $get_sql = '') {
    global $db;
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT {$offset},{$perpage}";
    }
    $result = $db->query($get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['message'] = $row['message'];
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

function get_company_news($offset, $perpage, $uid) {
    global $db;
    $uid = intval($uid);
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT {$offset},{$perpage}";
    }
    $result = $db->query("select * from " . table('company_news') . " WHERE uid ='{$uid}'  ORDER BY `order` DESC,`id` DESC {$limit}");
    while ($row = $db->fetch_array($result)) {
        $row['news_url'] = url_rewrite('QS_companynewsshow', array('id' => $row['id']));
        $row['title'] = cut_str($row['title'], 20, 0, "...");
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_company_news($del_id, $uid) {
    global $db;
    $uidsql = " AND uid=" . intval($uid) . "";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('company_news') . " WHERE id IN ({$sqlin}) {$uidsql}"))
        return false;
    return $db->affected_rows();
    ;
}

function get_company_img($offset, $perpage, $uid) {
    global $db;
    $uid = intval($uid);
    if (isset($offset) && !empty($perpage)) {
        $limit = " LIMIT {$offset},{$perpage}";
    }
    $result = $db->query("select * from " . table('company_img') . " WHERE uid ='{$uid}'  ORDER BY `id` DESC {$limit}");
    while ($row = $db->fetch_array($result)) {
        $row['title'] = cut_str($row['title'], 15, 0, "...");
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_comment_list($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $offset = intval($offset);
    $perpage = intval($perpage);
    $limit = " LIMIT {$offset},{$perpage}";
    $result = $db->query("SELECT c.*,m.username FROM " . table('comment') . " AS c " . $get_sql . " ORDER BY c.id DESC " . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['content_'] = cut_str($row['content'], 40, 0, '...');
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_company_comment($del_id, $jobs_id, $company_id) {
    global $db;
    $jobs_id = intval($jobs_id);
    $uidsql = " AND company_id=" . intval($company_id) . "";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('comment') . " WHERE id IN ({$sqlin}) {$uidsql}"))
        return false;
    $return = $db->affected_rows();
    $db->query("update " . table('jobs') . " set comment=comment-{$return} WHERE id='{$jobs_id}'  LIMIT 1");
    $db->query("update " . table('jobs_tmp') . " set comment=comment-{$return} WHERE id='{$jobs_id}'  LIMIT 1");
    return $return;
}

function delay_jobs($sqlin, $uid, $days) {
    global $db;
    $days = intval($days);
    $uid = intval($uid);
    $return = 0;
    if (empty($days))
        return false;
    $time = time();
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $result = $db->query("SELECT id,deadline FROM " . table('jobs') . " WHERE id IN ({$sqlin}) UNION ALL SELECT id,deadline FROM " . table('jobs_tmp') . " WHERE id IN ({$sqlin})");
        while ($row = $db->fetch_array($result)) {
            if ($row['deadline'] > $time) {
                $deadline = strtotime("+{$days} day", $row['deadline']);
            } else {
                $deadline = strtotime("+{$days} day");
            }
            if (!$db->query("update  " . table('jobs') . " SET deadline='{$deadline}'  WHERE id='{$row['id']}'  LIMIT 1"))
                return false;
            if (!$db->query("update  " . table('jobs_tmp') . " SET deadline='{$deadline}'  WHERE id='{$row['id']}'  LIMIT 1"))
                return false;
            distribution_jobs($row['id'], $uid);
        }
    }
    return true;
}

//职位评论审核
function comment_audit($id, $audit) {
    global $db;
    $audit = intval($audit);
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $return = 0;
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("update  " . table('comment') . " SET audit={$audit}  WHERE id IN ({$sqlin})"))
        return false;
    $return = $return + $db->affected_rows();
    return $return;
}

//主要获取会员的某种推广方案的剩余天数和时间
function get_setmeal_promotion($uid, $promotionid) {
    global $db;
    $uid = intval($uid);
    $promotionid = intval($promotionid);
    $sql = "select * from " . table('members_setmeal') . "  WHERE uid='{$uid}' AND  effective=1 LIMIT 1";
    $result = $db->getone($sql);
    $total_result = $db->getone("select recommend_num,stick_num,emergency_num,highlight_num from " . table('setmeal') . "  WHERE id='{$result['setmeal_id']}' LIMIT 1"); //获取某种套餐某种推广总条数

    if ($promotionid == '1') {
        $data['total_num'] = $total_result['recommend_num'];
        $data['name'] = 'recommend_num';
        $data['num'] = $result['recommend_num'];
        $data['days'] = $result['recommend_days'];
        $data['endtime'] = $result['endtime'];
    } elseif ($promotionid == '3') {
        $data['total_num'] = $total_result['stick_num'];
        $data['name'] = 'stick_num';
        $data['num'] = $result['stick_num'];
        $data['days'] = $result['stick_days'];
        $data['endtime'] = $result['endtime'];
    } elseif ($promotionid == '2') {
        $data['total_num'] = $total_result['emergency_num'];
        $data['name'] = 'emergency_num';
        $data['num'] = $result['emergency_num'];
        $data['days'] = $result['emergency_days'];
        $data['endtime'] = $result['endtime'];
    } elseif ($promotionid == '4') {
        $data['total_num'] = $total_result['highlight_num'];
        $data['name'] = 'highlight_num';
        $data['num'] = $result['highlight_num'];
        $data['days'] = $result['highlight_days'];
        $data['endtime'] = $result['endtime'];
    }
    return $data;
}

function get_talent_resume_basic($id) {
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

function check_com_down_talent_resumeid($resume_id, $company_uid) {
    global $db;
    $company_uid = intval($company_uid);
    $resume_id = intval($resume_id);
    $sql = "select did from " . table('user_down_talent_resume') . " WHERE user_uid = '{$company_uid}' AND resume_id='{$resume_id}' LIMIT 1";
    $info = $db->getone($sql);
    if (empty($info)) {
        return false;
    } else {
        return true;
    }
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

function count_jobs_num_by_uid($uid) {
    global $db;
    $total[0] = $db->getone("select count(*) from " . table('jobs') . " where uid=" . $uid);
    $total[1] = $db->getone("select count(*) from " . table('jobs_tmp') . " where uid=" . $uid);
    $total = $total[0]['count(*)'] + $total[1]['count(*)'];
    return $total;
}

function get_subsite_list() {
    global $db;
    $sql = "select * from " . table('subsite');
    return $db->getall($sql);
}

function get_jobs_templates() {
    global $db;
    $list = $db->getall("select * from " . table('jobs_templates') . " where uid=" . $_SESSION['uid']);
    foreach ($list as $key => $value) {
        $list[$key]['title'] = cut_str($list[$key]['title'], 12, 0, "...");
    }
    return $list;
}

function get_jobs_templates_one($id) {
    global $db;
    $list = $db->getone("select * from " . table('jobs_templates') . " where id=" . $id . " and uid=" . $_SESSION['uid']);
    $tag_cn = "";
    if ($list['tag'] != "") {
        $arr = explode("|", $list['tag']);
        foreach ($arr as $key => $value) {
            $t_arr = explode(",", $value);
            $tag_cn .= $t_arr[1] . ",";
        }
    }
    $list['tag_cn'] = trim($tag_cn, ",");
    return $list;
}

function del_templates($del_id, $uid) {
    global $db;
    $return = 0;
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $sql = "delete from " . table('jobs_templates') . " where id in(" . $sqlin . ") and uid=" . $uid;
        $db->query($sql);
        $return = $return + $db->affected_rows();
    }
    return $return;
}

function get_content_by_jobs_cat($id) {
    global $db;
    $content = $db->getone("select content from " . table('category_jobs') . " where id=" . $id);
    return $content['content'];
}

function get_color() {
    global $db;
    $sql = "select * from " . table('color');
    return $db->getall($sql);
}

function get_color_one($id) {
    global $db;
    $sql = "select * from " . table('color') . " WHERE id=" . $id . "";
    return $db->getone($sql);
}

function get_my_attention($offset, $perpage, $get_sql = '') {
    global $db;
    $limit = " LIMIT {$offset},{$perpage}";
    $selectstr = " a.*,r.subsite_id,r.birthdate,r.talent,r.intention_jobs,r.district_cn,r.sex_cn,r.fullname,r.education_cn,r.experience_cn,r.intention_jobs,r.display_name,r.attachment_resume ";
    $result = $db->query("SELECT {$selectstr} FROM " . table('view_resume') . " as a " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['age'] = date("Y") - $row['birthdate'];
        $row['url'] = url_rewrite("QS_resumeshow", array('id' => $row['resumeid']), true, $row['subsite_id']);
        if ($row['display_name'] == "3") {
            $row['fullname'] = cut_str($row['fullname'], 1, 0, "**");
        } elseif ($row['display_name'] == "2") {
            $row['fullname'] = "N" . str_pad($row['id'], 7, "0", STR_PAD_LEFT);
        }
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_my_attention($del_id, $uid) {
    global $db;
    $uidsql = " AND uid=" . intval($uid) . "";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('view_resume') . " WHERE id IN ({$sqlin}) {$uidsql}"))
        return false;
    return true;
}

function get_my_jobs($uid) {
    global $db;
    $wheresql = " where uid=" . $uid . " ";
    $sql = "SELECT id FROM " . table('jobs') . $wheresql . " UNION ALL  SELECT id FROM " . table('jobs_tmp') . $wheresql;
    $my_jobs = $db->getall($sql);
    foreach ($my_jobs as $key => $value) {
        $idarr[] = $value['id'];
    }
    $idstr = implode(",", $idarr);
    return $idstr;
}

function check_resume_report($uid, $resume_id) {
    global $db;
    $sql = "select id from " . table('report_resume') . " WHERE uid = '" . intval($uid) . "' AND resume_id='" . intval($resume_id) . "' LIMIT 1";
    return $db->getone($sql);
}

function get_adv_list() {
    global $db;
    $sql = "select * from " . table("ad_category") . " order by category_order desc";
    return $db->getall($sql);
}

function get_adv_one($id) {
    global $db;
    $id = intval($id);
    $sql = "select * from " . table('ad_category') . "  WHERE id='{$id}'  LIMIT 1";
    return $db->getone($sql);
}

function get_user_audit_reason($uid) {
    global $db;
    $sql = "select reason from " . table('audit_reason') . " where company_id=" . $uid;
    return $db->getone($sql);
}

function get_consultant($id) {
    global $db;
    $sql = "select * from " . table('consultant') . " where id=" . $id;
    return $db->getone($sql);
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

?>