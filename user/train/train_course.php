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
require_once(dirname(__FILE__) . '/train_common.php');
$smarty->assign('leftmenu', "course");
if ($act == 'course') {
    $wheresql = " WHERE uid='{$_SESSION['uid']}' ";
    $orderby = " order by refreshtime desc";
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $perpage = 10;
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('course') . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $offset = ($page->nowindex - 1) * $perpage;
    $smarty->assign('title', '课程管理 - 培训会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $sql = "SELECT * FROM " . table('course') . $wheresql . $orderby;
    $smarty->assign('courses', get_course($offset, $perpage, $sql, true));
    $smarty->assign('page', $page->show(3));
    $smarty->assign('points_rule', get_cache('points_rule'));
    $smarty->display('member_train/train_courses.htm');
} elseif ($act == 'addcourse') {
    $smarty->assign('subsite', get_subsite_list());
    if ($train_profile['trainname']) {
        $_SESSION['addrand'] = rand(1000, 5000);
        $smarty->assign('addrand', $_SESSION['addrand']);
        $teachers = get_audit_teachers($_SESSION['uid'], $train_profile['id']);
        $link[0]['text'] = '添加讲师';
        $link[0]['href'] = 'train_teacher.php?act=add_teachers';
        if (empty($teachers)) {
            showmsg('发布课程前，请先添加讲师并确定审核通过！', 1, $link);
        }
        $smarty->assign('title', '发布课程 - 培训会员中心 - ' . $_CFG['site_name']);
        $smarty->assign('train_profile', $train_profile);
        if ($_CFG['operation_train_mode'] == "2") {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            $smarty->assign('setmeal', $setmeal);
            $smarty->assign('add_mode', 2);
        } elseif ($_CFG['operation_train_mode'] == "1") {
            $smarty->assign('points_total', get_user_points($_SESSION['uid']));
            $smarty->assign('points', get_cache('points_rule'));
            $smarty->assign('add_mode', 1);
        }

        $smarty->assign('user', $user);
        $smarty->assign('teachers', $teachers);
        $captcha = get_cache('captcha');
        $smarty->assign('verify_addcourse', $captcha['verify_addcourse']);
        $smarty->display('member_train/train_addcourse.htm');
    } else {
        $link[0]['text'] = "完善机构资料";
        $link[0]['href'] = 'train_info.php?act=train_profile';
        showmsg("为了达到更好的宣传效果，请先完善您的机构资料！", 1, $link);
    }
} elseif ($act == 'addcourse_save') {
    $captcha = get_cache('captcha');
    $postcaptcha = trim($_POST['postcaptcha']);
    if ($captcha['verify_addcourse'] == '1' && empty($postcaptcha)) {
        showmsg("请填写验证码", 1);
    }
    if ($captcha['verify_addcourse'] == '1' && strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        showmsg("验证码错误", 1);
    }
    $add_mode = trim($_POST['add_mode']);
    $days = intval($_POST['days']);
    if ($days < $_CFG['course_add_days_min']) {
        showmsg("有效时间最少为 " . $_CFG['course_add_days_min'] . " 天！", 1);
    }
    if ($_CFG['operation_train_mode'] == '1') {
        $points_rule = get_cache('points_rule');
        $user_points = get_user_points($_SESSION['uid']);
        $user_limit_points = get_user_limit_points($_SESSION['uid']);
        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
        $user_points = $user_points + $user_limit_points;
        $total = 0;
        if ($points_rule['course_add']['type'] == "2" && $points_rule['course_add']['value'] > 0) {
            $total = $points_rule['course_add']['value'];
        }
        if ($points_rule['course_daily']['type'] == "2" && $points_rule['course_daily']['value'] > 0) {
            $total = $total + ($days * $points_rule['course_daily']['value']);
        }
        if ($total > $user_points) {
            $link[0]['text'] = "立即充值";
            $link[0]['href'] = 'train_service.php?act=order_add';
            $link[1]['text'] = "会员中心首页";
            $link[1]['href'] = 'train_index.php?act=';
            showmsg("你的" . $_CFG['train_points_byname'] . "不足，请充值后再发布！", 0, $link);
        }
        $setsqlarr['setmeal_deadline'] = 0;
    } elseif ($_CFG['operation_train_mode'] == '2') {
        $link[0]['text'] = "立即开通服务";
        $link[0]['href'] = 'train_service.php?act=setmeal_list';
        $link[1]['text'] = "会员中心首页";
        $link[1]['href'] = 'train_index.php?act=';
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            showmsg("您的服务已经到期，请重新开通", 1, $link);
        }
        if ($setmeal['course_num'] <= 0) {
            showmsg("当前发布的课程已经超过了最大限制，请升级服务套餐！", 1, $link);
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
        $setsqlarr['trainname'] = $train_profile['trainname'];
        $setsqlarr['train_id'] = $train_profile['id'];
        $setsqlarr['subsite_id'] = intval($_POST['subsite_id']);

        $setsqlarr['course_name'] = !empty($_POST['course_name']) ? trim($_POST['course_name']) : showmsg('您没有填写课程名称！', 1);
        $setsqlarr['category'] = !empty($_POST['category']) ? intval($_POST['category']) : showmsg('请选择课程类别！', 1);
        $setsqlarr['category_cn'] = trim($_POST['category_cn']);
        $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : showmsg('请选择开课地区！', 1);
        $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
        $setsqlarr['district_cn'] = trim($_POST['district_cn']);
        $setsqlarr['classtype'] = !empty($_POST['classtype']) ? intval($_POST['classtype']) : showmsg('请选择上课班制！', 1);
        $setsqlarr['classtype_cn'] = trim($_POST['classtype_cn']);
        $setsqlarr['teacher_id'] = !empty($_POST['teacher_id']) ? intval($_POST['teacher_id']) : showmsg('请选择主讲人！', 1);
        $setsqlarr['teacher_cn'] = trim($_POST['teacher_cn']);
        $setsqlarr['starttime'] = intval(convert_datefm($_POST['starttime'], 2));
        if (empty($setsqlarr['starttime'])) {
            showmsg('请填写开课时间！时间格式：YYYY-MM-DD', 1);
        }
        $setsqlarr['train_object'] = !empty($_POST['train_object']) ? trim($_POST['train_object']) : showmsg('您没有填写授课对象！', 1);
        $setsqlarr['train_certificate'] = !empty($_POST['train_certificate']) ? trim($_POST['train_certificate']) : '';
        $setsqlarr['classhour'] = !empty($_POST['classhour']) ? intval($_POST['classhour']) : showmsg('您没有填写授课学时！', 1);
        $setsqlarr['train_expenses'] = !empty($_POST['train_expenses']) ? intval($_POST['train_expenses']) : showmsg('您没有填写培训费用！', 1);
        $setsqlarr['favour_expenses'] = !empty($_POST['favour_expenses']) ? intval($_POST['favour_expenses']) : showmsg('您没有填写优惠价格！', 1);

        $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : showmsg('您没有填写课程描述！', 1);
        check_word($_CFG['filter'], $_POST['contents']) ? showmsg($_CFG['filter_tips'], 0) : '';
        $setsqlarr['addtime'] = $timestamp;
        $setsqlarr['deadline'] = strtotime("" . intval($_POST['days']) . " day");
        $setsqlarr['refreshtime'] = $timestamp;
        $setsqlarr['key'] = $setsqlarr['course_name'] . $train_profile['trainname'] . $setsqlarr['teacher_cn'] . $setsqlarr['train_certificate'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'];
        require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
        $sp = new SPWord();
        $setsqlarr['key'] = "{$setsqlarr['course_name']} {$train_profile['trainname']} {$setsqlarr['teacher_cn']} {$setsqlarr['train_certificate']} " . $sp->extracttag($setsqlarr['key']);
        $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
        $setsqlarr['likekey'] = "{$setsqlarr['course_name']},{$train_profile['trainname']},{$setsqlarr['teacher_cn']},{$setsqlarr['train_certificate']}";
        $setsqlarr['tpl'] = $train_profile['tpl'];
        $setsqlarr['map_x'] = $train_profile['map_x'];
        $setsqlarr['map_y'] = $train_profile['map_y'];
        if ($train_profile['audit'] == "1") {
            $setsqlarr['audit'] = intval($_CFG['audit_verifytrain_addcourse']);
        } else {
            $setsqlarr['audit'] = intval($_CFG['audit_unexaminedtrain_addcourse']);
        }
        $setsqlarr_contact['contact'] = !empty($_POST['contact']) ? trim($_POST['contact']) : showmsg('您没有填写联系人！', 1);
        $setsqlarr_contact['qq'] = trim($_POST['qq']);
        $setsqlarr_contact['telephone'] = !empty($_POST['telephone']) ? trim($_POST['telephone']) : showmsg('您没有填写联系电话！', 1);
        check_word($_CFG['filter'], $_POST['telephone']) ? showmsg($_CFG['filter_tips'], 0) : '';
        $setsqlarr_contact['address'] = !empty($_POST['address']) ? trim($_POST['address']) : showmsg('您没有填写联系地址！', 1);
        $setsqlarr_contact['email'] = !empty($_POST['email']) ? trim($_POST['email']) : showmsg('您没有填写联系邮箱！', 1);
        $setsqlarr_contact['notify'] = intval($_POST['notify']);

        $setsqlarr_contact['contact_show'] = intval($_POST['contact_show']);
        $setsqlarr_contact['email_show'] = intval($_POST['email_show']);
        $setsqlarr_contact['telephone_show'] = intval($_POST['telephone_show']);
        $setsqlarr_contact['address_show'] = intval($_POST['address_show']);
        $setsqlarr_contact['qq_show'] = intval($_POST['qq_show']);

        //添加课程信息
        $pid = inserttable(table('course'), $setsqlarr, true);
        empty($pid) ? showmsg("添加失败！", 0) : '';
        //添加联系方式
        $setsqlarr_contact['pid'] = $pid;
        !inserttable(table('course_contact'), $setsqlarr_contact) ? showmsg("添加失败！", 0) : '';
        if ($_CFG['operation_train_mode'] == '1') {
            if ($points_rule['course_add']['value'] > 0) {
                report_deal($_SESSION['uid'], $points_rule['course_add']['type'], $points_rule['course_add']['value']);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $points_rule['course_add']['type'] == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 4, 9101, $_SESSION['username'], "发布了课程：<strong>{$setsqlarr['course_name']}</strong>，({$operator}{$points_rule['course_add']['value']})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
            }
            if (intval($_POST['days']) > 0 && $points_rule['course_daily']['value'] > 0) {
                $points_day = intval($_POST['days']) * $points_rule['course_daily']['value'];
                report_deal($_SESSION['uid'], $points_rule['course_daily']['type'], $points_day);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $points_rule['course_daily']['type'] == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 4, 9101, $_SESSION['username'], "发布普通课程:<strong>{$_POST['course_name']}</strong>，有效期为{$_POST['days']}天，({$operator}{$points_day})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
            }
        } elseif ($_CFG['operation_train_mode'] == '2') {
            action_user_setmeal($_SESSION['uid'], "course_num");
            $setmeal = get_user_setmeal($_SESSION['uid']);
            write_memberslog($_SESSION['uid'], 4, 9102, $_SESSION['username'], "发布普通课程:<strong>{$_POST['course_name']}</strong>，还可以发布普通课程:<strong>{$setmeal['course_num']}</strong>条");
        }
    }

    $link[0]['text'] = '继续添加课程';
    $link[0]['href'] = 'train_course.php?act=addcourse';
    $link[1]['text'] = "会员中心首页";
    $link[1]['href'] = 'train_index.php?act=';
    showmsg("发布成功！", 2, $link);
} elseif ($act == 'course_perform') {
    $yid = !empty($_POST['y_id']) ? $_POST['y_id'] : $_GET['y_id'];
    $course_num = count($yid);
    if (empty($yid)) {
        showmsg("你没有选择课程！", 1);
    }
    $refresh = !empty($_POST['refresh']) ? $_POST['refresh'] : $_GET['refresh'];
    $delete = !empty($_POST['delete']) ? $_POST['delete'] : $_GET['delete'];
    if ($refresh) {

        if ($_CFG['operation_train_mode'] == '1') {
            $refrestime = get_last_refresh_date($_SESSION['uid'], "4001");
            $duringtime = time() - $refrestime['max(addtime)'];
            $space = $_CFG['train_pointsmode_refresh_space'] * 60;
            $refresh_time = get_today_refresh_times($_SESSION['uid'], "4001");
            if ($_CFG['train_pointsmode_refresh_time'] != 0 && ($refresh_time['count(*)'] >= $_CFG['train_pointsmode_refresh_time'])) {
                showmsg("每天最多只能刷新" . $_CFG['train_pointsmode_refresh_time'] . "次,您今天已超过最大刷新次数限制！", 2);
            } elseif ($duringtime <= $space) {
                showmsg($_CFG['train_pointsmode_refresh_space'] . "分钟内不能重复刷新课程！", 2);
            } else {
                $points_rule = get_cache('points_rule');
                if ($points_rule['course_refresh']['value'] > 0) {
                    $user_points = get_user_points($_SESSION['uid']);
                    $user_limit_points = get_user_limit_points($_SESSION['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $user_points = $user_points + $user_limit_points;
                    $total_point = $course_num * $points_rule['course_refresh']['value'];
                    if ($total_point > $user_points && $points_rule['course_refresh']['type'] == "2") {
                        $link[0]['text'] = "返回上一页";
                        $link[0]['href'] = 'javascript:history.go(-1)';
                        $link[1]['text'] = "立即充值";
                        $link[1]['href'] = 'train_service.php?act=order_add';
                        showmsg("您的" . $_CFG['train_points_byname'] . "不足，请先充值！", 0, $link);
                    }
                    report_deal($_SESSION['uid'], $points_rule['course_refresh']['type'], $total_point);
                    $user_points = get_user_points($_SESSION['uid']);
                    $user_limit_points = get_user_limit_points($_SESSION['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $operator = $points_rule['course_refresh']['type'] == "1" ? "+" : "-";
                    write_memberslog($_SESSION['uid'], 4, 9101, $_SESSION['username'], "刷新课程了{$course_num}条课程，({$operator}{$total_point})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
                }
            }
        } elseif ($_CFG['operation_train_mode'] == '2') {
            //限制刷新时间
            //最经一次的刷新时间
            $link[0]['text'] = "立即开通服务";
            $link[0]['href'] = 'train_service.php?act=setmeal_list';
            $link[1]['text'] = "会员中心首页";
            $link[1]['href'] = 'train_index.php?act=';
            $setmeal = get_user_setmeal($_SESSION['uid']);

            if (empty($setmeal)) {
                showmsg("您还没有开通服务，请开通", 1, $link);
            } elseif ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
                showmsg("您的服务已经到期，请重新开通", 1, $link);
            } else {
                $refrestime = get_last_refresh_date($_SESSION['uid'], "4001");
                $duringtime = time() - $refrestime['max(addtime)'];
                $space = $setmeal['refresh_course_space'] * 60;
                $refresh_time = get_today_refresh_times($_SESSION['uid'], "4001");
                if ($setmeal['refresh_course_time'] != 0 && ($refresh_time['count(*)'] >= $setmeal['refresh_course_time'])) {
                    showmsg("每天最多只能刷新" . $setmeal['refresh_course_time'] . "次,您今天已超过最大刷新次数限制！", 2);
                } elseif ($duringtime <= $space) {
                    showmsg($setmeal['refresh_course_space'] . "分钟内不能重复刷新课程！", 2);
                }
            }
        }
        refresh_course($yid, $_SESSION['uid']);
        write_memberslog($_SESSION['uid'], 4, 8203, $_SESSION['username'], "刷新课程");
        write_refresh_log($_SESSION['uid'], 4001);
        showmsg("刷新课程成功！", 2);
    } elseif ($delete) {
        if ($n = del_course($yid, $_SESSION['uid'])) {
            showmsg("删除成功！共删除 {$n} 行", 2);
        } else {
            showmsg("删除失败！", 0);
        }
    } elseif (!empty($_POST['display1'])) {
        activate_course($yid, 1, $_SESSION['uid']);
        showmsg("设置成功！", 2);
    } elseif (!empty($_POST['display2'])) {
        activate_course($yid, 2, $_SESSION['uid']);
        showmsg("设置成功！", 2);
    }
} elseif ($act == 'editcourse') {
    $course = get_course_one(intval($_GET['id']), $_SESSION['uid']);
    if (empty($course))
        showmsg("参数错误！", 1);
    $smarty->assign('subsite', get_subsite_list());
    $teachers = get_audit_teachers($_SESSION['uid'], $train_profile['id']);
    $smarty->assign('user', $user);
    $smarty->assign('title', '修改课程 - 培训会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('points_total', get_user_points($_SESSION['uid']));
    $smarty->assign('points', get_cache('points_rule'));
    $smarty->assign('course', $course);
    $smarty->assign('teachers', $teachers);
    $smarty->display('member_train/train_editcourse.htm');
}
elseif ($act == 'editcourse_save') {
    $id = intval($_POST['id']);
    $days = intval($_POST['days']);
    if ($_CFG['operation_train_mode'] == '1') {
        $add_mode = 1;
        $points_rule = get_cache('points_rule');
        $user_points = get_user_points($_SESSION['uid']);
        $user_limit_points = get_user_limit_points($_SESSION['uid']);
        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
        $user_points = $user_points + $user_limit_points;
        $total = 0;
        if ($points_rule['course_edit']['type'] == "2" && $points_rule['course_edit']['value'] > 0) {
            $total = $points_rule['course_edit']['value'];
        }
        if ($points_rule['course_daily']['type'] == "2" && $points_rule['course_daily']['value'] > 0) {
            $total = $total + ($days * $points_rule['course_daily']['value']);
        }
        if ($total > $user_points) {
            $link[0]['text'] = "返回上一页";
            $link[0]['href'] = 'javascript:history.go(-1)';
            $link[1]['text'] = "立即充值";
            $link[1]['href'] = 'train_service.php?act=order_add';
            showmsg("你的" . $_CFG['train_points_byname'] . "不足，请充值后再发布！", 0, $link);
        }
    } elseif ($_CFG['operation_train_mode'] == '2') {
        $add_mode = 2;
        $link[0]['text'] = "立即开通服务";
        $link[0]['href'] = 'train_service.php?act=setmeal_list';
        $link[1]['text'] = "会员中心首页";
        $link[1]['href'] = 'train_index.php?act=';
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') {
            showmsg("此课程通过服务套餐发布，您的套餐已经到期，请重新开通", 1, $link);
        }
        $setsqlarr['setmeal_deadline'] = $setmeal['endtime'];
        $setsqlarr['setmeal_id'] = $setmeal['setmeal_id'];
        $setsqlarr['setmeal_name'] = $setmeal['setmeal_name'];
    }

    $setsqlarr['add_mode'] = intval($add_mode);
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['trainname'] = $train_profile['trainname'];
    $setsqlarr['train_id'] = $train_profile['id'];

    $setsqlarr['course_name'] = !empty($_POST['course_name']) ? trim($_POST['course_name']) : showmsg('您没有填写课程名称！', 1);
    $setsqlarr['category'] = !empty($_POST['category']) ? intval($_POST['category']) : showmsg('请选择课程类别！', 1);
    $setsqlarr['category_cn'] = trim($_POST['category_cn']);
    $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : showmsg('请选择开课地区！', 1);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);
    $setsqlarr['classtype'] = !empty($_POST['classtype']) ? intval($_POST['classtype']) : showmsg('请选择上课班制！', 1);
    $setsqlarr['classtype_cn'] = trim($_POST['classtype_cn']);
    $setsqlarr['teacher_id'] = !empty($_POST['teacher_id']) ? intval($_POST['teacher_id']) : showmsg('请选择主讲人！', 1);
    $setsqlarr['teacher_cn'] = trim($_POST['teacher_cn']);
    $setsqlarr['starttime'] = intval(convert_datefm($_POST['starttime'], 2));
    if (empty($setsqlarr['starttime'])) {
        showmsg('请填写开课时间！时间格式：YYYY-MM-DD', 1);
    }
    $setsqlarr['train_object'] = !empty($_POST['train_object']) ? trim($_POST['train_object']) : showmsg('您没有填写授课对象！', 1);
    $setsqlarr['train_certificate'] = !empty($_POST['train_certificate']) ? trim($_POST['train_certificate']) : '';
    $setsqlarr['classhour'] = !empty($_POST['classhour']) ? intval($_POST['classhour']) : showmsg('您没有填写授课学时！', 1);
    $setsqlarr['train_expenses'] = !empty($_POST['train_expenses']) ? intval($_POST['train_expenses']) : showmsg('您没有填写培训费用！', 1);
    $setsqlarr['favour_expenses'] = !empty($_POST['favour_expenses']) ? intval($_POST['favour_expenses']) : showmsg('您没有填写优惠价格！', 1);

    $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : showmsg('您没有填写课程描述！', 1);
    check_word($_CFG['filter'], $_POST['contents']) ? showmsg($_CFG['filter_tips'], 0) : '';
    if ($days > 0) {
        if (intval($_POST['olddeadline']) >= time()) {
            $setsqlarr['deadline'] = intval($_POST['olddeadline']) + ($days * (60 * 60 * 24));
        } else {
            $setsqlarr['deadline'] = strtotime("{$days} day");
        }
    }

    $setsqlarr['refreshtime'] = $timestamp;
    $setsqlarr['key'] = $setsqlarr['course_name'] . $train_profile['trainname'] . $setsqlarr['teacher_cn'] . $setsqlarr['train_certificate'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'];
    require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
    $sp = new SPWord();
    $setsqlarr['key'] = "{$setsqlarr['course_name']} {$train_profile['trainname']} {$setsqlarr['teacher_cn']} {$setsqlarr['train_certificate']} " . $sp->extracttag($setsqlarr['key']);
    $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
    $setsqlarr['likekey'] = "{$setsqlarr['course_name']},{$train_profile['trainname']},{$setsqlarr['teacher_cn']},{$setsqlarr['train_certificate']}";
    $setsqlarr['subsite_id'] = intval($_POST['subsite_id']);
    $setsqlarr['tpl'] = $train_profile['tpl'];
    $setsqlarr['map_x'] = $train_profile['map_x'];
    $setsqlarr['map_y'] = $train_profile['map_y'];
    if ($train_profile['audit'] == "1") {
        $setsqlarr['audit'] = intval($_CFG['audit_verifytrain_editcourse']);
    } else {
        $setsqlarr['audit'] = intval($_CFG['audit_unexaminedtrain_editcourse']);
    }
    $setsqlarr_contact['contact'] = !empty($_POST['contact']) ? trim($_POST['contact']) : showmsg('您没有填写联系人！', 1);
    $setsqlarr_contact['qq'] = trim($_POST['qq']);
    $setsqlarr_contact['telephone'] = !empty($_POST['telephone']) ? trim($_POST['telephone']) : showmsg('您没有填写联系电话！', 1);
    check_word($_CFG['filter'], $_POST['telephone']) ? showmsg($_CFG['filter_tips'], 0) : '';
    $setsqlarr_contact['address'] = !empty($_POST['address']) ? trim($_POST['address']) : showmsg('您没有填写联系地址！', 1);
    $setsqlarr_contact['email'] = !empty($_POST['email']) ? trim($_POST['email']) : showmsg('您没有填写联系邮箱！', 1);
    $setsqlarr_contact['notify'] = intval($_POST['notify']);

    $setsqlarr_contact['contact_show'] = intval($_POST['contact_show']);
    $setsqlarr_contact['email_show'] = intval($_POST['email_show']);
    $setsqlarr_contact['telephone_show'] = intval($_POST['telephone_show']);
    $setsqlarr_contact['address_show'] = intval($_POST['address_show']);
    $setsqlarr_contact['qq_show'] = intval($_POST['qq_show']);
    if (!updatetable(table('course'), $setsqlarr, " id='{$id}' AND uid='{$_SESSION['uid']}' "))
        showmsg("保存失败！", 0);
    if (!updatetable(table('course_contact'), $setsqlarr_contact, " pid='{$id}' "))
        showmsg("保存失败！", 0);
    if ($_CFG['operation_train_mode'] == '1') {
        if ($points_rule['course_edit']['value'] > 0) {
            report_deal($_SESSION['uid'], $points_rule['course_edit']['type'], $points_rule['course_edit']['value']);
            $user_points = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $operator = $points_rule['course_edit']['type'] == "1" ? "+" : "-";
            write_memberslog($_SESSION['uid'], 4, 9101, $_SESSION['username'], "修改课程：<strong>{$setsqlarr['course_name']}</strong>，({$operator}{$points_rule['course_edit']['value']})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
        }
        if ($days > 0 && $points_rule['course_daily']['value'] > 0) {
            $points_day = intval($_POST['days']) * $points_rule['course_daily']['value'];
            report_deal($_SESSION['uid'], $points_rule['course_daily']['type'], $points_day);
            $user_points = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $operator = $points_rule['course_daily']['type'] == "1" ? "+" : "-";
            write_memberslog($_SESSION['uid'], 4, 9101, $_SESSION['username'], "延长课程({$_POST['course_name']})有效期为{$_POST['days']}天，({$operator}{$points_day})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
        }
    }
    $link[0]['text'] = "课程列表";
    $link[0]['href'] = '?act=course';
    $link[1]['text'] = "查看修改结果";
    $link[1]['href'] = "?act=editcourse&id={$id}";
    $link[2]['text'] = "会员中心首页";
    $link[2]['href'] = "train_index.php";
    write_memberslog($_SESSION['uid'], $_SESSION['utype'], 8202, $_SESSION['username'], "修改了课程：{$setsqlarr['course_name']}，课程ID：{$id}");
    showmsg("修改成功！", 2, $link);
}
unset($smarty);
?>