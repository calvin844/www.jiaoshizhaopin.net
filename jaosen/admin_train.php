<?php

/*
 * 74cms 培训用户相关
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
require_once(ADMIN_ROOT_PATH . 'include/admin_train_fun.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'list';
if ($act == 'list') {
    check_permissions($_SESSION['admin_purview'], "cou_show");
    $audit = intval($_GET['audit']);
    $recom = intval($_GET['recom']);
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY id DESC ";
    $wheresqlarr = array();
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    $oederbysql = ' order BY refreshtime DESC ';
    if (!empty($key) && $key_type > 0) {
        if ($key_type === 1)
            $wheresql = " WHERE course_name like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE trainname like '%{$key}%'";
        elseif ($key_type === 3 && intval($key) > 0)
            $wheresql = " WHERE id =" . intval($key);
        elseif ($key_type === 4 && intval($key) > 0)
            $wheresql = " WHERE train_id =" . intval($key);
        elseif ($key_type === 5 && intval($key) > 0)
            $wheresql = " WHERE uid =" . intval($key);
        $oederbysql = "";
    }
    else {
        if ($audit > 0) {
            $wheresqlarr['audit'] = $audit;
        }
        if ($recom > 0) {
            $wheresqlarr['recommend'] = $recom;
        }
        if (!empty($wheresqlarr))
            $wheresql = wheresql($wheresqlarr);
        if (!empty($_GET['settr'])) {
            $settr = strtotime("-" . intval($_GET['settr']) . " day");
            $wheresql = empty($wheresql) ? " WHERE addtime> " . $settr : $wheresql . " AND addtime> " . $settr;
            $oederbysql = " order BY addtime DESC ";
        }
        if (!empty($_GET['refre'])) {
            $settr = strtotime("-" . intval($_GET['refre']) . " day");
            $wheresql = empty($wheresql) ? " WHERE refreshtime> " . $settr : $wheresql . " AND refreshtime> " . $settr;
            $oederbysql = " order BY refreshtime DESC ";
        }
        if (!empty($_GET['deadline'])) {

            $settr = strtotime("+" . intval($_GET['deadline']) . " day");
            $wheresql = empty($wheresql) ? " WHERE deadline< {$settr}" : $wheresql . " AND deadline<{$settr} ";
            $oederbysql = " order BY deadline DESC ";
        }
    }

    $total_sql = "SELECT COUNT(*) AS num FROM " . table('course') . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $getsql = "SELECT * FROM " . table('course') . $wheresql . $oederbysql;
    $course = get_course($offset, $perpage, $getsql);
    $smarty->assign('pageheader', "课程管理");
    $smarty->assign('course', $course);
    $smarty->assign('now', time());
    $smarty->assign('total', $total);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('total_val', $total_val);
    get_token();
    $smarty->display('train/admin_train_course.htm');
} elseif ($act == 'teacher_list') {
    check_permissions($_SESSION['admin_purview'], "tea_show");
    $audit = intval($_GET['audit']);
    $recom = intval($_GET['recom']);
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY id DESC ";
    $wheresqlarr = array();
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if (!empty($key) && $key_type > 0) {

        if ($key_type === 1)
            $wheresql = " WHERE teachername like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE trainname like '%{$key}%'";
        elseif ($key_type === 3 && intval($key) > 0)
            $wheresql = " WHERE id =" . intval($key);
        elseif ($key_type === 4 && intval($key) > 0)
            $wheresql = " WHERE train_id =" . intval($key);
        elseif ($key_type === 5 && intval($key) > 0)
            $wheresql = " WHERE uid =" . intval($key);
        $oederbysql = "";
    }
    else {
        if ($audit > 0) {
            $wheresqlarr['audit'] = $audit;
        }
        if ($recom > 0) {
            $wheresqlarr['recommend'] = $recom;
        }
        if (!empty($wheresqlarr))
            $wheresql = wheresql($wheresqlarr);
        if (!empty($_GET['settr'])) {
            $settr = strtotime("-" . intval($_GET['settr']) . " day");
            $wheresql = empty($wheresql) ? " WHERE addtime> " . $settr : $wheresql . " AND addtime> " . $settr;
            $oederbysql = " order BY addtime DESC ";
        }
        if (!empty($_GET['refre'])) {
            $settr = strtotime("-" . intval($_GET['refre']) . " day");
            $wheresql = empty($wheresql) ? " WHERE refreshtime> " . $settr : $wheresql . " AND refreshtime> " . $settr;
            $oederbysql = " order BY refreshtime DESC ";
        }
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('train_teachers') . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $getsql = "SELECT * FROM " . table('train_teachers') . $wheresql . $oederbysql;
    $teacher = get_teacher($offset, $perpage, $getsql);
    $smarty->assign('pageheader', "讲师管理");
    $smarty->assign('teacher', $teacher);
    $smarty->assign('total', $total);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('total_val', $total_val);
    get_token();
    $smarty->display('train/admin_train_teacher.htm');
} elseif ($act == 'teacher_perform') {
    check_token();
    $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : adminmsg("你没有选择讲师！", 1);
    if (!empty($_POST['delete'])) {
        check_permissions($_SESSION['admin_purview'], "tea_del");
        if ($_POST['delete_teacher'] == 'yes') {
            !del_train_idteacher($yid) ? adminmsg("删除讲师失败！", 0) : "";
        }
        if ($_POST['delete_course'] == 'yes') {
            !del_train_teacourse($yid) ? adminmsg("删除课程失败！", 0) : "";
        }
        if ($_POST['delete_teacher'] <> 'yes' && $_POST['delete_course'] <> 'yes') {
            adminmsg("未选择删除类型！", 1);
        }
        adminmsg("删除成功！", 2);
    }
    if (!empty($_POST['set_audit'])) {
        check_permissions($_SESSION['admin_purview'], "tea_audit");
        $audit = intval($_POST['audit']);
        $pms_notice = intval($_POST['pms_notice']);
        $reason = trim($_POST['reason']);
        if ($n = edit_teachers_audit($yid, $audit, $reason, $pms_notice)) {
            adminmsg("审核成功！响应行数 {$n}", 2);
        } else {
            adminmsg("审核成功！响应行数 0", 1);
        }
    }
    if (!empty($_REQUEST['set_refre'])) {
        check_permissions($_SESSION['admin_purview'], "tea_edit");
        if ($n = refresh_teacher($yid)) {
            adminmsg("刷新成功！响应行数 {$n}", 2);
        } else {
            adminmsg("刷新成功！响应行数 0", 1);
        }
    }
    if (!empty($_POST['set_recom'])) {
        check_permissions($_SESSION['admin_purview'], "tra_promotion");
        $rec_notice = intval($_POST['rec_notice']);
        $recommend = intval($_POST['recommend']);
        $notice = trim($_POST['notice']);
        if ($n = recom_teacher($yid, $recommend, $rec_notice, $notice)) {
            adminmsg("讲师设置成功！响应行数 {$n}", 2);
        } else {
            adminmsg("讲师设置失败！", 0);
        }
    }
} elseif ($act == 'edit_teacher') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "tea_edit");
    $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : adminmsg("你没有选择讲师！", 1);
    $smarty->assign('pageheader', "讲师管理");
    $teacher = get_teacher_one($id);
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('teacher', $teacher);
    $smarty->display('train/admin_train_teacher_edit.htm');
} elseif ($act == 'editteacher_save') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "tea_edit");

    $setsqlarr['id'] = intval($_POST['id']);
    $setsqlarr['audit'] = intval($_POST['audit']);

    $setsqlarr['teachername'] = !empty($_POST['teachername']) ? trim($_POST['teachername']) : adminmsg('您没有填写讲师姓名！', 1);
    $setsqlarr['sex'] = trim($_POST['sex']) ? intval($_POST['sex']) : adminmsg('请选择性别！', 1);
    $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
    $setsqlarr['birthdate'] = intval($_POST['birthdate']) > 1945 ? intval($_POST['birthdate']) : adminmsg('请正确填写出生年份', 1);
    $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : adminmsg('请选择所在地区！', 1);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);
    $setsqlarr['education'] = !empty($_POST['education']) ? intval($_POST['education']) : adminmsg('请选择最高专业！', 1);
    $setsqlarr['education_cn'] = trim($_POST['education_cn']);
    $setsqlarr['speciality'] = !empty($_POST['speciality']) ? trim($_POST['speciality']) : adminmsg('请填写所学专业！', 1);
    $setsqlarr['positionaltitles'] = !empty($_POST['positionaltitles']) ? trim($_POST['positionaltitles']) : adminmsg('请填写现有职称！', 1);
    $setsqlarr['graduated_school'] = trim($_POST['graduated_school']) ? trim($_POST['graduated_school']) : adminmsg('请填写毕业院校！', 1);
    $setsqlarr['work_unit'] = trim($_POST['work_unit']) ? trim($_POST['work_unit']) : adminmsg('请填写工作单位！', 1);
    $setsqlarr['work_position'] = trim($_POST['work_position']);
    $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : adminmsg('您没有填写个人简介！', 1);
    check_word($_CFG['filter'], $_POST['contents']) ? adminmsg($_CFG['filter_tips'], 0) : '';
    $setsqlarr['achievements'] = !empty($_POST['achievements']) ? trim($_POST['achievements']) : adminmsg('您没有填写个人成就！', 1);
    check_word($_CFG['filter'], $_POST['achievements']) ? adminmsg($_CFG['filter_tips'], 0) : '';

    $setsqlarr['telephone'] = trim($_POST['telephone']) ? trim($_POST['telephone']) : adminmsg('请填写联系电话！', 1);
    $setsqlarr['email'] = trim($_POST['email']);
    $setsqlarr['address'] = trim($_POST['address']) ? trim($_POST['address']) : adminmsg('请填写通讯地址！', 1);
    $setsqlarr['website'] = trim($_POST['website']);
    $setsqlarr['qq'] = trim($_POST['qq']);
    $setsqlarr['refreshtime'] = $timestamp;


    $setsqlarr['email_show'] = intval($_POST['email_show']);
    $setsqlarr['telephone_show'] = intval($_POST['telephone_show']);
    $setsqlarr['address_show'] = intval($_POST['address_show']);
    $setsqlarr['qq_show'] = intval($_POST['qq_show']);

    $wheresql = " id='" . $setsqlarr['id'] . "' ";
    if (!updatetable(table('train_teachers'), $setsqlarr, $wheresql))
        adminmsg("保存失败！", 0);
    $teacherarr['teacher_cn'] = $setsqlarr['teachername'];
    $wheresql = " teacher_id=" . $setsqlarr['id'];
    if (!updatetable(table('course'), $teacherarr, $wheresql))
        adminmsg("保存失败！", 0);
    $link[0]['text'] = "返回讲师列表";
    $link[0]['href'] = $_POST['url'];
    adminmsg("修改成功！", 2, $link);
}

elseif ($act == 'edit_course') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "cou_edit");
    $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : adminmsg("你没有选择课程！", 1);
    $train_id = !empty($_REQUEST['train_id']) ? intval($_REQUEST['train_id']) : adminmsg("你没有选择课程！", 1);
    $smarty->assign('pageheader', "课程管理");
    $course = get_course_one($id);
    $teachers = get_audit_teachers($train_id);
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('course', $course);
    $smarty->assign('teachers', $teachers);
    $smarty->assign('subsite', get_subsite_list());
    $smarty->display('train/admin_train_course_edit.htm');
} elseif ($act == 'editcourse_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "cou_edit");
    $id = intval($_POST['id']);
    $train_id = intval($_POST['train_id']);
    $train_profile = get_train_one_id($train_id);
    $setsqlarr['subsite_id'] = intval($_POST['subsite_id']);
    $setsqlarr['display'] = intval($_POST['display']);
    $setsqlarr['audit'] = intval($_POST['audit']);

    $setsqlarr['course_name'] = !empty($_POST['course_name']) ? trim($_POST['course_name']) : adminmsg('您没有填写课程名称！', 1);
    $setsqlarr['category'] = !empty($_POST['category']) ? intval($_POST['category']) : adminmsg('请选择课程类别！', 1);
    $setsqlarr['category_cn'] = trim($_POST['category_cn']);
    $setsqlarr['district'] = !empty($_POST['district']) ? intval($_POST['district']) : adminmsg('请选择工作地区！', 1);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);
    $setsqlarr['classtype'] = !empty($_POST['classtype']) ? intval($_POST['classtype']) : adminmsg('请选择上课班制！', 1);
    $setsqlarr['classtype_cn'] = trim($_POST['classtype_cn']);
    $setsqlarr['teacher_id'] = !empty($_POST['teacher_id']) ? intval($_POST['teacher_id']) : adminmsg('请选择主讲人！', 1);
    $setsqlarr['teacher_cn'] = trim($_POST['teacher_cn']);
    $setsqlarr['starttime'] = intval(convert_datefm($_POST['starttime'], 2));
    if (empty($setsqlarr['starttime'])) {
        adminmsg('请填写开课时间！时间格式：YYYY-MM-DD', 1);
    }
    $setsqlarr['train_object'] = !empty($_POST['train_object']) ? trim($_POST['train_object']) : adminmsg('您没有填写授课对象！', 1);
    $setsqlarr['train_certificate'] = !empty($_POST['train_certificate']) ? trim($_POST['train_certificate']) : '';
    $setsqlarr['classhour'] = !empty($_POST['classhour']) ? intval($_POST['classhour']) : adminmsg('您没有填写授课学时！', 1);
    $setsqlarr['train_expenses'] = !empty($_POST['train_expenses']) ? intval($_POST['train_expenses']) : adminmsg('您没有填写培训费用！', 1);
    $setsqlarr['favour_expenses'] = !empty($_POST['favour_expenses']) ? intval($_POST['favour_expenses']) : adminmsg('您没有填写优惠价格！', 1);

    $setsqlarr['contents'] = !empty($_POST['contents']) ? trim($_POST['contents']) : adminmsg('您没有填写课程描述！', 1);
    check_word($_CFG['filter'], $_POST['contents']) ? adminmsg($_CFG['filter_tips'], 0) : '';

    $setsqlarr['refreshtime'] = $timestamp;
    $setsqlarr['key'] = $setsqlarr['course_name'] . $train_profile['trainname'] . $setsqlarr['teacher_cn'] . $setsqlarr['train_certificate'] . $setsqlarr['category_cn'] . $setsqlarr['district_cn'] . $setsqlarr['contents'];
    require_once(QISHI_ROOT_PATH . 'include/splitword.class.php');
    $sp = new SPWord();
    $setsqlarr['key'] = "{$setsqlarr['course_name']} {$train_profile['trainname']} {$setsqlarr['teacher_cn']} {$setsqlarr['train_certificate']} " . $sp->extracttag($setsqlarr['key']);
    $setsqlarr['key'] = $sp->pad($setsqlarr['key']);
    $setsqlarr['likekey'] = "{$setsqlarr['course_name']},{$train_profile['trainname']},{$setsqlarr['teacher_cn']},{$setsqlarr['train_certificate']}";

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
    if (!updatetable(table('course'), $setsqlarr, $wheresql))
        adminmsg("保存失败！", 0);
    $wheresql = " pid=" . $id;
    if (!updatetable(table('course_contact'), $setsqlarr_contact, $wheresql))
        adminmsg("保存失败！", 0);
    $link[0]['text'] = "返回课程列表";
    $link[0]['href'] = $_POST['url'];
    adminmsg("修改成功！", 2, $link);
}
elseif ($act == 'course_perform') {
    check_token();
    $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : adminmsg("你没有选择课程！", 1);
    if (!empty($_REQUEST['delete'])) {
        check_permissions($_SESSION['admin_purview'], "cou_del");
        $num = del_course($yid);
        if ($num > 0) {
            adminmsg("删除成功！共删除" . $num . "行", 2);
        } else {
            adminmsg("删除失败！", 0);
        }
    } elseif (!empty($_POST['set_audit'])) {
        check_permissions($_SESSION['admin_purview'], "cou_audit");
        $audit = intval($_POST['audit']);
        $pms_notice = intval($_POST['pms_notice']);
        $reason = trim($_POST['reason']);
        if ($n = edit_course_audit($yid, $audit, $reason, $pms_notice)) {
            adminmsg("审核成功！响应行数 {$n}", 2);
        } else {
            adminmsg("审核成功！响应行数 0", 1);
        }
    } elseif (!empty($_GET['refresh'])) {
        if ($n = refresh_course($yid)) {
            adminmsg("刷新成功！响应行数 {$n}", 2);
        } else {
            adminmsg("刷新失败！", 0);
        }
    } elseif (!empty($_POST['set_delay'])) {
        $days = intval($_POST['days']);
        if (empty($days)) {
            adminmsg("请填写要延长的天数！", 0);
        }
        if ($n = delay_course($yid, $days)) {
            adminmsg("延长有效期成功！响应行数 {$n}", 2);
        } else {
            adminmsg("操作失败！", 0);
        }
    } elseif (!empty($_POST['set_recom'])) {
        check_permissions($_SESSION['admin_purview'], "tra_promotion");
        $rec_notice = intval($_POST['rec_notice']);
        $recommend = intval($_POST['recommend']);
        $notice = trim($_POST['notice']);
        if ($n = recom_course($yid, $recommend, $rec_notice, $notice)) {
            adminmsg("课程设置成功！响应行数 {$n}", 2);
        } else {
            adminmsg("课程设置失败！", 0);
        }
    }
} elseif ($act == 'train_list') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "tra_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY t.id DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($key && $key_type > 0) {
        if ($key_type === 1)
            $wheresql = " WHERE t.trainname like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE t.id ='" . intval($key) . "'";
        elseif ($key_type === 3)
            $wheresql = " WHERE m.username like '{$key}%'";
        elseif ($key_type === 4)
            $wheresql = " WHERE t.uid ='" . intval($key) . "'";
        elseif ($key_type === 5)
            $wheresql = " WHERE t.address  like '%{$key}%'";
        elseif ($key_type === 6)
            $wheresql = " WHERE t.telephone  like '{$key}%'";
        $oederbysql = "";
    }
    $_GET['audit'] <> "" ? $wheresqlarr['t.audit'] = intval($_GET['audit']) : '';
    $_GET['yellowpages'] <> "" ? $wheresqlarr['t.yellowpages'] = intval($_GET['yellowpages']) : '';
    $_GET['recom'] <> "" ? $wheresqlarr['t.recommend'] = intval($_GET['recom']) : '';
    if (is_array($wheresqlarr))
        $wheresql = wheresql($wheresqlarr);
    if (!empty($_GET['settr'])) {
        $settr = strtotime("-" . intval($_GET['settr']) . " day");
        $wheresql = empty($wheresql) ? " WHERE addtime> " . $settr : $wheresql . " AND addtime> " . $settr;
    }
    $operation_mode = $_CFG['operation_train_mode'];
    if ($operation_mode == '1') {
        $joinsql = " LEFT JOIN " . table('members') . " AS m ON t.uid=m.uid  LEFT JOIN " . table('members_points') . " AS p ON t.uid=p.uid";
    } else {
        $joinsql = " LEFT JOIN " . table('members') . " AS m ON t.uid=m.uid  LEFT JOIN " . table('members_train_setmeal') . " AS p ON t.uid=p.uid";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('train_profile') . " AS t" . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $clist = get_train($offset, $perpage, $joinsql . $wheresql . $oederbysql, $operation_mode);
    $smarty->assign('pageheader', "机构管理");
    $smarty->assign('clist', $clist);
    $smarty->assign('certificate_train_dir', $certificate_train_dir);
    $smarty->assign('page', $page->show(3));
    $smarty->display('train/admin_train_list.htm');
} elseif ($act == 'train_perform') {
    check_token();
    $u_id = !empty($_POST['y_id']) ? $_POST['y_id'] : adminmsg("你没有选择机构！", 1);
    if ($_POST['delete']) {
        check_permissions($_SESSION['admin_purview'], "tra_del");
        if ($_POST['delete_train'] == 'yes') {
            !del_train($u_id) ? adminmsg("删除机构资料失败！", 0) : "";
        }
        if ($_POST['delete_teacher'] == 'yes') {
            !del_train_allteacher($u_id) ? adminmsg("删除讲师失败！", 0) : "";
        }
        if ($_POST['delete_course'] == 'yes') {
            !del_train_allcourse($u_id) ? adminmsg("删除课程失败！", 0) : "";
        }
        if ($_POST['delete_course'] <> 'yes' && $_POST['delete_train'] <> 'yes' && $_POST['delete_teacher'] <> 'yes') {
            adminmsg("未选择删除类型！", 1);
        }
        adminmsg("删除成功！", 2);
    }
    if (trim($_POST['set_audit'])) {
        check_permissions($_SESSION['admin_purview'], "tra_audit");
        $audit = $_POST['audit'];
        $pms_notice = intval($_POST['pms_notice']);
        $reason = trim($_POST['reason']);
        !edit_train_audit($u_id, intval($audit), $reason, $pms_notice) ? adminmsg("设置失败！", 0) : adminmsg("设置成功！", 2);
    } elseif (!empty($_POST['set_refresh'])) {
        if (empty($_POST['refresh_course'])) {
            $refrescou = false;
        } else {
            $refrescou = true;
        }
        if ($n = refresh_train($u_id, $refrescou)) {
            adminmsg("刷新成功！响应行数 {$n} 行", 2);
        } else {
            adminmsg("刷新失败！", 0);
        }
    } elseif (!empty($_POST['set_recom'])) {
        check_permissions($_SESSION['admin_purview'], "tra_promotion");
        $rec_notice = intval($_POST['rec_notice']);
        $recommend = intval($_POST['recommend']);
        $notice = trim($_POST['notice']);
        if ($n = recom_train($u_id, $recommend, $rec_notice, $notice)) {
            adminmsg("机构设置成功！响应行数 {$n}", 2);
        } else {
            adminmsg("机构设置失败！", 0);
        }
    }
} elseif ($act == 'edit_train_profile') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "tra_edit");
    $yid = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : adminmsg("你没有选择机构！", 1);
    $smarty->assign('pageheader', "机构管理");
    $train_profile = get_train_one_id($yid);
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('train_profile', $train_profile);
    $smarty->assign('certificate_train_dir', $certificate_train_dir); //营业执照路径
    $smarty->display('train/admin_train_profile_edit.htm');
} elseif ($act == 'train_profile_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "train_edit");
    $id = intval($_POST['id']);
    $setsqlarr['uid'] = intval($_POST['tuid']);
    $setsqlarr['trainname'] = trim($_POST['trainname']) ? trim($_POST['trainname']) : adminmsg('您没有输入机构名称！', 1);
    $setsqlarr['nature'] = trim($_POST['nature']) ? intval($_POST['nature']) : adminmsg('请选择机构性质！', 1);
    $setsqlarr['nature_cn'] = trim($_POST['nature_cn']);
    $setsqlarr['founddate'] = intval(convert_datefm($_POST['founddate'], 2));
    if (empty($setsqlarr['founddate'])) {
        adminmsg('请填写成立时间！时间格式：YYYY-MM-DD', 1);
    }
    if ($setsqlarr['founddate'] >= time()) {
        adminmsg('成立时间不能大于今天', 1);
    }
    $setsqlarr['audit'] = intval($_POST['audit']);
    $setsqlarr['district'] = intval($_POST['district']) > 0 ? intval($_POST['district']) : adminmsg('请选择所属地区！', 1);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);
    $setsqlarr['address'] = trim($_POST['address']) ? trim($_POST['address']) : adminmsg('请填写通讯地址！', 1);
    $setsqlarr['contact'] = trim($_POST['contact']) ? trim($_POST['contact']) : adminmsg('请填写联系人！', 1);
    $setsqlarr['telephone'] = trim($_POST['telephone']) ? trim($_POST['telephone']) : adminmsg('请填写联系电话！', 1);
    $setsqlarr['email'] = trim($_POST['email']) ? trim($_POST['email']) : adminmsg('请填写联系邮箱！', 1);
    $setsqlarr['website'] = trim($_POST['website']);
    $setsqlarr['contents'] = trim($_POST['contents']) ? trim($_POST['contents']) : adminmsg('请填写公司简介！', 1);
    $setsqlarr['teacherpower'] = trim($_POST['teacherpower']) ? trim($_POST['teacherpower']) : adminmsg('请填写师资力量！', 1);
    $setsqlarr['achievement'] = trim($_POST['achievement']) ? trim($_POST['achievement']) : adminmsg('请填写主要业绩！', 1);
    $setsqlarr['yellowpages'] = intval($_POST['yellowpages']);

    $setsqlarr['contact_show'] = intval($_POST['contact_show']);
    $setsqlarr['email_show'] = intval($_POST['email_show']);
    $setsqlarr['telephone_show'] = intval($_POST['telephone_show']);
    $setsqlarr['address_show'] = intval($_POST['address_show']);
    $wheresql = " id='{$id}' ";
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    if (updatetable(table('train_profile'), $setsqlarr, $wheresql)) {
        $coursearr['trainname'] = $setsqlarr['trainname'];
        if (!updatetable(table('course'), $coursearr, " uid=" . $setsqlarr['uid'] . ""))
            adminmsg('修改培训机构名称出错！', 0);
        if (!updatetable(table('train_teachers'), $coursearr, " uid=" . $setsqlarr['uid'] . ""))
            adminmsg('修改培训机构名称出错！', 0);
        unset($setsqlarr);
        adminmsg("保存成功！", 2, $link);
    }
    else {
        unset($setsqlarr);
        adminmsg("保存失败！", 0);
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
            $wheresql.=" AND c.trainname like '{$key}%'";
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
    $joinsql = " LEFT JOIN " . table('members') . " as b ON a.uid=b.uid  LEFT JOIN " . table('train_profile') . " as c ON a.uid=c.uid ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('members_train_setmeal') . " as a " . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $member = get_meal_members_list($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "机构管理");
    $smarty->assign('navlabel', 'meal_members');
    $smarty->assign('member', $member);
    $smarty->assign('setmeal', get_setmeal());
    $smarty->assign('page', $page->show(3));
    $smarty->display('train/admin_train_meal_members.htm');
} elseif ($act == 'members_list') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "tra_user_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $wheresql = " WHERE m.utype=4 ";
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
            $wheresql.=" AND t.trainname like '%{$key}%'";
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
    $joinsql = " LEFT JOIN " . table('train_profile') . " as t ON m.uid=t.uid ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('members') . " as m " . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $member = get_member_list($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "培训会员");
    $smarty->assign('member', $member);
    $smarty->assign('page', $page->show(3));
    $smarty->display('train/admin_train_user_list.htm');
} elseif ($act == 'user_edit') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "tra_user_edit");
    $train_user = get_user($_GET['tuid']);
    $smarty->assign('pageheader', "机构会员");
    $train_profile = get_train_one_uid($train_user['uid']);
    $train_user['tpl'] = $train_profile['tpl'];
    $smarty->assign('train_user', $train_user);
    $smarty->assign('userpoints', get_user_points($train_user['uid']));
    $smarty->assign('setmeal', get_user_setmeal($train_user['uid']));
    $smarty->assign('givesetmeal', get_setmeal(false));
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->display('train/admin_train_user_edit.htm');
} elseif ($act == 'set_account_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "tra_user_edit");
    require_once(ADMIN_ROOT_PATH . 'include/admin_user_fun.php');
    $setsqlarr['username'] = trim($_POST['username']);
    $setsqlarr['email'] = trim($_POST['email']);
    $setsqlarr['email_audit'] = intval($_POST['email_audit']);
    $setsqlarr['mobile'] = trim($_POST['mobile']);
    $setsqlarr['mobile_audit'] = intval($_POST['mobile_audit']);
    if ($_POST['qq_openid'] == "1") {
        $setsqlarr['qq_openid'] = '';
    }
    $thisuid = intval($_POST['train_uid']);
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
        updatetable(table('train_profile'), $tplarr, " uid='{$thisuid}'");
        updatetable(table('course'), $tplarr, " uid='{$thisuid}'");
        updatetable(table('train_teachers'), $tplarr, " uid='{$thisuid}'");
        unset($tplarr);
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
    check_permissions($_SESSION['admin_purview'], "tra_user_edit");
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
    check_permissions($_SESSION['admin_purview'], "tra_user_edit");
    if ($db->query("UPDATE " . table('members') . " SET status = '" . intval($_POST['status']) . "'  WHERE uid='" . intval($_POST['userstatus_uid']) . "'")) {
        $link[0]['text'] = "返回列表";
        $link[0]['href'] = $_POST['url'];
        adminmsg('操作成功！', 2, $link);
    } else {
        adminmsg('操作失败！', 1);
    }
} elseif ($act == 'userpoints_edit') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "tra_user_edit");
    if (intval($_POST['points']) < 1)
        adminmsg('请输入积分！', 1);
    if (trim($_POST['points_notes']) == '')
        adminmsg('请填写积分操作说明！', 1);
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    $user = get_user($_POST['train_uid']);
    $points_type = intval($_POST['points_type']);
    $t = $points_type == 1 ? "+" : "-";
    report_deal($user['uid'], $points_type, intval($_POST['points']));
    $points = get_user_points($user['uid']);
    $user_limit_points = get_user_limit_points($_SESSION['uid']);
    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
    write_memberslog(intval($_POST['train_uid']), 4, 9101, $user['username'], " 管理员操作积分({$t}{$_POST['points']})，(剩余积分:{$points}，剩余限时积分:{$user_limit_points})，备注：" . $_POST['points_notes']);
    $user = get_user($_POST['train_uid']);
    if (intval($_POST['is_money']) && $_POST['log_amount']) {
        $amount = round($_POST['log_amount'], 2);
        $ismoney = 2;
    } else {
        $amount = '0.00';
        $ismoney = 1;
    }
    $notes = "操作人：{$_SESSION['admin_name']},说明：修改会员 {$user['username']} 积分 ({$t}{$_POST['points']})。收取积分金额：{$amount} 元，备注：{$_POST['points_notes']}";
    write_setmeallog($_POST['train_uid'], $user['username'], $notes, 3, $amount, $ismoney, 1, 4);
    adminmsg('保存成功！', 2);
} elseif ($act == 'edit_setmeal_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "tra_user_edit");
    $setsqlarr['teachers_num'] = $_POST['teachers_num'];
    $setsqlarr['course_num'] = $_POST['course_num'];
    $setsqlarr['down_apply'] = $_POST['down_apply'];
    $setsqlarr['change_templates'] = intval($_POST['change_templates']);
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
    $train_uid = intval($_POST['train_uid']);
    if ($train_uid) {
        $setmeal = get_user_setmeal($train_uid);
        if (!updatetable(table('members_train_setmeal'), $setsqlarr, " uid=" . $train_uid . ""))
            adminmsg('修改出错！', 0);

        //会员套餐变更记录。管理员后台修改会员套餐：修改会员。3表示：管理员后台修改
        $setmeal['endtime'] = date('Y-m-d', $setmeal['endtime']);
        $setsqlarr['endtime'] = date('Y-m-d', $setsqlarr['endtime']);
        $setsqlarr['log_amount'] = round($_POST['log_amount']);
        $notes = edit_setmeal_notes($setsqlarr, $setmeal);
        if ($notes) {
            $user = get_user($_POST['train_uid']);
            $ismoney = round($_POST['log_amount']) ? 2 : 1;
            write_setmeallog($train_uid, $user['username'], $notes, 3, $setsqlarr['log_amount'], $ismoney, 2, 4);
        }
        if ($setsqlarr['endtime'] <> "") {
            $setmeal_deadline['setmeal_deadline'] = $setmealtime;
            if (!updatetable(table('course'), $setmeal_deadline, " uid='{$train_uid}' AND add_mode='2' "))
                adminmsg('修改出错！', 0);
        }
    }
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    adminmsg('操作成功！', 2, $link);
}
elseif ($act == 'set_setmeal_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "tra_user_edit");
    if (intval($_POST['reg_service']) > 0) {
        if (set_members_setmeal($_POST['train_uid'], $_POST['reg_service'])) {
            $link[0]['text'] = "返回列表";
            $link[0]['href'] = $_POST['url'];

            //会员套餐变更记录。管理员后台修改会员套餐：重新开通套餐。3表示：管理员后台修改
            $user = get_user($_POST['train_uid']);
            if (intval($_POST['is_money']) && $_POST['log_amount']) {
                $amount = round($_POST['log_amount'], 2);
                $ismoney = 2;
            } else {
                $amount = '0.00';
                $ismoney = 1;
            }
            $notes = "操作人：{$_SESSION['admin_name']},说明：为会员 {$user['username']} 重新开通服务，收取服务金额：{$amount}元，服务ID：{$_POST['reg_service']}。";
            write_setmeallog($_POST['train_uid'], $user['username'], $notes, 4, $amount, $ismoney, 2, 4);
            adminmsg('操作成功！', 2, $link);
        } else {
            adminmsg('操作失败！', 1);
        }
    } else {
        adminmsg('请选择服务套餐！', 1);
    }
} elseif ($act == 'order_list') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "ord_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    require_once(ADMIN_ROOT_PATH . 'include/admin_pay_fun.php');
    $wheresql = " WHERE o.utype=4 ";
    $oederbysql = " order BY o.addtime DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($key && $key_type > 0) {
        if ($key_type === 1)
            $wheresql = " WHERE o.utype=4 AND c.trainname like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE o.utype=4 AND m.username = '{$key}'";
        elseif ($key_type === 3)
            $wheresql = " WHERE o.utype=4 AND o.oid ='" . trim($key) . "'";
        $oederbysql = "";
    }
    else {
        $wheresqlarr['o.utype'] = '4';
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
    $joinsql = " left JOIN " . table('members') . " as m ON o.uid=m.uid LEFT JOIN  " . table('train_profile') . " as c ON o.uid=c.uid ";
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
    $smarty->display('train/admin_order_list.htm');
} elseif ($act == 'show_order') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "ord_show");
    $smarty->assign('pageheader', "订单管理");
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('payment', get_order_one($_GET['id']));
    $smarty->display('train/admin_order_show.htm');
} elseif ($act == 'order_notes_save') {
    check_token();
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = $_POST['url'];
    !$db->query("UPDATE " . table('order') . " SET  notes='" . $_POST['notes'] . "' WHERE id='" . intval($_GET['id']) . "'") ? adminmsg('操作失败', 1) : adminmsg("操作成功！", 2, $link);
} elseif ($act == 'order_set') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "ord_set");
    $smarty->assign('pageheader', "订单管理");
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('payment', get_order_one($_GET['id']));
    $smarty->display('train/admin_order_set.htm');
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
} elseif ($act == 'meal_log') {
    get_token();
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY a.log_id DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    $operation_mode = $_CFG['operation_train_mode'];
    $wheresql = " WHERE a.log_mode={$operation_mode} AND a.log_utype=4";
    if ($key && $key_type > 0) {
        if ($key_type === 1)
            $wheresql.="  AND a.log_username = '{$key}'";
        elseif ($key_type === 2)
            $wheresql.="  AND a.log_uid = '" . intval($key) . "' ";
        elseif ($key_type === 3)
            $wheresql.=" AND c.trainname like '{$key}%'";
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
        $joinsql = " LEFT JOIN " . table('members_points') . " as b ON a.log_uid=b.uid  LEFT JOIN " . table('train_profile') . " as c ON a.log_uid=c.uid ";
    } else {
        $joinsql = " LEFT JOIN " . table('members_train_setmeal') . " as b ON a.log_uid=b.uid  LEFT JOIN " . table('train_profile') . " as c ON a.log_uid=c.uid ";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('members_charge_log') . " as a " . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $meallog = get_meal_members_log($offset, $perpage, $joinsql . $wheresql . $oederbysql, $operation_mode);
    $smarty->assign('pageheader', '机构管理');
    $smarty->assign('navlabel', 'meal_log');
    $smarty->assign('meallog', $meallog);
    $smarty->assign('page', $page->show(3));
    $smarty->display('train/admin_train_meal_log.htm');
} elseif ($act == 'meal_log_pie') {
    require_once(ADMIN_ROOT_PATH . 'include/admin_flash_statement_fun.php');
    $pie_type = !empty($_GET['pie_type']) ? intval($_GET['pie_type']) : 1;
    meal_train_log_pie($pie_type, 4);
    $smarty->assign('pageheader', "机构管理");
    $smarty->assign('navlabel', 'meal_log_pie');
    $smarty->display('train/admin_train_meal_log_pie.htm');
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
} elseif ($act == 'delete_user') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "tra_user_del");
    $tuid = !empty($_REQUEST['tuid']) ? $_REQUEST['tuid'] : adminmsg("你没有选择会员！", 1);
    if ($_POST['delete']) {
        if (!empty($_POST['delete_user'])) {
            !delete_train_user($tuid) ? adminmsg("删除会员失败！", 0) : "";
        }
        if (!empty($_POST['delete_train'])) {
            !del_train($tuid) ? adminmsg("删除机构资料失败！", 0) : "";
        }
        if (!empty($_POST['delete_teachers'])) {
            !del_train_allteacher($tuid) ? adminmsg("删除机构讲师失败！", 0) : "";
        }
        if (!empty($_POST['delete_course'])) {
            !del_train_allcourse($tuid) ? adminmsg("删除机构课程失败！", 0) : "";
        }
        adminmsg("删除成功！", 2);
    }
} elseif ($act == 'members_add') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "tra_user_add");
    $smarty->assign('pageheader', "机构会员");
    $smarty->assign('givesetmeal', get_setmeal(false));
    $smarty->assign('points', get_cache('points_rule'));
    $smarty->display('train/admin_train_user_add.htm');
} elseif ($act == 'members_add_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "tra_user_add");
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
    if ($sql['utype'] == "4") {
        $db->query("INSERT INTO " . table('members_points') . " (uid) VALUES ('{$insert_id}')");
        $db->query("INSERT INTO " . table('members_train_setmeal') . " (uid) VALUES ('{$insert_id}')");
        if (intval($_POST['is_money']) && $_POST['log_amount']) {
            $amount = round($_POST['log_amount'], 2);
            $ismoney = 2;
        } else {
            $amount = '0.00';
            $ismoney = 1;
        }
        $regpoints_num = intval($_POST['regpoints_num']);
        if ($_POST['regpoints'] == "y") {
            write_memberslog($insert_id, 4, 9101, $sql['username'], "<span style=color:#FF6600>注册会员系统自动赠送!(+{$regpoints_num})</span>");
            $notes = "操作人：{$_SESSION['admin_name']},说明：后台添加机构会员并赠送(+{$regpoints_num})积分，收取费用：{$amount}元";
            write_setmeallog($insert_id, $sql['username'], $notes, 4, $amount, $ismoney, 1, 4);
            report_deal($insert_id, 1, $regpoints_num);
        }
        $reg_service = intval($_POST['train_reg_service']);
        if ($reg_service > 0) {
            $service = get_setmeal_one($reg_service);
            write_memberslog($insert_id, 4, 9102, $sql['username'], "开通服务({$service['setmeal_name']})");
            set_members_setmeal($insert_id, $reg_service);
            $notes = "操作人：{$_SESSION['admin_name']},说明：后台添加机构会员并开通服务({$service['setmeal_name']})，收取费用：{$amount}元";
            write_setmeallog($insert_id, $sql['username'], $notes, 4, $amount, $ismoney, 2, 4);
        }
        if (intval($_POST['is_money']) && $_POST['log_amount'] && !$notes) {
            $notes = "操作人：{$_SESSION['admin_name']},说明：后台添加机构会员，未赠送积分，未开通套餐，收取费用：{$amount}元";
            write_setmeallog($insert_id, $sql['username'], $notes, 4, $amount, 2, 2, 4);
        }
    }
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "?act=members_list";
    $link[1]['text'] = "继续添加";
    $link[1]['href'] = "?act=members_add";
    adminmsg('添加成功！', 2, $link);
} elseif ($act == 'train_img') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "tra_img_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY i.id DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($key && $key_type > 0) {

        if ($key_type === 1)
            $wheresql = " WHERE t.trainname like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE t.id ='" . intval($key) . "'";
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
    $joinsql = " LEFT JOIN " . table('train_profile') . " AS t ON i.train_id=t.id  ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('train_img') . " AS i" . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $clist = get_train_img($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "机构图片");
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('train/admin_train_img.htm');
} elseif ($act == 'del_train_img') {
    check_permissions($_SESSION['admin_purview'], "tra_img_del");
    check_token();
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择图片！", 1);
    $num = del_train_img($id);
    if ($num > 0) {
        adminmsg("删除成功！共删除" . $num . "行", 2);
    } else {
        adminmsg("删除失败！", 0);
    }
} elseif ($act == 'edit_img_audit') {
    check_permissions($_SESSION['admin_purview'], "tra_img_audit");
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
} elseif ($act == 'train_news') {
    get_token();
    check_permissions($_SESSION['admin_purview'], "tra_news_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $oederbysql = " order BY n.id DESC ";
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    if ($key && $key_type > 0) {

        if ($key_type === 1)
            $wheresql = " WHERE t.trainname like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE t.id ='" . intval($key) . "'";
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
    $joinsql = " LEFT JOIN " . table('train_profile') . " AS t ON n.train_id=t.id  ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('train_news') . " AS n" . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $clist = get_train_news($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('pageheader', "机构新闻");
    $smarty->assign('clist', $clist);
    $smarty->assign('page', $page->show(3));
    $smarty->display('train/admin_train_news.htm');
} elseif ($act == 'edit_news_audit') {
    check_permissions($_SESSION['admin_purview'], "tra_news_audit");
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
} elseif ($act == 'del_train_news') {
    check_permissions($_SESSION['admin_purview'], "tra_news_del");
    check_token();
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择新闻！", 1);
    $num = del_train_news($id);
    if ($num > 0) {
        adminmsg("删除成功！共删除" . $num . "行", 2);
    } else {
        adminmsg("删除失败！", 0);
    }
} elseif ($act == 'edit_train_news') {
    check_permissions($_SESSION['admin_purview'], "tra_news_edit");
    get_token();
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : adminmsg("你没有选择新闻！", 1);
    $news = get_news_one($id);
    $smarty->assign('news', $news);
    $smarty->assign('url', $_SERVER["HTTP_REFERER"]);
    $smarty->assign('pageheader', "机构新闻");
    $smarty->display('train/admin_train_news_edit.htm');
} elseif ($act == 'train_news_save') {
    check_token();
    check_permissions($_SESSION['admin_purview'], "tra_news_edit");
    $id = intval($_POST['id']);
    $setsqlarr['title'] = $_POST['title'] ? trim($_POST['title']) : adminmsg("你没有填写新闻标题！", 1);
    $setsqlarr['content'] = $_POST['content'] ? trim($_POST['content']) : adminmsg("你没有填写新闻内容！", 1);
    $setsqlarr['click'] = intval($_POST['click']);
    $setsqlarr['order'] = intval($_POST['order']);
    $setsqlarr['audit'] = intval($_POST['audit']);
    $setsqlarr['addtime'] = time();
    $link[1]['text'] = "返回新闻列表";
    $link[1]['href'] = '?act=train_news';
    $link[0]['text'] = '查看修改结果';
    $link[0]['href'] = '?act=edit_train_news&id=' . $id;
    !updatetable(table('train_news'), $setsqlarr, ' id=' . $id . ' ') ? adminmsg("修改机构新闻失败！", 1, $link) : adminmsg("修改机构新闻成功！", 2, $link);
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