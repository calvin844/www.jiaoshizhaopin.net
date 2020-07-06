<?php
define('IN_QISHI', true);
require_once(dirname(__FILE__).'/../include/plus.common.inc.php');
define("TOKEN", $_CFG['weixin_apptoken']);
define("ROOT",$_CFG['site_domain']);
define("FIRST_PIC",$_CFG['weixin_first_pic']);
define("DEFAULT_PIC",$_CFG['weixin_default_pic']);
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
class wechatCallbackapiTest extends mysql
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature())
		{
        	exit($echoStr);
        }
    }
    public function responseMsg()
    {
		if(!$this->checkSignature())
		{
        	exit();
        }
		$postStr = addslashes($GLOBALS["HTTP_RAW_POST_DATA"]);
		if (!empty($postStr))
		{
			libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
			$keyword = utf8_to_gbk($keyword);
            $time = time();
			$event = trim($postObj->Event);
			
			if ($event === "subscribe")
			{
				$word= "回复j返回紧急招聘，回复n返回最新招聘！您可以尝试输入职位名称如“会计”，系统将会返回您要找的信息，我们努力打造最人性化的服务平台，谢谢关注。";
				$word = gbk_to_utf8($word);
				$text="<xml>
				<ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
				<FromUserName><![CDATA[".$toUsername."]]></FromUserName>
				<CreateTime>".$time."</CreateTime>
				<MsgType><![CDATA[text]]></MsgType>
				<Content><![CDATA[".$word."]]></Content>
				</xml> ";
				exit($text);				
			}	
			$default_pic=ROOT."/data/images/".DEFAULT_PIC;
			$first_pic=ROOT."/data/images/".FIRST_PIC;

			if($event === "CLICK"){
				if($_CFG['weixin_apiopen']=='0')
				{
					$word="网站微信接口已经关闭";
					$word = gbk_to_utf8($word);
					$text="<xml>
					<ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
					<FromUserName><![CDATA[".$toUsername."]]></FromUserName>
					<CreateTime>".$time."</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[".$word."]]></Content>
					</xml> ";
					exit($text);
				}
				switch ($postObj->EventKey)
				{
					case "newjobs":
						$type=1;
						$jobstable=table('jobs_search_rtime');	
						break;
					case "emergencyjobs":
						$type=1;
						$jobstable=table('jobs_search_rtime');
						$wheresql=" where `emergency`=1 ";	
						break;
					case "recommendjobs":
						$type=1;
						$jobstable=table('jobs_search_rtime');
						$wheresql=" where `recommend`=1 ";	
						break;
					case "resume":
						$type=2;
						$jobstable=table('resume_search_rtime');	
						break;
					default:
						$type=1;
						$jobstable=table('jobs_search_rtime');	
						break;
				}
				$limit=" LIMIT 5";
				$orderbysql=" ORDER BY refreshtime DESC";
				$word='';
				$list = $id = array();
				$idresult = $this->query("SELECT id FROM {$jobstable} ".$wheresql.$orderbysql.$limit);
				while($row = $this->fetch_array($idresult))
				{
				$id[]=$row['id'];
				}
				if (!empty($id))
				{
					$wheresql=" WHERE id IN (".implode(',',$id).") ";
					if($type==1){
						$result = $this->query("SELECT * FROM ".table('jobs').$wheresql.$orderbysql);
					}elseif($type==2){
						$result = $this->query("SELECT * FROM ".table('resume').$wheresql.$orderbysql);
					}
					
					$count=mysql_num_rows($result);
					$i=1;
					$strmiddle="";
					$strbegin="<xml>
							 <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
							 <FromUserName><![CDATA[".$toUsername."]]></FromUserName>
							 <CreateTime>".$time."</CreateTime>
							 <MsgType><![CDATA[news]]></MsgType>
							 <ArticleCount>".$count."</ArticleCount>
							 <Articles>";	
					while($row = $this->fetch_array($result))
					{
						if($i==1){
	                        $picurl=$first_pic;	
						}else{
							$picurl=$default_pic;	
						}
						$i++;
						if($type==1){
							$jobs_name=gbk_to_utf8($row['jobs_name']);				
						    $companyname=gbk_to_utf8($row['companyname']);
						    $title=$jobs_name."--".$companyname;
						    $url=ROOT."/wap/wap-jobs-show.php?id=".$row['id'];
						}elseif($type==2){
							$fullname=gbk_to_utf8($row['fullname'])."(".gbk_to_utf8($row['sex_cn']).")";				
						    $intention_jobs=gbk_to_utf8($row['intention_jobs']);
						    $title=$fullname."--".$intention_jobs;
						    $url=ROOT."/wap/wap-resume-show.php?id=".$row['id'];
						}
						$strmiddle.="<item>
									 <Title><![CDATA[".$title."]]></Title> 
									 <Description><![CDATA[".$con."]]></Description>
									 <PicUrl><![CDATA[".$picurl."]]></PicUrl>	
									 <Url><![CDATA[".$url."]]></Url>
									 </item>";
					}
					$strend = "</Articles>
							 <FuncFlag>1</FuncFlag>
							 </xml>";
					$word = $strbegin.$strmiddle.$strend;
				}
				if(empty($word))
				{
					$word="没有找到相应的信息";
					$word = gbk_to_utf8($word);
					$text="<xml>
					<ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
					<FromUserName><![CDATA[".$toUsername."]]></FromUserName>
					<CreateTime>".$time."</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[".$word."]]></Content>
					</xml> ";
					exit($text);
				}
				else
				{
					exit($word);
				}	
			}
	       	if (!empty($keyword))
			{
			
				if($_CFG['weixin_apiopen']=='0')
				{
						$word="网站微信接口已经关闭";
						$word = gbk_to_utf8($word);
						$text="<xml>
						<ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
						<FromUserName><![CDATA[".$toUsername."]]></FromUserName>
						<CreateTime>".$time."</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[".$word."]]></Content>
						</xml> ";
						exit($text);
				}
			
				$limit=" LIMIT 5";
				$orderbysql=" ORDER BY refreshtime DESC";
				if($keyword=="n")
				{
					$jobstable=table('jobs_search_rtime');			 
				}
				else if($keyword=="j")
				{
					$jobstable=table('jobs_search_rtime');
					$wheresql=" where `emergency`=1 ";	
				}
				else
				{
				$jobstable=table('jobs_search_key');
				$wheresql.=" where likekey LIKE '%{$keyword}%' ";
				}
				$word='';
				$list = $id = array();
				$idresult = $this->query("SELECT id FROM {$jobstable} ".$wheresql.$orderbysql.$limit);
				while($row = $this->fetch_array($idresult))
				{
				$id[]=$row['id'];
				}
				if (!empty($id))
				{
					$wheresql=" WHERE id IN (".implode(',',$id).") ";
					$result = $this->query("SELECT * FROM ".table('jobs').$wheresql.$orderbysql);
					$count=mysql_num_rows($result);
					$i=1;
					$strmiddle="";
					$strbegin="<xml>
							 <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
							 <FromUserName><![CDATA[".$toUsername."]]></FromUserName>
							 <CreateTime>".$time."</CreateTime>
							 <MsgType><![CDATA[news]]></MsgType>
							 <ArticleCount>".$count."</ArticleCount>
							 <Articles>";	
					while($row = $this->fetch_array($result))
					{
						$jobs_name=gbk_to_utf8($row['jobs_name']);				
					    $companyname=gbk_to_utf8($row['companyname']);
					    $title=$jobs_name."--".$companyname;
					    $url=ROOT."/wap/wap-jobs-show.php?id=".$row['id'];

					    if($i==1){
	                        $picurl=$first_pic;	
						}else{
							$picurl=$default_pic;	
						}
						$i++;
						$strmiddle.="<item>
									 <Title><![CDATA[".$title."]]></Title> 
									 <Description><![CDATA[".$con."]]></Description>
									 <PicUrl><![CDATA[".$picurl."]]></PicUrl>	
									 <Url><![CDATA[".$url."]]></Url>
									 </item>";
					}
					$strend = "</Articles>
							 <FuncFlag>1</FuncFlag>
							 </xml>";
					$word = $strbegin.$strmiddle.$strend;
				}
				if(empty($word))
				{
					$word="没有找到包含关键字 {$keyword} 的信息，试试其他关键字";
					$word = gbk_to_utf8($word);
					$text="<xml>
					<ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
					<FromUserName><![CDATA[".$toUsername."]]></FromUserName>
					<CreateTime>".$time."</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[".$word."]]></Content>
					</xml> ";
					exit($text);
				}
				else
				{
					exit($word);
				}	 
			}
			else 
			{
			exit("");
			}
    	}
	}	
	private function checkSignature()
	{
	    $signature = $_GET["signature"];
	    $timestamp = $_GET["timestamp"];
	    $nonce = $_GET["nonce"];        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );		
		if($tmpStr == $signature )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
//
$wechatObj = new wechatCallbackapiTest($dbhost,$dbuser,$dbpass,$dbname);
if(isset($_REQUEST['echostr']))
			 $wechatObj->valid();
elseif(isset($_REQUEST['signature']))
{			  
	$wechatObj->responseMsg();
}
		
?>