<?php

/*
 * 74cms 网站首页
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
set_time_limit(0);
define('IN_QISHI', true);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pramga: no-cache");
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
    del_resume_all($id);
    exit;
}

$sql = "select TOP 1 * from pH_Person_Info WHERE NCID={$id}";
$rs = ms_getone($sql);

require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
$sp = new SPWord();


$db->query("Delete from " . table('91_city_error') . " where pid = " . $rs['NCID'] . " and type='resume'");
$default_city_arr = get_default_city($rs['NCID'], $rs);
$setsqlarr['id'] = intval($rs['NCID']);
$pid = $setsqlarr['id'];
$userinfo = get_personal_user_inusername(escape_str($rs['UserName']));
if (empty($userinfo['uid'])) {
    $sql = "select TOP 1 * from pH_Person_Base WHERE UserName='{$rs['UserName']}'";
    $user_rs = ms_getone($sql);
    access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_user_personal_save.php?id=" . $user_rs['NcID']);
    $userinfo = get_personal_user_inusername(escape_str($rs['UserName']));
}
$has_id = $db->getone("select id from " . table('resume') . " where id=" . $setsqlarr['id']);
$has_id_tmp = $db->getone("select id from " . table('resume_tmp') . " where id=" . $setsqlarr['id']);
if ((!empty($has_id)) || (!empty($has_id_tmp))) {
    del_resume($setsqlarr['id']);
}
$setsqlarr['uid'] = intval($userinfo['uid']);
$setsqlarr['title'] = escape_str($rs['RealName']) . '创建的简历';
$setsqlarr['fullname'] = escape_str($rs['RealName']);
$setsqlarr['sex'] = $rs['Sex'] == 1 ? 1 : 2;
$setsqlarr['sex_cn'] = $rs['Sex'] == 1 ? '男' : '女';
$setsqlarr['birthdate'] = intval($rs['BirthYear']);
$setsqlarr['height'] = intval($rs['Stature']);
$Marry = $rs['Marry'];
switch ($Marry) {
    case 1:
        $setsqlarr['marriage'] = 1;
        $marriage = "未婚";
        break;
    case 2:
        $setsqlarr['marriage'] = 2;
        $marriage = "已婚";
        break;
    default:
        $setsqlarr['marriage'] = 3;
        $marriage = "保密";
}
$setsqlarr['marriage_cn'] = $marriage;
$experience = get_experience($rs['Works']);
$setsqlarr['experience'] = $experience['id'];
$setsqlarr['experience_cn'] = $experience['cn'];

if (empty($rs['Door_Area']) || empty($rs['Door_City'])) {
    if (empty($rs['Locus_City']) || escape_str($rs['Locus_City']) == "其他" || escape_str($rs['Locus_City']) == "其它") {
        if (escape_str($rs['Locus_City']) == $default_city_arr['area']) {
            $city = $default_city_arr['city'];
            $area = $default_city_arr['area'];
        }
    } else {
        $city = escape_str($rs['Locus_City']);
        $area = escape_str($rs['Locus_Area']);
    }
} else {
    if (empty($rs['Door_City']) || escape_str($rs['Door_City']) == "其他" || escape_str($rs['Door_City']) == "其它") {
        if (escape_str($rs['Door_City']) == $default_city_arr['area']) {
            $city = $default_city_arr['city'];
            $area = $default_city_arr['area'];
        }
    } else {
        $city = escape_str($rs['Door_City']);
        $area = escape_str($rs['Door_Area']);
    }
}
$house_arr = get_city_arr($area . "-" . $city);
$setsqlarr['householdaddress'] = $house_arr['district'] . "." . $house_arr['sdistrict'];
$setsqlarr['householdaddress_cn'] = $house_arr['sdistrict_cn'] == "" ? $house_arr['district_cn'] : $house_arr['district_cn'] . "/" . $house_arr['sdistrict_cn'];
if (empty($rs['Locus_Area']) || empty($rs['Locus_City'])) {
    if (empty($rs['Door_City']) || escape_str($rs['Door_City']) == "其他" || escape_str($rs['Door_City']) == "其它") {
        if (escape_str($rs['Door_Area']) == $default_city_arr['area']) {
            $city = $default_city_arr['city'];
            $area = $default_city_arr['area'];
        }
    } else {
        $city = escape_str($rs['Door_City']);
        $area = escape_str($rs['Door_Area']);
    }
} else {
    if (empty($rs['Locus_City']) || escape_str($rs['Locus_City']) == "其他" || escape_str($rs['Locus_City']) == "其它") {
        if (escape_str($rs['Locus_Area']) == $default_city_arr['area']) {
            $city = $default_city_arr['city'];
            $area = $default_city_arr['area'];
        }
    } else {
        $city = escape_str($rs['Locus_City']);
        $area = escape_str($rs['Locus_Area']);
    }
}
$city_arr = get_city_arr($area . "-" . $city);
$setsqlarr['residence'] = $city_arr['district'] . "." . $city_arr['sdistrict'];
$setsqlarr['residence_cn'] = $city_arr['sdistrict_cn'] == "" ? $city_arr['district_cn'] : $city_arr['district_cn'] . "/" . $city_arr['sdistrict_cn'];
$education = get_education($rs['Edus']);
$setsqlarr['education'] = $education['id'];
$setsqlarr['education_cn'] = $education['cn'];
$setsqlarr['tag'] = '';
$setsqlarr['telephone'] = strlen($rs['PersonPhone']) >= 8 ? $rs['PersonPhone'] : "";
$setsqlarr['email'] = $userinfo['email'];
$setsqlarr['email_notify'] = 1;
//$setsqlarr['address']=escape_str($rs['Address']);
//$setsqlarr['website']=escape_str($rs['WebHome']);
//$setsqlarr['qq']=intval($rs['Msnqq']);
$a = rand(20, 60);
$userinfo['reg_time'] = $userinfo['reg_time'] ? $userinfo['reg_time'] : strtotime("-" . $a . "day");
$setsqlarr['refreshtime'] = $userinfo['reg_time'];
$setsqlarr['subsite_id'] = 0;
$setsqlarr['display_name'] = 3;
$audit = ($rs['Flag'] == 1) ? 1 : 2;
$setsqlarr['audit'] = $audit;
$setsqlarr['addtime'] = $setsqlarr['refreshtime'];
//$setsqlarr['recentjobs']=''; 
$nature = $rs['PerType'];
if ($nature == "1") {
    $setsqlarr['nature'] = 62;
    $nature_cn = "全职";
} elseif ($nature == "2") {
    $setsqlarr['nature'] = 63;
    $nature_cn = "兼职";
} else {
    $setsqlarr['nature'] = 62;
    $nature_cn = "全职";
}
$setsqlarr['nature_cn'] = $nature_cn;
//$dname=mb_substr($rs['AreaWill1'],3,3,'gb2312');

$dname = !empty($rs['AreaWill1']) ? $rs['AreaWill1'] : $rs['Locus_Area'] . "-" . $rs['Locus_City'];
$dname = escape_str($dname);
if (strstr($dname, '-')) {
    $str_tmp = explode('-', $dname);
    $dname_arr['area'] = $str_tmp[0];
    $dname_arr['city'] = $str_tmp[1];
} else {
    $dname_arr['area'] = $dname;
    $dname_arr['city'] = $dname;
}
if (empty($dname_arr['city']) || $dname_arr['city'] == "不限" || $dname_arr['city'] == "其它" || $dname_arr['city'] == "其他") {
    if ($dname_arr['area'] == $default_city_arr['area']) {
        $dname_arr['city'] = $default_city_arr['city'];
        $dname_arr['area'] = $default_city_arr['area'];
    }
}

$caty = get_city_arr($dname_arr['area'] . "-" . $dname_arr['city']);
$setsqlarr['district'] = $caty['district'];
$setsqlarr['sdistrict'] = $caty['sdistrict'];
$setsqlarr['district_cn'] = $caty['sdistrict_cn'] == "" ? $caty['district_cn'] : $caty['sdistrict_cn'];
$wage = $rs['Deal'];
$wage = explode(".", $wage);
$wage = get_wage($wage[0]);
$setsqlarr['wage'] = $wage['id'];
$setsqlarr['wage_cn'] = $wage['name'];
$trade = get_oldtrade($rs['Industry']);
$trade = get_resumetrade($trade);
$setsqlarr['trade'] = $trade['id'];
$setsqlarr['trade_cn'] = $trade['cn'];
$rs['WorkWillClass1'] = trim($rs['WorkWillClass1']);
$work_w1 = ($rs['WorkWillClass1'] != "") ? $rs['WorkWillClass1'] : $rs['WorkWill1'];
$work_w2 = ($rs['WorkWillClass2'] != "") ? $rs['WorkWillClass2'] : $rs['WorkWill2'];
$work_w3 = ($rs['WorkWillClass3'] != "") ? $rs['WorkWillClass3'] : $rs['WorkWill3'];
$work_w1 = get_work_w($work_w1);
$work_w2 = get_work_w($work_w2);
$work_w3 = get_work_w($work_w3);
$work_w1 = get_category_arr($work_w1);
$work_w2 = get_category_arr($work_w2);
$work_w3 = get_category_arr($work_w3);
$work_w1 = $work_w1['category_cn'] . "-" . $work_w1['subclass_cn'];
$work_w2 = $work_w2['category_cn'] . "-" . $work_w2['subclass_cn'];
$work_w3 = $work_w3['category_cn'] . "-" . $work_w3['subclass_cn'];
$intention_jobs = $work_w1 . ',' . $work_w2 . ',' . $work_w3;
$intention_jobs = explode(",", $intention_jobs);
$intention_jobs = array_unique($intention_jobs);
$intention_jobs = implode(",",$intention_jobs);
//var_dump($intention_jobs);exit;
$setsqlarr['intention_jobs'] = $intention_jobs;
$specialty = ms_getone("select  top 1 * from pH_Person_Skill WHERE Perid='" . escape_str($rs['PerId']) . "'");
foreach ($specialty as $specialty) {
    $setsqlarr['specialty'] = escape_str($specialty['SkillMemo']);
}
$setsqlarr['specialty'] = $setsqlarr['specialty'] ? $setsqlarr['specialty'] : escape_str($rs['Appraise']);
//照片
if ($rs['PhotoUrl']) {
    $setsqlarr['photo_audit'] = 1;
    $setsqlarr['photo_display'] = 1;
    $setsqlarr['photo_img'] = $rs['PhotoUrl'];
    $setsqlarr['photo'] = 1;
} else {
    $setsqlarr['photo_audit'] = 1;
    $setsqlarr['photo_display'] = 1;
    $setsqlarr['photo_img'] = '';
    $setsqlarr['photo'] = 0;
}
if ($setsqlarr['intention_jobs'] == "" || empty($setsqlarr['fullname']) || !$default_city_arr) {
    $setsqlarr['display'] = 2;
    $setsqlarr['complete_percent'] = 45;
} else {
    $setsqlarr['display'] = 1;
    $setsqlarr['complete_percent'] = 60;
}
if ($setsqlarr['audit'] != 1) {
    $setsqlarr['display'] = 2;
}
$setsqlarr['talent'] = 1;
$setsqlarr['key'] = $setsqlarr['intention_jobs'] . $setsqlarr['recentjobs'] . $setsqlarr['specialty'];
$setsqlarr['key'] = "{$setsqlarr['fullname']} " . $sp->extracttag($setsqlarr['key']);
$setsqlarr['key'] = str_replace(",", " ", $setsqlarr['intention_jobs']) . " {$setsqlarr['key']} {$resume_basic['education_cn']}";
$setsqlarr['key'] = $sp->pad($setsqlarr['key']);
$setsqlarr['click'] = intval($rs['ViewClicks']);

$pid = conversion_inserttable(table('resume'), $setsqlarr, 1);
$db->query("Delete from " . table('resume_tmp') . " WHERE id='" . intval($rs['NCID']) . "' LIMIT 1");

//意向职位
//$jobs = $rs['WorkWillClass1'] . ',' . $rs['WorkWillClass2'] . ',' . $rs['WorkWillClass3'] . ',' . $rs['WorkWill1'] . ',' . $rs['WorkWill2'] . ',' . $rs['WorkWill3'];
$jobs = explode(',', $setsqlarr['intention_jobs']);
$j_s = array();
if (is_array($jobs) && !empty($jobs)) {
    foreach ($jobs as $j) {
        if (empty($j)) {
            continue;
        } else {
            //$j = get_work_w($j);
            $category = get_category_arr($j);
            $category = $category['category'] . "." . $category['subclass'];
            //$category = implode('.', $category);
            $j_s[] = $category;
        }
    }
    $j_s = array_unique($j_s);
    $j_s = implode('-', $j_s);
    conversion_add_resume_jobs($pid, $userinfo['uid'], $j_s);
}
//教育经历
$edinfo = ms_getall("select  * from pH_Person_Education WHERE UserName='" . escape_str($rs['UserName']) . "'");
foreach ($edinfo as $edinfo) {
    $esql['uid'] = $userinfo['uid'];
    $esql['pid'] = $pid;
    $esql['startyear'] = $edinfo['Begin_Year'];
    $esql['startmonth'] = $edinfo['Begin_Month'];
    $esql['endyear'] = $edinfo['End_Year'];
    $esql['endmonth'] = $edinfo['End_Month'];
    $esql['school'] = escape_str($edinfo['SchoolName']);
    $esql['speciality'] = escape_str($edinfo['Speciality']);
    $education = get_education($edinfo['Edus']);
    $esql['education'] = $education['id'];
    $esql['education_cn'] = $education['cn'];
    $wheresqlarr = " uid=" . $userinfo['uid'] . " and pid=" . $pid . " and education=" . $education['id'];
    $sel_sql = "select * from " . table('resume_education') . " where" . $wheresqlarr;
    $result = $db->getone($sel_sql);
    //var_dump($esql);
    if (empty($result)) {
        conversion_inserttable(table('resume_education'), $esql);
    } else {
        updatetable(table('resume_education'), $esql, $wheresqlarr);
    }
}
//工作经历
$workinfo = ms_getall("select  * from pH_Person_Work WHERE UserName='" . escape_str($rs['UserName']) . "'");
foreach ($workinfo as $workinfo) {
    $wsql['uid'] = $userinfo['uid'];
    $wsql['pid'] = $pid;
    $wsql['startyear'] = $workinfo['Begin_Year'];
    $wsql['startmonth'] = $workinfo['Begin_Month'];
    $wsql['endyear'] = $workinfo['End_Year'];
    $wsql['endmonth'] = $workinfo['End_Month'];
    $wsql['companyname'] = escape_str($workinfo['CompanyName']);
    $change = array(iconv("gbk", 'utf-8//IGNORE', '类-') => '-');
    $workinfo['WorkName'] = strtr($workinfo['WorkName'], $change);
    $wsql['jobs'] = escape_str($workinfo['WorkName']);
    //$wsql['companyprofile']=escape_str($workinfo['WorkMemo']);
    $wsql['achievements'] = '未填写';
    $wheresqlarr = " uid=" . $userinfo['uid'] . " and pid=" . $pid . " and companyname='" . escape_str($workinfo['CompanyName']) . "' and jobs='" . escape_str($workinfo['WorkName']) . "'";
    $sel_sql = "select * from " . table('resume_work') . " where" . $wheresqlarr;
    $result = $db->getone($sel_sql);
    if (empty($result)) {
        conversion_inserttable(table('resume_work'), $wsql);
    } else {
        updatetable(table('resume_work'), $wsql, $wheresqlarr);
    }
}
//培训经历
$traininginfo = ms_getall("select  * from pH_Person_Education WHERE UserName='" . escape_str($rs['UserName']) . "' AND Type = 2");
foreach ($traininginfo as $traininginfo) {
    $tsql['uid'] = $userinfo['uid'];
    $tsql['pid'] = $pid;
    $tsql['startyear'] = $traininginfo['Begin_Year'];
    $tsql['startmonth'] = $traininginfo['Begin_Month'];
    $tsql['endyear'] = $traininginfo['End_Year'];
    $tsql['endmonth'] = $traininginfo['End_Month'];
    $tsql['agency'] = escape_str($traininginfo['SchoolName']);
    $tsql['course'] = escape_str($traininginfo['Speciality']);
    $tsql['description'] = escape_str($traininginfo['CertName']);
    $wheresqlarr = " uid=" . $userinfo['uid'] . " and pid=" . $pid . " and agency='" . escape_str($traininginfo['SchoolName']) . "' and course='" . escape_str($traininginfo['Speciality']) . "'";
    $sel_sql = "select * from " . table('resume_training') . " where" . $wheresqlarr;
    $result = $db->getone($sel_sql);
    if (empty($result)) {
        conversion_inserttable(table('resume_training'), $tsql);
    } else {
        updatetable(table('resume_training'), $tsql, $wheresqlarr);
    }
}
//索引表
if ($setsqlarr['display'] == 1) {
    $searchtab['id'] = $pid;
    $searchtab['uid'] = $setsqlarr['uid'];
    $searchtab['subsite_id'] = intval($setsqlarr['subsite_id']);
    $searchtab['sex'] = $setsqlarr['sex'];
    $searchtab['nature'] = $setsqlarr['nature'];
    $searchtab['marriage'] = $setsqlarr['marriage'];
    $searchtab['experience'] = $setsqlarr['experience'];
    $searchtab['district'] = $setsqlarr['district'];
    $searchtab['sdistrict'] = $setsqlarr['sdistrict'];
    $searchtab['wage'] = $setsqlarr['wage'];
    $searchtab['education'] = $setsqlarr['education'];
    $searchtab['photo'] = $setsqlarr['photo'];
    $searchtab['refreshtime'] = $setsqlarr['refreshtime'];
    $searchtab['click'] = $setsqlarr['click'];
    $searchtab['talent'] = $setsqlarr['talent'];
    if ($save_type == "update") {
        $wheresqlarr = " id=" . $pid;
        updatetable(table('resume_search_rtime'), $searchtab, $wheresqlarr);
    } else {
        inserttable(table('resume_search_rtime'), $searchtab);
    }
        unset($searchtab['click']);
    $searchtab['key'] = $setsqlarr['key'];
    $searchtab['audit'] = $setsqlarr['audit'];
    $searchtab['likekey'] = $setsqlarr['intention_jobs'] . ',' . $setsqlarr['recentjobs'] . ',' . $setsqlarr['specialty'] . ',' . $setsqlarr['fullname'];
    if ($save_type == "update") {
        $wheresqlarr = " id=" . $pid;
        updatetable(table('resume_search_key'), $searchtab, $wheresqlarr);
    } else {
        conversion_inserttable(table('resume_search_key'), $searchtab);
    }
    unset($searchtab);
    $tagindex = 1;
    $tagsql['tag1'] = $tagsql['tag2'] = $tagsql['tag3'] = $tagsql['tag4'] = $tagsql['tag5'] = 0;
    $tagsql['id'] = $pid;
    $tagsql['uid'] = $setsqlarr['uid'];
    $tagsql['audit'] = $setsqlarr['audit'];
    $tagsql['subsite_id'] = $setsqlarr['subsite_id'];
    $tagsql['experience'] = $setsqlarr['experience'];
    $tagsql['district'] = $setsqlarr['district'];
    $tagsql['sdistrict'] = $setsqlarr['sdistrict'];
    $tagsql['education'] = $setsqlarr['education'];
    if ($save_type == "update") {
        $wheresqlarr = " id=" . $pid;
        updatetable(table('resume_search_tag'), $tagsql, $wheresqlarr);
    } else {
        conversion_inserttable(table('resume_search_tag'), $tagsql);
    }
}

function get_work_w($work_w) {
    $work_w = escape_str($work_w);
    if (strpos($work_w, "-")) {
        $change = array('类-' => '-');
        $work_w = strtr($work_w, $change);
    } else {
        $tmp_str = substr($work_w, -2);
        if ($tmp_str == "类") {
            $work_w = substr($work_w, 0, strlen($work_w) - 2);
        }
    }
    return $work_w;
}

function get_nature($str) {
    global $db, $locoyspider;
    $sql = "select * from " . table('category') . " where c_alias='QS_jobs_nature' AND c_name='{$str}'";
    $cid = $db->getone($sql);
    if ($cid) {
        return array($cid['c_id'], $cid['c_name']);
    } else {
        $id = intval($locoyspider['jobs_nature']);
        $sql = "select * from " . table('category') . " where  c_id='{$id}'";
        $cid = $db->getone($sql);
        return array($cid['c_id'], $cid['c_name']);
    }
}

function get_experience($str = NULL) {
    $arr = array();

    switch ($str) {
        case "0":
            $str = "不限";
            $arr = array('id' => 76, 'cn' => '1-3年');
            break; //如果市不限，直接赋值为1-3年
        case "1":
            $str = "一年以上";
            $arr = array('id' => 76, 'cn' => '1-3年');
            break;
        case "2":
            $str = "二年以上";
            $arr = array('id' => 76, 'cn' => '1-3年');
            break;
        case "3":
            $str = "三年以上";
            $arr = array('id' => 77, 'cn' => '3-5年');
            break;
        case "4":
            $str = "四年以上";
            $arr = array('id' => 77, 'cn' => '3-5年');
            break;
        case "5":
            $str = "五年以上";
            $arr = array('id' => 78, 'cn' => '5-10年');
            break;
        case "10":
            $str = "十年以上";
            $arr = array('id' => 79, 'cn' => '10年以上');
            break;
        case "20":
            $str = "二十年以上";
            $arr = array('id' => 79, 'cn' => '10年以上');
            break;
        case "30":
            $str = "三十年以上";
            $arr = array('id' => 79, 'cn' => '10年以上');
            break;
        default:
            $str = "专职";
            $arr = array('id' => 76, 'cn' => '1-3年');
            break;
    }
    return $arr;
}

function get_education($str = NULL) {
    $arr = array();
    switch ($str) {
        case "10":
            $str = "小学";
            $arr = array('id' => 65, 'cn' => '初中');
            break; //如果是小学，赋值为初中
        case "20":
            $str = "初中";
            $arr = array('id' => 65, 'cn' => '初中');
            break;
        case "30":
            $str = "高中";
            $arr = array('id' => 66, 'cn' => '高中');
            break;
        case "40":
            $str = "中专";
            $arr = array('id' => 68, 'cn' => '中专');
            break;
        case "50":
            $str = "大专";
            $arr = array('id' => 69, 'cn' => '大专');
            break;
        case "60":
            $str = "本科";
            $arr = array('id' => 70, 'cn' => '本科');
            break;
        case "70":
            $str = "硕士";
            $arr = array('id' => 71, 'cn' => '硕士');
            break;
        case "80":
            $str = "博士";
            $arr = array('id' => 72, 'cn' => '博士');
            break;
        case "90":
            $str = "博士后";
            $arr = array('id' => 73, 'cn' => '博士后');
            break;
        default:
            $str = "大专";
            $arr = array('id' => 69, 'cn' => '大专');
            break;
    }
    return $arr;
}

function get_wage($str = NULL) {
    $str = intval($str);
    $arr = array();
    if ($str == 0) {
        $arr = array('id' => 55, 'name' => '面议');
    } elseif ($str >= 1000 && $str <= 1500) {
        $arr = array('id' => 56, 'name' => '1000~1500元/月');
    } elseif ($str > 1500 && $str < 2000) {
        $arr = array('id' => 57, 'name' => '1500~2000元/月');
    } elseif ($str >= 2000 && $str <= 3000) {
        $arr = array('id' => 58, 'name' => '2000~3000元/月');
    } elseif ($str > 3000 && $str < 5000) {
        $arr = array('id' => 59, 'name' => '3000~5000元/月');
    } elseif ($str >= 5000 && $str <= 10000) {
        $arr = array('id' => 60, 'name' => '5000~10000元/月');
    } elseif ($str > 10000) {
        $arr = array('id' => 61, 'name' => '一万以上/月');
    }
    return $arr;
}

function get_default_city($id, $rs) {
    global $db;
    if (!empty($rs['Locus_City']) && escape_str($rs['Locus_City']) != "其他" && escape_str($rs['Locus_City']) != "其它") {
        $default_city_arr['city'] = escape_str($rs['Locus_City']);
        $default_city_arr['area'] = escape_str($rs['Locus_Area']);
    } elseif (!empty($rs['Door_City']) && escape_str($rs['Door_City']) != "其他" && escape_str($rs['Door_City']) != "其它") {
        $default_city_arr['city'] = escape_str($rs['Door_City']);
        $default_city_arr['area'] = escape_str($rs['Door_Area']);
    } elseif (!empty($rs['AreaWill1']) && escape_str($rs['AreaWill1']) != "其他" && escape_str($rs['AreaWill1']) != "其它") {
        $old_AreaWill = escape_str($rs['AreaWill1']);
        if (strstr($old_AreaWill, '-')) {
            $str_tmp = explode('-', $old_AreaWill);
            $default_city_arr['area'] = $str_tmp[0];
            $default_city_arr['city'] = $str_tmp[1];
            if (strstr($default_city_arr['city'], '特别行政区')) {
                $default_city_arr['city'] = $str_tmp[0];
            } else {
                $default_city_arr['city'] = $str_tmp[1];
            }
            if ($old_AreaWill == "新疆图木舒克") {
                $default_city_arr['city'] = "图木舒克";
                $default_city_arr['area'] = "新疆";
            }
            if ($old_AreaWill == "天津蓟县") {
                $default_city_arr['city'] = "天津";
                $default_city_arr['area'] = "蓟县";
            }
            if (empty($default_city_arr['city']) && $default_city_arr['city'] == "其他" && $default_city_arr['city'] == "其它") {
                $error_in_arr['pid'] = $id;
                $error_in_arr['type'] = 'resume';
                $error_in_arr['addtime'] = time();
                $sql = "select * from " . table('91_city_error') . " where pid = " . $id . " and type='resume' limit 1";
                $has_error = $db->getone($sql);
                if (!empty($has_error['id'])) {
                    $wheresqlarr = " pid = " . $id . " and type='resume'";
                    updatetable(table('91_city_error'), $error_in_arr, $wheresqlarr);
                } else {
                    inserttable(table('91_city_error'), $error_in_arr);
                }
                return FALSE;
            }
        } else {
            $sql = "select id,parentid,categoryname from " . table('category_district') . " where parentid > 0";
            $info = $db->getall($sql);
            foreach ($info as $i) {
                //echo $i['categoryname']."-<br>";
                if (strstr($old_AreaWill, $i['categoryname'])) {
                    $return = $i;
                    break;
                }
            }
            if ($return) {
                $sql = "select id,parentid,categoryname from " . table('category_district') . " where parentid = " . $return['parentid'] . " limit 1";
                $area_info = $db->getone($sql);
                $default_city_arr['city'] = trim($return['categoryname']);
                $default_city_arr['area'] = trim($area_info['categoryname']);
            } else {
                $error_in_arr['pid'] = $id;
                $error_in_arr['type'] = 'resume';
                $error_in_arr['addtime'] = time();
                $sql = "select * from " . table('91_city_error') . " where pid = " . $id . " and type='resume' limit 1";
                $has_error = $db->getone($sql);
                if (!empty($has_error['id'])) {
                    $wheresqlarr = " pid = " . $id . " and type='resume'";
                    updatetable(table('91_city_error'), $error_in_arr, $wheresqlarr);
                } else {
                    inserttable(table('91_city_error'), $error_in_arr);
                }
                return FALSE;
            }
        }
    } else {
        $error_in_arr['pid'] = $id;
        $error_in_arr['type'] = 'resume';
        $error_in_arr['addtime'] = time();
        $sql = "select * from " . table('91_city_error') . " where pid = " . $id . " and type='resume' limit 1";
        $has_error = $db->getone($sql);
        if (!empty($has_error['id'])) {
            $wheresqlarr = " pid = " . $id . " and type='resume'";
            updatetable(table('91_city_error'), $error_in_arr, $wheresqlarr);
        } else {
            inserttable(table('91_city_error'), $error_in_arr);
        }
        return FALSE;
    }

    $tmp_str = substr($default_city_arr['city'], -2);
    if ($tmp_str == "市" || $tmp_str == "省" || $tmp_str == "县") {
        if (!strstr($default_city_arr['city'], "自治" . $tmp_str)) {
            $tmp_name = substr($default_city_arr['city'], 0, strlen($default_city_arr['city']) - 2);
            if (strlen($tmp_name) > 3) {
                $default_city_arr['city'] = $tmp_name;
            }
        }
    }
    $tmp_str = substr($default_city_arr['area'], -2);
    if ($tmp_str == "市" || $tmp_str == "省" || $tmp_str == "县") {
        if (!strstr($default_city_arr['area'], "自治" . $tmp_str)) {
            $tmp_name = substr($default_city_arr['area'], 0, strlen($default_city_arr['area']) - 2);
            if (strlen($tmp_name) > 3) {
                $default_city_arr['area'] = $tmp_name;
            }
        }
    }
    return $default_city_arr;
}

function get_city_arr($str) {
    global $db, $locoyspider;
    $str = trim($str);
    if (empty($str)) {
        return array("district" => "", "district_cn" => "", "sdistrict" => "", "sdistrict_cn" => "");
    }
    if (strstr($str, '-')) {
        $str_tmp = explode('-', $str);
        $str = $str_tmp[1];
        if (strstr($str, '特别行政区')) {
            $str = $str_tmp[0];
        } else {
            $area = $str_tmp[0];
        }
        if ($str == "新疆图木舒克") {
            $str = "图木舒克";
            $area = "新疆";
        }
        if ($str == "天津蓟县") {
            $str = "天津";
            $area = "蓟县";
        }
    }
    $tmp_str = substr($str, -2);
    if ($tmp_str == "市" || $tmp_str == "省" || $tmp_str == "县") {
        if (!strstr($str, "自治" . $tmp_str)) {
            $tmp_name = substr($str, 0, strlen($str) - 2);
            if (strlen($tmp_name) > 3) {
                $str = $tmp_name;
            }
        }
    }
    $tmp_str = substr($area, -2);
    if ($tmp_str == "市" || $tmp_str == "省" || $tmp_str == "县") {
        if (!strstr($area, "自治" . $tmp_str)) {
            $tmp_name = substr($area, 0, strlen($area) - 2);
            if (strlen($tmp_name) > 3) {
                $area = $tmp_name;
            }
        }
    }
    if ($str == "其他" || $str == "其它" || $str == "不限") {
        $str = $area;
    }
    $p = strlen($str) >= 7 ? 40 : 60;
    if (empty($str)) {
        return array("district" => "", "district_cn" => "", "sdistrict" => "", "sdistrict_cn" => "");
    } else {
        $sql = "select id,parentid,categoryname from " . table('category_district') . " order by parentid desc";
        $info = $db->getall($sql);
        $return = locoyspider_search_str($info, $str, "categoryname", $p);
        if ($return) {
            $sql = "select categoryname from " . table('category_district') . " where  id='" . $return['parentid'] . "' and  parentid =0 limit 1";
            $area_arr = $db->getone($sql);
            if ($return['parentid'] == 0) {
                $return['parentid'] = $return['id'];
                $return['id'] = 0;
                $area_arr['categoryname'] = $return['categoryname'];
                $return['categoryname'] = "";
            }
            //print_r(array("district"=>$return['parentid'],"sdistrict"=>$return['id'],"district_cn"=>$return['categoryname']));exit;
            return array("district" => $return['parentid'], "district_cn" => $area_arr['categoryname'], "sdistrict" => $return['id'], "sdistrict_cn" => $return['categoryname']);
        } else {
            if (!empty($area)) {
                $sql = "select id,parentid,categoryname from " . table('category_district') . "  where  categoryname='" . $area . "' and  parentid =0 limit 1";
                $area_arr = $db->getone($sql);
            }
            if (empty($area_arr['id'])) {
                $area_arr['id'] = 0;
                $area_arr['categoryname'] = $str;
            }
            $add_city = array("parentid" => $area_arr['id'], "categoryname" => $str);
            $c_id = inserttable(table('category_district'), $add_city, true);
            access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_city_pinyin.php?id=" . $c_id);
            //print_r(array("district"=>$area_arr['id'],"sdistrict"=>$c_id,"district_cn"=>$str));exit;
            if ($area_arr['id'] == 0) {
                $area_arr['id'] = $c_id;
                $c_id = 0;
                $area_arr['categoryname'] = $str;
                $str = "";
            }
            return array("district" => $area_arr['id'], "district_cn" => $area_arr['categoryname'], "sdistrict" => $c_id, "sdistrict_cn" => $str);
        }
    }
}

function get_oldtrade($str) {
    switch ($str) {
        case "30": return "教育/培训";
        case "31": return "医疗、保健、卫生服务";
    }
}

function get_resumetrade($str = NULL) {
    global $db, $locoyspider;
    $sql = "select c_id,c_name from " . table('category') . " where c_alias='QS_trade'  and c_id=45 LIMIT 1"; //默认其他行业
    $info = $db->getone($sql);
    $default = array("id" => $info['c_id'], "cn" => $info['c_name']);
    if (empty($str)) {
        return $default;
    } else {
        $sql = "select c_id,c_name from " . table('category') . "  where c_alias='QS_trade'";
        $info = $db->getall($sql);
        $return = locoyspider_search_str($info, $str, "c_name");
        if ($return) {
            return array("id" => $return['c_id'], "cn" => $return['c_name']);
        } else {
            $trade_in_arr = array("c_name" => $str, "c_alias" => "QS_trade");
            $trade_id = inserttable(table('category'), $trade_in_arr, TRUE);
            return array("id" => $trade_id, "cn" => $str);
        }
    }
}

function conversion_add_resume_jobs($pid, $uid, $str) {
    global $db;
    $str = trim($str);
    $arr = explode("-", $str);
    if (is_array($arr) && !empty($arr)) {
        foreach ($arr as $a) {
            $a = explode(".", $a);
            $setsqlarr['uid'] = intval($uid);
            $setsqlarr['pid'] = intval($pid);
            $setsqlarr['category'] = intval($a[0]);
            $setsqlarr['subclass'] = intval($a[1]);
            $sql = "select * from " . table('resume_jobs') . " where pid='" . intval($pid) . "' and uid='" . intval($uid) . "' and category='" . intval($a[0]) . "' and subclass='" . intval($a[1]) . "'";
            $result = $db->getone($sql);
            if (empty($result)) {
                if (!conversion_inserttable(table('resume_jobs'), $setsqlarr))
                    return false;
            }
        }
    }
}

function convert_datefm1($date, $format, $separator = "/") {
    if ($format == "1") {
        return date("Y-m-d", $date);
    } else {
        if (!preg_match("/^[0-9]{4}(\\" . $separator . ")[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/", $date))
            return $date;
        $date = explode($separator, $date);
        return mktime(0, 0, 0, $date[1], $date[2], $date[0]);
    }
}

function get_category_arr($str) {
    global $db, $locoyspider;
    if (strstr($str, "-")) {
        $jc_arr = explode("-", $str);
    } else {
        $jc_arr[0] = $str;
        $jc_arr[1] = "其它职位";
    }
    if (substr($jc_arr[0], -2) == "类") {
        $jc_arr[0] = substr($jc_arr[0], 0, strlen($jc_arr[0]) - 2);
    }
    if ($jc_arr[0] == "不限" || $jc_arr[0] == "其他" || $jc_arr[0] == "其它") {
        $jc_arr[0] = "其它";
    }
    if (strstr($jc_arr[1], "&#183;")) {
        $postion = strpos($jc_arr[1], "（");
        $jc_arr[1] = substr($jc_arr[1], 0, $postion);
    }
    if (substr($jc_arr[1], -2) == "类") {
        $jc_arr[1] = substr($jc_arr[1], 0, strlen($jc_arr[1]) - 2);
    }
    $sql = "select id,categoryname from " . table('category_jobs') . " where parentid = 0";
    $big_info = $db->getall($sql);
    $big_return = locoyspider_search_str($big_info, $jc_arr[0], "categoryname", 1);
    if (!$big_return) {
        $big_return['id'] = 7;
        $big_return['categoryname'] = "其它";
    }
    $sql = "select id,parentid,categoryname from " . table('category_jobs') . " where parentid = " . $big_return['id'];
    $small_info = $db->getall($sql);
    $small_return = locoyspider_search_str($small_info, $jc_arr[1], "categoryname", 50);
    if (!$small_return) {
        $sql = "select id,parentid,categoryname from " . table('category_jobs') . " where parentid = " . $big_return['id'] . " and categoryname = '其它职位'";
        $small_return = $db->getone($sql);
    }
    $jc_re = array("category" => $big_return['id'], "category_cn" => $big_return['categoryname'], "subclass" => $small_return['id'], "subclass_cn" => $small_return['categoryname']);
    //var_dump($jc_re);
    return $jc_re;
}

function conversion_inserttable($tablename, $insertsqlarr, $returnid = 0, $replace = false, $silent = 0) {
    global $db;
    $insertkeysql = $insertvaluesql = $comma = '';
    foreach ($insertsqlarr as $insert_key => $insert_value) {
        $insertkeysql .= $comma . '`' . $insert_key . '`';
        $insertvaluesql .= $comma . '\'' . $insert_value . '\'';
        $comma = ', ';
    }
    $method = $replace ? 'REPLACE' : 'INSERT';
    $state = $db->query($method . " INTO $tablename ($insertkeysql) VALUES ($insertvaluesql)", $silent ? 'SILENT' : '');
    if ($returnid && !$replace) {
        return $db->insert_id();
    } else {
        return $state;
    }
}

function get_personal_user_inusername($username) {
    global $db;
    $sql = "select * from " . table('members') . " where username = '{$username}' and utype=2 LIMIT 1";
    return $db->getone($sql);
}

function del_resume($id) {
    global $db;
    $db->query("Delete from " . table('resume') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from " . table('resume_tmp') . " WHERE id='" . $id . "' LIMIT 1");
    $db->query("Delete from " . table('resume_education') . " WHERE pid='" . $id . "'");
    $db->query("Delete from " . table('resume_work') . " WHERE pid='" . $id . "'");
    $db->query("Delete from " . table('resume_training') . " WHERE pid='" . $id . "'");
    $db->query("Delete from " . table('resume_search_rtime') . " WHERE id='" . $id . "'");
    $db->query("Delete from " . table('resume_search_key') . " WHERE id='" . $id . "'");
    $db->query("Delete from " . table('resume_search_tag') . " WHERE id='" . $id . "'");
    $db->query("Delete from " . table('resume_jobs') . " WHERE pid='" . $id . "'");
}

function del_resume_all($id) {
    global $db;
    del_resume($id);
    $db->query("Delete from " . table('personal_jobs_apply') . " WHERE resume_id='" . $id . "'");
}

?>