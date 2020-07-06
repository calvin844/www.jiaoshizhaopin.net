<?php

/*
 * 74cms 企业会员中心ajax弹出框
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/company_common.php');
if ($act == "company_profile_save_succeed") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_companyprofile_save_succeed_box.htm";
    $contents = file_get_contents($tpl);
    exit($contents);
}
// 面试邀请详情
elseif ($act == "interview_detail") {
    global $db;
    $did = $_GET['did'] ? intval($_GET['did']) : exit('ID丢失！');
    $interview_info = $db->getone("SELECT * FROM " . table('company_interview') . " WHERE did=" . $did . " LIMIT 1 ");
    if ($interview_info) {
        if (empty($interview_info['notes'])) {
            $interview_info['notes'] = '无通知内容！';
        }
        $htm = '<div class="interview-dialog dialog-block">
					<div class="dialog-item clearfix">
						<div class="d-type f-left">邀请简历：</div>
						<div class="d-content f-left">' . $interview_info['resume_name'] . '</div>
					</div>
					<div class="dialog-item clearfix">
						<div class="d-type f-left">面试职位：</div>
						<div class="data-filter d-content f-left">
							<div class="dropdown">' . $interview_info['jobs_name'] . '</div>
				            <input type="hidden" name="jobsid" value="" id="jobsid">
						</div>
					</div>
					<div class="dialog-item clearfix">
						<div class="d-type f-left">面试日期：</div>
						<div class="d-content f-left">' . date('Y-m-d', $interview_info['interview_addtime']) . '</div>
					</div>
					<div class="dialog-item clearfix">
						<div class="d-type f-left">通知内容：</div>
						<div class="d-content f-left">' . $interview_info['notes'] . '</div>
					</div>
				</div>';
        exit($htm);
    } else {
        exit('无此数据！');
    }
} elseif ($act == "user_email") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_authenticate_email_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_email_key'] = mt_rand(100000, 999999);
    $contents = str_replace('{#$email#}', $user["email"], $contents);
    $contents = str_replace('{#$send_email_key#}', $_SESSION['send_email_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "user_mobile") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_authenticate_mobile_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $contents = str_replace('{#$mobile#}', $user["mobile"], $contents);
    $contents = str_replace('{#$send_mobile_key#}', $_SESSION['send_mobile_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "old_mobile") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_authenticate_old_mobile_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $user["hid_mobile"] = substr($user["mobile"], 0, 3) . "*****" . substr($user["mobile"], 7, 4);
    $contents = str_replace('{#$mobile#}', $user["mobile"], $contents);
    $contents = str_replace('{#$hid_mobile#}', $user["hid_mobile"], $contents);
    $contents = str_replace('{#$send_mobile_key#}', $_SESSION['send_mobile_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "edit_mobile") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_authenticate_edit_mobile_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $contents = str_replace('{#$send_mobile_key#}', $_SESSION['send_mobile_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "set_promotion") {
    $catid = intval($_GET['catid']) ? intval($_GET['catid']) : exit("catid参数错误！");
    $jobid = intval($_GET['jobid']) ? intval($_GET['jobid']) : 0;
    $uid = intval($_SESSION['uid']) ? intval($_SESSION['uid']) : exit("uid参数错误！");
    if ($jobid > 0) {
        $jobinfo = get_jobs_one($jobid);
    }
    $promotion = get_promotion_category_one($catid);

    if ($_CFG['operation_mode'] == '2') {
        $setmeal = get_user_setmeal($uid); //获取会员套餐
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') {
            $end = 1; //判断套餐是否到期
            $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_promotion_end.htm";
        } else {
            $data = get_setmeal_promotion($uid, $catid); //获取会员某种推广的剩余条数和天数，名称，总条数
            $operation_mode = 2;
            $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_setmeal_promotion.htm";
        }
    } elseif ($_CFG['operation_mode'] == '1') {
        $points = get_user_points($uid);
        $operation_mode = 1;
        $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_points_promotion.htm";
    } elseif ($_CFG['operation_mode'] == '3') {
        $setmeal = get_user_setmeal($_SESSION['uid']); //获取会员套餐
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') {
            if ($_CFG['setmeal_to_points'] != 1) {
                $end = 1; //判断套餐是否到期
                $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_promotion_end.htm";
            } else {
                $operation_mode = 1;
                $points = get_user_points($uid);
                $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_points_promotion.htm";
            }
        } else {
            $data = get_setmeal_promotion($uid, $catid); //获取会员某种推广的剩余条数和天数，名称，总条数
            if ($data['num'] < 1) {
                if ($_CFG['setmeal_to_points'] == 1) {
                    $operation_mode = 1;
                    $points = get_user_points($uid);
                    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_points_promotion.htm";
                } else {
                    $operation_mode = 2;
                    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_setmeal_promotion.htm";
                }
            } else {
                $operation_mode = 2;
                $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_setmeal_promotion.htm";
            }
        }
    }
    $contents = file_get_contents($tpl);
    if ($end != 1) {
        if ($catid == "4") {
            $color = get_color();
            $color_list = '<tr>
				<td height="50">选择颜色：</td>
				<td>
					<div style="position:relateve;">
	             	 	<div id="val_menu" class="input_text_70_bg">请选择</div>	
	             	 	<div class="menu" id="menu1">
		              		<ul style="width:88px;">';
            foreach ($color as $key => $value) {
                $color_list.='<li id="' . $value["id"] . '" title="' . $value["value"] . '" style="background-color:' . $value["value"] . '">&nbsp;</li>';
            }
            $color_list.='</ul>
		              	</div>
		            </div>
		            <input type="hidden" name="val" value="" id="val">
				</td>
				<td></td>
			</tr>';
            $contents = str_replace('{#$color_list#}', $color_list, $contents);
        } else {
            $contents = str_replace('{#$color_list#}', "", $contents);
        }
        $contents = str_replace('{#$jobid#}', $jobid, $contents);
        $contents = str_replace('{#$catid#}', $catid, $contents);
        $contents = str_replace('{#$jobs_name#}', $jobinfo['jobs_name'], $contents);
        $contents = str_replace('{#$promotion_name#}', $promotion['cat_name'], $contents);
        $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
        if ($operation_mode == 1) {
            if ($promotion['cat_minday'] == "0") {
                $promotion['cat_minday'] = "不限制";
            }
            if ($promotion['cat_maxday'] == "0") {
                $promotion['cat_maxday'] = "不限制";
            }
            if ($promotion['cat_points'] == "0") {
                $promotion['cat_points'] = "免费";
            }
            $contents = str_replace('{#$user_points#}', $points, $contents);
            $contents = str_replace('{#$points_perday#}', $promotion['cat_points'], $contents);
            $contents = str_replace('{#$points_quantifier#}', $_CFG['points_quantifier'], $contents);
            $contents = str_replace('{#$points_byname#}', $_CFG['points_byname'], $contents);
            $contents = str_replace('{#$cat_minday#}', $promotion['cat_minday'], $contents);
            $contents = str_replace('{#$cat_maxday#}', $promotion['cat_maxday'], $contents);
        } elseif ($operation_mode == 2) {
            $contents = str_replace('{#$days#}', $data['days'], $contents);
            $contents = str_replace('{#$setmeal_name#}', $setmeal['setmeal_name'], $contents);
            $contents = str_replace('{#$num#}', $data['num'], $contents);
            $contents = str_replace('{#$pro_name#}', $data['name'], $contents);
            $contents = str_replace('{#$cat_minday#}', $promotion['cat_minday'], $contents);
            $contents = str_replace('{#$cat_maxday#}', $promotion['cat_maxday'], $contents);
        }
    }
    exit($contents);
} elseif ($act == "promotion_add_save") {
    $form = intval($_POST['form']) ? intval($_POST['form']) : 0;

    if (intval($_POST['catid'])) {
        $catid = intval($_POST['catid']);
    } else {
        $form > 0 ? exit("<script>alert('分类参数错误！');history.go(-1);</script>") : exit("-1");
    }
    if (intval($_POST['jobid'])) {
        $jobid = intval($_POST['jobid']);
    } else {
        $form > 0 ? exit("<script>alert('职位参数错误！');history.go(-1);</script>") : exit("-1");
    }
    if (intval($_POST['days'])) {
        $days = intval($_POST['days']);
    } else {
        $form > 0 ? exit("<script>alert('天数参数错误！');history.go(-1);</script>") : exit("-1");
    }
    if (intval($_SESSION['uid'])) {
        $uid = intval($_SESSION['uid']);
    } else {
        $form > 0 ? exit("<script>alert('用户参数错误！');history.go(-1);</script>") : exit("-1");
    }

    if ($catid == 4) {
        $val = intval($_POST['val']) ? intval($_POST['val']) : exit("-1");
        $color = get_color_one($val);
        $val_code = $color['value'];
    } else {
        $val_code = "";
    }
    $jobs = get_jobs_one($jobid, $uid);
    if ($jobs['deadline'] < time()) {
        $form > 0 ? exit("<script>alert('该职位已到期，请先延期！');history.go(-1);</script>") : exit("该职位已到期，请先延期！");
    }
    if ($jobid > 0 && $days > 0) {
        $pro_cat = get_promotion_category_one($catid);
        if ($_CFG['operation_mode'] == '3') {
            $setmeal = get_setmeal_promotion($uid, $catid); //获取会员套餐
            $num = $setmeal['num'];
            if (($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') || $num <= 0) {
                if ($_CFG['setmeal_to_points'] == 1) {
                    if ($pro_cat['cat_points'] > 0) {
                        $points = $pro_cat['cat_points'] * $days;
                        $user_points = get_user_points($uid);
                        $user_limit_points = get_user_limit_points($_SESSION['uid']);
                        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                        $user_points = $user_points + $user_limit_points;
                        if ($points > $user_points) {
                            $form > 0 ? exit("<script>alert('你的" . $_CFG['points_byname'] . "不够进行此次操作，请先充值！');history.go(-1);</script>") : exit("你的" . $_CFG['points_byname'] . "不够进行此次操作，请先充值！");
                        } else {
                            $_CFG['operation_mode'] = 1;
                        }
                    } else {
                        $_CFG['operation_mode'] = 2;
                    }
                } else {
                    $form > 0 ? exit("<script>alert('你的套餐已到期或套餐内剩余" . $pro_cat['cat_name'] . "不够，请尽快开通新套餐');history.go(-1);</script>") : exit("你的套餐已到期或套餐内剩余{$pro_cat['cat_name']}不够，请尽快开通新套餐");
                }
            } else {
                $_CFG['operation_mode'] = 2;
            }
        } elseif ($_CFG['operation_mode'] == '1') {
            if ($pro_cat['cat_points'] > 0) {
                $points = $pro_cat['cat_points'] * $days;
                $user_points = get_user_points($uid);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $user_points = $user_points + $user_limit_points;
                if ($points > $user_points) {
                    $form > 0 ? exit("<script>alert('你的" . $_CFG['points_byname'] . "不够进行此次操作，请先充值！');history.go(-1);</script>") : exit("你的" . $_CFG['points_byname'] . "不够进行此次操作，请先充值！");
                }
            }
        } elseif ($_CFG['operation_mode'] == '2') {
            $setmeal = get_setmeal_promotion($uid, $catid); //获取会员套餐
            $num = $setmeal['num'];
            if (($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') || $num <= 0) {
                $form > 0 ? exit("<script>alert('你的套餐已到期或套餐内剩余" . $pro_cat['cat_name'] . "不够，请尽快开通新套餐');history.go(-1);</script>") : exit("你的套餐已到期或套餐内剩余{$pro_cat['cat_name']}不够，请尽快开通新套餐");
            }
        }
        $info = get_promotion_one($jobid, $uid, $catid);
        if (!empty($info)) {
            $form > 0 ? exit("<script>alert('此职位正在推广中，请选择其他职位或其他方案');history.go(-1);</script>") : exit("此职位正在推广中，请选择其他职位或其他方案");
        }
        $setsqlarr['cp_available'] = 1;
        $setsqlarr['cp_promotionid'] = $catid;
        $setsqlarr['cp_uid'] = $uid;
        $setsqlarr['cp_jobid'] = $jobid;
        $setsqlarr['cp_days'] = $days;
        $setsqlarr['cp_starttime'] = time();
        $setsqlarr['cp_endtime'] = strtotime("{$days} day");
        $setsqlarr['cp_val'] = $val_code;
        if ($setsqlarr['cp_promotionid'] == "4" && empty($setsqlarr['cp_val'])) {
            $form > 0 ? exit("<script>alert('请选择颜色！');history.go(-1);</script>") : exit("请选择颜色！");
        }
        if (inserttable(table('promotion'), $setsqlarr)) {
            set_job_promotion($jobid, $setsqlarr['cp_promotionid'], $val_code);
            $jobs = get_jobs_one($jobid, $uid);
            if ($_CFG['operation_mode'] == '1' && $pro_cat['cat_points'] > 0) {
                report_deal($uid, 2, $points);
                $user_points = get_user_points($uid);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                write_memberslog($uid, 1, 9001, $_SESSION['username'], "{$pro_cat['cat_name']}：<strong>{$jobs['jobs_name']}</strong>，推广 {$days} 天，(-{$points})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})", 1, 1018, "{$pro_cat['cat_name']}", "-{$points}", "{$user_points}");
            } elseif ($_CFG['operation_mode'] == '2') {
                $user_pname = trim($_POST['pro_name']);
                action_user_setmeal($uid, $user_pname); //更新套餐中相应推广方式的条数
                $setmeal = get_user_setmeal($uid); //获取会员套餐
                write_memberslog($uid, 1, 9002, $_SESSION['username'], "{$pro_cat['cat_name']}：<strong>{$jobs['jobs_name']}</strong>，推广 {$days} 天，套餐内剩余{$pro_cat['cat_name']}条数：{$setmeal[$user_pname]}条。", 2, 1018, "{$pro_cat['cat_name']}", "-{$days}", "{$setmeal[$user_pname]}"); //9002是套餐操作
            }
            write_memberslog($uid, 1, 3004, $_SESSION['username'], "{$pro_cat['cat_name']}：<strong>{$jobs['jobs_name']}</strong>，推广 {$days} 天。");
            $form > 0 ? exit("<script>alert('设置成功！');history.go(-1);</script>") : exit("1");
        }
    } else {
        $form > 0 ? exit("<script>alert('参数错误！');history.go(-1);</script>") : exit("-1");
    }
}
// 职位刷新ajax
elseif ($act == "jobs_refresh_ajax") {
    global $db, $_CFG;
    $jobsid = $_GET['jobsid'] ? intval($_GET['jobsid']) : exit('职位ID丢失！');
    $jobs_info = get_jobs_one($jobsid, $_SESSION['uid']);
    if ($jobs_info['deadline'] < time()) {
        exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">该职位已到期！</span></div></div>');
    }
    if ($_CFG['operation_mode'] == '1') {
        $mode = 1;
    } elseif ($_CFG['operation_mode'] == '2') {
        $mode = 2;
    } elseif ($_CFG['operation_mode'] == '3') {
        $setmeal = get_user_setmeal($_SESSION['uid']);
        //该会员套餐过期 (套餐过期后就用积分来刷)
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            //后台开通  服务超限时启用积分消费
            if ($_CFG['setmeal_to_points'] == '1') {
                $mode = 1;
            }
            //后台没有开通  服务超限时启用积分消费
            else {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">您的服务已经到期，请重新开通</span></div></div>');
            }
        }
        //该会员套餐未过期 
        else {
            $points_rule = get_cache('points_rule');
            $user_points = get_user_points($_SESSION['uid']);
            //获取当天刷新的职位数(在套餐模式下刷新的)
            $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 2);
            //当天剩余刷新职位数(在套餐模式下刷新的)
            $surplus_time = $setmeal['refresh_jobs_time'] - $refresh_time['count(*)'];
            //刷新职位数 大于 剩余刷新职位数 (超了)
            if ($surplus_time <= 0) {
                //后台开通  服务超限时启用积分消费
                if ($_CFG['setmeal_to_points'] == '1') {
                    $mode = 1;
                } else {
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">您的服务已经到期，请重新开通</span></div></div>');
                }
            } else {
                $mode = 2;
            }
        }
    }
    //积分模式
    if ($mode == '1') {
        //限制刷新时间
        //最近一次的刷新时间
        $refrestime = get_last_refresh_date($_SESSION['uid'], "1001", 1);
        $duringtime = time() - $refrestime['max(addtime)'];
        $space = $_CFG['com_pointsmode_refresh_space'] * 60;
        $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 1);
        if ($_CFG['com_pointsmode_refresh_time'] != 0 && ($refresh_time['count(*)'] >= $_CFG['com_pointsmode_refresh_time'])) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">抱歉！今日刷新次数已达上限，想要招人快？ <a href="company_jobs.php?act=jobs" class="underline">职位推广</a> 效果提升5倍！</span></div></div>');
        } elseif ($duringtime <= $space) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">' . $_CFG['com_pointsmode_refresh_space'] . '分钟内不能重复刷新职位！</span></div></div>');
        } else {
            $points_rule = get_cache('points_rule');
            if ($points_rule['jobs_refresh']['value'] > 0) {
                $user_points = get_user_points($_SESSION['uid']);
                $total_point = 1 * $points_rule['jobs_refresh']['value'];
                if ($total_point > $user_points && $points_rule['jobs_refresh']['type'] == "2") {
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">抱歉！您的剩余' . $_CFG['points_byname'] . '不足，无法进行此操作，请立即 <a href="company_service.php?act=order_add" class="underline">充值</a>！</span></div></div>');
                } else {
                    // 不限次数
                    if ($_CFG['com_pointsmode_refresh_time'] == 0) {
                        exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">本次刷新需要消耗<span style="color:#ff9900;">' . $points_rule['jobs_refresh']['value'] . '</span>' . $_CFG['points_byname'] . '，确定要刷新吗？</span></div></div><div class="center-btn-wrap"><input type="button" value="确定" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="取消" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                    } else {
                        //剩余次数
                        $surplus = intval($_CFG['com_pointsmode_refresh_time']) - intval($refresh_time['count(*)']);
                        exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">今天还可用积分刷新<span style="color:#ff9900;">' . $surplus . '</span>次，本次刷新需要消耗<span style="color:#ff9900;">' . $points_rule['jobs_refresh']['value'] . '</span>' . $_CFG['points_byname'] . '，确定要刷新吗？</span></div></div><div class="center-btn-wrap"><input type="button" value="确定" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="取消" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                    }
                }
            } else {
                // 不限次数
                if ($_CFG['com_pointsmode_refresh_time'] == 0) {

                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">确定要刷新吗？</span></div></div><div class="center-btn-wrap"><input type="button" value="确定" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="取消" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                } else {
                    //剩余次数
                    $surplus = intval($_CFG['com_pointsmode_refresh_time']) - intval($refresh_time['count(*)']);
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">今天还可用积分刷新<span style="color:#ff9900;">' . $surplus . '</span>次，确定要刷新吗？</span></div></div><div class="center-btn-wrap"><input type="button" value="确定" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="取消" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                }
            }
        }
    }
    //套餐模式
    elseif ($mode == '2') {

        //限制刷新时间
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if (empty($setmeal)) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">您还没有开通服务，请开通服务</span></div></div>');
        } elseif ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">您的服务已经到期，请重新开通</span></div></div>');
        } else {
            //最近一次的刷新时间
            $refrestime = get_last_refresh_date($_SESSION['uid'], "1001", 2);
            $duringtime = time() - $refrestime['max(addtime)'];
            $space = $setmeal['refresh_jobs_space'] * 60;
            $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 2);
            if ($setmeal['refresh_jobs_time'] != 0 && ($refresh_time['count(*)'] >= $setmeal['refresh_jobs_time'])) {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">抱歉！今日刷新次数已达上限，想要招人快？ <a href="company_jobs.php?act=jobs" class="underline">职位推广</a> 效果提升5倍！</span></div></div>');
            } elseif ($duringtime <= $space) {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">' . $setmeal['refresh_jobs_space'] . '分钟内不能重复刷新职位！</span></div></div>');
            } else {
                //剩余次数
                $surplus = intval($setmeal['refresh_jobs_time']) - intval($refresh_time['count(*)']);
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">今天免费刷新次数剩余<span style="color:#ff9900;">' . $surplus . '</span>次，本次刷新<span style="color:#ff9900;">免费</span>，确定要刷新吗？</span></div></div><div class="center-btn-wrap"><input type="button" value="确定" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="取消" class="btn-65-30grey btn-big-font DialogClose" /></div>');
            }
        }
    }
}
// 批量职位刷新ajax
elseif ($act == "jobs_all_refresh_ajax") {
    global $db, $_CFG;
    $length = $_GET['length'] ? intval($_GET['length']) : exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">请选择职位！</span></div></div>');
    if ($_CFG['operation_mode'] == '1') {
        $mode = 1;
    } elseif ($_CFG['operation_mode'] == '2') {
        $mode = 2;
    } elseif ($_CFG['operation_mode'] == '3') {
        $setmeal = get_user_setmeal($_SESSION['uid']);
        //该会员套餐过期 (套餐过期后就用积分来刷)
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">您的服务已经到期，请重新开通</span></div></div>');
        }
        //该会员套餐未过期 
        else {
            $points_rule = get_cache('points_rule');
            $user_points = get_user_points($_SESSION['uid']);
            //获取当天刷新的职位数(在套餐模式下刷新的)
            $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 2);
            //当天剩余刷新职位数(在套餐模式下刷新的)
            $surplus_time = $setmeal['refresh_jobs_time'] - $refresh_time['count(*)'];
            //刷新职位数 大于 剩余刷新职位数 (超了)
            if ($setmeal['refresh_jobs_time'] != 0 && ($length > $surplus_time)) {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">您批量刷新职位数超过了套餐剩余数，请单个职位刷新操作！</span></div></div>');
            } else {
                $mode = 2;
            }
        }
    }
    //积分模式
    if ($mode == '1') {
        //限制刷新时间
        //最近一次的刷新时间
        $refrestime = get_last_refresh_date($_SESSION['uid'], "1001", 1);
        $duringtime = time() - $refrestime['max(addtime)'];
        $space = $_CFG['com_pointsmode_refresh_space'] * 60;
        $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 1);
        $surplus_time = $_CFG['com_pointsmode_refresh_time'] - $refresh_time['count(*)'];
        if ($_CFG['com_pointsmode_refresh_time'] != 0 && ($length > $surplus_time)) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">抱歉！您批量刷新职位数超过了积分刷新剩余数，请单个职位刷新操作！</span></div></div>');
        } elseif ($duringtime <= $space) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">' . $_CFG['com_pointsmode_refresh_space'] . '分钟内不能重复刷新职位！</span></div></div>');
        } else {
            $points_rule = get_cache('points_rule');
            if ($points_rule['jobs_refresh']['value'] > 0) {
                $user_points = get_user_points($_SESSION['uid']);
                $total_point = $length * $points_rule['jobs_refresh']['value'];
                if ($total_point > $user_points && $points_rule['jobs_refresh']['type'] == "2") {
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">抱歉！您的剩余' . $_CFG['points_byname'] . '不足，无法进行此操作，请立即 <a href="company_service.php?act=order_add" class="underline">充值</a>！</span></div></div>');
                } else {
                    // 不限次数
                    if ($_CFG['com_pointsmode_refresh_time'] == 0) {
                        exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">本次刷新需要消耗<span style="color:#ff9900;">' . $length * $points_rule['jobs_refresh']['value'] . '</span>' . $_CFG['points_byname'] . '，确定要刷新吗？</span></div></div><div class="center-btn-wrap"><input type="button" value="确定" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="取消" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                    } else {
                        //剩余次数
                        $surplus = intval($_CFG['com_pointsmode_refresh_time']) - intval($refresh_time['count(*)']);
                        exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">今天还可用积分刷新<span style="color:#ff9900;">' . $surplus . '</span>次，本次刷新需要消耗<span style="color:#ff9900;">' . $length * $points_rule['jobs_refresh']['value'] . '</span>' . $_CFG['points_byname'] . '，确定要刷新吗？</span></div></div><div class="center-btn-wrap"><input type="button" value="确定" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="取消" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                    }
                }
            } else {
                // 不限次数
                if ($_CFG['com_pointsmode_refresh_time'] == 0) {

                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">确定要刷新吗？</span></div></div><div class="center-btn-wrap"><input type="button" value="确定" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="取消" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                } else {
                    //剩余次数
                    $surplus = intval($_CFG['com_pointsmode_refresh_time']) - intval($refresh_time['count(*)']);
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">今天还可用积分刷新<span style="color:#ff9900;">' . $surplus . '</span>次，确定要刷新吗？</span></div></div><div class="center-btn-wrap"><input type="button" value="确定" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="取消" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                }
            }
        }
    }
    //套餐模式
    elseif ($mode == '2') {
        //限制刷新时间
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if (empty($setmeal)) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">您还没有开通服务，请开通服务</span></div></div>');
        } elseif ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">您的服务已经到期，请重新开通</span></div></div>');
        } else {
            //最近一次的刷新时间
            $refrestime = get_last_refresh_date($_SESSION['uid'], "1001", 2);
            $duringtime = time() - $refrestime['max(addtime)'];
            $space = $setmeal['refresh_jobs_space'] * 60;
            $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 2);
            $surplus_time = $setmeal['refresh_jobs_time'] - $refresh_time['count(*)'];
            if ($setmeal['refresh_jobs_time'] != 0 && ($length > $surplus_time)) {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">抱歉！您批量刷新职位数超过了套餐刷新剩余数，请单个职位刷新操作！</span></div></div>');
            } elseif ($duringtime <= $space) {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">' . $setmeal['refresh_jobs_space'] . '分钟内不能重复刷新职位！</span></div></div>');
            } else {
                //剩余次数
                $surplus = intval($setmeal['refresh_jobs_time']) - intval($refresh_time['count(*)']);

                if ($setmeal['refresh_jobs_time'] == 0) {
                    exit('<div class="del-dialog"><div class="tip-block">本次刷新<span style="color:#ff9900;">免费</span>，确定要刷新吗？</span></div></div><div class="center-btn-wrap"><input type="button" value="确定" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="取消" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                } else {
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">今天免费刷新次数剩余<span style="color:#ff9900;">' . $surplus . '</span>次，本次刷新<span style="color:#ff9900;">免费</span>，确定要刷新吗？</span></div></div><div class="center-btn-wrap"><input type="button" value="确定" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="取消" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                }
            }
        }
    }
}
unset($smarty);
?>