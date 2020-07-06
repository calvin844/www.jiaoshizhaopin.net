<?php
/*
 * 74cms ���˻�Ա����(�����˼���)
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/personal_common.php');
$smarty->assign('leftmenu',"resume");
$act=trim($_GET['act']);
if ($act=='resume_show')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_GET['pid']);
	$resume=get_manager_resume_basic($uid,$pid);
	if (empty($resume))
	{
	$link[0]['text'] = "���ؼ����б�";
	$link[0]['href'] = '?act=resume_list';
	showmsg('���������ڻ��Ѿ���ɾ����',1,$link);
	}
	$smarty->assign('random',mt_rand());
	$smarty->assign('time',time());
	$smarty->assign('user',$user);
	$smarty->assign('resume_basic',$resume);
	$smarty->assign('resume_education',get_manager_resume_education($uid,$pid));
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
	$smarty->assign('title','Ԥ������ - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->display('member_personal/personal_manager_resume.htm');
}
elseif ($act=='refresh')
{
	refresh_manager_resume($_SESSION['uid'])?showmsg('�����ɹ���',2):showmsg('����ʧ�ܣ�',0);
}
//ɾ������
elseif ($act=='del_resume')
{
	if (empty($_REQUEST['y_id']))
	{
	showmsg('��û��ѡ�������',1);
	}
	else
	{
	del_manager_resume($_SESSION['uid'],$_REQUEST['y_id'])?showmsg('ɾ���ɹ���',2):showmsg('ɾ��ʧ�ܣ�',0);
	}
}
//�����б�
elseif ($act=='resume_list')
{
	$wheresql=" WHERE uid='".$_SESSION['uid']."' ";
	$sql="SELECT * FROM ".table('manager_resume').$wheresql;
	$smarty->assign('title','�ҵľ����˼��� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->assign('act',$act);
	$smarty->assign('total',$total);
	$smarty->assign('resume_list',get_manager_resume_list($sql,12,true));
	$smarty->display('member_personal/personal_manager_resume_list.htm');
}
//��������-������Ϣ
elseif ($act=='make1')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$smarty->assign('resume_basic',get_manager_resume_basic($uid,$pid));
	$smarty->assign('resume_education',get_manager_resume_education($uid,$pid));
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
 	$smarty->assign('act',"make1");
	$smarty->assign('pid',$pid);
	$smarty->assign('user',$user);
	$smarty->assign('title','�ҵĸ߼����� - ���˻�Ա���� - '.$_CFG['site_name']);
	$captcha=get_cache('captcha');
	$smarty->assign('verify_resume',$captcha['verify_resume']);
	$smarty->assign('go_resume_show',$_GET['go_resume_show']);
	$smarty->display('member_personal/personal_manager_resume_make1.htm');
}
//�������� -���������Ϣ
elseif ($act=='make1_save')
{
	$captcha=get_cache('captcha');
	$postcaptcha = trim($_POST['postcaptcha']);
	if($captcha['verify_resume']=='1' && empty($postcaptcha) && intval($_REQUEST['pid'])===0)
	{
		showmsg("����д��֤��",1);
 	}
	if ($captcha['verify_resume']=='1' && intval($_REQUEST['pid'])===0 &&  strcasecmp($_SESSION['imageCaptcha_content'],$postcaptcha)!=0)
	{
		showmsg("��֤�����",1);
	}
	$setsqlarr['uid']=intval($_SESSION['uid']);
	$setsqlarr['title']=trim($_POST['title'])?trim($_POST['title']):showmsg('����д�������ƣ�',1);
	$setsqlarr['fullname']=trim($_POST['fullname'])?trim($_POST['fullname']):showmsg('����д������',1);
	$setsqlarr['sex']=trim($_POST['sex'])?intval($_POST['sex']):showmsg('��ѡ���Ա�',1);
	$setsqlarr['sex_cn']=trim($_POST['sex_cn']);
	$setsqlarr['birthdate']=intval($_POST['birthdate'])>1945?intval($_POST['birthdate']):showmsg('����ȷ��д�������',1);
	$setsqlarr['height']=intval($_POST['height']);
	$setsqlarr['marriage']=intval($_POST['marriage']);
	$setsqlarr['marriage_cn']=trim($_POST['marriage_cn']);
	$setsqlarr['experience']=intval($_POST['experience']);
	$setsqlarr['experience_cn']=trim($_POST['experience_cn']);
	$setsqlarr['householdaddress']=trim($_POST['householdaddress'])?trim($_POST['householdaddress']):showmsg('����д�������ڵأ�',1);	
	$setsqlarr['education']=intval($_POST['education']);
	$setsqlarr['education_cn']=trim($_POST['education_cn']);
	$setsqlarr['tag']=trim($_POST['tag']);
 	$setsqlarr['telephone']=trim($_POST['telephone'])?trim($_POST['telephone']):showmsg('����д��ϵ�绰��',1);
	$setsqlarr['email']=$user['email'];
	$setsqlarr['email_notify']=$_POST['email_notify']=="1"?1:0;
	$setsqlarr['address']=trim($_POST['address'])?trim($_POST['address']):showmsg('����дͨѶ��ַ��',1);
	$setsqlarr['website']=trim($_POST['website']);
	$setsqlarr['qq']=trim($_POST['qq']);
	$setsqlarr['refreshtime']=$timestamp;
	$setsqlarr['subsite_id']=intval($_CFG['subsite_id']);
	$setsqlarr['display_name']=intval($_CFG['resume_privacy']);	
	$setsqlarr['tpl']='manager';	
	if (intval($_REQUEST['pid'])===0)
	{	
			$setsqlarr['audit']=intval($_CFG['audit_resume']);
			$total=$db->get_total("SELECT COUNT(*) AS num FROM ".table('manager_resume')." WHERE uid='{$_SESSION['uid']}'");
			if ($total>=intval($_CFG['manager_resume_max']))
			{
			showmsg("�������Դ���{$_CFG['manager_resume_max']} �ݸ߼�����,�Ѿ�������������ƣ�",1);
			}
			else
			{
			$setsqlarr['addtime']=$timestamp;
			$pid=inserttable(table('manager_resume'),$setsqlarr,1);
			if (empty($pid))showmsg("����ʧ�ܣ�",0);	

			check_manager_resume($_SESSION['uid'],$pid);	

			write_memberslog($_SESSION['uid'],2,1501,$_SESSION['username'],"�����˸߼�����");
			header("Location: ?act=make2&pid=".$pid);
			}
	}
	else
	{
		$_CFG['audit_edit_resume']!="-1"?$setsqlarr['audit']=intval($_CFG['audit_edit_resume']):"";
		updatetable(table('manager_resume'),$setsqlarr," id='".intval($_REQUEST['pid'])."'  AND uid='{$setsqlarr['uid']}'");
		check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
		write_memberslog($_SESSION['uid'],2,1502,$_SESSION['username'],"�޸��˸߼�����({$_POST['title']})");
		header("Location: ?act=make2&pid={$_REQUEST['pid']}");
	}		
}
//��������-��ְ����
elseif ($act=='make2')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$link[0]['text'] = "���ؼ����б�";
	$link[0]['href'] = '?act=resume_list';
	if ($uid==0 || $pid==0) showmsg('���������ڣ�',1,$link);
			$resume_basic=get_manager_resume_basic($uid,$pid);
			$link[0]['text'] = "��д����������Ϣ";
			$link[0]['href'] = '?act=make1';
			if (empty($resume_basic)) showmsg("������д����������Ϣ��",1,$link);
			$smarty->assign('resume_basic',get_manager_resume_basic($uid,$pid));
 			$resume_jobs=get_manager_resume_jobs($pid);
			if ($resume_jobs)
			{
				foreach($resume_jobs as $rjob)
				{
				$jobsid[]=$rjob['category'].".".$rjob['subclass'];
				}
				$resume_jobs_id=implode("-",$jobsid);
			}
			$smarty->assign('resume_jobs_id',$resume_jobs_id);
	$smarty->assign('act',$act);
	$smarty->assign('pid',$pid);
	$smarty->assign('title','�ҵľ����˼��� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->assign('go_resume_show',$_GET['go_resume_show']);
	$smarty->assign('resume_education',get_manager_resume_education($uid,$pid));
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
	$smarty->display('member_personal/personal_manager_resume_make2.htm');
}
//����-��ְ����
elseif ($act=='make2_save')
{
	
	$resumeuid=intval($_SESSION['uid']);
	$resumepid=intval($_REQUEST['pid']);
	if ($resumeuid==0 || $resumepid==0 ) showmsg('��������',1);
	$resumearr['recentjobs']=trim($_POST['recentjobs']);
	$resumearr['district']=trim($_POST['district'])?intval($_POST['district']):showmsg('��ѡ�����������أ�',1);
	$resumearr['sdistrict']=intval($_POST['sdistrict']);
	$resumearr['district_cn']=trim($_POST['district_cn']);
	$resumearr['wage']=intval($_POST['wage'])?intval($_POST['wage']):showmsg('��ѡ��������н��',1);
	$resumearr['wage_cn']=trim($_POST['wage_cn']);
	$resumearr['trade']=$_POST['trade']?trim($_POST['trade']):showmsg('��ѡ���������µ���ҵ��',1);
	$resumearr['trade_cn']=trim($_POST['trade_cn']);
	$resumearr['intention_jobs']=trim($_POST['intention_jobs']);
	if ($_CFG['audit_edit_resume']!="-1")
	{
	$resumearr['audit']=$_CFG['audit_edit_resume'];
	}
	add_manager_resume_jobs($resumepid,$_SESSION['uid'],$_POST['intention_jobs_id'])?"":showmsg('����ʧ�ܣ�',0);
	updatetable(table('manager_resume'),$resumearr," id='{$resumepid}'  AND   uid='{$resumeuid}'");
	check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
	if ($_POST['go_resume_show'])
	{
		header("Location: ?act=resume_show&pid={$resumepid}");
	}
	else
	{
	header("Location: ?act=make3&pid=".intval($_POST['pid']));
	}
}
//��������-��������
elseif ($act=='make3')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$link[0]['text'] = "���ؼ����б�";
	$link[0]['href'] = '?act=resume_list';
	if ($uid==0 || $pid==0) showmsg('���������ڣ�',1,$link);
				$resume_basic=get_manager_resume_basic($uid,$pid);
				$link[0]['text'] = "��д����������Ϣ";
				$link[0]['href'] = '?act=make1';
				if (empty($resume_basic)) showmsg("������д����������Ϣ��",1,$link);
				$link[0]['text'] = "����д��ְ����";
				$link[0]['href'] = '?act=make2&pid='.$pid;
				if (empty($resume_basic['intention_jobs'])) showmsg("������д��ְ����",1,$link);
	$smarty->assign('resume_basic',$resume_basic);
 	$smarty->assign('act',$act);
	$smarty->assign('pid',$pid);
	$smarty->assign('title','�ҵļ��� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->assign('go_resume_show',$_GET['go_resume_show']);
	$smarty->assign('resume_education',get_manager_resume_education($uid,$pid));
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
	$smarty->display('member_personal/personal_manager_resume_make3.htm');
}
elseif ($act=='make3_save')
{
	
	if (intval($_POST['pid'])==0 ) showmsg('��������',1);
	$setsqlarrspecialty['specialty']=!empty($_POST['specialty'])?$_POST['specialty']:showmsg('����д���ļ����س���',1);
	$_CFG['audit_edit_resume']!="-1"?$setsqlarrspecialty['audit']=intval($_CFG['audit_edit_resume']):"";
	updatetable(table('manager_resume'),$setsqlarrspecialty," id='".intval($_POST['pid'])."' AND uid='".intval($_SESSION['uid'])."'");
	check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
	if ($_POST['go_resume_show'])
	{
		header("Location: ?act=resume_show&pid={$_POST['pid']}");
	}
	else
	{
		header("Location: ?act=make4&pid=".intval($_POST['pid']));
	}
}
//��������-��������
elseif ($act=='make4')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
 	$link[0]['text'] = "���ؼ����б�";
	$link[0]['href'] = '?act=resume_list';
	if ($uid==0 || $pid==0) showmsg('���������ڣ�',1,$link);
				$resume_basic=get_manager_resume_basic(intval($_SESSION['uid']),intval($_REQUEST['pid']));
				$link[0]['text'] = "��д����������Ϣ";
				$link[0]['href'] = '?act=make1';
				if (empty($resume_basic)) showmsg("������д����������Ϣ��",1,$link);
				$link[0]['text'] = "��д��ְ����";
				$link[0]['href'] = '?act=make2&pid='.intval($_REQUEST['pid']);
				if (empty($resume_basic['intention_jobs'])) showmsg("������д��ְ����",1,$link);
				$link[0]['text'] = "��д�����س�";
				$link[0]['href'] = '?act=make3&pid='.intval($_REQUEST['pid']);
				if (empty($resume_basic['specialty'])) showmsg("������д�����س���",1,$link);
	//
	$smarty->assign('resume_basic',$resume_basic);//������Ϣ	
	$smarty->assign('resume_education',get_manager_resume_education($uid,$pid));
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
 	$smarty->assign('act',$act);
	$smarty->assign('pid',$pid);
 	$smarty->assign('go_resume_show',$_GET['go_resume_show']);
	$smarty->assign('title','�ҵľ����˼��� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->display('member_personal/personal_manager_resume_make4.htm');
}
//��������-�����������
elseif ($act=='make4_save')
{
	$resume_education=get_manager_resume_education($_SESSION['uid'],$_REQUEST['pid']);
	if (count($resume_education)>=6) showmsg('�����������ܳ���6����',1,$link);
	$setsqlarr['uid']=intval($_SESSION['uid']);
	$setsqlarr['pid']=intval($_REQUEST['pid']);
 	if ($setsqlarr['uid']==0 || $setsqlarr['pid']==0 ) showmsg('��������',1);
	$setsqlarr['start']=trim($_POST['start'])?$_POST['start']:showmsg('����д��ʼʱ�䣡',1,$link);
	$setsqlarr['endtime']=trim($_POST['endtime'])?$_POST['endtime']:showmsg('����д����ʱ�䣡',1,$link);
	$setsqlarr['school']=trim($_POST['school'])?$_POST['school']:showmsg('����дѧУ���ƣ�',1,$link);
	$setsqlarr['speciality']=trim($_POST['speciality'])?$_POST['speciality']:showmsg('����дרҵ���ƣ�',1,$link);
	$setsqlarr['education']=trim($_POST['education'])?$_POST['education']:showmsg('��ѡ����ѧ����',1,$link);
	$setsqlarr['education_cn']=trim($_POST['education_cn'])?$_POST['education_cn']:showmsg('��ѡ����ѧ����',1,$link);
	if (inserttable(table('manager_resume_education'),$setsqlarr))
	{
		check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
		header("Location: ?act=make4&pid={$setsqlarr['pid']}");
	}
	else
	{
		showmsg("����ʧ�ܣ�",0,$link);
	}
		
}
//��������-�޸Ľ�������
elseif ($act=='edit_education')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$link[0]['text'] = "���ؼ����б�";
	$link[0]['href'] = '?act=resume_list';
	if ($uid==0 || $pid==0) showmsg('���������ڣ�',1,$link);
				$resume_basic=get_manager_resume_basic(intval($_SESSION['uid']),intval($_REQUEST['pid']));
				$link[0]['text'] = "��д����������Ϣ";
				$link[0]['href'] = '?act=make1';
				if (empty($resume_basic)) showmsg("������д����������Ϣ��",1,$link);
				$link[0]['text'] = "��д��ְ����";
				$link[0]['href'] = '?act=make2&pid='.intval($_REQUEST['pid']);
				if (empty($resume_basic['intention_jobs'])) showmsg("������д��ְ����",1,$link);
				$link[0]['text'] = "��д�����س�";
				$link[0]['href'] = '?act=make3&pid='.intval($_REQUEST['pid']);
				if (empty($resume_basic['specialty'])) showmsg("������д��ְ����",1,$link);
	//
	$smarty->assign('resume_basic',$resume_basic);	
 	$id=intval($_GET['id'])?intval($_GET['id']):showmsg('��������',1);
	$smarty->assign('act',$act);
	$smarty->assign('pid',$pid);
	$smarty->assign('go_resume_show',$_GET['go_resume_show']);
	$smarty->assign('education_edit',get_manager_resume_education_one($_SESSION['uid'],$id));
	$smarty->assign('resume_education',get_manager_resume_education($uid,$pid));
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
 	$smarty->assign('title','�༭���� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->display('member_personal/personal_manager_resume_education_edit.htm');
}
//�����޸ĵĽ�������
elseif ($act=='save_resume_education_edit')
{
	
	$id=trim($_POST['id'])?$_POST['id']:showmsg('��������',1);
	$setsqlarr['start']=trim($_POST['start'])?$_POST['start']:showmsg('����д��ʼʱ�䣡',1,$link);
	$setsqlarr['endtime']=trim($_POST['endtime'])?$_POST['endtime']:showmsg('����д����ʱ�䣡',1,$link);
	$setsqlarr['school']=trim($_POST['school'])?$_POST['school']:showmsg('����дѧУ���ƣ�',1,$link);
	$setsqlarr['speciality']=trim($_POST['speciality'])?$_POST['speciality']:showmsg('����дרҵ���ƣ�',1,$link);
	$setsqlarr['education']=trim($_POST['education'])?$_POST['education']:showmsg('��ѡ����ѧ����',1,$link);
	$setsqlarr['education_cn']=trim($_POST['education_cn'])?$_POST['education_cn']:showmsg('��ѡ����ѧ����',1,$link);
	if (updatetable(table('manager_resume_education'),$setsqlarr," id='{$id}' AND uid='{$_SESSION['uid']}'"))
		{
			if ($_POST['go_resume_show'])
			{
				header("Location: ?act=resume_show&pid={$_REQUEST['pid']}");
			}
			else
			{
			$link[0]['text'] = "������һҳ";
			$link[0]['href'] = "?act=make4&pid={$_REQUEST['pid']}";
			check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));	
			showmsg("�޸ĳɹ���",2,$link);
			}			
		}
		else
		{
		showmsg("����ʧ�ܣ�",0,$link);
		}
}
//��������-ɾ����������
elseif ($act=='del_education')
{
	 $id=intval($_GET['id']);
	 $sql="Delete from ".table('manager_resume_education')." WHERE id='{$id}'  AND uid='".intval($_SESSION['uid'])."' AND pid='".intval($_REQUEST['pid'])."' LIMIT 1 ";
	if ($db->query($sql))
	{
	check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));//���¼������״̬
	showmsg('ɾ���ɹ���',2);
	}
	else
	{
	showmsg('ɾ��ʧ�ܣ�',0);
	}	
}
//��������-��������
elseif ($act=='make5')
{
 	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$id=intval($_REQUEST['id']);
	
	$link[0]['text'] = "���ؼ����б�";
	$link[0]['href'] = '?act=resume_list';
	if ($uid==0 || $pid==0) showmsg('���������ڣ�',1,$link);
				$resume_basic=get_manager_resume_basic($uid,$pid);
				$link[0]['text'] = "��д����������Ϣ";
				$link[0]['href'] = '?act=make1';
				if (empty($resume_basic)) showmsg("������д����������Ϣ��",1,$link);
				$link[0]['text'] = "��д��ְ����";
				$link[0]['href'] = '?act=make2&pid='.$pid;
				if (empty($resume_basic['intention_jobs'])) showmsg("������д��ְ����",1,$link);
				$link[0]['text'] = "��д�����س�";
				$link[0]['href'] = '?act=make3&pid='.$pid;
				if (empty($resume_basic['specialty'])) showmsg("������д��ְ����",1,$link);
				$resume_education=get_manager_resume_education($uid,$pid);
				$link[0]['text'] = "��д������д��������";
				$link[0]['href'] = '?act=make4&pid='.$pid;
				if (empty($resume_education)) showmsg("������д����������",1,$link);
	$smarty->assign('resume_basic',$resume_basic);
	$smarty->assign('resume_education',get_manager_resume_education($uid,$pid));
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
 	$smarty->assign('act',$act);
	$smarty->assign('pid',$pid);
	$smarty->assign('id',$id);
	$smarty->assign('go_resume_show',$_GET['go_resume_show']);
	$smarty->assign('title','�ҵļ��� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->display('member_personal/personal_manager_resume_make5.htm');
}
//��������-������ӵĹ�������
elseif ($act=='make5_save')
{
	$resume_work=get_manager_resume_work($_SESSION['uid'],$_REQUEST['pid']);
	if (count($resume_work)>=10) showmsg('�����������ܳ���10����',1);
	$setsqlarr['uid']=intval($_SESSION['uid']);
	$setsqlarr['pid']=intval($_REQUEST['pid']);
 	if ($setsqlarr['pid']==0) showmsg('��������',1);
	$setsqlarr['start']=trim($_POST['start'])?$_POST['start']:showmsg('����д��ʼʱ�䣡',1,$link);
	$setsqlarr['endtime']=trim($_POST['endtime'])?$_POST['endtime']:showmsg('����д����ʱ�䣡',1,$link);
	$setsqlarr['companyname']=trim($_POST['companyname'])?$_POST['companyname']:showmsg('����д��ҵ���ƣ�',1,$link);
	$setsqlarr['jobs']=trim($_POST['jobs'])?$_POST['jobs']:showmsg('����дְλ���ƣ�',1,$link);
	$setsqlarr['companyprofile']=trim($_POST['companyprofile']);
	$setsqlarr['achievements']=trim($_POST['achievements']);
 		if (inserttable(table('manager_resume_work'),$setsqlarr))
		{
			check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
			header("Location: ?act=make5&pid={$setsqlarr['pid']}");
		}
		else
		{
			showmsg("����ʧ�ܣ�",0,$link);
		}
 }
elseif ($act=='del_work')
{
	$id=intval($_GET['id']);
	$sql="Delete from ".table('manager_resume_work')." WHERE id='".$id."' AND uid='".$_SESSION['uid']."' AND pid='".$_REQUEST['pid']."' LIMIT 1 ";
	if ($db->query($sql))
	{
	check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
	showmsg('ɾ���ɹ���',2);
	}
	else
	{
	showmsg('ɾ��ʧ�ܣ�',0);
	}
}
elseif ($act=='edit_work')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$link[0]['text'] = "���ؼ����б�";
	$link[0]['href'] = '?act=resume_list';
	if ($uid==0 || $pid==0) showmsg('���������ڣ�',1,$link);
				$resume_basic=get_manager_resume_basic(intval($_SESSION['uid']),intval($_REQUEST['pid']));
				$link[0]['text'] = "��д����������Ϣ";
				$link[0]['href'] = '?act=make1';
				if (empty($resume_basic)) showmsg("������д����������Ϣ��",1,$link);
				$link[0]['text'] = "��д��ְ����";
				$link[0]['href'] = '?act=make2&pid='.intval($_REQUEST['pid']);
				if (empty($resume_basic['intention_jobs'])) showmsg("������д��ְ����",1,$link);
				$link[0]['text'] = "��д�����س�";
				$link[0]['href'] = '?act=make3&pid='.intval($_REQUEST['pid']);
				if (empty($resume_basic['specialty'])) showmsg("������д��ְ����",1,$link);
	$id=intval($_GET['id']);
	//
	$smarty->assign('resume_basic',$resume_basic);
 	$smarty->assign('act',$act);
	$smarty->assign('pid',$pid);
	$smarty->assign('go_resume_show',$_GET['go_resume_show']);
	$smarty->assign('work_edit',get_manager_resume_work_one($_SESSION['uid'],$pid,$id));
	$smarty->assign('resume_education',get_manager_resume_education($uid,$pid));
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
	$smarty->assign('title','�༭���� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->display('member_personal/personal_manager_resume_work_edit.htm');
}
elseif ($act=='save_resume_work_edit')
{	
	$id=intval($_POST['id']);
	$setsqlarr['start']=trim($_POST['start'])?$_POST['start']:showmsg('����д��ʼʱ�䣡',1,$link);
	$setsqlarr['endtime']=trim($_POST['endtime'])?$_POST['endtime']:showmsg('����д����ʱ�䣡',1,$link);
	$setsqlarr['companyname']=trim($_POST['companyname'])?$_POST['companyname']:showmsg('����д��ҵ���ƣ�',1,$link);
	$setsqlarr['jobs']=trim($_POST['jobs'])?trim($_POST['jobs']):showmsg('����дְλ���ƣ�',1,$link);
	$setsqlarr['companyprofile']=trim($_POST['companyprofile']);
	$setsqlarr['achievements']=trim($_POST['achievements']);
	if (updatetable(table('manager_resume_work'),$setsqlarr," id='{$id}' AND uid='{$_SESSION['uid']}'"))
		{
			check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
			if ($_POST['go_resume_show'])
			{
				header("Location: ?act=resume_show&pid={$_REQUEST['pid']}");
			}
			else
			{
			$link[0]['text'] = "������һҳ";
			$link[0]['href'] = "?act=make5&pid={$_REQUEST['pid']}";
			showmsg("�޸ĳɹ���",2,$link);
			}
		}
		else
		{
		showmsg("����ʧ�ܣ�",0,$link);
		}
}


//��������-��Ŀ����
elseif ($act=='make6')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$id=intval($_REQUEST['id']);
	$link[0]['text'] = "���ؼ����б�";
	$link[0]['href'] = '?act=resume_list';
	if ($uid==0 || $pid==0) showmsg('���������ڣ�',1,$link);
				$resume_basic=get_manager_resume_basic($uid,$pid);
				$link[0]['text'] = "��д����������Ϣ";
				$link[0]['href'] = '?act=make1';
				if (empty($resume_basic)) showmsg("������д����������Ϣ��",1,$link);
				$link[0]['text'] = "��д��ְ����";
				$link[0]['href'] = '?act=make2&pid='.$pid;
				if (empty($resume_basic['intention_jobs'])) showmsg("������д��ְ����",1,$link);
				$link[0]['text'] = "��д�����س�";
				$link[0]['href'] = '?act=make3&pid='.$pid;
				if (empty($resume_basic['specialty'])) showmsg("������д��ְ����",1,$link);
				$resume_education=get_manager_resume_education($uid,$pid);
				$link[0]['text'] = "��д������д��������";
				$link[0]['href'] = '?act=make4&pid='.$pid;
				if (empty($resume_education)) showmsg("������д����������",1,$link);
					//
	$smarty->assign('resume_basic',$resume_basic);//������Ϣ	
	$smarty->assign('resume_education',get_manager_resume_education($uid,$pid));
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
 	$smarty->assign('act',$act);
	$smarty->assign('pid',$pid);
	$smarty->assign('id',$id);
	$smarty->assign('title','�ҵļ��� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->assign('go_resume_show',$_GET['go_resume_show']);
	$smarty->display('member_personal/personal_manager_resume_make6.htm');
}
//����-��ӵ���Ŀ����
elseif ($act=='make6_save')
{
	$resume_project=get_manager_resume_project($_SESSION['uid'],$_REQUEST['pid']);
	if (count($resume_project)>=8) showmsg('��ѵ�������ܳ���10����',1);
	$setsqlarr['uid']=intval($_SESSION['uid']);
	$setsqlarr['pid']=intval($_REQUEST['pid']);
 	if ($setsqlarr['uid']==0 || $setsqlarr['pid']==0 )  showmsg("��������",0,$link);
	$setsqlarr['start']=trim($_POST['start'])?$_POST['start']:showmsg('����д��ʼʱ�䣡',1,$link);
	$setsqlarr['endtime']=trim($_POST['endtime'])?$_POST['endtime']:showmsg('����д����ʱ�䣡',1,$link);
	$setsqlarr['projectname']=trim($_POST['projectname'])?$_POST['projectname']:showmsg('����д��Ŀ���ƣ�',1,$link);
	$setsqlarr['projectposition']=trim($_POST['projectposition'])?$_POST['projectposition']:showmsg('����д��Ŀְ��',1,$link);
	$setsqlarr['description']=trim($_POST['description'])?$_POST['description']:showmsg('����д��Ŀ������',1,$link);
	$setsqlarr['companyname']=trim($_POST['companyname']);
	$setsqlarr['projectduty']=trim($_POST['projectduty']);
	$setsqlarr['achievements']=trim($_POST['achievements']);
 		if (inserttable(table('manager_resume_project'),$setsqlarr))
		{
			check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
			header("Location: ?act=make6&pid={$setsqlarr['pid']}");
		}
		else
		{
			showmsg("����ʧ�ܣ�",0,$link);
		}
		
 }
//ɾ����Ŀ����
elseif ($act=='del_project')
{
	$id=!empty($_GET['id'])?intval($_GET['id']):showmsg('��������',1);
	$sql="Delete from ".table('manager_resume_project')." WHERE id='{$id}' AND uid='{$_SESSION['uid']}' AND pid='".intval($_REQUEST['pid'])."' LIMIT 1 ";
	if ($db->query($sql))
	{
	check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
	showmsg('ɾ���ɹ���',2);
	}
	else
	{
	showmsg('ɾ��ʧ�ܣ�',0);
	}
}
//�޸���ѵ����
elseif ($act=='edit_project')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$link[0]['text'] = "���ؼ����б�";
	$link[0]['href'] = '?act=resume_list';
	if ($uid==0 || $pid==0) showmsg('���������ڣ�',1,$link);
				$resume_basic=get_manager_resume_basic(intval($_SESSION['uid']),intval($_REQUEST['pid']));
				$link[0]['text'] = "��д����������Ϣ";
				$link[0]['href'] = '?act=make1';
				if (empty($resume_basic)) showmsg("������д����������Ϣ��",1,$link);

				$link[0]['text'] = "��д��ְ����";
				$link[0]['href'] = '?act=make2&pid='.intval($_REQUEST['pid']);
				if (empty($resume_basic['intention_jobs'])) showmsg("������д��ְ����",1,$link);
				$link[0]['text'] = "��д�����س�";
				$link[0]['href'] = '?act=make3&pid='.intval($_REQUEST['pid']);
				if (empty($resume_basic['specialty'])) showmsg("������д��ְ����",1,$link);
					//
	$smarty->assign('resume_basic',$resume_basic);	
 	$id=intval($_GET['id']);
	$smarty->assign('act',$act);
	$smarty->assign('pid',$pid);
	$smarty->assign('go_resume_show',$_GET['go_resume_show']);
	$smarty->assign('project_edit',get_manager_resume_project_one($_SESSION['uid'],$_REQUEST['pid'],$id));
	$smarty->assign('resume_education',get_manager_resume_education($uid,$pid));
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
	$smarty->assign('title','�༭���� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->display('member_personal/personal_manager_resume_project_edit.htm');
}
elseif ($act=='edit_project_save')
{
	$id=intval($_POST['id']);
	$setsqlarr['start']=trim($_POST['start'])?$_POST['start']:showmsg('����д��ʼʱ�䣡',1,$link);
	$setsqlarr['endtime']=trim($_POST['endtime'])?$_POST['endtime']:showmsg('����д����ʱ�䣡',1,$link);
	$setsqlarr['projectname']=trim($_POST['projectname'])?$_POST['projectname']:showmsg('����д��Ŀ���ƣ�',1,$link);
	$setsqlarr['projectposition']=trim($_POST['projectposition'])?$_POST['projectposition']:showmsg('����д��Ŀְ��',1,$link);
	$setsqlarr['description']=trim($_POST['description'])?$_POST['description']:showmsg('����д��Ŀ������',1,$link);
	$setsqlarr['companyname']=trim($_POST['companyname']);
	$setsqlarr['projectduty']=trim($_POST['projectduty']);
	$setsqlarr['achievements']=trim($_POST['achievements']);
		if (updatetable(table('manager_resume_project'),$setsqlarr," id='{$id}' AND uid='{$_SESSION['uid']}'"))
		{		
			check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
			if ($_POST['go_resume_show'])
			{
				header("Location: ?act=resume_show&pid={$_REQUEST['pid']}");
			}
			else
			{
			$link[0]['text'] = "������һҳ";
			$link[0]['href'] = "?act=make6&pid={$_REQUEST['pid']}";
			showmsg("�޸ĳɹ���",2,$link);
			}
		}else
		{
		showmsg("����ʧ�ܣ�",0,$link);
		}

 }

elseif ($act=='make7')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$smarty->assign('resume_basic',get_manager_resume_basic($uid,$pid));
	$smarty->assign('resume_education',get_manager_resume_education($uid,$pid));
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
	$smarty->assign('go_resume_show',$_GET['go_resume_show']);
	$smarty->assign('act',$act);
	$smarty->assign('pid',$pid);
	$smarty->assign('user',$user);
	$smarty->assign('title','�ҵĸ߼����� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->display('member_personal/personal_manager_resume_make7.htm');
}

elseif ($act=='make7_save')
{
 	
	$resumeuid=intval($_SESSION['uid']);
	$resumepid=intval($_REQUEST['pid']);
	if ($resumeuid==0 || $resumepid==0 ) showmsg('��������',1);
	$resumearr['language']=trim($_POST['language']);
	$resumearr['extra_message']=trim($_POST['extra_message']);
 	if ($_CFG['audit_edit_resume']!="-1")
	{
	$resumearr['audit']=$_CFG['audit_edit_resume'];
	}
	updatetable(table('manager_resume'),$resumearr," id='{$resumepid}'  AND   uid='{$resumeuid}'");
	check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
	if($_REQUEST['go_resume_show']){
		header("Location: ?act=resume_show&pid={$resumepid}");
	}else{
		header("Location: ?act=make8&pid={$resumepid}");
	}
}
elseif ($act=='make8')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$link[0]['text'] = "���ؼ����б�";
	$link[0]['href'] = '?act=resume_list';
	if ($uid==0 || $pid==0) showmsg('���������ڣ�',1,$link);
					$resume_basic=get_manager_resume_basic($uid,$pid);
					$link[0]['text'] = "��д����������Ϣ";
					$link[0]['href'] = '?act=make1';
					if (empty($resume_basic)) showmsg("������д����������Ϣ��",1,$link);
					$link[0]['text'] = "��д��ְ����";
					$link[0]['href'] = '?act=make2&pid='.$pid;
					if (empty($resume_basic['intention_jobs'])) showmsg("������д��ְ����",1,$link);
					$link[0]['text'] = "��д�����س�";
					$link[0]['href'] = '?act=make3&pid='.$pid;
					if (empty($resume_basic['specialty'])) showmsg("������д��ְ����",1,$link);
					$resume_education=get_manager_resume_education($uid,$pid);
					$link[0]['text'] = "��д������д��������";
					$link[0]['href'] = '?act=make4&pid='.$pid;
					if (empty($resume_education)) showmsg("������д����������",1,$link);
		 if ($resume_basic['photo_img'] && empty($_GET['addphoto']))
		 {
		 	header("Location: ?act=photo_cutting&pid=".$pid);
		 }
	$smarty->assign('resume_basic',$resume_basic);
	$smarty->assign('resume_education',$resume_education);
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
	$smarty->assign('act',$act);
	$smarty->assign('pid',$pid);
	$smarty->assign('step','0');
	$smarty->assign('title','�༭���� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->display('member_personal/personal_manager_resume_make8.htm');
}
elseif ($act=='make8_save')
{
	!$_FILES['photo']['name']?showmsg('���ϴ�ͼƬ��',1):"";
	require_once(QISHI_ROOT_PATH.'include/upload.php');
	if (intval($_REQUEST['pid'])==0) showmsg('��������',0);
	$resume_basic=get_manager_resume_basic(intval($_SESSION['uid']),intval($_REQUEST['pid']));
	if (empty($resume_basic['photo_img']))
	{
	$setsqlarr['photo_audit']=$_CFG['audit_resume_photo'];
	}
	else
	{
	$_CFG['audit_edit_photo']!="-1"?$setsqlarr['photo_audit']=intval($_CFG['audit_edit_photo']):"";
	}
	$photo_dir=substr($_CFG['manager_resume_photo_dir'],strlen($_CFG['site_dir']));
	$photo_dir="../../".$photo_dir.date("Y/m/d/");
 	make_dir($photo_dir);
	$setsqlarr['photo_img']=_asUpFiles($photo_dir, "photo",$_CFG['resume_photo_max'],'gif/jpg/bmp/png',true);
	$setsqlarr['photo_img']=date("Y/m/d/").$setsqlarr['photo_img'];
	!updatetable(table('manager_resume'),$setsqlarr," id='".intval($_REQUEST['pid'])."' AND uid='".intval($_SESSION['uid'])."'")?showmsg("����ʧ�ܣ�",0):'';
	check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
 	header("Location: ?act=photo_cutting&pid=".intval($_REQUEST['pid']));
}
//����-������Ƭ
elseif ($act=='photo_cutting')
{
					$uid=intval($_SESSION['uid']);
					$pid=intval($_REQUEST['pid']);
					$resume_basic=get_manager_resume_basic($uid,$pid);
					$link[0]['text'] = "��д����������Ϣ";
					$link[0]['href'] = '?act=make1';
					if (empty($resume_basic)) showmsg("������д����������Ϣ��",1,$link);
					$link[0]['text'] = "��д��ְ����";
					$link[0]['href'] = '?act=make2&pid='.$pid;
					if (empty($resume_basic['intention_jobs'])) showmsg("������д��ְ����",1,$link);
					$link[0]['text'] = "��д�����س�";
					$link[0]['href'] = '?act=make3&pid='.$pid;
					if (empty($resume_basic['specialty'])) showmsg("������д��ְ����",1,$link);
					$resume_education=get_manager_resume_education($uid,$pid);
					$link[0]['text'] = "��д������д��������";
					$link[0]['href'] = '?act=make4&pid='.$pid;
					if (empty($resume_education)) showmsg("������д����������",1,$link);
					if (empty($resume_basic['photo_img']))
					{
					header('Location: ?act=make8&pid='.$_REQUEST['pid']);
					}
	$photo_thumb_dir=QISHI_ROOT_PATH.substr($_CFG['manager_resume_photo_dir_thumb'],strlen($_CFG['site_dir']));
	make_dir($photo_thumb_dir.dirname($resume_basic['photo_img']));
	if (file_exists($photo_thumb_dir.$resume_basic['photo_img']))
	{
		$smarty->assign('resume_thumb_photo',$resume_basic['photo_img']);
	}
	$smarty->assign('resume_photo',$resume_basic['photo_img']);
	$smarty->assign('act','make8');
	$smarty->assign('step','1');
	$smarty->assign('pid',$_REQUEST['pid']);
	$smarty->assign('resume_basic',$resume_basic);
	$smarty->assign('resume_education',$resume_education);
	$smarty->assign('resume_work',get_manager_resume_work($uid,$pid));
	$smarty->assign('resume_project',get_manager_resume_project($uid,$pid));
	$smarty->assign('title','������Ƭ - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->display('member_personal/personal_manager_resume_photo_cutting.htm');
}
//����-������Ƭ
elseif ($act=='save_resume_photo_cutting')
{
	$resume_basic=get_manager_resume_basic(intval($_SESSION['uid']),intval($_REQUEST['pid']));
	if (empty($resume_basic)) showmsg("������д����������Ϣ��",0);
	require_once(QISHI_ROOT_PATH.'include/imageresize.class.php');
	$imgresize = new ImageResize();
	$photo_dir=QISHI_ROOT_PATH.substr($_CFG['manager_resume_photo_dir'],strlen($_CFG['site_dir']));
	$photo_thumb_dir=QISHI_ROOT_PATH.substr($_CFG['manager_resume_photo_dir_thumb'],strlen($_CFG['site_dir']));
	$imgresize->load($photo_dir.$resume_basic['photo_img']);
	$posary=explode(',', $_POST['cut_pos']);
	foreach($posary as $k=>$v) $posary[$k]=intval($v); 
	if($posary[2]>0 && $posary[3]>0) $imgresize->resize($posary[2], $posary[3]);
	$imgresize->cut(120,150, intval($posary[0]), intval($posary[1]));
	$imgresize->save($photo_thumb_dir.$resume_basic['photo_img']);
	header('Location: ?act=photo_cutting&show=ok&pid='.$_REQUEST['pid']);
}
elseif ($act=='edit_photo_display')
{
	check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
	header('Location: ?act=resume_show&pid='.intval($_REQUEST['pid']));
}
elseif ($act=='addcomplete')
{
					$uid=intval($_SESSION['uid']);
					$pid=intval($_REQUEST['pid']);
					$resume_basic=get_manager_resume_basic($uid,$pid);
					$link[0]['text'] = "��д����������Ϣ";
					$link[0]['href'] = '?act=make1';
					if (empty($resume_basic)) showmsg("������д����������Ϣ��",1,$link);
					$link[0]['text'] = "��д��ְ����";
					$link[0]['href'] = '?act=make2&pid='.$pid;
					if (empty($resume_basic['intention_jobs'])) showmsg("������д��ְ����",1,$link);
					$link[0]['text'] = "��д�����س�";
					$link[0]['href'] = '?act=make3&pid='.$pid;
					if (empty($resume_basic['specialty'])) showmsg("������д��ְ����",1,$link);
					$resume_education=get_manager_resume_education($uid,$pid);
					$link[0]['text'] = "��д������д��������";
					$link[0]['href'] = '?act=make4&pid='.$pid;
					if (empty($resume_education)) showmsg("������д����������",1,$link);
	$link[0]['text'] = "�鿴����";
	$link[0]['href'] ="?act=resume_show&pid={$pid}";
	$link[1]['text'] = "�����ҵļ���";
	$link[1]['href'] ="?act=resume_list";
	showmsg("������ɣ�",2,$link);
}
elseif ($act=='resume_privacy')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$resume_basic=get_manager_resume_basic($uid,$pid);
	if (empty($resume_basic)) showmsg("���������ڣ�",0);
	$smarty->assign('title','������˽���� - ���˻�Ա���� - '.$_CFG['site_name']);
	$smarty->assign('resume_basic',$resume_basic);
	$smarty->assign('pid',$pid);
	$smarty->display('member_personal/personal_managerresume_privacy.htm');
}
elseif ($act=='save_managerresume_privacy')
{
	$uid=intval($_SESSION['uid']);
	$pid=intval($_REQUEST['pid']);
	$setsqlarr['display']=intval($_POST['display']);
	$setsqlarr['display_name']=intval($_POST['display_name']);
	$setsqlarr['photo_display']=intval($_POST['photo_display']);
	$wheresql=" uid='".$_SESSION['uid']."' ";
	!updatetable(table('manager_resume'),$setsqlarr," uid='{$uid}' AND  id='{$pid}'")?showmsg("����ʧ�ܣ�",0):'';
	check_manager_resume($_SESSION['uid'],intval($_REQUEST['pid']));
	write_memberslog($_SESSION['uid'],2,1505,$_SESSION['username'],"���ø߼�������˽({$pid})");
	$link[0]['text'] = "�鿴����";
	$link[0]['href'] = '?act=resume_show&pid='.$pid;
	$link[1]['text'] = "��������";
	$link[1]['href'] = 'javascript:history.go(-1)';
	$link[2]['text'] = "���ؼ����б�";
	$link[2]['href'] = '?act=resume_list';
	showmsg('���óɹ���',2,$link);
}
//����������
if ($act=='down')
{
	$perpage=10;
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$wheresql=" WHERE d.resume_uid='{$_SESSION['uid']}' ";
	$resume_id =intval($_GET['resume_id']);
	if($resume_id>0)$wheresql.=" AND  d.resume_id='{$resume_id}' ";	
	$settr=intval($_GET['settr']);
	if($settr>0)
	{
	$settr_val=strtotime("-".$settr." day");
	$wheresql.=" AND d.down_addtime>".$settr_val;
	}
	$total_sql="SELECT COUNT(*) AS num from ".table('user_down_manager_resume')." AS d {$wheresql}";
	$total_val=$db->get_total($total_sql);
	$page = new page(array('total'=>$total_val, 'perpage'=>$perpage));
	$currenpage=$page->nowindex;
	$offset=($currenpage-1)*$perpage;
	$smarty->assign('title',"˭���ص��ҵļ��� - ���˻�Ա���� - {$_CFG['site_name']}");
	$smarty->assign('mylist',get_user_down_managerresume($offset,$perpage,$wheresql));
	$smarty->assign('page',$page->show(3));
	$smarty->assign('count',$total_val);
	$smarty->assign('act',$act);
	$smarty->assign('resume_list',get_audit_manager_resume_list($_SESSION['uid']));
	$smarty->display('member_personal/personal_down_manager_resume.htm');
}

unset($smarty);
?>