<?php

/*
 * 74cms 共用函数
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if (!defined('IN_QISHI'))
    die('Access Denied!');

function addslashes_deep($value) {
    if (empty($value)) {
        return $value;
    } else {
        if (!get_magic_quotes_gpc()) {
            $value = is_array($value) ? array_map('addslashes_deep', $value) : mystrip_tags(addslashes($value));
        } else {
            $value = is_array($value) ? array_map('addslashes_deep', $value) : mystrip_tags($value);
        }
        return $value;
    }
}

function mystrip_tags($string) {
    $string = new_html_special_chars($string);
    $string = remove_xss($string);
    return $string;
}

function new_html_special_chars($string) {
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
    $string = strip_tags($string);
    return $string;
}

function remove_xss($string) {
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

    $parm1 = Array('javascript', 'union', 'vbscript', 'expression', 'applet', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

    $parm3 = Array('alert', 'sleep', 'load_file', 'confirm', 'prompt', 'benchmark', 'select', 'update', 'insert', 'delete', 'alter', 'drop', 'truncate');

    $parm = array_merge($parm1, $parm2, $parm3);

    for ($i = 0; $i < sizeof($parm); $i++) {
        $pattern = '/';
        for ($j = 0; $j < strlen($parm[$i]); $j++) {
            if ($j > 0) {
                $pattern .= '(';
                $pattern .= '(&#[x|X]0([9][a][b]);?)?';
                $pattern .= '|(&#0([9][10][13]);?)?';
                $pattern .= ')?';
            }
            $pattern .= $parm[$i][$j];
        }
        $pattern .= '/i';
        $string = preg_replace($pattern, '****', $string);
    }
    return $string;
}

function table($table) {
    global $pre;
    return $pre . $table;
}

function showmsg($msg_detail, $msg_type = 0, $links = array(), $auto_redirect = true, $seconds = 3) {
    global $smarty;
    if (count($links) == 0) {
        $links[0]['text'] = '返回上一页';
        $links[0]['href'] = 'javascript:history.go(-1)';
    }
    $smarty->assign('ur_here', '系统提示');
    $smarty->assign('msg_detail', $msg_detail);
    $smarty->assign('msg_type', $msg_type);
    $smarty->assign('links', $links);
    $smarty->assign('default_url', $links[0]['href']);
    $smarty->assign('auto_redirect', $auto_redirect);
    $smarty->assign('seconds', $seconds);
    $smarty->display('showmsg.htm');
    exit;
}

function get_smarty_request($str) {
    $str = rawurldecode($str);
    $strtrim = rtrim($str, ']');
    if (substr($strtrim, 0, 4) == 'GET[') {
        $getkey = substr($strtrim, 4);
        return $_GET[$getkey];
    } elseif (substr($strtrim, 0, 5) == 'POST[') {
        $getkey = substr($strtrim, 5);
        return $_POST[$getkey];
    } else {
        return $str;
    }
}

function get_cache($cachename) {
    $cache_file_path = QISHI_ROOT_PATH . "data/cache_" . $cachename . ".php";
    @include($cache_file_path);
    return $data;
}

function get_mem_cache($sql, $method = "getall", $cache_time = "") {
    global $db, $mem, $_CFG;
    if ($_CFG['mem_time'] > -1) {
        $cache_time = !empty($cache_time) ? $cache_time : $_CFG['mem_time'];
        if (!$mem->get(md5($_CFG['main_domain'] . $sql))) {
            switch ($method) {
                case "getone":
                    $data = $db->getone($sql);
                    break;
                case "getall":
                    $data = $db->getall($sql);
                    break;
                case "get_total":
                    $data = $db->get_total($sql);
                    break;
                case "getfirst":
                    $data = $db->getfirst($sql);
                    break;
                default:
                    $data = $db->getall($sql);
                    break;
            }
            $mem->set(md5($_CFG['main_domain'] . $sql), $data, 0, $cache_time);
        }
        $data = $mem->get(md5($_CFG['main_domain'] . $sql));
    } else {
        switch ($method) {
            case "getone":
                $data = $db->getone($sql);
                break;
            case "getall":
                $data = $db->getall($sql);
                break;
            case "get_total":
                $data = $db->get_total($sql);
                break;
            case "getfirst":
                $data = $db->getfirst($sql);
                break;
            default:
                $data = $db->getall($sql);
                break;
        }
    }
    $db->free_result();
    return $data;
}

function exectime() {
    $time = explode(" ", microtime());
    $usec = (double) $time[0];
    $sec = (double) $time[1];
    return $sec + $usec;
}

function check_word($noword, $content) {
    $word = explode('|', $noword);
    if (!empty($word) && !empty($content)) {
        foreach ($word as $str) {
            if (!empty($str) && strstr($content, $str)) {
                return true;
            }
        }
    }
    return false;
}

function getip() {
    if (getenv('HTTP_CLIENT_IP') and strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') and strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') and strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) and $_SERVER['REMOTE_ADDR'] and strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $onlineip, $match);
    return $onlineip = $match[0] ? $match[0] : 'unknown';
}

function convertip($ip) {
    if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
        $iparray = explode('.', $ip);
        if ($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
            $return = '- LAN';
        } elseif ($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255) {
            $return = '- Invalid IP Address';
        } else {
            $tinyipfile = QISHI_ROOT_PATH . 'data/tinyipdata.dat';
            if (@file_exists($tinyipfile)) {
                $return = convertip_tiny($ip, $tinyipfile);
            }
        }
    }
    return $return;
}

function convertip_tiny($ip, $ipdatafile) {
    static $fp = NULL, $offset = array(), $index = NULL;
    $ipdot = explode('.', $ip);
    $ip = pack('N', ip2long($ip));
    $ipdot[0] = (int) $ipdot[0];
    $ipdot[1] = (int) $ipdot[1];
    if ($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
        $offset = @unpack('Nlen', @fread($fp, 4));
        $index = @fread($fp, $offset['len'] - 4);
    } elseif ($fp == FALSE) {
        return '- Invalid IP data file';
    }
    $length = $offset['len'] - 1028;
    $start = @unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);

    for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {

        if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
            $index_offset = @unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
            $index_length = @unpack('Clen', $index{$start + 7});
            break;
        }
    }
    @fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
    if ($index_length['len']) {
        return '- ' . @fread($fp, $index_length['len']);
    } else {
        return '- Unknown';
    }
}

function inserttable($tablename, $insertsqlarr, $returnid = 0, $replace = false, $silent = 0) {
    global $db;
    $insertkeysql = $insertvaluesql = $comma = '';
    foreach ($insertsqlarr as $insert_key => $insert_value) {
        $insertkeysql .= $comma . '`' . $insert_key . '`';
        $insertvaluesql .= $comma . '\'' . $insert_value . '\'';
        $comma = ', ';
    }
    $method = $replace ? 'REPLACE' : 'INSERT';
    $state = $db->query($method . " INTO $tablename ($insertkeysql) VALUES ($insertvaluesql)", $silent ? 'SILENT' : '');
    $insert_id = $db->insert_id();
    if ($tablename == 'qs_article' || $tablename == 'qs_jobs') {
        updateindex($tablename, $insert_id);
    }
    if ($returnid && !$replace) {
        return $insert_id;
    } else {
        return $state;
    }
}

function updateindex($tablename, $id) {
    global $db;
    if ($tablename == 'qs_article' || $tablename == 'qs_jobs') {
        $sql = "select district,sdistrict,addtime,refreshtime from " . $tablename . " where id = " . $id . " and audit = 1";
        $index_arr = $db->getone($sql);
        $t = substr($tablename, 3);
        $index_arr['type'] = $t;
        if ($tablename == 'qs_article') {
            $index_arr['parent_id'] = $id;
            $sql = "select id from " . table('jiaoshi_article_jobs_index') . " where  parent_id = " . $index_arr['parent_id'] . " and type = '" . $t . "'";
            $has_index = $db->getone($sql);
            if (!empty($has_index['id'])) {
                $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where parent_id=" . $index_arr['parent_id'] . " and type = '" . $t . "'");
            }
            $sql = "select id,category,subclass from " . table('jiaoshi_article_jobs') . " where article_id = " . $id;
            $article_jobs_arr = $db->getall($sql);
            if (!empty($article_jobs_arr)) {
                foreach ($article_jobs_arr as $aj) {
                    $index_arr['p_id'] = $aj['id'];
                    $index_arr['category'] = $aj['category'];
                    $index_arr['subclass'] = $aj['subclass'];
                    inserttable(table('jiaoshi_article_jobs_index'), $index_arr);
                }
            } else {
                $index_arr['p_id'] = 0;
                $index_arr['category'] = 0;
                $index_arr['subclass'] = 0;
                inserttable(table('jiaoshi_article_jobs_index'), $index_arr);
            }
        } elseif ($tablename == 'qs_jobs') {
            $sql = "select company_id from " . table('jobs') . " where id = " . $id;
            $jobs_arr = $db->getone($sql);
            $sql = "select category,subclass from " . table('jobs') . " where id = " . $id;
            $job_category_arr = $db->getone($sql);
            $index_arr['parent_id'] = $jobs_arr['company_id'];
            $index_arr['p_id'] = $id;
            $index_arr['category'] = $job_category_arr['category'];
            $index_arr['subclass'] = $job_category_arr['subclass'];
            $sql = "select id from " . table('jiaoshi_article_jobs_index') . " where  p_id = " . $id . " and type = '" . $t . "'";
            $has_index = $db->getone($sql);
            if (!empty($has_index['id'])) {
                $wheresql = " p_id = " . $index_arr['p_id'] . " and type = '" . $t . "'";
                updatetable(table('jiaoshi_article_jobs_index'), $index_arr, $wheresql);
            } else {
                inserttable(table('jiaoshi_article_jobs_index'), $index_arr);
            }
        }
    }
}

function updatetable($tablename, $setsqlarr, $wheresqlarr, $silent = 0) {
    global $db;
    $setsql = $comma = '';
    foreach ($setsqlarr as $set_key => $set_value) {
        if (is_array($set_value)) {
            $setsql .= $comma . '`' . $set_key . '`' . '=\'' . $set_value[0] . '\'';
        } else {
            $setsql .= $comma . '`' . $set_key . '`' . '=\'' . $set_value . '\'';
        }
        $comma = ', ';
    }
    $where = $comma = '';
    if (empty($wheresqlarr)) {
        $where = '1';
    } elseif (is_array($wheresqlarr)) {
        foreach ($wheresqlarr as $key => $value) {
            $where .= $comma . '`' . $key . '`' . '=\'' . $value . '\'';
            $comma = ' AND ';
        }
    } else {
        $where = $wheresqlarr;
    }
    $return = $db->query("UPDATE " . ($tablename) . " SET " . $setsql . " WHERE " . $where, $silent ? "SILENT" : "");
    if ($tablename == 'qs_article' || $tablename == 'qs_jobs') {
        $sql = "select id from " . $tablename . " WHERE " . $where;
        $index_arr = $db->getall($sql);
        if (count($index_arr) < 1000) {
            foreach ($index_arr as $i) {
                updateindex($tablename, $i['id']);
            }
        }
    }
    //var_dump("UPDATE ".($tablename)." SET ".$setsql." WHERE ".$where, $silent?"SILENT":"");exit;
    return $return;
}

function deletetable($tablename, $wheresqlarr = "", $limit = 0) {
    global $db;
    $where = '';
    if (empty($wheresqlarr)) {
        $where = '1';
    } elseif (is_array($wheresqlarr)) {
        foreach ($wheresqlarr as $key => $value) {
            $where .= $comma . '`' . $key . '`' . '=\'' . $value . '\'';
            $comma = ' AND ';
        }
    } else {
        $where = $wheresqlarr;
    }
    $limit = intval($limit) > 0 ? " LIMIT " . intval($limit) : "";
    $sql = "Delete from " . $tablename . " WHERE " . $where . $limit;
    $return = $db->query($sql);
    return $return;
}

function wheresql($wherearr = '') {
    $wheresql = "";
    if (is_array($wherearr)) {
        $where_set = ' WHERE ';
        foreach ($wherearr as $key => $value) {
            $wheresql .=$where_set . $comma . $key . '="' . $value . '"';
            $comma = ' AND ';
            $where_set = ' ';
        }
    }
    return $wheresql;
}

function convert_datefm($date, $format, $separator = "-") {
    if ($format == "1") {
        return date("Y-m-d", $date);
    } else {
        if (!preg_match("/^[0-9]{4}(\\" . $separator . ")[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/", $date))
            return false;
        $date = explode($separator, $date);
        return mktime(0, 0, 0, $date[1], $date[2], $date[0]);
    }
}

function sub_day($endday, $staday, $range = '') {
    $value = $endday - $staday;
    if ($value < 0) {
        return '';
    } elseif ($value >= 0 && $value < 59) {
        return ($value + 1) . "秒";
    } elseif ($value >= 60 && $value < 3600) {
        $min = intval($value / 60);
        return $min . "分钟";
    } elseif ($value >= 3600 && $value < 86400) {
        $h = intval($value / 3600);
        return $h . "小时";
    } elseif ($value >= 86400 && $value < 86400 * 30) {
        $d = intval($value / 86400);
        return intval($d) . "天";
    } elseif ($value >= 86400 * 30 && $value < 86400 * 30 * 12) {
        $mon = intval($value / (86400 * 30));
        return $mon . "月";
    } else {
        $y = intval($value / (86400 * 30 * 12));
        return $y . "年";
    }
}

function daterange($endday, $staday, $format = 'Y-m-d', $color = '', $range = 3) {
    $value = $endday - $staday;
    if ($value < 0) {
        return '';
    } elseif ($value >= 0 && $value < 59) {
        $return = ($value + 1) . "秒前";
    } elseif ($value >= 60 && $value < 3600) {
        $min = intval($value / 60);
        $return = $min . "分钟前";
    } elseif ($value >= 3600 && $value < 86400) {
        $h = intval($value / 3600);
        $return = $h . "小时前";
    } elseif ($value >= 86400) {
        $d = intval($value / 86400);
        if ($d > $range) {
            return date($format, $staday);
        } else {
            $return = $d . "天前";
        }
    }
    if ($color) {
        $return = "<span id=\"r_time\" style=\"color:{$color}\">" . $return . "</span>";
    }
    return $return;
}

function cut_str($string, $length, $start = 0, $dot = '') {
    $length = $length * 2;
    if (strlen($string) <= $length) {
        return $string;
    }
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
    $strcut = '';
    for ($i = 0; $i < $length; $i++) {
        $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
    }
    $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
    return $strcut . $dot;
}

function smtp_mail($sendto_email, $subject, $body, $From = '', $FromName = '', $attachment_arr = array()) {
    global $db, $_CFG;
    require_once(QISHI_ROOT_PATH . 'phpmailer/class.phpmailer.php');
    $mail = new PHPMailer();
    $sql = "select * from " . table("mailconfig");
    $mailconfig_tmp = $db->getall($sql);
    //var_dump($mailconfig_tmp);exit;
    foreach ($mailconfig_tmp as $m) {
        $mailconfig[$m['name']] = $m['value'];
    }
    if (strstr($mailconfig['smtpservers'], '|-_-|')) {
        $mailconfig['smtpservers'] = explode('|-_-|', $mailconfig['smtpservers']);
        $mailconfig['smtpusername'] = explode('|-_-|', $mailconfig['smtpusername']);
        $mailconfig['smtppassword'] = explode('|-_-|', $mailconfig['smtppassword']);
        $mailconfig['smtpfrom'] = explode('|-_-|', $mailconfig['smtpfrom']);
        $mailconfig['smtpport'] = explode('|-_-|', $mailconfig['smtpport']);
        $mailconfig['smtpnum'] = explode('|-_-|', $mailconfig['smtpnum']);
        $mailconfig['smtpupdatetime'] = explode('|-_-|', $mailconfig['smtpupdatetime']);
        for ($i = 0; $i < count($mailconfig['smtpservers']); $i++) {
            $mailconfigarray[] = array('smtpservers' => $mailconfig['smtpservers'][$i], 'smtpusername' => $mailconfig['smtpusername'][$i], 'smtppassword' => $mailconfig['smtppassword'][$i], 'smtpfrom' => $mailconfig['smtpfrom'][$i], 'smtpport' => $mailconfig['smtpport'][$i], 'smtpnum' => $mailconfig['smtpnum'][$i], 'smtpupdatetime' => $mailconfig['smtpupdatetime'][$i]);
        }
    } else {
        $mailconfigarray[] = $mailconfig;
    }
    foreach ($mailconfigarray as $m) {
        if (($m['smtpnum'] - 1) > 0) {
            if (empty($mc)) {
                $m['smtpnum'] = $m['smtpnum'] - 1;
                $mc = $m;
            }
        } else {
            if (time() > strtotime("+1 days", $m['smtpupdatetime'])) {
                $m['smtpnum'] = 1000;
                $m['smtpupdatetime'] = strtotime(date('Y-m-d'));
                $mc = $m;
                $m['smtpnum'] = $m['smtpnum'] - 1;
            }
        }
        $smtp_update_arr[] = $m;
    }
    if (empty($mc)) {
        write_syslog(2, 'MAIL', "没有可用的邮箱！请更新系统缓存后检查邮件配置");
    }
    $mailconfig['smtpservers'] = $mc['smtpservers'];
    $mailconfig['smtpusername'] = $mc['smtpusername'];
    $mailconfig['smtppassword'] = $mc['smtppassword'];
    $mailconfig['smtpfrom'] = $mc['smtpfrom'];
    $mailconfig['smtpport'] = $mc['smtpport'];
    $From = $From ? $From : $mailconfig['smtpfrom'];
    $FromName = $FromName ? $FromName : $_CFG['site_name'];
    if ($mailconfig['method'] == "1") {
        if (empty($mailconfig['smtpservers']) || empty($mailconfig['smtpusername']) || empty($mailconfig['smtppassword']) || empty($mailconfig['smtpfrom'])) {
            write_syslog(2, 'MAIL', "邮件配置信息不完整");
            return false;
        }
        $mail->IsSMTP();
        $mail->Host = $mailconfig['smtpservers'];
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Username = $mailconfig['smtpusername'];
        $mail->Password = $mailconfig['smtppassword'];
        $mail->Port = $mailconfig['smtpport'];
        $mail->From = $mailconfig['smtpfrom'];
        $mail->FromName = $FromName;
    } elseif ($mailconfig['method'] == "2") {
        $mail->IsSendmail();
    } elseif ($mailconfig['method'] == "3") {
        $mail->IsMail();
    }
    $mail->CharSet = QISHI_CHARSET;
    $mail->Encoding = "base64";
    $mail->AddReplyTo($From, $FromName);
    $mail->AddAddress($sendto_email, "");
    //$mail->addCC($From);
    $mail->IsHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = "text/html";
    if (!empty($attachment_arr)) {
        foreach ($attachment_arr as $a) {
            $mail->AddAttachment($a['path'], $a['name']); // 添加附件,并指定名称
        }
    }
    //var_dump($mail);
    $smtp_update['smtpservers'] = "";
    $smtp_update['smtpusername'] = "";
    $smtp_update['smtppassword'] = "";
    $smtp_update['smtpfrom'] = "";
    $smtp_update['smtpport'] = "";
    $smtp_update['smtpnum'] = "";
    $smtp_update['smtpupdatetime'] = "";
    foreach ($smtp_update_arr as $su) {
        $smtp_update['smtpservers'] .= $su['smtpservers'] . "|-_-|";
        $smtp_update['smtpusername'] .= $su['smtpusername'] . "|-_-|";
        $smtp_update['smtppassword'] .= $su['smtppassword'] . "|-_-|";
        $smtp_update['smtpfrom'] .= $su['smtpfrom'] . "|-_-|";
        $smtp_update['smtpport'] .= $su['smtpport'] . "|-_-|";
        $smtp_update['smtpnum'] .= $su['smtpnum'] . "|-_-|";
        $smtp_update['smtpupdatetime'] .= $su['smtpupdatetime'] . "|-_-|";
    }
    $smtp_update['smtpservers'] = trim($smtp_update['smtpservers'], "|-_-|");
    $smtp_update['smtpusername'] = trim($smtp_update['smtpusername'], "|-_-|");
    $smtp_update['smtppassword'] = trim($smtp_update['smtppassword'], "|-_-|");
    $smtp_update['smtpfrom'] = trim($smtp_update['smtpfrom'], "|-_-|");
    $smtp_update['smtpport'] = trim($smtp_update['smtpport'], "|-_-|");
    $smtp_update['smtpnum'] = trim($smtp_update['smtpnum'], "|-_-|");
    $smtp_update['smtpupdatetime'] = trim($smtp_update['smtpupdatetime'], "|-_-|");
    foreach ($smtp_update as $k => $v) {
        $db->query("UPDATE " . table('mailconfig') . " SET value='$v' WHERE name='$k'");
    }
    if ($mail->Send()) {
        return true;
    } else {
        write_syslog(2, 'MAIL', $mail->ErrorInfo);
        return false;
    }
}

function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE, $encodetype = 'URLENCOD') {
    $return = '';
    $matches = parse_url($url);
    $host = $matches['host'];
    $path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
    $port = !empty($matches['port']) ? $matches['port'] : 80;

    if ($post) {
        $out = "POST $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
        //$out .= "Referer: $boardurl\r\n";
        $out .= "Accept-Language: zh-cn\r\n";
        $boundary = $encodetype == 'URLENCODE' ? '' : ';' . substr($post, 0, trim(strpos($post, "\n")));
        $out .= $encodetype == 'URLENCODE' ? "Content-Type: application/x-www-form-urlencoded\r\n" : "Content-Type: multipart/form-data$boundary\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host:$port\r\n";
        $out .= 'Content-Length: ' . strlen($post) . "\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cache-Control: no-cache\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
        $out .= $post;
    } else {
        $out = "GET $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
        //$out .= "Referer: $boardurl\r\n";
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host:$port\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
    }

    if (function_exists('fsockopen')) {
        $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
    } elseif (function_exists('pfsockopen')) {
        $fp = @pfsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
    } else {
        $fp = false;
    }
    if (!$fp) {
        return '';
    } else {
        stream_set_blocking($fp, $block);
        stream_set_timeout($fp, $timeout);
        @fwrite($fp, $out);
        $status = stream_get_meta_data($fp);
        if (!$status['timed_out']) {
            while (!feof($fp)) {
                if (($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n")) {
                    break;
                }
            }

            $stop = false;
            while (!feof($fp) && !$stop) {
                $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                $return .= $data;
                if ($limit) {
                    $limit -= strlen($data);
                    $stop = $limit <= 0;
                }
            }
        }
        @fclose($fp);
        return $return;
    }
}

//通知类短信接口
function send_sms($mobile, $content) {
    global $db;
    $sms = get_cache('sms_config');
    if ($sms['open'] != "1" || empty($sms['notice_sms_name']) || empty($sms['notice_sms_key']) || empty($mobile) || empty($content)) {
        return false;
    } else {
        return dfopen("http://www.74cms.com/SMSsend.php?sms_name={$sms['notice_sms_name']}&sms_key={$sms['notice_sms_key']}&mobile={$mobile}&content={$content}");
    }
}

//验证码类短信接口
function captcha_send_sms($mobile, $content) {
    global $db;
    $sms = get_cache('sms_config');
    if ($sms['open'] != "1" || empty($sms['captcha_sms_name']) || empty($sms['captcha_sms_key']) || empty($mobile) || empty($content)) {
        return false;
    } else {
        return dfopen("http://www.74cms.com/SMSsend.php?sms_name={$sms['captcha_sms_name']}&sms_key={$sms['captcha_sms_key']}&mobile={$mobile}&content={$content}");
    }
}

//其他类短信接口
function free_send_sms($mobile, $content) {
    global $db;
    $sms = get_cache('sms_config');
    if ($sms['open'] != "1" || empty($sms['free_sms_name']) || empty($sms['free_sms_key']) || empty($mobile) || empty($content)) {
        return false;
    } else {
        return dfopen("http://www.74cms.com/SMSsend.php?sms_name={$sms['free_sms_name']}&sms_key={$sms['free_sms_key']}&mobile={$mobile}&content={$content}");
    }
}

function execution_crons() {
    global $db;
    $crons = $db->getone("select * from " . table('crons') . " WHERE (nextrun<" . time() . " OR nextrun=0) AND available=1 LIMIT 1  ");
    if (!empty($crons)) {
        require_once(QISHI_ROOT_PATH . "include/crons/" . $crons['filename']);
    }
}

function get_tpl($type, $id) {
    global $db, $_CFG, $smarty;
    $id = intval($id);
    $tarr = array("jobs", "company_profile", "resume", "companycommentshow", "companynewsshow", 'course', 'train_profile');
    if (!in_array($type, $tarr))
        exit();
    if ($type == 'companynewsshow') {
        $utpl = $db->getone("SELECT p.tpl FROM " . table('company_news') . "  AS c LEFT JOIN " . table('company_profile') . "  AS p ON c.company_id=p.id WHERE c.id='{$id}' limit 1");
    } else {
        //$utpl = $db->getone("SELECT tpl FROM " . table($type) . " WHERE id='{$id}' limit 1");
        $utpl = $db->getone("SELECT * FROM " . table($type) . " WHERE id='{$id}' limit 1");
        $u_setmeal = $db->getone("SELECT * FROM " . table('members_setmeal') . " WHERE uid='{$utpl['uid']}' limit 1");
        if ($u_setmeal['expense'] > 0) {
            $utpl['tpl'] = "vip";
        }
    }
    $thistpl = $utpl['tpl'];
    if (!empty($_GET['style'])) {
        $thistpl = $_GET['style'];
    }
    if (empty($thistpl)) {
        if ($type == 'resume') {
            $tpl_dir = $_CFG['tpl_personal'];
            $utpl = $db->getone("SELECT * FROM " . table("personal_resume_tpl") . " WHERE resume_id='{$id}' AND endtime>" . time() . " order by id desc limit 1");
            if (!empty($utpl)) {
                $tpl = $db->getone("SELECT * FROM " . table("tpl") . " WHERE tpl_id=" . $utpl['tpl_id'] . " AND tpl_type =2 AND tpl_display=1");
                $tpl_dir = !empty($tpl['tpl_dir']) ? $tpl['tpl_dir'] : $_CFG['tpl_personal'];
            }
            $thistpl = "../tpl_resume/" . $tpl_dir . "/";
            $smarty->assign('user_tpl', $_CFG['main_domain'] . "templates/tpl_resume/" . $tpl_dir . "/");
            return $thistpl;
        } elseif ($type == 'course' || $type == 'train_profile' || $type == 'trainnewsshow') {
            $thistpl = "../tpl_train/{$_CFG['tpl_train']}/";
            $smarty->assign('user_tpl', $_CFG['main_domain'] . "templates/tpl_train/{$_CFG['tpl_train']}/");
            return $thistpl;
        } else {
            $thistpl = "../tpl_company/{$_CFG['tpl_company']}/";
            $smarty->assign('user_tpl', $_CFG['main_domain'] . "templates/tpl_company/{$_CFG['tpl_company']}/");
            return $thistpl;
        }
    } else {
        if ($type == 'resume') {
            $smarty->assign('user_tpl', $_CFG['main_domain'] . "templates/tpl_resume/{$thistpl}/");
            return "../tpl_resume/{$thistpl}/";
        } else {
            $smarty->assign('user_tpl', $_CFG['main_domain'] . "templates/tpl_company/{$thistpl}/");
            return "../tpl_company/{$thistpl}/";
        }
    }
}

function url_rewrite($alias = NULL, $get = NULL, $rewrite = true, $subsite_id = 0) {
    global $_CFG, $_PAGE;
    $_SUBSITE = get_cache('subsite');

    $url = '';
    if ($rewrite == false) {
        $config = get_cache('config');
        $_CFG['version'] = QISHI_VERSION;
        $_CFG['web_logo'] = $config['web_logo'] ? $config['web_logo'] : 'logo.gif';
        $_CFG['upfiles_dir'] = $config['site_dir'] . "data/" . $config['updir_images'] . "/";
        $_CFG['thumb_dir'] = $config['site_dir'] . "data/" . $config['updir_thumb'] . "/";
        $_CFG['resume_photo_dir'] = $config['site_dir'] . "data/" . $config['resume_photo_dir'] . "/";
        $_CFG['resume_photo_dir_thumb'] = $config['site_dir'] . "data/" . $config['resume_photo_dir_thumb'] . "/";
        $_CFG['teacher_photo_dir'] = $config['site_dir'] . "data/train_teachers/";
        $_CFG['teacher_photo_dir_thumb'] = $config['site_dir'] . "data/train_teachers/thumb/";
        $_CFG['hunter_photo_dir'] = $config['site_dir'] . "data/hunter/";
        $_CFG['hunter_photo_dir_thumb'] = $config['site_dir'] . "data/hunter/thumb/";
        if (!empty($get)) {
            foreach ($get as $k => $v) {
                $url .="{$k}={$v}&";
            }
        }
        $url = !empty($url) ? "?" . rtrim($url, '&') : '';

        return $config['site_domain'] . $config['site_dir'] . $_PAGE[$alias]['file'] . $url;
    } else {
        if ($_PAGE[$alias]['url'] == '0') {//原始链接
            if (!empty($get)) {
                foreach ($get as $k => $v) {
                    $url .="{$k}={$v}&";
                }
            }
            $url = !empty($url) ? "?" . rtrim($url, '&') : '';
            if ($subsite_id > 0) {
                $subsite = $_SUBSITE[$subsite_id];
                if ($alias == "QS_login" || $alias == "QS_logout") {
                    return $_CFG['site_dir'] . $_PAGE[$alias]['file'] . $url;
                }
                return "http://" . $subsite['sitedomain'] . "/" . $_PAGE[$alias]['file'] . $url;
            } else {
                return $_CFG['site_domain'] . $_CFG['site_dir'] . $_PAGE[$alias]['file'] . $url;
            }
        } else {
            if ($subsite_id > 0) {
                $subsite = $_SUBSITE[$subsite_id];
                $url = "http://" . $subsite['sitedomain'] . "/" . $_PAGE[$alias]['rewrite'];
            } else {
                $url = $_CFG['site_domain'] . $_CFG['site_dir'] . $_PAGE[$alias]['rewrite'];
            }
            if ($_PAGE[$alias]['pagetpye'] == '2' && empty($get['page'])) {
                $get['page'] = 1;
            }
            foreach ($get as $k => $v) {
                $url = str_replace('($' . $k . ')', $v, $url);
            }

            $url = preg_replace('/\(\$(.+?)\)/', '', $url);
            if (substr($url, -5) == '?key=') {
                $url = rtrim($url, '?key=');
            }
            return $url;
        }
    }
}

function get_member_url($type, $dirname = false, $pre_domain = false) {
    global $_CFG;
    $type = intval($type);
    if ($pre_domain) {
        $domain = $pre_domain;
    } else {
        $domain = $_CFG['main_domain'];
    }
    if ($type === 0) {
        return "";
    } elseif ($type === 1) {
        $return = $domain . "user/company/company_index.php";
    } elseif ($type === 2) {
        $return = $domain . "user/personal/personal_index.php";
    } elseif ($type === 3) {
        $return = $domain . "user/hunter/hunter_index.php";
    } elseif ($type === 4) {
        $return = $domain . "user/train/train_index.php";
    }
    if ($dirname) {
        return dirname($return) . '/';
    } else {
        return $return;
    }
}

function subsiteinfo(&$_CFG) {
    if ($_CFG['subsite'] == "0") {
        $_CFG['subsite_dir'] = $_CFG['site_dir'];
        // $_CFG['main_domain'] = $_CFG['site_domain'];
        $_CFG['website_dir'] = $_CFG['site_domain'] . $_CFG['subsite_dir'];
        return false;
    } else {
        $_SUBSITE = get_cache('subsite');
        foreach ($_SUBSITE as $key => $sub) {
            $_CFG['district_array'][] = array('subsite' => $sub['districtname'], 'url' => "http://" . $sub['sitedomain']);
        }
        // $host=$_SERVER['HTTP_HOST'];
        $subsite_id = intval($_GET['subsite_id']);
        $_CFG['subsite_dir'] = $_CFG['site_dir'];
        // $_CFG['main_domain'] = $_CFG['site_domain'].$_CFG['site_dir'];
        $_CFG['website_dir'] = $_CFG['site_domain'] . $_CFG['subsite_dir'];
        if (array_key_exists($subsite_id, $_SUBSITE)) {
            $subsite = $_SUBSITE[$subsite_id];
            $_CFG['site_domain'] = "http://" . $subsite['sitedomain'];
            $_CFG['subsite_dir'] = "/";
            $_CFG['website_dir'] = $_CFG['site_domain'] . $_CFG['subsite_dir'];
            $_CFG['subsite_districtname'] = $subsite['districtname'];
            $_CFG['site_name'] = $subsite['sitename'];
            if (empty($_GET['district_cn'])) {
                $_GET['district_cn'] = $_CFG['subsite_districtname'];
            }
            $_CFG['subsite_id'] = $subsite_id;
            $subsite['logo'] ? $_CFG['web_logo'] = $subsite['logo'] : '';
            $subsite['tpl'] ? $_CFG['template_dir'] = $subsite['tpl'] . '/' : '';
            $subsite['filter_notice'] ? $_CFG['subsite_filter_notice'] = $subsite['filter_notice'] : '';
            $subsite['filter_jobs'] ? $_CFG['subsite_filter_jobs'] = $subsite['filter_jobs'] : '';
            $subsite['filter_resume'] ? $_CFG['subsite_filter_resume'] = $subsite['filter_resume'] : '';
            $subsite['filter_ad'] ? $_CFG['subsite_filter_ad'] = $subsite['filter_ad'] : '';
            $subsite['filter_links'] ? $_CFG['subsite_filter_links'] = $subsite['filter_links'] : '';
            $subsite['filter_news'] ? $_CFG['subsite_filter_news'] = $subsite['filter_news'] : '';
            $subsite['filter_explain'] ? $_CFG['subsite_filter_explain'] = $subsite['filter_explain'] : '';
            $subsite['filter_jobfair'] ? $_CFG['subsite_filter_jobfair'] = $subsite['filter_jobfair'] : '';
            $subsite['filter_simple'] ? $_CFG['subsite_filter_simple'] = $subsite['filter_simple'] : '';
            $subsite['filter_course'] ? $_CFG['subsite_filter_course'] = $subsite['filter_course'] : '';
        }
    }
}

function fulltextpad($str) {
    if (empty($str)) {
        return '';
    }
    $leng = strlen($str);
    if ($leng >= 8) {
        return $str;
    } else {
        $l = 4 - ($leng / 2);
        return str_pad($str, $leng + $l, '0');
    }
}

function asyn_userkey($uid) {
    global $db;
    $sql = "select * from " . table('members') . " where uid = '" . intval($uid) . "' LIMIT 1";
    $user = $db->getone($sql);
    return md5($user['username'] . $user['pwd_hash'] . $user['password']);
}

function write_syslog($type, $type_name, $str) {
    global $db, $online_ip, $ip_address;
    $l_page = addslashes(request_url());
    $str = addslashes($str);
    $sql = "INSERT INTO " . table('syslog') . " (l_type, l_type_name, l_time,l_ip,l_address,l_page,l_str) VALUES ('{$type}', '{$type_name}', '" . time() . "','{$online_ip}','{$ip_address}','{$l_page}','{$str}')";
    return $db->query($sql);
}

function write_memberslog($uid, $utype, $type, $username, $str, $mode, $op_type, $op_type_cn, $op_used, $op_leave) {
    global $db, $online_ip, $ip_address;
    $sql = "INSERT INTO " . table('members_log') . " (log_uid,log_username,log_utype,log_type,log_addtime,log_ip,log_address,log_value,log_mode,log_op_type,log_op_type_cn,log_op_used,log_op_leave) VALUES ( '{$uid}','{$username}','{$utype}','{$type}', '" . time() . "','{$online_ip}','{$ip_address}','{$str}','{$mode}','{$op_type}','{$op_type_cn}','{$op_used}','{$op_leave}')";
    return $db->query($sql);
}

function write_setmeallog($uid, $username, $value, $type, $amount = '0.00', $is_money = '1', $log_mode = '1', $log_utype = '1') {
    global $db;
    $sql = "INSERT INTO " . table('members_charge_log') . " (log_uid,log_username,log_type,log_addtime,log_value,log_amount,log_ismoney,log_mode,log_utype) VALUES ( '{$uid}','{$username}','{$type}', '" . time() . "','{$value}','{$amount}','{$is_money}','{$log_mode}','{$log_utype}')";
    return $db->query($sql);
}

function request_url() {
    if (isset($_SERVER['REQUEST_URI'])) {
        $url = $_SERVER['REQUEST_URI'];
    } else {
        if (isset($_SERVER['argv'])) {
            $url = $_SERVER['PHP_SELF'] . '?' . $_SERVER['argv'][0];
        } else {
            $url = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        }
    }
    return urlencode($url);
}

function label_replace($templates) {
    global $_CFG;
    $templates = str_replace('{sitename}', $_CFG['site_name'], $templates);
    $templates = str_replace('{sitedomain}', $_CFG['site_domain'] . $_CFG['site_dir'], $templates);
    $templates = str_replace('{username}', $_GET['sendusername'], $templates);
    $templates = str_replace('{password}', $_GET['sendpassword'], $templates);
    $templates = str_replace('{newpassword}', $_GET['newpassword'], $templates);
    $templates = str_replace('{personalfullname}', $_GET['personal_fullname'], $templates);
    $templates = str_replace('{jobsname}', $_GET['jobs_name'], $templates);
    $templates = str_replace('{companyname}', $_GET['companyname'], $templates);
    $templates = str_replace('{paymenttpye}', $_GET['paymenttpye'], $templates);
    $templates = str_replace('{amount}', $_GET['amount'], $templates);
    $templates = str_replace('{oid}', $_GET['oid'], $templates);
    $templates = str_replace('{trainname}', $_GET['trainname'], $templates);
    $templates = str_replace('{coursename}', $_GET['coursename'], $templates);
    $templates = str_replace('{teachername}', $_GET['teachername'], $templates);
    $templates = str_replace('{huntername}', $_GET['huntername'], $templates);
    return $templates;
}

function make_dir($path) {
    if (!file_exists($path)) {
        make_dir(dirname($path));
        @mkdir($path, 0777);
        @chmod($path, 0777);
    }
}

function write_pmsnotice($touid, $toname, $message) {
    global $db;
    $setsqlarr['message'] = trim($message);
    $setsqlarr['msgtype'] = 1;
    $setsqlarr['msgtouid'] = intval($touid);
    $setsqlarr['msgtoname'] = trim($toname);
    $setsqlarr['dateline'] = time();
    $setsqlarr['replytime'] = time();
    $setsqlarr['new'] = 1;
    inserttable(table('pms'), $setsqlarr);
}

//查看会员的日志
function get_last_refresh_date($uid, $type) {
    global $db;
    $sql = "select max(addtime) from " . table('refresh_log') . " where uid=" . intval($uid) . ' and ' . "`type`='" . $type . "'";
    return $db->getone($sql);
}

function get_today_refresh_times($uid, $type) {
    global $db;
    $today = strtotime(date('Y-m-d'));
    $tomorrow = $today + 3600 * 24;
    $sql = "select count(*) from " . table('refresh_log') . " where uid=" . intval($uid) . ' and ' . "`type`='" . $type . "' and addtime>" . $today . " and addtime<" . $tomorrow;
    return $db->getone($sql);
}

function write_refresh_log($uid, $type) {
    $setsqlarr['uid'] = $uid;
    $setsqlarr['type'] = $type;
    $setsqlarr['addtime'] = time();
    inserttable(table('refresh_log'), $setsqlarr);
}

/**
 * 3.5更新内容
 */
