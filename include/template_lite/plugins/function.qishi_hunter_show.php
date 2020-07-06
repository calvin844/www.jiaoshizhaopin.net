<?php
function tpl_function_qishi_hunter_show($params, &$smarty)
{
	global $db,$timestamp,$_CFG;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
	$a=explode(':',$str);
		switch ($a[0])
		{
		case "猎头ID":
			$aset['id'] = $a[1];
			break;
		case "会员UID":
			$aset['uid'] = $a[1];
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
		case "猎头名长度":
			$aset['hunterlen'] = $a[1];
			break;
		case "公司名长度":
			$aset['companynamelen'] = $a[1];
			break;
		case "擅长行业长度":
			$aset['goodtradelen'] = $a[1];
			break;
		case "擅长职能长度":
			$aset['goodcategorylen'] = $a[1];
			break;
		}
	}
	$aset=array_map("get_smarty_request",$aset);
	$aset['id']=$aset['id']?intval($aset['id']):0;
	$aset['brieflylen']=isset($aset['brieflylen'])?intval($aset['brieflylen']):0;
	$aset['listname']=$aset['listname']?$aset['listname']:"list";
	$aset['hunterlen']=isset($aset['hunterlen'])?intval($aset['hunterlen']):8;
	$aset['companynamelen']=isset($aset['companynamelen'])?intval($aset['companynamelen']):15;
	$aset['goodtradelen']=isset($aset['goodtradelen'])?intval($aset['goodtradelen']):13;
	$aset['goodcategorylen']=isset($aset['goodcategorylen'])?intval($aset['goodcategorylen']):13;
	if($aset['id'] && $aset['id']>0){
		$wheresql=" WHERE id={$aset['id']} ";
	}elseif($aset['uid'] && $aset['uid']>0){
		$wheresql=" WHERE uid={$aset['uid']} ";
	}
	
	$sql = "select * from ".table('hunter_profile').$wheresql." LIMIT 1";
	$row=$db->getone($sql);
	if (empty($row))
	{
		header("HTTP/1.1 404 Not Found"); 
		$smarty->display("404.htm");
		exit();
	}
	else
	{
		$row['huntername']=cut_str($row['huntername'],$aset['hunterlen'],0,$aset['dot']);
		$row['companyname']=cut_str($row['companyname'],$aset['companynamelen'],0,$aset['dot']);
		
		if ($aset['brieflylen']>0)
		{
			$row['contents']=cut_str(strip_tags($row['contents']),$aset['brieflylen'],0,$aset['dot']);
		}
		else
		{
			$row['contents']=strip_tags($row['contents']);
		}
		if ($aset['goodtradelen']>0)
		{
			$row['goodtrade_cn']=cut_str(strip_tags($row['goodtrade_cn']),$aset['goodtradelen'],0,$aset['dot']);
		}
		else
		{
			$row['goodtrade_cn']=strip_tags($row['goodtrade_cn']);
		}
		if ($aset['goodcategorylen']>0)
		{
			$row['goodcategory_cn']=cut_str(strip_tags($row['goodcategory_cn']),$aset['goodcategorylen'],0,$aset['dot']);
		}
		else
		{
			$row['goodcategory_cn']=strip_tags($row['goodcategory_cn']);
		}
				$row['companyname_']=$row['companyname'];
		$row['huntername_']=$row['huntername'];
		
		$row['years']=date('Y')+1-$row['worktime_start'];
		if ($row['photo_img'])
		{
		$row['photosrc']=$_CFG['hunter_photo_dir'].$row['photo_img'];
		}
		else
		{
		$row['photosrc']=$_CFG['hunter_photo_dir']."no_photo.gif";
		}
		$cooperate_company_arr = explode("|", $row["cooperate_company"]);
		foreach ($cooperate_company_arr as $key => $value) {
			$row['cooperate'][]['name'] = $value;
		}
		$timenow=time();
		if($_CFG['operation_mode']=='1'){
			$wheresql1.="  AND audit=1  AND display=1 ";
		}elseif($_CFG['operation_train_mode']=='2'){
			$wheresql1.="  AND audit=1 AND display=1 AND setmeal_id>0 AND (setmeal_deadline>{$timenow} OR setmeal_deadline=0)";
		}
		$row['countjobs'] = $db->get_total("select count(*) as num from ".table('hunter_jobs')." where uid=".$row['uid']." ".$wheresql1);
	}
$smarty->assign($aset['listname'],$row);
}
function GetTainProfile($id)
{
	global $db;
	$sql = "select * from ".table('train_profile')." where id=".intval($id)." LIMIT 1 ";
	return $db->getone($sql);
}
?>