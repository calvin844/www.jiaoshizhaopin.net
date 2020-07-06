<?php

function tpl_function_qishi_resume_show($params, &$smarty) {
    global $db, $_CFG, $QS_cookiepath, $QS_cookiedomain;
    $arr = explode(',', $params['set']);
    foreach ($arr as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "简历ID":
                $aset['id'] = $a[1];
                break;
            case "列表名":
                $aset['listname'] = $a[1];
                break;
            case "简历列":
                $aset['ids'] = $a[1];
                break;
        }
    }
    $aset = array_map("get_smarty_request", $aset);
    $aset['ids'] = $aset['ids'] == $aset['id'] ? "" : $aset['ids'];
    $aset['id'] = $aset['id'] ? intval($aset['id']) : 0;
    $aset['listname'] = $aset['listname'] ? $aset['listname'] : "list";
    $wheresql = !empty($aset['ids']) ? " WHERE  id IN(" . trim($aset['ids'], ",") . ")" : " WHERE  id=" . $aset['id'];
    if (empty($aset['id']) && empty($aset['ids'])) {
        header("HTTP/1.1 404 Not Found");
        $smarty->display("404.htm");
        exit();
    }
    if (!empty($aset['ids'])) {
        $vals = $db->getall("select * from " . table('resume') . $wheresql);
        foreach ($vals as $v) {
            if (!empty($aset['id'])) {
                if ($v['id'] != $aset['id']) {
                    $wheresql = " WHERE  id=" . $aset['id'];
                    $val = $db->getone("select * from " . table('resume') . $wheresql . " LIMIT  1");
                    break;
                }
            } else {
                echo "<script type='text/javascript' language='javascript'>window.location.href='" . url_rewrite("QS_resumeshow", array('id' => $v['id']), true, $v['subsite_id']) . "&resume_show_list=" . $aset['ids'] . "'</script>";
                break;
            }
        }
        foreach ($vals as $v) {
            if ($v['display_name'] == "2") {
                $v['fullname'] = "N" . str_pad($v['id'], 7, "0", STR_PAD_LEFT);
                $v['fullname_'] = $v['fullname'];
            } elseif ($v['display_name'] == "3") {
                $v['fullname'] = cut_str($v['fullname'], 1, 0, "**");
                $v['fullname_'] = $v['fullname'];
            } else {
                $v['fullname_'] = $v['fullname'];
                $v['fullname'] = $v['fullname'];
            }
            $v['resume_url'] = url_rewrite("QS_resumeshow", array('id' => $v['id']), true, $v['subsite_id']);
            $val['resume_show_list'][$v['id']]['name'] = $v['fullname'];
            $val['resume_show_list'][$v['id']]['url'] = $v['resume_url'];
        }
    } else {
        $val = $db->getone("select * from " . table('resume') . $wheresql . " LIMIT  1");
    }

    if (intval($_SESSION['utype']) == 1) {
        $uid = intval($_SESSION['uid']);
        $sql = "select * from " . table('members_setmeal') . "  WHERE uid='" . $uid . "' AND  effective=1 LIMIT 1";
        $setmeal = $db->getone($sql);
        $smarty->assign('setmeal', $setmeal);
        $company_profile = $db->getone("select id,companyname from " . table('company_profile') . " where uid=" . intval($uid));
        $val['utype'] = 1;
        if (!empty($_GET['job_apply_id'])) {
            $company_jobs = $db->getone("select * from " . table('personal_jobs_apply') . " where company_id=" . intval($company_profile['id']) . " and resume_id =" . $aset['id'] . " and did = " . intval($_GET['job_apply_id']));
            if (!empty($company_jobs['did'])) {

                $website = 'http://m.jiaoshizhaopin.net/personal_center/send_wechat_resume_view_core/' . $_GET['job_apply_id'];
//                //dfopen($website);
                $ch = curl_init();
                // 设置URL和相应的选项
                curl_setopt($ch, CURLOPT_URL, $website);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                // 抓取URL并把它传递给浏览器
                curl_exec($ch);
                // 关闭cURL资源，并且释放系统资源
                curl_close($ch);
            }
        }
    }
    if ($val && (intval($_GET['subsite_id']) == $val['subsite_id'])) {
        setcookie('QS[view_resume_log][' . $val['id'] . ']', $val['id'], 0, $QS_cookiepath, $QS_cookiedomain);
        if (intval($_SESSION['uid']) > 0 && intval($_SESSION['utype']) == 1) {
            //检查企业是否被个人屏蔽过
            $company_profile = $db->getone("select id,companyname from " . table('company_profile') . " where uid=" . intval($_SESSION['uid']));
            /*
              if (empty($company_profile['id'])) {
              header("HTTP/1.1 404 Not Found");
              $smarty->display("404.htm");
              exit();
              }
             * 
             */
            $shield_company = $db->getall("select comkeyword from " . table('personal_shield_company') . " where pid=" . $val['id'] . " and uid=" . $val['uid']);
            foreach ($shield_company as $key => $value) {
                if (!empty($value['comkeyword']) && stristr($company_profile['companyname'], $value['comkeyword'])) {
                    header("HTTP/1.1 404 Not Found");
                    $smarty->display("404.htm");
                    exit();
                }
            }
            //检查是否查看过
            add_view_log(intval($_SESSION['uid']), $val['id']);
        }
        $val['education_list'] = get_this_education($val['uid'], $val['id']);
        //$val['certificate_list'] = get_this_certificate($val['uid'], $val['id']);
        $val['work_list'] = get_this_work($val['uid'], $val['id']);
        $val['training_list'] = get_this_training($val['uid'], $val['id']);
        $val['age'] = $val['birthdate'] > 0 ? date("Y") - $val['birthdate'] : "";
        if ($val['photo'] == "1") {
            $val['photosrc'] = "/data/photo/" . $val['photo_img'];
        } else {
            $val['photosrc'] = "/data/photo/" . "no_photo.gif";
        }
        if ($val['tag']) {
            $tag = explode('|', $val['tag']);
            $taglist = array();
            if (!empty($tag) && is_array($tag)) {
                foreach ($tag as $t) {
                    $tli = explode(',', $t);
                    $taglist[] = array($tli[0], $tli[1]);
                }
            }
            $val['tag'] = $taglist;
        } else {
            $val['tag'] = array();
        }
        $val['show'] = 0;
        $apply = $db->getone("select * from " . table('personal_jobs_apply') . " where `resume_id`=" . $val['id'] . " AND company_uid=" . intval($_SESSION['uid']));
        $down = $db->getone("select * from " . table('company_down_resume') . " where `resume_id`=" . $val['id'] . " AND company_uid=" . intval($_SESSION['uid']));
            if ($val['display_name'] == "2") {
                $val['fullname'] = "N" . str_pad($val['id'], 7, "0", STR_PAD_LEFT);
                $val['fullname_'] = $val['fullname'];
            } elseif ($val['display_name'] == "3") {
                $val['fullname'] = cut_str($val['fullname'], 1, 0, "**");
                $val['fullname_'] = $val['fullname'];
            } else {
                $val['fullname_'] = $val['fullname'];
                $val['fullname'] = $val['fullname'];
            }
            $certificate_list = get_this_certificate($val['uid'], $val['id']);
            foreach ($certificate_list as $cl) {
                $cl['path'] = "no_certificate.jpg";
                $val['certificate_list'][] = $cl;
            }


        if (intval($_GET['apply']) == 1) {
            $val['apply'] = 1;
            $apply = $db->getone("select * from " . table('personal_jobs_apply') . " where `resume_id`=" . $val['id']);
            $val['jobs_name'] = $apply['jobs_name'];
            $val['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $apply['jobs_id']), true, $val['subsite_id']);
        } else {
            $val['apply'] = 0;
        }
        /* 简历活跃度  更新时间 主动申请职位数  浏览职位数 */
        $vitality = 0;
        $val['refreshtime_cn'] = daterange(time(), $val['refreshtime'], 'Y-m-d', "#FF3300");
        $timestr = time() - $val['refreshtime'];
        $day = intval($timestr / 86400);
        if ($day < 3) {
            $vitality+=2;
        } else {
            $vitality+=1;
        }
        $time = time() - 15 * 86400;
        $val['apply_jobs'] = $db->get_total("select count(*) as num from " . table("personal_jobs_apply") . " where resume_id=$val[id] and apply_addtime>$time ");
        if ($val['apply_jobs'] > 0 && $val['apply_jobs'] < 10) {
            $vitality+=1;
        } elseif ($val['apply_jobs'] >= 10) {
            $vitality+=2;
        }
        $val['view_jobs'] = 0;
        if ($val['view_jobs'] >= 10) {
            $vitality+=1;
        }
        $val['vitality'] = $vitality;
        /* 企业关注度 start */
        $attention = 0;
        $val['com_down'] = $db->get_total("select count(*) num from " . table("company_down_resume") . " where resume_id=$val[id] and down_addtime>$time ");

        if ($val['com_down'] >= 0 && $val['com_down'] < 10) {
            $attention+=1;
        } elseif ($val['com_down'] >= 10) {
            $attention+=2;
        }
        $val['com_invite'] = $db->get_total("select count(*) num from " . table("company_interview") . " where resume_id=$val[id] and interview_addtime>$time ");
        if ($val['com_invite'] > 0 && $val['com_invite'] < 10) {
            $attention+=1;
        } elseif ($val['com_invite'] >= 10) {
            $attention+=2;
        }
        $val['com_view'] = 0;
        if ($val['com_view'] >= 10) {
            $attention+=1;
        }
        $val['attention'] = $attention;
        if (!empty($val['intention_jobs'])) {
            $arr = explode(",", $val['intention_jobs']);
            $val['intention_jobs'] = $arr;
        }
        $val['attention'] = $attention;
        /* 企业关注度 end */
    } else {
        header("HTTP/1.1 404 Not Found");
        $smarty->display("404.htm");
        exit();
    }
    $channel = array("url" => '/', "name" => '简历');
    $smarty->assign('channel', $channel);
    $smarty->assign($aset['listname'], $val);
}