function filter_url($alias) {
    global $_PAGE, $smarty;
    $pass = true;
    $url = $_SERVER['REQUEST_URI'];
    $url_arr = explode("?", $url);
    $last_mark_arr = explode(".", $url_arr[0]);
    $last_mark = $last_mark_arr[1];
    $url_type = intval($_PAGE[$alias]['url']);
    $match = preg_match("/htm|html/i", $last_mark);
    // if(($url_type==0 && $match) || ($url_type==1 && !$match)){
    // 	header("HTTP/1.1 404 Not Found"); 
    //    	$smarty->display("404.htm");
    //     exit();
    // }
    /*
      不跳转404，重定向到正确页面
     */
    $param = array();
    if (!empty($url_arr[1])) {
        $param_arr = explode("&", $url_arr[1]);
        foreach ($param_arr as $key => $value) {
            $val = explode("=", $value);
            $param[$val[0]] = $val[1];
        }
    }
    if (($url_type == 0 && $match) || ($url_type == 1 && !$match)) {
        $url = url_rewrite($alias, $param);
        header("Location:" . $url);
        exit();
    }
}

/**
 * utf8转gbk
 * @param $utfstr
 */
function utf8_to_gbk($utfstr) {
    global $UC2GBTABLE;
    $okstr = '';
    if (empty($UC2GBTABLE)) {
        define('CODETABLEDIR', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'encoding' . DIRECTORY_SEPARATOR);
        $filename = CODETABLEDIR . 'gb-unicode.table';
        $fp = fopen($filename, 'rb');
        while ($l = fgets($fp, 15)) {
            $UC2GBTABLE[hexdec(substr($l, 7, 6))] = hexdec(substr($l, 0, 6));
        }
        fclose($fp);
    }
    $okstr = '';
    $ulen = strlen($utfstr);
    for ($i = 0; $i < $ulen; $i++) {
        $c = $utfstr[$i];
        $cb = decbin(ord($utfstr[$i]));
        if (strlen($cb) == 8) {
            $csize = strpos(decbin(ord($cb)), '0');
            for ($j = 0; $j < $csize; $j++) {
                $i++;
                $c .= $utfstr[$i];
            }
            $c = utf8_to_unicode($c);
            if (isset($UC2GBTABLE[$c])) {
                $c = dechex($UC2GBTABLE[$c] + 0x8080);
                $okstr .= chr(hexdec($c[0] . $c[1])) . chr(hexdec($c[2] . $c[3]));
            } else {
                $okstr .= '&#' . $c . ';';
            }
        } else {
            $okstr .= $c;
        }
    }
    $okstr = trim($okstr);
    return $okstr;
}

