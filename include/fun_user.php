<?php

/*
 * 74cms ��Ա���ĺ���
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
if (!defined('IN_QISHI')) {
    die('Access Denied!');
}

//ע���Ա
function user_register($username, $password, $member_type = 0, $email, $mobile = 0, $uc_reg = true) {
    global $db, $timestamp, $_CFG, $online_ip, $QS_pwdhash;
    $member_type = intval($member_type);
    $ck_username = get_user_inusername($username);
    $ck_email = get_user_inemail($email);
    $ck_mobile = get_user_inmobile($mobile);
    if ($member_type == 0) {
        return -1;
    } elseif (!empty($ck_username)) {
        return -2;
    } elseif (!empty($ck_email)) {
        return -3;
    } elseif (!empty($ck_mobile)) {
        return -4;
    }
    $pwd_hash = randstr();
    $password_hash = md5(md5($password) . $pwd_hash . $QS_pwdhash);
    $setsqlarr['username'] = $username;
    $setsqlarr['password'] = $password_hash;
    $setsqlarr['pwd_hash'] = $pwd_hash;
    $setsqlarr['email'] = $email;
    $setsqlarr['mobile'] = $mobile;
    $setsqlarr['mobile_audit'] = 1;
    $setsqlarr['utype'] = intval($member_type);
    $setsqlarr['reg_time'] = $timestamp;
    $setsqlarr['reg_ip'] = $online_ip;
    $insert_id = inserttable(table('members'), $setsqlarr, true);
    if ($member_type == "2") {
        $sql = "select * from " . table('jiaoshi_statistics_all') . " where name = 'reg_user' LIMIT 1";
        $reg_user = $db->getone($sql);
        $setsqlarr1['total_count'] = intval($reg_user['total_count']) + 1;
        $setsqlarr1['new_count'] = intval($reg_user['new_count']) + 1;
        $wheresql1 = " name='reg_user' ";
        updatetable(table('jiaoshi_statistics_all'), $setsqlarr1, $wheresql1);

        $date = date('Y-m-d', time());
        $sql = "select * from " . table('jiaoshi_statistics_day') . " where name = 'reg_user' and date = '" . $date . "' LIMIT 1";
        $reg_user = $db->getone($sql);
        if (!empty($reg_user)) {
            $setsqlarr2['web_count'] = intval($reg_user['web_count']) + 1;
            $wheresql2 = " name='reg_user' and date='" . $date . "' ";
            updatetable(table('jiaoshi_statistics_day'), $setsqlarr2, $wheresql2);
        } else {
            $setsqlarr2['web_count'] = 1;
            $setsqlarr2['name'] = 'reg_user';
            $setsqlarr2['date'] = $date;
            $setsqlarr2['count'] = 0;
            inserttable(table('jiaoshi_statistics_day'), $setsqlarr2);
        }
    } else if ($member_type == "1") {
        $sql = "select * from " . table('jiaoshi_statistics_all') . " where name = 'school' LIMIT 1";
        $school = $db->getone($sql);
        $setsqlarr1['total_count'] = intval($school['total_count']) + 1;
        $setsqlarr1['new_count'] = intval($school['new_count']) + 1;
        $wheresql1 = " name='school' ";
        updatetable(table('jiaoshi_statistics_all'), $setsqlarr1, $wheresql1);

        $date = date('Y-m-d', time());
        $sql = "select * from " . table('jiaoshi_statistics_day') . " where name = 'school' and date = '" . $date . "' LIMIT 1";
        $school = $db->getone($sql);
        if (!empty($school)) {
            $setsqlarr2['web_count'] = intval($school['web_count']) + 1;
            $wheresql2 = " name='school' and date='" . $date . "' ";
            updatetable(table('jiaoshi_statistics_day'), $setsqlarr2, $wheresql2);
        } else {
            $setsqlarr2['web_count'] = 1;
            $setsqlarr2['name'] = 'school';
            $setsqlarr2['date'] = $date;
            $setsqlarr2['count'] = 0;
            inserttable(table('jiaoshi_statistics_day'), $setsqlarr2);
        }
    }

    if ($member_type == "1") {
        if (!$db->query("INSERT INTO " . table('members_points') . " (uid) VALUES ('{$insert_id}')"))
            return false;
        if (!$db->query("INSERT INTO " . table('members_setmeal') . " (uid) VALUES ('{$insert_id}')"))
            return false;

        $points = get_cache('points_rule');
        include_once(QISHI_ROOT_PATH . 'include/fun_company.php');
        set_consultant($insert_id);
        if ($points['reg_points']['value'] > 0) {
            report_deal($insert_id, $points['reg_points']['type'], $points['reg_points']['value']);
            $operator = $points['reg_points']['type'] == "1" ? "+" : "-";
            write_memberslog($insert_id, 1, 9001, $username, "��ע���Ա,({$operator}{$points['reg_points']['value']}),(ʣ��:{$points['reg_points']['value']})", 1, 1010, "ע���Աϵͳ�Զ����ͻ���", "{$operator}{$points['reg_points']['value']}", "{$points['reg_points']['value']}");
            //���ֱ����¼
            write_setmeallog($insert_id, $username, "ע���Աϵͳ�Զ����ͣ�({$operator}{$points['reg_points']['value']}),(ʣ��:{$points['reg_points']['value']})", 1, '0.00', '1', 1, 1);
        }
        if ($_CFG['reg_service'] > 0) {
            set_members_setmeal($insert_id, $_CFG['reg_service']);
            $setmeal = get_setmeal_one($_CFG['reg_service']);
            write_memberslog($insert_id, 1, 9002, $username, "ע���Աϵͳ�Զ����ͣ�{$setmeal['setmeal_name']}", 2, 1011, "��ͨ����(ϵͳ����)", "-", "-");
            //�ײͱ����¼
            write_setmeallog($insert_id, $username, "ע���Աϵͳ�Զ����ͣ�{$setmeal['setmeal_name']}", 1, '0.00', '1', 2, 1);
        }
    } elseif ($member_type == '4') {
        if (!$db->query("INSERT INTO " . table('members_points') . " (uid) VALUES ('{$insert_id}')"))
            return false;
        if (!$db->query("INSERT INTO " . table('members_train_setmeal') . " (uid) VALUES ('{$insert_id}')"))
            return false;
        $points = get_cache('points_rule');
        if ($points['trainreg_points']['value'] > 0) {
            include_once(QISHI_ROOT_PATH . 'include/fun_train.php');
            report_deal($insert_id, $points['trainreg_points']['type'], $points['trainreg_points']['value']);
            $operator = $points['trainreg_points']['type'] == "1" ? "+" : "-";
            write_memberslog($insert_id, 4, 9101, $username, "��ע���Ա,({$operator}{$points['trainreg_points']['value']}),(ʣ��:{$points['trainreg_points']['value']})");
            write_setmeallog($insert_id, $username, "ע���Աϵͳ�Զ����ͣ�({$operator}{$points['trainreg_points']['value']}),(ʣ��:{$points['trainreg_points']['value']})", 1, '0.00', '1', 1, 4);
        }
        if ($_CFG['train_reg_service'] > 0) {
            include_once(QISHI_ROOT_PATH . 'include/fun_train.php');
            set_members_setmeal($insert_id, $_CFG['train_reg_service']);
            $setmeal = get_setmeal_one($_CFG['train_reg_service']);
            write_memberslog($insert_id, 4, 9102, $username, "ע���Աϵͳ�Զ����ͣ�{$setmeal['setmeal_name']}");
            write_setmeallog($insert_id, $username, "ע���Աϵͳ�Զ����ͣ�{$setmeal['setmeal_name']}", 1, '0.00', '1', 2, 4);
        }
    } elseif ($member_type == '3') {
        if (!$db->query("INSERT INTO " . table('members_points') . " (uid) VALUES ('{$insert_id}')"))
            return false;
        if (!$db->query("INSERT INTO " . table('members_hunter_setmeal') . " (uid) VALUES ('{$insert_id}')"))
            return false;
        $points = get_cache('points_rule');
        if ($points['hunterreg_points']['value'] > 0) {
            include_once(QISHI_ROOT_PATH . 'include/fun_hunter.php');
            report_deal($insert_id, $points['hunterreg_points']['type'], $points['hunterreg_points']['value']);
            $operator = $points['hunterreg_points']['type'] == "1" ? "+" : "-";
            write_memberslog($insert_id, 3, 9201, $username, "��ע���Ա,({$operator}{$points['hunterreg_points']['value']}),(ʣ��:{$points['hunterreg_points']['value']})");
            write_setmeallog($insert_id, $username, "ע���Աϵͳ�Զ����ͣ�({$operator}{$points['hunterreg_points']['value']}),(ʣ��:{$points['hunterreg_points']['value']})", 1, '0.00', '1', 1, 3);
        }
        if ($_CFG['hunter_reg_service'] > 0) {
            include_once(QISHI_ROOT_PATH . 'include/fun_hunter.php');
            set_members_setmeal($insert_id, $_CFG['hunter_reg_service']);
            $setmeal = get_setmeal_one($_CFG['hunter_reg_service']);
            write_memberslog($insert_id, 3, 9202, $username, "ע���Աϵͳ�Զ����ͣ�{$setmeal['setmeal_name']}");
            write_setmeallog($insert_id, $username, "ע���Աϵͳ�Զ����ͣ�{$setmeal['setmeal_name']}", 1, '0.00', '1', 2, 3);
        }
    }
    if (defined('UC_API') && $uc_reg) {
        include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
        $uc_reg_uid = uc_user_register($username, $password, $email);
    }
    write_memberslog($insert_id, $member_type, 1000, $username, "ע���Ϊ��Ա");
    return $insert_id;
}

//��Ա��¼
function user_login($account, $password, $type = "", $account_type = 1, $uc_login = true, $expire = NULL) {
    global $timestamp, $online_ip, $QS_pwdhash;
    $usinfo = $login = array();
    $success = false;
    if ($account_type == "1") {
        $usinfo = get_user_inusername($account);
    } elseif ($account_type == "2") {
        $usinfo = get_user_inemail($account);
    } elseif ($account_type == "3") {
        $usinfo = get_user_inmobile($account);
    }
    if (!empty($usinfo) && empty($usinfo['freeze'])) {
        //��ȡ�û�hash
        $pwd_hash = $usinfo['pwd_hash'];
        //��ȡ�û���
        $usname = $usinfo['username'];
        //����32λ������Ϣ��$password�û�����������ַ���
        $pwd = md5(md5($password) . $pwd_hash . $QS_pwdhash);
        //����16λ������Ϣ
        $pwd_old = substr(md5($password), 8, 16);
        //�ж��������м�¼���Ƿ���16λ����
        if ($usinfo['password'] == $pwd_old) {
            //�����16λ�Ĳ�������������ȷ�����û�����������Ϊ32λ���룬���������ݿ�
            $pwd = update_password(array('username' => $usinfo['username'], 'password' => $password, 'pwd_hash' => $pwd_hash));
            $usinfo['password'] = $pwd;
        }
        if (!empty($_SESSION['openid']) || !empty($_SESSION['sina_access_token'])) {
            if (!empty($type)) {
                switch ($type) {
                    case "wx":
                        update_user_wxopenid($_SESSION['openid'], $usinfo['uid']);
                        break;
                    case "qq":
                        update_user_qqopenid($_SESSION['openid'], $usinfo['uid']);
                        break;
                    case "weibo":
                        update_user_weiboopenid($_SESSION['sina_access_token'], $usinfo['uid']);
                        break;
                    default:
                        break;
                }
            }
        }
        //var_dump($pwd);
        //var_dump($usinfo['password']);exit;
        //$pwd_hash=$usinfo['pwd_hash'];
        //$usname=addslashes($usinfo['username']);
        //$pwd=md5(md5($password).$pwd_hash.$QS_pwdhash);
        $login['utype'] = $usinfo['utype'];
        if ($usinfo['password'] == $pwd) {
            update_user_info($usinfo['uid'], true, true, $expire);
            $login['qs_login'] = get_member_url($usinfo['utype']);
            $login['freeze'] = trim($usinfo['freeze']);
            $success = true;
            write_memberslog($usinfo['uid'], $usinfo['utype'], 1001, $usname, "�ɹ���¼");
        } else {
            $usinfo = '';
            $success = false;
        }
    } elseif (!empty($usinfo) && !empty($usinfo['freeze'])) {
        $login['freeze'] = trim($usinfo['freeze']);
    }
    if (defined('UC_API') && $uc_login) {
        include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
        $account = $usinfo['username'] ? $usinfo['username'] : $account;
        list($uc_uid, $uc_username, $uc_password, $uc_email) = uc_user_login($account, $password);
        if ($uc_uid > 0) {
            $login['uc_login'] = uc_user_synlogin($uc_uid);
            if ($success == false) {//UC�ɹ�74ʧ�ܾ�ע�ᣬע���û�ΪUC���û�����
                global $_CFG;
                $_SESSION['activate_username'] = $uc_username;
                $login['qs_login'] = $_CFG['main_domain'] . "user/user_reg.php?act=activate";
            }
        } elseif ($uc_uid === -1 && $success) {//74�ɹ���UCʧ�ܣ���ע�ᵽUC
            $uc_reg_uid = uc_user_register($usinfo['username'], $password, $usinfo['email']);
            if ($uc_reg_uid > 0) {
                $login['uc_login'] = uc_user_synlogin($uc_reg_uid);
            }
        }
    }
    return $login;
}

//��Ա�ֻ��ŵ�¼
function user_login_mobile($account, $type = "", $uc_login = true, $expire = NULL) {
    global $timestamp, $online_ip, $QS_pwdhash;
    $usinfo = $login = array();
    $success = false;
    $usinfo = get_user_inmobile($account);
    if (!empty($usinfo) && empty($usinfo['freeze'])) {
        //��ȡ�û���
        $usname = $usinfo['username'];
        if (!empty($_SESSION['openid']) || !empty($_SESSION['sina_access_token'])) {
            if (!empty($type)) {
                switch ($type) {
                    case "wx":
                        update_user_wxopenid($_SESSION['openid'], $usinfo['uid']);
                        break;
                    case "qq":
                        update_user_qqopenid($_SESSION['openid'], $usinfo['uid']);
                        break;
                    case "weibo":
                        update_user_weiboopenid($_SESSION['sina_access_token'], $usinfo['uid']);
                        break;
                    default:
                        break;
                }
            }
        }
        $login['utype'] = $usinfo['utype'];
        if ($login['mobile_audit'] == 0) {
            update_user_info($usinfo['uid'], true, true, $expire);
            $login['qs_login'] = get_member_url($usinfo['utype']);
            $login['freeze'] = trim($usinfo['freeze']);
            $success = true;
            write_memberslog($usinfo['uid'], $usinfo['utype'], 1001, $usname, "�ɹ���¼");
        } else {
            $usinfo = '';
            $success = false;
        }
    } elseif (!empty($usinfo) && !empty($usinfo['freeze'])) {
        $login['freeze'] = trim($usinfo['freeze']);
    }
    if (defined('UC_API') && $uc_login) {
        include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
        $account = $usinfo['username'] ? $usinfo['username'] : $account;
        list($uc_uid, $uc_username, $uc_password, $uc_email) = uc_user_login($account, $password);
        if ($uc_uid > 0) {
            $login['uc_login'] = uc_user_synlogin($uc_uid);
            if ($success == false) {//UC�ɹ�74ʧ�ܾ�ע�ᣬע���û�ΪUC���û�����
                global $_CFG;
                $_SESSION['activate_username'] = $uc_username;
                $login['qs_login'] = $_CFG['main_domain'] . "user/user_reg.php?act=activate";
            }
        } elseif ($uc_uid === -1 && $success) {//74�ɹ���UCʧ�ܣ���ע�ᵽUC
            $uc_reg_uid = uc_user_register($usinfo['username'], $password, $usinfo['email']);
            if ($uc_reg_uid > 0) {
                $login['uc_login'] = uc_user_synlogin($uc_reg_uid);
            }
        }
    }
    return $login;
}

//���COOKIE
function check_cookie($uid, $name, $pwd) {
    global $db;
    $row = $db->getone("SELECT COUNT(*) AS num FROM " . table('members') . " WHERE uid='{$uid}' and username='{$name}' and password = '{$pwd}'");
    if ($row['num'] > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 *
 * �����û���Ϣ
 *
 *
 */
