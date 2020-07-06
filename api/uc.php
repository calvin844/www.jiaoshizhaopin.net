<?php
define('IN_QISHI', TRUE);
include_once (dirname(dirname(__FILE__))."/include/common.inc.php");
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
$qsdb = new mysql($dbhost,$dbuser,$dbpass,$dbname);
unset($dbhost,$dbuser,$dbpass,$dbname);
define('UC_CLIENT_VERSION', '1.6.0');
define('UC_CLIENT_RELEASE', '20110501');
define('API_DELETEUSER', 1);		//note 用户删除 API 接口开关
define('API_RENAMEUSER', 1);		//note 用户改名 API 接口开关
define('API_GETTAG', 1);		//note 获取标签 API 接口开关
define('API_SYNLOGIN', 1);		//note 同步登录 API 接口开关
define('API_SYNLOGOUT', 1);		//note 同步登出 API 接口开关
define('API_UPDATEPW', 1);		//note 更改用户密码 开关
define('API_UPDATEBADWORDS', 1);	//note 更新关键字列表 开关
define('API_UPDATEHOSTS', 1);		//note 更新域名解析缓存 开关
define('API_UPDATEAPPS', 1);		//note 更新应用列表 开关
define('API_UPDATECLIENT', 1);		//note 更新客户端缓存 开关
define('API_UPDATECREDIT', 1);		//note 更新用户积分 开关
define('API_GETCREDITSETTINGS', 1);	//note 向 UCenter 提供积分设置 开关
define('API_GETCREDIT', 1);		//note 获取用户的某项积分 开关
define('API_UPDATECREDITSETTINGS', 1);	//note 更新应用积分设置 开关
define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');
define('UC_CLIENT_ROOT', QISHI_ROOT_PATH.'uc_client');
//note 普通的 http 通知方式
//note 普通的 http 通知方式
if(!defined('IN_UC')) {

	error_reporting(0);
	set_magic_quotes_runtime(0);
	
	defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

	$_DCACHE = $get = $post = array();

	$code = @$_GET['code'];	
	parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
	if(MAGIC_QUOTES_GPC) {
		$get = _stripslashes($get);
	}
	$timestamp = time();
	if($timestamp - $get['time'] > 3600) {
		exit('Authracation has expiried');
	}
	if(empty($get)) {
		exit('Invalid Request');
	}
	$action = $get['action'];

	require_once UC_CLIENT_ROOT.'/lib/xml.class.php';
	$post = xml_unserialize(file_get_contents('php://input'));

	if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings')))
	{
		$uc_note = new uc_note();
		exit($uc_note->$get['action']($get, $post));
	} else {
		exit(API_RETURN_FAILED);
	}

//note include 通知方式
} else {
	exit(API_RETURN_FAILED);
}
class uc_note {

	var $dbconfig = '';
	var $db = '';
	var $tablepre = '';
	var $appdir = '';

