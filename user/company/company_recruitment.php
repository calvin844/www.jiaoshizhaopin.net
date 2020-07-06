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
$smarty->assign('leftmenu', "recruitment");
$uid = intval($_SESSION['uid']);
$smarty->assign('total_nolook_resume', $db->get_total("SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . " WHERE company_uid=" . $uid . " AND personal_look=1 AND resume_name !='' AND resume_id IN(SELECT id FROM " . table('resume') . " WHERE sex_cn !='' OR attachment_resume !='' )"));
$smarty->assign('total_down_resume', $db->get_total("SELECT COUNT(*) AS num FROM " . table('company_down_resume') . " WHERE company_uid=" . $uid));
$smarty->assign('total_view_resume', $db->get_total("SELECT COUNT(*) AS num FROM " . table('view_resume') . " WHERE uid=" . $uid));
$smarty->assign('total_favorites_resume', $db->get_total("SELECT COUNT(*) AS num FROM " . table('company_favorites') . " WHERE company_uid=" . $uid));
$smarty->assign('total_interview', $db->get_total("SELECT COUNT(*) AS num FROM " . table('company_interview') . " WHERE company_uid=" . $uid));
if ($act == 'apply_jobs') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $joinsql = " LEFT JOIN  " . table('resume') . " AS r  ON  a.resume_id=r.id ";
    $uid = intval($_SESSION['uid']);
    $wheresql = " WHERE a.company_uid='" . $uid . "' AND a.resume_name != ''";
    if (intval($_GET['intention_jobs']) > 0) {
        $wheresql.=" AND a.jobs_id='" . intval($_GET['intention_jobs']) . "' ";
    }
    if (intval($_GET['talent']) > 0) {
        $wheresql.=" AND r.talent=" . intval($_GET['talent']);
    }
    if (intval($_GET['experience']) > 0) {
        $wheresql.=" AND r.experience=" . intval($_GET['experience']);
    }
    if (intval($_GET['education']) > 0) {
        $wheresql.=" AND r.education=" . intval($_GET['education']);
    }
    if (intval($_GET['sex']) > 0) {
        $wheresql.=" AND r.sex=" . intval($_GET['sex']);
    }
    if (intval($_GET['age']) > 0 || intval($_GET['addtime'] > 0)) {
        $wheresql.=" ORDER BY ";
        if (intval($_GET['age']) > 0) {
            $wheresql.=" r.birthdate asc,";
        }
        if (intval($_GET['addtime']) > 0) {
            $wheresql.=" a.apply_addtime DESC,";
        }
        $wheresql = trim($wheresql, ",");
    } else {
        $wheresql.=" ORDER BY a.did DESC ";
    }

    $perpage = 10;
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . " AS a " . $joinsql . $wheresql;
    $page = new page(array('total' => $db->get_total($total_sql), 'perpage' => $perpage));
    $offset = ($page->nowindex - 1) * $perpage;
    $smarty->assign('act', $act);
    $smarty->assign('title', '�յ���ְλ���� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $jobs_sql = "SELECT id,jobs_name FROM " . table('jobs') . " WHERE uid='" . $uid . "' " . " UNION ALL SELECT id,jobs_name FROM " . table('jobs_tmp') . " WHERE uid='" . $uid . "' ";
    $smarty->assign('company_jobs', get_jobs(0, 0, $jobs_sql, true));
    $jobs_apply_arr = get_apply_jobs($offset, $perpage, $joinsql . $wheresql);
    foreach ($jobs_apply_arr as $j) {
        if (!empty($j['attachment_resume'])) {
            $j['attachment_resume_name'] = explode("--", $j['attachment_resume']);
            $j['attachment_resume_name'] = $j['attachment_resume_name'][2];
        }
        $jobs_apply[] = $j;
    }
    $smarty->assign('jobs_apply', $jobs_apply);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('count', count_jobs_apply($_SESSION['uid']));
    $smarty->assign('count1', count_jobs_apply($_SESSION['uid'], 1));
    $smarty->assign('count2', count_jobs_apply($_SESSION['uid'], 2));
    $smarty->assign('jobs', get_auditjobs($_SESSION['uid']));
    $smarty->display('member_company/company_apply_jobs.htm');
} elseif ($act == 'set_apply_jobs') {
    $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : showmsg("��û��ѡ���κ���Ŀ��", 1);
    set_apply($yid, $_SESSION['uid'], 2) ? showmsg("���óɹ���", 2) : showmsg("����ʧ�ܣ�", 0);
} elseif ($act == 'apply_jobs_del') {
    $ajax = !empty($_REQUEST['ajax']) ? intval($_REQUEST['ajax']) : 0;
    if ($ajax) {
        $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : exit("��û��ѡ�������");
    } else {
        $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : showmsg("��û��ѡ�������", 1);
    }
    if ($n = del_apply_jobs($yid, $_SESSION['uid'])) {
        $ajax ? exit("ɾ���ɹ�����ɾ�� {$n} ��") : showmsg("ɾ���ɹ�����ɾ�� {$n} ��", 2);
    } else {
        $ajax ? exit("ʧ�ܣ�") : showmsg("ʧ�ܣ�", 0);
    }
} elseif ($act == 'search_resume') {
    $smarty->assign('title', '�������� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $smarty->display('member_company/company_search_resume.htm');
} elseif ($act == 'down_resume_list') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $perpage = 10;
    $joinsql = " LEFT JOIN  " . table('resume') . " as r ON d.resume_id=r.id ";
    $uid = intval($_SESSION['uid']);
    $wheresql = " WHERE  d.company_uid='" . $uid . "' ";
    if (intval($_GET['intention_jobs']) > 0) {
        $joinsql = " LEFT JOIN  " . table('resume') . " AS r ON  d.resume_id=r.id  LEFT JOIN  " . table('resume_jobs') . " AS j ON  d.resume_id=j.pid ";
        $wheresql = $joinsql . $wheresql . " AND j.subclass=" . intval($_GET['intention_jobs']);
        $joinsql = "";
    }
    if (intval($_GET['talent']) > 0) {
        $wheresql.=" AND r.talent=" . intval($_GET['talent']);
    }
    if (intval($_GET['experience']) > 0) {
        $wheresql.=" AND r.experience=" . intval($_GET['experience']);
    }
    if (intval($_GET['education']) > 0) {
        $wheresql.=" AND r.education=" . intval($_GET['education']);
    }
    if (intval($_GET['sex']) > 0) {
        $wheresql.=" AND r.sex=" . intval($_GET['sex']);
    }
    $settr = intval($_GET['settr']);
    if ($settr > 0) {
        $settr_val = strtotime("-" . $settr . " day");
        $wheresql.=" AND d.down_addtime>" . $settr_val;
    }
    if (intval($_GET['age']) > 0 || intval($_GET['addtime'] > 0)) {
        $wheresql.=" ORDER BY ";
        if (intval($_GET['age']) > 0) {
            $wheresql.=" r.birthdate asc,";
        }
        if (intval($_GET['addtime']) > 0) {
            $wheresql.=" d.down_addtime DESC,";
        }
        $wheresql = trim($wheresql, ",");
    } else {
        $wheresql.=" ORDER BY d.did DESC ";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_down_resume') . " as d" . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $smarty->assign('title', '�����صļ��� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $jobs_sql = "SELECT id,jobs_name FROM " . table('jobs') . " WHERE uid='" . $uid . "' " . " UNION ALL SELECT id,jobs_name FROM " . table('jobs_tmp') . " WHERE uid='" . $uid . "' ";
    $smarty->assign('jobs', get_jobs(0, 0, $jobs_sql, true));
    $jobs_down_arr = get_down_resume($offset, $perpage, $joinsql . $wheresql);
    foreach ($jobs_down_arr as $j) {
        if (!empty($j['attachment_resume'])) {
            $j['attachment_resume_name'] = explode("--", $j['attachment_resume']);
            $j['attachment_resume_name'] = $j['attachment_resume_name'][2];
        }
        $down_resume[] = $j;
    }
    $smarty->assign('down_resume', $down_resume);
    $smarty->assign('page', $page->show(3));
    $smarty->display('member_company/company_down_resume.htm');
} elseif ($act == 'down_resume_del') {
    $ajax = !empty($_REQUEST['ajax']) ? intval($_REQUEST['ajax']) : 0;
    if ($ajax) {
        $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : exit("��û��ѡ�������");
    } else {
        $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : showmsg("��û��ѡ�������", 1);
    }
    if ($n = del_down_resume($yid, $_SESSION['uid'])) {
        $ajax ? exit("ɾ���ɹ�����ɾ�� {$n} ��") : showmsg("ɾ���ɹ�����ɾ�� {$n} ��", 2);
    } else {
        $ajax ? exit("ʧ�ܣ�") : showmsg("ʧ�ܣ�", 0);
    }
} elseif ($act == 'perform') {
    $id = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : showmsg("��û��ѡ�������", 1);
    if (!empty($_REQUEST['shift'])) {
        $num = down_to_favorites($id, $_SESSION['uid']);
        if ($num === 'full') {
            showmsg("�˲ſ�����!", 1);
        } elseif ($num > 0) {
            showmsg("��ӳɹ�������� {$num} ��", 2);
        } else {
            showmsg("���ʧ��,�Ѿ����ڣ�", 1);
        }
    } elseif (!empty($_REQUEST['attention_shift'])) {
        $num = attention_to_favorites($id, $_SESSION['uid']);
        if ($num === 'full') {
            showmsg("�˲ſ�����!", 1);
        } elseif ($num > 0) {
            showmsg("��ӳɹ�������� {$num} ��", 2);
        } else {
            showmsg("���ʧ��,�Ѿ����ڣ�", 1);
        }
    }
} elseif ($act == 'ajax_down_to_favorites') {
    $id = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : exit("��û��ѡ�������");
    $num = down_to_favorites($id, $_SESSION['uid']);
    if ($num === 'full') {
        exit("�˲ſ�����!");
    } elseif ($num > 0) {
        exit("��ӳɹ�������� {$num} ��");
    } else {
        exit("���ʧ��,�Ѿ����ڣ�");
    }
} elseif ($act == 'favorites_list') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $perpage = 10;
    $joinsql = " LEFT JOIN  " . table('resume') . " AS r ON  f.resume_id=r.id ";
    $uid = intval($_SESSION['uid']);
    $wheresql = " WHERE f.company_uid='" . $uid . "' ";
    if (intval($_GET['intention_jobs']) > 0) {
        $joinsql = " LEFT JOIN  " . table('resume') . " AS r ON  f.resume_id=r.id  LEFT JOIN  " . table('resume_jobs') . " AS j ON  f.resume_id=j.pid ";
        $wheresql = $joinsql . $wheresql . " AND j.subclass=" . intval($_GET['intention_jobs']);
        $joinsql = "";
    }
    if (intval($_GET['talent']) > 0) {
        $wheresql.=" AND r.talent=" . intval($_GET['talent']);
    }
    if (intval($_GET['experience']) > 0) {
        $wheresql.=" AND r.experience=" . intval($_GET['experience']);
    }
    if (intval($_GET['education']) > 0) {
        $wheresql.=" AND r.education=" . intval($_GET['education']);
    }
    if (intval($_GET['sex']) > 0) {
        $wheresql.=" AND r.sex=" . intval($_GET['sex']);
    }
    $settr = intval($_GET['settr']);
    if ($settr > 0) {
        $settr_val = strtotime("-" . $settr . " day");
        $wheresql.=" AND f.favoritesa_ddtime>" . $settr_val;
    }
    if (intval($_GET['age']) > 0 || intval($_GET['addtime'] > 0)) {
        $wheresql.=" ORDER BY ";
        if (intval($_GET['age']) > 0) {
            $wheresql.=" r.birthdate asc,";
        }
        if (intval($_GET['addtime']) > 0) {
            $wheresql.=" f.favoritesa_ddtime DESC,";
        }
        $wheresql = trim($wheresql, ",");
    } else {
        $wheresql.=" ORDER BY f.did DESC ";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_favorites') . " AS f " . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $offset = ($page->nowindex - 1) * $perpage;
    $smarty->assign('title', '��ҵ�˲ſ� - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $smarty->assign('act', $act);
    $jobs_sql = "SELECT id,jobs_name FROM " . table('jobs') . " WHERE uid='" . $uid . "' " . " UNION ALL SELECT id,jobs_name FROM " . table('jobs_tmp') . " WHERE uid='" . $uid . "' ";
    $smarty->assign('jobs', get_jobs(0, 0, $jobs_sql, true));
    $jobs_favorites_arr = get_favorites($offset, $perpage, $joinsql . $wheresql);
    foreach ($jobs_favorites_arr as $j) {
        if (!empty($j['attachment_resume'])) {
            $j['attachment_resume_name'] = explode("--", $j['attachment_resume']);
            $j['attachment_resume_name'] = $j['attachment_resume_name'][2];
        }
        $favorites[] = $j;
    }
    $smarty->assign('favorites', $favorites);
    $smarty->assign('page', $page->show(3));
    $smarty->display('member_company/company_favorites.htm');
} elseif ($act == 'favorites_del') {
    $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : showmsg("��û��ѡ�������", 1);
    if ($n = del_favorites($yid, $_SESSION['uid'])) {
        showmsg("ɾ���ɹ�����ɾ�� {$n} ��", 2);
    } else {
        showmsg("ʧ�ܣ�", 0);
    }
}
//�����������б�
elseif ($act == 'interview_list') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $perpage = 10;
    $joinsql = " LEFT JOIN " . table('resume') . " as r ON i.resume_id=r.id ";
    $uid = intval($_SESSION['uid']);
    $wheresql = " WHERE i.company_uid='" . $uid . "' ";
    if (intval($_GET['intention_jobs']) > 0) {
        $wheresql.=" AND i.jobs_id=" . intval($_GET['intention_jobs']);
    }
    if (intval($_GET['talent']) > 0) {
        $wheresql.=" AND r.talent=" . intval($_GET['talent']);
    }
    if (intval($_GET['experience']) > 0) {
        $wheresql.=" AND r.experience=" . intval($_GET['experience']);
    }
    if (intval($_GET['education']) > 0) {
        $wheresql.=" AND r.education=" . intval($_GET['education']);
    }
    if (intval($_GET['sex']) > 0) {
        $wheresql.=" AND r.sex=" . intval($_GET['sex']);
    }
    $settr = intval($_GET['settr']);
    if ($settr > 0) {
        $settr_val = strtotime("-" . $settr . " day");
        $wheresql.=" AND i.interview_addtime>" . $settr_val;
    }
    if (intval($_GET['age']) > 0 || intval($_GET['addtime'] > 0)) {
        $wheresql.=" ORDER BY ";
        if (intval($_GET['age']) > 0) {
            $wheresql.=" r.birthdate asc,";
        }
        if (intval($_GET['addtime']) > 0) {
            $wheresql.=" i.interview_addtime DESC,";
        }
        $wheresql = trim($wheresql, ",");
    } else {
        $wheresql.=" ORDER BY i.did DESC ";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_interview') . " as i " . $joinsql . $wheresql;
    $total_val = $db->get_total($total_sql);
    $page = new page(array('total' => $total_val, 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $smarty->assign('act', $act);
    $smarty->assign('title', '�ҷ������������ - ��ҵ��Ա���� - ' . $_CFG['site_name']);
    $jobs_sql = "SELECT id,jobs_name FROM " . table('jobs') . " WHERE uid='" . $uid . "' " . " UNION ALL SELECT id,jobs_name FROM " . table('jobs_tmp') . " WHERE uid='" . $uid . "' ";
    $smarty->assign('jobs', get_jobs(0, 0, $jobs_sql, true));
    $smarty->assign('resume', get_interview($offset, $perpage, $joinsql . $wheresql));
    //$smarty->assign('jobs', get_auditjobs($_SESSION['uid']));
    $smarty->assign('page', $page->show(3));
    $smarty->display('member_company/company_interview.htm');
}
//ɾ������������Ϣ
elseif ($act == 'interview_del') {
    $ajax = !empty($_REQUEST['ajax']) ? intval($_REQUEST['ajax']) : 0;
    if ($ajax) {
        $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : exit("��û��ѡ�������");
    } else {
        $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : showmsg("��û��ѡ�������", 1);
    }
    if (del_interview($yid, $_SESSION['uid'])) {
        $ajax ? exit("ɾ���ɹ���") : showmsg("ɾ���ɹ���", 2);
    } else {
        $ajax ? exit("ʧ�ܣ�") : showmsg("ʧ�ܣ�", 0);
    }
} elseif ($act == 'my_attention') {
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');

    $perpage = 10;
    $joinsql = " LEFT JOIN " . table('resume') . " as r ON a.resumeid=r.id ";
    $uid = intval($_SESSION['uid']);
    $wheresql = " WHERE a.uid='" . $uid . "' ";
    if (intval($_GET['talent']) > 0) {
        $wheresql.=" AND r.talent=" . intval($_GET['talent']);
    }
    if (intval($_GET['experience']) > 0) {
        $wheresql.=" AND r.experience=" . intval($_GET['experience']);
    }
    if (intval($_GET['education']) > 0) {
        $wheresql.=" AND r.education=" . intval($_GET['education']);
    }
    if (intval($_GET['sex']) > 0) {
        $wheresql.=" AND r.sex=" . intval($_GET['sex']);
    }
    $settr = intval($_GET['settr']);
    if ($settr > 0) {
        $settr_val = strtotime("-" . $settr . " day");
        $wheresql.=" AND a.addtime>" . $settr_val;
    }
    if (intval($_GET['age']) > 0 || intval($_GET['addtime'] > 0)) {
        $wheresql.=" ORDER BY ";
        if (intval($_GET['age']) > 0) {
            $wheresql.=" r.birthdate asc,";
        }
        if (intval($_GET['addtime']) > 0) {
            $wheresql.=" a.addtime DESC,";
        }
        $wheresql = trim($wheresql, ",");
    } else {
        $wheresql.=" ORDER BY a.id DESC ";
    }
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('view_resume') . " AS a " . $joinsql . $wheresql;
    $page = new page(array('total' => $db->get_total($total_sql), 'perpage' => $perpage));
    $offset = ($page->nowindex - 1) * $perpage;
    $smarty->assign('title', '�����¼ - ��ҵ��Ա���� - ' . $_CFG['site_name']);

    $jobs_attention_arr = get_my_attention($offset, $perpage, $joinsql . $wheresql);
    foreach ($jobs_attention_arr as $j) {
        if (!empty($j['attachment_resume'])) {
            $j['attachment_resume_name'] = explode("--", $j['attachment_resume']);
            $j['attachment_resume_name'] = $j['attachment_resume_name'][2];
        }
        $resume_list[] = $j;
    }
    $smarty->assign('resume_list', $resume_list);
    $smarty->assign('page', $page->show(3));
    $smarty->display('member_company/company_my_attention.htm');
} elseif ($act == 'del_my_attention') {
    $ajax = !empty($_REQUEST['ajax']) ? intval($_REQUEST['ajax']) : 0;
    if ($ajax) {
        $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : exit("��û��ѡ�������");
    } else {
        $yid = !empty($_REQUEST['y_id']) ? $_REQUEST['y_id'] : showmsg("��û��ѡ�������", 1);
    }
    if (del_my_attention($yid, $_SESSION['uid'])) {
        $ajax ? exit("ɾ���ɹ���") : showmsg("ɾ���ɹ���", 2);
    } else {
        $ajax ? exit("ʧ�ܣ�") : showmsg("ʧ�ܣ�", 0);
    }
} elseif ($act == 'download_attachment_resume') {
    $uid = intval($_SESSION['uid']);
    $resume_uid = intval($_GET['resume_uid']);
    $apply = $db->getone("SELECT * FROM " . table('personal_jobs_apply') . " WHERE company_uid = '" . $uid . "' AND personal_uid = '" . $resume_uid . "'");
    $down = $db->getone("SELECT * FROM " . table('company_down_resume') . " WHERE company_uid = '" . $uid . "' AND resume_uid = '" . $resume_uid . "'");
    if (empty($apply) && empty($down)) {
        showmsg('��û������Ȩ�ޣ��������ظü���', 2);
    }
    $attachment_arr = $db->getone("SELECT * FROM " . table('resume_attachment') . " WHERE uid = " . $resume_uid);
    //������Ҫ���ص��ļ�����
    //$download_path = QISHI_ROOT_PATH . trim($attachment_arr['path'] . $attachment_arr['file_name'], "/");
    $download_path = "../.." . ($attachment_arr['path'] . $attachment_arr['file_name']);
    ob_clean(); //���һ�»�����
    //����ļ�����
    $filename = urldecode($attachment_arr['file_name']);
    //�ļ�����·�������ｫ��ʵ���ļ������tempĿ¼�£�
    $filePath = urldecode($download_path);
    //��utf8����ת����gbk���룬�����ļ��������Ƶ��ļ��޷���
    //$filePath = iconv('UTF-8', 'gbk', $filePath);
    //����ļ��Ƿ�ɶ�
    if (!is_file($filePath) || !is_readable($filePath))
        showmsg('�ļ����������', 2);
    /**
     * ����Ӧ�ü��ϰ�ȫ��֤֮��Ĵ��룬���磺���������Դ����֤UA��ʶ�ȵ�
     */
    //��ֻ����ʽ���ļ�����ǿ��ʹ�ö�����ģʽ
    $fileHandle = fopen($filePath, "rb");
    if ($fileHandle === false) {
        showmsg('�ļ���ʧ��', 2);
    }
    //�ļ������Ƕ�������������Ϊutf8���루֧�������ļ����ƣ�
    header('Content-type:application/octet-stream; charset=utf-8');
    header("Content-Transfer-Encoding: binary");
    header("Accept-Ranges: bytes");
    //�ļ���С
    header("Content-Length: " . filesize($filePath));
    //����������ļ����ع���
    header('Content-Disposition:attachment;filename="' . $filename . '"');
    header('Content-Type: application/octet-stream; name=' . $attachment_arr['file_name']);
    //ѭ����ȡ�ļ����ݣ������
    while (!feof($fileHandle)) {
        //���ļ�ָ�� handle ��ȡ��� length ���ֽڣ�ÿ�����10k��
        echo fread($fileHandle, 10240);
    }
    //�ر��ļ���
    fclose($fileHandle);
}
unset($smarty);
?>