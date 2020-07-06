<?php
function tpl_function_qishi_curriculum_show($params, &$smarty)
{
	global $db,$timestamp,$_CFG;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
	$a=explode(':',$str);
		switch ($a[0])
		{
		case "课程ID":
			$aset['id'] = $a[1];
			break;
		case "列表名":
			$aset['listname'] = $a[1];
			break;
		case "描述长度":
			$aset['brieflylen'] = $a[1];
			break;
		case "填补字符":
			$aset['dot'] = $a[1];
			break;
		}
	}
	$aset=array_map("get_smarty_request",$aset);
	$aset['id']=$aset['id']?intval($aset['id']):0;
	$aset['brieflylen']=isset($aset['brieflylen'])?intval($aset['brieflylen']):0;
	$aset['listname']=$aset['listname']?$aset['listname']:"list";
	$wheresql=" WHERE id={$aset['id']} ";
	$sql = "select * from ".table('course').$wheresql." LIMIT 1";
	$val=$db->getone($sql);
	if (empty($val) || (intval($_GET['subsite_id']) != $val['subsite_id']))
	{
		header("HTTP/1.1 404 Not Found"); 
		$smarty->display("404.htm");
		exit();
	}
	else
	{
			if ($val['setmeal_deadline']<time() && $val['setmeal_deadline']<>"0" && $val['add_mode']=="2")
			{
			$val['deadline']=$val['setmeal_deadline'];
			}
			$val['course_url']=url_rewrite('QS_courseshow',array('id'=>$val['id']));
			$profile=GetTainProfile($val['train_id']);
			$val['train']=$profile;
			$val['expire']=sub_day($val['deadline'],time());	
			$wheresql=" WHERE train_uid='{$row['uid']}' AND course_id= '{$row['id']}'";
		    $val['countapply']=$db->get_total("SELECT COUNT(*) AS num FROM ".table('personal_course_apply')." WHERE course_id= '{$val['id']}'");
			if ($aset['brieflylen']>0)
			{
				$val['briefly']=cut_str(strip_tags($val['contents']),$aset['brieflylen'],0,$val['dot']);
			}
			else
			{
				$val['briefly']=strip_tags($val['contents']);
			}
			$val['refreshtime_cn']=daterange(time(),$val['refreshtime'],'Y-m-d',"#FF3300");
			$val['train_url']=url_rewrite('QS_train_agencyshow',array('id'=>$val['train_id']));
			$val['teacher_url']=url_rewrite('QS_train_lecturershow',array('id'=>$val['teacher_id']));
			if ($val['train']['logo'])
			{
			$val['train']['logo']=$_CFG['site_dir']."data/train_logo/".$val['train']['logo'];
			}
			else
			{
			$val['train']['logo']=$_CFG['site_dir']."data/train_logo/no_logo.gif";
			}
	}
$smarty->assign($aset['listname'],$val);
}
function GetTainProfile($id)
{
	global $db;
	$sql = "select * from ".table('train_profile')." where id=".intval($id)." LIMIT 1 ";
	return $db->getone($sql);
}
?>