/**
 * gbk转utf8
 * @param $gbstr
 */
function gbk_to_utf8($gbstr) {
    global $CODETABLE;
    if (empty($CODETABLE)) {
        define('CODETABLEDIR', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'encoding' . DIRECTORY_SEPARATOR);
        $filename = CODETABLEDIR . 'gb-unicode.table';
        $fp = fopen($filename, 'rb');
        while ($l = fgets($fp, 15)) {
            $CODETABLE[hexdec(substr($l, 0, 6))] = substr($l, 7, 6);
        }
        fclose($fp);
    }
    $ret = '';
    $utf8 = '';
    while ($gbstr) {
        if (ord(substr($gbstr, 0, 1)) > 0x80) {
            $thisW = substr($gbstr, 0, 2);
            $gbstr = substr($gbstr, 2, strlen($gbstr));
            $utf8 = '';
            @$utf8 = unicode_to_utf8(hexdec($CODETABLE[hexdec(bin2hex($thisW)) - 0x8080]));
            if ($utf8 != '') {
                for ($i = 0; $i < strlen($utf8); $i += 3)
                    $ret .= chr(substr($utf8, $i, 3));
            }
        } else {
            $ret .= substr($gbstr, 0, 1);
            $gbstr = substr($gbstr, 1, strlen($gbstr));
        }
    }
    return $ret;
}

