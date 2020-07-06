<?php

function tpl_function_jiaoshi_districts($params, &$smarty) {
    global $db, $_CFG;
    $arrset = explode(',', $params['set']);
    $wheresql = '';
    $limit = '';
    $list = 'list';
    foreach ($arrset as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "列表名":
                $aset['list'] = $a[1];
                break;
            case "数目":
                $aset['num'] = $a[1];
                break;
            case "热度排序":
                $aset['is_hot'] = $a[1];
                break;
            case "后台排序":
                $aset['category_order'] = $a[1];
                break;
            case "是否显示不限":
                $aset['no_limit'] = $a[1];
                break;
            case "类别":
                $aset['type'] = $a[1];
                break;
            case "父地区id":
                $aset['parentid'] = $a[1];
                break;
            case "屏蔽":
                $aset['shield'] = $a[1];
                break;
            case "不显示":
                $aset['no_display'] = $a[1];
                break;
        }
    }
    if (isset($aset['no_limit'])) {
        $wheresql .= " where categoryname <> '不限' ";
    }

    if (isset($aset['type'])) {
        if ($wheresql == '') {
            $wheresql .=" where ";
        } else {
            $wheresql .= " and ";
        }
        if ($aset['type'] == "省份") {
            $wheresql .= " parentid = 0";
        } elseif ($aset['type'] == "城市") {
            if (isset($aset['parentid'])) {
                $wheresql .= " parentid = " . $aset['parentid'];
            } else {
                $wheresql .= " parentid > 0";
            }
        }
    }
    if (isset($aset['shield']) || isset($aset['shield'])) {
        $wheresql .= " and " . $aset['shield'] . " > 0";
    }
    if (isset($aset['no_display'])) {
        $no_display_arr = explode("|", $aset['no_display']);
        if (is_array($no_display_arr)) {
            foreach ($no_display_arr as $n) {
                $wheresql .= " and categoryname not like '" . $n . "' ";
            }
        } else {
            $wheresql .= " and categoryname not like '" . $aset['no_display'] . "' ";
        }
    }
    if (isset($aset['is_hot']) || isset($aset['category_order'])) {
        $wheresql .= " order by ";
        if (isset($aset['is_hot'])) {
            $wheresql .= "is_hot " . $aset['is_hot'] . ",";
        }
        if (isset($aset['category_order'])) {
            $wheresql .= "category_order " . $aset['category_order'] . ",";
        }
    }

    if (isset($aset['list'])) {
        $list = $aset['list'];
    }
    $wheresql = trim($wheresql, ",");
    if (isset($aset['num'])) {
        $limit = " limit " . $aset['num'];
    }
    $sql = "select * from " . table('category_district') . $wheresql . $limit;
    $city_res = get_mem_cache($sql, "getall");
    foreach ($city_res as $c) {
        if ($c['parentid'] != 0) {
            $sql = "select pinyin,categoryname from " . table('category_district') . " where id =" . $c['parentid'];
            $parent_pinyin = get_mem_cache($sql, "getone");
            $c['parent_pinyin'] = $parent_pinyin['pinyin'];
            $c['parent_name'] = $parent_pinyin['categoryname'];
        } else {
            $c['parent_pinyin'] = "";
            $c['parent_name'] = "";
        }
        $city_arr[] = $c;
    }
    $smarty->assign($list, $city_arr);
}

?>