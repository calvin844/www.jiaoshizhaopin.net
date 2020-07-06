<?php

/*
 * 74cms 企业用户相关
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
require_once(ADMIN_ROOT_PATH . 'include/admin_company_fun.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'jobs';
if ($act == 'jobs') {
    check_permissions($_SESSION['admin_purview'], "jobs_show");
    $audit = intval($_GET['audit']);
    $deadline = intval($_GET['deadline']);
    $jobtype = intval($_GET['jobtype']);
    $source = $_GET['source'];
    if (empty($jobtype)) {
        $jobtype = 1;
        $_GET['jobtype'] = 1;
    }
    if ($jobtype == 1) {
        $tablename = "jobs";
        $audit = '';
        $deadline = $deadline > 2 ? $deadline : '';
    } else {
        $tablename = "jobs_tmp";
    }
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY refreshtime DESC ";
    $wheresqlarr = array();
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if (!empty($key) && $key_type > 0) {
        if ($key_type === 1)
            $wheresql = " WHERE jobs_name like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE companyname like '%{$key}%'";
        elseif ($key_type === 3 && intval($key) > 0)
            $wheresql = " WHERE id =" . intval($key);
        elseif ($key_type === 4 && intval($key) > 0)
            $wheresql = " WHERE company_id =" . intval($key);
        elseif ($key_type === 5 && intval($key) > 0)
            $wheresql = " WHERE uid =" . intval($key);
        $oederbysql = "";
        $tablename = "all";
    }else {
        if ($audit > 0) {
            $wheresqlarr['audit'] = $audit;
        }
        if ($source == 1) {
            !empty($wheresql) ? $wheresql .= " AND robot != '0'" : $wheresql = " WHERE robot != '0'";
        } elseif ($source == 2) {
            !empty($wheresql) ? $wheresql .= " AND robot = 0" : $wheresql = " WHERE robot = 0";
        }
        if (isset($_GET['emergency']) && $_GET['emergency'] <> '') {
            $wheresqlarr['emergency'] = intval($_GET['emergency']);
            $oederbysql = " order BY refreshtime DESC";
        }
        if (isset($_GET['recommend']) && $_GET['recommend'] <> '') {
            $wheresqlarr['recommend'] = intval($_GET['recommend']);
            $oederbysql = " order BY refreshtime DESC";
        }
        if (!empty($wheresqlarr))
            $wheresql = wheresql($wheresqlarr);
        if (!empty($_GET['settr'])) {
            $settr = strtotime("-" . intval($_GET['settr']) . " day");
            $wheresql = empty($wheresql) ? " WHERE refreshtime> " . $settr : $wheresql . " AND refreshtime> " . $settr;
            $oederbysql = " order BY refreshtime DESC ";
        }
        if (!empty($_GET['addsettr'])) {
            $settr = strtotime("-" . intval($_GET['addsettr']) . " day");
            $wheresql = empty($wheresql) ? " WHERE addtime> " . $settr : $wheresql . " AND addtime> " . $settr;
            $oederbysql = " order BY addtime DESC ";
        }
        if ($deadline == 1) {
            $wheresql = empty($wheresql) ? " WHERE deadline< " . time() : $wheresql . " AND deadline> " . time();
            $oederbysql = " order BY deadline DESC ";
        } elseif ($deadline == 2) {
            $wheresql = empty($wheresql) ? " WHERE deadline>  " . time() : $wheresql . " AND deadline> " . time();
            $oederbysql = " order BY deadline DESC ";
        } elseif ($deadline > 2) {
            $settr = strtotime("+{$deadline} day");
            $wheresql = empty($wheresql) ? " WHERE deadline< {$settr}" : $wheresql . " AND deadline<{$settr} ";
            $oederbysql = " order BY deadline DESC ";
        }
        if (!empty($_GET['promote'])) {
            $promote = intval($_GET['promote']);
            if ($promote == -1) {
                $psql = "recommend=0 AND emergency=0 AND stick=0 AND highlight=''";
                $wheresql = empty($wheresql) ? " WHERE {$psql} " : "{$wheresql} AND {$psql} ";
            } elseif ($promote == 1) {
                $psql = "recommend=1";
                $wheresql = empty($wheresql) ? " WHERE {$psql} " : "{$wheresql} AND {$psql} ";
            } elseif ($promote == 2) {
                $psql = "emergency=1";
                $wheresql = empty($wheresql) ? " WHERE {$psql} " : "{$wheresql} AND {$psql} ";
            } elseif ($promote == 3) {
                $psql = "stick=1";
                $wheresql = empty($wheresql) ? " WHERE {$psql} " : "{$wheresql} AND {$psql} ";
            } elseif ($promote == 4) {
                $psql = "highlight<>'' ";
                $wheresql = empty($wheresql) ? " WHERE {$psql} " : "{$wheresql} AND {$psql} ";
            }
            $oederbysql = "";
        }
    }
    if ($tablename == "all") {
        $total_sql = "SELECT COUNT(*) AS num FROM " . table('jobs') . $wheresql . " UNION ALL SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . $wheresql;
    } else {
        $total_sql = "SELECT COUNT(*) AS num FROM " . table($tablename) . $wheresql;
    }
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    if ($tablename == "all") {
        $getsql = "SELECT * FROM " . table('jobs') . $wheresql . " UNION ALL SELECT * FROM " . table('jobs_tmp') . $wheresql;
    } else {
        $getsql = "SELECT * FROM " . table($tablename) . " " . $wheresql . $oederbysql;
    }
    $total[0] = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('jobs') . "", 'get_total');
    $total[1] = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . "", 'get_total');
    if ($jobtype == 2) {
        $total[2] = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . " WHERE audit=1 ", 'get_total');
        $total[3] = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . " WHERE audit=2 ", 'get_total');
        $total[4] = get_mem_cache("SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . " WHERE audit=3 ", 'get_total');
    }
    $jobs = get_jobs($offset, $perpage, $getsql);
    $smarty->assign('pageheader', "职位管理");
    $smarty->assign('jobs', $jobs);
    $smarty->assign('now', time());
    $smarty->assign('total', $total);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('totaljob', $total_val);
    $smarty->assign('cat', get_promotion_cat(1));
    get_token();
    $smarty->display('company/admin_company_jobs.htm');
} elseif ($act == 'jobs_perform') {
    check_token();
    $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : adminmsg("你没有选择职位！", 1);
    if (!empty($_REQUEST['delete'])) {
        check_permissions($_SESSION['admin_purview'], "jobs_del");
        $num = del_jobs($yid);
        if ($num > 0) {
            adminmsg("删除成功！共删除" . $num . "行", 2);
        } else {
            adminmsg("删除失败！", 0);
        }
    } elseif (!empty($_POST['set_audit'])) {
        check_permissions($_SESSION['admin_purview'], "jobs_audit");
        $audit = intval($_POST['audit']);
        $pms_notice = intval($_POST['pms_notice']);
        $reason = trim($_POST['reason']);
        if ($n = edit_jobs_audit($yid, $audit, $reason, $pms_notice)) {
            adminmsg("审核成功！响应行数 {$n}", 2);
        } else {
            adminmsg("审核成功！响应行数 0", 1);
        }
    } elseif (!empty($_GET['refresh'])) {
        if ($n = refresh_jobs($yid)) {
            adminmsg("刷新成功！响应行数 {$n}", 2);
        } else {
            adminmsg("刷新失败！", 0);
        }
    } elseif (!empty($_POST['set_delay'])) {
        $days = intval($_POST['days']);
        if (empty($days)) {
            adminmsg("请填写要延长的天数！", 0);
        }
        if ($n = delay_jobs($yid, $days)) {
            distribution_jobs($yid);
            adminmsg("延长有效期成功！响应行数 {$n}", 2);
        } else {
            adminmsg("操作失败！", 0);
        }
    } elseif (!empty($_REQUEST['export'])) {
        check_permissions($_SESSION['admin_purview'], "jobs_export");
        if (!export_jobs($yid)) {
            adminmsg("导出失败！", 0);
        }
    }
} elseif ($act == 'edit_jobs') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "jobs_edit");
    $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : adminmsg("你没有选择职位！", 1);
    $smarty->assign('pageheader', "职位管理");
    $jobs = get_jobs_one($id);
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('jobs', $jobs);
    $smarty->assign('jobsaudit', get_jobsaudit_one($id));
    $smarty->assign('subsite', get_subsite_list());
    $smarty->display('company/admin_company_jobs_edit.htm');
} elseif ($act == 'editjobs_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "jobs_edit");
    $id = intval($_POST['id']);
    $company_id = intval($_POST['company_id']);
    $company_profile = get_company_one_id($company_id);
    $category_id = explode(".", $_POST['category']);
    $setsqlarr['jobs_name'] = trim($_POST['jobs_name']) ? trim($_POST['jobs_name']) : adminmsg('您没有填写职位名称！', 1);
    $setsqlarr['nature'] = intval($_POST['nature']);
    $setsqlarr['nature_cn'] = trim($_POST['nature_cn']);
    $setsqlarr['topclass'] = intval($category_id[0]);
    $setsqlarr['category'] = intval($category_id[1]);
    $setsqlarr['subclass'] = intval($category_id[2]);
    $setsqlarr['category_cn'] = trim($_POST['category_cn']);
    $setsqlarr['subsite_id'] = intval($_POST['subsite_id']);
    $setsqlarr['amount'] = intval($_POST['amount']);
    $setsqlarr['district'] = intval($_POST['district']);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);
    $setsqlarr['addtime'] = strtotime($_POST['addtime']);
    $setsqlarr['deadline'] = strtotime($_POST['deadline']);
    $setsqlarr['wage'] = intval($_POST['wage']);
    $setsqlarr['wage_cn'] = trim($_POST['wage_cn']);
    $setsqlarr['display'] = intval($_POST['display']);
    $setsqlarr['audit'] = intval($_POST['audit']);
    $setsqlarr['sex'] = intval($_POST['sex']);
    $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
    $setsqlarr['education'] = intval($_POST['education']);
    $setsqlarr['education_cn'] = trim($_POST['education_cn']);
    $setsqlarr['experience'] = intval($_POST['experience']);
    $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
    $setsqlarr['graduate'] = intval($_POST['graduate']);
    $setsqlarr['contents'] = trim($_POST['contents']) ? trim($_POST['contents']) : adminmsg('您没有填写职位描述！', 1);
    $setsqlarr['key'] = $setsqlarr['jobs_name'] . $company_profile['companyname'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'];
    require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
    $sp = new SPWord();
    $setsqlarr['key'] = "{$setsqlarr['jobs_name']} {$company_profile['companyname']} " . $sp->extracttag($setsqlarr['key']);
    $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
    $days = intval($_POST['days']);
    if ($days > 0 && (intval($_POST['olddeadline']) - time()) > 0)
        $setsqlarr['deadline'] = intval($_POST['olddeadline']) + ($days * (60 * 60 * 24));
    if ($days > 0 && (intval($_POST['olddeadline']) - time()) < 0)
        $setsqlarr['deadline'] = strtotime("" . $days . " day");
    $setsqlarr_contact['contact'] = trim($_POST['contact']);
    $setsqlarr_contact['qq'] = trim($_POST['qq']);
    $setsqlarr_contact['telephone'] = trim($_POST['telephone']);
    $setsqlarr_contact['address'] = trim($_POST['address']);
    $setsqlarr_contact['email'] = trim($_POST['email']);
    $setsqlarr_contact['notify'] = trim($_POST['notify']);
    $setsqlarr_contact['contact_show'] = intval($_POST['contact_show']);
    $setsqlarr_contact['email_show'] = intval($_POST['email_show']);
    $setsqlarr_contact['telephone_show'] = intval($_POST['telephone_show']);
    $setsqlarr_contact['address_show'] = intval($_POST['address_show']);
    $setsqlarr_contact['qq_show'] = intval($_POST['qq_show']);

    $wheresql = " id='" . $id . "' ";
    $tb1 = $db->getone("select * from " . table('jobs') . " where id='{$id}' LIMIT 1");
    if (!empty($tb1)) {
        if (!updatetable(table('jobs'), $setsqlarr, $wheresql))
            adminmsg("保存失败！", 0);
    }
    else {
        if (!updatetable(table('jobs_tmp'), $setsqlarr, $wheresql))
            adminmsg("保存失败！", 0);
    }
    $wheresql = " pid=" . $id;
    if (!updatetable(table('jobs_contact'), $setsqlarr_contact, $wheresql))
        adminmsg("保存失败！", 0);
    //
    $searchtab['subsite_id'] = $setsqlarr['subsite_id'];
    $searchtab['nature'] = $setsqlarr['nature'];
    $searchtab['sex'] = $setsqlarr['sex'];
    $searchtab['topclass'] = $setsqlarr['topclass'];
    $searchtab['category'] = $setsqlarr['category'];
    $searchtab['subclass'] = $setsqlarr['subclass'];
    $searchtab['district'] = $setsqlarr['district'];
    $searchtab['sdistrict'] = $setsqlarr['sdistrict'];
    $searchtab['education'] = $setsqlarr['education'];
    $searchtab['experience'] = $setsqlarr['experience'];
    $searchtab['wage'] = $setsqlarr['wage'];
    //
    updatetable(table('jobs_search_wage'), $searchtab, " id='{$id}'");
    updatetable(table('jobs_search_rtime'), $searchtab, " id='{$id}'");
    updatetable(table('jobs_search_stickrtime'), $searchtab, " id='{$id}'");
    updatetable(table('jobs_search_hot'), $searchtab, " id='{$id}'");
    updatetable(table('jobs_search_scale'), $searchtab, " id='{$id}'");
    $searchtab['key'] = $setsqlarr['key'];
    $searchtab['likekey'] = $setsqlarr['jobs_name'] . ',' . $company_profile['companyname'];
    updatetable(table('jobs_search_key'), $searchtab, " id='{$id}' ");
    unset($setsqlarr_contact, $setsqlarr);
    distribution_jobs($id);
    $link[0]['text'] = "返回职位列表";
    $link[0]['href'] = $_POST['url'];
    adminmsg("修改成功！", 2, $link);
}
elseif ($act == 'company_list') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "com_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $orderbysql = " order BY c.id DESC ";
    //var_dump($link);exit;
    if ($_GET['order_p'] == 1) {
        $orderbysql = " order BY p.points DESC ";
    }
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($_GET['license'] == 1) {
        $license_str = "a_";
    } elseif ($_GET['license'] == 2) {
        $license_str = "no";
    } else {
        $license_str = "";
    }
    if ($key && $key_type > 0) {
        if ($key_type === 1)
            $wheresql = " WHERE c.companyname like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE c.id ='" . intval($key) . "'";
        elseif ($key_type === 3)
            $wheresql = " WHERE m.username like '{$key}%'";
        elseif ($key_type === 4)
            $wheresql = " WHERE c.uid ='" . intval($key) . "'";
        elseif ($key_type === 5)
            $wheresql = " WHERE c.address  like '%{$key}%'";
        elseif ($key_type === 6)
            $wheresql = " WHERE c.telephone  like '{$key}%'";
        $orderbysql = "";
    }
    $_GET['audit'] <> "" ? $wheresqlarr['c.audit'] = intval($_GET['audit']) : '';
    $_GET['yellowpages'] <> "" ? $wheresqlarr['c.yellowpages'] = intval($_GET['yellowpages']) : '';
    if (is_array($wheresqlarr)) {
        $wheresql = wheresql($wheresqlarr);
    }
    if (!empty($_GET['settr'])) {
        $settr = strtotime("-" . intval($_GET['settr']) . " day");
        $wheresql = empty($wheresql) ? " WHERE addtime> " . $settr : $wheresql . " AND addtime> " . $settr;
    }
    if (!empty($license_str)) {
        if ($license_str == "a_") {
            $wheresql = empty($wheresql) ? " WHERE license REGEXP BINARY '^a_' " : $wheresql . " AND license  REGEXP BINARY '^a_' ";
        } elseif ($license_str == "no") {
            $wheresql = empty($wheresql) ? " WHERE license REGEXP  '^[^a_]' " : $wheresql . " AND license  REGEXP  '^[^a_]' ";
        }
    }
    $operation_mode = $_CFG['operation_mode'];
    if ($operation_mode == '1') {
        $joinsql = " LEFT JOIN " . table('members') . " AS m ON c.uid=m.uid  LEFT JOIN " . table('members_points') . " AS p ON c.uid=p.uid";
    } else {
        $joinsql = " LEFT JOIN " . table('members') . " AS m ON c.uid=m.uid  LEFT JOIN " . table('members_setmeal') . " AS p ON c.uid=p.uid";
    }
    //同时查询套餐与积分
    $joinsql = " LEFT JOIN " . table('members') . " AS m ON c.uid=m.uid  LEFT JOIN " . table('members_setmeal') . " AS s ON c.uid=s.uid " . "  LEFT JOIN " . table('members_points') . " AS p ON c.uid=p.uid";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_profile') . " AS c" . $joinsql . $wheresql;
    $total_val = get_mem_cache($total_sql, "get_total");
    //$perpage = 30;
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $clist = get_company($offset, $perpage, $joinsql . $wheresql . $orderbysql, $operation_mode);
    $smarty->assign('pageheader', "企业管理");
    $smarty->assign('clist', $clist);
    $smarty->assign('certificate_dir', $certificate_dir);
    $smarty->assign('page', $page->show(3));
    $smarty->display('company/admin_company_list.htm');
} elseif ($act == 'company_perform') {
    check_token();
    $company_id = !empty($_POST['y_id']) ? $_POST['y_id'] : adminmsg("你没有选择企业！", 1);
    if ($_POST['delete']) {
        check_permissions($_SESSION['admin_purview'], "com_del");
        if ($_POST['delete_company'] == 'yes') {
            !del_company($company_id) ? adminmsg("删除企业资料失败！", 0) : "";
        }
        if ($_POST['delete_jobs'] == 'yes') {
            !del_company_alljobs($company_id) ? adminmsg("删除职位失败！", 0) : "";
        }
        if ($_POST['delete_jobs'] <> 'yes' && $_POST['delete_company'] <> 'yes') {
            adminmsg("未选择删除类型！", 1);
        }
        adminmsg("删除成功！", 2);
    }
    if (trim($_POST['set_license'])) {
        check_permissions($_SESSION['admin_purview'], "com_audit");
        $license_flag = $_POST['license'];
        $pms_notice = intval($_POST['pms_notice']);
        !edit_company_license($company_id, $license_flag, $pms_notice) ? adminmsg("设置失败！", 0) : adminmsg("设置成功！", 2);
    }
    if (trim($_POST['set_audit'])) {
        check_permissions($_SESSION['admin_purview'], "com_audit");
        $audit = $_POST['audit'];
        $pms_notice = intval($_POST['pms_notice']);
        $reason = trim($_POST['reason']);
        !edit_company_audit($company_id, intval($audit), $reason, $pms_notice) ? adminmsg("设置失败！", 0) : adminmsg("设置成功！", 2);
    } elseif (!empty($_POST['set_refresh'])) {
        if (empty($_POST['refresh_jobs'])) {
            $refresjobs = false;
        } else {
            $refresjobs = true;
        }
        if ($n = refresh_company($company_id, $refresjobs)) {
            adminmsg("刷新成功！响应行数 {$n} 行", 2);
        } else {
            adminmsg("刷新失败！", 0);
        }
    } elseif (!empty($_REQUEST['export'])) {
        check_permissions($_SESSION['admin_purview'], "company_export");
        if (!export_company($company_id)) {
            adminmsg("导出失败！", 0);
        }
    }
} elseif ($act == 'edit_company_profile') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "com_edit");
    $yid = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : adminmsg("你没有选择企业！", 1);
    $smarty->assign('pageheader', "企业管理");
    $company_profile = get_company_one_id($yid);
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('comaudit', get_comaudit_one($yid));
    $smarty->assign('company_profile', $company_profile);
    $smarty->assign('certificate_dir', $certificate_dir); //营业执照路径
    $smarty->display('company/admin_company_profile_edit.htm');
} elseif ($act == 'company_profile_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "com_edit");
    $setsqlarr = array();
    $contents = array();
    $id = intval($_POST['id']);
    $license_flag = intval($_POST['license_flag']);
    $setsqlarr['audit'] = intval($_POST['audit']);
    $setsqlarr['companyname'] = trim($_POST['companyname']) ? trim($_POST['companyname']) : adminmsg('您没有输入企业名称！', 1);
    $setsqlarr['nature'] = trim($_POST['nature']) ? trim($_POST['nature']) : adminmsg('您选择企业性质！', 1);
    $setsqlarr['nature_cn'] = trim($_POST['nature_cn']) ? trim($_POST['nature_cn']) : adminmsg('您选择企业性质！', 1);
    $setsqlarr['trade'] = trim($_POST['trade']) ? trim($_POST['trade']) : adminmsg('您选择所属行业！', 1);
    $setsqlarr['trade_cn'] = trim($_POST['trade_cn']) ? trim($_POST['trade_cn']) : adminmsg('您选择所属行业！', 1);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']) ? trim($_POST['district_cn']) : adminmsg('您选择所属地区！', 1);
    $setsqlarr['district'] = intval($_POST['district']);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['street'] = intval($_POST['street']);
    $setsqlarr['street_cn'] = trim($_POST['street_cn']);
    $setsqlarr['scale'] = trim($_POST['scale']) ? trim($_POST['scale']) : adminmsg('您选择公司规模！', 1);
    $setsqlarr['scale_cn'] = trim($_POST['scale_cn']) ? trim($_POST['scale_cn']) : adminmsg('您选择公司规模！', 1);
    $setsqlarr['registered'] = trim($_POST['registered']);
    $company_info = get_company_one_id($id);
    edit_company_license($company_info['uid'], $license_flag, 1);
    $setsqlarr['currency'] = trim($_POST['currency']);
    $setsqlarr['address'] = trim($_POST['address']);
    $setsqlarr['contact'] = trim($_POST['contact']);
    $setsqlarr['telephone'] = trim($_POST['telephone']);
    $setsqlarr['email'] = trim($_POST['email']);
    $setsqlarr['yellowpages'] = intval($_POST['yellowpages']);
    $setsqlarr['website'] = trim($_POST['website']);
    $setsqlarr['contents'] = trim($_POST['contents']) ? trim($_POST['contents']) : adminmsg('请填写公司简介！', 1);
    $setsqlarr['contact_show'] = intval($_POST['contact_show']);
    $setsqlarr['email_show'] = intval($_POST['email_show']);
    $setsqlarr['telephone_show'] = intval($_POST['telephone_show']);
    $setsqlarr['address_show'] = intval($_POST['address_show']);

    $wheresql = " id='{$id}' ";
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    if (updatetable(table('company_profile'), $setsqlarr, $wheresql)) {
        $jobarr['companyname'] = $setsqlarr['companyname'];
        $jobarr['trade'] = $setsqlarr['trade'];
        $jobarr['trade_cn'] = $setsqlarr['trade_cn'];
        $jobarr['scale'] = $setsqlarr['scale'];
        $jobarr['scale_cn'] = $setsqlarr['scale_cn'];
        $jobarr['street'] = $setsqlarr['street'];
        $jobarr['street_cn'] = $setsqlarr['street_cn'];
        if (!updatetable(table('jobs'), $jobarr, " uid=" . intval($_POST['cuid']) . ""))
            adminmsg('修改职位部分出错！', 0);
        if (!updatetable(table('jobs_tmp'), $jobarr, " uid=" . intval($_POST['cuid']) . ""))
            adminmsg('修改职位部分出错！', 0);
        if (!updatetable(table('jobfair_exhibitors'), array('companyname' => $jobarr['companyname']), " uid=" . intval($_POST['cuid']) . ""))
            adminmsg('修改公司名称出错！', 0);
        $soarray['trade'] = $jobarr['trade'];
        $soarray['scale'] = $jobarr['scale'];
        $soarray['street'] = $setsqlarr['street'];
        updatetable(table('jobs_search_scale'), $soarray, " uid=" . intval($_POST['cuid']) . "");
        updatetable(table('jobs_search_wage'), $soarray, " uid=" . intval($_POST['cuid']) . "");
        updatetable(table('jobs_search_rtime'), $soarray, " uid=" . intval($_POST['cuid']) . "");
        updatetable(table('jobs_search_stickrtime'), $soarray, " uid=" . intval($_POST['cuid']) . "");
        updatetable(table('jobs_search_hot'), $soarray, " uid=" . intval($_POST['cuid']) . "");
        updatetable(table('jobs_search_key'), $soarray, " uid=" . intval($_POST['cuid']) . "");
        //修改高级职位的企业信息
        $hunterjobarr['companyname'] = $setsqlarr['companyname'];
        $hunterjobarr['companyname_note'] = $setsqlarr['companyname'];
        $hunterjobarr['trade'] = $setsqlarr['trade'];
        $hunterjobarr['trade_cn'] = $setsqlarr['trade_cn'];
        $hunterjobarr['scale'] = $setsqlarr['scale'];
        $hunterjobarr['scale_cn'] = $setsqlarr['scale_cn'];
        $hunterjobarr['nature'] = $setsqlarr['nature'];
        $hunterjobarr['nature_cn'] = $setsqlarr['nature_cn'];
        $hunterjobarr['company_desc'] = $setsqlarr['contents'];
        updatetable(table('hunter_jobs'), $hunterjobarr, " uid=" . intval($_POST['cuid']) . "");
        unset($setsqlarr, $hunterjobarr);
        adminmsg("保存成功！", 2, $link);
    }
    else {
        unset($setsqlarr);
        adminmsg("保存失败！", 0);
    }
} elseif ($act == 'order_list') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "ord_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    require_once(ADMIN_ROOT_PATH . 'include/admin_pay_fun.php');
    $wheresql = " WHERE o.utype=1 ";
    $oederbysql = " order BY o.addtime DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($key && $key_type > 0) {
        if ($key_type === 1)
            $wheresql = " WHERE o.utype=1 AND c.companyname like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE o.utype=1 AND m.username = '{$key}'";
        elseif ($key_type === 3)
            $wheresql = " WHERE o.utype=1 AND o.oid ='" . trim($key) . "'";
        $oederbysql = "";
    }
    else {
        $wheresqlarr['o.utype'] = '1';
        !empty($_GET['is_paid']) ? $wheresqlarr['o.is_paid'] = intval($_GET['is_paid']) : '';
        !empty($_GET['typename']) ? $wheresqlarr['o.payment_name'] = trim($_GET['typename']) : '';
        if (is_array($wheresqlarr))
            $wheresql = wheresql($wheresqlarr);

        if (!empty($_GET['settr'])) {
            $settr = strtotime("-" . intval($_GET['settr']) . " day");
            $wheresql.=empty($wheresql) ? " WHERE " : " AND ";
            $wheresql.="o.addtime> " . $settr;
        }
    }
    $joinsql = " left JOIN " . table('members') . " as m ON o.uid=m.uid LEFT JOIN  " . table('company_profile') . " as c ON o.uid=c.uid ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('order') . " as o " . $joinsql . $wheresql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $orderlist = get_order_list($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "订单管理");
    $smarty->assign('payment_list', get_payment(2));
    $smarty->assign('orderlist', $orderlist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('company/admin_order_list.htm');
} elseif ($act == 'show_order') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "ord_show");
    $smarty->assign('pageheader', "订单管理");
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('payment', get_order_one($_GET['id']));
    $smarty->display('company/admin_order_show.htm');
} elseif ($act == 'order_notes_save') {
    check_token();
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    !$db->query("UPDATE " . table('order') . " SET  notes='" . $_POST['notes'] . "' WHERE id='" . intval($_GET['id']) . "'") ? adminmsg('操作失败', 1) : adminmsg("操作成功！", 2, $link);
}
//设置充值记录（收款开通）
elseif ($act == 'order_set') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "ord_set");
    $smarty->assign('pageheader', "订单管理");
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('payment', get_order_one($_GET['id']));
    $smarty->display('company/admin_order_set.htm');
} elseif ($act == 'order_set_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "ord_set");
    if (order_paid(trim($_POST['oid']))) {
        $link[0]['text'] = "返回列表";
        $link[0]['href'] = $_POST['url'];
        !$db->query("UPDATE " . table('order') . " SET notes='" . $_POST['notes'] . "' WHERE id=" . intval($_GET['id']) . "  LIMIT 1 ") ? adminmsg('操作失败', 1) : adminmsg("操作成功！", 2, $link);
    } else {
        adminmsg('操作失败', 1);
    }
}
//取消会员充值申请
elseif ($act == 'order_del') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "ord_del");
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择项目！", 1);
    if (del_order($id)) {
        adminmsg("取消成功！", 2, $link);
    } else {
        adminmsg("取消失败！", 1);
    }
} elseif ($act == 'meal_members') {
    get_token();
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $wheresql = " WHERE a.effective=1 ";
    $oederbysql = " order BY a.uid DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($key && $key_type > 0) {
        if ($key_type === 1)
            $wheresql.=" AND b.username = '{$key}'";
        elseif ($key_type === 2)
            $wheresql.=" AND b.uid = '" . intval($key) . "' ";
        elseif ($key_type === 3)
            $wheresql.=" AND b.email = '{$key}'";
        elseif ($key_type === 4)
            $wheresql.=" AND b.mobile like '{$key}%'";
        elseif ($key_type === 5)
            $wheresql.=" AND c.companyname like '{$key}%'";
        $oederbysql = "";
    }
    else {
        if (!empty($_GET['setmeal_id'])) {
            $setmeal_id = intval($_GET['setmeal_id']);
            $wheresql.=" AND a.setmeal_id=" . $setmeal_id;
        }
        if (!empty($_GET['settr'])) {
            $settr = intval($_GET['settr']);
            if ($settr == -1) {
                $wheresql.=" AND a.endtime<" . time() . " AND a.endtime>0 ";
            } else {
                $settr = strtotime("{$settr} day");
                $wheresql.="  AND a.endtime>" . time() . " AND a.endtime< {$settr}";
            }
        }
    }
    $joinsql = " LEFT JOIN " . table('members') . " as b ON a.uid=b.uid  LEFT JOIN " . table('company_profile') . " as c ON a.uid=c.uid ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('members_setmeal') . " as a " . $joinsql . $wheresql;
    $total_val = get_mem_cache($total_sql, "get_total");
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $member = get_meal_members_list($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "企业管理");
    $smarty->assign('navlabel', 'meal_members');
    $smarty->assign('member', $member);
    $smarty->assign('setmeal', get_setmeal());
    $smarty->assign('page', $page->show(3));
    $smarty->display('company/admin_company_meal_members.htm');
} elseif ($act == 'meal_log') {
    get_token();
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY a.log_id DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    $operation_mode = $_CFG['operation_mode'];
    $wheresql = " WHERE a.log_mode={$operation_mode} AND a.log_utype=1";
    if ($key && $key_type > 0) {
        if ($key_type === 1)
            $wheresql.="  AND a.log_username = '{$key}'";
        elseif ($key_type === 2)
            $wheresql.="  AND a.log_uid = '" . intval($key) . "' ";
        elseif ($key_type === 3)
            $wheresql.=" AND c.companyname like '{$key}%'";
        $oederbysql = " order BY a.log_id DESC ";
    }
    else {
        if (!empty($_GET['log_type'])) {
            $log_type = intval($_GET['log_type']);
            $wheresql.=" AND  a.log_type=" . $log_type;
        }
        if (!empty($_GET['settr'])) {
            $settr = intval($_GET['settr']);
            $settr = strtotime("-{$settr} day");
            $wheresql.=" AND a.log_addtime> " . $settr;
        }
        if (!empty($_GET['is_money'])) {
            $is_money = intval($_GET['is_money']);
            $wheresql.= " AND a.log_ismoney={$is_money}";
        }
    }
    if ($operation_mode == '1') {
        $joinsql = " LEFT JOIN " . table('members_points') . " as b ON a.log_uid=b.uid  LEFT JOIN " . table('company_profile') . " as c ON a.log_uid=c.uid ";
    } else {
        $joinsql = " LEFT JOIN " . table('members_setmeal') . " as b ON a.log_uid=b.uid  LEFT JOIN " . table('company_profile') . " as c ON a.log_uid=c.uid ";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('members_charge_log') . " as a " . $joinsql . $wheresql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $meallog = get_meal_members_log($offset, $perpage, $joinsql . $wheresql . $oederbysql, $operation_mode);
    $smarty->assign('pageheader', '企业管理');
    $smarty->assign('navlabel', 'meal_log');
    $smarty->assign('meallog', $meallog);
    $smarty->assign('page', $page->show(3));
    $smarty->display('company/admin_company_meal_log.htm');
} elseif ($act == 'meal_log_pie') {
    require_once(ADMIN_ROOT_PATH . 'include/admin_flash_statement_fun.php');
    $pie_type = !empty($_GET['pie_type']) ? intval($_GET['pie_type']) : 1;
    meal_log_pie($pie_type, 1);
    $smarty->assign('pageheader', "企业管理");
    $smarty->assign('navlabel', 'meal_log_pie');
    $smarty->display('company/admin_company_meal_log_pie.htm');
} elseif ($act == 'meallog_del') {
    check_permissions($_SESSION['admin_purview'], "meallog_del");
    check_token();
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择记录！", 1);
    $num = del_meal_log($id);
    if ($num > 0) {
        adminmsg("删除成功！共删除" . $num . "行", 2);
    } else {
        adminmsg("删除失败！", 0);
    }
} elseif ($act == 'meal_delay') {
    $tuid = !empty($_REQUEST['tuid']) ? $_REQUEST['tuid'] : adminmsg("你没有选择会员！", 1);
    $days = intval($_POST['days']);
    if (empty($days)) {
        adminmsg("请填写要延长的天数！", 0);
    }
    if ($n = delay_meal($tuid, $days)) {
        distribution_jobs_uid($tuid);
        adminmsg("延长有效期成功！响应行数 {$n}", 2);
    } else {
        adminmsg("操作失败！", 0);
    }
} elseif ($act == 'members_list') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "com_user_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $wheresql = " WHERE m.utype=1 ";
    $oederbysql = " order BY m.uid DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($key && $key_type > 0) {
        if ($key_type === 1)
            $wheresql.=" AND m.username = '{$key}'";
        elseif ($key_type === 2)
            $wheresql.=" AND m.uid = '" . intval($key) . "' ";
        elseif ($key_type === 3)
            $wheresql.=" AND m.email = '{$key}'";
        elseif ($key_type === 4)
            $wheresql.=" AND m.mobile like '{$key}%'";
        elseif ($key_type === 5)
            $wheresql.=" AND c.companyname like '%{$key}%'";
        $oederbysql = "";
    }
    else {
        if (!empty($_GET['settr'])) {
            $settr = strtotime("-" . intval($_GET['settr']) . " day");
            $wheresql.=" AND m.reg_time> " . $settr;
        }
        if (!empty($_GET['verification'])) {
            if ($_GET['verification'] == "1") {
                $wheresql.=" AND m.email_audit = 1";
            } elseif ($_GET['verification'] == "2") {
                $wheresql.=" AND m.email_audit = 0";
            } elseif ($_GET['verification'] == "3") {
                $wheresql.=" AND m.mobile_audit = 1";
            } elseif ($_GET['verification'] == "4") {
                $wheresql.=" AND m.mobile_audit = 0";
            }
        }
    }
    $joinsql = " LEFT JOIN " . table('company_profile') . " as c ON m.uid=c.uid ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('members') . " as m " . $joinsql . $wheresql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $member = get_member_list($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "企业会员");
    $smarty->assign('member', $member);
    $smarty->assign('page', $page->show(3));
    $smarty->display('company/admin_company_user_list.htm');
} elseif ($act == 'delete_user') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "com_user_del");
    $tuid = !empty($_REQUEST['tuid']) ? $_REQUEST['tuid'] : adminmsg("你没有选择会员！", 1);
    if ($_POST['delete']) {
        if (!empty($_POST['delete_user'])) {
            !delete_company_user($tuid) ? adminmsg("删除会员失败！", 0) : "";
        }
        $company_data = get_company_uid($tuid);
        if (!empty($_POST['delete_company'])) {
            foreach ($company_data as $c) {
                !del_company($c['id']) ? adminmsg("删除企业资料失败！", 0) : "";
            }
        }
        if (!empty($_POST['delete_jobs'])) {
            foreach ($company_data as $c) {
                !del_company_alljobs($c['id']) ? adminmsg("删除职位失败！", 0) : "";
            }
        }
        adminmsg("删除成功！", 2);
    }
} elseif ($act == 'members_add') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "com_user_add");
    $smarty->assign('pageheader', "企业会员");
    $smarty->assign('givesetmeal', get_setmeal(false));
    $smarty->assign('points', get_cache('points_rule'));
    $smarty->display('company/admin_company_user_add.htm');
} elseif ($act == 'members_add_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "com_user_add");
    require_once(ADMIN_ROOT_PATH . 'include/admin_user_fun.php');
    if (strlen(trim($_POST['username'])) < 3)
        adminmsg('用户名必须为3位以上！', 1);
    if (strlen(trim($_POST['password'])) < 6)
        adminmsg('密码必须为6位以上！', 1);
    $sql['username'] = !empty($_POST['username']) ? trim($_POST['username']) : adminmsg('请填写用户名！', 1);
    $sql['password'] = !empty($_POST['password']) ? trim($_POST['password']) : adminmsg('请填写密码！', 1);
    if ($sql['password'] <> trim($_POST['password1'])) {
        adminmsg('两次输入的密码不相同！', 1);
    }
    $sql['utype'] = !empty($_POST['member_type']) ? intval($_POST['member_type']) : adminmsg('你没有选择注册类型！', 1);
    if (empty($_POST['email']) || !preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $_POST['email'])) {
        adminmsg('电子邮箱格式错误！', 1);
    }
    $sql['email'] = trim($_POST['email']);
    if (get_user_inusername($sql['username'])) {
        adminmsg('该用户名已经被使用！', 1);
    }
    if (get_user_inemail($sql['email'])) {
        adminmsg('该 Email 已经被注册！', 1);
    }
    if (defined('UC_API')) {
        include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
        if (uc_user_checkname($sql['username']) <> "1") {
            adminmsg('该用户名已经被使用或者用户名非法！', 1);
            exit();
        } elseif (uc_user_checkemail($sql['email']) <> "1") {
            adminmsg('该 Email已经被使用或者非法！', 1);
            exit();
        } else {
            uc_user_register($sql['username'], $sql['password'], $sql['email']);
        }
    }
    $sql['pwd_hash'] = randstr();
    $sql['password'] = md5(md5($sql['password']) . $sql['pwd_hash'] . $QS_pwdhash);
    $sql['reg_time'] = time();
    $sql['reg_ip'] = $online_ip;
    $insert_id = inserttable(table('members'), $sql, true);
    if ($sql['utype'] == "1") {
        $db->query("INSERT INTO " . table('members_points') . " (uid) VALUES ('{$insert_id}')");
        $db->query("INSERT INTO " . table('members_setmeal') . " (uid) VALUES ('{$insert_id}')");
        if (intval($_POST['is_money']) && $_POST['log_amount']) {
            $amount = round($_POST['log_amount'], 2);
            $ismoney = 2;
        } else {
            $amount = '0.00';
            $ismoney = 1;
        }
        $regpoints_num = intval($_POST['regpoints_num']);
        if ($_POST['regpoints'] == "y") {
            write_memberslog($insert_id, 1, 9001, $sql['username'], "<span style=color:#FF6600>注册会员系统自动赠送!(+{$regpoints_num})</span>", 1, 1010, "注册会员系统自动赠送", "+{$regpoints_num}", "{$regpoints_num}");
            //会员积分变更记录。管理员后台修改会员的积分。3表示：管理员后台修改
            $notes = "操作人：{$_SESSION['admin_name']},说明：后台添加企业会员并赠送(+{$regpoints_num})积分，收取费用：{$amount}元";
            write_setmeallog($insert_id, $sql['username'], $notes, 4, $amount, $ismoney, 1, 1);
            report_deal($insert_id, 1, $regpoints_num);
        }
        $reg_service = intval($_POST['reg_service']);
        if ($reg_service > 0) {
            $service = get_setmeal_one($reg_service);
            write_memberslog($insert_id, 1, 9002, $sql['username'], "开通服务({$service['setmeal_name']})", 2, 1011, "开通服务", "", "");
            set_members_setmeal($insert_id, $reg_service);
            //会员积分变更记录。管理员后台修改会员的积分。3表示：管理员后台修改
            $notes = "操作人：{$_SESSION['admin_name']},说明：后台添加企业会员并开通服务({$service['setmeal_name']})，收取费用：{$amount}元";
            write_setmeallog($insert_id, $sql['username'], $notes, 4, $amount, $ismoney, 2, 1);
        }
        if (intval($_POST['is_money']) && $_POST['log_amount'] && !$notes) {
            $notes = "操作人：{$_SESSION['admin_name']},说明：后台添加企业会员，未赠送积分，未开通套餐，收取费用：{$amount}元";
            write_setmeallog($insert_id, $sql['username'], $notes, 4, $amount, 2, 2, 1);
        }
    }
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "?act=members_list";
    $link[1]['text'] = "继续添加";
    $link[1]['href'] = "?act=members_add";
    adminmsg('添加成功！', 2, $link);
} elseif ($act == 'user_edit') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "com_user_edit");
    $company_user = get_user($_GET['tuid']);
    $smarty->assign('pageheader', "企业会员");
    $company_profile = get_company_one_uid($company_user['uid']);
    $company_user['tpl'] = $company_profile['tpl'];
    $smarty->assign('company_user', $company_user);
    $smarty->assign('userpoints', get_user_points($company_user['uid']));
    $user_limit_points = get_user_limit_points($company_user['uid']);
    $user_limit_points['points'] = !empty($user_limit_points['id']) ? $user_limit_points['points'] : 0;
    $smarty->assign('user_limit_points', $user_limit_points['points']);
    $smarty->assign('setmeal', get_user_setmeal($company_user['uid']));
    $smarty->assign('givesetmeal', get_setmeal(false));
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->display('company/admin_company_user_edit.htm');
} elseif ($act == 'set_account_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "com_user_edit");
    require_once(ADMIN_ROOT_PATH . 'include/admin_user_fun.php');
    $setsqlarr['username'] = trim($_POST['username']);
    $setsqlarr['email'] = trim($_POST['email']);
    $setsqlarr['email_audit'] = intval($_POST['email_audit']);
    $setsqlarr['mobile'] = trim($_POST['mobile']);
    $setsqlarr['mobile_audit'] = intval($_POST['mobile_audit']);
    if ($_POST['qq_openid'] == "1") {
        $setsqlarr['qq_openid'] = '';
    }
    $thisuid = intval($_POST['company_uid']);
    if (strlen($setsqlarr['username']) < 3)
        adminmsg('用户名必须为3位以上！', 1);
    $getusername = get_user_inusername($setsqlarr['username']);
    if (!empty($getusername) && $getusername['uid'] <> $thisuid) {
        adminmsg("用户名 {$setsqlarr['username']}  已经存在！", 1);
    }
    if (empty($setsqlarr['email']) || !preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $setsqlarr['email'])) {
        adminmsg('电子邮箱格式错误！', 1);
    }
    $getemail = get_user_inemail($setsqlarr['email']);
    if (!empty($getemail) && $getemail['uid'] <> $thisuid) {
        adminmsg("Email  {$setsqlarr['email']}  已经存在！", 1);
    }
    if (!empty($setsqlarr['mobile']) && !preg_match("/^(1)\d{10}$/", $setsqlarr['mobile'])) {
        adminmsg('手机号码错误！', 1);
    }
    $getmobile = get_user_inmobile($setsqlarr['mobile']);
    if (!empty($setsqlarr['mobile']) && !empty($getmobile) && $getmobile['uid'] <> $thisuid) {
        adminmsg("手机号 {$setsqlarr['mobile']}  已经存在！", 1);
    }
    if ($_POST['tpl']) {
        $tplarr['tpl'] = trim($_POST['tpl']);
        updatetable(table('company_profile'), $tplarr, " uid='{$thisuid}'");
        updatetable(table('jobs'), $tplarr, " uid='{$thisuid}'");
        updatetable(table('jobs_tmp'), $tplarr, " uid='{$thisuid}'");
        unset($tplarr);
    }
    if (updatetable(table('members'), $setsqlarr, " uid=" . $thisuid . "")) {
        $link[0]['text'] = "返回列表";
        $link[0]['href'] = $_POST['url'];
        adminmsg('修改成功！', 2, $link);
    } else {
        adminmsg('修改失败！', 1);
    }
} elseif ($act == 'userpoints_edit') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "com_user_edit");
    if (intval($_POST['points']) < 1)
        adminmsg('请输入积分！', 1);
    if (trim($_POST['points_notes']) == '')
        adminmsg('请填写积分操作说明！', 1);
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    $user = get_user($_POST['company_uid']);
    $points_type = intval($_POST['points_type']);
    $t = $points_type == 1 ? "+" : "-";
    if ($_POST['do_type'] == 1) {
        report_deal($user['uid'], $points_type, intval($_POST['points']), 0);
    } elseif ($_POST['do_type'] == 2) {
        set_members_limit_points($user['uid'], $points_type, intval($_POST['points']), intval($_POST['days']));
    }
    $points = get_user_points($user['uid']);
    $user_limit_points = get_user_limit_points($_SESSION['uid']);
    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
    write_memberslog(intval($_POST['company_uid']), 1, 9001, $user['username'], " 管理员操作积分({$t}{$_POST['points']})，(剩余积分:{$points}，剩余限时积分:{$user_limit_points})，备注：" . $_POST['points_notes'], 1, 1012, "管理员操作积分", "{$t}{$_POST['points']}", "{$points}");
    //会员积分变更记录。管理员后台修改会员的积分。3表示：管理员后台修改
    $user = get_user($_POST['company_uid']);
    if (intval($_POST['is_money']) && $_POST['log_amount']) {
        $amount = round($_POST['log_amount'], 2);
        $ismoney = 2;
    } else {
        $amount = '0.00';
        $ismoney = 1;
    }
    $notes = "操作人：{$_SESSION['admin_name']},说明：修改会员 {$user['username']} 积分 ({$t}{$_POST['points']})。收取积分金额：{$amount} 元，备注：{$_POST['points_notes']}";
    write_setmeallog($_POST['company_uid'], $user['username'], $notes, 3, $amount, $ismoney, 1, 1);

    adminmsg('保存成功！', 2);
} elseif ($act == 'set_setmeal_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "com_user_edit");
    if (intval($_POST['reg_service']) > 0) {
        if (set_members_setmeal($_POST['company_uid'], $_POST['reg_service'])) {
            $link[0]['text'] = "返回列表";
            $link[0]['href'] = $_POST['url'];
            //会员套餐变更记录。管理员后台修改会员套餐：重新开通套餐。3表示：管理员后台修改
            $user = get_user($_POST['company_uid']);
            if (intval($_POST['is_money']) && $_POST['log_amount']) {
                $amount = round($_POST['log_amount'], 2);
                $ismoney = 2;
            } else {
                $amount = '0.00';
                $ismoney = 1;
            }
            $notes = "操作人：{$_SESSION['admin_name']},说明：为会员 {$user['username']} 重新开通服务，收取服务金额：{$amount}元，服务ID：{$_POST['reg_service']}。";
            write_setmeallog($_POST['company_uid'], $user['username'], $notes, 4, $amount, $ismoney, 2, 1);

            adminmsg('操作成功！', 2, $link);
        } else {
            adminmsg('操作失败！', 1);
        }
    } else {
        adminmsg('请选择服务套餐！', 1);
    }
} elseif ($act == 'edit_setmeal_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "com_user_edit");
    $setsqlarr['jobs_ordinary'] = $_POST['jobs_ordinary'];
    $setsqlarr['download_resume_ordinary'] = $_POST['download_resume_ordinary'];
    $setsqlarr['download_resume_senior'] = $_POST['download_resume_senior'];
    $setsqlarr['interview_ordinary'] = $_POST['interview_ordinary'];
    $setsqlarr['interview_senior'] = $_POST['interview_senior'];
    $setsqlarr['talent_pool'] = $_POST['talent_pool'];
    $setsqlarr['recommend_num'] = intval($_POST['recommend_num']);
    $setsqlarr['recommend_days'] = intval($_POST['recommend_days']);
    $setsqlarr['stick_num'] = intval($_POST['stick_num']);
    $setsqlarr['stick_days'] = intval($_POST['stick_days']);
    $setsqlarr['emergency_num'] = intval($_POST['emergency_num']);
    $setsqlarr['emergency_days'] = intval($_POST['emergency_days']);
    $setsqlarr['highlight_num'] = intval($_POST['highlight_num']);
    $setsqlarr['highlight_days'] = intval($_POST['highlight_days']);
    $setsqlarr['change_templates'] = intval($_POST['change_templates']);
    $setsqlarr['jobsfair_num'] = intval($_POST['jobsfair_num']);
    $setsqlarr['map_open'] = intval($_POST['map_open']);

    $setsqlarr['added'] = $_POST['added'];
    if ($_POST['setendtime'] <> "") {
        $setendtime = convert_datefm($_POST['setendtime'], 2);
        if ($setendtime == '') {
            adminmsg('日期格式错误！', 0);
        } else {
            $setsqlarr['endtime'] = $setendtime;
        }
    } else {
        $setsqlarr['endtime'] = 0;
    }
    if ($_POST['days'] <> "") {
        if (intval($_POST['days']) <> 0) {
            $oldendtime = intval($_POST['oldendtime']);
            $setsqlarr['endtime'] = strtotime("" . intval($_POST['days']) . " days", $oldendtime == 0 ? time() : $oldendtime);
        }
        if (intval($_POST['days']) == "0") {
            $setsqlarr['endtime'] = 0;
        }
    }
    $setmealtime = $setsqlarr['endtime'];
    $company_uid = intval($_POST['company_uid']);
    if ($company_uid) {
        $setmeal = get_user_setmeal($company_uid);
        if (!updatetable(table('members_setmeal'), $setsqlarr, " uid=" . $company_uid . ""))
            adminmsg('修改出错！', 0);
        //会员套餐变更记录。管理员后台修改会员套餐：修改会员。3表示：管理员后台修改
        $setmeal['endtime'] = date('Y-m-d', $setmeal['endtime']);
        $setsqlarr['endtime'] = date('Y-m-d', $setsqlarr['endtime']);
        $setsqlarr['log_amount'] = round($_POST['log_amount']);
        $notes = edit_setmeal_notes($setsqlarr, $setmeal);
        if ($notes) {
            $user = get_user($_POST['company_uid']);
            $ismoney = round($_POST['log_amount']) ? 2 : 1;
            write_setmeallog($company_uid, $user['username'], $notes, 3, $setsqlarr['log_amount'], $ismoney, 2, 1);
        }

        if ($setsqlarr['endtime'] <> "") {
            $setmeal_deadline['setmeal_deadline'] = $setmealtime;
            if (!updatetable(table('jobs'), $setmeal_deadline, " uid='{$company_uid}' AND add_mode='2' "))
                adminmsg('修改出错！', 0);
            if (!updatetable(table('jobs_tmp'), $setmeal_deadline, " uid='{$company_uid}' AND add_mode='2' "))
                adminmsg('修改出错！', 0);
            distribution_jobs_uid($company_uid);
        }
    }
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    adminmsg('操作成功！', 2, $link);
}
elseif ($act == 'userpass_edit') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "com_user_edit");
    if (strlen(trim($_POST['password'])) < 6)
        adminmsg('新密码必须为6位以上！', 1);
    require_once(ADMIN_ROOT_PATH . 'include/admin_user_fun.php');
    $user_info = get_user_inusername($_POST['username']);
    $pwd_hash = $user_info['pwd_hash'];
    $md5password = md5(md5(trim($_POST['password'])) . $pwd_hash . $QS_pwdhash);
    if ($db->query("UPDATE " . table('members') . " SET password = '$md5password'  WHERE uid='" . $user_info['uid'] . "'")) {
        if (defined('UC_API')) {
            include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
            uc_user_edit($user_info['username'], trim($_POST['password']), trim($_POST['password']), "", 1);
        }
        $link[0]['text'] = "返回列表";
        $link[0]['href'] = $_POST['url'];
        adminmsg('操作成功！', 2, $link);
    } else {
        adminmsg('操作失败！', 1);
    }
} elseif ($act == 'userfreeze_edit') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "com_user_edit");
    require_once(ADMIN_ROOT_PATH . 'include/admin_user_fun.php');
    $user_info = get_user_inusername($_POST['username']);
    $freeze = trim($_POST['freeze']);
    if ($db->query("UPDATE " . table('members') . " SET freeze = '" . $freeze . "'  WHERE uid='" . $user_info['uid'] . "'")) {
        $link[0]['text'] = "返回列表";
        $link[0]['href'] = $_POST['url'];
        adminmsg('操作成功！', 2, $link);
    } else {
        adminmsg('操作失败！', 1);
    }
} elseif ($act == 'userstatus_edit') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "com_user_edit");
    if (set_user_status(intval($_POST['status']), intval($_POST['userstatus_uid']))) {
        $link[0]['text'] = "返回列表";
        $link[0]['href'] = $_POST['url'];
        adminmsg('操作成功！', 2, $link);
    } else {
        adminmsg('操作失败！', 1);
    }
} elseif ($act == 'comment_list') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "com_comment");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY c.id DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    $audit = isset($_GET['audit']) ? intval($_GET['audit']) : "";
    if ($key && $key_type > 0) {
        if ($key_type === 1)
            $wheresql = " WHERE m.username  like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE c.content  like '%{$key}%'";
        elseif ($key_type === 3)
            $wheresql = " WHERE c.uid ='" . intval($key) . "'";
        elseif ($key_type === 5)
            $wheresql = " WHERE c.company_id ='" . intval($key) . "'";
        $oederbysql = "";
    }else {
        if ($audit > 0) {
            $wheresql = " WHERE c.audit ='" . intval($audit) . "'";
        }
    }

    $joinsql = " LEFT JOIN " . table('members') . " AS m ON c.uid=m.uid LEFT JOIN " . table('company_profile') . " AS j  ON c.company_id=j.id ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('comment') . " AS c " . $joinsql . $wheresql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $clist = get_comment($offset, $perpage, "SELECT c.*,j.id as company_id,j.companyname,m.uid,m.username FROM " . table('comment') . " AS c " . $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "企业评论");
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('company/admin_comment_list.htm');
} elseif ($act == 'comment_audit') {
    check_permissions($_SESSION['admin_purview'], "comment_audit");
    check_token();
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择职位评论！", 1);
    $audit = intval($_POST['audit']);
    $num = comment_audit($id, $audit);
    if ($num > 0) {
        adminmsg("审核成功！共审核" . $num . "行", 2);
    } else {
        adminmsg("审核成功!共影响{$num}行", 0);
    }
} elseif ($act == 'comment_del') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "com_comment");
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有信息！", 1);
    $n = comment_del($id);
    if ($n > 0) {
        adminmsg("删除成功！共删除 {$n} 行", 2);
    } else {
        adminmsg("删除失败！", 0);
    }
} elseif ($act == 'del_auditreason') {
    //check_token();
    check_permissions($_SESSION['admin_purview'], "jobs_audit"); //用的是职位审核的权限
    $id = !empty($_REQUEST['a_id']) ? $_REQUEST['a_id'] : adminmsg("你没有选择日志！", 1);
    $n = reasonaudit_del($id);
    if ($n > 0) {
        adminmsg("删除成功！共删除 {$n} 行", 2);
    } else {
        adminmsg("删除失败！", 0);
    }
} elseif ($act == 'company_img') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "img_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY i.id DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($key && $key_type > 0) {

        if ($key_type === 1)
            $wheresql = " WHERE c.companyname like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE c.id ='" . intval($key) . "'";
        elseif ($key_type === 3)
            $wheresql = " WHERE i.id ='" . intval($key) . "'";
        elseif ($key_type === 4)
            $wheresql = " WHERE i.title like '{$key}%'";
        $oederbysql = "";
    }
    $_GET['audit'] <> "" ? $wheresqlarr['i.audit'] = intval($_GET['audit']) : '';
    if (is_array($wheresqlarr))
        $wheresql = wheresql($wheresqlarr);
    if (!empty($_GET['settr'])) {
        $settr = strtotime("-" . intval($_GET['settr']) . " day");
        $wheresql = empty($wheresql) ? " WHERE i.addtime> " . $settr : $wheresql . " AND i.addtime> " . $settr;
    }
    $joinsql = " LEFT JOIN " . table('company_profile') . " AS c ON i.company_id=c.id  ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_img') . " AS i" . $joinsql . $wheresql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $clist = get_company_img($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "企业图片");
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('company/admin_company_img.htm');
} elseif ($act == 'del_company_img') {
    check_permissions($_SESSION['admin_purview'], "img_del");
    check_token();
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择图片！", 1);
    $num = del_company_img($id);
    if ($num > 0) {
        adminmsg("删除成功！共删除" . $num . "行", 2);
    } else {
        adminmsg("删除失败！", 0);
    }
} elseif ($act == 'edit_img_audit') {
    check_permissions($_SESSION['admin_purview'], "img_audit");
    check_token();
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择图片！", 1);
    $audit = intval($_POST['audit']);
    $pms_notice = intval($_POST['pms_notice']);
    $reason = trim($_POST['reason']);
    $num = edit_img_audit($id, $audit, $reason, $pms_notice);
    if ($num > 0) {
        adminmsg("审核成功！共审核" . $num . "行", 2);
    } else {
        adminmsg("审核成功!共影响{$num}行", 0);
    }
} elseif ($act == 'company_news') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "news_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY n.id DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($key && $key_type > 0) {

        if ($key_type === 1)
            $wheresql = " WHERE c.companyname like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE c.id ='" . intval($key) . "'";
        elseif ($key_type === 3)
            $wheresql = " WHERE n.id ='" . intval($key) . "'";
        elseif ($key_type === 4)
            $wheresql = " WHERE n.title like '{$key}%'";
        $oederbysql = "";
    }
    $_GET['audit'] <> "" ? $wheresqlarr['n.audit'] = intval($_GET['audit']) : '';
    if (is_array($wheresqlarr))
        $wheresql = wheresql($wheresqlarr);
    if (!empty($_GET['settr'])) {
        $settr = strtotime("-" . intval($_GET['settr']) . " day");
        $wheresql = empty($wheresql) ? " WHERE n.addtime> " . $settr : $wheresql . " AND n.addtime> " . $settr;
    }
    $joinsql = " LEFT JOIN " . table('company_profile') . " AS c ON n.company_id=c.id  ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_news') . " AS n" . $joinsql . $wheresql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $clist = get_company_news($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "企业新闻");
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('company/admin_company_news.htm');
} elseif ($act == 'edit_news_audit') {
    check_permissions($_SESSION['admin_purview'], "news_audit");
    check_token();
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择新闻！", 1);
    $audit = intval($_POST['audit']);
    $pms_notice = intval($_POST['pms_notice']);
    $reason = trim($_POST['reason']);
    $num = edit_news_audit($id, $audit, $reason, $pms_notice);
    if ($num > 0) {
        adminmsg("审核成功！共审核" . $num . "行", 2);
    } else {
        adminmsg("审核成功!共影响{$num}行", 0);
    }
} elseif ($act == 'del_company_news') {
    check_permissions($_SESSION['admin_purview'], "news_del");
    check_token();
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择新闻！", 1);
    $num = del_company_news($id);
    if ($num > 0) {
        adminmsg("删除成功！共删除" . $num . "行", 2);
    } else {
        adminmsg("删除失败！", 0);
    }
} elseif ($act == 'edit_company_news') {
    check_permissions($_SESSION['admin_purview'], "news_edit");
    get_token();
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择新闻！", 1);
    $news = get_news_one($id);
    $smarty->assign('news', $news);
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('pageheader', "企业新闻");
    $smarty->display('company/admin_company_news_edit.htm');
} elseif ($act == 'company_news_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "news_edit");
    $id = intval($_POST['id']);
    $setsqlarr['title'] = $_POST['title'] ? trim($_POST['title']) : adminmsg("你没有填写新闻标题！", 1);
    $setsqlarr['content'] = $_POST['content'] ? trim($_POST['content']) : adminmsg("你没有填写新闻内容！", 1);
    $setsqlarr['click'] = intval($_POST['click']);
    $setsqlarr['order'] = intval($_POST['order']);
    $setsqlarr['audit'] = intval($_POST['audit']);
    $setsqlarr['addtime'] = time();
    $link[1]['text'] = "返回新闻列表";
    $link[1]['href'] = '?act=company_news';
    $link[0]['text'] = '查看修改结果';
    $link[0]['href'] = '?act=edit_company_news&id=' . $id;
    !updatetable(table('company_news'), $setsqlarr, ' id=' . $id . ' ') ? adminmsg("修改企业新闻失败！", 1, $link) : adminmsg("修改企业新闻成功！", 2, $link);
} elseif ($act == 'management') {
    $id = intval($_GET['id']);
    $u = get_user($id);
    if (!empty($u)) {
        unset($_SESSION['uid']);
        unset($_SESSION['username']);
        unset($_SESSION['utype']);
        unset($_SESSION['uqqid']);
        setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie("QS[username]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie("QS[password]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        unset($_SESSION['activate_username']);
        unset($_SESSION['activate_email']);

        $_SESSION['uid'] = $u['uid'];
        $_SESSION['username'] = $u['username'];
        $_SESSION['utype'] = $u['utype'];
        $_SESSION['uqqid'] = "1";
        setcookie('QS[uid]', $u['uid'], 0, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[username]', $u['username'], 0, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[password]', $u['password'], 0, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[utype]', $u['utype'], 0, $QS_cookiepath, $QS_cookiedomain);
        header("Location:" . get_member_url($u['utype'], false, $_CFG['site_dir']));
    }
} elseif ($act == 'consultant') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "consultant_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY id DESC ";

    $total_sql = "SELECT COUNT(*) AS num FROM " . table('consultant') . $oederbysql;
    $total_val = get_mem_cache($total_sql, 'get_total');
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $clist = get_consultant($offset, $perpage, $oederbysql);
    $smarty->assign('pageheader', "顾问管理");
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('company/admin_consultant_list.htm');
} elseif ($act == 'consultant_add') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "consultant_add");
    $smarty->assign('pageheader', "顾问管理");
    $smarty->display('company/admin_consultant_add.htm');
} elseif ($act == 'consultant_add_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "consultant_add");
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg('请填写姓名！', 1);
    $setsqlarr['qq'] = !empty($_POST['qq']) ? trim($_POST['qq']) : adminmsg('请填写QQ！', 1);

    !$_FILES['pic']['name'] ? adminmsg('请上传照片！', 1) : "";
    $upload_image_dir = "../data/" . $_CFG['updir_images'] . "/" . date("Y/m/d/");
    make_dir($upload_image_dir);
    require_once(dirname(__FILE__) . '/include/upload.php');
    $setsqlarr['pic'] = _asUpFiles($upload_image_dir, "pic", "2048", 'gif/jpg/bmp/png', true);
    $setsqlarr['pic'] = date("Y/m/d/") . $setsqlarr['pic'];

    $insert_id = inserttable(table('consultant'), $setsqlarr, true);

    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "?act=consultant";
    $link[1]['text'] = "继续添加";
    $link[1]['href'] = "?act=consultant_add";
    adminmsg('添加成功！', 2, $link);
} elseif ($act == 'consultant_edit') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "consultant_edit");
    $id = intval($_GET['id']);
    if (!$id) {
        adminmsg("请选择顾问！", 1);
    }
    $consultant = get_consultant_one($id);
    $smarty->assign('consultant', $consultant);
    $smarty->assign('pageheader', "顾问管理");
    $smarty->display('company/admin_consultant_edit.htm');
} elseif ($act == 'consultant_edit_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "consultant_edit");
    $id = intval($_POST['id']);
    if (!$id) {
        adminmsg("请选择顾问！", 1);
    }
    $consultant = get_consultant_one($id);
    $setsqlarr['name'] = !empty($_POST['name']) ? trim($_POST['name']) : adminmsg('请填写姓名！', 1);
    $setsqlarr['qq'] = !empty($_POST['qq']) ? trim($_POST['qq']) : adminmsg('请填写QQ！', 1);
    if ($_FILES['pic']['name']) {
        $upload_image_dir = "../data/" . $_CFG['updir_images'] . "/" . date("Y/m/d/");
        make_dir($upload_image_dir);
        require_once(dirname(__FILE__) . '/include/upload.php');
        $setsqlarr['pic'] = _asUpFiles($upload_image_dir, "pic", "2048", 'gif/jpg/bmp/png', true);
        $setsqlarr['pic'] = date("Y/m/d/") . $setsqlarr['pic'];
        @unlink("../data/" . $_CFG['updir_images'] . "/" . $consultant['pic']);
    }

    updatetable(table('consultant'), $setsqlarr, " id={$id} ");

    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "?act=consultant";
    $link[1]['text'] = "查看修改结果";
    $link[1]['href'] = "?act=consultant_edit&id={$id}";
    adminmsg('修改成功！', 2, $link);
} elseif ($act == "consultant_del") {
    check_permissions($_SESSION['admin_purview'], "consultant_del");
    $id = intval($_GET['id']);
    if (!$id) {
        adminmsg("请选择顾问！", 1);
    }
    del_consultant($id);
    adminmsg("删除成功！", 2);
}
?>