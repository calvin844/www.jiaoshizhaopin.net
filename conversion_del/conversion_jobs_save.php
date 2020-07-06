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
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$t1 = exectime();
require_once('conversion_cngfig.php');
$locoyspider = get_cache('locoyspider');
$id = intval($_GET['id']);
$del = intval($_GET['del']);
if ($del == 1) {
    del_jobs($id);
    del_jobs_app($id);
    exit;
}

$sql = "select  top 1 *  from pH_Job_Base where JobId = " . $id;
$rs = ms_getone($sql);   //ִ���������

require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
$sp = new SPWord();

$thisid = $rs['JobId'];
if ($rs['JobName']) {
    $userinfo_rs = ms_getone("select TOP 1 UserName,NCID from pH_Company_Base WHERE Comid='" . escape_str($rs['Comid']) . "'");
    if (empty($userinfo_rs)) {
        echo $thisid . " ��˾��������!";
        exit;
    } else {
        $userinfo = get_user_inusername(escape_str($userinfo_rs['UserName']));
        if (!$userinfo) {
            access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_user_company_save.php?id=" . $userinfo_rs['NCID']);
            $userinfo = get_user_inusername(escape_str($userinfo_rs['UserName']));
        }
        $company_profile = get_company($userinfo['uid']);
        if (empty($company_profile['id'])) {
            access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_company_save.php?id=" . $userinfo_rs['NCID']);
            $company_profile = get_company($userinfo['uid']);
        }
    }
    $sql = "select id,addtime from " . table('jobs') . " where id=" . intval($rs['JobId']);
    $jobinfo = $db->getone($sql);
    $sql = "select id,addtime from " . table('jobs_tmp') . " where id=" . intval($rs['JobId']);
    $jobtmpinfo = $db->getone($sql);
    //����¼�Ѵ���ʱ�����µ���_Calvin_20140624
    if (!empty($jobinfo['id']) || !empty($jobtmpinfo['id'])) {
        del_jobs($thisid);
        del_jobs_app($thisid);
    }
    $setsqlarr['id'] = intval($rs['JobId']);
    $setsqlarr['add_mode'] = 1;
    $setsqlarr['uid'] = $userinfo['uid'];
    $setsqlarr['jobs_name'] = escape_str($rs['JobName']);
    $setsqlarr['companyname'] = mysql_escape_string(trim($company_profile['companyname']));
    $setsqlarr['company_id'] = $company_profile['id'];
    $setsqlarr['company_addtime'] = $company_profile['addtime'];
    $setsqlarr['company_audit'] = $company_profile['audit'];
    $setsqlarr['contents'] = escape_str($rs['Require']);
    $nature = get_jobs_nature($rs['JobType']);
    $setsqlarr['nature'] = $nature[0];
    $setsqlarr['nature_cn'] = $nature[1];
    $gender = get_sex($rs['Sex']);
    $setsqlarr['sex'] = $gender[0];
    $setsqlarr['sex_cn'] = $gender[1];
    $setsqlarr['amount'] = get_jobs_amount($rs['Number']);
    $setsqlarr['emergency'] = $rs['BestFlag'] ? 1 : 0;
    $category = get_jobs_category_arr(escape_str($rs['JobClass']));
    $setsqlarr['category'] = $category['category'];
    $setsqlarr['subclass'] = $category['subclass'];
    $setsqlarr['category_cn'] = $category['category_cn'];
    $setsqlarr['trade'] = $company_profile['trade'];
    $setsqlarr['trade_cn'] = $company_profile['trade_cn'];
    $setsqlarr['scale'] = $company_profile['scale'];
    $setsqlarr['scale_cn'] = $company_profile['scale_cn'];
    //�޸ĵ�������ת����Calvin_20140618
    $cname = escape_str($rs['Work_City']);
    $aname = escape_str($rs['Work_Area']);
    if (empty($cname) || $cname == "����" || $cname == "����" || $cname == "����") {
        $cname = match_other_city($setsqlarr['id'], escape_str($rs['Address']), $aname);
        if (!$cname) {
            exit;
        }
    }
    $caty = get_jobs_city_arr($cname, $aname);
    $setsqlarr['district'] = $caty['district'];
    $setsqlarr['sdistrict'] = $caty['sdistrict'];
    $setsqlarr['district_cn'] = $caty['sdistrict_cn'] == "" ? $caty['district_cn'] : $caty['district_cn'] . "/" . $caty['sdistrict_cn'];
    $setsqlarr['tag'] = "";
    $setsqlarr['street'] = $company_profile['street'];
    $setsqlarr['street_cn'] = $company_profile['street_cn'];
    //$setsqlarr['officebuilding']=$company_profile['officebuilding'];
    //$setsqlarr['officebuilding_cn']=$company_profile['officebuilding_cn'];	
    $education = get_jobs_education($rs['Edus']);
    $setsqlarr['education'] = $education['id'];
    $setsqlarr['education_cn'] = $education['cn'];
    $experience = get_jobs_experience($rs['Works']);
    $setsqlarr['experience'] = $experience['id'];
    $setsqlarr['experience_cn'] = $experience['cn'];
    $wage = $rs['Deal'];
    $wage = explode('.', $wage);
    $wage = $wage[0];
    $wage = get_jobs_wage($wage);
    $setsqlarr['wage'] = $wage['id'];
    $setsqlarr['wage_cn'] = $wage['name'];
    $setsqlarr['graduate'] = $rs['JobType'] == 3 ? 1 : 0;
    //�޸����ʱ��Ϊ���ˢ��ʱ��_Calvin_20140618
    $end_date = $rs['End_Date'];
    $end_date = explode(' ', $end_date);
    $add_date = $rs['LastUpdate_Time'];
    $add_date = explode(' ', $add_date);
    $setsqlarr['deadline'] = strtotime($rs['End_Date']);
    $setsqlarr['addtime'] = strtotime($rs['LastUpdate_Time']);
    $setsqlarr['refreshtime'] = strtotime($rs['LastUpdate_Time']);
    $setsqlarr['key'] = $setsqlarr['jobs_name'] . $setsqlarr['companyname'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'];
    $setsqlarr['key'] = "{$setsqlarr['jobs_name']} {$setsqlarr['companyname']} " . $sp->extracttag($setsqlarr['key']);
    $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
    $setsqlarr['subsite_id'] = 0;
    $setsqlarr['tpl'] = $company_profile['tpl'];
    $setsqlarr['map_x'] = $company_profile['map_x'];
    $setsqlarr['map_y'] = $company_profile['map_y'];
    $setsqlarr['click'] = $rs['ViewClicks'];
    $setsqlarr['audit'] = ($rs['JobFlag'] == 1) ? 1 : 2;
    $setsqlarr_contact['contact'] = escape_str($rs['ContactPerson']);
    $setsqlarr_contact['qq'] = "";
    $setsqlarr_contact['telephone'] = $rs['Phone'];
    $setsqlarr_contact['address'] = escape_str($rs['Address']);
    $setsqlarr_contact['email'] = escape_str($rs['Email']);
    $setsqlarr_contact['notify'] = 0;
    //���ְλ��Ϣ
    //var_dump($setsqlarr);
    var_dump($setsqlarr['id']);
    if ($setsqlarr['deadline'] < time() || $setsqlarr['audit'] != 1) {
        $pid = inserttable(table('jobs_tmp'), $setsqlarr, true);
    } else {
        $pid = inserttable(table('jobs'), $setsqlarr, true);
    }
    var_dump($pid);
    empty($pid) ? showmsg("���ʧ�ܣ�", 0) : '';
    //����ת������Ͷ����_Calvin_20140619(��д�ڴ�)---begin
    $sql = "select  *  from pH_Company_InBox where JobId=" . $rs['JobId'];
    $sc_rs = ms_getall($sql);
    if (!empty($sc_rs)) {
        foreach ($sc_rs as $sc_rs) {
            //��ѯ������Ϣ
            $pr_sql = "select NCID,UserName,RealName from pH_Person_Info where PerId='" . $sc_rs['Perid'] . "'";
            $pr_rs = ms_getone($pr_sql);
            //����¼�Ƿ����
            $pj_sql = "select did from " . table('personal_jobs_apply') . " where resume_id='" . $pr_rs['NCID'] . "' and apply_addtime=" . strtotime($sc_rs['AddDate']) . " LIMIT 1";
            $pj_info = $db->getone($pj_sql);
            //�����¼
            if (empty($pj_info)) {
                $sc_arr['resume_id'] = $pr_rs['NCID'];
                $sc_arr['resume_name'] = escape_str($pr_rs['RealName']);
                $db_sql = "select uid from " . table('members') . " where username='" . escape_str($pr_rs['UserName']) . "' LIMIT 1";
                $info = $db->getone($db_sql);
                if (empty($info['uid'])) {
                    access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_resume_save.php?id=" . $pr_rs['NCID']);
                    $info = get_personal_user_inusername(escape_str($pr_rs['UserName']));
                }
                $sc_arr['personal_uid'] = $info['uid'];
                $sc_arr['jobs_id'] = $pid;
                $sc_arr['jobs_name'] = $setsqlarr['jobs_name'];
                $sc_arr['company_id'] = $setsqlarr['company_id'];
                $sc_arr['company_name'] = $setsqlarr['companyname'];
                $sc_arr['company_uid'] = $setsqlarr['uid'];
                $sc_arr['apply_addtime'] = strtotime($sc_rs['AddDate']);
                if ($sc_rs['Flag']) {
                    $sc_arr['personal_look'] = 2;
                } else {
                    $sc_arr['personal_look'] = 1;
                }
                $sc_arr['notes'] = "��";
                //var_dump($sc_arr);
                inserttable(table('personal_jobs_apply'), $sc_arr);
            }
        }
    }
    //����ת������Ͷ����_Calvin_20140619(��д�ڴ�)---end
    $setsqlarr_contact['pid'] = $pid;
    //var_dump($setsqlarr_contact);
    !inserttable(table('jobs_contact'), $setsqlarr_contact) ? showmsg("���ʧ�ܣ�", 0) : '';
    $searchtab['id'] = $pid;
    $searchtab['uid'] = $setsqlarr['uid'];
    $searchtab['subsite_id'] = $setsqlarr['subsite_id'];
    $searchtab['recommend'] = 0;
    $searchtab['emergency'] = $setsqlarr['emergency'];
    $searchtab['nature'] = $setsqlarr['nature'];
    $searchtab['sex'] = $setsqlarr['sex'];
    $searchtab['category'] = $setsqlarr['category'];
    $searchtab['subclass'] = $setsqlarr['subclass'];
    $searchtab['trade'] = $setsqlarr['trade'];
    $searchtab['district'] = $setsqlarr['district'];
    $searchtab['sdistrict'] = $setsqlarr['sdistrict'];
    $searchtab['street'] = $company_profile['street'];
    //$searchtab['officebuilding']=$company_profile['officebuilding'];	
    $searchtab['education'] = $setsqlarr['education'];
    $searchtab['experience'] = $setsqlarr['experience'];
    $searchtab['wage'] = $setsqlarr['wage'];
    $searchtab['refreshtime'] = $setsqlarr['refreshtime'];
    $searchtab['scale'] = $setsqlarr['scale'];
    inserttable(table('jobs_search_wage'), $searchtab);
    inserttable(table('jobs_search_scale'), $searchtab);
    $searchtab['map_x'] = $setsqlarr['map_x'];
    $searchtab['map_y'] = $setsqlarr['map_y'];
    inserttable(table('jobs_search_rtime'), $searchtab);
    unset($searchtab['map_x'], $searchtab['map_y']);
    $searchtab['click'] = $setsqlarr['click'];
    inserttable(table('jobs_search_hot'), $searchtab);
    $searchtab['stick'] = 0;
    var_dump($searchtab);
    inserttable(table('jobs_search_stickrtime'), $searchtab);
    unset($searchtab['stick']);
    $searchtab['key'] = $setsqlarr['key'];
    $searchtab['map_x'] = $setsqlarr['map_x'];
    $searchtab['map_y'] = $setsqlarr['map_y'];
    $searchtab['likekey'] = $setsqlarr['jobs_name'] . "," . $setsqlarr['companyname'];
    inserttable(table('jobs_search_key'), $searchtab);
    unset($searchtab);
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
    $tagsql['category'] = $setsqlarr['category'];
    $tagsql['subclass'] = $setsqlarr['subclass'];
    $tagsql['district'] = $setsqlarr['district'];
    $tagsql['sdistrict'] = $setsqlarr['sdistrict'];
    inserttable(table('jobs_search_tag'), $tagsql);
}

function get_jobs_nature($str) {
    global $db, $locoyspider;
    $arr = array();
    switch ($str) {
        case "1":
            $str = "ȫְ";
            $arr = array(62, 'ȫְ');
            break;
        case "2":
            $str = "��ְ";
            $arr = array(63, '��ְ');
            break;
        default:
            $str = "ȫְ";
            $arr = array(62, 'ȫְ');
            break;
    }
    return $arr;
}

function get_jobs_amount($str) {
    global $locoyspider;
    $str = intval($str);
    if ($str > 0) {
        return $str;
    } else {
        return mt_rand($locoyspider['jobs_amount_min'], $locoyspider['jobs_amount_max']);
    }
}

function get_jobs_education($str = NULL) {
    //��ѯ�Ƿ���ڡ����ޡ�ѧ��Ҫ��û��ʱ����_Calvin_20140623
    global $db;
    $sql = "select c_id,c_name from " . table('category') . " where c_alias='QS_education'  and c_name='����' LIMIT 1";
    //var_dump($sql);exit;
    $info = $db->getone($sql);
    //var_dump($info['c_id']);
    if (empty($info['c_id'])) {
        $edu_in_arr = array('c_alias' => 'QS_education', 'c_name' => '����');
        $info['c_id'] = inserttable(table('category'), $edu_in_arr, TRUE);
    }
    $arr = array();
    switch ($str) {
        case "10":
            $str = "Сѧ";
            $arr = array('id' => 65, 'cn' => '����');
            break; //�����Сѧ����ֵΪ����
        case "20":
            $str = "����";
            $arr = array('id' => 65, 'cn' => '����');
            break;
        case "30":
            $str = "����";
            $arr = array('id' => 66, 'cn' => '����');
            break;
        case "40":
            $str = "��ר";
            $arr = array('id' => 68, 'cn' => '��ר');
            break;
        case "50":
            $str = "��ר";
            $arr = array('id' => 69, 'cn' => '��ר');
            break;
        case "60":
            $str = "����";
            $arr = array('id' => 70, 'cn' => '����');
            break;
        case "70":
            $str = "˶ʿ";
            $arr = array('id' => 71, 'cn' => '˶ʿ');
            break;
        case "80":
            $str = "��ʿ";
            $arr = array('id' => 72, 'cn' => '��ʿ');
            break;
        case "90":
            $str = "��ʿ��";
            $arr = array('id' => 73, 'cn' => '����');
            break;
        default:
            $str = "����";
            $arr = array('id' => $info['c_id'], 'cn' => '����');
            break;
    }
    return $arr;
}

function get_jobs_experience($str = NULL) {
    $arr = array();

    switch ($str) {
        case "0":
            $str = "����";
            $arr = array('id' => 76, 'cn' => '1-3��');
            break; //����в��ޣ�ֱ�Ӹ�ֵΪ1-3��
        case "1":
            $str = "һ������";
            $arr = array('id' => 76, 'cn' => '1-3��');
            break;
        case "2":
            $str = "��������";
            $arr = array('id' => 76, 'cn' => '1-3��');
            break;
        case "3":
            $str = "��������";
            $arr = array('id' => 77, 'cn' => '3-5��');
            break;
        case "4":
            $str = "��������";
            $arr = array('id' => 77, 'cn' => '3-5��');
            break;
        case "5":
            $str = "��������";
            $arr = array('id' => 78, 'cn' => '5-10��');
            break;
        case "10":
            $str = "ʮ������";
            $arr = array('id' => 79, 'cn' => '10������');
            break;
        case "20":
            $str = "��ʮ������";
            $arr = array('id' => 79, 'cn' => '10������');
            break;
        case "30":
            $str = "��ʮ������";
            $arr = array('id' => 79, 'cn' => '10������');
            break;
        default:
            $str = "רְ";
            $arr = array('id' => 76, 'cn' => '1-3��');
            break;
    }
    return $arr;
}

function get_jobs_wage($str = NULL) {
    $str = intval($str);
    $arr = array();
    if ($str == 0) {
        $arr = array('id' => 55, 'name' => '����');
    } elseif ($str >= 1000 && $str <= 1500) {
        $arr = array('id' => 56, 'name' => '1000~1500Ԫ/��');
    } elseif ($str > 1500 && $str < 2000) {
        $arr = array('id' => 57, 'name' => '1500~2000Ԫ/��');
    } elseif ($str >= 2000 && $str <= 3000) {
        $arr = array('id' => 58, 'name' => '2000~3000Ԫ/��');
    } elseif ($str > 3000 && $str < 5000) {
        $arr = array('id' => 59, 'name' => '3000~5000Ԫ/��');
    } elseif ($str >= 5000 && $str <= 10000) {
        $arr = array('id' => 60, 'name' => '5000~10000Ԫ/��');
    } elseif ($str > 10000) {
        $arr = array('id' => 61, 'name' => 'һ������/��');
    }
    return $arr;
}

function get_sex($str) {
    switch ($str) {
        case "1":
            return array(1, '��');
            break;
        case "0":
            return array(2, 'Ů');
            break;
        case "2":
            return array(3, '����');
            break;
        default:
            return array(3, '����');
    }
}

function get_company($uid) {
    global $db;
    $sql = "select * from " . table('company_profile') . " where uid=" . intval($uid) . " LIMIT 1 ";
    return $db->getone($sql);
}

//��Ӳ���ְλ������Ϣ��¼_Calvin_20141208
function get_jobs_category_arr($str) {
    global $db, $locoyspider;
    if (strstr($str, "-")) {
        $jc_arr = explode("-", $str);
    } else {
        $jc_arr[0] = $str;
        $jc_arr[1] = "����ְλ";
    }
    if (substr($jc_arr[0], -2) == "��") {
        $jc_arr[0] = substr($jc_arr[0], 0, strlen($jc_arr[0]) - 2);
    }
    if ($jc_arr[0] == "����" || $jc_arr[0] == "����" || $jc_arr[0] == "����") {
        $jc_arr[0] = "����";
    }
    if (strstr($jc_arr[1], "&#183;")) {
        $postion = strpos($jc_arr[1], "��");
        $jc_arr[1] = substr($jc_arr[1], 0, $postion);
    }
    if (substr($jc_arr[1], -2) == "��") {
        $jc_arr[1] = substr($jc_arr[1], 0, strlen($jc_arr[1]) - 2);
    }
    $sql = "select id,categoryname from " . table('category_jobs') . " where parentid = 0";
    $big_info = $db->getall($sql);
    $big_return = locoyspider_search_str($big_info, $jc_arr[0], "categoryname", 1);
    if (!$big_return) {
        $big_return['id'] = 7;
    }
    $sql = "select id,parentid,categoryname from " . table('category_jobs') . " where parentid = " . $big_return['id'];
    $small_info = $db->getall($sql);
    $small_return = locoyspider_search_str($small_info, $jc_arr[1], "categoryname", 50);
    if (!$small_return) {
        $sql = "select id,parentid,categoryname from " . table('category_jobs') . " where parentid = " . $big_return['id'] . " and categoryname = '����ְλ'";
        $small_return = $db->getone($sql);
    }
    $jc_re = array("category" => $big_return['id'], "subclass" => $small_return['id'], "category_cn" => $small_return['categoryname']);
    //var_dump($jc_re);
    return $jc_re;
}

function match_other_city($id, $title, $aname) {
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
        if (strstr($title, $i['categoryname'])) {
            $return = $i;
            break;
        }
    }
    if (!$return) {
        if (empty($aname)) {
            $error_in_arr['pid'] = $id;
            $error_in_arr['type'] = 'job';
            $error_in_arr['addtime'] = time();
            inserttable(table('91_city_error'), $error_in_arr);
            return FALSE;
        }
        $return['categoryname'] = $aname;
    }
    //var_dump($return['categoryname']);
    return $return['categoryname'];
}

