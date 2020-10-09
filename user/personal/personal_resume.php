<?php

/*
 * 74cms 个人会员中心
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/personal_common.php');
$smarty->assign('leftmenu', "resume");
$act = !empty($act) ? $act : "userprofile";
//简历列表
if ($act == 'resume_list') {
    $wheresql = " WHERE uid='" . $_SESSION['uid'] . "' ";
    $sql = "SELECT * FROM " . table('resume') . $wheresql;
    $smarty->assign('title', '我的简历 - 个人会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $total = $db->get_total("SELECT COUNT(*) AS num FROM " . table('resume') . $wheresql);
    $smarty->assign('total', $total);
    $smarty->assign('resume_list', get_resume_list($sql, 12, true, true, true));
    $smarty->display('member_personal/personal_resume_list.htm');
} elseif ($act == 'upload_resume') {
    $smarty->assign('title', '上传简历 - 个人会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $smarty->display('member_personal/personal_resume_upload.htm');
} elseif ($act == 'upload_resume_save') {
    /* 在线简历上传功能，未完 */
    !$_FILES['resume_file']['name'] ? exit('请上传文件！') : "";
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
    $in_resume['sex'] = $sex_result[1] == "男" ? 1 : 2;
    $in_resume['sex_cn'] = $sex_result[1] == "男" ? "男" : "女";
    $birthdate_rule = '/<span class="p5">\|<\/span>.* 岁 \((.*?)\/.*?\/.*?\)<span class="p5">/';
    preg_match($birthdate_rule, $content, $birthdate_result);
    $in_resume['birthdate'] = intval($birthdate_result[1]);
    $education_rule = '/<td valign="top" class="keys">学历\/学位：<\/td>[.\n*]<td valign="top" class="txt2">(.*?)<\/td>/';
    preg_match($education_rule, $content, $education_result);
    $education_category = set_category('QS_education');
    foreach ($education_category as $e) {
        if ($e['c_name'] == "不限" && $in_resume['education'] == "") {
            $in_resume['education'] = $e['c_id'];
            $in_resume['education_cn'] = $e['c_name'];
        }
        if ($e['c_name'] == $education_result[1]) {
            $in_resume['education'] = $e['c_id'];
            $in_resume['education_cn'] = $e['c_name'];
        }
    }
    $experience_rule = '/\)<span class="p5">\|<\/span>.*<span class="p5">\|<\/span>(.*?)年工作经验/';
    preg_match($experience_rule, $content, $experience_result);
    $experience_num = intval($experience_result[1]);
    switch ($experience_num) {
        case $experience_num <= 1:
            $in_resume['experience'] = 75;
            $in_resume['experience_cn'] = '1年以下';
            break;
        case $experience_num > 1 && $experience_num < 4:
            $in_resume['experience'] = 76;
            $in_resume['experience_cn'] = '1-3年';
            break;
        case $experience_num > 3 && $experience_num < 6:
            $in_resume['experience'] = 77;
            $in_resume['experience_cn'] = '3-5年';
            break;
        case $experience_num > 5 && $experience_num < 11:
            $in_resume['experience'] = 78;
            $in_resume['experience_cn'] = '5-10年';
            break;
        case $experience_num > 10:
            $in_resume['experience'] = 79;
            $in_resume['experience_cn'] = '10年以上';
            break;
        default:
            $in_resume['experience'] = 74;
            $in_resume['experience_cn'] = '无经验';
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
    $residence_rule = '/<\/span>现居住(.*?)<span/';
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
            $in_resume['residence_cn'] = $residence_data['categoryname'] . '/全部';
        }
    }
    $height_rule = '/<td valign="top" class="keys">身高：<\/td>[.\n*]<td valign="top" class="txt2">(.*?)cm<\/td>/';
    preg_match($height_rule, $content, $height_result);
    intval($height_result[1]) > 0 ? $in_resume['height'] = intval($height_result[1]) : "";
    $householdaddress_rule = '/<td valign="top" class="keys">户口\/国籍：<\/td>[.\n*]<td valign="top" class="txt2">(.*?)<\/td>/';
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
            $in_resume['householdaddress_cn'] = $householdaddress_data['categoryname'] . '/全部';
        }
    }
    $marriage_rule = '/<td valign="top" class="keys">婚姻状况：<\/td>[.\n*]<td valign="top" class="txt2">(.*?)<\/td>/';
    preg_match($marriage_rule, $content, $marriage_result);
    if ($marriage_result[1] == '已婚') {
        $in_resume['marriage'] = 2;
        $in_resume['marriage_cn'] = '已婚';
    } else {
        $in_resume['marriage'] = 1;
        $in_resume['marriage_cn'] = '未婚';
    }
    $in_resume['trade'] = '33';
    $in_resume['trade_cn'] = '教育/培训';
    $intention_jobs_rule = '/<td valign="top" class="txt2">\n*<span class="tag">(.*)\&nbsp\;<\/span>/';
    preg_match($intention_jobs_rule, $content, $intention_jobs_result1);
    $intention_jobs_rule = '/<span class="tag">(.*?)\&nbsp\;<\/span>/';
    preg_match_all($intention_jobs_rule, $intention_jobs_result1[0], $intention_jobs_result);
    foreach ($intention_jobs_result[1] as $i) {
        switch ($i) {
            case $i == "校长":
                $i_jobs['id'] = "0.4.133";
                $i_jobs['cn'] = '校长/副校长';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "大学教授":
                $i_jobs['id'] = "0.4.139";
                $i_jobs['cn'] = '教授';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "讲师/助教":
                $i_jobs['id'] = "0.4.135";
                $i_jobs['cn'] = '助教';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "中学教师":
                $i_jobs['id'] = "0.1.47";
                $i_jobs['cn'] = '中小学各科教师';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "小学教师":
                $i_jobs['id'] = "0.1.47";
                $i_jobs['cn'] = '中小学各科教师';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "幼教":
                $i_jobs['id'] = "0.2.81";
                $i_jobs['cn'] = '幼儿教师';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "外语培训师":
                $i_jobs['id'] = "0.3.87";
                $i_jobs['cn'] = '外语培训';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "院校教务管理人员":
                $i_jobs['id'] = "0.7.173";
                $i_jobs['cn'] = '教育机构管理/教务/教辅';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "兼职教师":
                $i_jobs['id'] = "0.7.178";
                $i_jobs['cn'] = '兼职教师';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "家教":
                $i_jobs['id'] = "0.7.176";
                $i_jobs['cn'] = '家庭教师';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "音乐/美术教师":
                $i_jobs['id'] = "0.3.86";
                $i_jobs['cn'] = '艺术培训';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "体育教师":
                $i_jobs['id'] = "0.7.174";
                $i_jobs['cn'] = '体育教练/健美教练';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "职业技术教师":
                $i_jobs['id'] = "0.3.93";
                $i_jobs['cn'] = '职业教育/培训教师';
                $intention_jobs[] = $i_jobs;
                break;
            case $i == "其他":
                $i_jobs['id'] = "0.7.180";
                $i_jobs['cn'] = '其它职位';
                $intention_jobs[] = $i_jobs;
                break;
            default:
                $i_jobs['id'] = "0.7.180";
                $i_jobs['cn'] = '其它职位';
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

    $district_rule = '/<td valign="top" class="keys">地点：<\/td>[.\n*]<td valign="top" class="txt2"><span class="tag">(.*?)<\/span><\/td>/';
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

    $nature_rule = '/<td valign="top" class="keys">工作类型：<\/td>[.\n*]<td valign="top" class="txt2">(.*?)<\/td>/';
    preg_match($nature_rule, $content, $nature_result);
    switch ($nature_result[1]) {
        case $i == "全职":
            $in_resume['nature'] = 62;
            $in_resume['nature_cn'] = '全职';
            break;
        case $i == "兼职":
            $in_resume['nature'] = 63;
            $in_resume['nature_cn'] = '兼职';
            break;
        case $i == "实习":
            $in_resume['nature'] = 64;
            $in_resume['nature_cn'] = '实习';
            break;
        default:
            $in_resume['nature'] = 62;
            $in_resume['nature_cn'] = '全职';
            break;
    }

    $nature_rule = '/<td valign="top" class="keys">工作类型：<\/td>[.\n*]<td valign="top" class="txt2">(.*?)<\/td>/';
    preg_match($nature_rule, $content, $nature_result);
    switch ($nature_result[1]) {
        case $i == "全职":
            $in_resume['nature'] = 62;
            $in_resume['nature_cn'] = '全职';
            break;
        case $i == "兼职":
            $in_resume['nature'] = 63;
            $in_resume['nature_cn'] = '兼职';
            break;
        case $i == "实习":
            $in_resume['nature'] = 64;
            $in_resume['nature_cn'] = '实习';
            break;
        default:
            $in_resume['nature'] = 62;
            $in_resume['nature_cn'] = '全职';
            break;
    }

    var_dump($in_resume);
    exit;
} elseif ($act == 'refresh') {
    $resumeid = intval($_GET['id']) ? intval($_GET['id']) : showmsg("您没有选择简历！");
    $refrestime = get_last_refresh_date($_SESSION['uid'], "2001");
    $duringtime = time() - $refrestime['max(addtime)'];
    $space = $_CFG['per_refresh_resume_space'] * 60;
    $refresh_time = get_today_refresh_times($_SESSION['uid'], "2001");
    if ($_CFG['per_refresh_resume_time'] != 0 && ($refresh_time['count(*)'] >= $_CFG['per_refresh_resume_time'])) {
        showmsg("每天最多只能刷新" . $_CFG['per_refresh_resume_time'] . "次,您今天已超过最大刷新次数限制！", 2);
    } elseif ($duringtime <= $space) {
        showmsg($_CFG['per_refresh_resume_space'] . "分钟内不能重复刷新简历！", 2);
    } else {
        refresh_resume($resumeid, $_SESSION['uid']) ? showmsg('操作成功！', 2) : showmsg('操作失败！', 0);
    }
}
//删除简历
elseif ($act == 'del_resume') {
    if (intval($_GET['id']) == 0) {
        exit('您没有选择简历！');
    } else {
        del_resume($_SESSION['uid'], intval($_GET['id'])) ? exit('success') : exit('fail');
    }
}
//创建简历-基本信息
elseif ($act == 'make1') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    $total = $db->get_total("SELECT COUNT(*) AS num FROM " . table('resume') . " WHERE uid='{$uid}'");
    if ($total >= intval($_CFG['resume_max'])) {
        showmsg("您最多可以创建{$_CFG['resume_max']} 份简历,已经超出了最大限制！", 1);
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
    $smarty->assign('title', '我的简历 - 个人会员中心 - ' . $_CFG['site_name']);
    $captcha = get_cache('captcha');
    $smarty->assign('verify_resume', $captcha['verify_resume']);
    $smarty->assign('go_resume_show', $_GET['go_resume_show']);
    $smarty->display('member_personal/personal_make_resume_step1.htm');
}
//创建简历 -保存基本信息、求职意向
elseif ($act == 'make1_save') {
    $captcha = get_cache('captcha');
    $postcaptcha = trim($_POST['postcaptcha']);
    if ($captcha['verify_resume'] == '1' && empty($postcaptcha) && intval($_REQUEST['pid']) === 0) {
        showmsg("请填写系统验证码", 1);
    }
    if ($captcha['verify_resume'] == '1' && intval($_REQUEST['pid']) === 0 && strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        showmsg("系统验证码错误", 1);
    }
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['telephone'] = trim($_POST['mobile']) ? trim($_POST['mobile']) : showmsg('请填写手机号！', 1);

    if ($user['mobile_audit'] == 0) {
        $mobile_audit = 0;
        $verifycode = trim($_POST['verifycode']);
        if ($verifycode) {
            if (empty($_SESSION['mobile_rand']) || $verifycode <> $_SESSION['mobile_rand']) {
                showmsg("手机验证码错误", 1);
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
    $setsqlarr['title'] = trim($_POST['title']) ? trim($_POST['title']) : "未命名简历";
    $setsqlarr['fullname'] = trim($_POST['fullname']) ? trim($_POST['fullname']) : showmsg('请填写姓名！', 1);
    $setsqlarr['display_name'] = intval($_POST['display_name']);
    $setsqlarr['sex'] = trim($_POST['sex']) ? intval($_POST['sex']) : showmsg('请选择性别！', 1);
    $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
    $setsqlarr['birthdate'] = intval($_POST['birthdate']) > 1945 ? intval($_POST['birthdate']) : showmsg('请正确填写出生年份', 1);
    $setsqlarr['residence'] = trim($_POST['residence']) ? trim($_POST['residence']) : "";
    $setsqlarr['residence_cn'] = trim($_POST['residence_cn']);
    $setsqlarr['education'] = intval($_POST['education']) ? intval($_POST['education']) : showmsg('请选择学历', 1);
    $setsqlarr['education_cn'] = trim($_POST['education_cn']);
    $setsqlarr['experience'] = intval($_POST['experience']) ? intval($_POST['experience']) : showmsg('请选择工作经验', 1);
    $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
    $setsqlarr['email'] = trim($_POST['email']) ? trim($_POST['email']) : showmsg('请填写邮箱！', 1);
    $setsqlarr['email_notify'] = $_POST['email_notify'] == "1" ? 1 : 0;
    $setsqlarr['height'] = intval($_POST['height']);
    $setsqlarr['householdaddress'] = trim($_POST['householdaddress']);
    $setsqlarr['householdaddress_cn'] = trim($_POST['householdaddress_cn']);
    $setsqlarr['marriage'] = intval($_POST['marriage']);
    $setsqlarr['marriage_cn'] = trim($_POST['marriage_cn']);
    $setsqlarr['intention_jobs'] = trim($_POST['intention_jobs']) ? trim($_POST['intention_jobs']) : showmsg('请选择意向职位！', 1);
    $setsqlarr['trade'] = $_POST['trade'] ? trim($_POST['trade']) : showmsg('请选择期望行业！', 1);
    $setsqlarr['trade_cn'] = trim($_POST['trade_cn']);
    $setsqlarr['district'] = trim($_POST['district']) ? intval($_POST['district']) : showmsg('请选择期望工作地区！', 1);
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
        showmsg("您最多可以创建{$_CFG['resume_max']} 份简历,已经超出了最大限制！", 1);
    } else {
        $setsqlarr['addtime'] = $timestamp;
        if (!empty($setsqlarr['fullname'])) {
            $pid = inserttable(table('resume'), $setsqlarr, 1);
        }
        if (empty($pid)) {
            showmsg("保存失败！", 0);
        }
        $searchtab['id'] = $pid;
        $searchtab['uid'] = $_SESSION['uid'];
        inserttable(table('resume_search_key'), $searchtab);
        inserttable(table('resume_search_rtime'), $searchtab);
        add_resume_jobs($pid, $_SESSION['uid'], $_POST['intention_jobs_id'], $setsqlarr['district'], $setsqlarr['sdistrict']) ? "" : showmsg('保存失败！', 0);
        add_resume_trade($pid, $_SESSION['uid'], $_POST['trade']) ? "" : showmsg('保存失败！', 0);
        check_resume($_SESSION['uid'], $pid);
        if (intval($_POST['entrust'])) {
            set_resume_entrust($pid);
        }
        write_memberslog($_SESSION['uid'], 2, 1101, $_SESSION['username'], "创建了简历");

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
    $smarty->assign('title', '我的简历 - 个人会员中心 - ' . $_CFG['site_name']);
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
        $html.='<div class="more link_lan"><a target="_blank" href="' . url_rewrite("QS_jobslist") . '">更多职位>></a></div>';
    }
    exit($html);
} elseif ($act == 'ajax_save_basic_info') {
    $telephone = trim($_POST['mobile']) ? trim($_POST['mobile']) : exit('请填写手机号！');
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
    $setsqlarr['fullname'] = trim($_POST['fullname']) ? utf8_to_gbk(trim($_POST['fullname'])) : exit('请填写姓名！');
    check_word($_CFG['filter'], $setsqlarr['fullname']) ? exit($_CFG['filter_tips']) : '';
    $setsqlarr['display_name'] = intval($_POST['display_name']);
    $setsqlarr['sex'] = trim($_POST['sex']) ? intval($_POST['sex']) : exit('请选择性别！');
    $setsqlarr['sex_cn'] = utf8_to_gbk(trim($_POST['sex_cn']));
    $setsqlarr['birthdate'] = intval($_POST['birthdate']) > 1945 ? intval($_POST['birthdate']) : exit('请正确填写出生年份');
    $setsqlarr['residence'] = trim($_POST['residence']) ? utf8_to_gbk(trim($_POST['residence'])) : exit('请填写现居住地！');
    $setsqlarr['education'] = intval($_POST['education']) ? intval($_POST['education']) : exit('请选择学历');
    $setsqlarr['education_cn'] = utf8_to_gbk(trim($_POST['education_cn']));
    $setsqlarr['experience'] = $_POST['experience'] ? $_POST['experience'] : exit('请选择工作经验');
    $setsqlarr['experience_cn'] = utf8_to_gbk(trim($_POST['experience_cn']));
    $setsqlarr['email'] = trim($_POST['email']) ? utf8_to_gbk(trim($_POST['email'])) : exit('请填写邮箱！');
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
    write_memberslog($_SESSION['uid'], 2, 1105, $_SESSION['username'], "修改了简历({$title})");
    exit("success");
} elseif ($act == 'ajax_save_basic') {
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['telephone'] = trim($_POST['telephone']) ? trim($_POST['telephone']) : exit('请填写手机号！');
    $setsqlarr['fullname'] = trim($_POST['fullname']) ? utf8_to_gbk(trim($_POST['fullname'])) : exit('请填写姓名！');
    $setsqlarr['title'] = $setsqlarr['fullname'] . "的简历";
    $sex = trim($_POST['sex']) ? explode('-', utf8_to_gbk($_POST['sex'])) : exit('请选择性别！');
    $setsqlarr['sex'] = $sex[0];
    $setsqlarr['sex_cn'] = $sex[1];
    $setsqlarr['birthdate'] = intval($_POST['birthdate']) > 1945 ? intval($_POST['birthdate']) : exit('请正确填写出生年份');
    $residence_district = trim($_POST['residence_district']) ? explode('-', utf8_to_gbk($_POST['residence_district'])) : "";
    $residence_sdistrict = trim($_POST['residence_sdistrict']) ? explode('-', utf8_to_gbk($_POST['residence_sdistrict'])) : "";
    $setsqlarr['residence'] = !empty($residence_district) ? $residence_district[0] . "." . $residence_sdistrict[0] : "";
    $setsqlarr['residence_cn'] = !empty($residence_district) ? $residence_district[1] . "/" . $residence_sdistrict[1] : "";
    $education = trim($_POST['education']) ? explode('-', utf8_to_gbk($_POST['education'])) : exit('请选择学历');
    $setsqlarr['education'] = $education[0];
    $setsqlarr['education_cn'] = $education[1];
    $experience = trim($_POST['experience']) ? explode('|', utf8_to_gbk($_POST['experience'])) : exit('请选择工作经验');
    $setsqlarr['experience'] = $experience[0];
    $setsqlarr['experience_cn'] = $experience[1];
    $setsqlarr['email'] = trim($_POST['email']) ? utf8_to_gbk(trim($_POST['email'])) : exit('请填写邮箱！');
    $setsqlarr['email_notify'] = 0;

    $marriage = trim($_POST['marriage']) ? explode('-', utf8_to_gbk($_POST['marriage'])) : "";
    $setsqlarr['marriage'] = !empty($marriage) ? $marriage[0] : "";
    $setsqlarr['marriage_cn'] = !empty($marriage) ? $marriage[1] : "";
    $setsqlarr['trade'] = $_POST['trade'] ? trim($_POST['trade']) : 33;
    $setsqlarr['trade_cn'] = "教育/培训";
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
            showmsg("保存失败！", 0);
        }
        $searchtab['id'] = $pid;
        $searchtab['uid'] = $_SESSION['uid'];
        inserttable(table('resume_search_key'), $searchtab);
        inserttable(table('resume_search_rtime'), $searchtab);
    }
    //add_resume_jobs(intval($_REQUEST['pid']), $_SESSION['uid'], $intention_jobs_id, $setsqlarr['district'], $setsqlarr['sdistrict']) ? "" : showmsg('保存失败！', 0);
    add_resume_trade(intval($_REQUEST['pid']), $_SESSION['uid'], $_POST['trade']) ? "" : showmsg('保存失败！', 0);
    check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
    if ($_CFG['audit_edit_resume'] != "-1" && $_POST['entrust'] == 1) {
        set_resume_entrust(intval($_REQUEST['pid']));
    }
    write_memberslog($_SESSION['uid'], 2, 1105, $_SESSION['username'], "修改了简历({$setsqlarr['title']})");

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
            write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "修改了个人资料");
            updatetable(table('members_info'), $infoarr, $wheresql);
        } else {
            $infoarr['uid'] = intval($_SESSION['uid']);
            write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "修改了个人资料");
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

    $district = trim($_POST['district']) ? explode('-', utf8_to_gbk($_POST['district'])) : exit('请选择期望工作地区！');
    $setsqlarr['district'] = $district[0];
    $sdistrict = trim($_POST['sdistrict']) ? explode('-', utf8_to_gbk($_POST['sdistrict'])) : "0-全部";
    $setsqlarr['sdistrict'] = $sdistrict[0];
    $setsqlarr['district_cn'] = $district[1] . "/" . $sdistrict[1];
    $setsqlarr['intention_jobs'] = !empty($_POST['intention_jobs']) ? utf8_to_gbk(trim($_POST['intention_jobs'])) : exit('请选择期望职位！');
    $intention_jobs_id = !empty($_POST['intention_jobs_id']) ? trim($_POST['intention_jobs_id'], ",") : exit('请重新选择期望职位！');

    $wage = trim($_POST['wage']) ? explode('-', utf8_to_gbk($_POST['wage'])) : "";
    $setsqlarr['wage'] = !empty($wage) ? $wage[0] : "";
    $setsqlarr['wage_cn'] = !empty($wage) ? $wage[1] : "";

    $setsqlarr['refreshtime'] = time();
    $_CFG['audit_edit_resume'] != "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_edit_resume']) : "";
    updatetable(table('resume'), $setsqlarr, " id='" . intval($_REQUEST['pid']) . "'  AND uid='{$setsqlarr['uid']}'");
    add_resume_jobs(intval($_REQUEST['pid']), $_SESSION['uid'], $intention_jobs_id, $setsqlarr['district'], $setsqlarr['sdistrict']) ? "" : showmsg('保存失败！', 0);
    check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
    write_memberslog($_SESSION['uid'], 2, 1105, $_SESSION['username'], "修改了简历");
    exit("success");
}
//创建简历-第二步
elseif ($act == 'make2') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    $link[0]['text'] = "返回简历列表";
    $link[0]['href'] = '?act=resume_list';
    if ($uid == 0 || $pid == 0)
        showmsg('简历不存在！', 1, $link);
    $resume_basic = get_resume_basic($uid, $pid);
    $link[0]['text'] = "填写简历基本信息";
    $link[0]['href'] = '?act=make1';
    if (empty($resume_basic))
        showmsg("请先填写简历基本信息！", 1, $link);
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
    $smarty->assign('title', '我的简历 - 个人会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('go_resume_show', $_GET['go_resume_show']);

    $smarty->assign('subsite', get_subsite_list());
    $smarty->display('member_personal/personal_make_resume_step2.htm');
} elseif ($act == 'make2_photo_ready') {
    !$_FILES['photo']['name'] ? exit('请上传图片！') : "";
    require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
    if (intval($_REQUEST['pid']) == 0)
        exit('参数错误！');
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
    !updatetable(table('resume'), $setsqlarr, " id='" . intval($_REQUEST['pid']) . "' AND uid='" . intval($_SESSION['uid']) . "'") ? exit("保存失败！") : '';
    // check_resume($_SESSION['uid'],intval($_REQUEST['pid']));
    exit($setsqlarr['photo_img']);
} elseif ($act == 'make2_photo_save') {
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic)) {
        showmsg("请先填写简历基本信息！", 0);
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
            !updatetable(table('resume'), $setsqlarr, " id='" . intval($_REQUEST['pid']) . "' AND uid='" . intval($_SESSION['uid']) . "'") ? exit("保存失败！") : '';
            check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
            showmsg("您图片的格式错误！请重新选择！", 3);
        } else {
            check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
            showmsg("保存成功！", 2);
        }
    } else {
        showmsg("请上传图片！", 1);
    }
} elseif ($act == 'make2_certificate_save') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    if (!empty($_REQUEST['img_path'])) {
        $setsqlarr['path'] = $_REQUEST['img_path'];
    } else {
        !$_FILES['certificate']['name'] ? exit('请上传图片！') : "";
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
//删除证书
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
            check_resume($_SESSION['uid'], intval($_REQUEST['pid'])); //更新简历完成状态
            exit('删除成功！');
        }
    }
    exit('删除失败！');
} elseif ($act == "tag_save") {
    if (intval($_POST['pid']) == 0) {
        showmsg('参数错误！', 1);
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
    showmsg("保存成功！", 2);
    // header('Location: ?act=edit_resume&pid='.$_REQUEST['pid']);
} elseif ($act == "set_entrust") {
    if (intval($_GET['pid']) == 0) {
        showmsg('参数错误！', 1);
    } else {
        set_resume_entrust(intval($_GET['pid']));
        updatetable(table('resume'), array("entrust" => 1), " id='" . intval($_GET['pid']) . "' AND uid='" . intval($_SESSION['uid']) . "'");
        showmsg("委托成功,系统将会在三天内为您自动投递合适的职位！", 2);
    }
} elseif ($act == "del_entrust") {
    if (intval($_GET['pid']) == 0) {
        showmsg('参数错误！', 1);
    } else {
        $wheresqlarr = " id = " . intval($_GET['pid']);
        deletetable(table('resume_entrust'), $wheresqlarr);
        updatetable(table('resume'), array("entrust" => 0), " id='" . intval($_GET['pid']) . "' AND uid='" . intval($_SESSION['uid']) . "'");
        showmsg("取消委托成功！", 2);
    }
} elseif ($act == 'save_education') {
    $id = intval($_POST['id']);
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['pid'] = intval($_REQUEST['pid']);
    if ($setsqlarr['uid'] == 0 || $setsqlarr['pid'] == 0) {
        exit('简历不存在！');
    }
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic)) {
        exit("请先填写简历基本信息！");
    }
    $resume_education = get_resume_education($_SESSION['uid'], $_REQUEST['pid']);
    if (count($resume_education) >= 6) {
        exit('教育经历不能超过6条！');
    }
    $school = utf8_to_gbk(trim($_POST['school']));
    $speciality = utf8_to_gbk(trim($_POST['speciality']));
    $education_cn = utf8_to_gbk(trim($_POST['education_cn']));
    $setsqlarr['school'] = $school ? $school : exit("请填写学校名称！");
    $setsqlarr['speciality'] = $speciality ? $speciality : exit("请填写专业名称！");
    $setsqlarr['education'] = intval($_POST['education']) ? intval($_POST['education']) : exit("请选择学历！");
    $setsqlarr['education_cn'] = $education_cn ? $education_cn : exit("请选择学历！");
    if (trim($_POST['edu_start_year']) == "" || trim($_POST['edu_start_month']) == "" || trim($_POST['edu_end_year']) == "" || trim($_POST['edu_end_month']) == "") {
        exit("请选择就读时间！");
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
            exit("保存失败");
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
              <div class="l1">' . $value["startyear"] . '年' . $value["startmonth"] . '月-' . $value["endyear"] . '年' . $value["endmonth"] . '月</div>
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
          <div class="txt">教育经历最能体现您的学历和专业能力，快来完成它吸引企业和HR青睐吧！</div>
          <div class="addbut">
          <input type="button" name="" id="empty_add_education" value="添加经历"  class="but130lan_add"/>
          </div>
          </div>';
          $html.=$js;
         * $html
         */
    }
    exit($html);
}
//创建简历-修改教育经历
elseif ($act == 'edit_education') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    if ($uid == 0 || $pid == 0)
        exit('简历不存在！');
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic))
        exit("请先填写简历基本信息！");
    $id = intval($_GET['id']) ? intval($_GET['id']) : exit('参数错误！');
    $education_edit = get_resume_education_one($_SESSION['uid'], $pid, $id);
    foreach ($education_edit as $key => $value) {
        $education_edit[$key] = gbk_to_utf8($value);
    }
    $json_encode = json_encode($education_edit);
    exit($json_encode);
}
//创建简历-删除教育经历
elseif ($act == 'del_education') {
    $id = intval($_GET['id']);
    $sql = "Delete from " . table('resume_education') . " WHERE id='{$id}'  AND uid='" . intval($_SESSION['uid']) . "' AND pid='" . intval($_REQUEST['pid']) . "' LIMIT 1 ";
    if ($db->query($sql)) {
        check_resume($_SESSION['uid'], intval($_REQUEST['pid'])); //更新简历完成状态
        exit('删除成功！');
    } else {
        exit('删除失败！');
    }
} elseif ($act == 'save_work') {
    $id = intval($_POST['id']);
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['pid'] = intval($_REQUEST['pid']);
    if ($setsqlarr['uid'] == 0 || $setsqlarr['pid'] == 0) {
        exit('简历不存在！');
    }
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic)) {
        exit("请先填写简历基本信息！");
    }
    $companyname = utf8_to_gbk(trim($_POST['companyname']));
    $jobs = utf8_to_gbk(trim($_POST['jobs']));
    $achievements = utf8_to_gbk(trim($_POST['achievements']));
    $setsqlarr['companyname'] = $companyname ? $companyname : exit("请填写公司名称！");
    $setsqlarr['jobs'] = $jobs ? $jobs : exit("请填写职位名称！");
    if (trim($_POST['work_start_year']) == "" || trim($_POST['work_start_month']) == "" || trim($_POST['work_end_year']) == "" || trim($_POST['work_end_month']) == "") {
        exit("请选择任职时间！");
    }
    $setsqlarr['startyear'] = intval($_POST['work_start_year']);
    $setsqlarr['startmonth'] = intval($_POST['work_start_month']);
    $setsqlarr['endyear'] = intval($_POST['work_end_year']);
    $setsqlarr['endmonth'] = intval($_POST['work_end_month']);
    $setsqlarr['achievements'] = $achievements ? $achievements : exit("请填写工作职责！");

    if ($id) {
        updatetable(table("resume_work"), $setsqlarr, array("id" => $id));
        exit("success");
    } else {
        $insert_id = inserttable(table("resume_work"), $setsqlarr, 1);
        if ($insert_id) {
            check_resume($_SESSION['uid'], intval($_REQUEST['pid']));
            exit("success");
        } else {
            exit("保存失败");
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
              <div class="l1">' . $value["startyear"] . '年' . $value["startmonth"] . '月-' . $value["endyear"] . '年' . $value["endmonth"] . '月</div>
              <div class="l2">' . $value["companyname"] . '</div>
              <div class="l3">' . $value["jobs"] . '</div>
              <div class="l4">
              <a class="edit_work" href="javascript:void(0);" url="?act=edit_work&id=' . $value["id"] . '&pid=' . $pid . '"></a>
              <a class="del_work d" href="javascript:void(0);" pid="' . $pid . '" work_id="' . $value["id"] . '" ></a><div class="clear"></div>
              <div class="clear"></div>
              </div>
              <div class="l5">工作职责：</div>
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
          <div class="txt">工作经历最能体现您丰富的阅历和出众的工作能力，是你薪酬翻倍的筹码哦HR青睐吧！</div>
          <div class="addbut">
          <input type="button" name="" id="empty_add_work" value="添加经历"  class="but130lan_add"/>
          </div>
          </div>';
          $html.=$js;
         * 
         */
    }
    exit($html);
}
//创建简历-修改工作经历
elseif ($act == 'edit_work') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    if ($uid == 0 || $pid == 0)
        exit('简历不存在！');
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic))
        exit("请先填写简历基本信息！");
    $id = intval($_GET['id']) ? intval($_GET['id']) : exit('参数错误！');
    $work_edit = get_resume_work_one($_SESSION['uid'], $pid, $id);
    foreach ($work_edit as $key => $value) {
        $work_edit[$key] = gbk_to_utf8($value);
    }
    $json_encode = json_encode($work_edit);
    exit($json_encode);
}
//创建简历-删除工作经历
elseif ($act == 'del_work') {
    $id = intval($_GET['id']);
    $sql = "Delete from " . table('resume_work') . " WHERE id='{$id}'  AND uid='" . intval($_SESSION['uid']) . "' AND pid='" . intval($_REQUEST['pid']) . "' LIMIT 1 ";
    if ($db->query($sql)) {
        check_resume($_SESSION['uid'], intval($_REQUEST['pid'])); //更新简历完成状态
        exit('删除成功！');
    } else {
        exit('删除失败！');
    }
} elseif ($act == 'save_training') {
    $id = intval($_POST['id']);
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['pid'] = intval($_REQUEST['pid']);
    if ($setsqlarr['uid'] == 0 || $setsqlarr['pid'] == 0) {
        exit('简历不存在！');
    }
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic)) {
        exit("请先填写简历基本信息！");
    }
    $agency = utf8_to_gbk(trim($_POST['agency']));
    $course = utf8_to_gbk(trim($_POST['course']));
    $description = utf8_to_gbk(trim($_POST['description']));
    $setsqlarr['agency'] = $agency ? $agency : exit("请填写培训机构！");
    $setsqlarr['course'] = $course ? $course : exit("请填写培训课程！");
    if (trim($_POST['training_start_year']) == "" || trim($_POST['training_start_month']) == "" || trim($_POST['training_end_year']) == "" || trim($_POST['training_end_month']) == "") {
        exit("请选择培训时间！");
    }
    $setsqlarr['startyear'] = intval($_POST['training_start_year']);
    $setsqlarr['startmonth'] = intval($_POST['training_start_month']);
    $setsqlarr['endyear'] = intval($_POST['training_end_year']);
    $setsqlarr['endmonth'] = intval($_POST['training_end_month']);
    $setsqlarr['description'] = $description ? $description : exit("请填写培训内容！");

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
              <div class="l1">' . $value["startyear"] . '年' . $value["startmonth"] . '月-' . $value["endyear"] . '年' . $value["endmonth"] . '月</div>
              <div class="l2">' . $value["agency"] . '</div>
              <div class="l3">' . $value["course"] . '</div>
              <div class="l4">
              <a class="edit_training" href="javascript:void(0);" url="?act=edit_training&id=' . $value["id"] . '&pid=' . $pid . '"></a>
              <a class="del_training d" href="javascript:void(0);" pid="' . $pid . '" training_id="' . $value["id"] . '" ></a><div class="clear"></div>
              </div>
              <div class="l5">培训内容：</div>
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
          <div class="txt">培训经历是你用于上进的最好的体现，快来说说令您难忘的学习经历吧！</div>
          <div class="addbut">
          <input type="button" name="" id="empty_add_training" value="添加经历"  class="but130lan_add"/>
          </div>
          </div>';
          $html.=$js;
         * description
         */
    }
    exit($html);
}
//创建简历-修改培训经历
elseif ($act == 'edit_training') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    if ($uid == 0 || $pid == 0)
        exit('简历不存在！');
    $resume_basic = get_resume_basic(intval($_SESSION['uid']), intval($_REQUEST['pid']));
    if (empty($resume_basic))
        exit("请先填写简历基本信息！");
    $id = intval($_GET['id']) ? intval($_GET['id']) : exit('参数错误！');
    $training_edit = get_resume_training_one($_SESSION['uid'], $pid, $id);
    foreach ($training_edit as $key => $value) {
        $training_edit[$key] = gbk_to_utf8($value);
    }
    $json_encode = json_encode($training_edit);
    exit($json_encode);
}
//创建简历-删除培训经历
elseif ($act == 'del_training') {
    $id = intval($_GET['id']);
    $sql = "Delete from " . table('resume_training') . " WHERE id='{$id}'  AND uid='" . intval($_SESSION['uid']) . "' AND pid='" . intval($_REQUEST['pid']) . "' LIMIT 1 ";
    if ($db->query($sql)) {
        check_resume($_SESSION['uid'], intval($_REQUEST['pid'])); //更新简历完成状态
        exit('删除成功！');
    } else {
        exit('删除失败！');
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
    $link[0]['text'] = "返回个人中心";
    $link[0]['href'] = '?act=edit_resume';
    showmsg("设置成功", 1, $link);
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
    $tpl_name = !empty($tpl_log) ? $tpl['tpl_name'] : "默认";
    $smarty->assign('tpl_name', $tpl_name);
    $resume_basic = get_resume_basic($uid, $pid);
    $residence = explode(".", $resume_basic['residence']);
    $residence_cn = explode("/", $resume_basic['residence_cn']);
    $resume_basic['residence_district'] = $residence[0];
    $resume_basic['residence_district_cn'] = $residence_cn[0];
    $resume_basic['residence_sdistrict'] = $residence[1];
    $resume_basic['residence_sdistrict_cn'] = !empty($residence_cn[1]) && $residence_cn[1] != $residence_cn[0] ? $residence_cn[1] : "全部";
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
    $resume_basic['sdistrict_cn'] = !empty($district_cn[1]) && $district_cn[1] != $district_cn[0] ? $district_cn[1] : "全部";
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
    $smarty->assign('title', '我的简历 - 个人会员中心 - ' . $_CFG['site_name']);
    $captcha = get_cache('captcha');
    $smarty->assign('verify_resume', $captcha['verify_resume']);
    $smarty->assign('go_resume_show', $_GET['go_resume_show']);
    $smarty->display('member_personal/personal_resume_edit.htm');
}
// 简历发布按钮 
elseif ($act == "edit_resume_save") {
    $uid = intval($_SESSION['uid']);
    $pid = $_POST['pid'] ? intval($_POST['pid']) : showmsg("简历ID丢失", 1);
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
    write_memberslog($_SESSION['uid'], 2, 1104, $_SESSION['username'], "设置简历隐私({$pid})");
} elseif ($act == 'talent_save') {
    $uid = intval($_SESSION['uid']);
    $pid = intval($_REQUEST['pid']);
    $resume = get_resume_basic($uid, $pid);
    if ($resume['complete_percent'] < $_CFG['elite_resume_complete_percent']) {
        showmsg("简历完整指数小于{$_CFG['elite_resume_complete_percent']}%，禁止申请！", 0);
    }
    $setsqlarr['talent'] = 3;
    $wheresql = " uid='{$uid}' AND id='{$pid}' ";
    updatetable(table('resume'), $setsqlarr, $wheresql);
    write_memberslog($uid, 2, 1107, $_SESSION['username'], "申请高级人才");
    showmsg('申请成功，请等待管理员审核！', 2);
} elseif ($act == "get_city") {
    $parent_district_id = intval($_GET['id']);
    $sql = "select * from " . table("category_district") . " where parentid = " . $parent_district_id . " order by category_order desc,id asc";
    $ajax_str = "0-全部||";
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
    !$_FILES['attachment_resume']['name'] ? exit('请上传文件！') : "";
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
    //接收需要下载的文件名称
    $download_path = "../.." . $attachment_arr['path'] . $attachment_arr['file_name'];
    ob_clean(); //清除一下缓冲区
    //获得文件名称
    $filename = urldecode($attachment_arr['file_name']);
    //文件完整路径（这里将真实的文件存放在temp目录下）
    $filePath = urldecode($download_path);
    //将utf8编码转换成gbk编码，否则，文件中文名称的文件无法打开
    //$filePath = iconv('UTF-8', 'gbk', $filePath);
    //检查文件是否可读
    if (!is_file($filePath) || !is_readable($filePath))
        showmsg('文件不允许访问', 2);
    /**
     * 这里应该加上安全验证之类的代码，例如：检测请求来源、验证UA标识等等
     */
    //以只读方式打开文件，并强制使用二进制模式
    $fileHandle = fopen($filePath, "rb");
    if ($fileHandle === false) {
        showmsg('文件打开失败', 2);
    }
    //文件类型是二进制流。设置为utf8编码（支持中文文件名称）
    header('Content-type:application/octet-stream; charset=utf-8');
    header("Content-Transfer-Encoding: binary");
    header("Accept-Ranges: bytes");
    //文件大小
    header("Content-Length: " . filesize($filePath));
    //触发浏览器文件下载功能
    header('Content-Disposition:attachment;filename="' . $filename . '"');
    header('Content-Type: application/octet-stream; name=' . $attachment_arr['file_name']);
    //循环读取文件内容，并输出
    while (!feof($fileHandle)) {
        //从文件指针 handle 读取最多 length 个字节（每次输出10k）
        echo fread($fileHandle, 10240);
    }
    //关闭文件流
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
        $link[0]['text'] = "返回我的简历";
        $link[0]['href'] = '/user/personal/personal_resume.php?act=edit_resume';
        showmsg("您的简历模版尚未结束！", 1);
    }
    $tpl_id = intval($_POST['tpl_id']) ? intval($_POST['tpl_id']) : showmsg("请选择模版！", 1);
    $smarty->assign('title', '简历模版 - 个人会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('tpl', get_resumetpl_one($tpl_id));
    $smarty->assign('payment', get_payment());
    $smarty->display('member_personal/personal_order_add_tpl.htm');
} elseif ($act == 'tpl_order_add_save') {
    $timestamp = time();
    $tpl = get_resumetpl_one($_POST['tpl_id']);
    if ($tpl) {
        $payment_name = empty($_POST['payment_name']) ? showmsg("请选择付款方式！", 1) : $_POST['payment_name'];
        $paymenttpye = get_payment_info($payment_name);
        if (empty($paymenttpye)) {
            showmsg("支付方式错误！", 0);
        }
        $fee = number_format(($tpl['tpl_val'] / 100) * $paymenttpye['fee'], 1, '.', ''); //手续费
        $order['oid'] = strtoupper(substr($paymenttpye['typename'], 0, 1)) . "rtpl-" . date('Ymd', time()) . date('His', time()) . rand(10, 99); //订单号
        if (strstr($paymenttpye['payment_name'], 'alipayapi-')) {
            $paymenttpye['typename'] = "alipayapi";
            $respond_name = "alipay";
        } else {
            $respond_name = $paymenttpye['typename'];
        }
        $order['v_url'] = $_CFG['main_domain'] . "include/payment/respond_" . $respond_name . ".php";
        $order['v_amount'] = $tpl['tpl_val'] + $fee; //金额
        $order_id = add_order($_SESSION['uid'], $order['oid'], $tpl['tpl_id'], $tpl['tpl_val'], $tpl['tpl_days'], $payment_name, "购买简历模版:" . $tpl['tpl_name'], $timestamp);
        if ($order_id) {
            if ($order['v_amount'] == 0) {//0元套餐
                if (order_paid($order['oid'])) {
                    $link[0]['text'] = "返回我的简历";
                    $link[0]['href'] = '/user/personal/personal_resume.php?act=edit_resume';
                    showmsg("操作成功，系统已为您开通了服务！", 2, $link);
                }
            }
            header("Location:?act=payment&order_id=" . $order_id . ""); //付款页面
        } else {
            showmsg("添加订单失败！", 0);
        }
    } else {
        showmsg("添加订单失败！", 0);
    }
} elseif ($act == 'payment') {
    $order_id = intval($_GET['order_id']);
    $myorder = get_order_one($_SESSION['uid'], $order_id);
    $payment = get_payment_info($myorder['payment_name']);
    if (empty($payment)) {
        showmsg("支付方式错误！", 0);
    }
    $fee = number_format(($myorder['amount'] / 100) * $payment['fee'], 1, '.', ''); //手续费
    $order['oid'] = $myorder['oid']; //订单号
    $order['v_amount'] = $myorder['amount'] + $fee;
    if ($myorder['payment_name'] != 'remittance') {//假如是非线下支付，
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
            showmsg("在线支付参数错误！", 0);
        }
    }
    $smarty->assign('payment', get_payment());
    $smarty->assign('title', '付款 - 个人会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('fee', $fee);
    $smarty->assign('amount', $myorder['amount']);
    $smarty->assign('oid', $order['oid']);
    $smarty->assign('byname', $payment);
    $smarty->assign('payment_form', $payment_form);
    $smarty->display('member_personal/personal_order_pay.htm');
}
unset($smarty);
?>