function update_user_info($uid, $record = true, $setcookie = true, $cookie_expire = NULL) {
    global $timestamp, $online_ip, $db, $QS_cookiepath, $QS_cookiedomain, $_CFG; //3.4�����޸� �������$_CFG
    $user = get_user_inid($uid);
    if (empty($user)) {
        return false;
    } else {
        $_SESSION['uid'] = intval($user['uid']);
        $_SESSION['username'] = addslashes($user['username']);
        $_SESSION['utype'] = intval($user['utype']);
    }
    if ($setcookie) {
        $expire = intval($cookie_expire) > 0 ? time() + 3600 * 24 * $cookie_expire : 0;
        setcookie('QS[uid]', $user['uid'], $expire, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[username]', addslashes($user['username']), $expire, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[password]', $user['password'], $expire, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[utype]', $user['utype'], $expire, $QS_cookiepath, $QS_cookiedomain);
    }
    if ($record) {
        $last_login_time = $timestamp;
        $last_login_time = time();
        $last_login_ip = $online_ip;
        $sql = "UPDATE " . table('members') . " SET last_login_time = '$last_login_time', last_login_ip = '$last_login_ip' WHERE uid='{$_SESSION['uid']}'  LIMIT 1";
        $db->query($sql);
        if (($_CFG['operation_mode'] == '1' || ($_CFG['operation_mode'] == '3' && $_CFG['setmeal_to_points'] == '1')) && $_SESSION['utype'] == "1") {
            $rule = get_cache('points_rule');
            if ($rule['userlogin']['value'] > 0) {
                $time = time();
                $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                $info = $db->getone("SELECT uid FROM " . table('members_handsel') . " WHERE uid ='{$_SESSION['uid']}' AND htype='userlogin' AND addtime>{$today}  LIMIT 1");
                if (empty($info)) {
                    $db->query("INSERT INTO " . table('members_handsel') . " (uid,htype,addtime) VALUES ('{$_SESSION['uid']}', 'userlogin','{$time}')");
                    require_once(QISHI_ROOT_PATH . 'include/fun_company.php');
                    report_deal($_SESSION['uid'], $rule['userlogin']['type'], $rule['userlogin']['value']);
                    $user_points = get_user_points($_SESSION['uid']);
                    $user_limit_points = get_user_limit_points($_SESSION['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $operator = $rule['userlogin']['type'] == "1" ? "+" : "-";
                    $_SESSION['handsel_userlogin'] = $operator . $rule['userlogin']['value'];
                    write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], date("Y-m-d") . " ��һ�ε�¼��({$operator}{$rule['userlogin']['value']})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})", 1, 1014, "��Աÿ���һ�ε�¼", "{$operator}{$rule['userlogin']['value']}", "{$user_points}");
                }
            }
        } elseif ($_CFG['operation_train_mode'] == '1' && $_SESSION['utype'] == '4') {
            $rule = get_cache('points_rule');
            if ($rule['trainuserlogin']['value'] > 0) {
                $time = time();
                $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                $info = $db->getone("SELECT uid FROM " . table('members_handsel') . " WHERE uid ='{$_SESSION['uid']}' AND htype='userlogin' AND addtime>{$today}  LIMIT 1");
                if (empty($info)) {
                    $db->query("INSERT INTO " . table('members_handsel') . " (uid,htype,addtime) VALUES ('{$_SESSION['uid']}', 'userlogin','{$time}')");
                    require_once(QISHI_ROOT_PATH . 'include/fun_train.php');
                    report_deal($_SESSION['uid'], $rule['trainuserlogin']['type'], $rule['trainuserlogin']['value']);
                    $user_points = get_user_points($_SESSION['uid']);
                    $user_limit_points = get_user_limit_points($_SESSION['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $operator = $rule['trainuserlogin']['type'] == "1" ? "+" : "-";
                    $_SESSION['handsel_userlogin'] = $operator . $rule['trainuserlogin']['value'];
                    write_memberslog($_SESSION['uid'], 4, 9101, $_SESSION['username'], date("Y-m-d") . " ��һ�ε�¼��({$operator}{$rule['trainuserlogin']['value']})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})");
                }
            }
        } elseif ($_CFG['operation_hunter_mode'] == '1' && $_SESSION['utype'] == '3') {
            $rule = get_cache('points_rule');
            if ($rule['hunteruserlogin']['value'] > 0) {
                $time = time();
                $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                $info = $db->getone("SELECT uid FROM " . table('members_handsel') . " WHERE uid ='{$_SESSION['uid']}' AND htype='userlogin' AND addtime>{$today}  LIMIT 1");
                if (empty($info)) {
                    $db->query("INSERT INTO " . table('members_handsel') . " (uid,htype,addtime) VALUES ('{$_SESSION['uid']}', 'userlogin','{$time}')");
                    require_once(QISHI_ROOT_PATH . 'include/fun_hunter.php');
                    report_deal($_SESSION['uid'], $rule['hunteruserlogin']['type'], $rule['hunteruserlogin']['value']);
                    $user_points = get_user_points($_SESSION['uid']);
                    $user_limit_points = get_user_limit_points($_SESSION['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $operator = $rule['hunteruserlogin']['type'] == "1" ? "+" : "-";
                    $_SESSION['handsel_userlogin'] = $operator . $rule['hunteruserlogin']['value'];
                    write_memberslog($_SESSION['uid'], 3, 9201, $_SESSION['username'], date("Y-m-d") . " ��һ�ε�¼��({$operator}{$rule['hunteruserlogin']['value']})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})");
                }
            }
        }
    }
    //��Ϣ
    $user_pmid = $db->getone("SELECT pmid FROM " . table('pms_sys_log') . " WHERE loguid ='{$_SESSION['uid']}' ORDER BY `pmid` DESC  LIMIT 1");
    $user_pmid = intval($user_pmid['pmid']);
    $result = $db->query("SELECT * FROM " . table('pms_sys') . " WHERE spmid>{$user_pmid} AND (spms_usertype='0' OR spms_usertype='{$_SESSION['utype']}') AND spms_type='1' ");
    while ($row = $db->fetch_array($result)) {
        $setsqlarr['msgtype'] = 1;
        $setsqlarr['msgtouid'] = $_SESSION['uid'];
        $setsqlarr['msgtoname'] = $_SESSION['username'];
        $setsqlarr['message'] = $row['message'];
        $setsqlarr['dateline'] = $timestamp;
        $setsqlarr['replytime'] = $timestamp;
        $setsqlarr['new'] = 1;
        inserttable(table('pms'), $setsqlarr);
        $log['loguid'] = $_SESSION['uid'];
        $log['pmid'] = $row['spmid'];
        inserttable(table('pms_sys_log'), $log);
        unset($setsqlarr, $log);
    }
    //ͳ����Ϣ
    $pmscount = $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$_SESSION['uid']}' OR msgtouid='{$_SESSION['uid']}') AND `new`='1' AND `replyuid`<>'{$_SESSION['uid']}'");
    setcookie('QS[pmscount]', $pmscount, $expire, $QS_cookiepath, $QS_cookiedomain);
    return true;
}

function get_user_inemail($email) {
    global $db;
    return $db->getone("select * from " . table('members') . " where email = '{$email}' LIMIT 1");
}

function get_user_inusername($username) {
    global $db;
    $sql = "select * from " . table('members') . " where username = '{$username}' LIMIT 1";
    return $db->getone($sql);
}

function get_user_inid($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('members') . " where uid = '{$uid}' LIMIT 1";
    return $db->getone($sql);
}

function get_user_inmobile($mobile) {
    global $db;
    $sql = "select * from " . table('members') . " where mobile = '{$mobile}' LIMIT 1";
    return $db->getone($sql);
}

function get_user_inqqopenid($openid) {
    global $db;
    if (empty($openid)) {
        return false;
    }
    $sql = "select * from " . table('members') . " where qq_openid = '{$openid}' LIMIT 1";
    return $db->getone($sql);
}

function get_user_inwxopenid($openid) {
    global $db;
    if (empty($openid)) {
        return false;
    }
    $sql = "select * from " . table('members') . " where wx_openid = '{$openid}' LIMIT 1";
    return $db->getone($sql);
}

function get_user_in_wechat_openid($openid) {
    global $db;
    if (empty($openid)) {
        return false;
    }
    $sql = "select * from " . table('members') . " where wechat_openid = '{$openid}' LIMIT 1";
    return $db->getone($sql);
}

function get_user_inwxunionid($unionid) {
    global $db;
    if (empty($unionid)) {
        return false;
    }
    $sql = "select * from " . table('members') . " where wx_unionid = '{$unionid}' LIMIT 1";
    return $db->getone($sql);
}

function update_user_wxopenid($openid, $uid) {
    global $db;
    if (empty($openid)) {
        return false;
    }
    $db->query("UPDATE " . table('members') . " SET wx_openid = '" . $openid . "'  WHERE uid='" . $uid . "' AND wx_openid='' LIMIT 1");
    $_SESSION['uwxid'] = $_SESSION['openid'];
}

function update_user_qqopenid($openid, $uid) {
    global $db;
    if (empty($openid)) {
        return false;
    }
    $db->query("UPDATE " . table('members') . " SET qq_openid = '" . $openid . "'  WHERE uid='" . $uid . "' AND qq_openid='' LIMIT 1");
    $_SESSION['uqqid'] = $_SESSION['openid'];
}

function update_user_weiboopenid($openid, $uid) {
    global $db;
    if (empty($openid)) {
        return false;
    }
    $db->query("UPDATE " . table('members') . " SET sina_access_token = '" . $openid . "'  WHERE uid='" . $uid . "' AND sina_access_token='' LIMIT 1");
    $_SESSION['sina_access_token'] = $openid;
}

function get_user_insina_access_token($access) {
    global $db;
    if (empty($access)) {
        return false;
    }
    $sql = "select * from " . table('members') . " where sina_access_token = '{$access}' LIMIT 1";
    return $db->getone($sql);
}

function get_user_intaobao_access_token($access) {
    global $db;
    if (empty($access)) {
        return false;
    }
    $sql = "select * from " . table('members') . " where taobao_access_token = '{$access}' LIMIT 1";
    return $db->getone($sql);
}

//�����û���
function activate_user($usname, $pwd, $email, $member_type) {
    global $timestamp, $online_ip;
    if (defined('UC_API')) {
        include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
        list($activateuid, $username, $password, $email) = uc_user_login($usname, $pwd);
        if ($activateuid > 0) {
            return user_register($usname, $pwd, $member_type, $email, false);
        } else {
            return -10;
        }
    }
    return false;
}

//��ȡ����ַ���
function randstr($length = 6) {
    $hash = '';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz@#!~?:-=';
    $max = strlen($chars) - 1;
    mt_srand((double) microtime() * 1000000);
    for ($i = 0; $i < $length; $i++) {
        $hash.=$chars[mt_rand(0, $max)];
    }
    return $hash;
}

//�޸�����
function edit_password($arr, $check = true) {
    global $db, $QS_pwdhash;
    if (!is_array($arr))
        return false;
    $user_info = get_user_inusername($arr['username']);
    $pwd_hash = $user_info['pwd_hash'];
    $password = md5(md5($arr['oldpassword']) . $pwd_hash . $QS_pwdhash);
    if ($check) {
        $row = $db->getone("SELECT * FROM " . table('members') . " WHERE username='{$arr['username']}' and password = '{$password}' LIMIT 1");
        if (empty($row)) {
            return -1;
        }
    }
    $md5password = md5(md5($arr['password']) . $pwd_hash . $QS_pwdhash);
    if ($db->query("UPDATE " . table('members') . " SET password = '$md5password'  WHERE username='" . $arr['username'] . "'"))
        return $arr['username'];
    write_memberslog($_SESSION['uid'], $_SESSION['utype'], 1004, $_SESSION['username'], "�޸�������");
    return false;
}

//��ȡ��Ա��¼��־
function get_user_loginlog($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT * FROM " . table('members_log') . " " . $get_sql . " ORDER BY log_id DESC " . $limit);
    while ($row = $db->fetch_array($result)) {
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_loginlog_one($uid, $type) {
    global $db;
    $result = $db->getone("SELECT * FROM " . table('members_log') . " WHERE log_uid={$uid} AND log_type={$type} ORDER BY log_id DESC LIMIT 1,1");
    return $result;
}

//��16λ��������תΪ32λ��������_Calvin_20140627
function update_password($arr) {
    global $db, $QS_pwdhash;
    if (!is_array($arr))
        return false;
    $md5password = md5(md5($arr['password']) . $arr['pwd_hash'] . $QS_pwdhash);
    $db->query("UPDATE " . table('members') . " SET password = '$md5password'  WHERE username='" . $arr['username'] . "'");
    return $md5password;
}

function get_user_resume($uid = 0) {
    global $db;
    $sql = "select * from " . table('resume') . " where uid = '{$uid}' LIMIT 1";
    return $db->getone($sql);
}

//��ȡ��Ա���߿γ̶���
function get_user_online_course($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT * FROM " . table('online_course_buy_log') . " " . $get_sql . " ORDER BY id DESC " . $limit);
    while ($row = $db->fetch_array($result)) {
        $row_arr[] = $row;
    }
    return $row_arr;
}

//��ȡ��Ա�ղص����߿γ�
function get_user_fav_online_course($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT * FROM " . table('online_course_favorite') . " " . $get_sql . " ORDER BY id DESC " . $limit);
    while ($row = $db->fetch_array($result)) {
        $row_arr[] = $row;
    }
    return $row_arr;
}

//ɾ����Ա�ղص����߿γ�
function del_user_fav_online_course($id = 0) {
    global $db;
    if (intval($id) > 0) {
        $db->query("DELETE FROM " . table('online_course_favorite') . " WHERE id=" . intval($id));
    }
}

?>