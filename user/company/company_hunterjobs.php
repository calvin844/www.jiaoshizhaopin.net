<?php

/*
 * 74cms ��ҵ��Ա����(�߼�ְλ)
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
if ($act == 'hunterjobs') {
    $wheresql = " WHERE uid='{$_SESSION['uid']}' ";
    $orderby = " order by refreshtime desc";
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $perpage = 10;
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('hunter_jobs') . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $offset = ($page->nowindex - 1) * $perpage;
    $smarty->assign('title', '�߼�ְλ���� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $sql = "SELECT * FROM " . table('hunter_jobs') . $wheresql . $orderby;
    $smarty->assign('hunterjobs', get_hunterjobs($offset, $perpage, $sql, true));
    $smarty->assign('page', $page->show(3));
    $smarty->assign('points_rule', get_cache('points_rule'));
    $smarty->display('member_company/company_hunterjobs.htm');
} elseif ($act == 'add_hunterjobs') {
    $smarty->assign('user', $user);
    if ($company_profile['companyname']) {
        $_SESSION['addrand'] = rand(1000, 5000);
        $smarty->assign('addrand', $_SESSION['addrand']);
        $smarty->assign('title', '�����߼�ְλ - ��ҵ��Ա���� - ' . $_CFG['site_name']);
        $smarty->assign('company_profile', $company_profile);
        if ($_CFG['operation_mode'] == "2") {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            $smarty->assign('setmeal', $setmeal);
            $smarty->assign('add_mode', 2);
        } elseif ($_CFG['operation_mode'] == "1") {
            $smarty->assign('points_total', get_user_points($_SESSION['uid']));
            $smarty->assign('points', get_cache('points_rule'));
            $smarty->assign('add_mode', 1);
        }
        $captcha = get_cache('captcha');
        $smarty->assign('verify_addjob', $captcha['verify_addjob']);
        $smarty->display('member_company/company_add_hunterjobs.htm');
    } else {
        $link[0]['text'] = "������ҵ����";
        $link[0]['href'] = 'company_info.php?act=company_profile';
        showmsg("Ϊ�˴ﵽ���õ���ƸЧ������������������ҵ���ϣ�", 1, $link);
    }
} elseif ($act == 'addjobs_save') {
    $captcha = get_cache('captcha');
    $postcaptcha = trim($_POST['postcaptcha']);
    if ($captcha['verify_addjob'] == '1' && empty($postcaptcha)) {
        showmsg("����д��֤��", 1);
    }
    if ($captcha['verify_addjob'] == '1' && strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        showmsg("��֤�����", 1);
    }
    $add_mode = trim($_POST['add_mode']);
    $days = intval($_POST['days']);
    if ($days < $_CFG['hunter_add_days_min']) {
        showmsg("��Чʱ������Ϊ " . $_CFG['hunter_add_days_min'] . " �죡", 1);
    }
    if ($_CFG['operation_mode'] == '1') {
        $points_rule = get_cache('points_rule');
        $user_points = get_user_points($_SESSION['uid']);
        $user_limit_points = get_user_limit_points($_SESSION['uid']);
        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
        $user_points = $user_points + $user_limit_points;
        $total = 0;
        if ($points_rule['hunterjobs_add']['type'] == "2" && $points_rule['hunterjobs_add']['value'] > 0) {
            $total = $points_rule['hunterjobs_add']['value'];
        }
        if ($points_rule['hunterjobs_daily']['type'] == "2" && $points_rule['hunterjobs_daily']['value'] > 0) {
            $total = $total + ($days * $points_rule['hunterjobs_daily']['value']);
        }
        if ($total > $user_points) {
            $link[0]['text'] = "������ֵ";
            $link[0]['href'] = 'company_service.php?act=order_add';
            $link[1]['text'] = "��Ա������ҳ";
            $link[1]['href'] = 'company_index.php?act=';
            showmsg("���" . $_CFG['points_byname'] . "���㣬���ֵ���ٷ�����", 0, $link);
        }
        $setsqlarr['setmeal_deadline'] = 0;
    } elseif ($_CFG['operation_mode'] == '2') {
        $link[0]['text'] = "������ͨ����";
        $link[0]['href'] = 'company_service.php?act=setmeal_list';
        $link[1]['text'] = "��Ա������ҳ";
        $link[1]['href'] = 'company_index.php?act=';
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            showmsg("���ķ����Ѿ����ڣ������¿�ͨ", 1, $link);
        }
        if ($setmeal['jobs_hunter'] <= 0) {
            showmsg("��ǰ�����ĸ߼�ְλ�Ѿ�������������ƣ������������ײͣ�", 1, $link);
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
        $setsqlarr['companyname'] = $company_profile['companyname'];
        $setsqlarr['companyname_note'] = $company_profile['companyname'];
        $setsqlarr['scale'] = $company_profile['scale'];
        $setsqlarr['scale_cn'] = $company_profile['scale_cn'];
        $setsqlarr['trade'] = $company_profile['trade'];
        $setsqlarr['trade_cn'] = $company_profile['trade_cn'];
        $setsqlarr['nature'] = $company_profile['nature'];
        $setsqlarr['nature_cn'] = $company_profile['nature_cn'];
        $setsqlarr['company_desc'] = $company_profile['contents'];

        $setsqlarr['jobs_name'] = !empty($_POST['jobs_name']) ? trim($_POST['jobs_name']) : showmsg('��û����дְλ���ƣ�', 1);
        $setsqlarr['category'] = !empty($_POST['category']) ? intval($_POST['category']) : showmsg('��ѡ��ְλ���', 1);
        $setsqlarr['subclass'] = intval($_POST['subclass']);
        $setsqlarr['category_cn'] = trim($_POST['category_cn']);

        $setsqlarr['department'] = !empty($_POST['department']) ? trim($_POST['department']) : showmsg('��û����д�������ţ�', 1);
        $setsqlarr['reporter'] = !empty($_POST['reporter']) ? trim($_POST['reporter']) : showmsg('��û����д�㱨����', 1);

        $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : showmsg('��ѡ����������', 1);
        $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
        $setsqlarr['district_cn'] = trim($_POST['district_cn']);

        $setsqlarr['wage'] = !empty($_POST['wage']) ? intval($_POST['wage']) : showmsg('��ѡ����н��Χ��', 1);
        $setsqlarr['wage_cn'] = trim($_POST['wage_cn']);
        $setsqlarr['wage_structure'] = !empty($_POST['wage_structure']) ? $_POST['wage_structure'] : showmsg('��ѡ��н�ʹ��ɣ�', 1);
        $setsqlarr['socialbenefits'] = trim($_POST['socialbenefits']);
        $setsqlarr['livebenefits'] = trim($_POST['livebenefits']);
        $setsqlarr['annualleave'] = trim($_POST['annualleave']);
        $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : showmsg('��û����дְλ������', 1);
        check_word($_CFG['filter'], $_POST['contents']) ? showmsg($_CFG['filter_tips'], 0) : '';


        $setsqlarr['age'] = !empty($_POST['age']) ? intval($_POST['age']) : showmsg('��ѡ������Ҫ��', 1);
        $setsqlarr['age_cn'] = trim($_POST['age_cn']);
        $setsqlarr['sex'] = intval($_POST['sex']);
        $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
        $setsqlarr['experience'] = !empty($_POST['experience']) ? intval($_POST['experience']) : showmsg('��ѡ��������Ҫ��', 1);
        $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
        $setsqlarr['education'] = !empty($_POST['education']) ? intval($_POST['education']) : showmsg('��ѡ��ѧ��Ҫ��', 1);
        $setsqlarr['education_cn'] = trim($_POST['education_cn']);
        $setsqlarr['tongzhao'] = intval($_POST['tongzhao']);
        $setsqlarr['tongzhao_cn'] = trim($_POST['tongzhao_cn']);
        $setsqlarr['language'] = trim($_POST['language']);
        $setsqlarr['jobs_qualified'] = !empty($_POST['jobs_qualified']) ? trim($_POST['jobs_qualified']) : showmsg('��û����д��ְ�ʸ�', 1);
        check_word($_CFG['filter'], $_POST['jobs_qualified']) ? showmsg($_CFG['filter_tips'], 0) : '';

        $setsqlarr['hopetrade'] = !empty($_POST['hopetrade']) ? trim($_POST['hopetrade']) : showmsg('��ѡ�������˲���Դ��ҵ��', 1);
        $setsqlarr['hopetrade_cn'] = trim($_POST['hopetrade_cn']);
        $setsqlarr['extra_message'] = trim($_POST['extra_message']);

        $setsqlarr['addtime'] = $timestamp;
        $setsqlarr['deadline'] = strtotime("" . intval($_POST['days']) . " day");
        $setsqlarr['refreshtime'] = $timestamp;
        $setsqlarr['key'] = $setsqlarr['jobs_name'] . $company_profile['companyname'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'] . $setsqlarr['jobs_qualified'];
        require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
        $sp = new SPWord();
        $setsqlarr['key'] = "{$setsqlarr['jobs_name']} {$company_profile['companyname']} " . $sp->extracttag($setsqlarr['key']);
        $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
        $setsqlarr['likekey'] = $setsqlarr['jobs_name'] . ',' . $setsqlarr['companyname'];
        $setsqlarr['subsite_id'] = intval($_CFG['subsite_id']);
        if ($company_profile['audit'] == "1") {
            $setsqlarr['audit'] = intval($_CFG['audit_verifycom_addjob']);
        } else {
            $setsqlarr['audit'] = intval($_CFG['audit_unexaminedcom_addjob']);
        }
        $setsqlarr['contact'] = !empty($_POST['contact']) ? trim($_POST['contact']) : showmsg('��û����д��ϵ�ˣ�', 1);
        $setsqlarr['qq'] = trim($_POST['qq']);
        $setsqlarr['telephone'] = !empty($_POST['telephone']) ? trim($_POST['telephone']) : showmsg('��û����д��ϵ�绰��', 1);
        check_word($_CFG['filter'], $_POST['telephone']) ? showmsg($_CFG['filter_tips'], 0) : '';
        $setsqlarr['address'] = !empty($_POST['address']) ? trim($_POST['address']) : showmsg('��û����д��ϵ��ַ��', 1);
        $setsqlarr['email'] = !empty($_POST['email']) ? trim($_POST['email']) : showmsg('��û����д��ϵ���䣡', 1);
        $setsqlarr['notify'] = intval($_POST['notify']);

        $setsqlarr['contact_show'] = intval($_POST['contact_show']);
        $setsqlarr['email_show'] = intval($_POST['email_show']);
        $setsqlarr['telephone_show'] = intval($_POST['telephone_show']);
        $setsqlarr['address_show'] = intval($_POST['address_show']);
        $setsqlarr['qq_show'] = intval($_POST['qq_show']);

        //���ְλ��Ϣ
        $pid = inserttable(table('hunter_jobs'), $setsqlarr, true);
        empty($pid) ? showmsg("���ʧ�ܣ�", 0) : '';
        //�����ϵ��ʽ
        if ($_CFG['operation_mode'] == '1') {
            if ($points_rule['hunterjobs_add']['value'] > 0) {
                report_deal($_SESSION['uid'], $points_rule['hunterjobs_add']['type'], $points_rule['hunterjobs_add']['value']);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $points_rule['hunterjobs_add']['type'] == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "�����˸߼�ְλ��<strong>{$setsqlarr['jobs_name']}</strong>��({$operator}{$points_rule['hunterjobs_add']['value']})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})");
            }
            if (intval($_POST['days']) > 0 && $points_rule['hunterjobs_daily']['value'] > 0) {
                $points_day = intval($_POST['days']) * $points_rule['hunterjobs_daily']['value'];
                report_deal($_SESSION['uid'], $points_rule['hunterjobs_daily']['type'], $points_day);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $points_rule['hunterjobs_daily']['type'] == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "�����߼�ְλ:<strong>{$_POST['jobs_name']}</strong>����Ч��Ϊ{$_POST['days']}�죬({$operator}{$points_day})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})");
            }
        } elseif ($_CFG['operation_mode'] == '2') {
            action_user_setmeal($_SESSION['uid'], "jobs_hunter");
            $setmeal = get_user_setmeal($_SESSION['uid']);
            write_memberslog($_SESSION['uid'], 1, 9002, $_SESSION['username'], "�����߼�ְλ:<strong>{$_POST['jobs_name']}</strong>�������Է����߼�ְλ:<strong>{$setmeal['jobs_hunter']}</strong>��");
        }
        write_memberslog($_SESSION['uid'], 1, 2001, $_SESSION['username'], "�����˸߼�ְλ��{$setsqlarr['jobs_name']}");
    }
    $link[0]['text'] = "�ѷ���ְλ";
    $link[0]['href'] = '?act=hunterjobs';
    $link[1]['text'] = "��������ְλ";
    $link[1]['href'] = '?act=add_hunterjobs';
    $link[3]['text'] = "��Ա������ҳ";
    $link[3]['href'] = "company_index.php?";
    showmsg("�����ɹ���", 2, $link);
} elseif ($act == 'jobs_perform') {
    $yid = !empty($_POST['y_id']) ? $_POST['y_id'] : $_GET['y_id'];
    $jobs_num = count($yid);
    if (empty($yid)) {
        showmsg("��û��ѡ��ְλ��", 1);
    }
    $refresh = !empty($_POST['refresh']) ? $_POST['refresh'] : $_GET['refresh'];
    $delete = !empty($_POST['delete']) ? $_POST['delete'] : $_GET['delete'];
    if ($refresh) {
        if ($_CFG['operation_mode'] == '1') {
            $points_rule = get_cache('points_rule');
            if ($points_rule['hunterjobs_refresh']['value'] > 0) {
                $user_points = get_user_points($_SESSION['uid']);
                $total_point = $jobs_num * $points_rule['hunterjobs_refresh']['value'];
                if ($total_point > $user_points && $points_rule['hunterjobs_refresh']['type'] == "2") {
                    $link[0]['text'] = "������һҳ";
                    $link[0]['href'] = 'javascript:history.go(-1)';
                    $link[1]['text'] = "������ֵ";
                    $link[1]['href'] = 'company_service.php?act=order_add';
                    showmsg("����" . $_CFG['points_byname'] . "���㣬���ȳ�ֵ��", 0, $link);
                }
                report_deal($_SESSION['uid'], $points_rule['hunterjobs_refresh']['type'], $total_point);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $points_rule['hunterjobs_refresh']['type'] == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "ˢ����{$jobs_num}���߼�ְλ��({$operator}{$total_point})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})");
            }
        }
        refresh_hunterjobs($yid);
        write_memberslog($_SESSION['uid'], 1, 2004, $_SESSION['username'], "ˢ���˸߼�ְλ");
        showmsg("ˢ��ְλ�ɹ���", 2);
    } elseif ($delete) {
        if ($n = del_hunterjobs($yid, $_SESSION['uid'])) {
            showmsg("ɾ���ɹ�����ɾ�� {$n} ��", 2);
        } else {
            showmsg("ɾ��ʧ�ܣ�", 0);
        }
    } elseif (!empty($_POST['display1'])) {
        activate_hunterjobs($yid, 1, $_SESSION['uid']);
        showmsg("���óɹ���", 2);
    } elseif (!empty($_POST['display2'])) {
        activate_hunterjobs($yid, 2, $_SESSION['uid']);
        showmsg("���óɹ���", 2);
    }
} elseif ($act == 'edit_hunterjobs') {
    $jobs = get_hunterjobs_one(intval($_GET['id']), $_SESSION['uid']);
    if (empty($jobs))
        showmsg("��������", 1);
    $smarty->assign('user', $user);
    $smarty->assign('title', '�޸ĸ߼�ְλ - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('points_total', get_user_points($_SESSION['uid']));
    $smarty->assign('points', get_cache('points_rule'));
    $smarty->assign('jobs', $jobs);
    $smarty->display('member_company/company_edit_hunterjobs.htm');
}
elseif ($act == 'edit_hunterjobs_save') {
    $id = intval($_POST['id']);
    $days = intval($_POST['days']);
    if ($_CFG['operation_mode'] == '1') {
        $points_rule = get_cache('points_rule');
        $user_points = get_user_points($_SESSION['uid']);
        $user_limit_points = get_user_limit_points($_SESSION['uid']);
        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
        $user_points = $user_points + $user_limit_points;
        $total = 0;
        if ($points_rule['hunterjobs_edit']['type'] == "2" && $points_rule['hunterjobs_edit']['value'] > 0) {
            $total = $points_rule['hunterjobs_edit']['value'];
        }
        if ($points_rule['hunterjobs_daily']['type'] == "2" && $points_rule['hunterjobs_daily']['value'] > 0) {
            $total = $total + ($days * $points_rule['hunterjobs_daily']['value']);
        }
        if ($total > $user_points) {
            $link[0]['text'] = "������һҳ";
            $link[0]['href'] = 'javascript:history.go(-1)';
            $link[1]['text'] = "������ֵ";
            $link[1]['href'] = 'company_service.php?act=order_add';
            showmsg("���" . $_CFG['points_byname'] . "���㣬���ֵ���ٷ�����", 0, $link);
        }
    } elseif ($_CFG['operation_mode'] == '2') {
        $link[0]['text'] = "������ͨ����";
        $link[0]['href'] = 'company_service.php?act=setmeal_list';
        $link[1]['text'] = "��Ա������ҳ";
        $link[1]['href'] = 'company_index.php?act=';
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            showmsg("����Ϣͨ�������ײͷ����������ײ��Ѿ����ڣ������¿�ͨ", 1, $link);
        }
    }

    $setsqlarr['jobs_name'] = !empty($_POST['jobs_name']) ? trim($_POST['jobs_name']) : showmsg('��û����дְλ���ƣ�', 1);
    $setsqlarr['category'] = !empty($_POST['category']) ? intval($_POST['category']) : showmsg('��ѡ��ְλ���', 1);
    $setsqlarr['subclass'] = intval($_POST['subclass']);
    $setsqlarr['category_cn'] = trim($_POST['category_cn']);

    $setsqlarr['department'] = !empty($_POST['department']) ? trim($_POST['department']) : showmsg('��û����д�������ţ�', 1);
    $setsqlarr['reporter'] = !empty($_POST['reporter']) ? trim($_POST['reporter']) : showmsg('��û����д�㱨����', 1);

    $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : showmsg('��ѡ����������', 1);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);

    $setsqlarr['wage'] = !empty($_POST['wage']) ? intval($_POST['wage']) : showmsg('��ѡ����н��Χ��', 1);
    $setsqlarr['wage_cn'] = trim($_POST['wage_cn']);
    $setsqlarr['wage_structure'] = !empty($_POST['wage_structure']) ? $_POST['wage_structure'] : showmsg('��ѡ��н�ʹ��ɣ�', 1);
    $setsqlarr['socialbenefits'] = trim($_POST['socialbenefits']);
    $setsqlarr['livebenefits'] = trim($_POST['livebenefits']);
    $setsqlarr['annualleave'] = trim($_POST['annualleave']);
    $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : showmsg('��û����дְλ������', 1);
    check_word($_CFG['filter'], $_POST['contents']) ? showmsg($_CFG['filter_tips'], 0) : '';


    $setsqlarr['age'] = !empty($_POST['age']) ? intval($_POST['age']) : showmsg('��ѡ������Ҫ��', 1);
    $setsqlarr['age_cn'] = trim($_POST['age_cn']);
    $setsqlarr['sex'] = intval($_POST['sex']);
    $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
    $setsqlarr['experience'] = !empty($_POST['experience']) ? intval($_POST['experience']) : showmsg('��ѡ��������Ҫ��', 1);
    $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
    $setsqlarr['education'] = !empty($_POST['education']) ? intval($_POST['education']) : showmsg('��ѡ��ѧ��Ҫ��', 1);
    $setsqlarr['education_cn'] = trim($_POST['education_cn']);
    $setsqlarr['tongzhao'] = intval($_POST['tongzhao']);
    $setsqlarr['tongzhao_cn'] = trim($_POST['tongzhao_cn']);
    $setsqlarr['language'] = trim($_POST['language']);
    $setsqlarr['jobs_qualified'] = !empty($_POST['jobs_qualified']) ? trim($_POST['jobs_qualified']) : showmsg('��û����д��ְ�ʸ�', 1);
    check_word($_CFG['filter'], $_POST['jobs_qualified']) ? showmsg($_CFG['filter_tips'], 0) : '';

    $setsqlarr['hopetrade'] = !empty($_POST['hopetrade']) ? trim($_POST['hopetrade']) : showmsg('��ѡ�������˲���Դ��ҵ��', 1);
    $setsqlarr['hopetrade_cn'] = trim($_POST['hopetrade_cn']);
    $setsqlarr['extra_message'] = trim($_POST['extra_message']);

    $setsqlarr['deadline'] = strtotime("" . intval($_POST['days']) . " day");
    $setsqlarr['refreshtime'] = $timestamp;
    $setsqlarr['key'] = $setsqlarr['jobs_name'] . $company_profile['companyname'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'] . $setsqlarr['jobs_qualified'];
    require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
    $sp = new SPWord();
    $setsqlarr['key'] = "{$setsqlarr['jobs_name']} {$company_profile['companyname']} " . $sp->extracttag($setsqlarr['key']);
    $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
    $setsqlarr['likekey'] = $setsqlarr['jobs_name'] . ',' . $setsqlarr['companyname'];
    $setsqlarr['subsite_id'] = intval($_CFG['subsite_id']);

    $setsqlarr['contact'] = !empty($_POST['contact']) ? trim($_POST['contact']) : showmsg('��û����д��ϵ�ˣ�', 1);
    $setsqlarr['qq'] = trim($_POST['qq']);
    $setsqlarr['telephone'] = !empty($_POST['telephone']) ? trim($_POST['telephone']) : showmsg('��û����д��ϵ�绰��', 1);
    check_word($_CFG['filter'], $_POST['telephone']) ? showmsg($_CFG['filter_tips'], 0) : '';
    $setsqlarr['address'] = !empty($_POST['address']) ? trim($_POST['address']) : showmsg('��û����д��ϵ��ַ��', 1);
    $setsqlarr['email'] = !empty($_POST['email']) ? trim($_POST['email']) : showmsg('��û����д��ϵ���䣡', 1);
    $setsqlarr['notify'] = intval($_POST['notify']);

    $setsqlarr['contact_show'] = intval($_POST['contact_show']);
    $setsqlarr['email_show'] = intval($_POST['email_show']);
    $setsqlarr['telephone_show'] = intval($_POST['telephone_show']);
    $setsqlarr['address_show'] = intval($_POST['address_show']);
    $setsqlarr['qq_show'] = intval($_POST['qq_show']);

    if ($_CFG['operation_mode'] == '1') {
        $setsqlarr['setmeal_deadline'] = 0;
        $setsqlarr['add_mode'] = 1;
    } elseif ($_CFG['operation_mode'] == '2') {
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
    $audit = intval($_POST['audit']);
    if ($company_profile['audit'] == "1") {
        $_CFG['audit_verifycom_editjob'] <> "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_verifycom_editjob']) : $audit;
    } else {
        $_CFG['audit_unexaminedcom_editjob'] <> "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_unexaminedcom_editjob']) : $audit;
    }

    if (!updatetable(table('hunter_jobs'), $setsqlarr, " id='{$id}' AND uid='{$_SESSION['uid']}' "))
        showmsg("����ʧ�ܣ�", 0);

    if ($_CFG['operation_mode'] == '1') {
        if ($points_rule['hunterjobs_edit']['value'] > 0) {
            report_deal($_SESSION['uid'], $points_rule['hunterjobs_edit']['type'], $points_rule['hunterjobs_edit']['value']);
            $user_points = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $operator = $points_rule['hunterjobs_edit']['type'] == "1" ? "+" : "-";
            write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "�޸ĸ߼�ְλ��<strong>{$setsqlarr['jobs_name']}</strong>��({$operator}{$points_rule['hunterjobs_edit']['value']})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})");
        }
        if ($days > 0 && $points_rule['hunterjobs_daily']['value'] > 0) {
            $points_day = intval($_POST['days']) * $points_rule['hunterjobs_daily']['value'];
            report_deal($_SESSION['uid'], $points_rule['hunterjobs_daily']['type'], $points_day);
            $user_points = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $operator = $points_rule['hunterjobs_daily']['type'] == "1" ? "+" : "-";
            write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "�ӳ��߼�ְλ({$_POST['jobs_name']})��Ч��Ϊ{$_POST['days']}�죬({$operator}{$points_day})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})");
        }
    }
    $link[0]['text'] = "ְλ�б�";
    $link[0]['href'] = '?act=hunterjobs';
    $link[1]['text'] = "�鿴�޸Ľ��";
    $link[1]['href'] = "?act=edit_hunterjobs&id={$id}";
    $link[2]['text'] = "��Ա������ҳ";
    $link[2]['href'] = "company_index.php";

    write_memberslog($_SESSION['uid'], $_SESSION['utype'], 2002, $_SESSION['username'], "�޸��˸߼�ְλ��{$setsqlarr['jobs_name']}��ְλID��{$id}");
    showmsg("�޸ĳɹ���", 2, $link);
} elseif ($act == 'down_resume_list') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $perpage = 10;
    $joinsql = " LEFT JOIN  " . table('manager_resume') . " as r ON d.resume_id=r.id ";
    $wheresql = " WHERE  d.user_uid='{$_SESSION['uid']}' ";
    $settr = intval($_GET['settr']);
    if ($settr > 0) {
        $settr_val = strtotime("-{$settr} day");
        $wheresql.=" AND d.down_addtime>{$settr_val} ";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('user_down_manager_resume') . " as d" . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $smarty->assign('title', '�����صľ����˼��� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $smarty->assign('list', get_down_manager_resume($offset, $perpage, $joinsql . $wheresql));
    $smarty->assign('page', $page->show(3));
    $smarty->display('member_company/company_down_manager_resume.htm');
} elseif ($act == 'del_down') {
    $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : showmsg("��û��ѡ�����ؼ�¼��", 1);
    if ($n = del_down_manager($yid, $_SESSION['uid'])) {
        showmsg("ɾ���ɹ�����ɾ�� {$n} ��", 2);
    } else {
        showmsg("ʧ�ܣ�", 0);
    }
}


unset($smarty);
?>