/**
 * utf8转unicode
 * @param  $c
 */
function utf8_to_unicode($c) {
    switch (strlen($c)) {
        case 1:
            return ord($c);
        case 2:
            $n = (ord($c[0]) & 0x3f) << 6;
            $n += ord($c[1]) & 0x3f;
            return $n;
        case 3:
            $n = (ord($c[0]) & 0x1f) << 12;
            $n += (ord($c[1]) & 0x3f) << 6;
            $n += ord($c[2]) & 0x3f;
            return $n;
        case 4:
            $n = (ord($c[0]) & 0x0f) << 18;
            $n += (ord($c[1]) & 0x3f) << 12;
            $n += (ord($c[2]) & 0x3f) << 6;
            $n += ord($c[3]) & 0x3f;
            return $n;
    }
}

/**
 * unicode转utf8
 * @param  $c
 */
function unicode_to_utf8($c) {
    $str = '';
    if ($c < 0x80) {
        $str .= $c;
    } elseif ($c < 0x800) {
        $str .= (0xC0 | $c >> 6);
        $str .= (0x80 | $c & 0x3F);
    } elseif ($c < 0x10000) {
        $str .= (0xE0 | $c >> 12);
        $str .= (0x80 | $c >> 6 & 0x3F);
        $str .= (0x80 | $c & 0x3F);
    } elseif ($c < 0x200000) {
        $str .= (0xF0 | $c >> 18);
        $str .= (0x80 | $c >> 12 & 0x3F);
        $str .= (0x80 | $c >> 6 & 0x3F);
        $str .= (0x80 | $c & 0x3F);
    }
    return $str;
}

