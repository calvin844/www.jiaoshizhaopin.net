<?php

/*
 * 74cms 下载简历
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/personal/personal_common.php');
$act = empty($_GET['act']) ? "form" : $_GET['act'];
if ($act == "form") {
    $sql = "select * from " . table('category_district') . " where parentid = 0";
    $district = $db->getall($sql);
    $sql = "select * from " . table('category_jobs') . " where parentid = 0 order by category_order desc";
    $parent_jobs = $db->getall($sql);
    $smarty->assign('district', $district);
    $smarty->assign('parent_jobs', $parent_jobs);
    $smarty->display('user/make_info2.htm');
} elseif ($act == "get_jobs") {
    $parent_id = intval($_GET['pid']);
    $sql = "select * from " . table('category_jobs') . " where parentid = " . $parent_id;
    $jobs = $db->getall($sql);
    $jobs_str = "";
    foreach ($jobs as $c) {
        $jobs_str.=$c['id'] . "-" . $c['categoryname'] . "||";
    }
    $jobs_str = trim($jobs_str, "||");
    echo $jobs_str;
} elseif ($act == "get_city") {
    $parent_id = intval($_GET['pid']);
    $city_str = "";
    if ($parent_id > 0 && $parent_id != 879) {
        $sql = "select * from " . table('category_district') . " where parentid = " . $parent_id;
        $city = $db->getall($sql);
        $city_str = "";
        foreach ($city as $c) {
            $city_str.=$c['id'] . "-" . $c['categoryname'] . "||";
        }
        $city_str = trim($city_str, "||");
    }
    echo $city_str;
} elseif ($act == "save") {
    $up['uid'] = $_SESSION['uid'];
    /*
      $nature_arr = explode("-", trim($_POST['nature']));
      $up['nature'] = $nature_arr[0];
      $up['nature_cn'] = $nature_arr[1];
     * 
     */
    $up['nature'] = 62;
    $up['nature_cn'] = "全职";
    $experience_arr = explode("||", trim($_POST['experience']));
    $up['experience'] = $experience_arr[0];
    $up['experience_cn'] = $experience_arr[1];
    $up['district'] = intval($_POST['district']);
    $up['district'] = $up['district'] == 0 || $up['district'] == 879 ? 0 : $up['district'];
    if ($up['district'] == 0) {
        $up['sdistrict'] = 0;
        $up['district_cn'] = "全国";
    } else {
        $up['sdistrict'] = isset($_POST['sdistrict']) ? intval($_POST['sdistrict']) : 0;
        $sql = "select * from " . table('category_district') . " where id = " . $up['district'];
        $district_cn = $db->getone($sql);
        $district_cn = $district_cn['categoryname'];
        if ($up['sdistrict'] != 0) {
            $sql = "select * from " . table('category_district') . " where id = " . $up['sdistrict'];
            $sdistrict_cn = $db->getone($sql);
            $sdistrict_cn = !empty($sdistrict_cn) ? "/" . $sdistrict_cn['categoryname'] : "";
            $up['district_cn'] = $district_cn . $sdistrict_cn;
        } else {
            $up['district_cn'] = $district_cn;
        }
    }
    $wage_arr = explode("-", trim($_POST['wage']));
    $up['wage'] = $wage_arr[0];
    $up['wage_cn'] = $wage_arr[1];
    $sql = "select * from " . table('category_jobs') . " where id = " . intval($_POST['parent_job_type_id']);
    $parent_job_cn = $db->getone($sql);
    $parent_job_cn = $parent_job_cn['categoryname'];
    if (intval($_POST['job_type_id']) != 0) {
        $sql = "select * from " . table('category_jobs') . " where id = " . intval($_POST['job_type_id']);
        $job_cn = $db->getone($sql);
        $job_cn = "-" . $job_cn['categoryname'];
    } else {
        $job_cn = "";
    }
    $up['intention_jobs'] = $parent_job_cn . $job_cn;
    $sql = "select * from " . table('resume') . " where uid = " . $_SESSION['uid'];
    $resume = $db->getone($sql);
    $setsqlarr['uid'] = $_SESSION['uid'];
    $setsqlarr['pid'] = intval($resume['id']);
    $setsqlarr['topclass'] = 0;
    $setsqlarr['category'] = intval($_POST['parent_job_type_id']);
    $setsqlarr['subclass'] = intval($_POST['job_type_id']);
    $setsqlarr['district'] = intval($_POST['district']);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $db->insert('resume_jobs', $setsqlarr);
    updatetable(table('resume'), $up, " uid='" . $up['uid'] . "'");
    check_resume($_SESSION['uid'], $resume['id']);

    $infoarr['uid'] = intval($_SESSION['uid']);
    $infoarr['experience'] = $up['experience'];
    $infoarr['experience_cn'] = $up['experience_cn'];
    if (get_userprofile($_SESSION['uid'])) {
        $wheresql = " uid='" . intval($_SESSION['uid']) . "'";
        write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "修改了个人资料");
        updatetable(table('members_info'), $infoarr, $wheresql);
    } else {
        $infoarr['uid'] = intval($_SESSION['uid']);
        write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "修改了个人资料");
        inserttable(table('members_info'), $infoarr);
    }
    header("Location: /user/user_make_info3.php");
}
?>