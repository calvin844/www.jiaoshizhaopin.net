<?php

/*
 * 74cms �����ʼ�
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
ignore_user_abort(true);
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$act = !empty($_GET['act']) ? trim($_GET['act']) : '';
$uid = intval($_GET['uid']);
$key = trim($_GET['key']);
if (empty($uid) || empty($key)) {
    exit("error");
}
$asyn_userkey = asyn_userkey($uid);
if ($asyn_userkey <> $key)
    exit("error");
$mailconfig = get_cache('mailconfig');
$mail_templates = get_cache('mail_templates');
//����ע���ʼ�
if ($act == 'reg') {
    if ($_GET['sendemail'] && $_GET['sendusername'] && $_GET['sendpassword'] && $mailconfig['set_reg'] == "1") {
        $userinfo = get_user_inid($uid);
        if ($userinfo['username'] == $_GET['sendusername'] && $userinfo['email'] == $_GET['sendemail']) {
            $templates = label_replace($mail_templates['set_reg']);
            $templates_title = label_replace($mail_templates['set_reg_title']);
            smtp_mail($_GET['sendemail'], $templates_title, $templates);
        }
    }
}
//����ְλ�����ʼ�
elseif ($act == 'jobs_apply') {
    $uid = intval($_GET['uid']);
    $jobsid = intval($_GET['jobs_id']);
    $resume = $db->getone("SELECT * FROM " . table('resume') . " WHERE uid = '" . $uid . "' LIMIT 1 ");
    $sql = "select * from " . table('resume_work') . " where pid='" . $resume['id'] . "' AND uid=" . $uid . "";
    $resume_work = $db->getall($sql);
    $jobs = $db->getone("SELECT * FROM " . table('jobs') . " WHERE id = '" . $jobsid . "' LIMIT 1 ");
    $jobs['contact'] = $db->getone("SELECT * FROM " . table('jobs_contact') . " WHERE pid = '" . $jobs['id'] . "' LIMIT 1 ");
    $sql = "select resume_type from " . table('personal_jobs_apply') . " WHERE personal_uid = '" . $uid . "' AND jobs_id='" . $jobsid . "'  AND resume_id='" . intval($resume['id']) . "' LIMIT 1";
    $job_apply = $db->getone($sql);
    $from_email = $resume['email'];
    $email = $_GET['email'];
    $word_content = "";
    $attachment_arr = "";
    $age = intval($resume['birthdate']) > 0 ? date('Y', time()) - $resume['birthdate'] : "δ��д";
    if (!empty($resume_work)) {
        foreach ($resume_work as $rw) {
            $word_content .= '<div style="width:100%;float:left;display:block;">';
            $word_content .= '<p>' . $rw['startyear'] . '.' . $rw['startmonth'] . '��' . $rw['endyear'] . '.' . $rw['endmonth'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['jobs'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['companyname'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['achievements'] . '</p>';
            $word_content .= '</div>';
        }
    }
    $content = '<style>
				a.logo {
					background:url("' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png") no-repeat;
					display: block;
					width: 160px;
					height: 44px;
					margin: 10px 40px;
					float: left;
				}
				.jl_peo_infor_li {
					float: left;
					width: 48.1%;
					padding-right: 9px;
					font-size: 14px;
					height: 30px;
					line-height: 30px;
					color: #333;
					overflow: hidden;
					zoom: 1;
				}
				.jl_peo_name {
					margin-bottom: 15px;
					float: left;
					color: #333;
					font-size: 28px;
					font-family: "΢���ź�";
					width: 400px;
					height: 28px;
					line-height: 28px;
					overflow: hidden;
					zoom: 1;
				}
			</style>
			<div style="background:#226cd6;height:4px;width:100%;"></div>
			<div style="height:65px;background:#fff;">
				<a title="��ʦ��Ƹ��" class="logo" href="' . $_CFG['main_domain'] . '"><img src="' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png" /></a>
				<div style="font-size:16px;padding:18px 0;">���յ�һ�����ԡ���ʦ��Ƹ�����ļ���Ͷ��</div>
			</div>
			<div style="background:#f5f5f5;padding:40px;">
				<div style="border-radius:4px;background:#fff;padding:20px;">
					<p>��ʦ��Ƹ�����й����Ľ�ʦ��Ƹרҵ��վ������<a href="' . $_CFG['main_domain'] . 'user/reg.php">ע��</a>��ѷ�����ʦְλ�������ʺţ���<a href="' . $_CFG['main_domain'] . 'user/login.php">��¼</a>��</p>
					<div style="margin-top:20px;border-top:1px dashed #ddd;padding:20px 0;">
						<p>������Ͷ�����ԣ�<a href="' . $_CFG['main_domain'] . 'job/' . $jobs['id'] . '.html">' . $jobs['jobs_name'] . '</a></p>
						<p>����˵����' . utf8_to_gbk($note) . '</p>
					</div>
					<div style="border-top:1px dashed #ddd;padding:20px 0;display:inline-block;">
							<div style="width:100%;float:left;display:block;">
								<div class="jl_peo_name">
                                                                    ' . $resume['fullname'] . '
								</div>
							</div>
							<div class="jl_peo_infor_li">
								<label>�ꡡ���䣺</label><span>' . $age . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>�ԡ�����</label><span>' . $resume['sex_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>���ѧ����</label><span>' . $resume['education_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>�������飺</label><span>' . $resume['experience_cn'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>��ϵ��ʽ��</label><span>' . $resume['telephone'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>�������䣺</label><span>' . $resume['email'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>����������</label><span>' . $resume['district_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>����н�꣺</label><span>' . $resume['wage_cn'] . '</span>
							</div>
							<div style="width:100%;float:left;display:block;">
                                                            <div class="jl_peo_infor_li"><b>��������</b></div>
                                                            <div class="jl_peo_infor_li"></div>
                                                            ' . $word_content . '
                                                        </div>
							<div style="width:100%;float:left;display:block;">
                                                            <div class="jl_peo_infor_li"><b>���ҽ���</b></div>
                                                            <div class="jl_peo_infor_li"></div>
                                                            <p style="width:100%;float:left;display:block;">
                                                            ' . $resume['specialty'] . '
                                                            </p>
                                                            <div class="jl_peo_infor_li"></div>
                                                        </div>
							<div style="clear:both;"></div>
							<div style="width:100%;float:left;display:block;">
                                                            <a href="' . $_CFG['main_domain'] . 'user/company/company_recruitment.php?act=apply_jobs" style="text-decoration:none;background:#F87C10;padding:12px 20px;color:#fff;margin-top:20px;">�鿴��������</a>
                                                        </div>
					</div>
				</div>
			</div>
			
			';
    $attachment_content = '<style>
				a.logo {
					background:url("' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png") no-repeat;
					display: block;
					width: 160px;
					height: 44px;
					margin: 10px 40px;
					float: left;
				}
				.jl_peo_infor_li {
					float: left;
					width: 48.1%;
					padding-right: 9px;
					font-size: 14px;
					height: 30px;
					line-height: 30px;
					color: #333;
					overflow: hidden;
					zoom: 1;
				}
				.jl_peo_name {
					margin-bottom: 15px;
					float: left;
					color: #333;
					font-size: 28px;
					font-family: "΢���ź�";
					width: 400px;
					height: 28px;
					line-height: 28px;
					overflow: hidden;
					zoom: 1;
				}
			</style>
			<div style="background:#226cd6;height:4px;width:100%;"></div>
			<div style="height:65px;background:#fff;">
				<a title="��ʦ��Ƹ��" class="logo" href="' . $_CFG['main_domain'] . '"><img src="' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png" /></a>
				<div style="font-size:16px;padding:18px 0;">���յ�һ�����ԡ���ʦ��Ƹ�����ļ���Ͷ��</div>
			</div>
			<div style="background:#f5f5f5;padding:40px;">
				<div style="border-radius:4px;background:#fff;padding:20px;">
					<p>��ʦ��Ƹ�����й����Ľ�ʦ��Ƹרҵ��վ������<a href="' . $_CFG['main_domain'] . 'user/reg.php">ע��</a>��ѷ�����ʦְλ�������ʺţ���<a href="' . $_CFG['main_domain'] . 'user/login.php">��¼</a>��</p>
					<div style="margin-top:20px;border-top:1px dashed #ddd;padding:20px 0;">
						<p>������Ͷ�����ԣ�<a href="' . $_CFG['main_domain'] . 'job/' . $jobs['id'] . '.html">' . $jobs['jobs_name'] . '</a></p>
						<p>����˵����' . utf8_to_gbk($note) . '</p>
					</div>
					<div style="border-top:1px dashed #ddd;padding:20px 0;display:inline-block;">
							<div style="width:100%;float:left;display:block;">
								<div class="jl_peo_name">
									' . $resume['fullname'] . '
								</div>
							</div>
							<div class="jl_peo_infor_li">
								<label>�ꡡ���䣺</label><span>' . $age . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>�ԡ�����</label><span>' . $resume['sex_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>���ѧ����</label><span>' . $resume['education_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>�������飺</label><span>' . $resume['experience_cn'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>��ϵ��ʽ��</label><span>' . $resume['telephone'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>�������䣺</label><span>' . $resume['email'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>����������</label><span>' . $resume['district_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>����н�꣺</label><span>' . $resume['wage_cn'] . '</span>
							</div>
							<div style="width:100%;float:left;display:block;">
                                                            <p style="font-size:16px; color:red; font-weight:bold; text-align:left;">�������������ظ����鿴</p>
                                                        </div>
							<div style="width:100%;float:left;display:block;">
                                                            <a href="' . $_CFG['main_domain'] . 'user/company/company_recruitment.php?act=apply_jobs" style="text-decoration:none;background:#F87C10;padding:12px 20px;color:#fff;margin-top:20px;">�鿴�������</a>
                                                        </div>
					</div>
				</div>
			</div>
			
			';
    if ($job_apply['resume_type'] == 1) {
        $content = $attachment_content;
        $sql = "select * from " . table("resume_attachment") . " where uid = " . $uid . " limit 1";
        $attachment = $db->getone($sql);
        $attachment_arr[0]['path'] = '..' . $attachment['path'] . $attachment['file_name'];
        $attachment_arr[0]['name'] = $attachment['file_name'];
    }
    $success = smtp_mail($email, $resume['fullname'] . '-ӦƸ' . $jobs['jobs_name'], $content, $from_email, '��ʦ��Ƹ��', $attachment_arr);
}
//�������Է����ʼ�
elseif ($act == 'set_invite') {
    $templates = label_replace($mail_templates['set_invite']);
    $templates_title = label_replace($mail_templates['set_invite_title']);
    smtp_mail($_GET['email'], $templates_title, $templates);
}
//�����ֵ�������ʼ�
elseif ($act == 'set_order') {
    $templates = label_replace($mail_templates['set_order']);
    $templates_title = label_replace($mail_templates['set_order_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//��ֵ�ɹ��������ʼ�
elseif ($act == 'set_payment') {
    $templates = label_replace($mail_templates['set_payment']);
    $templates_title = label_replace($mail_templates['set_payment_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//�޸����룬�����ʼ�
elseif ($act == 'set_editpwd') {
    $templates = label_replace($mail_templates['set_editpwd']);
    $templates_title = label_replace($mail_templates['set_editpwd_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//ְλ���ͨ���������ʼ�
elseif ($act == 'set_jobsallow') {
    $templates = label_replace($mail_templates['set_jobsallow']);
    $templates_title = label_replace($mail_templates['set_jobsallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//ְλδ���ͨ���������ʼ�
elseif ($act == 'set_jobsnotallow') {
    $templates = label_replace($mail_templates['set_jobsnotallow']);
    $templates_title = label_replace($mail_templates['set_jobsnotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//��ҵ��֤ͨ���������ʼ�
elseif ($act == 'set_licenseallow') {
    $templates = label_replace($mail_templates['set_licenseallow']);
    $templates_title = label_replace($mail_templates['set_licenseallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//��ҵ��֤δͨ���������ʼ�
elseif ($act == 'set_licensenotallow') {
    $templates = label_replace($mail_templates['set_licensenotallow']);
    $templates_title = label_replace($mail_templates['set_licensenotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//��ҵ�����ر��Ƽ��������ʼ�
elseif ($act == 'set_addmap') {
    $templates = label_replace($mail_templates['set_addmap']);
    $templates_title = label_replace($mail_templates['set_addmap_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//����ͨ����ˣ������ʼ�
elseif ($act == 'set_resumeallow') {
    $templates = label_replace($mail_templates['set_resumeallow']);
    $templates_title = label_replace($mail_templates['set_resumeallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//����δͨ����ˣ������ʼ�
elseif ($act == 'set_resumenotallow') {
    $templates = label_replace($mail_templates['set_resumenotallow']);
    $templates_title = label_replace($mail_templates['set_resumenotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//��ʦͨ����ˣ������ʼ�
elseif ($act == 'set_teaallow') {
    $templates = label_replace($mail_templates['set_teaallow']);
    $templates_title = label_replace($mail_templates['set_teaallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//��ʦδͨ����ˣ������ʼ�
elseif ($act == 'set_teanotallow') {
    $templates = label_replace($mail_templates['set_teanotallow']);
    $templates_title = label_replace($mail_templates['set_teanotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//�γ�ͨ����ˣ������ʼ�
elseif ($act == 'set_couallow') {
    $templates = label_replace($mail_templates['set_couallow']);
    $templates_title = label_replace($mail_templates['set_couallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//�γ�δͨ����ˣ������ʼ�
elseif ($act == 'set_counotallow') {
    $templates = label_replace($mail_templates['set_counotallow']);
    $templates_title = label_replace($mail_templates['set_counotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//������������γ̣������ʼ�
elseif ($act == 'set_applycou') {
    $templates = label_replace($mail_templates['set_applycou']);
    $templates_title = label_replace($mail_templates['set_applycou_title']);
    smtp_mail($_GET['email'], $templates_title, $templates);
}
//�����������룬�����ʼ�
elseif ($act == 'set_downapp') {
    $templates = label_replace($mail_templates['set_downapp']);
    $templates_title = label_replace($mail_templates['set_downapp_title']);
    smtp_mail($_GET['email'], $templates_title, $templates);
}
//��ͷͨ����ˣ������ʼ�
elseif ($act == 'set_hunallow') {
    $templates = label_replace($mail_templates['set_hunallow']);
    $templates_title = label_replace($mail_templates['set_hunallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//��ͷδͨ����ˣ������ʼ�
elseif ($act == 'set_hunnotallow') {
    $templates = label_replace($mail_templates['set_hunnotallow']);
    $templates_title = label_replace($mail_templates['set_hunnotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//�߼�ְλͨ����ˣ������ʼ�
elseif ($act == 'set_hunjobsallow') {
    $templates = label_replace($mail_templates['set_hunjobsallow']);
    $templates_title = label_replace($mail_templates['set_hunjobsallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//�߼�ְλδͨ����ˣ������ʼ�
elseif ($act == 'set_hunjobsnotallow') {
    $templates = label_replace($mail_templates['set_hunjobsnotallow']);
    $templates_title = label_replace($mail_templates['set_hunjobsnotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
?>