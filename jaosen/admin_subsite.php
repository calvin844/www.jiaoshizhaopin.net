<?php
 /*
 * 74cms 分站
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(__FILE__).'/../data/config.php');
require_once(dirname(__FILE__).'/include/admin_common.inc.php');
require_once(ADMIN_ROOT_PATH.'include/upload.php');
require_once(ADMIN_ROOT_PATH.'include/admin_category_fun.php');
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'set';
$smarty->assign('act',$act);
$smarty->assign('pageheader',"分站管理");
check_permissions($_SESSION['admin_purview'],"subsite");
if($act == 'set')
{	
	get_token();
	$smarty->assign('config',$_CFG);
	$smarty->assign('navlabel',"set");
	$smarty->display('subsite/admin_subsite_set.htm');
}
elseif($act == 'setsave')
{
		check_token();
		foreach($_POST as $k => $v)
		{
		!$db->query("UPDATE ".table('config')." SET value='$v' WHERE name='$k'")?adminmsg('更新站点设置失败', 1):"";
		}
		refresh_cache('config');
		adminmsg("保存成功！",2);
}
elseif($act == 'list')
{	

	get_token();
	$smarty->assign('list',get_subsite_list());
	$smarty->assign('navlabel',"list");
	$smarty->display('subsite/admin_subsite_list.htm');
}
elseif($act == 'add')
{		
	get_token();
	$smarty->assign('district',get_category_district());
	$smarty->assign('navlabel',"add");
	$smarty->display('subsite/admin_subsite_add.htm');
}
elseif($act == 'add_save')
{	
	check_token();
	$setsqlarr['s_sitename']=!empty($_POST['s_sitename'])?trim($_POST['s_sitename']):adminmsg('请填写分站名称！',1);
	$setsqlarr['s_effective']=isset($_POST['s_effective'])?intval($_POST['s_effective']):1;
	$setsqlarr['s_districtname']=!empty($_POST['s_districtname'])?trim($_POST['s_districtname']):adminmsg('请填写地区名称！',1);
	$setsqlarr['s_index']=getfirstchar($setsqlarr['s_districtname']);
	$setsqlarr['s_domain']=trim($_POST['s_domain']);
	$setsqlarr['s_dir']=!empty($_POST['s_dir'])?trim(trim($_POST['s_dir'],"/")):adminmsg('请填写目录！',1);
	if($setsqlarr['s_dir']){
		if( !eregi("^[_a-z0-9\.\/]+$",$setsqlarr['s_dir']) ){
			adminmsg("目录名称只能是:数字,字母,下画线");
		}
		if( $db->getone("SELECT * FROM ".table('subsite')." WHERE s_dir='".$setsqlarr['s_dir']."'") ){
			adminmsg("目录名称已经存在了,请更换一个");
		}
	}
	$setsqlarr['s_tpl']=!empty($_POST['s_tpl'])?trim($_POST['s_tpl']):'';
	$setsqlarr['s_order']=intval($_POST['s_order']);
	if ( $_FILES['s_logo']['name'])
	{
		$setsqlarr['s_logo']=_asUpFiles($upfiles_dir, "s_logo", 1024*2, 'jpg/gif/png',"logo".md5($setsqlarr['s_domain']));
		if (empty($setsqlarr['s_logo']))
		{
		adminmsg('上传图片出错！',1);
		}
	}
	$setsqlarr['s_filter_notice']=isset($_POST['s_filter_notice'])?intval($_POST['s_filter_notice']):1;
	$setsqlarr['s_filter_jobs']=isset($_POST['s_filter_jobs'])?intval($_POST['s_filter_jobs']):1;
	$setsqlarr['s_filter_resume']=isset($_POST['s_filter_resume'])?intval($_POST['s_filter_resume']):1;
	$setsqlarr['s_filter_ad']=isset($_POST['s_filter_ad'])?intval($_POST['s_filter_ad']):1;
	$setsqlarr['s_filter_links']=isset($_POST['s_filter_links'])?intval($_POST['s_filter_links']):1;
	$setsqlarr['s_filter_news']=isset($_POST['s_filter_news'])?intval($_POST['s_filter_news']):1;
	$setsqlarr['s_filter_explain']=isset($_POST['s_filter_explain'])?intval($_POST['s_filter_explain']):1;
	$setsqlarr['s_filter_jobfair']=isset($_POST['s_filter_jobfair'])?intval($_POST['s_filter_jobfair']):1;
	$setsqlarr['s_filter_simple']=isset($_POST['s_filter_simple'])?intval($_POST['s_filter_simple']):1;
	$setsqlarr['s_filter_course']=isset($_POST['s_filter_course'])?intval($_POST['s_filter_course']):1;
	$link[0]['text'] = "返回分站管理";
	$link[0]['href'] = '?act=list';
	$insertid = inserttable(table('subsite'),$setsqlarr,1);
	if (!$insertid)
	{
	adminmsg("添加失败！",0);
	}
	else
	{
	make_city_file($setsqlarr['s_dir'],$insertid);
	refresh_subsite_cache();
	adminmsg("添加成功！",2,$link);	
	}
}
elseif ($act == 'edit')
{
	get_token();
	$id=intval($_GET['id']);	
	$subsite=get_subsite_one($id);
	$smarty->assign('district',get_category_district());
	$smarty->assign('subsite',$subsite);
	$smarty->assign('rand',rand(1,100));
	$smarty->assign('upfiles_dir',$upfiles_dir);
	$smarty->display('subsite/admin_subsite_edit.htm');
}
elseif ($act == 'edit_save')
{
	check_token();
	$s_id=intval($_POST['s_id']);
	$setsqlarr['s_sitename']=!empty($_POST['s_sitename'])?trim($_POST['s_sitename']):adminmsg('请填写分站名称！',1);
	$setsqlarr['s_effective']=isset($_POST['s_effective'])?intval($_POST['s_effective']):1;
	$setsqlarr['s_districtname']=!empty($_POST['s_districtname'])?trim($_POST['s_districtname']):adminmsg('请填写地区名称！',1);
	$setsqlarr['s_index']=getfirstchar($setsqlarr['s_districtname']);
	$setsqlarr['s_domain']=trim($_POST['s_domain']);
	$setsqlarr['s_dir']=!empty($_POST['s_dir'])?trim(trim($_POST['s_dir'],"/")):adminmsg('请填写目录！',1);	
	
	if($setsqlarr['s_dir']){
		if( !eregi("^[_a-z0-9\.\/]+$",$setsqlarr['s_dir']) ){
			adminmsg("目录名称只能是:数字,字母,下画线");
		}
		if( $db->getone("SELECT * FROM ".table('subsite')." WHERE s_dir='".$setsqlarr['s_dir']."' and s_id!=".$s_id) ){
			adminmsg("目录名称已经存在了,请更换一个");
		}
	}
	$setsqlarr['s_tpl']=!empty($_POST['s_tpl'])?trim($_POST['s_tpl']):'';
	$setsqlarr['s_order']=intval($_POST['s_order']);

	if ( $_FILES['s_logo']['name'])
	{
		$setsqlarr['s_logo']=_asUpFiles($upfiles_dir, "s_logo", 1024*2, 'jpg/gif/png',"logo".md5($setsqlarr['s_domain']));
		if (empty($setsqlarr['s_logo']))
		{
		adminmsg('上传图片出错！',1);
		}
	}
	$setsqlarr['s_filter_notice']=isset($_POST['s_filter_notice'])?intval($_POST['s_filter_notice']):1;
	$setsqlarr['s_filter_jobs']=isset($_POST['s_filter_jobs'])?intval($_POST['s_filter_jobs']):1;
	$setsqlarr['s_filter_resume']=isset($_POST['s_filter_resume'])?intval($_POST['s_filter_resume']):1;
	$setsqlarr['s_filter_ad']=isset($_POST['s_filter_ad'])?intval($_POST['s_filter_ad']):1;
	$setsqlarr['s_filter_links']=isset($_POST['s_filter_links'])?intval($_POST['s_filter_links']):1;
	$setsqlarr['s_filter_news']=isset($_POST['s_filter_news'])?intval($_POST['s_filter_news']):1;
	$setsqlarr['s_filter_explain']=isset($_POST['s_filter_explain'])?intval($_POST['s_filter_explain']):1;
	$setsqlarr['s_filter_jobfair']=isset($_POST['s_filter_jobfair'])?intval($_POST['s_filter_jobfair']):1;
	$setsqlarr['s_filter_simple']=isset($_POST['s_filter_simple'])?intval($_POST['s_filter_simple']):1;
	$setsqlarr['s_filter_course']=isset($_POST['s_filter_course'])?intval($_POST['s_filter_course']):1;
	$link[0]['text'] = "查看修改结果";
	$link[0]['href'] =  '?act=edit&id='.$s_id;
	$link[1]['text'] = "返回分站管理";
	$link[1]['href'] = '?act=list';
	if (!updatetable(table('subsite'),$setsqlarr," s_id=".$s_id.""))
	{
	adminmsg("修改失败！",0);
	}
	else
	{
	make_city_file($setsqlarr['s_dir'],$s_id);
	refresh_subsite_cache();
	adminmsg("修改成功！",2,$link);
	}
}
elseif ($act == 'subsite_del')
{
	check_token();
	$id=$_REQUEST['id'];
	if ($num=del_subsite($id))
	{
	refresh_subsite_cache();
	adminmsg("删除成功！共删除".$num."行",2);
	}
	else
	{
	adminmsg("删除失败！",0);
	}
}
?>