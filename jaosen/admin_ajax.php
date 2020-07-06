<?php

/*
 * 74cms ajax
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../data/config.php');
require_once(dirname(__FILE__) . '/include/admin_common.inc.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'total';
if ($act == 'total') {
    $total_jobs = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . " WHERE audit=2", "get_total");
    $total_company = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('company_profile') . " WHERE audit=2", "get_total");
    $total_payment_log = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('order') . " WHERE is_paid=1 and utype=1", "get_total");
    $total_hunter_payment_log = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('order') . " WHERE is_paid=1 and utype=3", "get_total");
    $total_train_payment_log = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('order') . " WHERE is_paid=1 and utype=4", "get_total");
    $total_resume_audit = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('resume') . " WHERE audit=2", "get_total");
    $total_resume_talent = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('resume') . " WHERE talent=3 and display = 1 and audit = 1", "get_total");
    $total_resume_photo_audit = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('resume') . " WHERE photo_audit=2", "get_total");
    $total_feedback_replyinfo = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('feedback'), "get_total"); //意见与建议
    $total_report = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('report') . " ", "get_total"); //所有投诉信息

    $total_course_audit = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('course') . " WHERE audit=2", "get_total");
    $total_hunter_jobs_audit = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('hunter_jobs') . " WHERE audit=2", "get_total");
    $total_resume_certificate = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('resume_certificate') . " WHERE audit=0", "get_total");

    $str = "[{$total_jobs}]";
    $str.=",[{$total_resume_audit}]";
    $str.=",[{$total_company}]";
    $str.=",[{$total_resume_talent}]";
    $str.=",[{$total_payment_log}]";
    $str.=",[{$total_resume_photo_audit}]";
    $str.=",[{$total_report}]";
    $str.=",[{$total_feedback_replyinfo}]";
    $str.=",[{$total_hunter_payment_log}]";
    $str.=",[{$total_train_payment_log}]";
    $str.=",[{$total_course_audit}]";
    $str.=",[{$total_hunter_jobs_audit}]";
    $str.=",[{$total_resume_certificate}]";
    exit($str);
} elseif ($act == 'get_cat_city') {
    $pid = intval($_GET['pid']);
    $sql = "select * from " . table('category_district') . " where parentid='{$pid}'  order BY category_order desc,id asc";
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        $cat[] = $row['id'] . "," . $row['categoryname'] . "," . $row['category_order'];
    }
    if (!empty($cat)) {
        exit(implode('|', $cat));
    }
} elseif ($act == 'get_cat_jobs') {
    $pid = intval($_GET['pid']);
    $sql = "select * from " . table('category_jobs') . " where parentid='{$pid}'  order BY category_order desc,id asc";
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        $cat[] = $row['id'] . "," . $row['categoryname'] . "," . $row['category_order'];
    }
    if (!empty($cat)) {
        exit(implode('|', $cat));
    }
}
//高级职位
elseif ($act == 'get_cat_hunterjobs') {
    $pid = intval($_GET['pid']);
    $sql = "select * from " . table('category_hunterjobs') . " where parentid='{$pid}'  order BY category_order desc,id asc";
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        $cat[] = $row['id'] . "," . $row['categoryname'] . "," . $row['category_order'];
    }
    if (!empty($cat)) {
        exit(implode('|', $cat));
    }
} elseif ($act == 'get_jobs') {
    $type = trim($_GET['type']);
    $key = trim($_GET['key']);
    if (strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $key = iconv("utf-8", QISHI_DBCHARSET, $key);
    }
    if ($type == "get_id") {
        $id = intval($key);
        $sql = "select * from " . table('jobs') . " where id='{$id}'  LIMIT 1";
    } elseif ($type == "get_jobname") {
        $sql = "select * from " . table('jobs') . " where jobs_name like '%{$key}%'  LIMIT 30";
    } elseif ($type == "get_comname") {
        $sql = "select * from " . table('jobs') . " where companyname like '%{$key}%'  LIMIT 30";
    } elseif ($type == "get_uid") {
        $uid = intval($key);
        $sql = "select * from " . table('jobs') . " where uid='{$uid}'  LIMIT 30";
    } else {
        exit();
    }
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        $row['addtime'] = date("Y-m-d", $row['addtime']);
        $row['deadline'] = date("Y-m-d", $row['deadline']);
        $row['refreshtime'] = date("Y-m-d", $row['refreshtime']);
        $row['company_url'] = url_rewrite('QS_companyshow', array('id' => $row['company_id']), false);
        $row['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $row['id']), false);
        $info[] = $row['id'] . "%%%" . $row['jobs_name'] . "%%%" . $row['jobs_url'] . "%%%" . $row['companyname'] . "%%%" . $row['company_url'] . "%%%" . $row['addtime'] . "%%%" . $row['deadline'] . "%%%" . $row['refreshtime'];
    }
    if (!empty($info)) {
        exit(implode('@@@', $info));
    } else {
        exit();
    }
} elseif ($act == 'get_company') {
    $type = trim($_GET['type']);
    $key = trim($_GET['key']);
    if (strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $key = iconv("utf-8", QISHI_DBCHARSET, $key);
    }
    if ($type == "getuname") {
        $sql = "select * from " . table('members') . " AS m  LEFT JOIN  " . table('company_profile') . " AS c ON  m.uid=c.uid where m.username like '{$key}%' AND m.utype=1 LIMIT 20";
    } elseif ($type == "getcomname") {
        $sql = "select * from " . table('company_profile') . " where companyname like '%{$key}%'  LIMIT 30";
    } else {
        exit();
    }
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        if (empty($row['companyname'])) {
            continue;
        }
        $row['addtime'] = date("Y-m-d", $row['addtime']);
        $row['company_url'] = url_rewrite('QS_companyshow', array('id' => $row['id']), false);
        $info[] = $row['id'] . "%%%" . $row['companyname'] . "%%%" . $row['company_url'] . "%%%" . $row['addtime'];
    }
    if (!empty($info)) {
        exit(implode('@@@', $info));
    }
} elseif ($act == 'get_user_info') {
    $id = intval($_GET['id']);
    $info = get_mem_cache("select * from " . table('members') . " where uid='{$id}' LIMIT 1", "getone");
    if (empty($info)) {
        exit("会员信息不存在！可能已经被删除！");
    }
    $html = "用户名：{$info['username']}&nbsp;&nbsp;<span style=\"color:#0033CC\">(uid:{$info['uid']})</span><br/>";
    if (!empty($info['mobile'])) {
        $mobile_audit = $info['mobile_audit'] == "1" ? '<span style="color:#009900">[已验证]</span>' : '<span style="color:#FF9900">[未验证]</span>';
        $info['mobile'] = $info['mobile'] . $mobile_audit;
    } else {
        $info['mobile'] = '----';
    }
    $html.="手机：{$info['mobile']}<br/>";
    $email_audit = $info['email_audit'] == "1" ? '<span style="color:#009900">[已验证]</span>' : '<span style="color:#FF9900">[未验证]</span>';
    $html.="邮箱：{$info['email']}{$email_audit}<br/>";
    $info['reg_time'] = $info['reg_time'] ? date("Y/m/d H:i", $info['reg_time']) : '----';
    $html.="注册时间：{$info['reg_time']}<br/>";
    $info['reg_ip'] = $info['reg_ip'] ? $info['reg_ip'] : '----';
    $html.="注册IP：{$info['reg_ip']}<br/>";
    $info['last_login_time'] = $info['last_login_time'] ? date("Y/m/d H:i", $info['last_login_time']) : '----';
    $html.="最后登录时间：{$info['last_login_time']}<br/>";
    $info['last_login_ip'] = $info['last_login_ip'] ? $info['last_login_ip'] : '----';
    $html.="最后登录IP：{$info['last_login_ip']}<br/>";
    if ($info['utype'] == "1") {
        $points = get_mem_cache("select points from " . table('members_points') . " where uid = '{$id}'  LIMIT 1 ", "getone");
        $html.="{$_CFG['points_byname']}：{$points['points']}{$_CFG['points_quantifier']}<br/>";
        $com = get_mem_cache("select companyname from " . table('company_profile') . " where uid='{$id}' LIMIT 1", "getone");
        if (empty($com['companyname'])) {
            $com['companyname'] = "未填写";
        }
        $html.="公司名称：{$com['companyname']}<br/>";
        $totaljob = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('jobs') . "  where uid='{$id}'", "get_total");
        $html.="发布职位：{$totaljob}条<br/>";
        if ($_CFG['operation_mode'] == "2") {
            $setmeal = get_mem_cache("select * from " . table('members_setmeal') . "  WHERE uid='{$id}' AND  effective=1 LIMIT 1", "getone");
            if (!empty($setmeal)) {
                $html.="服务套餐：{$setmeal['setmeal_name']}<br/>";
                $html.="服务期限：" . date("Y/m/d", $setmeal['starttime']) . "--" . date("Y/m/d", $setmeal['endtime']);
            }
        }
    }
    if ($info['utype'] == "2") {
        $totalresume = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('resume') . "  where uid='{$id}'", "get_total");
        $html.="发布简历：{$totalresume}条<br/>";
    }
    exit($html);
}
?>