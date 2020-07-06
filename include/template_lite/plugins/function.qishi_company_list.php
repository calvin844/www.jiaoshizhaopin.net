<?php

function tpl_function_qishi_company_list($params, &$smarty) {
    global $db, $_CFG;
    $arrset = explode(',', $params['set']);
    foreach ($arrset as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "�б���":
                $aset['listname'] = $a[1];
                break;
            case "��ʾ��Ŀ":
                $aset['row'] = $a[1];
                break;
            case "��ʼλ��":
                $aset['start'] = $a[1];
                break;
            case "��ҵ������":
                $aset['companynamelen'] = $a[1];
                break;
            case "��������":
                $aset['brieflylen'] = $a[1];
                break;
            case "��ַ�":
                $aset['dot'] = $a[1];
                break;
            case "��ҵ":
                $aset['trade'] = $a[1];
                break;
            case "��ҳ":
                $aset['yellowpages'] = $a[1];
                break;
            case "����":
                $aset['displayorder'] = $a[1];
                break;
            case "��ҳ��ʾ":
                $aset['paged'] = $a[1];
                break;
            case "��˾ҳ��":
                $aset['companyshow'] = $a[1];
                break;
            case "�б�ҳ":
                $aset['listpage'] = $a[1];
                break;
            case "ʡ��":
                $aset['parent_py'] = $a[1];
                break;
            case "����":
                $aset['district'] = $a[1];
                break;
            case "��֤":
                $aset['audit'] = $a[1];
                break;
            case "�ؼ���":
                $aset['keyword'] = $a[1];
                break;
            case "���а�":
                $aset['billboard'] = $a[1];
                break;
            case "ְλ��":
                $aset['jobs_num'] = $a[1];
                break;
            case "��Ա":
                $aset['member_level'] = $a[1];
                break;
            case "����":
                $aset['nature'] = $a[1];
                break;
        }
    }

    if (is_array($aset)) {
        $aset = array_map("get_smarty_request", $aset);
    }
    $aset['listname'] = isset($aset['listname']) ? $aset['listname'] : "list";
    $aset['row'] = !empty($aset['row']) ? intval($aset['row']) : 10;
    $aset['start'] = isset($aset['start']) ? intval($aset['start']) : 0;
    $aset['companynamelen'] = isset($aset['companynamelen']) ? intval($aset['companynamelen']) : 16;
    $aset['dot'] = isset($aset['dot']) ? $aset['dot'] : '';
    $aset['companyshow'] = isset($aset['companyshow']) ? $aset['companyshow'] : 'QS_companyshow';
    $aset['listpage'] = !empty($aset['listpage']) ? $aset['listpage'] : 'QS_companylist';
    $aset['jobs_num'] = $aset['jobs_num'] > -1 ? intval($aset['jobs_num']) : -1;
    $aset['member_level'] = isset($aset['member_level']) ? intval($aset['member_level']) : 0;
    $aset['nature'] = isset($aset['nature']) ? trim($aset['nature'], ",") : "";
