<?php

/*
 * 74cms 培训会员中心
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/hunter_common.php');
$smarty->assign('leftmenu', "jobs");
if ($act == 'jobs') {
    $wheresql = " WHERE uid='{$_SESSION['uid']}' AND utype={$_SESSION['utype']} ";
    $orderby = " order by refreshtime desc";
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $perpage = 10;
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('hunter_jobs') . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $offset = ($page->nowindex - 1) * $perpage;
    $smarty->assign('title', '职位管理 - 猎头会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $sql = "SELECT * FROM " . table('hunter_jobs') . $wheresql . $orderby;
    $smarty->assign('hunterjobs', get_hunterjobs($offset, $perpage, $sql, true));
    $smarty->assign('page', $page->show(3));
    $smarty->assign('points_rule', get_cache('points_rule'));
    $smarty->display('member_hunter/hunter_jobs.htm');
} elseif ($act == 'addjobs') {
    $smarty->assign('user', $user);
    if ($hunter_profile['huntername']) {
        $_SESSION['addrand'] = rand(1000, 5000);
        $smarty->assign('addrand', $_SESSION['addrand']);
        $smarty->assign('title', '发布职位 - 猎头会员中心 - ' . $_CFG['site_name']);
        $smarty->assign('hunter_profile', $hunter_profile);
        if ($_CFG['operation_hunter_mode'] == "2") {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            $smarty->assign('setmeal', $setmeal);
            $smarty->assign('add_mode', 2);
        } elseif ($_CFG['operation_hunter_mode'] == "1") {
            $smarty->assign('points_total', get_user_points($_SESSION['uid']));
            $smarty->assign('points', get_cache('points_rule'));
            $smarty->assign('add_mode', 1);
        }
        $captcha = get_cache('captcha');
        $smarty->assign('subsite', get_subsite_list());
        $smarty->assign('verify_addjob', $captcha['verify_addjob']);
        $smarty->display('member_hunter/hunter_addjobs.htm');
    } else {
        $link[0]['text'] = "完善猎头资料";
        $link[0]['href'] = 'hunter_info.php?act=hunter_profile';
        showmsg("为了达到更好的招聘效果，请先完善您的猎头资料！", 1, $link);
    }
} elseif ($act == 'addjobs_save') {
    $captcha = get_cache('captcha');
    $postcaptcha = trim($_POST['postcaptcha']);
    if ($captcha['verify_addjob'] == '1' && empty($postcaptcha)) {
        showmsg("请填写验证码", 1);
    }
    if ($captcha['verify_addjob'] == '1' && strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        showmsg("验证码错误", 1);
    }
    $add_mode = trim($_POST['add_mode']);
    $days = intval($_POST['days']);
    if ($days < $_CFG['hunter_add_days_min']) {
        showmsg("有效时间最少为 " . $_CFG['hunter_add_days_min'] . " 天！", 1);
    }
    if ($_CFG['operation_hunter_mode'] == '1') {
        $points_rule = get_cache('points_rule');
        $user_points = get_user_points($_SESSION['uid']);
        $user_limit_points = get_user_limit_points($_SESSION['uid']);
        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
        $user_points = $user_points + $user_limit_points;
        $total = 0;
        if ($points_rule['hunter_hunterjobs_add']['type'] == "2" && $points_rule['hunter_hunterjobs_add']['value'] > 0) {
            $total = $points_rule['hunter_hunterjobs_add']['value'];
        }
        if ($points_rule['hunter_hunterjobs_daily']['type'] == "2" && $points_rule['hunter_hunterjobs_daily']['value'] > 0) {
            $total = $total + ($days * $points_rule['hunter_hunterjobs_daily']['value']);
        }
        if ($total > $user_points) {
            $link[0]['text'] = "立即充值";
            $link[0]['href'] = 'hunter_service.php?act=order_add';
            $link[1]['text'] = "会员中心首页";
            $link[1]['href'] = 'hunter_index.php?act=';
            showmsg("你的" . $_CFG['hunter_points_byname'] . "不足，请充值后再发布！", 0, $link);
        }
        $setsqlarr['setmeal_deadline'] = 0;
    } elseif ($_CFG['operation_hunter_mode'] == '2') {
        $link[0]['text'] = "立即开通服务";
        $link[0]['href'] = 'hunter_service.php?act=setmeal_list';
        $link[1]['text'] = "会员中心首页";
        $link[1]['href'] = 'hunter_index.php?act=';
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') {
            showmsg("您的服务已经到期，请重新开通", 1, $link);
        }
        if ($setmeal['jobs_add'] <= 0) {
            showmsg("当前发布的职位已经超过了最大限制，请升级服务套餐！", 1, $link);
        }
        $setsqlarr['setmeal_deadline'] = $setmeal['endtime'];
        $setsqlarr['setmeal_id'] = $setmeal['setmeal_id'];
        $setsqlarr['setmeal_name'] = $setmeal['setmeal_name'];
    }
    $addrand = intval($_POST['addrand']);
    if ($_SESSION['addrand'] == $addrand) {
        unset($_SESSION['addrand']);
        $setsqlarr['add_mode'] = intval($add_mode);
        $setsqlarr['uid'] = intval($_SESSION['uid']);
        $setsqlarr['utype'] = intval($_SESSION['utype']);

        $setsqlarr['companyname'] = !empty($_POST['companyname']) ? trim($_POST['companyname']) : showmsg('您没有填写招聘方公司的显示名称！', 1);
        $setsqlarr['companyname_note'] = !empty($_POST['companyname_note']) ? trim($_POST['companyname_note']) : showmsg('您没有填写招聘方公司备注名称！', 1);
        $setsqlarr['nature'] = !empty($_POST['nature']) ? intval($_POST['nature']) : showmsg('请选择招聘方公司性质！', 1);
        $setsqlarr['nature_cn'] = trim($_POST['nature_cn']);
        $setsqlarr['scale'] = !empty($_POST['scale']) ? intval($_POST['scale']) : showmsg('请选择招聘方公司规模！', 1);
        $setsqlarr['scale_cn'] = trim($_POST['scale_cn']);
        $setsqlarr['trade'] = !empty($_POST['trade']) ? intval($_POST['trade']) : showmsg('请选择招聘方公司行业！', 1);
        $setsqlarr['trade_cn'] = trim($_POST['trade_cn']);
        $setsqlarr['company_desc'] = !empty($_POST['company_desc']) ? trim($_POST['company_desc']) : showmsg('您没有填写招聘方公司的简介！', 1);


        $setsqlarr['jobs_name'] = !empty($_POST['jobs_name']) ? trim($_POST['jobs_name']) : showmsg('您没有填写职位名称！', 1);
        $setsqlarr['category'] = !empty($_POST['category']) ? intval($_POST['category']) : showmsg('请选择职位类别！', 1);
        $setsqlarr['subclass'] = intval($_POST['subclass']);
        $setsqlarr['category_cn'] = trim($_POST['category_cn']);

        $setsqlarr['department'] = !empty($_POST['department']) ? trim($_POST['department']) : showmsg('您没有填写所属部门！', 1);
        $setsqlarr['reporter'] = !empty($_POST['reporter']) ? trim($_POST['reporter']) : showmsg('您没有填写汇报对象！', 1);

        $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : showmsg('请选择工作地区！', 1);
        $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
        $setsqlarr['district_cn'] = trim($_POST['district_cn']);

        $setsqlarr['wage'] = !empty($_POST['wage']) ? intval($_POST['wage']) : showmsg('请选择年薪范围！', 1);
        $setsqlarr['wage_cn'] = trim($_POST['wage_cn']);
        $setsqlarr['wage_structure'] = !empty($_POST['wage_structure']) ? $_POST['wage_structure'] : showmsg('请选择薪资构成！', 1);
        $setsqlarr['socialbenefits'] = trim($_POST['socialbenefits']);
        $setsqlarr['livebenefits'] = trim($_POST['livebenefits']);
        $setsqlarr['annualleave'] = trim($_POST['annualleave']);
        $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : showmsg('您没有填写职位描述！', 1);
        check_word($_CFG['filter'], $_POST['contents']) ? showmsg($_CFG['filter_tips'], 0) : '';


        $setsqlarr['age'] = !empty($_POST['age']) ? intval($_POST['age']) : showmsg('请选择年龄要求！', 1);
        $setsqlarr['age_cn'] = trim($_POST['age_cn']);
        $setsqlarr['sex'] = intval($_POST['sex']);
        $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
        $setsqlarr['experience'] = !empty($_POST['experience']) ? intval($_POST['experience']) : showmsg('请选择工作经验要求！', 1);
        $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
        $setsqlarr['education'] = !empty($_POST['education']) ? intval($_POST['education']) : showmsg('请选择学历要求！', 1);
        $setsqlarr['education_cn'] = trim($_POST['education_cn']);
        $setsqlarr['tongzhao'] = intval($_POST['tongzhao']);
        $setsqlarr['tongzhao_cn'] = trim($_POST['tongzhao_cn']);
        $setsqlarr['language'] = trim($_POST['language']);
        $setsqlarr['jobs_qualified'] = !empty($_POST['jobs_qualified']) ? trim($_POST['jobs_qualified']) : showmsg('您没有填写任职资格！', 1);
        check_word($_CFG['filter'], $_POST['jobs_qualified']) ? showmsg($_CFG['filter_tips'], 0) : '';

        $setsqlarr['hopetrade'] = !empty($_POST['hopetrade']) ? trim($_POST['hopetrade']) : showmsg('请选择期望人才来源行业！', 1);
        $setsqlarr['hopetrade_cn'] = trim($_POST['hopetrade_cn']);
        $setsqlarr['extra_message'] = trim($_POST['extra_message']);

        $setsqlarr['addtime'] = $timestamp;
        $setsqlarr['deadline'] = strtotime("" . intval($_POST['days']) . " day");
        $setsqlarr['refreshtime'] = $timestamp;
        $setsqlarr['key'] = $setsqlarr['jobs_name'] . $setsqlarr['companyname'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'] . $setsqlarr['jobs_qualified'];
        require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
        $sp = new SPWord();
        $setsqlarr['key'] = "{$setsqlarr['jobs_name']} {$setsqlarr['companyname']} " . $sp->extracttag($setsqlarr['key']);
        $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
        $setsqlarr['likekey'] = $setsqlarr['jobs_name'] . ',' . $setsqlarr['companyname'] . ',' . $setsqlarr['category_cn'] . ',' . $setsqlarr['district_cn'];
        $setsqlarr['subsite_id'] = intval($_POST['subsite_id']);

        if ($hunter_profile['audit'] == '1') {
            $setsqlarr['audit'] = intval($_CFG['audit_verifyhunter_addjob']);
        } else {
            $setsqlarr['audit'] = intval($_CFG['audit_unexaminedhunter_addjob']);
        }
        $setsqlarr['contact'] = !empty($_POST['contact']) ? trim($_POST['contact']) : showmsg('您没有填写联系人！', 1);
        $setsqlarr['qq'] = trim($_POST['qq']);
        $setsqlarr['telephone'] = !empty($_POST['telephone']) ? trim($_POST['telephone']) : showmsg('您没有填写联系电话！', 1);
        check_word($_CFG['filter'], $_POST['telephone']) ? showmsg($_CFG['filter_tips'], 0) : '';
        $setsqlarr['address'] = !empty($_POST['address']) ? trim($_POST['address']) : showmsg('您没有填写联系地址！', 1);
        $setsqlarr['email'] = !empty($_POST['email']) ? trim($_POST['email']) : showmsg('您没有填写联系邮箱！', 1);
        $setsqlarr['notify'] = intval($_POST['notify']);

        $setsqlarr['contact_show'] = intval($_POST['contact_show']);
        $setsqlarr['email_show'] = intval($_POST['email_show']);
        $setsqlarr['telephone_show'] = intval($_POST['telephone_show']);
        $setsqlarr['address_show'] = intval($_POST['address_show']);
        $setsqlarr['qq_show'] = intval($_POST['qq_show']);

        //添加职位信息
        $pid = inserttable(table('hunter_jobs'), $setsqlarr, true);
        empty($pid) ? showmsg("添加失败！", 0) : '';
        //添加联系方式
        if ($_CFG['operation_hunter_mode'] == '1') {
            if ($points_rule['hunter_hunterjobs_add']['value'] > 0) {
                report_deal($_SESSION['uid'], $points_rule['hunter_hunterjobs_add']['type'], $points_rule['hunter_hunterjobs_add']['value']);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $points_rule['hunter_hunterjobs_add']['type'] == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 3, 9201, $_SESSION['username'], "发布了职位：<strong>{$setsqlarr['jobs_name']}</strong>，({$operator}{$points_rule['hunter_hunterjobs_add']['value']})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
            }
            if (intval($_POST['days']) > 0 && $points_rule['hunter_hunterjobs_daily']['value'] > 0) {
                $points_day = intval($_POST['days']) * $points_rule['hunter_hunterjobs_daily']['value'];
                report_deal($_SESSION['uid'], $points_rule['hunter_hunterjobs_daily']['type'], $points_day);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $points_rule['hunter_hunterjobs_daily']['type'] == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 3, 9201, $_SESSION['username'], "发布普通职位:<strong>{$_POST['jobs_name']}</strong>，有效期为{$_POST['days']}天，({$operator}{$points_day})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
            }
        } elseif ($_CFG['operation_hunter_mode'] == '2') {
            action_user_setmeal($_SESSION['uid'], "jobs_add");
            $setmeal = get_user_setmeal($_SESSION['uid']);
            write_memberslog($_SESSION['uid'], 3, 9202, $_SESSION['username'], "发布职位:<strong>{$_POST['jobs_name']}</strong>，还可以发布职位:<strong>{$setmeal['jobs_add']}</strong>条");
        }
        write_memberslog($_SESSION['uid'], 3, 8502, $_SESSION['username'], "发布了职位：{$setsqlarr['jobs_name']}");
    }
    header("location:?act=addjobs_save_succeed");
} elseif ($act == 'addjobs_save_succeed') {
    $uid = intval($_SESSION['uid']);
    $smarty->assign('concern_id', get_concern_id($uid));
    $smarty->assign('title', '发布职位 - 企业会员中心 - ' . $_CFG['site_name']);
    $smarty->display('member_hunter/hunter_addjobs_succeed.htm');
} elseif ($act == 'jobs_perform') {
    $yid = !empty($_POST['y_id']) ? $_POST['y_id'] : $_GET['y_id'];
    $jobs_num = count($yid);
    if (empty($yid)) {
        showmsg("你没有选择职位！", 1);
    }

    $refresh = !empty($_POST['refresh']) ? $_POST['refresh'] : $_GET['refresh'];
    $delete = !empty($_POST['delete']) ? $_POST['delete'] : $_GET['delete'];
    if ($refresh) {
        if ($jobs_num == 1) {
            if (is_array($yid)) {
                $yid = $yid[0];
            }
            $jobs_info = $db->getone("select * from " . table('hunter_jobs') . " where id=" . $yid);
            if ($jobs_info['deadline'] < time()) {
                showmsg("该职位已到期，请先延期！", 1);
            }
        }
        if ($_CFG['operation_hunter_mode'] == '1') {
            //限制刷新时间
            //最经一次的刷新时间
            $refrestime = get_last_refresh_date($_SESSION['uid'], "3001");
            $duringtime = time() - $refrestime['max(addtime)'];
            $space = $_CFG['hunter_pointsmode_refresh_space'] * 60;
            $refresh_time = get_today_refresh_times($_SESSION['uid'], "3001");
            if ($_CFG['hunter_pointsmode_refresh_time'] != 0 && ($refresh_time['count(*)'] >= $_CFG['hunter_pointsmode_refresh_time'])) {
                showmsg("每天最多只能刷新" . $_CFG['hunter_pointsmode_refresh_time'] . "次,您今天已超过最大刷新次数限制！", 2);
            } elseif ($duringtime <= $space) {
                showmsg($_CFG['hunter_pointsmode_refresh_space'] . "分钟内不能重复刷新职位！", 2);
            } else {
                $points_rule = get_cache('points_rule');
                if ($points_rule['hunter_hunterjobs_refresh']['value'] > 0) {
                    $user_points = get_user_points($_SESSION['uid']);
                    $user_limit_points = get_user_limit_points($_SESSION['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $user_points = $user_points + $user_limit_points;
                    $total_point = $jobs_num * $points_rule['hunter_hunterjobs_refresh']['value'];
                    if ($total_point > $user_points && $points_rule['hunter_hunterjobs_refresh']['type'] == "2") {
                        $link[0]['text'] = "返回上一页";
                        $link[0]['href'] = 'javascript:history.go(-1)';
                        $link[1]['text'] = "立即充值";
                        $link[1]['href'] = 'hunter_service.php?act=order_add';
                        showmsg("您的" . $_CFG['hunter_points_byname'] . "不足，请先充值！", 0, $link);
                    }
                    report_deal($_SESSION['uid'], $points_rule['hunter_hunterjobs_refresh']['type'], $total_point);
                    $user_points = get_user_points($_SESSION['uid']);
                    $user_limit_points = get_user_limit_points($_SESSION['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $operator = $points_rule['hunter_hunterjobs_refresh']['type'] == "1" ? "+" : "-";
                    write_memberslog($_SESSION['uid'], 3, 9201, $_SESSION['username'], "刷新了{$jobs_num}条职位，({$operator}{$total_point})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
                }
            }
        } elseif ($_CFG['operation_hunter_mode'] == '2') {
            $link[0]['text'] = "立即开通服务";
            $link[0]['href'] = 'hunter_service.php?act=setmeal_list';
            $link[1]['text'] = "会员中心首页";
            $link[1]['href'] = 'hunter_index.php?act=';
            $setmeal = get_user_setmeal($_SESSION['uid']);
            if (empty($setmeal)) {
                showmsg("您还没有开通服务，请开通", 1, $link);
            } elseif ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
                showmsg("您的服务已经到期，请重新开通", 1, $link);
            } else {
                $refrestime = get_last_refresh_date($_SESSION['uid'], "3001");
                $duringtime = time() - $refrestime['max(addtime)'];
                $space = $setmeal['hunter_refresh_jobs_space'] * 60;
                $refresh_time = get_today_refresh_times($_SESSION['uid'], "3001");
                if ($setmeal['hunter_refresh_jobs_time'] != 0 && ($refresh_time['count(*)'] >= $setmeal['hunter_refresh_jobs_time'])) {
                    showmsg("每天最多只能刷新" . $setmeal['hunter_refresh_jobs_time'] . "次,您今天已超过最大刷新次数限制！", 2);
                } elseif ($duringtime <= $space) {
                    showmsg($setmeal['hunter_refresh_jobs_space'] . "分钟内不能重复刷新职位！", 2);
                }
            }
        }
        refresh_jobs($yid, $_SESSION['uid']);
        write_memberslog($_SESSION['uid'], 3, 8504, $_SESSION['username'], "刷新职位");
        write_refresh_log($_SESSION['uid'], 3001);
        showmsg("刷新职位成功！", 2);
    } elseif ($delete) {
        if ($n = del_jobs($yid, $_SESSION['uid'])) {
            showmsg("删除成功！共删除 {$n} 行", 2);
        } else {
            showmsg("删除失败！", 0);
        }
    } elseif (!empty($_REQUEST['display1'])) {
        activate_jobs($yid, 1, $_SESSION['uid']);
        showmsg("设置成功！", 2);
    } elseif (!empty($_REQUEST['display2'])) {
        activate_jobs($yid, 2, $_SESSION['uid']);
        showmsg("设置成功！", 2);
    }
} elseif ($act == 'edit_jobs') {
    $jobs = get_jobs_one(intval($_GET['id']), $_SESSION['uid']);
    if (empty($jobs))
        showmsg("参数错误！", 1);
    $smarty->assign('user', $user);
    $smarty->assign('subsite', get_subsite_list());
    $smarty->assign('title', '修改职位 - 猎头会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('points_total', get_user_points($_SESSION['uid']));
    $smarty->assign('points', get_cache('points_rule'));
    $smarty->assign('jobs', $jobs);
    $smarty->display('member_hunter/hunter_editjobs.htm');
}
elseif ($act == 'editjobs_save') {
    $id = intval($_POST['id']);
    $add_mode = trim($_POST['add_mode']);
    $days = intval($_POST['days']);
    if ($_CFG['operation_hunter_mode'] == '1') {
        $points_rule = get_cache('points_rule');
        $user_points = get_user_points($_SESSION['uid']);
        $user_limit_points = get_user_limit_points($_SESSION['uid']);
        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
        $user_points = $user_points + $user_limit_points;
        $total = 0;
        if ($points_rule['hunter_hunterjobs_edit']['type'] == "2" && $points_rule['hunter_hunterjobs_edit']['value'] > 0) {
            $total = $points_rule['hunter_hunterjobs_edit']['value'];
        }
        if ($points_rule['hunter_hunterjobs_daily']['type'] == "2" && $points_rule['hunter_hunterjobs_daily']['value'] > 0) {
            $total = $total + ($days * $points_rule['hunter_hunterjobs_daily']['value']);
        }
        if ($total > $user_points) {
            $link[0]['text'] = "返回上一页";
            $link[0]['href'] = 'javascript:history.go(-1)';
            $link[1]['text'] = "立即充值";
            $link[1]['href'] = 'hunter_service.php?act=order_add';
            showmsg("你的" . $_CFG['hunter_points_byname'] . "不足，请充值后再发布！", 0, $link);
        }
    } elseif ($_CFG['operation_hunter_mode'] == '2') {
        $link[0]['text'] = "立即开通服务";
        $link[0]['href'] = 'hunter_service.php?act=setmeal_list';
        $link[1]['text'] = "会员中心首页";
        $link[1]['href'] = 'hunter_index.php?act=';
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            showmsg("此信息通过服务套餐发布，您的套餐已经到期，请重新开通", 1, $link);
        }
        $setsqlarr['setmeal_deadline'] = $setmeal['endtime'];
        $setsqlarr['setmeal_id'] = $setmeal['setmeal_id'];
        $setsqlarr['setmeal_name'] = $setmeal['setmeal_name'];
    }
    $setsqlarr['add_mode'] = intval($add_mode);

    $setsqlarr['companyname'] = !empty($_POST['companyname']) ? trim($_POST['companyname']) : showmsg('您没有填写招聘方公司的显示名称！', 1);
    $setsqlarr['companyname_note'] = !empty($_POST['companyname_note']) ? trim($_POST['companyname_note']) : showmsg('您没有填写招聘方公司备注名称！', 1);
    $setsqlarr['nature'] = !empty($_POST['nature']) ? intval($_POST['nature']) : showmsg('请选择招聘方公司性质！', 1);
    $setsqlarr['nature_cn'] = trim($_POST['nature_cn']);
    $setsqlarr['scale'] = !empty($_POST['scale']) ? intval($_POST['scale']) : showmsg('请选择招聘方公司规模！', 1);
    $setsqlarr['scale_cn'] = trim($_POST['scale_cn']);
    $setsqlarr['trade'] = !empty($_POST['trade']) ? intval($_POST['trade']) : showmsg('请选择招聘方公司行业！', 1);
    $setsqlarr['trade_cn'] = trim($_POST['trade_cn']);
    $setsqlarr['company_desc'] = !empty($_POST['company_desc']) ? trim($_POST['company_desc']) : showmsg('您没有填写招聘方公司的简介！', 1);


    $setsqlarr['jobs_name'] = !empty($_POST['jobs_name']) ? trim($_POST['jobs_name']) : showmsg('您没有填写职位名称！', 1);
    $setsqlarr['category'] = !empty($_POST['category']) ? intval($_POST['category']) : showmsg('请选择职位类别！', 1);
    $setsqlarr['subclass'] = intval($_POST['subclass']);
    $setsqlarr['category_cn'] = trim($_POST['category_cn']);

    $setsqlarr['department'] = !empty($_POST['department']) ? trim($_POST['department']) : showmsg('您没有填写所属部门！', 1);
    $setsqlarr['reporter'] = !empty($_POST['reporter']) ? trim($_POST['reporter']) : showmsg('您没有填写汇报对象！', 1);

    $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : showmsg('请选择工作地区！', 1);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);

    $setsqlarr['wage'] = !empty($_POST['wage']) ? intval($_POST['wage']) : showmsg('请选择年薪范围！', 1);
    $setsqlarr['wage_cn'] = trim($_POST['wage_cn']);
    $setsqlarr['wage_structure'] = !empty($_POST['wage_structure']) ? $_POST['wage_structure'] : showmsg('请选择薪资构成！', 1);
    $setsqlarr['socialbenefits'] = trim($_POST['socialbenefits']);
    $setsqlarr['livebenefits'] = trim($_POST['livebenefits']);
    $setsqlarr['annualleave'] = trim($_POST['annualleave']);
    $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : showmsg('您没有填写职位描述！', 1);
    check_word($_CFG['filter'], $_POST['contents']) ? showmsg($_CFG['filter_tips'], 0) : '';


    $setsqlarr['age'] = !empty($_POST['age']) ? intval($_POST['age']) : showmsg('请选择年龄要求！', 1);
    $setsqlarr['age_cn'] = trim($_POST['age_cn']);
    $setsqlarr['sex'] = intval($_POST['sex']);
    $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
    $setsqlarr['experience'] = !empty($_POST['experience']) ? intval($_POST['experience']) : showmsg('请选择工作经验要求！', 1);
    $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
    $setsqlarr['education'] = !empty($_POST['education']) ? intval($_POST['education']) : showmsg('请选择学历要求！', 1);
    $setsqlarr['education_cn'] = trim($_POST['education_cn']);
    $setsqlarr['tongzhao'] = intval($_POST['tongzhao']);
    $setsqlarr['tongzhao_cn'] = trim($_POST['tongzhao_cn']);
    $setsqlarr['language'] = trim($_POST['language']);
    $setsqlarr['jobs_qualified'] = !empty($_POST['jobs_qualified']) ? trim($_POST['jobs_qualified']) : showmsg('您没有填写任职资格！', 1);
    check_word($_CFG['filter'], $_POST['jobs_qualified']) ? showmsg($_CFG['filter_tips'], 0) : '';

    $setsqlarr['hopetrade'] = !empty($_POST['hopetrade']) ? trim($_POST['hopetrade']) : showmsg('请选择期望人才来源行业！', 1);
    $setsqlarr['hopetrade_cn'] = trim($_POST['hopetrade_cn']);
    $setsqlarr['extra_message'] = trim($_POST['extra_message']);

    $setsqlarr['refreshtime'] = $timestamp;
    $setsqlarr['key'] = $setsqlarr['jobs_name'] . $setsqlarr['companyname'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'] . $setsqlarr['jobs_qualified'];
    require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
    $sp = new SPWord();
    $setsqlarr['key'] = "{$setsqlarr['jobs_name']} {$setsqlarr['companyname']} " . $sp->extracttag($setsqlarr['key']);
    $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
    $setsqlarr['likekey'] = $setsqlarr['jobs_name'] . ',' . $setsqlarr['companyname'] . ',' . $setsqlarr['category_cn'] . ',' . $setsqlarr['district_cn'];


    if ($_CFG['operation_hunter_mode'] == '1') {
        $setsqlarr['setmeal_deadline'] = 0;
        $setsqlarr['add_mode'] = 1;
    } elseif ($_CFG['operation_hunter_mode'] == '2') {
        $setmeal = get_user_setmeal($_SESSION['uid']);
        $setsqlarr['setmeal_deadline'] = $setmeal['endtime'];
        $setsqlarr['setmeal_id'] = $setmeal['setmeal_id'];
        $setsqlarr['setmeal_name'] = $setmeal['setmeal_name'];
        $setsqlarr['add_mode'] = 2;
    }
    if ($days > 0) {
        if (intval($_POST['olddeadline']) >= time()) {
            $setsqlarr['deadline'] = intval($_POST['olddeadline']) + ($days * (60 * 60 * 24));
        } else {
            $setsqlarr['deadline'] = strtotime("{$days} day");
        }
    } else {
        $setsqlarr['deadline'] = intval($_POST['olddeadline']);
    }

    if ($hunter_profile['audit'] == "1") {
        $_CFG['audit_verifyhunter_editjob'] <> "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_verifyhunter_editjob']) : '';
    } else {
        $_CFG['audit_unexaminedhunter_editjob'] <> "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_unexaminedhunter_editjob']) : '';
    }

    $setsqlarr['contact'] = !empty($_POST['contact']) ? trim($_POST['contact']) : showmsg('您没有填写联系人！', 1);
    $setsqlarr['qq'] = trim($_POST['qq']);
    $setsqlarr['telephone'] = !empty($_POST['telephone']) ? trim($_POST['telephone']) : showmsg('您没有填写联系电话！', 1);
    check_word($_CFG['filter'], $_POST['telephone']) ? showmsg($_CFG['filter_tips'], 0) : '';
    $setsqlarr['address'] = !empty($_POST['address']) ? trim($_POST['address']) : showmsg('您没有填写联系地址！', 1);
    $setsqlarr['email'] = !empty($_POST['email']) ? trim($_POST['email']) : showmsg('您没有填写联系邮箱！', 1);
    $setsqlarr['notify'] = intval($_POST['notify']);

    $setsqlarr['contact_show'] = intval($_POST['contact_show']);
    $setsqlarr['email_show'] = intval($_POST['email_show']);
    $setsqlarr['telephone_show'] = intval($_POST['telephone_show']);
    $setsqlarr['address_show'] = intval($_POST['address_show']);
    $setsqlarr['qq_show'] = intval($_POST['qq_show']);

    if (!updatetable(table('hunter_jobs'), $setsqlarr, " id='{$id}' AND uid='{$_SESSION['uid']}' "))
        showmsg("保存失败！", 0);
    if ($_CFG['operation_hunter_mode'] == '1') {
        if ($points_rule['hunter_hunterjobs_edit']['value'] > 0) {
            report_deal($_SESSION['uid'], $points_rule['hunter_hunterjobs_edit']['type'], $points_rule['hunter_hunterjobs_edit']['value']);
            $user_points = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $operator = $points_rule['hunter_hunterjobs_edit']['type'] == "1" ? "+" : "-";
            write_memberslog($_SESSION['uid'], 3, 9201, $_SESSION['username'], "修改职位：<strong>{$setsqlarr['jobs_name']}</strong>，({$operator}{$points_rule['hunter_hunterjobs_edit']['value']})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
        }
        if ($days > 0 && $points_rule['hunter_hunterjobs_daily']['value'] > 0) {
            $points_day = intval($_POST['days']) * $points_rule['hunter_hunterjobs_daily']['value'];
            report_deal($_SESSION['uid'], $points_rule['hunter_hunterjobs_daily']['type'], $points_day);
            $user_points = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $operator = $points_rule['hunter_hunterjobs_daily']['type'] == "1" ? "+" : "-";
            write_memberslog($_SESSION['uid'], 3, 9201, $_SESSION['username'], "延长职位({$_POST['jobs_name']})有效期为{$_POST['days']}天，({$operator}{$points_day})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
        }
    }
    $link[0]['text'] = "职位列表";
    $link[0]['href'] = '?act=jobs';
    $link[1]['text'] = "查看修改结果";
    $link[1]['href'] = "?act=edit_jobs&id={$id}";
    $link[2]['text'] = "会员中心首页";
    $link[2]['href'] = "hunter_index.php";
    //
    write_memberslog($_SESSION['uid'], $_SESSION['utype'], 8503, $_SESSION['username'], "修改了职位：{$setsqlarr['jobs_name']}，职位ID：{$id}");
    showmsg("修改成功！", 2, $link);
}
unset($smarty);
?>