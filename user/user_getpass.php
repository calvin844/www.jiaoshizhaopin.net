<?php

/*
 * 74cms ��Աע��
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
$alias = "QS_login";
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
unset($dbhost, $dbuser, $dbpass, $dbname);
$smarty->cache = false;
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'enter';
$smarty->assign('header_nav', "getpass");
if ($act == 'enter') {
    $smarty->assign('title', '�һ����� - ' . $_CFG['site_name']);
    $token = substr(md5(mt_rand(100000, 999999)), 8, 16);
    $_SESSION['getpass_token'] = $token;
    $smarty->assign('step', 1);
    $smarty->assign('token', $token);
    $smarty->display('user/getpass.htm');
}
//�һ������2��
elseif ($act == 'get_pass_step2') {
    if (empty($_POST['token']) || $_POST['token'] != $_SESSION['getpass_token']) {
        $link[0]['text'] = "�����һ�����";
        $link[0]['href'] = "?act=enter";
        showmsg("�һ�����ʧ�ܣ�����������", 0, $link);
    }
    $email = $_POST['email'] ? trim($_POST['email']) : showmsg("����������");
    if (preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $email)) {
        $usinfo = get_user_inemail($email);
    } else {
        showmsg("�����ʽ");
    }
    if (empty($usinfo)) {
        showmsg("û���ҵ�����û�");
    }
    $_SESSION['getpass_email'] = $email;
    $str = $_CFG['site_name'] . "��������<br>�����ڽ����������룬��������" . $_CFG['site_domain'] . "/user/user_getpass.php?act=get_pass_step3&token=" . $_SESSION['getpass_token'];
    smtp_mail($email, $_CFG['site_name'] . "��������", $str);
    $link[0]['text'] = "������վ��ҳ";
    $link[0]['href'] = "/";
    showmsg("��ǰ����֤������ȡ�ʼ��������ʼ�˵���������롣", 0, $link);
}
// �һ����� ������
elseif ($act == 'get_pass_step3') {
    if (empty($_GET['token']) || $_GET['token'] != $_SESSION['getpass_token']) {
        $link[0]['text'] = "�����һ�����";
        $link[0]['href'] = "?act=enter";
        showmsg("�һ�����ʧ�ܣ�����������", 0, $link);
    }
    $token = substr(md5(mt_rand(100000, 999999)), 8, 16);
    $_SESSION['getpass_token'] = $token;
    $smarty->assign('token', $token);
    $smarty->assign('step', 3);
    $smarty->assign('userinfo', $userinfo);
    $smarty->assign('title', '�һ����� - ����������-' . $_CFG['site_name']);
    $smarty->display('user/getpass.htm');
} elseif ($act == "get_pass_step3_email") {
    /*
      global $QS_pwdhash;
      $uid = $_GET['uid'] ? intval($_GET['uid']) : "";
      $key = $_GET['key'] ? trim($_GET['key']) : "";
      $time = $_GET['time'] ? trim($_GET['time']) : "";
      $userinfo = get_user_inid($uid);
      if (empty($userinfo)) {
      $link[0]['text'] = "�����һ�����";
      $link[0]['href'] = "?act=enter";
      showmsg("�һ�����ʧ��,�û���Ϣ����", 0, $link);
      }
      $end_time = $time + 24 * 3600;
      if ($end_time < time()) {
      $link[0]['text'] = "�����һ�����";
      $link[0]['href'] = "?act=enter";
      showmsg("�һ�����ʧ��,���ӹ���", 0, $link);
      }
      $key_str = substr(md5($userinfo['username'] . $QS_pwdhash), 8, 16);
      if ($key_str != $key) {
      $link[0]['text'] = "�����һ�����";
      $link[0]['href'] = "?act=enter";
      showmsg("�һ�����ʧ��,key����", 0, $link);
      }
      $token = substr(md5(mt_rand(100000, 999999)), 8, 16);
      $_SESSION['getpass_token'] = $token;
      $smarty->assign('token', $token);
      $smarty->assign('userinfo', $userinfo);
      $smarty->assign('title', '�һ����� - ����������-' . $_CFG['site_name']);
      $smarty->display('user/get-pass-step3.htm');
     * 
     */
}
// ���� ����
elseif ($act == "get_pass_save") {
    global $QS_pwdhash;
    if (empty($_POST['token']) || $_POST['token'] != $_SESSION['getpass_token']) {
        $link[0]['text'] = "�����һ�����";
        $link[0]['href'] = "?act=enter";
        showmsg("�һ�����ʧ�ܣ�����������", 0, $link);
    }
    $email = $_SESSION['getpass_email'] ? trim($_SESSION['getpass_email']) : showmsg("����������");
    if (preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $email)) {
        $userinfo = get_user_inemail($email);
    }
    $password = $_POST['password'] ? trim($_POST['password']) : showmsg("���������룡", 1);
    if (empty($userinfo)) {
        $link[0]['text'] = "�����һ�����";
        $link[0]['href'] = "?act=enter";
        showmsg("����������ʧ��", 0, $link);
    }
    $password_hash = md5(md5($password) . $userinfo['pwd_hash'] . $QS_pwdhash);
    $setsqlarr['password'] = $password_hash;
    $rst = updatetable(table('members'), $setsqlarr, " uid = " . $userinfo['uid']);
    if ($rst) {
        $link[0]['text'] = "���ϵ�¼";
        $link[0]['href'] = "/user/login.php";
        showmsg("����������ɹ���", 0, $link);
    } else {
        showmsg("����������ʧ�ܣ�", 1);
    }
}
unset($smarty);
?>