<?php

/*
 * 74cms 网站首页
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
require_once('../include/common.inc.tmp.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$company_name = trim($_POST['company_name']);
$company_address = trim($_POST['company_address']);
$job_address = trim($_POST['job_address']);
$job_name = trim($_POST['job_name']);
$job_nature = trim($_POST['job_nature']);
$job_district = trim($_POST['job_district']);
$job_education = !empty($_POST['job_education']) ? trim($_POST['job_education']) : "不限学历";
$job_experience = !empty($_POST['job_experience']) ? trim($_POST['job_experience']) : "不限经验";
$job_wage = !empty($_POST['job_wage']) ? trim($_POST['job_wage']) : "面议";
$job_contents = trim($_POST['job_contents']);
$job_addtime = trim($_POST['job_addtime']);
$collect_url = trim($_POST['url']);
$collect_state = 1;
$collect_time = time();
$sql = "select id from " . table("collect_jobs_log") . " where collect_url = '" . $collect_url . "'";
$result = $db->getone($sql);
if (empty($result)) {
    $f_id = FALSE;
    if (!empty($job_contents)) {
        $data['company_name'] = $company_name;
        $data['company_address'] = $company_address;
        $data['job_address'] = $job_address;
        $data['job_name'] = $job_name;
        $data['job_nature'] = $job_nature;
        $data['job_district'] = $job_district;
        $data['job_education'] = $job_education;
        $data['job_experience'] = $job_experience;
        $data['job_wage'] = $job_wage;
        $data['job_contents'] = $job_contents;
        $data['job_addtime'] = strtotime($job_addtime);
        $data['collect_url'] = $collect_url;
        $data['collect_state'] = $collect_state;
        $data['collect_time'] = $collect_time;
        $f_id = inserttable(table('collect_jobs_log'), $data, 1);
    }
    if ($f_id) {
        return TRUE;
    } else {
        return FALSE;
    }
}
return TRUE;
?>