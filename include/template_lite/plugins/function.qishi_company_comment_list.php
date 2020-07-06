<?php
function tpl_function_qishi_company_comment_list($params, &$smarty)
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
	case "公司ID":
		$aset['company_id'] = $a[1];
		break;
	case "显示数目":
		$aset['row'] = $a[1];
		break;
	case "内容长度":
		$aset['contentlelen'] = $a[1];
		break;		
	case "开始位置":
		$aset['start'] = $a[1];
		break;
	case "填补字符":
		$aset['dot'] = $a[1];
		break;
	case "排序":
		$aset['displayorder'] = $a[1];
		break;
	case "分页显示":
		$aset['paged'] = $a[1];
		break;
	}
}
if (is_array($aset)) $aset=array_map("get_smarty_request",$aset);
$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
$aset['row']=isset($aset['row'])?intval($aset['row']):30;
$aset['start']=isset($aset['start'])?intval($aset['start']):0;
$aset['contentlelen']=isset($aset['contentlelen'])?intval($aset['contentlelen']):15;
if ($aset['displayorder'])
{
	if (strpos($aset['displayorder'],'>'))
	{
	$arr=explode('>',$aset['displayorder']);
	$arr[0]=preg_match('/addtime|id/',$arr[0])?$arr[0]:"";
	$arr[1]=preg_match('/asc|desc/',$arr[1])?$arr[1]:"";
		if ($arr[0] && $arr[1])
		{
		$orderbysql=" ORDER BY c.`".$arr[0]."` ".$arr[1];
		}
	}
}
else
{
$orderbysql=" ORDER BY c.`id` DESC";
}
$wheresql=" WHERE c.company_id='".intval($aset['company_id'])."'";
if (isset($aset['paged']))
{
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$total_sql="SELECT COUNT(*) AS num FROM ".table('comment')." AS c ".$wheresql;
	$total_count=$db->get_total($total_sql);
	$pagelist = new page(array('total'=>$total_count, 'perpage'=>$aset['row'],'alias'=>'QS_companycomment','getarray'=>$_GET));
	$currenpage=$pagelist->nowindex;
	$aset['start']=($currenpage-1)*$aset['row'];
		if ($total_count>$aset['row'])
		{
		$smarty->assign('page',$pagelist->show(3));
		}
		else
		{
		$smarty->assign('page','');
		}
		$smarty->assign('total',$total_count);
}
$joinsql=" LEFT JOIN  ".table('members')." AS m ON c.uid=m.uid ";
$limit=" LIMIT ".abs($aset['start']).','.$aset['row'];
$result = $db->query("SELECT c.*,m.username FROM ".table('comment')." AS c ".$joinsql.$wheresql.$orderbysql.$limit);
$list= array();
while($row = $db->fetch_array($result))
{
	$row['content_']=str_replace('&nbsp;','',$row['content']);
	$row['content_']=strip_tags($row['content_']);
		if ($aset['contentlelen']>0)
		{
		$row['content_']=cut_str($row['content_'],$aset['contentlelen'],0,$aset['dot']);
		}
	$list[] = $row;
}
$smarty->assign($aset['listname'],$list);
}
?>