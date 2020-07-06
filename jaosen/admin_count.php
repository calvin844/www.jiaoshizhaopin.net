<?php

/*
 * 74cms 个人
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../data/config.php');
require_once(dirname(__FILE__) . '/include/admin_common.inc.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'index';
if ($act == 'index') {
    $sql = "select * from " . table('category_district') . " where parentid = 0";
    $district = $db->getall($sql);
    $smarty->assign('district', $district);
    $smarty->display('count/index.htm');
} elseif ($act == "get_city") {
    $parent_id = intval($_GET['pid']);
    $sql = "select * from " . table('category_district') . " where parentid = " . $parent_id;
    $city = $db->getall($sql);
    $city_str = "";
    foreach ($city as $c) {
        $city_str.=$c['id'] . "-" . $c['categoryname'] . "||";
    }
    $city_str = trim($city_str, "||");
    echo $city_str;
} elseif ($act == "search") {
    $province = intval($_POST['province_id']);
    $city = intval($_POST['city_id']);
    $start = strtotime($_POST['start']);
    $end = strtotime($_POST['end']);
    $dsql = "";
    if ($province > 0) {
        if ($city > 0) {
            $dsql = " and uid in(select uid from " . table('company_profile') . " where sdistrict = " . $city . ")";
        } else {
            $dsql = " and uid in(select uid from " . table('company_profile') . " where district = " . $province . ")";
        }
    }
    $sql = "select count(*) as company_total from " . table('members') . " where utype = 1" . $dsql;
    $company_total = $db->getone($sql);
    $data['company_total'] = $company_total['company_total'];

    $sql = "select count(*) as company_reg from " . table('members') . " where utype = 1 and reg_time > " . $start . " and reg_time < " . $end . $dsql;
    $company_reg = $db->getone($sql);
    $data['company_reg'] = $company_reg['company_reg'];

    if ($province > 0) {
        if ($city > 0) {
            $dsql = " and company_uid in(select uid from " . table('company_profile') . " where sdistrict = " . $city . ")";
        } else {
            $dsql = " and company_uid in(select uid from " . table('company_profile') . " where district = " . $province . ")";
        }
    }
    $sql = "select  count(1) as company_uid from " . table('company_down_resume') . " where down_addtime > " . $start . " and down_addtime < " . $end . $dsql . " GROUP BY company_uid";
    $down_resume = $db->getone($sql);
    $down_resume = $down_resume['company_uid'];

    $sql = "select   count(1) as company_uid from " . table('company_favorites') . " where favoritesa_ddtime > " . $start . " and favoritesa_ddtime < " . $end . $dsql . " GROUP BY company_uid";
    $favorites_resume = $db->getone($sql);
    $favorites_resume = $favorites_resume['company_uid'];


    if ($province > 0) {
        if ($city > 0) {
            $dsql = " and sdistrict = " . $city;
        } else {
            $dsql = " and district = " . $province;
        }
    }
    $sql = "select count(*) as jobs_total from " . table('jobs') . " where addtime > " . $start . " or deadline < " . $end . $dsql;
    $jobs_total = $db->getone($sql);
    $sql = "select count(*) as jobs_tmp_total from " . table('jobs_tmp') . " where addtime > " . $start . " or deadline < " . $end . $dsql;
    $jobs_tmp_total = $db->getone($sql);
    $jobs_total = intval($jobs_total['jobs_total']) + intval($jobs_tmp_total['jobs_tmp_total']);
    $data['jobs_total'] = $jobs_total;

    $company_user_total = array();
    $sql = "select uid from " . table('jobs') . " where addtime > " . $start . " and addtime < " . $end . $dsql . " GROUP BY uid";
    $jobs_addtime = $db->getall($sql);
    $jobs_addtime = count($jobs_addtime);

    $sql = "select uid from " . table('jobs_tmp') . " where addtime > " . $start . " and addtime < " . $end . $dsql . " GROUP BY uid";
    $jobs_addtime_tmp = $db->getall($sql);
    $jobs_addtime_tmp = count($jobs_addtime_tmp);

    $sql = "select uid from " . table('jobs') . " where refreshtime > " . $start . " and refreshtime < " . $end . $dsql . " GROUP BY uid";
    $jobs_refreshtime = $db->getall($sql);
    $jobs_refreshtime = count($jobs_refreshtime);

    $sql = "select uid from " . table('jobs_tmp') . " where refreshtime > " . $start . " and refreshtime < " . $end . $dsql . " GROUP BY uid";
    $jobs_refreshtime_tmp = $db->getall($sql);
    $jobs_refreshtime_tmp = count($jobs_refreshtime_tmp);

    $data['company_user_total'] = $jobs_addtime + $jobs_addtime_tmp + $jobs_refreshtime + $jobs_refreshtime_tmp + $down_resume + $favorites_resume;



    if ($province > 0) {
        if ($city > 0) {
            $dsql = " and uid in(select uid from " . table('resume') . " where sdistrict = " . $city . ")";
        } else {
            $dsql = " and uid in(select uid from " . table('resume') . " where district = " . $province . ")";
        }
    }
    $sql = "select count(*) as person_total from " . table('members') . " where utype = 2 " . $dsql;
    $person_total = $db->getone($sql);
    $data['person_total'] = $person_total['person_total'];
    $sql = "select count(*) as person_reg from " . table('members') . " where utype = 2 and reg_time > " . $start . " and reg_time < " . $end . $dsql;
    $person_reg = $db->getone($sql);
    $data['person_reg'] = $person_reg['person_reg'];


    if ($province > 0) {
        if ($city > 0) {
            $dsql = " and sdistrict = " . $city;
        } else {
            $dsql = " and district = " . $province;
        }
    }
    $sql = "select count(*) as resume from " . table('resume') . " where audit = 1 and addtime > " . $start . " and addtime < " . $end . $dsql;
    $resume = $db->getone($sql);
    $data['resume'] = $resume['resume'];


    $sql = "select uid from " . table('resume') . " where refreshtime > " . $start . " and refreshtime < " . $end . $dsql . " GROUP BY uid";
    $person_refreshtime = $db->getall($sql);
    $person_refreshtime = count($person_refreshtime);



    if ($province > 0) {
        if ($city > 0) {
            $dsql = " and personal_uid in(select uid from " . table('resume') . " where sdistrict = " . $city . ")";
        } else {
            $dsql = " and personal_uid in(select uid from " . table('resume') . " where district = " . $province . ")";
        }
    }
    $sql = "select personal_uid from " . table('personal_jobs_apply') . " where apply_addtime > " . $start . " and apply_addtime < " . $end . $dsql . " GROUP BY personal_uid";
    $person_apply = $db->getall($sql);
    $person_apply = count($person_apply);
    $sql = "select personal_uid from " . table('personal_favorites') . " where addtime > " . $start . " and addtime < " . $end . $dsql . " GROUP BY personal_uid";
    $personal_favorites = $db->getall($sql);
    $personal_favorites = count($personal_favorites);

    $data['user_total'] = $person_refreshtime + $person_apply + $personal_favorites;

    $sql = "select count(*) as apply_total from " . table('personal_jobs_apply') . " where apply_addtime > " . $start . " and apply_addtime < " . $end . $dsql;
    $apply_total = $db->getone($sql);
    $data['apply_total'] = $apply_total['apply_total'];

    if ($province > 0) {
        $sql = "select categoryname from " . table('category_district') . " where id=" . $province;
        $province_arr = $db->getone($sql);
        $smarty->assign('province_cn', $province_arr['categoryname']);
    } else {
        $smarty->assign('province_cn', "全国");
    }
    if ($city > 0) {
        $sql = "select categoryname from " . table('category_district') . " where id=" . $city;
        $city_arr = $db->getone($sql);
        $smarty->assign('city_cn', " - " . $city_arr['categoryname']);
    } else {
        $smarty->assign('city_cn', "");
    }
    foreach ($data as $k => $v) {
        $data[$k] = empty($v) ? 0 : $v;
    }
    $sql = "select * from " . table('category_district') . " where parentid = 0";
    $district = $db->getall($sql);
    $smarty->assign('district', $district);

    $smarty->assign('person_total', $data['person_total']);
    $smarty->assign('resume', $data['resume']);
    $smarty->assign('person_reg', $data['person_reg']);
    $smarty->assign('user_total', $data['user_total']);
    $smarty->assign('company_total', $data['company_total']);
    $smarty->assign('jobs_total', $data['jobs_total']);
    $smarty->assign('company_reg', $data['company_reg']);
    $smarty->assign('company_user_total', $data['company_user_total']);
    $smarty->assign('apply_total', $data['apply_total']);
    $smarty->assign('start', $_POST['start']);
    $smarty->assign('end', $_POST['end']);
    $smarty->display('count/result.htm');
}
?>