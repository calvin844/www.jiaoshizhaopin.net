<?php
 /*
 * 74cms ajax 加载公司职位更多
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(dirname(__FILE__)).'/include/plus.common.inc.php');
$act = !empty($_POST['act']) ? trim($_POST['act']) :trim($_GET['act']);
if ($act=='get_companyjobslist')
{
	$comjobshtml="";
	$rows=2;
	$companyid=intval($_GET['company_id']); 
	$comjobsarray=$db->getall("select * from ".table('jobs')." where company_id = '{$companyid}' ORDER BY stick DESC , refreshtime DESC LIMIT {$rows}");
	if (!empty($comjobsarray))
	{
		foreach($comjobsarray as $li)
		{
			//$jobs_url=url_rewrite("QS_jobsshow",array('id'=>$li['id']),true,$li['subsite_id']);
			$jobs_url="/job/".$li['id'].".html";
			$jobs_name=cut_str($li['jobs_name'],"10",0,"..");
			$comjobshtml.="<li><div class=\"j_name\"><a href=\"{$jobs_url}\" target=\"_blank\">{$jobs_name}</a><span class=\"j_time\">".date('Y-m-d',$li['addtime'])."发布</span></div><p><span>学历要求：{$li['education_cn']}</span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span>工作经验：{$li['experience_cn']}</span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span>工作地点：{$li['district_cn']}</span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span>薪资待遇：<em>{$li['wage_cn']}</em></span></p></li>";
		}
		//$jobslist_url = url_rewrite("QS_companyjobs",array("id"=>$companyid));
		$jobslist_url = "/school/".$companyid.".html";
		$comjobshtml.="<p class=\"more\"><a target=\"_blank\" href=\"".$jobslist_url."\">查看更多>></a></p>";
		exit($comjobshtml);
	}
	else
	{
		exit('empty!');
	}
}
elseif ($act=='get_companycommentlist')
{
	$comcommenthtml="";
	$rows=2;
	$companyid=intval($_GET['company_id']); 
	$comcommentarray=$db->getall("select  c.*,m.username from ".table('comment')." as c LEFT JOIN  ".table('members')." AS m ON c.uid=m.uid where company_id = '{$companyid}' and audit=1 ORDER BY c.id DESC LIMIT {$rows}");
	if (!empty($comcommentarray))
	{
		foreach($comcommentarray as $li)
		{
			$comcommenthtml.="<li><div class=\"avatar\"><img src=\"{$_CFG['main_domain']}data/images/avatar.gif\" width=\"65\" height=\"65\" /></div><div class=\"comm_content\"><div class=\"comm_top\"><p class=\"name\">{$li['username']}</p><p class=\"date\">".date('Y-m-d H:i',$li['addtime'])."</p><div class=\"clear\"></div></div><div class=\"comm_txt\">";
			$comcommenthtml.="<p>{$li['content']}</p>";
			$comcommenthtml.="</div></div><div class=\"clear\"></div></li>";
		}
		//$commentlist_url = url_rewrite("QS_companycomment",array("id"=>$companyid));
		$commentlist_url = "/school/".$companyid.".html";
		$comcommenthtml.="<p class=\"more\"><a target=\"_blank\" href=\"".$commentlist_url."\">查看更多>></a></p>";
		exit($comcommenthtml);
	}
	else
	{
		exit('empty!');
	}
}
?>