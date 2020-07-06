<?php

function tpl_function_qishi_jobs_show($params, &$smarty) {
    global $db, $timestamp, $_CFG, $mem;
    $arr = explode(',', $params['set']);
    foreach ($arr as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "职位ID":
                $aset['id'] = $a[1];
                break;
            case "列表名":
                $aset['listname'] = $a[1];
                break;
            case "描述长度":
                $aset['brieflylen'] = $a[1];
                break;
            case "填补字符":
                $aset['dot'] = $a[1];
                break;
        }
    }
    $aset = array_map("get_smarty_request", $aset);
    $aset['id'] = $aset['id'] ? intval($aset['id']) : 0;
    $aset['brieflylen'] = isset($aset['brieflylen']) ? intval($aset['brieflylen']) : 0;
    $aset['listname'] = $aset['listname'] ? $aset['listname'] : "list";
    $wheresql = " WHERE id={$aset['id']} ";
    if (intval($_SESSION['utype'] == 2 && intval($_SESSION['uid']) > 0)) {
        $uid = intval($_SESSION['uid']);
        $resume_basic = $db->getone("SELECT * FROM " . table('resume') . " WHERE uid='" . $uid . "' LIMIT 1 ");
        if (!empty($resume_basic['attachment_resume'])) {
            $resume_basic['attachment_resume_name'] = explode("--", $resume_basic['attachment_resume']);
            $resume_basic['attachment_resume_name'] = $resume_basic['attachment_resume_name'][2];
        }
        $resume_education = $db->getall("SELECT * FROM " . table('resume_education') . " WHERE uid='" . $uid . "' ");
        $resume_work = $db->getall("SELECT * FROM " . table('resume_work') . " WHERE uid=" . $uid);
        $smarty->assign("resume_basic", $resume_basic);
        $smarty->assign("resume_education", $resume_education);
        $smarty->assign("resume_work", $resume_work);
    }
    $sql = "select * from " . table('jobs') . $wheresql . " LIMIT 1";
    $val = $db->getone($sql);
    if (empty($val)) {
        $sql = "select * from " . table('jobs_tmp') . $wheresql . " LIMIT 1";
        $val = $db->getone($sql);
    }
    if ($val['district'] > 0) {
        $sql = "select categoryname,pinyin from " . table('category_district') . " WHERE  id=" . intval($val['district']) . " LIMIT 1";
        $job_tmp = get_mem_cache($sql, "getone");
        $job_district['district_py'] = $job_tmp['pinyin'];
        $job_district['district_cn'] = $job_tmp['categoryname'];
    }
    if ($val['sdistrict'] > 0) {
        $sql = "select categoryname,pinyin,parentid from " . table('category_district') . " WHERE  id=" . intval($val['sdistrict']) . " LIMIT 1";
        $job_tmp = get_mem_cache($sql, "getone");
        $job_district['sdistrict_py'] = $job_tmp['pinyin'];
        $job_district['sdistrict_cn'] = $job_tmp['categoryname'];
        $job_district['sdistrict_parent'] = $job_tmp['parentid'];
    }
    $smarty->assign("job_district", $job_district);
    $smarty->assign("district_id", $val['sdistrict']);
    $val['overdue'] = 1;
    if (empty($val)) {
        $sql = "select * from " . table('jobs_tmp') . $wheresql . " LIMIT 1";
        $val = $db->getone($sql);
        $val['overdue'] = 2;
    }
    if (empty($val) || (intval($_GET['subsite_id']) != $val['subsite_id'])) {
        header("HTTP/1.1 404 Not Found");
        $smarty->display("404.htm");
        exit();
    } else {
        if (intval($_SESSION['uid']) > 0 && intval($_SESSION['utype']) == 2) {
            $check = check_view_log(intval($_SESSION['uid']), $val['id']);
            if (!$check) {
                add_view_log(intval($_SESSION['uid']), $val['id']);
            }
        }
        if ($val['setmeal_deadline'] < time() && $val['setmeal_deadline'] <> "0" && $val['add_mode'] == "2") {
            $val['deadline'] = $val['setmeal_deadline'];
        }

        if ($val['deadline'] > 0) {
            $all_time = $val['deadline'] - $val['addtime'];
            if (strtotime(date('Y-m-d')) > $val['addtime']) {
                $now_time = strtotime(date('Y-m-d')) - $val['addtime'];
                $time_percent = $now_time / $all_time;
            } else {
                $time_percent = 0;
            }
            $val['endtime'] = date("Y-m-d", $val['deadline']);
        } else {
            $time_percent = 0;
            $val['endtime'] = '暂无';
        }
        $val['starttime'] = date("Y-m-d", $val['addtime']);
        $val['time_percent'] = ceil($time_percent * 100);

        $val['amount'] = $val['amount'] == "0" ? '若干' : $val['amount'];
        $val['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $val['id']), true, $val['subsite_id']);
        $profile = GetJobsCompanyProfile($val['company_id']);
        $val['company'] = $profile;
        $val['contact'] = GetJobsContact($val['id']);
        $district_cn = $val['district_cn'];
        $d_arr = explode("/", $district_cn);
        $val['district_ch'] = $d_arr[0];
        $val['sdistrict_ch'] = $d_arr[1];
        $val['expire'] = sub_day($val['deadline'], time());
        $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . " WHERE jobs_id= '{$val['id']}'";
        //$val['countresume'] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . " WHERE jobs_id= '{$val['id']}'");
        $val['countresume'] = get_mem_cache($total_sql, "get_total", 300);
        if ($aset['brieflylen'] > 0) {
            $val['briefly'] = cut_str(strip_tags($val['contents']), $aset['brieflylen'], 0, $val['dot']);
        } else {
            $val['briefly'] = strip_tags($val['contents']);
        }
        $val['refreshtime_cn'] = daterange(time(), $val['refreshtime'], 'Y-m-d', "#FF3300");
        $val['company_url'] = url_rewrite('QS_companyshow', array('id' => $val['company_id']));
        if ($val['company']['logo']) {
            $val['company']['logo'] = $_CFG['main_domain'] . "data/logo/" . $val['company']['logo'];
        } else {
            $val['company']['logo'] = $_CFG['main_domain'] . "data/logo/no_logo.gif";
        }
        if ($val['company']['website']) {
            if (strstr($val['company']['website'], "http://") === false) {
                $val['company']['website'] = "http://" . $val['company']['website'];
            }
        }

        if (intval($_SESSION['utype']) == 2) {
            $view_log = get_view_log(intval($_SESSION['uid']));
            foreach ($view_log as $key => $value) {
                if ($val['overdue'] == 2) {
                    $jobs_info = $db->getone("select id,subsite_id,company_id,jobs_name,companyname from " . table('jobs_tmp') . " where id=" . $value['jobsid']);
                } else {
                    $jobs_info = $db->getone("select id,subsite_id,company_id,jobs_name,companyname from " . table('jobs') . " where id=" . $value['jobsid']);
                }
                if ($jobs_info['subsite_id'] != intval($_GET['subsite_id'])) {
                    continue;
                }
                $val['view_log'][$key]['jobsid'] = $jobs_info['id'];
                $val['view_log'][$key]['jobs_name'] = $jobs_info['jobs_name'];
                $val['view_log'][$key]['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $jobs_info['id']), true, $jobs_info['subsite_id']);
                $val['view_log'][$key]['companyname'] = $jobs_info['companyname'];
            }
            $interest_id = get_interest_jobs_id(intval($_SESSION['uid']));
        }
        if ($val['tag']) {
            $tag = explode('|', $val['tag']);
            $taglist = array();
            if (!empty($tag) && is_array($tag)) {
                foreach ($tag as $t) {
                    $tli = explode(',', $t);
                    $taglist[] = $tli[1];
                }
            }
            $val['tag'] = $taglist;
        } else {
            $val['tag'] = array();
        }
        /*
          薪酬统计
         */
        $allcategory = $db->getall("select * from " . table('category_jobs') . " where parentid<>0");
        foreach ($allcategory as $key => $value) {
            $cat_arr[$value['id']] = $value['categoryname'];
        }
        $category_id = search_strs($cat_arr, $val['jobs_name']);
        if ($category_id) {
            $salary_data = $db->getall("select c.c_name from " . table('jobs_search_wage') . " as j left join " . table('category') . " as c on c.c_id=j.wage where (j.category=" . $category_id . " or j.subclass=" . $category_id . ") and sdistrict=" . $val['sdistrict']);
            if ($salary_data) {
                $total = 0;
                $total_salary = 0;
                foreach ($salary_data as $key => $value) {
                    $total_salary += intval($value['c_name']) + mt_rand(500, 2000);
                    $total++;
                }
                $val['salary']['value'] = intval($total_salary / $total);
                $val['salary']['px'] = ($val['salary']['value'] / 12000 * 446) . "px";
            }
        }
        /*
          薪酬统计
         */
        $sql = "select * from " . table('jiaoshi_comment') . " WHERE comment_type = 'job' and comment_type_id=" . intval($val['id']) . " order by addtime desc";
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
    }
    $smarty->assign($aset['listname'], $val);
}

function GetJobsCompanyProfile($id) {
    global $db;
    $sql = "select * from " . table('company_profile') . " where id=" . intval($id) . " LIMIT 1 ";
    return $db->getone($sql);
}

function GetJobsContact($id) {
    global $db;
    $sql = "select * from " . table('jobs_contact') . " where pid=" . intval($id) . " LIMIT 1 ";
    return $db->getone($sql);
}

function check_view_log($uid, $jobsid) {
    global $db;
    if ($jobsid < 1) {
        showmsg('职位不存在！', 1);
    }
    $result = $db->getone("select * from " . table("view_jobs") . " where `uid`=" . $uid . " and `jobsid`=" . $jobsid);
    return $result;
}

function add_view_log($uid, $jobsid) {
    $setsqlarr['uid'] = $uid;
    $setsqlarr['jobsid'] = $jobsid;
    $setsqlarr['addtime'] = time();
    inserttable(table("view_jobs"), $setsqlarr);
    $setsqlarr = array('personal_look' => 2);
    $wheresqlarr = array('resume_uid' => $uid, 'jobs_id' => $jobsid);
    updatetable(table("company_interview"), $setsqlarr, $wheresqlarr);
}

function get_view_log($uid) {
    global $db;
    $result = $db->getall("select * from " . table("view_jobs") . " where `uid`=" . $uid . " order by id desc limit 5");
    return $result;
}

function get_interest_jobs_id($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select id from " . table('resume') . " where   uid='{$uid}' LIMIT 3 ";
    $info = $db->getall($sql);
    if (is_array($info)) {
        foreach ($info as $s) {
            $jobsid = get_resume_jobs($s['id']);
            if (is_array($jobsid)) {
                foreach ($jobsid as $cid) {
                    $interest_id[] = $cid['category'];
                }
            }
        }
        if (is_array($interest_id))
            return implode("-", array_unique($interest_id));
    }
    return "";
}

//获取意向职位
function get_resume_jobs($pid) {
    global $db;
    $pid = intval($pid);
    $sql = "select * from " . table('resume_jobs') . " where pid='{$pid}'  LIMIT 20";
    return $db->getall($sql);
}

//模糊匹配
function search_strs($arr, $str) {
    foreach ($arr as $key => $list) {
        similar_text($list, $str, $percent);
        $od[$percent] = $key;
    }
    krsort($od);
    foreach ($od as $key => $li) {
        if ($key >= 60) {
            return $li;
        } else {
            return false;
        }
    }
}

?>