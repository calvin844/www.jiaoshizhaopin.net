<?php

/*
 * 74cms �ƻ����� ÿ������ͳ��
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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
        //����ģʽ
        if ($_CFG['operation_mode'] == '1') {
            //����ˢ��ʱ��
            //���һ�ε�ˢ��ʱ��
            $refrestime = get_last_refresh_date($l['uid'], "1001");
            $duringtime = time() - $refrestime['max(addtime)'];
            $space = $_CFG['com_pointsmode_refresh_space'] * 60;
            $refresh_time = get_today_refresh_times($l['uid'], "1001");
            if ($_CFG['com_pointsmode_refresh_time'] != 0 && ($refresh_time['count(*)'] >= $_CFG['com_pointsmode_refresh_time'])) {
                //showmsg("ÿ�����ֻ��ˢ��" . $_CFG['com_pointsmode_refresh_time'] . "��,�������ѳ������ˢ�´������ƣ�", 2);
                continue;
            } elseif ($duringtime <= $space) {
                //showmsg($_CFG['com_pointsmode_refresh_space'] . "�����ڲ����ظ�ˢ��ְλ��", 2);
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
                        //showmsg("����" . $_CFG['points_byname'] . "���㣬���ȳ�ֵ��", 0, $link);
                        continue;
                    }
                    report_deal($l['uid'], $points_rule['jobs_refresh']['type'], $total_point);
                    $user_points = get_user_points($l['uid']);
                    $user_limit_points = get_user_limit_points($l['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $operator = $points_rule['jobs_refresh']['type'] == "1" ? "+" : "-";
                    write_memberslog($l['uid'], 1, 9001, "ϵͳ��ʱˢ��", "�Զ�ˢ����1��ְλ��({$operator}{$total_point})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})", 1, 1003, "ˢ��ְλ", "{$operator}{$total_point}", "{$user_points}");
                }
            }
        }
        //�ײ�ģʽ
        elseif ($_CFG['operation_mode'] == '2') {
            //����ˢ��ʱ��
            //�һ�ε�ˢ��ʱ��
            $setmeal = get_user_setmeal($l['uid']);
            if (empty($setmeal)) {
                //showmsg("����û�п�ͨ�����뿪ͨ", 1, $link);
                continue;
            } elseif ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
                //showmsg("���ķ����Ѿ����ڣ������¿�ͨ", 1, $link);
                continue;
            } else {
                $refrestime = get_last_refresh_date($l['uid'], "1001");
                $duringtime = time() - $refrestime['max(addtime)'];
                $space = $setmeal['refresh_jobs_space'] * 60;
                $refresh_time = get_today_refresh_times($l['uid'], "1001");
                if ($setmeal['refresh_jobs_time'] != 0 && ($refresh_time['count(*)'] >= $setmeal['refresh_jobs_time'])) {
                    //showmsg("ÿ�����ֻ��ˢ��" . $setmeal['refresh_jobs_time'] . "��,�������ѳ������ˢ�´������ƣ�", 2);
                    continue;
                } elseif ($duringtime <= $space) {
                    //showmsg($setmeal['refresh_jobs_space'] . "�����ڲ����ظ�ˢ��ְλ��", 2);
                    continue;
                }
            }
        }
        //���ģʽ
        elseif ($_CFG['operation_mode'] == '3') {
            //����ˢ��ʱ��
            //�һ�ε�ˢ��ʱ��
            $setmeal = get_user_setmeal($l['uid']);
            $refrestime = get_last_refresh_date($l['uid'], "1001");
            $duringtime = time() - $refrestime['max(addtime)'];
            $space = $setmeal['refresh_jobs_space'] * 60;
            $refresh_time = get_today_refresh_times($l['uid'], "1001");
            if ($setmeal['refresh_jobs_time'] != 0 && ($refresh_time['count(*)'] >= $setmeal['refresh_jobs_time'])) {
                //showmsg("ÿ�����ֻ��ˢ��" . $setmeal['refresh_jobs_time'] . "��,�������ѳ������ˢ�´������ƣ�", 2);
                continue;
            } elseif ($duringtime <= $space) {
                //showmsg($setmeal['refresh_jobs_space'] . "�����ڲ����ظ�ˢ��ְλ��", 2);
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
                        //showmsg("����" . $_CFG['points_byname'] . "���㣬���ȳ�ֵ��", 0, $link);
                        continue;
                    }
                    report_deal($l['uid'], $points_rule['jobs_refresh']['type'], $total_point);
                    $user_points = get_user_points($l['uid']);
                    $user_limit_points = get_user_limit_points($l['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $operator = $points_rule['jobs_refresh']['type'] == "1" ? "+" : "-";
                    write_memberslog($l['uid'], 1, 9001, "ϵͳ��ʱˢ��", "�Զ�ˢ����1��ְλ��({$operator}{$total_point})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})", 1, 1003, "ˢ��ְλ", "{$operator}{$total_point}", "{$user_points}");
                }
            }
        }

        refresh_jobs($l['job_id'], $l['uid']);
        write_memberslog($l['uid'], 1, 2004, "ϵͳ��ʱˢ��", "�Զ�ˢ��ְλ");
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