<?php
define('IN_QISHI', true);
require_once(dirname(__FILE__).'/../data/config.php');
require_once(dirname(__FILE__).'/include/admin_common.inc.php');
define("APPID", $_CFG['weixin_appid']);
define("APPSECRET", $_CFG['weixin_appsecret']);
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPSECRET;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);
$jsoninfo = json_decode($output, true);
$access_token = $jsoninfo["access_token"];
$jsonmenu = '{
     "button":[
     {
	   "name":"找工作",
	   "sub_button":[
	   {	
		   "type":"click",
		   "name":"最新职位",
		   "key":"newjobs"
		},
		{
		   "type":"click",
		   "name":"紧急招聘",
		   "key":"emergencyjobs"
		},
		{
		   "type":"click",
		   "name":"推荐职位",
		   "key":"recommendjobs"
		}]
	  },
      {
           "type":"click",
           "name":"找人才",
           "key":"resume"
      }]
 }';
$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$result = https_request($url, $jsonmenu);
$result_arr = json_decode($result,true);
if($result_arr['errmsg']=='ok'){
  adminmsg(iconv("utf-8","gb2312","更新菜单成功！"),2);
}else{
  adminmsg(iconv("utf-8","gb2312","更新菜单失败，请检查代码！"),1);
}
function https_request($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
?>