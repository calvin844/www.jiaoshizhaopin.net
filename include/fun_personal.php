<?php

/*
 * 74cms 个人会员函数
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

function get_resume_list($wheresql, $titlele = 12, $countinterview = false, $countdown = false, $countapply = false) {
    global $db;
    $result = $db->query("{$wheresql} LIMIT 30");
    while ($row = $db->fetch_array($result)) {
        $row['resume_url'] = url_rewrite('QS_resumeshow', array('id' => $row['id']), true, $row['subsite_id']);
        $row['title'] = cut_str($row['title'], $titlele, 0, "...");
        $row['number'] = "N" . str_pad($row['id'], 7, "0", STR_PAD_LEFT);
        $row['lastname'] = cut_str($row['fullname'], 1, 0, "**");
        if ($countinterview) {
            $wheresql = " WHERE resume_uid='{$row['uid']}' AND resume_id= '{$row['id']}'";
            $row['countinterview'] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('company_interview') . $wheresql);
        }
        if ($countdown) {
            $wheresql = " WHERE resume_uid='{$row['uid']}' AND resume_id= '{$row['id']}'";
            $row['countdown'] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('company_down_resume') . $wheresql);
        }
        if ($countapply) {
            $wheresql = " WHERE personal_uid='{$row['uid']}' AND resume_id= '{$row['id']}'";
            $row['countapply'] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . $wheresql);
        }
        $interest_jobs_id = get_interest_jobs_id_by_resume($row['uid'], $row['id']);
        $interest_jobs_id = explode("-", $interest_jobs_id);
        $interest_jobs_id = implode(",", $interest_jobs_id);
        if ($interest_jobs_id) {
            $cate = $db->getall("select id,parentid from " . table('category_jobs') . " where id in (" . $interest_jobs_id . ")");
            //var_dump($cate);
            foreach ($cate as $k => $v) {
                if (empty($v['parentid'])) {
                    $arr[] = "0.0." . $v['id'];
                } else {
                    $arr[] = $v['parentid'] . "." . $v['id'] . ".0";
                }
            }
            $row['interestjobs'] = implode("|", $arr);
        } else {
            $row['interestjobs'] = "";
        }
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_auditresume_list($uid, $titlele = 12) {
    global $db;
    $uid = intval($uid);
    $result = $db->query("SELECT * FROM " . table('resume') . " WHERE uid='{$uid}'" . $wheresql);
    while ($row = $db->fetch_array($result)) {
        $row['resume_url'] = url_rewrite('QS_resumeshow', array('id' => $row['id']), true, $row['subsite_id']);
        $row['title'] = cut_str($row['title'], $titlele, 0, "...");
        $row['number'] = "N" . str_pad($row['id'], 7, "0", STR_PAD_LEFT);
        $row['lastname'] = cut_str($row['fullname'], 1, 0, "**");
        $row_arr[] = $row;
    }
    return $row_arr;
}

//获取简历基本信息
function get_resume_basic($uid = 0, $id = 0) {
    global $db;
    $id = intval($id);
    $uid = intval($uid);
    $where = " where 1 ";
    if ($uid > 0) {
        $where .= ' AND uid=' . $uid;
    }
    if ($id > 0) {
        $where .= ' AND id=' . $id;
    }
    $info = $db->getone("select * from " . table('resume') . $where . " LIMIT 1 ");
    //var_dump($info);exit;
    if (empty($info)) {
        return false;
    } else {
        $info['age'] = date("Y") - $info['birthdate'];
        $info['number'] = "N" . str_pad($info['id'], 7, "0", STR_PAD_LEFT);
        $info['lastname'] = cut_str($info['fullname'], 1, 0, "**");
        return $info;
    }
}

//获取用户证书列表
function get_resume_certificate($uid, $pid) {
    global $db;
    if (intval($uid) != $uid) {
        return false;
    }
    $sql = "SELECT * FROM " . table('resume_certificate') . " WHERE  pid='" . intval($pid) . "' AND uid='" . intval($uid) . "' ";
    return $db->getall($sql);
}

//获取用户附件简历
function get_attachment_resume($uid, $pid) {
    global $db;
    if (intval($uid) != $uid) {
        return false;
    }
    $sql = "SELECT * FROM " . table('resume_attachment') . " WHERE uid='" . intval($uid) . "' ";
    return $db->getone($sql);
}

function del_attachment_resume($uid) {
    global $db;
    if (!is_array($uid)) {
        $uid = array($uid);
    }
    $sqlin = implode(",", $uid);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        return false;
    }
    $sql = "SELECT * FROM " . table('resume_attachment') . " WHERE uid IN (" . $sqlin . ")";
    $arr = $db->getall($sql);
    foreach ($arr as $a) {
        unlink('../..' . $a['path'] . $a['file_name']);
        updatetable(table('resume'), array("attachment_resume" => "", "default_resume" => 0), array('uid' => $a['uid']));
        if (!$db->query("Delete from " . table('resume_attachment') . " WHERE uid IN (" . $a['uid'] . ")")) {
            return false;
        }
    }
    return TRUE;
}

//设置用户默认简历
function set_default_resume($uid, $data = 0) {
    global $db;
    $uid = intval($uid);
    $data = intval($data);
    if ($uid > 0) {
        if (!updatetable(table('resume'), array("default_resume" => $data), array('uid' => $uid))) {
            return false;
        }
    } else {
        return false;
    }
    return TRUE;
}

//获取用户证书列表
function get_resume_certificate_one($uid, $pid, $id) {
    global $db;
    $sql = "select * from " . table('resume_certificate') . " where id='" . intval($id) . "' AND uid='" . intval($uid) . "' AND pid='" . intval($pid) . "' LIMIT 1";
    return $db->getone($sql);
}

//获取教育经历列表
function get_resume_education($uid, $pid) {
    global $db;
    if (intval($uid) != $uid)
        return false;
    $sql = "SELECT * FROM " . table('resume_education') . " WHERE  pid='" . intval($pid) . "' AND uid='" . intval($uid) . "' ";
    return $db->getall($sql);
}

//获取 单条 教育经历
function get_resume_education_one($uid, $pid, $id) {
    global $db;
    $sql = "select * from " . table('resume_education') . " where id='" . intval($id) . "' AND uid='" . intval($uid) . "' AND pid='" . intval($pid) . "' LIMIT 1";
    return $db->getone($sql);
}

//获取：工作经历
function get_resume_work($uid, $pid) {
    global $db;
    $sql = "select * from " . table('resume_work') . " where pid='" . $pid . "' AND uid=" . intval($uid) . "";
    return $db->getall($sql);
}

//获取 单条 工作经历
function get_resume_work_one($uid, $pid, $id) {
    global $db;
    $sql = "select * from " . table('resume_work') . " where id='" . intval($id) . "' AND uid='" . intval($uid) . "' AND pid='" . intval($pid) . "' LIMIT 1 ";
    return $db->getone($sql);
}

//获取：培训经历列表
function get_resume_training($uid, $pid) {
    global $db;
    $sql = "select * from " . table('resume_training') . " where pid='" . intval($pid) . "' AND  uid='" . intval($uid) . "' ";
    return $db->getall($sql);
}

//获取 单条 培训经历
function get_resume_training_one($uid, $pid, $id) {
    global $db;
    $sql = "select * from " . table('resume_training') . " where id='" . intval($id) . "' AND uid='" . intval($uid) . "'  AND pid='" . intval($pid) . "'  LIMIT 1 ";
    return $db->getone($sql);
}

//获取意向职位
function get_resume_jobs($pid) {
    global $db;
    $pid = intval($pid);
    $sql = "select * from " . table('resume_jobs') . " where pid='{$pid}' order by id asc  LIMIT 20";
    return $db->getall($sql);
}

//增加意向职位
function add_resume_jobs($pid, $uid, $str, $district, $sdistrict) {
    global $db;
    $db->query("Delete from " . table('resume_jobs') . " WHERE pid='" . intval($pid) . "'");
    $str = trim($str);
    $arr = explode(",", $str);
    if (is_array($arr) && !empty($arr)) {
        foreach ($arr as $a) {
            $a = explode(".", $a);
            $setsqlarr['uid'] = intval($uid);
            $setsqlarr['pid'] = intval($pid);
            $setsqlarr['topclass'] = intval($a[0]);
            $setsqlarr['category'] = intval($a[1]);
            $setsqlarr['subclass'] = intval($a[2]);
            if (stristr($district, ",")) {
                $d_arr = explode(",", $district);
                $sd_arr = explode(",", $sdistrict);
                foreach ($d_arr as $k => $v) {
                    $setsqlarr['district'] = $v;
                    $setsqlarr['sdistrict'] = $sd_arr[$k];
                    if (!inserttable(table('resume_jobs'), $setsqlarr)) {
                        return false;
                    }
                }
            } else {
                $setsqlarr['district'] = intval($district);
                $setsqlarr['sdistrict'] = intval($sdistrict);
                //var_dump($setsqlarr);exit;
                if (!inserttable(table('resume_jobs'), $setsqlarr)) {
                    return false;
                }
            }
        }
    }
    return true;
}

//增加意向行业
function add_resume_trade($pid, $uid, $str) {
    global $db;
    $db->query("Delete from " . table('resume_trade') . " WHERE pid='" . intval($pid) . "'");
    $str = trim($str);
    $arr = explode(",", $str);
    if (is_array($arr) && !empty($arr)) {
        foreach ($arr as $k => $a) {
            $setsqlarr['uid'] = intval($uid);
            $setsqlarr['pid'] = intval($pid);
            $setsqlarr['trade'] = intval($a);
            if (!inserttable(table('resume_trade'), $setsqlarr))
                return false;
        }
    }
    return true;
}

function get_user_info($uid) {
    global $db;
    $sql = "select * from " . table('members') . " where uid = " . intval($uid) . " LIMIT 1";
    return $db->getone($sql);
}

function get_resumetpl() {
    global $db;
    $sql = "select * from " . table('tpl') . " where tpl_type =2 AND tpl_display=1";
    return $db->getall($sql);
}

function get_resumetpl_one($id) {
    global $db;
    $sql = "select * from " . table('tpl') . " where tpl_id=" . $id;
    return $db->getone($sql);
}

//获取单条订单
function get_order_one($uid, $id) {
    global $db;
    $sql = "select * from " . table('personal_resume_tpl_order') . " where id =" . intval($id) . " AND uid = " . intval($uid) . "  LIMIT 1 ";
    return $db->getone($sql);
}

//增加订单
function add_order($uid, $oid, $tpl_id, $amount, $days = 0, $payment_name, $description, $addtime) {
    global $db;
    $setsqlarr['uid'] = intval($uid);
    $setsqlarr['oid'] = $oid;
    $setsqlarr['tpl_id'] = $tpl_id;
    $setsqlarr['amount'] = $amount;
    $setsqlarr['days'] = $days;
    $setsqlarr['payment_name'] = $payment_name;
    $setsqlarr['description'] = $description;
    $setsqlarr['addtime'] = $addtime;
    write_memberslog($uid, 1, 3001, $_SESSION['username'], "添加简历模版订单，编号{$oid}，金额{$amount}元");
    return inserttable(table('personal_resume_tpl_order'), $setsqlarr, true);
}

//付款后开通
function order_paid($v_oid) {
    global $db, $_CFG;
    $timestamp = time();
    $order = $db->getone("select * from " . table('personal_resume_tpl_order') . " WHERE oid ='{$v_oid}' AND state= '0' LIMIT 1 ");
    if ($order) {
        $user = get_user_info($order['uid']);
        $sql = "UPDATE " . table('personal_resume_tpl_order') . " SET state= '1',paytime='{$timestamp}' WHERE oid='{$v_oid}' LIMIT 1 ";
        if (!$db->query($sql)) {
            return false;
        }
        if ($order['days'] > 0) {
            $resume = get_resume_basic($order['uid']);
            $resumetpl = get_resumetpl_one($order['tpl_id']);
            set_resume_tpl($order['uid'], $order['tpl_id'], $resume['id'], $order['days']);
            $notes = date('Y-m-d H:i', time()) . "通过：" . get_payment_info($order['payment_name'], true) . " 成功购买简历模版(" . $resumetpl['tpl_name'] . ") " . $order['amount'] . "元，有效期至" . date("Y-m-d", time() + 86400 * $order['days']) . "，订单:{$v_oid}";
            write_memberslog($order['uid'], 1, 1109, $user['username'], $notes);
        }
        return true;
    }
    return true;
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

function set_resume_tpl($uid, $tpl_id, $resume_id, $days = 0) {
    $tpl_data['uid'] = intval($uid);
    $tpl_data['tpl_id'] = $tpl_id;
    $tpl_data['resume_id'] = $resume_id;
    $tpl_data['addtime'] = time();
    $tpl_data['endtime'] = time() + 86400 * $days;
    $insert_id = inserttable(table("personal_resume_tpl"), $tpl_data, 1);
}

function get_resume_tpl($uid) {
    global $db;
    $sql = "select * from " . table('personal_resume_tpl') . " where uid =" . intval($uid) . " AND endtime>" . time() . " AND tpl_id !=2 ORDER BY id desc  LIMIT 1 ";
    return $db->getone($sql);
}

function get_payment() {
    global $db;
    $sql = "select * from " . table('payment') . " where p_install='2' ORDER BY listorder desc";
    $list = $db->getall($sql);
    return $list;
}

function get_userprofile($uid) {
    global $db;
    $sql = "select * from " . table('members_info') . " where uid = " . intval($uid) . " LIMIT 1";
    return $db->getone($sql);
}

function refresh_resume($pid, $uid) {
    global $db;
    $time = time();
    $uid = intval($uid);
    if (!$db->query("update  " . table('resume') . "  SET refreshtime='{$time}'  WHERE id='{$pid}' AND uid='{$uid}'"))
        return false;
    if (!$db->query("update  " . table('resume_search_rtime') . "  SET refreshtime='{$time}'  WHERE id='{$pid}' AND uid='{$uid}'"))
        return false;
    if (!$db->query("update  " . table('resume_search_key') . "  SET refreshtime='{$time}'  WHERE id='{$pid}' AND uid='{$uid}'"))
        return false;
    $sql = "select audit from " . table('resume') . " where uid = " . intval($uid) . " LIMIT 1";
    $resume = $db->getone($sql);
    if ($resume['audit'] == 1) {
        $db->query("update  " . table('resume') . "  SET entrust='1'  WHERE id='{$pid}' AND uid='{$uid}'");
    }
    write_memberslog($_SESSION['uid'], 2, 1102, $_SESSION['username'], "刷新了id为{$pid}的简历");
    write_refresh_log($_SESSION['uid'], 2001);
    return true;
}

//删除简历
function del_resume($uid, $pid) {
    global $db;
    $uid = intval($uid);
    if (!$db->query("Delete from " . table('resume') . " WHERE id='{$pid}' AND uid='{$uid}' "))
        return false;
    if (!$db->query("Delete from " . table('resume_jobs') . " WHERE pid='{$pid}' AND uid='{$uid}' "))
        return false;
    if (!$db->query("Delete from " . table('resume_trade') . " WHERE pid='{$pid}' AND uid='{$uid}' "))
        return false;
    if (!$db->query("Delete from " . table('resume_education') . " WHERE pid='{$pid}' AND uid='{$uid}' "))
        return false;
    if (!$db->query("Delete from " . table('resume_training') . " WHERE pid='{$pid}' AND uid='{$uid}' "))
        return false;
    if (!$db->query("Delete from " . table('resume_work') . " WHERE pid='{$pid}' AND uid='{$uid}' "))
        return false;
    if (!$db->query("Delete from " . table('resume_search_rtime') . " WHERE id='{$pid}' AND uid='{$uid}' "))
        return false;
    if (!$db->query("Delete from " . table('resume_search_key') . " WHERE id='{$pid}' AND uid='{$uid}' "))
        return false;
    if (!$db->query("Delete from " . table('resume_search_tag') . " WHERE id='{$pid}' AND uid='{$uid}' "))
        return false;
    if (!$db->query("Delete from " . table('view_resume') . " WHERE resumeid='{$pid}'"))
        return false;
    $db->query("delete from " . table('resume_entrust') . " where id=" . $pid);
    write_memberslog($_SESSION['uid'], 2, 1103, $_SESSION['username'], "删除简历({$pid})");
    return true;
}

//修改简历照片显示设置
function edit_photo_display($uid, $pid, $display) {
    global $db;
    $db->query("update  " . table('resume') . "  SET photo_display='" . intval($display) . "' WHERE uid='" . intval($uid) . "' AND id='" . intval($pid) . "' LIMIT 1");
    return true;
}

//检查简历的完成程度
function check_resume($uid, $pid) {
    global $db, $timestamp, $_CFG;
    $uid = intval($uid);
    $pid = intval($pid);
    $percent = 0;
    $resume_basic = get_resume_basic($uid, $pid);
    $resume_education = get_resume_education($uid, $pid);
    $resume_work = get_resume_work($uid, $pid);
    $resume_training = get_resume_training($uid, $pid);
    $resume_tag = $resume_basic['tag'];
    $resume_specialty = $resume_basic['specialty'];
    $resume_photo = $resume_basic['photo_img'];
    if (!empty($resume_basic))
        $percent = $percent + 40;
    if (!empty($resume_education))
        $percent = $percent + 15;
    if (!empty($resume_work))
        $percent = $percent + 15;
    if (!empty($resume_training))
        $percent = $percent + 15;
    if (!empty($resume_tag))
        $percent = $percent + 5;
    if (!empty($resume_specialty))
        $percent = $percent + 5;
    if (!empty($resume_photo))
        $percent = $percent + 5;
    if ($resume_basic['photo_img'] && $resume_basic['photo_audit'] == "1" && $resume_basic['photo_display'] == "1") {
        $setsqlarr['photo'] = 1;
    } else {
        $setsqlarr['photo'] = 0;
    }
    $setsqlarr['complete_percent'] = $percent;
    require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
    $sp = new SPWord();
    $setsqlarr['key'] = addslashes($resume_basic['intention_jobs']) . addslashes($resume_basic['recentjobs']) . addslashes($resume_basic['specialty']);
    $setsqlarr['key'] = addslashes($resume_basic['fullname']) . $sp->extracttag($setsqlarr['key']);
    $setsqlarr['key'] = str_replace(",", " ", addslashes($resume_basic['intention_jobs'])) . " {$setsqlarr['key']} " . addslashes($resume_basic['education_cn']);
    $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
    if (!empty($resume_education)) {
        foreach ($resume_education as $li) {
            $setsqlarr['key'] = addslashes($li['school']) . " {$setsqlarr['key']} " . addslashes($li['speciality']);
        }
    }
    if (!empty($resume_work)) {
        foreach ($resume_work as $li) {
            $setsqlarr['key'] = addslashes($li['companyname']) . " {$setsqlarr['key']} " . addslashes($li['speciality']);
        }
    }
    if (!empty($resume_training)) {
        foreach ($resume_training as $li) {
            $setsqlarr['key'] = addslashes($li['agency']) . " {$setsqlarr['key']} " . addslashes($li['speciality']);
        }
    }
    $setsqlarr['refreshtime'] = $timestamp;
    if ($setsqlarr['complete_percent'] < 60) {
        $setsqlarr['level'] = 1;
    } elseif ($setsqlarr['complete_percent'] >= 60 && $setsqlarr['complete_percent'] < 80) {
        $setsqlarr['level'] = 2;
    } elseif ($setsqlarr['complete_percent'] >= 80) {
        $setsqlarr['level'] = 3;
    }
    updatetable(table('resume'), $setsqlarr, "uid='{$uid}' AND id='{$pid}'");
    // distribution_resume($pid,$uid);
    $j = get_resume_basic($uid, $pid);
    $j = array_map("addslashes", $j);
    $searchtab['sex'] = $j['sex'];
    $searchtab['nature'] = $j['nature'];
    $searchtab['marriage'] = $j['marriage'];
    $searchtab['experience'] = $j['experience'];
    $searchtab['district'] = $j['district'];
    $searchtab['sdistrict'] = $j['sdistrict'];
    $searchtab['wage'] = $j['wage'];
    $searchtab['education'] = $j['education'];
    $searchtab['photo'] = $j['photo'];
    $searchtab['refreshtime'] = $j['refreshtime'];
    $searchtab['talent'] = $j['talent'];
    $searchtab['subsite_id'] = $j['subsite_id'];
    $searchtab['display'] = $j['display'];
    $searchtab['audit'] = $j['audit'];
    updatetable(table('resume_search_rtime'), $searchtab, "uid='{$uid}' AND id='{$pid}'");
    $searchtab['key'] = $j['key'];
    $searchtab['likekey'] = $j['intention_jobs'] . ',' . $j['trade_cn'] . ',' . $j['specialty'] . ',' . $j['fullname'];
    updatetable(table('resume_search_key'), $searchtab, "uid='{$uid}' AND id='{$pid}'");
    unset($searchtab);
    if ($j['tag']) {
        $tag = explode('|', $j['tag']);
        $tagindex = 1;
        $tagsql['tag1'] = $tagsql['tag2'] = $tagsql['tag3'] = $tagsql['tag4'] = $tagsql['tag5'] = 0;
        if (!empty($tag) && is_array($tag)) {
            foreach ($tag as $v) {
                $vid = explode(',', $v);
                $tagsql['tag' . $tagindex] = intval($vid[0]);
                $tagindex++;
            }
        }
        $tagsql['id'] = $j['id'];
        $tagsql['uid'] = $j['uid'];
        $tagsql['subsite_id'] = $j['subsite_id'];
        $tagsql['experience'] = $j['experience'];
        $tagsql['district'] = $j['district'];
        $tagsql['sdistrict'] = $j['sdistrict'];
        $tagsql['education'] = $j['education'];
        $tagsql['display'] = $j['display'];
        $tagsql['audit'] = $j['audit'];
        updatetable(table('resume_search_tag'), $tagsql, "uid='{$uid}' AND id='{$pid}'");
    }
}

function get_com_downresume($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . intval($offset) . ',' . intval($perpage);
    $select = "d.*,c.id,c.companyname,c.addtime,c.district_cn,c.trade_cn,c.nature_cn";
    $sql = "SELECT {$select} from " . table('company_down_resume') . " AS d {$get_sql} ORDER BY did DESC {$limit}";
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        $row['company_url'] = url_rewrite('QS_companyshow', array('id' => $row['id']));
        $row_arr[] = $row;
    }
    return $row_arr;
}

//面试邀请
function get_invitation($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . intval($offset) . ',' . intval($perpage);
    $select = "i.*,j.subsite_id,j.jobs_name,j.addtime,j.companyname,j.company_addtime,j.district_cn,j.wage_cn,j.deadline,j.refreshtime,j.click";
    $sql = "SELECT {$select} from " . table('company_interview') . " AS i {$get_sql} ORDER BY did DESC {$limit}";
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        if (empty($row['companyname'])) {
            $jobs = $db->getone("select * from " . table('jobs_tmp') . " WHERE id='{$row['jobs_id']}' LIMIT 1");
            $row['jobs_name'] = $jobs['jobs_name'];
            $row['addtime'] = $jobs['addtime'];
            $row['companyname'] = $jobs['companyname'];
            $row['company_addtime'] = $jobs['company_addtime'];
            $row['company_id'] = $jobs['company_id'];
            $row['wage_cn'] = $jobs['wage_cn'];
            $row['district_cn'] = $jobs['district_cn'];
            $row['deadline'] = $jobs['deadline'];
            $row['refreshtime'] = $jobs['refreshtime'];
            $row['click'] = $jobs['click'];
            $row['subsite_id'] = $jobs['subsite_id'];
        }
        $row['company_url'] = url_rewrite('QS_companyshow', array('id' => $row['company_id']));
        $row['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $row['jobs_id']), true, $row['subsite_id']);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function set_invitation($id, $uid, $setlook) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    $setsqlarr['personal_look'] = intval($setlook);
    $wheresql = " did IN (" . $sqlin . ") AND resume_uid=" . intval($uid) . "";
    foreach ($id as $aid) {
        $members = $db->getone("select m.username from " . table('company_interview') . " AS i JOIN " . table('members') . " AS m ON i.company_uid=m.uid WHERE i.did='{$aid}' LIMIT 1");
        write_memberslog($_SESSION['uid'], 2, 1108, $_SESSION['username'], "查看了 {$members['username']} 的邀请面试");
    }
    return updatetable(table('company_interview'), $setsqlarr, $wheresql);
}

//删除 -邀请记录
function del_interview($id, $uid) {
    global $db;
    $return = 0;
    $uidsql = " AND resume_uid=" . intval($uid) . "";
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    $sql = "Delete from " . table('company_interview') . " WHERE did IN (" . $sqlin . ") " . $uidsql . "";
    write_memberslog($_SESSION['uid'], 2, 1502, $_SESSION['username'], "删除了面试邀请($sqlin)");
    $db->query($sql);
    $return = $return + $db->affected_rows();
    return $return;
}

function add_favorites($id, $uid) {
    global $db, $timestamp;
    if (strpos($id, "-")) {
        $id = str_replace("-", ",", $id);
        if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $id))
            return false;
    }
    else {
        $id = intval($id);
    }
    $sql = "select * from " . table('jobs') . " WHERE id IN ({$id}) ";
    $jobs = $db->getall($sql);
    $i = 0;
    foreach ($jobs as $list) {
        $sql1 = "select jobs_id from " . table('personal_favorites') . " where jobs_id=" . $list['id'] . " AND personal_uid=" . $uid . "  LIMIT 1";
        if ($db->getone($sql1)) {
            continue;
        }
        $setsqlarr['personal_uid'] = $uid;
        $setsqlarr['jobs_id'] = $list['id'];
        $setsqlarr['jobs_name'] = $list['jobs_name'];
        $setsqlarr['addtime'] = $timestamp;
        inserttable(table('personal_favorites'), $setsqlarr);
        $i = $i + 1;
    }
    return $i;
}

function add_favorite_article($id, $uid) {
    global $db, $timestamp;
    if (strpos($id, "-")) {
        $id = str_replace("-", ",", $id);
        if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $id))
            return false;
    }
    else {
        $id = intval($id);
    }
    $sql = "select * from " . table('article') . " WHERE id =" . $id . "; ";
    $article = $db->getone($sql);
    $sql1 = "select article_id from " . table('personal_favorite_articles') . " where article_id=" . $article['id'] . " AND personal_uid=" . $uid . "  LIMIT 1";
    $result = $db->getone($sql1);
    if (empty($result)) {
        $setsqlarr['personal_uid'] = $uid;
        $setsqlarr['article_id'] = $article['id'];
        $setsqlarr['title'] = $article['title'];
        $setsqlarr['addtime'] = $timestamp;
        inserttable(table('personal_favorite_articles'), $setsqlarr);
        return true;
    } else {
        return false;
    }
}

function get_favorites($offset, $perpage, $get_sql = '', $uid = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT {$offset},{$perpage}";
    $select = " f.*,j.subsite_id,j.jobs_name,j.addtime as jobs_addtime,j.companyname,j.company_addtime,j.company_id,j.wage_cn,j.district_cn,j.deadline,j.refreshtime,j.click";
    $result = $db->query("SELECT {$select} FROM " . table('personal_favorites') . " AS f {$get_sql} ORDER BY f.did DESC {$limit}");
    while ($row = $db->fetch_array($result)) {
        if (empty($row['companyname'])) {
            $jobs = $db->getone("select * from " . table('jobs_tmp') . " WHERE id='{$row['jobs_id']}' LIMIT 1");
            $row['jobs_name'] = $jobs['jobs_name'];
            $row['jobs_addtime'] = $jobs['addtime'];
            $row['companyname'] = $jobs['companyname'];
            $row['company_addtime'] = $jobs['company_addtime'];
            $row['company_id'] = $jobs['company_id'];
            $row['wage_cn'] = $jobs['wage_cn'];
            $row['district_cn'] = $jobs['district_cn'];
            $row['deadline'] = $jobs['deadline'];
            $row['refreshtime'] = $jobs['refreshtime'];
            $row['click'] = $jobs['click'];
            $row['subsite_id'] = $jobs['subsite_id'];
        }
        $row['company_url'] = url_rewrite('QS_companyshow', array('id' => $row['company_id']));
        $row['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $row['jobs_id']), true, $row['subsite_id']);
        $row['type'] = 'job';
        $row['end'] = $row['deadline'] > time() ? 0 : 1;
        //var_dump($row['end']);
        $row_arr[] = $row;
    }
    $result = $db->query("SELECT * FROM " . table('personal_favorite_articles') . " WHERE personal_uid = " . $uid . ";");
    while ($row = $db->fetch_array($result)) {
        $article = $db->getone("select * from " . table('article') . " WHERE id='{$row['article_id']}' LIMIT 1");
        $row['type'] = 'article';
        $row['did'] = $row['id'];
        $row['jobs_name'] = "简章职位";
        $row['jobs_addtime'] = $article['addtime'];
        $row['company_addtime'] = $article['company_addtime'];
        $row['wage_cn'] = "详见简章";
        $row['district_cn'] = $article['district_cn'];
        $row['deadline'] = $article['endtime'];
        $row['refreshtime'] = $article['refreshtime'];
        $row['click'] = $article['click'];
        $row['article_url'] = $_SERVER['host'] . "/morejobs/jobshow_" . $row['article_id'] . ".html";
        $row['end'] = $row['deadline'] > time() ? 0 : 1;
        $row_arr[] = $row;
    }
    $row_arr = my_sort($row_arr, 'addtime', SORT_DESC);
    return $row_arr;
}

function del_favorites($id, $uid, $articleid = '') {
    global $db;
    if (!empty($id)) {
        $uidsql = " AND personal_uid=" . intval($uid) . "";
        if (!is_array($id))
            $id = array($id);
        $sqlin = implode(",", $id);
        if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
            return false;
        $sql = "Delete from " . table('personal_favorites') . " WHERE did IN (" . $sqlin . ") " . $uidsql . "";
        write_memberslog($_SESSION['uid'], 2, 1202, $_SESSION['username'], "删除了职位收藏($sqlin)");
        $db->query($sql);
    }
    if (!empty($articleid)) {
        $uidsql = " AND personal_uid=" . intval($uid) . "";
        if (!is_array($articleid))
            $id = array($articleid);
        $sqlin = implode(",", $articleid);
        if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
            return false;
        $sql = "Delete from " . table('personal_favorite_articles') . " WHERE id IN (" . $sqlin . ") " . $uidsql . "";
        write_memberslog($_SESSION['uid'], 2, 1202, $_SESSION['username'], "删除了简章收藏($sqlin)");
        $db->query($sql);
    }
    return true;
}

function check_jobs_apply($jobs_id, $resume_id, $p_uid) {
    global $db;
    $sql = "select did from " . table('personal_jobs_apply') . " WHERE personal_uid = '" . intval($p_uid) . "' AND jobs_id='" . intval($jobs_id) . "'  AND resume_id='" . intval($resume_id) . "' LIMIT 1";
    return $db->getone($sql);
}

function get_now_applyjobs_num($uid) {
    global $db;
    $uid = intval($uid);
    $now = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $wheresql = " WHERE personal_uid = '{$uid}' AND apply_addtime>{$now} ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . $wheresql;
    return $db->get_total($total_sql);
}

function get_apply_jobs($offset, $perpage, $get_sql = '', $uid = '', $wheresql = '') {
    global $db;
    //职位申请
    //$limit = " LIMIT " . intval($offset) . ',' . intval($perpage);
    $limit = "";
    $select = " a.*,j.subsite_id,j.jobs_name,j.addtime,j.company_id,j.companyname,j.company_addtime,j.wage_cn,j.district_cn,j.deadline,j.refreshtime,j.click";
    $sql = "SELECT {$select} FROM " . table('personal_jobs_apply') . " AS a{$get_sql} ORDER BY a.did DESC " . $limit;
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        if (empty($row['companyname'])) {
            $jobs = $db->getone("select * from " . table('jobs_tmp') . " WHERE id='{$row['jobs_id']}' LIMIT 1");
            $row['addtime'] = $jobs['addtime'];
            $row['jobs_name'] = $jobs['jobs_name'];
            $row['companyname'] = $jobs['companyname'];
            $row['company_addtime'] = $jobs['company_addtime'];
            $row['company_id'] = $jobs['company_id'];
            $row['wage_cn'] = $jobs['wage_cn'];
            $row['district_cn'] = $jobs['district_cn'];
            $row['deadline'] = $jobs['deadline'];
            $row['refreshtime'] = $jobs['refreshtime'];
            $row['click'] = $jobs['click'];
            $row['subsite_id'] = $jobs['subsite_id'];
        }
        $resume = $db->getone("select title from " . table('resume') . " where id=" . $row['resume_id']);
        $row['resume_name'] = $resume['title'];
        $row['company_url'] = url_rewrite('QS_companyshow', array('id' => $row['company_id']));
        $row['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $row['jobs_id']), true, $row['subsite_id']);
        $row['type'] = 'job';
        $row['end'] = $row['deadline'] > time() ? 0 : 1;
        $row_arr[] = $row;
    }
    //简章职位申请
    $sql = "SELECT * FROM " . table('jiaoshi_article_apply') . " AS a{$wheresql} ORDER BY a.id DESC ";
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        $news = $db->getone("select * from " . table('article') . " WHERE id='{$row['article_id']}' LIMIT 1");
        $news_jobs = $db->getone("select * from " . table('jiaoshi_article_jobs') . " WHERE id='{$row['article_job_id']}' LIMIT 1");
        $row['did'] = $row['id'];
        $row['type'] = 'article';
        $row['addtime'] = $news['addtime'];
        $row['jobs_name'] = $news_jobs['job_name'];
        $row['article_addtime'] = $news['addtime'];
        $row['district_cn'] = $news['district_cn'];
        $row['deadline'] = $news['endtime'];
        $row['refreshtime'] = $news['refreshtime'];
        $row['click'] = $news['click'];
        $resume = $db->getone("select title from " . table('resume') . " where id=" . $row['resume_id']);
        $row['resume_name'] = $resume['title'];
        $row['article_url'] = $_SERVER['host'] . '/morejobs/jobshow_' . $row['article_id'] . '.html';
        $row['end'] = $row['deadline'] > time() ? 0 : 1;
        $row_arr[] = $row;
    }
    $row_arr = my_sort($row_arr, 'apply_addtime', SORT_DESC);
    $row_arr = array_slice($row_arr, $offset, $perpage);
    return $row_arr;
}

function app_get_jobs($id) {
    global $db;
    if (strpos($id, "-")) {
        $id = str_replace("-", ",", $id);
        if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $id))
            return false;
    }
    else {
        $id = intval($id);
    }
    $sql = "select * from " . table('jobs') . " WHERE id IN ({$id}) ";
    return $db->getall($sql);
}

function app_get_article($id) {
    global $db;
    $sql = "select * from " . table('article') . " WHERE id = " . $id . "; ";
    return $db->getone($sql);
}

function check_article_apply($uid, $job, $resume_id) {
    global $db;
    $sql = "select id from " . table('jiaoshi_article_apply') . " WHERE personal_uid = '" . intval($uid) . "' AND article_job_id ='" . intval($job) . "'  AND resume_id='" . intval($resume_id) . "' LIMIT 1";
    return $db->getone($sql);
}

function del_jobs_apply($del_id, $uid, $articleid) {
    global $db;
    if (!empty($del_id)) {
        $uidsql = " AND personal_uid=" . intval($uid) . " ";
        if (!is_array($del_id))
            $del_id = array($del_id);
        $sqlin = implode(",", $del_id);
        if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
            return false;
        if (!$db->query("Delete from " . table('personal_jobs_apply') . " WHERE did IN (" . $sqlin . ") " . $uidsql . ""))
            return false;
        write_memberslog($_SESSION['uid'], 2, 1302, $_SESSION['username'], "删除了职位申请($sqlin)");
    }
    if (!empty($articleid)) {
        $uidsql = " AND personal_uid=" . intval($uid) . "";
        if (!is_array($articleid))
            $articleid = array($articleid);
        $sqlin = implode(",", $articleid);
        if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
            return false;
        if (!$db->query("Delete from " . table('jiaoshi_article_apply') . " WHERE id IN ({$sqlin}) {$uidsql}"))
            return false;
        write_memberslog($_SESSION['uid'], 2, 1402, $_SESSION['username'], "删除了简章职位申请({$sqlin})");
    }
    return true;
}

function count_resume($uid) {
    global $db;
    $wheresql = " WHERE uid='" . intval($uid) . "' ";
    $total = $db->get_total("SELECT COUNT(*) AS num FROM " . table('resume') . $wheresql);
    return $total;
}

function count_interview($uid, $look = NULL) {
    global $db;
    $uid = intval($uid);
    $wheresql = " WHERE  resume_uid='{$uid}' ";
    if (intval($look) > 0)
        $wheresql.=" AND  personal_look=" . intval($look);
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_interview') . " {$wheresql}";
    return $db->get_total($total_sql);
}

function count_personal_jobs_apply($uid, $look = NULL) {
    global $db;
    $wheresql = " WHERE personal_uid='{$_SESSION['uid']}' ";
    if (intval($look) > 0) {
        $wheresql.=" AND personal_look='{$look}' ";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . $wheresql;
    $total_sql2 = "SELECT COUNT(*) AS num FROM " . table('jiaoshi_article_apply') . $wheresql;
    $t1 = $db->get_total($total_sql);
    $t2 = $db->get_total($total_sql2);
    return $t1 + $t2;
}

function count_jobs_library($uid, $days = NULL) {
    global $db;
    $wheresql = " WHERE personal_uid=" . intval($uid) . " ";
    if (intval($days) > 0) {
        $settr_val = strtotime("-" . $days . " day");
        $wheresql.=" AND addtime>" . $settr_val;
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_favorites') . $wheresql;
    return $db->get_total($total_sql);
}

function get_feedback($uid) {
    global $db;
    $sql = "select * from " . table('feedback') . " where uid='" . intval($uid) . "' ORDER BY id desc";
    return $db->getall($sql);
}

function del_feedback($del_id, $uid) {
    global $db;
    if (!$db->query("Delete from " . table('feedback') . " WHERE id='" . intval($del_id) . "' AND uid='" . intval($uid) . "'  "))
        return false;
    write_memberslog($_SESSION['uid'], 2, 7002, $_SESSION['username'], "删除反馈信息($del_id)");
    return true;
}

function get_interest_jobs_id($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select id from " . table('resume') . " where   uid='{$uid}' LIMIT 3 ";
    $info = $db->getall($sql);
    if (is_array($info)) {
        foreach ($info as $s) {
            $jobsid = get_resume_jobs($s['id']);
            if (is_array($jobsid)) {
                foreach ($jobsid as $cid) {
                    $interest_id[] = $cid['category'];
                }
            }
        }
        if (is_array($interest_id)) {
            return implode("-", array_unique($interest_id));
        }
    }
    return "";
}

function get_interest_jobs_id_by_resume($uid, $pid) {
    global $db;
    $uid = intval($uid);
    $sql = "select id from " . table('resume') . " where   uid='{$uid}' AND id='{$pid}' LIMIT 3 ";
    $info = $db->getone($sql);
    $interest_id = array();
    $jobsid = get_resume_jobs($info['id']);
    if (is_array($jobsid)) {
        foreach ($jobsid as $cid) {
            $interest_id[] = $cid['subclass'];
        }
    }
    if (!empty($interest_id)) {
        return implode("-", array_unique($interest_id));
    }
    return "";
}

function get_interest_jobs_list($cid) {
    global $db;
    $orderbysql = " order by refreshtime desc ";
    $limitsql = " limit 3 ";
    $list = array();
    if ($cid) {
        if (strpos("-", $cid)) {
            $wheresql = "";
            $arr = explode("-", $cid);
            foreach ($arr as $key => $value) {
                $wheresql .= "OR category=" . $value . " ";
            }
            $wheresql = $wheresql ? " WHERE " . trim($wheresql, "OR") : "";
            $list = $db->getall("select * from " . table('jobs') . $wheresql . $orderbysql . $limitsql);
        } else {
            $list = $db->getall("select * from " . table('jobs') . " where category=" . $cid . $orderbysql . $limitsql);
        }
    }
    if (empty($list)) {
        $list = $db->getall("select * from " . table("jobs") . $orderbysql . $limitsql);
    }
    return $list;
}

function check_jobs_report($uid, $jobs_id) {
    global $db;
    $sql = "select id from " . table('report') . " WHERE uid = '" . intval($uid) . "' AND jobs_id='" . intval($jobs_id) . "' LIMIT 1";
    return $db->getone($sql);
}

function get_pms($offset, $perpage, $get_sql = '') {
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

//3.4
function app_get_course($id) {
    global $db;
    $id = intval($id);
    $sql = "select * from " . table('course') . " WHERE id ={$id} limit 1";
    return $db->getone($sql);
}

//3.4
function get_now_applycour_num($uid) {
    global $db;
    $uid = intval($uid);
    $now = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $wheresql = " WHERE personal_uid = '{$uid}' AND apply_addtime>{$now} ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_course_apply') . $wheresql;
    return $db->get_total($total_sql);
}

//3.4
function check_course_apply($courseid, $uid) {
    global $db;
    $sql = "select did from " . table('personal_course_apply') . " WHERE personal_uid = '" . intval($uid) . "' AND course_id='" . intval($courseid) . "' LIMIT 1";
    return $db->getone($sql);
}

function get_apply_course($offset, $perpage, $get_sql = '') {
    global $db;
    $limit = " LIMIT {$offset},{$perpage}";
    $result = $db->query("SELECT * FROM " . table('personal_course_apply') . "  {$get_sql} ORDER BY did DESC {$limit}");
    while ($row = $db->fetch_array($result)) {
        $row['course_url'] = url_rewrite('QS_train_curriculumshow', array('id' => $row['course_id']), false);
        $row['train_url'] = url_rewrite('QS_train_agencyshow', array('id' => $row['train_id']), false);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function count_personal_cour_apply($uid, $look = NULL) {
    global $db;
    $wheresql = " WHERE personal_uid='{$_SESSION['uid']}' ";
    if (intval($look) > 0)
        $wheresql.=" AND personal_look='{$look}' ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_course_apply') . $wheresql;
    return $db->get_total($total_sql);
}

function del_apply($del_id, $uid) {
    global $db;
    $uidsql = " AND personal_uid=" . intval($uid) . "";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('personal_course_apply') . " WHERE did IN ({$sqlin}) {$uidsql}"))
        return false;
    write_memberslog($_SESSION['uid'], 2, 1402, $_SESSION['username'], "删除课程申请({$sqlin})");
    return true;
}

function count_personal_resume_down($uid) {
    global $db;
    $wheresql = " WHERE resume_uid='{$uid}' ";
    $num = $db->get_total("SELECT COUNT(*) AS num FROM " . table('company_down_resume') . $wheresql);
    return $num;
}

function get_subsite_list() {
    global $db;
    $sql = "select * from " . table('subsite');
    return $db->getall($sql);
}

function get_view_jobs($offset, $perpage, $get_sql = '') {
    global $db;
    $limit = " LIMIT {$offset},{$perpage}";
    $selectstr = " a.*,r.jobs_name,r.uid as company_uid,r.subsite_id,r.audit,r.deadline,r.display ";
    $result = $db->query("SELECT {$selectstr} FROM " . table('view_jobs') . " as a {$get_sql} ORDER BY a.id DESC {$limit}");
    while ($row = $db->fetch_array($result)) {
        if (empty($row['jobs_name'])) {
            $jobs = $db->getone("select * from " . table('jobs_tmp') . " WHERE `id`='{$row['jobsid']}' LIMIT 1");
            $row['jobs_name'] = $jobs['jobs_name'];
            $row['jobsid'] = $jobs['id'];
            $row['company_uid'] = $jobs['uid'];
            $row['subsite_id'] = $jobs['subsite_id'];
            $row['audit'] = $jobs['audit'];
            $row['deadline'] = $jobs['deadline'];
            $row['display'] = $jobs['display'];
        }
        if (!empty($row['jobs_name'])) {
            $company_profile = $db->getone("select `id`,`companyname` from " . table('company_profile') . " where `uid`=" . $row['company_uid']);
            $row['companyname'] = $company_profile['companyname'];
            $row['company_url'] = url_rewrite("QS_companyshow", array('id' => $company_profile['id']), false);
        }
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
        $row['url'] = url_rewrite("QS_jobsshow", array('id' => $row['jobsid']), true, $row['subsite_id']);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_view_jobs($del_id, $uid) {
    global $db;
    $uidsql = " AND uid=" . intval($uid) . "";
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('view_jobs') . " WHERE id IN ({$sqlin}) {$uidsql}"))
        return false;
    return true;
}

function get_view_resume($offset, $perpage, $get_sql = '') {
    global $db;
    $limit = " LIMIT {$offset},{$perpage}";
    $selectstr = " a.*,r.subsite_id,r.id as resume_id,r.uid as resume_uid,r.title ";
    $result = $db->query("SELECT {$selectstr} FROM " . table('view_resume') . " as a {$get_sql} ORDER BY a.id DESC {$limit}");
    while ($row = $db->fetch_array($result)) {
        if ($row['resume_uid'] != intval($_SESSION['uid'])) {
            continue;
        }
        $row['title'] = cut_str($row['title'], 13, 0, "..");
        $company_profile = $db->getone("select `id`,`companyname` from " . table('company_profile') . " where `uid`=" . $row['uid']);
        $row['companyname'] = $company_profile['companyname'];
        $row['companyname'] = cut_str($row['companyname'], 13, 0, "..");
        $row['company_url'] = url_rewrite("QS_companyshow", array('id' => $company_profile['id']), false);
        $row['url'] = url_rewrite("QS_resumeshow", array('id' => $row['resumeid']), true, $row['subsite_id']);

        $jobs = $db->getall("select * from " . table('jobs') . " where `uid`=" . $row['uid'] . $wheresql);
        foreach ($jobs as $key1 => $value1) {
            $row['jobslist'][$key1]['jobsname'] = $value1['jobs_name'];
            $row['jobslist'][$key1]['jobs_url'] = url_rewrite("QS_jobsshow", array('id' => $value1['id']), true, $value1['subsite_id']);
        }
        if ($row['resume_uid']) {
            $downlog = $db->getone("select did from " . table('company_down_resume') . " where resume_id={$row['resumeid']} and resume_uid={$row['resume_uid']} and company_uid={$row['uid']}");
            if (intval($downlog)) {
                $row['hasdown'] = 1;
            } else {
                $row['hasdown'] = 0;
            }
        }

        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_my_resume($uid) {
    global $db;
    $wheresql = " where uid=" . $uid . " ";
    $sql = "SELECT id FROM " . table('resume') . $wheresql;
    $my_resume = $db->getall($sql);
    foreach ($my_resume as $key => $value) {
        $idarr[] = $value['id'];
    }
    $idstr = implode(",", $idarr);
    return $idstr;
}

function del_view_resume($del_id) {
    global $db;
    if (!is_array($del_id))
        $del_id = array($del_id);
    $sqlin = implode(",", $del_id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('view_resume') . " WHERE id IN ({$sqlin}) "))
        return false;
    return true;
}

function count_personal_attention_me($uid) {
    global $db;
    $id_arr = array();
    $id_str = "";
    $total = 0;
    $personal_resume = $db->getall("select id from " . table('resume') . " where uid=" . $uid);
    if ($personal_resume) {
        foreach ($personal_resume as $key => $value) {
            $id_arr[] = $value["id"];
        }
        $id_str = implode(",", $id_arr);
        $total = $db->get_total("select count(*) as num from " . table("view_resume") . " where resumeid in (" . $id_str . ")");
    }
    return $total;
}

//获取简历屏蔽企业关键字
function get_com_keyword($uid, $pid) {
    global $db;
    $result = $db->getall("select * from " . table('personal_shield_company') . " where uid=" . $uid . " and pid=" . $pid);
    return $result;
}

function del_shield_company($uid, $pid, $keyword_id) {
    global $db;
    if (!$db->query("delete from " . table('personal_shield_company') . " where uid=" . $uid . " and pid=" . $pid . " and id=" . $keyword_id))
        return false;
    return true;
}

function count_com_keyword($uid, $pid) {
    global $db;
    $count = $db->get_total("select count(*) as num from " . table('personal_shield_company') . " where uid=" . $uid . " and pid=" . $pid);
    return $count;
}

function set_resume_entrust($resume_id) {
    global $db;
    $resume = $db->getone("select audit,uid,fullname,addtime,entrust from " . table('resume') . " where id=" . $resume_id);
    $db->query("delete from " . table('resume_entrust') . " where id=" . $resume_id);
    if ($resume["audit"] == "1" && $resume["entrust"] == "1") {
        $has = $db->getone("select 1 from " . table('resume_entrust') . " where id=" . $resume_id);
        if (!$has) {
            $setsqlarr['id'] = $resume_id;
            $setsqlarr['uid'] = $resume['uid'];
            $setsqlarr['fullname'] = $resume['fullname'];
            $setsqlarr['resume_addtime'] = $resume['addtime'];
            inserttable(table('resume_entrust'), $setsqlarr);
            updatetable(table('resume'), array("entrust" => "1"), " id=" . $resume_id . " ");
        }
    }
    return true;
}

function set_category($c_alias) {
    global $db;
    $category = $db->getall("select * from " . table('category') . " where c_alias='" . $c_alias . "'");
    return $category;
}

function set_subscribe($email = "") {
    global $db;
    $subscribe = "";
    if (!empty($email)) {
        $subscribe = $db->getone("select * from " . table('jobs_subscribe') . " where email='" . $email . "'");
    }
    return $subscribe;
}

?>