	function _serialize($arr, $htmlon = 0) {
		if(!function_exists('xml_serialize')) {
			include_once UC_CLIENT_ROOT.'/lib/xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	function uc_note() {
		$this->appdir =QISHI_ROOT_PATH;
		$this->dbconfig = QISHI_ROOT_PATH.'data/config.php';
		$this->db = $GLOBALS['qsdb'];
		$this->tablepre = $GLOBALS['pre'];
	}

	function test($get, $post) {
		return API_RETURN_SUCCEED;
	}
//删除用户
	function deleteuser($get, $post) {
		!API_DELETEUSER && exit(API_RETURN_FORBIDDEN);
		return API_RETURN_SUCCEED;
	}
//用户的用户名
	function renameuser($get, $post) {
		if(!API_RENAMEUSER) {
			return API_RETURN_FORBIDDEN;
		}
		return API_RETURN_SUCCEED;
	}
//进行同步登录
	function synlogin($get, $post) {
		global $QS_cookiedomain,$QS_cookiepath;
		$username = $get['username'];
		if(!API_SYNLOGIN)
		{
			return API_RETURN_FORBIDDEN;
		}
		//note 同步登录 API 接口\
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		$result=$this->db->getone("SELECT * FROM ".$this->tablepre."members WHERE username='".$username."' LIMIT 1 ");
		if(!empty($result))
		{
			$_SESSION['uid'] =  $result['uid'];
			$_SESSION['username'] =$result['username'];
			$_SESSION['utype']= $result['utype'];
			$_SESSION['uqqid']= $result['qq_openid'];
			setcookie('QS[uid]',$result['uid'],           time()+3600*24,$QS_cookiepath,$QS_cookiedomain);
		    setcookie('QS[username]',$result['username'], time()+3600*24,$QS_cookiepath,$QS_cookiedomain);
		    setcookie('QS[password]',$result['password'], time()+3600*24,$QS_cookiepath,$QS_cookiedomain);
		    setcookie('QS[utype]',  $result['utype'],     time()+3600*24,$QS_cookiepath,$QS_cookiedomain);
		} 
		else
		{
		$_SESSION['activate_username']=$username;
		$_SESSION['uid'] = '';
		$_SESSION['username'] = '';
		$_SESSION['utype']='';
		setcookie('QS[uid]','', time()-3600,$QS_cookiepath,$QS_cookiedomain);
		setcookie('QS[username]','', time()-3600,$QS_cookiepath,$QS_cookiedomain);
		setcookie('QS[password]','', time()-3600,$QS_cookiepath,$QS_cookiedomain);
		setcookie('QS[utype]','', time()-3600,$QS_cookiepath,$QS_cookiedomain);
		//file_put_contents("2.txt",  var_export($_SESSION, true), LOCK_EX);
		}
	}
	function synlogout($get, $post) {
		global $QS_cookiepath,$QS_cookiedomain;
		if(!API_SYNLOGOUT)
		{
			return API_RETURN_FORBIDDEN;
		}
		//note 同步登出 API 接口
		//file_put_contents("synlogout.txt",  var_export($_SESSION, true), LOCK_EX);
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		unset($_SESSION['uid']);
		unset($_SESSION['username']);
		unset($_SESSION['utype']);
		unset($_SESSION['uqqid']);
		unset($_SESSION["openid"]);
		unset($_SESSION['activate_username']);
		unset($_SESSION['activate_email']);
		setcookie('QS[uid]','', time()-3600,$QS_cookiepath,$QS_cookiedomain);
		setcookie('QS[username]','', time()-3600,$QS_cookiepath,$QS_cookiedomain);
		setcookie('QS[password]','', time()-3600,$QS_cookiepath,$QS_cookiedomain);
		setcookie('QS[utype]','', time()-3600,$QS_cookiepath,$QS_cookiedomain);			
	}
	function updatepw($get, $post) {
		if(!API_UPDATEPW)
		{
			return API_RETURN_FORBIDDEN;
		}
		//note 修改密码 API 接口
		$username = $get['username'];
		$password = $get['password'];
		file_put_contents("password.txt",  $get['password'], LOCK_EX);
		if ($username && $password)
		{		
		require_once(QISHI_ROOT_PATH.'data/config.php');
		$result=$this->db->getone("SELECT * FROM ".$this->tablepre."members WHERE username='".$username."' LIMIT 1 ");
		$md5password=md5(md5($password).$result['pwd_hash'].$QS_pwdhash);
		$this->db->query("UPDATE  ".$this->tablepre."members SET password='{$md5password}' WHERE username='{$username}' LIMIT 1 ");
		}
		return API_RETURN_SUCCEED;
	}

	function updatebadwords($get, $post) {
		if(!API_UPDATEBADWORDS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'uc_client/data/cache/badwords.php';
		$fp = fopen($cachefile, 'w');
		$data = array();
		if(is_array($post)) {
			foreach($post as $k => $v) {
				$data['findpattern'][$k] = $v['findpattern'];
				$data['replace'][$k] = $v['replacement'];
			}
		}
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'badwords\'] = '.var_export($data, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatehosts($get, $post) {
		if(!API_UPDATEHOSTS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'uc_client/data/cache/hosts.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updateapps($get, $post) {
		if(!API_UPDATEAPPS) {
			return API_RETURN_FORBIDDEN;
		}
		$UC_API = $post['UC_API'];

		//note 写 app 缓存文件
		$cachefile = $this->appdir.'uc_client/data/cache/apps.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updateclient($get, $post) {
		if(!API_UPDATECLIENT) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'uc_client/data/cache/settings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}
//当某应用执行了积分兑换请求的接口函数 uc_credit_exchange_request() 后
	function updatecredit($get, $post) {
		if(!API_UPDATECREDIT) {
			return API_RETURN_FORBIDDEN;
		}
		 
		return API_RETURN_SUCCEED;
	}
//此接口用于把应用程序中指定用户的积分传递给 UCenter。
	function getcredit($get, $post) {
		if(!API_GETCREDIT) {
			return API_RETURN_FORBIDDEN;
		}
	}
//此接口负责把应用程序的积分设置传递给 UCenter，以供 UCenter 在积分兑换设置中使用。
	function getcreditsettings($get, $post) {
		if(!API_GETCREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}
		 
	}
//此接口负责接收 UCenter 积分兑换设置的参数。
	function updatecreditsettings($get, $post) {
		if(!API_UPDATECREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}
		 
	} 
}
//note 使用该函数前需要 require_once $this->appdir.'./config.inc.php';
function _setcookie($var, $value, $life = 0, $prefix = 1) {
	global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
	setcookie(($prefix ? $cookiepre : '').$var, $value,
		$life ? $timestamp + $life : 0, $cookiepath,
		$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
				return '';
			}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function _stripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = _stripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}
?>