function get_this_certificate($uid, $pid) {
    global $db;
    $sql = "SELECT * FROM " . table('resume_certificate') . " WHERE uid='" . intval($uid) . "' AND pid='" . intval($pid) . "' and audit = 1 ";
    return $db->getall($sql);
}

function get_this_education($uid, $pid) {
    global $db;
    $sql = "SELECT * FROM " . table('resume_education') . " WHERE uid='" . intval($uid) . "' AND pid='" . intval($pid) . "' ";
    return $db->getall($sql);
}

function get_this_work($uid, $pid) {
    global $db;
    $sql = "select * from " . table('resume_work') . " where uid=" . intval($uid) . " AND pid='" . $pid . "' ";
    return $db->getall($sql);
}

function get_this_training($uid, $pid) {
    global $db;
    $sql = "select * from " . table('resume_training') . " where uid='" . intval($uid) . "' AND pid='" . intval($pid) . "'";
    return $db->getall($sql);
}

function add_view_log($uid, $resumeid) {
    global $db;
    $result = $db->getone("select * from " . table("view_resume") . " where `uid`=" . $uid . " and `resumeid`=" . $resumeid);
    if (!$result) {
        $setsqlarr['uid'] = $uid;
        $setsqlarr['resumeid'] = $resumeid;
        $setsqlarr['addtime'] = time();
        inserttable(table("view_resume"), $setsqlarr);
    }
    $setsqlarr = array('personal_look' => 2);
    $wheresqlarr = array('company_uid' => $uid, 'resume_id' => $resumeid);
    updatetable(table("personal_jobs_apply"), $setsqlarr, $wheresqlarr);
}

?>