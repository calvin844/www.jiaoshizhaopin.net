<?php
/*********************************************
*骑士培训课程列表
* *******************************************/
function tpl_function_qishi_curriculum_list($params, &$smarty)
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
	case "课程名长度":
		$aset['courselen'] = $a[1];
		break;
	case "机构名长度":
		$aset['trainnamelen'] = $a[1];
		break;
	case "描述长度":
		$aset['brieflylen'] = $a[1];
		break;
	case "填补字符":
		$aset['dot'] = $a[1];
		break;
	case "课程类别":
		$aset['coursecategory'] = $a[1];
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
	case "上课班制":
		$aset['classtype'] = $a[1];
		break;
	case "推荐":
		$aset['recommend'] = $a[1];
		break;
	case "关键字":
		$aset['key'] = $a[1];
		break;
	case "关键字类型":
		$aset['keytype'] = $a[1];
		break;
	case "日期范围":
		$aset['refre'] = $a[1];
		break;
	case "开课时间":
		$aset['starttime'] = $a[1];
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
	case "机构页面":
		$aset['trainshow'] = $a[1];
		break;
	case "课程页面":
		$aset['courseshow'] = $a[1];
		break;
	case "讲师页面":
		$aset['teachershow'] = $a[1];
		break;
	case "列表页":
		$aset['listpage'] = $a[1];
		break;
	}
}
	$timenow=time();
