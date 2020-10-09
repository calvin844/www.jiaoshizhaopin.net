<?php

/*
 * 74cms ����word
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
unset($dbhost, $dbuser, $dbpass, $dbname);
$uid = intval($_GET['uid']); //����������Ա��uid
$id = intval($_GET['resume_id']);
if ($_SESSION['uid'] == '' || $_SESSION['username'] == '') {
    $resume_url = url_rewrite('QS_resumeshow', array('id' => $id));
    header("Location:" . url_rewrite('QS_login') . "?url={$resume_url}");
    exit();
}
if (!(($_SESSION['utype'] == '2' && $_SESSION['uid'] == $uid) || $_SESSION['utype'] == '1')) {
    showmsg('��û��Ȩ�ޣ�ֻ�и����û�����ҵ�û�����ת������', 1);
    exit();
}
$wheresql = " WHERE  id='{$id}'  AND uid='{$uid}' ";
$sql = "select * from " . table('resume') . $wheresql . " LIMIT  1";
$val = $db->getone($sql);
if ($val) {
    $val['education_list'] = get_this_education($val['uid'], $val['id']);
    $val['work_list'] = get_this_work($val['uid'], $val['id']);
    $val['training_list'] = get_this_training($val['uid'], $val['id']);
    $val['age'] = date("Y") - $val['birthdate'];
    $val['tagcn'] = preg_replace("/\d+/", '', $val['tag']);
    $val['tagcn'] = preg_replace('/\,/', '', $val['tagcn']);
    $val['tagcn'] = preg_replace('/\|/', '&nbsp;&nbsp;&nbsp;', $val['tagcn']);

    if ($_CFG['showresumecontact'] == '1' || $_CFG['showresumecontact'] == '0') {
        $show = true;
    } elseif ($_CFG['showresumecontact'] == '2') {//��ϵ��ʽ����Ա���غ�ɼ�
        if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '1') {
            $apply = $db->getone("select * from " . table('personal_jobs_apply') . " where `resume_id`=" . $id . " AND company_uid=" . intval($_SESSION['uid']));
            $down = $db->getone("select * from " . table('company_down_resume') . " where `resume_id`=" . $id . " AND company_uid=" . intval($_SESSION['uid']));
            if (empty($apply) && empty($down)) {
                $show = false;
            } else {
                $show = true;
            }
        } elseif ($_SESSION['utype'] == '2' && $_SESSION['uid'] == $uid) {
            $show = true;
        } else {
            $show = false;
        }
    }

    if ($show) {
        $val['fullname_'] = $val['fullname'];
        $val['fullname'] = $val['fullname'];
    } elseif ($val['display_name'] == "2") {
        $val['fullname'] = "N" . str_pad($val['id'], 7, "0", STR_PAD_LEFT);
        $val['fullname_'] = $val['fullname'];
    } elseif ($val['display_name'] == "3") {
        $val['fullname'] = cut_str($val['fullname'], 1, 0, "**");
        $val['fullname_'] = $val['fullname'];
    } else {
        $val['fullname_'] = $val['fullname'];
        $val['fullname'] = $val['fullname'];
    }
    if (intval($_GET['apply']) == 1) {
        $set_apply = 1;
        $apply = $db->getone("select * from " . table('personal_jobs_apply') . " where `resume_id`=" . $val['id']);
        $val['jobs_name'] = $apply['jobs_name'];
    } else {
        $set_apply = 0;
    }


    $tpl_dir = $_CFG['tpl_personal'];
    $utpl = $db->getone("SELECT * FROM " . table("personal_resume_tpl") . " WHERE resume_id='" . $val['id'] . "' AND endtime>" . time() . " order by id desc limit 1");
    if (!empty($utpl)) {
        $tpl = $db->getone("SELECT * FROM " . table("tpl") . " WHERE tpl_id=" . $utpl['tpl_id'] . " AND tpl_type =2 AND tpl_display=1");
        $tpl_dir = !empty($tpl['tpl_dir']) ? $tpl['tpl_dir'] : $_CFG['tpl_personal'];
    }

    $htm = file_get_contents(QISHI_ROOT_PATH . 'templates/tpl_resume/' . $tpl_dir . '/doc.html');
    $val['photo_img'] = empty($val['photo_img']) ? "no_photo.gif" : $val['photo_img'];
    $htm = str_replace("{fullname}", $val['fullname'], $htm);
    $htm = str_replace("{photo_img}", "http://www.jiaoshizhaopin.net/data/photo/" . $val['photo_img'], $htm);
    $htm = str_replace("{sex_cn}", $val['sex_cn'], $htm);
    $htm = str_replace("{age}", $val['age'], $htm);
    $htm = str_replace("{marriage_cn}", $val['marriage_cn'], $htm);
    $htm = str_replace("{education_cn}", $val['education_cn'], $htm);
    $htm = str_replace("{experience_cn}", $val['experience_cn'], $htm);
    $htm = str_replace("{residence_cn}", $val['residence_cn'], $htm);
    $htm = str_replace("{nature_cn}", $val['nature_cn'], $htm);
    $htm = str_replace("{district_cn}", $val['district_cn'], $htm);
    $htm = str_replace("{wage_cn}", $val['wage_cn'], $htm);
    $htm = str_replace("{intention_jobs}", $val['intention_jobs'], $htm);
    $htm = str_replace("{trade_cn}", $val['trade_cn'], $htm);
    $htm = str_replace("{tagcn}", nl2br($val['tagcn']), $htm);
    $htm = str_replace("{specialty}", nl2br($val['specialty']), $htm);

    $education_html = '<table class="education_table" cellpadding="0" cellspacing="0">
                            <tr class="big_title">
                                    <td align="left" colspan="4">��������</td>
                            </tr>
                            <tr class="small_title">
                                    <td align="left">��ֹ����</td>
                                    <td align="left">ѧУ����</td>
                                    <td align="left">רҵ����</td>
                                    <td align="left">���ѧ��</td>
                            </tr>';
    if ($val['education_list']) {
        foreach ($val['education_list'] as $eli) {
            $education_html.='<tr class="data_list">
                    <td align="left">' . $eli['startyear'] . '.' . $eli['startmonth'] . '��' . $eli['endyear'] . '.' . $eli['endmonth'] . '</td>
                    <td align="left">' . $eli['school'] . '</td>
                    <td align="left">' . $eli['speciality'] . '</td>
                    <td align="left">' . $eli['education_cn'] . '</td>
              </tr>';
        }
    } else {
        $education_html.='<tr class="data_list"><td>û����д��������</td></tr>';
    }
    $education_html.='</table>';
    $htm = str_replace("{education_html}", $education_html, $htm);

    $work_html = '<table class="work_table" cellpadding="0" cellspacing="0">
    <tr class="big_title">
            <td align="left">��������</td>
    </tr>';
    if ($val['work_list']) {
        foreach ($val['work_list'] as $wli) {
            $work_html.='<tr class="data_list">
		<td align="left">
		��ֹ���£�' . $wli['startyear'] . '.' . $wli['startmonth'] . '��' . $wli['endyear'] . '.' . $wli['endmonth'] . '<br />
		��ҵ���ƣ�' . $wli['companyname'] . '<br />
		����ְλ��' . $wli['jobs'];
            if ($wli['achievements']) {
                $work_html.='<br />ҵ�����֣�' . $wli['achievements'];
            }
            if ($wli['companyprofile']) {
                $work_html.='<br />��˾���ܣ�' . $wli['companyprofile'];
            }

            $work_html.='</td>
	  </tr>';
        }
    } else {
        $work_html.='<tr class="data_list"><td>û����д��������</td></tr>';
    }
    $work_html.='</table>';
    $htm = str_replace("{work_html}", $work_html, $htm);

    $training_html = '<table class="training_table" cellpadding="0" cellspacing="0">
			<tr class="big_title">
				<td align="left">��ѵ����</td>
			</tr>';
    if ($val['training_list']) {
        foreach ($val['training_list'] as $tli) {
            $training_html.=' <tr class="data_list">
		<td align="left">
		��ֹ���ڣ�' . $tli['startyear'] . '.' . $tli['startmonth'] . '��' . $tli['endyear'] . '.' . $tli['endmonth'] . '<br />
		��ѵ������' . $tli['agency'] . '<br />
		��ѵ�γ̣�' . $tli['course'] . '<br />
		��ѵ������' . $tli['description'] . '
		</td>
	  </tr>';
        }
    } else {
        $training_html.='<tr class="data_list"><td>û����д��ѵ����</td></tr>';
    }
    $training_html.='</table>';
    $htm = str_replace("{training_html}", $training_html, $htm);

    $htm = str_replace("{telephone}", $val['telephone'], $htm);
    $htm = str_replace("{email}", $val['email'], $htm);
    $htm = str_replace("{site_name}", $_CFG['site_name'], $htm);
    $htm = str_replace("{main_domain}", $_CFG['main_domain'], $htm);
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-Type: application/doc");
    header("Content-Disposition:attachment; filename={$val['fullname']}�ĸ��˼���.doc");
    echo $htm;
} else {
    showmsg('���������ڣ�', 1);
    exit();
}

function get_this_education($uid, $pid) {
    global $db;
    $sql = "SELECT * FROM " . table('resume_education') . " WHERE uid='" . intval($uid) . "' AND pid='" . intval($pid) . "' ";
    return $db->getall($sql);
}

function get_this_work($uid, $pid) {
    global $db;
    $sql = "select * from " . table('resume_work') . " where uid=" . intval($uid) . " AND pid='" . $pid . "' ";
    return $db->getall($sql);
}

function get_this_training($uid, $pid) {
    global $db;
    $sql = "select * from " . table('resume_training') . " where uid='" . intval($uid) . "' AND pid='" . intval($pid) . "'";
    return $db->getall($sql);
}

function get_user_setmealt($uid) {
    global $db;
    $sql = "select * from " . table('members_setmeal') . "  WHERE uid=" . intval($uid) . " AND  effective=1 LIMIT 1";
    return $db->getone($sql);
}

?>