//��Ӳ���ְλ������Ϣ��¼_Calvin_20140618
function get_jobs_city_arr($cname, $aname) {
    global $db;
    $cname = trim($cname);
    $aname = trim($aname);
    $tmp_str = substr($cname, -2);
    if ($tmp_str == "��" || $tmp_str == "ʡ" || $tmp_str == "��") {
        if (!strstr($cname, "����" . $tmp_str)) {
            $tmp_name = substr($cname, 0, strlen($cname) - 2);
            if (strlen($tmp_name) > 3) {
                $cname = $tmp_name;
            }
        }
    }
    $tmp_str = substr($aname, -2);
    if ($tmp_str == "��" || $tmp_str == "ʡ" || $tmp_str == "��") {
        if (!strstr($aname, "����" . $tmp_str)) {
            $tmp_name = substr($aname, 0, strlen($aname) - 2);
            if (strlen($tmp_name) > 3) {
                $aname = $tmp_name;
            }
        }
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
                    $return['parentname'] = $aname;
                    $return['categoryname'] = $aname;
                } else {
                    $add_city = array("parentid" => $return_a['id'], "categoryname" => $cname);
                    $c_id = inserttable(table('category_district'), $add_city, true);
                    access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_city_pinyin.php?id=" . $c_id);
                    $return['id'] = $c_id;
                    $return['parentid'] = $return_a['id'];
                    $return['parentname'] = $return_a['categoryname'];
                    $return['categoryname'] = $cname;
                }
            } else {
                $add_city = array("parentid" => $return_a['id'], "categoryname" => $cname);
                $c_id = inserttable(table('category_district'), $add_city, true);
                access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_city_pinyin.php?id=" . $c_id);
                $return['parentid'] = $return_a['id'];
                $return['parentname'] = $return_a['categoryname'];
                $return['id'] = $c_id;
                $return['categoryname'] = $cname;
            }
        } else {
            if ($cname != $aname) {
                $sql = "select categoryname from " . table('category_district') . " where id = " . $return['parentid'];
                $area_info = $db->getone($sql);
                $return['parentname'] = $area_info['categoryname'];
            } else {
                $return['parentname'] = $cname;
            }
        }
    }
    if ($return['parentid'] == 0) {
        $return['parentid'] = $return['id'];
        $return['id'] = 0;
        $return['parentname'] = $return['categoryname'];
        $return['categoryname'] = "";
    }
    return array("district" => $return['parentid'], "district_cn" => $return['parentname'], "sdistrict" => $return['id'], "sdistrict_cn" => $return['categoryname']);
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

