<?php

/*
 * 74cms 企业用户转换
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
set_time_limit(0);
define('IN_QISHI', true);
require_once('../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$t1 = exectime();
require_once('conversion_cngfig.php');
$size = 1000;
$id = intval($_GET['id']);

$sql = "select TOP " . $size . " * from pH_New_Info where NewId>" . $id . " and Cityid=1037 ORDER BY NewId asc";
$rs = ms_getall($sql);   //执行搜索语句

foreach ($rs as $rs) {
    $thisid = $rs['NewId'];
    $sql = "select id from " . table('article') . " where id=" . intval($rs['NewId']) . " LIMIT 1";
    $newsinfo = $db->getone($sql);
    if (!empty($newsinfo)) {
        //del_news($thisid);
        $do_type = "u";
    }
    $setsqlarr['id'] = $rs['NewId'];
    $setsqlarr['title'] = escape_str(strtr($rs['Title'], " ", ","));
    $news_ids = get_news_typeid($rs['SubTypeName'], $setsqlarr['title']);
    $setsqlarr['type_id'] = $news_ids['id'];
    $setsqlarr['parentid'] = $news_ids['parentid'];
    $setsqlarr['content'] = escape_str($rs['Content']);
    $pattern = '/([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9])+/i';
    preg_match_all($pattern, $setsqlarr['content'], $matches);
    if (!empty($matches)) {
        $setsqlarr['email'] = $matches[0][0];
    }
    $setsqlarr['subsite_id'] = 0;
    $setsqlarr['tit_b'] = 0;
    $author = trim($rs['Author']);
    $newfrom = trim($rs['NewFrom']);
    $author = escape_str($author) == "calvin" ? "中国教师招聘网" : escape_str($author);
    $setsqlarr['author'] = !empty($author) ? $author : "中国教师招聘网";
    $setsqlarr['source'] = !empty($newfrom) ? escape_str($newfrom) : "中国教师招聘网";
    $setsqlarr['focos'] = 1;
    $setsqlarr['is_display'] = 1;
    $cname = escape_str($rs['Info_City']);
    $aname = escape_str($rs['Info_Area']);
    if (empty($cname) || $cname == "不限" || $cname == "其他" || $cname == "其它") {
        $cname = match_other_city($setsqlarr['id'], $setsqlarr['title'], $aname);
        if (!$cname) {
            continue;
        }
    }
    $caty = get_news_city_arr($cname, $aname);
    $setsqlarr['district'] = $caty['district'];
    $setsqlarr['sdistrict'] = $caty['sdistrict'];
    $setsqlarr['district_cn'] = $caty['district_cn'] . "/" . $caty['sdistrict_cn'];
    $city_str = $setsqlarr['district_cn'];
    $job_str = get_news_job($setsqlarr['content']);
    if (!empty($rs['InfoDescription'])) {
        $des = $setsqlarr['title'] . " " . $city_str . " " . $job_str . " " . escape_str($rs['InfoDescription']);
    } else {
        $des = $setsqlarr['title'] . " " . $city_str . " " . $job_str;
    }
    $city_str = strtr($city_str, "/", ",");
    $keyword = $city_str . "," . $job_str . ",教师,招聘,教师招聘,中国教师招聘网";
    $keyword_arr = explode(",", $keyword);
    $keyword = implode(",", array_unique($keyword_arr));
    $setsqlarr['seo_keywords'] = $keyword;
    $setsqlarr['seo_description'] = $des;
    $setsqlarr['article_order'] = 0;
    $setsqlarr['addtime'] = strtotime($rs['DateAndTime']);
    $setsqlarr['refreshtime'] = $setsqlarr['addtime'];
    $setsqlarr['click'] = $rs['Hits'];
    if ($do_type == "u") {
        $wheresqlarr = " id  = " . $setsqlarr['id'];
        updatetable(table('article'), $setsqlarr, $wheresqlarr);
    } else {
        inserttable(table('article'), $setsqlarr);
    }
    $sql = "select id from " . table("jiaoshi_article_jobs_index") . " where parent_id  = " . $setsqlarr['id'] . " and type = 'article' limit 1";
    $has = $db->getone($sql);
    if (empty($has['id'])) {
        $in_arr['parent_id'] = $setsqlarr['id'];
        $in_arr['p_id'] = 0;
        $in_arr['type'] = "article";
        $in_arr['topclass'] = 0;
        $in_arr['category'] = 0;
        $in_arr['subclass'] = 0;
        $in_arr['district'] = $setsqlarr['district'];
        $in_arr['sdistrict'] = $setsqlarr['sdistrict'];
        $in_arr['addtime'] = $setsqlarr['addtime'];
        $in_arr['refreshtime'] = $setsqlarr['refreshtime'];
        inserttable(table('jiaoshi_article_jobs_index'), $in_arr);
    } else {
        $in_arr['district'] = $setsqlarr['district'];
        $in_arr['sdistrict'] = $setsqlarr['sdistrict'];
        $in_arr['addtime'] = $setsqlarr['addtime'];
        $in_arr['refreshtime'] = $setsqlarr['refreshtime'];
        $wheresqlarr = " parent_id  = " . $setsqlarr['id'] . " and type = 'article'";
        updatetable(table('jiaoshi_article_jobs_index'), $in_arr, $wheresqlarr);
    }
    echo $thisid . "完成<br/>";
}

if (empty($thisid)) {
    exit("所有文章已经全部转换完成！");
}

$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$html.='<html xmlns="http://www.w3.org/1999/xhtml">';
$html.='<head>';
$html.='<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
$html.='<title>文章</title>';
$html.='</head>';
$html.='<body>';
$m = $m - 1;
$t = intval($_GET['t']) + $m;
$html.='成功转换' . $size . '篇文章(总计转换了' . $t . '篇)，请不要关闭或离开页面，3秒后进入下' . $size . '篇文章的转换，id标记(' . $thisid . ')';
$html.='<script type="text/javascript" language="javascript">';
$html.='function reloadyemian()';
$html.='{ ';
$html.='window.location.href="conversion_news.php?t=' . $t . '&id=' . $thisid . '"; ';
$html.='} ';
$html.='window.setTimeout("reloadyemian();",3000); ';
$html.='</script> ';
$html.='</body></html>';
$url_in_arr = array("url" => "conversion_news.php?t=" . $t . "&id=" . $thisid, "addtime" => strtotime("now"));
$cons_id = inserttable(table('cons'), $url_in_arr, TRUE);
echo $html;

function get_type_name($type_name) {
    switch ($type_name) {
        case "中小学":
            $type_name = "中小学";
            break;
        case "幼儿":
            $type_name = "幼儿";
            break;
        case "职教培训":
            $type_name = "职教与培训";
            break;
        case "高校学院":
            $type_name = "高校与科研院所";
            break;
        case "事业单位":
            $type_name = "事业单位招聘";
            break;
        case "知名企业":
            $type_name = "事业单位招聘";
            break;
        case "业界资讯":
            $type_name = "事业单位招聘";
            break;
        case "其他":
            $type_name = "事业单位招聘";
            break;
        default:
            $type_name = "综合";
            break;
    }
    return $type_name;
}

function get_news_typeid($type_name, $title) {
    global $db;
    $type_name = escape_str(trim($type_name));
    if (strstr($type_name, ",")) {
        $type_arr = explode(",", $type_name);
        foreach ($type_arr as $t) {
            if (strstr($title, $t)) {
                $type_name = $t;
                break;
            }
        }
    }
    $type_name = get_type_name($type_name);
    $sql = "select id,parentid from " . table('article_category') . " where categoryname='" . $type_name . "' and parentid=0 LIMIT 1";
    $type_info = $db->getone($sql);
    if (empty($type_info['id'])) {
        $in_arr = array('parentid' => 0, 'categoryname' => $type_name);
        $type_info['id'] = inserttable(table('article_category'), $in_arr, TRUE);
        $type_info['parentid'] = 0;
    }
    return $type_info;
}

function match_other_city($id, $title, $aname) {
    global $db;
    $aname = trim($aname);
    $tmp_str = substr($aname, -2);
    if ($tmp_str == "市" || $tmp_str == "省" || $tmp_str == "县") {
        if (!strstr($aname, "自治" . $tmp_str)) {
            $tmp_name = substr($aname, 0, strlen($aname) - 2);
            if (strlen($tmp_name) > 3) {
                $aname = $tmp_name;
            }
        }
    }
    $sql = "select id,parentid,categoryname from " . table('category_district') . " where parentid > 0";
    $info = $db->getall($sql);
    foreach ($info as $i) {
        if (strstr($title, $i['categoryname'])) {
            $return = $i;
            break;
        }
    }
    if (empty($return)) {
        if (empty($aname) || $aname == "不限") {
            $error_in_arr['pid'] = $id;
            $error_in_arr['type'] = 'news';
            $error_in_arr['addtime'] = time();
            $sql = "select * from " . table('91_city_error') . " where pid = " . $error_in_arr['pid'] . " and type = '" . $error_in_arr['type'] . "' limit 1";
            $has = $db->getone($sql);
            if (!empty($has['id'])) {
                $wheresqlarr = " id =" . $has['id'];
                updatetable(table('91_city_error'), $error_in_arr, $wheresqlarr);
            } else {
                inserttable(table('91_city_error'), $error_in_arr);
            }
            return FALSE;
        }
        return $aname;
    }
    return $return['categoryname'];
}

//添加补充文章地区信息记录_Calvin_20140623
function get_news_city_arr($cname, $aname) {
    global $db;
    $cname = trim($cname);
    $aname = trim($aname);
    $tmp_str = substr($cname, -2);
    if ($tmp_str == "市" || $tmp_str == "省" || $tmp_str == "县") {
        if (!strstr($cname, "自治" . $tmp_str)) {
            $tmp_name = substr($cname, 0, strlen($cname) - 2);
            if (strlen($tmp_name) > 3) {
                $cname = $tmp_name;
            }
        }
    }
    $tmp_str = substr($aname, -2);
    if ($tmp_str == "市" || $tmp_str == "省" || $tmp_str == "县") {
        if (!strstr($aname, "自治" . $tmp_str)) {
            $tmp_name = substr($aname, 0, strlen($aname) - 2);
            if (strlen($tmp_name) > 3) {
                $aname = $tmp_name;
            }
        }
    }
    if (!empty($cname)) {
        $sql = "select id,parentid,categoryname from " . table('category_district');
        $info = $db->getall($sql);
        $return = locoyspider_search_str($info, $cname, "categoryname", 60);
        if (!$return) {
            //如果地区表里没有相应数据，对地区表数据进行补充
            $sql = "select id,categoryname from " . table('category_district') . " where parentid = 0";
            $info_a = $db->getall($sql);
            $return_a = locoyspider_search_str($info_a, $aname, "categoryname", 60);
            if (!$return_a) {
                $add_area = array("parentid" => 0, "categoryname" => $aname);
                $return_a['id'] = inserttable(table('category_district'), $add_area, true);
                access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_city_pinyin.php?id=" . $return_a['id']);
                if ($cname == $aname) {
                    $return['id'] = $return_a['id'];
                    $return['parentid'] = 0;
                    $return['parentname'] = $aname;
                    $return['categoryname'] = $aname;
                } else {
                    $add_city = array("parentid" => $return_a['id'], "categoryname" => $cname);
                    $c_id = inserttable(table('category_district'), $add_city, true);
                    access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_city_pinyin.php?id=" . $c_id);
                    $return['id'] = $c_id;
                    $return['parentid'] = $return_a['id'];
                    $return['parentname'] = $return_a['categoryname'];
                    $return['categoryname'] = $cname;
                }
            } else {
                $add_city = array("parentid" => $return_a['id'], "categoryname" => $cname);
                $c_id = inserttable(table('category_district'), $add_city, true);
                access_url("http://" . $_SERVER['HTTP_HOST'] . "/conversion/conversion_city_pinyin.php?id=" . $c_id);
                $return['parentid'] = $return_a['id'];
                $return['parentname'] = $return_a['categoryname'];
                $return['id'] = $c_id;
                $return['categoryname'] = $cname;
            }
        } else {
            if ($cname != $aname) {
                $sql = "select categoryname from " . table('category_district') . " where id = " . $return['parentid'];
                $area_info = $db->getone($sql);
                $return['parentname'] = $area_info['categoryname'];
            } else {
                $return['parentname'] = $cname;
            }
        }
    }
    if ($return['parentid'] == 0 && $return['id'] > 0) {
        $return['parentid'] = $return['id'];
        $return['id'] = 0;
    }
    return array("district" => $return['parentid'], "district_cn" => $return['parentname'], "sdistrict" => $return['id'], "sdistrict_cn" => $return['categoryname']);
}

function get_news_job($content) {
    global $db;
    $job_str = "";
    $sql = "SELECT name FROM " . table("jiaoshi_keyword_tag") . " ORDER BY id ASC";
    $key_arr = $db->getall($sql);
    foreach ($key_arr as $k) {
        if (strpos($content, $k['name']) && !strpos($job_str, $k['name'])) {
            if ($k['name'] == "中学" || $k['name'] == "小学") {
                if (!strpos($job_str, "中小学")) {
                    $job_str = $job_str . $k['name'] . ",";
                }
            } else {
                $job_str = $job_str . $k['name'] . ",";
            }
        }
    }
    $sql = "select * from " . table('category_jobs') . " where categoryname <> '其他' and categoryname <> '其它'";
    $c_jobs = $db->getall($sql);
    foreach ($c_jobs as $k) {
        if (strpos($content, $k['categoryname']) && !strpos($job_str, $k['categoryname'])) {
            $job_str = $job_str . $k['categoryname'] . ",";
        }
    }
    return trim($job_str, ",");
}

function del_news($id) {
    global $db, $thumb_dir, $upfiles_dir;
    if (!is_array($id))
        $id = array($id);
    foreach ($id as $val) {
        $sql_img = "select Small_img from " . table('article') . " where id=" . intval($val) . " LIMIT 1";
        $y_img = $db->getone($sql_img);
        @unlink($upfiles_dir . "/" . $y_img['Small_img']);
        @unlink($thumb_dir . $y_img['Small_img']);
        //$db->query("Delete from  " . table('article') . " where id=" . intval($val) . " LIMIT 1");
        //$db->query("Delete from  " . table('jiaoshi_article_jobs') . " where article_id=" . intval($val));
        //$db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where parent_id=" . intval($val) . " and type = 'article'");
    }
    return true;
}

?>