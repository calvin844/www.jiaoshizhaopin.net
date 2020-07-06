<?php

/*
 * 74cms 文章页面
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../data/config.php');
require_once(dirname(__FILE__) . '/include/admin_common.inc.php');
require_once(ADMIN_ROOT_PATH . 'include/admin_article_fun.php');
//require_once(ADMIN_ROOT_PATH . 'include/admin_category_fun.php');
require_once(ADMIN_ROOT_PATH . 'include/upload.php');

$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'newslist';
$smarty->assign('act', $act);
if ($act == 'newslist') {
    check_permissions($_SESSION['admin_purview'], "article_show");
    require_once(QISHI_ROOT_PATH . 'include/page.class.php');
    $key = isset($_GET['key']) ? trim($_GET['key']) : "";
    $key_type = isset($_GET['key_type']) ? intval($_GET['key_type']) : "";
    $oederbysql = " order BY a.article_order DESC,a.id DESC";
    if ($key && $key_type > 0) {

        if ($key_type === 1)
            $wheresql = " WHERE a.title like '%{$key}%'";
        elseif ($key_type === 2)
            $wheresql = " WHERE a.id =" . intval($key);
    }
    !empty($_GET['parentid']) ? $wheresqlarr['a.parentid'] = intval($_GET['parentid']) : '';
    !empty($_GET['type_id']) ? $wheresqlarr['a.type_id'] = intval($_GET['type_id']) : '';
    !empty($_GET['focos']) ? $wheresqlarr['a.focos'] = intval($_GET['focos']) : '';
    if (!empty($wheresqlarr))
        $wheresql = wheresql($wheresqlarr);
    if (!empty($_GET['settr'])) {
        $settr = strtotime("-" . intval($_GET['settr']) . " day");
        $wheresql = empty($wheresql) ? " WHERE a.addtime> " . $settr : $wheresql . " AND a.addtime> " . $settr;
        $oederbysql = " order BY a.addtime DESC";
    }
    if (!empty($_GET['refreshtime'])) {
        $refreshtime = strtotime("-" . intval($_GET['refreshtime']) . " day");
        $wheresql = empty($wheresql) ? " WHERE a.refreshtime> " . $refreshtime : $wheresql . " AND a.refreshtime> " . $refreshtime;
        $oederbysql = " order BY a.addtime DESC";
    }
    if (!empty($_GET['jobs'])) {
        if ($_GET['jobs'] == 1) {
            $jobs_where = " a.jobs > 0";
        } else {
            $jobs_where = " a.jobs = 0";
        }
        $wheresql = empty($wheresql) ? " WHERE " . $jobs_where : $wheresql . " AND  " . $jobs_where;
        $oederbysql = " order BY a.addtime DESC";
    }
    if (!empty($_GET['order_by'])) {
        $oederbysql = " order BY a." . $_GET['order_by'] . " DESC";
    }
    $joinsql = " LEFT JOIN " . table('article_category') . " AS c ON a.type_id=c.id  LEFT JOIN " . table('article_property') . " AS p ON a.focos=p.id ";
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('article') . " AS a " . $joinsql . $wheresql;
    $page = new page(array('total' => $db->get_total($total_sql), 'perpage' => $perpage));
    $currenpage = $page->nowindex;
    $offset = ($currenpage - 1) * $perpage;
    $article = get_news($offset, $perpage, $joinsql . $wheresql . $oederbysql);
    $smarty->assign('article', $article);
    $smarty->assign('page', $page->show(3));
    $smarty->assign('pageheader', "新闻资讯");
    get_token();
    $smarty->display('article/admin_article.htm');
} elseif ($act == 'migrate_article') {
    $id = $_REQUEST['id'];
    if (empty($id))
        adminmsg("请选择项目！", 1);
    check_token();
    check_permissions($_SESSION['admin_purview'], "article_del");
    if (del_news($id)) {
        adminmsg("删除成功！", 2);
    }
} elseif ($act == 'news_add') {
    check_permissions($_SESSION['admin_purview'], "article_add");
    $smarty->assign('article_category', get_article_category());
    $smarty->assign('job_category', get_job_category(array("parentid" => 0)));
    $smarty->assign('job_categorys', get_job_category(array("parentid" => 1)));
    $smarty->assign('author', $_SESSION['admin_name']);
    $smarty->assign('pageheader', "新闻资讯");
    $smarty->assign('subsite', get_subsite_list());
    $sql = "select * from " . table('category_district') . " where parentid=0  order BY category_order desc,id asc";
    $provinces = $db->getall($sql);
    $smarty->assign('provinces', $provinces);
    get_token();
    $smarty->display('article/admin_article_add.htm');
} elseif ($act == 'addsave') {
    global $_CFG;
    check_permissions($_SESSION['admin_purview'], "article_add");
    check_token();
    $setsqlarr['title'] = trim($_POST['title']) ? trim($_POST['title']) : adminmsg('您没有填写标题！', 1);
    $setsqlarr['type_id'] = !empty($_POST['type_id']) ? intval($_POST['type_id']) : adminmsg('您没有选择分类！', 1);
    $setsqlarr['content'] = !empty($_POST['content']) ? $_POST['content'] : adminmsg('您没有内容！', 1);
    $setsqlarr['email'] = trim($_POST['email']) ? trim($_POST['email']) : "";
    $setsqlarr['district'] = !empty($_POST['province']) ? intval($_POST['province']) : adminmsg('您没有选省份！', 1);
    $setsqlarr['sdistrict'] = intval($_POST['city']);
    $setsqlarr['audit'] = 1;
    $setsqlarr['admin_id'] = $_SESSION['admin_id'];
    if ($setsqlarr['district'] == 0 && $setsqlarr['sdistrict'] > 0) {
        $setsqlarr['district'] = $setsqlarr['sdistrict'];
        $setsqlarr['sdistrict'] = 0;
    }
    $sql = "select * from " . table('category_district') . " where id=" . $setsqlarr['district'];
    $district = $db->getone($sql);
    if ($setsqlarr['sdistrict'] > 0) {
        $sql = "select * from " . table('category_district') . " where id=" . $setsqlarr['sdistrict'];
        $sdistrict = $db->getone($sql);
        $setsqlarr['district_cn'] = $district['categoryname'] . "/" . $sdistrict['categoryname'];
        $district_str = $district['categoryname'] . "-" . $sdistrict['categoryname'];
    } else {
        $setsqlarr['district_cn'] = $district['categoryname'] . "/" . $district['categoryname'];
        $district_str = $district['categoryname'] . "-" . $district['categoryname'];
    }
    $job_str = get_news_job($setsqlarr['content']);
    $des = $setsqlarr['title'] . " " . $district_str . " " . $job_str;
    $city_str = strtr($district_str, "-", ",");
    $keyword = $city_str . "," . $job_str . ",教师,招聘,教师招聘,中国教师招聘网";
    $keyword_arr = explode(",", $keyword);
    $keyword = implode(",", array_unique($keyword_arr));
    $setsqlarr['seo_keywords'] = $keyword;
    $setsqlarr['seo_description'] = $des;
    $i = 1;
    $attachment_str = "";
    if (!empty($_POST['attachment'])) {
        foreach ($_POST['attachment'] as $j) {
            if ($i % 2 != 0) {
                $attachment_str .= $j . "||";
            } else {
                $j = empty($j) ? "" : trim($j);
                $attachment_str .= $j . "##";
            }
            $i++;
        }
    }
    $attachment_str = trim($attachment_str, "##");
    $attachment_str_arr = explode("##", $attachment_str);
    foreach ($attachment_str_arr as $aa) {
        $at = explode("||", $aa);
        $at[0] = trim($at[0]);
        $at[1] = trim($at[1]);
        if ($at[0] != "" && $at[1] != "") {
            $at = implode("||", $at);
            $setsqlarr['attachment'][] = $at;
        }
    }
    if (!empty($setsqlarr['attachment'])) {
        $setsqlarr['attachment'] = implode("##", $setsqlarr['attachment']);
    }
    $setsqlarr['jobs'] = count($_POST['jobs']);
    $setsqlarr['endtime'] = strtotime($_POST['endtime']);
    $setsqlarr['refreshtime'] = time();
    $setsqlarr['tit_color'] = trim($_POST['tit_color']);
    $setsqlarr['tit_b'] = intval($_POST['tit_b']);
    $setsqlarr['author'] = !empty($_POST['author']) ? trim($_POST['author']) : $_CFG['site_name'];
    $setsqlarr['source'] = !empty($_POST['source']) ? trim($_POST['source']) : $_CFG['site_name'];
    $setsqlarr['focos'] = intval($_POST['focos']);
    $setsqlarr['is_display'] = intval($_POST['is_display']);
    $setsqlarr['is_url'] = trim($_POST['is_url']);
    $setsqlarr['article_order'] = intval($_POST['article_order']);
    if ($_FILES['Small_img']['name']) {
        $upfiles_dir.=date("Y/m/d/");
        make_dir($upfiles_dir);
        $Small_img = _asUpFiles($upfiles_dir, "Small_img", 1024 * 2, 'jpg/gif/png', true);
        $makefile = $upfiles_dir . $Small_img;
        make_dir($thumb_dir . date("Y/m/d/"));
        makethumb($makefile, $thumb_dir . date("Y/m/d/"), $thumbwidth, $thumbheight);
        $setsqlarr['Small_img'] = date("Y/m/d/") . $Small_img;
    }
    $setsqlarr['addtime'] = time();
    $setsqlarr['parentid'] = get_article_parentid($setsqlarr['type_id']);
    $setsqlarr['subsite_id'] = intval($_POST['subsite_id']);
    $link[0]['text'] = "继续添加文章";
    $link[0]['href'] = '?act=news_add&type_id_cn=' . trim($_POST['type_id_cn']) . "&type_id=" . $_POST['type_id'];
    $link[1]['text'] = "返回文章列表";
    $link[1]['href'] = '?act=newslist';
    $in_id = inserttable(table('article'), $setsqlarr, 1);
    if (!empty($_POST['jobs'])) {
        foreach ($_POST['jobs'] as $j) {
            $jobs_in_arr['article_id'] = $in_id;
            $jobs_in_arr['topclass'] = 0;
            $jobs_in_arr['category'] = $j[0];
            $jobs_in_arr['subclass'] = $j[1];
            $category = get_job_category(array('id' => $j[1]));
            $jobs_in_arr['category_cn'] = $category[0]['categoryname'];
            $jobs_in_arr['job_name'] = $j[2];
            $jobs_in_arr['amount'] = $j[3];
            inserttable(table('jiaoshi_article_jobs'), $jobs_in_arr);
        }
        updateindex(table('article'), $in_id);
    }
    !$in_id ? adminmsg("添加失败！", 0) : adminmsg("添加成功！", 2, $link);
} elseif ($act == 'article_edit') {
    check_permissions($_SESSION['admin_purview'], "article_edit");
    $id = intval($_GET['id']);
    $sql = "select * from " . table('article') . " where id=" . intval($id) . " LIMIT 1";
    $edit_article = $db->getone($sql);
    if ($edit_article['endtime'] > 0) {
        $edit_article['endtime'] = date("Y-m-d", $edit_article['endtime']);
    }
    $sql = "select * from " . table('jiaoshi_article_jobs') . " where article_id=" . intval($id);
    $jobs_res = $db->getall($sql);
    foreach ($jobs_res as $jr) {
        $jr['job_categorys'] = get_job_category(array("parentid" => $jr['category']));
        $jobs_arr[] = $jr;
    }
    $attachment_arr = "";
    if (!empty($edit_article['attachment'])) {
        $edit_article['attachment'] = explode("##", trim($edit_article['attachment'], "##"));
        foreach ($edit_article['attachment'] as $a) {
            $attachment_arr[] = explode('||', $a);
        }
    }
    $smarty->assign('jobs', $jobs_arr);
    $smarty->assign('job_category', get_job_category(array("parentid" => 0)));
    $smarty->assign('job_categorys', get_job_category(array("parentid" => 1)));
    $smarty->assign('attachment', $attachment_arr);
    $smarty->assign('edit_article', $edit_article);
    $smarty->assign('upfiles_dir', $upfiles_dir);
    $smarty->assign('thumb_dir', $thumb_dir);
    $smarty->assign('article_category', get_article_category());
    $smarty->assign('subsite', get_subsite_list());
    $smarty->assign('pageheader', "新闻资讯");
    $sql = "select * from " . table('category_district') . " where parentid=0  order BY category_order desc,id asc";
    $provinces = $db->getall($sql);
    $provinces_id = $edit_article['district'] == 0 ? $edit_article['sdistrict'] : $edit_article['district'];
    $sql = "select * from " . table('category_district') . " where parentid=" . $provinces_id . "  order BY category_order desc,id asc";
    $cities = $db->getall($sql);
    $smarty->assign('cities', $cities);
    $smarty->assign('provinces', $provinces);
    get_token();
    $smarty->display('article/admin_article_edit.htm');
} elseif ($act == 'article_refresh') {
    //check_permissions($_SESSION['admin_purview'],"article_refresh");
    //check_token();
    $id = $_REQUEST['id'];
    if ($num = article_refresh($id)) {
        adminmsg("刷新成功！", 2);
    } else {
        adminmsg("刷新失败！", 1);
    }
} elseif ($act == 'editsave') {
    check_permissions($_SESSION['admin_purview'], "article_edit");
    check_token();
    $id = intval($_POST['id']);
    $setsqlarr['title'] = trim($_POST['title']) ? trim($_POST['title']) : adminmsg('您没有填写标题！', 1);
    $setsqlarr['type_id'] = !empty($_POST['type_id']) ? intval($_POST['type_id']) : adminmsg('您没有选择分类！', 1);
    $setsqlarr['content'] = !empty($_POST['content']) ? $_POST['content'] : adminmsg('您没有内容！', 1);
    $setsqlarr['email'] = trim($_POST['email']) ? trim($_POST['email']) : "";
    $setsqlarr['district'] = !empty($_POST['province']) ? intval($_POST['province']) : adminmsg('您没有选省份！', 1);
    $setsqlarr['sdistrict'] = intval($_POST['city']);
    if ($setsqlarr['district'] == 0 && $setsqlarr['sdistrict'] > 0) {
        $setsqlarr['district'] = $setsqlarr['sdistrict'];
        $setsqlarr['sdistrict'] = 0;
    }
    $sql = "select * from " . table('category_district') . " where id=" . $setsqlarr['district'];
    $district = $db->getone($sql);
    if ($setsqlarr['sdistrict'] > 0) {
        $sql = "select * from " . table('category_district') . " where id=" . $setsqlarr['sdistrict'];
        $sdistrict = $db->getone($sql);
        $setsqlarr['district_cn'] = $district['categoryname'] . "/" . $sdistrict['categoryname'];
        $district_str = $district['categoryname'] . "-" . $sdistrict['categoryname'];
    } else {
        $setsqlarr['district_cn'] = $district['categoryname'] . "/" . $district['categoryname'];
        $district_str = $district['categoryname'] . "-" . $district['categoryname'];
    }
    $job_str = get_news_job($setsqlarr['content']);
    $des = $setsqlarr['title'] . " " . $district_str . " " . $job_str;
    $city_str = strtr($district_str, "-", ",");
    $keyword = $city_str . "," . $job_str . ",教师,招聘,教师招聘,中国教师招聘网";
    $keyword_arr = explode(",", $keyword);
    $keyword = implode(",", array_unique($keyword_arr));
    $setsqlarr['seo_keywords'] = $keyword;
    $setsqlarr['seo_description'] = $des;

    $i = 1;
    $attachment_str = "";
    if (!empty($_POST['attachment'])) {
        foreach ($_POST['attachment'] as $j) {
            if ($i % 2 != 0) {
                $attachment_str .= $j . "||";
            } else {
                $j = empty($j) ? "" : trim($j);
                $attachment_str .= $j . "##";
            }
            $i++;
        }
    }
    $attachment_str = trim($attachment_str, "##");
    $attachment_str_arr = explode("##", $attachment_str);
    foreach ($attachment_str_arr as $aa) {
        $at = explode("||", $aa);
        $at[0] = trim($at[0]);
        $at[1] = trim($at[1]);
        if ($at[0] != "" && $at[1] != "") {
            $at = implode("||", $at);
            $setsqlarr['attachment'][] = $at;
        }
    }
    if (!empty($setsqlarr['attachment'])) {
        $setsqlarr['attachment'] = implode("##", $setsqlarr['attachment']);
    }
    $setsqlarr['jobs'] = count($_POST['jobs']);
    $setsqlarr['endtime'] = strtotime($_POST['endtime']);
    //$setsqlarr['refreshtime'] = time();
    $setsqlarr['tit_color'] = trim($_POST['tit_color']);
    $setsqlarr['tit_b'] = intval($_POST['tit_b']);
    $setsqlarr['author'] = trim($_POST['author']);
    $setsqlarr['source'] = trim($_POST['source']);
    $setsqlarr['focos'] = intval($_POST['focos']);
    $setsqlarr['is_display'] = intval($_POST['is_display']);
    $setsqlarr['is_url'] = trim($_POST['is_url']);
    $setsqlarr['article_order'] = intval($_POST['article_order']);
    if ($_FILES['Small_img']['name']) {
        $upfiles_dir.=date("Y/m/d/");
        make_dir($upfiles_dir);
        $Small_img = _asUpFiles($upfiles_dir, "Small_img", 1024 * 2, 'jpg/gif/png', true);
        $makefile = $upfiles_dir . $Small_img;
        make_dir($thumb_dir . date("Y/m/d/"));
        makethumb($makefile, $thumb_dir . date("Y/m/d/"), $thumbwidth, $thumbheight);
        $setsqlarr['Small_img'] = date("Y/m/d/") . $Small_img;
    }
    $setsqlarr['parentid'] = get_article_parentid($setsqlarr['type_id']);
    $setsqlarr['subsite_id'] = intval($_POST['subsite_id']);
    $link[0]['text'] = "返回文章列表";
    $link[0]['href'] = '?act=newslist';
    $link[1]['text'] = "查看已修改文章";
    $link[1]['href'] = "?act=article_edit&id=" . $id;
    del_article_jobs($id);
    if (!empty($_POST['jobs'])) {
        foreach ($_POST['jobs'] as $j) {
            $jobs_in_arr['article_id'] = $id;
            $jobs_in_arr['topclass'] = 0;
            $jobs_in_arr['category'] = $j[0];
            $jobs_in_arr['subclass'] = $j[1];
            $category = get_job_category(array('id' => $j[1]));
            $jobs_in_arr['category_cn'] = $category[0]['categoryname'];
            $jobs_in_arr['job_name'] = $j[2];
            $jobs_in_arr['amount'] = $j[3];
            inserttable(table('jiaoshi_article_jobs'), $jobs_in_arr);
        }
    }
    !updatetable(table('article'), $setsqlarr, " id=" . $id . "") ? adminmsg("修改失败！", 0) : adminmsg("修改成功！", 2, $link);
} elseif ($act == 'del_img') {
    check_token();
    $id = intval($_GET['id']);
    $img = $_GET['img'];
    $img = str_replace("../", "***", $img);
    $sql = "update " . table('article') . " set Small_img='' where id=" . $id . " LIMIT 1";
    $db->query($sql);
    @unlink($upfiles_dir . $img);
    @unlink($thumb_dir . $img);
    adminmsg("删除缩略图成功！", 2);
} elseif ($act == 'property') {
    check_permissions($_SESSION['admin_purview'], "article_property");
    $smarty->assign('pageheader', "新闻资讯");
    get_token();
    $smarty->display('article/admin_article_property.htm');
} elseif ($act == 'property_add') {
    check_permissions($_SESSION['admin_purview'], "article_property");
    $smarty->assign('pageheader', "新闻资讯");
    get_token();
    $smarty->display('article/admin_article_property_add.htm');
} elseif ($act == 'add_property_save') {
    check_permissions($_SESSION['admin_purview'], "article_property");
    check_token();
    $num = 0;
    if (is_array($_POST['categoryname']) && count($_POST['categoryname']) > 0) {
        for ($i = 0; $i < count($_POST['categoryname']); $i++) {
            if (!empty($_POST['categoryname'][$i])) {
                $setsqlarr['categoryname'] = trim($_POST['categoryname'][$i]);
                $setsqlarr['category_order'] = intval($_POST['category_order'][$i]);
                !inserttable(table('article_property'), $setsqlarr) ? adminmsg("添加失败！", 0) : "";
                $num = $num + $db->affected_rows();
            }
        }
    }
    if ($num == 0) {
        adminmsg("添加失败,数据不完整", 1);
    } else {
        $link[0]['text'] = "返回属性管理页面";
        $link[0]['href'] = '?act=property';
        $link[1]['text'] = "继续添加属性";
        $link[1]['href'] = "?act=property_add";
        adminmsg("添加成功！共添加" . $num . "个分类", 2, $link);
    }
} elseif ($act == 'del_property') {
    check_permissions($_SESSION['admin_purview'], "article_property");
    check_token();
    $id = $_REQUEST['id'];
    if ($num = del_property($id)) {
        adminmsg("删除成功！共删除" . $num . "个分类", 2);
    } else {
        adminmsg("删除失败！", 1);
    }
} elseif ($act == 'edit_property') {
    check_permissions($_SESSION['admin_purview'], "article_property");
    $id = intval($_GET['id']);
    $smarty->assign('property', get_article_property_one($id));
    $smarty->assign('pageheader', "新闻资讯");
    get_token();
    $smarty->display('article/admin_article_property_edit.htm');
} elseif ($act == 'edit_property_save') {
    check_permissions($_SESSION['admin_purview'], "article_property");
    check_token();
    $id = intval($_POST['id']);
    $setsqlarr['categoryname'] = trim($_POST['categoryname']) ? trim($_POST['categoryname']) : adminmsg('请填写分类名称！', 1);
    $setsqlarr['category_order'] = intval($_POST['category_order']);
    $link[0]['text'] = "查看修改结果";
    $link[0]['href'] = '?act=edit_property&id=' . $id;
    $link[1]['text'] = "返回属性管理";
    $link[1]['href'] = '?act=property';
    !updatetable(table('article_property'), $setsqlarr, " id=" . $id . "") ? adminmsg("修改失败！", 0) : adminmsg("修改成功！", 2, $link);
} elseif ($act == 'category') {
    check_permissions($_SESSION['admin_purview'], "article_category");
    $smarty->assign('pageheader', "新闻资讯");
    get_token();
    $smarty->display('article/admin_article_category.htm');
} elseif ($act == 'category_add') {
    check_permissions($_SESSION['admin_purview'], "article_category");
    $parentid = !empty($_GET['parentid']) ? intval($_GET['parentid']) : '0';
    $smarty->assign('pageheader', "新闻资讯");
    get_token();
    $smarty->display('article/admin_article_category_add.htm');
} elseif ($act == 'add_category_save') {
    check_permissions($_SESSION['admin_purview'], "article_category");
    check_token();
    $num = 0;
    if (is_array($_POST['categoryname']) && count($_POST['categoryname']) > 0) {
        for ($i = 0; $i < count($_POST['categoryname']); $i++) {
            if (!empty($_POST['categoryname'][$i])) {
                $setsqlarr['categoryname'] = trim($_POST['categoryname'][$i]);
                $setsqlarr['parentid'] = intval($_POST['parentid'][$i]);
                $setsqlarr['category_order'] = intval($_POST['category_order'][$i]);
                $setsqlarr['title'] = $_POST['title'][$i];
                $setsqlarr['description'] = $_POST['description'][$i];
                $setsqlarr['keywords'] = $_POST['keywords'][$i];
                if ($_FILES['service_img']['name']) {
                    $upfiles_dir .= "category_service_img/";
                    make_dir($upfiles_dir);
                    $service_img = _asUpFiles($upfiles_dir, "service_img", 1024 * 2, 'jpg/gif/png', true);
                    $setsqlarr['service_img'] = "/data/images/category_service_img/" . $service_img;
                }
                $setsqlarr['service_txt'] = $_POST['service_txt'][$i];
                !inserttable(table('article_category'), $setsqlarr) ? adminmsg("添加失败！", 0) : "";
                $num = $num + $db->affected_rows();
            }
        }
    }
    if ($num == 0) {
        adminmsg("添加失败,数据不完整", 1);
    } else {
        $link[0]['text'] = "返回分类管理";
        $link[0]['href'] = '?act=category';
        $link[1]['text'] = "继续添加分类";
        $link[1]['href'] = "?act=category_add";
        adminmsg("添加成功！共添加" . $num . "个分类", 2, $link);
    }
} elseif ($act == 'del_category') {
    check_permissions($_SESSION['admin_purview'], "article_category");
    check_token();
    $id = $_REQUEST['id'];
    if ($num = del_category($id)) {
        adminmsg("删除成功！共删除" . $num . "个分类", 2);
    } else {
        adminmsg("删除失败！", 1);
    }
} elseif ($act == 'edit_category') {
    check_permissions($_SESSION['admin_purview'], "article_category");
    $id = intval($_GET['id']);
    $smarty->assign('category', get_article_category_one($id));
    $smarty->assign('pageheader', "新闻资讯");
    get_token();
    $smarty->display('article/admin_article_category_edit.htm');
} elseif ($act == 'edit_category_save') {
    check_permissions($_SESSION['admin_purview'], "article_category");
    check_token();
    $id = intval($_POST['id']);
    $setsqlarr['parentid'] = trim($_POST['parentid']) ? intval($_POST['parentid']) : 0;
    $setsqlarr['categoryname'] = trim($_POST['categoryname']) ? trim($_POST['categoryname']) : adminmsg('请填写分类名称！', 1);
    $setsqlarr['category_order'] = !empty($_POST['category_order']) ? intval($_POST['category_order']) : 0;
    $setsqlarr['title'] = $_POST['title'];
    $setsqlarr['description'] = $_POST['description'];
    $setsqlarr['keywords'] = $_POST['keywords'];
    if ($_FILES['service_img']['name']) {
        $upfiles_dir .= "category_service_img/";
        make_dir($upfiles_dir);
        $service_img = _asUpFiles($upfiles_dir, "service_img", 1024 * 2, 'jpg/gif/png', true);
        $setsqlarr['service_img'] = "/data/images/category_service_img/" . $service_img;
    }
    $setsqlarr['service_txt'] = $_POST['service_txt'];
    $link[0]['text'] = "查看修改结果";
    $link[0]['href'] = '?act=edit_category&id=' . $id;
    $link[1]['text'] = "返回分类管理";
    $link[1]['href'] = '?act=category';
    if (!updatetable(table('article_category'), $setsqlarr, " id='" . $id . "'")) {
        adminmsg("修改失败！", 0);
    } else {
        $set_type_sqlarr['parentid'] = $setsqlarr['parentid'];
        updatetable(table('article'), $set_type_sqlarr, " type_id='" . $id . "'");
        adminmsg("修改成功！", 2, $link);
    }
} elseif ($act == 'ajax_job_category') {
    $parent_id = intval($_GET['id']);
    $result_arr = get_job_category(array("parentid" => $parent_id));
    foreach ($result_arr as $k => $r) {
        $select = $k == 0 ? "selected" : "";
        $ajax_str .= "<option " . $select . " value='" . $r['id'] . "'>" . $r['categoryname'] . "</option>";
    }
    exit($ajax_str);
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

?>