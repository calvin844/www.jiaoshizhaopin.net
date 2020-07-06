<?php

function tpl_function_qishi_company_show($params, &$smarty) {
    global $db, $mem, $_CFG;
    $arr = explode(',', $params['set']);
    foreach ($arr as $str) {
        $a = explode(':', $str);
        switch ($a[0]) {
            case "企业ID":
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
    $wheresql.=" AND  user_status=1 ";
    $sql = "select * from " . table('company_profile') . " WHERE  id='{$aset['id']}' {$wheresql} LIMIT  1";
    $profile = $db->getone($sql);
    if (empty($profile)) {
        header("HTTP/1.1 404 Not Found");
        $smarty->display("404.htm");
        exit();
    } else {
        $profile['company_url'] = url_rewrite('QS_companyshow', array('id' => $profile['id']));
        $profile['company_profile'] = $profile['contents'];
        $profile['description'] = cut_str(strip_tags($profile['contents']), 50, 0, "...");
        $profile['addtime'] = date('Y年m月d日', $profile['addtime']);
        if ($profile['logo']) {
            $profile['logo'] = $_CFG['main_domain'] . "data/logo/" . $profile['logo'];
        } else {
            $profile['logo'] = $_CFG['main_domain'] . "data/logo/no_logo.gif";
        }
    }
    /* 简历处理率 */
    $total_sql = "SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . " WHERE  company_id='{$profile['id']}'";
    $total_sql = $db->get_total($total_sql);
    $look_sql = "SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . " WHERE  company_id='{$profile['id']}' and personal_look = 1";
    $look_sql = $db->get_total($look_sql);
    $profile['resume_processing'] = $look_sql / $total_sql;
    $profile['resume_processing'] = $profile['resume_processing'] * 100;
    $profile['resume_processing'] = sprintf("%1\$u", $profile['resume_processing']);
    // 在招职位
    $profile['jobs_num'] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('jobs') . " WHERE company_id='{$profile['id']}' ");
    // 感兴趣简历
    if ($profile['uid'] > 0) {
        $profile['resume_num'] = $db->get_total("SELECT COUNT(*) AS num FROM " . table('company_favorites') . " WHERE company_uid='{$profile['uid']}' ");
    }
    $sql = "select * from " . table('jiaoshi_comment') . " WHERE comment_type = 'company' and comment_type_id=" . intval($profile['id']) . " order by addtime desc";
    $comment_arr = $db->getall($sql);
    $profile['comment_total'] = count($comment_arr) - 5;
    foreach ($comment_arr as $a) {
        if (count($comment_img) < 5 && !in_array($a['openid'], $comment_img)) {
            $comment_img[] = $a['openid'];
        }
    }
    $profile['comment_sum'] = count($comment_arr);
    $comment_arr = array_chunk($comment_arr, 5);
    $profile['comment_img'] = $comment_img;
    $profile['comment_arr'] = $comment_arr[0];
    $host = $_SERVER['SERVER_NAME'] == "test.91jiaoshi.com" ? "test.jiaoshizhaopin.net" : "www.jiaoshizhaopin.net";
    $path = "/data2/www/" . $host . "/data/company_wxapp_img/";
    $filename = $profile['id'] . ".jpg";
    unlink($path . $filename);
    $uid = intval($_SESSION['uid']);
    $uid_str = $uid > 0 ? "&uid=" . $uid : "";
    if (!$mem->get('wxapp_accesstoken')) {
        $AccessToken_data = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx62bb8320a3477913&secret=3d8593d5ca379d83ba9f47886d47147d");
        $AccessToken_arr = json_decode($AccessToken_data);
        $AccessToken = $AccessToken_arr->access_token;
        $mem->set('wxapp_accesstoken', $AccessToken, 0, 7000);
    }
    $AccessToken = $mem->get('wxapp_accesstoken');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $AccessToken);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    // POST数据
    $post_data = array(
        "scene" => "companyid=" . $profile['id'] . $uid_str,
        "page" => "pages/company/details/details",
        "width" => 280
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));


    // 把post的变量加上
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "cache-control: no-cache"
    ));
    $output = curl_exec($ch);

    curl_close($ch);
    $qCodePath = $output;

    $qCodeImg = imagecreatefromstring($qCodePath);

    //        list($qCodeWidth, $qCodeHight, $qCodeType) = getimagesize($qCodePath);
    //        list($qCodeWidth, $qCodeHight, $qCodeType) = getimagesize($qCodeImg);
    // imagecopymerge使用注解

    imagejpeg($qCodeImg, $path . "/" . $filename);
    //保存文件
    imagedestroy($qCodeImg);
    $smarty->assign($aset['listname'], $profile);
}

?>