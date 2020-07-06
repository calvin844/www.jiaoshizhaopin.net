<?php

/* * *******************************************
 * 骑士职位列表
 * ****************************************** */

date_default_timezone_set("Asia/Shanghai");   //设置时区  

function tpl_function_qishi_jobs_list($params, &$smarty) {
    global $db, $_CFG;
    $arrset = explode(',', $params['set']);
//var_dump($arrset);
    foreach ($arrset as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "列表名":
                $aset['listname'] = $a[1];
                break;
            case "显示数目":
                $aset['row'] = $a[1];
                break;
            case "开始位置":
                $aset['start'] = $a[1];
                break;
            case "职位名长度":
                $aset['jobslen'] = $a[1];
                break;
            case "企业名长度":
                $aset['companynamelen'] = $a[1];
                break;
            case "描述长度":
                $aset['brieflylen'] = $a[1];
                break;
            case "填补字符":
                $aset['dot'] = $a[1];
                break;
            case "职位分类":
                $aset['jobcategory'] = $a[1];
                break;
            case "职位大类":
                $aset['category'] = $a[1];
                break;
            case "职位小类":
                $aset['subclass'] = $a[1];
                break;
            case "地区分类":
                $aset['citycategory'] = $a[1];
                break;
            case "省份":
                $aset['parent_py'] = $a[1];
            case "地区拼音":
                $aset['district_py'] = $a[1];
                break;
            case "地区大类":
                $aset['district'] = $a[1];
                break;
            case "地区小类":
                $aset['sdistrict'] = $a[1];
                break;
            case "道路":
                $aset['street'] = $a[1];
                break;
            case "写字楼":
                $aset['officebuilding'] = $a[1];
                break;
            case "标签":
                $aset['tag'] = $a[1];
                break;
            case "行业":
                $aset['trade'] = $a[1];
                break;
            case "学历":
                $aset['education'] = $a[1];
                break;
            case "工作经验":
                $aset['experience'] = $a[1];
                break;
            case "工资":
                $aset['wage'] = $a[1];
                break;
            case "职位性质":
                $aset['nature'] = $a[1];
                break;
            case "公司规模":
                $aset['scale'] = $a[1];
                break;
            case "职位状态":
                $aset['state'] = $a[1];
                break;
            case "紧急招聘":
                $aset['emergency'] = $a[1];
                break;
            case "推荐":
                $aset['recommend'] = $a[1];
                break;
            case "关键字":
                $aset['key'] = $a[1];
                break;
            case "关键字类型":
                $aset['keytype'] = $a[1];
                break;
            case "日期范围":
                $aset['settr'] = $a[1];
                break;
            case "排序":
                $aset['displayorder'] = $a[1];
                break;
            case "分页显示":
                $aset['page'] = $a[1];
                break;
            case "会员UID":
                $aset['uid'] = $a[1];
                break;
            case "公司页面":
                $aset['companyshow'] = $a[1];
                break;
            case "职位页面":
                $aset['jobsshow'] = $a[1];
                break;
            case "列表页":
                $aset['listpage'] = $a[1];
                break;
            case "不显示":
                $aset['hidden'] = $a[1];
                break;
            case "首页搜索":
                $aset['search_index'] = $a[1];
                break;
            case "索引搜索":
                $aset['index_search'] = $a[1];
                break;
            case "拼音":
                $aset['category_py'] = $a[1];
                break;
            case "分类中文":
                $aset['Classify'] = $a[1];
                break;
            case "课目":
                $aset['Position'] = $a[1];
                break;
        }
    }
    $aset = array_map("get_smarty_request", $aset);
    $aset['listname'] = !empty($aset['listname']) ? $aset['listname'] : "list";
    $aset['listpage'] = !empty($aset['listpage']) ? $aset['listpage'] : "QS_jobslist";
    $aset['listpage'] = $aset['listpage'] == "jobs_list" ? "QS_jobslist" : $aset['listpage'];
    $aset['row'] = intval($aset['row']) > 0 ? intval($aset['row']) : 10;
    if (!empty($aset['district_py'])) {
        $aset['district_py'] = $aset['district_py'];
    } elseif (!empty($aset['parent_py'])) {
        $aset['district_py'] = $aset['parent_py'];
    } else {
        $aset['district_py'] = '';
    }
    if ($aset['row'] > 30) {
        $aset['row'] = 30;
    }
    $aset['start'] = !empty($aset['start']) ? intval($aset['start']) : 0;
    $aset['jobslen'] = !empty($aset['jobslen']) ? intval($aset['jobslen']) : 8;
    $aset['companynamelen'] = !empty($aset['companynamelen']) ? intval($aset['companynamelen']) : 15;
    $aset['brieflylen'] = !empty($aset['brieflylen']) ? intval($aset['brieflylen']) : 0;
    $aset['companyshow'] = !empty($aset['companyshow']) ? $aset['companyshow'] : 'QS_companyshow';
    $aset['jobsshow'] = !empty($aset['jobsshow']) ? $aset['jobsshow'] : 'QS_jobsshow';
    $aset['index_search'] = !empty($aset['index_search']) && empty($aset['key']) ? 1 : 0;
    $aset['Classify'] = !empty($aset['Classify']) ? trim($aset['Classify']) : "";
    $aset['Position'] = !empty($aset['Position']) ? trim($aset['Position']) : "";
    $dorder = "";
    $row_arr = "";
    $resume_info = "";
    //浏览过的职位
    if (intval($_SESSION['uid']) > 0) {
        $resume_info = $db->getone("select complete_percent from " . table('resume') . " WHERE uid='" . intval($_SESSION['uid']) . "' LIMIT 1 ");
        $smarty->assign('complete_percent', $resume_info['complete_percent']);
        $selectstr = " a.*,r.jobs_name,r.wage_cn ";
        $result = $db->query("SELECT " . $selectstr . " FROM " . table('view_jobs') . " as a  LEFT JOIN  " . table('jobs') . " AS r  ON  a.jobsid=r.id  WHERE a.uid='" . intval($_SESSION['uid']) . "'  ORDER BY a.id DESC LIMIT 5");
        while ($row = $db->fetch_array($result)) {
            if (empty($row['jobs_name'])) {
                $jobs = $db->getone("select * from " . table('jobs_tmp') . " WHERE `id`='{$row['jobsid']}' LIMIT 1");
                $row['jobs_name'] = $jobs['jobs_name'];
                $row['jobsid'] = $jobs['id'];
                $row['wage_cn'] = $jobs['wage_cn'];
                $row['sdistrict'] = $jobs['sdistrict'];
                $row['district'] = $jobs['district'];
            }
            $district_id = $row['sdistrict'] > 0 ? $row['sdistrict'] : $row['district'];
            if (intval($district_id) > 0) {
                $d_sql = "select * from " . table('category_district') . " where id = " . $district_id . " limit 1";
                $district_arr = get_mem_cache($d_sql, "getone");
                $row['d_cn'] = $district_arr['categoryname'];
            }
            $row['url'] = url_rewrite("QS_jobsshow", array('id' => $row['jobsid']), true, $row['subsite_id']);
            $row_arr[] = $row;
        }
        $smarty->assign('view_jobs', $row_arr);
    }

    if (!empty($aset['experience']) && $aset['experience'] <> '') {
        $wheresql.=" AND experience=" . intval($aset['experience']);
    }
    if (!empty($aset['Position']) && $aset['Position'] != 'all') {
        $sql = "select * from " . table("jiaoshi_subject") . " where subject_name like '" . $aset['Position'] . "'";
        $Position = get_mem_cache($sql, "getone");
        if (!empty($Position)) {
            $Position_ids = explode(",", $Position['category_job_ids']);
            foreach ($Position_ids as $p) {
                $aset['jobcategory'] .= "0." . $p . "|";
            }
            $aset['jobcategory'] = trim($aset['jobcategory'], "|");
            $_GET['jobcategory'] = $aset['jobcategory'];
        }
    }
    if (!empty($aset['Classify']) && $aset['Classify'] != 'all') {
        $sql = "select * from " . table("category_jobs") . " where categoryname like '" . $aset['Classify'] . "'";
        $classify_cn = get_mem_cache($sql, "getone");
        if (!empty($classify_cn)) {
            $aset['jobcategory'] = $classify_cn['parentid'] == 0 ? $classify_cn['id'] . ".0.0" : $classify_cn['parentid'] . "." . $classify_cn['id'] . ".0";
            $_GET['jobcategory'] = $aset['jobcategory'];
        }
    }
    if (!empty($aset['category_py']) && $aset['category_py'] != 'all' && empty($aset['jobcategory'])) {
        $sql = "select * from " . table("category_jobs") . " where pinyin = '" . $aset['category_py'] . "' limit 1";
        $category_arr = get_mem_cache($sql, "getone");
        $aset['jobcategory'] = $category_arr['parentid'] == 0 ? $category_arr['id'] . ".0.0" : $category_arr['parentid'] . "." . $category_arr['id'] . ".0";
        $_GET['jobcategory'] = $aset['jobcategory'];
    }
    if (!empty($aset['key']) && $aset['search_index'] == 1) {
        $key = $aset['key'];
        if (empty($aset['citycategory'])) {
            $sql = "select id,parentid,categoryname from " . table('category_district');
            $district_all = $db->getall($sql);
            foreach ($district_all as $d) {
                if (strstr($aset['key'], $d['categoryname'])) {
                    $aset['citycategory'] = $d['parentid'] . '.' . $d['id'];
                    $change = array($d['categoryname'] => '');
                    $aset['key'] = strtr($aset['key'], $change);
                    break;
                }
            }
        }
        echo '<script type="text/javascript" language="javascript">window.location.href="' . $_CFG['main_domain'] . 'jobs/jobs-list.php?key=' . $aset['key'] . '&citycategory=' . $aset['citycategory'] . '";</script> ';
    }
    $openorderby = false;
    if (!empty($aset['displayorder'])) {
        $arr = explode('>', $aset['displayorder']);
        $arr[1] = preg_match('/asc|desc/', $arr[1]) ? $arr[1] : "desc";
        if ($arr[0] == "rtime") {
            $orderbysql = " ORDER BY refreshtime {$arr[1]}";
            if ($aset['index_search']) {
                $jobstable = table('jiaoshi_article_jobs_index');
            } else {
                $jobstable = table('jobs_search_rtime');
            }
        } elseif ($arr[0] == "addtime") {
            $orderbysql = " ORDER BY refreshtime {$arr[1]}";
            $jobstable = table('jiaoshi_article_jobs_index');
        } elseif ($arr[0] == "stickrtime") {
            $orderbysql = " ORDER BY stick {$arr[1]} , refreshtime {$arr[1]}";
            $jobstable = table('jobs_search_stickrtime');
        } elseif ($arr[0] == "hot") {
            $orderbysql = " ORDER BY click {$arr[1]}";
            $jobstable = table('jobs_search_hot');
        } elseif ($arr[0] == "scale") {
            $orderbysql = " ORDER BY scale {$arr[1]},refreshtime {$arr[1]}";
            $jobstable = table('jobs_search_scale');
        } elseif ($arr[0] == "wage") {
            $orderbysql = " ORDER BY wage {$arr[1]},refreshtime {$arr[1]}";
            $jobstable = table('jobs_search_wage');
        } elseif ($arr[0] == "key") {
            $jobstable = table('jobs_search_key');
        } elseif ($arr[0] == "null") {
            $orderbysql = "";
            $jobstable = table('jobs_search_rtime');
        } else {
            $orderbysql = " ORDER BY stick {$arr[1]} , refreshtime {$arr[1]}";
            $jobstable = table('jobs_search_stickrtime');
        }
    } else {
        $orderbysql = " ORDER BY stick DESC , refreshtime DESC";
        $jobstable = table('jobs_search_stickrtime');
        if ($aset['index_search']) {
            $orderbysql = " ORDER BY refreshtime DESC";
            $jobstable = table('jiaoshi_article_jobs_index');
        }
    }
    if (!$aset['index_search']) {
        //选择站点
        if ($_CFG['subsite'] == "1" && $_CFG['subsite_filter_jobs'] == "1" && intval($_CFG['subsite_id']) > 0) {
            $wheresql.=" AND subsite_id=" . intval($_CFG['subsite_id']) . " ";
        }
        //选择所属用户
        if (!empty($aset['uid']) && $aset['uid'] > 0) {
            $wheresql.=" AND uid=" . intval($aset['uid']);
        }
        //选择工作性质
        if (!empty($aset['nature']) && $aset['nature'] <> '') {
            if (strpos($aset['nature'], "-")) {
                $or = $orsql = "";
                $arr = explode("-", $aset['nature']);
                if (count($arr) > 10)
                    exit();
                $sqlin = implode(",", $arr);
                if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
                    $wheresql.=" AND nature IN  (" . $sqlin . ") ";
                }
            } else {
                $wheresql.=" AND nature=" . intval($aset['nature']) . " ";
            }
        }
        //选择规模
        if (!empty($aset['scale']) && $aset['scale'] <> '') {
            $wheresql.=" AND scale=" . intval($aset['scale']);
        }
        //选择学历
        if (!empty($aset['education']) && $aset['education'] <> '') {
            $wheresql.=" AND education=" . intval($aset['education']);
            $sql = "select * from " . table('category') . " WHERE c_alias='QS_education' AND c_id=" . intval($aset['education']);
            $select_education = $db->getone($sql);
            $smarty->assign('select_education', $select_education['c_id'] . "|" . $select_education['c_name']);
        }
        //选择工资
        if (!empty($aset['wage']) && $aset['wage'] <> '') {
            $wheresql.=" AND wage=" . intval($aset['wage']);
            $sql = "select * from " . table('category') . " WHERE c_alias='QS_wage' AND c_id=" . intval($aset['wage']);
            $select_wage = $db->getone($sql);
            $smarty->assign('select_wage', $select_wage['c_id'] . "|" . $select_wage['c_name']);
        }
        //选择经验
        if (!empty($aset['experience']) && $aset['experience'] <> '') {
            $wheresql.=" AND experience=" . intval($aset['experience']);
            $sql = "select * from " . table('category') . " WHERE c_alias='QS_experience' AND c_id=" . intval($aset['experience']);
            $select_experience = $db->getone($sql);
            $smarty->assign('select_experience', $select_experience['c_id'] . "|" . $select_experience['c_name']);
        }
        //选择行业
        if (!empty($aset['trade']) && $aset['trade'] <> '') {
            if (strpos($aset['trade'], "|")) {
                $or = $orsql = "";
                $arr = explode("|", $aset['trade']);
                $arr = array_unique($arr);
                if (count($arr) > 10)
                    exit();
                $sqlin = implode(",", $arr);
                if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
                    $wheresql.=" AND trade IN  ({$sqlin}) ";
                }
            } else {
                $wheresql.=" AND trade=" . intval($aset['trade']) . " ";
            }
        }
        //选择街道
        if (!empty($aset['street']) && $aset['street'] <> '') {
            $wheresql.=" AND street=" . intval($aset['street']);
        }
        //选择大厦
        if (!empty($aset['officebuilding']) && $aset['officebuilding'] <> '') {
            $wheresql.=" AND officebuilding=" . intval($aset['officebuilding']);
        }
        //选择标签
        if (!empty($aset['tag']) && !empty($aset['tag'])) {
            $tag = intval($aset['tag']);
            $wheresql.=" AND  (tag1='{$tag}' OR tag2='{$tag}' OR tag3='{$tag}' OR tag4='{$tag}' OR tag5='{$tag}') ";
            $orderbysql = "";
            $jobstable = table('jobs_search_tag');
        }
        //选择不显示的职位
        if (!empty($aset['hidden']) && !empty($aset['hidden'])) {
            $wheresql.=" AND  id <>  " . $aset['hidden'];
        }
    }

    if (!empty($aset['settr']) && $aset['settr'] <> '') {
        $settr = intval($aset['settr']);
        if ($settr > 0) {
            $settr_val = intval(strtotime("-" . $aset['settr'] . " day"));
            $wheresql.=" AND refreshtime>" . $settr_val;
        }
    }

    if (!empty($aset['district_py'])) {
        //lxh 在地区表中查找出相应的数据
        $sql = "select id,parentid,categoryname,pinyin from " . table('category_district') . " where pinyin='" . $aset['district_py'] . "';";
        $district = $db->getone($sql);
        if (!empty($district)) {
            //如果是城市，则查找出同一省份下的各个城市。如果是省份，则查找出全国的省份。
            if (!empty($district['parentid'])) {
                //这里是城市
                $sql = "select id,parentid,categoryname,pinyin from " . table('category_district') . " where id=" . $district['parentid'] . ";";
                $parent_district = $db->getone($sql);
                $smarty->assign('parent_district', $parent_district);
                $wheresql.=" AND sdistrict=" . intval($district['id']) . " ";
                if (!!empty($aset['district']) && $aset['district'] == '' && empty($aset['citycategory'])) {
                    $aset['citycategory'] == $parent_district['parentid'] . '.' . $parent_district['id'];
                }
            } else {
                //这里是省份
                $smarty->assign('parent_district', $district);
                $wheresql.=" AND district=" . intval($district['id']) . " ";
                if (!!empty($aset['district']) && $aset['district'] == '' && empty($aset['citycategory'])) {
                    $aset['citycategory'] == $district['id'] . '.0';
                }
            }
            $smarty->assign('district', $district);
            if (!!empty($aset['district']) && $aset['district'] == '' && empty($aset['citycategory'])) {
                $aset['citycategory'] == '.';
            }
        }
    }


    if (!empty($aset['citycategory']) && $aset['citycategory'] != '0.') {
        $dsql = $xsql = "";
        $arr = explode("_", $aset['citycategory']);
        $arr = array_unique($arr);
        if (count($arr) > 10) {
            exit();
        }
        foreach ($arr as $sid) {
            $cat = explode(".", $sid);
            if (intval($cat[1]) === 0) {
                $dsql.= " OR district =" . intval($cat[0]);
                $dorder .= " case when district = '" . intval($cat[0]) . "' then 1 else 10 end, ";
            } else {
                $xsql.= " OR sdistrict =" . intval($cat[1]);
                $dorder .= " case when sdistrict = '" . intval($cat[1]) . "' then 1 else 10 end, ";
            }
            $district_id = intval($cat[0]);
            if ($district_id > 0) {
                $district_sql = "select * from " . table('category_district') . " where id = " . $district_id . " and parentid = 0 limit 1";
                $district = get_mem_cache($district_sql, "getone");
                $smarty->assign('district_arr', $district);
            }
            $sdistrict_id = intval($cat[1]);
            if ($sdistrict_id > 0) {
                $sdistrict_sql = "select * from " . table('category_district') . " where id = " . $sdistrict_id . " and parentid > 0 limit 1";
                $sdistrict = get_mem_cache($sdistrict_sql, "getone");
                $smarty->assign('sdistrict_arr', $sdistrict);
            }
        }
        $wheresql.=" AND  (" . ltrim(ltrim($dsql . $xsql), 'OR') . ") ";
    } else {
        if (!empty($aset['district']) && $aset['district'] <> '') {
            if (strpos($aset['district'], "-")) {
                $or = $orsql = "";
                $arr = explode("-", $aset['district']);
                $arr = array_unique($arr);
                if (count($arr) > 20)
                    exit();
                $sqlin = implode(",", $arr);
                if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
                    $wheresql.=" AND district IN  ({$sqlin}) ";
                    $dorder .= " case when district IN '" . $sqlin . "' then 1 else 10 end, ";
                }
            } else {
                $wheresql.=" AND district =" . intval($aset['district']);
                $dorder .= " case when district = '" . intval($aset['district']) . "' then 1 else 10 end, ";
            }
        }
        if (!empty($aset['sdistrict']) && $aset['sdistrict'] <> '') {
            if (strpos($aset['sdistrict'], "-")) {
                $or = $orsql = "";
                $arr = explode("-", $aset['sdistrict']);
                $arr = array_unique($arr);
                if (count($arr) > 10)
                    exit();
                $sqlin = implode(",", $arr);
                if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
                    $wheresql.=" AND sdistrict IN  ({$sqlin}) ";
                    $dorder .= " case when sdistrict IN '" . $sqlin . "' then 1 else 10 end, ";
                }
            } else {
                $wheresql.=" AND sdistrict =" . intval($aset['sdistrict']);
                $dorder .= " case when sdistrict = '" . intval($aset['district']) . "' then 1 else 10 end, ";
            }
        }
    }



    if (!empty($aset['jobcategory'])) {
        $dsql = $xsql = $rsql = "";
        if (strstr($aset['jobcategory'], "|")) {
            $arr = explode("|", $aset['jobcategory']);
            $arr = array_unique($arr);
            if (count($arr) > 100) {
                exit();
            }
            foreach ($arr as $sid) {
                $cat = explode(".", $sid);
                if (intval($cat[1]) != 0) {
                    $xsql.= " OR subclass =" . intval($cat[1]);
                }
            }
            $wheresql.=" AND  (" . trim($xsql, ' OR') . ") ";
        } else {
            if (!strstr($aset['jobcategory'], ".")) {
                $cat_sql = "select * from " . table('category_jobs') . " where id = " . $aset['jobcategory'];
                $cat_info = $db->getone($cat_sql);
                if ($cat_info['parentid'] > 0) {
                    $aset['jobcategory'] = $cat_info['parentid'] . "." . $cat_info['id'] . ".0";
                } else {
                    $aset['jobcategory'] = $cat_info['id'] . ".0.0";
                }
            }
            $cat = explode(".", $aset['jobcategory']);
            if (intval($cat[0]) != 0 && intval($cat[1]) == 0) {
                $wheresql.=" AND  category =" . intval($cat[0]);
            } else {
                $wheresql.=" AND  subclass =" . intval($cat[1]);
            }
            /*
              $cat_id = intval($cat[1]) != 0 ? intval($cat[1]) : intval($cat[0]);
              $cat_sql = "select * from " . table('category_jobs') . " where id = " . $cat_id;
              $cat_info = $db->getone($cat_sql);
              $category_cn = $cat_info['categoryname'];
              $smarty->assign('category_cn', $category_cn);
              $smarty->assign('category_arr', $cat_info);
             * 
             */

            $subclass_id = intval($cat[1]) != 0 ? intval($cat[1]) : intval($cat[0]);
            if ($subclass_id > 0) {
                $subclass_sql = "select * from " . table('category_jobs') . " where id = " . $subclass_id;
                $subclass_info = $db->getone($subclass_sql);
                $subclass_cn = $subclass_info['categoryname'];
                $smarty->assign('subclass_cn', $subclass_cn);
                $smarty->assign('subclass_arr', $subclass_info);
            }
        }
    } else {
        if (!empty($aset['category']) && $aset['category'] <> '') {
            if (strpos($aset['category'], "-")) {
                $or = $orsql = "";
                $arr = explode("-", $aset['category']);
                $arr = array_unique($arr);
                if (count($arr) > 10)
                    exit();
                $sqlin = implode(",", $arr);
                if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
                    $wheresql.=" AND topclass IN  ({$sqlin}) ";
                }
            } else {
                $wheresql.=" AND topclass = " . intval($aset['category']);
            }
        }
        if (!empty($aset['subclass']) && $aset['subclass'] <> '') {
            if (strpos($aset['subclass'], "-")) {
                $or = $orsql = "";
                $arr = explode("-", $aset['subclass']);
                $arr = array_unique($arr);
                if (count($arr) > 10)
                    exit();
                $sqlin = implode(",", $arr);
                if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
                    $wheresql.=" AND category IN  ({$sqlin}) ";
                }
            } else {
                $wheresql.=" AND category = " . intval($aset['subclass']);
            }
        }
    }

    if (!empty($aset['key'])) {
        if ($_CFG['jobsearch_purview'] == '2') {
            if ($_SESSION['username'] == '') {
                header("Location: " . url_rewrite('QS_login') . "?url=" . urlencode($_SERVER["REQUEST_URI"]));
            }
        }
        $key = trim($aset['key']);
        if ($_CFG['jobsearch_type'] == '1') {
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
            $wheresql.=" AND  MATCH (`key`) AGAINST ('{$key}'{$mode}) ";
        } else {
            $wheresql.=" AND likekey LIKE '%{$key}%' ";
        }
        if ($_CFG['jobsearch_sort'] == '1') {
            $orderbysql = " ORDER BY refreshtime DESC ";
        } else {
            $orderbysql = " ORDER BY refreshtime DESC ";
        }
        $jobstable = table('jobs_search_key');
    }
    if (!empty($wheresql)) {
        $wheresql = " and " . ltrim(ltrim($wheresql), 'AND');
    }
    if (!$aset['index_search']) {
        $in_id = " id in(SELECT id from " . table('jobs') . ") ";
    } else {
        $in_id = " p_id > 0 ";
    }
    if ($aset['state'] == 2 || $aset['emergency'] == 1) {
        //$wheresql = " and emergency =1";
    }

    $total_sql = "SELECT COUNT(*) AS num FROM {$jobstable} where " . $in_id . $wheresql;
    $total_count = $db->getone($total_sql);
    $total_count = $total_count['num'];
    //var_dump($total_count);
    if (!empty($aset['page'])) {
        require_once(QISHI_ROOT_PATH . 'include/page.class.php');
        $page = new page(array('total' => $total_count, 'perpage' => $aset['row'], 'alias' => $aset['listpage'], 'getarray' => $_GET));
        $currenpage = $page->nowindex;
        $aset['start'] = abs($currenpage - 1) * $aset['row'];
        if ($total_count > $aset['row']) {
            $smarty->assign('page', $page->show(3));
            $smarty->assign('pagemin', $page->show(4));
            $smarty->assign('pagenow', $page->show(6));
        }
    }
    $smarty->assign('total', $total_count);
    $limit = " LIMIT {$aset['start']} , {$aset['row']}";
    $list = $id = array();

    $o1 = substr($orderbysql, 0, 10);
    $o2 = substr($orderbysql, 10);
    $old_orderbysql = $orderbysql;
    if ($aset['state'] == 1) {
        $aset['recommend'] = 1;
        $orderbysql = " ORDER BY recommend DESC, " . $dorder . " refreshtime DESC ";
    } elseif ($aset['state'] == 2) {
        $aset['emergency'] = 1;
        $orderbysql = " ORDER BY emergency DESC, " . $dorder . " refreshtime DESC ";
    }

    if ($aset['index_search']) {
        $list_index_sql = "SELECT p_id,type,refreshtime FROM " . table('jiaoshi_article_jobs_index') . " where " . $in_id . $wheresql . $orderbysql . $limit;
        $index_result = $db->getall($list_index_sql);
        $news_wheresql = $company_wheresql = " where id IN(";
        foreach ($index_result as $i) {
            if ($i['type'] == 'article') {
                $news_wheresql .= $i['p_id'] . ",";
            } elseif ($i['type'] == 'jobs') {
                $company_wheresql .= $i['p_id'] . ",";
            }
        }
        $news_wheresql = trim($news_wheresql, ",");
        $company_wheresql = trim($company_wheresql, ",");
        $news_wheresql .= ") ";
        $company_wheresql .= ") ";
        $orderbysql = "";
        $limit = "";
    } else {
        $idresult = $db->query("SELECT id FROM {$jobstable} WHERE " . $in_id . $wheresql . $orderbysql . $limit);
        while ($row = $db->fetch_array($idresult)) {
            $id[] = $row['id'];
        }
        if (!empty($id)) {
            $company_wheresql = " WHERE id IN (" . implode(',', $id) . ") ";
            $o1 = substr($old_orderbysql, 0, 10);
            $o2 = substr($old_orderbysql, 10);
            if ($aset['state'] == 1) {
                $aset['recommend'] = 1;
                $orderbysql = $o1 . "recommend DESC, refreshtime DESC, " . $o2;
            } elseif ($aset['state'] == 2) {
                $aset['emergency'] = 1;
                $orderbysql = $o1 . "emergency DESC, refreshtime DESC, " . $o2;
            }
        }
    }
    //var_dump($news_wheresql);

    if (!empty($company_wheresql) && $company_wheresql != " where id IN() ") {
        $jobs_result_sql = "SELECT * FROM " . table('jobs') . $company_wheresql . $orderbysql;
        $jobs_result = $db->getall($jobs_result_sql);
    }
    if ($aset['index_search']) {
        if ($news_wheresql != " where id IN() ") {
            $news_result_sql = "SELECT * FROM " . table('jiaoshi_article_jobs') . $news_wheresql . $orderbysql;
            $news_res = $db->getall($news_result_sql);
            foreach ($news_res as $n) {
                $refreshtime_sql = "SELECT title,refreshtime,email,district_cn,click,endtime FROM " . table('article') . " where id = " . $n['article_id'];
                $refreshtime_result = $db->getone($refreshtime_sql);
                $n['refreshtime'] = $refreshtime_result['refreshtime'];
                $n['district_cn'] = $refreshtime_result['district_cn'];
                $n['title'] = $refreshtime_result['title'];
                $n['endtime'] = $refreshtime_result['endtime'];
                $n['click'] = $refreshtime_result['click'];
                $n['email'] = $refreshtime_result['email'];
                $news_result[] = $n;
            }
        }
    }
    if (!empty($jobs_result) && !empty($news_result)) {
        $result = array_merge($news_result, $jobs_result);
    } elseif (empty($jobs_result) && !empty($news_result)) {
        $result = $news_result;
    } elseif (!empty($jobs_result) && empty($news_result)) {
        $result = $jobs_result;
    }
    if (!$aset['index_search']) {
        $result = $jobs_result;
    } else {
        foreach ($result as $key => $value) {
            $refreshtime[$key] = $value['refreshtime'];
        }
        array_multisort($refreshtime, SORT_DESC, $result);
    }
    //echo "SELECT * FROM ".table('jobs')." ".$wheresql.$orderbysql;
    foreach ($result as $row) {
        $row['news_flag'] = $row['jobs_name'] ? 0 : 1;
        $row['jobs_name_'] = $row['jobs_name'] ? $row['jobs_name'] : $row['job_name'];
        $row['refreshtime_cn'] = daterange(time(), $row['refreshtime'], 'Y-m-d', "#FF3300");
        $row['jobs_name'] = cut_str($row['jobs_name_'], $aset['jobslen'], 0, $aset['dot']);
        if (!empty($row['highlight']) && !$row['news_flag']) {
            $row['jobs_name'] = "<span style=\"color:{$row['highlight']}\">{$row['jobs_name']}</span>";
        }
        if ($aset['brieflylen'] > 0 && !$row['news_flag']) {
            $row['briefly'] = cut_str(strip_tags($row['contents']), $aset['brieflylen'], 0, $aset['dot']);
        } else {
            $row['briefly'] = strip_tags($row['contents']);
        }
        $row['amount'] = $row['amount'] == "0" ? '若干' : $row['amount'];
        $row['companyname_'] = $row['companyname'] ? $row['companyname'] : $row['title'];
        $row['companyname'] = cut_str($row['companyname_'], $aset['companynamelen'], 0, $aset['dot']);
        if (!$row['news_flag']) {
            $row['jobs_url'] = url_rewrite($aset['jobsshow'], array('id' => $row['id']), true, $row['subsite_id']);
            $row['company_url'] = url_rewrite($aset['companyshow'], array('id' => $row['company_id']));
        } else {
            $row['company_url'] = $row['jobs_url'] = url_rewrite("QS_newsshow", array('id' => $row['article_id']), true, $row['subsite_id']);
        }
        $row['daterange'] = daterange(time(), $row['addtime'], 'Y-m-d');
        $deadline = $row['deadline'] ? $row['deadline'] : $row['endtime'];
        $time_tmp = ($row['deadline'] - time()) / 86400;
        if ($time_tmp > 365) {
            $time_tmp = ceil($time_tmp / 365);
            $time_tmp = $time_tmp . "年";
        } elseif ($time_tmp > 30) {
            $time_tmp = ceil($time_tmp / 30);
            $time_tmp = $time_tmp . "月";
        } else {
            $time_tmp = ceil($time_tmp);
            $time_tmp = $time_tmp . "日";
        }
        $row['remaining_time'] = $time_tmp;
        if (!$row['news_flag']) {
            $row['briefly_'] = strip_tags($row['contents']);
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
        }
        $district_id = $row['sdistrict'] > 0 ? $row['sdistrict'] : $row['district'];
        if (intval($district_id) > 0) {
            $d_sql = "select * from " . table('category_district') . " where id = " . $district_id . " limit 1";
            $district_arr = get_mem_cache($d_sql, "getone");
            $row['d_cn'] = $district_arr['categoryname'];
        }

        $list[] = $row;
    }
    //$list['total'] = $total_count;
    //$list['category_cn'] = $cat_info['categoryname'];
    $smarty->assign($aset['listname'], $list);
}

function time_tran($the_time) {
    $now_time = date("Y-m-d H:i:s", time());
    //echo $now_time;  
    $now_time = strtotime($now_time);
    $show_time = strtotime($the_time);
    $dur = $now_time - $show_time;
    if ($dur < 0) {
        return $the_time;
    } else {
        if ($dur < 60) {
            return $dur . '秒前';
        } else {
            if ($dur < 3600) {
                return floor($dur / 60) . '分钟前';
            } else {
                if ($dur < 86400) {
                    return floor($dur / 3600) . '小时前';
                } else {
                    if ($dur < 259200) {//3天内  
                        return floor($dur / 86400) . '天前';
                    } else {
                        return $the_time;
                    }
                }
            }
        }
    }
}

?>