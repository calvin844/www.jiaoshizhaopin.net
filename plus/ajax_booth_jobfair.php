<?php

/*
 * 74cms ajax 预定招聘会
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(dirname(__FILE__)) . '/include/plus.common.inc.php');
require_once(dirname(dirname(__FILE__)) . '/include/fun_company.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : '';
if ($act == 'booth') {
    $id = intval($_GET['id']);
    if (intval($_SESSION['utype']) != 1) {
        exit("只有企业会员可以预定！");
    }
    if (empty($id)) {
        exit("ERR");
    }
    $time = time();
    $sql = "select * from " . table('jobfair') . " where id='{$id}' limit 1";
    $jobfair = $db->getone($sql);
    if ($jobfair['predetermined_status'] == "1" && $jobfair['holddates'] > $time && ($jobfair['predetermined_end'] == "0" || $jobfair['predetermined_end'] > $time) && $jobfair['predetermined_web'] == "1") {
        if ($time < $jobfair['predetermined_start']) {
            exit("此招聘会还未开始预订！开始预订时间：" . date("Y-m-d", $jobfair['predetermined_start']));
        }
        if ($db->getone("select * from " . table('jobfair_exhibitors') . " where jobfairid='{$id}' AND uid={$_SESSION['uid']} limit 1")) {
            exit("你已经预定过此招聘会的展位了，不能重复预定");
        }

        if ($_CFG['operation_mode'] == '1') {
            $user_points = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $user_points = $user_points + $user_limit_points;
            if ($jobfair['predetermined_point'] > $user_points) {
                exit("你的" . $_CFG['points_byname'] . "不足，请充值后再预定！");
            }
        } elseif ($_CFG['operation_mode'] == '2') {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            if ($setmeal['jobsfair_num'] <= 0) {
                exit("您累计参加的招聘会已经超过了最大限制，请升级服务套餐！");
            }
        } elseif ($_CFG['operation_mode'] == '3') {
            $_CFG['operation_mode'] = 2;
            $setmeal = get_user_setmeal($_SESSION['uid']);
            if ($setmeal['jobsfair_num'] <= 0) {
                if ($_CFG['setmeal_to_points'] == 1) {
                    $user_points = get_user_points($_SESSION['uid']);
                    if ($jobfair['predetermined_point'] > $user_points) {
                        exit("你的" . $_CFG['points_byname'] . "不足，请充值后再预定！");
                    } else {
                        $_CFG['operation_mode'] = 1;
                    }
                } else {
                    exit("您累计参加的招聘会已经超过了最大限制，请升级服务套餐！");
                }
            }
        }
        $company_profile = get_company($_SESSION['uid']);
        $setsqlarr['jobfairid'] = $id;
        $setsqlarr['uid'] = intval($_SESSION['uid']);
        $setsqlarr['etypr'] = 1;
        $setsqlarr['eaddtime'] = $timestamp;
        $setsqlarr['companyname'] = $company_profile['companyname'];
        $setsqlarr['company_id'] = $company_profile['id'];
        $setsqlarr['company_addtime'] = $company_profile['addtime'];
        $setsqlarr['jobfair_title'] = $jobfair['title'];
        $setsqlarr['jobfair_addtime'] = $jobfair['addtime'];
        $setsqlarr['note'] = "{$_SESSION['username']} 预定了招聘会 《{$jobfair['title']}》 的展位，已成功扣除积分 {$jobfair['predetermined_point']}";
        if (inserttable(table('jobfair_exhibitors'), $setsqlarr)) {
            if ($jobfair['predetermined_point'] > 0 && $_CFG['operation_mode'] == '1') {
                report_deal($_SESSION['uid'], 2, $jobfair['predetermined_point']);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "预定了招聘会 《{$jobfair['title']}》 的展位，(-{$jobfair['predetermined_point']})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})", 1, 1019, "预定招聘会展位", "-{$jobfair['predetermined_point']}", "{$user_points}");
            } elseif ($_CFG['operation_mode'] == '2') {
                action_user_setmeal($_SESSION['uid'], 'jobsfair_num');
                $jobsfair_num = $setmeal['jobsfair_num'] - 1;
                write_memberslog($_SESSION['uid'], 1, 9002, $_SESSION['username'], "预定了招聘会 《{$jobfair['title']}》 的展位，剩余参加招聘会{$jobsfair_num}场次", 2, 1019, "预定招聘会展位", "1", "{$jobsfair_num}");
            }
            write_memberslog($_SESSION['uid'], 1, 1401, $_SESSION['username'], "预定了招聘会 《{$jobfair['title']}》 的展位");
            exit("预定成功！");
        }
    }
}
?>