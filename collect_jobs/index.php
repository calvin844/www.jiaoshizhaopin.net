<?php

/*
 * 74cms ��վ��ҳ
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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
$job_education = !empty($_POST['job_education']) ? trim($_POST['job_education']) : "����ѧ��";
$job_experience = !empty($_POST['job_experience']) ? trim($_POST['job_experience']) : "���޾���";
$job_wage = !empty($_POST['job_wage']) ? trim($_POST['job_wage']) : "����";
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