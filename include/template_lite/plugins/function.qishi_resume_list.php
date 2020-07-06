<?php

function tpl_function_qishi_resume_list($params, &$smarty) {
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
            case "筛选数目":
                $aset['screen_row'] = $a[1];
                break;
            case "开始位置":
                $aset['start'] = $a[1];
                break;
            case "姓名长度":
                $aset['namelen'] = $a[1];
                break;
            case "特长描述长度":
                $aset['specialtylen'] = $a[1];
                break;
            case "意向职位长度":
                $aset['jobslen'] = $a[1];
                break;
            case "填补字符":
                $aset['dot'] = $a[1];
                break;
            case "职位分类":
                $aset['jobcategory'] = trim($a[1]);
                break;
            case "职位大类":
                $aset['category'] = trim($a[1]);
                break;
            case "职位小类":
                $aset['subclass'] = trim($a[1]);
                break;
            case "地区分类":
                $aset['citycategory'] = trim($a[1]);
                break;
            case "地区大类":
                $aset['district'] = $a[1];
                break;
            case "地区小类":
                $aset['sdistrict'] = $a[1];
                break;
            case "行业":
                $aset['trade'] = trim($a[1]);
                break;
            case "标签":
                $aset['tag'] = $a[1];
                break;
            case "学历":
                $aset['education'] = $a[1];
                break;
            case "工作经验":
                $aset['experience'] = $a[1];
                break;
            case "等级":
                $aset['talent'] = $a[1];
                break;
            case "照片":
                $aset['photo'] = $a[1];
                break;
            case "关键字":
                $aset['key'] = $a[1];
                break;
            case "排序":
                $aset['displayorder'] = $a[1];
                break;
            case "分页显示":
                $aset['paged'] = $a[1];
                break;
            case "页面":
                $aset['showname'] = $a[1];
                break;
            case "列表页":
                $aset['listpage'] = $a[1];
                break;
            case "总数量":
                $aset['resume_num'] = $a[1];
                break;
            case "显示学校":
                $aset['show_school'] = $a[1];
                break;
            case "排行榜":
                $aset['billboard'] = $a[1];
                break;
            case "热门教师":
                $aset['hot_resume'] = $a[1];
                break;
        }
    }
    if (is_array($aset)) {
        $aset = array_map("get_smarty_request", $aset);
    }
    $aset['listname'] = isset($aset['listname']) ? $aset['listname'] : "list";
    $aset['row'] = intval($aset['row']) > 0 ? intval($aset['row']) : 10;
    if ($aset['row'] > 30) {
        //$aset['row'] = 30;
    }
    $aset['start'] = isset($aset['start']) ? intval($aset['start']) : 0;
    $aset['namelen'] = isset($aset['namelen']) ? intval($aset['namelen']) : 4;
    $aset['specialtylen'] = isset($aset['specialtylen']) ? intval($aset['specialtylen']) : 0;
    $aset['jobslen'] = isset($aset['jobslen']) ? intval($aset['jobslen']) : 0;
    $aset['dot'] = isset($aset['dot']) ? $aset['dot'] : null;
    $aset['showname'] = isset($aset['showname']) ? $aset['showname'] : 'QS_resumeshow';
    $aset['listpage'] = isset($aset['listpage']) ? $aset['listpage'] : 'QS_resumelist';
    $aset['resume_num'] = isset($aset['resume_num']) ? $aset['resume_num'] : 0;
    $aset['show_school'] = isset($aset['show_school']) ? $aset['show_school'] : 0;
    $aset['billboard'] = isset($aset['billboard']) ? $aset['billboard'] : 0;
    $resumetable = table('resume_search_rtime');
