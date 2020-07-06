<?php

/*
 * 74cms 简历列表
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
  define('IN_QISHI', true);
  $alias="QS_resumelist";
  require_once(dirname(__FILE__).'/../include/common.inc.php');
  if($mypage['caching']>0){
  $smarty->cache =true;
  $smarty->cache_lifetime=$mypage['caching'];
  }else{
  $smarty->cache = false;
  }
  $cached_id=$_CFG['subsite_id']."|".$alias.(isset($_GET['id'])?"|".(intval($_GET['id'])%100).'|'.intval($_GET['id']):'').(isset($_GET['page'])?"|p".intval($_GET['page'])%100:'');
  if(!$smarty->is_cached($mypage['tpl'],$cached_id))
  {
  require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
  $db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
  unset($dbhost,$dbuser,$dbpass,$dbname);
  $smarty->display($mypage['tpl'],$cached_id);
  $db->close();
  }
  else
  {
  $smarty->display($mypage['tpl'],$cached_id);
  }
  unset($smarty);
 */

define('IN_QISHI', true);
//require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(dirname(__FILE__) . '/../user/company/company_common.php');
//require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
//$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
//unset($dbhost, $dbuser, $dbpass, $dbname);
/*
  if ($_SESSION['uid'] == '' || $_SESSION['username'] == '' || intval($_SESSION['uid']) === 0) {
  header("Location: " . url_rewrite('QS_login') . "?act=logout");
  exit();
  } elseif ($_SESSION['utype'] != '1') {
  $link[0]['text'] = "会员中心";
  $link[0]['href'] = url_rewrite('QS_login');
  showmsg('您访问的页面需要 企业会员 登录！', 1, $link);
  }
 * 
 */
