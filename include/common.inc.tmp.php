<?php
 /*
 * 74cms ?±ç?¨é??ç½???ä»?
 * ============================================================================
 * ????????: éª?å£?ç½?ç»?ï¼?å¹¶ä??????????©ã??
 * ç½?ç«??°å??: http://www.74cms.comï¼?
 * ----------------------------------------------------------------------------
 * è¿?ä¸???ä¸?ä¸????±è½¯ä»¶ï??¨å???½å?¨ä??¨ä???ä¸???????????ä¸?å¯¹ç?åº?ä»£ç??è¿?è¡?ä¿??¹å??
 * ä½¿ç??ï¼?ä¸???è®¸å?¹ç?åº?ä»£ç??ä»¥ä»»ä½?å½¢å?ä»»ä???????????å¸???
 * ============================================================================
*/
if(!defined('IN_QISHI')) exit('Access Denied!');
define('QISHI_ROOT_PATH',dirname(dirname(__FILE__)).'/');
error_reporting(E_ERROR);
ini_set('session.save_handler', 'files');
session_save_path(QISHI_ROOT_PATH.'data/sessions/');
session_start();
require_once(QISHI_ROOT_PATH.'data/config.php');
header("Content-Type:text/html;charset=".QISHI_CHARSET);
require_once(QISHI_ROOT_PATH.'include/common.fun.php');
require_once(QISHI_ROOT_PATH.'include/74cms_version.php');
$QSstarttime=exectime();


date_default_timezone_set("PRC");
$timestamp = time();
$online_ip=getip();
$ip_address=convertip($online_ip);
$_NAV=get_cache('nav');
$_PAGE=get_cache('page');
$_CFG=get_cache('config');
$_PLUG=get_cache('plug');
$_CFG['main_domain'] = $_CFG['site_domain'].$_CFG['site_dir'];
$_CFG['wap_domain'] = $_CFG['wap_domain']==""?$_CFG['main_domain']."wap":$_CFG['wap_domain'];
$_CFG['version']=QISHI_VERSION;
$_CFG['web_logo']=$_CFG['web_logo']?$_CFG['web_logo']:'logo.gif';
$_CFG['upfiles_dir']=$_CFG['main_domain']."data/".$_CFG['updir_images']."/";
$_CFG['thumb_dir']=$_CFG['main_domain']."data/".$_CFG['updir_thumb']."/";
$_CFG['resume_photo_dir']=$_CFG['main_domain']."data/".$_CFG['resume_photo_dir']."/";
$_CFG['resume_photo_dir_thumb']=$_CFG['main_domain']."data/".$_CFG['resume_photo_dir_thumb']."/";
$_CFG['teacher_photo_dir']=$_CFG['main_domain']."data/train_teachers/";
$_CFG['teacher_photo_dir_thumb']=$_CFG['main_domain']."data/train_teachers/thumb/";
$_CFG['hunter_photo_dir']=$_CFG['main_domain']."data/hunter/";
$_CFG['hunter_photo_dir_thumb']=$_CFG['main_domain']."data/hunter/thumb/";
$_CFG['subsite_id']=0;
subsiteinfo($_CFG);
$_CFG['site_template']=$_CFG['main_domain'].'templates/'.$_CFG['template_dir'];
$mypage=$_PAGE[$alias];
$mypage['tag']?$page_select=$mypage['tag']:'';
require_once(QISHI_ROOT_PATH.'include/tpl.inc.php');
	if ($_CFG['isclose'])
	{
				$smarty->assign('info',$_CFG['close_reason']=$_CFG['close_reason']?$_CFG['close_reason']:'ç«??¹æ???¶å?³é??...');
				$smarty->display('warning.htm');
				exit();
	}
	if ($_CFG['filter_ip'] && check_word($_CFG['filter_ip'],$online_ip))
	{
			$smarty->assign('info',$_CFG['filter_ip_tips']);
			$smarty->display('warning.htm');
			exit();
	}
?>