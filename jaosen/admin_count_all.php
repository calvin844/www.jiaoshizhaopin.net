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
    $end = strtotime(date('Y-m-d', time()));
    $start = $end - 86400;
    $start = !empty($_GET['start']) ? strtotime($_GET['start']) : $start;
    $end = !empty($_GET['end']) ? strtotime($_GET['end']) : $end;
    $sql = "select * from " . table('category_district');
    $district_arr = $db->getall($sql);
    $district_arr[] = array('id' => 0, 'parentid' => 0, 'categoryname' => '全国');
    foreach ($district_arr as $d) {
        $data[$d['id']] = $d;
        /**
        $dsql = "";
        if ($d['id'] > 0) {
            if ($d['parentid'] > 0) {
                $dsql = " and uid in(select uid from " . table('company_profile') . " where sdistrict = " . $d['id'] . ")";
            } else {
                $dsql = " and uid in(select uid from " . table('company_profile') . " where district = " . $d['id'] . ")";
            }
        }
        $sql = "select count(*) as company_reg from " . table('members') . " where utype = 1 and reg_time > " . $start . " and reg_time < " . $end . $dsql;
        $company_reg = $db->getone($sql);
        $data[$d['id']]['company_reg'] = $company_reg['company_reg'];
        if ($d['id'] > 0) {
            if ($d['parentid'] > 0) {
                $dsql = " and company_uid in(select uid from " . table('company_profile') . " where sdistrict = " . $d['id'] . ")";
            } else {
                $dsql = " and company_uid in(select uid from " . table('company_profile') . " where district = " . $d['id'] . ")";
            }
        }
        $sql = "select  count(1) as down_resume from " . table('company_down_resume') . " where down_addtime > " . $start . " and down_addtime < " . $end . $dsql;
        $down_resume = $db->getone($sql);
        $data[$d['id']]['down_resume_total'] = $down_resume['down_resume'];
        $dsql = "";
        if ($d['id'] > 0) {
            if ($d['parentid'] > 0) {
                $dsql = " and sdistrict = " . $d['id'];
            } else {
                $dsql = " and district = " . $d['id'];
            }
        }
        $sql = "select count(*) as jobs_total from " . table('jobs') . " where addtime > " . $start . " and addtime < " . $end . $dsql;
        $jobs_total = $db->getone($sql);
        $sql = "select count(*) as jobs_tmp_total from " . table('jobs_tmp') . " where addtime > " . $start . " and addtime < " . $end . $dsql;
        $jobs_tmp_total = $db->getone($sql);
        $jobs_total = intval($jobs_total['jobs_total']) + intval($jobs_tmp_total['jobs_tmp_total']);
        $data[$d['id']]['jobs_total'] = $jobs_total;

        $sql = "select count(*) as jobs_refreshtime_total from " . table('jobs') . " where refreshtime > " . $start . " and refreshtime < " . $end . $dsql;
        $jobs_refreshtime_total = $db->getone($sql);
        $sql = "select count(*) as jobs_refreshtime_tmp_total from " . table('jobs_tmp') . " where refreshtime > " . $start . " and refreshtime < " . $end . $dsql;
        $jobs_refreshtime_tmp_total = $db->getone($sql);
        $jobs_refreshtime_total = intval($jobs_refreshtime_total['jobs_refreshtime_total']) + intval($jobs_refreshtime_tmp_total['jobs_refreshtime_tmp_total']);
        $data[$d['id']]['jobs_refreshtime_total'] = $jobs_refreshtime_total;

        $sql = "select count(*) as resume from " . table('resume') . " where audit = 1 and addtime > " . $start . " and addtime < " . $end . $dsql;
        $resume = $db->getone($sql);
        $data[$d['id']]['resume'] = $resume['resume'];

        $sql = "select count(*) as resume_refreshtime from " . table('resume') . " where audit = 1 and refreshtime > " . $start . " and refreshtime < " . $end . $dsql;
        $resume_refreshtime = $db->getone($sql);
        $data[$d['id']]['resume_refreshtime'] = $resume_refreshtime['resume_refreshtime'];

        $sql = "select count(*) as article_total from " . table('article') . " where addtime > " . $start . " and addtime < " . $end . $dsql;
        $article_total = $db->getone($sql);
        $data[$d['id']]['article_total'] = $article_total['article_total'];


        if ($d['id'] > 0) {
            if ($d['parentid'] > 0) {
                $dsql = " and uid in(select uid from " . table('resume') . " where sdistrict = " . $d['id'] . ")";
            } else {
                $dsql = " and uid in(select uid from " . table('resume') . " where district = " . $d['id'] . ")";
            }
        }
        $sql = "select count(*) as person_reg from " . table('members') . " where utype = 2 and reg_time > " . $start . " and reg_time < " . $end . $dsql;
        $person_reg = $db->getone($sql);
        $data[$d['id']]['person_reg'] = $person_reg['person_reg'];

        if ($d['id'] > 0) {
            if ($d['parentid'] > 0) {
                $dsql = " and personal_uid in(select uid from " . table('resume') . " where sdistrict = " . $d['id'] . ")";
            } else {
                $dsql = " and personal_uid in(select uid from " . table('resume') . " where district = " . $d['id'] . ")";
            }
        }
        $sql = "select count(*) as apply_total from " . table('personal_jobs_apply') . " where apply_addtime > " . $start . " and apply_addtime < " . $end . $dsql;
        $apply_total = $db->getone($sql);
        $data[$d['id']]['apply_total'] = $apply_total['apply_total'];
        $sql = "select count(*) as article_apply_total from " . table('jiaoshi_article_apply') . " where apply_addtime > " . $start . " and apply_addtime < " . $end . $dsql;
        $article_apply_total = $db->getone($sql);
        $data[$d['id']]['article_apply_total'] = $article_apply_total['article_apply_total'];
         * **/
    }
    foreach ($data as $d) {
        if ($d['parentid'] == 0) {
            $all_data[$d['id']] = $d;
        }
    }
    foreach ($data as $d) {
        if ($d['parentid'] > 0) {
            $all_data[$d['parentid']]['son'][] = $d;
        }
    }
    $smarty->assign('all_data', $all_data);
    $smarty->display('count_all/index.htm');
}

