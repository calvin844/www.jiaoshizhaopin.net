<?php

function tpl_function_jiaoshi_news_company_list($params, &$smarty) {
    global $db, $_CFG;
    $arrset = explode(',', $params['set']);
    foreach ($arrset as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "列表名":
                $aset['listname'] = $a[1];
                break;
            case "显示数目":
                $aset['row'] = $a[1];
                break;
            case "企业名长度":
                $aset['companynamelen'] = $a[1];
                break;
            case "描述长度":
                $aset['brieflylen'] = $a[1];
                break;
            case "公司页面":
                $aset['companyshow'] = $a[1];
                break;
            case "省份":
                $aset['parent_py'] = $a[1];
                break;
            case "地区":
                $aset['district'] = $a[1];
                break;
            case "认证":
                $aset['audit'] = $a[1];
                break;
            case "职位数":
                $aset['jobs_num'] = $a[1];
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
    $aset['companyshow'] = isset($aset['companyshow']) ? $aset['companyshow'] : 'QS_companyshow';
    $aset['jobs_num'] = $aset['jobs_num'] > -1 ? intval($aset['jobs_num']) : -1;
    if (isset($aset['district'])) {
        $aset['district'] = $aset['district'];
    } elseif (isset($aset['parent_py'])) {
        $aset['district'] = $aset['parent_py'];
    } else {
        $aset['district'] = '';
    }
    $aset['audit'] = isset($aset['audit']) ? $aset['audit'] : 0;
    if (isset($aset['audit']) && intval($aset['audit']) > 0) {
        $wheresql.=" AND audit=" . intval($aset['audit']);
    }
    if (!empty($wheresql)) {
        if ($aset['jobs_num'] > -1 && intval($aset['audit']) > 0) {
            $wheresql = " WHERE (audit= 1 AND id IN( SELECT company_id from " . table('jobs') . " WHERE audit=1 )) AND " . ltrim(ltrim($wheresql), 'AND');
        } elseif ($aset['jobs_num'] > -1) {
            $wheresql = " WHERE (audit= 1 OR id IN( SELECT company_id from " . table('jobs') . " WHERE audit=1 )) AND " . ltrim(ltrim($wheresql), 'AND');
        } else {
            $wheresql = " WHERE " . ltrim(ltrim($wheresql), 'AND');
        }
    } else {
        if ($aset['jobs_num'] > -1 && intval($aset['audit']) > 0) {
            $wheresql = " WHERE (audit= 1 AND id IN( SELECT company_id from " . table('jobs') . " WHERE audit=1 )) ";
        } elseif ($aset['jobs_num'] > -1) {
            $wheresql = " WHERE (audit= 1 OR id IN( SELECT company_id from " . table('jobs') . " WHERE audit=1 )) ";
        } else {
            $wheresql = " WHERE 1 ";
        }
    }
    $d_wheresql = "";
    if (!empty($aset['district'])) {
        //lxh 在地区表中查找出相应的数据
        $sql = "select id,parentid,categoryname,pinyin from " . table('category_district') . " where pinyin='" . $aset['district'] . "';";
        $district = get_mem_cache($sql, "getone");
        if (!empty($district)) {
            //如果是城市，则查找出同一省份下的各个城市。如果是省份，则查找出全国的省份。
            if ($district['parentid'] > 0) {
                //这里是城市
                $sql = "select id,parentid,categoryname,pinyin from " . table('category_district') . " where id=" . $district['parentid'] . ";";
                $parent_district = $db->getone($sql);
                $smarty->assign('parent_district', $parent_district);
                $d_wheresql = " AND sdistrict=" . intval($district['id']) . " ";
            } else {
                //这里是省份
                $smarty->assign('parent_district', $district);
                $d_wheresql = " AND (sdistrict=" . intval($district['id']) . " OR district=" . intval($district['id']) . ") ";
            }
            $smarty->assign('district', $district);
        }
    }
    $wheresql_n = $wheresql . $d_wheresql;
    $sql = "SELECT * FROM " . table('company_profile') . " " . $wheresql_n . " LIMIT " . $aset['row'];
    $result_tmp = get_mem_cache($sql, "getall");
    while (count($result_tmp) < $aset['row']) {
        $rid_str = "";
        foreach ($result_tmp as $r) {
            $rid_str .= $r['id'] . ",";
        }
        $rid_str = !empty($rid_str) ? " AND id NOT IN(" . trim($rid_str, ",") . ")" : "";
        if ($district['parentid'] > 0) {
            $d_wheresql_bak = " AND district = " . intval($district['parentid']) . " ";
            $district['parentid'] = 0;
        } else {
            $d_wheresql_bak = "";
        }
        $sql = "SELECT * FROM " . table('company_profile') . " " . $wheresql . $d_wheresql_bak . $rid_str . " LIMIT " . ($aset['row'] - count($result_tmp));
        $result_tmp2 = get_mem_cache($sql, "getall");
        if (!empty($result_tmp)) {
            $result_tmp = array_merge($result_tmp, $result_tmp2);
        } else {
            $result_tmp = $result_tmp2;
        }
    }
    foreach ($result_tmp as $rt) {
        $sql = "SELECT * FROM " . table('members_setmeal') . " WHERE uid = " . $rt['uid'] . " limit 1";
        $rt_setmeal = $db->getone($sql);
        $rt['setmeal_days'] = $rt_setmeal['days'];
        if ($rt['setmeal_days'] > 0) {
            $result1[] = $rt;
        } else {
            $result2[] = $rt;
        }
    }
    $result1 = my_sort($result1, 'setmeal_days', SORT_DESC);
    $result2 = my_sort($result2, 'click', SORT_DESC);
    if (!empty($result1) && !empty($result2)) {
        $result = array_merge($result1, $result2);
    } elseif (empty($result1) && !empty($result2)) {
        $result = $result2;
    } elseif (!empty($result1) && empty($result2)) {
        $result = $result1;
    } else {
        $result = array();
    }
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
        $row['jobs_num'] = $db->get_total("select count(*) as num from " . table('jobs') . " where company_id = " . $row['id'] . " and audit = 1 ");
        $jobs = $db->getall("select * from " . table('jobs') . " where company_id = " . $row['id'] . " and audit = 1 order by click desc");
        $i = 0;
        foreach ($jobs as $job) {
            $job['index'] = $i++;
            $row['jobs'][] = $job;
        }
        $list[] = $row;
    }
    $smarty->assign($aset['listname'], $list);
}

?>