function get_personal_user_inusername($username) {
    global $db;
    $sql = "select * from " . table('members') . " where username = '{$username}' and utype=2 LIMIT 1";
    return $db->getone($sql);
}

function del_jobs($id) {
    global $db;
    $db->query("Delete from  " . table('jobs') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id='" . $id . "' and type = 'jobs' LIMIT 1");
    $db->query("Delete from  " . table('jobs_tmp') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from  " . table('jobs_search_wage') . " WHERE id='" . $id . "'");
    $db->query("Delete from  " . table('jobs_search_hot') . " WHERE id='" . $id . "'");
    $db->query("Delete from  " . table('jobs_search_key') . " WHERE id='" . $id . "'");
    $db->query("Delete from  " . table('jobs_search_rtime') . " WHERE id='" . $id . "'");
    $db->query("Delete from  " . table('jobs_search_scale') . " WHERE id='" . $id . "'");
    $db->query("Delete from  " . table('jobs_search_stickrtime') . " WHERE id='" . $id . "'");
    $db->query("Delete from  " . table('jobs_search_tag') . " WHERE id='" . $id . "'");
}

function del_jobs_app($id) {
    global $db;
    $db->query("Delete from " . table('personal_jobs_apply') . " WHERE jobs_id='" . $id . "'");
}

?>