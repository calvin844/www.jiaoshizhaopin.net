<?php

/*
 * 74cms ְλ����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);

require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
unset($dbhost, $dbuser, $dbpass, $dbname);
trim($_POST['contact']) ? $contact_data['contact'] = trim($_POST['contact']) : "";
trim($_POST['email']) ? $contact_data['email'] = trim($_POST['email']) : "";
trim($_POST['telephone']) ? $contact_data['telephone'] = trim($_POST['telephone']) : "";
trim($_POST['address']) ? $contact_data['address'] = trim($_POST['address']) : "";
$postcaptcha = $_POST['postcaptcha'];
if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
    $postcaptcha = utf8_to_gbk($postcaptcha);
}
if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
    echo '<script>alert("��֤�����");window.history.go(-1)</script>';
    exit();
}

$company_data['uid'] = $_SESSION['uid'];
$company_data['id'] = $_SESSION['bind_company_id'];
$company_data['refreshtime'] = time();
/*
  $company = $db->getone("select * from " . table('company_profile') . " where id='" . $company_data['id'] . "' and uid=0");
  if (empty($company)) {
  echo '<script>alert("����ҵ����������Ա��");window.history.go(-1)</script>';
  }
 * 
 */
updatetable(table('company_profile'), $company_data, " id='" . intval($company_data['id']) . "' ");
$job_data['uid'] = $_SESSION['uid'];
$job_data['refreshtime'] = time();
$job_data['robot'] = 0;
updatetable(table('jobs'), $job_data, " company_id='" . intval($company_data['id']) . "' ");
updatetable(table('jobs_tmp'), $job_data, " company_id='" . intval($company_data['id']) . "' ");
$job_arr = $db->getall("select * from " . table('jobs') . " where company_id='" . $company_data['id'] . "'");
foreach ($job_arr as $ja) {
    updatetable(table('jobs_search_hot'), $job_data, " id='" . intval($ja['id']));
    updatetable(table('jobs_search_key'), $job_data, " id='" . intval($ja['id']));
    updatetable(table('jobs_search_rtime'), $job_data, " id='" . intval($ja['id']));
    updatetable(table('jobs_search_stickrtime'), $job_data, " id='" . intval($ja['id']));
    updatetable(table('jobs_search_wage'), $job_data, " id='" . intval($ja['id']));
    $apply_data['company_uid'] = $_SESSION['uid'];
    updatetable(table('personal_jobs_apply'), $apply_data, " jobs_id='" . intval($ja['id']));
    $contact_arr = $db->getone("select * from " . table('jobs_contact') . " where pid='" . intval($ja['id']) . "'");
    if (!empty($contact_arr)) {
        updatetable(table('jobs_contact'), $contact_data, " pid='" . intval($ja['id']) . "'");
    } else {
        $contact_data['notify'] = 1;
        $contact_data['contact_show'] = 0;
        $contact_data['email_show'] = 0;
        $contact_data['telephone_show'] = 0;
        $contact_data['address_show'] = 0;
        $contact_data['pid'] = intval($ja['id']);
        $insertid = inserttable(table('jobs_contact'), $contact_data);
    }
}
echo '<script>alert("�󶨳ɹ���");window.location.href="/user/company/company_index.php"</script>';
?>