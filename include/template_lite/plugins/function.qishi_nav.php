<?php
/*********************************************
*������Ŀ
* *******************************************/
function tpl_function_qishi_nav($params, &$smarty)
{
	global $db,$_NAV,$_CFG;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
	$a=explode(':',$str);
		switch ($a[0])
		{
		case "��������":
			$aset['alias'] = $a[1];
			break;
		case "�б���":
			$aset['listname'] = $a[1];
			break;
		}
	}
	if($_CFG['subsite_id']>0){
		foreach ($_NAV[$aset['alias']] as $key => $value) {
			if($value['pagealias']=="QS_login"){
				$_NAV[$aset['alias']][$key]['url'] = $_CFG['main_domain'].ltrim($_NAV[$aset['alias']][$key]['url'],$_CFG['site_dir']);
			}else{
				$_NAV[$aset['alias']][$key]['url'] = $_CFG['site_domain']."/".ltrim($_NAV[$aset['alias']][$key]['url'],$_CFG['site_dir']);
			}
			
		}
	}
	$aset['listname']=$aset['listname']?$aset['listname']:"list";
	$smarty->assign($aset['listname'],$_NAV[$aset['alias']]);
}
?>