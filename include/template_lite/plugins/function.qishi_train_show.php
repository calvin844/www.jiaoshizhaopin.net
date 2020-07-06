<?php
function tpl_function_qishi_train_show($params, &$smarty)
{ 
	global $db,$_CFG;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
	$a=explode(':',$str);
		switch ($a[0])
		{
		case "机构ID":
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
		$wheresql.=" AND  user_status=1 ";
		$sql = "select * from ".table('train_profile')." WHERE  id='{$aset['id']}' {$wheresql} LIMIT  1";
		$profile=$db->getone($sql);
		if (empty($profile))
		{
			header("HTTP/1.1 404 Not Found"); 
			$smarty->display("404.htm");
			exit();
		}
		else
		{
		$profile['train_url']=url_rewrite('QS_train_agencyshow',array('id'=>$profile['id']));
		$profile['train_profile']=$profile['contents'];
		$profile['description']=cut_str(strip_tags($profile['contents']),50,0,"...");
		if($profile['website']){
			if(strstr($profile['website'],"http://")===false){
				$profile['website'] = "http://".$profile['website'];
			}
		}
			if ($profile['logo'])
			{
			$profile['logo']=$_CFG['site_dir']."data/train_logo/".$profile['logo'];
			}
			else
			{
			$profile['logo']=$_CFG['site_dir']."data/train_logo/no_logo.gif";
			}
		}
	$smarty->assign($aset['listname'],$profile);
}
?>