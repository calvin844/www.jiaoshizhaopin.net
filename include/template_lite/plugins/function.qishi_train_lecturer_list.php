<?php
/*********************************************
*骑士培训讲师列表
* *******************************************/
function tpl_function_qishi_train_lecturer_list($params, &$smarty)
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
	case "讲师名长度":
		$aset['teacherlen'] = $a[1];
		break;
	case "机构名长度":
		$aset['trainnamelen'] = $a[1];
		break;
	case "擅长专业长度":
		$aset['specialitylen'] = $a[1];
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
	case "讲师学历":
		$aset['education'] = $a[1];
		break;
	case "推荐":
		$aset['recommend'] = $a[1];
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
$aset=array_map("get_smarty_request",$aset);
$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
$aset['listpage']=isset($aset['listpage'])?$aset['listpage']:"QS_courselist";
$aset['row']=intval($aset['row'])>0?intval($aset['row']):10;
if ($aset['row']>30)$aset['row']=30;
$aset['start']=isset($aset['start'])?intval($aset['start']):0;
$aset['teacherlen']=isset($aset['teacherlen'])?intval($aset['teacherlen']):8;
$aset['trainnamelen']=isset($aset['trainnamelen'])?intval($aset['trainnamelen']):15;
$aset['specialitylen']=isset($aset['specialitylen'])?intval($aset['specialitylen']):13;
$aset['education']=isset($aset['education'])?intval($aset['education']):0;
$aset['brieflylen']=isset($aset['brieflylen'])?intval($aset['brieflylen']):0;
$aset['recommend']=isset($aset['recommend'])?intval($aset['recommend']):'';
$aset['trainshow']=isset($aset['trainshow'])?$aset['trainshow']:'QS_train_agencyshow';
$aset['teachershow']=isset($aset['teachershow'])?$aset['teachershow']:'QS_train_lecturershow';

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
if (isset($aset['recommend'])  && $aset['recommend']<>'')
{
	$wheresql.=" AND recommend=".intval($aset['recommend']);
}

if (isset($aset['education']) && $aset['education']<>'')
{
	$wheresql.=" AND education=".intval($aset['education'])." ";
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

if (!empty($wheresql))
{
$wheresql=" WHERE ".ltrim(ltrim($wheresql),'AND');
}
if (isset($aset['page']))
{
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$total_sql="SELECT COUNT(*) AS num FROM ".table('train_teachers')." {$wheresql}";
	$total_count=$db->get_total($total_sql);	
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

	$result = $db->query("SELECT * FROM ".table('train_teachers').$wheresql.$orderbysql.$limit);	
 	while($row = $db->fetch_array($result))
	{
		$row['refreshtime_cn']=daterange(time(),$row['refreshtime'],'Y-m-d',"#FF3300");
		$row['teachername']=cut_str($row['teachername'],$aset['teacherlen'],0,$aset['dot']);
		$row['trainname']=cut_str($row['trainname'],$aset['trainnamelen'],0,$aset['dot']);
		
			if ($aset['brieflylen']>0)
			{
				$row['briefly']=cut_str(strip_tags($row['contents']),$aset['brieflylen'],0,$aset['dot']);
				$row['achievements']=cut_str(strip_tags($row['achievements']),$aset['brieflylen'],0,$aset['dot']);
			}
			else
			{
				$row['briefly']=strip_tags($row['contents']);
				$row['achievements']=strip_tags($row['achievements']);
			}
			
			if ($aset['specialitylen']>0)
			{
				$row['speciality']=cut_str(strip_tags($row['speciality']),$aset['specialitylen'],0,$aset['dot']);
			}
			else
			{
				$row['speciality']=strip_tags($row['speciality']);
			}
			
		$row['briefly_']=strip_tags($row['contents']);
		$row['achievements_']=strip_tags($row['achievements']);
		$row['speciality_']=strip_tags($row['speciality']);
		$row['trainname_']=$row['trainname'];
		$row['train_url']=url_rewrite($aset['trainshow'],array('id'=>$row['train_id']));
		$row['teacher_url']=url_rewrite($aset['teachershow'],array('id'=>$row['id']));
		$row['age']=date('Y')+1-$row['birthdate'];
			if ($row['photo']=="1")
			{
			$row['photosrc']=$_CFG['teacher_photo_dir'].$row['photo_img'];
			}
			else
			{
			$row['photosrc']=$_CFG['teacher_photo_dir']."no_photo.gif";
			}
		
		$list[] = $row;
		unset($row);
	}
	$smarty->assign($aset['listname'],$list);
}
?>