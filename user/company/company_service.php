<?php

/*
 * 74cms ��ҵ��Ա����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/company_common.php');
$smarty->assign('leftmenu', "service"); //�ҵ��˻� -> ���ֲ��� 
if ($act == 'j_account') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $smarty->assign('operation_mode', intval($_CFG['operation_mode']));
    //��֧״̬(����->1 ����->2)/����ʱ��
    $cid = trim($_GET['cid']);
    $settr = intval($_GET['settr']);
    //�ײ�
    $my_setmeal = get_user_setmeal($_SESSION['uid']);
    $smarty->assign('setmeal', $my_setmeal);
    //����
    $my_points = get_user_points(intval($_SESSION['uid']));
    $smarty->assign('points', $my_points);
    $smarty->assign('act', 'j_account');
    $smarty->assign('title', '�ҵ��˻� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    //����������ϸ
    if (trim($_GET['detail']) == '1') {
        $wheresql = " WHERE log_uid='{$_SESSION['uid']}' AND log_type=9001 AND log_mode=1";
        if ($settr > 0) {
            $settr_val = strtotime("-" . $settr . " day");
            $wheresql.=" AND log_addtime>" . $settr_val;
            $smarty->assign('settr', $_GET['settr']);
        }
        if ($cid == '1') {
            $smarty->assign('c_type', "����");
            $smarty->assign('cid', $_GET['cid']);
            $wheresql.=" AND log_op_used < 0 ";
        } elseif ($cid == '2') {
            $smarty->assign('c_type', "����");
            $smarty->assign('cid', $_GET['cid']);
            $wheresql.=" AND log_op_used > 0 ";
        }
        $perpage = 10;
        $total_sql = "SELECT COUNT(*) AS num FROM " . table('members_log') . $wheresql;
        $total_val = $db->get_total($total_sql);
        $page = new page(array('total' => $total_val, 'perpage' => $perpage, 'getarray' => $_GET));
        $offset = ($page->nowindex - 1) * $perpage;
        $smarty->assign('report', get_user_report($offset, $perpage, $wheresql));
        $smarty->assign('page', $page->show(3));
        $smarty->display('member_company/company_my_account_detail.htm');
    }
    //���ֹ���
    else {
        $smarty->assign('points_rule', get_points_rule());
        $smarty->display('member_company/company_my_account.htm');
    }
}
//�ҵ��˻� -> �ײͲ��� 
elseif ($act == 't_account') {
    $settr = intval($_GET['settr']);
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $smarty->assign('operation_mode', intval($_CFG['operation_mode']));
    //����
    $my_points = get_user_points(intval($_SESSION['uid']));
    $smarty->assign('points', $my_points);
    //�ײ�
    $my_setmeal = get_user_setmeal($_SESSION['uid']);
    $smarty->assign('setmeal', $my_setmeal);
    $smarty->assign('act', 't_account');
    $smarty->assign('title', '�ҵ��˻� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    //�ײ�������ϸ
    if (trim($_GET['detail']) == '1') {
        $wheresql = " WHERE log_uid='{$_SESSION['uid']}' AND log_type=9002 AND log_mode=2 ";
        if ($settr > 0) {
            $settr_val = strtotime("-" . $settr . " day");
            $wheresql.=" AND log_addtime>" . $settr_val;
            $smarty->assign('settr', $_GET['settr']);
        }
        $perpage = 10;
        $total_sql = "SELECT COUNT(*) AS num FROM " . table('members_log') . $wheresql;
        $total_val = $db->get_total($total_sql);
        $page = new page(array('total' => $total_val, 'perpage' => $perpage, 'getarray' => $_GET));
        $offset = ($page->nowindex - 1) * $perpage;
        $smarty->assign('report', get_user_report($offset, $perpage, $wheresql));
        $smarty->assign('page', $page->show(3));
        $smarty->display('member_company/company_my_account_package_detail.htm');
    }
    //�ײ͹���
    else {
        //������Ƹְλ �� �˲ſ����� Ҫ��������һ�� ��Ϊ���ǵõ���ͳ��
        //��������Ƹְλ = �����е� + ����˵�
        $jobs = "SELECT COUNT(*) AS num FROM " . table('jobs') . " where uid='{$_SESSION['uid']}'";
        $jobs_num = $db->get_total($jobs);
        $jobs_tmp = "SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . " where uid='{$_SESSION['uid']}' and  audit=2 ";
        $jobs_tmp_num = $db->get_total($jobs_tmp);
        $smarty->assign('jobs_num', intval($jobs_num) + intval($jobs_tmp_num));
        //�˲ſ�����
        $favorites = "SELECT COUNT(*) AS num FROM " . table('company_favorites') . " where company_uid='{$_SESSION['uid']}'";
        $favorites_num = $db->get_total($favorites);
        $smarty->assign('favorites_num', intval($favorites_num));
        $smarty->assign('setmeal_rule', get_members_setmeal_rule($my_setmeal['setmeal_id']));
        $smarty->display('member_company/company_my_account_package.htm');
    }
} elseif ($act == 'account') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $i_type = trim($_GET['i_type']);
    $settr = intval($_GET['settr']);
    if ($_CFG['operation_mode'] == "1") {
        $wheresql = " WHERE log_uid='{$_SESSION['uid']}' AND log_type=9001 AND log_mode=1";
    } elseif ($_CFG['operation_mode'] == "2") {
        $wheresql = " WHERE log_uid='{$_SESSION['uid']}' AND log_type=9002 AND log_mode=2 ";
    } else {
        $wheresql1 = " WHERE log_uid='{$_SESSION['uid']}' AND log_type=9001 AND log_mode=1";
        $wheresql2 = " WHERE log_uid='{$_SESSION['uid']}' AND log_type=9002 AND log_mode=2 ";
    }

    if ($_CFG['operation_mode'] == "3") {
        if ($settr > 0) {
            $settr_val = strtotime("-" . $settr . " day");
            $wheresql1.=" AND log_addtime>" . $settr_val;
            $wheresql2.=" AND log_addtime>" . $settr_val;
        }
        $perpage = 15;
        $total_sql1 = "SELECT COUNT(*) AS num FROM " . table('members_log') . $wheresql1;
        $total_sql2 = "SELECT COUNT(*) AS num FROM " . table('members_log') . $wheresql2;
        $total_val1 = $db->get_total($total_sql1);
        $total_val2 = $db->get_total($total_sql2);
        $page1 = new page(array('total' => $total_val1, 'perpage' => $perpage));
        $page2 = new page(array('total' => $total_val2, 'perpage' => $perpage));
        $offset1 = ($page1->nowindex - 1) * $perpage;
        $offset2 = ($page2->nowindex - 1) * $perpage;
        $smarty->assign('report', get_user_report($offset1, $perpage, $wheresql1));
        $smarty->assign('setmeal_report', get_user_report($offset2, $perpage, $wheresql2));
        $smarty->assign('page1', $page1->show(3));
        $smarty->assign('page2', $page2->show(3));
    } else {
        if ($settr > 0) {
            $settr_val = strtotime("-" . $settr . " day");
            $wheresql.=" AND log_addtime>" . $settr_val;
        }
        $perpage = 15;
        $total_sql = "SELECT COUNT(*) AS num FROM " . table('members_log') . $wheresql;
        $total_val = $db->get_total($total_sql);
        $page = new page(array('total' => $total_val, 'perpage' => $perpage));
        $offset = ($page->nowindex - 1) * $perpage;
        $smarty->assign('report', get_user_report($offset, $perpage, $wheresql));
        $smarty->assign('page', $page->show(3));
    }

    $setmeal = get_user_setmeal($_SESSION['uid']);
    if ($setmeal['endtime'] > 0) {
        $setmeal_endtime = sub_day($setmeal['endtime'], time());
    } else {
        $setmeal_endtime = "������";
    }

    $smarty->assign('title', '�ҵ��˻� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $limit_points = get_user_limit_points($_SESSION['uid']);
    $points = get_user_points($_SESSION['uid']);
    $smarty->assign('limit_points', $limit_points);
    $smarty->assign('points', get_user_points($_SESSION['uid']));
    $smarty->assign('total_points', $limit_points['points'] + $points);
    $smarty->assign('setmeal', $setmeal);
    $smarty->assign('points_rule', get_points_rule());
    $smarty->assign('setmeal_rule', get_members_setmeal_rule($setmeal['setmeal_id']));
    $smarty->assign('setmeal_endtime', $setmeal_endtime);
    if ($_CFG['operation_mode'] == "1") {
        $smarty->display('member_company/company_my_account.htm');
    } elseif ($_CFG['operation_mode'] == "2") {
        $smarty->display('member_company/company_my_account_package.htm');
    } else {
        $smarty->display('member_company/company_my_account_complex.htm');
    }
} elseif ($act == 'order_list') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $is_paid = trim($_GET['is_paid']);
    $wheresql = " WHERE uid='" . $_SESSION['uid'] . "' ";
    if ($is_paid <> '' && is_numeric($is_paid)) {
        $wheresql.=" AND is_paid='" . intval($is_paid) . "' ";
    }
    $perpage = 10;
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('order') . $wheresql;
    $page = new page(array('total' => $db->get_total($total_sql), 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $smarty->assign('title', '��ֵ��¼ - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $smarty->assign('is_paid', $is_paid);
    $smarty->assign('payment', get_order_all($offset, $perpage, $wheresql));
    if ($total_val > $perpage) {
        $smarty->assign('page', $page->show(3));
    }
    $smarty->display('member_company/company_order_list.htm');
} elseif ($act == 'order_add') {
    $smarty->assign('title', '���߳�ֵ - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('payment', get_payment());
    $smarty->assign('setmeal', get_points_setmeal());
    $smarty->assign('activity', get_setmeal_activity());
    $limit_points = get_user_limit_points($_SESSION['uid']);
    $points = get_user_points($_SESSION['uid']);
    $smarty->assign('limit_points', $limit_points);
    $smarty->assign('points', get_user_points($_SESSION['uid']));
    $smarty->assign('total_points', $limit_points['points'] + $points);
    $smarty->display('member_company/company_order_add.htm');
} elseif ($act == 'order_add_save') {
    if (empty($company_profile['companyname'])) {
        $link[0]['text'] = "��д��ҵ����";
        $link[0]['href'] = 'company_info.php?act=company_profile';
        showmsg("������д������ҵ���ϣ�", 1, $link);
    }
    $myorder = get_user_order($_SESSION['uid'], 1);
    $order_num = count($myorder);
    if ($order_num >= 5) {
        $link[0]['text'] = "�����鿴";
        $link[0]['href'] = '?act=order_list&is_paid=1';
        showmsg("δ����Ķ������ܳ��� 5 �������ȴ�����ٴ����룡", 1, $link, true, 8);
    }
    //�������µ��ײʹ���
    $setmeal_id = explode("_", $_POST['setmeal']);
    $setmeal_id = $setmeal_id[1];
    $setmeal_info = get_points_setmeal($setmeal_id);
    $amount = $setmeal_info['expense'];
    $setmeal = $setmeal_info['id'];
    $days = $setmeal_info['days'] > 0 ? $setmeal_info['days'] : 0;
    $activity_id = $_POST['activity_meal_' . $setmeal_id];
    $activity_name = "";
    if ($activity_id > 0) {
        $activity_info = get_setmeal_activity($activity_id);
        $amount = $amount + $activity_info['expense'];
        $activity_name = "���ײͻ��" . $activity_info['activity_name'];
    }
    $api_path = "";
    //$amount=(trim($_POST['amount'])).(intval($_POST['amount']))?trim($_POST['amount']):showmsg('����д��ֵ��',1);
    //($amount<$_CFG['payment_min'])?showmsg("���ʳ�ֵ�������� ".$_CFG['payment_min']." Ԫ��",1):'';
    $payment_name = empty($_POST['payment_name']) ? showmsg("��ѡ�񸶿ʽ��", 1) : $_POST['payment_name'];
    $paymenttpye = get_payment_info($payment_name);
    if (empty($paymenttpye)) {
        showmsg("֧����ʽ����", 0);
    }
    $fee = number_format(($amount / 100) * $paymenttpye['fee'], 1, '.', ''); //������
    $order['oid'] = strtoupper(substr($paymenttpye['typename'], 0, 1)) . "-" . date('ymd', time()) . "-" . date('His', time()); //������
    $order['v_amount'] = $amount + $fee;
    if ($payment_name != 'remittance') {//�����Ƿ�����֧����
        if (strstr($payment_name, 'alipayapi-')) {
            $paymenttpye['typename'] = "alipayapi";
            $respond_name = "alipay";
        } else {
            $respond_name = $payment['typename'];
        }
    }
    $order['v_url'] = $_CFG['main_domain'] . "include/payment/respond_" . $respond_name . ".php";
    if ($setmeal_info['points'] < 1) {
        $points = $amount * $_CFG['payment_rate'];
    } else {
        $points = $setmeal_info['points'];
    }
    $description_str = "��ֵ����:" . $points . $activity_name;
    if (intval($_POST['invoice'])) {
        $invoice_name = trim($_POST['invoice_name']);
        if (!empty($invoice_name)) {
            $description_str .= "����Ʊ��Ϣ��" . $invoice_name;
        }
    }
    $order_id = add_order($_SESSION['uid'], $order['oid'], $amount, $days, $payment_name, $description_str, $timestamp, $points, $setmeal, $activity_info, 1);
    if ($order_id) {
        header("location:?act=payment&order_id=" . $order_id);
    } else {
        showmsg("��Ӷ���ʧ�ܣ�", 0);
    }
} elseif ($act == 'payment') {
    $setmeal = get_user_setmeal($_SESSION['uid']);
    if ($setmeal['endtime'] > 0) {
        $setmeal_endtime = sub_day($setmeal['endtime'], time());
    } else {
        $setmeal_endtime = "������";
    }
    $smarty->assign('user_setmeal', $setmeal);
    $smarty->assign('setmeal_endtime', $setmeal_endtime);
    $smarty->assign('payment', get_payment());
    $order_id = intval($_GET['order_id']);
    $myorder = get_order_one($_SESSION['uid'], $order_id);
    $payment = get_payment_info($myorder['payment_name']);
    if (empty($payment)) {
        showmsg("֧����ʽ����", 0);
    }
    $fee = number_format(($amount / 100) * $payment['fee'], 1, '.', ''); //������
    $order['oid'] = $myorder['oid']; //������
    $order['v_amount'] = $myorder['amount'] + $fee;
    if ($myorder['payment_name'] != 'remittance') {//�����Ƿ�����֧����
        if (strstr($myorder['payment_name'], 'alipayapi-')) {
            $api_path = "alipayapi/";
            $payment['typename'] = "alipayapi";
            $respond_name = "alipay";
        } else {
            $respond_name = $payment['typename'];
        }
        $order['v_url'] = $_CFG['main_domain'] . "include/payment/respond_" . $respond_name . ".php";
        require_once(QISHI_ROOT_PATH . "include/payment/" . $api_path . $payment['typename'] . ".php");
        $payment_form = get_code($order, $payment);
        if (empty($payment_form)) {
            showmsg("����֧����������", 0);
        }
    }
    $smarty->assign('points', get_user_points($_SESSION['uid']));
    $smarty->assign('title', '���� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('fee', $fee);
    $smarty->assign('amount', $myorder['amount']);
    $smarty->assign('oid', $order['oid']);
    $smarty->assign('byname', $payment);
    $smarty->assign('payment_form', $payment_form);
    $smarty->display('member_company/company_order_pay.htm');
} elseif ($act == 'order_del') {
    $link[0]['text'] = "������һҳ";
    $link[0]['href'] = '?act=order_list';
    $id = intval($_GET['id']);
    del_order($_SESSION['uid'], $id) ? showmsg('ȡ���ɹ���', 2, $link) : showmsg('ȡ��ʧ�ܣ�', 1);
} elseif ($act == 'setmeal_list') {
    $setmeal = get_user_setmeal($_SESSION['uid']);
    if ($setmeal['endtime'] > 0) {
        $setmeal_endtime = sub_day($setmeal['endtime'], time());
    } else {
        $setmeal_endtime = "������";
    }
    $smarty->assign('user_setmeal', $setmeal);
    $smarty->assign('setmeal_endtime', $setmeal_endtime);
    $smarty->assign('title', '�����б� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('setmeal', get_setmeal());
    $smarty->display('member_company/company_setmeal_list.htm');
} elseif ($act == 'setmeal_order_add') {
    $setmealid = intval($_GET['setmealid']) ? intval($_GET['setmealid']) : showmsg("��ѡ������ײͣ�", 1);
    $setmeal = get_user_setmeal($_SESSION['uid']);
    if ($setmeal['endtime'] > 0) {
        $setmeal_endtime = sub_day($setmeal['endtime'], time());
    } else {
        $setmeal_endtime = "������";
    }
    $smarty->assign('user_setmeal', $setmeal);
    $smarty->assign('setmeal_endtime', $setmeal_endtime);
    $smarty->assign('title', '������� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('setmeal', get_setmeal_one($setmealid));
    $smarty->assign('payment', get_payment());
    $smarty->display('member_company/company_order_add_setmeal.htm');
} elseif ($act == 'setmeal_order_add_save') {
    if (empty($company_profile['companyname'])) {
        $link[0]['text'] = "��д��ҵ����";
        $link[0]['href'] = 'company_info.php?act=company_profile';
        showmsg("������д������ҵ���ϣ�", 1, $link);
    }
    $myorder = get_user_order($_SESSION['uid'], 1);
    $order_num = count($myorder);
    if ($order_num >= 5) {
        $link[0]['text'] = "�����鿴";
        $link[0]['href'] = '?act=order_list&is_paid=1';
        showmsg("δ����Ķ������ܳ��� 5 �������ȴ�����ٴ����룡", 1, $link, true, 8);
    }
    $setmeal = get_setmeal_one($_POST['setmealid']);
    if ($setmeal && $setmeal['apply'] == "1") {
        $payment_name = empty($_POST['payment_name']) ? showmsg("��ѡ�񸶿ʽ��", 1) : $_POST['payment_name'];
        $paymenttpye = get_payment_info($payment_name);
        if (empty($paymenttpye))
            showmsg("֧����ʽ����", 0);

        $fee = number_format(($setmeal['expense'] / 100) * $paymenttpye['fee'], 1, '.', ''); //������
        $order['oid'] = strtoupper(substr($paymenttpye['typename'], 0, 1)) . "-" . date('ymd', time()) . "-" . date('His', time()); //������
        if (strstr($paymenttpye['payment_name'], 'alipayapi-')) {
            $paymenttpye['typename'] = "alipayapi";
            $respond_name = "alipay";
        } else {
            $respond_name = $payment['typename'];
        }
        $order['v_url'] = $_CFG['main_domain'] . "include/payment/respond_" . $respond_name . ".php";
        $order['v_amount'] = $setmeal['expense'] + $fee; //���
        $order_id = add_order($_SESSION['uid'], $order['oid'], $setmeal['expense'], $setmeal['days'], $payment_name, "��ͨ����:" . $setmeal['setmeal_name'], $timestamp, "", $setmeal['id'], "", 1);
        if ($order_id) {
            if ($order['v_amount'] == 0) {//0Ԫ�ײ�
                if (order_paid($order['oid'])) {
                    $link[0]['text'] = "�鿴����";
                    $link[0]['href'] = '?act=order_list';
                    $link[1]['text'] = "��Ա������ҳ";
                    $link[1]['href'] = 'company_index.php?act=';
                    showmsg("�����ɹ���ϵͳ��Ϊ����ͨ�˷���", 2, $link);
                }
            }
            header("Location:?act=payment&order_id=" . $order_id . ""); //����ҳ��
        } else {
            showmsg("��Ӷ���ʧ�ܣ�", 0);
        }
    } else {
        showmsg("��Ӷ���ʧ�ܣ�", 0);
    }
} elseif ($act == 'feedback') {
    $smarty->assign('title', '�û����� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('feedback', get_feedback($_SESSION['uid']));
    $smarty->display('member_company/company_feedback.htm');
} elseif ($act == 'gifts') {
    $smarty->assign('title', '��Ʒ�� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('gifts', get_gifts($_SESSION['uid']));
    $captcha = get_cache('captcha');
    $smarty->assign('verify_gifts', $captcha['verify_gifts']);
    $smarty->display('member_company/company_gifts.htm');
} elseif ($act == 'gifts_apy') {
    $account = trim($_POST['account']) ? trim($_POST['account']) : showmsg("����д���ţ�", 1);
    $pwd = trim($_POST['pwd']) ? trim($_POST['pwd']) : showmsg("����д���룡", 1);
    $captcha = get_cache('captcha');
    $postcaptcha = trim($_POST['postcaptcha']);
    if ($captcha['verify_gifts'] == '1' && empty($postcaptcha)) {
        showmsg("����д��֤��", 1);
    }
    if ($captcha['verify_gifts'] == '1' && strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        showmsg("��֤�����", 1);
    }
    $info = $db->getone("select * from " . table('gifts') . " where account='{$account}'  AND password='{$pwd}' LIMIT 1 ");
    if (empty($info)) {
        showmsg("���Ż��������", 0);
    } else {
        if ($info['usettime'] > 0) {
            showmsg("���ſ��ѱ�ʹ�ã������ظ�ʹ��", 1);
        } else {
            $gifts_type = $db->getone("select * from " . table('gifts_type') . " where t_id='{$info['t_id']}' LIMIT 1 ");
            if ($gifts_type['t_endtime'] != 0 && $gifts_type['t_endtime'] < strtotime(date("Y-m-d"))) {
                showmsg("���ſ��ѳ�����Ч�ڣ�����ʹ��", 1);
            }
            if ($gifts_type['t_effective'] == 0) {
                showmsg("���ſ��ѱ�����Ա����Ϊ�����ã�����ϵ��վ����Ա", 1);
            }
            if ($gifts_type['t_repeat'] > 0) {
                $total = $db->get_total("SELECT COUNT(*) AS num FROM " . table('members_gifts') . " where uid='{$_SESSION['uid']}'");
                if ($total >= $gifts_type['t_repeat']) {
                    showmsg("{$gifts_type['t_name']} ÿ����Ա������ʹ�� {$gifts_type['t_repeat']} �Ρ�", 1);
                }
            }
            $db->query("UPDATE " . table('gifts') . " SET usettime = '" . time() . "',useuid= '{$_SESSION['uid']}'  where account='{$account}'");
            $setsqlarr['uid'] = $_SESSION['uid'];
            $setsqlarr['usetime'] = time();
            $setsqlarr['account'] = $account;
            $setsqlarr['giftsname'] = $gifts_type['t_name'];
            $setsqlarr['giftsamount'] = $gifts_type['t_amount'];
            $setsqlarr['giftstid'] = $gifts_type['t_id'];
            inserttable(table('members_gifts'), $setsqlarr);
            report_deal($_SESSION['uid'], 1, $setsqlarr['giftsamount']);
            $user_points = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $operator = "+";
            write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "ʹ����Ʒ��({$account})��ֵ({$operator}{$setsqlarr['giftsamount']})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})", 1, 1021, "��Ʒ����ֵ", "{$operator}{$setsqlarr['giftsamount']}", "{$user_points}");
            showmsg("��ֵ�ɹ���", 2);
        }
    }
} elseif ($act == 'feedback_save') {
    $get_feedback = get_feedback($_SESSION['uid']);
    if (count($get_feedback) >= 5) {
        showmsg('������Ϣ���ܳ���5����', 1);
        exit();
    }
    $setsqlarr['infotype'] = intval($_POST['infotype']);
    $setsqlarr['feedback'] = trim($_POST['feedback']) ? trim($_POST['feedback']) : showmsg("����д���ݣ�", 1);
    $setsqlarr['uid'] = $_SESSION['uid'];
    $setsqlarr['usertype'] = $_SESSION['utype'];
    $setsqlarr['username'] = $_SESSION['username'];
    $setsqlarr['addtime'] = $timestamp;
    write_memberslog($_SESSION['uid'], 1, 7001, $_SESSION['username'], "����˷�����Ϣ");
    !inserttable(table('feedback'), $setsqlarr) ? showmsg("���ʧ�ܣ�", 0) : showmsg("��ӳɹ�����ȴ�����Ա�ظ���", 2);
} elseif ($act == 'del_feedback') {
    $id = intval($_GET['id']);
    del_feedback($id, $_SESSION['uid']) ? showmsg('ɾ���ɹ���', 2) : showmsg('ɾ��ʧ�ܣ�', 1);
} elseif ($act == 'adv_list') {
    $smarty->assign('title', '������λ - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('adv_list', get_adv_list());
    $smarty->display('member_company/company_adv_list.htm');
} elseif ($act == 'adv_order_add') {
    $advid = intval($_GET['advid']) ? intval($_GET['advid']) : showmsg("��ѡ����λ��", 1);
    $smarty->assign('company_profile', $company_profile);
    $smarty->assign('points', get_user_points($_SESSION['uid']));
    $smarty->assign('title', '������λ - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('advinfo', get_adv_one($advid));
    $smarty->assign('payment', get_payment());
    $smarty->display('member_company/company_order_add_adv.htm');
} elseif ($act == 'order_adv_add_save') {
    if (empty($company_profile['companyname'])) {
        $link[0]['text'] = "��д��ҵ����";
        $link[0]['href'] = 'company_info.php?act=company_profile';
        showmsg("������д������ҵ���ϣ�", 1, $link);
    }
    $advinfo = get_adv_one($_POST['advid']);
    $time = intval($_POST['time']);
    $time_unit = $_POST['time_unit'];
    $contact = trim($_POST['contact']);
    $phone = trim($_POST['phone']);
    $payment_name = empty($_POST['payment_name']) ? showmsg("��ѡ�񸶿ʽ��", 1) : $_POST['payment_name'];
    $description_str = $advinfo['categoryname'];
    if (intval($_POST['invoice'])) {
        $invoice_name = trim($_POST['invoice_name']);
        if (!empty($invoice_name)) {
            $description_str .= "����Ʊ��Ϣ��" . $invoice_name;
        }
    }
    if ($payment_name == "points") {
        $p = get_user_points($_SESSION['uid']);
        $expense = intval($_POST['points_expense_input']);
        if ($p < $expense) {
            showmsg("���Ļ��ֲ�����֧����", 1);
        }
        //$order['oid'] = date('Ymd', time()) . "-" . date('His', time()); //������
        $order['oid'] = date('YmdHis', time()) . "-" . $_SESSION['uid'] . "-1"; //������
        $order['v_amount'] = $expense; //���
        $order_id = add_adv_order($_SESSION['uid'], $order['oid'], $order['v_amount'], $time, $time_unit, "���ѻ���", $description_str, $timestamp, 1, $contact, $phone);
    } else {
        $paymenttpye = get_payment_info($payment_name);
        if (empty($paymenttpye))
            showmsg("֧����ʽ����", 0);
        $expense = intval($_POST['expense_input']);
        $fee = number_format(($expense / 100) * $paymenttpye['fee'], 1, '.', ''); //������
        //$order['oid'] = date('Ymd', time()) . "-" . $paymenttpye['partnerid'] . "-" . date('His', time()); //������
        $order['oid'] = date('YmdHis', time()) . "-" . $_SESSION['uid'] . "-2"; //������
        if (strstr($paymenttpye['payment_name'], 'alipayapi-')) {
            $paymenttpye['typename'] = "alipayapi";
            $respond_name = "alipay";
        } else {
            $respond_name = $payment['typename'];
        }
        $order['v_url'] = $_CFG['site_domain'] . $_CFG['site_dir'] . "include/payment/respond_" . $respond_name . ".php";
        $order['v_amount'] = $expense + $fee; //���
        $order_id = add_adv_order($_SESSION['uid'], $order['oid'], $order['v_amount'], $time, $time_unit, $payment_name, $description_str, $timestamp, 0, $contact, $phone);
    }
    if ($order_id) {
        if ($order['v_amount'] == 0) {//0Ԫ���λ
            if (adv_order_paid($order['oid'])) {
                $link[0]['text'] = "�鿴����";
                $link[0]['href'] = '?act=adv_order_list';
                $link[1]['text'] = "��Ա������ҳ";
                $link[1]['href'] = 'company_index.php?act=';
                showmsg("�����ɹ�����ȴ�����Ա��ˣ�", 2, $link);
            }
        }
        header("Location:?act=adv_payment&order_id=" . $order_id . ""); //����ҳ��
    } else {
        showmsg("��Ӷ���ʧ�ܣ�", 0);
    }
} elseif ($act == 'adv_payment') {
    $_SESSION['adv_pay'] = 1;
    $smarty->assign('payment', get_payment());
    $order_id = intval($_GET['order_id']);
    $myorder = get_adv_order_one($_SESSION['uid'], $order_id);
    if ($myorder['is_points'] == "0") {
        $payment = get_payment_info($myorder['payment_name']);
        if (empty($payment))
            showmsg("֧����ʽ����", 0);
        $fee = number_format(($amount / 100) * $payment['fee'], 1, '.', ''); //������
        $order['oid'] = $myorder['oid']; //������
        $order['v_amount'] = $myorder['amount'] + $fee;
        if ($myorder['payment_name'] != 'remittance') {//�����Ƿ�����֧����
            if (strstr($myorder['payment_name'], 'alipayapi-')) {
                $api_path = "alipayapi/";
                $payment['typename'] = "alipayapi";
                $respond_name = "alipay";
            } else {
                $respond_name = $payment['typename'];
            }
            $order['v_url'] = $_CFG['site_domain'] . $_CFG['site_dir'] . "include/payment/respond_" . $respond_name . ".php";
            require_once(QISHI_ROOT_PATH . "include/payment/" . $api_path . $payment['typename'] . ".php");
            $payment_form = get_code($order, $payment);
            if (empty($payment_form)) {
                showmsg("����֧����������", 0);
            }
        }
    }
    if ($myorder['is_points'] == "1") {
        $myorder['amount'] = intval($myorder['amount']);
    }
    if ($myorder['week'] > 0) {
        $myorder['time'] = $myorder['week'];
        $myorder['time_unit'] = "��";
    } elseif ($myorder['month'] > 0) {
        $myorder['time'] = $myorder['month'];
        $myorder['time_unit'] = "��";
    } elseif ($myorder['year'] > 0) {
        $myorder['time'] = $myorder['year'];
        $myorder['time_unit'] = "��";
    } elseif ($myorder['piece'] > 0) {
        $myorder['time'] = $myorder['piece'];
        $myorder['time_unit'] = "��";
    }
    $smarty->assign('myorder', $myorder);
    $smarty->assign('fee', $fee);
    $smarty->assign('title', '���� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('byname', $payment);
    $smarty->assign('payment_form', $payment_form);
    $smarty->display('member_company/company_adv_order_pay.htm');
} elseif ($act == "adv_order_pay") {
    $orderid = intval($_GET['order_id']) ? intval($_GET['order_id']) : showmsg("��û��ѡ�񶩵���", 1);
    $myorder = get_adv_order_one($_SESSION['uid'], $orderid);
    if (adv_order_paid($myorder['oid'])) {
        $link[0]['text'] = "�鿴����";
        $link[0]['href'] = '?act=adv_order_list';
        $link[1]['text'] = "��Ա������ҳ";
        $link[1]['href'] = 'company_index.php?act=';
        showmsg("�����ɹ�����ȴ�����Ա��ˣ�", 2, $link);
    }
} elseif ($act == 'adv_order_list') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $is_paid = trim($_GET['is_paid']);
    $wheresql = " WHERE uid='" . $_SESSION['uid'] . "' ";
    if ($is_paid <> '' && is_numeric($is_paid)) {
        $wheresql.=" AND is_paid='" . intval($is_paid) . "' ";
    }
    $perpage = 10;
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('adv_order') . $wheresql;
    $page = new page(array('total' => $db->get_total($total_sql), 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $smarty->assign('title', '��涩�� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $smarty->assign('is_paid', $is_paid);
    $smarty->assign('payment', get_adv_order_all($offset, $perpage, $wheresql));
    if ($total_val > $perpage) {
        $smarty->assign('page', $page->show(3));
    }
    $smarty->display('member_company/company_adv_order_list.htm');
} elseif ($act == 'adv_order_del') {
    $link[0]['text'] = "������һҳ";
    $link[0]['href'] = '?act=adv_order_list';
    $id = intval($_GET['id']);
    del_adv_order($_SESSION['uid'], $id) ? showmsg('ȡ���ɹ���', 2, $link) : showmsg('ȡ��ʧ�ܣ�', 1);
}
unset($smarty);
?>