//var_dump($aset);exit;
    if (isset($aset['displayorder'])) {
        $arr = explode('>', $aset['displayorder']);
        $arr[1] = preg_match('/asc|desc/', $arr[1]) ? $arr[1] : "desc";
        if ($arr[0] == "rtime") {
            $orderbysql = " ORDER BY r.refreshtime {$arr[1]}";
        }
    }
    if (!empty($aset['category']) || !empty($aset['subclass']) || !empty($aset['jobcategory'])) {
        if (!empty($aset['jobcategory'])) {
            $dsql = $xsql = "";
            $arr = explode(",", $aset['jobcategory']);
            $arr = array_unique($arr);
            foreach ($arr as $sid) {
                $cat = explode(".", $sid);
                if (intval($cat[1]) === 0) {
                    $dsql.= " OR category =" . intval($cat[0]);
                } else {
                    $xsql.= " OR subclass =" . intval($cat[1]);
                }
            }
            $joinwheresql.=" WHERE " . ltrim(ltrim($dsql . $xsql), 'OR');
        } else {
            if (!empty($aset['category'])) {
                if (strpos($aset['category'], "-")) {
                    $or = $orsql = "";
                    $arr = explode("-", $aset['category']);
                    $sqlin = implode(",", $arr);
                    if (count($arr) > 10) {
                        exit();
                    }
                    $sqlin = implode(",", $arr);
                    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
                        $joinwheresql.=" AND topclass IN  ({$sqlin}) ";
                    }
                } else {
                    $joinwheresql.=" AND  topclass=" . intval($aset['category']);
                }
            }
            if (!empty($aset['subclass'])) {
                if (strpos($aset['subclass'], "-")) {
                    $or = $orsql = "";
                    $arr = explode("-", $aset['subclass']);
                    if (count($arr) > 10)
                        exit();
                    $sqlin = implode(",", $arr);
                    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
                        $joinwheresql.=" AND category IN  ({$sqlin}) ";
                    }
                } else {
                    $joinwheresql.=" AND category=" . intval($aset['subclass']);
                }
            }
            if (!empty($joinwheresql)) {
                $joinwheresql = " WHERE " . ltrim(ltrim($joinwheresql), 'AND');
            }
        }
        $joinsql = "  INNER  JOIN  ( SELECT DISTINCT pid FROM " . table('resume_jobs') . " {$joinwheresql} )AS j ON  r.id=j.pid ";
    }
    if (!empty($aset['trade'])) {
        if (strpos($aset['trade'], "|")) {
            $or = $orsql = "";
            $arr = explode("|", $aset['trade']);
            if (count($arr) > 10)
                exit();
            $sqlin = implode(",", $arr);
            if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
                $joinwheresql_trade.=" AND trade IN  ({$sqlin}) ";
            }
        } else {
            $joinwheresql_trade.=" AND trade=" . intval($aset['trade']);
        }

        if (!empty($joinwheresql_trade)) {
            $joinwheresql_trade = " WHERE " . ltrim(ltrim($joinwheresql_trade), 'AND');
        }
        $joinsql = $joinsql == "" ? "  INNER  JOIN  ( SELECT DISTINCT pid FROM " . table('resume_trade') . " {$joinwheresql_trade} )AS t ON  r.id=t.pid " : $joinsql . "  INNER  JOIN  ( SELECT DISTINCT pid FROM " . table('resume_trade') . " {$joinwheresql_trade} )AS t ON  r.id=t.pid ";
    }
    if (!empty($aset['citycategory'])) {
        if (strpos($aset['citycategory'], ",")) {
            $dsql = $xsql = "";
            $arr = explode(",", $aset['citycategory']);
            $arr = array_unique($arr);
            if (count($arr) > 10)
                exit();
            foreach ($arr as $sid) {
                $cat = explode(".", $sid);
                if (intval($cat[1]) === 0) {
                    $dsql.= " OR r.district =" . intval($cat[0]);
                } else {
                    $xsql.= " OR r.sdistrict =" . intval($cat[1]);
                }
            }
            $wheresql.=" AND  (" . ltrim(ltrim($dsql . $xsql), 'OR') . ") ";
        } else {
            $cat = explode(".", $aset['citycategory']);

            if (intval($cat[1]) > 0) {
                $wheresql.=" AND r.sdistrict =" . intval($cat[1]);
            } else {
                $wheresql.=" AND r.district=" . intval($cat[0]) . " ";
            }
        }
    } else {
        if (isset($aset['district']) && $aset['district'] <> '') {
            if (strpos($aset['district'], "-")) {
                $or = $orsql = "";
                $arr = explode("-", $aset['district']);
                $arr = array_unique($arr);
                if (count($arr) > 10)
                    exit();
                $sqlin = implode(",", $arr);
                if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
                    $wheresql.=" AND r.district IN  ({$sqlin}) ";
                }
            } else {
                $wheresql.=" AND r.district=" . intval($aset['district']) . " ";
            }
        }
        if (isset($aset['sdistrict']) && $aset['sdistrict'] <> '') {
            if (strpos($aset['sdistrict'], "-")) {
                $or = $orsql = "";
                $arr = explode("-", $aset['sdistrict']);
                $arr = array_unique($arr);
                if (count($arr) > 10)
                    exit();
                $sqlin = implode(",", $arr);
                if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
                    $wheresql.=" AND r.sdistrict IN  ({$sqlin}) ";
                }
            } else {
                $wheresql.=" AND r.sdistrict=" . intval($aset['sdistrict']) . " ";
            }
        }
    }
    if ($_CFG['subsite'] == "1" && $_CFG['subsite_filter_resume'] == "1" && intval($_CFG['subsite_id']) > 0) {
        $wheresql.=" AND r.subsite_id=" . intval($_CFG['subsite_id']) . " ";
    }
    if (isset($aset['experience']) && !empty($aset['experience'])) {
        $wheresql.=" AND r.experience=" . intval($aset['experience']) . " ";
    }
    if (isset($aset['education']) && !empty($aset['education'])) {
        $wheresql.=" AND r.education=" . intval($aset['education']) . " ";
    }
    if (isset($aset['talent']) && !empty($aset['talent'])) {
        $wheresql.=" AND r.talent=" . intval($aset['talent']) . " ";
    }
    if (isset($aset['photo']) && !empty($aset['photo'])) {
        $wheresql.=" AND r.photo='" . intval($aset['photo']) . "' ";
    }
    if (isset($aset['key']) && !empty($aset['key'])) {
        if ($_CFG['resumesearch_purview'] == '2') {
            if ($_SESSION['username'] == '') {
                header("Location: " . url_rewrite('QS_login') . "?url=" . urlencode($_SERVER["REQUEST_URI"]));
            }
        }
        $key = trim($aset['key']);
        if ($_CFG['resumesearch_type'] == '1') {
            $akey = explode(' ', $key);
            if (count($akey) > 1) {
                $akey = array_filter($akey);
                $akey = array_slice($akey, 0, 2);
                $akey = array_map("fulltextpad", $akey);
                $key = '+' . implode(' +', $akey);
                $mode = ' IN BOOLEAN MODE';
            } else {
                $key = fulltextpad($key);
                $mode = ' ';
            }
            $wheresql.=" AND  MATCH (r.`key`) AGAINST ('{$key}'{$mode}) ";
        } else {
            $wheresql.=" AND r.likekey LIKE '%{$key}%' ";
        }
        if ($_CFG['resumesearch_sort'] == '1') {
            $orderbysql = "";
        } else {
            $orderbysql = " ORDER BY r.refreshtime DESC ";
        }
        $resumetable = table('resume_search_key');
    }
    if (isset($aset['hot_resume']) && !empty($aset['hot_resume'])) {
        $o1 = substr($orderbysql, 0, 10);
        $o2 = substr($orderbysql, 10);
        $old_orderbysql = $orderbysql;
        $wheresql.=" AND refreshtime > " . (time() - 86400 * 30);
        $orderbysql = $o1 . "click DESC, " . $o2;
    }
    if (isset($aset['tag']) && !empty($aset['tag'])) {
        $tag = intval($aset['tag']);
        $wheresql.=" AND  (tag1='{$tag}' OR tag2='{$tag}' OR tag3='{$tag}' OR tag4='{$tag}' OR tag5='{$tag}') ";
        $orderbysql = "";
        $resumetable = table('resume_search_tag');
    }

    $wheresql.=" AND r.display='1' AND r.audit = 1 AND uid IN (SELECT uid FROM " . table("resume") . " WHERE fullname != '') ";
    if (!empty($wheresql)) {
        $wheresql = " WHERE " . ltrim(ltrim($wheresql), 'AND');
    }
    if (isset($aset['paged'])) {
        require_once(QISHI_ROOT_PATH . 'include/page.class.php');
        $total_sql = "SELECT  COUNT(*) AS num  FROM  {$resumetable} AS r " . $joinsql . $wheresql;
        //var_dump($total_sql);exit;
        $total_count = get_mem_cache($total_sql, "get_total");
        if (intval($_CFG['resume_list_max']) > 0) {
            //$total_count > intval($_CFG['resume_list_max']) && $total_count = intval($_CFG['resume_list_max']);
        }
        //echo $total_sql;
        //echo "SELECT  COUNT(DISTINCT r.id) AS num  FROM  ".table('resume')." AS r ".$wheresql;
        $page = new page(array('total' => $total_count, 'perpage' => $aset['row'], 'alias' => $aset['listpage'], 'getarray' => $_GET));
        $currenpage = $page->nowindex;
        $aset['start'] = abs($currenpage - 1) * $aset['row'];
        $smarty->assign('page', $page->show(3));
        $smarty->assign('pagemin', $page->show(7));
        $smarty->assign('total', $total_count);
        $smarty->assign('pagenow', $page->show(6));
    }
    $limit = " LIMIT {$aset['start']} , {$aset['row']}";
    $list = $id = array();
    //$idresult = $db->query("SELECT id FROM {$resumetable}  AS r" . $joinsql . $wheresql . $orderbysql . $limit);
    //echo "SELECT id FROM {$resumetable}  AS r".$joinsql.$wheresql.$orderbysql.$limit;
    $sql = "SELECT id FROM {$resumetable}  AS r" . $joinsql . $wheresql . $orderbysql . $limit;
    $idresult = $db->getall($sql);
    foreach ($idresult as $row) {
        $id[] = $row['id'];
    }
    if (!empty($id)) {
        $wheresql = " WHERE id IN (" . implode(',', $id) . ") ";
        //echo "SELECT * FROM ".table('resume')."  AS r ".$wheresql.$orderbysql;
        $sql = "SELECT * FROM " . table('resume') . "  AS r " . $wheresql . $orderbysql;
        $result = get_mem_cache($sql, "getall");
        $i = 0;
        $shield_num = 0;
        $screen_row = $aset['screen_row'];
        if ($_SESSION['utype'] == 1) {
            $sql = "SELECT * FROM " . table('company_profile') . " where uid = " . $_SESSION['uid'];
            $company = get_mem_cache($sql, "getone");
        }
        foreach ($result as $row) {
            if ($_SESSION['utype'] == 1) {
                $shield_flag = 0;
                $shield = $db->getall("select * from " . table('personal_shield_company') . " where pid=" . $row['id']);
                foreach ($shield as $s) {
                    if (stripos($company['companyname'], $s['comkeyword']) > -1) {
                        $shield_flag = 1;
                    }
                }
                if ($shield_flag == 1) {
                    continue;
                }
            }
            if ($row['display_name'] == "2") {
                $row['fullname'] = "N" . str_pad($row['id'], 7, "0", STR_PAD_LEFT);
                $row['fullname_'] = $row['fullname'];
            } elseif ($row['display_name'] == "3") {
                $row['fullname'] = cut_str($row['fullname'], 1, 0, "**");
                $row['fullname_'] = $row['fullname'];
            } else {
                $row['fullname_'] = $row['fullname'];
                $row['fullname'] = cut_str($row['fullname'], $aset['namelen'], 0, $aset['dot']);
            }
            if (in_array($row['id'], $_COOKIE['QS']['view_resume_log'])) {
                $row['checked'] = true;
            } else {
                $row['checked'] = false;
            }
            $row['specialty_'] = strip_tags($row['specialty']);
            if ($aset['specialtylen'] > 0) {
                $row['specialty'] = cut_str(strip_tags($row['specialty']), $aset['specialtylen'], 0, $aset['dot']);
            }
            if ($aset['jobslen'] > 0) {
                $row['intention_jobs'] = cut_str(strip_tags($row['intention_jobs']), $aset['jobslen'], 0, $aset['dot']);
            }
            $f = !empty($row['intention_jobs']) ? explode(",", $row['intention_jobs']) : array();
            $row['intention_jobs_first'] = $f[0];
            $row['trade_cn'] = cut_str(strip_tags($row['trade_cn']), 10, 0, "..");
            $row['photosrc'] = $row['photo'] ? $_CFG['resume_photo_dir_thumb'] . $row['photo_img'] : $_CFG['resume_photo_dir_thumb'] . "no_photo.gif";
            if (!empty($aset['photo']) && !file($row['photosrc'])) {
                continue;
            }
            $row['resume_url'] = url_rewrite($aset['showname'], array('id' => $row['id']), true, $row['subsite_id']);
            $row['refreshtime_cn'] = daterange(time(), $row['refreshtime'], 'Y-m-d', "#FF3300");
            $row['age'] = date("Y") - $row['birthdate'];
            $row['index'] = $i++;
            if ($aset['show_school']) {
                $sql = "select school,speciality from " . table('resume_education') . " where pid=" . $row['id'] . " order by education desc";
                $resume_education = get_mem_cache($sql, "getone");
                $row['school'] = $resume_education['school'];
                $row['speciality'] = $resume_education['speciality'];
            }
            if ($row['tag']) {
                $tag = explode('|', $row['tag']);
                $taglist = array();
                if (!empty($tag) && is_array($tag)) {
                    foreach ($tag as $t) {
                        $tli = explode(',', $t);
                        $taglist[] = array($tli[0], $tli[1]);
                    }
                }
                $row['tag'] = $taglist;
            } else {
                $row['tag'] = array();
            }
            $list[] = $row;
            if ($aset['screen_row'] > 0 && $i == $aset['screen_row']) {
                break;
            }
        }
    } else {
        $list = array();
    }
    if (!empty($aset['resume_num'])) {
        $date = strtotime("-1 day");
        $date = date("Y-m-d", $date);
        $sql = "select count  from " . table('jiaoshi_statistics_day') . " where name = 'USER_DAU' and date = '" . $date . "'";
        $resume_num = get_mem_cache($sql, "getone");
        $resume_num = $resume_num['count'] * 123 + rand(1000, 2500);
        $smarty->assign('resume_num', $resume_num);
    }
    $smarty->assign($aset['listname'], $list);
}

?>