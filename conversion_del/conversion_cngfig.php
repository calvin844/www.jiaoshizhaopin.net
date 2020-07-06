<?php

$StrDBName = "leekingsql";
$StrUid = "leekingsql";
$StrPwd = "Sk8t4IeF";

mssql_connect("server2008", $StrUid, $StrPwd) or die('<script>location.reload();</script>');
mssql_select_db($StrDBName);

function ms_getall($sql, $type = MSSQL_ASSOC, $test = 1) {

    $query = mssql_query($sql);
    while ($row = mssql_fetch_array($query, $type)) {
        $rows[] = $row;
    }
    return $rows;
}

function ms_getone($sql, $type = MSSQL_ASSOC) {
    $query = mssql_query($sql);
    $row = mssql_fetch_array($query, $type);
    return $row;
}

function ms_update($sql) {
    echo $sql . "<br>";
    $query = mssql_query($sql);
    if (!$query) {
        // The query has failed, print a nice error message
        // using mssql_get_last_message()
        die('MSSQL error: ' . mssql_get_last_message());
    }
    return $query;
}

function access_url($url) {
    if ($url == '') {
        return false;
    }
    $fp = fopen($url, 'r');
    if ($fp) {
        while (!feof($fp)) {
            $file.=fgets($fp) . "";
        }
        fclose($fp);
    }
    return $file;
}

function escape_str($str, $again = 0) {
    global $frdbcharset;
    //$cha=mb_detect_encoding($str);
    //echo $cha."<br>";
    $str = iconv("utf-8", 'gbk//IGNORE', $str);
    if ($again != 0) {
        $cha = mb_detect_encoding($str);
        if ($cha == "UTF-8") {
            $str = mb_convert_encoding($str, "GBK", "UTF-8");
        }
    }
    $str = mysql_escape_string($str);
    $str = str_replace('\\\'', '\'\'', $str);
    $str = str_replace("\\\\", "\\\\\\\\", $str);
    $str = str_replace('$', '\$', $str);
    return $str;
}

//Ä£ºýËÑË÷
function locoyspider_search_str($arr, $str, $arrinname, $p = 0) {
    global $locoyspider;
    if ($p == 0) {
        $p = $locoyspider['search_threshold'];
    }
    foreach ($arr as $key => $list) {
        similar_text($list[$arrinname], $str, $percent);
        $od[$percent] = $key;
    }
    krsort($od);
    foreach ($od as $key => $li) {
        if ($key >= $p) {
            return $arr[$li];
        } else {
            return false;
        }
    }
}

?>