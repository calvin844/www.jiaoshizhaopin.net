<?php

/*
 * 74cms ajax����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(dirname(__FILE__)) . '/include/plus.common.inc.php');
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : '';
$cur_url = !empty($_REQUEST['cur_url']) ? trim($_REQUEST['cur_url']) : '';
if ($act == 'do_login') {

    /*
      $username = isset($_POST['username']) ? trim($_POST['username']) : "";
      $password = isset($_POST['password']) ? trim($_POST['password']) : "";
      $expire = isset($_POST['expire']) ? intval($_POST['expire']) : "";
      $account_type = 1;
      if (preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $username)) {
      $account_type = 2;
      } elseif (preg_match("/^(1)\d{10}$/", $username)) {
      $account_type = 3;
      }
      $url = isset($_POST['url']) ? $_POST['url'] : "";
      if (strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
      $username = utf8_to_gbk($username);
      $password = utf8_to_gbk($password);
      }
      $captcha = get_cache('captcha');
      if ($captcha['verify_userlogin'] == "1") {
      $postcaptcha = $_POST['postcaptcha'];
      if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
      $postcaptcha = utf8_to_gbk($postcaptcha);
      }
      if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
      unset($_SESSION['imageCaptcha_content']);
      exit("errcaptcha");
      }
      }
      require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
      if ($username && $password) {
      $login = user_login($username, $password, $account_type, true, $expire);
      $url = $url ? $url : $login['qs_login'];
      if ($login['qs_login']) {
      exit($login['uc_login'] . "<script language=\"javascript\" type=\"text/javascript\">window.location.href=\"" . $url . "\";</script>");
      } else {
      exit("err");
      }
      }
      exit("err");
      } elseif ($act == 'check_mobile') {
      $mobile = intval($_GET['phone']);
      $mobile_user = preg_match("/^(1)\d{10}$/", $mobile) ? get_user_inmobile($mobile) : exit("0-�ֻ��Ÿ�ʽ����");
      !empty($mobile_user) ? exit("1-���ֻ����ѱ�ע��") : "";
      if ($mobile > 0) {
      $result = $mem->get($mobile);
      if (!$result) {
      $result = rand(1000, 9999);
      $mem->set($mobile, $result, 60);
      }
      $text = iconv("GB2312", "UTF-8//IGNORE", "����ʦ����������֤����" . $result);
      //$text = "����ʦ����������֤����" . $result;
      $ch = curl_init();
      $apikey = "732be982abc9313b64561224601f70a3";
      $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
      curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
      $json_data = curl_exec($ch);
      }
      exit("2-OK");
      } elseif ($act == 'do_reg') {
      $captcha = get_cache('captcha');
      if ($captcha['verify_userreg'] == "1") {
      $postcaptcha = $_POST['postcaptcha'];
      if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
      $postcaptcha = utf8_to_gbk($postcaptcha);
      }
      if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
      exit("err");
      }
      }
      require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
      $username = isset($_POST['username']) ? trim($_POST['username']) : exit("err");
      $password = isset($_POST['password']) ? trim($_POST['password']) : exit("err");
      $member_type = isset($_POST['member_type']) ? intval($_POST['member_type']) : exit("err");
      $email = isset($_POST['email']) ? trim($_POST['email']) : exit("err");
      if (strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
      $username = utf8_to_gbk($username);
      $password = utf8_to_gbk($password);
      }
      if (defined('UC_API')) {
      include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
      if (uc_user_checkname($username) < 0) {
      exit("err");
      }
      if (uc_user_checkemail($email) < 0) {
      exit("err");
      }
      }
      $register = user_register($username, $password, $member_type, $email);
      if ($register > 0) {
      $login_js = user_login($username, $password);
      $mailconfig = get_cache('mailconfig');
      if ($mailconfig['set_reg'] == "1") {
      dfopen($_CFG['website_dir'] . "plus/asyn_mail.php?uid=" . $_SESSION['uid'] . "&key=" . asyn_userkey($_SESSION['uid']) . "&sendemail=" . $email . "&sendusername=" . $username . "&sendpassword=" . $password . "&act=reg");
      }
      $ucjs = $login_js['uc_login'];
      $qsurl = $login_js['qs_login'];
      $qsjs = "<script language=\"javascript\" type=\"text/javascript\">window.location.href=\"" . $qsurl . "\";</script>";
      if ($ucjs || $qsurl) {
      exit($ucjs . $qsjs);
      } else {
      exit("err");
      }
      } else {
      exit("err");
      }
      } elseif ($act == 'check_usname') {
      require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
      $usname = trim($_POST['usname']);
      if (strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
      $usname = utf8_to_gbk($usname);
      }
      $user = get_user_inusername($usname);
      if (defined('UC_API')) {
      include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
      if (uc_user_checkname($usname) === 1 && empty($user)) {
      exit("true");
      } else {
      exit("false");
      }
      }
      empty($user) ? exit("true") : exit("false");
      } elseif ($act == 'check_email') {
      require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
      $email = trim($_POST['email']);
      if (strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
      $email = utf8_to_gbk($email);
      }
      $user = get_user_inemail($email);
      if (defined('UC_API')) {
      include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
      if (uc_user_checkemail($email) === 1 && empty($user)) {
      exit("true");
      } else {
      exit("false");
      }
      }
      empty($user) ? exit("true") : exit("false");

     */
} elseif ($act == "top_loginform") {
    $url_arr = explode("/", $cur_url);
    if (!empty($url_arr[3])) {
        $url_str = $url_arr[3];
    } else {
        $url_str = "index.php";
    }
    $contents = '<div class="main_box">';
    $contents.='<a title="�й���ʦ��Ƹ��" class="logo" href="/index.php"></a>';
    $contents.='<ul class="nav">';
    $contents.='<li class="' . ($url_str == 'index.php' ? 'cur' : '') . '">';
    $contents.='<div>';
    $contents.='<a title="��ҳ" href="' . $_CFG['main_domain'] . '">��ҳ</a>';
    $contents.='</div>';
    $contents.='</li>';
    $contents.='<li class="' . ($url_str == 'company' ? 'cur' : '') . '">';
    $contents.='<div>';
    $contents.='<a title="ѧУ" href="/company">ѧУ</a>';
    $contents.='</div>';
    $contents.='</li>';
    $contents.='<li class="' . ($url_str == 'TeachingJobs' ? 'cur' : '') . '">';
    $contents.='<div>';
    $contents.='<a title="ְλ" href="/jobs/jobs-list.php?listpage=jobs_list">ְλ</a>';
    $contents.='</div>';
    $contents.='</li>';
    $contents.='<li class="' . ($url_str == 'zhaokao' ? 'cur' : '') . '">';
    $contents.='<div>';
    $contents.='<a title="�п�" href="/zhaokao">�п�</a>';
    $contents.='</div>';
    $contents.='</li>';
    /**
    $contents.='<li>';
    $contents.='<div>';
    $contents.='<a target="_blank" title="��ʦ�ʸ�֤����" href="http://tiku.jiaoshizhaopin.net">����</a>';
    $contents.='</div>';
    $contents.='</li>';
    $contents.='{#$user_center#}';
     * 
     */
    $contents.='</ul>';
    $contents.='<div class="login_box">';
    $contents.='{#$login_box#}';
    $contents.='</div>';
    $contents.='</div>';
    if ($_COOKIE['QS']['username'] && $_COOKIE['QS']['password']) {
        if (strlen($_COOKIE['QS']['username']) > 6) {
            $top_username = substr($_COOKIE['QS']['username'], 0, 6) . "...";
        } else {
            $top_username = $_COOKIE['QS']['username'];
        }
        $login_box = '<i></i><a title="{#$username#}" href="{#$user_url#}">' . $top_username . '</a>{#$pmscount_a#}';
        $login_box.='<ul>';
        $login_box.='<li> <a title="��Ա����" href="{#$user_url#}">��Ա����</a></li>';
        $login_box.='<li> <a title="�˳�" href="{#$logout_url#}">�˳�</a></li>';
        $login_box.='</ul>';
    } elseif ($_SESSION['activate_username'] && defined('UC_API')) {
        $login_box = ' <i></i><span>�����ʺ� {#$activate_username#} �輤���ſ���ʹ�ã�</span> <a href="{#$activate_url#}" style="color:#339900">��������</a>';

        $user_center = '<li>';
        $user_center.='<div>';
        $user_center.='<a title="�ҵļ���" href="/user/login.php">�ҵļ���</a>';
        $user_center.='</div>';
        $user_center.='</li>';
        $user_center.='<li>';
        $user_center.='<div>';
        $user_center.='<a title="����ְλ" href="/user/login.php">����ְλ</a>';
        $user_center.='</div>';
        $user_center.='</li>';
    } else {
//$contents='��ӭ����{#$site_name#}��&nbsp;&nbsp;&nbsp;&nbsp;<a href="{#$login_url#}" style="color:#0066cc" >[��¼]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{#$reg_url#}" style="color:#0066cc">[���ע��]</a>';
        $login_box = '<i></i><a class="login" title="��¼" href="{#$login_url#}">��¼</a>';
        $login_box.='<a title="ע��" href="{#$reg_url#}">ע��</a>';

        $user_center = '<li>';
        $user_center.='<div>';
        $user_center.='<a title="�ҵļ���" href="/user/login.php">�ҵļ���</a>';
        $user_center.='</div>';
        $user_center.='</li>';
        $user_center.='<li>';
        $user_center.='<div>';
        $user_center.='<a title="����ְλ" href="/user/login.php">����ְλ</a>';
        $user_center.='</div>';
        $user_center.='</li>';
    }
    $contents = str_replace('{#$login_box#}', $login_box, $contents);
    $contents = str_replace('{#$activate_username#}', $_SESSION['activate_username'], $contents);
    $contents = str_replace('{#$site_name#}', $_CFG['site_name'], $contents);
    $contents = str_replace('{#$username#}', $_COOKIE['QS']['username'], $contents);
    $contents = str_replace('{#$pmscount#}', $_COOKIE['QS']['pmscount'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    if ($_COOKIE['QS']['utype'] == '1') {
        $user_url = $_CFG['main_domain'] . "user/company/company_index.php";
        $user_center = '<li class="' . ($url_str == 'user' && $url_arr[4] == 'company' ? 'cur' : '') . '">';
        $user_center.='<div>';
        $user_center.='<a title="��ҵ��Ա����" href="' . $user_url . '">��ҵ��Ա����</a>';
        $user_center.='</div>';
        $user_center.='</li>';
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/company/company_user.php?act=pm&new=1" title="����Ϣ">(' . $_COOKIE['QS']['pmscount'] . ')</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '2') {
        $user_url = $_CFG['main_domain'] . "user/personal/personal_index.php";
        $user_center = '<li class="' . ($url_str == 'user' && $url_arr[4] == 'personal' ? 'cur' : '') . '">';
        $user_center.='<div>';
        $user_center.='<a title="��������" href="' . $user_url . '">��������</a>';
        $user_center.='</div>';
        $user_center.='</li>';
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/personal/personal_user.php?act=pm&new=1" title="����Ϣ">(' . $_COOKIE['QS']['pmscount'] . ')</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '4') {
        $user_url = $_CFG['main_domain'] . "user/train/train_index.php";
        $user_center = '<li class="' . ($url_str == 'user' && $url_arr[4] == 'train' ? 'cur' : '') . '">';
        $user_center.='<div>';
        $user_center.='<a title="��������" href="' . $user_url . '">��������</a>';
        $user_center.='</div>';
        $user_center.='</li>';
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/train/train_user.php?act=pm&new=1" title="����Ϣ">(' . $_COOKIE['QS']['pmscount'] . ')</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '3') {
        $user_url = $_CFG['main_domain'] . "user/hunter/hunter_index.php";
        $user_center = '<li class="' . ($url_str == 'user' && $url_arr[4] == 'hunter' ? 'cur' : '') . '">';
        $user_center.='<div>';
        $user_center.='<a title="��������" href="' . $user_url . '">��������</a>';
        $user_center.='</div>';
        $user_center.='</li>';
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/hunter/hunter_user.php?act=pm&new=1" title="����Ϣ">(' . $_COOKIE['QS']['pmscount'] . ')</a>';
        }
    }
    $contents = str_replace('{#$user_center#}', $user_center, $contents);
    $contents = str_replace('{#$pmscount_a#}', $pmscount_a, $contents);
    $contents = str_replace('{#$user_url#}', $user_url, $contents);
    $contents = str_replace('{#$login_url#}', url_rewrite('QS_login', NULL, false), $contents);
    $contents = str_replace('{#$logout_url#}', url_rewrite('QS_login', NULL, false) . "?act=logout", $contents);
    $contents = str_replace('{#$reg_url#}', $_CFG['main_domain'] . "user/user_reg.php", $contents);
    $contents = str_replace('{#$activate_url#}', $_CFG['main_domain'] . "user/user_reg.php?act=activate", $contents);
    exit($contents);
} elseif ($act == "loginform") {
    $contents = '';
    if ($_COOKIE['QS']['username'] && $_COOKIE['QS']['password']) {
        $tpl = '../templates/' . $_CFG['template_dir'] . "plus/login_success.htm";
    } elseif ($_SESSION['activate_username'] && defined('UC_API')) {
        $tpl = '../templates/' . $_CFG['template_dir'] . "plus/login_activate.htm";
    } else {
        $tpl = '../templates/' . $_CFG['template_dir'] . "plus/login_form.htm";
    }
    $contents = file_get_contents($tpl);
    $contents = str_replace('{#$activate_username#}', $_SESSION['activate_username'], $contents);
    $contents = str_replace('{#$site_name#}', $_CFG['site_name'], $contents);
    $contents = str_replace('{#$username#}', $_COOKIE['QS']['username'], $contents);
    $contents = str_replace('{#$pmscount#}', $_COOKIE['QS']['pmscount'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    $contents = str_replace('{#$website_dir#}', $_CFG['website_dir'], $contents);
    if ($_COOKIE['QS']['utype'] == '1') {
        $user_url = $_CFG['main_domain'] . "user/company/company_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/company/company_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '2') {
        $user_url = $_CFG['main_domain'] . "user/personal/personal_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/personal/personal_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '4') {
        $user_url = $_CFG['main_domain'] . "user/train/train_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/train/train_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    if ($_COOKIE['QS']['utype'] == '3') {
        $user_url = $_CFG['main_domain'] . "user/hunter/hunter_index.php";
        if ($_COOKIE['QS']['pmscount'] > 0) {
            $pmscount_a = '<a href="' . $_CFG['main_domain'] . 'user/hunter/hunter_user.php?act=pm&new=1" style="padding:1px 4px; background-color:#FF6600; color:#FFFFFF;text-decoration:none" title="����Ϣ">��Ϣ ' . $_COOKIE['QS']['pmscount'] . '</a>';
        }
    }
    $contents = str_replace('{#$pmscount_a#}', $pmscount_a, $contents);
    $contents = str_replace('{#$user_url#}', $user_url, $contents);
    $contents = str_replace('{#$login_url#}', url_rewrite('QS_login', NULL, false), $contents);
    $contents = str_replace('{#$logout_url#}', url_rewrite('QS_login', NULL, false) . "?act=logout", $contents);
    $contents = str_replace('{#$reg_url#}', $_CFG['main_domain'] . "user/user_reg.php", $contents);
    $contents = str_replace('{#$activate_url#}', $_CFG['main_domain'] . "user/user_reg.php?act=activate", $contents);
    exit($contents);
}
?>