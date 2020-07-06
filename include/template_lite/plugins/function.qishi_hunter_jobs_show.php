<?php
function tpl_function_qishi_hunter_jobs_show($params, &$smarty){
	global $db,$timestamp,$_CFG;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
	$a=explode(':',$str);
		switch ($a[0])
		{
		case "职位ID":
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
	$sql = "select * from ".table('hunter_jobs').$wheresql." LIMIT 1";
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
			$val['jobs_url']=url_rewrite('QS_hunter_jobsshow',array('id'=>$val['id']),true,$val['subsite_id']);
			$val['expire']=sub_day($val['deadline'],time());	
			if ($aset['brieflylen']>0)
			{
				$val['briefly']=cut_str(strip_tags($val['contents']),$aset['brieflylen'],0,$val['dot']);
				$val['jobs_qualified']=cut_str(strip_tags($val['jobs_qualified']),$aset['brieflylen'],0,$val['dot']);
			}
			else
			{
				$val['briefly']=strip_tags($val['contents']);
				$val['jobs_qualified']=strip_tags($val['jobs_qualified']);
			}
			$val['refreshtime_cn']=daterange(time(),$val['refreshtime'],'Y-m-d',"#FF3300");
			$val['company_url']=url_rewrite('QS_companyshow',array('id'=>$val['company_id']));
			$val['languagecn']=preg_replace("/\d+/", '',$val['language']);
			$val['languagecn']=preg_replace('/\,/','',$val['languagecn']);
			$val['languagecn']=preg_replace('/\|/','&nbsp;&nbsp;&nbsp;',$val['languagecn']);

			$wage_structure = explode("|", $val['wage_structure']);
			foreach ($wage_structure as $key => $value) {
				$wage = explode(",", $value);
				$val['structure'][$key]['value'] = $wage[1];
			}
			if($val['utype']=='1'){
			$company=GetJobsCompanyProfile($val['uid']);
			$val['company_id']=$company['id'];
			}
	}
$smarty->assign($aset['listname'],$val);
}
function GetJobsCompanyProfile($uid)
{
	global $db;
	$sql = "select id from ".table('company_profile')." where uid=".intval($uid)." LIMIT 1 ";
	return $db->getone($sql);
}

?>
