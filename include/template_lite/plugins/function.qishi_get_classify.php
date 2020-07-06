<?php

function tpl_function_qishi_get_classify($params, &$smarty) {
    global $db;
    $_CAT = get_cache('category');
    $arr = explode(',', $params['set']);
    foreach ($arr as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "列表名":
                $aset['listname'] = $a[1];
                break;
            case "类型":
                $aset['act'] = $a[1];
                break;
            case "显示数目":
                $aset['row'] = $a[1];
                break;
            case "名称长度":
                $aset['titlelen'] = $a[1];
                break;
            case "填补字符":
                $aset['dot'] = $a[1];
                break;
            case "id":
                $aset['id'] = $a[1];
                break;
        }
    }
    if (is_array($aset)) {
        $aset = array_map("get_smarty_request", $aset);
    }
    $aset['listname'] = isset($aset['listname']) ? $aset['listname'] : "list";
    $aset['titlelen'] = isset($aset['titlelen']) ? intval($aset['titlelen']) : 18;
    $act = $aset['act'];
    $aset['dot'] = isset($aset['dot']) ? $aset['dot'] : null;
    if (intval($aset['row']) > 0) {
        $limit = " LIMIT " . intval($aset['row']);
    }
    $list = array();
    if ($act == "QS_jobs") {
        $id_where = $aset['id'] ? " where parentid=" . intval($aset['id']) : "";
        $result = $db->query("SELECT * FROM " . table('category_jobs') . $id_where . " ORDER BY category_order desc,id asc" . $limit);
        while ($row = $db->fetch_array($result)) {
            $row['categoryname'] = cut_str($row['categoryname'], $aset['titlelen'], 0, $aset['dot']);
            $row['title'] = cut_str($row['categoryname'], $aset['titlelen'], 0, $aset['dot']);
            $row['jobcategory'] = $row['parentid'] == '0' ? $row['id'] . ".0.0" : $row['parentid'] . "." . $row['id'] . ".0";
            $row['url_code'] = urlencode($row['categoryname']);
            $type_result = $db->query("SELECT * FROM " . table('category_jobs') . " WHERE parentid =" . $row['id'] . "  ORDER BY category_order desc,id asc");
            while ($type_row = $db->fetch_array($type_result)) {
                $type_row['categoryname'] = cut_str($type_row['categoryname'], $aset['titlelen'], 0, $aset['dot']);
                $type_row['title'] = cut_str($type_row['categoryname'], $aset['titlelen'], 0, $aset['dot']);
                $type_row['jobcategory'] = $type_row['parentid'] == '0' ? $type_row['id'] . ".0.0" : $type_row['parentid'] . "." . $type_row['id'] . ".0";
                $type_row['url_code'] = urlencode($type_row['categoryname']);
                $row['son'][] = $type_row;
            }
            $list[] = $row;
        }
    }
    if ($act == "hot_category") {
        $sql = "SELECT count(*) as num ,subclass FROM " . table("jiaoshi_article_jobs_index") . " where subclass > 0 group by subclass";
        $hot_category_res = get_mem_cache($sql, "getall");
        foreach ($hot_category_res as $key => $value) {
            $num[$key] = $value['num'];
        }
        array_multisort($num, SORT_DESC, $hot_category_res);
        $hot_category_res = array_chunk($hot_category_res, 10);
        foreach ($hot_category_res[0] as $h) {
            $sql = "SELECT parentid,categoryname FROM " . table("category_jobs") . " where id = " . $h['subclass'] . " limit 1";
            $categoryname = get_mem_cache($sql, "getone");
            if ($categoryname['parentid'] > 0) {
                $sql = "SELECT categoryname FROM " . table("category_jobs") . " where id = " . $categoryname['parentid'] . " limit 1";
                $parent = get_mem_cache($sql, "getone");
                $h['parent_cn'] = $parent['categoryname'];
            }
            $h['categoryname'] = $categoryname['categoryname'];
            $h['parentid'] = $categoryname['parentid'];
            $list[] = $h;
        }
    }
//高级职位
    if ($act == "QS_hunter_jobs") {
        $id = intval($aset['id']);
        $result = $db->query("SELECT * FROM " . table('category_hunterjobs') . " where parentid=" . $id . " ORDER BY category_order desc,id asc" . $limit);
        while ($row = $db->fetch_array($result)) {
            $row['categoryname'] = cut_str($row['categoryname'], $aset['titlelen'], 0, $aset['dot']);
            $row['title'] = cut_str($row['categoryname'], $aset['titlelen'], 0, $aset['dot']);
            $row['jobcategory'] = $row['parentid'] == '0' ? $row['id'] . ".0" : $row['parentid'] . "." . $row['id'];
            $list[] = $row;
        }
    }
