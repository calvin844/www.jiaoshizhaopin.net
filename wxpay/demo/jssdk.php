<?php
class Jssdk {
  private $appId = 'wxcc4c59e6d018ce34';
  private $appSecret = 'e820c77b2f6cf1a1e2e987c9a368542a';
  private $mem;
  public function __construct() {
	$this->mem = new Memcache;
	$this->mem->connect("localhost", 11111);
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
	$datastr = $this->mem->get('jsapi_ticket');
	if (empty($datastr)) {
		$datastr = '{"jsapi_ticket":"","expire_time":0}';
	}
	$data = json_decode($datastr);
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
	  $datastr = $this->httpGet($url);
      $res = json_decode($datastr);
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
		$datastr = '{"jsapi_ticket":"'.$ticket.'","expire_time":'.$data->expire_time.'}';
		$this->mem->set('jsapi_ticket', $datastr, 0, 86400);
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;
  }

  public function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
	$datastr = $this->mem->get('access_token');
	if (empty($datastr)) {
		$datastr = '{"access_token":"","expire_time":0}';
	}
	$data = json_decode($datastr);
    if ($data->expire_time < time()) {
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
      $datastr = $this->httpGet($url);
	  $res = json_decode($datastr);
      $access_token = $res->access_token;
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
		$datastr = '{"access_token":"'.$access_token.'","expire_time":'.($data->expire_time + time()).'}';
		$this->mem->set('access_token', $datastr, 0, 7000);
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
  }

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }
	
}