function browser() {
    switch (TRUE) {
        // Apple/iPhone browser renders as mobile
        case (preg_match('/(apple|iphone|ipod)/i', $_SERVER['HTTP_USER_AGENT']) && preg_match('/mobile/i', $_SERVER['HTTP_USER_AGENT'])):
            $browser = "mobile";
            break;
        // Other mobile browsers render as mobile
        case (preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|
	treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])):
            $browser = "mobile";
            break;
        // Wap browser
        case (((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'text/vnd.wap.wml') > 0) || (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0)) || ((isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])))):
            $browser = "mobile";
            break;
        // Shortend user agents
        case (in_array(strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 3)), array('lg ' => 'lg ', 'lg-' => 'lg-', 'lg_' => 'lg_', 'lge' => 'lge')));
            $browser = "mobile";
            break;
        // More shortend user agents
        case (in_array(strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4)), array('acs-' => 'acs-', 'amoi' => 'amoi', 'doco' => 'doco', 'eric' => 'eric', 'huaw' => 'huaw', 'lct_' => 'lct_', 'leno' => 'leno', 'mobi' => 'mobi', 'mot-' => 'mot-', 'moto' => 'moto', 'nec-' => 'nec-', 'phil' => 'phil', 'sams' => 'sams', 'sch-' => 'sch-', 'shar' => 'shar', 'sie-' => 'sie-', 'wap_' => 'wap_', 'zte-' => 'zte-')));
            $browser = "mobile";
            break;
        // Render mobile site for mobile search engines
        case (preg_match('/Googlebot-Mobile/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/YahooSeeker\/M1A1-R2D2/i', $_SERVER['HTTP_USER_AGENT'])):
            $browser = "mobile";
            break;
    }
    return $browser;
}

function get_memcache_time($key) {
    global $mem;
    $list = array();
    $allSlabs = $mem->getExtendedStats('slabs');
    $items = $mem->getExtendedStats('items');
    foreach ($allSlabs as $server => $slabs) {
        foreach ($slabs AS $slabId => $slabMeta) {
            $cdump = $mem->getExtendedStats('cachedump', (int) $slabId, 0);
            foreach ($cdump AS $keys => $arrVal) {
                foreach ($arrVal AS $k => $v) {
                    if ($k == $key) {
                        return time() - $v[1];
                    }
                }
            }
        }
    }
}

function my_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC) {
    if (is_array($arrays)) {
        foreach ($arrays as $array) {
            if (is_array($array)) {
                $key_arrays[] = $array[$sort_key];
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
    array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
    return $arrays;
}

?>