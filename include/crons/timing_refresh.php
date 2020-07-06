<?php

/*
 * 74cms 计划任务 每日数据统计
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(QISHI_ROOT_PATH . 'include/fun_company.php');
global $_CFG;
$start_id = intval($_GET['start_id']) > 0 ? intval($_GET['start_id']) : 0;
$now_day = strtotime(date('Y-m-d'));
$now = time();
$end_id = "";
$list = $db->getall("select * from " . table('timing_refresh_job') . " where id > " . $start_id . " and last_time <= " . $now_day . "  order by id asc limit 1000");
if (!empty($list)) {
    foreach ($list as $l) {
        $hour = substr($l['time'], 0, 2);
        $min = substr($l['time'], 2, 2);
        $time_str = date('Y-m-d') . " " . $hour . ":" . $min;
        $do_time = strtotime($time_str);
        if ($now < $do_time) {
            continue;
        }
        $jobs_info = $db->getone("select * from " . table('jobs') . " where id=" . $l['job_id']);
        if (empty($jobs_info)) {
            $db->query("Delete from " . table('timing_refresh_job') . " WHERE id='" . intval($l['id']) . "'");
            continue;
        }
        if ($jobs_info['deadline'] < time()) {
            $db->query("Delete from " . table('timing_refresh_job') . " WHERE id='" . intval($l['id']) . "'");
            continue;
        }
        //积分模式
        if ($_CFG['operation_mode'] == '1') {
            //限制刷新时间
            //最后一次的刷新时间
            $refrestime = get_last_refresh_date($l['uid'], "1001");
            $duringtime = time() - $refrestime['max(addtime)'];
            $space = $_CFG['com_pointsmode_refresh_space'] * 60;
            $refresh_time = get_today_refresh_times($l['uid'], "1001");
            if ($_CFG['com_pointsmode_refresh_time'] != 0 && ($refresh_time['count(*)'] >= $_CFG['com_pointsmode_refresh_time'])) {
                //showmsg("每天最多只能刷新" . $_CFG['com_pointsmode_refresh_time'] . "次,您今天已超过最大刷新次数限制！", 2);
                continue;
            } elseif ($duringtime <= $space) {
                //showmsg($_CFG['com_pointsmode_refresh_space'] . "分钟内不能重复刷新职位！", 2);
                continue;
            } else {
                $points_rule = get_cache('points_rule');
                if ($points_rule['jobs_refresh']['value'] > 0) {
                    $user_points = get_user_points($l['uid']);
                    $user_limit_points = get_user_limit_points($l['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $user_points = $user_points + $user_limit_points;
                    $total_point = $jobs_num * $points_rule['jobs_refresh']['value'];
                    if ($total_point > $user_points && $points_rule['jobs_refresh']['type'] == "2") {
                        //showmsg("您的" . $_CFG['points_byname'] . "不足，请先充值！", 0, $link);
                        continue;
                    }
                    report_deal($l['uid'], $points_rule['jobs_refresh']['type'], $total_point);
                    $user_points = get_user_points($l['uid']);
                    $user_limit_points = get_user_limit_points($l['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $operator = $points_rule['jobs_refresh']['type'] == "1" ? "+" : "-";
                    write_memberslog($l['uid'], 1, 9001, "系统定时刷新", "自动刷新了1条职位，({$operator}{$total_point})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})", 1, 1003, "刷新职位", "{$operator}{$total_point}", "{$user_points}");
                }
            }
        }
        //套餐模式
        elseif ($_CFG['operation_mode'] == '2') {
            //限制刷新时间
            //最经一次的刷新时间
            $setmeal = get_user_setmeal($l['uid']);
            if (empty($setmeal)) {
                //showmsg("您还没有开通服务，请开通", 1, $link);
                continue;
            } elseif ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
                //showmsg("您的服务已经到期，请重新开通", 1, $link);
                continue;
            } else {
                $refrestime = get_last_refresh_date($l['uid'], "1001");
                $duringtime = time() - $refrestime['max(addtime)'];
                $space = $setmeal['refresh_jobs_space'] * 60;
                $refresh_time = get_today_refresh_times($l['uid'], "1001");
                if ($setmeal['refresh_jobs_time'] != 0 && ($refresh_time['count(*)'] >= $setmeal['refresh_jobs_time'])) {
                    //showmsg("每天最多只能刷新" . $setmeal['refresh_jobs_time'] . "次,您今天已超过最大刷新次数限制！", 2);
                    continue;
                } elseif ($duringtime <= $space) {
                    //showmsg($setmeal['refresh_jobs_space'] . "分钟内不能重复刷新职位！", 2);
                    continue;
                }
            }
        }
        //混合模式
        elseif ($_CFG['operation_mode'] == '3') {
            //限制刷新时间
            //最经一次的刷新时间
            $setmeal = get_user_setmeal($l['uid']);
            $refrestime = get_last_refresh_date($l['uid'], "1001");
            $duringtime = time() - $refrestime['max(addtime)'];
            $space = $setmeal['refresh_jobs_space'] * 60;
            $refresh_time = get_today_refresh_times($l['uid'], "1001");
            if ($setmeal['refresh_jobs_time'] != 0 && ($refresh_time['count(*)'] >= $setmeal['refresh_jobs_time'])) {
                //showmsg("每天最多只能刷新" . $setmeal['refresh_jobs_time'] . "次,您今天已超过最大刷新次数限制！", 2);
                continue;
            } elseif ($duringtime <= $space) {
                //showmsg($setmeal['refresh_jobs_space'] . "分钟内不能重复刷新职位！", 2);
                continue;
            } else {
                $points_rule = get_cache('points_rule');
                if ($points_rule['jobs_refresh']['value'] > 0) {
                    $user_points = get_user_points($l['uid']);
                    $user_limit_points = get_user_limit_points($l['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $user_points = $user_points + $user_limit_points;
                    $total_point = $jobs_num * $points_rule['jobs_refresh']['value'];
                    if ($total_point > $user_points && $points_rule['jobs_refresh']['type'] == "2") {
                        //showmsg("您的" . $_CFG['points_byname'] . "不足，请先充值！", 0, $link);
                        continue;
                    }
                    report_deal($l['uid'], $points_rule['jobs_refresh']['type'], $total_point);
                    $user_points = get_user_points($l['uid']);
                    $user_limit_points = get_user_limit_points($l['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $operator = $points_rule['jobs_refresh']['type'] == "1" ? "+" : "-";
                    write_memberslog($l['uid'], 1, 9001, "系统定时刷新", "自动刷新了1条职位，({$operator}{$total_point})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})", 1, 1003, "刷新职位", "{$operator}{$total_point}", "{$user_points}");
                }
            }
        }

        refresh_jobs($l['job_id'], $l['uid']);
        write_memberslog($l['uid'], 1, 2004, "系统定时刷新", "自动刷新职位");
        write_refresh_log($l['uid'], 1001);

        $setsqlarr1['last_time'] = $now_day + 86400;
        $wheresql1 = " id= " . $l['id'];
        updatetable(table('timing_refresh_job'), $setsqlarr1, $wheresql1);
        $end_id = $l['id'];
    }
}
if (!empty($end_id)) {
    header("Location:http://" . $_SERVER ['HTTP_HOST'] . "/user/do_crons.php?id=10&start_id=" . $end_id);
}
//var_dump($end_id);
//exit;
?>