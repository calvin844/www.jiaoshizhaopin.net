<?php

/*
 * 74cms 计划任务 清除缓存
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if (!defined('IN_QISHI')) {
    die('Access Denied!');
}

require_once('../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');

global $_CFG;
$start_id = intval($_GET['start_id']) > 0 ? intval($_GET['start_id']) : 0;
$end_id = "";
$sql = "select e.*,r.category,r.subclass,r.district,r.sdistrict,r.pid from " . table('resume_entrust') . " as e left join " . table('resume_jobs') . " as r on e.id=r.pid where e.id>" . $start_id . " limit 100";
$result = $db->getall($sql);
if (!empty($result)) {
    foreach ($result as $row) {
        if (empty($row['pid'])) {
            update_entrust($row['id']);
            continue;
        }
        if ($row['subclass'] == 0) {
            if (!empty($row['category'])) {
                $c_str = " category = " . $row['category'];
            }
        } else {
            if (!empty($row['subclass'])) {
                $c_str = " subclass = " . $row['subclass'];
            }
        }
        if ($row['sdistrict'] == 0) {
            if (!empty($row['district'])) {
                $d_str = " and district = " . $row['district'];
            }
        } else {
            if (!empty($row['sdistrict'])) {
                $d_str = " and sdistrict = " . $row['sdistrict'];
            }
        }
        if (empty($d_str) || empty($c_str)) {
            update_entrust($row['pid']);
            continue;
        }
        $sql = "select id,jobs_name,company_id,companyname,uid from " . table('jobs') . " where uid>0 and company_audit=1 and deadline > " . time() . " and " . $c_str . $d_str . " limit 10";
        $jobs = $db->getall($sql);
        if (empty($jobs)) {
            continue;
        } else {
            $end_id = $row['pid'];
            $resume_basic = $db->getone("select uid,id,display_name,fullname from " . table('resume') . " where id=" . $row['pid']);
            foreach ($jobs as $key => $value) {
                if (check_jobs_apply($value['id'], $row['pid'], $row['uid'])) {
                    continue;
                }
                if ($resume_basic['display_name'] == "2") {
                    $personal_fullname = "N" . str_pad($resume_basic['id'], 7, "0", STR_PAD_LEFT);
                } elseif ($resume_basic['display_name'] == "3") {
                    $personal_fullname = cut_str($resume_basic['fullname'], 1, 0, "**");
                } else {
                    $personal_fullname = $resume_basic['fullname'];
                }
                send_email($resume_basic['uid'], $value['id']);
                $addarr['resume_id'] = $resume_basic['id'];
                $addarr['resume_name'] = $personal_fullname;
                $addarr['personal_uid'] = intval($row['uid']);
                $addarr['jobs_id'] = $value['id'];
                $addarr['jobs_name'] = $value['jobs_name'];
                $addarr['company_id'] = $value['company_id'];
                $addarr['company_name'] = $value['companyname'];
                $addarr['company_uid'] = $value['uid'];
                $addarr['notes'] = "系统自动投递";
                $addarr['apply_addtime'] = time();
                $addarr['personal_look'] = 1;
                inserttable(table('personal_jobs_apply'), $addarr);
            }
            update_entrust($row['pid']);
        }
        $db->query("UPDATE " . table('jobs_search_wage') . " SET refreshtime='{$time}' WHERE id='{$jobs['id']}' LIMIT 1");
    }
}
if (!empty($end_id)) {
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $html.='<html xmlns="http://www.w3.org/1999/xhtml">';
    $html.='<head>';
    $html.='</head>';
    $html.='<body>';
    $html.='<script type="text/javascript" language="javascript">';
    $html.='function reloadyemian()';
    $html.='{ ';
    $html.='window.location.href="http://' . $_SERVER['HTTP_HOST'] . '/user/do_crons.php?' . 'id=9&start_id=' . $end_id . '"';
    $html.='} ';
    $html.='window.setTimeout("reloadyemian();",3000); ';
    $html.='</script> ';
    $html.='</body></html>';
    echo $html;
}

/**
  //更新任务时间表
  if ($crons['weekday'] >= 0) {
  $weekday = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
  $nextrun = strtotime("Next " . $weekday[$crons['weekday']]);
  } elseif ($crons['day'] > 0) {
  $nextrun = strtotime('+1 months');
  $nextrun = mktime(0, 0, 0, date("m", $nextrun), $crons['day'], date("Y", $nextrun));
  } else {
  $nextrun = time();
  }
  if ($crons['hour'] >= 0) {
  $nextrun = strtotime('+1 days', $nextrun);
  $nextrun = mktime($crons['hour'], 0, 0, date("m", $nextrun), date("d", $nextrun), date("Y", $nextrun));
  }
  if (intval($crons['minute']) > 0) {
  $nextrun = strtotime('+1 hours', $nextrun);
  $nextrun = mktime(date("H", $nextrun), $crons['minute'], 0, date("m", $nextrun), date("d", $nextrun), date("Y", $nextrun));
  }
  $setsqlarr['nextrun'] = $nextrun;
  $setsqlarr['lastrun'] = time();
  updatetable(table('crons'), $setsqlarr, " cronid ='" . intval($crons['cronid']) . "'");
 * 
 */
