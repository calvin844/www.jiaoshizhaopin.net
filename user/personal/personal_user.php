<?php

/*
 * 74cms 个人会员中心
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/personal_common.php');
$smarty->assign('leftmenu', "user");
if ($act == 'binding') {
    $smarty->assign('user', $user);
    $smarty->assign('title', '账号绑定 - 会员中心 - ' . $_CFG['site_name']);
    $smarty->display('member_personal/personal_binding.htm');
} elseif ($act == 'userprofile') {
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $_SESSION['send_key'] = mt_rand(100000, 999999);
    $uid = intval($_SESSION['uid']);
    $resume = get_resume_basic($uid);
    if (!empty($resume['attachment_resume'])) {
        $resume['attachment_resume_name'] = explode("--", $resume['attachment_resume']);
        $resume['attachment_resume_name'] = $resume['attachment_resume_name'][2];
    }
    $smarty->assign('total', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1'"));
    $smarty->assign('send_mobile_key', $_SESSION['send_mobile_key']);
    $smarty->assign('send_email_key', $_SESSION['send_key']);
    $smarty->assign('user', $user);
    $smarty->assign('title', '个人资料 - 会员中心 - ' . $_CFG['site_name']);
    //$smarty->assign('userprofile', get_userprofile($uid));
    $smarty->assign('resume', $resume);
    $smarty->assign('resume_education', get_resume_education($uid, $resume['id']));
    $smarty->assign('resume_work', get_resume_work($uid, $resume['id']));
    $subscribe = set_subscribe($user['email']);
    $job_str = "";
    if (!empty($subscribe['subject_id'])) {
        $id_arr = explode(",", $subscribe['subject_id']);
        foreach ($id_arr as $ia) {
            $temp = explode(".", $ia);
            $temp[2] == 0 ? $select .= $temp[1] . "," : $select .= $temp[2] . ",";
        }
        $select = trim($select, ",");
        if (!empty($select)) {
            $job = $db->getall("SELECT * FROM " . table('category_jobs') . " WHERE id IN(" . $select . ")");
            foreach ($job as $j) {
                $job_str.=$j['categoryname'] . ",";
            }
        }
    }
    $subscribe['job_str'] = trim($job_str, ",");
    $subscribe = !empty($job_str) ? $subscribe : "";
    $smarty->assign('subscribe', $subscribe);
    $smarty->display('member_personal/personal_userprofile.htm');
} elseif ($act == 'wechat_save') {
    $uid = intval($_SESSION['uid']);
    $setsqlarr['wechat_name'] = !empty($_POST['wechat_name']) ? trim($_POST['wechat_name']) : "";
    $job_id = !empty($_POST['job_id']) ? intval($_POST['job_id']) : 0;
    $user = get_user_info($uid);
    if (empty($setsqlarr['wechat_name']) && empty($user['wechat_img'])) {
        showmsg('微信号和微信个人二维码至少填写一项！', 1);
    }
    if (!empty($user)) {
        $wheresql = " uid='" . $uid . "'";
        write_memberslog($uid, 2, 1005, $uid, "修改了个人资料");
        $up_flag = updatetable(table('members'), $setsqlarr, $wheresql);
    } else {
        write_memberslog($uid, 2, 1005, $uid, "修改了个人资料");
        $up_flag = !inserttable(table('members'), $setsqlarr);
    }
    if ($up_flag) {
        showmsg("提交成功！", 2);
    } else {
        showmsg("提交失败！", 2);
    }
} elseif ($act == 'userprofile_save') {
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    if ($user["email_audit"] == 0) {
        $setsqlarr['email'] = trim($_POST['email']) ? trim($_POST['email']) : showmsg('请填写邮箱！', 1);
        $email_verifycode = trim($_POST['email_verifycode']);
        if ($email_verifycode) {
            if (empty($_SESSION['email_rand']) || $email_verifycode <> $_SESSION['email_rand']) {
                showmsg("邮箱验证码错误", 1);
            } else {
                $verifysqlarr['email'] = $setsqlarr['email'];
                $verifysqlarr['email_audit'] = 1;
                updatetable(table('members'), $verifysqlarr, " uid='{$setsqlarr['uid']}'");
                unset($verifysqlarr);
            }
        }
        unset($_SESSION['verify_email'], $_SESSION['email_rand']);
    }
    if ($user["mobile_audit"] == 0) {
        $setsqlarr['phone'] = trim($_POST['mobile']) ? trim($_POST['mobile']) : showmsg('请填写手机号！', 1);
        $mobile_verifycode = trim($_POST['mobile_verifycode']);
        if ($mobile_verifycode) {
            if (empty($_SESSION['mobile_rand']) || $mobile_verifycode <> $_SESSION['mobile_rand']) {
                showmsg("手机验证码错误", 1);
            } else {
                $verifysqlarr['mobile'] = $setsqlarr['phone'];
                $verifysqlarr['mobile_audit'] = 1;
                updatetable(table('members'), $verifysqlarr, " uid='{$setsqlarr['uid']}'");
                unset($verifysqlarr);
            }
        }
        unset($_SESSION['verify_mobile'], $_SESSION['mobile_rand']);
    }


    $setsqlarr['realname'] = trim($_POST['realname']) ? trim($_POST['realname']) : showmsg('请填写真实姓名！', 1);
    $setsqlarr['sex'] = intval($_POST['sex']) ? intval($_POST['sex']) : showmsg('请选择性别！', 1);
    $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
    $setsqlarr['birthday'] = intval($_POST['birthday']) ? intval($_POST['birthday']) : showmsg('请选择出生年份', 1);
    $setsqlarr['residence'] = trim($_POST['residence']) ? trim($_POST['residence']) : showmsg('请选择现居住地！', 1);
    $setsqlarr['residence_cn'] = trim($_POST['residence_cn']);
    $setsqlarr['education'] = intval($_POST['education']) ? intval($_POST['education']) : showmsg('请选择学历', 1);
    $setsqlarr['education_cn'] = trim($_POST['education_cn']);
    $setsqlarr['experience'] = intval($_POST['experience']) ? intval($_POST['experience']) : showmsg('请选择工作经验', 1);
    $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
    $setsqlarr['height'] = intval($_POST['height']);
    $setsqlarr['householdaddress'] = trim($_POST['householdaddress']);
    $setsqlarr['householdaddress_cn'] = trim($_POST['householdaddress_cn']);
    $setsqlarr['marriage'] = intval($_POST['marriage']);
    $setsqlarr['marriage_cn'] = trim($_POST['marriage_cn']);
    if (get_userprofile($_SESSION['uid'])) {
        $wheresql = " uid='" . intval($_SESSION['uid']) . "'";
        write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "修改了个人资料");
        !updatetable(table('members_info'), $setsqlarr, $wheresql) ? showmsg("修改失败！", 0) : showmsg("修改成功！", 2);
    } else {
        $setsqlarr['uid'] = intval($_SESSION['uid']);
        write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "修改了个人资料");
        !inserttable(table('members_info'), $setsqlarr) ? showmsg("修改失败！", 0) : showmsg("修改成功！", 2);
    }
}
//头像
elseif ($act == 'avatars') {
    $uid = intval($_SESSION['uid']);
    $smarty->assign('total', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1'"));
    $smarty->assign('title', '个人头像 - 会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('user', $user);
    $smarty->assign('rand', rand(1, 100));
    $smarty->display('member_personal/personal_avatars.htm');
} elseif ($act == 'avatars_ready') {
    require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
    !$_FILES['avatars']['name'] ? showmsg('请上传图片！', 1) : "";
    $up_dir_original = "../../data/avatar/original/";
    $up_dir_100 = "../../data/avatar/100/";
    $up_dir_48 = "../../data/avatar/48/";
    $up_dir_thumb = "../../data/avatar/thumb/";

    make_dir($up_dir_original . date("Y/m/d/"));
    make_dir($up_dir_100 . date("Y/m/d/"));
    make_dir($up_dir_48 . date("Y/m/d/"));
    make_dir($up_dir_thumb . date("Y/m/d/"));

    $setsqlarr['avatars'] = _asUpFiles($up_dir_original . date("Y/m/d/"), "avatars", 500, 'gif/jpg/bmp/png', true);
    $setsqlarr['avatars'] = date("Y/m/d/") . $setsqlarr['avatars'];
    if ($setsqlarr['avatars']) {
        makethumb($up_dir_original . $setsqlarr['avatars'], $up_dir_thumb . date("Y/m/d/"), 445, 300);
        // makethumb($up_dir_original.$setsqlarr['avatars'],$up_dir_100.date("Y/m/d/"),100,100);
        // makethumb($up_dir_original.$setsqlarr['avatars'],$up_dir_48.date("Y/m/d/"),48,48);
        $wheresql = " uid='" . $_SESSION['uid'] . "'";
        write_memberslog($_SESSION['uid'], 2, 1006, $_SESSION['username'], "修改了个人头像");
        updatetable(table('members'), $setsqlarr, $wheresql) ? exit($setsqlarr['avatars']) : showmsg('保存失败！', 1);
    } else {
        showmsg('保存失败！', 1);
    }
} elseif ($act == 'avatars_save') {
    require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
    require_once(QISHI_ROOT_PATH . 'include/imageresize.class.php');
    $imgresize = new ImageResize();
    $userinfomation = get_user_info($_SESSION['uid']);
    if ($userinfomation['avatars']) {
        $up_dir_original = "../../data/avatar/original/";
        $up_dir_100 = "../../data/avatar/100/";
        $up_dir_48 = "../../data/avatar/48/";
        $up_dir_thumb = "../../data/avatar/thumb/";
        $imgresize->load($up_dir_thumb . $userinfomation['avatars']);
        $imgresize->cut(intval($_POST['w']), intval($_POST['h']), intval($_POST['x']), intval($_POST['y']));
        $imgresize->save($up_dir_thumb . $userinfomation['avatars']);

        makethumb($up_dir_thumb . $userinfomation['avatars'], $up_dir_100 . date("Y/m/d/"), 100, 100);
        makethumb($up_dir_thumb . $userinfomation['avatars'], $up_dir_48 . date("Y/m/d/"), 48, 48);

        @unlink($up_dir_original . $userinfomation['avatars']);
        @unlink($up_dir_thumb . $userinfomation['avatars']);

        $wheresql = " uid='" . $_SESSION['uid'] . "'";
        write_memberslog($_SESSION['uid'], 2, 1006, $_SESSION['username'], "修改了个人头像");
        showmsg('保存成功！', 2);
    } else {
        showmsg('请上传图片！', 1);
    }
}
//修改密码
elseif ($act == 'password_edit') {
    $uid = intval($_SESSION['uid']);
    $smarty->assign('total', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1'"));
    $smarty->assign('title', '修改密码 - 个人会员中心 - ' . $_CFG['site_name']);
    $smarty->display('member_personal/personal_password.htm');
}
//保存修改密码
elseif ($act == 'save_password') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $arr['username'] = $_SESSION['username'];
    $arr['oldpassword'] = trim($_POST['oldpassword']) ? trim($_POST['oldpassword']) : showmsg('请输入旧密码！', 1);
    $arr['password'] = trim($_POST['password']) ? trim($_POST['password']) : showmsg('请输入新密码！', 1);
    if ($arr['password'] != trim($_POST['password1']))
        showmsg('两次输入密码不相同，请重新输入！', 1);
    $info = edit_password($arr);
    if ($info == -1)
        showmsg('旧密码输入错误，请重新输入！', 1);
    if ($info == $_SESSION['username']) {
        //发送邮件
        $mailconfig = get_cache('mailconfig');
        if ($mailconfig['set_editpwd'] == "1" && $user['email_audit'] == "1") {
            dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid=" . $_SESSION['uid'] . "&key=" . asyn_userkey($_SESSION['uid']) . "&act=set_editpwd&newpassword=" . $arr['password']);
        }
        //邮件发送完毕
        //sms
        $sms = get_cache('sms_config');
        if ($sms['open'] == "1" && $sms['set_editpwd'] == "1" && $user['mobile_audit'] == "1") {
            dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid=" . $_SESSION['uid'] . "&key=" . asyn_userkey($_SESSION['uid']) . "&act=set_editpwd&newpassword=" . $arr['password']);
        }
        //sms
        if (defined('UC_API')) {
            include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
            uc_user_edit($arr['username'], $arr['oldpassword'], $arr['password']);
        }
        write_memberslog($_SESSION['uid'], 2, 1004, $_SESSION['username'], "修改密码");
        showmsg('密码修改成功！', 2);
    }
} elseif ($act == 'authenticate') {
    $uid = intval($_SESSION['uid']);
    $smarty->assign('total', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1'"));
    $smarty->assign('user', $user);
    $smarty->assign('re_audit', $_GET['re_audit']);
    $smarty->assign('title', '验证邮箱 - 个人会员中心 - ' . $_CFG['site_name']);
    $_SESSION['send_key'] = mt_rand(100000, 999999);
    $smarty->assign('send_key', $_SESSION['send_key']);
    $smarty->display('member_personal/personal_authenticate.htm');
} elseif ($act == 'feedback') {
    $smarty->assign('title', '用户反馈 - 个人会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('feedback', get_feedback($_SESSION['uid']));
    $smarty->display('member_personal/personal_feedback.htm');
}
//保存用户反馈
elseif ($act == 'feedback_save') {
    $get_feedback = get_feedback($_SESSION['uid']);
    if (count($get_feedback) >= 5) {
        showmsg('反馈信息不能超过5条！', 1);
        exit();
    }
    $setsqlarr['infotype'] = intval($_POST['infotype']);
    $setsqlarr['feedback'] = trim($_POST['feedback']) ? trim($_POST['feedback']) : showmsg('请填写内容！', 1);
    $setsqlarr['uid'] = $_SESSION['uid'];
    $setsqlarr['usertype'] = $_SESSION['utype'];
    $setsqlarr['username'] = $_SESSION['username'];
    $setsqlarr['addtime'] = $timestamp;
    write_memberslog($_SESSION['uid'], 2, 7001, $_SESSION['username'], "添加反馈信息");
    !inserttable(table('feedback'), $setsqlarr) ? showmsg("添加失败！", 0) : showmsg("添加成功，请等待管理员回复！", 2);
}
//删除用户反馈
elseif ($act == 'del_feedback') {
    $id = intval($_GET['id']);
    del_feedback($id, $_SESSION['uid']) ? showmsg('删除成功！', 2) : showmsg('删除失败！', 1);
} elseif ($act == 'pm') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $perpage = 10;
    $uid = intval($_SESSION['uid']);
    $wheresql = " WHERE (p.msgfromuid='{$uid}' OR p.msgtouid='{$uid}') ";
    $joinsql = " LEFT JOIN  " . table('members') . " AS i  ON  p.msgfromuid=i.uid ";
    $orderby = " order by p.pmid desc";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('pms') . ' AS p ' . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $sql = "SELECT p.* FROM " . table('pms') . ' AS p' . $joinsql . $wheresql . $orderby;
    $smarty->assign('pms', get_pms($offset, $perpage, $sql));
    $smarty->assign('total', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1'"));
    $smarty->assign('title', '短消息 - 会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('uid', $uid);
    $db->query("UPDATE " . table('pms') . " SET `new`='2' WHERE new=1 AND msgtouid='{$uid}'");
    $smarty->display('member_personal/personal_user_pm.htm');
} elseif ($act == 'pm_del') {
    $pmid = intval($_GET['pmid']);
    $uid = intval($_SESSION['uid']);
    $pms = $db->getone("select * from " . table('pms') . " where pmid = '{$pmid}' AND (msgfromuid='{$uid}' OR msgtouid='{$uid}') LIMIT 1");
    if (!empty($pms)) {
        $db->query("Delete from " . table('pms') . " WHERE pmid='{$pms['pmid']}'");
    }
    $link[0]['text'] = "返回列表";
    $link[0]['href'] = "?act=pm";
    //统计消息
    $pmscount = $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$_SESSION['uid']}' OR msgtouid='{$_SESSION['uid']}') AND `new`='1' AND `replyuid`<>'{$_SESSION['uid']}'");
    setcookie('QS[pmscount]', $pmscount, $expire, $QS_cookiepath, $QS_cookiedomain);
    showmsg("操作成功！", 2, $link);
}

//会员登录日志
elseif ($act == 'login_log') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $uid = intval($_SESSION['uid']);
    $smarty->assign('total', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1'"));
    $wheresql = " WHERE log_uid='{$_SESSION['uid']}' AND log_type='1001' ";
    $perpage = 15;
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('members_log') . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $smarty->assign('loginlog', get_user_loginlog($offset, $perpage, $wheresql));
    $smarty->assign('page', $page->show(3));
    $smarty->assign('title', '会员登录日志 - 企业会员中心 - ' . $_CFG['site_name']);
    $smarty->display('member_personal/personal_user_loginlog.htm');
}

//在线课程订单
elseif ($act == 'online_course') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $uid = intval($_SESSION['uid']);
    $perpage = 15;
    $wheresql = " WHERE uid=" . $uid;
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('online_course_buy_log') . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $o_list = get_user_online_course($offset, $perpage, $wheresql);
    foreach ($o_list as $o) {
        $o['course'] = $db->getone("SELECT * FROM " . table('online_course_info') . " where id=" . $o['course_id']);
        switch ($o['state']) {
            case 1:
                $o['state_cn'] = "未支付";
                break;
            case 2:
                $o['state_cn'] = "已支付";
                break;
            case 3:
                $o['state_cn'] = "订单异常";
                break;
            default:
                $o['state_cn'] = "订单异常";
                break;
        }
        $order_list[] = $o;
    }
    $smarty->assign('order_list', $order_list);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('title', '会员在线课程订单 - 会员中心 - ' . $_CFG['site_name']);
    $smarty->display('member_personal/personal_user_online_course_list.htm');
}
//在线课程收藏
elseif ($act == 'online_course_fav') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $uid = intval($_SESSION['uid']);
    $perpage = 15;
    $wheresql = " WHERE uid=" . $uid;
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('online_course_favorite') . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $o_list = get_user_fav_online_course($offset, $perpage, $wheresql);
    foreach ($o_list as $o) {
        $o['course'] = $db->getone("SELECT * FROM " . table('online_course_info') . " where id=" . $o['course_id']);
        $fav_list[] = $o;
    }
    $smarty->assign('fav_list', $fav_list);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('title', '我收藏的在线课程 - 会员中心 - ' . $_CFG['site_name']);
    $smarty->display('member_personal/personal_user_fav_online_course_list.htm');
}
//删除在线课程收藏
elseif ($act == 'del_online_course_fav') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $id = intval($_GET['id']);
    del_user_fav_online_course($id);
} elseif ($act == 'wechat_img_save') {
    $uid = intval($_SESSION['uid']);
    $user = $db->getone("SELECT * FROM " . table('members') . " where uid=" . $uid);
    !$_FILES['wechat_img']['name'] ? exit('请上传图片！') : "";
    if ($uid < 1) {
        exit("-7");
    }
    require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
    $photo_dir = "../../data/pay_img/" . date("Y/m/d/");
    make_dir($photo_dir);
    $setsqlarr['wechat_img'] = _asUpFiles($photo_dir, "wechat_img", $_CFG['resume_photo_max'], 'gif/jpg/bmp/png', true);
    $setsqlarr['wechat_img'] = date("Y/m/d/") . $setsqlarr['wechat_img'];
    if (!empty($setsqlarr['wechat_img'])) {
        !empty($user['wechat_img']) ? unlink("../../data/pay_img/" . $user['wechat_img']) : "";
        updatetable(table("members"), $setsqlarr, " uid=" . $uid);
        exit($setsqlarr['wechat_img']);
    } else {
        exit("-8");
    }
}
unset($smarty);
?>