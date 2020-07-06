<?php

/*
 * 74cms ajax
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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
    $total_feedback_replyinfo = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('feedback'), "get_total"); //����뽨��
    $total_report = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('report') . " ", "get_total"); //����Ͷ����Ϣ

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
//�߼�ְλ
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
        exit("��Ա��Ϣ�����ڣ������Ѿ���ɾ����");
    }
    $html = "�û�����{$info['username']}&nbsp;&nbsp;<span style=\"color:#0033CC\">(uid:{$info['uid']})</span><br/>";
    if (!empty($info['mobile'])) {
        $mobile_audit = $info['mobile_audit'] == "1" ? '<span style="color:#009900">[����֤]</span>' : '<span style="color:#FF9900">[δ��֤]</span>';
        $info['mobile'] = $info['mobile'] . $mobile_audit;
    } else {
        $info['mobile'] = '----';
    }
    $html.="�ֻ���{$info['mobile']}<br/>";
    $email_audit = $info['email_audit'] == "1" ? '<span style="color:#009900">[����֤]</span>' : '<span style="color:#FF9900">[δ��֤]</span>';
    $html.="���䣺{$info['email']}{$email_audit}<br/>";
    $info['reg_time'] = $info['reg_time'] ? date("Y/m/d H:i", $info['reg_time']) : '----';
    $html.="ע��ʱ�䣺{$info['reg_time']}<br/>";
    $info['reg_ip'] = $info['reg_ip'] ? $info['reg_ip'] : '----';
    $html.="ע��IP��{$info['reg_ip']}<br/>";
    $info['last_login_time'] = $info['last_login_time'] ? date("Y/m/d H:i", $info['last_login_time']) : '----';
    $html.="����¼ʱ�䣺{$info['last_login_time']}<br/>";
    $info['last_login_ip'] = $info['last_login_ip'] ? $info['last_login_ip'] : '----';
    $html.="����¼IP��{$info['last_login_ip']}<br/>";
    if ($info['utype'] == "1") {
        $points = get_mem_cache("select points from " . table('members_points') . " where uid = '{$id}'  LIMIT 1 ", "getone");
        $html.="{$_CFG['points_byname']}��{$points['points']}{$_CFG['points_quantifier']}<br/>";
        $com = get_mem_cache("select companyname from " . table('company_profile') . " where uid='{$id}' LIMIT 1", "getone");
        if (empty($com['companyname'])) {
            $com['companyname'] = "δ��д";
        }
        $html.="��˾���ƣ�{$com['companyname']}<br/>";
        $totaljob = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('jobs') . "  where uid='{$id}'", "get_total");
        $html.="����ְλ��{$totaljob}��<br/>";
        if ($_CFG['operation_mode'] == "2") {
            $setmeal = get_mem_cache("select * from " . table('members_setmeal') . "  WHERE uid='{$id}' AND  effective=1 LIMIT 1", "getone");
            if (!empty($setmeal)) {
                $html.="�����ײͣ�{$setmeal['setmeal_name']}<br/>";
                $html.="�������ޣ�" . date("Y/m/d", $setmeal['starttime']) . "--" . date("Y/m/d", $setmeal['endtime']);
            }
        }
    }
    if ($info['utype'] == "2") {
        $totalresume = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('resume') . "  where uid='{$id}'", "get_total");
        $html.="����������{$totalresume}��<br/>";
    }
    exit($html);
}
?>