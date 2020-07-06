<?php

/*
 * 74cms ��ҵ�û�ת��
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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
$rs = ms_getall($sql);   //ִ���������

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
    $author = escape_str($author) == "calvin" ? "�й���ʦ��Ƹ��" : escape_str($author);
    $setsqlarr['author'] = !empty($author) ? $author : "�й���ʦ��Ƹ��";
    $setsqlarr['source'] = !empty($newfrom) ? escape_str($newfrom) : "�й���ʦ��Ƹ��";
    $setsqlarr['focos'] = 1;
    $setsqlarr['is_display'] = 1;
    $cname = escape_str($rs['Info_City']);
    $aname = escape_str($rs['Info_Area']);
    if (empty($cname) || $cname == "����" || $cname == "����" || $cname == "����") {
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
    $keyword = $city_str . "," . $job_str . ",��ʦ,��Ƹ,��ʦ��Ƹ,�й���ʦ��Ƹ��";
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
    echo $thisid . "���<br/>";
}

if (empty($thisid)) {
    exit("���������Ѿ�ȫ��ת����ɣ�");
}

$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$html.='<html xmlns="http://www.w3.org/1999/xhtml">';
$html.='<head>';
$html.='<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
$html.='<title>����</title>';
$html.='</head>';
$html.='<body>';
$m = $m - 1;
$t = intval($_GET['t']) + $m;
$html.='�ɹ�ת��' . $size . 'ƪ����(�ܼ�ת����' . $t . 'ƪ)���벻Ҫ�رջ��뿪ҳ�棬3��������' . $size . 'ƪ���µ�ת����id���(' . $thisid . ')';
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
        case "��Сѧ":
            $type_name = "��Сѧ";
            break;
        case "�׶�":
            $type_name = "�׶�";
            break;
        case "ְ����ѵ":
            $type_name = "ְ������ѵ";
            break;
        case "��УѧԺ":
            $type_name = "��У�����Ժ��";
            break;
        case "��ҵ��λ":
            $type_name = "��ҵ��λ��Ƹ";
            break;
        case "֪����ҵ":
            $type_name = "��ҵ��λ��Ƹ";
            break;
        case "ҵ����Ѷ":
            $type_name = "��ҵ��λ��Ƹ";
            break;
        case "����":
            $type_name = "��ҵ��λ��Ƹ";
            break;
        default:
            $type_name = "�ۺ�";
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
    if ($tmp_str == "��" || $tmp_str == "ʡ" || $tmp_str == "��") {
        if (!strstr($aname, "����" . $tmp_str)) {
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
        if (empty($aname) || $aname == "����") {
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

//��Ӳ������µ�����Ϣ��¼_Calvin_20140623
function get_news_city_arr($cname, $aname) {
    global $db;
    $cname = trim($cname);
    $aname = trim($aname);
    $tmp_str = substr($cname, -2);
    if ($tmp_str == "��" || $tmp_str == "ʡ" || $tmp_str == "��") {
        if (!strstr($cname, "����" . $tmp_str)) {
            $tmp_name = substr($cname, 0, strlen($cname) - 2);
            if (strlen($tmp_name) > 3) {
                $cname = $tmp_name;
            }
        }
    }
    $tmp_str = substr($aname, -2);
    if ($tmp_str == "��" || $tmp_str == "ʡ" || $tmp_str == "��") {
        if (!strstr($aname, "����" . $tmp_str)) {
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
            //�����������û����Ӧ���ݣ��Ե��������ݽ��в���
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
            if ($k['name'] == "��ѧ" || $k['name'] == "Сѧ") {
                if (!strpos($job_str, "��Сѧ")) {
                    $job_str = $job_str . $k['name'] . ",";
                }
            } else {
                $job_str = $job_str . $k['name'] . ",";
            }
        }
    }
    $sql = "select * from " . table('category_jobs') . " where categoryname <> '����' and categoryname <> '����'";
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