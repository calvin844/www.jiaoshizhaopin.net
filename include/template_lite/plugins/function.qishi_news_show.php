<?php

function tpl_function_qishi_news_show($params, &$smarty) {
    global $db;
    $arr = explode(',', $params['set']);
    foreach ($arr as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "资讯ID":
                $aset['id'] = $a[1];
                break;
            case "列表名":
                $aset['listname'] = $a[1];
                break;
        }
    }
    $aset = array_map("get_smarty_request", $aset);
    $aset['id'] = $aset['id'] ? intval($aset['id']) : 0;
    $aset['listname'] = $aset['listname'] ? $aset['listname'] : "list";
    unset($arr, $str, $a, $params);
    $sql = "select * from " . table('article') . " WHERE  id=" . intval($aset['id']) . " AND  is_display=1 LIMIT 1";
    $val = $db->getone($sql);


    $sql = "select categoryname,parentid from " . table('category_jobs') . " WHERE parentid>0 AND categoryname!='其它'";
    $jobs_tmp = get_mem_cache($sql, "getall");
    foreach ($jobs_tmp as $jt) {
        $url = "/TeachingJobs/?Classify=" . urlencode($jt['categoryname']) . "&listpage=jobs_list";
        $j_str = '<a class="news_link" target="_blank" title="中小学' . $jt['categoryname'] . '招聘" href="' . $url . '">' . $jt['categoryname'] . '</a>';
        $val['content'] = str_replace($jt['categoryname'], $j_str, $val['content']);
    }

    $sql = "select categoryname,parentid,pinyin from " . table('category_district') . " WHERE id NOT IN(879,56,57,58,406,598) ";
    $district_tmp = get_mem_cache($sql, "getall");
    foreach ($district_tmp as $dt) {
        $pd = "";
        if ($dt['parentid'] > 0) {
            $sql = "select pinyin from " . table('category_district') . " WHERE id = " . $dt['parentid'];
            $pd = get_mem_cache($sql, "getone");
            $pd = "/" . $pd['pinyin'];
        }
        $d_str = '<a class="news_link" target="_blank" title="' . $dt['categoryname'] . '教师招聘网" href="' . $pd . '/' . $dt['pinyin'] . '">' . $dt['categoryname'] . '</a>';
        $val['content'] = str_replace($dt['categoryname'], $d_str, $val['content']);
    }

    if ($val['district'] > 0) {
        $sql = "select categoryname,pinyin from " . table('category_district') . " WHERE  id=" . intval($val['district']) . " LIMIT 1";
        $news_tmp = get_mem_cache($sql, "getone");
        $news_district['district_py'] = $news_tmp['pinyin'];
        $news_district['district_cn'] = $news_tmp['categoryname'];
    }
    if ($val['sdistrict'] > 0) {
        $sql = "select categoryname,pinyin,parentid from " . table('category_district') . " WHERE  id=" . intval($val['sdistrict']) . " LIMIT 1";
        $news_tmp = get_mem_cache($sql, "getone");
        $news_district['sdistrict_py'] = $news_tmp['pinyin'];
        $news_district['sdistrict_cn'] = $news_tmp['categoryname'];
        $news_district['sdistrict_parent'] = $news_tmp['parentid'];
    }
    $smarty->assign("news_district", $news_district);
    $smarty->assign("district_id", $val['sdistrict']);
    if ($val['jobs'] > 0) {
        $sql = "select * from " . table('jiaoshi_article_jobs') . " WHERE article_id=" . intval($aset['id']);
        $jobs = get_mem_cache($sql, "getall");
        $val['jobs'] = $jobs;
    }
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
        $lable_arr = explode(" ", $val['description']);
        $lable_arr = $lable_arr[2];
        $val['lable'] = explode(",", $lable_arr);
        //var_dump($lable_arr);exit;
    }
    $sql = "select * from " . table('jobs') . " WHERE  sdistrict=" . intval($val['sdistrict']) . " ORDER BY `recommend` DESC, `addtime` DESC LIMIT 2";
    $jobs = $db->getall($sql);
    if (count($jobs) < 2) {
        $sql = "select * from " . table('jobs') . " WHERE  district=" . intval($val['district']) . " ORDER BY `recommend` DESC, `addtime` DESC LIMIT 2";
        $jobs = $db->getall($sql);
    }
    $val['recommended_jobs'] = $jobs;
    if ($val['sdistrict'] > 0) {
        $sql = "select * from " . table('article') . " WHERE id <> " . intval($val['id']) . " and  sdistrict=" . intval($val['sdistrict']) . " ORDER BY  `addtime` DESC LIMIT 6";
        $sdistrict_articles = get_mem_cache($sql, "getall");
    }
    if (count($sdistrict_articles) < 6) {
        if ($val['district'] > 0) {
            $num = 6 - count($sdistrict_articles);
            $sql = "select * from " . table('article') . " WHERE id <> " . intval($val['id']) . " and  sdistrict <> " . intval($val['id']) . " and  district=" . intval($val['district']) . " ORDER BY  `addtime` DESC LIMIT " . $num;
            $district_articles = get_mem_cache($sql, "getall");
        }
    }
    if (!empty($sdistrict_articles) && !empty($district_articles)) {
        $articles = array_merge($district_articles, $sdistrict_articles);
    } elseif (empty($sdistrict_articles) && !empty($district_articles)) {
        $articles = $district_articles;
    } elseif (!empty($sdistrict_articles) && empty($district_articles)) {
        $articles = $sdistrict_articles;
    }
    foreach ($articles as $key => $value) {
        $addtime[$key] = $value['addtime'];
    }
    array_multisort($addtime, SORT_DESC, $articles);

    foreach ($articles as $row) {
        $sdistrict_id = $row['sdistrict'] ? $row['sdistrict'] : $row['district'];
        $sql = "select parentid,pinyin from " . table('category_district') . " where id = " . $sdistrict_id;
        $pinyin_res = get_mem_cache($sql, "getone");
        $sql = "select pinyin from " . table('category_district') . " where id = " . $pinyin_res['parentid'];
        $parent_pinyin_res = get_mem_cache($sql, "getone");
        $row['parent_pinyin'] = $parent_pinyin_res['pinyin'];
        $row['district_pinyin'] = $pinyin_res['pinyin'];
        if (!empty($parent_pinyin_res)) {
            $row['url'] = "/" . $parent_pinyin_res['pinyin'] . "/" . $pinyin_res['pinyin'] . "/jobshow_" . $row['id'] . ".html";
        } else {
            $row['url'] = "/" . $pinyin_res['pinyin'] . "/jobshow_" . $row['id'] . ".html";
        }
        $r_articles[] = $row;
    }

    $val['recommended_articles'] = $r_articles;

    $sql = "select * from " . table('online_course_info') . " WHERE audit = 2 AND (end_time=0 OR end_time>" . time() . ") ORDER BY  `addtime` DESC LIMIT 6";
    $course = get_mem_cache($sql, "getall");
    $val['recommended_course'] = $course;
    $sql = "select * from " . table('article_category') . " WHERE id=" . $val['type_id'];
    $category = get_mem_cache($sql, "getone");
    $val['service_img'] = $category['service_img'];
    $val['service_txt'] = $category['service_txt'];


    $val['overdue'] = 1;
    if ($val['endtime'] > 0 && time() > $val['endtime']) {
        $val['overdue'] = 2;
    }

    if ($val['endtime'] > 0) {
        $all_time = $val['endtime'] - $val['addtime'];
        if (strtotime(date('Y-m-d')) > $val['addtime']) {
            $now_time = strtotime(date('Y-m-d')) - $val['addtime'];
            $time_percent = $now_time / $all_time;
        } else {
            $time_percent = 0;
        }
        $val['endtime'] = date("Y-m-d", $val['endtime']);
    } else {
        $time_percent = 0;
        $val['endtime'] = '暂无';
    }
    $val['starttime'] = date("Y-m-d", $val['addtime']);
    $val['time_percent'] = ceil($time_percent * 100);
    if (!empty($val['attachment'])) {
        $val['attachment'] = trim($val['attachment'], ";");
        $val['attachment'] = explode(";", $val['attachment']);
        foreach ($val['attachment'] as $a) {
            $attachment_arr[] = explode(",", $a);
        }
    } else {
        $attachment_arr = "";
    }
    $val['attachment'] = $attachment_arr;

    $sql = "select count(1) as num from " . table('jiaoshi_article_apply') . " WHERE article_id =" . intval($val['id']);
    $apply_num = $db->getone($sql);
    $val['apply_num'] = $apply_num['num'] > 0 ? $apply_num['num'] : 0;

    $sql = "select * from " . table('jiaoshi_comment') . " WHERE comment_type = 'article' and comment_type_id=" . intval($val['id']) . " order by addtime desc";
    $comment_arr = $db->getall($sql);
    $val['comment_total'] = count($comment_arr) - 5;
    foreach ($comment_arr as $a) {
        if (count($comment_img) < 5 && !in_array($a['openid'], $comment_img)) {
            $comment_img[] = $a['openid'];
        }
    }
    $val['comment_sum'] = count($comment_arr);
    $comment_arr = array_chunk($comment_arr, 5);
    $val['comment_img'] = $comment_img;
    $val['comment_arr'] = $comment_arr[0];

    $address = explode("#", $val['address']);
    $site = explode("#", $val['site']);
    $telephone = explode("#", $val['telephone']);
    $contact = explode("#", $val['contact']);
    foreach ($contact as $ak => $av) {
        $val['contact_arr'][$ak]['address'] = !empty($address[$ak]) ? $address[$ak] : "无";
        $val['contact_arr'][$ak]['site'] = !empty($site[$ak]) ? $site[$ak] : "无";
        $val['contact_arr'][$ak]['telephone'] = !empty($telephone[$ak]) ? $telephone[$ak] : "";
        $val['contact_arr'][$ak]['contact'] = $av;
    }

    /*
      $prev = $db->getone("select id,title from " . table('article') . " where id<" . $val['id'] . " and type_id=" . $val['type_id'] . " order by id desc limit 1");
      if (!$prev) {
      $val['prev'] = 0;
      } else {
      $val['prev'] = 1;
      $val['prev_title'] = $prev['title'];
      $val['prev_id'] = $prev['id'];
      $val['prev_url'] = url_rewrite("QS_newsshow", array('id' => $prev['id']));
      }
      $next = $db->getone("select id,title from " . table('article') . " where id>" . $val['id'] . " and type_id=" . $val['type_id'] . " limit 1");
      if (!$next) {
      $val['next'] = "没有了";
      } else {
      $val['next'] = 1;
      $val['next_title'] = $next['title'];
      $val['next_id'] = $next['id'];
      $val['next_url'] = url_rewrite("QS_newsshow", array('id' => $next['id']));
      }
     * 
     */
    $channel = array("url" => '/morejobs', "name" => '简章');
    $smarty->assign('channel', $channel);
    $smarty->assign($aset['listname'], $val);
    /*
      $pattern = "/([a-z0-9\-_\.]+@[a-z0-9]+\.[a-z0-9\-_\.]+)/";
      preg_match_all($pattern, $val['content'], $email_arr);
      $smarty->assign('email', $email_arr[0][0]);
     * 
     */
}

?>