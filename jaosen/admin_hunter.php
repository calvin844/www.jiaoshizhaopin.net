<?php

/*
 * 74cms 猎头用户相关
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
require_once(ADMIN_ROOT_PATH . 'include/admin_hunter_fun.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'jobs';
if ($act == 'jobs') {
    check_permissions($_SESSION['admin_purview'], "hun_jobs_show");
    $audit = intval($_GET['audit']);
    $utype = intval($_GET['utype']);
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $wheresqlarr = array();
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    $oederbysql = ' order BY refreshtime DESC ';
    if (!empty($key) && $key_type > 0) {
        if ($key_type === 1)
            $wheresql = " WHERE jobs_name like '%{$key}%'";
        elseif ($key_type === 2 && intval($key) > 0)
            $wheresql = " WHERE id =" . intval($key);
        elseif ($key_type === 3)
            $wheresql = " WHERE companyname like '%{$key}%'";
        elseif ($key_type === 4)
            $wheresql = " WHERE companyname_note like '%{$key}%'";
        elseif ($key_type === 5 && intval($key) > 0)
            $wheresql = " WHERE uid =" . intval($key);
        $oederbysql = "";
    }
    else {
        if ($audit > 0) {
            $wheresqlarr['audit'] = $audit;
        }
        if ($utype > 0) {
            $wheresqlarr['utype'] = $utype;
        }
        if (!empty($wheresqlarr))
            $wheresql = wheresql($wheresqlarr);
        if (!empty($_GET['deadline'])) {
            $settr = strtotime("+" . intval($_GET['deadline']) . " day");
            $wheresql = empty($wheresql) ? " WHERE deadline< " . $settr : $wheresql . " AND deadline< " . $settr;
            $oederbysql = " order BY refreshtime DESC ";
        }
        if (!empty($_GET['refre'])) {
            $settr = strtotime("-" . intval($_GET['refre']) . " day");
            $wheresql = empty($wheresql) ? " WHERE refreshtime> " . $settr : $wheresql . " AND refreshtime> " . $settr;
            $oederbysql = " order BY refreshtime DESC ";
        }
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('hunter_jobs') . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $getsql = "SELECT * FROM " . table('hunter_jobs') . $wheresql . $oederbysql;
    $hunterjobs = get_hunterjobs($offset, $perpage, $getsql);
    $smarty->assign('pageheader', "职位管理");
    $smarty->assign('hunterjobs', $hunterjobs);
    $smarty->assign('now', time());
    $smarty->assign('total', $total);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('total_val', $total_val);
    get_token();
    $smarty->display('hunter/admin_hunter_jobs.htm');
} elseif ($act == 'jobs_perform') {
    check_token();
    $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : adminmsg("你没有选择职位！", 1);
    if (!empty($_REQUEST['delete'])) {
        check_permissions($_SESSION['admin_purview'], "hun_jobs_del");
        $num = del_hunterjobs($yid);
        if ($num > 0) {
            adminmsg("删除成功！共删除" . $num . "行", 2);
        } else {
            adminmsg("删除失败！", 0);
        }
    } elseif (!empty($_POST['set_audit'])) {
        check_permissions($_SESSION['admin_purview'], "hun_jobs_audit");
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
            adminmsg("延长有效期成功！响应行数 {$n}", 2);
        } else {
            adminmsg("操作失败！", 0);
        }
    } elseif (!empty($_POST['set_recom'])) {
        check_permissions($_SESSION['admin_purview'], "hun_jobs_rec");
        $rec_notice = intval($_POST['rec_notice']);
        $recommend = intval($_POST['recommend']);
        $notice = trim($_POST['notice']);
        if ($n = recom_hunter_jobs($yid, $recommend, $rec_notice, $notice)) {
            adminmsg("职位设置成功！响应行数 {$n}", 2);
        } else {
            adminmsg("职位设置失败！", 0);
        }
    }
} elseif ($act == 'edit_jobs') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "hun_jobs_edit");
    $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : adminmsg("你没有选择职位！", 1);
    $smarty->assign('pageheader', "职位管理");
    $jobs = get_jobs_one($id);
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('jobs', $jobs);
    $smarty->assign('subsite', get_subsite_list());
    $smarty->display('hunter/admin_hunter_jobs_edit.htm');
} elseif ($act == 'editjobs_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "hun_jobs_edit");
    $id = intval($_POST['id']);
    $utype = intval($_POST['utype']);
    $days = intval($_POST['days']);
    $companyname1 = intval($_POST['companyname1']);
    if ($utype == '3') {
        $setsqlarr['companyname'] = !empty($_POST['companyname']) ? trim($_POST['companyname']) : adminmsg('您没有填写招聘方公司的显示名称！', 1);
        $setsqlarr['companyname_note'] = !empty($_POST['companyname_note']) ? trim($_POST['companyname_note']) : adminmsg('您没有填写招聘方公司备注名称！', 1);
        $setsqlarr['nature'] = !empty($_POST['nature']) ? intval($_POST['nature']) : adminmsg('请选择招聘方公司性质！', 1);
        $setsqlarr['nature_cn'] = trim($_POST['nature_cn']);
        $setsqlarr['scale'] = !empty($_POST['scale']) ? intval($_POST['scale']) : adminmsg('请选择招聘方公司规模！', 1);
        $setsqlarr['scale_cn'] = trim($_POST['scale_cn']);
        $setsqlarr['trade'] = !empty($_POST['trade']) ? intval($_POST['trade']) : adminmsg('请选择招聘方公司行业！', 1);
        $setsqlarr['trade_cn'] = trim($_POST['trade_cn']);
        $setsqlarr['company_desc'] = !empty($_POST['company_desc']) ? trim($_POST['company_desc']) : adminmsg('您没有填写招聘方公司的简介！', 1);
    }

    $setsqlarr['jobs_name'] = !empty($_POST['jobs_name']) ? trim($_POST['jobs_name']) : adminmsg('您没有填写职位名称！', 1);
    $setsqlarr['category'] = !empty($_POST['category']) ? intval($_POST['category']) : adminmsg('请选择职位类别！', 1);
    $setsqlarr['subclass'] = intval($_POST['subclass']);
    $setsqlarr['category_cn'] = trim($_POST['category_cn']);

    $setsqlarr['department'] = !empty($_POST['department']) ? trim($_POST['department']) : adminmsg('您没有填写所属部门！', 1);
    $setsqlarr['reporter'] = !empty($_POST['reporter']) ? trim($_POST['reporter']) : adminmsg('您没有填写汇报对象！', 1);

    $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : adminmsg('请选择工作地区！', 1);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);

    $setsqlarr['wage'] = !empty($_POST['wage']) ? intval($_POST['wage']) : adminmsg('请选择年薪范围！', 1);
    $setsqlarr['wage_cn'] = trim($_POST['wage_cn']);
    $setsqlarr['wage_structure'] = !empty($_POST['wage_structure']) ? $_POST['wage_structure'] : adminmsg('请选择薪资构成！', 1);
    $setsqlarr['socialbenefits'] = trim($_POST['socialbenefits']);
    $setsqlarr['livebenefits'] = trim($_POST['livebenefits']);
    $setsqlarr['annualleave'] = trim($_POST['annualleave']);
    $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : adminmsg('您没有填写职位描述！', 1);
    check_word($_CFG['filter'], $_POST['contents']) ? adminmsg($_CFG['filter_tips'], 0) : '';


    $setsqlarr['age'] = !empty($_POST['age']) ? intval($_POST['age']) : adminmsg('请选择年龄要求！', 1);
    $setsqlarr['age_cn'] = trim($_POST['age_cn']);
    $setsqlarr['sex'] = intval($_POST['sex']);
    $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
    $setsqlarr['experience'] = !empty($_POST['experience']) ? intval($_POST['experience']) : adminmsg('请选择工作经验要求！', 1);
    $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
    $setsqlarr['education'] = !empty($_POST['education']) ? intval($_POST['education']) : adminmsg('请选择学历要求！', 1);
    $setsqlarr['education_cn'] = trim($_POST['education_cn']);
    $setsqlarr['tongzhao'] = intval($_POST['tongzhao']);
    $setsqlarr['tongzhao_cn'] = trim($_POST['tongzhao_cn']);
    $setsqlarr['language'] = trim($_POST['language']);
    $setsqlarr['jobs_qualified'] = !empty($_POST['jobs_qualified']) ? trim($_POST['jobs_qualified']) : adminmsg('您没有填写任职资格！', 1);
    check_word($_CFG['filter'], $_POST['jobs_qualified']) ? adminmsg($_CFG['filter_tips'], 0) : '';

    $setsqlarr['hopetrade'] = !empty($_POST['hopetrade']) ? trim($_POST['hopetrade']) : adminmsg('请选择期望人才来源行业！', 1);
    $setsqlarr['hopetrade_cn'] = trim($_POST['hopetrade_cn']);
    $setsqlarr['extra_message'] = trim($_POST['extra_message']);

    $setsqlarr['refreshtime'] = $timestamp;
    if ($utype == '3') {
        $setsqlarr['key'] = $setsqlarr['jobs_name'] . $setsqlarr['companyname'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'] . $setsqlarr['jobs_qualified'];
        require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
        $sp = new SPWord();
        $setsqlarr['key'] = "{$setsqlarr['jobs_name']} {$setsqlarr['companyname']} " . $sp->extracttag($setsqlarr['key']);
        $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
        $setsqlarr['likekey'] = $setsqlarr['jobs_name'] . ',' . $setsqlarr['companyname'] . ',' . $setsqlarr['category_cn'] . ',' . $setsqlarr['district_cn'];
    } elseif ($utype == '1') {
        $setsqlarr['key'] = $setsqlarr['jobs_name'] . $companyname1 . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'] . $setsqlarr['jobs_qualified'];
        require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
        $sp = new SPWord();
        $setsqlarr['key'] = "{$setsqlarr['jobs_name']} {$companyname1} " . $sp->extracttag($setsqlarr['key']);
        $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
        $setsqlarr['likekey'] = $setsqlarr['jobs_name'] . ',' . $companyname1 . ',' . $setsqlarr['category_cn'] . ',' . $setsqlarr['district_cn'];
    }
    $setsqlarr['subsite_id'] = intval($_POST['subsite_id']);
    if ($days > 0) {
        if (intval($_POST['olddeadline']) >= time()) {
            $setsqlarr['deadline'] = intval($_POST['olddeadline']) + ($days * (60 * 60 * 24));
        } else {
            $setsqlarr['deadline'] = strtotime("{$days} day");
        }
    } else {
        $setsqlarr['deadline'] = intval($_POST['olddeadline']);
    }

    $setsqlarr['audit'] = intval($_POST['audit']);
    $setsqlarr['display'] = intval($_POST['display']);

    $setsqlarr['contact'] = !empty($_POST['contact']) ? trim($_POST['contact']) : adminmsg('您没有填写联系人！', 1);
    $setsqlarr['qq'] = trim($_POST['qq']);
    $setsqlarr['telephone'] = !empty($_POST['telephone']) ? trim($_POST['telephone']) : adminmsg('您没有填写联系电话！', 1);
    check_word($_CFG['filter'], $_POST['telephone']) ? adminmsg($_CFG['filter_tips'], 0) : '';
    $setsqlarr['address'] = !empty($_POST['address']) ? trim($_POST['address']) : adminmsg('您没有填写联系地址！', 1);
    $setsqlarr['email'] = !empty($_POST['email']) ? trim($_POST['email']) : adminmsg('您没有填写联系邮箱！', 1);
    $setsqlarr['notify'] = intval($_POST['notify']);

    $setsqlarr['contact_show'] = intval($_POST['contact_show']);
    $setsqlarr['email_show'] = intval($_POST['email_show']);
    $setsqlarr['telephone_show'] = intval($_POST['telephone_show']);
    $setsqlarr['address_show'] = intval($_POST['address_show']);
    $setsqlarr['qq_show'] = intval($_POST['qq_show']);

    $wheresql = " id='" . $id . "' ";
    if (!updatetable(table('hunter_jobs'), $setsqlarr, $wheresql))
        adminmsg("保存失败！", 0);
    $link[0]['text'] = "返回职位列表";
    $link[0]['href'] = $_POST['url'];
    adminmsg("修改成功！", 2, $link);
}

elseif ($act == 'hunter_list') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "hun_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY c.id DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($key && $key_type > 0) {

        if ($key_type === 1)
            $wheresql = " WHERE c.huntername like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE c.companyname like '%{$key}%'";
        elseif ($key_type === 3)
            $wheresql = " WHERE m.username like '{$key}%'";
        elseif ($key_type === 4)
            $wheresql = " WHERE c.uid ='" . intval($key) . "'";
        elseif ($key_type === 5)
            $wheresql = " WHERE c.address  like '%{$key}%'";
        elseif ($key_type === 6)
            $wheresql = " WHERE c.telephone  like '{$key}%'";
        $oederbysql = "";
    }
    $_GET['audit'] <> "" ? $wheresqlarr['c.audit'] = intval($_GET['audit']) : '';
    if (is_array($wheresqlarr))
        $wheresql = wheresql($wheresqlarr);
    if (!empty($_GET['settr'])) {
        $settr = strtotime("-" . intval($_GET['settr']) . " day");
        $wheresql = empty($wheresql) ? " WHERE addtime> " . $settr : $wheresql . " AND addtime> " . $settr;
    }
    $operation_hunter_mode = $_CFG['operation_hunter_mode'];
    if ($operation_hunter_mode == '1') {
        $joinsql = " LEFT JOIN " . table('members') . " AS m ON c.uid=m.uid  LEFT JOIN " . table('members_points') . " AS p ON c.uid=p.uid";
    } else {
        $joinsql = " LEFT JOIN " . table('members') . " AS m ON c.uid=m.uid  LEFT JOIN " . table('members_hunter_setmeal') . " AS p ON c.uid=p.uid";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('hunter_profile') . " AS c" . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $clist = get_hunter($offset, $perpage, $joinsql . $wheresql . $oederbysql, $operation_hunter_mode);
    $smarty->assign('pageheader', "猎头顾问管理");
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('hunter/admin_hunter_list.htm');
} elseif ($act == 'hunter_perform') {
    check_token();
    $u_id = !empty($_POST['y_id']) ? $_POST['y_id'] : adminmsg("你没有选择猎头！", 1);
    if ($_POST['delete']) {
        check_permissions($_SESSION['admin_purview'], "hun_del");
        if ($_POST['delete_hunter'] == 'yes') {
            !del_hunter($u_id) ? adminmsg("删除猎头资料失败！", 0) : "";
        }
        if ($_POST['delete_jobs'] == 'yes') {
            !del_hunter_alljobs($u_id) ? adminmsg("删除职位失败！", 0) : "";
        }
        if ($_POST['delete_jobs'] <> 'yes' && $_POST['delete_hunter'] <> 'yes') {
            adminmsg("未选择删除类型！", 1);
        }
        adminmsg("删除成功！", 2);
    }
    if (trim($_POST['set_audit'])) {
        check_permissions($_SESSION['admin_purview'], "hun_audit");
        $audit = $_POST['audit'];
        $pms_notice = intval($_POST['pms_notice']);
        $reason = trim($_POST['reason']);
        !edit_hunter_audit($u_id, intval($audit), $reason, $pms_notice) ? adminmsg("设置失败！", 0) : adminmsg("设置成功！", 2);
    } elseif (!empty($_POST['set_refresh'])) {
        if (empty($_POST['refresh_jobs'])) {
            $refresjobs = false;
        } else {
            $refresjobs = true;
        }
        if ($n = refresh_hunter($u_id, $refresjobs)) {
            adminmsg("刷新成功！响应行数 {$n} 行", 2);
        } else {
            adminmsg("刷新失败！", 0);
        }
    }
} elseif ($act == 'edit_hunter_profile') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "hun_edit");
    $yid = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : adminmsg("你没有选择猎头！", 1);
    $smarty->assign('pageheader', "猎头顾问管理");
    $hunter_profile = get_hunter_one_id($yid);
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('hunter_profile', $hunter_profile);
    $smarty->display('hunter/admin_hunter_profile_edit.htm');
} elseif ($act == 'hunter_profile_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "hun_edit");
    $id = intval($_POST['id']);
    $setsqlarr['audit'] = intval($_POST['audit']);
    $setsqlarr['huntername'] = trim($_POST['huntername']) ? trim($_POST['huntername']) : adminmsg('您没有输入猎头名称！', 1);
    $setsqlarr['companyname'] = trim($_POST['companyname']) ? trim($_POST['companyname']) : adminmsg('您没有输入猎头所在公司！', 1);
    $code = trim($_POST['code']) ? trim($_POST['code']) : adminmsg('您没有填写座机区号！', 1);
    $telephone = trim($_POST['companytelephone']) ? trim($_POST['companytelephone']) : adminmsg('您没有填写座机号码！', 1);
    $setsqlarr['companytelephone'] = $code . '-' . $telephone;
    $setsqlarr['district'] = intval($_POST['district']) > 0 ? intval($_POST['district']) : adminmsg('您没有选择所属地区！', 1);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);
    $setsqlarr['worktime_start'] = intval($_POST['worktime_start']) > 1970 ? intval($_POST['worktime_start']) : adminmsg('您没有填写从业开始时间！', 1);
    $setsqlarr['rank'] = trim($_POST['rank']) ? intval($_POST['rank']) : adminmsg('您选择猎头头衔！', 1);
    $setsqlarr['rank_cn'] = trim($_POST['rank_cn']);
    $setsqlarr['goodtrade'] = trim($_POST['goodtrade']) ? trim($_POST['goodtrade']) : adminmsg('您没有选择擅长行业！', 1);
    $setsqlarr['goodtrade_cn'] = trim($_POST['goodtrade_cn']);
    $setsqlarr['goodcategory'] = trim($_POST['goodcategory']) ? trim($_POST['goodcategory']) : adminmsg('您没有选择擅长职能！', 1);
    $setsqlarr['goodcategory_cn'] = trim($_POST['goodcategory_cn']);
    $setsqlarr['contents'] = trim($_POST['contents']) ? trim($_POST['contents']) : adminmsg('请填写猎头简介！', 1);
    $setsqlarr['cooperate_company'] = trim($_POST['cooperate_company']);


    $setsqlarr['address'] = trim($_POST['address']) ? trim($_POST['address']) : adminmsg('请填写通讯地址！', 1);
    $setsqlarr['telephone'] = trim($_POST['telephone']) ? trim($_POST['telephone']) : adminmsg('请填写联系电话！', 1);
    $setsqlarr['email'] = trim($_POST['email']) ? trim($_POST['email']) : adminmsg('请填写联系邮箱！', 1);
    $setsqlarr['yellowpages'] = intval($_POST['yellowpages']);

    $setsqlarr['email_show'] = intval($_POST['email_show']);
    $setsqlarr['telephone_show'] = intval($_POST['telephone_show']);
    $setsqlarr['address_show'] = intval($_POST['address_show']);
    $wheresql = " id='{$id}' ";

    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    if (updatetable(table('hunter_profile'), $setsqlarr, $wheresql)) {
        unset($setsqlarr);
        adminmsg("保存成功！", 2, $link);
    } else {
        unset($setsqlarr);
        adminmsg("保存失败！", 0);
    }
} elseif ($act == 'members_list') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "hun_user_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $wheresql = " WHERE m.utype=3 ";
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
            $wheresql.=" AND c.huntername like '%{$key}%'";
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
    $joinsql = " LEFT JOIN " . table('hunter_profile') . " as c ON m.uid=c.uid ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('members') . " as m " . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $member = get_member_list($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "猎头顾问会员");
    $smarty->assign('member', $member);
    $smarty->assign('page', $page->show(3));
    $smarty->display('hunter/admin_hunter_user_list.htm');
} elseif ($act == 'delete_user') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "hun_user_del");
    $tuid = !empty($_REQUEST['tuid']) ? $_REQUEST['tuid'] : adminmsg("你没有选择会员！", 1);
    if ($_POST['delete']) {
        if (!empty($_POST['delete_user'])) {
            !delete_hunter_user($tuid) ? adminmsg("删除会员失败！", 0) : "";
        }
        if (!empty($_POST['delete_hunter'])) {
            !del_hunter($tuid) ? adminmsg("删除猎头资料失败！", 0) : "";
        }
        if (!empty($_POST['delete_jobs'])) {
            !del_hunter_alljobs($tuid) ? adminmsg("删除职位失败！", 0) : "";
        }
        adminmsg("删除成功！", 2);
    }
} elseif ($act == 'members_add') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "hun_user_add");
    $smarty->assign('pageheader', "猎头顾问会员");
    $smarty->assign('givesetmeal', get_setmeal(false));
    $smarty->assign('points', get_cache('points_rule'));
    $smarty->display('hunter/admin_hunter_user_add.htm');
} elseif ($act == 'members_add_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "hun_user_add");
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
    if ($sql['utype'] == "3") {
        $db->query("INSERT INTO " . table('members_points') . " (uid) VALUES ('{$insert_id}')");
        $db->query("INSERT INTO " . table('members_hunter_setmeal') . " (uid) VALUES ('{$insert_id}')");
        if (intval($_POST['is_money']) && $_POST['log_amount']) {
            $amount = round($_POST['log_amount'], 2);
            $ismoney = 2;
        } else {
            $amount = '0.00';
            $ismoney = 1;
        }
        $regpoints_num = intval($_POST['regpoints_num']);
        if ($_POST['regpoints'] == "y") {
            write_memberslog($insert_id, 3, 9201, $sql['username'], "<span style=color:#FF6600>注册会员系统自动赠送!(+{$regpoints_num})</span>");
            //会员积分变更记录。管理员后台修改会员的积分。3表示：管理员后台修改
            $notes = "操作人：{$_SESSION['admin_name']},说明：后台添加猎头会员并赠送(+{$regpoints_num})积分，收取费用：{$amount}元";
            write_setmeallog($insert_id, $sql['username'], $notes, 4, $amount, $ismoney, 1, 3);
            report_deal($insert_id, 1, $regpoints_num);
        }
        $reg_service = intval($_POST['reg_service']);
        if ($reg_service > 0) {
            $service = get_setmeal_one($reg_service);
            write_memberslog($insert_id, 3, 9202, $sql['username'], "开通服务({$service['setmeal_name']})");
            set_members_setmeal($insert_id, $reg_service);
            //会员积分变更记录。管理员后台修改会员的积分。3表示：管理员后台修改
            $notes = "操作人：{$_SESSION['admin_name']},说明：后台添加猎头会员并开通服务({$service['setmeal_name']})，收取费用：{$amount}元";
            write_setmeallog($insert_id, $sql['username'], $notes, 4, $amount, $ismoney, 2, 3);
        }
        if (intval($_POST['is_money']) && $_POST['log_amount'] && !$notes) {
            $notes = "操作人：{$_SESSION['admin_name']},说明：后台添加猎头会员，未赠送积分，未开通套餐，收取费用：{$amount}元";
            write_setmeallog($insert_id, $sql['username'], $notes, 4, $amount, 2, 2, 3);
        }
    }
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "?act=members_list";
    $link[1]['text'] = "继续添加";
    $link[1]['href'] = "?act=members_add";
    adminmsg('添加成功！', 2, $link);
} elseif ($act == 'user_edit') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "hun_user_edit");
    $hunter_user = get_user($_GET['tuid']);
    $smarty->assign('pageheader', "猎头顾问会员");
    $hunter_profile = get_hunter_one_uid($hunter_user['uid']);
    $smarty->assign('hunter_user', $hunter_user);
    $smarty->assign('userpoints', get_user_points($hunter_user['uid']));
    $smarty->assign('setmeal', get_user_setmeal($hunter_user['uid']));
    $smarty->assign('givesetmeal', get_setmeal(false));
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->display('hunter/admin_hunter_user_edit.htm');
} elseif ($act == 'set_account_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "hun_user_edit");
    require_once(ADMIN_ROOT_PATH . 'include/admin_user_fun.php');
    $setsqlarr['username'] = trim($_POST['username']);
    $setsqlarr['email'] = trim($_POST['email']);
    $setsqlarr['email_audit'] = intval($_POST['email_audit']);
    $setsqlarr['mobile'] = trim($_POST['mobile']);
    $setsqlarr['mobile_audit'] = intval($_POST['mobile_audit']);
    if ($_POST['qq_openid'] == "1") {
        $setsqlarr['qq_openid'] = '';
    }
    $thisuid = intval($_POST['hunter_uid']);
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
    if (!empty($setsqlarr['mobile']) && !preg_match("/^(13|15|18)\d{9}$/", $setsqlarr['mobile'])) {
        adminmsg('手机号码错误！', 1);
    }
    $getmobile = get_user_inmobile($setsqlarr['mobile']);
    if (!empty($setsqlarr['mobile']) && !empty($getmobile) && $getmobile['uid'] <> $thisuid) {
        adminmsg("手机号 {$setsqlarr['mobile']}  已经存在！", 1);
    }
    if (updatetable(table('members'), $setsqlarr, " uid=" . $thisuid . "")) {
        $link[0]['text'] = "返回列表";
        $link[0]['href'] = $_POST['url'];
        adminmsg('修改成功！', 2, $link);
    } else {
        adminmsg('修改失败！', 1);
    }
} elseif ($act == 'userpass_edit') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "hun_user_edit");
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
} elseif ($act == 'userstatus_edit') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "hun_user_edit");
    if ($db->query("UPDATE " . table('members') . " SET status = '" . intval($_POST['status']) . "'  WHERE uid='" . intval($_POST['userstatus_uid']) . "'")) {
        $link[0]['text'] = "返回列表";
        $link[0]['href'] = $_POST['url'];
        adminmsg('操作成功！', 2, $link);
    } else {
        adminmsg('操作失败！', 1);
    }
} elseif ($act == 'userpoints_edit') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "hun_user_edit");
    if (intval($_POST['points']) < 1)
        adminmsg('请输入积分！', 1);
    if (trim($_POST['points_notes']) == '')
        adminmsg('请填写积分操作说明！', 1);
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    $user = get_user($_POST['hunter_uid']);
    $points_type = intval($_POST['points_type']);
    $t = $points_type == 1 ? "+" : "-";
    report_deal($user['uid'], $points_type, intval($_POST['points']));
    $points = get_user_points($user['uid']);
    $user_limit_points = get_user_limit_points($_SESSION['uid']);
    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
    write_memberslog(intval($_POST['hunter_uid']), 3, 9201, $user['username'], " 管理员操作积分({$t}{$_POST['points']})，(剩余积分:{$points}，剩余限时积分:{$user_limit_points})，备注：" . $_POST['points_notes']);
    //会员积分变更记录。管理员后台修改会员的积分。3表示：管理员后台修改
    if (intval($_POST['is_money']) && $_POST['log_amount']) {
        $amount = round($_POST['log_amount'], 2);
        $ismoney = 2;
    } else {
        $amount = '0.00';
        $ismoney = 1;
    }
    $notes = "操作人：{$_SESSION['admin_name']},说明：修改会员 {$user['username']} 积分 ({$t}{$_POST['points']})。收取积分金额：{$amount} 元，备注：{$_POST['points_notes']}";
    write_setmeallog($_POST['hunter_uid'], $user['username'], $notes, 3, $amount, $ismoney, 1, 3);
    adminmsg('保存成功！', 2);
} elseif ($act == 'edit_setmeal_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "hun_user_edit");
    $setsqlarr['jobs_add'] = $_POST['jobs_add'];
    $setsqlarr['download_resume_talent'] = $_POST['download_resume_manager'];
    $setsqlarr['interview_manager'] = $_POST['interview_manager'];
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
    $hunter_uid = intval($_POST['hunter_uid']);
    if ($hunter_uid) {
        $setmeal = get_user_setmeal($hunter_uid);
        if (!updatetable(table('members_hunter_setmeal'), $setsqlarr, " uid=" . $hunter_uid . ""))
            adminmsg('修改出错！', 0);
        //会员套餐变更记录。管理员后台修改会员套餐：修改会员。3表示：管理员后台修改
        $setmeal['endtime'] = date('Y-m-d', $setmeal['endtime']);
        $setsqlarr['endtime'] = date('Y-m-d', $setsqlarr['endtime']);
        $setsqlarr['log_amount'] = round($_POST['log_amount']);
        $notes = edit_setmeal_notes($setsqlarr, $setmeal);
        if ($notes) {
            $user = get_user($_POST['hunter_uid']);
            $ismoney = round($_POST['log_amount']) ? 2 : 1;
            write_setmeallog($hunter_uid, $user['username'], $notes, 3, $setsqlarr['log_amount'], $ismoney, 2, 3);
        }
        if ($setsqlarr['endtime'] <> "") {
            $setmeal_deadline['setmeal_deadline'] = $setmealtime;
            if (!updatetable(table('hunter_jobs'), $setmeal_deadline, " uid='{$hunter_uid}' AND add_mode='2' "))
                adminmsg('修改出错！', 0);
        }
    }
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    adminmsg('操作成功！', 2, $link);
}

elseif ($act == 'set_setmeal_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "hun_user_edit");
    if (intval($_POST['reg_service']) > 0) {
        if (set_members_setmeal($_POST['hunter_uid'], $_POST['reg_service'])) {
            $link[0]['text'] = "返回列表";
            $link[0]['href'] = $_POST['url'];
            //会员套餐变更记录。管理员后台修改会员套餐：重新开通套餐。3表示：管理员后台修改
            $user = get_user($_POST['hunter_uid']);
            if (intval($_POST['is_money']) && $_POST['log_amount']) {
                $amount = round($_POST['log_amount'], 2);
                $ismoney = 2;
            } else {
                $amount = '0.00';
                $ismoney = 1;
            }
            $notes = "操作人：{$_SESSION['admin_name']},说明：为会员 {$user['username']} 重新开通服务，收取服务金额：{$amount}元，服务ID：{$_POST['reg_service']}。";
            write_setmeallog($_POST['hunter_uid'], $user['username'], $notes, 4, $amount, $ismoney, 2, 3);
            adminmsg('操作成功！', 2, $link);
        } else {
            adminmsg('操作失败！', 1);
        }
    } else {
        adminmsg('请选择服务套餐！', 1);
    }
} elseif ($act == 'meal_log') {
    get_token();
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY a.log_id DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    $operation_hunter_mode = $_CFG['operation_hunter_mode'];
    $wheresql = " WHERE a.log_mode={$operation_hunter_mode} AND a.log_utype=3";
    if ($key && $key_type > 0) {
        if ($key_type === 1)
            $wheresql.="  AND a.log_username = '{$key}'";
        elseif ($key_type === 2)
            $wheresql.="  AND a.log_uid = '" . intval($key) . "' ";
        elseif ($key_type === 3)
            $wheresql.=" AND c.huntername like '{$key}%'";
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
    if ($operation_hunter_mode == '1') {
        $joinsql = " LEFT JOIN " . table('members_points') . " as b ON a.log_uid=b.uid  LEFT JOIN " . table('hunter_profile') . " as c ON a.log_uid=c.uid ";
    } else {
        $joinsql = " LEFT JOIN " . table('members_hunter_setmeal') . " as b ON a.log_uid=b.uid  LEFT JOIN " . table('hunter_profile') . " as c ON a.log_uid=c.uid ";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('members_charge_log') . " as a " . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $meallog = get_meal_members_log($offset, $perpage, $joinsql . $wheresql . $oederbysql, $operation_hunter_mode);
    $smarty->assign('pageheader', '猎头顾问管理');
    $smarty->assign('navlabel', 'meal_log');
    $smarty->assign('meallog', $meallog);
    $smarty->assign('page', $page->show(3));
    $smarty->display('hunter/admin_hunter_meal_log.htm');
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
            $wheresql.=" AND c.huntername like '{$key}%'";
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
    $joinsql = " LEFT JOIN " . table('members') . " as b ON a.uid=b.uid  LEFT JOIN " . table('hunter_profile') . " as c ON a.uid=c.uid ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('members_hunter_setmeal') . " as a " . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $member = get_meal_members_list($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "猎头管理");
    $smarty->assign('navlabel', 'meal_members');
    $smarty->assign('member', $member);
    $smarty->assign('setmeal', get_setmeal());
    $smarty->assign('page', $page->show(3));
    $smarty->display('hunter/admin_hunter_meal_members.htm');
} elseif ($act == 'meal_delay') {
    $tuid = !empty($_REQUEST['tuid']) ? $_REQUEST['tuid'] : adminmsg("你没有选择会员！", 1);
    $days = intval($_POST['days']);
    if (empty($days)) {
        adminmsg("请填写要延长的天数！", 0);
    }
    if ($n = delay_meal($tuid, $days)) {
        adminmsg("延长有效期成功！响应行数 {$n}", 2);
    } else {
        adminmsg("操作失败！", 0);
    }
} elseif ($act == 'order_list') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "ord_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    require_once(ADMIN_ROOT_PATH . 'include/admin_pay_fun.php');
    $wheresql = " WHERE o.utype=3 ";
    $oederbysql = " order BY o.addtime DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($key && $key_type > 0) {
        if ($key_type === 1)
            $wheresql = " WHERE o.utype=3 AND c.huntername like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE o.utype=3 AND m.username = '{$key}'";
        elseif ($key_type === 3)
            $wheresql = " WHERE o.utype=3 AND o.oid ='" . trim($key) . "'";
        $oederbysql = "";
    }
    else {
        $wheresqlarr['o.utype'] = '3';
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
    $joinsql = " left JOIN " . table('members') . " as m ON o.uid=m.uid LEFT JOIN  " . table('hunter_profile') . " as c ON o.uid=c.uid ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('order') . " as o " . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $orderlist = get_order_list($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "订单管理");
    $smarty->assign('payment_list', get_payment(2));
    $smarty->assign('orderlist', $orderlist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('hunter/admin_order_list.htm');
} elseif ($act == 'order_set') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "ord_set");
    $smarty->assign('pageheader', "订单管理");
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('payment', get_order_one($_GET['id']));
    $smarty->display('hunter/admin_order_set.htm');
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
} elseif ($act == 'show_order') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "ord_show");
    $smarty->assign('pageheader', "订单管理");
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('payment', get_order_one($_GET['id']));
    $smarty->display('hunter/admin_order_show.htm');
} elseif ($act == 'order_notes_save') {
    check_token();
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    !$db->query("UPDATE " . table('order') . " SET  notes='" . $_POST['notes'] . "' WHERE id='" . intval($_GET['id']) . "'") ? adminmsg('操作失败', 1) : adminmsg("操作成功！", 2, $link);
} elseif ($act == 'order_del') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "ord_del");
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择项目！", 1);
    if (del_order($id)) {
        adminmsg("取消成功！", 2, $link);
    } else {
        adminmsg("取消失败！", 1);
    }
} elseif ($act == 'meal_log_pie') {
    require_once(ADMIN_ROOT_PATH . 'include/admin_flash_statement_fun.php');
    $pie_type = !empty($_GET['pie_type']) ? intval($_GET['pie_type']) : 1;
    meal_hunter_log_pie($pie_type, 3);
    $smarty->assign('pageheader', "猎头顾问管理");
    $smarty->assign('navlabel', 'meal_log_pie');
    $smarty->display('hunter/admin_hunter_meal_log_pie.htm');
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
}
?>