$act = empty($_GET['act']) ? 'search_list' : trim($_GET['act']);
if ($act == 'search_list') {
    $jobcategory = empty($_GET['type']) ? '' : trim($_GET['type']);
    $citycategory = empty($_GET['district']) ? '' : trim($_GET['district']);
    $experience = empty($_GET['experience']) ? '' : intval($_GET['experience']);
    $education = empty($_GET['education']) ? '' : intval($_GET['education']);
    $photo = empty($_GET['photo']) ? '' : intval($_GET['photo']);
    $talent = empty($_GET['talent']) ? '' : intval($_GET['talent']);
    $row = 10;
    $key = empty($_GET['key']) ? '' : trim($_GET['key']);
    $wheresql = " WHERE  r.audit =1 AND r.display =1  AND refreshtime > " . strtotime("-5 year");
    $orderbysql = " ORDER BY r.talent DESC, r.refreshtime DESC ";
    $resumetable = table('resume_search_rtime');
    if (!empty($jobcategory)) {
        $dsql = $xsql = "";
        $j = $db->getone("SELECT * FROM " . table('category_jobs') . " WHERE id='" . intval($jobcategory) . "'");
        if (intval($j['parentid']) === 0) {
            $dsql.= " OR category =" . intval($jobcategory);
            $smarty->assign('select_parent_type', "parent_type|0");
        } else {
            $xsql.= " OR subclass =" . intval($jobcategory);
            $p = $db->getone("SELECT * FROM " . table('category_jobs') . " WHERE id='" . intval($j['parentid']) . "'");
            $smarty->assign('select_parent_type', "parent_type|" . $p['id']);
        }
        $smarty->assign('select_type', "type|" . $j['categoryname']);
        $joinwheresql.=" WHERE " . ltrim(ltrim($dsql . $xsql), 'OR');
        $joinsql = "  INNER  JOIN  ( SELECT DISTINCT pid FROM " . table('resume_jobs') . $joinwheresql . " ) AS j ON  r.id=j.pid ";
    }

    if (!empty($citycategory)) {
        $dsql = $xsql = "";
        $d = $db->getone("SELECT * FROM " . table('category_district') . " WHERE id='" . intval($citycategory) . "'");
        if (intval($d['parentid']) === 0) {
            $dsql.= " OR r.district =" . intval($citycategory);
        } else {
            $xsql.= " OR r.sdistrict =" . intval($citycategory);
        }
        $smarty->assign('select_district', "district|" . $d['categoryname']);
        $wheresql.=" AND  (" . ltrim(ltrim($dsql . $xsql), 'OR') . ") ";
    }
    if (isset($experience) && !empty($experience)) {
        $e = $db->getone("SELECT * FROM " . table('category') . " WHERE c_id='" . intval($experience) . "'");
        $smarty->assign('select_experience', "experience|" . $e['c_name']);
        $wheresql.=" AND r.experience=" . intval($experience) . " ";
    }
    if (isset($education) && !empty($education)) {
        $e2 = $db->getone("SELECT * FROM " . table('category') . " WHERE c_id='" . intval($education) . "'");
        $smarty->assign('select_education', "education|" . $e2['c_name']);
        $wheresql.=" AND r.education=" . intval($education) . " ";
    }
    if (isset($talent) && !empty($talent)) {
        $talent = $talent == 1 ? 2 : 1;
        $wheresql.=" AND r.talent=" . intval($talent) . " ";
    }
    if (isset($photo) && !empty($photo)) {
        $wheresql.=" AND r.photo='" . intval($photo) . "' ";
    }
    if (isset($key) && !empty($key)) {
        if ($_CFG['resumesearch_purview'] == '2') {
            if ($_SESSION['username'] == '') {
                header("Location: " . url_rewrite('QS_login') . "?url=" . urlencode($_SERVER["REQUEST_URI"]));
            }
        }
        $key = trim($key);
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
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $total_sql = "SELECT  COUNT(*) AS num  FROM  " . $resumetable . " AS r " . $joinsql . $wheresql;
    //var_dump($total_sql);exit;
    //$total_count = get_mem_cache($total_sql, "get_total");
    $total_count = $db->get_total($total_sql);
    $page = new page(array('total' => $total_count, 'perpage' => 10, 'alias' => 'QS_resumelist', 'getarray' => $_GET));
    $currenpage = $page->nowindex;
    $start = abs($currenpage - 1) * $row;
    $smarty->assign('page', $page->show(3));
    $smarty->assign('pagemin', $page->show(7));
    $smarty->assign('total', $total_count);
    $smarty->assign('pagenow', $page->show(6));
    $limit = " LIMIT " . $start . " , " . $row;
    $sql = "SELECT id FROM " . $resumetable . "  AS r" . $joinsql . $wheresql . $orderbysql . $limit;
    $idresult = $db->getall($sql);
    foreach ($idresult as $row) {
        $id[] = $row['id'];
    }
    if (!empty($id)) {
        $wheresql = " WHERE id IN (" . implode(',', $id) . ") ";
        $sql = "SELECT * FROM " . table('resume') . "  AS r " . $wheresql . $orderbysql;
        $result = get_mem_cache($sql, "getall");
        $i = 0;
        $shield_num = 0;
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
            }
            if (in_array($row['id'], $_COOKIE['QS']['view_resume_log'])) {
                $row['checked'] = true;
            } else {
                $row['checked'] = false;
            }
            $row['specialty_'] = strip_tags($row['specialty']);
            $f = !empty($row['intention_jobs']) ? explode(",", $row['intention_jobs']) : array();
            $row['intention_jobs_first'] = $f[0];
            $row['trade_cn'] = cut_str(strip_tags($row['trade_cn']), 10, 0, "..");
            $row['photosrc'] = $row['photo'] ? $_CFG['resume_photo_dir_thumb'] . $row['photo_img'] : $_CFG['resume_photo_dir_thumb'] . "no_photo.gif";
            $row['resume_url'] = url_rewrite('QS_resumeshow', array('id' => $row['id']), true, $row['subsite_id']);
            $row['refreshtime_cn'] = daterange(time(), $row['refreshtime'], 'Y-m-d', "#FF3300");
            $row['age'] = date("Y") - $row['birthdate'];
            $row['index'] = $i++;
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
        }
    } else {
        $list = array();
    }
    $smarty->assign('resume', $list);
    $smarty->display('resume-list.htm');
} elseif ($act == "get_city") {
    $parent_district_id = intval($_GET['id']);
    $sql = "select * from " . table("category_district") . " where parentid = " . $parent_district_id . " order by category_order desc,id asc";
    $ajax_str = $parent_district_id . "-全部||";
    $result_arr = $db->getall($sql);
    foreach ($result_arr as $r) {
        $ajax_str .= $r['id'] . "-" . $r['categoryname'] . "||";
    }
    $ajax_str = trim($ajax_str, "||");
    exit($ajax_str);
} elseif ($act == "get_job_type") {
    $parent_job_id = intval($_GET['id']);
    $sql = "select * from " . table("category_jobs") . " where parentid = " . $parent_job_id . " order by category_order desc,id asc";
    $ajax_str = "";
    $result_arr = $db->getall($sql);
    foreach ($result_arr as $r) {
        $ajax_str .= $r['id'] . "-" . $r['categoryname'] . "||";
    }
    $ajax_str = trim($ajax_str, "||");
    exit($ajax_str);
} elseif ($act == "add_fav") {
    $yid = isset($_REQUEST['yid']) ? $_REQUEST['yid'] : showmsg("你没有选择简历！", 1);
    $add_return = add_favorites($yid, $_SESSION['uid']);
    if ($add_return === "full") {
        showmsg("收藏失败，人才库容量已经超出最大限制！", 1);
    } elseif ($add_return == "0") {
        showmsg("收藏失败，您已收藏过此简历！", 1);
    } else {
        $links[0]['text'] = '返回上一页';
        $links[0]['href'] = 'javascript:history.go(-1)';
        $links[1]['text'] = '查看人才库';
        $links[1]['href'] = '/user/company/company_recruitment.php?act=favorites_list';
        showmsg("添加成功！共添加" . $add_return . "份简历", 3, $links);
    }
}
?>