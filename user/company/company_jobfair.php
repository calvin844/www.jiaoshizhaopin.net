<?php
/*
 * 74cms 企业会员中心
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(__FILE__).'/company_common.php');
$smarty->assign('leftmenu',"jobfair");
if ($act=='mybooth')
{
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$oederbysql=" order BY e.id DESC";
	$wheresql= " WHERE e.uid={$_SESSION['uid']} ";
	$settr=intval($_GET['settr']);
	if($settr>0)
	{
	$settr_val=strtotime("-".$settr." day");
	$wheresql.=" AND e.eaddtime>".$settr_val;
	}
	$joinsql=" LEFT JOIN  ".table('jobfair')." AS j ON  e.jobfairid=j.id ";
	$total_sql="SELECT COUNT(*) AS num FROM ".table('jobfair_exhibitors')." as e ".$joinsql.$wheresql;
	$perpage=10;
	$total_val=$db->get_total($total_sql);
	$page = new page(array('total'=>$total_val, 'perpage'=>$perpage));
	$currenpage=$page->nowindex;
	$offset=($currenpage-1)*$perpage;
	$smarty->assign('title','我预定的展位 - 招聘会 - 会员中心 - '.$_CFG['site_name']);
	$smarty->assign('list',get_jobfair_exhibitors($offset,$perpage,$joinsql.$wheresql));
	if ($total_val>$perpage)$smarty->assign('page',$page->show(3));
	$smarty->display('member_company/company_jobfair_exhibitors.htm');
}
unset($smarty);
?>