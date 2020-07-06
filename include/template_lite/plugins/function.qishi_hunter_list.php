<?php
/*********************************************
*骑士猎头顾问列表
* *******************************************/
function tpl_function_qishi_hunter_list($params, &$smarty)
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
	case "显示数目":
		$aset['row'] = $a[1];
		break;
	case "开始位置":
		$aset['start'] = $a[1];
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
	case "描述长度":
		$aset['brieflylen'] = $a[1];
		break;
	case "填补字符":
		$aset['dot'] = $a[1];
		break;
	case "地区分类":
		$aset['citycategory'] = $a[1];
		break;
	case "地区大类":
		$aset['district'] = $a[1];
		break;
	case "地区小类":
		$aset['sdistrict'] = $a[1];
		break;
	case "猎头头衔":
		$aset['rank'] = $a[1];
		break;
	case "日期范围":
		$aset['settr'] = $a[1];
		break;
	case "排序":
		$aset['displayorder'] = $a[1];
		break;
	case "分页显示":
		$aset['page'] = $a[1];
		break;
	case "会员UID":
		$aset['uid'] = $a[1];
		break;
	case "猎头页面":
		$aset['huntershow'] = $a[1];
		break;
	case "列表页":
		$aset['listpage'] = $a[1];
		break;
	case "关键字":
		$aset['key'] = $a[1];
		break;
	}
}
$aset=array_map("get_smarty_request",$aset);
$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
$aset['listpage']=isset($aset['listpage'])?$aset['listpage']:"QS_hunterlist";
$aset['row']=intval($aset['row'])>0?intval($aset['row']):10;
if ($aset['row']>30)$aset['row']=30;
$aset['start']=isset($aset['start'])?intval($aset['start']):0;
$aset['hunterlen']=isset($aset['hunterlen'])?intval($aset['hunterlen']):8;
$aset['companynamelen']=isset($aset['companynamelen'])?intval($aset['companynamelen']):15;
$aset['goodtradelen']=isset($aset['goodtradelen'])?intval($aset['goodtradelen']):13;
$aset['goodcategorylen']=isset($aset['goodcategorylen'])?intval($aset['goodcategorylen']):13;
$aset['rank']=isset($aset['rank'])?intval($aset['rank']):0;
$aset['brieflylen']=isset($aset['brieflylen'])?intval($aset['brieflylen']):0;

$orderbysql=" ORDER BY refreshtime desc ";

if (isset($aset['displayorder']))
{
		$arr=explode('>',$aset['displayorder']);
		$arr[1]=preg_match('/asc|desc/',$arr[1])?$arr[1]:"desc";
		if ($arr[0]=="rtime")
		{
		$orderbysql=" ORDER BY refreshtime {$arr[1]}";
		}
		elseif ($arr[0]=="atime")
		{
		$orderbysql=" ORDER BY addtime {$arr[1]}";
		}
		elseif ($arr[0]=="hot")
		{
		$orderbysql=" ORDER BY click {$arr[1]}";
		}
		else
		{
		$orderbysql=" ORDER BY refreshtime {$arr[1]}";
		}
}
$wheresql.=" AND audit=1 ";
if (isset($aset['settr']) && $aset['settr']<>'')
{
	$settr=intval($aset['settr']);
	if ($settr>0)
	{
	$settr_val=intval(strtotime("-".$aset['settr']." day"));
	$wheresql.=" AND refreshtime>".$settr_val;
	}
}

