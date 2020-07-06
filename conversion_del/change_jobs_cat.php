<?php

/*
 * 74cms 企业用户转换
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

exit("所有职位分类已经全部处理完成！");
?>