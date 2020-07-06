<?php

/*
 * 附件简历上传
  |   @param: $dir      -- 存放目录,最后加"/" [字串]
  |   @param: $file_var -- 表单变量 [字串]
  |   @param: $max_size -- 设定最大上传值,以k为单位. [整数/浮点数]
  |   @param: $type     -- 限定后辍名(小写)，多个用"/"隔开,不限定则留空 [字串]
  |   @param: $name     -- 上传后命名,留空则为原名,true为系统随机定名 [布林值]
  |   return: 上传后文件名
 */

function _asUpResume($dir, $file_var, $max_size = '', $type = '') {
    if ($_SESSION['uid'] == '') {
        header("Location: " . url_rewrite('QS_login') . "?act=logout");
        exit();
    }
    if (!file_exists($dir)) {
        showmsg("上传失败：上传目录 " . $dir . " 不存在!", 0);
    } elseif (!is_writable($dir)) {
        showmsg("上传失败：上传目录 " . $dir . " 无法写入!", 0);
        exit();
    }
    $upfile = & $_FILES["$file_var"];
    $upfilename = $upfile['name'];

    if (!empty($upfilename)) {
        if (!is_uploaded_file($upfile['tmp_name'])) {
            showmsg('上传失败：你选择的文件无法上传', 0);
            exit();
        } elseif ($max_size > 0 && $upfile['size'] / 1024 > $max_size) {
            showmsg("上传失败：文件大小不能超过  " . $max_size . "KB", 0);
            exit();
        }
        //$ext_name = strtolower(str_replace(".", "", strrchr($upfilename, ".")));
        $ext_name = pathinfo($upfilename, PATHINFO_EXTENSION);
        if (!empty($type)) {
            $arr_type = explode('/', $type);
            $arr_type = array_map("strtolower", $arr_type);
            if (!in_array($ext_name, $arr_type)) {
                showmsg("上传失败：只允许上传 " . $type . " 的文件！", 0);
                exit();
            }
            $harmtype = array("asp", "php", "jsp", "js", "css", "php3", "php4", "ashx", "aspx", "exe");
            if (in_array($ext_name, $harmtype)) {
                exit("ERR!");
            }
        }
        $uploadname = "教师招聘网" . "--" . $_SESSION['uid'] . "--" . str_replace(' ', ',', $upfile['name']);
        if (!move_uploaded_file($upfile['tmp_name'], $dir . $uploadname)) {
            showmsg('上传失败：文件上传出错！', 0);
            exit();
        }
        return $uploadname;
    }
    return '';
}

?>