<?php

/*
 * 74cms 数据库操作
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if (!defined('IN_QISHI'))
    exit('Access Denied!');

class mysql {

    var $linkid = null;

    function __construct($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = 'gbk', $connect = 1) {
        $this->connect($dbhost, $dbuser, $dbpw, $dbname, $dbcharset, $connect);
    }

    function connect($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = 'gbk', $connect = 1) {
        $func = empty($connect) ? 'mysql_pconnect' : 'mysql_connect';
        if (!$this->linkid = @$func($dbhost, $dbuser, $dbpw, true)) {
            $this->dbshow('Can not connect to Mysql!' . mysql_error());
        } else {
            if ($this->dbversion() > '4.1') {
                mysql_query("SET NAMES gbk");
                if ($this->dbversion() > '5.0.1') {
                    mysql_query("SET sql_mode = ''", $this->linkid);
                    mysql_query("SET character_set_connection=" . $dbcharset . ", character_set_results=" . $dbcharset . ", character_set_client=binary", $this->linkid);
                }
            }
        }
        if ($dbname) {
            if (mysql_select_db($dbname, $this->linkid) === false) {
                $this->dbshow("Can't select MySQL database($dbname)!");
            }
        }
    }

    function select_db($dbname) {
        return mysql_select_db($dbname, $this->linkid);
    }

    function query($sql) {
        if (!$query = @mysql_query($sql, $this->linkid)) {
            $this->dbshow("Query error:$sql");
        } else {
            return $query;
        }
    }

    function getall($sql, $type = MYSQL_ASSOC) {
        $query = $this->query($sql);
        while ($row = mysql_fetch_array($query, $type)) {
            $rows[] = $row;
        }
        return $rows;
    }

    function getone($sql, $type = MYSQL_ASSOC) {
        $query = $this->query($sql, $this->linkid);
        $row = mysql_fetch_array($query, $type);
        return $row;
    }

    function get_total($sql) {
        $row = $this->getall($sql);
        $v = 0;
        if (!empty($row) && is_array($row)) {
            foreach ($row as $n) {
                $v = $v + $n['num'];
            }
        }
        return $v;
    }

    function getfirst($sql, $type = MYSQL_NUM) {
        $query = $this->query($sql, $this->linkid);
        $row = mysql_fetch_array($query, $type);
        return $row[0];
    }

    function fetch_array($result, $type = MYSQL_ASSOC) {
        return mysql_fetch_array($result, $type);
    }

    function affected_rows() {
        return mysql_affected_rows($this->linkid);
    }

    function num_rows() {
        return mysql_num_rows($this->linkid);
    }

    function num_fields($result) {
        return mysql_num_fields($result);
    }

    function insert($table = "", $in = array()) {
        $key_str = "";
        $v_str = "";
        foreach ($in as $k => $v) {
            $key_str.=$k . ",";
            $v = trim($v);
// 去除斜杠
            if (get_magic_quotes_gpc()) {
                $v = stripslashes($v);
            }
// 如果不是数字则加引号
            if (!is_numeric($value)) {
                $v = "'" . mysql_real_escape_string($v) . "',";
            }
            $v_str.= $v;
        }
        $key_str = trim($key_str, ",");
        $v_str = trim($v_str, ",");
        $this->query("INSERT INTO " . table($table) . " (" . $key_str . ") VALUES (" . $v_str . ")");
        $insert_id = $this->insert_id();
        if ($insert_id > 0) {
            return $insert_id;
        } else {
            return FALSE;
        }
    }

    function update($table = "", $up = array(), $where = "") {
        if (!empty($where)) {
            $str = "";
            foreach ($up as $k => $v) {
                $v = trim($v);
// 去除斜杠
                if (get_magic_quotes_gpc()) {
                    $v = stripslashes($v);
                }
// 如果不是数字则加引号
                if (!is_numeric($value)) {
                    $v = mysql_real_escape_string($v);
                }
                $str .= "`" . $k . "` = '" . $v . "',";
            }
            $str = trim($str, ",");
            $this->query("UPDATE " . $table . " SET " . $str . " WHERE " . $where);
        }
    }

    function insert_id() {
        return mysql_insert_id($this->linkid);
    }

    function free_result() {
        return mysql_free_result($this->linkid);
    }

    function escape_string($string) {
        if (PHP_VERSION >= '4.3') {
            return mysql_real_escape_string($string, $this->linkid);
        } else {
            return mysql_escape_string($string, $this->linkid);
        }
    }

    function error() {
        return mysql_error($this->linkid);
    }

    function errno() {
        return mysql_errno($this->linkid);
    }

    function close() {
        return mysql_close($this->linkid);
    }

    function dbversion() {
        return mysql_get_server_info($this->linkid);
    }

    function dbshow($err) {
        if ($err) {
            $info = "Error：" . $err;
        } else {
            $info = "Errno：" . $this->errno() . " Error：" . $this->error();
        }
        exit($info);
    }

}

?>