// 楼层
    elseif ($act == "QS_jobs_floor") {
        $id = trim($aset['id']);
        $id = str_replace("_", ",", $id);
        $result = $db->query("SELECT * FROM " . table('category_jobs') . " where parentid in (" . $id . ") ORDER BY category_order desc,id asc" . $limit);
        while ($row = $db->fetch_array($result)) {
            $row['categoryname'] = cut_str($row['categoryname'], $aset['titlelen'], 0, $aset['dot']);
            $row['title'] = cut_str($row['categoryname'], $aset['titlelen'], 0, $aset['dot']);
            $row['jobcategory'] = $row['parentid'] == '0' ? $row['id'] . ".0" : $row['parentid'] . "." . $row['id'];
            $list[] = $row;
        }
    }
// 地图
    elseif ($act == "QS_map") {
        $sql = "SELECT * FROM " . table('category_jobs');
        $result = get_mem_cache($sql, "getall");
        foreach ($result as $row) {
            if ($row['parentid'] == '0') {
                $list[$row['id']]['id'] = $row['id'];
                $list[$row['id']]['name'] = $row['categoryname'];
            } else {
                $list[$row['parentid']]['son'][$row['id']]['id'] = $row['id'];
                $list[$row['parentid']]['son'][$row['id']]['parentid'] = $row['parentid'];
                $list[$row['parentid']]['son'][$row['id']]['name'] = $row['categoryname'];
            }
        }
    } elseif ($act == "QS_jobs_parent") {
        if (strpos($aset['id'], "-")) {
            $arr = explode("-", $aset['id']);
            $idstr = implode(",", $arr);
            if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $idstr))
                exit("err");
        }
        else {
            $idstr = intval($aset['id']);
        }
        if ($idstr == "0") {
            $list = "";
        } else {
            $result = $db->query("SELECT parentid FROM " . table('category_jobs') . " where id IN (" . $idstr . ")" . $limit);
            while ($row = $db->fetch_array($result)) {
                $list[] = $row['parentid'];
            }
            $list = array_unique($list);
            $list = implode(",", $list);
        }
    } elseif ($act == "QS_district") {

        if (isset($aset['id'])) {
            $wheresql = " WHERE parentid=" . intval($aset['id']) . " ";
        }
        $sql = "select * from " . table('category_district') . " " . $wheresql . "  ORDER BY category_order desc,id asc" . $limit;
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result)) {
            $row['categoryname'] = cut_str($row['categoryname'], $aset['titlelen'], 0, $aset['dot']);
            $row['title'] = cut_str($row['categoryname'], $aset['titlelen'], 0, $aset['dot']);
            $row['citycategory'] = $row['parentid'] == '0' ? $row['id'] . ".0" : $row['parentid'] . "." . $row['id'];
            $list[] = $row;
        }
    } elseif ($act == "QS_district_parent") {
        if (strpos($aset['id'], "-")) {
            $arr = explode("-", $aset['id']);
            $idstr = implode(",", $arr);
            if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $idstr))
                exit("err");
        }
        else {
            $idstr = intval($aset['id']);
        }
        if ($idstr == "0") {
            $list = "";
        } else {
            $result = $db->query("SELECT parentid FROM " . table('category_district') . " where id IN (" . $idstr . ")" . $limit);
            while ($row = $db->fetch_array($result)) {
                $list[] = $row['parentid'];
            }
            $list = array_unique($list);
            $list = implode(",", $list);
        }
    } elseif ($act == "QS_street") {
        $wheresql = " WHERE c_alias='QS_street' ";
        $sql = "select * from " . table('category') . " {$wheresql} ORDER BY c_order desc,c_id asc {$limit}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result)) {
            $row['categoryname'] = cut_str($row['c_name'], $aset['titlelen'], 0, $aset['dot']);
            $list[] = $row;
        }
    } else {
        if (!empty($_CAT[$act])) {
            foreach ($_CAT[$act] as $cat) {
                $cat['categoryname'] = cut_str($cat['categoryname'], $aset['titlelen'], 0, $aset['dot']);
                $list[] = $cat;
            }
            if (intval($aset['row']) > 0) {
                $list = array_slice($list, 0, intval($aset['row']));
            }
        } else {
            $wheresql = " WHERE c_alias='" . $act . "' ";
            $sql = "select * from " . table('category') . " {$wheresql} ORDER BY c_order desc,c_id asc {$limit}";
            $result = $db->query($sql);
            while ($row = $db->fetch_array($result)) {
                $row['categoryname'] = cut_str($row['c_name'], $aset['titlelen'], 0, $aset['dot']);
                $list[] = $row;
            }
        }
    }
    $smarty->assign($aset['listname'], $list);
}

?>