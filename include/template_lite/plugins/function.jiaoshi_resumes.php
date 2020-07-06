<?php

function tpl_function_jiaoshi_resumes($params, &$smarty) {
    global $db, $_CFG;
    $arrset = explode(',', $params['set']);
    $wheresql = '';
    foreach ($arrset as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "显示数目":
                $aset['row'] = $a[1];
                break;
            case "排序":
                $aset['displayorder'] = $a[1];
                break;
            case "后缀":
                $aset['suffix'] = $a[1];
                break;
        }
    }
    $limit_num = 10;
    if (isset($aset['row']) && !empty($aset['row'])) {
        $limit_num = $aset['row'];
    }
    $order_sql = " order by addtime desc";
    if (isset($aset['displayorder']) && !empty($aset['displayorder'])) {
        $order_sql = " order by " . $aset['displayorder'];
    }
    $resumes = array();
    $sql = "select * from " . table('resume') . " where uid in (select uid from " . table('resume_education') . ") and audit = 1" . $where_sql . $order_sql . " limit 0," . $limit_num . ";";
    $new_resume_arr = $db->getall($sql);
    foreach ($new_resume_arr as $nr) {
        if (isset($aset['suffix'])) {
            $nr['fullname'] = substr($nr['fullname'], 0, 2) . $aset['suffix'];
        }
        $sql = "select school from " . table('resume_education') . " where uid = " . $nr['uid'] . " order by id desc limit 1";
        $school_res = $db->getone($sql);
        $nr['age'] = date("Y") - $nr['birthdate'];
        $nr['education'] = $school_res;
        $nr['addtime'] = date("m-d", $nr['addtime']);
        array_push($resumes, $nr);
    }
    $smarty->assign('resumes', $resumes);
}

?>