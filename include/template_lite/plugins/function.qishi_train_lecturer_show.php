<?php
function tpl_function_qishi_train_lecturer_show($params, &$smarty)
{
	global $db,$timestamp,$_CFG;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
	$a=explode(':',$str);
		switch ($a[0])
		{
		case "讲师ID":
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
	$sql = "select * from ".table('train_teachers').$wheresql." LIMIT 1";
	$val=$db->getone($sql);
	if (empty($val))
	{
		header("HTTP/1.1 404 Not Found"); 
		$smarty->display("404.htm");
		exit();
	}
	else
	{
			$profile=GetTainProfile($val['train_id']);
			$val['train']=$profile;
			if ($aset['brieflylen']>0)
			{
				$val['briefly']=cut_str(strip_tags($val['contents']),$aset['brieflylen'],0,$val['dot']);
			}
			else
			{
				$val['briefly']=strip_tags($val['contents']);
			}
			$val['age']=date('Y')+1-$val['birthdate'];
			$val['refreshtime_cn']=daterange(time(),$val['refreshtime'],'Y-m-d',"#FF3300");
			$val['train_url']=url_rewrite('QS_train_agencyshow',array('id'=>$val['train_id']));
			$val['teacher_url']=url_rewrite('QS_train_lecturer',array('id'=>$val['id']));
			if ($val['photo']=="1")
			{
			$val['photosrc']=$_CFG['teacher_photo_dir'].$val['photo_img'];
			}
			else
			{
			$val['photosrc']=$_CFG['teacher_photo_dir']."no_photo.gif";
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