$aset=array_map("get_smarty_request",$aset);
$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
$aset['listpage']=isset($aset['listpage'])?$aset['listpage']:"QS_courselist";
$aset['row']=intval($aset['row'])>0?intval($aset['row']):10;
if ($aset['row']>30)$aset['row']=30;
$aset['start']=isset($aset['start'])?intval($aset['start']):0;
$aset['courselen']=isset($aset['courselen'])?intval($aset['courselen']):8;
$aset['trainnamelen']=isset($aset['trainnamelen'])?intval($aset['trainnamelen']):15;
$aset['brieflylen']=isset($aset['brieflylen'])?intval($aset['brieflylen']):0;
$aset['recommend']=isset($aset['recommend'])?intval($aset['recommend']):'';
$aset['trainshow']=isset($aset['trainshow'])?$aset['trainshow']:'QS_train_agencyshow';
$aset['courseshow']=isset($aset['courseshow'])?$aset['courseshow']:'QS_train_curriculumshow';
$aset['teachershow']=isset($aset['teachershow'])?$aset['teachershow']:'QS_train_lecturershow';
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
		elseif ($arr[0]=="stime")
		{
		$orderbysql=" ORDER BY starttime {$arr[1]}";
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

if ($_CFG['subsite']=="1" && $_CFG['subsite_filter_course']=="1" && intval($_CFG['subsite_id'])>0)
{
	$wheresql.=" AND subsite_id=".intval($_CFG['subsite_id'])." ";
}
if (isset($aset['refre']) && $aset['refre']<>'')
{
	$settr=intval($aset['refre']);
	if ($settr>0)
	{
	$settr_val=intval(strtotime("-".$aset['refre']." day"));
	$wheresql.=" AND refreshtime>".$settr_val;
	}
}
if (isset($aset['starttime']) && $aset['starttime']<>'')
{
	$trart=intval($aset['starttime']);
	if ($trart>0)
	{
	$trart_val=intval(strtotime("+".$aset['starttime']." day"));
	$wheresql.=" AND starttime>{$timenow} AND starttime<".$trart_val;
	}
}

if (isset($aset['uid'])  && $aset['uid']<>'')
{
	$wheresql.=" AND uid=".intval($aset['uid']);
}
if (isset($aset['recommend'])  && $aset['recommend']<>'')
{
	$wheresql.=" AND recommend=".intval($aset['recommend']);
}

if (isset($aset['classtype']) && $aset['classtype']<>'')
{
	$wheresql.=" AND classtype=".intval($aset['classtype'])." ";
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
//不支持多个类型搜索
if (!empty($aset['coursecategory']) && $aset['coursecategory']<>'')
{
			$wheresql.=" AND category =".intval($aset['coursecategory']);
}
if (isset($aset['key']) && !empty($aset['key']))
{
	if ($_CFG['courseearch_purview']=='2')
	{
		if ($_SESSION['username']=='')
		{
		header("Location: ".url_rewrite('QS_login')."?url=".urlencode($_SERVER["REQUEST_URI"]));
		}
	}
	$key=trim($aset['key']);
	if ($_CFG['courseearch_type']=='1')
	{
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
	else
	{
			$wheresql.=" AND likekey LIKE '%{$key}%' ";
	}
	if ($_CFG['courseearch_sort']=='1')
	{
	$orderbysql="";
	}
	else
	{
	$orderbysql=" ORDER BY refreshtime DESC ";
	}	
}
$wheresql.=$_CFG['outdated_course']=='1'?" AND deadline > {$timenow} ":' ';
if($_CFG['operation_train_mode']=='1'){
	$wheresql.="  AND audit=1  AND display=1 AND add_mode=1 ";
}elseif($_CFG['operation_train_mode']=='2'){
	$wheresql.="  AND audit=1  AND display=1 AND add_mode=2 AND setmeal_id>0 AND (setmeal_deadline>{$timenow} OR setmeal_deadline=0)";
}

if (!empty($wheresql))
{
$wheresql=" WHERE ".ltrim(ltrim($wheresql),'AND');
}
if (isset($aset['page']))
{
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$total_sql="SELECT COUNT(*) AS num FROM ".table('course')." {$wheresql}";
	//echo $total_sql;
	//echo "<br>";
	$total_count=$db->get_total($total_sql);	
	if ($_CFG['course_list_max']>0)
	{
		$total_count>intval($_CFG['course_list_max']) && $total_count=intval($_CFG['course_list_max']);
	}
	$page = new page(array('total'=>$total_count, 'perpage'=>$aset['row'],'alias'=>$aset['listpage'],'getarray'=>$_GET));
	$currenpage=$page->nowindex;
	$aset['start']=abs($currenpage-1)*$aset['row'];
	if ($total_count>$aset['row'])
	{
	$smarty->assign('page',$page->show(3));
	$smarty->assign('pagemin',$page->show(4));
	}
	$smarty->assign('total',$total_count);
}
	$limit=" LIMIT {$aset['start']} , {$aset['row']}";
	$list = $id = array();
	$result = $db->query("SELECT * FROM ".table('course').$wheresql.$orderbysql.$limit);
	while($row = $db->fetch_array($result))
	{
		$row['course_name_']=$row['course_name'];
		$row['refreshtime_cn']=daterange(time(),$row['refreshtime'],'Y-m-d',"#FF3300");
		$row['starttime_cn']=date("Y-m-d",$row['starttime']);
		$row['course_name']=cut_str($row['course_name'],$aset['courselen'],0,$aset['dot']);
			if ($aset['brieflylen']>0)
			{
				$row['briefly']=cut_str(strip_tags($row['contents']),$aset['brieflylen'],0,$aset['dot']);
			}
			else
			{
				$row['briefly']=strip_tags($row['contents']);
			}
		$row['briefly_']=strip_tags($row['contents']);
		$row['trainname_']=$row['trainname'];
		$row['trainname']=cut_str($row['trainname'],$aset['trainnamelen'],0,$aset['dot']);
		$row['course_url']=url_rewrite($aset['courseshow'],array('id'=>$row['id']));
		$row['train_url']=url_rewrite($aset['trainshow'],array('id'=>$row['train_id']));
		$row['teacher_url']=url_rewrite($aset['teachershow'],array('id'=>$row['teacher_id']));
		$list[] = $row;
	}
	$smarty->assign($aset['listname'],$list);
}
?>