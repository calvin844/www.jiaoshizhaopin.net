<?php
function tpl_function_qishi_manager_resume_show($params, &$smarty)
{
global $db,$_CFG;
$arr=explode(',',$params['set']);
foreach($arr as $str)
{
$a=explode(':',$str);
	switch ($a[0])
	{
	case "简历ID":
		$aset['id'] = $a[1];
		break;
	case "列表名":
		$aset['listname'] = $a[1];
		break;
	}
}
$aset=array_map("get_smarty_request",$aset);
$aset['id']=$aset['id']?intval($aset['id']):0;
$aset['listname']=$aset['listname']?$aset['listname']:"list";
$wheresql=" WHERE  id=".$aset['id']."";
$val=$db->getone("select * from ".table('manager_resume').$wheresql." LIMIT  1");
if ($val)
{
	if ($val['display_name']=="2")
	{
		$val['fullname']="N".str_pad($val['id'],7,"0",STR_PAD_LEFT);
		$val['fullname_']=$val['fullname'];		
	}
	elseif($val['display_name']=="3")
	{
		$val['fullname']=cut_str($val['fullname'],1,0,"**");
		$val['fullname_']=$val['fullname'];	
	}
	else
	{
		$val['fullname_']=$val['fullname'];
		$val['fullname']=$val['fullname'];
	}
	$val['education_list']=get_this_education($val['uid'],$val['id']);
	$val['work_list']=get_this_work($val['uid'],$val['id']);
	$val['project_list']=get_this_project($val['uid'],$val['id']);
	$val['age']=date("Y")-$val['birthdate'];
	if ($val['photo']=="1")
	{
	$val['photosrc']=$_CFG['manager_resume_photo_dir_thumb'].$val['photo_img'];
	}
	else
	{
	$val['photosrc']=$_CFG['resume_photo_dir_thumb']."no_photo.gif";
	}
	$val['tagcn']=preg_replace("/\d+/", '',$val['tag']);
	$val['tagcn']=preg_replace('/\,/','',$val['tagcn']);
	$val['tagcn']=preg_replace('/\|/','&nbsp;&nbsp;&nbsp;',$val['tagcn']);
	$val['languagecn']=preg_replace("/\d+/", '',$val['language']);
	$val['languagecn']=preg_replace('/\,/','',$val['languagecn']);
	$val['languagecn']=preg_replace('/\|/','&nbsp;&nbsp;&nbsp;',$val['languagecn']);
	
}
else
{
	header("HTTP/1.1 404 Not Found"); 
	$smarty->display("404.htm");
	exit();
}
$smarty->assign($aset['listname'],$val);
}
function get_this_education($uid,$pid)
{
	global $db;
	$sql = "SELECT * FROM ".table('manager_resume_education')." WHERE uid='".intval($uid)."' AND pid='".intval($pid)."' ";
	return $db->getall($sql);
}
function get_this_work($uid,$pid)
{
	global $db;
	$sql = "select * from ".table('manager_resume_work')." where uid=".intval($uid)." AND pid='".$pid."' " ;
	return $db->getall($sql);
}
function get_this_project($uid,$pid)
{
	global $db;
	$sql = "select * from ".table('manager_resume_project')." where uid='".intval($uid)."' AND pid='".intval($pid)."'";
	return $db->getall($sql);
}
?>