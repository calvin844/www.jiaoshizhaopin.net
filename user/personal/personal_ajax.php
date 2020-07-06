<?php

/*
 * 74cms ���˻�Ա����ajax������
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
if ($act == "privacy") {
    $pid = intval($_GET['pid']);
    $uid = intval($_SESSION['uid']);
    $resume_basic = get_resume_basic($uid, $pid);
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_personal/ajax_privacy_box.htm";
    $contents = file_get_contents($tpl);
    $contents = str_replace('{#$resumeid#}', $pid, $contents);
    $contents = str_replace('{#$title#}', $resume_basic['title'], $contents);
    $contents = str_replace('{#$lastname#}', $resume_basic['lastname'], $contents);
    $contents = str_replace('{#$privacy_display#}', $resume_basic['display'], $contents);
    $contents = str_replace('{#$privacy_display_name#}', $resume_basic['display_name'], $contents);
    $contents = str_replace('{#$privacy_photo_display#}', $resume_basic['photo_display'], $contents);
    exit($contents);
} elseif ($act == "user_email") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_personal/ajax_authenticate_email_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_email_key'] = mt_rand(100000, 999999);
    $contents = str_replace('{#$email#}', $user["email"], $contents);
    $contents = str_replace('{#$send_email_key#}', $_SESSION['send_email_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "user_mobile") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_personal/ajax_authenticate_mobile_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $contents = str_replace('{#$mobile#}', $user["mobile"], $contents);
    $contents = str_replace('{#$send_mobile_key#}', $_SESSION['send_mobile_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "old_mobile") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_personal/ajax_authenticate_old_mobile_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $user["hid_mobile"] = substr($user["mobile"], 0, 3) . "*****" . substr($user["mobile"], 7, 4);
    $contents = str_replace('{#$mobile#}', $user["mobile"], $contents);
    $contents = str_replace('{#$hid_mobile#}', $user["hid_mobile"], $contents);
    $contents = str_replace('{#$send_mobile_key#}', $_SESSION['send_mobile_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "edit_mobile") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_personal/ajax_authenticate_edit_mobile_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $contents = str_replace('{#$send_mobile_key#}', $_SESSION['send_mobile_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "shield_company") {
    $pid = intval($_GET['pid']);
    $uid = intval($_SESSION['uid']);
    $resume_basic = get_resume_basic($uid, $pid);
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_personal/ajax_shield_company.htm";
    $contents = file_get_contents($tpl);
    $contents = str_replace('{#$resumeid#}', $pid, $contents);
    $contents = str_replace('{#$title#}', $resume_basic['title'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "save_shield_company") {
    $setsqlarr['pid'] = intval($_POST['pid']) ? intval($_POST['pid']) : exit("-1");
    $setsqlarr['uid'] = intval($_SESSION['uid']) ? intval($_SESSION['uid']) : exit("-1");
    $count = count_com_keyword($setsqlarr['uid'], $setsqlarr['pid']);
    if ($count >= 10) {
        exit("full");
    }
    $setsqlarr['comkeyword'] = trim($_POST['comkeyword']) ? utf8_to_gbk(trim($_POST['comkeyword'])) : exit("-1");
    $insertid = inserttable(table("personal_shield_company"), $setsqlarr, 1);
    if ($insertid) {
        exit("1");
    } else {
        exit("-1");
    }
} elseif ($act == "get_shield_com_keyword") {
    $pid = intval($_POST['pid']) ? intval($_POST['pid']) : exit("-1");
    $uid = intval($_SESSION['uid']) ? intval($_SESSION['uid']) : exit("-1");
    $comkeyword = get_com_keyword($uid, $pid);
    $html = "";
    if (!empty($comkeyword)) {
        foreach ($comkeyword as $key => $value) {
            $html.='<div class="input_checkbox"><span keyword_id="' . $value["id"] . '" class="del">' . $value["comkeyword"] . '</span></div>';
        }
    }
    $html.='<div class="input_checkbox_add"><span>���</span></div>';
    exit($html);
} elseif ($act == "del_shield_company") {
    $pid = intval($_POST['pid']) ? intval($_POST['pid']) : exit("-1");
    $uid = intval($_SESSION['uid']) ? intval($_SESSION['uid']) : exit("-1");
    $keyword_id = intval($_POST['keyword_id']) ? intval($_POST['keyword_id']) : exit("-1");
    if (del_shield_company($uid, $pid, $keyword_id)) {
        exit("1");
    } else {
        exit("-1");
    }
} elseif ($act == "tpl") {
    $pid = intval($_GET['pid']);
    $uid = intval($_SESSION['uid']);
    $resume_basic = get_resume_basic($uid, $pid);
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_personal/ajax_tpl.htm";
    $resumetpl = get_resumetpl();
    $resume_url = url_rewrite("QS_resumeshow", array("id" => $pid));
    if ($resume_basic['tpl'] == "") {
        $resume_basic['tpl'] = $_CFG['tpl_personal'];
    }
    $html = "";
    if (!empty($resumetpl)) {
        foreach ($resumetpl as $key => $value) {
            if ($resume_basic['tpl'] == $value['tpl_dir']) {
                $html.='<div class="resume_box">
						<div class="img"><img src="' . $_CFG["site_dir"] . 'templates/tpl_resume/' . $value["tpl_dir"] . '/thumbnail.jpg" alt="" /></div>
						<p><label><input type="radio" checked="checked" name="resume_tpl" class="set_tpl" value="' . $value["tpl_dir"] . '" />' . $value["tpl_name"] . '</label><a target="_blank" href="' . $resume_url . '&style=' . $value["tpl_dir"] . '">[Ԥ��]</a></p>
					</div>';
            } else {
                $html.='<div class="resume_box">
						<div class="img"><img src="' . $_CFG["site_dir"] . 'templates/tpl_resume/' . $value["tpl_dir"] . '/thumbnail.jpg" alt="" /></div>
						<p><label><input type="radio" name="resume" name="resume_tpl" class="set_tpl" value="' . $value["tpl_dir"] . '" />' . $value["tpl_name"] . '</label><a target="_blank" href="' . $resume_url . '&style=' . $value["tpl_dir"] . '">[Ԥ��]</a></p>
					</div>';
            }
        }
    }
    $contents = file_get_contents($tpl);
    $contents = str_replace('{#$resumeid#}', $pid, $contents);
    $contents = str_replace('{#$resume_tpl#}', $resume_basic['tpl'], $contents);
    $contents = str_replace('{#$title#}', $resume_basic['title'], $contents);
    $contents = str_replace('{#tpl_list#}', $html, $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "save_tpl") {
    $wheresqlarr['id'] = intval($_POST['tpl_pid']) ? intval($_POST['tpl_pid']) : exit("-1");
    $wheresqlarr['uid'] = intval($_SESSION['uid']) ? intval($_SESSION['uid']) : exit("-1");
    $setsqlarr['tpl'] = trim($_POST['tpl_dir']) ? trim($_POST['tpl_dir']) : exit("-1");
    updatetable(table('resume'), $setsqlarr, $wheresqlarr);
    exit("1");
} elseif ($act == "ajax_check_resume") {
    $resume_info = get_resume_basic($_SESSION['uid']);
    if (!$resume_info) {
        exit("2");
    } else {
        exit("1");
    }
} elseif ($act == "del_resume") {
    $pid = intval($_GET['pid']) ? intval($_GET['pid']) : exit("��û��ѡ�������");
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_personal/ajax_delete_resume_box.htm";
    $contents = file_get_contents($tpl);
    $contents = str_replace('{#$resumeid#}', $pid, $contents);
    exit($contents);
} elseif ($act == "refresh_resume") {
    $resumeid = intval($_GET['id']) ? intval($_GET['id']) : exit("��û��ѡ�������");
    $refrestime = get_last_refresh_date($_SESSION['uid'], "2001");
    $duringtime = time() - $refrestime['max(addtime)'];
    $space = $_CFG['per_refresh_resume_space'] * 60;
    $refresh_time = get_today_refresh_times($_SESSION['uid'], "2001");
    if ($_CFG['per_refresh_resume_time'] != 0 && ($refresh_time['count(*)'] >= $_CFG['per_refresh_resume_time'])) {
        exit("ÿ�����ֻ��ˢ��" . $_CFG['per_refresh_resume_time'] . "��,�������ѳ������ˢ�´������ƣ�");
    } elseif ($duringtime <= $space) {
        exit($_CFG['per_refresh_resume_space'] . "�����ڲ����ظ�ˢ�¼�����");
    } else {
        refresh_resume($resumeid, $_SESSION['uid']) ? exit("1") : exit('����ʧ�ܣ�');
    }
} elseif ($act == "del_resume_edu") {
    $pid = intval($_GET['pid']) ? intval($_GET['pid']) : exit("��û��ѡ�������");
    $id = intval($_GET['id']) ? intval($_GET['id']) : exit("��û��ѡ�����������");
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_personal/ajax_delete_resume_edu_box.htm";
    $contents = file_get_contents($tpl);
    $contents = str_replace('{#$resumeid#}', $pid, $contents);
    $contents = str_replace('{#$id#}', $id, $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "del_resume_work") {
    $pid = intval($_GET['pid']) ? intval($_GET['pid']) : exit("��û��ѡ�������");
    $id = intval($_GET['id']) ? intval($_GET['id']) : exit("��û��ѡ����������");
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_personal/ajax_delete_resume_work_box.htm";
    $contents = file_get_contents($tpl);
    $contents = str_replace('{#$resumeid#}', $pid, $contents);
    $contents = str_replace('{#$id#}', $id, $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "del_resume_training") {
    $pid = intval($_GET['pid']) ? intval($_GET['pid']) : exit("��û��ѡ�������");
    $id = intval($_GET['id']) ? intval($_GET['id']) : exit("��û��ѡ����ѵ������");
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_personal/ajax_delete_resume_training_box.htm";
    $contents = file_get_contents($tpl);
    $contents = str_replace('{#$resumeid#}', $pid, $contents);
    $contents = str_replace('{#$id#}', $id, $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
}
// ������������
elseif ($act == "interview_detail") {
    global $db;
    $did = $_GET['did'] ? intval($_GET['did']) : exit('ID��ʧ��');
    $interview_info = $db->getone("SELECT * FROM " . table('company_interview') . " WHERE did=" . $did . " LIMIT 1 ");
    if ($interview_info) {
        if (empty($interview_info['notes'])) {
            $interview_info['notes'] = '��֪ͨ���ݣ�';
        }
        updatetable(table('company_interview'),array('personal_look'=>2), " did=" . $did);
        $htm = '<div class="interview-dialog dialog-block">
					<div class="dialog-item clearfix">
						<div class="d-type f-left">���������</div>
						<div class="d-content f-left">' . $interview_info['resume_name'] . '</div>
					</div>
					<div class="dialog-item clearfix">
						<div class="d-type f-left">����ְλ��</div>
						<div class="data-filter d-content f-left">
							<div class="dropdown">' . $interview_info['jobs_name'] . '</div>
				            <input type="hidden" name="jobsid" value="" id="jobsid">
						</div>
					</div>
					<div class="dialog-item clearfix">
						<div class="d-type f-left">�������ڣ�</div>
						<div class="d-content f-left">' . date('Y-m-d', $interview_info['interview_addtime']) . '</div>
					</div>
					<div class="dialog-item clearfix">
						<div class="d-type f-left">֪ͨ���ݣ�</div>
						<div class="d-content f-left">' . $interview_info['notes'] . '</div>
					</div>
				</div>';
        exit($htm);
    } else {
        exit('�޴����ݣ�');
    }
}
unset($smarty);
?>