function check_jobs_apply($jobs_id, $resume_id, $p_uid) {
    global $db;
    $sql = "select did from " . table('personal_jobs_apply') . " WHERE personal_uid = '" . intval($p_uid) . "' AND jobs_id='" . intval($jobs_id) . "'  AND resume_id='" . intval($resume_id) . "' LIMIT 1";
    return $db->getone($sql);
}

function update_entrust($id) {
    global $db;
    $db->query("delete from " . table('resume_entrust') . " where id=" . $id);
    updatetable(table("resume"), array("entrust" => "0"), " id=" . $id . " ");
}

function send_email($uid, $jobsid) {
    global $db, $_CFG;
    $resume = $db->getone("SELECT * FROM " . table('resume') . " WHERE uid = '" . $uid . "' LIMIT 1 ");
    $sql = "select * from " . table('resume_work') . " where pid='" . $resume['id'] . "' AND uid=" . $uid . "";
    $resume_work = $db->getall($sql);
    $jobs = $db->getone("SELECT * FROM " . table('jobs') . " WHERE id = '" . $jobsid . "' LIMIT 1 ");
    $jobs['contact'] = $db->getone("SELECT * FROM " . table('jobs_contact') . " WHERE pid = '" . $jobs['id'] . "' LIMIT 1 ");
    $from_email = $resume['email'];
    $email = $jobs['contact']['email'];
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
					background:url(../images/header_logo.png) no-repeat;
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
                                                            <a href="' . $_CFG['wap_domain'] . '/resume/view_full_resume?article_id=' . $article['id'] . '&resume_id=' . $resume['id'] . '&redirect_url=' . $_CFG['main_domain'] . 'user/company/company_recruitment.php?act=apply_jobs" style="text-decoration:none;background:#F87C10;padding:12px 20px;color:#fff;margin-top:20px;">查看完整简历</a>
                                                        </div>
					</div>
				</div>
			</div>
			
			';
    $attachment_content = '<style>
				a.logo {
					background:url(../images/header_logo.png) no-repeat;
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
                                                            <a href="' . $_CFG['wap_domain'] . '/resume/view_full_resume?article_id=' . $article['id'] . '&resume_id=' . $resume['id'] . '&redirect_url=' . $_CFG['main_domain'] . 'user/company/company_recruitment.php?act=apply_jobs" style="text-decoration:none;background:#F87C10;padding:12px 20px;color:#fff;margin-top:20px;">查看更多简历</a>
                                                        </div>
					</div>
				</div>
			</div>
			
			';
    if ($resume['default_resume'] == 1) {
        $content = $attachment_content;
        $sql = "select * from " . table("resume_attachment") . " where uid = " . $uid . " limit 1";
        $attachment = $db->getone($sql);
        $attachment_arr[0]['path'] = '../' . $attachment['path'] . $attachment['file_name'];
        $attachment_arr[0]['name'] = $attachment['file_name'];
    }
    //$email = "377128799@qq.com";
    smtp_mail($email, $resume['fullname'] . '-应聘' . $jobs['jobs_name'], $content, $from_email, '教师招聘网', $attachment_arr);
}

?>