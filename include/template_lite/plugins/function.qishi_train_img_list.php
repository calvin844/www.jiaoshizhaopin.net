<?php
function tpl_function_qishi_train_img_list($params, &$smarty)
{
global $db,$_CFG;
$arrset=explode(',',$params['set']);
foreach($arrset as $str)
{
$a=explode(':',$str);
	switch ($a[0])
	{
	case "列表名":
		$aset['listname'] = $a[1];
		break;
	case "机构ID":
		$aset['train_id'] = $a[1];
		break;
	case "显示数目":
		$aset['row'] = $a[1];
		break;	
	case "开始位置":
		$aset['start'] = $a[1];
		break;
	case "排序":
		$aset['displayorder'] = $a[1];
		break;
	case "分页显示":
		$aset['paged'] = $a[1];
		break;
	case "列表页":
		$aset['listpage'] = $a[1];
		break;
	}
}
if (is_array($aset)) $aset=array_map("get_smarty_request",$aset);
$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
$aset['listpage']=isset($aset['listpage'])?$aset['listpage']:"QS_trainimg";
$aset['row']=isset($aset['row'])?intval($aset['row']):30;
$aset['start']=isset($aset['start'])?intval($aset['start']):0;
if ($aset['displayorder'])
{
	if (strpos($aset['displayorder'],'>'))
	{
	$arr=explode('>',$aset['displayorder']);
	$arr[0]=preg_match('/addtime|id/',$arr[0])?$arr[0]:"";
	$arr[1]=preg_match('/asc|desc/',$arr[1])?$arr[1]:"";
		if ($arr[0] && $arr[1])
		{
		$orderbysql=" ORDER BY `".$arr[0]."` ".$arr[1];
		}
	}
}
else
{
$orderbysql=" ORDER BY `id` DESC";
}
$wheresql=" WHERE train_id='".intval($aset['train_id'])."' AND audit=1";
if (isset($aset['paged']))
{
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$total_sql="SELECT COUNT(*) AS num FROM ".table('train_img').$wheresql;
	$total_count=$db->get_total($total_sql);
	$pagelist = new page(array('total'=>$total_count, 'perpage'=>$aset['row'],'alias'=>$aset['listpage'],'getarray'=>$_GET));
	$currenpage=$pagelist->nowindex;
	$aset['start']=($currenpage-1)*$aset['row'];
		if ($total_count>$aset['row'])
		{
		$smarty->assign('page',$pagelist->show(3));
		}
		$smarty->assign('total',$total_count);
}
$limit=" LIMIT ".abs($aset['start']).','.$aset['row'];
$result = $db->query("SELECT * FROM ".table('train_img')." ".$wheresql.$orderbysql.$limit);
$total_sql="SELECT COUNT(*) AS num FROM ".table('train_img').$wheresql;
$total_count=$db->get_total($total_sql);
$group_num = $total_count%3==0?($total_count/3):(intval($total_count/3)+1);
$list= array();
$i=1;
$count = 0;
while($row = $db->fetch_array($result))
{
	if(($count%3)==0 && intval($count/3)>=1){
		$i++;
	}
	$list[$i]['group'][] = $row;
	$count++;
}
$smarty->assign($aset['listname'],$list);
}
?>