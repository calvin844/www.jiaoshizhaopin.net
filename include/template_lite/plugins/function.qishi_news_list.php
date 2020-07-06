<?php

function tpl_function_qishi_news_list($params, &$smarty) {
    global $db, $_CFG, $mem;
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
            case "标题长度":
                $aset['titlelen'] = $a[1];
                break;
            case "摘要长度":
                $aset['infolen'] = $a[1];
                break;
            case "开始位置":
                $aset['start'] = $a[1];
                break;
            case "填补字符":
                $aset['dot'] = $a[1];
                break;
            case "日期范围":
                $aset['settr'] = $a[1];
                break;
            case "排序":
                $aset['displayorder'] = $a[1];
                break;
            case "关键字":
                $aset['key'] = $a[1];
                break;
            case "分页显示":
                $aset['paged'] = $a[1];
                break;
            case "页面":
                $aset['showname'] = $a[1];
                break;
            case "列表页":
                $aset['listpang'] = $a[1];
                break;
            case "省份":
                $aset['parent_py'] = $a[1];
                break;
            case "地区":
                $aset['district'] = $a[1];
                break;
            case "时间格式":
                $aset['time_format'] = $a[1];
                break;
            case "缓存时间":
                $aset['cache_time'] = $a[1];
                break;
            case "索引搜索":
                $aset['index_search'] = $a[1];
                break;
            case "伪关键":
                $aset['Position'] = $a[1];
                break;
            case "地区中文":
                $aset['City'] = $a[1];
                break;
            case "排除分类":
                $aset['type_out'] = $a[1];
                break;
            case "分类":
                $aset['type_id'] = $a[1];
                break;
        }
    }
    if (is_array($aset)) {
        $aset = array_map("get_smarty_request", $aset);
    }
    $aset['City'] = htmlspecialchars($aset['City']);
    $aset['listname'] = isset($aset['listname']) ? $aset['listname'] : "list";
    $aset['row'] = isset($aset['row']) ? intval($aset['row']) : 10;
    $aset['start'] = isset($aset['start']) ? intval($aset['start']) : 0;
    $aset['titlelen'] = isset($aset['titlelen']) ? intval($aset['titlelen']) : 15;
    $aset['infolen'] = isset($aset['infolen']) ? intval($aset['infolen']) : 0;
    $aset['showname'] = isset($aset['showname']) ? $aset['showname'] : 'QS_newsshow';
    $aset['listpang'] = isset($aset['listpang']) ? $aset['listpang'] : 'QS_newslist';
    $aset['listpang'] = $aset['type_out'] != 7 ? '' : $aset['listpang'];
    $time = strtotime(date('Y-m-d', time()));
    $top_result = array();
    $te_level = 1;
    $te_district = "";
    if (isset($aset['district'])) {
        $aset['district'] = $aset['district'];
    } elseif (isset($aset['parent_py'])) {
        $aset['district'] = $aset['parent_py'];
    } else {
        $aset['district'] = '';
    }
    if (!empty($aset['City'])) {
        $sql = "select pinyin from " . table('category_district') . " where categoryname like '" . $aset['City'] . "' limit 1";
        $city_cn = get_mem_cache($sql, "getone");
        $aset['district'] = $city_cn['pinyin'];
    }
    $aset['cache_time'] = isset($aset['cache_time']) ? $aset['cache_time'] : $_CFG['mem_time'];
    $aset['key'] = isset($aset['key']) ? $aset['key'] : '';
    $aset['key'] = isset($aset['Position']) ? $aset['Position'] : $aset['key'];
    $aset['index_search'] = isset($aset['index_search']) && empty($aset['key']) ? 1 : 0;
    if ($aset['displayorder'] && !$aset['index_search']) {
        if (strpos($aset['displayorder'], '>')) {
            $arr = explode('>', $aset['displayorder']);
            $arr[0] = preg_match('/article_order|click|id|addtime|refreshtime/', $arr[0]) ? $arr[0] : "";
            $arr[1] = preg_match('/asc|desc/', $arr[1]) ? $arr[1] : "";
            if ($arr[0] && $arr[1]) {
                $orderbysql = " " . $arr[0] . " " . $arr[1];
            }
            if ($arr[0] == "article_order") {
                $orderbysql.=" ,id DESC ";
            }
        }
    } else {
        $orderbysql = " refreshtime desc ";
    }
    if (!$aset['index_search']) {
        $wheresql = " WHERE audit = 1 AND is_display=1 AND endtime > " . $time;
        if (isset($aset['type_out'])) {
            $type_arr = explode("-", $aset['type_out']);
            $type_str = implode(",", $type_arr);
            $wheresql .=" and type_id not in(" . $type_str . ")";
        }
    } else {
        $wheresql = " WHERE 1 ";
    }
    if (isset($aset['type_id'])) {
        $type_arr = explode("-", $aset['type_id']);
        $type_str = implode(",", $type_arr);
        $wheresql .=" and type_id in(" . $type_str . ")";
    }
    $jobs_wheresql = " WHERE 1 ";

    //lxh 如果检测到这是地区频道，则不再调用原来的程序
    if (!empty($aset['district'])) {
        //lxh 在地区表中查找出相应的数据
        $sql = "select id,parentid,categoryname,pinyin from " . table('category_district') . " where pinyin='" . $aset['district'] . "';";
        //$district = get_mem_cache($sql, "getone");
        $district = $db->getone($sql);
        if ($district) {
            //如果是城市，则查找出同一省份下的各个城市。如果是省份，则查找出全国的省份。
            if ($district['parentid'] > 0) {
                //这里是城市
                $sql = "select id,parentid,categoryname,pinyin from " . table('category_district') . " where id=" . $district['parentid'] . " limit 1;";
                $parent_district = get_mem_cache($sql, "getone");
                $smarty->assign('parent_district', $parent_district);
                $wheresql.=" AND sdistrict=" . intval($district['id']) . " ";
                $te_district = "AND (district = " . $district['parentid'] . " OR sdistrict=" . intval($district['id']) . ") ";
                $te_district3 = "AND sdistrict=" . intval($district['id']);
                $te_level = 3;
            } else {
                //这里是省份
                $smarty->assign('parent_district', $district);
                $wheresql.=" AND district=" . intval($district['id']) . " ";
                $te_district = " AND district=" . intval($district['id']) . " ";
                $te_level = 2;
            }
            $smarty->assign('district', $district);
        } elseif ($aset['district'] == "morejobs" || $aset['district'] != "internship" || $aset['district'] != "news_b_list") {
            $parent_district['id'] = 0;
            $smarty->assign('district', $parent_district);
        } elseif ($aset['district'] != "morejobs" && $aset['district'] != "internship" && $aset['district'] != "news_b_list") {
            Header("Location: /404.html");
            exit;
        }
    }
    if (!$aset['index_search']) {
        $keyword_tags_tmp = get_mem_cache("select * from qs_jiaoshi_keyword_tag;", "getall");
        $keyword_tags = array();
        foreach ($keyword_tags_tmp as $k) {
            $k['total_count'] = $k['count'] + $k['web_count'];
            array_push($keyword_tags, $k);
        }
        $keyword_tags = my_sort($keyword_tags, 'total_count', SORT_DESC);
        $smarty->assign('hot_keywords', array_slice($keyword_tags, 0, 10));
    }

    $match_keywords = array();
    if (!empty($aset['key'])) {
        $i = 0;
        foreach ($keyword_tags as $k) {
            $pos = stristr($aset['key'], $k['name']);
            if ($pos) {
                array_push($match_keywords, $k);
                if ($i++ == 0) {
                    $wheresql.=" AND seo_description like '%" . $k['name'] . "%'";
                } else {
                    $wheresql.=" or seo_description like '%" . $k['name'] . "%'";
                }
                $db->query("UPDATE qs_jiaoshi_keyword_tag SET web_count = " . ($k['web_count'] + 1) . " WHERE id = " . $k['id'] . ";");
            }
        }
        if ($i == 0) {
            $keyword_tag_tmp = $db->getone("select * from qs_jiaoshi_keyword_tag_tmp WHERE name = '" . $aset['key'] . "';");
            if (empty($keyword_tag_tmp)) {
                $db->query("INSERT qs_jiaoshi_keyword_tag_tmp SET web_count = 1, name = '" . $aset['key'] . "';");
            } else {
                $db->query("UPDATE qs_jiaoshi_keyword_tag_tmp SET web_count = " . ($keyword_tag_tmp['web_count'] + 1) . " WHERE id = " . $keyword_tag_tmp['id'] . ";");
            }
            $wheresql.=" and title like '%" . $aset['key'] . "%'";
        }
        $smarty->assign('keyword', $aset['key']);
    }
    if (isset($aset['paged'])) {
        require_once(QISHI_ROOT_PATH . 'include/page.class.php');
        if (!$aset['index_search']) {
            $total_sql = "SELECT COUNT(1) AS num FROM " . table('article') . $wheresql;
            $news_total_count = get_mem_cache($total_sql, "get_total", $aset['cache_time']);
            //$news_total_count = $db->get_total($total_sql);
            $total_count = $news_total_count;
        } else {
            $total_sql = "SELECT id FROM " . table('jiaoshi_article_jobs_index') . $wheresql . " group by parent_id ";
            $total_count = get_mem_cache($total_sql, "getall", $aset['cache_time']);
            $total_count = count($total_count);
        }
        $pagelist = new page(array('total' => $total_count, 'perpage' => $aset['row'], 'alias' => $aset['listpang'], 'getarray' => $_GET));
        $currenpage = $pagelist->nowindex;
        $aset['start'] = ($currenpage - 1) * $aset['row'];
        if ($total_count > $aset['row']) {
            $smarty->assign('page', $pagelist->show(3));
        } else {
            $smarty->assign('page', '');
        }
        $smarty->assign('total', $total_count);
    }
    $limit = " LIMIT " . abs($aset['start']) . ',' . $aset['row'];

    if ($aset['index_search']) {
        $orderbysql = " order by " . $orderbysql;
        $list_index_sql = "SELECT parent_id,type,refreshtime FROM " . table('jiaoshi_article_jobs_index') . " " . $wheresql . " group by parent_id " . $orderbysql . $limit;
        $index_result = get_mem_cache($list_index_sql, "getall", $aset['cache_time']);
        $news_time = $company_time = array();
        $news_wheresql = $company_wheresql = " where id IN(";
        foreach ($index_result as $i) {
            if ($i['type'] == 'article') {
                $news_wheresql .= $i['parent_id'] . ",";
                $news_time[$i['parent_id']] = $i['refreshtime'];
            } elseif ($i['type'] == 'jobs') {
                $company_wheresql .= $i['parent_id'] . ",";
                $company_time[$i['parent_id']] = $i['refreshtime'];
            }
        }
        $news_wheresql = trim($news_wheresql, ",");
        $company_wheresql = trim($company_wheresql, ",");
        $news_wheresql .= ") ";
        $company_wheresql .= ") ";
        $orderbysql = "";
        $limit = "";
    } else {
        $news_wheresql = $wheresql;
    }


    if ($company_wheresql != " where id IN() ") {
        $c_orderbysql = " order by " . $orderbysql;
        $list_company_sql = "SELECT * FROM " . table('company_profile') . " " . $company_wheresql . $c_orderbysql . $limit;
        $parent_result = get_mem_cache($list_company_sql, "getall", $aset['cache_time']);
        foreach ($parent_result as $r) {
            $r['refreshtime'] = $company_time[$r['id']];
            $company_result[] = $r;
        }
    }

    if ($news_wheresql != " where id IN() ") {
        $news_wheresql .= " AND audit = 1 AND endtime > " . $time;
        $n_orderbysql = " order by " . $orderbysql;
        if ($currenpage == 1) {
            $r = intval($aset['row']);
            $te_result = array();
            switch ($te_level) {
                case 1:
                    $te_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND top_level = " . $te_level . " order by top_month desc";
                    break;
                case 2:
                    $te_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND (top_level = 1 OR (top_level = 2 " . $te_district . ")) order by top_month desc";
                    break;
                case 3:
                    $te_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND (top_level = 1 OR (top_level = 2 " . $te_district . ") OR (top_level = 3 " . $te_district3 . ")) order by top_month desc";
                    break;
                default:
                    break;
            }
            //$te_article_sql = "SELECT * FROM " . table('article') . " " . $news_wheresql . " AND top > " . time() . " AND top_level = " . $te_level . " AND emergency > " . time() . " AND emergency_level = " . $te_level . " order by top_month desc";
            $te_result_tmp = get_mem_cache($te_article_sql, "getall", $aset['cache_time']);
            if (!empty($te_result_tmp)) {
                foreach ($te_result_tmp as $t) {
                    $t['top_flag'] = 1;
                    $top_tmp[] = $t['id'];
                    $te_result[] = $t;
                }
            }
            //$te_result_tmp = $db->getall($te_article_sql);
            //var_dump($te_article_sql);exit;
            /*
              if (!empty($te_result_tmp) && !empty($limit)) {
              if (count($te_result_tmp) > $r) {
              switch ($te_level) {
              case 1:
              $te_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND top_level = " . $te_level . " AND emergency > " . $time . " AND emergency_level = " . $te_level . " order by top_month desc limit " . $r;
              break;
              case 2:
              $te_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND (top_level = 1 OR (top_level = 2 " . $te_district . ")) AND emergency > " . $time . " AND (emergency_level = 1 OR (emergency_level = 2 " . $te_district . ")) order by top_month desc limit " . $r;
              break;
              case 3:
              $te_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND (top_level = 1 OR (top_level = 2 " . $te_district . ") OR (top_level = 3 " . $te_district3 . ")) AND emergency > " . $time . " AND (emergency_level = 1 OR (emergency_level = 2 " . $te_district . ") OR (emergency_level = 3 " . $te_district3 . ")) order by top_month desc limit " . $r;
              break;
              default:
              break;
              }
              //$te_article_sql = "SELECT * FROM " . table('article') . " " . $news_wheresql . " AND top > " . time() . " AND top_level = " . $te_level . " AND emergency > " . time() . " AND emergency_level = " . $te_level . " order by top_month desc limit " . $r;
              $te_result_tmp = get_mem_cache($te_article_sql, "getall", $aset['cache_time']);
              //$te_result_tmp = $db->getall($te_article_sql);
              }
              foreach ($te_result_tmp as $t) {
              $t['top_flag'] = 1;
              $t['emergency_flag'] = 1;
              $top_tmp[] = $t['id'];
              $te_result[] = $t;
              }
              $r = $r - count($te_result);
              }
              if ($r > 0 && ($r < $aset['row'] || $r == $aset['row'])) {
              switch ($te_level) {
              case 1:
              $top_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND top_level = " . $te_level . " order by top_month desc";
              break;
              case 2:
              $top_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND (top_level = 1 OR (top_level = 2 " . $te_district . ")) order by top_month desc";
              break;
              case 3:
              $top_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND (top_level = 1 OR (top_level = 2 " . $te_district . ") OR (top_level = 3 " . $te_district3 . ")) order by top_month desc";
              break;
              default:
              break;
              }
              //$top_article_sql = "SELECT * FROM " . table('article') . " " . $news_wheresql . " AND top > " . time() . " AND top_level = " . $te_level . " AND emergency =0 order by top_month desc";
              $top_result_tmp = get_mem_cache($top_article_sql, "getall", $aset['cache_time']);
              //$top_result_tmp = $db->getall($top_article_sql);
              if (!empty($top_result_tmp) && !empty($limit)) {
              if (count($top_result_tmp) > $r) {
              switch ($te_level) {
              case 1:
              $top_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND top_level = " . $te_level . " AND emergency =0 order by top_month desc limit " . $r;
              break;
              case 2:
              $top_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND (top_level = 1 OR (top_level = 2 " . $te_district . ")) AND emergency =0 order by top_month desc limit " . $r;
              break;
              case 3:
              $top_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top > " . $time . " AND (top_level = 1 OR (top_level = 2 " . $te_district . ") OR (top_level = 3 " . $te_district3 . ")) AND emergency =0 order by top_month desc limit " . $r;
              break;
              default:
              break;
              }
              //$top_article_sql = "SELECT * FROM " . table('article') . " " . $news_wheresql . " AND top > " . time() . " AND top_level = " . $te_level . " AND emergency =0 order by top_month desc limit " . $r;
              $top_result_tmp = get_mem_cache($top_article_sql, "getall", $aset['cache_time']);
              //$top_result_tmp = $db->getall($top_article_sql);
              }
              foreach ($top_result_tmp as $t) {
              $t['top_flag'] = 1;
              $t['emergency_flag'] = 0;
              $top_tmp[] = $t['id'];
              $top_result[] = $t;
              }
              $r = $r - count($top_result);
              if (!empty($te_result)) {
              $te_result = array_merge($te_result, $top_result);
              } else {
              $te_result = $top_result;
              }
              }
              }
              if ($r > 0 && ($r < $aset['row'] || $r == $aset['row'])) {
              switch ($te_level) {
              case 1:
              $emergency_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top =0 AND emergency > " . $time . " AND emergency_level = " . $te_level . " order by top_month desc";
              break;
              case 2:
              $emergency_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top =0 AND emergency > " . $time . " AND (emergency_level = 1 OR (emergency_level = 2 " . $te_district . ")) order by top_month desc";
              break;
              case 3:
              $emergency_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top =0 AND emergency > " . $time . " AND (emergency_level = 1 OR (emergency_level = 2 " . $te_district . ") OR (emergency_level = 3 " . $te_district3 . ")) order by top_month desc";
              break;
              default:
              break;
              }
              //$emergency_article_sql = "SELECT * FROM " . table('article') . " " . $news_wheresql . " AND top =0 AND emergency >" . time() . " AND emergency_level = " . $te_level . " order by id desc";
              $emergency_result_tmp = get_mem_cache($emergency_article_sql, "getall", $aset['cache_time']);
              //$emergency_result_tmp = $db->getall($emergency_article_sql);
              if (!empty($emergency_result_tmp) && !empty($limit)) {
              if (count($emergency_result_tmp) > $r) {
              switch ($te_level) {
              case 1:
              $emergency_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top =0 AND emergency > " . $time . " AND emergency_level = " . $te_level . " order by top_month desc limit " . $r;
              break;
              case 2:
              $emergency_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top =0 AND emergency > " . $time . " AND (emergency_level = 1 OR (emergency_level = 2 " . $te_district . ") order by top_month desc limit " . $r;
              break;
              case 3:
              $emergency_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) WHERE audit = 1 and is_display=1 AND top =0 AND emergency > " . $time . " (emergency_level = 1 OR (emergency_level = 2 " . $te_district . ") OR (emergency_level = 3 " . $te_district3 . ")) order by top_month desc limit " . $r;
              break;
              default:
              break;
              }
              //$emergency_article_sql = "SELECT * FROM " . table('article') . " " . $news_wheresql . " AND top =0 AND emergency >" . time() . " AND emergency_level = " . $te_level . " order by id desc limit " . $r;
              $emergency_result_tmp = get_mem_cache($emergency_article_sql, "getall", $aset['cache_time']);
              //$emergency_result_tmp = $db->getall($emergency_article_sql);
              }
              foreach ($emergency_result_tmp as $t) {
              $t['top_flag'] = 0;
              $t['emergency_flag'] = 1;
              $top_tmp[] = $t['id'];
              $emergency_result[] = $t;
              }
              $r = $r - count($emergency_result);
              if (!empty($te_result)) {
              $te_result = array_merge($te_result, $emergency_result);
              } else {
              $te_result = $emergency_result;
              }
              }
              }
              if (!empty($top_tmp)) {
              $top_id = implode(",", $top_tmp);
              $news_wheresql .= " AND id NOT IN(" . $top_id . ") ";
              $limit = " LIMIT " . abs($aset['start']) . ',' . $r;
              }
             * */
        }
        $list_article_sql = "SELECT * FROM " . table('article') . " FORCE INDEX (list_index) " . $news_wheresql . $n_orderbysql . $limit;

        $parent_result = get_mem_cache($list_article_sql, "getall", $aset['cache_time']);
        //$parent_result = $db->getall($list_article_sql);
        if (!empty($te_result)) {
            $parent_result = array_merge($te_result, $parent_result);
        }
        foreach ($parent_result as $r) {
            if (!empty($news_time)) {
                $r['refreshtime'] = $news_time[$r['id']];
            }
            $news_result[] = $r;
        }
    }
    //var_dump($news_result);exit;


    if (!empty($company_result) && !empty($news_result)) {
        $result = array_merge($news_result, $company_result);
    } elseif (empty($company_result) && !empty($news_result)) {
        $result = $news_result;
    } elseif (!empty($company_result) && empty($news_result)) {
        $result = $company_result;
    }
    if (!$aset['index_search']) {
        $result = $news_result;
    } else {
        foreach ($result as $key => $value) {
            $refreshtime[$key] = $value['refreshtime'];
        }
        array_multisort($refreshtime, SORT_DESC, $result);
    }


    $list = array();

    foreach ($result as $row) {
        $row['job_flag'] = $row['title'] ? 0 : 1;
        $row['title_'] = $row['title'] ? $row['title'] : $row['companyname'];
        //$row['title_'] = $row['title'];
        $style_color = $row['tit_color'] ? "color:" . $row['tit_color'] . ";" : '';
        $style_font = $row['tit_b'] == "1" ? "font-weight:bold;" : '';
        $row['title'] = cut_str($row['title_'], $aset['titlelen'], 0, $aset['dot']);
        if ($style_color || $style_font) {
            $row['title'] = "<span style=" . $style_color . $style_font . ">" . $row['title'] . "</span>";
        }

        $sdistrict_id = $row['sdistrict'] ? $row['sdistrict'] : $row['district'];
        $sql = "select parentid,pinyin from " . table('category_district') . " where id = " . $sdistrict_id;
        $pinyin_res = get_mem_cache($sql, "getone");
        $sql = "select pinyin from " . table('category_district') . " where id = " . $pinyin_res['parentid'];
        $parent_pinyin_res = get_mem_cache($sql, "getone");
        $row['parent_pinyin'] = $parent_pinyin_res['pinyin'];
        $row['district_pinyin'] = $pinyin_res['pinyin'];
        $row['district_cn'] = explode("/", $row['district_cn']);
        if (!empty($row['district_cn'][1])) {
            $row['district_cn'] = $row['district_cn'][1];
        } else {
            $row['district_cn'] = $row['district_cn'][0];
        }

        if (!empty($row['is_url']) && $row['is_url'] != 'http://') {
            $row['url'] = $row['is_url'];
        } else {
            if ($row['job_flag']) {
                $row['url'] = url_rewrite("QS_companyshow", array('id' => $row['id']));
            } else {
                //$row['url'] = url_rewrite($aset['showname'], array('id' => $row['id']), true, $row['subsite_id']);
                if (!empty($parent_pinyin_res)) {
                    $row['url'] = "/" . $parent_pinyin_res['pinyin'] . "/" . $pinyin_res['pinyin'] . "/jobshow_" . $row['id'] . ".html";
                } else {
                    $row['url'] = "/" . $pinyin_res['pinyin'] . "/jobshow_" . $row['id'] . ".html";
                }
            }
        }
        $row['content'] = $row['content'] ? $row['content'] : $row['contents'];
        $row['content'] = str_replace('&nbsp;', '', $row['content']);
        if ($row['job_flag']) {
            $jobs_arr_tmp = array();
            $company_jobs_sql = "SELECT * FROM " . table('jobs') . " where company_id = " . $row['id'];
            $jobs_tmp = get_mem_cache($company_jobs_sql, "getall");
            foreach ($jobs_tmp as $jt) {
                $jt['job_name'] = $jt['jobs_name'];
                $jt['url'] = url_rewrite("QS_jobsshow", array('id' => $jt['id']), true, $jt['subsite_id']);
                $jobs_arr_tmp[] = $jt;
            }
            $row['jobs_list'] = $jobs_arr_tmp;
            $row['jobs_count'] = count($jobs_arr_tmp);
        } else {
            $jobs_arr_tmp = array();
            if ($row['jobs'] > 0) {
                $article_jobs_sql = "SELECT * FROM " . table('jiaoshi_article_jobs') . " where article_id = " . $row['id'];
                $jobs_tmp = get_mem_cache($article_jobs_sql, "getall");
                foreach ($jobs_tmp as $jt) {
                    $jt['url'] = url_rewrite($aset['showname'], array('id' => $row['id']), true, $row['subsite_id']);
                    $jobs_arr_tmp[] = $jt;
                }
                $row['jobs_list'] = $jobs_arr_tmp;
                $row['jobs_count'] = count($jobs_arr_tmp);
            }
        }
        $row['briefly_'] = strip_tags($row['content']);
        if ($aset['infolen'] > 0) {
            $row['briefly'] = cut_str(strip_tags($row['content']), $aset['infolen'], 0, $aset['dot']);
        }
        $row['img'] = $row['Small_img'] ? $_CFG['thumb_dir'] . $row['Small_img'] : "";
        $row['bimg'] = $row['Small_img'] ? $_CFG['upfiles_dir'] . $row['Small_img'] : "";
        if (isset($aset['time_format'])) {
            $row['addtime'] = date($aset['time_format'], $row['addtime']);
            $row['refreshtime'] = date($aset['time_format'], $row['refreshtime']);
        }
        if (!$row['job_flag']) {
            $keywords = $row['seo_description'] ? explode(' ', $row['seo_description']) : "";
            $keywords = $keywords != "" ? explode(',', $keywords[2]) : "";
            if (!empty($keywords)) {
                $keywords_tmp = array();
                foreach ($keywords as $keyword_tmp) {
                    $keyword['name'] = $keyword_tmp;
                    $keyword['is_match'] = 0;
                    foreach ($match_keywords as $match_keyword) {
                        if ($match_keyword['name'] == $keyword_tmp) {
                            $keyword['is_match'] = 1;
                            break;
                        }
                    }
                    array_push($keywords_tmp, $keyword);
                }
                $row['keywords'] = $keywords_tmp;
            }
            $sql = "select count(1) as sum from " . table('personal_favorite_articles') . " where article_id=" . $row['id'];
            $result = get_mem_cache($sql, "getall");
            $row['favorite_total'] = $result['sum'];
            $time_tmp = ($row['endtime'] - time()) / 86400;
            if ($row['endtime'] > 0 && $time_tmp > 0) {
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
            }
        }
        $list[] = $row;
    }
//} 
    $channel = array("url" => '/morejobs', "name" => '简章');
    $smarty->assign('channel', $channel);
    if ($aset['district'] == "news_b_list") {
        $list = "";
    }
    $smarty->assign($aset['listname'], $list);
}

?>