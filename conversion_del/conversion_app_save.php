<?php
 /*
 * 74cms ��վ��ҳ
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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

//����ת������Ͷ����_Calvin_20140619(��д�ڴ�)---begin
$sql="select  top 1 *  from pH_Company_InBox where Ncid = ".$id;
$sc_rs = ms_getone($sql);
if(!empty($sc_rs)){
    //��ѯ������Ϣ
    $pr_sql="select NCID,UserName,RealName from pH_Person_Info where PerId='".$sc_rs['PerId']."'";
    $pr_rs = ms_getone($pr_sql);
    
    //��ѯְλ��Ϣ
    $job_sql="select top 1 * from pH_Job_Base where Jobid='".$sc_rs['Jobid']."'";
    $job_rs = ms_getone($job_sql);
    
    //��ѯ��˾��Ϣ
    $com_sql="select top 1 * from pH_Company_Base where Comid='".$sc_rs['Comid']."'";
    $com_rs = ms_getone($com_sql);
    
    //����¼�Ƿ����
    $pj_sql="select did from ".table('personal_jobs_apply')." where resume_id='".$pr_rs['NCID']."' and apply_addtime=".strtotime($sc_rs['AddDate'])." LIMIT 1";
    $pj_info=$db->getone($pj_sql);
    //�����¼
    if(empty($pj_info)){
        $sc_arr['resume_id'] = $pr_rs['NCID'];
        $sc_arr['resume_name'] = escape_str($pr_rs['RealName']).'�����ļ���';
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
        $sc_arr['notes'] = "��";
        //var_dump($sc_arr);exit;
        inserttable(table('personal_jobs_apply'),$sc_arr);
    }else{
        echo "�Ѵ���";
        exit;
    }
}
    //����ת������Ͷ����_Calvin_20140619(��д�ڴ�)---end

?>