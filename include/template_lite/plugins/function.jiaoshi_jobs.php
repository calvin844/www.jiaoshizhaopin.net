<?php

function tpl_function_jiaoshi_jobs($params, &$smarty) {
    global $db, $_CFG;
    $arrset = explode(',', $params['set']);
    $wheresql = '';
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
    $time = strtotime(date('Y-m-d', time()));
    if (isset($aset['district_pinyin']) && !empty($aset['district_pinyin'])) {
        $sql = "select id,parentid,categoryname from " . table('category_district') . " where pinyin='" . $aset['district_pinyin'] . "';";
        $district = $db->getone($sql);
    }
    if (isset($aset['district']) && !empty($aset['district'])) {
        $sql = "select id,parentid,categoryname from " . table('category_district') . " where id='" . $aset['district'] . "';";
        $district = get_mem_cache($sql, "getone");
        $smarty->assign('district', $district);
    }
    if (!empty($district)) {
        if (empty($district['parentid'])) {
            $wheresql.=' AND sdistrict=' . $district['id'] . ' OR district=' . $district['id'];
        } else {
            $wheresql.=' AND sdistrict=' . $district['id'];
        }
    }
    if (isset($aset['category']) && !empty($aset['category'])) {
        $wheresql.=' AND category=' . $aset['category'];
    }
    if (isset($aset['type']) && !empty($aset['type'])) {
        if ($aset['type'] == 'emergency') {
            $wheresql.=' AND emergency=1';
        } else if ($aset['type'] == 'recommend') {
            $wheresql.=' AND recommend=1';
        }
    }
    $start = 0;
    if (isset($aset['start']) && !empty($aset['start'])) {
        $start = $aset['start'];
    }
    $limit_num = 10;
    if (isset($aset['row']) && !empty($aset['row'])) {
        $limit_num = $aset['row'];
    }
    $order_sql = " order by addtime desc";
    if (isset($aset['displayorder']) && !empty($aset['displayorder'])) {
        $order_sql = " order by " . $aset['displayorder'];
    }
    $unit = "company";
    if (isset($aset['unit']) && !empty($aset['unit'])) {
        $unit = $aset['unit'];
    }
    if ($unit == "job") {
        $sql = "select * from " . table('jobs') . " where audit = 1 and display = 1 and deadline > " . $time . " " . $wheresql . $order_sql . " limit " . $start . "," . $limit_num . ";";
        $jobs_res = get_mem_cache($sql, "getall");
        foreach ($jobs_res as $j) {
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
    } else {
        $sql = "select * from " . table('jobs') . " where audit = 1 and display = 1" . $wheresql . $order_sql . " limit  " . $start . "," . ($limit_num * 20) . ";";
        $jobs_arr = get_mem_cache($sql, "getall");
        $companies = array();
        $school_num = 0;
        $job_num_arr = array();
        foreach ($jobs_arr as $j) {
            if ($school_num < $limit_num) {
                if (empty($companies[$j['company_id']])) {
                    $sql = "select * from " . table('promotion') . " where cp_jobid =" . $j['id'] . " and cp_endtime > " . time() . " order by cp_id limit 1";
                    $promotion_res = $db->getone($sql);
                    if ($promotion_res['cp_promotionid'] == 4) {
                        $j['color'] = $promotion_res['cp_val'];
                    }
                    $companies[$j['company_id']]['id'] = $j['company_id'];
                    $companies[$j['company_id']]['name'] = $j['companyname'];
                    $companies[$j['company_id']]['city_id'] = $j['district'] . "." . $j['sdistrict'];
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
                    $companies[$j['company_id']]['city_pinyin'] = $sdistrict_py == "" ? "/" . $district_py : "/" . $district_py . "/" . $sdistrict_py;
                    if (strpos($j['district_cn'], "/")) {
                        $j['district_cn'] = explode("/", $j['district_cn']);
                        $j['district_cn'] = $j['district_cn'][1];
                    }
                    $companies[$j['company_id']]['city'] = $j['district_cn'];
                    $companies[$j['company_id']]['jobs'] = array();
                    $companies[$j['company_id']]['index'] = $school_num;
                    $companies[$j['company_id']]['jobs_length'] = 0;
                    $school_num++;
                    $job_num_arr[$j['company_id']] = 0;
                }
                $j['index'] = $job_num_arr[$j['company_id']];
                $j['deadline'] = date("m-d", $j['deadline']);
                $companies[$j['company_id']]['jobs_length'] = ++$job_num_arr[$j['company_id']];
                array_push($companies[$j['company_id']]['jobs'], $j);
            } else {
                break;
            }
        }
        $smarty->assign('companies', $companies);
    }
}

?>