if ($act == 'ajax') {
    $end = strtotime(date('Y-m-d', time()));
    $start = $end - 86400;
    $start = !empty($_GET['start']) ? strtotime($_GET['start']) : $start;
    $end = !empty($_GET['end']) ? strtotime($_GET['end']) : $end;
    $did = !empty($_GET['did']) ? intval($_GET['did']) : 0;
    if ($did > 0) {
        $sql = "select * from " . table('category_district') . " where id = " . $did;
        $d = $db->getone($sql);
    }
    $data_str = $did;
    $dsql = "";
    if ($d['id'] > 0) {
        if ($d['parentid'] > 0) {
            $dsql = " and uid in(select uid from " . table('company_profile') . " where sdistrict = " . $d['id'] . ")";
        } else {
            $dsql = " and uid in(select uid from " . table('company_profile') . " where district = " . $d['id'] . ")";
        }
    }
    $sql = "select count(*) as company_reg from " . table('members') . " where utype = 1 and reg_time > " . $start . " and reg_time < " . $end . $dsql;
    $company_reg = $db->getone($sql);
    $data[$d['id']]['company_reg'] = $company_reg['company_reg'];
    if ($d['id'] > 0) {
        if ($d['parentid'] > 0) {
            $dsql = " and company_uid in(select uid from " . table('company_profile') . " where sdistrict = " . $d['id'] . ")";
        } else {
            $dsql = " and company_uid in(select uid from " . table('company_profile') . " where district = " . $d['id'] . ")";
        }
    }
    $sql = "select  count(1) as down_resume from " . table('company_down_resume') . " where down_addtime > " . $start . " and down_addtime < " . $end . $dsql;
    $down_resume = $db->getone($sql);
    $data[$d['id']]['down_resume_total'] = $down_resume['down_resume'];
    $dsql = "";
    if ($d['id'] > 0) {
        if ($d['parentid'] > 0) {
            $dsql = " and sdistrict = " . $d['id'];
        } else {
            $dsql = " and district = " . $d['id'];
        }
    }
    $sql = "select count(*) as jobs_total from " . table('jobs') . " where addtime > " . $start . " and addtime < " . $end . $dsql;
    $jobs_total = $db->getone($sql);
    $sql = "select count(*) as jobs_tmp_total from " . table('jobs_tmp') . " where addtime > " . $start . " and addtime < " . $end . $dsql;
    $jobs_tmp_total = $db->getone($sql);
    $jobs_total = intval($jobs_total['jobs_total']) + intval($jobs_tmp_total['jobs_tmp_total']);
    $data[$d['id']]['jobs_total'] = $jobs_total;

    $sql = "select count(*) as jobs_refreshtime_total from " . table('jobs') . " where refreshtime > " . $start . " and refreshtime < " . $end . $dsql;
    $jobs_refreshtime_total = $db->getone($sql);
    $sql = "select count(*) as jobs_refreshtime_tmp_total from " . table('jobs_tmp') . " where refreshtime > " . $start . " and refreshtime < " . $end . $dsql;
    $jobs_refreshtime_tmp_total = $db->getone($sql);
    $jobs_refreshtime_total = intval($jobs_refreshtime_total['jobs_refreshtime_total']) + intval($jobs_refreshtime_tmp_total['jobs_refreshtime_tmp_total']);
    $data[$d['id']]['jobs_refreshtime_total'] = $jobs_refreshtime_total;

    $sql = "select count(*) as resume from " . table('resume') . " where audit = 1 and addtime > " . $start . " and addtime < " . $end . $dsql;
    $resume = $db->getone($sql);
    $data[$d['id']]['resume'] = $resume['resume'];

    $sql = "select count(*) as resume_refreshtime from " . table('resume') . " where audit = 1 and refreshtime > " . $start . " and refreshtime < " . $end . $dsql;
    $resume_refreshtime = $db->getone($sql);
    $data[$d['id']]['resume_refreshtime'] = $resume_refreshtime['resume_refreshtime'];

    $sql = "select count(*) as article_total from " . table('article') . " where addtime > " . $start . " and addtime < " . $end . $dsql;
    $article_total = $db->getone($sql);
    $data[$d['id']]['article_total'] = $article_total['article_total'];


    if ($d['id'] > 0) {
        if ($d['parentid'] > 0) {
            $dsql = " and uid in(select uid from " . table('resume') . " where sdistrict = " . $d['id'] . ")";
        } else {
            $dsql = " and uid in(select uid from " . table('resume') . " where district = " . $d['id'] . ")";
        }
    }
    $sql = "select count(*) as person_reg from " . table('members') . " where utype = 2 and reg_time > " . $start . " and reg_time < " . $end . $dsql;
    $person_reg = $db->getone($sql);
    $data[$d['id']]['person_reg'] = $person_reg['person_reg'];

    if ($d['id'] > 0) {
        if ($d['parentid'] > 0) {
            $dsql = " and personal_uid in(select uid from " . table('resume') . " where sdistrict = " . $d['id'] . ")";
        } else {
            $dsql = " and personal_uid in(select uid from " . table('resume') . " where district = " . $d['id'] . ")";
        }
    }
    $sql = "select count(*) as apply_total from " . table('personal_jobs_apply') . " where apply_addtime > " . $start . " and apply_addtime < " . $end . $dsql;
    $apply_total = $db->getone($sql);
    $data[$d['id']]['apply_total'] = $apply_total['apply_total'];
    $sql = "select count(*) as article_apply_total from " . table('jiaoshi_article_apply') . " where apply_addtime > " . $start . " and apply_addtime < " . $end . $dsql;
    $article_apply_total = $db->getone($sql);
    $data[$d['id']]['article_apply_total'] = $article_apply_total['article_apply_total'];


    $data_str .= "-" . $data[$d['id']]['company_reg'] . "-" . $data[$d['id']]['jobs_total'] . "-" . $data[$d['id']]['jobs_refreshtime_total'] . "-" . $data[$d['id']]['down_resume_total'] . "-" . $data[$d['id']]['person_reg'] . "-" . $data[$d['id']]['resume'] . "-" . $data[$d['id']]['resume_refreshtime'] . "-" . $data[$d['id']]['apply_total'] . "-" . $data[$d['id']]['article_total'] . "-" . $data[$d['id']]['article_apply_total'];
    echo $data_str;
    exit;
}
?>