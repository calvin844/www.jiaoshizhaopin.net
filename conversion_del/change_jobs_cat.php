<?php

/*
 * 74cms ��ҵ�û�ת��
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
require_once('../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
require_once('conversion_cngfig.php');
//$start_id = $_GET['id'];

/*
$db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id NOT IN( select id from " . table("jobs") . ") and type = 'jobs' ");
$db->query("Delete from  " . table('jobs_search_hot') . " where id NOT IN( select id from " . table("jobs") . ")");
$db->query("Delete from  " . table('jobs_search_key') . " where id NOT IN( select id from " . table("jobs") . ")");
$db->query("Delete from  " . table('jobs_search_rtime') . " where id NOT IN( select id from " . table("jobs") . ")");
$db->query("Delete from  " . table('jobs_search_scale') . " where id NOT IN( select id from " . table("jobs") . ")");
$db->query("Delete from  " . table('jobs_search_stickrtime') . " where id NOT IN( select id from " . table("jobs") . ")");
$db->query("Delete from  " . table('jobs_search_wage') . " where id NOT IN( select id from " . table("jobs") . ")");
$db->query("Delete from  " . table('jobs_search_tag') . " where id NOT IN( select id from " . table("jobs") . ")");
 * 
 */

$sql = "select * from " . table("resume_jobs") . " where topclass > 0 and category =0 ";
$err = $db->getall($sql);

foreach ($err as $e) {
    $id = $e['id'];
    $uid = $e['uid'];
    $topclass = $e['topclass'];
    $category = $e['category'];
    $subclass = $e['subclass'];
    $up['topclass'] = 0;
    $up['category'] = $subclass;
    $up['subclass'] = $topclass;
    $wheresqlarr = " id = " . $id . " and uid = " . $uid;
    updatetable(table("resume_jobs"), $up, $wheresqlarr);
    //$wheresqlarr = " p_id = " . $id . " and type = 'jobs'";
    //updatetable(table("jiaoshi_article_jobs_index"), $up, $wheresqlarr);
}

exit("����ְλ�����Ѿ�ȫ��������ɣ�");
?>