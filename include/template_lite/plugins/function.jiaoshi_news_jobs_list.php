<?php

function tpl_function_jiaoshi_news_jobs_list($params, &$smarty) {
    global $db, $_CFG;
    $arrset = explode(',', $params['set']);
    foreach ($arrset as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "地区":
                $aset['district'] = $a[1];
                break;
            case "地区拼音":
                $aset['district_pinyin'] = $a[1];
                break;
            case "类别":
                $aset['category'] = $a[1];
                break;
            case "开始位置":
                $aset['start'] = $a[1];
                break;
            case "显示数目":
                $aset['row'] = $a[1];
                break;
            case "分页显示":
                $aset['paged'] = $a[1];
                break;
            case "列表页":
                $aset['listpage'] = $a[1];
                break;
            case "排序":
                $aset['displayorder'] = $a[1];
                break;
            case "单位":
                $aset['unit'] = $a[1];
                break;
            case "属性":
                $aset['type'] = $a[1];
                break;
            case "时间格式":
                $aset['time_format'] = $a[1];
                break;
        }
    }
    $d_wheresql = "";
    if (isset($aset['district']) && !empty($aset['district'])) {
        $sql = "select id,parentid,categoryname from " . table('category_district') . " where id='" . $aset['district'] . "';";
        $district = get_mem_cache($sql, "getone");
        if (empty($district['parentid'])) {
            $d_wheresql = ' AND (sdistrict=' . $district['id'] . ' OR district=' . $district['id'] . ')';
        } else {
            $d_wheresql = ' AND sdistrict=' . $district['id'];
        }
    }
    $wheresql = "where audit = 1 and display = 1 and deadline > " . time();
    $wheresql_n = $wheresql . $d_wheresql;
    $sql = "SELECT * FROM " . table('jobs') . " " . $wheresql_n . " GROUP BY company_id  LIMIT " . $aset['row'];
    $result_tmp = get_mem_cache($sql, "getall");
    while (count($result_tmp) < $aset['row']) {
        $rid_str = "";
        foreach ($result_tmp as $r) {
            $rid_str .= $r['company_id'] . ",";
        }
        $rid_str = !empty($rid_str) ? " AND company_id NOT IN(" . trim($rid_str, ",") . ")" : "";
        if ($district['parentid'] > 0) {
            $d_wheresql_bak = " AND district = " . intval($district['parentid']) . " ";
            $district['parentid'] = 0;
        } else {
            $d_wheresql_bak = "";
        }
        $sql = "SELECT * FROM " . table('jobs') . " " . $wheresql . $d_wheresql_bak . $rid_str." GROUP BY company_id  LIMIT " . ($aset['row'] - count($result_tmp));
        $result_tmp2 = get_mem_cache($sql, "getall");
        if (!empty($result_tmp)) {
            $result_tmp = array_merge($result_tmp, $result_tmp2);
        } else {
            $result_tmp = $result_tmp2;
        }
    }
    foreach ($result_tmp as $rt) {
        $sql = "SELECT * FROM " . table('company_profile') . " WHERE id = ".$rt['company_id'];
        $company = $db->getone($sql);
        $sql = "SELECT * FROM " . table('members_setmeal') . " WHERE uid = " . $company['uid'] . " limit 1";
        $rt_setmeal = $db->getone($sql);
        $rt['setmeal_days'] = $rt_setmeal['days'];
        if ($rt['setmeal_days'] > 0) {
            $result1[] = $rt;
        } else {
            $result2[] = $rt;
        }
    }
    $result1 = my_sort($result1, 'setmeal_days', SORT_DESC);
    $result2 = my_sort($result2, 'addtime', SORT_DESC);
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
    foreach ($result as $j) {
            if (strpos($j['district_cn'], "/")) {
                $j['district_cn'] = explode("/", $j['district_cn']);
                $j['district_cn'] = $j['district_cn'][1];
            }
            $j['district_cn'] = $j['district_cn'];
            
            if ($j['district'] > 0) {
                $district_py_sql = "select pinyin from " . table("category_district") . " where id = " . $j['district'];
                $district_py = get_mem_cache($district_py_sql, "getone");
                $district_py = $district_py['pinyin'];
            } else {
                $district_py = "";
            }
            if ($j['sdistrict'] > 0) {
                $sdistrict_py_sql = "select pinyin from " . table("category_district") . " where id = " . $j['sdistrict'];
                $sdistrict_py = get_mem_cache($sdistrict_py_sql, "getone");
                $sdistrict_py = $sdistrict_py['pinyin'];
            } else {
                $sdistrict_py = "";
            }
            $j['city_pinyin'] = $sdistrict_py == "" ? "/" . $district_py : "/" . $district_py . "/" . $sdistrict_py;
                    
            $outtime = $j['deadline'] - time();
            if ($outtime >= 0) {
                $j['deadline_day'] = ceil($outtime / 86400);
            }
            if (isset($aset['time_format'])) {
                $j['addtime'] = date($aset['time_format'], $j['addtime']);
                $j['deadline'] = date($aset['time_format'], $j['deadline']);
                $j['refreshtime'] = date($aset['time_format'], $j['refreshtime']);
            }
            $jobs_arr[] = $j;
    }
    $smarty->assign('jobs', $jobs_arr);
}

?>