<?php
 /*
 * 74cms 招聘会
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
 if(!defined('IN_QISHI'))
 {
 	die('Access Denied!');
 }
function get_jobfair($offset, $perpage, $sql= '')
{
	global $db;
	$row_arr = array();
	$limit=" LIMIT ".$offset.','.$perpage;
	$result = $db->query("SELECT * FROM ".table('jobfair')." ".$sql." ".$limit);
	while($row = $db->fetch_array($result))
	{
	$row_arr[] = $row;
	}
	return $row_arr;
}
function get_jobfair_display()
{
	global $db;
	$info = $db->getall("SELECT * FROM ".table('jobfair')." WHERE display=1  order BY `order` DESC,id DESC");
	return $info;
}
function get_jobfair_audit()
{
	global $db;
	$info = $db->getall("SELECT * FROM ".table('jobfair')." WHERE display=1 AND predetermined_status=1 AND holddates>".time()." order BY `order` DESC,id DESC");
	return $info;
}
function del_jobfair($id)
{
	global $db;
	if(!is_array($id)) $id=array($id);
	$return=0;
	$sqlin=implode(",",$id);
	if (preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin))
	{
		if (!$db->query("Delete from ".table('jobfair')." WHERE id IN (".$sqlin.")")) return false;
		$return=$return+$db->affected_rows();
	}
	return $return;
}
function get_jobfair_exhibitors($offset, $perpage, $sql= '')
{
	global $db;
	$row_arr = array();
	$limit=" LIMIT ".$offset.','.$perpage;
	$result = $db->query("SELECT * FROM ".table('jobfair_exhibitors')." ".$sql." ".$limit);
	while($row = $db->fetch_array($result))
	{
	if ($row['uid']>0 && $row['company_id']>0)
	{
	$row['company_url']=url_rewrite('QS_companyshow',array('id'=>$row['company_id']),false);
	}
	else
	{
	$row['company_url']="";
	}	
	$jobfair = $db->getone("select subsite_id from ".table('jobfair')." where id=".$row['jobfairid']);
	$row['jobfair_url']=url_rewrite('QS_jobfairshow',array('id'=>$row['jobfairid']),true,$jobfair['subsite_id']);
	$row_arr[] = $row;
	}
	return $row_arr;
}
function del_exhibitors($id)
{
	global $db;
	if(!is_array($id)) $id=array($id);
	$return=0;
	$sqlin=implode(",",$id);
	if (preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin))
	{
		if (!$db->query("Delete from ".table('jobfair_exhibitors')." WHERE id IN (".$sqlin.")")) return false;
		$return=$return+$db->affected_rows();
	}
	return $return;
}
function edit_exhibitors_audit($id,$audit)
{
	global $db;
	if (!is_array($id))$id=array($id);
	$return=0;
	$sqlin=implode(",",$id);	
	if (preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin))
	{
		if (!$db->query("update  ".table('jobfair_exhibitors')." SET audit='".intval($audit)."'  WHERE id IN (".$sqlin.")")) return false;
		$return=$return+$db->affected_rows();
	}
	return $return;
}
?>