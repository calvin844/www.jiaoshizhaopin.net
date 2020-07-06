<?php

/*
 * 74cms ������
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
require_once(ADMIN_ROOT_PATH . 'include/admin_company_fun.php');

$ip = isset($_GET['ip']) ? trim($_GET['ip']) : 0;
$del = isset($_GET['del']) ? intval($_GET['del']) : 0;
$deluser = isset($_GET['deluser']) ? intval($_GET['deluser']) : 0;
if ($del == 2) {
    $company_sql = "SELECT uid FROM " . table('company_profile') . " WHERE `contact` LIKE '����'";
    $company = $db->getall($company_sql);
    foreach ($company as $c) {
        $user_id_arr[] = $c['uid'];
    }
    $user_id = implode(",", $user_id_arr);
    if (empty($user_id_arr)) {
        exit("����������û�");
    }
    $jobs_sql = "SELECT id,jobs_name FROM " . table('jobs') . " WHERE uid IN(" . $user_id . ")";
    $jobs_arr = $db->getall($jobs_sql);
    foreach ($jobs_arr as $ja) {
        $db->query("Delete from " . table('jobs') . " WHERE id =" . $ja['id']);
        $jobs_id[] = $ja['id'];
    }
    $jobs_sql_tmp = "SELECT id,jobs_name FROM " . table('jobs_tmp') . " WHERE uid IN(" . $user_id . ")";
    $jobs_arr_tmp = $db->getall($jobs_sql_tmp);
    foreach ($jobs_arr_tmp as $jat) {
        $db->query("Delete from " . table('jobs_tmp') . " WHERE id =" . $jat['id']);
        $jobs_id[] = $jat['id'];
    }
    $jobs_id = implode(",", $jobs_id);
    if (!empty($jobs_id)) {
        $db->query("Delete from " . table('jiaoshi_article_jobs_index') . " WHERE p_id IN(" . $jobs_id . ")");
        $db->query("Delete from " . table('jobs_contact') . " WHERE pid IN(" . $jobs_id . ")");
        $db->query("Delete from " . table('jobs_search_hot') . " WHERE id IN(" . $jobs_id . ")");
        $db->query("Delete from " . table('jobs_search_key') . " WHERE id IN(" . $jobs_id . ")");
        $db->query("Delete from " . table('jobs_search_rtime') . " WHERE id IN(" . $jobs_id . ")");
        $db->query("Delete from " . table('jobs_search_scale') . " WHERE id IN(" . $jobs_id . ")");
        $db->query("Delete from " . table('jobs_search_stickrtime') . " WHERE id IN(" . $jobs_id . ")");
        $db->query("Delete from " . table('jobs_search_wage') . " WHERE id IN(" . $jobs_id . ")");
        $db->query("Delete from " . table('jobs_search_tag') . " WHERE id IN(" . $jobs_id . ")");
    }
    !delete_company_user($user_id_arr) ? adminmsg("ɾ����Աʧ�ܣ�", 0) : "";
    $company_data = get_company_uid($user_id_arr);
    foreach ($company_data as $c) {
        !del_company($c['id']) ? adminmsg("ɾ����ҵ����ʧ�ܣ�", 0) : "";
    }
    exit('ɾ�����');
}
if ($ip && $del == 0) {
    $user_sql = "SELECT uid FROM " . table('members') . " WHERE last_login_ip = '" . $ip . "'";
    $user = $db->getall($user_sql);
    foreach ($user as $u) {
        $user_id_arr[] = $u['uid'];
    }
    $user_id = implode(",", $user_id_arr);
    if (empty($user_id_arr)) {
        exit("����������û�");
    }
    $user_num = count($user_id_arr);
    $jobs_sql_tmp = "SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . " WHERE uid IN(" . $user_id . ")";
    $jobs_tmp = $db->getone($jobs_sql_tmp);
    $jobs_sql = "SELECT COUNT(*) AS num FROM " . table('jobs') . " WHERE uid IN(" . $user_id . ")";
    $jobs = $db->getone($jobs_sql);
    $total = (intval($jobs['num']) + intval($jobs_tmp['num']));
    exit("ӵ���˺�����" . $user_num . "<br/>ְλ������" . $total . "<br/>չʾְλ��" . $jobs['num'] . "<br/>��չʾְλ��" . $jobs_tmp['num']);
}
if ($ip && $del == 1) {
    $user_sql = "SELECT uid FROM " . table('members') . " WHERE last_login_ip = '" . $ip . "'";
    $user = $db->getall($user_sql);
    foreach ($user as $u) {
        $user_id_arr[] = $u['uid'];
    }
    $user_id = implode(",", $user_id_arr);
    if (empty($user_id_arr)) {
        exit("����������û�");
    }
    $jobs_sql = "SELECT id,jobs_name FROM " . table('jobs') . " WHERE uid IN(" . $user_id . ")";
    $jobs_arr = $db->getall($jobs_sql);
    $str = "����ְλ�ѱ�ɾ����<br/>";
    foreach ($jobs_arr as $ja) {
        $db->query("Delete from " . table('jobs') . " WHERE id =" . $ja['id']);
        $jobs_id[] = $ja['id'];
        $str .= $ja['jobs_name'] . "<br/>";
    }
    $jobs_sql_tmp = "SELECT id,jobs_name FROM " . table('jobs_tmp') . " WHERE uid IN(" . $user_id . ")";
    $jobs_arr_tmp = $db->getall($jobs_sql_tmp);
    foreach ($jobs_arr_tmp as $jat) {
        $db->query("Delete from " . table('jobs_tmp') . " WHERE id =" . $jat['id']);
        $jobs_id[] = $jat['id'];
        $str .= $jat['jobs_name'] . "<br/>";
    }
    $jobs_id = implode(",", $jobs_id);
    $str = "ְλID��" . $jobs_id . "<br/>" . $str;
    $db->query("Delete from " . table('jiaoshi_article_jobs_index') . " WHERE p_id IN(" . $jobs_id . ")");
    $db->query("Delete from " . table('jobs_contact') . " WHERE pid IN(" . $jobs_id . ")");
    $db->query("Delete from " . table('jobs_search_hot') . " WHERE id IN(" . $jobs_id . ")");
    $db->query("Delete from " . table('jobs_search_key') . " WHERE id IN(" . $jobs_id . ")");
    $db->query("Delete from " . table('jobs_search_rtime') . " WHERE id IN(" . $jobs_id . ")");
    $db->query("Delete from " . table('jobs_search_scale') . " WHERE id IN(" . $jobs_id . ")");
    $db->query("Delete from " . table('jobs_search_stickrtime') . " WHERE id IN(" . $jobs_id . ")");
    $db->query("Delete from " . table('jobs_search_wage') . " WHERE id IN(" . $jobs_id . ")");
    $db->query("Delete from " . table('jobs_search_tag') . " WHERE id IN(" . $jobs_id . ")");
    if (empty($jobs_id)) {
        $str = "û�п�ɾ��ְλ��";
    }
    if ($deluser == 1) {
        !delete_company_user($user_id_arr) ? adminmsg("ɾ����Աʧ�ܣ�", 0) : "";

        $company_data = get_company_uid($user_id_arr);
        foreach ($company_data as $c) {
            !del_company($c['id']) ? adminmsg("ɾ����ҵ����ʧ�ܣ�", 0) : "";
        }
    }
    exit($str);
}
$smarty->display('sys/admin_del_jobs_byip.htm');
?>