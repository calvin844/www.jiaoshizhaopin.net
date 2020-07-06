<?php

/*
 * 74cms 发送邮件
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
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
//发送注册邮件
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
//申请职位发送邮件
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
    $age = intval($resume['birthdate']) > 0 ? date('Y', time()) - $resume['birthdate'] : "未填写";
    if (!empty($resume_work)) {
        foreach ($resume_work as $rw) {
            $word_content .= '<div style="width:100%;float:left;display:block;">';
            $word_content .= '<p>' . $rw['startyear'] . '.' . $rw['startmonth'] . '至' . $rw['endyear'] . '.' . $rw['endmonth'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['jobs'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['companyname'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['achievements'] . '</p>';
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
					font-family: "微软雅黑";
					width: 400px;
					height: 28px;
					line-height: 28px;
					overflow: hidden;
					zoom: 1;
				}
			</style>
			<div style="background:#226cd6;height:4px;width:100%;"></div>
			<div style="height:65px;background:#fff;">
				<a title="教师招聘网" class="logo" href="' . $_CFG['main_domain'] . '"><img src="' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png" /></a>
				<div style="font-size:16px;padding:18px 0;">您收到一份来自“教师招聘网”的简历投递</div>
			</div>
			<div style="background:#f5f5f5;padding:40px;">
				<div style="border-radius:4px;background:#fff;padding:20px;">
					<p>教师招聘网，中国最大的教师招聘专业网站，立即<a href="' . $_CFG['main_domain'] . 'user/reg.php">注册</a>免费发布教师职位。已有帐号，请<a href="' . $_CFG['main_domain'] . 'user/login.php">登录</a>。</p>
					<div style="margin-top:20px;border-top:1px dashed #ddd;padding:20px 0;">
						<p>本简历投递来自：<a href="' . $_CFG['main_domain'] . 'job/' . $jobs['id'] . '.html">' . $jobs['jobs_name'] . '</a></p>
						<p>附加说明：' . utf8_to_gbk($note) . '</p>
					</div>
					<div style="border-top:1px dashed #ddd;padding:20px 0;display:inline-block;">
							<div style="width:100%;float:left;display:block;">
								<div class="jl_peo_name">
                                                                    ' . $resume['fullname'] . '
								</div>
							</div>
							<div class="jl_peo_infor_li">
								<label>年　　龄：</label><span>' . $age . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>性　　别：</label><span>' . $resume['sex_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>最高学历：</label><span>' . $resume['education_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>工作经验：</label><span>' . $resume['experience_cn'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>联系方式：</label><span>' . $resume['telephone'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>电子邮箱：</label><span>' . $resume['email'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>期望地区：</label><span>' . $resume['district_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>期望薪酬：</label><span>' . $resume['wage_cn'] . '</span>
							</div>
							<div style="width:100%;float:left;display:block;">
                                                            <div class="jl_peo_infor_li"><b>工作经历</b></div>
                                                            <div class="jl_peo_infor_li"></div>
                                                            ' . $word_content . '
                                                        </div>
							<div style="width:100%;float:left;display:block;">
                                                            <div class="jl_peo_infor_li"><b>自我介绍</b></div>
                                                            <div class="jl_peo_infor_li"></div>
                                                            <p style="width:100%;float:left;display:block;">
                                                            ' . $resume['specialty'] . '
                                                            </p>
                                                            <div class="jl_peo_infor_li"></div>
                                                        </div>
							<div style="clear:both;"></div>
							<div style="width:100%;float:left;display:block;">
                                                            <a href="' . $_CFG['main_domain'] . 'user/company/company_recruitment.php?act=apply_jobs" style="text-decoration:none;background:#F87C10;padding:12px 20px;color:#fff;margin-top:20px;">查看完整简历</a>
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
					font-family: "微软雅黑";
					width: 400px;
					height: 28px;
					line-height: 28px;
					overflow: hidden;
					zoom: 1;
				}
			</style>
			<div style="background:#226cd6;height:4px;width:100%;"></div>
			<div style="height:65px;background:#fff;">
				<a title="教师招聘网" class="logo" href="' . $_CFG['main_domain'] . '"><img src="' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png" /></a>
				<div style="font-size:16px;padding:18px 0;">您收到一份来自“教师招聘网”的简历投递</div>
			</div>
			<div style="background:#f5f5f5;padding:40px;">
				<div style="border-radius:4px;background:#fff;padding:20px;">
					<p>教师招聘网，中国最大的教师招聘专业网站，立即<a href="' . $_CFG['main_domain'] . 'user/reg.php">注册</a>免费发布教师职位。已有帐号，请<a href="' . $_CFG['main_domain'] . 'user/login.php">登录</a>。</p>
					<div style="margin-top:20px;border-top:1px dashed #ddd;padding:20px 0;">
						<p>本简历投递来自：<a href="' . $_CFG['main_domain'] . 'job/' . $jobs['id'] . '.html">' . $jobs['jobs_name'] . '</a></p>
						<p>附加说明：' . utf8_to_gbk($note) . '</p>
					</div>
					<div style="border-top:1px dashed #ddd;padding:20px 0;display:inline-block;">
							<div style="width:100%;float:left;display:block;">
								<div class="jl_peo_name">
									' . $resume['fullname'] . '
								</div>
							</div>
							<div class="jl_peo_infor_li">
								<label>年　　龄：</label><span>' . $age . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>性　　别：</label><span>' . $resume['sex_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>最高学历：</label><span>' . $resume['education_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>工作经验：</label><span>' . $resume['experience_cn'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>联系方式：</label><span>' . $resume['telephone'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>电子邮箱：</label><span>' . $resume['email'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>期望地区：</label><span>' . $resume['district_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>期望薪酬：</label><span>' . $resume['wage_cn'] . '</span>
							</div>
							<div style="width:100%;float:left;display:block;">
                                                            <p style="font-size:16px; color:red; font-weight:bold; text-align:left;">简历详情请下载附件查看</p>
                                                        </div>
							<div style="width:100%;float:left;display:block;">
                                                            <a href="' . $_CFG['main_domain'] . 'user/company/company_recruitment.php?act=apply_jobs" style="text-decoration:none;background:#F87C10;padding:12px 20px;color:#fff;margin-top:20px;">查看更多简历</a>
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
    $success = smtp_mail($email, $resume['fullname'] . '-应聘' . $jobs['jobs_name'], $content, $from_email, '教师招聘网', $attachment_arr);
}
//邀请面试发送邮件
elseif ($act == 'set_invite') {
    $templates = label_replace($mail_templates['set_invite']);
    $templates_title = label_replace($mail_templates['set_invite_title']);
    smtp_mail($_GET['email'], $templates_title, $templates);
}
//申请充值，发送邮件
elseif ($act == 'set_order') {
    $templates = label_replace($mail_templates['set_order']);
    $templates_title = label_replace($mail_templates['set_order_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//充值成功，发送邮件
elseif ($act == 'set_payment') {
    $templates = label_replace($mail_templates['set_payment']);
    $templates_title = label_replace($mail_templates['set_payment_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//修改密码，发送邮件
elseif ($act == 'set_editpwd') {
    $templates = label_replace($mail_templates['set_editpwd']);
    $templates_title = label_replace($mail_templates['set_editpwd_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//职位审核通过，发送邮件
elseif ($act == 'set_jobsallow') {
    $templates = label_replace($mail_templates['set_jobsallow']);
    $templates_title = label_replace($mail_templates['set_jobsallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//职位未审核通过，发送邮件
elseif ($act == 'set_jobsnotallow') {
    $templates = label_replace($mail_templates['set_jobsnotallow']);
    $templates_title = label_replace($mail_templates['set_jobsnotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//企业认证通过，发送邮件
elseif ($act == 'set_licenseallow') {
    $templates = label_replace($mail_templates['set_licenseallow']);
    $templates_title = label_replace($mail_templates['set_licenseallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//企业认证未通过，发送邮件
elseif ($act == 'set_licensenotallow') {
    $templates = label_replace($mail_templates['set_licensenotallow']);
    $templates_title = label_replace($mail_templates['set_licensenotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//企业加入特别推荐，发送邮件
elseif ($act == 'set_addmap') {
    $templates = label_replace($mail_templates['set_addmap']);
    $templates_title = label_replace($mail_templates['set_addmap_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//简历通过审核，发送邮件
elseif ($act == 'set_resumeallow') {
    $templates = label_replace($mail_templates['set_resumeallow']);
    $templates_title = label_replace($mail_templates['set_resumeallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//简历未通过审核，发送邮件
elseif ($act == 'set_resumenotallow') {
    $templates = label_replace($mail_templates['set_resumenotallow']);
    $templates_title = label_replace($mail_templates['set_resumenotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//讲师通过审核，发送邮件
elseif ($act == 'set_teaallow') {
    $templates = label_replace($mail_templates['set_teaallow']);
    $templates_title = label_replace($mail_templates['set_teaallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//讲师未通过审核，发送邮件
elseif ($act == 'set_teanotallow') {
    $templates = label_replace($mail_templates['set_teanotallow']);
    $templates_title = label_replace($mail_templates['set_teanotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//课程通过审核，发送邮件
elseif ($act == 'set_couallow') {
    $templates = label_replace($mail_templates['set_couallow']);
    $templates_title = label_replace($mail_templates['set_couallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//课程未通过审核，发送邮件
elseif ($act == 'set_counotallow') {
    $templates = label_replace($mail_templates['set_counotallow']);
    $templates_title = label_replace($mail_templates['set_counotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//个人在线申请课程，发送邮件
elseif ($act == 'set_applycou') {
    $templates = label_replace($mail_templates['set_applycou']);
    $templates_title = label_replace($mail_templates['set_applycou_title']);
    smtp_mail($_GET['email'], $templates_title, $templates);
}
//机构下载申请，发送邮件
elseif ($act == 'set_downapp') {
    $templates = label_replace($mail_templates['set_downapp']);
    $templates_title = label_replace($mail_templates['set_downapp_title']);
    smtp_mail($_GET['email'], $templates_title, $templates);
}
//猎头通过审核，发送邮件
elseif ($act == 'set_hunallow') {
    $templates = label_replace($mail_templates['set_hunallow']);
    $templates_title = label_replace($mail_templates['set_hunallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//猎头未通过审核，发送邮件
elseif ($act == 'set_hunnotallow') {
    $templates = label_replace($mail_templates['set_hunnotallow']);
    $templates_title = label_replace($mail_templates['set_hunnotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//高级职位通过审核，发送邮件
elseif ($act == 'set_hunjobsallow') {
    $templates = label_replace($mail_templates['set_hunjobsallow']);
    $templates_title = label_replace($mail_templates['set_hunjobsallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
//高级职位未通过审核，发送邮件
elseif ($act == 'set_hunjobsnotallow') {
    $templates = label_replace($mail_templates['set_hunjobsnotallow']);
    $templates_title = label_replace($mail_templates['set_hunjobsnotallow_title']);
    $useremail = get_user_inid($uid);
    smtp_mail($useremail['email'], $templates_title, $templates);
}
?>