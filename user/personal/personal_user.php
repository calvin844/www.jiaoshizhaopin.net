<?php

/*
 * 74cms ���˻�Ա����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/personal_common.php');
$smarty->assign('leftmenu', "user");
if ($act == 'binding') {
    $smarty->assign('user', $user);
    $smarty->assign('title', '�˺Ű� - ��Ա���� - ' . $_CFG['site_name']);
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
    $smarty->assign('title', '�������� - ��Ա���� - ' . $_CFG['site_name']);
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
        showmsg('΢�źź�΢�Ÿ��˶�ά��������дһ�', 1);
    }
    if (!empty($user)) {
        $wheresql = " uid='" . $uid . "'";
        write_memberslog($uid, 2, 1005, $uid, "�޸��˸�������");
        $up_flag = updatetable(table('members'), $setsqlarr, $wheresql);
    } else {
        write_memberslog($uid, 2, 1005, $uid, "�޸��˸�������");
        $up_flag = !inserttable(table('members'), $setsqlarr);
    }
    if ($up_flag) {
        showmsg("�ύ�ɹ���", 2);
    } else {
        showmsg("�ύʧ�ܣ�", 2);
    }
} elseif ($act == 'userprofile_save') {
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    if ($user["email_audit"] == 0) {
        $setsqlarr['email'] = trim($_POST['email']) ? trim($_POST['email']) : showmsg('����д���䣡', 1);
        $email_verifycode = trim($_POST['email_verifycode']);
        if ($email_verifycode) {
            if (empty($_SESSION['email_rand']) || $email_verifycode <> $_SESSION['email_rand']) {
                showmsg("������֤�����", 1);
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
        $setsqlarr['phone'] = trim($_POST['mobile']) ? trim($_POST['mobile']) : showmsg('����д�ֻ��ţ�', 1);
        $mobile_verifycode = trim($_POST['mobile_verifycode']);
        if ($mobile_verifycode) {
            if (empty($_SESSION['mobile_rand']) || $mobile_verifycode <> $_SESSION['mobile_rand']) {
                showmsg("�ֻ���֤�����", 1);
            } else {
                $verifysqlarr['mobile'] = $setsqlarr['phone'];
                $verifysqlarr['mobile_audit'] = 1;
                updatetable(table('members'), $verifysqlarr, " uid='{$setsqlarr['uid']}'");
                unset($verifysqlarr);
            }
        }
        unset($_SESSION['verify_mobile'], $_SESSION['mobile_rand']);
    }


    $setsqlarr['realname'] = trim($_POST['realname']) ? trim($_POST['realname']) : showmsg('����д��ʵ������', 1);
    $setsqlarr['sex'] = intval($_POST['sex']) ? intval($_POST['sex']) : showmsg('��ѡ���Ա�', 1);
    $setsqlarr['sex_cn'] = trim($_POST['sex_cn']);
    $setsqlarr['birthday'] = intval($_POST['birthday']) ? intval($_POST['birthday']) : showmsg('��ѡ��������', 1);
    $setsqlarr['residence'] = trim($_POST['residence']) ? trim($_POST['residence']) : showmsg('��ѡ���־�ס�أ�', 1);
    $setsqlarr['residence_cn'] = trim($_POST['residence_cn']);
    $setsqlarr['education'] = intval($_POST['education']) ? intval($_POST['education']) : showmsg('��ѡ��ѧ��', 1);
    $setsqlarr['education_cn'] = trim($_POST['education_cn']);
    $setsqlarr['experience'] = intval($_POST['experience']) ? intval($_POST['experience']) : showmsg('��ѡ��������', 1);
    $setsqlarr['experience_cn'] = trim($_POST['experience_cn']);
    $setsqlarr['height'] = intval($_POST['height']);
    $setsqlarr['householdaddress'] = trim($_POST['householdaddress']);
    $setsqlarr['householdaddress_cn'] = trim($_POST['householdaddress_cn']);
    $setsqlarr['marriage'] = intval($_POST['marriage']);
    $setsqlarr['marriage_cn'] = trim($_POST['marriage_cn']);
    if (get_userprofile($_SESSION['uid'])) {
        $wheresql = " uid='" . intval($_SESSION['uid']) . "'";
        write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "�޸��˸�������");
        !updatetable(table('members_info'), $setsqlarr, $wheresql) ? showmsg("�޸�ʧ�ܣ�", 0) : showmsg("�޸ĳɹ���", 2);
    } else {
        $setsqlarr['uid'] = intval($_SESSION['uid']);
        write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "�޸��˸�������");
        !inserttable(table('members_info'), $setsqlarr) ? showmsg("�޸�ʧ�ܣ�", 0) : showmsg("�޸ĳɹ���", 2);
    }
}
//ͷ��
elseif ($act == 'avatars') {
    $uid = intval($_SESSION['uid']);
    $smarty->assign('total', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1'"));
    $smarty->assign('title', '����ͷ�� - ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('user', $user);
    $smarty->assign('rand', rand(1, 100));
    $smarty->display('member_personal/personal_avatars.htm');
} elseif ($act == 'avatars_ready') {
    require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
    !$_FILES['avatars']['name'] ? showmsg('���ϴ�ͼƬ��', 1) : "";
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
        write_memberslog($_SESSION['uid'], 2, 1006, $_SESSION['username'], "�޸��˸���ͷ��");
        updatetable(table('members'), $setsqlarr, $wheresql) ? exit($setsqlarr['avatars']) : showmsg('����ʧ�ܣ�', 1);
    } else {
        showmsg('����ʧ�ܣ�', 1);
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
        write_memberslog($_SESSION['uid'], 2, 1006, $_SESSION['username'], "�޸��˸���ͷ��");
        showmsg('����ɹ���', 2);
    } else {
        showmsg('���ϴ�ͼƬ��', 1);
    }
}
//�޸�����
elseif ($act == 'password_edit') {
    $uid = intval($_SESSION['uid']);
    $smarty->assign('total', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1'"));
    $smarty->assign('title', '�޸����� - ���˻�Ա���� - ' . $_CFG['site_name']);
    $smarty->display('member_personal/personal_password.htm');
}
//�����޸�����
elseif ($act == 'save_password') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $arr['username'] = $_SESSION['username'];
    $arr['oldpassword'] = trim($_POST['oldpassword']) ? trim($_POST['oldpassword']) : showmsg('����������룡', 1);
    $arr['password'] = trim($_POST['password']) ? trim($_POST['password']) : showmsg('�����������룡', 1);
    if ($arr['password'] != trim($_POST['password1']))
        showmsg('�����������벻��ͬ�����������룡', 1);
    $info = edit_password($arr);
    if ($info == -1)
        showmsg('����������������������룡', 1);
    if ($info == $_SESSION['username']) {
        //�����ʼ�
        $mailconfig = get_cache('mailconfig');
        if ($mailconfig['set_editpwd'] == "1" && $user['email_audit'] == "1") {
            dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid=" . $_SESSION['uid'] . "&key=" . asyn_userkey($_SESSION['uid']) . "&act=set_editpwd&newpassword=" . $arr['password']);
        }
        //�ʼ��������
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
        write_memberslog($_SESSION['uid'], 2, 1004, $_SESSION['username'], "�޸�����");
        showmsg('�����޸ĳɹ���', 2);
    }
} elseif ($act == 'authenticate') {
    $uid = intval($_SESSION['uid']);
    $smarty->assign('total', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1'"));
    $smarty->assign('user', $user);
    $smarty->assign('re_audit', $_GET['re_audit']);
    $smarty->assign('title', '��֤���� - ���˻�Ա���� - ' . $_CFG['site_name']);
    $_SESSION['send_key'] = mt_rand(100000, 999999);
    $smarty->assign('send_key', $_SESSION['send_key']);
    $smarty->display('member_personal/personal_authenticate.htm');
} elseif ($act == 'feedback') {
    $smarty->assign('title', '�û����� - ���˻�Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('feedback', get_feedback($_SESSION['uid']));
    $smarty->display('member_personal/personal_feedback.htm');
}
//�����û�����
elseif ($act == 'feedback_save') {
    $get_feedback = get_feedback($_SESSION['uid']);
    if (count($get_feedback) >= 5) {
        showmsg('������Ϣ���ܳ���5����', 1);
        exit();
    }
    $setsqlarr['infotype'] = intval($_POST['infotype']);
    $setsqlarr['feedback'] = trim($_POST['feedback']) ? trim($_POST['feedback']) : showmsg('����д���ݣ�', 1);
    $setsqlarr['uid'] = $_SESSION['uid'];
    $setsqlarr['usertype'] = $_SESSION['utype'];
    $setsqlarr['username'] = $_SESSION['username'];
    $setsqlarr['addtime'] = $timestamp;
    write_memberslog($_SESSION['uid'], 2, 7001, $_SESSION['username'], "��ӷ�����Ϣ");
    !inserttable(table('feedback'), $setsqlarr) ? showmsg("���ʧ�ܣ�", 0) : showmsg("��ӳɹ�����ȴ�����Ա�ظ���", 2);
}
//ɾ���û�����
elseif ($act == 'del_feedback') {
    $id = intval($_GET['id']);
    del_feedback($id, $_SESSION['uid']) ? showmsg('ɾ���ɹ���', 2) : showmsg('ɾ��ʧ�ܣ�', 1);
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
    $smarty->assign('title', '����Ϣ - ��Ա���� - ' . $_CFG['site_name']);
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
    $link[0]['text'] = "�����б�";
    $link[0]['href'] = "?act=pm";
    //ͳ����Ϣ
    $pmscount = $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$_SESSION['uid']}' OR msgtouid='{$_SESSION['uid']}') AND `new`='1' AND `replyuid`<>'{$_SESSION['uid']}'");
    setcookie('QS[pmscount]', $pmscount, $expire, $QS_cookiepath, $QS_cookiedomain);
    showmsg("�����ɹ���", 2, $link);
}

//��Ա��¼��־
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
    $smarty->assign('title', '��Ա��¼��־ - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->display('member_personal/personal_user_loginlog.htm');
}

//���߿γ̶���
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
                $o['state_cn'] = "δ֧��";
                break;
            case 2:
                $o['state_cn'] = "��֧��";
                break;
            case 3:
                $o['state_cn'] = "�����쳣";
                break;
            default:
                $o['state_cn'] = "�����쳣";
                break;
        }
        $order_list[] = $o;
    }
    $smarty->assign('order_list', $order_list);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('title', '��Ա���߿γ̶��� - ��Ա���� - ' . $_CFG['site_name']);
    $smarty->display('member_personal/personal_user_online_course_list.htm');
}
//���߿γ��ղ�
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
    $smarty->assign('title', '���ղص����߿γ� - ��Ա���� - ' . $_CFG['site_name']);
    $smarty->display('member_personal/personal_user_fav_online_course_list.htm');
}
//ɾ�����߿γ��ղ�
elseif ($act == 'del_online_course_fav') {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $id = intval($_GET['id']);
    del_user_fav_online_course($id);
} elseif ($act == 'wechat_img_save') {
    $uid = intval($_SESSION['uid']);
    $user = $db->getone("SELECT * FROM " . table('members') . " where uid=" . $uid);
    !$_FILES['wechat_img']['name'] ? exit('���ϴ�ͼƬ��') : "";
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