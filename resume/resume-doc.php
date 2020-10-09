<?php

/*
 * 74cms 生成word
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
unset($dbhost, $dbuser, $dbpass, $dbname);
$uid = intval($_GET['uid']); //简历所属会员的uid
$id = intval($_GET['resume_id']);
if ($_SESSION['uid'] == '' || $_SESSION['username'] == '') {
    $resume_url = url_rewrite('QS_resumeshow', array('id' => $id));
    header("Location:" . url_rewrite('QS_login') . "?url={$resume_url}");
    exit();
}
if (!(($_SESSION['utype'] == '2' && $_SESSION['uid'] == $uid) || $_SESSION['utype'] == '1')) {
    showmsg('您没有权限！只有个人用户和企业用户可以转换简历', 1);
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
    } elseif ($_CFG['showresumecontact'] == '2') {//联系方式：会员下载后可见
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
                                    <td align="left" colspan="4">教育经历</td>
                            </tr>
                            <tr class="small_title">
                                    <td align="left">起止年月</td>
                                    <td align="left">学校名称</td>
                                    <td align="left">专业名称</td>
                                    <td align="left">获得学历</td>
                            </tr>';
    if ($val['education_list']) {
        foreach ($val['education_list'] as $eli) {
            $education_html.='<tr class="data_list">
                    <td align="left">' . $eli['startyear'] . '.' . $eli['startmonth'] . '至' . $eli['endyear'] . '.' . $eli['endmonth'] . '</td>
                    <td align="left">' . $eli['school'] . '</td>
                    <td align="left">' . $eli['speciality'] . '</td>
                    <td align="left">' . $eli['education_cn'] . '</td>
              </tr>';
        }
    } else {
        $education_html.='<tr class="data_list"><td>没有填写教育经历</td></tr>';
    }
    $education_html.='</table>';
    $htm = str_replace("{education_html}", $education_html, $htm);

    $work_html = '<table class="work_table" cellpadding="0" cellspacing="0">
    <tr class="big_title">
            <td align="left">工作经历</td>
    </tr>';
    if ($val['work_list']) {
        foreach ($val['work_list'] as $wli) {
            $work_html.='<tr class="data_list">
		<td align="left">
		起止年月：' . $wli['startyear'] . '.' . $wli['startmonth'] . '至' . $wli['endyear'] . '.' . $wli['endmonth'] . '<br />
		企业名称：' . $wli['companyname'] . '<br />
		从事职位：' . $wli['jobs'];
            if ($wli['achievements']) {
                $work_html.='<br />业绩表现：' . $wli['achievements'];
            }
            if ($wli['companyprofile']) {
                $work_html.='<br />公司介绍：' . $wli['companyprofile'];
            }

            $work_html.='</td>
	  </tr>';
        }
    } else {
        $work_html.='<tr class="data_list"><td>没有填写工作经历</td></tr>';
    }
    $work_html.='</table>';
    $htm = str_replace("{work_html}", $work_html, $htm);

    $training_html = '<table class="training_table" cellpadding="0" cellspacing="0">
			<tr class="big_title">
				<td align="left">培训经历</td>
			</tr>';
    if ($val['training_list']) {
        foreach ($val['training_list'] as $tli) {
            $training_html.=' <tr class="data_list">
		<td align="left">
		起止日期：' . $tli['startyear'] . '.' . $tli['startmonth'] . '至' . $tli['endyear'] . '.' . $tli['endmonth'] . '<br />
		培训机构：' . $tli['agency'] . '<br />
		培训课程：' . $tli['course'] . '<br />
		培训描述：' . $tli['description'] . '
		</td>
	  </tr>';
        }
    } else {
        $training_html.='<tr class="data_list"><td>没有填写培训经历</td></tr>';
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
    header("Content-Disposition:attachment; filename={$val['fullname']}的个人简历.doc");
    echo $htm;
} else {
    showmsg('简历不存在！', 1);
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