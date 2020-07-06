<?php
/*********************************************
*导航栏目
* *******************************************/
function tpl_function_qishi_allsite($params, &$smarty)
{
	global $db,$_CFG;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
	$a=explode(':',$str);
		switch ($a[0])
		{
		case "调用名称":
			$aset['alias'] = $a[1];
			break;
		case "列表名":
			$aset['listname'] = $a[1];
			break;
		}
	}
	$subsite = $db->getall("select * from ".table('subsite')." where s_effective=1 order by s_index asc");
	foreach ($subsite as $key => $value) {
		$list[$value['s_index']]['index'] = strtoupper($value['s_index']);
		$list[$value['s_index']]['city'][$key]['name'] = $value['s_districtname'];
		if($value['s_domain']==""){
			$list[$value['s_index']]['city'][$key]['url'] = $_CFG['main_domain'].$value['s_dir'];
		}else{
			$list[$value['s_index']]['city'][$key]['url'] = "http://".$value['s_domain'];
		}
		
	}
	$aset['listname']=$aset['listname']?$aset['listname']:"list";
	$smarty->assign($aset['listname'],$list);
}
?>