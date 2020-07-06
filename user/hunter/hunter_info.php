	<?php
/*
 * 74cms 猎头会员中心
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(__FILE__).'/hunter_common.php');
$smarty->assign('leftmenu',"info");
if ($act=='hunter_profile')
{
	$smarty->assign('title','猎头资料管理 - 猎头会员中心 - '.$_CFG['site_name']);
	$smarty->assign('hunter_profile',$hunter_profile);
	$smarty->display('member_hunter/hunter_profile.htm');
}
elseif ($act=='hunter_profile_save')
{
	
	$uid=intval($_SESSION['uid']);
	$setsqlarr['uid']=intval($_SESSION['uid']);
	$setsqlarr['huntername']=trim($_POST['huntername'])?trim($_POST['huntername']):showmsg('您没有输入猎头名称！',1);
	$setsqlarr['companyname']=trim($_POST['companyname'])?trim($_POST['companyname']):showmsg('您没有输入猎头所在公司！',1);
	$code=trim($_POST['code'])?trim($_POST['code']):showmsg('您没有填写座机区号！',1);
	$telephone=trim($_POST['companytelephone'])?trim($_POST['companytelephone']):showmsg('您没有填写座机号码！',1);
	$setsqlarr['companytelephone']=$code.'-'.$telephone;
	$setsqlarr['district']=intval($_POST['district'])>0?intval($_POST['district']):showmsg('您没有选择所属地区！',1);
	$setsqlarr['sdistrict']=intval($_POST['sdistrict']);
	$setsqlarr['district_cn']=trim($_POST['district_cn']);
	$setsqlarr['worktime_start']=intval($_POST['worktime_start'])>1970?intval($_POST['worktime_start']):showmsg('您没有填写从业开始时间！',1);
	$setsqlarr['rank']=trim($_POST['rank'])?intval($_POST['rank']):showmsg('您没有选择猎头头衔！',1);
	$setsqlarr['rank_cn']=trim($_POST['rank_cn']);
	$setsqlarr['goodtrade']=trim($_POST['goodtrade'])?trim($_POST['goodtrade']):showmsg('您没有选择擅长行业！',1);
	$setsqlarr['goodtrade_cn']=trim($_POST['goodtrade_cn']);
	$setsqlarr['goodcategory']=trim($_POST['goodcategory'])?trim($_POST['goodcategory']):showmsg('您没有选择擅长职能！',1);
	$setsqlarr['goodcategory_cn']=trim($_POST['goodcategory_cn']);
	$setsqlarr['contents']=trim($_POST['contents'])?trim($_POST['contents']):showmsg('请填写猎头简介！',1);
	$setsqlarr['cooperate_company']=trim($_POST['cooperate_company']);
	
	
	$setsqlarr['address']=trim($_POST['address'])?trim($_POST['address']):showmsg('请填写通讯地址！',1);
	$setsqlarr['telephone']=trim($_POST['telephone'])?trim($_POST['telephone']):showmsg('请填写联系电话！',1);
	$setsqlarr['email']=trim($_POST['email'])?trim($_POST['email']):showmsg('请填写联系邮箱！',1);
	$setsqlarr['yellowpages']=intval($_POST['yellowpages']);
	
	$setsqlarr['email_show']=intval($_POST['email_show']);
	$setsqlarr['telephone_show']=intval($_POST['telephone_show']);
	$setsqlarr['address_show']=intval($_POST['address_show']);
	
	$setsqlarr['key']=$setsqlarr['huntername'].$setsqlarr['companyname'].$setsqlarr['goodtrade_cn'].$setsqlarr['goodcategory_cn'].$setsqlarr['contents'];
	require_once(QISHI_ROOT_PATH.'include/splitword.class.php');
	$sp = new SPWord();
	$setsqlarr['key']="{$setsqlarr['huntername']} {$setsqlarr['companyname']} ".$sp->extracttag($setsqlarr['key']);
	$setsqlarr['key']=$sp->pad($setsqlarr['key']);
	
	$link[0]['text'] = "查看修改结果";
	$link[0]['href'] = '?act=hunter_profile';
	$link[1]['text'] = "发布招聘信息";
	$link[1]['href'] = "hunter_jobs.php?act=addjobs";
	$link[2]['text'] = "会员中心首页";
	$link[2]['href'] = "hunter_index.php?";
	if ($_CFG['hunter_repeat']=="0")
	{
		$info=$db->getone("SELECT uid FROM ".table('hunter_profile')." WHERE huntername ='{$setsqlarr['huntername']}' AND companyname ='{$setsqlarr['companyname']}' AND uid<>'{$_SESSION['uid']}' LIMIT 1");
		if(!empty($info))
		{
			showmsg("{$setsqlarr['huntername']}已经存在，同猎头信息不能重复注册",1);
		}
	}
	if ($hunter_profile)
	{
			$setsqlarr['refreshtime']=$timestamp;
			$_CFG['audit_edit_hunter']<>"-1"?$setsqlarr['audit']=intval($_CFG['audit_edit_hunter']):'';
			if (updatetable(table('hunter_profile'), $setsqlarr," uid='{$uid}'"))
			{
				unset($setsqlarr);
				write_memberslog($_SESSION['uid'],$_SESSION['utype'],8501,$_SESSION['username'],"修改猎头资料");
				showmsg("保存成功！",2,$link);
			}
			else
			{
				showmsg("保存失败！",0);
			}
	}
	else
	{
			$setsqlarr['audit']=intval($_CFG['audit_add_hunter']);
			$setsqlarr['addtime']=$timestamp;
			$setsqlarr['refreshtime']=$timestamp;
			if (inserttable(table('hunter_profile'),$setsqlarr))
			{
				write_memberslog($_SESSION['uid'],$_SESSION['utype'],8500,$_SESSION['username'],"完善猎头资料");
				showmsg("保存成功！",2,$link);
			}
			else
			{
				showmsg("保存失败！",0);
			}
	}
}

elseif ($act=='photo')
{
	if (empty($hunter_profile)) showmsg('请先完善猎头基本资料！',0);
	$smarty->assign('title','照片认证 - 猎头会员中心 - '.$_CFG['site_name']);
	$smarty->assign('points',get_cache('points_rule'));
	$smarty->assign('hunter_profile',$hunter_profile);
	$smarty->display('member_hunter/hunter_photo.htm');
}
elseif ($act=='photo_save')
{
	require_once(QISHI_ROOT_PATH.'include/upload.php');
	$setsqlarr['audit']=2;//添加默认审核中..
	!$_FILES['photo_img']['name']?showmsg('请上传照片！',1):"";
	$photo_dir="../../data/hunter/".date("Y/m/d/");
	make_dir($photo_dir);
	$setsqlarr['photo_img']=_asUpFiles($photo_dir, "photo_img",$_CFG['resume_photo_max'],'gif/jpg/bmp/png',true);
	if ($setsqlarr['photo_img'])
	{
	/*
	3.5新增打水印start
	 */
	if(extension_loaded('gd')){
		include_once(QISHI_ROOT_PATH.'include/watermark.php');
		$font_dir=QISHI_ROOT_PATH."data/contactimgfont/cn.ttc";
		if(file_exists($font_dir)){
			$tpl=new watermark;
			$tpl->img($photo_dir.$setsqlarr['photo_img'],gbk_to_utf8($_CFG['site_name']),$font_dir,15,0);
		}
	}
	/*
	3.5新增end
	 */
	$setsqlarr['photo_img']=date("Y/m/d/").$setsqlarr['photo_img'];
	$auth=$hunter_profile;
	@unlink("../../data/hunter/".$auth['photo_img']);
	!updatetable(table('hunter_profile'),$setsqlarr," id='".intval($hunter_profile['id'])."' AND uid='".intval($_SESSION['uid'])."'")?showmsg("保存失败！",0):showmsg("保存成功！",2);
	}
	else
	{
	showmsg('保存失败！',1);
	}
}

unset($smarty);
?>