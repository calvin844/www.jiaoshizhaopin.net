<?php

/*
 * 74cms ���˻�Ա����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/personal_common.php');
$smarty->assign('leftmenu', "resume");
$act = !empty($act) ? $act : "userprofile";
//�����б�
if ($act == 'resume_list') {
    $wheresql = " WHERE uid='" . $_SESSION['uid'] . "' ";
    $sql = "SELECT * FROM " . table('resume') . $wheresql;
    $smarty->assign('title', '�ҵļ��� - ���˻�Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $total = $db->get_total("SELECT COUNT(*) AS num FROM " . table('resume') . $wheresql);
    $smarty->assign('total', $total);
    $smarty->assign('resume_list', get_resume_list($sql, 12, true, true, true));
    $smarty->display('member_personal/personal_resume_list.htm');
} elseif ($act == 'upload_resume') {
    $smarty->assign('title', '�ϴ����� - ���˻�Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $smarty->display('member_personal/personal_resume_upload.htm');
} elseif ($act == 'upload_resume_save') {
    /* ���߼����ϴ����ܣ�δ�� */
    !$_FILES['resume_file']['name'] ? exit('���ϴ��ļ���') : "";
    require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
    $resume_dir = "../../data/resume_file/" . date("Y/m/d/");
    make_dir($resume_dir);
    $resume_file_path = _asUpFiles($resume_dir, "resume_file", 10 * 1024, 'html/htm', true);
    $content = file_get_contents($resume_dir . $resume_file_path);
    $fullname_rule = '/<td colspan="2" class="name">(.*)<\/td>/';
    preg_match($fullname_rule, $content, $fullname_result);
    $in_resume['fullname'] = trim($fullname_result[1]);
    $sex_rule = '/<p><img class="vam" src=".*" width="20" height="20">(.*?)<span class="p5">\|/';
    preg_match($sex_rule, $content, $sex_result);
    $in_resume['sex'] = $sex_result[1] == "��" ? 1 : 2;
    $in_resume['sex_cn'] = $sex_result[1] == "��" ? "��" : "Ů";
    $birthdate_rule = '/<span class="p5">\|<\/span>.* �� \((.*?)\/.*?\/.*?\)<span class="p5">/';
    preg_match($birthdate_rule, $content, $birthdate_result);
    $in_resume['birthdate'] = intval($birthdate_result[1]);
    $education_rule = '/<td valign="top" class="keys">ѧ��\/ѧλ��<\/td>[.\n*]<td valign="top" class="txt2">(.*?)<\/td>/';
    preg_match($education_rule, $content, $education_result);
    $education_category = set_category('QS_education');
    foreach ($education_category as $e) {
        if ($e['c_name'] == "����" && $in_resume['education'] == "") {
            $in_resume['education'] = $e['c_id'];
            $in_resume['education_cn'] = $e['c_name'];
        }
        if ($e['c_name'] == $education_result[1]) {
            $in_resume['education'] = $e['c_id'];
            $in_resume['education_cn'] = $e['c_name'];
        }
    }
    $experience_rule = '/\)<span class="p5">\|<\/span>.*<span class="p5">\|<\/span>(.*?)�깤������/';
    preg_match($experience_rule, $content, $experience_result);
    $experience_num = intval($experience_result[1]);
    switch ($experience_num) {
        case $experience_num <= 1:
            $in_resume['experience'] = 75;
            $in_resume['experience_cn'] = '1������';
            break;
        case $experience_num > 1 && $experience_num < 4:
            $in_resume['experience'] = 76;
            $in_resume['experience_cn'] = '1-3��';
            break;
        case $experience_num > 3 && $experience_num < 6:
            $in_resume['experience'] = 77;
            $in_resume['experience_cn'] = '3-5��';
            break;
        case $experience_num > 5 && $experience_num < 11:
            $in_resume['experience'] = 78;
            $in_resume['experience_cn'] = '5-10��';
            break;
        case $experience_num > 10:
            $in_resume['experience'] = 79;
            $in_resume['experience_cn'] = '10������';
            break;
        default:
            $in_resume['experience'] = 74;
            $in_resume['experience_cn'] = '�޾���';
            break;
    }
    $mobile_rule = '/<img class="vam" src="http:\/\/img01\.51jobcdn\.com\/im\/2016\/resume\/y2\.png" width="20" height="20">(.*?)<\/td>/';
    preg_match($mobile_rule, $content, $mobile_result);
    $in_resume['mobile'] = $mobile_result[1];
    $email_rule = '/<td valign="top" class="txt4">(.*?)<\/td>/';
    preg_match($email_rule, $content, $email_result);
    $in_resume['email'] = trim($email_result[1]);
    if (empty($in_resume['email'])) {
        $email = $db->getone("SELECT email FROM " . table('members') . " WHERE uid='" . intval($_SESSION['uid']) . "'");
        $in_resume['email'] = $email['email'];
    }
    $residence_rule = '/<\/span>�־�ס(.*?)<span/';
    preg_match($residence_rule, $content, $residence_result);
    $residence_arr = explode("-", $residence_result[1]);
    $residence_data = $db->getone("SELECT * FROM " . table('category_district') . " WHERE categoryname='" . trim($residence_arr[1]) . "'");
    if (empty($residence_data)) {
        $residence_data = $db->getone("SELECT * FROM " . table('category_district') . " WHERE categoryname='" . trim($residence_arr[0]) . "'");
    }
    if (!empty($residence_data)) {
        $residence_data_p = "";
        if ($residence_data['parentid'] > 0) {
            $residence_data_p = $db->getone("SELECT * FROM " . table('category_district') . " WHERE id='" . $residence_data['parentid'] . "'");
        }
        if (!empty($residence_data_p)) {
            $in_resume['residence'] = $residence_data_p['id'] . '.' . $residence_data['id'];
            $in_resume['residence_cn'] = $residence_data_p['categoryname'] . '/' . $residence_data['categoryname'];
        } else {
            $in_resume['residence'] = $residence_data['id'] . '.0';
            $in_resume['residence_cn'] = $residence_data['categoryname'] . '/ȫ��';
        }
    }
    $height_rule = '/<td valign="top" class="keys">��ߣ�<\/td>[.\n*]<td valign="top" class="txt2">(.*?)cm<\/td>/';
    preg_match($height_rule, $content, $height_result);
    intval($height_result[1]) > 0 ? $in_resume['height'] = intval($height_result[1]) : "";
    $householdaddress_rule = '/<td valign="top" class="keys">����\/������<\/td>[.\n*]<td valign="top" class="txt2">(.*?)<\/td>/';
    preg_match($householdaddress_rule, $content, $householdaddress_result);
    $householdaddress_data = $db->getone("SELECT * FROM " . table('category_district') . " WHERE categoryname='" . trim($householdaddress_result[1]) . "'");
    if (!empty($householdaddress_data)) {
        $householdaddress_data_p = "";
        if ($householdaddress_data['parentid'] > 0) {
            $householdaddress_data_p = $db->getone("SELECT * FROM " . table('category_district') . " WHERE id='" . $householdaddress_data['parentid'] . "'");
        }
        if (!empty($householdaddress_data_p)) {
            $in_resume['householdaddress'] = $householdaddress_data_p['id'] . '.' . $householdaddress_data['id'];
            $in_resume['householdaddress_cn'] = $householdaddress_data_p['categoryname'] . '/' . $householdaddress_data['categoryname'];
        } else {
            $in_resume['householdaddress'] = $householdaddress_data['id'] . '.0';
            $in_resume['householdaddress_cn'] = $householdaddress_data['categoryname'] . '/ȫ��';
        }
    }
    $marriage_rule = '/<td valign="top" class="keys">����״����<\/td>[.\n*]<td valign="top" class="txt2">(.*?)<\/td>/';
    preg_match($marriage_rule, $content, $marriage_result);
    if ($marriage_result[1] == '�ѻ�') {
        $in_resume['marriage'] = 2;
        $in_resume['marriage_cn'] = '�ѻ�';
    } else {
        $in_resume['marriage'] = 1;
        $in_resume['marriage_cn'] = 'δ��';
    }
    $in_resume['trade'] = '33';
    $in_resume['trade_cn'] = '����/��ѵ';
    $intention_jobs_rule = '/<td valign="top" class="txt2">\n*<span class="tag">(.*)\&nbsp\;<\/span>/';
    preg_match($intention_jobs_rule, $content, $intention_jobs_result1);
    $intention_jobs_rule = '/<span class="tag">(.*?)\&nbsp\;<\/span>/';
    preg_match_all($intention_jobs_rule, $intention_jobs_result1[0], $intention_jobs_result);
    foreach ($intention_jobs_result[1] as $i) {
        switch ($i) {
            case $i == "У��":
                $i_jobs['id'] = "0.4.133";
                $i_jobs['cn'] = 'У��/��У��';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "��ѧ����":
                $i_jobs['id'] = "0.4.139";
                $i_jobs['cn'] = '����';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "��ʦ/����":
                $i_jobs['id'] = "0.4.135";
                $i_jobs['cn'] = '����';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "��ѧ��ʦ":
                $i_jobs['id'] = "0.1.47";
                $i_jobs['cn'] = '��Сѧ���ƽ�ʦ';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "Сѧ��ʦ":
                $i_jobs['id'] = "0.1.47";
                $i_jobs['cn'] = '��Сѧ���ƽ�ʦ';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "�׽�":
                $i_jobs['id'] = "0.2.81";
                $i_jobs['cn'] = '�׶���ʦ';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "������ѵʦ":
                $i_jobs['id'] = "0.3.87";
                $i_jobs['cn'] = '������ѵ';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "ԺУ���������Ա":
                $i_jobs['id'] = "0.7.173";
                $i_jobs['cn'] = '������������/����/�̸�';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "��ְ��ʦ":
                $i_jobs['id'] = "0.7.178";
                $i_jobs['cn'] = '��ְ��ʦ';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "�ҽ�":
                $i_jobs['id'] = "0.7.176";
                $i_jobs['cn'] = '��ͥ��ʦ';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "����/������ʦ":
                $i_jobs['id'] = "0.3.86";
                $i_jobs['cn'] = '������ѵ';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "������ʦ":
                $i_jobs['id'] = "0.7.174";
                $i_jobs['cn'] = '��������/��������';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "ְҵ������ʦ":
                $i_jobs['id'] = "0.3.93";
                $i_jobs['cn'] = 'ְҵ����/��ѵ��ʦ';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "����":
                $i_jobs['id'] = "0.7.180";
                $i_jobs['cn'] = '����ְλ';
                $intention_jobs[] = $i_jobs;
                break;
            default:
                $i_jobs['id'] = "0.7.180";
                $i_jobs['cn'] = '����ְλ';
                $intention_jobs[] = $i_jobs;
                break;
        }
    }
    $in_resume['intention_jobs'] = "";
    foreach ($intention_jobs as $ij) {
        $id_arr = explode(".", $ij['id']);
        $p_ij = $db->getone("SELECT * FROM " . table('category_jobs') . " WHERE id='" . $id_arr[1] . "'");
        $in_resume['intention_jobs'].=$p_ij['categoryname'] . "-" . $ij['cn'] . ",";
    }
    $in_resume['intention_jobs'] = trim($in_resume['intention_jobs'], ",");

    $district_rule = '/<td valign="top" class="keys">�ص㣺<\/td>[.\n*]<td valign="top" class="txt2"><span class="tag">(.*?)<\/span><\/td>/';
    preg_match($district_rule, $content, $district_result);
    $district_data = $db->getone("SELECT * FROM " . table('category_district') . " WHERE categoryname='" . trim($district_result[1]) . "'");
    if ($district_data['parentid'] > 0) {
        $p_district_data = $db->getone("SELECT * FROM " . table('category_district') . " WHERE id='" . trim($district_data['parentid']) . "'");
        $in_resume['district'] = $p_district_data['id'];
        $in_resume['sdistrict'] = $district_data['id'];
        $in_resume['district_cn'] = $p_district_data['categoryname'] . "/" . $district_data['categoryname'];
    } else {
        $in_resume['district'] = $district_data['id'];
        $in_resume['sdistrict'] = 0;
        $in_resume['district_cn'] = $district_data['categoryname'];
    }

    $nature_rule = '/<td valign="top" class="keys">�������ͣ�<\/td>[.\n*]<td valign="top" class="txt2">(.*?)<\/td>/';
    preg_match($nature_rule, $content, $nature_result);
    switch ($nature_result[1]) {
        case $i == "ȫְ":
            $in_resume['nature'] = 62;
            $in_resume['nature_cn'] = 'ȫְ';
            break;
        case $i == "��ְ":
            $in_resume['nature'] = 63;
            $in_resume['nature_cn'] = '��ְ';
            break;
        case $i == "ʵϰ":
            $in_resume['nature'] = 64;
            $in_resume['nature_cn'] = 'ʵϰ';
            break;
        default:
            $in_resume['nature'] = 62;
            $in_resume['nature_cn'] = 'ȫְ';
            break;
    }

    $nature_rule = '/<td valign="top" class="keys">�������ͣ�<\/td>[.\n*]<td valign="top" class="txt2">(.*?)<\/td>/';
    preg_match($nature_rule, $content, $nature_result);
    switch ($nature_result[1]) {
        case $i == "ȫְ":
            $in_resume['nature'] = 62;
            $in_resume['nature_cn'] = 'ȫְ';
            break;
        case $i == "��ְ":
            $in_resume['nature'] = 63;
            $in_resume['nature_cn'] = '��ְ';
            break;
        case $i == "ʵϰ":
            $in_resume['nature'] = 64;
            $in_resume['nature_cn'] = 'ʵϰ';
            break;
        default:
            $in_resume['nature'] = 62;
            $in_resume['nature_cn'] = 'ȫְ';
            break;
    }

    var_dump($in_resume);
    exit;
} elseif ($act == 'refresh') {
    $resumeid = intval($_GET['id']) ? intval($_GET['id']) : showmsg("��û��ѡ�������");
    $refrestime = get_last_refresh_date($_SESSION['uid'], "2001");
    $duringtime = time() - $refrestime['max(addtime)'];
    $space = $_CFG['per_refresh_resume_space'] * 60;
    $refresh_time = get_today_refresh_times($_SESSION['uid'], "2001");
    if ($_CFG['per_refresh_resume_time'] != 0 && ($refresh_time['count(*)'] >= $_CFG['per_refresh_resume_time'])) {
        showmsg("ÿ�����ֻ��ˢ��" . $_CFG['per_refresh_resume_time'] . "��,�������ѳ������ˢ�´������ƣ�", 2);
    } elseif ($duringtime <= $space) {
        showmsg($_CFG['per_refresh_resume_space'] . "�����ڲ����ظ�ˢ�¼�����", 2);
    } else {
        refresh_resume($resumeid, $_SESSION['uid']) ? showmsg('�����ɹ���', 2) : showmsg('����ʧ�ܣ�', 0);
    }
}
//ɾ������
elseif ($act == 'del_resume') {
    if (intval($_GET['id']) == 0) {
        exit('��û��ѡ�������');
    } else {
        del_resume($_SESSION['uid'], intval($_GET['id'])) ? exit('success') : exit('fail');
    }
}
//��������-������Ϣ
elseif ($act == 'make1') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    $total = $db->get_total("SELECT COUNT(*) AS num FROM " . table('resume') . " WHERE uid='{$uid}'");
    if ($total >= intval($_CFG['resume_max'])) {
        showmsg("�������Դ���{$_CFG['resume_max']} �ݼ���,�Ѿ�������������ƣ�", 1);
        exit;
    }
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $smarty->assign('send_key', $_SESSION['send_mobile_key']);
    $smarty->assign('resume_basic', get_resume_basic($uid, $pid));
    $smarty->assign('resume_education', get_resume_education($uid, $pid));
    $smarty->assign('resume_work', get_resume_work($uid, $pid));
    $smarty->assign('resume_training', get_resume_training($uid, $pid));
    $smarty->assign('subsite', get_subsite_list());
    $smarty->assign('act', $act);
    $smarty->assign('pid', $pid);
    $smarty->assign('user', $user);
    $smarty->assign('userprofile', get_userprofile($_SESSION['uid']));
    $smarty->assign('title', '�ҵļ��� - ���˻�Ա���� - ' . $_CFG['site_name']);
    $captcha = get_cache('captcha');
    $smarty->assign('verify_resume', $captcha['verify_resume']);
    $smarty->assign('go_resume_show', $_GET['go_resume_show']);
    $smarty->display('member_personal/personal_make_resume_step1.htm');
}
//�������� -���������Ϣ����ְ����
elseif ($act == 'make1_save') {
    $captcha = get_cache('captcha');
    $postcaptcha = trim($_POST['postcaptcha']);
    if ($captcha['verify_resume'] == '1' && empty($postcaptcha) && intval($_REQUEST['pid']) === 0) {
        showmsg("����дϵͳ��֤��", 1);
    }
    if ($captcha['verify_resume'] == '1' && intval($_REQUEST['pid']) === 0 && strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        showmsg("ϵͳ��֤�����", 1);
    }
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['telephone'] = trim($_POST['mobile']) ? trim($_POST['mobile']) : showmsg('����д�ֻ��ţ�', 1);

    if ($user['mobile_audit'] == 0) {
        $mobile_audit = 0;
        $verifycode = trim($_POST['verifycode']);
        if ($verifycode) {
            if (empty($_SESSION['mobile_rand']) || $verifycode <> $_SESSION['mobile_rand']) {
                showmsg("�ֻ���֤�����", 1);
            } else {
                $verifysqlarr['mobile'] = $setsqlarr['telephone'];
                $verifysqlarr['mobile_audit'] = 1;
                $mobile_audit = 1;
                updatetable(table('members'), $verifysqlarr, " uid='{$setsqlarr['uid']}'");
                unset($verifysqlarr);
            }
        }
        unset($_SESSION['verify_mobile'], $_SESSION['mobile_rand']);
    } else {
        $mobile_audit = 1;
    }
    $setsqlarr['subsite_id'] = intval($_POST['subsite_id']);
    $setsqlarr['title'] = trim($_POST['title']) ? trim($_POST['title']) : "δ��������";
    $setsqlarr['fullname'] = trim($_POST['fullname']) ? trim($_POST['fullname']) : showmsg('����д������', 1);
    $setsqlarr['display_name'] = intval($_POST['display_name']);
    $setsqlarr['sex'] = trim($_POST['sex']) ? intval($_POST['sex']) : showmsg('��ѡ���Ա�', 1);
    $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
    $setsqlarr['birthdate'] = intval($_POST['birthdate']) > 1945 ? intval($_POST['birthdate']) : showmsg('����ȷ��д�������', 1);
    $setsqlarr['residence'] = trim($_POST['residence']) ? trim($_POST['residence']) : "";
    $setsqlarr['residence_cn'] = trim($_POST['residence_cn']);
    $setsqlarr['education'] = intval($_POST['education']) ? intval($_POST['education']) : showmsg('��ѡ��ѧ��', 1);
    $setsqlarr['education_cn'] = trim($_POST['education_cn']);
    $setsqlarr['experience'] = intval($_POST['experience']) ? intval($_POST['experience']) : showmsg('��ѡ��������', 1);
    $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
    $setsqlarr['email'] = trim($_POST['email']) ? trim($_POST['email']) : showmsg('����д���䣡', 1);
    $setsqlarr['email_notify'] = $_POST['email_notify'] == "1" ? 1 : 0;
    $setsqlarr['height'] = intval($_POST['height']);
    $setsqlarr['householdaddress'] = trim($_POST['householdaddress']);
    $setsqlarr['householdaddress_cn'] = trim($_POST['householdaddress_cn']);
    $setsqlarr['marriage'] = intval($_POST['marriage']);
    $setsqlarr['marriage_cn'] = trim($_POST['marriage_cn']);
    $setsqlarr['intention_jobs'] = trim($_POST['intention_jobs']) ? trim($_POST['intention_jobs']) : showmsg('��ѡ������ְλ��', 1);
    $setsqlarr['trade'] = $_POST['trade'] ? trim($_POST['trade']) : showmsg('��ѡ��������ҵ��', 1);
    $setsqlarr['trade_cn'] = trim($_POST['trade_cn']);
    $setsqlarr['district'] = trim($_POST['district']) ? intval($_POST['district']) : showmsg('��ѡ����������������', 1);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);
    $setsqlarr['nature'] = intval($_POST['nature']) ? intval($_POST['nature']) : "";
    $setsqlarr['nature_cn'] = trim($_POST['nature_cn']);
    $setsqlarr['wage'] = intval($_POST['wage']) ? intval($_POST['wage']) : "";
    $setsqlarr['wage_cn'] = trim($_POST['wage_cn']);
    $setsqlarr['entrust'] = intval($_POST['entrust']);
    $setsqlarr['refreshtime'] = $timestamp;
    $setsqlarr['audit'] = intval($_CFG['audit_resume']);
    $total = $db->get_total("SELECT COUNT(*) AS num FROM " . table('resume') . " WHERE uid='{$_SESSION['uid']}'");
    if ($total >= intval($_CFG['resume_max'])) {
        showmsg("�������Դ���{$_CFG['resume_max']} �ݼ���,�Ѿ�������������ƣ�", 1);
    } else {
        $setsqlarr['addtime'] = $timestamp;
        if (!empty($setsqlarr['fullname'])) {
            $pid = inserttable(table('resume'), $setsqlarr, 1);
        }
        if (empty($pid)) {
            showmsg("����ʧ�ܣ�", 0);
        }
        $searchtab['id'] = $pid;
        $searchtab['uid'] = $_SESSION['uid'];
        inserttable(table('resume_search_key'), $searchtab);
        inserttable(table('resume_search_rtime'), $searchtab);
        add_resume_jobs($pid, $_SESSION['uid'], $_POST['intention_jobs_id'], $setsqlarr['district'], $setsqlarr['sdistrict']) ? "" : showmsg('����ʧ�ܣ�', 0);
        add_resume_trade($pid, $_SESSION['uid'], $_POST['trade']) ? "" : showmsg('����ʧ�ܣ�', 0);
        check_resume($_SESSION['uid'], $pid);
        if (intval($_POST['entrust'])) {
            set_resume_entrust($pid);
        }
        write_memberslog($_SESSION['uid'], 2, 1101, $_SESSION['username'], "�����˼���");

        if (!get_userprofile($_SESSION['uid'])) {
            $infoarr['realname'] = $setsqlarr['fullname'];
            $infoarr['sex'] = $setsqlarr['sex'];
            $infoarr['sex_cn'] = $setsqlarr['sex_cn'];
            $infoarr['birthday'] = $setsqlarr['birthdate'];
            $infoarr['residence'] = $setsqlarr['residence'];
            $infoarr['residence_cn'] = $setsqlarr['residence_cn'];
            $infoarr['education'] = $setsqlarr['education'];
            $infoarr['education_cn'] = $setsqlarr['education_cn'];
            $infoarr['experience'] = $setsqlarr['experience'];
            $infoarr['experience_cn'] = $setsqlarr['experience_cn'];
            $infoarr['height'] = $setsqlarr['height'];
            $infoarr['householdaddress'] = $setsqlarr['householdaddress'];
            $infoarr['householdaddress_cn'] = $setsqlarr['householdaddress_cn'];
            $infoarr['marriage'] = $setsqlarr['marriage'];
            $infoarr['marriage_cn'] = $setsqlarr['marriage_cn'];
            $infoarr['phone'] = $setsqlarr['telephone'];
            $infoarr['email'] = $setsqlarr['email'];
            $infoarr['uid'] = intval($_SESSION['uid']);
            inserttable(table('members_info'), $infoarr);

            $sql = "select * from " . table('jiaoshi_statistics_all') . " where name = 'resume' LIMIT 1";
            $resume = $db->getone($sql);
            $setsqlarr1['total_count'] = intval($resume['total_count']) + 1;
            $setsqlarr1['new_count'] = intval($resume['new_count']) + 1;
            $wheresql1 = " name='resume' ";
            updatetable(table('jiaoshi_statistics_all'), $setsqlarr1, $wheresql1);

            $date = date('Y-m-d', time());
            $sql = "select * from " . table('jiaoshi_statistics_day') . " where name = 'resume' and date = '" . $date . "' LIMIT 1";
            $resume = $db->getone($sql);
            if (!empty($resume)) {
                $setsqlarr2['web_count'] = intval($resume['web_count']) + 1;
                $wheresql2 = " name='resume' and date='" . $date . "' ";
                updatetable(table('jiaoshi_statistics_day'), $setsqlarr2, $wheresql2);
            } else {
                $setsqlarr2['web_count'] = 1;
                $setsqlarr2['name'] = 'resume';
                $setsqlarr2['date'] = $date;
                $setsqlarr2['count'] = 0;
                inserttable(table('jiaoshi_statistics_day'), $setsqlarr2);
            }
        }
        header("Location: ?act=make1_succeed&pid=" . $pid);
    }
} elseif ($act == 'make1_succeed') {
    $pid = intval($_GET['pid']);
    $smarty->assign('pid', $pid);
    $smarty->assign('resume_basic', get_resume_basic($_SESSION['uid'], $pid));
    $smarty->assign('title', '�ҵļ��� - ���˻�Ա���� - ' . $_CFG['site_name']);
    $smarty->display('member_personal/personal_make_resume_step1_succeed.htm');
} elseif ($act == 'ajax_get_interest_jobs') {
    global $_CFG;
    $uid = intval($_SESSION['uid']);
    $pid = intval($_GET['pid']);
    $html = "";
    $interest_id = get_interest_jobs_id_by_resume($uid, $pid);
    $jobs_list = get_interest_jobs_list($interest_id);
    if (!empty($jobs_list)) {
        foreach ($jobs_list as $k => $v) {
            $jobs_url = url_rewrite("QS_jobsshow", array("id" => $v['id']));
            $company_url = url_rewrite("QS_companyshow", array("id" => $v['company_id']));
            $html.='<div class="l1 link_bk"><a href="' . $jobs_url . '" target="_blank">' . $v["jobs_name"] . '</a></div>
			  <div class="l2 link_bk"><a href="' . $company_url . '" target="_blank">' . $v["companyname"] . '</a></div>
			  <div class="l3">' . $v["district_cn"] . '</div>
			  <div class="l4">' . $v["wage_cn"] . '</div>';
            $html.='<div class="clear"></div>';
        }
        $html.='<div class="more link_lan"><a target="_blank" href="' . url_rewrite("QS_jobslist") . '">����ְλ>></a></div>';
    }
    exit($html);
} elseif ($act == 'ajax_save_basic_info') {
    $telephone = trim($_POST['mobile']) ? trim($_POST['mobile']) : exit('����д�ֻ��ţ�');
    $resume_basic = get_resume_basic($_SESSION['uid'], $_REQUEST['pid']);
    $setsqlarr['telephone'] = $telephone;
    if ($user['mobile_audit'] != "1") {
        $members['mobile'] = $telephone;
        $members_info['phone'] = $telephone;
        updatetable(table("members"), $members, " uid = " . intval($_SESSION['uid']));
        updatetable(table("members_info"), $members_info, " uid = " . intval($_SESSION['uid']));
        unset($members['mobile'], $members_info['phone']);
    }
    $setsqlarr['title'] = utf8_to_gbk(trim($_POST['title']));
    $setsqlarr['fullname'] = trim($_POST['fullname']) ? utf8_to_gbk(trim($_POST['fullname'])) : exit('����д������');
    check_word($_CFG['filter'], $setsqlarr['fullname']) ? exit($_CFG['filter_tips']) : '';
    $setsqlarr['display_name'] = intval($_POST['display_name']);
    $setsqlarr['sex'] = trim($_POST['sex']) ? intval($_POST['sex']) : exit('��ѡ���Ա�');
    $setsqlarr['sex_cn'] = utf8_to_gbk(trim($_POST['sex_cn']));
    $setsqlarr['birthdate'] = intval($_POST['birthdate']) > 1945 ? intval($_POST['birthdate']) : exit('����ȷ��д�������');
    $setsqlarr['residence'] = trim($_POST['residence']) ? utf8_to_gbk(trim($_POST['residence'])) : exit('����д�־�ס�أ�');
    $setsqlarr['education'] = intval($_POST['education']) ? intval($_POST['education']) : exit('��ѡ��ѧ��');
    $setsqlarr['education_cn'] = utf8_to_gbk(trim($_POST['education_cn']));
    $setsqlarr['experience'] = $_POST['experience'] ? $_POST['experience'] : exit('��ѡ��������');
    $setsqlarr['experience_cn'] = utf8_to_gbk(trim($_POST['experience_cn']));
    $setsqlarr['email'] = trim($_POST['email']) ? utf8_to_gbk(trim($_POST['email'])) : exit('����д���䣡');
    if ($user['email_audit'] != "1") {
        $members['email'] = $setsqlarr['email'];
        $members_info['email'] = $setsqlarr['email'];
        updatetable(table("members"), $members, " uid = " . intval($_SESSION['uid']));
        updatetable(table("members_info"), $members_info, " uid = " . intval($_SESSION['uid']));
        unset($members['email'], $members_info['email']);
    }
    check_word($_CFG['filter'], $setsqlarr['email']) ? exit($_CFG['filter_tips']) : '';
    $setsqlarr['email_notify'] = $_POST['email_notify'] == "1" ? 1 : 0;
    $setsqlarr['height'] = intval($_POST['height']);
    $setsqlarr['householdaddress'] = utf8_to_gbk(trim($_POST['householdaddress']));
    $setsqlarr['householdaddress_cn'] = utf8_to_gbk(trim($_POST['householdaddress_cn']));
    $setsqlarr['marriage'] = intval($_POST['marriage']);
    $setsqlarr['marriage_cn'] = utf8_to_gbk(trim($_POST['marriage_cn']));
    $setsqlarr['refreshtime'] = $timestamp;
    $_CFG['audit_edit_resume'] != "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_edit_resume']) : "";

    updatetable(table('resume'), $setsqlarr, " id='" . intval($_REQUEST['pid']) . "'  AND uid='{$_SESSION['uid']}'");

    check_resume($_SESSION['uid'], intval($_REQUEST['pid']));

    $title = utf8_to_gbk(trim($_POST['title']));
    write_memberslog($_SESSION['uid'], 2, 1105, $_SESSION['username'], "�޸��˼���({$title})");
    exit("success");
} elseif ($act == 'ajax_save_basic') {
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['telephone'] = trim($_POST['telephone']) ? trim($_POST['telephone']) : exit('����д�ֻ��ţ�');
    $setsqlarr['fullname'] = trim($_POST['fullname']) ? utf8_to_gbk(trim($_POST['fullname'])) : exit('����д������');
    $setsqlarr['title'] = $setsqlarr['fullname'] . "�ļ���";
    $sex = trim($_POST['sex']) ? explode('-', utf8_to_gbk($_POST['sex'])) : exit('��ѡ���Ա�');
    $setsqlarr['sex'] = $sex[0];
    $setsqlarr['sex_cn'] = $sex[1];
    $setsqlarr['birthdate'] = intval($_POST['birthdate']) > 1945 ? intval($_POST['birthdate']) : exit('����ȷ��д�������');
    $residence_district = trim($_POST['residence_district']) ? explode('-', utf8_to_gbk($_POST['residence_district'])) : "";
    $residence_sdistrict = trim($_POST['residence_sdistrict']) ? explode('-', utf8_to_gbk($_POST['residence_sdistrict'])) : "";
    $setsqlarr['residence'] = !empty($residence_district) ? $residence_district[0] . "." . $residence_sdistrict[0] : "";
    $setsqlarr['residence_cn'] = !empty($residence_district) ? $residence_district[1] . "/" . $residence_sdistrict[1] : "";
    $education = trim($_POST['education']) ? explode('-', utf8_to_gbk($_POST['education'])) : exit('��ѡ��ѧ��');
    $setsqlarr['education'] = $education[0];
    $setsqlarr['education_cn'] = $education[1];
    $experience = trim($_POST['experience']) ? explode('|', utf8_to_gbk($_POST['experience'])) : exit('��ѡ��������');
    $setsqlarr['experience'] = $experience[0];
    $setsqlarr['experience_cn'] = $experience[1];
    $setsqlarr['email'] = trim($_POST['email']) ? utf8_to_gbk(trim($_POST['email'])) : exit('����д���䣡');
    $setsqlarr['email_notify'] = 0;

    $marriage = trim($_POST['marriage']) ? explode('-', utf8_to_gbk($_POST['marriage'])) : "";
    $setsqlarr['marriage'] = !empty($marriage) ? $marriage[0] : "";
    $setsqlarr['marriage_cn'] = !empty($marriage) ? $marriage[1] : "";
    $setsqlarr['trade'] = $_POST['trade'] ? trim($_POST['trade']) : 33;
    $setsqlarr['trade_cn'] = "����/��ѵ";
    $setsqlarr['entrust'] = $_POST['entrust'] ? trim($_POST['entrust']) : 0;
    $setsqlarr['display'] = $_POST['display'] ? trim($_POST['display']) : 2;
    $setsqlarr['refreshtime'] = time();
    $setsqlarr['tag'] = trim(utf8_to_gbk($_POST['tag']), "|");
    $setsqlarr['specialty'] = trim(utf8_to_gbk($_POST['specialty']));
    if ($_CFG['audit_edit_resume'] > 0) {
        $setsqlarr['audit'] = intval($_CFG['audit_edit_resume']);
    }
    $o_resume = $db->getone("select complete_percent from " . table('resume') . " WHERE uid='" . intval($_SESSION['uid']) . "' LIMIT 1 ");
    if (!empty($o_resume)) {
        updatetable(table('resume'), $setsqlarr, " id='" . intval($_REQUEST['pid']) . "'  AND uid='{$setsqlarr['uid']}'");
    } else {
        $setsqlarr['addtime'] = $timestamp;
        if (!empty($setsqlarr['fullname'])) {
            $pid = inserttable(table('resume'), $setsqlarr, 1);
        }
        if (empty($pid)) {
            showmsg("����ʧ�ܣ�", 0);
        }
        $searchtab['id'] = $pid;
        $searchtab['uid'] = $_SESSION['uid'];
        inserttable(table('resume_search_key'), $searchtab);
        inserttable(table('resume_search_rtime'), $searchtab);
    }
    //add_resume_jobs(intval($_REQUEST['pid']), $_SESSION['uid'], $intention_jobs_id, $setsqlarr['district'], $setsqlarr['sdistrict']) ? "" : showmsg('����ʧ�ܣ�', 0);
    add_resume_trade(intval($_REQUEST['pid']), $_SESSION['uid'], $_POST['trade']) ? "" : showmsg('����ʧ�ܣ�', 0);
    check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
    if ($_CFG['audit_edit_resume'] != "-1" && $_POST['entrust'] == 1) {
        set_resume_entrust(intval($_REQUEST['pid']));
    }
    write_memberslog($_SESSION['uid'], 2, 1105, $_SESSION['username'], "�޸��˼���({$setsqlarr['title']})");

    if (!get_userprofile($_SESSION['uid'])) {
        $infoarr['realname'] = $setsqlarr['fullname'];
        $infoarr['sex'] = $setsqlarr['sex'];
        $infoarr['sex_cn'] = $setsqlarr['sex_cn'];
        $infoarr['birthday'] = $setsqlarr['birthdate'];
        $infoarr['residence'] = $setsqlarr['residence'];
        $infoarr['residence_cn'] = $setsqlarr['residence_cn'];
        $infoarr['education'] = $setsqlarr['education'];
        $infoarr['education_cn'] = $setsqlarr['education_cn'];
        $infoarr['experience'] = $setsqlarr['experience'];
        $infoarr['experience_cn'] = $setsqlarr['experience_cn'];
        //$infoarr['height'] = $setsqlarr['height'];
        //$infoarr['householdaddress'] = $setsqlarr['householdaddress'];
        //$infoarr['householdaddress_cn'] = $setsqlarr['householdaddress_cn'];
        $infoarr['marriage'] = $setsqlarr['marriage'];
        $infoarr['marriage_cn'] = $setsqlarr['marriage_cn'];
        $infoarr['phone'] = $setsqlarr['telephone'];
        $infoarr['email'] = $setsqlarr['email'];
        $infoarr['uid'] = intval($_SESSION['uid']);
        if (get_userprofile($_SESSION['uid'])) {
            $wheresql = " uid='" . intval($_SESSION['uid']) . "'";
            write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "�޸��˸�������");
            updatetable(table('members_info'), $infoarr, $wheresql);
        } else {
            $infoarr['uid'] = intval($_SESSION['uid']);
            write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "�޸��˸�������");
            inserttable(table('members_info'), $infoarr);
        }

        $sql = "select * from " . table('jiaoshi_statistics_all') . " where name = 'resume' LIMIT 1";
        $resume = $db->getone($sql);
        $setsqlarr1['total_count'] = intval($resume['total_count']) + 1;
        $setsqlarr1['new_count'] = intval($resume['new_count']) + 1;
        $wheresql1 = " name='resume' ";
        updatetable(table('jiaoshi_statistics_all'), $setsqlarr1, $wheresql1);

        $date = date('Y-m-d', time());
        $sql = "select * from " . table('jiaoshi_statistics_day') . " where name = 'resume' and date = '" . $date . "' LIMIT 1";
        $resume = $db->getone($sql);
        if (!empty($resume)) {
            $setsqlarr2['web_count'] = intval($resume['web_count']) + 1;
            $wheresql2 = " name='resume' and date='" . $date . "' ";
            updatetable(table('jiaoshi_statistics_day'), $setsqlarr2, $wheresql2);
        } else {
            $setsqlarr2['web_count'] = 1;
            $setsqlarr2['name'] = 'resume';
            $setsqlarr2['date'] = $date;
            $setsqlarr2['count'] = 0;
            inserttable(table('jiaoshi_statistics_day'), $setsqlarr2);
        }
    }
    /*
      if ($go_verify) {
      $wheresql = " WHERE uid=" . intval($_SESSION['uid']);
      $infoarr['phone'] = $setsqlarr['telephone'];
      updatetable(table('members_info'), $infoarr, $wheresql);
      unset($infoarr);
      $infoarr['mobile'] = $setsqlarr['telephone'];
      $infoarr['mobile_audit'] = 1;
      updatetable(table('members'), $infoarr, $wheresql);
      }
     * 
     */
    exit("success");
} elseif ($act == 'ajax_save_intention') {
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $nature = trim($_POST['nature']) ? explode('-', utf8_to_gbk($_POST['nature'])) : "";
    $setsqlarr['nature'] = $nature[0];
    $setsqlarr['nature_cn'] = $nature[1];

    $district = trim($_POST['district']) ? explode('-', utf8_to_gbk($_POST['district'])) : exit('��ѡ����������������');
    $setsqlarr['district'] = $district[0];
    $sdistrict = trim($_POST['sdistrict']) ? explode('-', utf8_to_gbk($_POST['sdistrict'])) : "0-ȫ��";
    $setsqlarr['sdistrict'] = $sdistrict[0];
    $setsqlarr['district_cn'] = $district[1] . "/" . $sdistrict[1];
    $setsqlarr['intention_jobs'] = !empty($_POST['intention_jobs']) ? utf8_to_gbk(trim($_POST['intention_jobs'])) : exit('��ѡ������ְλ��');
    $intention_jobs_id = !empty($_POST['intention_jobs_id']) ? trim($_POST['intention_jobs_id'], ",") : exit('������ѡ������ְλ��');

    $wage = trim($_POST['wage']) ? explode('-', utf8_to_gbk($_POST['wage'])) : "";
    $setsqlarr['wage'] = !empty($wage) ? $wage[0] : "";
    $setsqlarr['wage_cn'] = !empty($wage) ? $wage[1] : "";

    $setsqlarr['refreshtime'] = time();
    $_CFG['audit_edit_resume'] != "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_edit_resume']) : "";
    updatetable(table('resume'), $setsqlarr, " id='" . intval($_REQUEST['pid']) . "'  AND uid='{$setsqlarr['uid']}'");
    add_resume_jobs(intval($_REQUEST['pid']), $_SESSION['uid'], $intention_jobs_id, $setsqlarr['district'], $setsqlarr['sdistrict']) ? "" : showmsg('����ʧ�ܣ�', 0);
    check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
    write_memberslog($_SESSION['uid'], 2, 1105, $_SESSION['username'], "�޸��˼���");
    exit("success");
}
//��������-�ڶ���
elseif ($act == 'make2') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    $link[0]['text'] = "���ؼ����б�";
    $link[0]['href'] = '?act=resume_list';
    if ($uid == 0 || $pid == 0)
        showmsg('���������ڣ�', 1, $link);
    $resume_basic = get_resume_basic($uid, $pid);
    $link[0]['text'] = "��д����������Ϣ";
    $link[0]['href'] = '?act=make1';
    if (empty($resume_basic))
        showmsg("������д����������Ϣ��", 1, $link);
    $smarty->assign('resume_basic', $resume_basic);
    $smarty->assign('resume_education', get_resume_education($uid, $pid));
    $smarty->assign('resume_work', get_resume_work($uid, $pid));
    $smarty->assign('resume_training', get_resume_training($uid, $pid));
    $resume_jobs = get_resume_jobs($pid);
    if ($resume_jobs) {
        foreach ($resume_jobs as $rjob) {
            $jobsid[] = $rjob['topclass'] . "." . $rjob['category'] . "." . $rjob['subclass'];
        }
        $resume_jobs_id = implode(",", $jobsid);
    }
    $smarty->assign('resume_jobs_id', $resume_jobs_id);
    $smarty->assign('act', $act);
    $smarty->assign('pid', $pid);
    $smarty->assign('title', '�ҵļ��� - ���˻�Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('go_resume_show', $_GET['go_resume_show']);

    $smarty->assign('subsite', get_subsite_list());
    $smarty->display('member_personal/personal_make_resume_step2.htm');
} elseif ($act == 'make2_photo_ready') {
    !$_FILES['photo']['name'] ? exit('���ϴ�ͼƬ��') : "";
    require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
    if (intval($_REQUEST['pid']) == 0)
        exit('��������');
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    //var_dump($resume_basic);
    if (empty($resume_basic['photo_img'])) {
        $setsqlarr['photo_audit'] = $_CFG['audit_resume_photo'];
    } else {
        $_CFG['audit_edit_photo'] != "-1" ? $setsqlarr['photo_audit'] = intval($_CFG['audit_edit_photo']) : "";
    }
    $photo_dir = substr($_CFG['resume_photo_dir'], strlen($_CFG['website_dir']));
    $photo_dir = "../../" . $photo_dir . date("Y/m/d/");
    $up_res_rlogo = "../../data/photo/rlogo/";
    make_dir($photo_dir);
    make_dir($up_res_rlogo . date("Y/m/d/"));
    $setsqlarr['photo_img'] = _asUpFiles($photo_dir, "photo", $_CFG['resume_photo_max'], 'gif/jpg/bmp/png', true);
    $setsqlarr['photo'] = 1;

    // var_dump($setsqlarr['photo_img']);die;
    makethumb($photo_dir . $setsqlarr['photo_img'], $up_res_rlogo . date("Y/m/d/"), 280, 280);
    $setsqlarr['photo_img'] = date("Y/m/d/") . $setsqlarr['photo_img'];
    !updatetable(table('resume'), $setsqlarr, " id='" . intval($_REQUEST['pid']) . "' AND uid='" . intval($_SESSION['uid']) . "'") ? exit("����ʧ�ܣ�") : '';
    // check_resume($_SESSION['uid'],intval($_REQUEST['pid']));
    exit($setsqlarr['photo_img']);
} elseif ($act == 'make2_photo_save') {
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic)) {
        showmsg("������д����������Ϣ��", 0);
    }
    if ($resume_basic['photo_img']) {
        require_once(QISHI_ROOT_PATH . 'include/imageresize.class.php');
        $imgresize = new ImageResize();
        $up_res_rlogo = "../../data/photo/rlogo/";
        $photo_dir = QISHI_ROOT_PATH . substr($_CFG['resume_photo_dir'], strlen($_CFG['website_dir']));
        $photo_thumb_dir = QISHI_ROOT_PATH . substr($_CFG['resume_photo_dir_thumb'], strlen($_CFG['website_dir']));
        make_dir($photo_thumb_dir . date("Y/m/d/"));
        $imgresize->load($up_res_rlogo . $resume_basic['photo_img']);
        $imgresize->cut(intval($_POST['w']), intval($_POST['h']), intval($_POST['x']), intval($_POST['y']));
        $img_return = $imgresize->save($photo_thumb_dir . $resume_basic['photo_img']);
        if (!$img_return) {
            $setsqlarr['photo'] = 0;
            $setsqlarr['photo_img'] = "";
            !updatetable(table('resume'), $setsqlarr, " id='" . intval($_REQUEST['pid']) . "' AND uid='" . intval($_SESSION['uid']) . "'") ? exit("����ʧ�ܣ�") : '';
            check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
            showmsg("��ͼƬ�ĸ�ʽ����������ѡ��", 3);
        } else {
            check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
            showmsg("����ɹ���", 2);
        }
    } else {
        showmsg("���ϴ�ͼƬ��", 1);
    }
} elseif ($act == 'make2_certificate_save') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    if (!empty($_REQUEST['img_path'])) {
        $setsqlarr['path'] = $_REQUEST['img_path'];
    } else {
        !$_FILES['certificate']['name'] ? exit('���ϴ�ͼƬ��') : "";
        if ($uid < 1) {
            exit("-7");
        }
        $resume_basic = get_resume_basic($uid);
        if (empty($resume_basic)) {
            exit("-8");
        }
        if (intval($_REQUEST['pid']) == 0) {
            if (intval($resume_basic['pid']) > 0) {
                $_REQUEST['pid'] = intval($resume_basic['pid']);
            } else {
                exit('-7');
            }
        }
        $resume_certificate = get_resume_certificate($uid, $pid);
        if (count($resume_certificate) > 4) {
            exit("-9");
        }
        require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
        $photo_dir = "../../data/resume_certificate/" . date("Y/m/d/");
        make_dir($photo_dir);
        $setsqlarr['path'] = _asUpFiles($photo_dir, "certificate", $_CFG['resume_photo_max'], 'gif/jpg/bmp/png', true);
        $setsqlarr['path'] = date("Y/m/d/") . $setsqlarr['path'];
    }
    if ($_REQUEST['ajax_path']) {
        exit($setsqlarr['path']);
    }
    if (!empty($setsqlarr['path'])) {
        $certificate_name = utf8_to_gbk(trim($_REQUEST['certificate_name']));
        $certificate_name = !empty($certificate_name) ? $certificate_name : exit('-11');
        $setsqlarr['uid'] = $uid;
        $setsqlarr['pid'] = $pid;
        $setsqlarr['note'] = $certificate_name;
        $setsqlarr['addtime'] = time();
        inserttable(table("resume_certificate"), $setsqlarr);
        $resume_certificate = get_resume_certificate($uid, $pid);
        foreach ($resume_certificate as $r) {
            $return[] = $r['id'] . '-+-' . $r['path'] . '-+-' . $r['note'];
        }
        $return = implode("##", $return);
        exit($return);
    } else {
        exit("-10");
    }
}
//ɾ��֤��
elseif ($act == 'del_certificate') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "select * from " . table('resume_certificate') . " where id = " . $id . " limit 1";
        $img = $db->getone($sql);
        if (!empty($img['path'])) {
            $img_path = "../../data/resume_certificate/" . $img['path'];
            unlink($img_path);
            $wheresqlarr = " id = " . $id;
            deletetable(table('resume_certificate'), $wheresqlarr);
            check_resume($_SESSION['uid'], intval($_REQUEST['pid'])); //���¼������״̬
            exit('ɾ���ɹ���');
        }
    }
    exit('ɾ��ʧ�ܣ�');
} elseif ($act == "tag_save") {
    if (intval($_POST['pid']) == 0) {
        showmsg('��������', 1);
    }
    if (!empty($_POST['tag'])) {
        $setsqlarr['tag'] = $_POST['tag'];
        $setsqlarr['tag'] = trim($setsqlarr['tag'], "|");
    }
    //var_dump($_POST['tag']);exit;
    !empty($_POST['specialty']) ? $setsqlarr['specialty'] = trim($_POST['specialty']) : "";
    $_CFG['audit_edit_resume'] != "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_edit_resume']) : "";
    updatetable(table('resume'), $setsqlarr, " id='" . intval($_POST['pid']) . "' AND uid='" . intval($_SESSION['uid']) . "'");
    check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
    showmsg("����ɹ���", 2);
    // header('Location: ?act=edit_resume&pid='.$_REQUEST['pid']);
} elseif ($act == "set_entrust") {
    if (intval($_GET['pid']) == 0) {
        showmsg('��������', 1);
    } else {
        set_resume_entrust(intval($_GET['pid']));
        updatetable(table('resume'), array("entrust" => 1), " id='" . intval($_GET['pid']) . "' AND uid='" . intval($_SESSION['uid']) . "'");
        showmsg("ί�гɹ�,ϵͳ������������Ϊ���Զ�Ͷ�ݺ��ʵ�ְλ��", 2);
    }
} elseif ($act == "del_entrust") {
    if (intval($_GET['pid']) == 0) {
        showmsg('��������', 1);
    } else {
        $wheresqlarr = " id = " . intval($_GET['pid']);
        deletetable(table('resume_entrust'), $wheresqlarr);
        updatetable(table('resume'), array("entrust" => 0), " id='" . intval($_GET['pid']) . "' AND uid='" . intval($_SESSION['uid']) . "'");
        showmsg("ȡ��ί�гɹ���", 2);
    }
} elseif ($act == 'save_education') {
    $id = intval($_POST['id']);
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['pid'] = intval($_REQUEST['pid']);
    if ($setsqlarr['uid'] == 0 || $setsqlarr['pid'] == 0) {
        exit('���������ڣ�');
    }
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic)) {
        exit("������д����������Ϣ��");
    }
    $resume_education = get_resume_education($_SESSION['uid'], $_REQUEST['pid']);
    if (count($resume_education) >= 6) {
        exit('�����������ܳ���6����');
    }
    $school = utf8_to_gbk(trim($_POST['school']));
    $speciality = utf8_to_gbk(trim($_POST['speciality']));
    $education_cn = utf8_to_gbk(trim($_POST['education_cn']));
    $setsqlarr['school'] = $school ? $school : exit("����дѧУ���ƣ�");
    $setsqlarr['speciality'] = $speciality ? $speciality : exit("����дרҵ���ƣ�");
    $setsqlarr['education'] = intval($_POST['education']) ? intval($_POST['education']) : exit("��ѡ��ѧ����");
    $setsqlarr['education_cn'] = $education_cn ? $education_cn : exit("��ѡ��ѧ����");
    if (trim($_POST['edu_start_year']) == "" || trim($_POST['edu_start_month']) == "" || trim($_POST['edu_end_year']) == "" || trim($_POST['edu_end_month']) == "") {
        exit("��ѡ��Ͷ�ʱ�䣡");
    }
    $setsqlarr['startyear'] = intval($_POST['edu_start_year']);
    $setsqlarr['startmonth'] = intval($_POST['edu_start_month']);
    $setsqlarr['endyear'] = intval($_POST['edu_end_year']);
    $setsqlarr['endmonth'] = intval($_POST['edu_end_month']);
    if ($id) {
        updatetable(table("resume_education"), $setsqlarr, array("id" => $id));
        exit("success");
    } else {
        $insert_id = inserttable(table("resume_education"), $setsqlarr, 1);
        if ($insert_id) {
            check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
            exit("success");
        } else {
            exit("����ʧ��");
        }
    }
} elseif ($act == 'ajax_get_education_list') {
    $pid = intval($_GET['pid']);
    $uid = intval($_SESSION['uid']);
    $education_list = get_resume_education($uid, $pid);
    $html = "";
    if ($education_list) {
        foreach ($education_list as $key => $value) {
            $html.=$pid . "||" . $value["id"] . "||" . $value["startyear"] . "||" . $value["startmonth"] . "||" . $value["endyear"] . "||" . $value["endmonth"] . "||" . $value["school"] . "||" . $value["speciality"] . "||" . $value["education_cn"] . "||" . $value["education"] . "-|-";
            /*
              $html.='<div class="jl1">
              <div class="l1">' . $value["startyear"] . '��' . $value["startmonth"] . '��-' . $value["endyear"] . '��' . $value["endmonth"] . '��</div>
              <div class="l2">' . $value["school"] . '</div>
              <div class="l3">' . $value["speciality"] . '</div>
              <div class="l4">' . $value["education_cn"] . '</div>
              <div class="l5">
              <a class="edit_education" href="javascript:void(0);" url="?act=edit_education&id=' . $value["id"] . '&pid=' . $pid . '"></a>
              <a class="del_education d" href="javascript:void(0);" pid="' . $pid . '" edu_id="' . $value["id"] . '" ></a><div class="clear"></div>
              </div>
              <div class="clear"></div>
              </div>';
             * 
             */
        }
        $html = trim($html, "-|-");
    } else {
        /*
          $js = '<script type="text/javascript">$("#add_education").hide();</script>';
          $html.='<div class="noinfo" id="education_empty_box">
          <div class="txt">��������������������ѧ����רҵ���������������������ҵ��HR�����ɣ�</div>
          <div class="addbut">
          <input type="button" name="" id="empty_add_education" value="��Ӿ���"  class="but130lan_add"/>
          </div>
          </div>';
          $html.=$js;
         * $html
         */
    }
    exit($html);
}
//��������-�޸Ľ�������
elseif ($act == 'edit_education') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    if ($uid == 0 || $pid == 0)
        exit('���������ڣ�');
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic))
        exit("������д����������Ϣ��");
    $id = intval($_GET['id']) ? intval($_GET['id']) : exit('��������');
    $education_edit = get_resume_education_one($_SESSION['uid'], $pid, $id);
    foreach ($education_edit as $key => $value) {
        $education_edit[$key] = gbk_to_utf8($value);
    }
    $json_encode = json_encode($education_edit);
    exit($json_encode);
}
//��������-ɾ����������
elseif ($act == 'del_education') {
    $id = intval($_GET['id']);
    $sql = "Delete from " . table('resume_education') . " WHERE id='{$id}'  AND uid='" . intval($_SESSION['uid']) . "' AND pid='" . intval($_REQUEST['pid']) . "' LIMIT 1 ";
    if ($db->query($sql)) {
        check_resume($_SESSION['uid'], intval($_REQUEST['pid'])); //���¼������״̬
        exit('ɾ���ɹ���');
    } else {
        exit('ɾ��ʧ�ܣ�');
    }
} elseif ($act == 'save_work') {
    $id = intval($_POST['id']);
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['pid'] = intval($_REQUEST['pid']);
    if ($setsqlarr['uid'] == 0 || $setsqlarr['pid'] == 0) {
        exit('���������ڣ�');
    }
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic)) {
        exit("������д����������Ϣ��");
    }
    $companyname = utf8_to_gbk(trim($_POST['companyname']));
    $jobs = utf8_to_gbk(trim($_POST['jobs']));
    $achievements = utf8_to_gbk(trim($_POST['achievements']));
    $setsqlarr['companyname'] = $companyname ? $companyname : exit("����д��˾���ƣ�");
    $setsqlarr['jobs'] = $jobs ? $jobs : exit("����дְλ���ƣ�");
    if (trim($_POST['work_start_year']) == "" || trim($_POST['work_start_month']) == "" || trim($_POST['work_end_year']) == "" || trim($_POST['work_end_month']) == "") {
        exit("��ѡ����ְʱ�䣡");
    }
    $setsqlarr['startyear'] = intval($_POST['work_start_year']);
    $setsqlarr['startmonth'] = intval($_POST['work_start_month']);
    $setsqlarr['endyear'] = intval($_POST['work_end_year']);
    $setsqlarr['endmonth'] = intval($_POST['work_end_month']);
    $setsqlarr['achievements'] = $achievements ? $achievements : exit("����д����ְ��");

    if ($id) {
        updatetable(table("resume_work"), $setsqlarr, array("id" => $id));
        exit("success");
    } else {
        $insert_id = inserttable(table("resume_work"), $setsqlarr, 1);
        if ($insert_id) {
            check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
            exit("success");
        } else {
            exit("����ʧ��");
        }
    }
} elseif ($act == 'ajax_get_work_list') {
    $pid = intval($_GET['pid']);
    $uid = intval($_SESSION['uid']);
    $work_list = get_resume_work($uid, $pid);
    $html = "";
    if ($work_list) {
        foreach ($work_list as $key => $value) {
            $html.=$pid . "||" . $value["id"] . "||" . $value["startyear"] . "||" . $value["startmonth"] . "||" . $value["endyear"] . "||" . $value["endmonth"] . "||" . $value["companyname"] . "||" . $value["jobs"] . "||" . $value["achievements"] . "-|-";
            /*

              $html.='<div class="jl2">
              <div class="l1">' . $value["startyear"] . '��' . $value["startmonth"] . '��-' . $value["endyear"] . '��' . $value["endmonth"] . '��</div>
              <div class="l2">' . $value["companyname"] . '</div>
              <div class="l3">' . $value["jobs"] . '</div>
              <div class="l4">
              <a class="edit_work" href="javascript:void(0);" url="?act=edit_work&id=' . $value["id"] . '&pid=' . $pid . '"></a>
              <a class="del_work d" href="javascript:void(0);" pid="' . $pid . '" work_id="' . $value["id"] . '" ></a><div class="clear"></div>
              <div class="clear"></div>
              </div>
              <div class="l5">����ְ��</div>
              <div class="l6">' . $value["achievements"] . '
              </div>
              <div class="clear"></div>
              </div>';
             * 
             */
        }
        $html = trim($html, "-|-");
    } else {
        /*
          $js = '<script type="text/javascript">$("#add_work").hide();</script>';
          $html.='<div class="noinfo" id="work_empty_box">
          <div class="txt">�������������������ḻ�������ͳ��ڵĹ�������������н�귭���ĳ���ŶHR�����ɣ�</div>
          <div class="addbut">
          <input type="button" name="" id="empty_add_work" value="��Ӿ���"  class="but130lan_add"/>
          </div>
          </div>';
          $html.=$js;
         * 
         */
    }
    exit($html);
}
//��������-�޸Ĺ�������
elseif ($act == 'edit_work') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    if ($uid == 0 || $pid == 0)
        exit('���������ڣ�');
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic))
        exit("������д����������Ϣ��");
    $id = intval($_GET['id']) ? intval($_GET['id']) : exit('��������');
    $work_edit = get_resume_work_one($_SESSION['uid'], $pid, $id);
    foreach ($work_edit as $key => $value) {
        $work_edit[$key] = gbk_to_utf8($value);
    }
    $json_encode = json_encode($work_edit);
    exit($json_encode);
}
//��������-ɾ����������
elseif ($act == 'del_work') {
    $id = intval($_GET['id']);
    $sql = "Delete from " . table('resume_work') . " WHERE id='{$id}'  AND uid='" . intval($_SESSION['uid']) . "' AND pid='" . intval($_REQUEST['pid']) . "' LIMIT 1 ";
    if ($db->query($sql)) {
        check_resume($_SESSION['uid'], intval($_REQUEST['pid'])); //���¼������״̬
        exit('ɾ���ɹ���');
    } else {
        exit('ɾ��ʧ�ܣ�');
    }
} elseif ($act == 'save_training') {
    $id = intval($_POST['id']);
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['pid'] = intval($_REQUEST['pid']);
    if ($setsqlarr['uid'] == 0 || $setsqlarr['pid'] == 0) {
        exit('���������ڣ�');
    }
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic)) {
        exit("������д����������Ϣ��");
    }
    $agency = utf8_to_gbk(trim($_POST['agency']));
    $course = utf8_to_gbk(trim($_POST['course']));
    $description = utf8_to_gbk(trim($_POST['description']));
    $setsqlarr['agency'] = $agency ? $agency : exit("����д��ѵ������");
    $setsqlarr['course'] = $course ? $course : exit("����д��ѵ�γ̣�");
    if (trim($_POST['training_start_year']) == "" || trim($_POST['training_start_month']) == "" || trim($_POST['training_end_year']) == "" || trim($_POST['training_end_month']) == "") {
        exit("��ѡ����ѵʱ�䣡");
    }
    $setsqlarr['startyear'] = intval($_POST['training_start_year']);
    $setsqlarr['startmonth'] = intval($_POST['training_start_month']);
    $setsqlarr['endyear'] = intval($_POST['training_end_year']);
    $setsqlarr['endmonth'] = intval($_POST['training_end_month']);
    $setsqlarr['description'] = $description ? $description : exit("����д��ѵ���ݣ�");

    if ($id) {
        updatetable(table("resume_training"), $setsqlarr, array("id" => $id));
        exit("success");
    } else {
        $insert_id = inserttable(table("resume_training"), $setsqlarr, 1);
        if ($insert_id) {
            check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
            exit("success");
        } else {
            exit("err");
        }
    }
} elseif ($act == 'ajax_get_training_list') {
    $pid = intval($_GET['pid']);
    $uid = intval($_SESSION['uid']);
    $training_list = get_resume_training($uid, $pid);
    $html = "";
    if ($training_list) {
        foreach ($training_list as $key => $value) {
            $html.=$pid . "||" . $value["id"] . "||" . $value["startyear"] . "||" . $value["startmonth"] . "||" . $value["endyear"] . "||" . $value["endmonth"] . "||" . $value["agency"] . "||" . $value["course"] . "||" . $value["description"] . "-|-";
            /*
              $html.='<div class="jl2">
              <div class="l1">' . $value["startyear"] . '��' . $value["startmonth"] . '��-' . $value["endyear"] . '��' . $value["endmonth"] . '��</div>
              <div class="l2">' . $value["agency"] . '</div>
              <div class="l3">' . $value["course"] . '</div>
              <div class="l4">
              <a class="edit_training" href="javascript:void(0);" url="?act=edit_training&id=' . $value["id"] . '&pid=' . $pid . '"></a>
              <a class="del_training d" href="javascript:void(0);" pid="' . $pid . '" training_id="' . $value["id"] . '" ></a><div class="clear"></div>
              </div>
              <div class="l5">��ѵ���ݣ�</div>
              <div class="l6">' . $value["description"] . '</div>
              <div class="clear"></div>
              </div>';
             * 
             */
        }
        $html = trim($html, "-|-");
    } else {
        /*
          $js = '<script type="text/javascript">$("#add_training").hide();</script>';
          $html.='<div class="noinfo" id="training_empty_box">
          <div class="txt">��ѵ�������������Ͻ�����õ����֣�����˵˵����������ѧϰ�����ɣ�</div>
          <div class="addbut">
          <input type="button" name="" id="empty_add_training" value="��Ӿ���"  class="but130lan_add"/>
          </div>
          </div>';
          $html.=$js;
         * description
         */
    }
    exit($html);
}
//��������-�޸���ѵ����
elseif ($act == 'edit_training') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    if ($uid == 0 || $pid == 0)
        exit('���������ڣ�');
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic))
        exit("������д����������Ϣ��");
    $id = intval($_GET['id']) ? intval($_GET['id']) : exit('��������');
    $training_edit = get_resume_training_one($_SESSION['uid'], $pid, $id);
    foreach ($training_edit as $key => $value) {
        $training_edit[$key] = gbk_to_utf8($value);
    }
    $json_encode = json_encode($training_edit);
    exit($json_encode);
}
//��������-ɾ����ѵ����
elseif ($act == 'del_training') {
    $id = intval($_GET['id']);
    $sql = "Delete from " . table('resume_training') . " WHERE id='{$id}'  AND uid='" . intval($_SESSION['uid']) . "' AND pid='" . intval($_REQUEST['pid']) . "' LIMIT 1 ";
    if ($db->query($sql)) {
        check_resume($_SESSION['uid'], intval($_REQUEST['pid'])); //���¼������״̬
        exit('ɾ���ɹ���');
    } else {
        exit('ɾ��ʧ�ܣ�');
    }
} elseif ($act == 'set_resume_tpl') {
    $uid = intval($_SESSION['uid']);
    $resume = get_resume_basic($uid);
    $tpl_list = get_resumetpl();
    $smarty->assign('tpl_list', $tpl_list);
    $smarty->assign('resume', $resume);
    $smarty->display('member_personal/personal_set_tpl.htm');
} elseif ($act == 'resume_tpl_save') {
    $tpl_data['uid'] = intval($_SESSION['uid']);
    $tpl_data['tpl_id'] = $_POST['tpl_id'];
    $tpl_data['resume_id'] = $_POST['resume_id'];
    $tpl_data['addtime'] = time();
    $tpl_data['endtime'] = time() + 86400 * 30;
    $insert_id = inserttable(table("personal_resume_tpl"), $tpl_data, 1);
    $link[0]['text'] = "���ظ�������";
    $link[0]['href'] = '?act=edit_resume';
    showmsg("���óɹ�", 1, $link);
} elseif ($act == 'edit_resume') {
    $uid = intval($_SESSION['uid']);
    $resume = get_resume_basic($uid);
    $pid = intval($resume['id']);
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $smarty->assign('send_key', $_SESSION['send_mobile_key']);
    $resume_basic = get_resume_basic($uid, $pid);
    $resume_basic['age'] = date("Y") - $resume_basic['birthdate'];
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_favorites') . " WHERE personal_uid=" . $uid;
    $total_sql2 = "SELECT COUNT(*) AS num FROM " . table('personal_favorite_articles') . " WHERE personal_uid=" . $uid;
    $favorites_total = $db->get_total($total_sql);
    $favorites_total += $db->get_total($total_sql2);
    $smarty->assign('favorites_total', $favorites_total);
    $smarty->assign('comkeyword', get_com_keyword($uid, $pid));
    $smarty->assign('count_attention_me', count_personal_attention_me($uid));
    $smarty->assign('count_interview', count_interview($uid));
    $smarty->assign('count_apply', count_personal_jobs_apply($uid));
    $tpl_log = get_resume_tpl($uid);
    if (!empty($tpl_log)) {
        $tpl = get_resumetpl_one($tpl_log['tpl_id']);
    }
    $tpl_name = !empty($tpl_log) ? $tpl['tpl_name'] : "Ĭ��";
    $smarty->assign('tpl_name', $tpl_name);
    $resume_basic = get_resume_basic($uid, $pid);
    $residence = explode(".", $resume_basic['residence']);
    $residence_cn = explode("/", $resume_basic['residence_cn']);
    $resume_basic['residence_district'] = $residence[0];
    $resume_basic['residence_district_cn'] = $residence_cn[0];
    $resume_basic['residence_sdistrict'] = $residence[1];
    $resume_basic['residence_sdistrict_cn'] = !empty($residence_cn[1]) && $residence_cn[1] != $residence_cn[0] ? $residence_cn[1] : "ȫ��";
    $resume_basic['residence_sdistrict_list'] = "";
    if ($resume_basic['residence_district'] > 0) {
        $sql = "select * from " . table("category_district") . " where parentid = " . $resume_basic['residence_district'] . " order by category_order desc,id asc";
        $resume_basic['residence_sdistrict_list'] = $db->getall($sql);
    }
    $district = explode(".", $resume_basic['district']);
    $district_cn = explode("/", $resume_basic['district_cn']);
    $resume_basic['district'] = $district[0];
    $resume_basic['district_cn'] = $district_cn[0];
    $resume_basic['sdistrict'] = $district[1];
    $resume_basic['sdistrict_cn'] = !empty($district_cn[1]) && $district_cn[1] != $district_cn[0] ? $district_cn[1] : "ȫ��";
    $resume_basic['sdistrict_list'] = "";
    if ($resume_basic['district'] > 0) {
        $sql = "select * from " . table("category_district") . " where parentid = " . $resume_basic['district'] . " order by category_order desc,id asc";
        $resume_basic['sdistrict_list'] = $db->getall($sql);
        if (!empty($resume_basic['attachment_resume'])) {
            $resume_basic['attachment_resume_name'] = explode("--", $resume_basic['attachment_resume']);
            $resume_basic['attachment_resume_name'] = $resume_basic['attachment_resume_name'][1];
        }
    }
    $smarty->assign('resume_basic', $resume_basic);
    $smarty->assign('resume_tag', set_category("QS_resumetag"));
    $smarty->assign('resume_education', get_resume_education($uid, $pid));
    $smarty->assign('resume_work', get_resume_work($uid, $pid));
    $smarty->assign('resume_certificate', get_resume_certificate($uid, $pid));
    $smarty->assign('resume_training', get_resume_training($uid, $pid));
    $resume_jobs = get_resume_jobs($pid);
    if ($resume_jobs) {
        foreach ($resume_jobs as $rjob) {
            if ($rjob['category'] != 0 && $rjob['subclass'] != 0) {
                $sql = "select * from " . table("category_jobs") . " where id = " . $rjob['subclass'];
                $job_cn = $db->getone($sql);
                $sql = "select * from " . table("category_jobs") . " where id = " . $rjob['category'];
                $pjob_cn = $db->getone($sql);
                $rjob['job_cn'] = $pjob_cn['categoryname'] . "-" . $job_cn['categoryname'];
                $jobsid[] = $rjob['topclass'] . "." . $rjob['category'] . "." . $rjob['subclass'] . "||" . $rjob['job_cn'];
            }
            $d_arr[] = $rjob['district'] . "." . $rjob['sdistrict'];
        }
        $d_arr = array_unique($d_arr);
        $jobsid = array_unique($jobsid);
        foreach ($d_arr as $da) {
            $da_arr = explode(".", $da);
            $districtid[] = $da_arr[0];
            $sdistrictid[] = $da_arr[1];
        }
        $resume_jobs_id = implode(",", $jobsid);
        $district = implode(",", $districtid);
        $sdistrict = implode(",", $sdistrictid);
        $district_cn = ",";
        foreach ($sdistrictid as $k => $v) {
            $did = $v == 0 ? $districtid[$k] : $v;
            $sql = "select * from " . table('category_district') . " where id='{$did}'";
            $district_arr = $db->getone($sql);
            if (!stristr($district_cn, "," . $district_arr['categoryname'] . ",")) {
                $district_cn .= $district_arr['categoryname'] . ",";
            }
        }
        $district_cn = trim($district_cn, ",");
        $smarty->assign('district', $district);
        $smarty->assign('sdistrict', $sdistrict);
        $smarty->assign('district_cn', $district_cn);
        $smarty->assign('resume_jobs_id', $resume_jobs_id);
    }
    $smarty->assign('act', $act);
    $smarty->assign('pid', $pid);
    $smarty->assign('user', $user);
    $smarty->assign('title', '�ҵļ��� - ���˻�Ա���� - ' . $_CFG['site_name']);
    $captcha = get_cache('captcha');
    $smarty->assign('verify_resume', $captcha['verify_resume']);
    $smarty->assign('go_resume_show', $_GET['go_resume_show']);
    $smarty->display('member_personal/personal_resume_edit.htm');
}
// ����������ť 
elseif ($act == "edit_resume_save") {
    $uid = intval($_SESSION['uid']);
    $pid = $_POST['pid'] ? intval($_POST['pid']) : showmsg("����ID��ʧ", 1);
    $resume_basic = get_resume_basic($uid, $pid);
    $make = intval($_POST['make']);
    check_resume($uid, $pid);
    if ($make == 1) {
        header("Location: ?act=make1_succeed&pid=" . $pid);
    } else {
        header("Location: ?act=resume_list");
    }
} elseif ($act == 'save_resume_privacy') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    $setsqlarr['display'] = intval($_POST['display']);
    $setsqlarr['display_name'] = intval($_POST['display_name']);
    $setsqlarr['photo_display'] = intval($_POST['photo_display']);
    $wheresql = " uid='" . $_SESSION['uid'] . "' ";
    !updatetable(table('resume'), $setsqlarr, " uid='{$uid}' AND  id='{$pid}'");
    $setsqlarrdisplay['display'] = intval($_POST['display']);
    !updatetable(table('resume_search_key'), $setsqlarrdisplay, " uid='{$uid}' AND  id='{$pid}'");
    !updatetable(table('resume_search_rtime'), $setsqlarrdisplay, " uid='{$uid}' AND  id='{$pid}'");
    !updatetable(table('resume_search_tag'), $setsqlarrdisplay, " uid='{$uid}' AND  id='{$pid}'");
    write_memberslog($_SESSION['uid'], 2, 1104, $_SESSION['username'], "���ü�����˽({$pid})");
} elseif ($act == 'talent_save') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    $resume = get_resume_basic($uid, $pid);
    if ($resume['complete_percent'] < $_CFG['elite_resume_complete_percent']) {
        showmsg("��������ָ��С��{$_CFG['elite_resume_complete_percent']}%����ֹ���룡", 0);
    }
    $setsqlarr['talent'] = 3;
    $wheresql = " uid='{$uid}' AND id='{$pid}' ";
    updatetable(table('resume'), $setsqlarr, $wheresql);
    write_memberslog($uid, 2, 1107, $_SESSION['username'], "����߼��˲�");
    showmsg('����ɹ�����ȴ�����Ա��ˣ�', 2);
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
} elseif ($act == 'upload_attachment_resume') {
    $uid = intval($_SESSION['uid']);
    !$_FILES['attachment_resume']['name'] ? exit('���ϴ��ļ���') : "";
    if ($uid < 1) {
        exit("-7");
    }
    $attachment_resume_num = get_attachment_resume($uid);
    if ($attachment_resume_num) {
        exit("-9");
    }
    require_once(QISHI_ROOT_PATH . 'include/resume_upload.php');
    $up_dir = "../../data/attachment_resume/" . date("Y/m/d/");
    make_dir($up_dir);
    $setsqlarr['file_name'] = _asUpResume($up_dir, "attachment_resume", 1024 * 10, 'docx/doc/pdf/ppt/txt/wps');
    $setsqlarr['path'] = "/data/attachment_resume/" . date("Y/m/d/");
    $setsqlarr['suffix'] = pathinfo($setsqlarr['file_name'], PATHINFO_EXTENSION);
    //$setsqlarr['suffix'] = strtolower(str_replace(".", "", strrchr($setsqlarr['file_name'], ".")));
    if ($_REQUEST['ajax_path']) {
        exit($setsqlarr['path']);
    }
    if (!empty($setsqlarr['path'])) {
        $setsqlarr['uid'] = $uid;
        $setsqlarr['addtime'] = time();
        inserttable(table("resume_attachment"), $setsqlarr);
        $resume_basic = get_resume_basic($uid);
        $resume_education = get_resume_education($uid, $resume_basic['id']);
        $resume_work = get_resume_work($uid, $resume_basic['id']);
        $up_resume_arr = array('attachment_resume' => $setsqlarr['file_name']);
        $up_resume_arr['default_resume'] = (!empty($resume_education) && !empty($resume_work)) ? 0 : 1;
        updatetable(table("resume"), $up_resume_arr, array('uid' => $uid));
        exit($setsqlarr['path']);
    } else {
        exit("-10");
    }
} elseif ($act == 'download_attachment_resume') {
    $uid = intval($_SESSION['uid']);
    $attachment_arr = $db->getone("SELECT * FROM " . table('resume_attachment') . " WHERE uid = " . $uid);
    //������Ҫ���ص��ļ�����
    $download_path = "../.." . $attachment_arr['path'] . $attachment_arr['file_name'];
    ob_clean(); //���һ�»�����
    //����ļ�����
    $filename = urldecode($attachment_arr['file_name']);
    //�ļ�����·�������ｫ��ʵ���ļ������tempĿ¼�£�
    $filePath = urldecode($download_path);
    //��utf8����ת����gbk���룬�����ļ��������Ƶ��ļ��޷���
    //$filePath = iconv('UTF-8', 'gbk', $filePath);
    //����ļ��Ƿ�ɶ�
    if (!is_file($filePath) || !is_readable($filePath))
        showmsg('�ļ����������', 2);
    /**
     * ����Ӧ�ü��ϰ�ȫ��֤֮��Ĵ��룬���磺���������Դ����֤UA��ʶ�ȵ�
     */
    //��ֻ����ʽ���ļ�����ǿ��ʹ�ö�����ģʽ
    $fileHandle = fopen($filePath, "rb");
    if ($fileHandle === false) {
        showmsg('�ļ���ʧ��', 2);
    }
    //�ļ������Ƕ�������������Ϊutf8���루֧�������ļ����ƣ�
    header('Content-type:application/octet-stream; charset=utf-8');
    header("Content-Transfer-Encoding: binary");
    header("Accept-Ranges: bytes");
    //�ļ���С
    header("Content-Length: " . filesize($filePath));
    //����������ļ����ع���
    header('Content-Disposition:attachment;filename="' . $filename . '"');
    header('Content-Type: application/octet-stream; name=' . $attachment_arr['file_name']);
    //ѭ����ȡ�ļ����ݣ������
    while (!feof($fileHandle)) {
        //���ļ�ָ�� handle ��ȡ��� length ���ֽڣ�ÿ�����10k��
        echo fread($fileHandle, 10240);
    }
    //�ر��ļ���
    fclose($fileHandle);
} elseif ($act == 'del_attachment_resume') {
    $uid = intval($_SESSION['uid']);
    if (del_attachment_resume($uid)) {
        $up_resume_arr['default_resume'] = 0;
        updatetable(table("resume"), $up_resume_arr, array('uid' => $uid));
        exit('1');
    } else {
        exit('0');
    }
} elseif ($act == 'set_default_resume') {
    $uid = intval($_SESSION['uid']);
    $data = intval($_GET['data']);
    if (set_default_resume($uid, $data)) {
        exit('1');
    } else {
        exit('0');
    }
} elseif ($act == 'tpl_order_add') {
    $uid = intval($_SESSION['uid']);
    $tpl_log = get_resume_tpl($uid);
    if ($tpl_log) {
        $link[0]['text'] = "�����ҵļ���";
        $link[0]['href'] = '/user/personal/personal_resume.php?act=edit_resume';
        showmsg("���ļ���ģ����δ������", 1);
    }
    $tpl_id = intval($_POST['tpl_id']) ? intval($_POST['tpl_id']) : showmsg("��ѡ��ģ�棡", 1);
    $smarty->assign('title', '����ģ�� - ���˻�Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('tpl', get_resumetpl_one($tpl_id));
    $smarty->assign('payment', get_payment());
    $smarty->display('member_personal/personal_order_add_tpl.htm');
} elseif ($act == 'tpl_order_add_save') {
    $timestamp = time();
    $tpl = get_resumetpl_one($_POST['tpl_id']);
    if ($tpl) {
        $payment_name = empty($_POST['payment_name']) ? showmsg("��ѡ�񸶿ʽ��", 1) : $_POST['payment_name'];
        $paymenttpye = get_payment_info($payment_name);
        if (empty($paymenttpye)) {
            showmsg("֧����ʽ����", 0);
        }
        $fee = number_format(($tpl['tpl_val'] / 100) * $paymenttpye['fee'], 1, '.', ''); //������
        $order['oid'] = strtoupper(substr($paymenttpye['typename'], 0, 1)) . "rtpl-" . date('Ymd', time()) . date('His', time()) . rand(10, 99); //������
        if (strstr($paymenttpye['payment_name'], 'alipayapi-')) {
            $paymenttpye['typename'] = "alipayapi";
            $respond_name = "alipay";
        } else {
            $respond_name = $paymenttpye['typename'];
        }
        $order['v_url'] = $_CFG['main_domain'] . "include/payment/respond_" . $respond_name . ".php";
        $order['v_amount'] = $tpl['tpl_val'] + $fee; //���
        $order_id = add_order($_SESSION['uid'], $order['oid'], $tpl['tpl_id'], $tpl['tpl_val'], $tpl['tpl_days'], $payment_name, "�������ģ��:" . $tpl['tpl_name'], $timestamp);
        if ($order_id) {
            if ($order['v_amount'] == 0) {//0Ԫ�ײ�
                if (order_paid($order['oid'])) {
                    $link[0]['text'] = "�����ҵļ���";
                    $link[0]['href'] = '/user/personal/personal_resume.php?act=edit_resume';
                    showmsg("�����ɹ���ϵͳ��Ϊ����ͨ�˷���", 2, $link);
                }
            }
            header("Location:?act=payment&order_id=" . $order_id . ""); //����ҳ��
        } else {
            showmsg("��Ӷ���ʧ�ܣ�", 0);
        }
    } else {
        showmsg("��Ӷ���ʧ�ܣ�", 0);
    }
} elseif ($act == 'payment') {
    $order_id = intval($_GET['order_id']);
    $myorder = get_order_one($_SESSION['uid'], $order_id);
    $payment = get_payment_info($myorder['payment_name']);
    if (empty($payment)) {
        showmsg("֧����ʽ����", 0);
    }
    $fee = number_format(($myorder['amount'] / 100) * $payment['fee'], 1, '.', ''); //������
    $order['oid'] = $myorder['oid']; //������
    $order['v_amount'] = $myorder['amount'] + $fee;
    if ($myorder['payment_name'] != 'remittance') {//�����Ƿ�����֧����
        if (strstr($myorder['payment_name'], 'alipayapi-')) {
            $api_path = "alipayapi/";
            $payment['typename'] = "alipayapi";
            $respond_name = "alipay";
        } else {
            $respond_name = $payment['typename'];
        }
        $order['v_url'] = $_CFG['main_domain'] . "include/payment/respond_" . $respond_name . ".php";
        require_once(QISHI_ROOT_PATH . "include/payment/" . $api_path . $payment['typename'] . ".php");
        $payment_form = get_code($order, $payment);
        if (empty($payment_form)) {
            showmsg("����֧����������", 0);
        }
    }
    $smarty->assign('payment', get_payment());
    $smarty->assign('title', '���� - ���˻�Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('fee', $fee);
    $smarty->assign('amount', $myorder['amount']);
    $smarty->assign('oid', $order['oid']);
    $smarty->assign('byname', $payment);
    $smarty->assign('payment_form', $payment_form);
    $smarty->display('member_personal/personal_order_pay.htm');
}
unset($smarty);
?>