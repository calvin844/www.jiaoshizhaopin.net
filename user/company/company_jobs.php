<?php

/*
 * 74cms ��ҵ��Ա����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/company_common.php');
$smarty->assign('leftmenu', "jobs");
if ($act == 'jobs') {
    $jobtype = intval($_GET['jobtype']);
    $wheresql = " WHERE uid='{$_SESSION['uid']}' ";
    $orderby = " order by refreshtime desc";
    switch ($jobtype) {
        case 1:
            $tabletype = "jobs";
            $templates = "company_jobs_type1";
            break;
        case 2:
            $tabletype = "jobs_tmp";
            $wheresql.=" AND display=2";
            $templates = "company_jobs_type2";
            break;
        case 3:
            $tabletype = "jobs_tmp";
            $wheresql.=" AND audit=2 ";
            $templates = "company_jobs_type3";
            break;
        case 4:
            $tabletype = "jobs_tmp";
            $wheresql.=" AND audit=3 ";
            $templates = "company_jobs_type4";
            break;
        case 5:
            $tabletype = "jobs_tmp";
            $wheresql.=" AND deadline<" . time() . " ";
            $templates = "company_jobs_type5";
            break;
        case 6:
            $tabletype = "jobs";
            $templates = "company_job_timing_refresh";
            break;
        default:
            $tabletype = "jobs";
            $templates = "company_jobs_type1";
            break;
    }
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $perpage = 10;
    if ($tabletype == "all") {
        $total_sql = "SELECT COUNT(*) AS num FROM " . table('jobs') . $wheresql . " UNION ALL  SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . $wheresql;
    } else {
        $total_sql = "SELECT COUNT(*) AS num FROM " . table($tabletype) . $wheresql;
    }
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $offset = ($page->nowindex - 1) * $perpage;
    $smarty->assign('title', 'ְλ���� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $smarty->assign('audit', $audit);
    if ($tabletype == "all") {
        $sql = "SELECT * FROM " . table('jobs') . $wheresql . " UNION ALL SELECT * FROM " . table('jobs_tmp') . $wheresql . $orderby;
    } else {
        $sql = "SELECT * FROM " . table($tabletype) . $wheresql . $orderby;
    }
    $total[0] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('jobs') . " WHERE uid='{$_SESSION['uid']}'");
    $total[1] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . " WHERE uid='{$_SESSION['uid']}'");
    $total[2] = $total[0] + $total[1];
    $smarty->assign('total', $total);
    $smarty->assign('setmeal', get_user_setmeal($_SESSION['uid']));
    $jobs = get_jobs($offset, $perpage, $sql, true);
    if ($templates == "company_jobs_type4") {
        foreach ($jobs as $k => $v) {
            $result = $db->getone("SELECT * FROM " . table('audit_reason') . " WHERE `jobs_id`=" . $v['id'] . " ORDER BY id DESC LIMIT 1");
            $v['reason'] = $result['reason'];
            $jobs[$k] = $v;
        }
    }
    if ($templates == "company_job_timing_refresh") {
        foreach ($jobs as $k => $v) {
            $result = $db->getall("SELECT time FROM " . table('timing_refresh_job') . " WHERE `job_id`=" . $v['id']);
            foreach ($result as $r) {
                $v['time_arr'][] = $r['time'];
            }
            $jobs[$k] = $v;
        }
    }
    $u_setmeal = $db->getone("SELECT * FROM " . table('members_setmeal') . " WHERE uid='" . intval($_SESSION['uid']) . "' limit 1");
    $vip = $u_setmeal['expense'] > 0 && $u_setmeal['endtime'] > time() ? 1 : 0;

    $refresh_time = get_today_refresh_times(intval($_SESSION['uid']), "1001");
    $refresh_jobs_total_time = $u_setmeal['refresh_jobs_time'] != 0 ? intval($u_setmeal['refresh_jobs_time']) : "����";
    $refresh_jobs_today_time = $u_setmeal['refresh_jobs_time'] != 0 ? $u_setmeal['refresh_jobs_time'] - intval($refresh_time['count(*)']) : "����";

    $smarty->assign('refresh_jobs_total_time', $refresh_jobs_total_time);
    $smarty->assign('refresh_jobs_today_time', $refresh_jobs_today_time);
    $smarty->assign('vip', $vip);
    $smarty->assign('jobs', $jobs);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('points_rule', get_cache('points_rule'));
    $smarty->display('member_company/' . $templates . '.htm');
} elseif ($act == 'set_promotion') {
    $catid = intval($_GET['catid']) ? intval($_GET['catid']) : exit("0-��������");
    $jobid = intval($_GET['jobid']) ? intval($_GET['jobid']) : exit("0-��������");
    $uid = intval($_SESSION['uid']) ? intval($_SESSION['uid']) : exit("0-��������");
    if ($jobid > 0) {
        $jobinfo = get_jobs_one($jobid);
    }
    $promotion = get_promotion_category_one($catid);
    $setmeal = get_user_setmeal($uid); //��ȡ��Ա�ײ�
    if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') {
        exit("0-��ܰ���ѣ����ķ����Ѿ����ڣ����������칺�����ײ�!");
    } else {
        $data = get_setmeal_promotion($uid, $catid); //��ȡ��Աĳ���ƹ��ʣ�����������������ƣ�������
        exit("1-" . $jobinfo['jobs_name'] . "-" . $promotion['cat_name'] . "-" . $data['days'] . "-" . $setmeal['setmeal_name'] . "-" . $data['num'] . "-" . $jobid . "-" . $catid . "-" . $data['name']);
    }
} elseif ($act == 'jobs_templates') {
    $smarty->assign('title', 'ְλģ����� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $smarty->assign('jobs_templates', get_jobs_templates());
    $smarty->display('member_company/company_jobs_templates.htm');
} elseif ($act == 'addjobs') {
    $smarty->assign('user', $user);
    $wheresql = " WHERE uid='{$_SESSION['uid']}' ";
    $orderby = " order by refreshtime desc";
    $offset = "";
    $perpage = "";
    $sql = "SELECT * FROM " . table("jobs") . $wheresql . $orderby;
    $jobs_list = get_jobs($offset, $perpage, $sql);
    $sql = "SELECT * FROM " . table("jobs_tmp") . $wheresql . $orderby;
    $jobs_list = array_merge($jobs_list, get_jobs($offset, $perpage, $sql));
    $jobs_templates = get_jobs_templates();
    if (!empty($jobs_templates)) {
        foreach ($jobs_templates as $j) {
            $j['jobs_name_'] = $j['title'];
            $j['templates'] = 1;
            $templates[] = $j;
        }
        $jobs_list = array_merge($jobs_list, $templates);
    }
    $smarty->assign('jobs_list', $jobs_list);
    $smarty->assign('subsite', get_subsite_list());
    if ($tabletype == "all") {
        $sql = "SELECT * FROM " . table('jobs') . $wheresql . " UNION ALL SELECT * FROM " . table('jobs_tmp') . $wheresql . $orderby;
    } else {
        $sql = "SELECT * FROM " . table($tabletype) . $wheresql . $orderby;
    }
    $total[0] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('jobs') . " WHERE uid='{$_SESSION['uid']}'");
    $total[1] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . " WHERE uid='{$_SESSION['uid']}'");
    $total[2] = $total[0] + $total[1];
    $smarty->assign('total', $total);
    if ($company_profile['companyname']) {
        $_SESSION['addrand'] = rand(1000, 5000);
        $smarty->assign('addrand', $_SESSION['addrand']);
        $smarty->assign('title', '����ְλ - ��ҵ��Ա���� - ' . $_CFG['site_name']);
        $smarty->assign('company_profile', $company_profile);
        if ($_CFG['operation_mode'] == "3") {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            if (($setmeal['endtime'] > time() || $setmeal['endtime'] == "0") && $setmeal['jobs_ordinary'] > 0) {
                $smarty->assign('setmeal', $setmeal);
                $smarty->assign('add_mode', 2);
            } elseif ($_CFG['setmeal_to_points'] == "1") {
                $smarty->assign('points_total', get_user_points($_SESSION['uid']));
                $smarty->assign('promotion', get_promotion_category());
                $smarty->assign('points', get_cache('points_rule'));
                $smarty->assign('add_mode', 1);
            } else {
                $smarty->assign('setmeal', $setmeal);
                $smarty->assign('add_mode', 2);
            }
        } elseif ($_CFG['operation_mode'] == "2") {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            $smarty->assign('setmeal', $setmeal);
            $smarty->assign('add_mode', 2);
        } elseif ($_CFG['operation_mode'] == "1") {
            $smarty->assign('points_total', get_user_points($_SESSION['uid']));
            $smarty->assign('promotion', get_promotion_category());
            $smarty->assign('points', get_cache('points_rule'));
            $smarty->assign('add_mode', 1);
        }
        $captcha = get_cache('captcha');
        $smarty->assign('verify_addjob', $captcha['verify_addjob']);
        $smarty->display('member_company/company_addjobs.htm');
    } else {
        $link[0]['text'] = "������ҵ����";
        $link[0]['href'] = 'company_info.php?act=company_profile';
        showmsg("Ϊ�˴ﵽ���õ���ƸЧ������������������ҵ���ϣ�", 1, $link);
    }
} elseif ($act == 'addjobs_save') {
    $captcha = get_cache('captcha');
    $postcaptcha = trim($_POST['postcaptcha']);
    if (empty($company_profile['companyname'])) {
        $link[0]['text'] = "������ҵ����";
        $link[0]['href'] = 'company_info.php?act=company_profile';
        showmsg("Ϊ�˴ﵽ���õ���ƸЧ������������������ҵ���ϣ�", 1, $link);
    }
    if ($company_profile['audit'] != 1) {
        $link[0]['text'] = "������ҵ����";
        $link[0]['href'] = 'company_info.php?act=company_profile';
        showmsg("������ҵ���ϻ�ûͨ�����", 1, $link);
    }
    if (!$user['email_audit']) {
        $link[0]['text'] = "��֤����";
        $link[0]['href'] = 'company_user.php?act=authenticate';
        showmsg("Ϊ�˴ﵽ���õ���ƸЧ����������֤�������䣡", 1, $link);
    }
    if ($captcha['verify_addjob'] == '1' && empty($postcaptcha)) {
        showmsg("����д��֤��", 1);
    }
    if ($captcha['verify_addjob'] == '1' && strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        showmsg("��֤�����", 1);
    }
    $add_mode = trim($_POST['add_mode']);
    $days = trim($_POST['days']) ? trim($_POST['days']) : showmsg('��ѡ���ֹ���ڣ�', 1);
    if ($add_mode == '1') {
        $points_rule = get_cache('points_rule');
        $user_points = get_user_points($_SESSION['uid']);
        $user_limit_points = get_user_limit_points($_SESSION['uid']);
        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
        $user_points = $user_points + $user_limit_points;
        $total = 0;
        if ($points_rule['jobs_add']['type'] == "2" && $points_rule['jobs_add']['value'] > 0) {
            $total = $points_rule['jobs_add']['value'];
        }
        if ($total > $user_points) {
            $link[0]['text'] = "������ֵ";
            $link[0]['href'] = 'company_service.php?act=order_add';
            $link[1]['text'] = "��Ա������ҳ";
            $link[1]['href'] = 'company_index.php?act=';
            showmsg("���" . $_CFG['points_byname'] . "���㣬���ֵ���ٷ�����", 0, $link);
        }
        $setsqlarr['setmeal_deadline'] = 0;
    } elseif ($add_mode == '2') {
        $link[0]['text'] = "������ͨ����";
        $link[0]['href'] = 'company_service.php?act=setmeal_list';
        $link[1]['text'] = "��Ա������ҳ";
        $link[1]['href'] = 'company_index.php?act=';
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            showmsg("���ķ����Ѿ����ڣ������¿�ͨ", 1, $link);
        }
        if ($setmeal['jobs_ordinary'] <= 0) {
            showmsg("��ǰ������ְλ�Ѿ�������������ƣ������������ײͣ�", 1, $link);
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
        $setsqlarr['companyname'] = $company_profile['companyname'];
        $setsqlarr['company_id'] = $company_profile['id'];
        $setsqlarr['company_addtime'] = $company_profile['addtime'];
        $setsqlarr['company_audit'] = $company_profile['audit'];
        $setsqlarr['jobs_name'] = !empty($_POST['jobs_name']) ? trim($_POST['jobs_name']) : showmsg('��û����дְλ���ƣ�', 1);
        $setsqlarr['recommend'] = intval($_POST['recommend']);
        $setsqlarr['emergency'] = intval($_POST['emergency']);
        if ($_CFG['operation_mode'] == '2') {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            if ($setsqlarr['recommend'] == 1 && $setmeal['recommend_num'] <= 0) {
                $link[0]['text'] = "������һҳ";
                $link[0]['href'] = 'javascript:history.go(-1)';
                $link[1]['text'] = "���¿�ͨ����";
                $link[1]['href'] = 'company_service.php?act=setmeal_list';
                showmsg("����ײ��ѵ��ڻ��ײ���ʣ���Ƽ�ְλ�����������뾡�쿪ͨ���ײ�", 1, $link);
            }
            if ($setsqlarr['emergency'] == 1 && $setmeal['emergency_num'] <= 0) {
                $link[0]['text'] = "������һҳ";
                $link[0]['href'] = 'javascript:history.go(-1)';
                $link[1]['text'] = "���¿�ͨ����";
                $link[1]['href'] = 'company_service.php?act=setmeal_list';
                showmsg("����ײ��ѵ��ڻ��ײ���ʣ��Ӽ�ְλ�����������뾡�쿪ͨ���ײ�", 1, $link);
            }
        }

        $nature = explode('-', trim($_POST['nature']));
        $setsqlarr['nature'] = $nature[0];
        $setsqlarr['nature_cn'] = $nature[1];

        $setsqlarr['topclass'] = 0;
        $category = trim($_POST['job_ptype']) ? explode('-', trim($_POST['job_ptype'])) : showmsg('��ѡ��ְλ���', 1);
        $setsqlarr['category'] = $category[0];
        $subclass = explode('-', trim($_POST['job_type']));
        $setsqlarr['subclass'] = intval($subclass[0]);
        $setsqlarr['category_cn'] = $setsqlarr['subclass'] > 0 ? trim($category[1]) . '-' . trim($subclass[1]) : trim($category[1]) . '-ȫ��';
        $setsqlarr['amount'] = intval($_POST['amount']);

        $district = trim($_POST['district']) ? explode('-', trim($_POST['district'])) : showmsg('��ѡ����������', 1);
        $setsqlarr['district'] = $district[0];
        $sdistrict = trim($_POST['sdistrict']) ? explode('-', trim($_POST['sdistrict'])) : array("0", "ȫ��");
        $setsqlarr['sdistrict'] = $sdistrict[0];
        $setsqlarr['district_cn'] = $district[1] . "/" . $sdistrict[1];
        $wage = trim($_POST['wage']) ? explode('-', trim($_POST['wage'])) : showmsg('��ѡ��н�ʴ�����', 1);
        $setsqlarr['wage'] = $wage[0];
        $setsqlarr['wage_cn'] = $wage[1];
        $setsqlarr['negotiable'] = 0;
        $setsqlarr['tag'] = trim($_POST['tag'], "|");
        $sex = explode('-', trim($_POST['sex']));
        $setsqlarr['sex'] = $sex[0];
        $setsqlarr['sex_cn'] = $sex[1];
        $education = trim($_POST['education']) ? explode('-', trim($_POST['education'])) : showmsg('��ѡ��ѧ��Ҫ��', 1);
        $setsqlarr['education'] = $education[0];
        $setsqlarr['education_cn'] = $education[1];
        $experience = trim($_POST['experience']) ? explode('|', trim($_POST['experience'])) : showmsg('��ѡ�������飡', 1);
        $setsqlarr['experience'] = $experience[0];
        $setsqlarr['experience_cn'] = $experience[1];



        $setsqlarr['graduate'] = 0;
        $setsqlarr['age'] = intval($_POST['minage']) . "-" . intval($_POST['maxage']);
        $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : showmsg('��û����дְλ������', 1);
        check_word($_CFG['filter'], $_POST['contents']) ? showmsg($_CFG['filter_tips'], 0) : '';
        $setsqlarr['trade'] = $company_profile['trade'];
        $setsqlarr['trade_cn'] = $company_profile['trade_cn'];
        $setsqlarr['scale'] = $company_profile['scale'];
        $setsqlarr['scale_cn'] = $company_profile['scale_cn'];
        $setsqlarr['street'] = $company_profile['street'];
        $setsqlarr['street_cn'] = $company_profile['street_cn'];
        $setsqlarr['addtime'] = time();
        if ($days > 0) {
            $setsqlarr['deadline'] = strtotime($days);
        }
        $setsqlarr['refreshtime'] = time();
        $setsqlarr['key'] = $setsqlarr['jobs_name'] . $company_profile['companyname'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'];
        require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
        $sp = new SPWord();
        $setsqlarr['key'] = "{$setsqlarr['jobs_name']} {$company_profile['companyname']} " . $sp->extracttag($setsqlarr['key']);
        $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
        $setsqlarr['subsite_id'] = intval($_POST['subsite_id']);
        $setsqlarr['tpl'] = $company_profile['tpl'];
        $setsqlarr['map_x'] = $company_profile['map_x'];
        $setsqlarr['map_y'] = $company_profile['map_y'];
        if ($company_profile['audit'] == "1") {
            $setsqlarr['audit'] = intval($_CFG['audit_verifycom_addjob']);
        } else {
            $setsqlarr['audit'] = intval($_CFG['audit_unexaminedcom_addjob']);
        }
        $setsqlarr_contact['contact'] = !empty($_POST['contact']) ? trim($_POST['contact']) : showmsg('��û����д��ϵ�ˣ�', 1);
        $setsqlarr_contact['telephone'] = !empty($_POST['telephone']) ? trim($_POST['telephone']) : showmsg('��û����д��ϵ�绰��', 1);
        check_word($_CFG['filter'], $_POST['telephone']) ? showmsg($_CFG['filter_tips'], 0) : '';
        $setsqlarr_contact['address'] = !empty($_POST['address']) ? trim($_POST['address']) : showmsg('��û����д��ϵ��ַ��', 1);
        $setsqlarr_contact['email'] = !empty($_POST['email']) ? trim($_POST['email']) : showmsg('��û����д��ϵ���䣡', 1);
        $setsqlarr_contact['notify'] = 1;

        $setsqlarr_contact['contact_show'] = intval($_POST['contact_show']);
        $setsqlarr_contact['email_show'] = intval($_POST['email_show']);
        $setsqlarr_contact['telephone_show'] = intval($_POST['telephone_show']);
        $setsqlarr_contact['address_show'] = intval($_POST['address_show']);
        $table = $setsqlarr['audit'] == 1 ? "jobs" : "jobs_tmp";
        //���ְλ��Ϣ
        $pid = inserttable(table($table), $setsqlarr, true);
        if (empty($pid)) {
            showmsg("���ʧ�ܣ�", 0);
        }
        //�����ϵ��ʽ
        $setsqlarr_contact['pid'] = $pid;
        !inserttable(table('jobs_contact'), $setsqlarr_contact) ? showmsg("���ʧ�ܣ�", 0) : '';

        if ($setsqlarr['recommend'] == 1) {
            promotion_add_save($pid, $_POST['r_days'], 1);
        }
        if ($setsqlarr['emergency'] == 1) {
            promotion_add_save($pid, $_POST['e_days'], 2);
        }

        if ($add_mode == '1') {
            if ($points_rule['jobs_add']['value'] > 0) {
                report_deal($_SESSION['uid'], $points_rule['jobs_add']['type'], $points_rule['jobs_add']['value']);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $points_rule['jobs_add']['type'] == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "������ְλ��<strong>{$setsqlarr['jobs_name']}</strong>��({$operator}{$points_rule['jobs_add']['value']})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})", 1, 1001, "����ְλ", "{$operator}{$points_rule['jobs_add']['value']}", "{$user_points}");
            }
            if (intval($_POST['days']) > 0 && $points_rule['jobs_daily']['value'] > 0) {
                $points_day = intval($_POST['days']) * $points_rule['jobs_daily']['value'];
                report_deal($_SESSION['uid'], $points_rule['jobs_daily']['type'], $points_day);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $points_rule['jobs_daily']['type'] == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "����ְλ:<strong>{$_POST['jobs_name']}</strong>����Ч��Ϊ{$_POST['days']}�죬({$operator}{$points_day})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})", 1, 1001, "����ְλ", "{$operator}{$points_day}", "{$user_points}");
            }
        } elseif ($add_mode == '2') {
            action_user_setmeal($_SESSION['uid'], "jobs_ordinary");
            $setmeal = get_user_setmeal($_SESSION['uid']);
            write_memberslog($_SESSION['uid'], 1, 9002, $_SESSION['username'], "������ְͨλ:<strong>{$_POST['jobs_name']}</strong>�������Է�����ְͨλ:<strong>{$setmeal['jobs_ordinary']}</strong>��", 2, 1001, "����ְλ", "1", "{$setmeal['jobs_ordinary']}");
        }
        $searchtab['id'] = $pid;
        $searchtab['uid'] = $setsqlarr['uid'];
        $searchtab['subsite_id'] = $setsqlarr['subsite_id'];
        $searchtab['recommend'] = $setsqlarr['recommend'];
        $searchtab['emergency'] = $setsqlarr['emergency'];
        $searchtab['nature'] = $setsqlarr['nature'];
        $searchtab['sex'] = $setsqlarr['sex'];
        $searchtab['topclass'] = $setsqlarr['topclass'];
        $searchtab['category'] = $setsqlarr['category'];
        $searchtab['subclass'] = $setsqlarr['subclass'];
        $searchtab['trade'] = $setsqlarr['trade'];
        $searchtab['district'] = $setsqlarr['district'];
        $searchtab['sdistrict'] = $setsqlarr['sdistrict'];
        $searchtab['street'] = $company_profile['street'];
        $searchtab['education'] = $setsqlarr['education'];
        $searchtab['experience'] = $setsqlarr['experience'];
        $searchtab['wage'] = $setsqlarr['wage'];
        $searchtab['refreshtime'] = $setsqlarr['refreshtime'];
        $searchtab['scale'] = $setsqlarr['scale'];
        //
        inserttable(table('jobs_search_wage'), $searchtab);
        inserttable(table('jobs_search_scale'), $searchtab);
        //
        $searchtab['map_x'] = $setsqlarr['map_x'];
        $searchtab['map_y'] = $setsqlarr['map_y'];
        inserttable(table('jobs_search_rtime'), $searchtab);
        unset($searchtab['map_x'], $searchtab['map_y']);
        //
        $searchtab['stick'] = $setsqlarr['stick'];
        inserttable(table('jobs_search_stickrtime'), $searchtab);
        unset($searchtab['stick']);
        //
        $searchtab['click'] = $setsqlarr['click'];
        inserttable(table('jobs_search_hot'), $searchtab);
        unset($searchtab['click']);
        //
        $searchtab['key'] = $setsqlarr['key'];
        $searchtab['likekey'] = $setsqlarr['jobs_name'] . ',' . $setsqlarr['companyname'];
        $searchtab['map_x'] = $setsqlarr['map_x'];
        $searchtab['map_y'] = $setsqlarr['map_y'];
        inserttable(table('jobs_search_key'), $searchtab);
        unset($searchtab);
        //
        $tag = explode('|', $setsqlarr['tag']);
        $tagindex = 1;
        $tagsql['tag1'] = $tagsql['tag2'] = $tagsql['tag3'] = $tagsql['tag4'] = $tagsql['tag5'] = 0;
        if (!empty($tag) && is_array($tag)) {
            foreach ($tag as $v) {
                $vid = explode(',', $v);
                $tagsql['tag' . $tagindex] = intval($vid[0]);
                $tagindex++;
            }
        }
        $tagsql['id'] = $pid;
        $tagsql['uid'] = $setsqlarr['uid'];
        $tagsql['subsite_id'] = $setsqlarr['subsite_id'];
        $tagsql['topclass'] = $setsqlarr['topclass'];
        $tagsql['category'] = $setsqlarr['category'];
        $tagsql['subclass'] = $setsqlarr['subclass'];
        $tagsql['district'] = $setsqlarr['district'];
        $tagsql['sdistrict'] = $setsqlarr['sdistrict'];
        inserttable(table('jobs_search_tag'), $tagsql);

        $sql = "select * from " . table('jiaoshi_statistics_all') . " where name = 'job' LIMIT 1";
        $job = $db->getone($sql);
        $setsqlarr1['total_count'] = intval($job['total_count']) + 1;
        $setsqlarr1['new_count'] = intval($job['new_count']) + 1;
        $wheresql1 = " name='job' ";
        updatetable(table('jiaoshi_statistics_all'), $setsqlarr1, $wheresql1);
        $date = date('Y-m-d', time());
        $sql = "select * from " . table('jiaoshi_statistics_day') . " where name = 'job' and date = '" . $date . "' LIMIT 1";
        $job = $db->getone($sql);
        if (!empty($job)) {
            $setsqlarr2['web_count'] = intval($job['web_count']) + 1;
            $wheresql2 = " name='job' and date='" . $date . "' ";
            updatetable(table('jiaoshi_statistics_day'), $setsqlarr2, $wheresql2);
        } else {
            $setsqlarr2['web_count'] = 1;
            $setsqlarr2['name'] = 'job';
            $setsqlarr2['date'] = $date;
            $setsqlarr2['count'] = 0;
            inserttable(table('jiaoshi_statistics_day'), $setsqlarr2);
        }

        distribution_jobs($pid, $_SESSION['uid']);
        write_memberslog($_SESSION['uid'], 1, 2001, $_SESSION['username'], "������ְλ��{$setsqlarr['jobs_name']}");
    }
    $link[0]['text'] = "��������";
    $link[0]['href'] = '/resume/resume-list.php';
    $link[1]['text'] = "ְλ����";
    $link[1]['href'] = '/user/company/company_jobs.php?act=jobs';
    $link[2]['text'] = "��������";
    $link[2]['href'] = '/user/company/company_jobs.php?act=addjobs';
    //showmsg("ְλ�����ɹ�", 2, $link, 0);
    header("location:?act=addjobs_save_succeed");
} elseif ($act == 'addjobs_save_succeed') {
    $uid = intval($_SESSION['uid']);
    $smarty->assign('concern_id', get_concern_id($uid));
    $smarty->assign('title', '����ְλ - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->display('member_company/company_addjobs_succeed.htm');
} elseif ($act == 'del_jobs_templates') {
    $yid = !empty($_POST['y_id']) ? $_POST['y_id'] : $_GET['y_id'];
    if (empty($yid)) {
        showmsg("��û��ѡ��ģ�壡", 1);
    }
    if ($n = del_templates($yid, $_SESSION['uid'])) {
        showmsg("ɾ���ɹ�����ɾ�� {$n} ��", 2);
    } else {
        showmsg("ɾ��ʧ�ܣ�", 0);
    }
} elseif ($act == 'jobs_perform') {
    global $_CFG;
    $yid = !empty($_POST['y_id']) ? $_POST['y_id'] : $_GET['y_id'];
    $jobs_num = count($yid);
    $do_type = "";
    if (empty($yid)) {
        showmsg("��û��ѡ��ְλ��", 1);
    }
    $refresh = !empty($_REQUEST['refresh']) ? $_REQUEST['refresh'] : "";
    $delete = !empty($_REQUEST['delete']) ? $_REQUEST['delete'] : "";
    $display1 = !empty($_REQUEST['display1']) ? $_REQUEST['display1'] : "";
    $display2 = !empty($_REQUEST['display2']) ? $_REQUEST['display2'] : "";
    $do_type = !empty($_REQUEST['do_type']) ? $_REQUEST['do_type'] : "";
    switch ($do_type) {
        case "1":
            $refresh = 1;
            break;
        case "2":
            $delete = 1;
            break;
        case "3":
            $display1 = 1;
            break;
        case "display2":
            $display2 = 1;
            break;
        default:
            break;
    }
    if ($refresh) {
        if ($jobs_num == 1) {
            if (!is_array($yid)) {
                $yid = array($yid);
            }
        }
        foreach ($yid as $yid) {
            $jobs_info = $db->getone("select * from " . table('jobs') . " where id=" . $yid);
            if (empty($jobs_info)) {
                $jobs_info = $db->getone("select * from " . table('jobs_tmp') . " where id=" . $yid);
            }
            if ($jobs_info['deadline'] < time()) {
                showmsg($jobs_info['jobs_name'] . "�ѵ��ڣ������·���ְλ��", 1);
            }
            //����ģʽ
            if ($_CFG['operation_mode'] == '1') {
                //����ˢ��ʱ��
                //���һ�ε�ˢ��ʱ��
                $refrestime = get_last_refresh_date($_SESSION['uid'], "1001");
                $duringtime = time() - $refrestime['max(addtime)'];
                $space = $_CFG['com_pointsmode_refresh_space'] * 60;
                $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001");
                if ($_CFG['com_pointsmode_refresh_time'] != 0 && ($refresh_time['count(*)'] >= $_CFG['com_pointsmode_refresh_time'])) {
                    showmsg("ÿ�����ֻ��ˢ��" . $_CFG['com_pointsmode_refresh_time'] . "��,�������ѳ������ˢ�´������ƣ�", 2);
                } elseif ($duringtime <= $space) {
                    showmsg($_CFG['com_pointsmode_refresh_space'] . "�����ڲ����ظ�ˢ��ְλ��", 2);
                } else {
                    $points_rule = get_cache('points_rule');
                    if ($points_rule['jobs_refresh']['value'] > 0) {
                        $user_points = get_user_points($_SESSION['uid']);
                        $user_limit_points = get_user_limit_points($_SESSION['uid']);
                        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                        $user_points = $user_points + $user_limit_points;
                        $total_point = $jobs_num * $points_rule['jobs_refresh']['value'];
                        if ($total_point > $user_points && $points_rule['jobs_refresh']['type'] == "2") {
                            $link[0]['text'] = "������һҳ";
                            $link[0]['href'] = 'javascript:history.go(-1)';
                            $link[1]['text'] = "������ֵ";
                            $link[1]['href'] = 'company_service.php?act=order_add';
                            showmsg("����" . $_CFG['points_byname'] . "���㣬���ȳ�ֵ��", 0, $link);
                        }
                        report_deal($_SESSION['uid'], $points_rule['jobs_refresh']['type'], $total_point);
                        $user_points = get_user_points($_SESSION['uid']);
                        $user_limit_points = get_user_limit_points($_SESSION['uid']);
                        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                        $operator = $points_rule['jobs_refresh']['type'] == "1" ? "+" : "-";
                        write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "ˢ����{$jobs_num}��ְλ��({$operator}{$total_point})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})", 1, 1003, "ˢ��ְλ", "{$operator}{$total_point}", "{$user_points}");
                    }
                }
            }
            //�ײ�ģʽ
            elseif ($_CFG['operation_mode'] == '2') {
                //����ˢ��ʱ��
                //�һ�ε�ˢ��ʱ��
                $link[0]['text'] = "������ͨ����";
                $link[0]['href'] = 'company_service.php?act=setmeal_list';
                $link[1]['text'] = "��Ա������ҳ";
                $link[1]['href'] = 'company_index.php?act=';
                $setmeal = get_user_setmeal($_SESSION['uid']);
                if (empty($setmeal)) {
                    showmsg("����û�п�ͨ�����뿪ͨ", 1, $link);
                } elseif ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
                    showmsg("���ķ����Ѿ����ڣ������¿�ͨ", 1, $link);
                } else {
                    $refrestime = get_last_refresh_date($_SESSION['uid'], "1001");
                    $duringtime = time() - $refrestime['max(addtime)'];
                    $space = $setmeal['refresh_jobs_space'] * 60;
                    $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001");
                    if ($setmeal['refresh_jobs_time'] != 0 && ($refresh_time['count(*)'] >= $setmeal['refresh_jobs_time'])) {
                        showmsg("ÿ�����ֻ��ˢ��" . $setmeal['refresh_jobs_time'] . "��,�������ѳ������ˢ�´������ƣ�", 2);
                        exit;
                    } elseif ($duringtime <= $space) {
                        showmsg($setmeal['refresh_jobs_space'] . "�����ڲ����ظ�ˢ��ְλ��", 2);
                    }
                }
            }
            //���ģʽ
            elseif ($_CFG['operation_mode'] == '3') {
                //����ˢ��ʱ��
                //�һ�ε�ˢ��ʱ��
                $setmeal = get_user_setmeal($_SESSION['uid']);

                $refrestime = get_last_refresh_date($_SESSION['uid'], "1001");
                $duringtime = time() - $refrestime['max(addtime)'];
                $space = $setmeal['refresh_jobs_space'] * 60;
                $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001");
                if ($setmeal['refresh_jobs_time'] != 0 && ($refresh_time['count(*)'] >= $setmeal['refresh_jobs_time'])) {
                    showmsg("ÿ�����ֻ��ˢ��" . $setmeal['refresh_jobs_time'] . "��,�������ѳ������ˢ�´������ƣ�", 2);
                } elseif ($duringtime <= $space) {
                    showmsg($setmeal['refresh_jobs_space'] . "�����ڲ����ظ�ˢ��ְλ��", 2);
                } else {
                    $points_rule = get_cache('points_rule');
                    if ($points_rule['jobs_refresh']['value'] > 0) {
                        $user_points = get_user_points($_SESSION['uid']);
                        $user_limit_points = get_user_limit_points($_SESSION['uid']);
                        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                        $user_points = $user_points + $user_limit_points;
                        $total_point = $jobs_num * $points_rule['jobs_refresh']['value'];
                        if ($total_point > $user_points && $points_rule['jobs_refresh']['type'] == "2") {
                            $link[0]['text'] = "������һҳ";
                            $link[0]['href'] = 'javascript:history.go(-1)';
                            $link[1]['text'] = "������ֵ";
                            $link[1]['href'] = 'company_service.php?act=order_add';
                            showmsg("����" . $_CFG['points_byname'] . "���㣬���ȳ�ֵ��", 0, $link);
                        }
                        report_deal($_SESSION['uid'], $points_rule['jobs_refresh']['type'], $total_point);
                        $user_points = get_user_points($_SESSION['uid']);
                        $user_limit_points = get_user_limit_points($_SESSION['uid']);
                        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                        $operator = $points_rule['jobs_refresh']['type'] == "1" ? "+" : "-";
                        write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "ˢ����{$jobs_num}��ְλ��({$operator}{$total_point})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})", 1, 1003, "ˢ��ְλ", "{$operator}{$total_point}", "{$user_points}");
                    }
                }
            }

            refresh_jobs($yid, $_SESSION['uid']);
        }
        write_memberslog($_SESSION['uid'], 1, 2004, $_SESSION['username'], "ˢ��ְλ");
        write_refresh_log($_SESSION['uid'], 1001);
        showmsg("ˢ��ְλ�ɹ���", 2);
    } elseif ($delete) {
        if ($n = del_jobs($yid, $_SESSION['uid'])) {
            showmsg("ɾ���ɹ�����ɾ�� {$n} ��", 2);
        } else {
            showmsg("ɾ��ʧ�ܣ�", 0);
        }
    } elseif ($display1) {
        activate_jobs($yid, 1, $_SESSION['uid']);
        showmsg("���óɹ���", 2);
    } elseif ($display2) {
        activate_jobs($yid, 2, $_SESSION['uid']);
        showmsg("���óɹ���", 2);
    }
} elseif ($act == 'editjobs') {
    $jobs = get_jobs_one(intval($_GET['id']), $_SESSION['uid']);
    if (!empty($jobs['age'])) {
        $age_arr = explode("-", $jobs['age']);
        $jobs['minage'] = $age_arr[0];
        $jobs['maxage'] = $age_arr[1];
    } else {
        $jobs['minage'] = $jobs['maxage'] = "";
    }
    if (empty($jobs))
        showmsg("��������", 1);
    if ($jobs['age']) {
        $jobs_age = explode("-", $jobs['age']);
        $jobs['minage'] = $jobs_age[0];
        $jobs['maxage'] = $jobs_age[1];
    }
    $smarty->assign('user', $user);
    $smarty->assign('title', '�޸�ְλ - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('points_total', get_user_points($_SESSION['uid']));
    $smarty->assign('points', get_cache('points_rule'));
    $smarty->assign('jobs', $jobs);
    $smarty->display('member_company/company_editjobs.htm');
} elseif ($act == 'editjobs_save') {
    $captcha = get_cache('captcha');
    $postcaptcha = trim($_POST['postcaptcha']);
    if ($captcha['verify_addjob'] == '1' && empty($postcaptcha)) {
        showmsg("����д��֤��", 1);
    }
    if ($captcha['verify_addjob'] == '1' && strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        showmsg("��֤�����", 1);
    }
    $id = intval($_POST['id']);
    $add_mode = trim($_POST['add_mode']);
    $days = trim($_POST['days']) ? trim($_POST['days']) : showmsg('��ѡ���ֹ���ڣ�', 1);
    if ($add_mode == '1') {
        $points_rule = get_cache('points_rule');
        $user_points = get_user_points($_SESSION['uid']);
        $user_limit_points = get_user_limit_points($_SESSION['uid']);
        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
        $user_points = $user_points + $user_limit_points;
        $total = 0;
        if ($points_rule['jobs_edit']['type'] == "2" && $points_rule['jobs_edit']['value'] > 0) {
            $total = $points_rule['jobs_edit']['value'];
        }
        if ($total > $user_points) {
            $link[0]['text'] = "������һҳ";
            $link[0]['href'] = 'javascript:history.go(-1)';
            $link[1]['text'] = "������ֵ";
            $link[1]['href'] = 'company_service.php?act=order_add';
            showmsg("���" . $_CFG['points_byname'] . "���㣬���ֵ���ٷ�����", 0, $link);
        }
    } elseif ($add_mode == '2') {
        $link[0]['text'] = "������ͨ����";
        $link[0]['href'] = 'company_service.php?act=setmeal_list';
        $link[1]['text'] = "��Ա������ҳ";
        $link[1]['href'] = 'company_index.php?act=';
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            showmsg("�����ײ��Ѿ����ڣ������¿�ͨ", 1, $link);
        }
    }

    $setsqlarr['jobs_name'] = !empty($_POST['jobs_name']) ? trim($_POST['jobs_name']) : showmsg('��û����дְλ���ƣ�', 1);

    $nature = explode('-', trim($_POST['nature']));
    $setsqlarr['nature'] = $nature[0];
    $setsqlarr['nature_cn'] = $nature[1];

    $setsqlarr['topclass'] = 0;
    $category = trim($_POST['job_ptype']) ? explode('-', trim($_POST['job_ptype'])) : showmsg('��ѡ��ְλ���', 1);
    $setsqlarr['category'] = $category[0];
    $subclass = explode('-', trim($_POST['job_type']));
    $setsqlarr['subclass'] = intval($subclass[0]);
    $setsqlarr['category_cn'] = $setsqlarr['subclass'] > 0 ? trim($category[1]) . '-' . trim($subclass[1]) : trim($category[1]) . '-ȫ��';
    $setsqlarr['amount'] = intval($_POST['amount']);

    $district = trim($_POST['district']) ? explode('-', trim($_POST['district'])) : showmsg('��ѡ����������', 1);
    $setsqlarr['district'] = $district[0];
    $sdistrict = trim($_POST['sdistrict']) ? explode('-', trim($_POST['sdistrict'])) : "0-ȫ��";
    $setsqlarr['sdistrict'] = $sdistrict[0];
    $setsqlarr['district_cn'] = $district[1] . "/" . $sdistrict[1];
    $wage = trim($_POST['wage']) ? explode('-', trim($_POST['wage'])) : showmsg('��ѡ��н�ʴ�����', 1);
    $setsqlarr['wage'] = $wage[0];
    $setsqlarr['wage_cn'] = $wage[1];
    $setsqlarr['negotiable'] = 0;
    $setsqlarr['tag'] = trim($_POST['tag'], "|");
    $sex = explode('-', trim($_POST['sex']));
    $setsqlarr['sex'] = $sex[0];
    $setsqlarr['sex_cn'] = $sex[1];
    $education = trim($_POST['education']) ? explode('-', trim($_POST['education'])) : showmsg('��ѡ��ѧ��Ҫ��', 1);
    $setsqlarr['education'] = $education[0];
    $setsqlarr['education_cn'] = $education[1];
    $experience = trim($_POST['experience']) ? explode('|', trim($_POST['experience'])) : showmsg('��ѡ�������飡', 1);
    $setsqlarr['experience'] = $experience[0];
    $setsqlarr['experience_cn'] = $experience[1];

    $setsqlarr['graduate'] = 0;
    $setsqlarr['age'] = trim($_POST['minage']) . "-" . trim($_POST['maxage']);
    $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : showmsg('��û����дְλ������', 1);
    check_word($_CFG['filter'], $_POST['contents']) ? showmsg($_CFG['filter_tips'], 0) : '';
    if ($add_mode == '1') {
        $setsqlarr['setmeal_deadline'] = 0;
        $setsqlarr['add_mode'] = 1;
    } elseif ($add_mode == '2') {
        $setmeal = get_user_setmeal($_SESSION['uid']);
        $setsqlarr['setmeal_deadline'] = $setmeal['endtime'];
        $setsqlarr['setmeal_id'] = $setmeal['setmeal_id'];
        $setsqlarr['setmeal_name'] = $setmeal['setmeal_name'];
        $setsqlarr['add_mode'] = 2;
    }
    if ($days > 0) {
        $setsqlarr['deadline'] = strtotime($days);
    }
    $setsqlarr['key'] = $setsqlarr['jobs_name'] . $company_profile['companyname'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'];
    require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
    $sp = new SPWord();
    $setsqlarr['key'] = "{$setsqlarr['jobs_name']} {$company_profile['companyname']} " . $sp->extracttag($setsqlarr['key']);
    $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
    if ($company_profile['audit'] == "1") {
        $_CFG['audit_verifycom_editjob'] <> "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_verifycom_editjob']) : '';
    } else {
        $_CFG['audit_unexaminedcom_editjob'] <> "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_unexaminedcom_editjob']) : '';
    }
    $setsqlarr_contact['contact'] = !empty($_POST['contact']) ? trim($_POST['contact']) : showmsg('��û����д��ϵ�ˣ�', 1);
    $setsqlarr_contact['telephone'] = !empty($_POST['telephone']) ? trim($_POST['telephone']) : showmsg('��û����д��ϵ�绰��', 1);
    check_word($_CFG['filter'], $_POST['telephone']) ? showmsg($_CFG['filter_tips'], 0) : '';
    $setsqlarr_contact['address'] = !empty($_POST['address']) ? trim($_POST['address']) : showmsg('��û����д��ϵ��ַ��', 1);
    $setsqlarr_contact['email'] = !empty($_POST['email']) ? trim($_POST['email']) : showmsg('��û����д��ϵ���䣡', 1);
    $setsqlarr_contact['notify'] = 1;

    $setsqlarr_contact['contact_show'] = intval($_POST['contact_show']);
    $setsqlarr_contact['email_show'] = intval($_POST['email_show']);
    $setsqlarr_contact['telephone_show'] = intval($_POST['telephone_show']);
    $setsqlarr_contact['address_show'] = intval($_POST['address_show']);

    if (!updatetable(table('jobs'), $setsqlarr, " id='{$id}' AND uid='{$_SESSION['uid']}' "))
        showmsg("����ʧ�ܣ�", 0);
    if (!updatetable(table('jobs_tmp'), $setsqlarr, " id='{$id}' AND uid='{$_SESSION['uid']}' "))
        showmsg("����ʧ�ܣ�", 0);
    if (!updatetable(table('jobs_contact'), $setsqlarr_contact, " pid='{$id}' ")) {
        showmsg("����ʧ�ܣ�", 0);
    }
    if ($add_mode == '1') {
        if ($points_rule['jobs_edit']['value'] > 0) {
            report_deal($_SESSION['uid'], $points_rule['jobs_edit']['type'], $points_rule['jobs_edit']['value']);
            $user_points = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $operator = $points_rule['jobs_edit']['type'] == "1" ? "+" : "-";
            write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "�޸�ְλ��<strong>{$setsqlarr['jobs_name']}</strong>��({$operator}{$points_rule['jobs_edit']['value']})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})", 1, 1002, "�޸���Ƹ��Ϣ", "{$operator}{$points_rule['jobs_edit']['value']}", "{$user_points}");
        }
    }
    $link[0]['text'] = "ְλ�б�";
    $link[0]['href'] = '?act=jobs';
    $link[1]['text'] = "�鿴�޸Ľ��";
    $link[1]['href'] = "?act=editjobs&id={$id}";
    $link[2]['text'] = "��Ա������ҳ";
    $link[2]['href'] = "company_index.php";
    //
    $searchtab['nature'] = $setsqlarr['nature'];
    $searchtab['sex'] = $setsqlarr['sex'];
    $searchtab['topclass'] = $setsqlarr['topclass'];
    $searchtab['category'] = $setsqlarr['category'];
    $searchtab['subclass'] = $setsqlarr['subclass'];
    $searchtab['district'] = $setsqlarr['district'];
    $searchtab['sdistrict'] = $setsqlarr['sdistrict'];
    $searchtab['education'] = $setsqlarr['education'];
    $searchtab['experience'] = $setsqlarr['experience'];
    $searchtab['wage'] = $setsqlarr['wage'];
    //
    updatetable(table('jobs_search_wage'), $searchtab, " id='{$id}' AND uid='{$_SESSION['uid']}' ");
    updatetable(table('jobs_search_rtime'), $searchtab, " id='{$id}' AND uid='{$_SESSION['uid']}' ");
    updatetable(table('jobs_search_stickrtime'), $searchtab, " id='{$id}' AND uid='{$_SESSION['uid']}' ");
    updatetable(table('jobs_search_hot'), $searchtab, " id='{$id}' AND uid='{$_SESSION['uid']}' ");
    updatetable(table('jobs_search_scale'), $searchtab, " id='{$id}' AND uid='{$_SESSION['uid']}'");
    $searchtab['key'] = $setsqlarr['key'];
    $searchtab['likekey'] = $setsqlarr['jobs_name'] . ',' . $company_profile['companyname'];
    updatetable(table('jobs_search_key'), $searchtab, " id='{$id}' AND uid='{$_SESSION['uid']}' ");
    unset($searchtab);
    $tag = explode('|', $setsqlarr['tag']);
    $tagindex = 1;
    foreach ($tag as $v) {
        $vid = explode(',', $v);
        if ($tagindex < 6) {
            $tagsql['tag' . $tagindex] = intval($vid[0]);
            $tagindex++;
        }
    }
    $tagsql['id'] = $id;
    $tagsql['uid'] = $_SESSION['uid'];
    $tagsql['topclass'] = $setsqlarr['topclass'];
    $tagsql['category'] = $setsqlarr['category'];
    $tagsql['subclass'] = $setsqlarr['subclass'];
    $tagsql['district'] = $setsqlarr['district'];
    $tagsql['sdistrict'] = $setsqlarr['sdistrict'];
    updatetable(table('jobs_search_tag'), $tagsql, " id='{$id}' AND uid='{$_SESSION['uid']}' ");
    distribution_jobs($id, $_SESSION['uid']);
    write_memberslog($_SESSION['uid'], $_SESSION['utype'], 2002, $_SESSION['username'], "�޸���ְλ��{$setsqlarr['jobs_name']}��ְλID��{$id}");
    showmsg("�޸ĳɹ���", 2, $link);
} elseif ($act == "ajax_save_jobs_templates") {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = utf8_to_gbk($value);
    }
    $setsqlarr['title'] = !empty($_POST['jobs_name']) ? trim($_POST['jobs_name']) . "��ģ��" : exit('-1');
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : exit('-1');
    $setsqlarr['nature'] = intval($_POST['nature']);
    $setsqlarr['nature_cn'] = trim($_POST['nature_cn']);
    $setsqlarr['sex'] = intval($_POST['sex']);
    $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
    $setsqlarr['amount'] = intval($_POST['amount']);
    $setsqlarr['topclass'] = intval($_POST['topclass']);
    $setsqlarr['category'] = !empty($_POST['category']) ? intval($_POST['category']) : exit('-1');
    $setsqlarr['subclass'] = intval($_POST['subclass']);
    $setsqlarr['category_cn'] = trim($_POST['category_cn']);
    $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : exit('-1');
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);
    $setsqlarr['education'] = intval($_POST['education']);
    $setsqlarr['education_cn'] = trim($_POST['education_cn']);
    $setsqlarr['experience'] = intval($_POST['experience']);
    $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
    $setsqlarr['wage'] = intval($_POST['wage']);
    $setsqlarr['wage_cn'] = trim($_POST['wage_cn']);
    $setsqlarr['tag'] = trim($_POST['tag'], "|");
    $setsqlarr['graduate'] = intval($_POST['graduate']);
    $setsqlarr['negotiable'] = intval($_POST['negotiable']);
    $setsqlarr['minage'] = intval($_POST['minage']);
    $setsqlarr['maxage'] = intval($_POST['maxage']);
    $setsqlarr['addtime'] = time();
    $pid = inserttable(table('jobs_templates'), $setsqlarr, true);
    if ($pid > 0) {
        exit("1");
    } else {
        exit("0");
    }
} elseif ($act == 'copy_job') {
    $id = intval($_GET['id']);
    $templates = intval($_GET['templates']);
    if ($id < 1) {
        exit("-1");
    }
    if ($templates == 0) {
        $job = get_jobs_one($id);
    } else {
        $job = get_jobs_templates_one($id);
        $job['age'] = $job['minage'] . "-" . $job['maxage'];
    }
    if (!empty($job)) {
        foreach ($job as $key => $value) {
            if (!is_numeric($value)) {
                $job[$key] = gbk_to_utf8($value);
            }
        }
        exit(json_encode($job));
    } else {
        exit("-1");
    }
} elseif ($act == 'copy_templates') {
    $id = intval($_GET['id']);
    if ($id < 1) {
        exit("-1");
    }
    $templates = get_jobs_templates_one($id);
    if (!empty($templates)) {
        foreach ($templates as $key => $value) {
            $templates[$key] = gbk_to_utf8($value);
        }
        exit(json_encode($templates));
    } else {
        exit("-1");
    }
} elseif ($act == "get_content_by_jobs_cat") {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $content = get_content_by_jobs_cat($id);
        if (!empty($content)) {
            exit($content);
        } else {
            exit("-1");
        }
    } else {
        exit("-1");
    }
} elseif ($act == 'add_templates') {
    $_SESSION['addrand'] = rand(1000, 5000);
    $smarty->assign('addrand', $_SESSION['addrand']);
    $smarty->assign('title', '����ְλģ�� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->display('member_company/company_add_templates.htm');
} elseif ($act == "add_templates_save") {
    $addrand = intval($_POST['addrand']);
    if ($_SESSION['addrand'] == $addrand) {
        unset($_SESSION['addrand']);
        $setsqlarr['title'] = !empty($_POST['title']) ? trim($_POST['title']) : showmsg('����дģ�����ƣ�', 1);
        $setsqlarr['uid'] = intval($_SESSION['uid']);
        $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : showmsg('����дְλ������', 1);
        $setsqlarr['nature'] = intval($_POST['nature']);
        $setsqlarr['nature_cn'] = trim($_POST['nature_cn']);
        $setsqlarr['sex'] = intval($_POST['sex']);
        $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
        $setsqlarr['amount'] = intval($_POST['amount']);
        $setsqlarr['topclass'] = intval($_POST['topclass']);
        $setsqlarr['category'] = !empty($_POST['category']) ? intval($_POST['category']) : showmsg('��ѡ��ְλ���', 1);
        $setsqlarr['subclass'] = intval($_POST['subclass']);
        $setsqlarr['category_cn'] = trim($_POST['category_cn']);
        $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : showmsg('��ѡ����������', 1);
        $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
        $setsqlarr['district_cn'] = trim($_POST['district_cn']);
        $setsqlarr['education'] = intval($_POST['education']);
        $setsqlarr['education_cn'] = trim($_POST['education_cn']);
        $setsqlarr['experience'] = intval($_POST['experience']);
        $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
        $setsqlarr['wage'] = intval($_POST['wage']);
        $setsqlarr['wage_cn'] = trim($_POST['wage_cn']);
        $setsqlarr['tag'] = trim($_POST['tag']);
        $setsqlarr['graduate'] = intval($_POST['graduate']);
        $setsqlarr['negotiable'] = intval($_POST['negotiable']);
        $setsqlarr['minage'] = intval($_POST['minage']);
        $setsqlarr['maxage'] = intval($_POST['maxage']);
        $setsqlarr['addtime'] = time();
        $pid = inserttable(table('jobs_templates'), $setsqlarr, true);
        $link[0]['text'] = "ְλģ���б�";
        $link[0]['href'] = 'company_jobs.php?act=jobs_templates';
        $link[1]['text'] = "��������ְλģ��";
        $link[1]['href'] = 'company_jobs.php?act=add_templates';
        empty($pid) ? showmsg("���ʧ�ܣ�", 0) : showmsg("��ӳɹ���", 2, $link);
    }
} elseif ($act == 'edit_templates') {
    $id = intval($_GET['id']);
    if ($id < 1) {
        showmsg("��ѡ��ְλģ�壡", 1);
    }
    $templates = get_jobs_templates_one($id);
    $_SESSION['addrand'] = rand(1000, 5000);
    $smarty->assign('addrand', $_SESSION['addrand']);
    $smarty->assign('templates', $templates);
    $smarty->assign('title', '�޸�ְλģ�� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->display('member_company/company_edit_templates.htm');
} elseif ($act == "edit_templates_save") {
    $id = intval($_POST['id']);
    if ($id < 1) {
        showmsg("��ѡ��ְλģ�壡", 1);
    }
    $addrand = intval($_POST['addrand']);
    if ($_SESSION['addrand'] == $addrand) {
        unset($_SESSION['addrand']);
        $setsqlarr['title'] = !empty($_POST['title']) ? trim($_POST['title']) : showmsg('����дģ�����ƣ�', 1);
        $setsqlarr['uid'] = intval($_SESSION['uid']);
        $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : showmsg('����дְλ������', 1);
        $setsqlarr['nature'] = intval($_POST['nature']);
        $setsqlarr['nature_cn'] = trim($_POST['nature_cn']);
        $setsqlarr['sex'] = intval($_POST['sex']);
        $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
        $setsqlarr['amount'] = intval($_POST['amount']);
        $setsqlarr['topclass'] = intval($_POST['topclass']);
        $setsqlarr['category'] = !empty($_POST['category']) ? intval($_POST['category']) : showmsg('��ѡ��ְλ���', 1);
        $setsqlarr['subclass'] = intval($_POST['subclass']);
        $setsqlarr['category_cn'] = trim($_POST['category_cn']);
        $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : showmsg('��ѡ����������', 1);
        $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
        $setsqlarr['district_cn'] = trim($_POST['district_cn']);
        $setsqlarr['education'] = intval($_POST['education']);
        $setsqlarr['education_cn'] = trim($_POST['education_cn']);
        $setsqlarr['experience'] = intval($_POST['experience']);
        $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
        $setsqlarr['wage'] = intval($_POST['wage']);
        $setsqlarr['wage_cn'] = trim($_POST['wage_cn']);
        $setsqlarr['tag'] = trim($_POST['tag']);
        $setsqlarr['graduate'] = intval($_POST['graduate']);
        $setsqlarr['negotiable'] = intval($_POST['negotiable']);
        $setsqlarr['minage'] = intval($_POST['minage']);
        $setsqlarr['maxage'] = intval($_POST['maxage']);
        $setsqlarr['addtime'] = time();
        if (!updatetable(table('jobs_templates'), $setsqlarr, " id='{$id}' AND uid='{$_SESSION['uid']}' "))
            showmsg("����ʧ�ܣ�", 0);
        $link[0]['text'] = "ְλģ���б�";
        $link[0]['href'] = 'company_jobs.php?act=jobs_templates';
        $link[1]['text'] = "�鿴�޸Ľ��";
        $link[1]['href'] = 'company_jobs.php?act=edit_templates&id=' . $id;
        showmsg("�޸ĳɹ���", 2, $link);
    }
} elseif ($act == "get_city") {
    $parent_district_id = intval($_GET['id']);
    $sql = "select * from " . table("category_district") . " where parentid = " . $parent_district_id . " order by category_order desc,id asc";
    $ajax_str = "0-ȫ��||";
    $result_arr = $db->getall($sql);
    foreach ($result_arr as $r) {
        $ajax_str .= $r['id'] . "-" . $r['categoryname'] . "||";
    }
    $ajax_str = trim($ajax_str, "||");
    exit($ajax_str);
} elseif ($act == "get_job_type") {
    $parent_job_id = intval($_GET['id']);
    $sql = "select * from " . table("category_jobs") . " where parentid = " . $parent_job_id . " order by category_order desc,id asc";
    $ajax_str = "";
    $result_arr = $db->getall($sql);
    foreach ($result_arr as $r) {
        $ajax_str .= $r['id'] . "-" . $r['categoryname'] . "||";
    }
    $ajax_str = trim($ajax_str, "||");
    exit($ajax_str);
} elseif ($act == "set_timing_refresh") {
    $job_id = intval($_GET['jid']);
    if (!($job_id > 0)) {
        exit('��ѡ��ְλ');
    }
    $val_str = trim($_GET['val_str'], ",");
    if (empty($val_str)) {
        exit('��ѡ��ʱ��');
    }
    $sql = "select * from " . table("jobs") . " where id = " . $job_id;
    $job = $db->getone($sql);
    if (empty($job)) {
        exit('��Чְλ');
    }
    $db->query("Delete from " . table('timing_refresh_job') . " WHERE job_id='" . $job_id . "'");
    $time_arr = explode(",", $val_str);
    foreach ($time_arr as $t) {
        $pid = 0;
        $setsqlarr['uid'] = intval($_SESSION['uid']);
        $setsqlarr['job_id'] = $job_id;
        $setsqlarr['time'] = $t;
        $date = date('Y-m-d', $job['refreshtime']);
        $setsqlarr['last_time'] = strtotime($date);
        $setsqlarr['add_time'] = time();
        $pid = inserttable(table('timing_refresh_job'), $setsqlarr, true);
        if (!$pid > 0) {
            exit($job['jobs_name'] . '����ʧ�ܣ�');
        }
    }
    exit('1');
} elseif ($act == "stop_timing_refresh") {
    $job_id = intval($_GET['job_id']);
    $uid = intval($_SESSION['uid']);
    $sql = "select * from " . table("timing_refresh_job") . " where job_id = " . $job_id . " and uid = " . $uid;
    $timing_refresh_job = $db->getone($sql);
    if (!empty($timing_refresh_job)) {
        $db->query("Delete from " . table('timing_refresh_job') . " WHERE job_id='" . $job_id . "'");
    }
    $sql = "select id from " . table("timing_refresh_job") . " where job_id = " . $job_id;
    $job_timing_refresh = $db->getone($sql);
    if (empty($job_timing_refresh['id'])) {
        exit('ȡ���ɹ�');
    } else {
        exit('ȡ��ʧ��');
    }
} elseif ($act == "batch_set_timing_refresh") {
    $y_id = trim($_GET['y_id'], ",");
    $id_arr = explode(',', $y_id);
    $hour = $_GET['batch_time'];
    $jobs_num = count($id_arr);
    if (empty($id_arr)) {
        exit("��û��ѡ��ְλ��");
    }
    $uid = intval($_SESSION['uid']);
    foreach ($id_arr as $t) {
        if ($hour == 0) {
            $db->query("Delete from " . table('timing_refresh_job') . " WHERE job_id='" . $t . "'");
        } else {
            $sql = "select * from " . table("jobs") . " where id = " . $t;
            $job = $db->getone($sql);
            $setsqlarr['uid'] = $uid;
            $setsqlarr['job_id'] = $t;
            $setsqlarr['time'] = $hour;
            $setsqlarr['last_time'] = $job['refreshtime'];
            $setsqlarr['add_time'] = time();
            $sql = "select * from " . table("timing_refresh_job") . " where job_id = " . $t;
            $job_timing_refresh = $db->getone($sql);
            if (!empty($job_timing_refresh)) {
                unset($setsqlarr['add_time']);
                $wheresql = " job_id='" . $t . "' ";
                updatetable(table('timing_refresh_job'), $setsqlarr, $wheresql);
            } else {
                inserttable(table('timing_refresh_job'), $setsqlarr, true);
            }
        }
    }
    if ($hour == 0) {
        exit('ȡ���ɹ�����ȡ��' . $jobs_num . "��ְλ");
    } else {
        exit('���óɹ���������' . $jobs_num . "��ְλ");
    }
}

function promotion_add_save($jobsid, $days, $type) {
    global $_CFG;
    $jobs = get_jobs_one($jobsid, $_SESSION['uid']);
    if ($jobs['deadline'] < time()) {
        showmsg("��ְλ�ѵ��ڣ��������ڣ�", 1);
    }
    if ($jobsid > 0 && $days > 0) {
        $pro_cat = get_promotion_category_one(intval($type));
        if ($_CFG['operation_mode'] == '3') {
            $setmeal = get_setmeal_promotion($_SESSION['uid'], intval($type)); //��ȡ��Ա�ײ�
            $num = $setmeal['num'];
            if (($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') || $num <= 0) {
                if ($_CFG['setmeal_to_points'] == 1) {
                    if ($pro_cat['cat_points'] > 0) {
                        $points = $pro_cat['cat_points'] * $days;
                        $user_points = get_user_points($_SESSION['uid']);
                        $user_limit_points = get_user_limit_points($_SESSION['uid']);
                        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                        $user_points = $user_points + $user_limit_points;
                        if ($points > $user_points) {
                            $link[0]['text'] = "������һҳ";
                            $link[0]['href'] = 'javascript:history.go(-1)';
                            $link[1]['text'] = "��ֵ����";
                            $link[1]['href'] = 'company_service.php?act=order_add';
                            showmsg("���" . $_CFG['points_byname'] . "�������д˴β��������ȳ�ֵ��", 1, $link);
                        } else {
                            $_CFG['operation_mode'] = 1;
                        }
                    } else {
                        $_CFG['operation_mode'] = 2;
                    }
                } else {
                    $link[0]['text'] = "������һҳ";
                    $link[0]['href'] = 'javascript:history.go(-1)';
                    $link[1]['text'] = "���¿�ͨ����";
                    $link[1]['href'] = 'company_service.php?act=setmeal_list';
                    showmsg("����ײ��ѵ��ڻ��ײ���ʣ��{$pro_cat['cat_name']}�������뾡�쿪ͨ���ײ�", 1, $link);
                }
            } else {
                $_CFG['operation_mode'] = 2;
            }
        } elseif ($_CFG['operation_mode'] == '1') {
            if ($pro_cat['cat_points'] > 0) {
                $points = $pro_cat['cat_points'] * $days;
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $user_points = $user_points + $user_limit_points;
                if ($points > $user_points) {
                    $link[0]['text'] = "������һҳ";
                    $link[0]['href'] = 'javascript:history.go(-1)';
                    $link[1]['text'] = "��ֵ����";
                    $link[1]['href'] = 'company_service.php?act=order_add';
                    showmsg("���" . $_CFG['points_byname'] . "�������д˴β��������ȳ�ֵ��", 1, $link);
                }
            }
        } elseif ($_CFG['operation_mode'] == '2') {
            $setmeal = get_setmeal_promotion($_SESSION['uid'], intval($type)); //��ȡ��Ա�ײ�
            $num = $setmeal['num'];
            if (($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') || $num <= 0) {
                $link[0]['text'] = "������һҳ";
                $link[0]['href'] = 'javascript:history.go(-1)';
                $link[1]['text'] = "���¿�ͨ����";
                $link[1]['href'] = 'company_service.php?act=setmeal_list';
                showmsg("����ײ��ѵ��ڻ��ײ���ʣ��{$pro_cat['cat_name']}�������뾡�쿪ͨ���ײ�", 1, $link);
            }
        }
        $info = get_promotion_one($jobsid, $_SESSION['uid'], $type);
        if (!empty($info)) {
            showmsg("��ְλ�����ƹ��У���ѡ������ְλ����������", 1);
        }
        $setsqlarr['cp_available'] = 1;
        $setsqlarr['cp_promotionid'] = intval($type);
        $setsqlarr['cp_uid'] = $_SESSION['uid'];
        $setsqlarr['cp_jobid'] = $jobsid;
        $setsqlarr['cp_days'] = $days;
        $setsqlarr['cp_starttime'] = time();
        $setsqlarr['cp_endtime'] = strtotime("{$days} day");
        if (inserttable(table('promotion'), $setsqlarr)) {
            set_job_promotion($jobsid, $setsqlarr['cp_promotionid']);
            $jobs = get_jobs_one($jobsid, $_SESSION['uid']);
            if ($_CFG['operation_mode'] == '1' && $pro_cat['cat_points'] > 0) {
                report_deal($_SESSION['uid'], 2, $points);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "{$pro_cat['cat_name']}��<strong>{$jobs['jobs_name']}</strong>���ƹ� {$days} �죬(-{$points})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})", 1, 1018, "{$pro_cat['cat_name']}", "-{$points}", "{$user_points}");
            } elseif ($_CFG['operation_mode'] == '2') {
                foreach ($_POST['pro_name'] as $pn) {
                    if (!empty($pn)) {
                        $user_pname = trim($pn);
                        action_user_setmeal($_SESSION['uid'], $user_pname); //�����ײ�����Ӧ�ƹ㷽ʽ������
                        $setmeal = get_user_setmeal($_SESSION['uid']); //��ȡ��Ա�ײ�
                        write_memberslog($_SESSION['uid'], 1, 9002, $_SESSION['username'], "{$pro_cat['cat_name']}��<strong>{$jobs['jobs_name']}</strong>���ƹ� {$days} �죬�ײ���ʣ��{$pro_cat['cat_name']}������{$setmeal[$user_pname]}����", 2, 1018, "{$pro_cat['cat_name']}", "-{$days}", "{$setmeal[$user_pname]}"); //9002���ײͲ���
                    }
                }
            }
            write_memberslog($_SESSION['uid'], 1, 3004, $_SESSION['username'], "{$pro_cat['cat_name']}��<strong>{$jobs['jobs_name']}</strong>���ƹ� {$days} �졣");
        }
    } else {
        showmsg("��������", 0);
    }
}

unset($smarty);
?>