<?php

/*
 * 74cms ����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../data/config.php');
require_once(dirname(__FILE__) . '/include/admin_common.inc.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'index';
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'index';
if ($act == 'index') {
    $id = !empty($_GET['id']) ? intval($_GET['id']) : 0;
    $data_list = array();
    if ($id > 0) {
        $limit = 100;
        $page = intval($_GET['p']) > 0 ? intval($_GET['p']) : 1;
        $where = " WHERE pid = " . $id;
        $sql = "select count(*) as total from " . table('activities_20170410_vote') . $where." GROUP BY `ip`";
        $total = $db->getone($sql);
        $page_arr['total'] = ceil($total['total'] / $limit);
        $page = ($page > $page_arr['total']) ? $page_arr['total'] : $page;
        $offset = $page > 1 ? ($page - 1) * $limit : 0;
        $page_arr['cur_page'] = $page;
        $page_arr['per_page'] = ($page - 1) < 1 ? 1 : intval($page - 1);
        $page_arr['next_page'] = ($page + 1) > $page_arr['total'] ? $page_arr['total'] : intval($page + 1);
        $i = ($page - 2) < 1 ? 1 : ($page - 2);
        $i = $i > $page_arr['total'] ? $page_arr['total'] : $i;
        $end = ($page + 3) > $page_arr['total'] ? intval($page_arr['total']) + 1 : ($page + 3);
        for ($i = $page - 2; $i < $end; $i++) {
            $i = $i < 1 ? 1 : $i;
            $i = $i > $page_arr['total'] ? $page_arr['total'] : $i;
            $page_arr['page_arr'][] = $i;
        }
        
        $sql = "select  ip,count(*) as ip_vote from " . table('activities_20170410_vote') . $where . " ORDER BY ip LIMIT " . $offset . "," . $limit;
        $ilist = $db->getall($sql);
        foreach ($ilist as $ilist) {
            $data_list[] = $ilist;
        }
    }
    $smarty->assign('id', $id);
    $smarty->assign('data_list', $data_list);
    $smarty->assign('page_arr', $page_arr);
    $smarty->display('activities/vote_list.htm');
}
?>