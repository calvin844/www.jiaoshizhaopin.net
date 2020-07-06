<?php
 /*
 * 74cms 网站首页
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
set_time_limit(0);
define('IN_QISHI', true);
require_once('../include/common.inc.php'); 
require_once(QISHI_ROOT_PATH.'include/fun_user.php');
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
$t1 = exectime();
require_once('conversion_cngfig.php');
$locoyspider=get_cache('locoyspider');
$id=intval($_GET['id']);

require_once(QISHI_ROOT_PATH.'include/splitword.class.php');
$sp = new SPWord();

//补充转换简历投递量_Calvin_20140619(需写在此)---begin
$sql="select  top 1 *  from pH_Company_InBox where Ncid = ".$id;
$sc_rs = ms_getone($sql);
if(!empty($sc_rs)){
    //查询简历信息
    $pr_sql="select NCID,UserName,RealName from pH_Person_Info where PerId='".$sc_rs['PerId']."'";
    $pr_rs = ms_getone($pr_sql);
    
    //查询职位信息
    $job_sql="select top 1 * from pH_Job_Base where Jobid='".$sc_rs['Jobid']."'";
    $job_rs = ms_getone($job_sql);
    
    //查询公司信息
    $com_sql="select top 1 * from pH_Company_Base where Comid='".$sc_rs['Comid']."'";
    $com_rs = ms_getone($com_sql);
    
    //检查记录是否存在
    $pj_sql="select did from ".table('personal_jobs_apply')." where resume_id='".$pr_rs['NCID']."' and apply_addtime=".strtotime($sc_rs['AddDate'])." LIMIT 1";
    $pj_info=$db->getone($pj_sql);
    //补充记录
    if(empty($pj_info)){
        $sc_arr['resume_id'] = $pr_rs['NCID'];
        $sc_arr['resume_name'] = escape_str($pr_rs['RealName']).'创建的简历';
        $db_sql="select uid from ".table('members')." where username='".addslashes($pr_rs['UserName'])."' LIMIT 1";
        $info=$db->getone($db_sql);
        $sc_arr['personal_uid'] = $info['uid'];
        $sc_arr['jobs_id'] = $sc_rs['Jobid'];
        $sc_arr['jobs_name'] = escape_str($job_rs['JobName']);
        $sc_arr['company_id'] = $com_rs['NCID'];
        $sc_arr['company_name'] = escape_str($com_rs['CompanyName']);
        $userinfo=get_user_inusername(escape_str($com_rs['UserName']));
        $sc_arr['company_uid'] = $userinfo['uid'];;
        $sc_arr['apply_addtime']=strtotime($sc_rs['AddDate']);
        if($sc_rs['Flag']){
            $sc_arr['personal_look'] = 2;
        }else{
            $sc_arr['personal_look'] = 1;
        }
        $sc_arr['notes'] = "无";
        //var_dump($sc_arr);exit;
        inserttable(table('personal_jobs_apply'),$sc_arr);
    }else{
        echo "已存在";
        exit;
    }
}
    //补充转换简历投递量_Calvin_20140619(需写在此)---end

?>