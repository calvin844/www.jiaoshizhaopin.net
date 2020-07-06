<?php

/*
 * 74cms 培训机构会员中心
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/train_common.php');
$smarty->assign('leftmenu', "info");
if ($act == 'train_profile') {
    $smarty->assign('title', '机构资料管理 - 培训会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('train_profile', $train_profile);
    $smarty->display('member_train/train_profile.htm');
} elseif ($act == 'train_profile_save') {
    $uid = intval($_SESSION['uid']);
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['trainname'] = trim($_POST['trainname']) ? trim($_POST['trainname']) : showmsg('您没有输入机构名称！', 1);
    $setsqlarr['nature'] = trim($_POST['nature']) ? intval($_POST['nature']) : showmsg('请选择机构性质！', 1);
    $setsqlarr['nature_cn'] = trim($_POST['nature_cn']);
    $setsqlarr['founddate'] = intval(convert_datefm($_POST['founddate'], 2));
    if (empty($setsqlarr['founddate'])) {
        showmsg('请填写成立时间！时间格式：YYYY-MM-DD', 1);
    }
    if ($setsqlarr['founddate'] >= time()) {
        showmsg('成立时间不能大于今天', 1);
    }
    $setsqlarr['district'] = intval($_POST['district']) > 0 ? intval($_POST['district']) : showmsg('请选择所属地区！', 1);
    $setsqlarr['sdistrict'] = intval($_POST['sdistrict']);
    $setsqlarr['district_cn'] = trim($_POST['district_cn']);
    $setsqlarr['address'] = trim($_POST['address']) ? trim($_POST['address']) : showmsg('请填写通讯地址！', 1);
    $setsqlarr['contact'] = trim($_POST['contact']) ? trim($_POST['contact']) : showmsg('请填写联系人！', 1);
    $setsqlarr['telephone'] = trim($_POST['telephone']) ? trim($_POST['telephone']) : showmsg('请填写联系电话！', 1);
    $setsqlarr['email'] = trim($_POST['email']) ? trim($_POST['email']) : showmsg('请填写联系邮箱！', 1);
    $setsqlarr['website'] = trim($_POST['website']);
    $setsqlarr['contents'] = trim($_POST['contents']) ? trim($_POST['contents']) : showmsg('请填写机构简介！', 1);
    check_word($_CFG['filter'], $_POST['contents']) ? showmsg($_CFG['filter_tips'], 0) : '';
    $setsqlarr['teacherpower'] = trim($_POST['teacherpower']) ? trim($_POST['teacherpower']) : showmsg('请填写师资力量！', 1);
    check_word($_CFG['filter'], $_POST['teacherpower']) ? showmsg($_CFG['filter_tips'], 0) : '';
    $setsqlarr['achievement'] = trim($_POST['achievement']) ? trim($_POST['achievement']) : showmsg('请填写主要业绩！', 1);
    check_word($_CFG['filter'], $_POST['achievement']) ? showmsg($_CFG['filter_tips'], 0) : '';
    $setsqlarr['yellowpages'] = intval($_POST['yellowpages']);

    $setsqlarr['contact_show'] = intval($_POST['contact_show']);
    $setsqlarr['email_show'] = intval($_POST['email_show']);
    $setsqlarr['telephone_show'] = intval($_POST['telephone_show']);
    $setsqlarr['address_show'] = intval($_POST['address_show']);

    $link[0]['text'] = "查看修改结果";
    $link[0]['href'] = '?act=train_profile';
    $link[1]['text'] = "发布培训课程";
    $link[1]['href'] = "train_course.php?act=addcourse";
    $link[2]['text'] = "会员中心首页";
    $link[2]['href'] = "train_index.php?";
    if ($_CFG['train_repeat'] == '0') {
        $info = $db->getone("SELECT uid FROM " . table('train_profile') . " WHERE trainname ='{$setsqlarr['trainname']}' AND uid<>'{$_SESSION['uid']}' LIMIT 1");
        if (!empty($info)) {
            showmsg("{$setsqlarr['trainname']}已经存在，同机构信息不能重复注册", 1);
        }
    }
    if ($train_profile) {
        $_CFG['audit_edit_train'] <> '-1' ? $setsqlarr['audit'] = intval($_CFG['audit_edit_train']) : $train_profile['audit'];
        if (updatetable(table('train_profile'), $setsqlarr, " uid='{$uid}'")) {
            $coursearr['trainname'] = $setsqlarr['trainname'];
            if (!updatetable(table('course'), $coursearr, " uid=" . $setsqlarr['uid'] . ""))
                showmsg('修改培训机构名称出错！', 0);
            if (!updatetable(table('train_teachers'), $coursearr, " uid=" . $setsqlarr['uid'] . ""))
                showmsg('修改培训机构名称出错！', 0);
            write_memberslog($_SESSION['uid'], $_SESSION['utype'], 8101, $_SESSION['username'], "修改培训机构资料");
            showmsg("保存成功！", 2, $link);
        }
        else {
            showmsg("保存失败！", 0);
        }
    } else {
        $setsqlarr['audit'] = intval($_CFG['audit_add_train']);
        $setsqlarr['addtime'] = $timestamp;
        $setsqlarr['refreshtime'] = $timestamp;
        if (inserttable(table('train_profile'), $setsqlarr)) {
            write_memberslog($_SESSION['uid'], $_SESSION['utype'], 8100, $_SESSION['username'], "完善培训机构资料");
            showmsg("保存成功！", 2, $link);
        } else {
            showmsg("保存失败！", 0);
        }
    }
} elseif ($act == 'train_auth') {
    $link[0]['text'] = "完善机构资料";
    $link[0]['href'] = '?act=train_profile';
    $link[1]['text'] = "管理首页";
    $link[1]['href'] = 'train_index.php';
    if (empty($train_profile['trainname']))
        showmsg("请完善您的机构资料再上传营业执照！", 1, $link);
    $smarty->assign('title', '营业执照 - 培训机构会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('points', get_cache('points_rule'));
    $smarty->assign('train_profile', $train_profile);
    $smarty->display('member_train/train_auth.htm');
}
elseif ($act == 'train_auth_save') {
    require_once(QISHI_ROOT_PATH . 'include/upload.php');
    $setsqlarr['license'] = trim($_POST['license']) ? trim($_POST['license']) : showmsg('您没有输入营业执照注册号！', 1);
    $setsqlarr['audit'] = 2; //添加默认审核中..
    !$_FILES['certificate_img']['name'] ? showmsg('请上传图片！', 1) : "";
    $certificate_dir = "../../data/" . $_CFG['updir_train_certificate'] . "/" . date("Y/m/d/");
    make_dir($certificate_dir);
    $setsqlarr['certificate_img'] = _asUpFiles($certificate_dir, "certificate_img", $_CFG['certificate_train_max_size'], 'gif/jpg/bmp/png', true);
    if ($setsqlarr['certificate_img']) {
        /*
          3.5新增打水印start
         */
        if (extension_loaded('gd')) {
            include_once(QISHI_ROOT_PATH . 'include/watermark.php');
            $font_dir = QISHI_ROOT_PATH . "data/contactimgfont/cn.ttc";
            if (file_exists($font_dir)) {
                $tpl = new watermark;
                $tpl->img($certificate_dir . $setsqlarr['certificate_img'], gbk_to_utf8($_CFG['site_name']), $font_dir, 15, 0);
            }
        }
        /*
          3.5新增end
         */
        $setsqlarr['certificate_img'] = date("Y/m/d/") . $setsqlarr['certificate_img'];
        $auth = $train_profile;
        @unlink("../../data/" . $_CFG['updir_train_certificate'] . "/" . $auth['certificate_img']);
        $wheresql = "uid='" . $_SESSION['uid'] . "'";
        write_memberslog($_SESSION['uid'], 4, 8102, $_SESSION['username'], "上传了培训机构营业执照");
        !updatetable(table('train_profile'), $setsqlarr, $wheresql) ? showmsg('保存失败！', 1) : showmsg('保存成功，请耐心等待管理员审核！', 2);
    } else {
        showmsg('保存失败！', 1);
    }
} elseif ($act == 'train_logo') {
    $link[0]['text'] = "完善机构资料";
    $link[0]['href'] = '?act=train_profile';
    $link[1]['text'] = "会员中心首页";
    $link[1]['href'] = 'train_index.php';
    if (empty($train_profile['trainname']))
        showmsg("请完善您的机构资料再上传机构LOGO！", 1, $link);
    $smarty->assign('title', '机构LOGO - 培训会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('train_profile', $train_profile);
    $smarty->assign('rand', rand(1, 100));
    $smarty->display('member_train/train_logo.htm');
}
elseif ($act == 'train_logo_save') {
    require_once(QISHI_ROOT_PATH . 'include/upload.php');
    !$_FILES['logo']['name'] ? showmsg('请上传图片！', 1) : "";
    $uplogo_dir = "../../data/train_logo/" . date("Y/m/d/");
    make_dir($uplogo_dir);
    $setsqlarr['logo'] = _asUpFiles($uplogo_dir, "logo", $_CFG['logo_train_max_size'], 'gif/jpg/bmp/png', $_SESSION['uid']);
    if ($setsqlarr['logo']) {
        $setsqlarr['logo'] = date("Y/m/d/") . $setsqlarr['logo'];
        $logo_src = "../../data/train_logo/" . $setsqlarr['logo'];
        $thumb_dir = $uplogo_dir;
        makethumb($logo_src, $thumb_dir, 300, 110); //生成缩略图
        $wheresql = "uid='" . $_SESSION['uid'] . "'";
        if (updatetable(table('train_profile'), $setsqlarr, $wheresql)) {
            $link[0]['text'] = "查看LOGO";
            $link[0]['href'] = '?act=train_logo';
            write_memberslog($_SESSION['uid'], 4, 8103, $_SESSION['username'], "上传了培训机构LOGO");
            showmsg('上传成功！', 2, $link);
        } else {
            showmsg('保存失败！', 1);
        }
    } else {
        showmsg('保存失败！', 1);
    }
} elseif ($act == 'train_logo_del') {
    $uplogo_dir = "../../data/train_logo/";
    $auth = $train_profile; //获取原始图片
    @unlink($uplogo_dir . $auth['logo']); //先删除原始图片
    $setsqlarr['logo'] = "";
    $wheresql = "uid='" . $_SESSION['uid'] . "'";
    if (updatetable(table('train_profile'), $setsqlarr, $wheresql)) {
        write_memberslog($_SESSION['uid'], 4, 8104, $_SESSION['username'], "删除了机构LOGO");
        showmsg('删除成功！', 2);
    } else {
        showmsg('删除失败！', 1);
    }
} elseif ($act == 'train_map') {
    $link[0]['text'] = "填写机构资料";
    $link[0]['href'] = '?act=train_profile';
    if (empty($train_profile['trainname']))
        showmsg("请完善您的培训机构资料再设置电子地图！", 1, $link);
    if ($train_profile['map_open'] == "1") {//假如已经开通
        header("Location: ?act=train_map_set");
    } else {
        if ($_CFG['operation_train_mode'] == '1') {
            $points = get_cache('points_rule'); //获取积分消费规则
            $smarty->assign('points', $points['train_map']['value']);
        } elseif ($_CFG['operation_train_mode'] == '2') {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            $smarty->assign('map_open', $setmeal['map_open']);
        }
        $smarty->assign('title', '开通电子地图 - 培训会员中心 - ' . $_CFG['site_name']);
        $smarty->display('member_train/train_map_open.htm');
    }
} elseif ($act == 'train_map_open') {
    $link[0]['text'] = "填写培训机构资料";
    $link[0]['href'] = '?act=train_profile';
    if (empty($train_profile['trainname']))
        showmsg("请完善您的培训机构资料再设置电子地图！", 1);
    if ($_CFG['operation_train_mode'] == '1') {
        $points = get_cache('points_rule');
        $user_points = get_user_points($_SESSION['uid']);
        if ($points['train_map']['type'] == '2' && $points['train_map']['value'] > $user_points) {
            showmsg("你的" . $_CFG['train_points_byname'] . "不足，请充值后再进行相关操作！", 0);
        }
    } elseif ($_CFG['operation_train_mode'] == '2') {
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') {
            showmsg("你的服务套餐已到期，请重新开通服务！", 0);
        } elseif ($setmeal['map_open'] == '0') {
            showmsg("你服务套餐：{$setmeal['setmeal_name']} 没有开通电子地图的权限，请升级服务套餐！", 0);
        }
    }
    $wheresql = "uid='" . $_SESSION['uid'] . "'";
    $setsqlarr['map_open'] = 1;
    if (updatetable(table('train_profile'), $setsqlarr, $wheresql)) {
        //发送邮件
        $mailconfig = get_cache('mailconfig');
        if ($mailconfig['set_addmap'] == "1" && $user['email_audit'] == "1") {
            dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid=" . $_SESSION['uid'] . "&key=" . asyn_userkey($_SESSION['uid']) . "&act=set_addmap");
        }
        //sms
        $sms = get_cache('sms_config');
        if ($sms['open'] == "1" && $sms['set_addmap'] == "1" && $user['mobile_audit'] == "1") {
            dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid=" . $_SESSION['uid'] . "&key=" . asyn_userkey($_SESSION['uid']) . "&act=set_addmap");
        }
        //sms
        $link[0]['text'] = "设置电子地图";
        $link[0]['href'] = '?act=train_map_set';
        $link[1]['text'] = "返回会员中心首页";
        $link[1]['href'] = 'train_index.php?act=';
        write_memberslog($_SESSION['uid'], 4, 8105, $_SESSION['username'], "开通了电子地图");
        if ($_CFG['operation_train_mode'] == '1') {
            if ($points['train_map']['value'] > 0) {
                report_deal($_SESSION['uid'], $points['train_map']['type'], $points['train_map']['value']);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $points['train_map']['type'] == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 4, 9101, $_SESSION['username'], "开通了电子地图({$operator}{$points['train_map']['value']})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
            }
        } elseif ($_CFG['operation_train_mode'] == '2') {
            write_memberslog($_SESSION['uid'], 4, 9102, $_SESSION['username'], "使用服务套餐开通了电子地图");
        }
        showmsg('成功开通！', 2, $link);
    } else {
        showmsg('开通失败！', 1);
    }
} elseif ($act == 'train_map_set') {
    $smarty->assign('title', '设置电子地图 - 培训会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('train_profile', $train_profile);
    $smarty->display('member_train/train_map_set.htm');
} elseif ($act == 'train_map_set_save') {
    $setsqlarr['map_x'] = trim($_POST['x']) ? trim($_POST['x']) : showmsg('请先点击“在地图上标记我的位置”按钮，然后再点击保存我的位置进行保存！', 1);
    $setsqlarr['map_y'] = trim($_POST['y']) ? trim($_POST['y']) : showmsg('请先点击“在地图上标记我的位置”按钮，然后再点击保存我的位置进行保存！', 1);
    $setsqlarr['map_zoom'] = trim($_POST['zoom']);
    $wheresql = " uid='{$_SESSION['uid']}'";
    write_memberslog($_SESSION['uid'], 4, 8106, $_SESSION['username'], "设置了电子地图坐标");
    if (updatetable(table('train_profile'), $setsqlarr, $wheresql)) {
        $coursesql['map_x'] = $setsqlarr['map_x'];
        $coursesql['map_y'] = $setsqlarr['map_y'];
        updatetable(table('course'), $coursesql, $wheresql);
        unset($setsqlarr['map_zoom']);
        showmsg('保存成功', 2);
    } else {
        showmsg('保存失败', 1);
    }
} elseif ($act == 'train_news') {
    $smarty->assign('news', get_train_news(0, 60, $_SESSION['uid']));
    $smarty->assign('title', '机构新闻 - 培训会员中心 - ' . $_CFG['site_name']);
    $smarty->display('member_train/train_news_list.htm');
}
if ($act == 'train_news_add') {
    $link[0]['text'] = "完善机构资料";
    $link[0]['href'] = '?act=train_profile';
    $link[1]['text'] = "会员中心首页";
    $link[1]['href'] = 'train_index.php';
    if (empty($train_profile['trainname']))
        showmsg("请完善您的机构资料！", 1, $link);
    $smarty->assign('title', '添加机构新闻 - 会员中心 - ' . $_CFG['site_name']);
    $smarty->display('member_train/train_news_add.htm');
}
elseif ($act == 'train_news_add_save') {
    $n = $db->get_total("SELECT COUNT(*) AS num FROM " . table('train_news') . " WHERE uid='" . intval($_SESSION['uid']) . "'");
    if ($n >= 60) {
        showmsg('机构新闻最多发布60条！', 1);
    }
    if ($train_profile['audit'] == '1') {
        $setsqlarr['audit'] = intval($_CFG['audit_verifytrain_addnews']);
    } else {
        $setsqlarr['audit'] = intval($_CFG['audit_unexaminedtrain_addnews']);
    }
    $setsqlarr['title'] = !empty($_POST['title']) ? trim($_POST['title']) : showmsg('请填写标题！', 1);
    $setsqlarr['order'] = intval($_POST['order']);
    $setsqlarr['content'] = !empty($_POST['content']) ? trim($_POST['content']) : showmsg('请填写内容', 1);
    $setsqlarr['addtime'] = time();
    $setsqlarr['uid'] = intval($_SESSION['uid']);
    $setsqlarr['train_id'] = $train_profile['id'];
    $link[0]['text'] = "新闻列表";
    $link[0]['href'] = '?act=train_news';
    $link[1]['text'] = "继续添加";
    $link[1]['href'] = '?act=train_news_add';
    !inserttable(table('train_news'), $setsqlarr) ? showmsg("添加失败！", 0) : showmsg("添加成功！", 2, $link);
}
if ($act == 'train_news_edit') {
    $uid = intval($_SESSION['uid']);
    $id = intval($_GET['id']);
    $smarty->assign('news', $db->getone("select * from " . table('train_news') . " where uid='{$uid}' AND id ='{$id}' LIMIT 1"));
    $smarty->assign('title', '修改机构新闻 - 会员中心 - ' . $_CFG['site_name']);
    $smarty->display('member_train/train_news_edit.htm');
} elseif ($act == 'train_news_edit_save') {
    if ($train_profile['audit'] == '1') {
        $_CFG['audit_verifytrain_editnews'] <> "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_verifytrain_editnews']) : '';
    } else {
        $_CFG['audit_unexaminedtrain_editnews'] <> "-1" ? $setsqlarr['audit'] = intval($_CFG['audit_unexaminedtrain_editnews']) : '';
    }
    $setsqlarr['title'] = !empty($_POST['title']) ? trim($_POST['title']) : showmsg('请填写标题！', 1);
    $setsqlarr['order'] = intval($_POST['order']);
    $setsqlarr['content'] = !empty($_POST['content']) ? trim($_POST['content']) : showmsg('请填写内容', 1);
    $link[0]['text'] = "新闻列表";
    $link[0]['href'] = '?act=train_news';
    $uid = intval($_SESSION['uid']);
    $id = intval($_POST['id']);
    !updatetable(table('train_news'), $setsqlarr, " uid='{$uid}' AND id='{$id}' ") ? showmsg("修改失败！", 0) : showmsg("修改成功！", 2, $link);
} elseif ($act == 'train_news_del') {
    $id = !empty($_POST['id']) ? $_POST['id'] : $_GET['id'];
    if (empty($id)) {
        showmsg("你没有选择新闻！", 1);
    }
    if ($n = del_train_news($id, $_SESSION['uid'])) {
        showmsg("删除成功！共删除 {$n} 行", 2);
    } else {
        showmsg("删除失败！", 0);
    }
} elseif ($act == 'train_img') {
    $link[0]['text'] = "完善机构资料";
    $link[0]['href'] = '?act=train_profile';
    $link[1]['text'] = "会员中心首页";
    $link[1]['href'] = 'train_index.php';
    if (empty($train_profile['trainname']))
        showmsg("请完善您的机构资料再上传机构图片！", 1, $link);
    $smarty->assign('title', '机构图片 - 机构会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('img', get_train_img(0, 60, $_SESSION['uid']));
    $smarty->display('member_train/train_img.htm');
}
elseif ($act == 'train_img_save') {
    $n = $db->get_total("SELECT COUNT(*) AS num FROM " . table('train_img') . " WHERE uid='" . intval($_SESSION['uid']) . "'");
    if ($n >= 8) {
        showmsg('机构图片最多发布8张！', 1);
    }
    require_once(QISHI_ROOT_PATH . 'include/upload.php');
    !$_FILES['img']['name'] ? showmsg('请上传图片！', 1) : "";
    $datedir = date("Y/m/d/");
    $up_dir = "../../data/train_img/original/" . $datedir;
    make_dir($up_dir);
    $setsqlarr['img'] = _asUpFiles($up_dir, "img", 800, 'gif/jpg/bmp/png', true);
    if ($setsqlarr['img']) {
        $img_src = $up_dir . $setsqlarr['img'];
        $thumb_dir = "../../data/train_img/thumb/" . $datedir;
        make_dir($thumb_dir);
        makethumb($img_src, $up_dir, 600, 600);
        makethumb($img_src, $thumb_dir, 295, 165);
        $setsqlarr['uid'] = intval($_SESSION['uid']);
        $setsqlarr['train_id'] = $train_profile['id'];
        $setsqlarr['addtime'] = time();
        $setsqlarr['title'] = trim($_POST['title']);
        $setsqlarr['img'] = $datedir . $setsqlarr['img'];
        if ($train_profile['audit'] == '1') {
            $setsqlarr['audit'] = intval($_CFG['audit_verifytrain_addimg']);
        } else {
            $setsqlarr['audit'] = intval($_CFG['audit_unexaminedtrain_addimg']);
        }
        if (inserttable(table('train_img'), $setsqlarr)) {
            $link[0]['text'] = "返回上一页";
            $link[0]['href'] = '?act=train_img';
            showmsg('上传成功！', 2, $link);
        } else {
            showmsg('保存失败！', 1);
        }
    } else {
        showmsg('保存失败！', 1);
    }
} elseif ($act == 'train_img_del') {
    $uid = intval($_SESSION['uid']);
    $id = intval($_GET['id']);
    $img = $db->getone("select * from " . table('train_img') . " WHERE uid='{$uid}' AND id='{$id}' LIMIT 1");
    if (empty($img)) {
        showmsg('删除失败！', 1);
    }
    @unlink("../../data/train_img/original/" . $img['img']);
    @unlink("../../data/train_img/thumb/" . $img['img']);
    $db->query("Delete from " . table('train_img') . " WHERE  uid='{$uid}' AND id='{$id}'");
    showmsg('删除成功！', 2);
}
unset($smarty);
?>