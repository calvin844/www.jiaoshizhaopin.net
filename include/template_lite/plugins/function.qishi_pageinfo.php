<?php

function tpl_function_qishi_pageinfo($params, &$smarty) {
    global $db, $_CFG;
    $arr = explode(',', $params['set']);
    foreach ($arr as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "调用":
                $aset['alias'] = $a[1];
                break;
            case "列表名":
                $aset['listname'] = $a[1];
                break;
            case "分类ID":
                $aset['id'] = $a[1];
                break;
        }
    }
    if (is_array($aset)) {
        $aset = array_map("get_smarty_request", $aset);
    }
    $aset['alias'] = $aset['alias'] ? $aset['alias'] : "QS_index";
    $aset['listname'] = $aset['listname'] ? $aset['listname'] : "list";
    if ($alias == "QS_newslist" && $aset['id']) {
        $sql = "select title,description,keywords from " . table('article_category') . " where id = " . intval($aset['id']) . " LIMIT  1";
        $info = $db->getone($sql);
    } else {
        $sql = "select title,description,keywords from " . table('page') . " where alias = '" . $aset['alias'] . "'  LIMIT  1";
        $info = $db->getone($sql);
    }
    $uid = $_SESSION['uid'] > 0 ? intval($_SESSION['uid']) : 0;
    $sql = "SELECT * FROM " . table('members') . " WHERE uid =" . $uid;
    $user = $db->getone($sql);
    $info['title'] = str_replace('{domain}', $_CFG['site_domain'], $info['title']);
    $info['title'] = str_replace('{sitename}', $_CFG['site_name'], $info['title']);
    $info['title'] = str_replace('{district}', $_CFG['subsite_districtname'], $info['title']);
    $info['description'] = str_replace('{domain}', $_CFG['site_domain'], $info['description']);
    $info['description'] = str_replace('{sitename}', $_CFG['site_name'], $info['description']);
    $info['description'] = str_replace('{district}', $_CFG['subsite_districtname'], $info['description']);
    $info['keywords'] = str_replace('{domain}', $_CFG['site_domain'], $info['keywords']);
    $info['keywords'] = str_replace('{sitename}', $_CFG['site_name'], $info['keywords']);
    $info['keywords'] = str_replace('{district}', $_CFG['subsite_districtname'], $info['keywords']);
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_favorites') . " WHERE personal_uid=" . $uid;
    $total_sql2 = "SELECT COUNT(*) AS num FROM " . table('personal_favorite_articles') . " WHERE personal_uid=" . $uid;
    $favorites_total = $db->get_total($total_sql);
    $favorites_total += $db->get_total($total_sql2);
    $smarty->assign('favorites_total', $favorites_total);
    $id_arr = array();
    $id_str = "";
    $total = 0;
    $personal_resume = $db->getall("select id from " . table('resume') . " where uid=" . $uid);
    if ($personal_resume) {
        foreach ($personal_resume as $key => $value) {
            $id_arr[] = $value["id"];
        }
        $id_str = implode(",", $id_arr);
        $attention_total = $db->get_total("select count(*) as num from " . table("view_resume") . " where resumeid in (" . $id_str . ")");
    }

    $url = "https://m.qingkao.net/ov2/get_component_login_code_data?sales_id=86&access_token=gSJH9WUQOX8ra8CuXtjeU7Qgn3R1I";
    $result = file_get_contents($url);
    $login_data = json_decode($result);
    $smarty->assign('code_img', $login_data->qrcode);
    $smarty->assign('login_code', $login_data->login_code);
    

    $smarty->assign('count_attention_me', $attention_total);
    $wheresql = " WHERE  resume_uid='{$uid}' ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('company_interview') . " {$wheresql}";
    $count_interview = $db->get_total($total_sql);
    $smarty->assign('count_interview', $count_interview);
    $wheresql = " WHERE personal_uid='{$_SESSION['uid']}' ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . $wheresql;
    $total_sql2 = "SELECT COUNT(*) AS num FROM " . table('jiaoshi_article_apply') . $wheresql;
    $t1 = $db->get_total($total_sql);
    $t2 = $db->get_total($total_sql2);
    $count_personal_jobs_apply = $t1 + $t2;
    $smarty->assign('count_apply', $count_personal_jobs_apply);
    $smarty->assign($aset['listname'], $info);
    $smarty->assign('user', $user);
}

?>