//$aset['district'] = isset($aset['district'])?$aset['district']:'';
    if (isset($aset['district'])) {
        $aset['district'] = $aset['district'];
    } elseif (isset($aset['parent_py'])) {
        $aset['district'] = $aset['parent_py'];
    } else {
        $aset['district'] = '';
    }
    $aset['keyword'] = isset($aset['keyword']) ? $aset['keyword'] : '';
    $aset['audit'] = isset($aset['audit']) ? $aset['audit'] : 0;
    $aset['billboard'] = isset($aset['billboard']) ? $aset['billboard'] : 0;
    if ($aset['displayorder']) {
        if (strpos($aset['displayorder'], '>')) {
            $arr = explode('>', $aset['displayorder']);
            $arr[0] = preg_match('/click|id|refreshtime/', $arr[0]) ? $arr[0] : "";
            $arr[1] = preg_match('/asc|desc/', $arr[1]) ? $arr[1] : "";
            if ($arr[0] && $arr[1]) {
                $orderbysql = " ORDER BY `" . $arr[0] . "` " . $arr[1];
            }
        }
    }
    if (isset($aset['yellowpages']) && $aset['yellowpages'] == '1') {
        $wheresql.=" AND yellowpages='1' ";
    }
    if (isset($aset['nature']) && !empty($aset['nature'])) {
        $wheresql.=" AND nature IN(" . $aset['nature'] . ")";
    }
    if (isset($aset['trade']) && intval($aset['trade']) > 0) {
        $wheresql.=" AND trade=" . intval($aset['trade']);
    }
    if (isset($aset['audit']) && intval($aset['audit']) > 0) {
        $wheresql.=" AND audit=" . intval($aset['audit']);
    }
    if (!empty($wheresql)) {
        if ($aset['jobs_num'] > -1 && intval($aset['audit']) > 0) {
            $wheresql = " WHERE (audit= 1 AND id IN( SELECT company_id from " . table('jobs') . " WHERE audit=1 )) AND " . ltrim(ltrim($wheresql), 'AND');
        } elseif ($aset['jobs_num'] > -1) {
            $wheresql = " WHERE (audit= 1 OR id IN( SELECT company_id from " . table('jobs') . " WHERE audit=1 )) AND " . ltrim(ltrim($wheresql), 'AND');
        } else {
            $wheresql = " WHERE audit= 1 AND " . ltrim(ltrim($wheresql), 'AND');
        }
        if ($aset['member_level'] > 0) {
            $wheresql .= " AND uid IN(SELECT uid from " . table('members_setmeal') . "  WHERE setmeal_id != 19) ";
        }
    }
    if (!empty($aset['district'])) {
        //lxh �ڵ������в��ҳ���Ӧ������
        $sql = "select id,parentid,categoryname,pinyin from " . table('category_district') . " where pinyin='" . $aset['district'] . "';";
        $district = get_mem_cache($sql, "getone");
        if (!empty($district)) {
            //����ǳ��У�����ҳ�ͬһʡ���µĸ������С������ʡ�ݣ�����ҳ�ȫ����ʡ�ݡ�
            if ($district['parentid'] > 0) {
                //�����ǳ���
                $sql = "select id,parentid,categoryname,pinyin from " . table('category_district') . " where id=" . $district['parentid'] . ";";
                $parent_district = $db->getone($sql);
                $smarty->assign('parent_district', $parent_district);
                $wheresql.=" AND sdistrict=" . intval($district['id']) . " ";
            } else {
                //������ʡ��
                $smarty->assign('parent_district', $district);
                $wheresql.=" AND (sdistrict=" . intval($district['id']) . " OR district=" . intval($district['id']) . ") ";
            }
            $smarty->assign('district', $district);
        }
    }
    if (isset($aset['keyword']) && !empty($aset['keyword'])) {
        $wheresql.=" AND companyname like '%" . $aset['keyword'] . "%'";
        $smarty->assign('keyword', $aset['keyword']);
    }
    if (!empty($aset['billboard'])) {
        $orderbysql = " ORDER BY refreshtime desc";
    }
    if (isset($aset['paged'])) {
        require_once(QISHI_ROOT_PATH . 'include/page.class.php');
        $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_profile') . $wheresql;
        $total_count = $db->get_total($total_sql);
        $pagelist = new page(array('total' => $total_count, 'perpage' => $aset['row'], 'alias' => $aset['listpage'], 'getarray' => $_GET));
        $currenpage = $pagelist->nowindex;
        $aset['start'] = ($currenpage - 1) * $aset['row'];
        if ($total_count > $aset['row']) {
            $smarty->assign('page', $pagelist->show(3));
        }
        $smarty->assign('total', $total_count);
    }
    if (!empty($aset['billboard'])) {
        $limit = " LIMIT 0, 50";
    } else {
        $limit = " LIMIT " . abs($aset['start']) . ',' . $aset['row'];
    }
    $sql = "SELECT * FROM " . table('company_profile') . " " . $wheresql . $orderbysql . $limit;
    $result = get_mem_cache($sql, "getall");
    $list = array();
    $i = 0;
    foreach ($result as $row) {
        $row['companyname_'] = $row['companyname'];
        $row['companyname'] = cut_str($row['companyname'], $aset['companynamelen'], 0, $aset['dot']);
        $row['url'] = url_rewrite($aset['companyshow'], array('id' => $row['id']));
        $row['contents'] = str_replace('&nbsp;', '', $row['contents']);
        $row['briefly_'] = strip_tags($row['contents']);
        $row['briefly'] = strip_tags($row['briefly_']);
        $row['index'] = $i++;
        if ($aset['brieflylen'] > 0) {
            $row['briefly'] = cut_str(strip_tags($row['contents']), $aset['brieflylen'], 0, $aset['dot']);
        }
        if ($row['logo']) {
            $row['logo'] = $_CFG['main_domain'] . "data/logo/" . $row['logo'];
        } else {
            $row['logo'] = $_CFG['main_domain'] . "data/logo/no_logo.gif";
        }
        $row['jobs_num'] = $db->get_total("select count(*) as num from " . table('jobs') . " where company_id=" . $row['id'] . " and audit=1 ");
        $jobs = $db->getall("select * from " . table('jobs') . " where company_id=" . $row['id'] . " and audit=1 order by click desc");
        $i = 0;
        foreach ($jobs as $job) {
            $job['index'] = $i++;
            $row['jobs'][] = $job;
        }
        $row['comment_num'] = $db->get_total("select count(*) as num from " . table('comment') . " where company_id=" . $row['id'] . " and audit=1");
        if (!empty($aset['billboard'])) {
            //��������а�������Ҫ���Ӱ������������ְλ��click+ѧУ��click
            $ranking = $row['click'] / 2;
            foreach ($jobs as $job) {
                $ranking += $job['click'];
            }
            $row['ranking'] = $ranking;
        }
        $list[] = $row;
    }
    if (!empty($aset['billboard'])) {
        $temp = my_sort($list, 'ranking', SORT_DESC);
        $temp2 = array();
        $i = 0;
        foreach ($temp as $company) {
            if ($i < $aset['row']) {
                $company['index'] = $i++;
                $temp2[] = $company;
            } else {
                break;
            }
        }
        $smarty->assign($aset['listname'], $temp2);
    } else {
        $smarty->assign($aset['listname'], $list);
    }
}

?>