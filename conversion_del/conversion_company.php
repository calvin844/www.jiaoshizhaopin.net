<?php

/*
 * 74cms ��վ��ҳ
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
set_time_limit(0);
define('IN_QISHI', true);
require_once('../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$locoyspider = get_cache('locoyspider');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$t1 = exectime();
require_once('conversion_cngfig.php');
$size = 1000;
$id = intval($_GET['id']);

$sql = "select top " . $size . " * from pH_Company_Base where NCID >" . $id . " order by NCID asc ";
$rs = ms_getall($sql);
;   //ִ���������

foreach ($rs as $rs) {
    $thisid = $rs['NCID'];
    if ($rs['CompanyName']) {
        //var_dump($rs['CompanyName']);exit;
        //��ѯ��˾��ϸ��Ϣ�Ƿ��Ѵ���
        $sql = "select id from " . table('company_profile') . " where id=" . intval($rs['NCID']) . " LIMIT 1";
        $companyinfo = $db->getone($sql);
        $userinfo = get_user_inusername(escape_str($rs['UserName']));
        if (empty($userinfo)) {
            continue;
        } elseif (!empty($companyinfo)) {
            del_company($id, $userinfo['uid']);
        }
        $setsqlarr['id'] = intval($rs['NCID']);
        $setsqlarr['uid'] = intval($userinfo['uid']);
        $setsqlarr['companyname'] = escape_str($rs['CompanyName']);


        $oldnature = get_oldnature($rs['Properity']);
        $nature = get_company_nature($oldnature);
        $setsqlarr['nature'] = $nature['id'];
        $setsqlarr['nature_cn'] = $nature['cn'];
        //var_dump($setsqlarr);exit;

        $setsqlarr['trade'] = 33;
        $setsqlarr['trade_cn'] = "����/��ѵ";


        $cname = escape_str($rs['Locus_City']);
        $aname = escape_str($rs['Locus_Area']);
        if (empty($cname) || $cname == "����" || $cname == "����" || $cname == "����") {
            $cname = match_other_city($setsqlarr['id'], $setsqlarr['companyname'], $aname);
            if (!$cname) {
                continue;
            }
        }
        $caty = get_company_city_arr(trim($cname), trim($aname));
        $setsqlarr['district'] = $caty['district'];
        $setsqlarr['sdistrict'] = $caty['sdistrict'];
        $setsqlarr['district_cn'] = $caty['district_cn'];



        $scale = get_company_scale($rs['Workers']);
        $setsqlarr['scale'] = $scale[0];
        $setsqlarr['scale_cn'] = $scale[1];



        $setsqlarr['registered'] = intval($rs['Reg_Currency']);
        $setsqlarr['currency'] = "�����";
        $setsqlarr['yellowpages'] = 1;

        $setsqlarr['address'] = escape_str($rs['Address']);
        $setsqlarr['contact'] = escape_str($rs['ContactPerson']);
        $setsqlarr['license'] = escape_str($rs['Licence']);
        $setsqlarr['click'] = $rs['ViewClicks'];
        $setsqlarr['logo'] = escape_str($rs['LogoUrl']);



        $setsqlarr['telephone'] = $rs['Phone'];
        $setsqlarr['email'] = escape_str($rs['Email']);
        if (!strstr($rs['WebHome'], "http://")) {
            $WebHome = "http://" . $rs['WebHome'];
        } else {
            $WebHome = $rs['WebHome'];
        }
        $setsqlarr['website'] = $WebHome;
        $setsqlarr['contents'] = strip_tags(escape_str($rs['Company_Memo']));
        //$t=$rs['RegDate'];
        //$t=explode(' ',$t);
        $setsqlarr['addtime'] = strtotime($rs['RegDate']);

        //$t=$rs['LastDate'];
        //$t=explode(' ',$t);
        $setsqlarr['refreshtime'] = strtotime($rs['LastDate']);
        //�޸�
        $setsqlarr['audit'] = ($rs['Flag'] == 1) ? 1 : 2;

        //������ӹ�˾VIP��Ϣ_Calvin_20140630
        $vip_level = $rs['Nc_Vip_Level'];
        $vip_info = get_vip($vip_level, $setsqlarr['uid'], $rs['Nc_Vip_Date'], $rs['Nc_Vip_EndDate'], $rs['Nc_Vip_Clicks']);

        if (!empty($companyinfo)) {
            $wheresqlarr = " id = " . intval($rs['NCID']);
            updatetable(table('company_profile'), $setsqlarr, $wheresqlarr);
            echo intval($rs['NCID']) . " �������<br/>";
        } else {
            inserttable(table('company_profile'), $setsqlarr);
            echo intval($rs['NCID']) . " �������<br/>";
        }
    }
}
//exit;
if (empty($thisid)) {
    $url_in_arr = array("url" => "conversion_jobs.php", "addtime" => strtotime("now"));
    $cons_id = inserttable(table('cons'), $url_in_arr, TRUE);
    if (!empty($cons_id)) {
        echo '������ҵ�����Ѿ�ȫ��ת����ɣ�';
        //echo '<script type="text/javascript" language="javascript">window.location.href="conversion_jobs.php";</script> ';
    }
    exit("������ҵ�����Ѿ�ȫ��ת����ɣ�");
}

$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$html.='<html xmlns="http://www.w3.org/1999/xhtml">';
$html.='<head>';
$html.='<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
$html.='<title>��ҵ����</title>';
$html.='</head>';
$html.='<body>';
$m = $m - 1;
$t = intval($_GET['t']) + $m;
$html.='�ɹ�ת��' . $size . '����ҵ����(�ܼ�ת����' . $t . '��)���벻Ҫ�رջ��뿪ҳ�棬3��������' . $size . '����ҵ���ϵ�ת����id���(' . $thisid . ')';
$html.='<script type="text/javascript" language="javascript">';
$html.='function reloadyemian()';
$html.='{ ';
$html.='window.location.href="conversion_company.php?t=' . $t . '&id=' . $thisid . '"; ';
$html.='} ';
$html.='window.setTimeout("reloadyemian();",3000); ';
$html.='</script> ';
$html.='</body></html>';
$url_in_arr = array("url" => "conversion_company.php?t=" . $t . "&id=" . $thisid, "addtime" => strtotime("now"));
$cons_id = inserttable(table('cons'), $url_in_arr, TRUE);
echo $html;


$t2 = exectime();
$t0 = $t2 - $t1;
$m = $m - 1;
echo "��ҵ����ת����ɣ���ʱ�� $t0  �ܼ�$m<br><br>";

function match_other_city($id, $title, $aname) {
    //echo $title."<br>";
    global $db;
    $aname = trim($aname);
    $tmp_str = substr($aname, -2);
    if ($tmp_str == "��" || $tmp_str == "ʡ" || $tmp_str == "��") {
        if (!strstr($aname, "����" . $tmp_str)) {
            $tmp_name = substr($aname, 0, strlen($aname) - 2);
            if (strlen($tmp_name) > 3) {
                $aname = $tmp_name;
            }
        }
    }
    $sql = "select id,parentid,categoryname from " . table('category_district') . " where parentid > 0";
    $info = $db->getall($sql);
    foreach ($info as $i) {
        //echo $i['categoryname']."-<br>";
        if (strstr($title, $i['categoryname'])) {
            $return = $i;
            break;
        }
    }
    if (!$return) {
        //echo $aname."<br>";
        if (empty($aname)) {
            $error_in_arr['pid'] = $id;
            $error_in_arr['type'] = 'company';
            $error_in_arr['addtime'] = time();
            $sql = "select * from " . table('91_city_error') . " where pid = " . $error_in_arr['pid'] . " and type = '" . $error_in_arr['type'] . "' limit 1";
            $has = $db->getone($sql);
            if (!empty($has['id'])) {
                $wheresqlarr = " id =" . $has['id'];
                updatetable(table('91_city_error'), $error_in_arr, $wheresqlarr);
            } else {
                inserttable(table('91_city_error'), $error_in_arr);
            }
            return FALSE;
        }
        $return['categoryname'] = $aname;
    }
    //var_dump($return['categoryname']);
    return $return['categoryname'];
}

//��Ӳ��乫˾������Ϣ��¼_Calvin_20140618
function get_company_city_arr($cname, $aname) {
    global $db;
    $cname = trim($cname);
    $aname = trim($aname);
    $tmp_str = substr($cname, -2);
    if ($tmp_str == "��" || $tmp_str == "ʡ" || $tmp_str == "��") {
        $tmp_name = substr($cname, 0, strlen($cname) - 2);
        if (strlen($tmp_name) > 3) {
            $cname = $tmp_name;
        }
    }
    //var_dump($str);
    $tmp_str = substr($aname, -2);
    if ($tmp_str == "��" || $tmp_str == "ʡ" || $tmp_str == "��") {
        $tmp_name = substr($aname, 0, strlen($aname) - 2);
        if (strlen($tmp_name) > 3) {
            $aname = $tmp_name;
        }
    }
    if ($cname == "����" || $cname == "����" || $cname == "����") {
        $cname = $aname;
    }
    if (!empty($cname)) {
        $sql = "select id,parentid,categoryname from " . table('category_district');
        $info = $db->getall($sql);
        $return = locoyspider_search_str($info, $cname, "categoryname", 60);
        if (!$return) {
            //�����������û����Ӧ���ݣ��Ե��������ݽ��в���
            $sql = "select id,categoryname from " . table('category_district') . " where parentid = 0";
            $info_a = $db->getall($sql);
            $return_a = locoyspider_search_str($info_a, $aname, "categoryname", 60);
            if (!$return_a) {
                $add_area = array("parentid" => 0, "categoryname" => $aname);
                $return_a['id'] = inserttable(table('category_district'), $add_area, true);
                access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_city_pinyin.php?id=" . $return_a['id']);
                if ($cname == $aname) {
                    $return['id'] = $return_a['id'];
                    $return['parentid'] = 0;
                    $return['categoryname'] = $aname;
                } else {
                    $add_city = array("parentid" => $return_a['id'], "categoryname" => $cname);
                    $c_id = inserttable(table('category_district'), $add_city, true);
                    access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_city_pinyin.php?id=" . $c_id);
                    $return['id'] = $c_id;
                    $return['parentid'] = $return_a['id'];
                    $return['categoryname'] = $cname;
                }
            } else {
                $add_city = array("parentid" => $return_a['id'], "categoryname" => $cname);
                $c_id = inserttable(table('category_district'), $add_city, true);
                access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_city_pinyin.php?id=" . $c_id);
                $return['parentid'] = $return_a['id'];
                $return['id'] = $c_id;
                $return['categoryname'] = $cname;
            }
        }
    }
    return array("district" => $return['parentid'], "sdistrict" => $return['id'], "district_cn" => $return['categoryname']);
}

function get_oldnature($str) {
    switch ($str) {
        case "1":
            return "������ҵ";
        case "2":
            return "�����ҵ";
        case "3":
            return "�����ҵ";
        case "4":
            return "������ҵ";
        case "5":
            return "�������";
        case "6":
            return "����";
        case "7":
            return "����/��Ӫ����֯";
        case "8":
            return "������ҵ";
        default:
            return "�����ҵ";
    }
}

//��Ӳ��乫˾������Ϣ��¼_Calvin_20140620
function get_company_nature($str = NULL) {
    global $db, $locoyspider;
    $sql = "select c_id,c_name from " . table('category') . " where c_alias='QS_company_type'  and c_id=" . intval($locoyspider['company_nature']) . " LIMIT 1";
    $info = $db->getone($sql);
    $default = array("id" => $info['c_id'], "cn" => $info['c_name']);
    if (empty($str)) {
        return $default;
    } else {
        $sql = "select c_id,c_name from " . table('category') . "  where c_alias='QS_company_type' and c_name ='" . $str . "'";
        $info = $db->getone($sql);
        if ($info) {
            return array("id" => $info['c_id'], "cn" => $info['c_name']);
        } else {
            //���û�ж�Ӧ�Ĺ�˾���ʣ������ʱ�������ݲ���
            $cn_arr = array('c_name' => $str, 'c_alias' => 'QS_company_type');
            $cn_id = inserttable(table('category'), $cn_arr, true);
            return array("id" => $cn_id, "cn" => $str);
        }
    }
}

function get_company_scale($str) {
    global $db, $locoyspider;
    switch ($str) {
        case "9": return array(80, '20������');
        case "49": return array(81, '20-99��');
        case "199": return array(82, '100-499��');
        case "499": return array(82, '100-499��');
        case "999": return array(83, '500-999��');
        case "1000": return array(84, '1000-9999��');
    }
}

function get_vip($id, $uid, $date, $enddate, $click) {

    global $db;
    $start = strtotime($date);
    $end = strtotime($enddate);

    if ($start) {
        $vip_arr = get_level($id);
        $sql = "select id from " . table("setmeal") . " where setmeal_name = '" . $vip_arr['setmeal_name'] . "' limit 1";
        $vip_result = $db->getone($sql);
        if (empty($vip_result['id'])) {
            $vip_result['id'] = inserttable(table("setmeal"), $vip_arr, true);
        }
        $vip_arr['effective'] = 1;
        $vip_arr['setmeal_id'] = $vip_result['id'];
        $vip_arr['starttime'] = $start;
        $vip_arr['endtime'] = $end;
    }
    $vip_arr['uid'] = $uid;
    $sql = "select * from " . table("members_setmeal") . " where uid = '" . $vip_arr['uid'] . "' limit 1";
    $member_result = $db->getone($sql);
    if (empty($member_result['id'])) {
        inserttable(table("members_setmeal"), $vip_arr);
    } else {
        $wheresqlarr = " id =" . $member_result['id'];
        updatetable(table("members_setmeal"), $vip_arr, $wheresqlarr);
    }
    $sql = "select * from " . table("members_points") . " where uid = '" . $vip_arr['uid'] . "' limit 1";
    $points_result = $db->getone($sql);

    if ($start && $end && $end > time()) {
        $points = floor(($click / $vip_arr['days']) * (($end - time()) / 86400)) * 6;
    } else {
        $points = 0;
    }
    if (empty($points_result['id'])) {
        inserttable(table("members_points"), array("uid" => $vip_arr['uid'], "points" => $points));
    } else {
        $wheresqlarr = " uid =" . $vip_arr['uid'];
        updatetable(table("members_points"), array("points" => $points), $wheresqlarr);
    }
}

function get_level($id) {
    switch ($id) {
        case 0:
            //setmeal_name:����,days:����,expense:��Ǯ,jobs_ordinary:����ְλ,download_resume_ordinary:������ͨ����,download_resume_senior:���ظ߼�����,interview_senior:��������,talent_pool:�˲ſ�
            return array("setmeal_name" => "����û�", "days" => "0", "expense" => "0", "jobs_ordinary" => "5", "download_resume_ordinary" => "0", "download_resume_senior" => "0", "interview_ordinary" => "0", "interview_senior" => "0", "talent_pool" => "0");
        case 1:
            return array("setmeal_name" => "�����û�", "days" => "30", "expense" => "0", "jobs_ordinary" => "10", "download_resume_ordinary" => "3", "download_resume_senior" => "3", "interview_ordinary" => "3", "interview_senior" => "3", "talent_pool" => "3000");
        case 2:
            return array("setmeal_name" => "�¶�VIP", "days" => "30", "expense" => "500", "jobs_ordinary" => "15", "download_resume_ordinary" => "80", "download_resume_senior" => "80", "interview_ordinary" => "80", "interview_senior" => "80", "talent_pool" => "9000");
        case 3:
            return array("setmeal_name" => "����VIP", "days" => "90", "expense" => "1000", "jobs_ordinary" => "100", "download_resume_ordinary" => "300", "download_resume_senior" => "300", "interview_ordinary" => "300", "interview_senior" => "300", "talent_pool" => "18000");
        case 4:
            return array("setmeal_name" => "�����VIP", "days" => "180", "expense" => "1600", "jobs_ordinary" => "500", "download_resume_ordinary" => "800", "download_resume_senior" => "800", "interview_ordinary" => "800", "interview_senior" => "800", "talent_pool" => "36000");
        case 5:
            return array("setmeal_name" => "���VIP", "days" => "365", "expense" => "2400", "jobs_ordinary" => "1000", "download_resume_ordinary" => "1500", "download_resume_senior" => "1500", "interview_ordinary" => "1500", "interview_senior" => "1500", "talent_pool" => "72000");
        case 6:
            return array("setmeal_name" => "�¶ȸ߼�VIP", "days" => "30", "expense" => "580", "jobs_ordinary" => "15", "download_resume_ordinary" => "100", "download_resume_senior" => "100", "interview_ordinary" => "100", "interview_senior" => "100", "talent_pool" => "9000");
        case 7:
            return array("setmeal_name" => "���ȸ߼�VIP", "days" => "90", "expense" => "1680", "jobs_ordinary" => "100", "download_resume_ordinary" => "500", "download_resume_senior" => "500", "interview_ordinary" => "500", "interview_senior" => "500", "talent_pool" => "18000");
        case 8:
            return array("setmeal_name" => "����ȸ߼�VIP", "days" => "180", "expense" => "2280", "jobs_ordinary" => "500", "download_resume_ordinary" => "1000", "download_resume_senior" => "1000", "interview_ordinary" => "1000", "interview_senior" => "1000", "talent_pool" => "36000");
        case 9:
            return array("setmeal_name" => "��ȸ߼�VIP", "days" => "365", "expense" => "3400", "jobs_ordinary" => "1000", "download_resume_ordinary" => "2000", "download_resume_senior" => "2000", "interview_ordinary" => "2000", "interview_senior" => "2000", "talent_pool" => "72000");
        default:
            return array("setmeal_name" => "����û�", "days" => "0", "expense" => "0", "jobs_ordinary" => "5", "download_resume_ordinary" => "0", "download_resume_senior" => "0", "interview_ordinary" => "0", "interview_senior" => "0", "talent_pool" => "0");
    }
}

function convert_datefm1($date, $format, $separator = "-") {
    if ($format == "1") {
        return date("Y-m-d", $date);
    } else {
        if (!preg_match("/^[0-9]{4}(\\" . $separator . ")[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/", $date))
            return $date;
        $date = explode($separator, $date);
        return mktime(0, 0, 0, $date[1], $date[2], $date[0]);
    }
}

function mysql_escape_str_deep($value) {
    if (empty($value)) {
        return $value;
    } else {
        return $value = is_array($value) ? array_map('mysql_escape_str_deep', $value) : escape_str($value);
    }
}

function del_company($id, $uid) {
    global $db;
    $db->query("Delete from " . table('company_profile') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from " . table('members_points') . " WHERE uid=" . $uid);
    $db->query("Delete from " . table('members_setmeal') . " WHERE uid=" . $uid);
    /*
      $sql = "select id from " . table('jobs') . " WHERE company_id=" . $id;
      $company_jobs = $db->getall($sql);
      if (!empty($company_jobs)) {
      foreach ($company_jobs as $k) {
      del_job($k[id]);
      }
      }
     * 
     */
}

function del_job($id) {
    $db->query("Delete from " . table('jobs') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id='" . $id . "' and type = 'jobs'");
    $db->query("Delete from " . table('jobs_tmp') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_hot') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_key') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_rtime') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_scale') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_stickrtime') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_wage') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_tag') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from " . table('personal_jobs_apply') . " WHERE jobs_id='" . $id . "'");
}

?>