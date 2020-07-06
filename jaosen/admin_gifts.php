<?php
 /*
 * 74cms 礼品卡
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI',true);
require_once(dirname(__FILE__).'/../data/config.php');
require_once(dirname(__FILE__).'/include/admin_common.inc.php');
require_once(ADMIN_ROOT_PATH.'include/admin_gifts_fun.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'list';
$smarty->assign('act',$act);
check_permissions($_SESSION['admin_purview'],"gifts");
$smarty->assign('pageheader',"礼品卡");
if($act == 'list')
{
	get_token();
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$oederbysql=" order BY g.id  DESC";
	$key=isset($_GET['key'])?trim($_GET['key']):"";
	$key_type=isset($_GET['key_type'])?intval($_GET['key_type']):"";
	if ($key && $key_type>0)
	{
		
		if     ($key_type===1)$wheresql=" WHERE g.account like '%{$key}%'";
		$oederbysql="";
	}
	else
	{
		$usettime=isset($_GET['usettime'])?intval($_GET['usettime']):"";
		if ($usettime===0)
		{
		$wheresql=empty($wheresql)?" WHERE g.usettime= 0":$wheresql." AND g.usettime=0 ";
		}
		if ($usettime===1)
		{
		$wheresql=empty($wheresql)?" WHERE g.usettime> 0":$wheresql." AND g.usettime>0 ";
		$oederbysql=" order BY g.usettime  DESC";
		}
		
		$settr=intval($_GET['settr']);
		if ($settr>0)
		{
			$wheresql.=empty($wheresql)?" WHERE ":" AND  ";
			$days=intval($settr);
			$settr=strtotime("-{$days} day");
			$wheresql.=" g.addtime> {$settr} ";	
		}		
		if ($_GET['t_effective']<>"")
		{
		$t_effective=intval($_GET['t_effective']);
		$wheresql=empty($wheresql)?" WHERE t.t_effective= ".$t_effective:$wheresql." AND t.t_effective= ".$t_effective;
		}
		$t_id=isset($_GET['t_id'])?intval($_GET['t_id']):"";
		if ($t_id>0)
		{
		$wheresql=empty($wheresql)?" WHERE t.t_id= ".$t_id:$wheresql." AND t.t_id= ".$t_id;
		}
	}
	$joinsql=" LEFT JOIN  ".table('gifts_type')." AS t ON g.t_id=t.t_id ";
	$total_sql="SELECT COUNT(*) AS num FROM ".table('gifts')." AS g ".$joinsql.$wheresql;
	$total=$db->get_total($total_sql);
	$page = new page(array('total'=>$total, 'perpage'=>$perpage));
	$currenpage=$page->nowindex;
	$offset=($currenpage-1)*$perpage;
	$list = get_gifts($offset, $perpage,$joinsql.$wheresql.$oederbysql);
	$smarty->assign('category',get_gifts_category());
	$smarty->assign('list',$list);
	$smarty->assign('total',$total);
	$smarty->assign('page',$page->show(3));
	$smarty->assign('navlabel',"list");
	$smarty->display('gifts/admin_gifts_list.htm');
}
elseif($act == 'generate')
{
	get_token();
	$category=get_gifts_category();
	if(empty($category))
	{
	$link[0]['text'] = "查看分类";
	$link[0]['href'] = '?act=category';
	adminmsg("礼品卡分类不存在，不能生成礼品卡，请增加礼品卡分类",1,$link);
	}
	$smarty->assign('navlabel',"generate");
	$smarty->assign('category',get_gifts_category());
	$smarty->display('gifts/admin_gifts_generate.htm');
}
elseif($act == 'generate_save')
{
	check_token();
	$number=intval($_POST['number']);
	$pwd_pre=trim($_POST['pwd_pre']);
	$t_id=!empty($_POST['t_id'])?intval($_POST['t_id']):adminmsg('请选择分类！',1);
	$category=get_gifts_category_one($t_id);
	$gifts_pre=$category['t_pre'];
	if ($number==0)
	{
		adminmsg("生成数量不正确，请填写大于0的整数！",0);
	}
	else
	{
		$addtime=time();
		$rand=mt_rand(1000,9999);
		for ($i = 1; $i <= $number; $i++)
		{
			$microtime = explode(" ", microtime());
			$account=$gifts_pre.$rand.str_pad($i,4,"0",STR_PAD_LEFT).mt_rand(1000,9999).mt_rand(1000,9999);
			$password=$pwd_pre.mt_rand(100000,999999);
			$db->query("INSERT INTO ".table('gifts')." ( `t_id` , `account` , `password` , `usettime` , `addtime` ) VALUES ( '{$t_id}', '{$account}', '{$password}', '0', '{$addtime}')");
		}
	$link[0]['text'] = "返回礼品卡列表";
	$link[0]['href'] = '?act=list';
	adminmsg("生成成功，共生成 {$number} 行",2,$link);
		
	}
}
elseif($act == 'gifts_act')
{
	check_token();
	if ($_POST['deletetid'])
	{
		$t_id=$_POST['d_tid'];
		if(empty($t_id))
		{
		adminmsg("您没有选择分类",1);
		}
		if ($num=del_gifts_tid($t_id))
		{
		adminmsg("删除成功！共删除".$num."行",2);
		}
		else
		{
		adminmsg("删除失败！",0);
		}
	}
	if ($_POST['deleteid'])
	{
		$id=$_POST['id'];
		if(empty($id))
		{
		adminmsg("您没有选择信息",1);
		}
		if ($num=del_gifts($id))
		{
		adminmsg("删除成功！共删除".$num."行",2);
		}
		else
		{
		adminmsg("删除失败！",0);
		}
	}
	if ($_POST['downtid'] || $_POST['downid'])
	{
		$id=$_POST['id'];	
		if (!empty($_POST['downid']))
		{
			if(empty($id))
			{
			adminmsg("您没有选择信息",1);
			}
			if(!is_array($id)) $id=array($id);
			$sqlin=implode(",",$id);
			if (preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin))
			{
				$sql="SELECT * FROM ".table('gifts')." AS a LEFT JOIN ".table('gifts_type')." AS t ON a.t_id=t.t_id WHERE a.id IN ({$sqlin})";
			}
		}
		elseif (!empty($_POST['downtid']))
		{
			$t_id=intval($_POST['t_id']);
			if(empty($t_id))
			{
			adminmsg("您没有选择分类",1);
			}
			else
			{
				$sql="SELECT * FROM ".table('gifts')." AS a LEFT JOIN ".table('gifts_type')." AS t ON a.t_id=t.t_id WHERE a.t_id='{$t_id}'";
			}
		}
		if (!empty($sql))
		{
				$result=$db->query($sql);
				while($row = $db->fetch_array($result))
				{
				$xls.=$row['t_name'].chr(9);
				$row['t_effective']=$row['t_effective']=="1"?"有效":"无效";
				$xls.=$row['t_effective'].chr(9);
				$xls.=$row['account'].chr(9);
				$xls.=$row['password'].chr(9);
				$xls.=$row['t_amount'].chr(9);
					if ($row['t_starttime']=="0" && $row['t_endtime']=="0")
					{
					$effectivedate="不限";
					}
					else
					{
						$row['t_starttime']=$row['t_starttime']=="0"?"不限":date("Y/m/d",$row['t_starttime']);
						$row['t_endtime']=$row['t_endtime']=="0"?"不限":date("Y/m/d",$row['t_endtime']);
						$effectivedate=$row['t_starttime']."-".$row['t_endtime'];
					}
				$xls.=$effectivedate.chr(9);
				$row['usettime']=$row['usettime']=="0"?"未使用":"已使用，使用时间".date("Y/m/d",$row['usettime']);
				$xls.=$row['usettime'].chr(9);
				$xls.=$row['t_repeat'].chr(9);
				$xls.=chr(13);
				}
				header("Content-type:application/vnd.ms-excel");
				header("Content-Disposition:attachment;filename=".date("Y-m-d-").uniqid().".xls");
				$xlstop='类型'.chr(9);
				$xlstop.='可用状态'.chr(9);
				$xlstop.='帐号'.chr(9);
				$xlstop.='密码'.chr(9);
				$xlstop.='积分'.chr(9);
				$xlstop.='有效期'.chr(9);
				$xlstop.='使用状态'.chr(9);
				$xlstop.='叠加次数'.chr(9);
				$xlstop.=chr(13);
				exit($xlstop.$xls);
		}		
	}
}
elseif($act == 'category')
{
	get_token();
	$smarty->assign('category',get_gifts_category());
	$smarty->assign('navlabel',"category");
	$smarty->display('gifts/admin_gifts_category.htm');
}
elseif($act == 'category_add')
{
	get_token();
	$smarty->assign('navlabel',"category");
	$smarty->display('gifts/admin_gifts_category_add.htm');
}
elseif($act == 'add_category_save')
{
	check_token();
	$setsqlarr['t_name']=!empty($_POST['t_name'])?trim($_POST['t_name']):adminmsg('请填写分类名称！',1);
	$setsqlarr['t_starttime']=trim($_POST['t_starttime']);
	if ($setsqlarr['t_starttime']<>"0")
	{
		if (!preg_match("/^[0-9]{4}(\\-)[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/",$setsqlarr['t_starttime']))
		{
		adminmsg("开始时间格式错误！",0);
		}
		else
		{
		$setsqlarr['t_starttime']=intval(convert_datefm($_POST['t_starttime'],2));
		}
	}
	$setsqlarr['t_endtime']=trim($_POST['t_endtime']);
	if ($setsqlarr['t_endtime']<>"0")
	{
		if (!preg_match("/^[0-9]{4}(\\-)[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/",$setsqlarr['t_endtime']))
		{
		adminmsg("结束时间格式错误！",0);
		}
		else
		{
		$setsqlarr['t_endtime']=intval(convert_datefm($_POST['t_endtime'],2));
		}
	}
	$setsqlarr['t_repeat']=intval($_POST['t_repeat']);
	$setsqlarr['t_effective']=intval($_POST['t_effective']);
	$setsqlarr['t_amount']=intval($_POST['t_amount'])>0?intval($_POST['t_amount']):adminmsg('请正确填写积分！',1);
	$setsqlarr['t_pre']=!empty($_POST['t_pre'])?trim($_POST['t_pre']):adminmsg('请填写分类前缀！',1);
	$info=$db->getone("select * from ".table('gifts_type')." where t_pre='{$setsqlarr['t_pre']}' LIMIT 1");
	if (!empty($info))
	{
	adminmsg("分类前缀 {$setsqlarr['t_pre']} 已经存在！",1);
	}
	!inserttable(table('gifts_type'),$setsqlarr)?adminmsg("添加失败！",0):"";
	$link[0]['text'] = "返回分类管理";
	$link[0]['href'] = '?act=category';
	$link[1]['text'] = "继续添加";
	$link[1]['href'] = "?act=category_add";
	adminmsg("添加成功！",2,$link);
}
elseif($act == 'edit_category')
{
	get_token();
	$id=intval($_GET['id']);
	$category=get_gifts_category_one($id);
	if ($category['t_starttime']<>"0")
	{
	$category['t_starttime']=trim(convert_datefm($category['t_starttime'],1));
	}
	if ($category['t_endtime']<>"0")
	{
	$category['t_endtime']=trim(convert_datefm($category['t_endtime'],1));
	}
	$smarty->assign('category',$category);
	$smarty->assign('navlabel',"category");
	$smarty->display('gifts/admin_gifts_category_edit.htm');
}
elseif($act == 'edit_category_save')
{
	check_token();
	$id=intval($_POST['id']);
	$setsqlarr['t_name']=!empty($_POST['t_name'])?trim($_POST['t_name']):adminmsg('请填写分类名称！',1);
	$setsqlarr['t_starttime']=trim($_POST['t_starttime']);
	if ($setsqlarr['t_starttime']<>"0")
	{
		if (!preg_match("/^[0-9]{4}(\\-)[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/",$setsqlarr['t_starttime']))
		{
		adminmsg("开始时间格式错误！",0);
		}
		else
		{
		$setsqlarr['t_starttime']=intval(convert_datefm($_POST['t_starttime'],2));
		}
	}
	$setsqlarr['t_endtime']=trim($_POST['t_endtime']);
	if ($setsqlarr['t_endtime']<>"0")
	{
		if (!preg_match("/^[0-9]{4}(\\-)[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/",$setsqlarr['t_endtime']))
		{
		adminmsg("结束时间格式错误！",0);
		}
		else
		{
		$setsqlarr['t_endtime']=intval(convert_datefm($_POST['t_endtime'],2));
		}
	}
	$setsqlarr['t_repeat']=intval($_POST['t_repeat']);
	$setsqlarr['t_effective']=intval($_POST['t_effective']);
	$setsqlarr['t_amount']=intval($_POST['t_amount'])>0?intval($_POST['t_amount']):adminmsg('请正确填写积分！',1);
	$setsqlarr['t_pre']=!empty($_POST['t_pre'])?trim($_POST['t_pre']):adminmsg('请填写分类前缀！',1);
	$info=$db->getone("select * from ".table('gifts_type')." where t_pre='{$setsqlarr['t_pre']}' LIMIT 1");
	if (!empty($info) && $info['t_id']<>$id)
	{
	adminmsg("分类前缀 {$setsqlarr['t_pre']} 已经存在！",1);
	}
	$link[0]['text'] = "查看修改结果";
	$link[0]['href'] = '?act=edit_category&id='.$id;
	$link[1]['text'] = "返回分类管理";
	$link[1]['href'] = '?act=category';
	!updatetable(table('gifts_type'),$setsqlarr," t_id=".$id."")?adminmsg("修改失败！",0):adminmsg("修改成功！",2,$link);
}
elseif($act == 'del_category')
{
	check_token();
	$id=$_REQUEST['id'];
	$num=del_gifts_category($id);
	if ($num==-1)
	{
	adminmsg("删除失败！所选分类中存在礼品卡，请先删除生成的礼品卡",1);
	}
	if ($num>0)
	{
	adminmsg("删除成功！共删除".$num."行",2);
	}
	else
	{
	adminmsg("删除失败！",0);
	}
}
elseif($act == 'use')
{
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$oederbysql=" order BY g.usetime  DESC";
	$key=isset($_GET['key'])?trim($_GET['key']):"";
	$key_type=isset($_GET['key_type'])?intval($_GET['key_type']):"";
	if ($key && $key_type>0)
	{
		
		if     ($key_type===1)$wheresql=" WHERE g.account='{$key}'";
		if     ($key_type===2)$wheresql=" WHERE m.username='{$key}'";
		if     ($key_type===3)$wheresql=" WHERE g.uid='{$key}'";
		if     ($key_type===4)$wheresql=" WHERE m.email='{$key}'";
		if     ($key_type===5)$wheresql=" WHERE m.mobile='{$key}'";
		$oederbysql="";
	}
	else
	{

		$settr=intval($_GET['settr']);
		if ($settr>0)
		{
			$wheresql.=empty($wheresql)?" WHERE ":" AND  ";
			$days=intval($settr);
			$settr=strtotime("-{$days} day");
			$wheresql.=" g.usetime> {$settr} ";	
		}
		$t_id=isset($_GET['t_id'])?intval($_GET['t_id']):"";
		if ($t_id>0)
		{
		$wheresql=empty($wheresql)?" WHERE g.giftstid= ".$t_id:$wheresql." AND g.giftstid= ".$t_id;
		}
	}
	$joinsql=" LEFT JOIN  ".table('members')." AS m ON g.uid=m.uid ";
	$total_sql="SELECT COUNT(*) AS num FROM ".table('members_gifts')." AS g ".$joinsql.$wheresql;
	$total=$db->get_total($total_sql);
	$page = new page(array('total'=>$total, 'perpage'=>$perpage));
	$currenpage=$page->nowindex;
	$offset=($currenpage-1)*$perpage;
	$list = get_members_gifts($offset, $perpage,$joinsql.$wheresql.$oederbysql);
	$smarty->assign('category',get_gifts_category());
	$smarty->assign('list',$list);
	$smarty->assign('total',$total);
	$smarty->assign('page',$page->show(3));
	$smarty->assign('navlabel',"use");
	$smarty->display('gifts/admin_members_gifts_list.htm');
}
?>