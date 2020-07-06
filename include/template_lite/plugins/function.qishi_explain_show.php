<?php

function tpl_function_qishi_explain_show($params, &$smarty) {
    global $db;
    $arr = explode(',', $params['set']);
    foreach ($arr as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "说明页ID":
                $aset['id'] = $a[1];
                break;
            case "列表名":
                $aset['listname'] = $a[1];
                break;
        }
    }
    $aset = array_map("get_smarty_request", $aset);
    $aset['listname'] = $aset['listname'] ? $aset['listname'] : "list";
    $sql = "select * from " . table('explain') . " WHERE  id=" . intval($aset['id']) . " LIMIT 0 , 1";
    $val = $db->getone($sql);
    if (empty($val) || (intval($_GET['subsite_id']) != $val['subsite_id'])) {
        header("HTTP/1.1 404 Not Found");
        $smarty->display("404.htm");
        exit();
    }
    if ($val['seo_keywords'] == "") {
        $val['keywords'] = $val['title'];
    } else {
        $val['keywords'] = $val['seo_keywords'];
    }
    if ($val['seo_description'] == "") {
        $val['description'] = cut_str(strip_tags($val['content']), 60, 0, "");
    } else {
        $val['description'] = $val['seo_description'];
    }
    $smarty->assign($aset['listname'], $val);
    if ($aset['id'] == 8) {
        $sql = "select * from " . table("ad_category") . " order by category_order desc";
        $adv_list = $db->getall($sql);
        $smarty->assign('adv_list', $adv_list);
        $sql = "select * from " . table("jiaoshi_setmeal") . " order by setmeal_order desc";
        $setmeal_list = $db->getall($sql);
        $setmeal_list_tmp1 = array();
        $setmeal_list_tmp2 = array();
        foreach ($setmeal_list as $setmeal) {
            $sql = "select * from " . table("jiaoshi_setmeal_activity") . " where setmeal_id = {$setmeal['id']} order by activity_order desc";
            $activities = $db->getall($sql);
            $i = 1;
            $activities_tmp = array();
            foreach ($activities as $activity) {
                $activity['index'] = $i++;
                $activities_tmp[] = $activity;
            }
            $setmeal['activities'] = $activities_tmp;
            if ($setmeal['days'] == 0) {
                $setmeal_list_tmp1[] = $setmeal;
            } else {
                $setmeal_list_tmp2[] = $setmeal;
            }
        }
        $smarty->assign('setmeal_list1', $setmeal_list_tmp1);
        $smarty->assign('setmeal_list2', $setmeal_list_tmp2);
    }
}

?>