if (isset($aset['uid'])  && $aset['uid']<>'')
{
	$wheresql.=" AND uid=".intval($aset['uid']);
}
if (isset($aset['rank']) && $aset['rank']<>'')
{
	$wheresql.=" AND rank=".intval($aset['rank'])." ";
}
if (!empty($aset['citycategory']))
{
		$dsql=$xsql="";
		$arr=explode(",",$aset['citycategory']);
		$arr=array_unique($arr);
		if (count($arr)>10) exit();
		foreach($arr as $sid)
		{
				$cat=explode(".",$sid);
				if (intval($cat[1])===0)
				{
				$dsql.= " OR district =".intval($cat[0]);
				}
				else
				{
				$xsql.= " OR sdistrict =".intval($cat[1]);
				}
				
				
		}
		$wheresql.=" AND  (".ltrim(ltrim($dsql.$xsql),'OR').") ";
}
else
{
	if (isset($aset['district'])  && $aset['district']<>'')
	{
		if (strpos($aset['district'],"-"))
		{
			$or=$orsql="";
			$arr=explode("-",$aset['district']);
			$arr=array_unique($arr);
			if (count($arr)>20) exit();
			$sqlin=implode(",",$arr);
			if (preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin))
			{
				$wheresql.=" AND district IN  ({$sqlin}) ";
			}
		}
		else
		{
			$wheresql.=" AND district =".intval($aset['district']);
		}
	}
	if (isset($aset['sdistrict'])  && $aset['sdistrict']<>'')
	{
		if (strpos($aset['sdistrict'],"-"))
		{
			$or=$orsql="";
			$arr=explode("-",$aset['sdistrict']);
			$arr=array_unique($arr);
			if (count($arr)>10) exit();
			$sqlin=implode(",",$arr);
			if (preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin))
			{
				$wheresql.=" AND sdistrict IN  ({$sqlin}) ";
			}
		}
		else
		{
			$wheresql.=" AND sdistrict =".intval($aset['sdistrict']);
		}
	}	
}
if (isset($aset['key']) && !empty($aset['key']))
{
	$key=trim($aset['key']);
	
	$akey=explode(' ',$key);
	if (count($akey)>1)
	{
	$akey=array_filter($akey);
	$akey=array_slice($akey,0,2);
	$akey=array_map("fulltextpad",$akey);
	$key='+'.implode(' +',$akey);
	$mode=' IN BOOLEAN MODE';
	}
	else
	{
	$key=fulltextpad($key);
	$mode=' ';
	}
	$wheresql.=" AND  MATCH (`key`) AGAINST ('{$key}'{$mode}) ";
}
if (!empty($wheresql))
{
$wheresql=" WHERE ".ltrim(ltrim($wheresql),'AND');
}
if (isset($aset['page']))
{
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$total_sql="SELECT COUNT(*) AS num FROM ".table('hunter_profile')." {$wheresql}";
	$total_count=$db->get_total($total_sql);	
	$page = new page(array('total'=>$total_count, 'perpage'=>$aset['row'],'alias'=>$aset['listpage'],'getarray'=>$_GET));
	$currenpage=$page->nowindex;
	$aset['start']=abs($currenpage-1)*$aset['row'];
	$countpage = $total_count%$aset['row']==0?$total_count/$aset['row']:intval($total_count/$aset['row'])+1;
	$smarty->assign('countpage',$countpage);
	
	$smarty->assign('page',$page->show(3));
	$smarty->assign('pagemin',$page->show(4));
	
	$smarty->assign('currenpage',$currenpage);
	$smarty->assign('total',$total_count);
}
	$limit=" LIMIT {$aset['start']} , {$aset['row']}";
	$list = $id = array();
	$result = $db->query("SELECT * FROM ".table('hunter_profile').$wheresql.$orderbysql.$limit);	
 	while($row = $db->fetch_array($result))
	{
		$row['refreshtime_cn']=daterange(time(),$row['refreshtime'],'Y-m-d',"#FF3300");
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
			
		$row['contents']=strip_tags($row['contents']);
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
		$timenow=time();
		if($_CFG['operation_mode']=='1'){
			$wheresql1.="  AND audit=1  AND display=1 ";
		}elseif($_CFG['operation_train_mode']=='2'){
			$wheresql1.="  AND audit=1 AND display=1 AND setmeal_id>0 AND (setmeal_deadline>{$timenow} OR setmeal_deadline=0)";
		}
		$row['countjobs'] = $db->get_total("select count(*) as num from ".table('hunter_jobs')." where uid=".$row['uid']." ".$wheresql1);
		if(intval($_CFG['subsite_id'])==0){
			$jobs = $db->getall("select id,jobs_name,refreshtime from ".table('hunter_jobs')." where uid=".$row['uid']." ".$wheresql1." limit 4");
		}else{
			$wheresql1.=" and subsite_id=".intval($_CFG['subsite_id'])." ";
			$jobs = $db->getall("select id,jobs_name,refreshtime from ".table('hunter_jobs')." where uid=".$row['uid']." ".$wheresql1." limit 4");
		}
		foreach ($jobs as $key => $value) {
			$row['jobslist'][$key]['jobs_name'] = cut_str($value['jobs_name'],10,0,"..");
			$row['jobslist'][$key]['refreshtime'] = date("Y-m-d",$value['refreshtime']);
			if(intval($_CFG['subsite_id'])==0){
				$row['jobslist'][$key]['jobs_url'] = url_rewrite("QS_hunter_jobsshow",array("id"=>$value['id']),true,$value['subsite_id']);
			}else{
				$row['jobslist'][$key]['jobs_url'] = url_rewrite("QS_hunter_jobsshow",array("id"=>$value['id']),true);
			}
		}
		$list[] = $row;
		unset($row);
	}
	$smarty->assign($aset['listname'],$list);
}
?>