<?php 
define('IN_QISHI', true);
set_time_limit(0);
$common_path ="../include/common.inc.php";
	if (file_exists($common_path))
	{
	include($common_path);
	}
	else
	{
	exit("没有找到74CMS的程序目录！");
	}
require_once(dirname(__FILE__).'/mysql.class.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
$version_old = '3.4';
$version_new = '3.5';
function update_category($name,$id){
		switch ($name) 
		{
			case '项目经理/产品经理':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=705;
				$catearr['category_cn']='产品经理/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '软件项目经理/主管':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=715;
				$catearr['category_cn']='软件工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '项目执行/协调人员':
				$catearr['topclass']=74;
				$catearr['category']=78;
				$catearr['subclass']=729;
				$catearr['category_cn']='IT项目执行/协调';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '软件工程师':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=715;
				$catearr['category_cn']='软件工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '系统分析师':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=718;
				$catearr['category_cn']='系统分析/架构';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '系统工程师':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=715;
				$catearr['category_cn']='软件工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '研发工程师':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=701;
				$catearr['category_cn']='软件工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '硬件工程师':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=723;
				$catearr['category_cn']='硬件工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '硬件测试工程师':
				$catearr['topclass']=74;
				$catearr['category']=79;
				$catearr['subclass']=735;
				$catearr['category_cn']='硬件测试';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '软件测试工程师':
				$catearr['topclass']=74;
				$catearr['category']=79;
				$catearr['subclass']=734;
				$catearr['category_cn']='软件测试';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '数据库工程师':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=716;
				$catearr['category_cn']='数据库开发/管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电子商务':
				$catearr['topclass']=1;
				$catearr['category']=4;
				$catearr['subclass']=574;
				$catearr['category_cn']='电子商务';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '网站优化/SEO推广':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=707;
				$catearr['category_cn']='网站优化/SEO';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '互联网开发工程师':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=701;
				$catearr['category_cn']='互联网软件开发';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '语音视频开发工程师':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=742;
				$catearr['category_cn']='其他通信';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '通信技术工程师':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=737;
				$catearr['category_cn']='通信技术工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '信息安全工程师':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=711;
				$catearr['category_cn']='网络信息安全工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '网络工程师':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=710;
				$catearr['category_cn']='网络工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '网站营运经理/主管':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=703;
				$catearr['category_cn']='网站运营';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '网站策划':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=708;
				$catearr['category_cn']='网站策划';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '网站编辑':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=709;
				$catearr['category_cn']='网站编辑';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '网页设计/制作':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=699;
				$catearr['category_cn']='网页设计/美工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '技术文员/助理':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=714;
				$catearr['category_cn']='其他互联网/网络';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '多媒体设计与开发':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=927;
				$catearr['category_cn']='多媒体设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '技术支持工程师':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=603;
				$catearr['category_cn']='其他客服/技术支持';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '系统维护工程师':
				$catearr['topclass']=74;
				$catearr['category']=75;
				$catearr['subclass']=692;
				$catearr['category_cn']='计算机维修/维护';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '网络管理员':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=702;
				$catearr['category_cn']='网络管理员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '游戏设计与开发':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=713;
				$catearr['category_cn']='游戏设计/开发';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他计算机类职位':
				$catearr['topclass']=74;
				$catearr['category']=75;
				$catearr['subclass']=698;
				$catearr['category_cn']='其他计算机应用';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '营销总监':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=580;
				$catearr['category_cn']='市场营销';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '市场总监':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=577;
				$catearr['category_cn']='市场总监';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '销售总监':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=542;
				$catearr['category_cn']='销售总监';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '生产总监':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=787;
				$catearr['category_cn']='技术研发经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '财务总监':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=649;
				$catearr['category_cn']='财务总监';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '行政总监':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=637;
				$catearr['category_cn']='行政总监';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '人力资源总监':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=624;
				$catearr['category_cn']='人力资源总监';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '运营总监':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=623;
				$catearr['category_cn']='其他经营管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '信息总监':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=971;
				$catearr['category_cn']='情报信息分析/调研员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '副总经理':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=615;
				$catearr['category_cn']='副总经理/副总裁';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '总经理/CEO':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=614;
				$catearr['category_cn']='CEO/总裁/总经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '技术总监/经理':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=618;
				$catearr['category_cn']='办事处/分公司经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '总裁助理/总经理助理':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=616;
				$catearr['category_cn']='总裁助理/总经理助理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '项目合伙人':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=623;
				$catearr['category_cn']='其他经营管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '部门经理':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=620;
				$catearr['category_cn']='部门经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '分公司/办事处经理':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=618;
				$catearr['category_cn']='办事处/分公司经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他管理类职位':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=623;
				$catearr['category_cn']='其他经营管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '市场部经理':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=577;
				$catearr['category_cn']='市场总监';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '市场助理':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=579;
				$catearr['category_cn']='市场专员/助理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '市场策划主管':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=581;
				$catearr['category_cn']='市场策划经理/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '市场调研主管':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=583;
				$catearr['category_cn']='市场调研/业务分析';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '市场分析/调研专员':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=583;
				$catearr['category_cn']='市场调研/业务分析';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '产品品牌经理/主管':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=584;
				$catearr['category_cn']='品牌经理/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '客户开发主管':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=601;
				$catearr['category_cn']='客户关系管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '客户维护主管':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=601;
				$catearr['category_cn']='客户关系管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '客户代表':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=557;
				$catearr['category_cn']='客户代表';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '促销主管':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=556;
				$catearr['category_cn']='导购员/促销员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '促销员':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=556;
				$catearr['category_cn']='导购员/促销员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '广告企划主管':
				$catearr['topclass']=169;
				$catearr['category']=170;
				$catearr['subclass']=905;
				$catearr['category_cn']='广告客户经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '广告策划':
				$catearr['topclass']=169;
				$catearr['category']=170;
				$catearr['subclass']=908;
				$catearr['category_cn']='文案/策划';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '广告设计':
				$catearr['topclass']=169;
				$catearr['category']=170;
				$catearr['subclass']=907;
				$catearr['category_cn']='广告创意/设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '文案创意':
				$catearr['topclass']=169;
				$catearr['category']=170;
				$catearr['subclass']=908;
				$catearr['category_cn']='文案/策划';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '公关经理/主管':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=588;
				$catearr['category_cn']='公关经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '媒介经理':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=591;
				$catearr['category_cn']='媒介经理/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '媒介推广专员':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=591;
				$catearr['category_cn']='媒介经理/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '市场营销专员':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=580;
				$catearr['category_cn']='市场营销';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '市场企划经理/主管':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=581;
				$catearr['category_cn']='市场策划经理/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '市场企划专员':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=581;
				$catearr['category_cn']='市场策划经理/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '会务经理/主管':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=590;
				$catearr['category_cn']='会务经理/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '会务专员':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=590;
				$catearr['category_cn']='会务经理/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他营销类职位':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=593;
				$catearr['category_cn']='其他市场/策划/公关';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '销售部经理':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=543;
				$catearr['category_cn']='销售经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '业务拓展主管/经理':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=550;
				$catearr['category_cn']='业务拓展(BD)经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '大客户经理':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=551;
				$catearr['category_cn']='大客户经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '销售工程师':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=555;
				$catearr['category_cn']='销售工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '销售主管/助理':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=545;
				$catearr['category_cn']='销售主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '大区经理':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=548;
				$catearr['category_cn']='区域销售经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '客户经理':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=547;
				$catearr['category_cn']='客户经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '办事处经理/主任':
				$catearr['topclass']=1;
				$catearr['category']=20;
				$catearr['subclass']=618;
				$catearr['category_cn']='办事处/分公司经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '店面经理':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=548;
				$catearr['category_cn']='客户经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '渠道经理/主管':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=549;
				$catearr['category_cn']='渠道经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '商务经理':
				$catearr['topclass']=1;
				$catearr['category']=4;
				$catearr['subclass']=571;
				$catearr['category_cn']='商务经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '商务专员/助理':
				$catearr['topclass']=1;
				$catearr['category']=4;
				$catearr['subclass']=572;
				$catearr['category_cn']='商务专员/助理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '销售代表':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=554;
				$catearr['category_cn']='业务员/销售代表';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '客户服务/咨询':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=970;
				$catearr['category_cn']='咨询员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '售前/售后工程师':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=559;
				$catearr['category_cn']='售前技术支持';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '咨询热线/呼叫中心':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=594;
				$catearr['category_cn']='呼叫中心/电话客服';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电话销售':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=556;
				$catearr['category_cn']='电话销售';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电话销售':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=561;
				$catearr['category_cn']='电话销售';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '客服专员':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=596;
				$catearr['category_cn']='客服专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他销售类职位':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=603;
				$catearr['category_cn']='其他客服/技术支持';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '平面设计与制作':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=922;
				$catearr['category_cn']='平面设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '三维动画设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=925;
				$catearr['category_cn']='动画/3D设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '网页美工设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=928;
				$catearr['category_cn']='美术编辑/设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '产品包装设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=929;
				$catearr['category_cn']='包装设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '室内装潢设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=924;
				$catearr['category_cn']='室内设计师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '工业造型设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=931;
				$catearr['category_cn']='工艺品/珠宝设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '工业/产品设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=932;
				$catearr['category_cn']='工业设计/产品设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '服装/造型设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=934;
				$catearr['category_cn']='服装/纺织品设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '家具/家居用品设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=930;
				$catearr['category_cn']='家具/家居用品设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '玩具设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=935;
				$catearr['category_cn']='其他艺术设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '珠宝设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=931;
				$catearr['category_cn']='工艺品/珠宝设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '景观设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=935;
				$catearr['category_cn']='其他艺术设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '设计管理人员':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=744;
				$catearr['category_cn']='工程项目管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '美术/图形设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=928;
				$catearr['category_cn']='美术编辑/设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '媒体广告设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=927;
				$catearr['category_cn']='多媒体设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '多媒体设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=927;
				$catearr['category_cn']='多媒体设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '店面/展览设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=926;
				$catearr['category_cn']='店面/陈列/展览设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '形象设计':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=935;
				$catearr['category_cn']='其他艺术设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他设计类职位':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=935;
				$catearr['category_cn']='其他艺术设计';
				update_data($catearr,$id);
				unset($catearr);
				break;	
			case '电子工程师':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=865;
				$catearr['category_cn']='电子工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电器工程师':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=882;
				$catearr['category_cn']='电气工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电信工程师':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=742;
				$catearr['category_cn']='其他通信';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '通信技术工程师':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=737;
				$catearr['category_cn']='通信技术工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '嵌入式系统软件开发':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=724;
				$catearr['category_cn']='嵌入式软/硬件开发';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电路工程师':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=737;
				$catearr['category_cn']='通信技术工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '自动控制工程师':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=883;
				$catearr['category_cn']='自动控制工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '无线电技术':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=738;
				$catearr['category_cn']='无线通信工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '有线传输工程师':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=739;
				$catearr['category_cn']='数据通信工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '无线通信工程师':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=738;
				$catearr['category_cn']='无线通信工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '移动通信工程师':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=740;
				$catearr['category_cn']='移动通信工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '数据通信工程师':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=739;
				$catearr['category_cn']='数据通信工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '增值产品开发工程师':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=742;
				$catearr['category_cn']='其他通信';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '数码产品开发工程师':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=724;
				$catearr['category_cn']='嵌入式软/硬件开发';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '集成电路(IC)设计':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=870;
				$catearr['category_cn']='版图/电路设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '半导体工程师':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=871;
				$catearr['category_cn']='电子材料/半导体';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '音响工程师/技术员':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=865;
				$catearr['category_cn']='电子工程师/技术员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电子电器维修工程师':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=874;
				$catearr['category_cn']='电子设备维修';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其它电子通信类职位':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=880;
				$catearr['category_cn']='其他电子/半导体/仪表';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电力工程师':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=890;
				$catearr['category_cn']='电力工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '机械工程师':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=828;
				$catearr['category_cn']='机械工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '模具工程师':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=830;
				$catearr['category_cn']='模具工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '模具设计':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=851;
				$catearr['category_cn']='模具工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '机械制图员':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=827;
				$catearr['category_cn']='机械设计师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '机电工程师':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=884;
				$catearr['category_cn']='机电工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '机电一体化':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=884;
				$catearr['category_cn']='机电工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '铸造工程师':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='其他机械';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '锻造工程师':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='其他机械';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '注塑工程师':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='其他机械';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'CNC工程师':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='其他机械';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '冲压工程师':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='其他机械';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '机械设计师':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=827;
				$catearr['category_cn']='机械设计师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '精密机械':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=833;
				$catearr['category_cn']='精密机械/仪器仪表';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '仪器仪表':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=833;
				$catearr['category_cn']='精密机械/仪器仪表';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '纺织机械':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='其他机械';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '机械维修工程师':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=831;
				$catearr['category_cn']='机械维修工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '汽车设计':
				$catearr['topclass']=116;
				$catearr['category']=119;
				$catearr['subclass']=819;
				$catearr['category_cn']='汽车设计工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '飞行器设计':
				$catearr['topclass']=116;
				$catearr['category']=119;
				$catearr['subclass']=819;
				$catearr['category_cn']='其他机械';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '焊接工程师':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=841;
				$catearr['category_cn']='电焊/铆焊工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '锅炉工程师':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=840;
				$catearr['category_cn']='空调/电梯/锅炉工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '装配工程师':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=845;
				$catearr['category_cn']='装卸/叉车工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '船舶工程师':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=853;
				$catearr['category_cn']='其他技术工人';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '设备维修安装人员':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=849;
				$catearr['category_cn']='安装/调试';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '能源水利':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=853;
				$catearr['category_cn']='其他技术工人';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '矿产冶金':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=896;
				$catearr['category_cn']='地质勘查/开采';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电气工程师':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=882;
				$catearr['category_cn']='电气工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '光源与照明工程':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=892;
				$catearr['category_cn']='光源与照明工程';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '水利/水电工程师':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=889;
				$catearr['category_cn']='水利/水电工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '核力/火力工程师':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=897;
				$catearr['category_cn']='其他电力/能源';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '空调/热能工程师':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=894;
				$catearr['category_cn']='空调/热能工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他机械类职位':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='其他机械';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '建筑总工程师':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=743;
				$catearr['category_cn']='高级建筑工程师/总工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '土建工程师':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=753;
				$catearr['category_cn']='土木土建';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电气工程师':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=882;
				$catearr['category_cn']='电气工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '给排水工程师':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=761;
				$catearr['category_cn']='给排水';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '暖通工程师':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=762;
				$catearr['category_cn']='制冷暖通';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '结构工程师':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=754;
				$catearr['category_cn']='结构工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '建筑设计师':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=751;
				$catearr['category_cn']='建筑师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '结构设计师':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=754;
				$catearr['category_cn']='结构工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '造价工程师':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=746;
				$catearr['category_cn']='造价/预决算';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '工程预决算':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=746;
				$catearr['category_cn']='造价/预决算';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '工程部经理':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=744;
				$catearr['category_cn']='工程项目管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '项目经理/工程监理':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=769;
				$catearr['category_cn']='房地产项目经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '房地产开发':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=770;
				$catearr['category_cn']='房地产开发/策划';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '房地产策划':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=770;
				$catearr['category_cn']='房地产开发/策划';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '房地产评估':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=776;
				$catearr['category_cn']='房地产估价师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '房地产销售':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=773;
				$catearr['category_cn']='房地产销售/置业顾问';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '案场主管':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=779;
				$catearr['category_cn']='其他房地产';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '置业顾问/售楼员':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=773;
				$catearr['category_cn']='房地产销售/置业顾问';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '智能大厦/综合布线':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=764;
				$catearr['category_cn']='智能大厦系统集成';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '测绘技术':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=767;
				$catearr['category_cn']='测绘/测量';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '市政建设/城市规划':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=757;
				$catearr['category_cn']='城镇规划设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '市政建设/城市规划':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=750;
				$catearr['category_cn']='城镇规划设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '安防工程师':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=768;
				$catearr['category_cn']='其他建筑';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '幕墙工程师':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=760;
				$catearr['category_cn']='幕墙设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '室内外装潢设计':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=763;
				$catearr['category_cn']='室内设计师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '道路桥梁隧道工程':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=756;
				$catearr['category_cn']='路桥/港口/航道';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '施工员/技术员':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=747;
				$catearr['category_cn']='施工员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '园艺/绿化':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=758;
				$catearr['category_cn']='园林/景观设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '园林/景观设计':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=758;
				$catearr['category_cn']='园林/景观设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '建筑制图/建模/测绘':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=767;
				$catearr['category_cn']='测绘/测量';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '物业管理':
				$catearr['topclass']=96;
				$catearr['category']=99;
				$catearr['subclass']=781;
				$catearr['category_cn']='物业管理员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '物业维修人员':
				$catearr['topclass']=96;
				$catearr['category']=99;
				$catearr['subclass']=781;
				$catearr['category_cn']='物业管理员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他建筑类职位':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=768;
				$catearr['category_cn']='其他建筑';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '人力资源经理/主管':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=625;
				$catearr['category_cn']='人力资源经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '行政经理/主管':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=638;
				$catearr['category_cn']='行政经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '行政专员/助理':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=639;
				$catearr['category_cn']='行政专员/助理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '人事专员/助理':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=627;
				$catearr['category_cn']='人力资源专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '招聘专员/助理':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=630;
				$catearr['category_cn']='招聘专员/助理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '办公室主任':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=640;
				$catearr['category_cn']='办公室主任';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '经理助理':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=642;
				$catearr['category_cn']='经理助理/秘书';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '高级文秘':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=642;
				$catearr['category_cn']='经理助理/秘书';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '招聘主管':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=626;
				$catearr['category_cn']='人力资源主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '薪资福利主管/专员':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=631;
				$catearr['category_cn']='薪资福利主管/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '绩效考核主管/专员':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=632;
				$catearr['category_cn']='绩效考核经理/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '员工培训与发展主管':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=633;
				$catearr['category_cn']='培训经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '培训部经理':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=633;
				$catearr['category_cn']='培训经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '培训主管/专员':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=634;
				$catearr['category_cn']='培训专员/培训师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '薪酬分析师':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=631;
				$catearr['category_cn']='薪资福利主管/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '文员/秘书':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=641;
				$catearr['category_cn']='文员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '档案管理员':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=645;
				$catearr['category_cn']='图书资料/文档管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '接待/总机/接线':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=643;
				$catearr['category_cn']='前台/接待';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电脑操作员/打字员':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=646;
				$catearr['category_cn']='电脑操作员/打字员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '人力资源信息系统专员':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=648;
				$catearr['category_cn']='其他行政/后勤';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他文职类职位':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=648;
				$catearr['category_cn']='其他行政/后勤';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '店长':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1007;
				$catearr['category_cn']='店长/卖场经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '物流部经理':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1043;
				$catearr['category_cn']='物流经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '连锁部经理':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1007;
				$catearr['category_cn']='店长/卖场经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '采购部经理':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=611;
				$catearr['category_cn']='采购经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '卖场经理':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1007;
				$catearr['category_cn']='店长/卖场经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '理货员/陈列员':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1010;
				$catearr['category_cn']='理货员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '收银员':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1011;
				$catearr['category_cn']='收银员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '导购员':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1009;
				$catearr['category_cn']='导购员/促销员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '营业员/店员':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1008;
				$catearr['category_cn']='店员/营业员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '收银主管':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1011;
				$catearr['category_cn']='收银员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '安防主管':
				$catearr['topclass']=74;
				$catearr['category']=75;
				$catearr['subclass']=698;
				$catearr['category_cn']='其他计算机应用';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '防损员/内保':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1012;
				$catearr['category_cn']='防损员/内保';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '西点师/面包糕点加工':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='其他餐饮/旅游/娱乐';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '熟食/生鲜食品加工':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='其他餐饮/旅游/娱乐';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其它零售服务类':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1014;
				$catearr['category_cn']='其他百货/零售';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '家政服务/保姆':
				$catearr['topclass']=241;
				$catearr['category']=243;
				$catearr['subclass']=1018;
				$catearr['category_cn']='家政服务/保姆';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '保安经理':
				$catearr['topclass']=241;
				$catearr['category']=243;
				$catearr['subclass']=1015;
				$catearr['category_cn']='保安/门卫';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '保镖':
				$catearr['topclass']=241;
				$catearr['category']=243;
				$catearr['subclass']=1015;
				$catearr['category_cn']='保安/门卫';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '空乘人员':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1050;
				$catearr['category_cn']='海陆空运操作员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '地勤人员':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1050;
				$catearr['category_cn']='海陆空运操作员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '列车司机':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1049;
				$catearr['category_cn']='客运/货车/班车司机';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '乘务员':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1050;
				$catearr['category_cn']='海陆空运操作员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '船员':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1050;
				$catearr['category_cn']='海陆空运操作员';
				update_data($catearr,$id);
				unset($catearr);
				break;	
			case '司机':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1048;
				$catearr['category_cn']='商务司机';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '搬运工':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1052;
				$catearr['category_cn']='速递员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '清洁工':
				$catearr['topclass']=241;
				$catearr['category']=243;
				$catearr['subclass']=1017;
				$catearr['category_cn']='保洁/清洁工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '仓库保管':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1042;
				$catearr['category_cn']='仓库管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '后勤':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=648;
				$catearr['category_cn']='其他行政/后勤';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '声讯服务':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='其他餐饮/旅游/娱乐';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他后勤类职位':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=648;
				$catearr['category_cn']='其他行政/后勤';
				update_data($catearr,$id);
				unset($catearr);
				break;

			case '物流经理':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1043;
				$catearr['category_cn']='物流经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '物流主管':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1043;
				$catearr['category_cn']='物流经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '物流专员/助理':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1044;
				$catearr['category_cn']='物流专员/助理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '国内贸易经理/主管':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=604;
				$catearr['category_cn']='国际贸易经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '外贸经理/主管':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=604;
				$catearr['category_cn']='国际贸易经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '运输经理/主管':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1043;
				$catearr['category_cn']='物流经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '业务跟单':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=607;
				$catearr['category_cn']='业务跟单';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '报关员/单证员':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=608;
				$catearr['category_cn']='报关员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '快递员/速递员':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=613;
				$catearr['category_cn']='其他贸易/采购';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '采购经理/主管':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=611;
				$catearr['category_cn']='采购经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '采购员/采购助理':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=612;
				$catearr['category_cn']='采购员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '仓库管理':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1042;
				$catearr['category_cn']='仓库管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '供应链经理':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1047;
				$catearr['category_cn']='物料管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '供应链主管/专员':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1054;
				$catearr['category_cn']='其他物流/交通/仓储';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '物料经理':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1047;
				$catearr['category_cn']='物料管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '物料主管/专员':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1047;
				$catearr['category_cn']='物料管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '仓库经理/主管':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1042;
				$catearr['category_cn']='仓库管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '仓库管理员':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1042;
				$catearr['category_cn']='仓库管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '货运代理':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1046;
				$catearr['category_cn']='货运代理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其它物流类职位':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1054;
				$catearr['category_cn']='其他物流/交通/仓储';
				update_data($catearr,$id);
				unset($catearr);
				break;

			case '财务经理':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=650;
				$catearr['category_cn']='财务经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '预算主管':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=651;
				$catearr['category_cn']='财务主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '成本经理/主管':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=656;
				$catearr['category_cn']='成本管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '账务经理/主管':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=650;
				$catearr['category_cn']='财务经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '会计主管':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=652;
				$catearr['category_cn']='会计经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '资金主管':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=652;
				$catearr['category_cn']='会计经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '投资主管':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=651;
				$catearr['category_cn']='财务主管';
				update_data($catearr,$id);
				unset($catearr);
			case '融资主管':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=651;
				$catearr['category_cn']='财务主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '财务分析师':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=655;
				$catearr['category_cn']='财务分析';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '会计':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=653;
				$catearr['category_cn']='会计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '出纳':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=654;
				$catearr['category_cn']='出纳';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '收银员':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1011;
				$catearr['category_cn']='收银员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '审计':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=658;
				$catearr['category_cn']='审计员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '统计':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=659;
				$catearr['category_cn']='统计员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '成本管理员':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=657;
				$catearr['category_cn']='成本会计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '资金专员':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=660;
				$catearr['category_cn']='其他财务/审计/税务';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '审计经理/主管':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=652;
				$catearr['category_cn']='会计经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '税务经理/主管':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=652;
				$catearr['category_cn']='会计经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '税务专员/助理':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=660;
				$catearr['category_cn']='其他财务/审计/税务';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他财务类职位':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=660;
				$catearr['category_cn']='其他财务/审计/税务';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '酒店/餐厅经理':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1028;
				$catearr['category_cn']='娱乐/餐饮经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '大堂/前厅经理':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1021;
				$catearr['category_cn']='大堂经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '楼层/店面经理':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1022;
				$catearr['category_cn']='楼面经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '领班':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1023;
				$catearr['category_cn']='前厅接待';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '服务员':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1024;
				$catearr['category_cn']='客房服务员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '厨师':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1031;
				$catearr['category_cn']='厨师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '调酒师':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1032;
				$catearr['category_cn']='调酒/茶艺';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '导游':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1025;
				$catearr['category_cn']='导游/旅行顾问';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '礼仪/迎宾/接待':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1023;
				$catearr['category_cn']='前厅接待';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '传菜主管/传菜员':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='其他餐饮/旅游/娱乐';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '营业员/收银员':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1029;
				$catearr['category_cn']='娱乐/餐饮服务员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '订票/订房服务':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1027;
				$catearr['category_cn']='订票/订房';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '清洁服务人员':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='其他餐饮/旅游/娱乐';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '茶艺师':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1032;
				$catearr['category_cn']='调酒/茶艺';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '营养师':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=989;
				$catearr['category_cn']='营养师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其它酒店类职位':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='其他餐饮/旅游/娱乐';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '教学管理人员':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=956;
				$catearr['category_cn']='教务管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '大学教授/教师':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=962;
				$catearr['category_cn']='其他教育';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '职业技术教师':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=962;
				$catearr['category_cn']='其他教育';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '中学教师':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=947;
				$catearr['category_cn']='中学教师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '小学教师':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=948;
				$catearr['category_cn']='小学教师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '幼儿教育':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=949;
				$catearr['category_cn']='幼教';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '教育培训':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=957;
				$catearr['category_cn']='培训师/讲师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '讲师/助教':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=958;
				$catearr['category_cn']='培训助理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '家教':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=955;
				$catearr['category_cn']='家教';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '兼职教师':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=962;
				$catearr['category_cn']='其他教育';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '培训督导':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=958;
				$catearr['category_cn']='培训助理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '培训讲师':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=957;
				$catearr['category_cn']='培训师/讲师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '培训策划':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=960;
				$catearr['category_cn']='培训策划';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '培训助理':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=958;
				$catearr['category_cn']='培训助理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他教育类职位':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=962;
				$catearr['category_cn']='其他教育';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '艺术/设计总监':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=912;
				$catearr['category_cn']='导演/编导/艺术总监';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '总编/副总编':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=936;
				$catearr['category_cn']='总编/副总编/主编';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '导演':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=912;
				$catearr['category_cn']='导演/编导/艺术总监';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '制片人':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=915;
				$catearr['category_cn']='经纪人';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '播音员':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=914;
				$catearr['category_cn']='主持人/播音员/DJ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '演员':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=913;
				$catearr['category_cn']='演员/模特';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '节目主持人':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=914;
				$catearr['category_cn']='主持人/播音员/DJ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '模特':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=913;
				$catearr['category_cn']='演员/模特';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '媒体形象包装':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=929;
				$catearr['category_cn']='包装设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '化妆师':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1035;
				$catearr['category_cn']='化妆师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '影视灯光':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=918;
				$catearr['category_cn']='舞台设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '影视音像工程':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=927;
				$catearr['category_cn']='多媒体设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '影视舞美':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=911;
				$catearr['category_cn']='影视策划/制作人员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '剧务':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=920;
				$catearr['category_cn']='其他影视/媒体';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电视工程技术':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=920;
				$catearr['category_cn']='其他影视/媒体';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '文字记者':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=938;
				$catearr['category_cn']='记者';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '摄影记者':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=938;
				$catearr['category_cn']='记者';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '文字编辑':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=937;
				$catearr['category_cn']='编辑/作家';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '美术编辑':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=937;
				$catearr['category_cn']='编辑/作家';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '技术编辑':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=937;
				$catearr['category_cn']='编辑/作家';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '影视策划/制作人员':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=911;
				$catearr['category_cn']='影视策划/制作人员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '作家/撰稿人':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=937;
				$catearr['category_cn']='编辑/作家';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '摄影师/摄像师':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=916;
				$catearr['category_cn']='摄影/摄像';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '后期制作':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=919;
				$catearr['category_cn']='后期制作';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '排版设计':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=940;
				$catearr['category_cn']='排版设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '校对/录入':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=941;
				$catearr['category_cn']='校对/录入';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '出版/发行':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=943;
				$catearr['category_cn']='出版/印刷/发行';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他媒体类职位':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=920;
				$catearr['category_cn']='其他影视/媒体';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '英语':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=973;
				$catearr['category_cn']='英语翻译';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '日语':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=974;
				$catearr['category_cn']='日语翻译';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '德语':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=978;
				$catearr['category_cn']='德语翻译';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '法语':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=976;
				$catearr['category_cn']='法语翻译';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '俄语':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=977;
				$catearr['category_cn']='俄语翻译';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '朝鲜语':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=983;
				$catearr['category_cn']='其他翻译';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '阿拉伯语':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=981;
				$catearr['category_cn']='阿拉伯语翻译';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '西班牙语':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=980;
				$catearr['category_cn']='西班牙语翻译';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其它语种翻译':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=983;
				$catearr['category_cn']='其他翻译';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=839;
				$catearr['category_cn']='电工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '水工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=847;
				$catearr['category_cn']='水工/木工/油漆工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '木工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=847;
				$catearr['category_cn']='水工/木工/油漆工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '钳工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=842;
				$catearr['category_cn']='钳工/机修工/钣金工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电焊工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=841;
				$catearr['category_cn']='电焊/铆焊工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '油漆工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=847;
				$catearr['category_cn']='水工/木工/油漆工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '锅炉工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=840;
				$catearr['category_cn']='空调/电梯/锅炉工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '叉车工/车工/磨工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=845;
				$catearr['category_cn']='装卸/叉车工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '铣工/冲压工/锣工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=843;
				$catearr['category_cn']='车/磨/铣/冲/镗工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '切割技工/钣金工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=844;
				$catearr['category_cn']='切割技工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '模具工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=851;
				$catearr['category_cn']='模具工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '空调工/电梯工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=840;
				$catearr['category_cn']='空调/电梯/锅炉工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '汽车修理工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=848;
				$catearr['category_cn']='汽车修理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '机修工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=853;
				$catearr['category_cn']='其他技术工人';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '普工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=852;
				$catearr['category_cn']='普工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他技工类职位':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=853;
				$catearr['category_cn']='普工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '制革':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=858;
				$catearr['category_cn']='皮革/毛皮加工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '制衣':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=855;
				$catearr['category_cn']='纺纱/织造/针织';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '制鞋':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=857;
				$catearr['category_cn']='鞋帽制作';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '印刷':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=860;
				$catearr['category_cn']='印染';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '染整技术':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=860;
				$catearr['category_cn']='印染';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '服装纺织':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=855;
				$catearr['category_cn']='纺纱/织造/针织';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '食品工程':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=902;
				$catearr['category_cn']='食品/饮料研发';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '陶瓷技术':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=904;
				$catearr['category_cn']='其他化工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '纸浆造纸工艺':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=904;
				$catearr['category_cn']='其他化工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '金银首饰加工':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=904;
				$catearr['category_cn']='其他化工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '架构师':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=899;
				$catearr['category_cn']='实验室研究员/技术员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '面料辅料开发':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=856;
				$catearr['category_cn']='服装打样/制版';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '面料辅料采购':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=862;
				$catearr['category_cn']='其他服装/纺织品';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '板房/楦头':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=753;
				$catearr['category_cn']='土木土建';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '打样/制版':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=751;
				$catearr['category_cn']='建筑师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '电脑放码员':
				$catearr['topclass']=74;
				$catearr['category']=75;
				$catearr['subclass']=695;
				$catearr['category_cn']='电脑操作员/打字员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '纸样师/车板工':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=850;
				$catearr['category_cn']='裁剪车缝熨烫';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '裁床':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=850;
				$catearr['category_cn']='裁剪车缝熨烫';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他轻工类职位':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=853;
				$catearr['category_cn']='其他技术工人';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '公务员':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1056;
				$catearr['category_cn']='社会服务';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '大学应届毕业生':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1056;
				$catearr['category_cn']='社会服务';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '中专/职校生':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1056;
				$catearr['category_cn']='社会服务';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '研究生':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1057;
				$catearr['category_cn']='科学研究人员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '储备干部':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1056;
				$catearr['category_cn']='社会服务';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '实习生':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1055;
				$catearr['category_cn']='实习生';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '培训生':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1056;
				$catearr['category_cn']='社会服务';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '涂料技术':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=904;
				$catearr['category_cn']='其他化工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '声光技术':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=892;
				$catearr['category_cn']='光源与照明工程';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '激光技术':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=877;
				$catearr['category_cn']='激光/光电子技术';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '地质勘探':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=896;
				$catearr['category_cn']='地质勘查/开采';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '农林牧渔业':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1059;
				$catearr['category_cn']='禽畜养殖';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '环保工程师':
				$catearr['topclass']=225;
				$catearr['category']=228;
				$catearr['subclass']=999;
				$catearr['category_cn']='环保工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '污水处理工程师':
				$catearr['topclass']=225;
				$catearr['category']=228;
				$catearr['subclass']=1002;
				$catearr['category_cn']='水处理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '驯兽师':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1061;
				$catearr['category_cn']='兽医/宠物医生';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '农林牧渔业场长':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1062;
				$catearr['category_cn']='园林园艺';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '农艺师':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1058;
				$catearr['category_cn']='农艺师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '畜牧师':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1060;
				$catearr['category_cn']='动物营养/饲料研发';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '饲养员':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1059;
				$catearr['category_cn']='禽畜养殖';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '咨询总监/经理':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=969;
				$catearr['category_cn']='咨询经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '咨询/顾问':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=968;
				$catearr['category_cn']='专业顾问';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '咨询专员/助理':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=970;
				$catearr['category_cn']='咨询员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '涉外咨询':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=972;
				$catearr['category_cn']='其他咨询';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '信息咨询/中介服务':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=972;
				$catearr['category_cn']='其他咨询';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '律师':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=963;
				$catearr['category_cn']='律师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '律师助理':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=963;
				$catearr['category_cn']='律师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '法务人员':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=965;
				$catearr['category_cn']='法务人员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '专业培训师':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=957;
				$catearr['category_cn']='培训师/讲师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '企业管理顾问':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=968;
				$catearr['category_cn']='专业顾问';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '猎头顾问':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=968;
				$catearr['category_cn']='专业顾问';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '法律顾问':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=964;
				$catearr['category_cn']='法律顾问';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '法务经理':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=965;
				$catearr['category_cn']='法务人员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '合规经理':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=967;
				$catearr['category_cn']='其他法律';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '合规专员':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=967;
				$catearr['category_cn']='其他法律';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '知识产权/专利顾问':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=966;
				$catearr['category_cn']='知识产权/专利顾问';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他咨询类职位':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=972;
				$catearr['category_cn']='其他咨询';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '证券期货':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=661;
				$catearr['category_cn']='证券/期货/外汇经纪人';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '银行信贷管理':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=672;
				$catearr['category_cn']='信贷管理/资信评估';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '证券分析师':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=662;
				$catearr['category_cn']='证券分析师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '保险代理/经纪人':
				$catearr['topclass']=49;
				$catearr['category']=52;
				$catearr['subclass']=683;
				$catearr['category_cn']='保险代理人/经纪人';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '保险业务经理':
				$catearr['topclass']=49;
				$catearr['category']=52;
				$catearr['subclass']=677;
				$catearr['category_cn']='保险业务经理/主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '客户经理/经纪人':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=665;
				$catearr['category_cn']='客户经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '资金管理/财务管理':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=651;
				$catearr['category_cn']='财务主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '资产评估':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=673;
				$catearr['category_cn']='资产评估/分析';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '柜员/银行会计':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=674;
				$catearr['category_cn']='银行柜员/会计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '股票/期货操盘手':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=663;
				$catearr['category_cn']='股票/期货操盘手';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '金融/经济研究员':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=676;
				$catearr['category_cn']='其他证券/期货/投资/银行';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '投资/基金项目经理':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=667;
				$catearr['category_cn']='投资/基金项目经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '投资/理财顾问':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=666;
				$catearr['category_cn']='投资/理财顾问';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '融资经理/融资专员':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=669;
				$catearr['category_cn']='融资经理/专员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '国际结算/外汇交易':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=664;
				$catearr['category_cn']='外汇/基金/国债经理人';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '拍卖师':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=670;
				$catearr['category_cn']='拍卖/典当/租赁/担保';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他金融类职位':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=676;
				$catearr['category_cn']='其他证券/期货/投资/银行';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '医生':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='医生/医师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '医师':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='医生/医师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '医药检验':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=992;
				$catearr['category_cn']='其他医院/医疗';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '心理医生':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=987;
				$catearr['category_cn']='心理医生';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '针灸推拿':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=985;
				$catearr['category_cn']='医疗技术人员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '临床医学':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='医生/医师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '中医':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='医生/医师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '西医':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='医生/医师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '中西医':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='医生/医师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '护士长/护士':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=986;
				$catearr['category_cn']='护士/护理人员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '护理主任/护理人员':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=986;
				$catearr['category_cn']='护士/护理人员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '营养师':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=989;
				$catearr['category_cn']='营养师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '美容化妆师/发型师':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1034;
				$catearr['category_cn']='美容师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '药剂师':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=991;
				$catearr['category_cn']='药库主任/药剂师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '医药代表':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=996;
				$catearr['category_cn']='医药代表';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '妇幼保健':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=986;
				$catearr['category_cn']='护士/护理人员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '美容整形师':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1034;
				$catearr['category_cn']='美容师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '理疗师':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=985;
				$catearr['category_cn']='医疗技术人员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '彩妆培训师':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=985;
				$catearr['category_cn']='化妆师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '按摩/足疗':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1041;
				$catearr['category_cn']='按摩/足疗';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '健身顾问/教练':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1038;
				$catearr['category_cn']='健身顾问/教练';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '瑜伽/舞蹈老师':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1039;
				$catearr['category_cn']='舞蹈教师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '兽医/宠物医生':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=990;
				$catearr['category_cn']='兽医/宠物医生';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他医疗类职位':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=992;
				$catearr['category_cn']='其他医院/医疗';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '日用化工':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=898;
				$catearr['category_cn']='化工技术';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '生物化工':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=898;
				$catearr['category_cn']='化工技术';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '生物制药':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=994;
				$catearr['category_cn']='生物技术制药';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '塑料工程师':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=901;
				$catearr['category_cn']='橡胶/塑料';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '涂料研发工程师':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=899;
				$catearr['category_cn']='实验室研究员/技术员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '化学药品':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=993;
				$catearr['category_cn']='药品生产/质管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '化工工程师':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=898;
				$catearr['category_cn']='化工技术';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '化学分析测试员':
			case '化妆品研发人员':
			case '化工实验室研究员':
			case '医药技术研发人员':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=899;
				$catearr['category_cn']='实验室研究员/技术员';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '配色技术员':
			case '有机化工':
			case '无机化工':
			case '精细化工':
			case '分析化工':
			case '高分子化工':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=898;
				$catearr['category_cn']='化工技术';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '环境/环保技术':
				$catearr['topclass']=225;
				$catearr['category']=228;
				$catearr['subclass']=999;
				$catearr['category_cn']='环保工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '环境/环保技术':
				$catearr['topclass']=225;
				$catearr['category']=228;
				$catearr['subclass']=999;
				$catearr['category_cn']='环保工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '药品生产/质量管理':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=993;
				$catearr['category_cn']='药品生产/质管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '生物工程':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=994;
				$catearr['category_cn']='生物技术制药';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '临床试验/药品注册':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=993;
				$catearr['category_cn']='药品生产/质管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '医药招商':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=996;
				$catearr['category_cn']='医药代表';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '食品/饮料研发':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=902;
				$catearr['category_cn']='食品/饮料研发';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '其他化工类职位':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=904;
				$catearr['category_cn']='其他化工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '厂长/副厂长':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=788;
				$catearr['category_cn']='厂长/副厂长';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '总工程师/副总工程师':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=789;
				$catearr['category_cn']='总工/副总工';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '生产管理/车间主任':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=790;
				$catearr['category_cn']='车间主任/生产经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '产品工艺设计':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=800;
				$catearr['category_cn']='工业设计/产品设计';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '安全管理人员':
				$catearr['topclass']=116;
				$catearr['category']=118;
				$catearr['subclass']=812;
				$catearr['category_cn']='安全管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '工程项目经理/主管':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=791;
				$catearr['category_cn']='班组长/生产主管';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '品质/质量管理(QA·QC)':
				$catearr['topclass']=116;
				$catearr['category']=118;
				$catearr['subclass']=805;
				$catearr['category_cn']='品质保证/质量控制经理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '物流管理':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=796;
				$catearr['category_cn']='物流管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '物料管理':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1047;
				$catearr['category_cn']='物料管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '设备管理':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=794;
				$catearr['category_cn']='工程/设备管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '采购管理':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=797;
				$catearr['category_cn']='采购管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '仓库管理':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=795;
				$catearr['category_cn']='仓库管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '计划员':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=792;
				$catearr['category_cn']='生产计划';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '调度员':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=793;
				$catearr['category_cn']='生产调度';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '化验员':
			case '检验员':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=803;
				$catearr['category_cn']='化验/检验';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '工艺工程师':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=799;
				$catearr['category_cn']='产品/工艺工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '工业工程师（IE）':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=801;
				$catearr['category_cn']='工业工程师';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '故障分析工程师':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=802;
				$catearr['category_cn']='检修维护';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '跟单员':
			case '统计员':
			case '制造工程师':
			case '生产文员':
			case '其他工厂类职位':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=802;
				$catearr['category_cn']='其他生产制造管理';
				update_data($catearr,$id);
				unset($catearr);
				break;
			default:
				$return=search_str($new_category_jobs,$name,"categoryname");
				if ($return)
				{
					if($return['parentid']==1||$return['parentid']==19||$return['parentid']==49||$return['parentid']==74||$return['parentid']==96||$return['parentid']==116||$return['parentid']==136||$return['parentid']==169||$return['parentid']==203||$return['parentid']==225||$return['parentid']==241||$return['parentid']==258){
						$catearr['topclass']=$return['parentid'];
						$catearr['category']=$return['id'];
						$catearr['subclass']=0;
						$catearr['category_cn']=$return['categoryname'];
						update_data($catearr,$id);
						unset($catearr);
					}else{
						$topclass=$db->getone("select parentid from ".table('category_jobs')." where id=".$return['parentid']);
						$catearr['topclass']=$topclass['parentid'];
						$catearr['category']=$return['parentid'];
						$catearr['subclass']=$return['id'];
						$catearr['category_cn']=$return['categoryname'];
						update_data($catearr,$id);
						unset($catearr);
					}
				}
				break;
		}
	}
	function update_data($catearr,$id){
		update_updatetable(table('jobs'),$catearr,array('subclass'=>$id));
		update_updatetable(table('jobs_tmp'),$catearr,array('subclass'=>$id));
		$resume_catearr['topclass']=$catearr['topclass'];
		$resume_catearr['category']=$catearr['category'];
		$resume_catearr['subclass']=$catearr['subclass'];
		update_updatetable(table('jobs_search_hot'),$resume_catearr,array('subclass'=>$id));
		update_updatetable(table('jobs_search_key'),$resume_catearr,array('subclass'=>$id));
		update_updatetable(table('jobs_search_rtime'),$resume_catearr,array('subclass'=>$id));
		update_updatetable(table('jobs_search_scale'),$resume_catearr,array('subclass'=>$id));
		update_updatetable(table('jobs_search_stickrtime'),$resume_catearr,array('subclass'=>$id));
		update_updatetable(table('jobs_search_tag'),$resume_catearr,array('subclass'=>$id));
		update_updatetable(table('jobs_search_wage'),$resume_catearr,array('subclass'=>$id));
		update_updatetable(table('resume_jobs'),$resume_catearr,array('subclass'=>$id));
		unset($resume_catearr);
	}
	//模糊搜索
	function search_str($arr,$str,$arrinname)
	{
			foreach ($arr as $key =>$list)
			{
				similar_text($list[$arrinname],$str,$percent);
				$od[$percent]=$key;
			}
				krsort($od);
				foreach ($od as $key =>$li)
				{
					if ($key>=50)
					{
					return $arr[$li];
					}
					else
					{
					return false;
					}
				}	
	}
$step=isset($_GET['step'])?$_GET['step']:1;
if ($_GET['act']=="upit")
{
	if ($version_old<>QISHI_VERSION )
	{
	exit("本程序仅用于升级 74cms v".$version_old."到74cms v".$version_new);
	}
	$Field=$db->getone("SHOW COLUMNS FROM ".table('jobs')." WHERE Field = 'topclass' ");
	if (!empty($Field))
	{
	exit("您的数据库已经执行过升级了，请先将数据库恢复到 {$version_old} 版本，然后再运行本程序!");
	}
	if (empty($_POST['key']))
	{
	exit("<script language=javascript>alert('请填写授权码！');window.location='index.php?step=3'</script>");
	}
$sql=updataopen("http://www.74cms.com/updata/3.4_3.5.php?key={$_POST['key']}&domain={$_SERVER['SERVER_NAME']}");
if (empty($sql))
{
exit("<script language=javascript>alert('获取升级数据失败，请联系骑士客服协助升级！');window.location='index.php?step=3'</script>");
}
else
{
	if ($sql=="err_1")
	{
	exit("<script language=javascript>alert('授权码错误！');window.location='index.php?step=3'</script>");
	}
	elseif ($sql=="err_2")
	{
	exit("<script language=javascript>alert('此版本的升级已经超过1次');window.location='index.php?step=3'</script>");
	}
	elseif ($sql=="err_3")
	{
	exit("<script language=javascript>alert('域名(".$_SERVER['SERVER_NAME'].")未授权！');window.location='index.php?step=3'</script>");
	}
	else
	{
		runquery($sql);
		$Field=$db->getone("SHOW COLUMNS FROM ".table('setmeal')." WHERE Field = 'refresh_jobs_space' ");
		if (empty($Field))
		{
		$db->query("ALTER TABLE ".table('setmeal')." ADD `refresh_jobs_space` int(10) unsigned NOT NULL DEFAULT '0'");
		$db->query("ALTER TABLE ".table('setmeal')." ADD `refresh_jobs_time` int(10) unsigned NOT NULL DEFAULT '0'");
		}
		$Field=$db->getone("SHOW COLUMNS FROM ".table('members_setmeal')." WHERE Field = 'refresh_jobs_space' ");
		if (empty($Field))
		{
		$db->query("ALTER TABLE ".table('members_setmeal')." ADD `refresh_jobs_space` int(10) unsigned NOT NULL DEFAULT '0'");
		$db->query("ALTER TABLE ".table('members_setmeal')." ADD `refresh_jobs_time` int(10) unsigned NOT NULL DEFAULT '0'");
		}
		$Field=$db->getone("SHOW COLUMNS FROM ".table('train_setmeal')." WHERE Field = 'refresh_course_space' ");
		if (empty($Field))
		{
		$db->query("ALTER TABLE ".table('train_setmeal')." ADD `refresh_course_space` int(10) unsigned NOT NULL DEFAULT '0'");
		$db->query("ALTER TABLE ".table('train_setmeal')." ADD `refresh_course_time` int(10) unsigned NOT NULL DEFAULT '0'");
		}
		$Field=$db->getone("SHOW COLUMNS FROM ".table('members_train_setmeal')." WHERE Field = 'refresh_course_space' ");
		if (empty($Field))
		{
		$db->query("ALTER TABLE ".table('members_train_setmeal')." ADD `refresh_course_space` int(10) unsigned NOT NULL DEFAULT '0'");
		$db->query("ALTER TABLE ".table('members_train_setmeal')." ADD `refresh_course_time` int(10) unsigned NOT NULL DEFAULT '0'");
		}
		$Field=$db->getone("SHOW COLUMNS FROM ".table('hunter_setmeal')." WHERE Field = 'hunter_refresh_jobs_space' ");
		if (empty($Field))
		{
		$db->query("ALTER TABLE ".table('hunter_setmeal')." ADD `hunter_refresh_jobs_space` int(10) unsigned NOT NULL DEFAULT '0'");
		$db->query("ALTER TABLE ".table('hunter_setmeal')." ADD `hunter_refresh_jobs_time` int(10) unsigned NOT NULL DEFAULT '0'");
		}
		$Field=$db->getone("SHOW COLUMNS FROM ".table('members_hunter_setmeal')." WHERE Field = 'hunter_refresh_jobs_space' ");
		if (empty($Field))
		{
		$db->query("ALTER TABLE ".table('members_hunter_setmeal')." ADD `hunter_refresh_jobs_space` int(10) unsigned NOT NULL DEFAULT '0'");
		$db->query("ALTER TABLE ".table('members_hunter_setmeal')." ADD `hunter_refresh_jobs_time` int(10) unsigned NOT NULL DEFAULT '0'");
		}

		$Field=$db->getone("SHOW COLUMNS FROM ".table('setmeal')." WHERE Field = 'refresh_senior_jobs_space' ");
		if (!empty($Field))
		{
		$db->query("ALTER TABLE ".table('setmeal')." DROP `refresh_senior_jobs_space`");
		$db->query("ALTER TABLE ".table('setmeal')." DROP `refresh_senior_jobs_time`");
		}
		$Field=$db->getone("SHOW COLUMNS FROM ".table('members_setmeal')." WHERE Field = 'refresh_senior_jobs_space' ");
		if (!empty($Field))
		{
		$db->query("ALTER TABLE ".table('members_setmeal')." DROP `refresh_senior_jobs_space`");
		$db->query("ALTER TABLE ".table('members_setmeal')." DROP `refresh_senior_jobs_time`");
		}
		$weixin_token = $db->getone("select `value` from ".table('config')." where `name`='weixin_appsecret'");
		update_updatetable(table('config'),array('value'=>$weixin_token['value']),array('name'=>'weixin_apptoken'));
		update_updatetable(table('config'),array('value'=>''),array('name'=>'weixin_appsecret'));

		$sms_name = $db->getone("select `value` from ".table('sms_config')." where `name`='captcha_sms_name'");
		$sms_key = $db->getone("select `value` from ".table('sms_config')." where `name`='captcha_sms_key'");
		update_updatetable(table('sms_config'),array('value'=>$sms_name['value']),array('name'=>'notice_sms_name'));
		update_updatetable(table('sms_config'),array('value'=>$sms_name['value']),array('name'=>'free_sms_name'));
		update_updatetable(table('sms_config'),array('value'=>$sms_key['value']),array('name'=>'notice_sms_key'));
		update_updatetable(table('sms_config'),array('value'=>$sms_key['value']),array('name'=>'free_sms_key'));

		$resume_tmp = $db->getall("select * from ".table('resume_tmp'));
		foreach ($resume_tmp as $key => $value) {
			$value['audit'] = "3";
			$value['id'] = NULL;
			$pid = update_inserttable(table("resume"),$value,1);
			$j = $value;
			$searchtab['id']=$pid;
			$searchtab['sex']=$j['sex'];
			$searchtab['nature']=$j['nature'];
			$searchtab['marriage']=$j['marriage'];
			$searchtab['experience']=$j['experience'];
			$searchtab['district']=$j['district'];
			$searchtab['sdistrict']=$j['sdistrict'];
			$searchtab['wage']=$j['wage'];
			$searchtab['education']=$j['education'];
			$searchtab['photo']=$j['photo'];
			$searchtab['refreshtime']=$j['refreshtime'];
			$searchtab['talent']=$j['talent'];
			$searchtab['subsite_id']=$j['subsite_id'];
			$searchtab['audit']=$j['audit'];
			update_inserttable(table('resume_search_rtime'),$searchtab,0);
			$searchtab['key']=$j['key'];
			$searchtab['likekey']=$j['intention_jobs'].','.$j['trade_cn'].','.$j['specialty'].','.$j['fullname'];
			update_inserttable(table('resume_search_key'),$searchtab,0);
			unset($searchtab);
			if($j['tag']){
				$tag=explode('|',$j['tag']);
				$tagindex=1;
				$tagsql['tag1']=$tagsql['tag2']=$tagsql['tag3']=$tagsql['tag4']=$tagsql['tag5']=0;
				if (!empty($tag) && is_array($tag))
				{
						foreach($tag as $v)
						{
							$vid=explode(',',$v);
							$tagsql['tag'.$tagindex]=intval($vid[0]);
							$tagindex++;
						}
				}
				$tagsql['id']=$pid;
				$tagsql['uid']=$j['uid'];
				$tagsql['subsite_id']=$j['subsite_id'];
				$tagsql['experience']=$j['experience'];
				$tagsql['district']=$j['district'];
				$tagsql['sdistrict']=$j['sdistrict'];
				$tagsql['education']=$j['education'];
				$tagsql['audit']=$j['audit'];
				update_inserttable(table('resume_search_tag'),$tagsql,0);
			}
		}
		$db->query("DROP TABLE ".table("resume_tmp"));
		$manager_resume = $db->getall("select * from ".table('manager_resume'));
		foreach ($manager_resume as $key => $value) {
			$setsqlarr['audit'] = "3";
			$setsqlarr['subsite_id'] = $value['subsite_id'];
			$setsqlarr['uid'] = $value['uid'];
			$setsqlarr['display'] = $value['display'];
			$setsqlarr['display_name'] = $value['display_name'];
			$setsqlarr['title'] = $value['title'];
			$setsqlarr['fullname'] = $value['fullname'];
			$setsqlarr['sex'] = $value['sex'];
			$setsqlarr['sex_cn'] = $value['sex_cn'];
			$setsqlarr['trade'] = $value['trade'];
			$setsqlarr['trade_cn'] = $value['trade_cn'];
			$setsqlarr['birthdate'] = $value['birthdate'];
			$setsqlarr['height'] = $value['height'];
			$setsqlarr['marriage'] = $value['marriage'];
			$setsqlarr['marriage_cn'] = $value['marriage_cn'];
			$setsqlarr['experience'] = $value['experience'];
			$setsqlarr['experience_cn'] = $value['experience_cn'];
			$setsqlarr['district'] = $value['district'];
			$setsqlarr['sdistrict'] = $value['sdistrict'];
			$setsqlarr['district_cn'] = $value['district_cn'];
			$setsqlarr['wage'] = $value['wage'];
			$setsqlarr['wage_cn'] = $value['wage_cn'];
			$setsqlarr['education'] = $value['education'];
			$setsqlarr['education_cn'] = $value['education_cn'];
			$setsqlarr['tag'] = $value['tag'];
			$setsqlarr['telephone'] = $value['telephone'];
			$setsqlarr['email'] = $value['email'];
			$setsqlarr['email_notify'] = $value['email_notify'];
			$setsqlarr['intention_jobs'] = $value['intention_jobs'];
			$setsqlarr['specialty'] = $value['specialty'];
			$setsqlarr['photo'] = $value['photo'];
			$setsqlarr['photo_img'] = $value['photo_img'];
			$setsqlarr['photo_audit'] = $value['photo_audit'];
			$setsqlarr['photo_display'] = $value['photo_display'];
			$setsqlarr['addtime'] = $value['addtime'];
			$setsqlarr['refreshtime'] = $value['refreshtime'];
			$setsqlarr['talent'] = 2;
			$setsqlarr['level'] = 1;
			$setsqlarr['complete_percent'] = $value['complete_percent'];
			$setsqlarr['key'] = $value['key'];
			$setsqlarr['click'] = $value['click'];
			$setsqlarr['tpl'] = $value['tpl'];
			$pid = update_inserttable(table("resume"),$setsqlarr,1);
			$j = $value;
			$searchtab['id']=$pid;
			$searchtab['sex']=$j['sex'];
			$searchtab['nature']=62;
			$searchtab['marriage']=$j['marriage'];
			$searchtab['experience']=$j['experience'];
			$searchtab['district']=$j['district'];
			$searchtab['sdistrict']=$j['sdistrict'];
			$searchtab['wage']=$j['wage'];
			$searchtab['education']=$j['education'];
			$searchtab['photo']=$j['photo'];
			$searchtab['refreshtime']=$j['refreshtime'];
			$searchtab['talent']=2;
			$searchtab['subsite_id']=$j['subsite_id'];
			$searchtab['audit']=3;
			update_inserttable(table('resume_search_rtime'),$searchtab,0);
			$searchtab['key']=$j['key'];
			$searchtab['likekey']=$j['intention_jobs'].','.$j['trade_cn'].','.$j['specialty'].','.$j['fullname'];
			update_inserttable(table('resume_search_key'),$searchtab,0);
			unset($searchtab);
			if($j['tag']){
				$tag=explode('|',$j['tag']);
				$tagindex=1;
				$tagsql['tag1']=$tagsql['tag2']=$tagsql['tag3']=$tagsql['tag4']=$tagsql['tag5']=0;
				if (!empty($tag) && is_array($tag))
				{
						foreach($tag as $v)
						{
							$vid=explode(',',$v);
							$tagsql['tag'.$tagindex]=intval($vid[0]);
							$tagindex++;
						}
				}
				$tagsql['id']=$pid;
				$tagsql['uid']=$j['uid'];
				$tagsql['subsite_id']=$j['subsite_id'];
				$tagsql['experience']=$j['experience'];
				$tagsql['district']=$j['district'];
				$tagsql['sdistrict']=$j['sdistrict'];
				$tagsql['education']=$j['education'];
				$tagsql['audit']=$j['audit'];
				update_inserttable(table('resume_search_tag'),$tagsql,0);
			}
		}
		$db->query("DROP TABLE ".table("manager_resume"));
		$db->query("DROP TABLE ".table("manager_resume_education"));
		$db->query("DROP TABLE ".table("manager_resume_jobs"));
		$db->query("DROP TABLE ".table("manager_resume_project"));
		$db->query("DROP TABLE ".table("manager_resume_work"));

		// 职位分类
		$db->query("alter table ".table("category_jobs")." rename to ".table('category_jobs_bak'));
$sql_str = <<<EOT
CREATE TABLE qs_category_jobs (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) unsigned NOT NULL,
  `categoryname` varchar(80) NOT NULL,
  `category_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `stat_jobs` varchar(15) NOT NULL,
  `stat_resume` varchar(15) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`)
) TYPE=MyISAM;
EOT;
runquery($sql_str);
		$sql_cate="INSERT INTO ".table('category_jobs')." (`id`, `parentid`, `categoryname`, `category_order`, `stat_jobs`, `stat_resume`, `content`) VALUES
(1, 0, '销售|市场|客服|贸易', 0, '', '', ''),
(2, 1, '销售管理', 0, '', '', ''),
(3, 1, '销售人员', 0, '', '', ''),
(4, 1, '销售行政商务', 0, '', '', ''),
(5, 1, '市场/策划/公关', 0, '', '', ''),
(6, 1, '客服/技术支持', 0, '', '', ''),
(7, 1, '贸易/采购', 0, '', '', ''),
(638, 22, '行政经理/主管', 0, '', '', '岗位职责：\r\n1、公司人工成本、行政费用的预算与管理；\r\n2、制度流程建设、各类会议活动组织、办公环境管理、公司各类采购统筹、固定资产及车辆管理、公司仓储及礼品库管理；\r\n3、行政管理体系搭建、办公区域租赁装修、行政采购体系搭建维护、大型年会及重要活动组织；\r\n4、采购、管理公司固定资产与低值易耗品（非办公用品和日常用品）；\r\n5、负责固定资产台账审核、组织固定资产盘点工作。\r\n\r\n岗位要求：\r\n1、本科以上学历，企业行政经理工作经验两年以上；\r\n2、固定资产管理经验、资产管理合同管理、或有大型企业材料库管经验；\r\n3、有一定财务经验；\r\n4、较强的责任心和敬业精神，良好的组织协调能力及沟通能力，较强的分析、解决问题能力；\r\n5、熟练使用办公软件和办公自动化设备。'),
(637, 22, '行政总监', 0, '', '', '岗位职责：\r\n1、协助决策层制定公司发展战略，负责其功能领域内短期及长期的公司决策和战略，对公司中长期目标的达成产生重要影响；\r\n2、根据公司要求，制定公司行政管理的方针、政策和制度；\r\n3、负责公司日常行政的管理和信息化的建设；\r\n4、建设企业文化建设和推广；\r\n5、负责公司固定资产的管理，保障各级公司资产的管理制度化、程序化；\r\n6、负责公司范围内物业、后勤管理。'),
(636, 21, '其他人力资源', 0, '', '', ''),
(635, 21, '企业文化/员工关系', 0, '', '', ''),
(634, 21, '培训专员/培训师', 0, '', '', ''),
(633, 21, '培训经理/主管', 0, '', '', ''),
(19, 0, '经营管理|人力资源|行政', 0, '', '', ''),
(20, 19, '经营管理', 0, '', '', ''),
(21, 19, '人力资源', 0, '', '', ''),
(22, 19, '行政/后勤', 0, '', '', ''),
(632, 21, '绩效考核经理/专员', 0, '', '', ''),
(631, 21, '薪资福利主管/专员', 0, '', '', ''),
(630, 21, '招聘专员/助理', 0, '', '', ''),
(629, 21, '招聘经理/主管', 0, '', '', ''),
(628, 21, '人力资源助理', 0, '', '', ''),
(627, 21, '人力资源专员', 0, '', '', ''),
(626, 21, '人力资源主管', 0, '', '', ''),
(625, 21, '人力资源经理', 0, '', '', ''),
(624, 21, '人力资源总监', 0, '', '', ''),
(623, 20, '其他经营管理', 0, '', '', ''),
(622, 20, '企业策划人员', 0, '', '', ''),
(621, 20, '项目经理', 0, '', '', ''),
(620, 20, '部门经理', 0, '', '', ''),
(619, 20, '运营主管', 0, '', '', ''),
(618, 20, '办事处/分公司经理', 0, '', '', ''),
(617, 20, '总监', 0, '', '', ''),
(616, 20, '总裁助理/总经理助理', 0, '', '', ''),
(615, 20, '副总经理/副总裁', 0, '', '', ''),
(614, 20, 'CEO/总裁/总经理', 0, '', '', ''),
(49, 0, '财务|金融|保险', 0, '', '', ''),
(50, 49, '财务/审计/税务', 0, '', '', ''),
(51, 49, '证券/期货/投资/银行', 0, '', '', ''),
(52, 49, '保险', 0, '', '', ''),
(665, 51, '客户经理', 0, '', '', ''),
(664, 51, '外汇/基金/国债经理人', 0, '', '', ''),
(663, 51, '股票/期货操盘手', 0, '', '', ''),
(662, 51, '证券分析师', 0, '', '', ''),
(661, 51, '证券/期货/外汇经纪人', 0, '', '', ''),
(660, 50, '其他财务/审计/税务', 0, '', '', NULL),
(659, 50, '统计员', 0, '', '', '岗位职责：\r\n1、分析公司财务状况，研究行业内公司信息，对筹融资策略进行财务分析和财经政策跟踪；\r\n2、分析评估各项业务和各部门业绩，提供财务建议和决策支持；\r\n3、预测并监督公司现金流和各项资金使用情况；\r\n4、参与投资和融资项目的财务测算、成本分析、敏感性分析，配合制定投资和融资方案；\r\n5、撰写财务分析报告、投资财务调研报告、可行性研究报告；\r\n6、协调公司和部门的其他工作。\r\n\r\n岗位要求：\r\n1、本科以上学历，金融、财经类相关专业；\r\n2、两年以上本岗位从业经验，从业经历具有较好的稳定性；\r\n3、有较强的团队协作、组织协调能力以及学习能力，较好的英文沟通交流能力，同时具备严谨的时间管理和成本管理；\r\n4、工作认真负责，热情主动，责任心强，具备良好的职业化素养和气质，优秀的语言和文字表达能力；\r\n5、受过项目管理、管理学、金融市场分析、财务分析、产品知识等方面的培训。'),
(658, 50, '审计员', 0, '', '', '岗位职责：\r\n1、编制税收报表，草拟税务成本预测和分析报告，申报纳税；\r\n2、负责税务登记证的办理、年检及注销，税务资料的整理和保管；\r\n3、税务审核、税务稽查工作，以及年度各项税种的纳税申报工作，办理有关税收减免手续；\r\n4、完成年度税务审计工作；\r\n5、负责协助财务总裁完善各项财务管理制度。\r\n\r\n岗位要求：\r\n1、财务、会计或税务相关专业，本科及以上学历，具有注册税务师执业资格；\r\n2、八年以上工作经验，无不良执业记录；\r\n3、能够进行税务咨询、税务审计或税务合规等方面的工作, 有扎实的专业基础知识；\r\n4、熟悉国家、地方的财税政策及法律法规，擅长税务筹划分析；\r\n5、善于与人沟通，具有良好的客户沟通协调能力及团队协调能力；\r\n6、熟练使用办公软件，EXCEL、WORD 等。'),
(657, 50, '成本会计', 0, '', '', NULL),
(656, 50, '成本管理', 0, '', '', NULL),
(655, 50, '财务分析', 0, '', '', '岗位职责：\r\n1、全面分析、监督公司财务及经营状况；\r\n2、建立企业评估模型，收集财务信息，撰写分析报告；\r\n3、初审财务报表，提供初审意见；\r\n4、审定实施的财务方案，提出改进意见和决策支持；\r\n5、研究分析公司成本收益情况；\r\n6、协助和配合预算编制、成本控制和内外部审计工作；\r\n7、整理、归类财务分析文件和档案。\r\n\r\n岗位要求：\r\n1、财会、审计等相关专业本科以上学历，有会计师资格或会计证者优先；\r\n2、3年以上财务管理领域相关工作经验；\r\n3、熟悉财务管理、预算编制、成本管理和审计等业务流程；\r\n4、擅长财务分析，熟悉财务分析模型，熟练使用财务软件；\r\n5、良好的协调、沟通能力和团队协作精神。'),
(654, 50, '出纳', 0, '', '', '岗位职责：\r\n1、负责日常收支的管理和核对；\r\n2、办公室基本账务的核对；\r\n3、负责收集和审核原始凭证，保证报销手续及原始单据的合法性、准确性；\r\n4、负责登记现金、银行存款日记账并准确录入系统，按时编制银行存款余额调节表；\r\n5、负责记账凭证的编号、装订；保存、归档财务相关资料；\r\n6、负责开具各项票据；\r\n7、配合总会负责办公室财务管理统计汇总。\r\n\r\n岗位要求：\r\n1、大学专科以上学历，会计学或财务管理专业毕业；\r\n2、具有1年以上出纳工作经验；\r\n3、熟悉操作财务软件、Excel、Word等办公软件；\r\n4、记账要求字迹清晰、准确、及时，账目日清月结，报表编制准确、及时；\r\n5、工作认真，态度端正；\r\n6、了解国家财经政策和会计、税务法规，熟悉银行结算业务。'),
(653, 50, '会计', 0, '', '', '岗位职责：\r\n1、审批财务收支，审阅财务专题报告和会计报表，对重大的财务收支计划、经济合同进行会签；\r\n2、编制预算和执行预算，参与拟订资金筹措和使用方案，确保资金的有效使用；\r\n3、审查公司对外提供的会计资料；\r\n4、负责审核公司本部和各下属单位上报的会计报表和集团公司会计报表，编制财务综合分析报告和专题分析报告，为公司领导决策提供可靠的依据；\r\n5、制订公司内部财务、会计制度和工作程序，经批准后组织实施并监督执行；\r\n6、组织编制与实现公司的财务收支计划、信贷计划与成本费用计划。\r\n\r\n岗位要求：\r\n1、会计相关专业，大专以上学历；\r\n2、2年以上工作经验，有一般纳税人企业工作经验者优先；\r\n3、认真细致，爱岗敬业，吃苦耐劳，有良好的职业操守；\r\n4、思维敏捷，接受能力强，能独立思考，善于总结工作经验；\r\n5、熟练应用财务及Office办公软件，对金蝶、用友等财务系统有实际操作者优先；\r\n6、具有良好的沟通能力；\r\n7、有会计从业资格证书，同时具备会计初级资格证者优先考虑。'),
(652, 50, '会计经理/主管', 0, '', '', NULL),
(651, 50, '财务主管', 0, '', '', NULL),
(650, 50, '财务经理', 0, '', '', '岗位职责：\r\n1、日常财务核算、会计凭证、出纳、税务工作的审核；\r\n2、研究制定会计政策和操作指导，调整会计准则；\r\n3、审核公司财务报表、核对关联往来，合并报表并进行财务分析；\r\n4、根据投资者要求，对外提供财务月报、季报和年报；\r\n5、组织业务学习、培训和会计岗位技能训练；\r\n6、依据费用管理规定，合理控制费用支出；\r\n7、定期组织检查会计政策执行情况，严控操作风险，解决存在问题；\r\n8、协调对外审计，提供所需财会资料。\r\n\r\n岗位要求：\r\n1、财会专业大学以上学历；\r\n2、有会计证或注册会计师资格者优先；\r\n3、5年以上会计工作经验，2年以上审计工作经验；\r\n4、熟悉财务核算流程，有不断学习的意愿和能力；\r\n5、有良好的沟通和人际交往能力，组织协调能力和承压能力。'),
(649, 50, '财务总监', 0, '', '', '岗位职责：\r\n1、主持公司财务战略的制定、财务管理及内部控制工作，完成企业财务计划；\r\n2、利用财务核算与会计管理原理为公司经营决策提供依据，协助总经理制定公司战略，并主持公司财务战略规划的制定；\r\n3、制定公司资金运营计划，监督资金管理报告和预、决算；\r\n4、对公司投资活动所需要的资金筹措方式进行成本计算；\r\n5、筹集公司运营所需资金，保证公司战略发展的资金需求，审批公司重大资金流向；\r\n6、主持对重大投资项目和经营活动的风险评估、指导、跟踪和财务风险控制；\r\n7、协调公司同银行、工商、税务等政府部门的关系，维护公司利益；\r\n8、参与公司重要事项的分析和决策，为企业的生产经营、业务发展及对外投资等事项提供财务方面的分析和决策依据；\r\n9、审核财务报表，提交财务管理工作报告。\r\n\r\n岗位要求：\r\n1、会计或金融专业本科以上学历，有注册会计师资格者优先；\r\n2、5年以上财务管理工作经验，或3年以上相近管理职位经验；\r\n3、熟悉会计、审计、税务、财务管理、会计电算化、相关法律法规；\r\n4、熟练掌握高级财务管理软件和办公软件；\r\n5、出色的财务分析、融资和资金管理能力；\r\n6、良好的组织、协调能力，良好的表达能力和团队合作精神。\r\n7、精通韩语，朝鲜族或有韩国留学经验者优先。'),
(74, 0, '计算机|通信', 0, '', '', ''),
(75, 74, '计算机应用', 0, '', '', NULL),
(76, 74, '互联网/网络', 0, '', '', NULL),
(77, 74, '计算机软硬件', 0, '', '', NULL),
(78, 74, 'IT管理', 0, '', '', NULL),
(79, 74, 'IT品质/技术支持', 0, '', '', NULL),
(80, 74, '通信', 0, '', '', NULL),
(693, 75, '打印机/复印机维修', 0, '', '', NULL),
(692, 75, '计算机维修/维护', 0, '', '', NULL),
(691, 75, '应用系统集成', 0, '', '', NULL),
(690, 75, '安防系统集成', 0, '', '', NULL),
(689, 75, '智能大厦系统集成', 0, '', '', NULL),
(688, 75, '计算机网络系统集成', 0, '', '', NULL),
(687, 75, '动画/3D设计', 0, '', '', '岗位职责：\r\n1、负责项目产品的图文、动画媒体的策划设计工作；\r\n2、负责宣传片、片头及片中动画的创意和制作；\r\n3、对作品的音乐选择提出建议和意见；\r\n4、指导、培训、培养美术类人员的设计、制作工作；\r\n5、负责制定平面和动画制作类相关标准、流程规定及创新工作。\r\n\r\n岗位要求：\r\n1、视觉艺术设计或美术相关设计专业毕业，大专及以上学历；\r\n2、两年以上平面、动画设计经验，有多媒体行业从业经验为优；\r\n3、熟练使用相关多媒体设计及后期编辑软件，并熟练掌握动画脚本语言；\r\n4、创作欲望强，创意构思独特、表现力强、具有良好的团队协作精神；\r\n5、极强的界面设计和动画计策划能力，较好的手绘能力，了解分镜头绘制方法\r\n6、具备创意制作过程的沟通领悟能力。'),
(686, 75, '平面设计', 0, '', '', '岗位职责：\r\n1、网站内容建设的布局和结构等方面的整体规划和文字编辑工作；\r\n2、负责网站日常美术设计和宣传资料的制作；\r\n3、完成信息内容的策划和日常更新与维护；\r\n4、编写网站宣传资料及相关产品资料；\r\n5、配合策划推广活动，并参与执行；\r\n\r\n岗位要求：\r\n1、有电子商务网站编辑经验或开过淘宝店铺经验者优先；\r\n2、较强的创意、策划能力，良好的文字表达能力，思维敏捷；\r\n3、熟练使用Photoshop、Flash、fireworks、Dreamweaver等常用设计制作软件；\r\n4、工作认真，有责任心，踏实肯干，富有团队精神；\r\n5、具备良好的美术基础，良好的创意构思能力。'),
(685, 75, '计算机辅助设计/CAD', 0, '', '', '岗位职责：\r\n1、配合3D设计师完成会展项目的CAD施工图；\r\n2、在主案设计师的指导下完成厅馆类项目的施工图及CAD深化设计；\r\n3、积极配合项目部及工程部的各项工作。\r\n\r\n岗位要求：\r\n1、大专及以上学历，平面设计相关专业，熟练运用各类软件；\r\n2、具备高度的工作热情，沟通协调能力较强；\r\n3、有会展行业从业经验者优先。'),
(96, 0, '建筑|房地产|物业管理', 0, '', '', ''),
(97, 96, '建筑', 0, '', '', NULL),
(98, 96, '房地产', 0, '', '', NULL),
(99, 96, '物业管理', 0, '', '', NULL),
(757, 97, '城镇规划设计', 0, '', '', NULL),
(756, 97, '路桥/港口/航道', 0, '', '', '岗位职责：\r\n1、全面主持项目技术管理工作；\r\n2、保证工程质量、进度和安全，符合公司要求；\r\n3、负责制订和实施实施性施工组织设计、重大施工方案、施工计划、安全生产管理办法等；、\r\n4、负责施工现场的技术指导，及时处理施工技术问题；、\r\n5、主持各类技术资料和技术文件的收集、整理和编制工作，组织编制本工程竣工文件和技术总结；\r\n6、参加竣工验收和产权移交工作；、\r\n7、协助项目经理抓好生产管理，协调与施工队的关系；、\r\n8、指导、监督、考核下属技术人员的技术工作，编制并实施本项目部技术人员的技术培训计划。\r\n\r\n岗位要求：\r\n1、市政道路桥梁专业大学本科以上学历；\r\n2、有市政院、规划院工作经历者优先考虑；\r\n3、有独立承担本专业工程项目组织设计的经验和能力者优先；\r\n4、有中级以上专业职称优先；有专业注册资格优先；\r\n5、熟练运用CAD及市政道路专业设计软件；\r\n6、专业基础知识扎实，熟悉专业规范，对专业知识有较强的钻研精神；\r\n7、良好的合作精神和较强的工作责任心。'),
(755, 97, '岩土工程', 0, '', '', NULL),
(754, 97, '结构工程师', 0, '', '', NULL),
(753, 97, '土木土建', 0, '', '', '岗位职责：\r\n1、负责土建图纸的审核，工地现场考察、勘察、测绘，进行土建工程概预算，督促设计单位按要求对图纸进行修改和完善；\r\n2、协助招标工作，参加招标工程图纸答疑，草拟土建专业相关条款，审核土建专业报价是否符合相关规定及各项收费是否合理；\r\n3、施工过程中，负责土建施工质量、进度和成本的控制，解决施工中出现的具体专业技术问题；\r\n4、协调业主、施工单位和监理单位之间以及与其他各专业之间的关系；\r\n5、组织人员审查竣工资料和对单位工程及单项工程初验和组织竣工验收，作出土建工程结算核定意见，办理结算单。\r\n\r\n岗位要求：\r\n1、大学本科及以上学历，建筑、工民建、土木工程类相关专业；\r\n2、2年以上土建相关领域施工工作经验，具有中级以上职称者优先；\r\n3、熟悉国家及地方相关法规、政策，熟悉土建类施工图、施工管理和有关土建的施工规范及要求，掌握项目规划、建筑设计、施工、验收规范及市政配套等基本建设程序；\r\n4、精通土建工作量清单及组价编制，熟练使用预算清单软件，熟悉施工现场工作流程和环节，了解市场工程造价信息及材料信息；\r\n5、具有良好的技术英语水平和计算机操作能力，熟练使用CAD制图，富有责任心、事业心及团队合作精神。'),
(752, 97, '建筑设计/制图', 0, '', '', NULL),
(751, 97, '建筑师', 0, '', '', '岗位职责：\r\n1、负责公司所有施工工程质量和技术工作的总体控制；\r\n2、管理公司的整体核心技术，组织制定和实施重大技术决策和技术方案；\r\n3、解决施工现场的技术问题；\r\n4、负责组织对重大质量事故的鉴定和处理、不定期对施工现场进行检查，随时监控工程质量，发现问题及时召集相关人员进行处理；\r\n5、审批招、投标工作的相关技术资料等；\r\n6、审批项目部上报的施工组织设计、施工方案，并制定施工工艺参数；\r\n7、参加甲方组织的图纸会审、方案论证；\r\n8、负责对工程组织基础、主体分部和单位工程的质量验收;\r\n9、负责贯彻执行国家科技法规和政策，建立健全相应的管理制度；\r\n10、根据施工现场上报的自检数据及第三方检测单位的初步检测数据，对工程质量进行评估，并做出相应的应急预案；\r\n11、主持交竣工技术文件资料的编制，参加交竣工验收、组织施工技术总结和学术论文的撰写并负责审核和向上级报告。\r\n\r\n岗位要求：\r\n1、大学以上学历，建筑或土木工程专业；\r\n2、10年以上设计院工作经验，5年以上企业全面经营管理工作经验；\r\n3、熟悉建筑专业设计规范；\r\n4、具有较好的人事协调能力和沟通能力，有较好的文字表达能力和口头表达能力。'),
(750, 97, '建造师', 0, '', '', NULL),
(749, 97, '安全员', 0, '', '', '岗位职责：\r\n1、组织安全文件的编写，安全教育及安全文件的管理；\r\n2、对施工现场进行安全监督、检查、指导，并做好安全检查记录；\r\n3、对不符合安全规范施工的班组及个人进行安全教育、处罚，并及时责令整改；\r\n4、负责安全预案及改进方案的编制；\r\n5、正确填报施工现场安全措施检查情况的安全生产报告，定期提出安全生产的情况分析报告的意见；\r\n6、组织安全检查、安全教育、安全活动和特种作业人员培训和考核；\r\n7、处理一般性的安全事故。\r\n\r\n岗位要求：\r\n1、大学专科及以上学历，、工民建、建筑和结构类相关专业；\r\n2、2年以上工程安全管理工作经验，具有安全员岗位资质证书优先；\r\n3、熟悉国家各项安全法律法规，熟悉生产现场安全工作流程、安全操作规范和安全管理的程序，能够及时发现安全隐患并给予纠正；\r\n4、熟悉掌握建筑施工生产过程及安全防护消防、临电等相关安全规章、标准和日常安全管理，有高度的责任心；\r\n5、具有一定的协调、组织和沟通能力，具有一定的语言表达能力。'),
(748, 97, '资料员', 0, '', '', '岗位职责：\r\n1、负责工程部档案文件的归档、移交、借阅管理；\r\n2、负责工程资料、图纸的管理，工程文件的处理；\r\n3、负责会议纪要、周工作计划、月度工作简报等公文整理；\r\n4、完成上级交办的其他任务。\r\n\r\n岗位要求：\r\n1、大学专科及以上学历，工程管理、工民建、档案管理等相关专业；\r\n2、从事相关工作1年以上，具备一定的工程资料管理经验；\r\n3、熟练使用CAD、WORD、EXCEL等绘图及办公软件；\r\n4、具有良好的团队合作精神，责任心强；\r\n5、工作有条理，有较强协调能力。'),
(747, 97, '施工员', 0, '', '', NULL),
(746, 97, '造价/预决算', 0, '', '', '岗位职责：\r\n1、项目投资分析，进行日常成本测算，提供设计变更成本建议；\r\n2、负责对设计估算、施工图预算、招标文件编制、工程量计算进行审核；\r\n3、组织内部招标实施，配合外部招标；\r\n4、合同文件的起草与管理，跟踪分析合同执行情况，审核相关条款；\r\n5、工程款支付审核，结算管理，概预算与决算报告；\r\n6、变更洽商审核及处理索赔事宜。\r\n\r\n岗位要求：\r\n1、建筑工程、造价、预算等相关专业大专以上学历；\r\n2、2年以上相关工作经验，具有注册造价师资格；\r\n3、熟练掌握相关领域工程造价管理和成本控制流程，了解相关规定和政策；\r\n4、善于撰写招标文件、合同及进行商务谈判；\r\n5、工作严谨，善于沟通，具备良好的团队合作精神和职业操守；\r\n6、卓越的执行能力，学习能力和独立工作能力。'),
(745, 97, '工程监理', 0, '', '', '岗位职责：\r\n1、在专业监理工程师的指导下开展监理工作；\r\n2、协助专业监理工程师完成工程量的核定；\r\n3、担任现场监理工作，发现问题及时向专业监理工程师报告；\r\n4、对承建单位实施计划和进度进行检查并记录；\r\n5、承建单位实施过程中的软件和设备安装、调试、测试进行监督并记录。\r\n6、按设计图及相关标准，对承包单位的工艺过程和施工工序进行检查和记录。\r\n\r\n岗位要求：\r\n1、大学专科及以上学历，建筑、土木、工民建类相关专业；\r\n2、1年以上工程监理工作经验，有助理工程师资格者优先；\r\n3、精通工程监理，工程管理等相关专业知识，了解建筑法、合同法、招投标法等相关法律法规，了解工程概预算相关知识；\r\n4、具有较强的沟通能力和组织协调能力，能够合理、有效地协调各项相关工作，工作严谨、认真、细致，具备一定的计算机操作能力；\r\n5、责任心强、吃苦耐劳，能适应经产出差。'),
(744, 97, '工程项目管理', 0, '', '', NULL),
(743, 97, '高级建筑工程师/总工', 0, '', '', NULL),
(116, 0, '生产|质管|技工', 0, '', '', ''),
(117, 116, '生产制造/运营', 0, '', '', ''),
(118, 116, '质量/安全管理', 0, '', '', ''),
(119, 116, '汽车', 0, '', '', ''),
(120, 116, '机械', 0, '', '', ''),
(121, 116, '技术工人', 0, '', '', ''),
(122, 116, '服装/纺织品', 0, '', '', ''),
(798, 117, '技术研发', 0, '', '', ''),
(797, 117, '采购管理', 0, '', '', ''),
(796, 117, '物流管理', 0, '', '', ''),
(795, 117, '仓库管理', 0, '', '', ''),
(794, 117, '工程/设备管理', 0, '', '', ''),
(793, 117, '生产调度', 0, '', '', ''),
(792, 117, '生产计划', 0, '', '', ''),
(791, 117, '班组长/生产主管', 0, '', '', ''),
(790, 117, '车间主任/生产经理', 0, '', '', ''),
(789, 117, '总工/副总工', 0, '', '', ''),
(788, 117, '厂长/副厂长', 0, '', '', ''),
(787, 117, '技术研发经理/主管', 0, '', '', ''),
(136, 0, '电子|电气|能源|化工', 0, '', '', ''),
(137, 136, '电子/半导体/电器/仪表', 0, '', '', ''),
(138, 136, '电气', 0, '', '', ''),
(139, 136, '电力/能源', 0, '', '', ''),
(140, 136, '化工', 0, '', '', ''),
(880, 137, '其他电子/半导体/仪表', 0, '', '', ''),
(879, 137, '汽车电子工程师', 0, '', '', ''),
(878, 137, '电器维修', 0, '', '', ''),
(877, 137, '激光/光电子技术', 0, '', '', ''),
(876, 137, '电池/电源开发', 0, '', '', ''),
(875, 137, '电子设备装配调试', 0, '', '', ''),
(874, 137, '电子设备维修', 0, '', '', ''),
(873, 137, '仪器/仪表/计量', 0, '', '', ''),
(872, 137, '电子元器件工程师', 0, '', '', ''),
(871, 137, '电子材料/半导体', 0, '', '', ''),
(870, 137, '版图/电路设计', 0, '', '', ''),
(869, 137, '嵌入式软/硬件开发', 0, '', '', ''),
(868, 137, '工程经理/主管', 0, '', '', ''),
(867, 137, '电子技术测试', 0, '', '', ''),
(866, 137, '电子技术研发', 0, '', '', ''),
(865, 137, '电子工程师/技术员', 0, '', '', ''),
(169, 0, '广告|媒体|艺术|出版', 0, '', '', ''),
(170, 169, '广告', 0, '', '', NULL),
(171, 169, '影视/媒体', 0, '', '', NULL),
(172, 169, '艺术设计', 0, '', '', NULL),
(173, 169, '新闻出版', 0, '', '', NULL),
(927, 172, '多媒体设计', 0, '', '', NULL),
(926, 172, '店面/陈列/展览设计', 0, '', '', NULL),
(925, 172, '动画/3D设计', 0, '', '', NULL),
(924, 172, '室内设计师', 0, '', '', NULL),
(923, 172, '排版设计', 0, '', '', NULL),
(922, 172, '平面设计', 0, '', '', NULL),
(921, 172, '计算机辅助设计/CAD', 0, '', '', NULL),
(920, 171, '其他影视/媒体', 0, '', '', NULL),
(919, 171, '后期制作', 0, '', '', NULL),
(918, 171, '舞台设计', 0, '', '', NULL),
(917, 171, '歌手/乐手', 0, '', '', NULL),
(916, 171, '摄影/摄像', 0, '', '', NULL),
(915, 171, '经纪人', 0, '', '', NULL),
(914, 171, '主持人/播音员/DJ', 0, '', '', NULL),
(913, 171, '演员/模特', 0, '', '', NULL),
(912, 171, '导演/编导/艺术总监', 0, '', '', NULL),
(911, 171, '影视策划/制作人员', 0, '', '', NULL),
(910, 170, '其他广告', 0, '', '', NULL),
(909, 170, '美术指导', 0, '', '', NULL),
(908, 170, '文案/策划', 0, '', '', NULL),
(907, 170, '广告创意/设计', 0, '', '', NULL),
(906, 170, '广告客户专员', 0, '', '', NULL),
(905, 170, '广告客户经理', 0, '', '', NULL),
(203, 0, '教育|法律|咨询|翻译', 0, '', '', ''),
(204, 203, '教育/培训', 0, '', '', NULL),
(205, 203, '法律', 0, '', '', NULL),
(206, 203, '咨询', 0, '', '', NULL),
(207, 203, '翻译', 0, '', '', NULL),
(961, 204, '招生/课程顾问', 0, '', '', NULL),
(960, 204, '培训策划', 0, '', '', NULL),
(959, 204, '教育产品开发', 0, '', '', NULL),
(958, 204, '培训助理', 0, '', '', NULL),
(957, 204, '培训师/讲师', 0, '', '', NULL),
(956, 204, '教务管理', 0, '', '', NULL),
(955, 204, '家教', 0, '', '', NULL),
(954, 204, '校长/副校长', 0, '', '', NULL),
(953, 204, '舞蹈教师', 0, '', '', NULL),
(952, 204, '美术教师', 0, '', '', NULL),
(951, 204, '音乐教师', 0, '', '', NULL),
(950, 204, '外语教师', 0, '', '', NULL),
(949, 204, '幼教', 0, '', '', NULL),
(948, 204, '小学教师', 0, '', '', NULL),
(947, 204, '中学教师', 0, '', '', NULL),
(225, 0, '医疗|制药|环保', 0, '', '', ''),
(226, 225, '医院/医疗', 0, '', '', NULL),
(227, 225, '制药/医疗器械', 0, '', '', NULL),
(228, 225, '环保', 0, '', '', NULL),
(992, 226, '其他医院/医疗', 0, '', '', NULL),
(991, 226, '药库主任/药剂师', 0, '', '', '岗位职责：\r\n1、负责药品部门的营运、管理工作；\r\n2、负责药品的调剂、发放及药物咨询工作，\r\n3、按照《GSP》标准及制度要求进行报表和文件管理。\r\n岗位要求：\r\n1、中专或以上学历，1年以上药品销售、管理经验；\r\n2、持药剂师/执业药剂师职称证书，GSP上岗证\r\n3、工作积极主动，具有高度的责任感、敬业精神和团队合作精神。'),
(990, 226, '兽医/宠物医生', 0, '', '', '岗位职责：\r\n1、负责犬舍日常清洁，犬只的日常护理；\r\n2、能对宠物疾病进行临床诊断、治疗，如配药、打针、输液等；\r\n3、如需手术，认真做好手术前准备、术中监护和术后的护理工作。\r\n岗位要求:\r\n1、喜爱犬只，具备爱心和耐性；\r\n2、工作积极主动有进取心，良好的学习能力、语言表达能力和团队协作能力；\r\n3、动物医学类相关专业，专科及以上学历。'),
(989, 226, '营养师', 0, '', '', '岗位职责：\r\n1、负责与客户进行有效沟通，根据用户需求提供咨询服务；\r\n2、负责对客户进行培训和指导，并提出合理的建议和分析报告；\r\n3、负责拓展业务，不断开发新的客户、维持老客户；\r\n4、负责和相关部门进行具体项目的沟通，保证咨询项目的顺利进行\r\n岗位要求：\r\n1、3年以上相关工作经验；\r\n2、工作认真踏实，能承受一定的工作压力；\r\n3、品行端正、身体健康、富有敬业精神；\r\n4、有责任心，团队合作精神强。'),
(988, 226, '医院管理人员', 0, '', '', NULL),
(987, 226, '心理医生', 0, '', '', NULL),
(986, 226, '护士/护理人员', 0, '', '', '岗位职责：\r\n1、配合医生做好对病人的治疗工作；\r\n2、观察病人的病情转化情况。\r\n岗位要求：\r\n1、护理及相关专业大专及以上学历；\r\n2、一年以上工作经验，有护士上岗证优先；\r\n3、亲和力强，富于爱心，踏实敬业。'),
(985, 226, '医疗技术人员', 0, '', '', NULL),
(984, 226, '医生/医师', 0, '', '', '岗位职责：\r\n1、接待日常门诊、急诊等医疗工作，认真检查患者病情，细心诊断，正确处方，合理用药，杜绝误诊；\r\n2、根据安排做好防病宣传，普及防病和救护知识；\r\n3、定期对医务室的各种医疗器械消毒、更换；\r\n4、药品清点检查，对过期药品及时清理，确保员工用药安全。\r\n岗位要求：\r\n1、大专以上学历，医学、临床、护理等专业；\r\n2、语言表达清晰、流畅、具有良好的交流沟通能力，亲和力；\r\n3、熟悉计算机基础知识，打字熟练；\r\n4、具有良好的职业道德和团队协作精神。'),
(241, 0, '服务业', 0, '', '', ''),
(242, 241, '百货/零售', 0, '', '', NULL),
(243, 241, '保安/家政', 0, '', '', NULL),
(244, 241, '餐饮/旅游/娱乐', 0, '', '', NULL),
(245, 241, '美容/健身', 0, '', '', NULL),
(246, 241, '物流/交通/仓储', 0, '', '', NULL),
(1014, 242, '其他百货/零售', 0, '', '', NULL),
(1013, 242, '品类管理', 0, '', '', NULL),
(1012, 242, '防损员/内保', 0, '', '', '岗位职责：\r\n1、维护卖场秩序，保证卖场财产安全、顾客和员工安全；\r\n2、检查卖场防盗系统、消防等安全设施的使用状况，定期维护与保养；\r\n3、巡视商品内各个安全通道、消防重点区域以及禁烟情况，杜绝一切安全隐患；\r\n4、查找商品损耗原因，协助降低损耗量；\r\n5、配合保安完成卖场“反扒”工。'),
(1011, 242, '收银员', 0, '', '', '岗位职责：\r\n1、遵守各项财务制度和操作程序；\r\n2、按规定为离店客人办理离店手续，确保客人在离店之前办好所有帐目的手续；\r\n3、催收已退未结的账目，将未结帐目报告给大堂副理；\r\n4、处理好退款，付款及帐户转移；\r\n5、负责为客人兑换外币，提供贵重物品寄存保险箱；\r\n岗位要求：\r\n1、高中以上学历，年龄在XX年以上， 形象气质好；\r\n2、非本地人员需提借本市户口人担保;\r\n3、具有酒店前台收银工作经验，具有相应的会计知识。'),
(1010, 242, '理货员', 0, '', '', '岗位职责：\r\n1、熟悉所在商品部门的商品名称、产地、厂家、规格、用途、性能、保质期限；\r\n2、遵守超市仓库管理和商品发货的有关规定，按作业流程进行该项工作；\r\n3、掌握商品标价的知识，正确标好价格；\r\n4、熟练掌握商品陈列的有关专业知识，并把它运用到实际工作中；\r\n5、搞好货架与责任区\r\n岗位要求：\r\n1、高中以上学历；\r\n2、有大型商场或超市工作经验者优先；\r\n3、品貌端正，热爱零售行业，吃苦耐劳，责任心强，身体健康，有很强的敬业精神和良好的心理素质；\r\n4、具备简单的计算机操作技巧，了解商品分类和存储知识；\r\n5、能够胜任繁重的体力工作，能适应中夜班调休安排。'),
(1009, 242, '导购员/促销员', 0, '', '', '岗位职责：\r\n1、全面主持店面的管理工作，配合总部的各项营销策略的实施；\r\n2、执行总部下达的各项任务；\r\n3、做好门店各个部门的分工管理工作；\r\n4、监督商品的要货、上货、补货，做好进货验收、商品陈列、商品质量和服务质量管理等有关作业；\r\n5、监督门店商品损耗管理，把握商品损耗尺度；'),
(1008, 242, '店员/营业员', 0, '', '', '岗位职责：\r\n1、全面主持店面的管理工作，配合总部的各项营销策略的实施；\r\n2、执行总部下达的各项任务；\r\n3、做好门店各个部门的分工管理工作；\r\n4、监督商品的要货、上货、补货，做好进货验收、商品陈列、商品质量和服务质量管理等有关作业；\r\n5、监督门店商品损耗管理，把握商品损耗尺度；'),
(1007, 242, '店长/卖场经理', 0, '', '', '岗位职责：\r\n1、全面主持店面的管理工作，配合总部的各项营销策略的实施；\r\n2、执行总部下达的各项任务；\r\n3、做好门店各个部门的分工管理工作；\r\n4、监督商品的要货、上货、补货，做好进货验收、商品陈列、商品质量和服务质量管理等有关作业；\r\n5、监督门店商品损耗管理，把握商品损耗尺度；\r\n岗位要求：\r\n1、大专及以上学历,专业不限；\r\n2、3年以上零售业管理工作经验，具有较强的店务管理经验；\r\n3、精通团队管理、客户管理、商品管理、陈列管理，物流配送，熟悉店务的各项流程的制定、执行；\r\n4、较强的团队管理能力和沟通能力，能够承受较大的工作强度和工作压力；\r\n5、年龄35岁以下；'),
(258, 0, '学生|社工|科研|农业|其他', 0, '', '', ''),
(259, 258, '学生/社工/科研', 0, '', '', NULL),
(260, 258, '农/林/牧/渔业', 0, '', '', NULL),
(261, 258, '其他', 0, '', '', NULL),
(1064, 260, '渔业水产', 0, '', '', NULL),
(1063, 260, '林业苗木', 0, '', '', NULL),
(1062, 260, '园林园艺', 0, '', '', NULL),
(1061, 260, '兽医/宠物医生', 0, '', '', NULL),
(1060, 260, '动物营养/饲料研发', 0, '', '', NULL),
(1059, 260, '禽畜养殖', 0, '', '', NULL),
(1058, 260, '农艺师', 0, '', '', NULL),
(1057, 259, '科学研究人员', 0, '', '', NULL),
(1056, 259, '社会服务', 0, '', '', NULL),
(1055, 259, '实习生', 0, '', '', NULL),
(773, 98, '房地产销售/置业顾问', 0, '', '', '岗位职责：\r\n1、 负责客户的接待、咨询工作，为客户提供专业的房地产置业咨询服务；\r\n2、 陪同客户看房，促成二手房买卖或租赁业务；\r\n3、 负责公司房源开发与积累，并与业主建立良好的业务协作关系。\r\n\r\n岗位要求：\r\n1、年龄在20—30周岁，大专以上学历；\r\n2、诚实守信，吃苦耐劳，具有良好的团队精神；\r\n3、能承受较强的工作压力，愿意挑战高薪；\r\n4、普通话流利；\r\n5、有相关经验者优先。'),
(772, 98, '房地产经纪人', 0, '', '', NULL),
(771, 98, '房地产营销策划', 0, '', '', NULL),
(770, 98, '房地产开发/策划', 0, '', '', '岗位职责：\r\n1、负责房地产项目开发前期策划的工作，如项目定位分析、投资收益分析、人文景观设置建议等；\r\n2、负责房地产项目开发的后期营销策划的工作，如项目概念定位的成功演绎、各类营销手法的运用；\r\n3、负责与相关媒体对接,搞好公司各类推广项目,做好活动的策划,包装,宣传,跟进等实施工作；\r\n4、撰写全程策划报告、定位报告、规划建议、执行报告；\r\n5、负责对销售及策划进度进行动态掌控。\r\n\r\n岗位要求：\r\n1、营销、广告、建筑、土木类相关专业大学专科及以上学历；\r\n2、2年以上房地产行业策划咨询工作经验，有成功策划案例者优先；\r\n3、熟悉房地产策划，能够完成前期策划报告、营销策划报告、推广策划书等房地产开发过程中的策划和撰写工作；\r\n4、熟悉城市建设、房地产行业，熟悉房地产国家相关法律法规，及时掌握房地产市场动态，具有敏锐的市场洞察力、判断力；\r\n5、热爱房地产策划事业，上进心强，对市场、客户需求有一定的敏感性。'),
(769, 98, '房地产项目经理', 0, '', '', NULL),
(768, 97, '其他建筑', 0, '', '', NULL),
(767, 97, '测绘/测量', 0, '', '', '岗位职责：\r\n1、根据项目的建设规划，组织开展线路,航测等方面勘测；\r\n2、对项目工程建设过程进行指导、监督；\r\n3、对项目建设及后期运营过程中相关问题进行准确的分析或预测；\r\n4、根据设计方案组织现场施工，并提出相应的改进建议。\r\n\r\n岗位要求：\r\n1、大专及以上学历，工程测量、航测，地理信息等相关专业毕业；\r\n2、2年以上相关行业工程施工经验；\r\n3、GPS、全站仪操作熟练；\r\n4、南方CASS、AUTOCAD操作熟练；道亨软件熟练；\r\n5、具有工程现场管理和技术指导经验，能分析处理运行技术问题，指导操作人员作业；\r\n6、有较强的沟通能力和敬业精神。'),
(766, 97, '电气工程', 0, '', '', NULL),
(765, 97, '综合布线', 0, '', '', '岗位职责：\r\n1、负责弱电系统方案规划、设计和搭建，做出施工图纸，并以书面形式汇报、跟踪各项弱电建设进度，处理各种紧急情况； \r\n2、负责陪同销售去面对客户了解客户需求，做出前期设计调整来满足顾客对布线方面的要求； \r\n3、负责与用户沟通,详细了解需求,完成由设计到施工的管理，到用户工程竣工文档编制、提交的全过程管理。\r\n4、配合系统工程师完成系统集成项目后期的实施工作。\r\n\r\n岗位要求：\r\n1. 计算机相关专业，熟悉计算机及网络相关知识，熟练CAD制图；\r\n2. 具有弱电系统经验（主要是综合布线、通信机房设备安装/调试、计算机网络系统、安防系统等）；\r\n3. 熟悉弱电行业的相关规范和标准等，具备较强的项目及团队管理协调能力；\r\n4. 工作热情，责任心强，富有创新精神，具有良好的学习能力、分析问题和解决问题的能力； \r\n5. 人际沟通能力强，具有良好的团队协作精神。 \r\n6、能够执行公司业务流程及工程施工规范，责任心强；'),
(764, 97, '智能大厦系统集成', 0, '', '', NULL),
(763, 97, '室内设计师', 0, '', '', NULL),
(762, 97, '制冷暖通', 0, '', '', '岗位职责：\r\n1、制定工程项目水暖工程的具体施工方案，现场指导工程施工过程并提供技术支持；\r\n2、与设计单位沟通，协调机电顾问公司的暖通工作，审核暖通设计方案和图纸质量；\r\n3、为暖通施工提供技术支持，对暖通设备的选用提供合理的建议和修改意见；\r\n4、参加现场巡视，配合工程施工和验收，处理现场施工问题；\r\n5、对工程项目中的水暖工程进行技术分析并进行监督和管理；\r\n6、负责与施工单位、监理单位的协调沟通。\r\n\r\n岗位要求：\r\n1、年龄25岁以上，给排水、暖通、机电类专业专科以上学历；\r\n2、3年以上暖通工作经验，持有国家相关部门颁发的职业资格证书（上岗证）；\r\n3、熟悉暖通工程施工工艺、施工流程及相关验收规范，了解暖通工程施工材料市场行情和工程设计的行业规范；\r\n4、具有扎实的工程现场管理经验和良好的质量意识、成本意识与进度控制能力；\r\n5、具有良好的沟通、协调能力，丰富的现场协调能力、良好的团队精神与敬业精神；\r\n6、身体健康，吃苦耐劳，能够接受外派。'),
(761, 97, '给排水', 0, '', '', '岗位职责：\r\n1、负责给排水和消防系统工程设计、施工技术及现场管理工作；\r\n2、审核给排水等工程招标文件的技术部分；\r\n3、协调施工单位，监理单位处理现场问题；\r\n4、负责工程各项隐蔽验收、分项验收及竣工验收等验收工作；\r\n5、做好本专业有关技术资料的整理工作；\r\n6、完成主管领导交办的其他工作。\r\n\r\n岗位要求：\r\n1、工程、给排水类相关专业大学专科及以上学历；\r\n2、2年以上相关工作经验，中级以上职称；\r\n3、熟悉设计行业和业务，掌握设计工作流程，具备本专业的基本理论知识，了解相关专业知识；，熟练运用Office、Auto、CAD等计算机软件系统；\r\n4、细心严谨，能吃苦耐劳，具有团队精神及沟通协调能力。'),
(760, 97, '幕墙设计', 0, '', '', '岗位职责：\r\n1、全力协助设计监督完成项目设计任务；\r\n2、按照设计监督的要求绘制或修改图纸，包括立面图、平面图、大样图等；\r\n3、制作设计监督委派的材料统计清单；\r\n4、制作设计监督委派的材料订购信息/文书；\r\n5、将所有已完成的工作提交给图纸设计员或设计监督确认；\r\n6、协助设计监督维护技术档案，包括文件收集、复制、打印、登记等；\r\n7、协助专业总工解决工地现场出现的技术问题。\r\n\r\n岗位要求：\r\n1、大专及以上学历，5年以上甲级设计院或大型房地产开发/施工公司工作经验；\r\n2、熟练使用AUTOCAD绘图软件及办公软件；\r\n3、熟悉建筑相关的国家规范及法规；\r\n4、有大型综合项目设计经验，熟练掌握建筑施工图要求及做法；\r\n5、承担建筑图纸相应专业审核工作；\r\n6、有现场施工配合经验，有住宅房型、规范等设计经验；\r\n7、具备良好的沟通协调及人际关系能力；\r\n8、中级工程师。'),
(759, 97, '市政工程师', 0, '', '', NULL),
(758, 97, '园林/景观设计', 0, '', '', '岗位职责：\r\n1、组织落实景观工程设计工作，控制图纸及变更的交付进度、深度及合理性；\r\n2、编制、审查景观工程计划、，制定设计标准，完善景观设计，控制景观实施效果；\r\n3、主管景观方案、施工图等文件的审核；\r\n4、组织景观工程材料的选样及定板工作；\r\n5、组织采购景观工程所需要的小品；\r\n6、负责景观工程竣工验收、监控施工进度、质量，解决相关技术问题。\r\n\r\n岗位要求：\r\n1、园林、园艺、艺术设计类相关专业大学专科及以上学历；\r\n2、2年以上景观设计相关领域管理工作经验，中级以上职称；\r\n3、熟悉苗木特性，善于现场布景，能独立组织景观项目的设计和施工工作，能有效的控制工期和景观实施效果，熟练使用电脑办公软件及绘图专业软件；\r\n4、具备专业创新能力、掌握高效的工作方法，具备良好的协调能力，具有极强的敬业精神和责任心。'),
(742, 80, '其他通信', 0, '', '', NULL),
(741, 80, '通信产品维修', 0, '', '', NULL),
(740, 80, '移动通信工程师', 0, '', '', NULL),
(739, 80, '数据通信工程师', 0, '', '', NULL),
(738, 80, '无线通信工程师', 0, '', '', '岗位职责：\r\n1、无线产品或产品无线模块部分的开发设计、器件选型；\r\n2、研究无线电波传播模型；\r\n3、无线电相关技术应用研究；\r\n4、编写研发技术文档。\r\n\r\n岗位要求：\r\n1、无线电或通信专业，本科及以上学历；英语良好；\r\n2、2年以上相关工作经历；\r\n3、精通无线电通信相关理论知识，熟悉数字信号处理算法。'),
(737, 80, '通信技术工程师', 0, '', '', '岗位职责：\r\n1、负责本公司产品的维修、调整、改频、开通、监控等工作；\r\n2、负责对使用本公司产品的所有站点进行定期巡检工作，及时发现隐患，及时处理故障；\r\n3、负责用户要求的设备改造工作；\r\n4、及时反馈质量问题；\r\n5、定期向维护部负责人汇报维护工作情况。\r\n\r\n岗位要求：\r\n1、通信类、计算机相关专业；\r\n2、1年以上工作经验，有相关资格证书者优先；\r\n3、熟悉交换机等相关设备（如思科、华为）；\r\n4、较强的独立分析问题和解决问题的能力。'),
(736, 79, '其他IT品质/技术支持', 0, '', '', NULL),
(735, 79, '硬件测试', 0, '', '', '岗位职责：\r\n1、完成公司项目、产品的所有相关测试工作；\r\n2、根据产品需求和设计文档，制定测试计划，并分析测试需求、设计测试流程；\r\n3、根据产品测试需求完成测试环境的设计与配置工作；\r\n4、执行具体测试任务并确认测试结果、缺陷跟踪，完成测试报告以及测试结果分析；\r\n5、在测试各环节与开发、产品等部门沟通保证测试输入和输出的正确性和完备性；\r\n6、完成产品缺陷验证和确认，对于难以重现的缺陷，需要完成可能性原因分析与验证；\r\n7、定期提交产品缺陷统计分析报告并完成产品测试总结报告；\r\n8、完成测试团队的管理考核及业务培训工作\r\n\r\n岗位要求：\r\n1、相关专业本科以上学历；英语良好；\r\n2、两年以上相关测试工作经验；\r\n3、熟悉产品结构及质量控制理论；善于制定测试计划，编制测试方案及用例，能够规范测试流程；熟悉相关测试工具；熟练使用办公软件；\r\n4、具备良好的业务沟通和理解能力,有较强的责任感及进取精神。'),
(734, 79, '软件测试', 0, '', '', '岗位职责：\r\n1、完成公司项目、产品的所有相关测试工作；\r\n2、根据产品需求和设计文档，制定测试计划，并分析测试需求、设计测试流程；\r\n3、根据产品测试需求完成测试环境的设计与配置工作；\r\n4、执行具体测试任务并确认测试结果、缺陷跟踪，完成测试报告以及测试结果分析；\r\n5、在测试各环节与开发、产品等部门沟通保证测试输入和输出的正确性和完备性；\r\n6、完成产品缺陷验证和确认，对于难以重现的缺陷，需要完成可能性原因分析与验证；\r\n7、定期提交产品缺陷统计分析报告并完成产品测试总结报告；\r\n8、完成测试团队的管理考核及业务培训工作。\r\n\r\n岗位要求：\r\n1、相关专业本科以上学历；英语良好；\r\n2、两年以上相关测试工作经验；\r\n3、熟悉产品结构及质量控制理论；善于制定测试计划，编制测试方案及用例，能够规范测试流程；熟悉相关测试工具；熟练使用办公软件；\r\n4、具备良好的业务沟通和理解能力,有较强的责任感及进取精神。'),
(733, 79, 'IT品质管理', 0, '', '', '岗位职责：\r\n1、负责网络及其设备的维护、管理、故障排除等日常工作，确保公司网络日常的正常运作；\r\n2、负责公司办公环境的软硬件和桌面系统的日常维护；\r\n3、维护和监控公司局域网、广域网，保证其正常运行，确保局域网、广域网在工作期间内安全稳定运行；\r\n4、安装和维护公司计算机、服务器系统软件和应用软件，同时为其他部门提供软硬件技术支持；\r\n5、解决排除各种软硬件故障，做好记录，定期制作系统运行报告；\r\n6、维护数据中心，对系统数据进行备份。\r\n\r\n岗位要求：\r\n1、通信、电子工程、自动化、计算机等相关专业，大专或以上学历，1年以上网格系统与IT系统维护工作经验；\r\n2、熟悉和掌握各种计算机软硬件，可独立进行安装、调试及故障排除；\r\n3、精通局域网的维护及网络安全知识，可熟练进行局域网的搭建和网络设备的基本维护和故障处理；\r\n4、熟练运用WINDOWS、server20002003等对服务器进行维护与管理；\r\n5、有良好的专业英语读写水平；工作主动性强，耐心细致，有责任心，具备团队合作精神。'),
(732, 79, 'IT技术支持/维护工程师', 0, '', '', '岗位职责：\r\n1、负责网络及其设备的维护、管理、故障排除等日常工作，确保公司网络日常的正常运作；\r\n2、负责公司办公环境的软硬件和桌面系统的日常维护；\r\n3、维护和监控公司局域网、广域网，保证其正常运行，确保局域网、广域网在工作期间内安全稳定运行；\r\n4、安装和维护公司计算机、服务器系统软件和应用软件，同时为其他部门提供软硬件技术支持；\r\n5、解决排除各种软硬件故障，做好记录，定期制作系统运行报告；\r\n6、维护数据中心，对系统数据进行备份。\r\n\r\n岗位要求：\r\n1、通信、电子工程、自动化、计算机等相关专业，大专或以上学历，1年以上网格系统与IT系统维护工作经验；\r\n2、熟悉和掌握各种计算机软硬件，可独立进行安装、调试及故障排除；\r\n3、精通局域网的维护及网络安全知识，可熟练进行局域网的搭建和网络设备的基本维护和故障处理；\r\n4、熟练运用WINDOWS、server20002003等对服务器进行维护与管理；\r\n5、有良好的专业英语读写水平；工作主动性强，耐心细致，有责任心，具备团队合作精神。'),
(731, 79, 'IT技术支持/维护经理', 0, '', '', NULL),
(730, 78, '其他IT管理', 0, '', '', NULL),
(729, 78, 'IT项目执行/协调', 0, '', '', NULL),
(728, 78, 'IT项目经理/主管', 0, '', '', '岗位职责：\r\n1、参与网站项目需求分析，根据项目需求设计和优化技术方案；\r\n2、负责设计、细化和实施程序开发计划，管理程序开发团队；\r\n3、负责架构设计，功能和模块划分，负责核心模块开发；\r\n4、解决技术难点。\r\n\r\n岗位要求：\r\n1、本科以上学历，3年以上PHP开发经验，1年以上开发管理经验；\r\n2、精通javascript,、css，php，熟悉ajax应用、SQL数据库设计及熟练应用SQL语言；\r\n3、熟悉软件开发流程、设计模式，较好的文档能力及良好的编码风格；\r\n4、视频、课件制作及E-learning开发工作经验者尤佳；\r\n5、良好的理解和表达能力，善于沟通，很好的团队合作意识。'),
(727, 78, '信息技术经理/专员', 0, '', '', NULL),
(726, 78, 'IT技术总监/经理', 0, '', '', '岗位职责：\r\n1、组织制定和实施重大技术决策和技术方案，制定技术发展战略、规划发展方向；\r\n2、提出新项目开发计划，并提交项目建议书；\r\n3、进行项目计划、工作统筹，带领技术团队完成项目开发和文档管理；\r\n4、进行技术难题的攻关和预研；\r\n5、实现提出的技术需求；解答客户提出的技术问题，提供技术支持；\r\n6、拟定团队的工作目标并监督实施；\r\n7、团队管理、指导学习、安排培训，提升团队技术水平。\r\n\r\n岗位要求：\r\n1、计算机专业，本科以上学历；\r\n2、5年以上工作经验；3年以上项目实施和管理经验；\r\n3、技术精通，对行业背景有深刻了解；\r\n4、思路清晰、语言表达能力强，有良好的英文能力；\r\n5、较强的学习和运用新技术的能力；\r\n6、具有良好的心理素质以及团队合作精神，有较强的责任心。'),
(725, 77, '其他计算机软硬件', 0, '', '', NULL),
(724, 77, '嵌入式软/硬件开发', 0, '', '', NULL),
(723, 77, '硬件工程师', 0, '', '', '岗位职责：\r\n1、负责完成产品的硬件单板、逻辑电路的设计与开发；协助PCB设计及单板试制加工；\r\n2、项目要求完成总体方案、器件选型、原理图设计、调试、测试维护优化等工作，并对设计质量负责；\r\n3、及时编写各种文档和标准化资料；\r\n4、对本单元产品提供技术支持；\r\n5、培训、指导生产部技术人员生产本单元硬件过程。\r\n\r\n岗位要求：\r\n1、本科及以上学历，通信、计算机、电子等相关专业；2年以上相关工作经验；\r\n2、有较好的数模电路、信号与系统基础知识；具备一个或以上的数模电路调试经验；\r\n3、精通Protel等开发工具；精通汇编或C语言开发；\r\n4、能够独立阅读英文相关资料；\r\n5、工作责任感强，有较好的钻研精神和团队合作意识。'),
(722, 77, '软件界面设计', 0, '', '', NULL),
(721, 77, 'WEB前端开发', 0, '', '', NULL),
(720, 77, '手机软件开发', 0, '', '', NULL),
(719, 77, 'ERP技术开发/应用/实施', 0, '', '', NULL),
(718, 77, '系统分析/架构', 0, '', '', '岗位职责：\r\n1、参与系统的需求调研和需求分析，撰写相关技术文档\r\n2、搭建系统开发环境，完成系统框架和核心代码的实现；\r\n3、项目概要设计、详细设计、开发计划等的编制并实施；\r\n4、系统开发测试、部署和集成；\r\n5、负责解决开发过程中的技术问题；\r\n6、参与代码维护与备份。\r\n\r\n岗位要求：\r\n1、2年以上实际JAVA项目开发实施工作经验，计算机软件及相关专业本科学历以上；\r\n2、具有丰富J2EE架构设计经验；精通java编程、设计模式和组件技术，熟悉关系型数据库；\r\n3、熟练使用uml、xml相关的开发工具和处理程序，熟悉spring，struts、,hibernate等常用开源系统\r\n4、熟悉各种Java应用服务器的使用；熟悉LINUX，UNIX操作系统；\r\n5、熟练使用eclipse、cvs、svn、ant、tomcat、mysql等常用开发工具和开发环境；\r\n6、熟悉、Restful、WebService、xml-rpc、jax-ws、xmlschema等标准或风格，熟悉、axis2、xfire、cxf、restlet、中的至少两项，熟悉、apache、系列开源软件；\r\n7、了解、JMS、JSON、restlet、json-rpc、Osgi、SCA、JMX、ESB，了解分析模式与设计模式并能够灵活运用；\r\n8、具备良好的团队合作精神和沟通交流能力。'),
(717, 77, '程序设计员', 0, '', '', '岗位职责：\r\n1、电子商务网站开发、系统开发；\r\n2、按照项目管理流程，参与研发部门的总体设计评审；\r\n3、进行详细设计、代码开发，配合测试，高质量完成项目；\r\n4、参与技术难题攻关、组织技术积累等工作。\r\n\r\n岗位要求：\r\n1、一年以上开发经验，有企业级应用开发经验；\r\n2、精通Java/J2EE编程，有Spring+Hibernate或类似框架的实际项目经验，熟练使用Eclipse等开发工具；\r\n3、熟悉JavaScript、Ajax、XML等相关技术；\r\n4、熟悉Velocity、Freemaker等模板引擎；\r\n5、熟悉Oracle、MySQL等数据库开发、SQL性能调优；\r\n6、熟练掌握常用的Linux命令、、shell脚本，Windows、Server的各项服务、应用配置；\r\n7、深入理解OO思想，熟悉UML语言；\r\n8、有良好的代码书写、注释和单元测试习惯，熟练运用多种软件设计模式；\r\n9、具备良好的沟通合作技巧，较强的责任心及团队合作精神。'),
(716, 77, '数据库开发/管理', 0, '', '', '岗位职责：\r\n1、进行业务系统数据库的规划、设计、实施，设计并优化数据库物理建设方案\r\n2、对数据库进行管理，负责数据库应用系统的运营及监控；\r\n3、业务系统数据库的定期维护和异常处理；\r\n4、对数据库性能分析与调优，排错，保证数据安全；\r\n5、对数据库进行定期备份、和按需恢复；\r\n6、配合其他部门进行的数据处理、查询，统计和分析工作。\r\n\r\n岗位要求：\r\n1、计算机相关专业，本科以上学历；\r\n2、两年以上相关工作经验；\r\n3、精通关系数据库原理,熟悉数据库系统的规划、安装、配置、性能调试，\r\n4、精通SQL脚本的编写，有丰富的数据库管理、运维调优经验；\r\n5、熟练使用数据库管理、分析、设计工具；\r\n6、快速处理系统突发事件的能力，较强的学习和创新能力；\r\n7、良好的良好的沟通能力、团队合作精神。'),
(715, 77, '软件工程师', 0, '', '', '岗位职责：\r\n1、完成软件系统代码的实现，编写代码注释和开发文档；\r\n2、辅助进行系统的功能定义,程序设计；\r\n3、根据设计文档或需求说明完成代码编写，调试，测试和维护；\r\n4、分析并解决软件开发过程中的问题；\r\n5、协助测试工程师制定测试计划，定位发现的问题；\r\n6、配合项目经理完成相关任务目标。\r\n\r\n岗位要求：\r\n1、计算机或相关专业本科学历以上；\r\n2、2年以上软件开发经验；\r\n3、熟悉面向对象思想，精通编程，调试和相关技术；\r\n4、熟悉应用服务器的安装、调试、配置及使用；\r\n5、具备需求分析和系统设计能力，、以及较强的逻辑分析和独立解决问题能力；\r\n6、能熟练阅读中文、英文技术文档；富有团队精神,责任感和沟通能力。'),
(714, 76, '其他互联网/网络', 0, '', '', NULL),
(713, 76, '游戏设计/开发', 0, '', '', '岗位职责：\r\n1、负责游戏情节和具体细节的策划和设计工作；\r\n2、负责跟进游戏的最终表现效果；\r\n3、负责游戏的文字创意、流程设计等工作；\r\n4、负责协调程序员，原画设计人员完成游戏实现；\r\n5、负责进行市场调研、需求分析等，根据用户使用提出分析报告；\r\n6、负责指定时间机会和工作任务，并监督按时完成。\r\n\r\n岗位要求：\r\n1、专科及以上学历，1年游戏策划工作经验；\r\n2、精通photoshop创建和编辑各种图素，须有平面设计基础；\r\n3、熟悉游戏制作的流程及各个环节，熟悉游戏市场，对行业发展有清晰认识；\r\n4、有良好的逻辑思维能力，有优秀的创造力和想像力；\r\n5、有深厚的文字功底，具备良好的职业素养及团队合作精神。'),
(712, 76, '综合布线', 0, '', '', '岗位职责：\r\n1、负责弱电系统方案规划、设计和搭建，做出施工图纸，并以书面形式汇报、跟踪各项弱电建设进度，处理各种紧急情况； \r\n2、负责陪同销售去面对客户了解客户需求，做出前期设计调整来满足顾客对布线方面的要求； \r\n3、负责与用户沟通,详细了解需求,完成由设计到施工的管理，到用户工程竣工文档编制、提交的全过程管理。\r\n4、配合系统工程师完成系统集成项目后期的实施工作。\r\n\r\n岗位要求：\r\n1. 计算机相关专业，熟悉计算机及网络相关知识，熟练CAD制图；\r\n2. 具有弱电系统经验（主要是综合布线、通信机房设备安装/调试、计算机网络系统、安防系统等）；\r\n3. 熟悉弱电行业的相关规范和标准等，具备较强的项目及团队管理协调能力；\r\n4. 工作热情，责任心强，富有创新精神，具有良好的学习能力、分析问题和解决问题的能力； \r\n5. 人际沟通能力强，具有良好的团队协作精神。 \r\n6、能够执行公司业务流程及工程施工规范，责任心强；'),
(711, 76, '网络信息安全工程师', 0, '', '', '岗位职责：\r\n1、负责公司安全风险的评估、加固和审计；\r\n2、负责公司安全事件的分析和响应；\r\n3、负责公司安全策略的定制和应用；\r\n4、负责公司安全知识的研究和沉淀；\r\n5、为公司提供相应的安全技术培训。\r\n\r\n岗位要求：\r\n1、了解信息安全体系和安全标准（BS7799或ISO27001），对信息安全体系和安全风险评估有较全面的认识；\r\n2、熟悉各种路由器、防火墙、交换机、负载均衡等网络设备的选型、部署、维护、安全防范；\r\n3、掌握Windows、Linux等操作系统的系统安全策略和实施；\r\n4、熟悉相关网络安全产品，如AD域、防火墙、IDS、IPS、防病毒系统、漏洞评估工具、监控产品等；\r\n5、有相关的项目经验，对主流的安全产品比较熟悉，能编写技术类文档，了解各种攻击与防护技术。'),
(710, 76, '网络工程师', 0, '', '', NULL),
(709, 76, '网站编辑', 0, '', '', '岗位职责：\r\n1、负责网站相关栏目/频道的信息搜集、编辑、审校等工作；\r\n2、完成信息内容的策划和日常更新与维护；\r\n3、编写网站宣传资料及相关产品资料；\r\n4、收集、研究和处理网络读者的意见和反馈信息；\r\n5、配合责任编辑组织策划推广活动，并参与执行；\r\n6、协助完成频道管理与栏目的发展规划，促进网站知名度的提高；\r\n7、加强与内部相关部门和组织外部的沟通与协作。\r\n\r\n岗位要求：\r\n1、编辑、出版、新闻、中文等相关专业大专或以上学历；\r\n2、有媒体编辑领域从业经验者优先；\r\n3、熟练操作常用的网页制作软件和网络搜索工具，了解网站开发、运行及维护的相关知识；\r\n4、良好的文字功底，较强的网站专题策划和信息采编能力；\r\n5、较高的职业素养、敬业精神及团队精神，擅于沟通。'),
(708, 76, '网站策划', 0, '', '', '岗位职责：\r\n1、负责公司网站的各个板块内容的规划和设计\r\n2、负责公司网站内容的编辑及论坛等日常管理\r\n3、负责网站信息内容的更新和维护\r\n4、负责栏目资料和信息的搜集、整理\r\n5、负责网站信息内容的编辑、审校，保证信息内容的健康\r\n6、负责选取，撰写、摘录、转载各类站点相关文章\r\n7、协助主管策划网站和站点、频道页面及专题活动等\r\n\r\n岗位要求：\r\n1、本科及以上学历，新闻、中文、旅游相关专业\r\n2、具有较广的知识面，良好的文字编辑、写作能力\r\n3、熟悉电脑操作，熟悉Photoshop、Dreamweaver、Frontpage等软件工具\r\n4、熟悉HTML语言使用，掌握网络知识，有网站从业经验或旅游类媒体从业经验者优先\r\n5、积极向上，学习能力好，创新意识强，能够承受工作压力，工作责任心强，富有团队合作精神。'),
(707, 76, '网站优化/SEO', 0, '', '', NULL),
(706, 76, 'it项目经理', 0, '', '', NULL),
(705, 76, '产品经理/专员', 0, '', '', NULL),
(704, 76, '网站推广', 0, '', '', NULL),
(703, 76, '网站运营', 0, '', '', '岗位职责：\r\n1、负责网站的设计、建设及日常维护与更新；\r\n2、对网站系统数据库进行日常管理，统计数据库中相关信息；\r\n3、负责公司网站运营及公司品牌与业务推广；\r\n4、负责网络运行的安全性、可靠性及稳定性；\r\n5、维护公司与其它合作网站、电视、电台、报纸等媒体的合作关系；\r\n6、负责公司网站的链接、广告交换和网站层面的合作推广工作。\r\n\r\n岗位要求：\r\n1、计算机相关专业，本科以上学历；\r\n2、有一年以上目网站建设经验，有大型网站工作经验者优先考虑；\r\n3、熟练使用photoshop、flash、dreamweaver等工具，熟悉ASP，JAVA,SQL,HTML等开发语言；\r\n4、可以独立完成网站前后台工作，熟悉互联网B2B、B2C网站的运营及推广营销；\r\n5、良好的沟通能力及团队协作能力，富有责任心、学习能力强。'),
(702, 76, '网络管理员', 0, '', '', '岗位职责：\r\n1、负责内部局域网络维护；\r\n2、进行小型机、服务器、路由器等设备管理，以及网络平台的运行监控和维护；\r\n3、进行办公设备的日常维护及管理；技术档案维护；\r\n4、负责病毒的查杀，维护网络系统安全；\r\n5、处理网络及计算机故障；\r\n6、负责内部信息系统建设、维护；进行域名、后台数据、邮箱管理。\r\n\r\n岗位要求：\r\n1、计算机或IT相关专业，大学本科，25岁以下\r\n2、一年的网络管理、服务器网管工作经验；\r\n3、熟悉路由器，交换机、防火墙的网络设备的设置与管理；\r\n4、了解操作系统，熟悉WEB、FTP、MAIL服务器的架设；\r\n5、学习能力强，较好的沟通和协作能力，极强的执行力和沟通能力，具备良好的服务意识。'),
(701, 76, '互联网软件开发', 0, '', '', NULL),
(700, 76, '淘宝美工', 0, '', '', NULL),
(699, 76, '网页设计/美工', 0, '', '', '岗位职责：\r\n1、负责公司网站的设计、改版、更新；\r\n2、负责公司产品的界面进行设计、编辑、美化等工作；\r\n3、对公司的宣传产品进行美工设计；\r\n4、负责客户及系统内的广告和专题的设计；\r\n5、负责与开发人员配合完成所辖网站等前台页面设计和编辑；\r\n6、其他与美术设计相关的工作。\r\n\r\n岗位要求：\r\n1、美术、平面设计相关专业，专科及以上学历；\r\n2、两年以上网页设计及平面设计工作经验；\r\n3、有扎实的美术功底、良好的创意思维和理解能力，能及时把握客户需求；\r\n4、精通Photoshop\\Dreamweaver\\Illustrator等设计软件，对图片渲染和视觉效果有较好认识；\r\n5、善于与人沟通，良好的团队合作精神和高度的责任感，能够承受压力，有创新精神，保证工作质量；\r\n6、应聘时请务必提供个人作品。'),
(698, 75, '其他计算机应用', 0, '', '', NULL),
(697, 75, '电脑绘图', 0, '', '', '岗位职责：\r\n1、负责语音平台的维护；\r\n2、处理语音系统故障，负责语音系统升级；\r\n3、统计语音系统的运行数据；\r\n4、协助项目主管进行团队管理。\r\n\r\n岗位要求：\r\n1、有语音理论基础；\r\n2、熟悉Cisco语音相关的软硬件；\r\n3、有维护或建设Cisco语音平台经验；\r\n4、英语听说读写熟练；\r\n5、有意愿致力于各类技术的学习与实践，有Cisco语音方向相关认证者优先。'),
(696, 75, '多媒体设计', 0, '', '', '岗位职责：\r\n1、负责项目产品的图文、动画媒体的策划设计工作；\r\n2、负责宣传片、片头及片中动画的创意和制作；\r\n3、对作品的音乐选择提出建议和意见；\r\n4、指导、培训、培养美术类人员的设计、制作工作；\r\n6、负责制定平面和动画制作类相关标准、流程规定及创新工作。\r\n\r\n岗位要求：\r\n1、视觉艺术设计或美术相关设计专业毕业，大专及以上学历；\r\n2、两年以上平面、动画设计经验，有多媒体行业从业经验为优；\r\n3、熟练使用相关多媒体设计及后期编辑软件，并熟练掌握动画脚本语言；\r\n4、创作欲望强，创意构思独特、表现力强、具有良好的团队协作精神；\r\n5、极强的界面设计和动画计策划能力，较好的手绘能力，了解分镜头绘制方法\r\n7、具备创意制作过程的沟通领悟能力。'),
(695, 75, '电脑操作员/打字员', 0, '', '', NULL),
(694, 75, '系统管理员/网管', 0, '', '', NULL),
(684, 52, '其他保险', 0, '', '', '岗位职责：\r\n1、负责银行的日常业务操作，会计帐务处理等工作；\r\n2、负责银行营业部财务费用会计核算；\r\n3、负责开具银行收付凭证,及时付款,凭证电脑输入等工作；\r\n4、负责纳税申报、会计凭证和帐册的装订等工作；\r\n5、负责银行日常凭证的事后监督和整理保管；\r\n6、负责银行日常财务报表的编制和勾对检查；\r\n7、负责银行营业部资金清算后台处理和核算；\r\n\r\n岗位要求：\r\n1、专科及以上学历，财务、会计等相关专业；\r\n2、熟悉中国税务法律，申报企业各类税；\r\n3、熟悉资产负债表及损益表等财务报表的编制；\r\n4、具有2年以上银行财务经验，有会计证者优先；\r\n5、熟练掌握OFFICE等办公软件和财务软件；\r\n6、具有很强的责任心，工作细心，认真。'),
(683, 52, '保险代理人/经纪人', 0, '', '', '岗位职责：\r\n1、负责银行的日常业务操作，会计帐务处理等工作；\r\n2、负责银行营业部财务费用会计核算；\r\n3、负责开具银行收付凭证,及时付款,凭证电脑输入等工作；\r\n4、负责纳税申报、会计凭证和帐册的装订等工作；\r\n5、负责银行日常凭证的事后监督和整理保管；\r\n6、负责银行日常财务报表的编制和勾对检查；\r\n7、负责银行营业部资金清算后台处理和核算；\r\n8、负责银行营业部内控制度的制订和检查工作\r\n\r\n岗位要求：\r\n1、专科及以上学历，财务、会计等相关专业；\r\n2、熟悉中国税务法律，申报企业各类税；\r\n3、熟悉资产负债表及损益表等财务报表的编制；\r\n4、具有2年以上银行财务经验，有会计证者优先；\r\n5、熟练掌握OFFICE等办公软件和财务软件；\r\n6、具有很强的责任心，工作细心，认真。'),
(682, 52, '保险精算/研发/培训', 0, '', '', '岗位职责：\r\n1、负责银行的日常业务操作，会计帐务处理等工作；\r\n2、负责银行营业部财务费用会计核算；\r\n3、负责开具银行收付凭证,及时付款,凭证电脑输入等工作；\r\n4、负责纳税申报、会计凭证和帐册的装订等工作；\r\n5、负责银行日常凭证的事后监督和整理保管；\r\n6、负责银行日常财务报表的编制和勾对检查；\r\n7、负责银行营业部资金清算后台处理和核算；\r\n8、负责银行营业部内控制度的制订和检查工作。\r\n\r\n岗位要求：\r\n1、专科及以上学历，财务、会计等相关专业；\r\n2、熟悉中国税务法律，申报企业各类税；\r\n3、熟悉资产负债表及损益表等财务报表的编制；\r\n4、具有2年以上银行财务经验，有会计证者优先；\r\n5、熟练掌握OFFICE等办公软件和财务软件；\r\n6、具有很强的责任心，工作细心，认真。'),
(681, 52, '保险内勤', 0, '', '', '岗位职责：\r\n1、负责银行的日常业务操作，会计帐务处理等工作；\r\n2、负责银行营业部财务费用会计核算；\r\n3、负责开具银行收付凭证,及时付款,凭证电脑输入等工作；\r\n4、负责纳税申报、会计凭证和帐册的装订等工作；\r\n5、负责银行日常凭证的事后监督和整理保管；\r\n6、负责银行日常财务报表的编制和勾对检查；\r\n7、负责银行营业部资金清算后台处理和核算；\r\n8、负责银行营业部内控制度的制订和检查工作。\r\n\r\n岗位要求：\r\n1、专科及以上学历，财务、会计等相关专业；\r\n2、熟悉中国税务法律，申报企业各类税；\r\n3、熟悉资产负债表及损益表等财务报表的编制；\r\n4、具有2年以上银行财务经验，有会计证者优先；\r\n5、熟练掌握OFFICE等办公软件和财务软件；\r\n6、具有很强的责任心，工作细心，认真。'),
(680, 52, '客户服务/续期管理', 0, '', '', '岗位职责：\r\n1、负责银行的日常业务操作，会计帐务处理等工作；\r\n2、负责银行营业部财务费用会计核算；\r\n3、负责开具银行收付凭证,及时付款,凭证电脑输入等工作；\r\n4、负责纳税申报、会计凭证和帐册的装订等工作；\r\n5、负责银行日常凭证的事后监督和整理保管；\r\n6、负责银行日常财务报表的编制和勾对检查；\r\n7、负责银行营业部资金清算后台处理和核算；\r\n8、负责银行营业部内控制度的制订和检查工作。\r\n\r\n岗位要求：\r\n1、专科及以上学历，财务、会计等相关专业；\r\n2、熟悉中国税务法律，申报企业各类税；\r\n3、熟悉资产负债表及损益表等财务报表的编制；\r\n4、具有2年以上银行财务经验，有会计证者优先；\r\n5、熟练掌握OFFICE等办公软件和财务软件；\r\n6、具有很强的责任心，工作细心，认真。'),
(679, 52, '核保理赔', 0, '', '', '岗位职责：\r\n1、负责银行的日常业务操作，会计帐务处理等工作；\r\n2、负责银行营业部财务费用会计核算；\r\n3、负责开具银行收付凭证,及时付款,凭证电脑输入等工作；\r\n4、负责纳税申报、会计凭证和帐册的装订等工作；\r\n5、负责银行日常凭证的事后监督和整理保管；\r\n6、负责银行日常财务报表的编制和勾对检查；\r\n7、负责银行营业部资金清算后台处理和核算；\r\n8、负责银行营业部内控制度的制订和检查工作。\r\n\r\n岗位要求：\r\n1、专科及以上学历，财务、会计等相关专业；\r\n2、熟悉中国税务法律，申报企业各类税；\r\n3、熟悉资产负债表及损益表等财务报表的编制；\r\n4、具有2年以上银行财务经验，有会计证者优先；\r\n5、熟练掌握OFFICE等办公软件和财务软件；\r\n6、具有很强的责任心，工作细心，认真。'),
(678, 52, '理财顾问/财务规划师', 0, '', '', NULL),
(677, 52, '保险业务经理/主管', 0, '', '', '岗位职责：\r\n1、负责银行的日常业务操作，会计帐务处理等工作；\r\n2、负责银行营业部财务费用会计核算；\r\n3、负责开具银行收付凭证,及时付款,凭证电脑输入等工作；\r\n4、负责纳税申报、会计凭证和帐册的装订等工作；\r\n5、负责银行日常凭证的事后监督和整理保管；\r\n6、负责银行日常财务报表的编制和勾对检查；\r\n7、负责银行营业部资金清算后台处理和核算；\r\n8、负责银行营业部内控制度的制订和检查工作。\r\n\r\n岗位要求：\r\n1、专科及以上学历，财务、会计等相关专业；\r\n2、熟悉中国税务法律，申报企业各类税；\r\n3、熟悉资产负债表及损益表等财务报表的编制；\r\n4、具有2年以上银行财务经验，有会计证者优先；\r\n5、熟练掌握OFFICE等办公软件和财务软件；\r\n6、具有很强的责任心，工作细心，认真。'),
(676, 51, '其他证券/期货/投资/银行', 0, '', '', ''),
(675, 51, '银行卡/电子银行推广', 0, '', '', ''),
(674, 51, '银行柜员/会计', 0, '', '', ''),
(673, 51, '资产评估/分析', 0, '', '', ''),
(672, 51, '信贷管理/资信评估', 0, '', '', ''),
(671, 51, '风险控制', 0, '', '', ''),
(670, 51, '拍卖/典当/租赁/担保', 0, '', '', ''),
(669, 51, '融资经理/专员', 0, '', '', ''),
(668, 51, '投资银行业务', 0, '', '', ''),
(667, 51, '投资/基金项目经理', 0, '', '', ''),
(666, 51, '投资/理财顾问', 0, '', '', ''),
(648, 22, '其他行政/后勤', 0, '', '', NULL),
(647, 22, '技术资料编写/管理', 0, '', '', NULL),
(646, 22, '电脑操作员/打字员', 0, '', '', NULL),
(645, 22, '图书资料/文档管理', 0, '', '', NULL),
(644, 22, '内勤/后勤', 0, '', '', '岗位职责：\r\n1、本科以上学历，企业行政经理工作经验两年以上；\r\n2、固定资产管理经验、资产管理合同管理、或有大型企业材料库管经验；\r\n3、有一定财务经验；\r\n4、较强的责任心和敬业精神，良好的组织协调能力及沟通能力，较强的分析、解决问题能力；\r\n5、熟练使用办公软件和办公自动化设备。\r\n\r\n岗位要求：\r\n1、1年以上行政主管工作经验；\r\n2、熟悉行政工作流程，办公用品采购流程，企业资产管理；\r\n3、较强的责任心和敬业精神，良好的组织协调能力及沟通能力，较强的分析、解决问题能力；\r\n4、熟练使用办公软件和办公自动化设备。'),
(643, 22, '前台/接待', 0, '', '', NULL),
(642, 22, '经理助理/秘书', 0, '', '', NULL),
(641, 22, '文员', 0, '', '', '岗位职责：\r\n1、形象好，气质佳，年龄在20-30岁，女士优先；\r\n2、1年以上相关工作经验，文秘、行政管理等相关专业优先考虑；\r\n3、熟悉办公室行政管理知识及工作流程，具备基本商务信函写作能力及较强的书面和口头表达能力；\r\n4、熟悉公文写作格式，熟练运用OFFICE等办公软件；\r\n5、工作仔细认真。\r\n\r\n岗位要求：\r\n1、女士优先，形象好，气质佳，年龄18—24岁，身高1.65以上；\r\n2、大专及以上学历，1年相关工作经验，文秘、行政管理等相关专业优先考虑；\r\n3、较强的服务意识，熟练使用电脑办公软件；\r\n4、具备良好的协调能力、沟通能力，负有责任心，性格活泼开朗，具有亲和力；\r\n5、普通话准确流利；\r\n6、具备一定商务礼仪知识。'),
(640, 22, '办公室主任', 0, '', '', NULL),
(639, 22, '行政专员/助理', 0, '', '', '岗位职责：\r\n1、起草和修改报告、文稿等；\r\n2、及时准确的更新员工通讯录；管理公司网络、邮箱；\r\n3、负责日常办公用品采购、发放、登记管理，办公室设备管理；\r\n4、订阅年度报刊杂志，收发日常报刊杂志及交换邮件；\r\n5、员工考勤系统维护、考勤统计及外出人员管理\r\n6、保证前台所需物资的充足（如水、纸、设备、耗材及报销单据表格等）及费用结算。\r\n\r\n岗位要求：\r\n1、文秘、行政管理等相关专业中专以上学历；\r\n2、二年以上相关工作经验；\r\n3、熟悉办公室行政管理知识及工作流程，熟悉公文写作格式，具备基本商务信函写作能力，熟练运用OFFICE等办公软件；\r\n4、工作仔细认真、责任心强、为人正直，具备较强的书面和口头表达能力；\r\n5、形象好，气质佳，年龄在20-30岁，女性优先。'),
(613, 7, '其他贸易/采购', 0, '', '', NULL),
(612, 7, '采购员', 0, '', '', '岗位职责：\r\n1、执行采购订单和采购合同，落实具体采购流程；\r\n2、负责采购订单制作、确认、安排发货及跟踪到货日期；\r\n3、执行并完善成本降低及控制方案；\r\n4、开发、评审、管理供应商，维护与其关系；\r\n5、填写有关采购表格，提交采购分析和总结报告；\r\n6、完成采购主管安排的其它工作。\r\n\r\n岗位要求：\r\n1、中专及以上学历，XX类相关专业；\r\n2、xx行业1年以上相关工作经验；\r\n3、熟悉采购流程，良好的沟通能力、谈判能力和成本意识；\r\n4、工作细致认真，责任心强，思维敏捷，具有较强的团队合作精神，英语能力强者优先考虑；\r\n5、有良好的职业道德和素养，能承受一定工作压力。'),
(611, 7, '采购经理/主管', 0, '', '', '岗位职责：\r\n1、负责公司采购工作，包括：询价、比价、签定采购合同、验收、评估及反馈汇总工作；\r\n2、调查、分析和评估目标市场，确定需要和采购时机；\r\n3、完善公司采购制度，制定并优化采购流程，控制采购质量与成本；\r\n4、组织对供应商进行评估、认证、管理及考核；\r\n5、制订部门的短、中、长期工作计划，编制并提交部门预算；\r\n6、负责采购人员的岗前培训和在岗培训，并组织考核；\r\n7、协调公司各部门间工作。\r\n\r\n岗位要求：\r\n1、大学本科及以上学历，管理类、物流类相关专业；\r\n2、xx行业3年以上相关采购行业管理工作经验，有外资企业采购经理工作经历者优先；\r\n3、熟悉招标和采购流程，熟悉供应商评估、考核，熟悉相关质量体系标准；\r\n4、具备良好部门内和跨部门的组织和协调能力，良好的谈判、人际沟通能力，团队协作能力强；\r\n5、具备较强职业道德素质。'),
(610, 7, '单证员', 0, '', '', '岗位职责：\r\n1、负责贸易开证和收证初审，收单和制单的初审和制作，交单等单据结算的全过程执行和跟踪，确保单证的质量及各项条款的正确性；\r\n2、负责商务函电、合同的翻译与归档工作；\r\n3、联系沟通客户，及时反馈客户定单情况；\r\n4、上级交办的其他相关工作。\r\n\r\n岗位要求：\r\n1、大专及以上学历，国际贸易、商务英语类相关专业；\r\n2、2年以上贸易单证相关领域工作经验、有外企相关工作经历者优先考虑；\r\n3、熟悉外贸单证操作流程，熟悉信用证及相关知识，有一定财务基础知识者优先考虑；\r\n4、熟练的英文口头及书面表达技巧，熟练操作常用办公软件；\r\n5、工作作风细致、严谨，有较强的工作热情和责任感。'),
(609, 7, '报检员', 0, '', '', NULL),
(608, 7, '报关员', 0, '', '', '岗位职责：\r\n1、负责进出口货品的报关、查验及各种单证的审核和缮制；\r\n2、负责协调海关,商检与公司内部清关事宜的联络；\r\n3、核销单及相应文件及时收回；\r\n4、货物需装柜时到场监装，如遇海关查货，应到场或委托报关行监督整理货物，重新封箱；\r\n5、完成工作相关报表。\r\n\r\n岗位要求：\r\n1、大专及以上学历，国际贸易、物流类相关专业；\r\n2、一年以上相关领域工作经验，有外企相关领域工作经历者优先考虑；\r\n3、熟悉相关报关流程，熟悉相应的国家法律法规，持有报关员资格证者优先考虑；\r\n4、具备一定的沟通及处理问题的能力，具有一定英语能力；\r\n5、性格开朗、为人正直，工作细心负责。'),
(607, 7, '业务跟单', 0, '', '', '岗位职责：\r\n1、负责出口订单的跟踪、单证、物流、以及跟单工作中涉及的各项内容；\r\n2、协调出口运输以及与货代，船公司之间的联络；\r\n3、客户的业务联系沟通；\r\n4、配合财务做好核销，对帐工作；\r\n5、负责出口统计和其它相关工作。\r\n\r\n岗位要求：\r\n1、大专及以上学历，国际贸易、商务英语、销售类相关专业；\r\n2、2年以上外贸跟单相关领域工作经验，有外企相关领域工作经验者优先考虑；\r\n3、熟悉进出口业务跟单操作流程，熟悉货物运输安排及相关注意事项；\r\n4、良好的英语听说读写能力，熟练的计算机应用技巧，较强的沟通表达能力，独立处理工作能力强；\r\n5、诚实敬业，有强烈的抗压性和责任心，团队精神佳。'),
(606, 7, '国内贸易专员', 0, '', '', NULL),
(605, 7, '国际贸易专员', 0, '', '', '岗位职责：\r\n1、执行公司的贸易业务，实施贸易规程，开拓市场；\r\n2、负责联系客户、编制报价、参与商务谈判，签订合同；\r\n3、负责生产跟踪、发货、现场监装；\r\n4、负责单证审核、报关、结算、售后服务等工作；\r\n5、客户的拓展与维护；\r\n6、业务相关资料的整理和归档；\r\n7、相关业务工作的汇报。\r\n\r\n岗位要求：\r\n1、大专及以上学历，国际贸易、商务英语类相关专业；\r\n2、2年以上贸易领域业务操作经验，有外企工作经历者优先考虑；\r\n3、熟悉贸易操作流程及相关法律法规，具备贸易领域专业知识；\r\n4、具有较高的英语水平，较好的计算机操作水平，有报关证等相关贸易操作证书者优先考虑；\r\n5、具有良好的业务拓展能力和商务谈判技巧，公关意识强，具有较强的事业心、团队合作精神和独立处事能力，勇于开拓和创新。'),
(604, 7, '国际贸易经理/主管', 0, '', '', '岗位职责：\r\n1、组织建立贸易部，制定部门工作计划及相关预算，全面主持贸易部的日常管理工作；\r\n2、制定并规划进出口业务流程，收集、分析行业重要信息数据、积极开拓国内外市场；\r\n3、负责签订大宗贸易合同及监督合同执行，处理相关商务事宜；\r\n4、重要客户的接洽联络、关系维护；\r\n5、负责贸易部人员的评估、考核、培训、奖惩等工作。\r\n\r\n岗位要求：\r\n1、大学本科及以上学历，国际贸易类相关专业；\r\n2、5年以上进出口业务管理工作经验，有外企相关领域工作经历者优先考虑；\r\n3、熟悉进出口业务流程，熟悉外贸进出口法律条规，具备贸易管理专业知识和相关技能；\r\n4、具有优秀的英文听、说、读、写能力，熟悉使用办公软件；\r\n5、具备优秀的组织管理能力，良好的沟通和谈判技巧，良好的创新意识、团队合作能力及服务意识，责任心强。'),
(603, 6, '其他客服/技术支持', 0, '', '', NULL),
(602, 6, '客户培训师', 0, '', '', NULL),
(601, 6, '客户关系管理', 0, '', '', '岗位职责：\r\n1、监控和维护CRM系统，指导业务员和销售行政录入和管理客户信息数据；\r\n2、充分了解客户需求细分客户类型，进行分级服务管理，分析并挖掘潜在客户及重点客户；\r\n3、根据销售、服务等业务需求处理数据，提供有效报告；\r\n4、监督销售员客户覆盖行动情况，及时反馈其各级主管并推动工作；\r\n\r\n岗位要求：\r\n1、相关工作经验2年以上；\r\n2、良好的沟通及语言表达能力，有独立分析、思考解决问题的能力；\r\n3、能承受较强的工作压力，有良好的学习能力；\r\n4、有效进行沟通和协作的能力，组织能力/应变能力较强；\r\n5、熟练应用Office办公软件；\r\n6、为人正直善良，踏实勤奋，有CRM相关工作经验者优先。'),
(600, 6, '售后技术支持', 0, '', '', '岗位职责：\r\n1、了解客户服务需求信息，进行有效跟踪，做好售前、售后指导和服务工作； \r\n1、熟练运用公司产品，解答客户提问并落实问题； \r\n3、与相关部门紧密配合，协调沟通；\r\n5、维护客户关系，并开发新客户\r\n\r\n岗位要求：\r\n1、至少1年以上销售或客服工作经验；\r\n2、具备敏锐的商业意识，较强的应变能力、口头表达与沟通能力；\r\n3、有较强的推广和维护协调客户的能力，熟悉客户服务流程；\r\n4、具备较强的学习能力，可快速掌握专业知识，及时开展工作；\r\n5、熟练运用office及良好的文档写作能力；\r\n6、工作严谨，计划性强，善于分析思考问题，有责任心；'),
(599, 6, '售前技术支持', 0, '', '', '岗位职责：\r\n1、了解客户服务需求信息，进行有效跟踪，做好售前、售后指导和服务工作； \r\n1、熟练运用公司产品，解答客户提问并落实问题； \r\n3、与相关部门紧密配合，协调沟通；\r\n4、维护客户关系，并开发新客户\r\n\r\n岗位要求：\r\n1、至少1年以上销售或客服工作经验；\r\n2、具备敏锐的商业意识，较强的应变能力、口头表达与沟通能力；\r\n3、有较强的推广和维护协调客户的能力，熟悉客户服务流程；\r\n4、具备较强的学习能力，可快速掌握专业知识，及时开展工作；\r\n5、熟练运用office及良好的文档写作能力；\r\n6、工作严谨，计划性强，善于分析思考问题，有责任心；\r\n7、勤奋踏实，良好的服务意识与团队合作精神。'),
(598, 6, '淘宝客服', 0, '', '', NULL),
(597, 6, '网络客服', 0, '', '', NULL),
(596, 6, '客服专员', 0, '', '', '岗位职责：\r\n1、受理及主动电话客户，能够及时发现客户问题并给到正确和满意的回复；\r\n2、与客户建立良好的联系，熟悉及挖掘客户需求，并对客户进行系统的应用培训；\r\n3、具备处理问题、安排进展、跟进进程、沟通及疑难问题服务的意识跟能力，最大限度的提高客户满意度。遇到不能解决的问题按流程提交相关人员或主管处理，并跟踪进展直至解决；\r\n4、具备一定的销售能力，针对公司现有的客户进行营销，让客户接受更为广泛的网络产品，达到最好的网络营销的效果。\r\n5、不断接受公司的各项业务和技能提升培训。\r\n\r\n岗位要求：\r\n1、专科学历，有一定客户服务工作经验或销售经验，有一定的客户服务知识和能力 。\r\n2、计算机操作熟练，office办公软件使用熟练，有一定的网络知识基础，熟练使用Photoshop等制图工具者优先考虑。\r\n3、要求一定要有“客户为先”的服务精神，一切从帮助客户、满足客户角度出发。 \r\n4、性格要求沉稳、隐忍，善于倾听，有同理心，乐观、积极。普通话标准、流利，反应灵敏。 \r\n5、热爱工作，敬业、勤恳，乐于思考，具有自我发展的主观愿望和自我学习能力。可适当加班者优先。'),
(595, 6, '客服经理/主管', 0, '', '', NULL),
(594, 6, '呼叫中心/电话客服', 0, '', '', '岗位职责：\r\n1、根据公司提供的客户电话，向客户推广公司的产品服务； \r\n2、负责接听客户热线，为客户讲解、推广产品；\r\n3、通过电话负责客户的约访工作；\r\n4、协助配合销售团队，创造销售业绩。\r\n\r\n岗位要求：\r\n1、声音甜美，普通话标准，沟通表达能力佳；\r\n2、熟练操作办公自动化设备及OFFICE软件；\r\n3、良好的执行力和团队合作精神；\r\n4、熟悉电话销售或客户服务的业务模式，有电话销售经验者优先。'),
(593, 5, '其他市场/策划/公关', 0, '', '', NULL),
(592, 5, '婚礼/庆典策划', 0, '', '', NULL),
(591, 5, '媒介经理/专员', 0, '', '', '岗位职责：\r\n1、负责联络、拓展及维护媒体关系及合作机构关系，进行媒体资源整合和维护；\r\n2、对媒体进行信息发布监测，并定期形成监测报告，比如传播稿件搜集并制作项目剪报等；\r\n3、策划并执行与媒体合作项目的推广活动，以及对外合作的执行；\r\n4、进行竞争对手公关传播情况调研分析，定期出具调研报告；\r\n5、邀约媒体人员参加各种类型的活动等。\r\n\r\n岗位要求：\r\n1、本科以上学历，公关、新闻传播学、市场营销等相关专业优先；\r\n2、两年以上公关公司或行业工作经验，有互联网行业从业经验者优先；\r\n3、能够独立组织新闻发布会、专访及其它公关活动；\r\n4、拥有丰富的媒体资源，能够熟练撰写公关所需文件；\r\n5、优秀的语言表达能力，能够在公关活动中进行富于感染力的演讲；\r\n6、较强的观察力和应变能力，优秀的人际交往和协调能力，较强的社会活动能力；'),
(590, 5, '会务经理/专员', 0, '', '', '岗位职责：\r\n1、负责审核合作单位的会展计划，协调会展项目；\r\n2、处理展会市场推广过程的具体事务，并协助完成活动组织与沟通协调的具体工作；\r\n3、协助主管进行活动方案的制定，包括协调整合各方资源，确认和执行活动方案，对活动全流程进行组织管理；\r\n4、对展会公司进行深入合作，推动各地销售。\r\n\r\n岗位要求：\r\n1、广告传媒、市场营销及相关专业专科及以上学历；\r\n2、两年以上展览展示相关工作经验，有大型展会实战工作经验者优先；\r\n3、熟悉会务、展览、执行流程；\r\n4、具有一定的项目管理能力，对所辖行业有认知；\r\n5、具有优秀的沟通技巧和人际交往能'),
(589, 5, '公关专员', 0, '', '', '岗位职责：\r\n1、负责公关客户的开发及维护；\r\n2、服从直属领导的安排，与市场部同事协同工作，、高效优质地完成所负责客户日常服务工作；\r\n3、负责公司对外重要活动的接待及公关工作；\r\n4、负责公司产品的讲解及介绍。\r\n\r\n岗位要求：\r\n1、1年以上公关行业经验，新闻传播、公共关系、中文、市场营销等相关专业专科及以上学历；\r\n2、普通话标准，言行举止大方得体；\r\n3、敏锐的洞察力，极佳的沟通能力，较强学习能力，协调能力和团队精神；\r\n4、能承受较大工作压力，适应不定期加班。'),
(588, 5, '公关经理/主管', 0, '', '', '岗位职责：\r\n1、主持制定和执行市场公关计划，配合公司项目策划公部门对外的各项公关活动；\r\n2、定期提交公关活动报告并对市场整体策略提供建议；\r\n3、开展公众关系调查，并及时调整公关宣传政策；\r\n4、策划主持重要的公关专题活动，协调处理各方面的关系；\r\n5、参与制定及实施公司新闻传播计划，实施新闻宣传的监督和效果评估；\r\n6、提供市场开拓及促销、联盟、展会、现场会等方面的公关支持，协助接待公司来宾；\r\n7、建立和维护公共关系数据库、公关文档。\r\n\r\n岗位要求：\r\n1、专科以上学历，公关、新闻传播学、市场营销等相关专业优先；\r\n2、熟悉_____行业，两年以上大、中型企业公关、市场工作经验或公关公司工作经验；\r\n3、对企业文化的提炼与传播和公共关系的建立与维护有较为深刻的理解；\r\n4、具有承办庆典、新闻发布会等重大活动的工作经验；\r\n5、良好的文字功底，具有较强的组织能力、协调能力和资源整合力；\r\n6、思维敏捷、善于沟通，亲和力强，形象气质佳，具有良'),
(587, 5, '促销经理/主管', 0, '', '', NULL),
(586, 5, '市场督导', 0, '', '', NULL),
(585, 5, '活动策划/执行', 0, '', '', NULL),
(584, 5, '品牌经理/专员', 0, '', '', '岗位职责：\r\n1、协助品牌主管实施企业的品牌推广计划；\r\n2、建立并维护异业合作伙伴关系及相关宣传和策划活动；\r\n3、进行产品市场推广的策划和实施,并对推广效果进行跟踪；\r\n4、定期分析市场情况,并提出有效推广的建议。\r\n\r\n岗位要求：\r\n1、市场营销、管理类、广告类或相关专业专科以上学历；\r\n2、具有_______行业的从业背景，有品牌专员工作经验者优先；\r\n3、有较好的综合素质及文化修养；\r\n4、诚实勤奋，有良好的沟通及协调能力，较强的执行能力；\r\n5、具有亲和力，敬业、有团队合作精神；\r\n6、熟练操作OFFICE软件。'),
(583, 5, '市场调研/业务分析', 0, '', '', '岗位职责：\r\n1、协助经理制订市场发展计划并负责各类专项调研的组织和执行；\r\n2、负责与国内各主流调研机构的沟通，并根据原始数据和经理的要求输出各类分析报告。\r\n3、结合各方面意见形成信调报告的评估文档，作出相应总结分析，并形成经验积累；\r\n4、负责市场活动、服务或信息供应商的统一管理和评估、；\r\n5、完成相关统计分析或进度报表；\r\n6、收集竞争对手或产品的各种信息，如策略、活动、推广资料等，负责进行市场调研。\r\n\r\n岗位要求：\r\n1、市场营销管理类或相关专业本科以上学历；\r\n2、具有_______行业的从业背景，有专业从事市场调研工作两年以上经验者优先；\r\n3、熟悉产品结构调整与产品运作操作，具有发散性思维和创新意识；\r\n4、善于思考，具备良好的应变能力、沟通协调能力和文字组织能力；\r\n5、具有敏锐的洞察力和一定的分析判断能力；\r\n6、积极主动，性格开朗，能够建立较为广泛的行业内人脉关系。'),
(582, 5, '市场拓展', 0, '', '', NULL),
(581, 5, '市场策划经理/专员', 0, '', '', '岗位职责：\r\n1、负责商业情报的收集及信息平台的规划，研究市场的宏观方面的信息，包含市场动态、竞争品牌动向、产品与市场信息；\r\n2、参与制定年、季、月度市场推广方案并督导、执行；\r\n3、独立完成广告策划方案、品牌推广方案、方案设计报告的撰写；\r\n4、协调公司内部的运作实施，并完成品牌、产品推广的效果评估，提出改进方案。\r\n\r\n岗位要求：\r\n1、市场营销管理类或相关专业专科以上学历；\r\n2、具有_______行业的从业背景，有市场策划工作经验；\r\n3、优秀的文案功底，有较强的创造性思维能力、创意概念及良好的沟通能力；\r\n4、了解市场动态，依据市场变化适时策划制定整体促销方案，策划定期的促销活动；\r\n5、有一定的组织实施经验，监督、指导、落实促销活动的执行，有成功的策划案例者优先；\r\n6、有综合运用包括广告策划、软文宣传、公关活动等在内的各种营销方式进行市场宣传、品牌推广的能力；\r\n7、熟练操作办公软件。'),
(580, 5, '市场营销', 0, '', '', NULL),
(579, 5, '市场专员/助理', 0, '', '', '岗位职责：\r\n1、协助销售组织展开市场运作：与销售紧密配合，执行相关产品的市场营销活动计划，并做出相应的分析与反馈；\r\n2、在市场部经理的指导下，传达产品终端陈列、展示模式，并给予培训和指导；\r\n3、负责产品广告和促销计划的执行、跟踪和反馈及促销用品使用的执行和监督；\r\n4、了解、分析、反馈市场竞争情况，协调、处理所负责产品的突发事件；\r\n5、协助展开市场调查、区域市场自愿组织、政府事务等所有市场部职能事务的协调、执行和管理；\r\n6、监控主要市场活动的投入产出情况，准备并提供行业市场数据的处理及分析；\r\n7、协助区域负责人完成市场计划。\r\n\r\n岗位要求：\r\n1、大学本科以上学历，市场营销、计算机、管理类等相关专业优先；\r\n2、熟悉_______行业，一年以上相关工作经验，具有产品市场专员从业经验者优先；\r\n3、具备较强的中英文字表达能力，文档组织编写能力；\r\n4、熟练使用MS Office软件编写产品文档、产品演示文稿和进行数据分析；\r\n5、具有较强的规划、分析能力和创新意识，对产品和数据运营敏感 , 思维清晰而有条理；\r\n6、良好的沟通、协调能力，表达能力强，突出的执行能力；\r\n7、良好的职业素质和敬业精神。'),
(578, 5, '市场经理/主管', 0, '', '', '岗位职责：\r\n1、负责进行公司市场战略规划，制定公司的市场总体工作计划，提出市场推广、品牌、公关、活动等方面的具体方向和实施方案；\r\n2、组织和监督实施年度市场推广计划；\r\n3、进行市场调研与分析，研究同行、业界发展状况，定期进行市场预测及情报分析，为公司决策提供依据；\r\n4、制定公司整体公关策略及危机公关的应对处理；\r\n5、建立完善市场部工作流程以及制度规范；\r\n6、制定市场推广费用预算及市场部全年整体财务预算制定、控制以及完善激励考核制度；\r\n7、管理市场团队，并对团队成员和相关部门进行市场培训和指导。\r\n\r\n岗位要求：\r\n1、市场营销管理类或相关专业本科以上学历；\r\n2、五年市场营销工作经验，在相关企业任职市场总监三年以上，具有市场策划行业的从业背景，对该领域发展有深刻理解；\r\n3、具备很强的策划能力，熟悉各类媒体运作方式，有大型市场活动推广成功经验；\r\n4、具有敏感的商业和市场意识，分析问题及解决问题能力强，具有优秀的资源整合能力和业务推进能力；\r\n5、具备良好的沟通合作技巧及丰富的团队建设经验。'),
(577, 5, '市场总监', 0, '', '', NULL),
(576, 4, '其他销售行政及商务', 0, '', '', NULL),
(575, 4, '市场督导', 0, '', '', NULL),
(574, 4, '电子商务', 0, '', '', NULL),
(573, 4, '商务代表', 0, '', '', '岗位职责：\r\n1、负责整理客户资料；\r\n2、接受客户订单，制作销售订单，并与财务对接；\r\n3、负责联络沟通客户；\r\n4、协助主管完善部门规章制度和操作流程与规范，做好销售的后台支持；\r\n\r\n岗位要求：\r\n1、大专及以上学历，国际贸易、商务英语类专业；\r\n2、1年以上商务相关领域工作经验；\r\n3、有商务推广、策划方面的经验；\r\n4、英语及计算机运用熟练，有一定财务知识者优先考虑；'),
(572, 4, '商务专员/助理', 0, '', '', '岗位职责：\r\n1、负责整理客户资料；\r\n2、接受客户订单，制作销售订单，并与财务对接；\r\n3、负责联络沟通客户；\r\n4、协助主管完善部门规章制度和操作流程与规范，做好销售的后台支持；\r\n6、相关的销售协议、合同等存档管理。\r\n\r\n岗位要求：\r\n1、大专及以上学历，国际贸易、商务英语类专业；\r\n2、1年以上商务相关领域工作经验；\r\n3、有商务推广、策划方面的经验；\r\n4、英语及计算机运用熟练，有一定财务知识者优先考虑；\r\n6、良好的语言表达及较强的沟通能力，工作认真细致，积极进取，善于学习与创新。'),
(571, 4, '商务经理/主管', 0, '', '', '岗位职责：\r\n1、负责整理客户资料；\r\n2、接受客户订单，制作销售订单，并与财务对接；\r\n3、负责联络沟通客户；\r\n4、协助主管完善部门规章制度和操作流程与规范，做好销售的后台支持；\r\n5、相关的销售协议、合同等存档管理。\r\n\r\n岗位要求：\r\n1、大专及以上学历，国际贸易、商务英语类专业；\r\n2、1年以上商务相关领域工作经验；\r\n3、有商务推广、策划方面的经验；\r\n4、英语及计算机运用熟练，有一定财务知识者优先考虑；\r\n5、良好的语言表达及较强的沟通能力，工作认真细致，积极进取，善于学习与创新。'),
(570, 4, '销售行政人员', 0, '', '', '岗位职责：\r\n1、负责公司销售合同等文件资料的管理、归类、整理、建档和保管；\r\n2、负责各类销售指标的月度、季度、年度统计报表和报告的制作、编写，并随时汇报销售动态；\r\n3、负责收集、整理、归纳市场行情，提出分析报告；\r\n4、协助销售经理做好电话来访工作，在销售人员缺席时及时转告客户信息，妥善处理；\r\n5、协助销售经理做好部门内务、各种内部会议的记录等工作。\r\n\r\n岗位要求：\r\n1、专科以上学历，形象气质佳；\r\n2、从事过销售助理或统计类工作者优先考虑；\r\n3、做事认真、细心、负责；\r\n4、熟练使用office等办公软件；\r\n5、具有服务意识，能适应较大的工作压力；\r\n6、机敏灵活，具有较强的沟通协调能力。'),
(569, 4, '销售助理', 0, '', '', NULL),
(568, 4, '销售行政经理/主管', 0, '', '', NULL),
(567, 3, '其他销售人员', 0, '', '', NULL),
(566, 3, '导购员/促销员', 0, '', '', '岗位职责：\r\n1、根据公司的整体规划，协助主管制定年度促销计划及促销费用预算；\r\n2、负责公司的产品推广，做好售后服务，建立良好的客户关系；\r\n3、按时按质完成促销活动的销售统计报表；\r\n4、促销产品及赠品的设计、制作及发放管理；\r\n5、竞品信息的收集与反馈；\r\n6、按照促销计划实施促销活\r\n岗位要求：\r\n1、市场营销、管理类、广告类或相关专业专科以上学历；\r\n2、有一定市场策划能力，沟通协调能力；\r\n3、有较好的综合素质及文化修养，敬业、有团队合作精神；\r\n4、具有亲和力，较强的执行能力；\r\n5、熟练操作OFFICE软件。'),
(565, 3, '保险代理人/经纪人', 0, '', '', '岗位职责：\r\n1、负责从事客户投资理财规划和保险产品销售；\r\n2、负责从事组织的发展、训练、管理工作；\r\n3、负责协助分析客户的保险及财务状况；\r\n4、负责定期接受业务培训；\r\n5、负责辅导新人的销售、培训及管理工作；\r\n6、负责为参保客户提供所销售保险的一切服务\r\n\r\n岗位要求：\r\n1、大专及以上学历，年龄在25-45周岁，男女不限；\r\n2、掌握经济、金融、管理等专业知识；\r\n3、熟悉保险产品特别相关的专业知识；\r\n4、具有较强的沟通与组织协调能力及亲和力；\r\n5、具有良好的语言表达能力及分析判断能力；\r\n6、有积极进取的精神及接受挑战的性格；\r\n7、具有良好的责任心、有一定的团队协作精神。'),
(564, 3, '房地产销售/置业顾问', 0, '', '', '岗位职责：\r\n1、 负责客户的接待、咨询工作，为客户提供专业的房地产置业咨询服务；\r\n2、 陪同客户看房，促成二手房买卖或租赁业务；\r\n3、 负责公司房源开发与积累，并与业主建立良好的业务协作关系。\r\n\r\n岗位要求：\r\n1、年龄在20—30周岁，大专以上学历；\r\n2、诚实守信，吃苦耐劳，具有良好的团队精神；\r\n3、能承受较强的工作压力，愿意挑战高薪；\r\n4、普通话流利；'),
(563, 3, '汽车销售/经纪人', 0, '', '', '岗位职责：\r\n1、负责整车销售服务和进店客户咨询服务；\r\n2、负责整理各车型的销售资料及客户档案；\r\n3、负责开拓产品的销售市场，完成各项销售指标；\r\n4、负责挖掘客户需求，实现产品销售；\r\n5、负责售前业务跟进及售后客户维系工作。\r\n\r\n岗位要求：\r\n1、大专及以上学历，有驾驶证并驾驶熟练，形象好，气质佳；\r\n2、主动性强，工作态度积极，热爱汽车销售工作；\r\n3、有较强的事业心，勇于面对挑战；\r\n4、良好的沟通和表达能力、应变能力和解决问题的能力，心理素质佳；\r\n5、良好的团队协作精神和客户服务意识；\r\n6、有销售经验或市场营销专业优先。'),
(562, 3, '医疗器械销售', 0, '', '', NULL),
(561, 3, '医药代表', 0, '', '', '岗位职责：\r\n1、在辖区内医院进行公司产品的推广销售，完成销售任务；\r\n2、根据需要拜访医护人员，向客户推广产品，不断提高产品市场份额；\r\n3、开拓潜在的医院渠道客户，并对既有的客户进行维护；\r\n4、充分了解市场状态，及时向上级主管反映竟争对手的情况及市场动态、提出合理化建议；\r\n5、制定并实施辖区医院的推销计划，组织医院内各种推广活动；\r\n6、树立公司的良好形象， 对公司商业秘密做到保密。\r\n\r\n岗位要求：\r\n1、专科及以上学历，医药、营销类相关专业；\r\n2、2年以上销售工作经验，有医疗器材、耗材、药品销售经验者优先；\r\n3、有医院销售经验，熟悉医院工作流程，拥有良好的医院资源和销售渠道，热爱药品销售服务工作；\r\n4、具有较强的独立工作能力和社交技巧，较好的沟通能力、协调能力和团队合作能力；\r\n5、身体健康，具有独立分析和解决问题的能力。'),
(560, 3, '网络销售', 0, '', '', '岗位职责：\r\n1、利用网络进行公司产品的销售及推广；\r\n2、负责公司网上贸易平台的操作管理和产品信息的发布；\r\n3、了解和搜集网络上各同行及竞争产品的动态信息；\r\n4、通过网络进行渠道开发和业务拓展；\r\n5、按时完成销售任务。\r\n\r\n岗位要求：\r\n1、专科及以上学历，市场营销等相关专业；\r\n2、2年以上网络销售工作经验，具有网络销售渠道者优先；\r\n3、精通各种网络销售技巧，有网上开店等相关工作经验，熟悉各大门户网站及各网购网站；\r\n4、熟悉互联网络，熟练使用网络交流工具和各种办公软件；\r\n5、有较强的沟通能力。'),
(559, 3, '渠道专员', 0, '', '', NULL),
(558, 3, '区域代表', 0, '', '', NULL),
(557, 3, '客户代表', 0, '', '', NULL),
(556, 3, '电话销售', 0, '', '', '岗位职责：\r\n1、负责搜集新客户的资料并进行沟通，开发新客户；\r\n2、通过电话与客户进行有效沟通了解客户需求, 寻找销售机会并完成销售业绩；\r\n3、维护老客户的业务，挖掘客户的最大潜力；\r\n4、定期与合作客户进行沟通，建立良好的长期合作关系。\r\n\r\n岗位要求：\r\n1、20-30岁，口齿清晰，普通话流利，语音富有感染力；\r\n2、对销售工作有较高的热情；\r\n3、具备较强的学习能力和优秀的沟通能力；\r\n4、性格坚韧，思维敏捷，具备良好的应变能力和承压能力；\r\n5、有敏锐的市场洞察力，有强烈的事业心、责任心和积极的工作态度，有相关电话销售工作经验者优先。'),
(555, 3, '销售工程师', 0, '', '', NULL),
(553, 2, '其他销售管理', 0, '', '', NULL),
(552, 2, '团购经理/主管', 0, '', '', NULL),
(551, 2, '大客户经理', 0, '', '', NULL),
(550, 2, '业务拓展(BD)经理', 0, '', '', NULL),
(549, 2, '渠道经理/主管', 0, '', '', '岗位职责：\r\n1、新渠道开发，渠道商的联络、考评、筛选、淘汰和更新工作；\r\n2、行业推广渠道发展趋势分析；\r\n3、执行渠道商的培训、售前协助、售后客户服务和技术支持；\r\n4、配合渠道开发部门成本分析和控制方案；\r\n5、完成领导交办的其他任务；\r\n6、适应短期出差。\r\n\r\n岗位要求：\r\n1、二年以上销售和市场经验，具备优秀的渠道开发和市场开拓能力；\r\n2、有强烈的事业心和责任感，具备良好的人际交往、社会活动能力及公关谈判能力；\r\n3、对工作有激情、执着、敬业, 思维清晰、活跃；\r\n4、较好的谈吐，形象好，气质佳；\r\n5、具有良好的团队协作精神，良好的协调、沟通和把握全局的能力；\r\n6、思维敏锐，极富创新精神，环境适应能力强，抗压力能力强。'),
(548, 2, '区域销售经理/主管', 0, '', '', '岗位职责：\r\n1、完成所辖区域的产品销售任务，提升产品在区域内的占比；\r\n2、负责所辖区域内市场的开拓、客户的开发、网点的布局及新客户前期进场谈判工作；\r\n3、负责所辖区域内卖场的出样规划布置，整体形象的维护；\r\n4、负责所辖区域内的产品线的设定，产品零售价、标价的制订，整体价格体系的维护；\r\n5、掌握所辖区域内客户进、销、存情况，及时跟进客户提货计划和物流发货状况；\r\n6、负责渠道促销方案的制订\r\n7、负责预算、确认渠道客户的各项费用，及时对账、催款；\r\n8、掌握所辖区域内竞品动态及节假日促销活动计划，并制订出相应策略。\r\n\r\n岗位要求：\r\n1、本科及以上学历，市场营销或经济、管理类相关专业优先；\r\n2、具有1年以上家电或快消品行业的销售管理经验者优先，对家电行业渠道运作、市场销售有较强理解者尤佳；\r\n3、吃苦耐劳，有较强的工作责任心和团队协作精神；\r\n4、Office办公软件运用熟练，尤其是PPT汇报材料制作与Excel数据整理；\r\n5、能力优秀者可适当放宽要求。'),
(547, 2, '客户经理/主管', 0, '', '', '岗位职责：\r\n1、负责市场调研和需求分析；\r\n2、负责年度销售的预测，目标的制定及分解；\r\n3、确定销售部门目标体系和销售配额；\r\n4、制定销售计划和销售预算；\r\n5、负责销售渠道和客户的管理；\r\n6、组建销售队伍，培训销售人员；\r\n7、评估销售业绩，建设销售团队。\r\n\r\n岗位要求：\r\n1、专科及以上学历，市场营销等相关专业；\r\n2、2年以上销售行业工作经验，有销售管理工作经历者优先；\r\n3、具有丰富的客户资源和客户关系，业绩优秀；\r\n4、具备较强的市场分析、营销、推广能力和良好的人际沟通、协调能力，分析和解决问题的能力；\r\n5、有较强的事业心，具备一定的领导能力。'),
(542, 2, '销售总监', 0, '', '', '岗位职责：\r\n1、协助总经理制定公司的发展战略，销售战略，制定并组织实施完整的销售计划，领导团队将计划转变为销售结果；\r\n2、开拓热力行业业务，与客户、同行业间（热力行业）建立良好的合作关系；\r\n3、制定全年销售费用预算，引导和控制市场销售工作的方向和进度；\r\n4、分解销售任务指标，制定责任、费用评价办法，制定、调整销售运营政策；\r\n5、建立热力行业客户数据库，了解不同规模用户的现状与可能需求；\r\n6、组织部门开发多种销售手段，完成销售计划及回款任务；\r\n7、销售团队建设，帮助建立、补充、发展、培养销售队伍\r\n8、主持公司重大营销合同的谈判与签订工作；\r\n9、进行客户分析，挖掘用户需求，开发新的客户和新的市场领域。\r\n岗位要求：\r\n1、28－40岁，大专以上学历，有良好的职业操守，品行优秀，综合素质高；\r\n2、具有五年以上市场营销或管理工作经验；\r\n3、文字能力强，表达能力强；\r\n4、具有较强的市场开拓与销售技能；\r\n5、具备优秀的沟通能力和团队合作精神，组建和培训团队经验丰富，既往销售业绩良好；\r\n6、具备较强的时间管理能力和工作管理能力；\r\n7、有很好的热力行业人际资源。'),
(543, 2, '销售经理', 0, '', '', NULL),
(554, 3, '业务员/销售代表', 0, '', '', '岗位职责：\r\n1、负责公司产品的销售及推广；\r\n2、根据市场营销计划，完成部门销售指标；\r\n3、开拓新市场,发展新客户,增加产品销售范围；\r\n4、负责辖区市场信息的收集及竞争对手的分析；\r\n5、负责销售区域内销售活动的策划和执行，完成销售任务；\r\n6、管理维护客户关系以及客户间的长期战略合作计划。\r\n\r\n岗位要求：\r\n1、负责公司产品的销售及推广；\r\n2、根据市场营销计划，完成部门销售指标；\r\n3、开拓新市场,发展新客户,增加产品销售范围；\r\n4、负责辖区市场信息的收集及竞争对手的分析；\r\n5、负责销售区域内销售活动的策划和执行，完成销售任务；\r\n6、管理维护客户关系以及客户间的长期战略合作计划。'),
(545, 2, '销售主管', 0, '', '', '岗位职责：\r\n1、负责市场调研和需求分析；\r\n2、负责年度销售的预测，目标的制定及分解；\r\n3、确定销售部门目标体系和销售配额；\r\n4、制定销售计划和销售预算；\r\n5、负责销售渠道和客户的管理；\r\n6、组建销售队伍，培训销售人员；\r\n7、评估销售业绩，建设销售团队。\r\n\r\n岗位要求：\r\n1、专科及以上学历，市场营销等相关专业；\r\n2、2年以上销售行业工作经验，有销售管理工作经历者优先；\r\n3、具有丰富的客户资源和客户关系，业绩优秀；\r\n4、具备较强的市场分析、营销、推广能力和良好的人际沟通、协调能力，分析和解决问题的能力；\r\n5、有较强的事业心，具备一定的领导能力'),
(774, 98, '房地产销售经理', 0, '', '', NULL),
(775, 98, '房地产中介/交易', 0, '', '', NULL),
(776, 98, '房地产估价师', 0, '', '', NULL),
(777, 98, '房地产配套工程师', 0, '', '', NULL),
(778, 98, '房地产项目招投标', 0, '', '', NULL),
(779, 98, '其他房地产', 0, '', '', NULL),
(780, 99, '物业管理经理/主管', 0, '', '', '岗位职责：\r\n1、能编写物业项目的工作指导书，以及项目的服务计划，编制项目的预算；\r\n2、全面负责物业项目的人员管理、财务管理、物品管理和各项现场管理；\r\n3、能及时向各部门传达公司各项工作会议的精神及颁布的制度，督促严格执行；\r\n4、落实人员做好物业内楼宇管理、日常维修、清扫保洁、绿化养护、门卫保安、卫生消毒、车辆管理及依法制止违规装修和违章搭建等具体管理服务工作；\r\n5、根据公司和物业项目业委会的要求，主持、审查、签订对外的各项经济合同；\r\n6、审批房屋、公共设施的维修、养护计划和业主/租户装修申请，组织维修人员按时保质完成各项任务，并检查督促业主/租户按规定进行装修；\r\n7、对业主/租户之间在物业使用中发生的争议进行协调；\r\n8、定期向业委会报告物业维修、更新费用的收支账目，接受审核；\r\n9、接受街道和房地产管理部门的指导和监督，配合做好社区管理工作。\r\n\r\n岗位要求：\r\n1、身体健康，形象佳，年龄30～45岁，男女皆可；\r\n2、大专以上学历，物业管理专业更佳；\r\n3、五年以上高档住宅物业（5万元/平方米市场售价）工作经历，精通物业服务各项工作的具体内容、标准、程序；\r\n4、有全国物业管理经理资格证书或物业管理师证书；\r\n5、上海市户籍，有上海、北京、重庆、深圳或海外从业经历；\r\n6、英语优秀者优先；\r\n7、五大行物业从业者优先。'),
(781, 99, '物业管理员', 0, '', '', '岗位职责：\r\n1、负责接待工作，做好来访登记；\r\n2、负责为业户办理入伙、入住、装修手续，处理业户咨询、投诉工作；\r\n3、管理公司清洁、绿化、治安、维修等服务工作；\r\n4、负责发现运作中不合格的服务项目，进行跟踪、验证；\r\n5、全面掌握区域物业公共设施、设备的使用过程。\r\n\r\n岗位要求：\r\n1、30岁以下，大专以上学历，物业管理相关专业；\r\n2、二年以上高档物业服务或星级酒店行业客服工作经验；\r\n3、较强的客户服务意识，良好的沟通表达能力、较强的协调能力；\r\n4、持有物业管理上岗证，有品牌物业企业工作经历优先考虑。'),
(782, 99, '物业招商/租赁/租售', 0, '', '', NULL),
(783, 99, '物业顾问', 0, '', '', NULL),
(784, 99, '物业设施管理', 0, '', '', NULL),
(785, 99, '物业维修', 0, '', '', '岗位职责：\r\n1、对公司规定的项目点的工作进行检查和维修；\r\n2、对值班期间发生的问题及时上报或处理；\r\n3、所有物业设备的检查等；\r\n4、执行、参加公司临时委派的任务。\r\n\r\n岗位要求：\r\n1、2年以上综合物业维修工作经验；\r\n2、具有设备设施的维护、保养、检修工作经验；\r\n3、熟悉水、暖、通等专业知识；\r\n4、持维修电工上岗证(低压电工上岗证)、或维修钳工等级证(维修钳工等级证)等岗位操作证书；\r\n5、有一定沟通能力、执行能力和团队合作精神，责任心强，能够吃苦耐劳。'),
(786, 99, '其他物业管理', 0, '', '', NULL),
(799, 117, '产品/工艺工程师', 0, '', '', ''),
(800, 117, '工业设计/产品设计', 0, '', '', ''),
(801, 117, '工业工程师', 0, '', '', ''),
(802, 117, '检修维护', 0, '', '', ''),
(803, 117, '化验/检验', 0, '', '', ''),
(804, 117, '其他生产制造管理', 0, '', '', ''),
(805, 118, '品质保证/质量控制经理', 0, '', '', ''),
(806, 118, '质量控制工程师', 0, '', '', ''),
(807, 118, '质量检验/测试', 0, '', '', ''),
(808, 118, '体系认证工程师/审核员', 0, '', '', ''),
(809, 118, '供应商管理', 0, '', '', ''),
(810, 118, '采购质量管理', 0, '', '', ''),
(811, 118, '安全/健康/环境工程师', 0, '', '', ''),
(812, 118, '安全管理', 0, '', '', ''),
(813, 118, '安全消防', 0, '', '', ''),
(814, 118, '其他质量/安全管理', 0, '', '', ''),
(815, 119, '汽车修理', 0, '', '', NULL),
(816, 119, '4S店经理/维修站经理', 0, '', '', NULL),
(817, 119, '汽车销售/经纪人', 0, '', '', NULL),
(818, 119, '汽车机构工程师', 0, '', '', NULL),
(819, 119, '汽车设计工程师', 0, '', '', NULL),
(820, 119, '汽车电子工程师', 0, '', '', NULL),
(821, 119, '汽车装饰美容', 0, '', '', NULL),
(822, 119, '加油站工作员', 0, '', '', NULL),
(823, 119, '其他汽车', 0, '', '', NULL),
(824, 120, '产品/工艺工程师', 0, '', '', ''),
(825, 120, '工程/设备工程师', 0, '', '', ''),
(826, 120, '工程/机械绘图员', 0, '', '', ''),
(827, 120, '机械设计师', 0, '', '', ''),
(828, 120, '机械工程师', 0, '', '', ''),
(829, 120, 'CNC/数控工程师', 0, '', '', ''),
(830, 120, '模具工程师', 0, '', '', ''),
(831, 120, '机械维修工程师', 0, '', '', ''),
(832, 120, '设备维修', 0, '', '', ''),
(833, 120, '精密机械/仪器仪表', 0, '', '', ''),
(834, 120, '材料工程师', 0, '', '', ''),
(835, 120, '冶金机械', 0, '', '', ''),
(836, 120, '化工机械', 0, '', '', ''),
(837, 120, '其他机械', 0, '', '', ''),
(838, 121, '技工', 0, '', '', NULL),
(839, 121, '电工', 0, '', '', NULL),
(840, 121, '空调/电梯/锅炉工', 0, '', '', NULL),
(841, 121, '电焊/铆焊工', 0, '', '', NULL),
(842, 121, '钳工/机修工/钣金工', 0, '', '', NULL),
(843, 121, '车/磨/铣/冲/镗工', 0, '', '', NULL),
(844, 121, '切割技工', 0, '', '', NULL),
(845, 121, '装卸/叉车工', 0, '', '', NULL),
(846, 121, '电器维修', 0, '', '', NULL),
(847, 121, '水工/木工/油漆工', 0, '', '', NULL),
(848, 121, '汽车修理', 0, '', '', NULL),
(849, 121, '安装/调试', 0, '', '', NULL),
(850, 121, '裁剪车缝熨烫', 0, '', '', NULL),
(851, 121, '模具工', 0, '', '', NULL),
(852, 121, '普工', 0, '', '', NULL),
(853, 121, '其他技术工人', 0, '', '', NULL),
(854, 122, '服装/纺织品设计', 0, '', '', NULL),
(855, 122, '纺纱/织造/针织', 0, '', '', NULL),
(856, 122, '服装打样/制版', 0, '', '', NULL),
(857, 122, '鞋帽制作', 0, '', '', NULL),
(858, 122, '皮革/毛皮加工', 0, '', '', NULL),
(859, 122, '裁剪车缝熨烫', 0, '', '', NULL),
(860, 122, '印染', 0, '', '', NULL),
(861, 122, '服装/纺织/皮革跟单', 0, '', '', NULL),
(862, 122, '其他服装/纺织品', 0, '', '', NULL),
(881, 138, '电机工程师', 0, '', '', NULL),
(882, 138, '电气工程师', 0, '', '', NULL),
(883, 138, '自动控制工程师', 0, '', '', NULL),
(884, 138, '机电工程师', 0, '', '', NULL),
(885, 138, '电气设计', 0, '', '', NULL),
(886, 138, '电气维修', 0, '', '', NULL),
(887, 138, '照明设计', 0, '', '', NULL),
(888, 138, '其他电气', 0, '', '', NULL),
(889, 139, '水利/水电工程师', 0, '', '', NULL),
(890, 139, '电力工程师', 0, '', '', NULL),
(891, 139, '电力设备检修', 0, '', '', NULL),
(892, 139, '光源与照明工程', 0, '', '', NULL),
(893, 139, '光伏系统工程师', 0, '', '', NULL),
(894, 139, '空调/热能工程师', 0, '', '', NULL),
(895, 139, '石油天然气技术', 0, '', '', NULL),
(896, 139, '地质勘查/开采', 0, '', '', NULL),
(897, 139, '其他电力/能源', 0, '', '', NULL),
(898, 140, '化工技术', 0, '', '', ''),
(899, 140, '实验室研究员/技术员', 0, '', '', ''),
(900, 140, '石油化工', 0, '', '', ''),
(901, 140, '橡胶/塑料', 0, '', '', ''),
(902, 140, '食品/饮料研发', 0, '', '', ''),
(903, 140, '水处理', 0, '', '', ''),
(904, 140, '其他化工', 0, '', '', ''),
(928, 172, '美术编辑/设计', 0, '', '', NULL),
(929, 172, '包装设计', 0, '', '', NULL),
(930, 172, '家具/家居用品设计', 0, '', '', NULL),
(931, 172, '工艺品/珠宝设计', 0, '', '', NULL),
(932, 172, '工业设计/产品设计', 0, '', '', NULL),
(933, 172, '舞台设计', 0, '', '', NULL),
(934, 172, '服装/纺织品设计', 0, '', '', NULL),
(935, 172, '其他艺术设计', 0, '', '', NULL),
(936, 173, '总编/副总编/主编', 0, '', '', NULL),
(937, 173, '编辑/作家', 0, '', '', NULL),
(938, 173, '记者', 0, '', '', NULL),
(939, 173, '文案/策划', 0, '', '', NULL),
(940, 173, '排版设计', 0, '', '', NULL),
(941, 173, '校对/录入', 0, '', '', NULL),
(942, 173, '美术编辑/设计', 0, '', '', NULL),
(943, 173, '出版/印刷/发行', 0, '', '', NULL),
(944, 173, '制版/装订/烫金', 0, '', '', NULL),
(945, 173, '印刷工', 0, '', '', NULL),
(946, 173, '其他新闻/出版', 0, '', '', NULL),
(962, 204, '其他教育', 0, '', '', NULL),
(963, 205, '律师', 0, '', '', NULL),
(964, 205, '法律顾问', 0, '', '', NULL),
(965, 205, '法务人员', 0, '', '', NULL),
(966, 205, '知识产权/专利顾问', 0, '', '', NULL),
(967, 205, '其他法律', 0, '', '', NULL),
(968, 206, '专业顾问', 0, '', '', NULL),
(969, 206, '咨询经理', 0, '', '', NULL),
(970, 206, '咨询员', 0, '', '', NULL),
(971, 206, '情报信息分析/调研员', 0, '', '', NULL),
(972, 206, '其他咨询', 0, '', '', NULL),
(973, 207, '英语翻译', 0, '', '', NULL),
(974, 207, '日语翻译', 0, '', '', NULL),
(975, 207, '韩语翻译', 0, '', '', NULL),
(976, 207, '法语翻译', 0, '', '', NULL),
(977, 207, '俄语翻译', 0, '', '', NULL),
(978, 207, '德语翻译', 0, '', '', NULL),
(979, 207, '意大利语翻译', 0, '', '', NULL),
(980, 207, '西班牙语翻译', 0, '', '', NULL),
(981, 207, '阿拉伯语翻译', 0, '', '', NULL),
(982, 207, '葡萄牙语翻译', 0, '', '', NULL),
(983, 207, '其他翻译', 0, '', '', NULL),
(993, 227, '药品生产/质管', 0, '', '', '岗位职责：\r\n1、负责医药中间体、原料药等质检工作及相关化学分析；\r\n2、负责化学合成实验；\r\n3、书写质检报告并向上级汇报。\r\n岗位要求：\r\n1、中专以上学历，生物或化学等相关专业；\r\n2、有1年以上药品检验工作经验，熟悉质量检验规程及标准；\r\n3、能够熟练使用办公软件及检验设备。'),
(994, 227, '生物技术制药', 0, '', '', '岗位职责：\r\n1、负责生物工程应用技术的前期调研，确认立项题目，编制可行性研究报告，自主申报公司内、外部立项；\r\n2、负责生物工程方面项目的研发工作，对质量、工期和成本负责；\r\n3、负责生物工程技术及产品的鉴定评审、专利申报、内外的推广宣传、技术培训；\r\n4、负责指导生物工程技术研发成果在工程中的应用及技术的持续完善和提高；\r\n岗位要求：\r\n1、本科及以上学历，有五年以上生物工程研发经验；\r\n2、有一年以上菌种培育的经验；\r\n3、熟练使用试验仪器，准确进行数据分析；\r\n4、有较强的数据分析能力及逻辑分析能力；\r\n5、英语口语较流利，与外籍专家交流无障碍。'),
(995, 227, '医疗设备生产', 0, '', '', '岗位职责：\r\n1、负责医疗器械产品开发；\r\n2、负责寻找国内合格代工方，与之沟通产品实现方案并确定工艺及产品技术标准；评价代工方资质；\r\n3、质控合格外协方保证产品质量符合预期标准；\r\n4、编制器械产品技术文件及质量文件；\r\n5、编制符合行业开发流程的项目计划并负责推行、落实、监控，负责设备各模块组件的整体实现。\r\n岗位要求：\r\n1、大学本科以上学历，5年以上机械电子产品行业经验；\r\n2、熟悉注塑、喷涂、PCB工艺，熟悉注塑、喷涂、PCB工艺质量控制；\r\n3、熟练使用AutoCAD软件，UG、Solidwork、pro-E、Catia其中之一；\r\n4、实际医疗器械电子产品研发及整机实现经验不少于3年；\r\n5、具备独立设计开发和分析解决问题的能力，有较强的动手能力；\r\n6、有良好的沟通和团队协作能力，能用英语进行口语交流；\r\n7、熟悉医疗器械行业者优先。'),
(996, 227, '医药代表', 0, '', '', '岗位职责：\r\n1、在辖区内医院进行公司产品的推广销售，完成销售任务；\r\n2、根据需要拜访医护人员，向客户推广产品，不断提高产品市场份额；\r\n3、开拓潜在的医院渠道客户，并对既有的客户进行维护；\r\n4、充分了解市场状态，及时向上级主管反映竟争对手的情况及市场动态、提出合理化建议；\r\n5、制定并实施辖区医院的推销计划，组织医院内各种推广活动；\r\n岗位要求：\r\n1、专科及以上学历，医药、营销类相关专业；\r\n2、2年以上销售工作经验，有医疗器材、耗材、药品销售经验者优先；\r\n3、有医院销售经验，熟悉医院工作流程，拥有良好的医院资源和销售渠道，热爱药品销售服务工作；\r\n4、具有较强的独立工作能力和社交技巧，较好的沟通能力、协调能力和团队合作能力；\r\n5、身体健康，具有独立分析和解决问题的能力。'),
(997, 227, '医疗器械销售', 0, '', '', NULL),
(998, 227, '其他制药/医疗器械', 0, '', '', NULL),
(999, 228, '环保工程师', 0, '', '', NULL),
(1000, 228, '环境评价工程师', 0, '', '', NULL),
(1001, 228, '环保检测', 0, '', '', NULL),
(1002, 228, '水处理', 0, '', '', '岗位职责：\r\n1、负责污泥处理项目技术交流；\r\n2、负责编制污泥处理工程项目的工艺、方案、初步设计、施工图设计等；\r\n3、能够进行施工现场的技术指导和运行调试。\r\n岗位要求：\r\n1、大学本科及以上学历，环境工程及相关专业；\r\n2、熟悉污水处理相关流程，了解环保法律法规；\r\n3、3年以上相关工作经验。'),
(1003, 228, '固废工程师', 0, '', '', NULL),
(1004, 228, '废气处理工程师', 0, '', '', NULL),
(1005, 228, '环境管理/园林景区保护', 0, '', '', NULL),
(1006, 228, '其他环保', 0, '', '', NULL),
(1015, 243, '保安/门卫', 0, '', '', '岗位职责：\r\n1、确实掌握安全事宜，服勤于大门前、大厅内、后门及各指定之警卫岗；\r\n2、遵行保安经理之指示，服勤安全警卫勤务，确保财产与顾客安全。\r\n岗位要求：\r\n1、18—30周岁，身高175CM以上，身体健康，容貌端正；\r\n2、熟悉安全制度及安全器材使用、意外事件及紧急事故之预防与安排；\r\n3、良好的亲和力，退伍军人优先考虑。'),
(1016, 243, '中控员', 0, '', '', NULL),
(1017, 243, '保洁/清洁工', 0, '', '', '岗位职责：\r\n1、负责所分配区域或客户的卫生清洁工作；\r\n2、保证按质按量的完成所分配的任务；\r\n3、听从分配和安排。\r\n岗位要求：\r\n1、男女不限，身体健康，年龄40-55岁；\r\n2、有相关工作经验者优先考虑。'),
(1018, 243, '家政服务/保姆', 0, '', '', '岗位职责：\r\n1、负责所分配客户的家庭卫生清洁工作；\r\n2、负责家庭用餐的食材采购、做饭等工作；\r\n3、负责照顾好小孩的饮食起居、学校接送等事项；\r\n4、听从分配和安排。\r\n岗位要求：\r\n1、女，身体健康，年龄20-45岁；\r\n2、有相关工作经验者优先考虑。'),
(1019, 243, '其他保安/家政', 0, '', '', NULL),
(1020, 244, '酒店/宾馆经理', 0, '', '', NULL),
(1021, 244, '大堂经理', 0, '', '', '岗位职责：\r\n1、带领员工认真做好餐前准备，确保质量标准；\r\n2、正式开餐后，督导服务员认真做好服务工作并亲自参加服务工作；\r\n3、及时跟踪、检查台面，对不合格的地方进行指正、改正；\r\n4、及时对餐台上菜速度、情况了解，及时催菜；\r\n5、餐后组织服务员及时清台，整理好餐厅桌椅卫生，保持\r\n岗位要求：\r\n1、初中以上文化程度，形象气质佳；\r\n2、熟悉餐厅管理和服务方面的知识，具有熟练的服务技能；\r\n3、有较高的处理餐厅突发事件的应变能力及对客沟通能力；\r\n4、热爱服务工作，工作踏实、认真，有较强的事业心和责任感。'),
(1022, 244, '楼面经理', 0, '', '', NULL),
(1023, 244, '前厅接待', 0, '', '', NULL),
(1024, 244, '客房服务员', 0, '', '', NULL),
(1025, 244, '导游/旅行顾问', 0, '', '', '岗位职责：\r\n1、负责区域内的销售工作，包括响应客户的询单需求，与客户沟通最佳的旅游产品解决方案，向客户报价并直至达成交易；\r\n2、与自己负责区域内客户保持紧密的联系，定时电话拜访，增加寻单量；\r\n3、与客户达成交易后，负责对成交的业务与后期相关部门进行协调沟通与辅助完成\r\n岗位要求：\r\n1、大专以上学历，有旅游行业从业经验优先；\r\n2、性格外向、反应敏捷、表达能力强，有良好的团队合作意识；\r\n3、具有较高的销售及谈判技巧，具有亲和力；\r\n4、热爱旅游事业，有责任心，能承受较大的工作压力，吃苦耐劳；\r\n5、具有良好的协调、组织、策划能力，善于沟通；'),
(1026, 244, '行程管理/计调', 0, '', '', NULL),
(1027, 244, '订票/订房', 0, '', '', NULL),
(1028, 244, '娱乐/餐饮经理', 0, '', '', NULL),
(1029, 244, '娱乐/餐饮服务员', 0, '', '', '岗位职责：\r\n1、按照领班安排认真做好桌椅、餐厅卫生，餐厅铺台，准备好各种用品，确保正常营业使用。\r\n2、接待顾客应主动、热情、礼貌、耐心、周到，使顾客有宾至如归之感；\r\n3、运用礼貌语言，为客人提供最佳服务，\r\n4、善于向顾客介绍和推销本餐厅饮品及特色菜点；\r\n5、配合领班工作，服从管理。\r\n岗位要求：\r\n1、年龄18-30岁，身体健康，女性身高1、58m以上、男性身高1、70以上，能吃苦，\r\n2、品行端正，能吃苦耐劳，初中以上文化程度。'),
(1030, 244, '礼仪/迎宾', 0, '', '', '岗位职责：\r\n1、做好消费宾客的迎、送接待工作，接受宾客各种渠道的预定并加以落实；\r\n2、详细做好预订记录；\r\n3、了解和收集宾客的建议和意见并及时反馈给上级领导；\r\n4、以规范的服务礼节，树立公司品牌优质，文雅的服务形象。\r\n岗位要求：\r\n1、女性，年龄18—28周岁，身体健康，身材匀称、五官端庄，身高1、65—1、72米。\r\n2、具有良好的沟通协调能力及服务意识，反应灵敏，端庄大方、举止文雅；\r\n3、敬业乐业、具有较强的责任心和吃苦耐劳的职业素养，具备一定的英语水平。\r\n4、具备星级酒店前台工作经验或高档涉外写'),
(1031, 244, '厨师', 0, '', '', '岗位职责：\r\n1、负责处理厨房的运作及行政事务；\r\n2、执行餐饮经理下达的各项工作任务和工作指示；\r\n3、负责制订厨房的各种工作计划；\r\n4、对厨房的出品、质量和食品成本承担重要的责任；\r\n5、保持对厨房范围的巡视，对下属员工进行督导，及时解决现场发生的问题，帮助下属提高工作能力；\r\n岗位要求：\r\n1、年龄三十岁以上，高中以上学历，身体健康、精力充沛，二年以上星级酒店厨师长工作经验；\r\n2、具有强烈的责任心，勇于开拓和创新，作风干练；\r\n3、拥有较高的烹饪技术，了解和熟悉食品材料的产地、规格、质量、一般进货价\r\n4、对成本控制管理、食品营养学、厨房的设备知识拥有'),
(1032, 244, '调酒/茶艺', 0, '', '', '岗位职责：\r\n1、传达餐饮部或厨师长的相关通知、注意事项等；\r\n2、为厨师配好所需的食材、保证食材的新鲜、卫生等；\r\n3、负责灶台的卫生清理、正常使用。\r\n岗位要求：\r\n1、年龄18—45岁，性别不限，身体健康；\r\n2、具有责任心，良好的执行能力和沟通能力，能够严格按照标准操作；\r\n3、勤奋努力，对餐饮工作有较高的工作热情。'),
(1033, 244, '其他餐饮/旅游/娱乐', 0, '', '', NULL),
(1034, 245, '美容师', 0, '', '', '岗位职责：\r\n1、为顾客提供皮肤护理、美容美体服务；\r\n2、安装美容仪器要求、程序、性能进行美容护理操作；\r\n3、保持工作环境的干净整洁；\r\n4、学习产品知识和专业技术，不断提高自身职业素质和技能；\r\n5、能指导美容助理工作。\r\n岗位要求：\r\n1、形象好，气质佳，在18到30岁之间；\r\n2、有一年以上美容美体/纤体，仪器操作经验；\r\n3、沟通理解能力强、技术手法好，有服务意识；\r\n4、具有亲和力和团队精神，有上进心；\r\n5、有美容师资格证术或者中医证书优先考虑'),
(1035, 245, '化妆师', 0, '', '', '岗位职责：\r\n1、根据不同的拍摄方案要求，设计不同的妆面造型；\r\n2、能完成人物的化妆、发型、服装、配饰等整体形象设计；\r\n3、协助摄影师完成拍摄工作。\r\n岗位要求：\r\n1、工作经验一年以上，有摄影工作室或者影楼工作经验；\r\n2、人品好，个人形象较好，敬业爱业；\r\n3、善于与客人沟通，妆面造型清新自然有自己的一定想法；\r\n4、沟通合作能力强，能很好的团队合作精神。'),
(1036, 245, '美发师', 0, '', '', '岗位职责：\r\n1、能按美发店服务程序和规范，有礼貌的接待宾客，并提供一般的美发、咨询服务；\r\n2、根据顾客不同特征，设计制作出符合顾客要求的发型；\r\n3、能独立进行洗发、剪发、吹风、梳理、烫发、染发的技术操作；\r\n4、能熟练的使用美发用品、工具设备；\r\n5、能指导美发助理工作。\r\n岗位要求：\r\n1、形象好气质佳，熟练当代时尚美发技术；\r\n2、三年以上发型师经验，剪法精准细致，吹风造型功底深厚，设计理念时尚而有品位，有英国沙宣或上海文峰沙宣班培训经历者、有较大型时尚活动或美发比赛经历者优先；\r\n3、沟通能力好，能够为顾客提供优质服务。'),
(1037, 245, '美甲师', 0, '', '', NULL),
(1038, 245, '健身顾问/教练', 0, '', '', '岗位职责：\r\n1、负责会员健身器械的指导与训练；\r\n2、负责会员操课的制定及代课；\r\n2、能够组织、推广拓展训练项目。\r\n岗位要求：\r\n1、有2年以上相关工作经验；\r\n2、有亚洲体适能或欧洲体适能资格证者；\r\n3、有丰富的私人教练或操课代课经验；\r\n4、爱好运动、旅游、性格开朗。'),
(1039, 245, '舞蹈教师', 0, '', '', '岗位职责：\r\n1、有较高的示范和指导能力；\r\n2、能够根据相应的人群制定相应的授课方案；\r\n3、能够根据相应的地理环境、训练环境制定相应的授课方案；\r\n4、正确宣传瑜伽理念及修练方式；\r\n5、能正确的制定瑜伽饮食配套方案。\r\n岗位要求：\r\n1、中专以上学历，有相关的指教资格证书；\r\n2、具有资深相关经验，有良好管理及责任感较强者可优先考虑；\r\n3、身体健康，有良好的生活方式；\r\n4、热爱瑜伽事业，具有良好的职业精神；\r\n5、接受过正规系统的瑜伽专业培训；\r\n6、沟通能力强，具有服务意识，为人正直、诚实、敬业'),
(1040, 245, '按摩/足疗', 0, '', '', '岗位职责：\r\n1、为顾客介绍特色项目及各项费用；\r\n2、按照规定的程序保质保量地完成顾客消费的项目；\r\n3、打扫和维护自己的卫生区域。\r\n岗位要求：\r\n1、年龄20-30岁，高中以上学历；\r\n2、1年以上相关工作经验；\r\n3、亲和力强、具有良好服务意识；\r\n4、具有良好的沟通能力和团队合作精神；\r\n5、具备相关证书者优先考虑。'),
(1041, 245, '其他美容/健身', 0, '', '', NULL),
(1042, 246, '仓库管理', 0, '', '', '岗位职责：\r\n1、执行物资管理中与仓库有关的SOP，确保仓库作业顺利进行；\r\n2、负责仓库日常物资的验收、入库、码放、保管、盘点、对账等工作；\r\n3、负责仓库日常物资的拣选、复核、装车及发运工作；\r\n4、负责保持仓内货品和环境的清洁、整齐和卫生工作；\r\n5、负责相关单证的保管与存档；\r\n6、仓库数据的统计、存档、帐务和系统数据的输入；\r\n7、部门主管交办的其它事宜。\r\n岗位要求：\r\n1、中专及以上学历，物流仓储类相关专业；\r\n2、1年以上相关领域实际业务操作经验，有外企相关领域工作经历者优先考虑；\r\n3、熟悉仓库进出货操作流程，具备物资保管专业知识和技能；\r\n4、熟悉电脑办公软件操作,懂得SAP操作者优先考虑；\r\n5、积极耐劳、责任心强、具有合作和创新精神。'),
(1043, 246, '物流经理/主管', 0, '', '', '岗位职责：\r\n1、负责部门日常物流管理工作，包括：运输、仓储、配送、车辆管理等；\r\n2、制定和执行物流工作计划，对物流工作规范进行总结和完善；\r\n3、监督实施物流体系职责与管理标准；\r\n4、控制送货和仓储成本；\r\n5、参与制定与控制部门物流运作预算；\r\n6、制定物流解决方案，提升客户满意度；\r\n7、定期汇总上报各项物流管理报表；\r\n8、负责所在部门人员的考核、培训工作。\r\n岗位要求：\r\n1、大专及以上学历，管理类、物流类相关专业；\r\n2、3年以上物流相关领域管理工作经验，有外资企业物流管理工作经历者优先；\r\n3、熟悉物流管理业务流程，有丰富的流程管理操作技能；\r\n4、熟悉ERP及物流信息管理系统并有实施经验；\r\n5、良好的沟通及谈判能力，团队管理能力，独立工作能力强，能承受较大工作压力。'),
(1044, 246, '物流专员/助理', 0, '', '', NULL),
(1045, 246, '货运/运输管理', 0, '', '', NULL),
(1046, 246, '货运代理', 0, '', '', NULL),
(1047, 246, '物料管理', 0, '', '', NULL),
(1048, 246, '商务司机', 0, '', '', NULL),
(1049, 246, '客运/货车/班车司机', 0, '', '', NULL),
(1050, 246, '海陆空运操作员', 0, '', '', NULL),
(1051, 246, '调度员', 0, '', '', '岗位职责：\r\n1、建立和完善车辆管理制度；\r\n2、公司车辆的调度，对司机出车路程、次数等进行安排；\r\n3、车辆用油的控制与申请使用；\r\n4、组织车辆的日常管理、维修、保养，保证车辆的整洁和安全；\r\n5、完成司机的日常管理及考核等工作；\r\n6、组织司机进行交通法规、安全知识的学习。\r\n岗位要求：\r\n1、高中以上学历；\r\n2、两年以上相关岗位工作经验；\r\n3、对人员、车辆的管理具有丰富的经验，熟悉车辆证照的办理流程，熟悉交通法律、法规；\r\n4、有责任心、吃苦耐劳，敬业爱岗、工作态度积极，优秀的沟通、协调、执行能力；\r\n5、男性，40岁以下。'),
(1052, 246, '速递员', 0, '', '', '岗位职责：\r\n1、负责区域内的物品送达及货款的及时返回；\r\n2、执行业务操作流程，准时送达物品，指导客户填写相关资料并及时取回；\r\n3、整理并呈递相关业务单据和资料；\r\n4、客户的维护，客户咨询的处理和意见的反馈；\r\n5、突发事件的处理。\r\n岗位要求：\r\n1、初中及以上学历；\r\n2、熟悉当地地形，有同行业工作经验者优先；\r\n3、吃苦耐劳，人品端正，做事仔细认真；\r\n4、身体健康，无不良嗜好'),
(1053, 246, '理货员', 0, '', '', '岗位职责：\r\n1、熟悉所在商品部门的商品名称、产地、厂家、规格、用途、性能、保质期限；\r\n2、遵守超市仓库管理和商品发货的有关规定，按作业流程进行该项工作；\r\n3、掌握商品标价的知识，正确标好价格；\r\n4、熟练掌握商品陈列的有关专业知识，并把它运用到实际工作中；\r\n5、搞好货架与责任区\r\n岗位要求：\r\n1、高中以上学历；\r\n2、有大型商场或超市工作经验者优先；\r\n3、品貌端正，热爱零售行业，吃苦耐劳，责任心强，身体健康，有很强的敬业精神和良好的心理素质；\r\n4、具备简单的计算机操作技巧，了解商品分类和存储知识；\r\n5、能够胜任繁重的体力工作，能适应中夜班调休安排。'),
(1054, 246, '其他物流/交通/仓储', 0, '', '', NULL);";
		$db->query($sql_cate);
		$subclass_sql="select categoryname,id from ".table('category_jobs_bak')." where parentid<>0";
		$subclas_name=$db->getall($subclass_sql);
		$new_category_jobs_sql = "select * from ".table('category_jobs')." where parentid<>0";
		$new_category_jobs=$db->getall($new_category_jobs_sql);
		foreach ($subclas_name as $value) {
			update_category($value['categoryname'],$value['id']);
		}
		


	}
	
	$db->query("DROP TABLE ".table("category_jobs_bak"));
}
//
header("Location: index.php?step=4");
exit();
}
if ($_GET['act']=="check")
{
 $need_check_dirs = array(
 			'data',
			'data/comads',
			'data/avatar',
		    'data/backup',
		    'data/certificate',
		    'data/images',
		    'data/images/thumb',
		    'data/link',
		    'data/logo',					
 			'data/photo',
		    'data/photo/thumb',
 		    'data/hrtools',
	      'temp',
		    'temp/caches',
		    'temp/templates_c',		
		    'temp/backup_templates',			
	      'templates',	
	      'admin/statement' 
);
$dir_check = check_dirs($need_check_dirs);
$step="2";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>骑士CMS升级程序 </title>
<script src="images/jquery.js" type="text/javascript"></script>
<style type="text/css">
<!--
body,td,th {
	font-size: 12px;
 
	color: #006699;
}
h1{ margin-bottom:10px; margin-top:10px;}
.txt{ line-height:200%; padding-left:10px; padding-top:10px;}
.txt span{ color:#FF0000}
input{ font-size:12px; padding:3px;}
-->
</style>
</head>
<body>
<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td  height="100" ><img src="images/logo.gif" /></td>
  </tr>
</table>
<?php if ($step=="1") {?>
			<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
        <td height="30" bgcolor="#EFF9FE"  style=" border-bottom:1px #C6E6F4 solid;border-top:1px #C6E6F4  solid;"><strong>&nbsp;&nbsp;升级提示</strong></td>
      </tr>
 <tr>
   <td  class="txt">
    ·本升级程序仅适用于74cms企业版 v<?php echo  $version_old ?> 升级到 74cms企业版 v<?php echo $version_new ?> <br />
	 ·如果您修改过网站程序文件,特别是数据库结构，请不要运行此程序，否则将会导致程序错误。<br />
·此次升级可能需要较长时间，升级整个过程是自动完成的，升级过程中不要关闭窗口！<br>
<span>·升级之前务必备份数据库资料,否则可能产生无法恢复的后果!<br /></span>
  ·当升级程序运行完成后，请删除(除data目录外)所有文件和文件夹，然后上传<?php echo $version_new ?>覆盖所有文件和文件夹。<br> 
  ·当升级程序运行完成后，请给相关文件夹或文件设置读写权限,详细请看《升级必读》<br> 
 ·升级完毕后请务必删除此文件!<br>
   ·如果您使用的不是官方默认模版，升级后可能出现页面空白，请在后台将模版设置成为官方默认模版。<br>
  ·升级遇到问题请联系骑士客服处理!<br>

  </td>
 </tr>
 <tr>
   <td align="center"   class="txt">
   <input type="button" value="我知道了"  onclick="window.location='?act=check'"/>   </td>
 </tr>
</table>
 

	  <?php }?>
	  <?php if ($step=="2") {?>
	    <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" id="update">

 <tr>
        <td height="30" bgcolor="#EFF9FE"  style=" border-bottom:1px  #C6E6F4  solid;border-top:1px #C6E6F4  solid;"><strong>&nbsp;&nbsp;目录权限检查</strong></td>
      </tr>
 <tr>
   <td   >
   
   
   
   
     <table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td width="200" height="30"   style="border-bottom:1px  #CADFF0 solid; padding-left:20px;"><strong>目录</strong></td>
            <td align="center" style="border-bottom:1px #CADFF0 solid"><strong>读取权限</strong></td>
            <td align="center" style="border-bottom:1px #CADFF0 solid"><strong>写入权限</strong></td>
          </tr>
		  <?php
		  foreach ($dir_check as $val)
		  {
		  ?>
          <tr>
            <td style="padding-left:20px;" height="23"><?php echo $val['dir']?></td>
            <td align="center"><?php echo $val['read']?></td>
            <td align="center"><?php echo $val['write']?></td>
          </tr>
		  <?php }?>
      </table>
		<div align="center"  style="color:#FF0000; margin-top:15px; margin-bottom:10px;">
	如果您已确认以上目录权限正常,请点击开始升级，升级过程中请不要关闭窗口<br /><br />

<input type="button" value="下一步"   onclick="window.location='?step=3'"  id="BTNupdate"/>
		</div>
   
   
   <!--<input type="button" value="开始升级"  onclick="window.location='?act=update'" id="BTNupdate"/> -->
   
   
   </td>
 </tr>
</table>
 
 <?php }?> 
   <?php if ($step=="3") {?>
	    <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" id="update">

 <tr>
        <td height="30" bgcolor="#EFF9FE"  style=" border-bottom:1px  #C6E6F4  solid;border-top:1px #C6E6F4  solid;"><strong>&nbsp;&nbsp;请输入商业用户授权码</strong></td>
      </tr>
 <tr>
   <td   >
   <br /><br />
     
     <form id="form1" name="form1" method="post" action="index.php?act=upit">
	  <div align="center"  style="color:#FF0000; margin-top:15px; margin-bottom:10px;">
	  <table width="500" border="0" align="center" cellpadding="0" cellspacing="5">
       <tr>
         <td width="180" align="right">请输入您的授权码：</td>
         <td align="left"><label>
             <input name="key" type="text" id="key" size="30" />
         </label></td>
       </tr>
     </table>
	如果您不知道您的授权码，请找骑士客服获取。每个版本升级授权码只可以使用1次，遇到问题请及时联系骑士客服处理。
	<br />
	点击开始升级，整个过程可能需要3-5分钟，升级过程中请务必不要关闭窗口<br /><br />

<input name="提交" type="submit"    value="开始升级"  id="BTNupdate"/>
	  </div>
   
   
    
      </form>
      <!--<input type="button" value="开始升级"  onclick="window.location='?act=update'" id="BTNupdate"/> -->
   
   
   </td>
 </tr>
</table>
<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" style=" display:none;" id="updatewait">

 <tr>
        <td height="30" bgcolor="#EFF9FE"  style=" border-bottom:1px  #C6E6F4  solid;border-top:1px #C6E6F4  solid;"><strong>&nbsp;&nbsp;升级中</strong></td>
  </tr>
 <tr>
   <td align="center"  style="color:#009900"   >
   
   <br />
   <img src="images/30.gif" />   <br />
   <br />
   正在升级，整个过程可能需要3-5分钟，请务必不要关闭窗口</td>
 </tr>
</table>
<script type="text/javascript"> 
$(document).ready(function()
{
 		$("#BTNupdate").click(function(){
		$("#update").hide();
		$("#updatewait").show();
				 
				});	
});
</script>
 <?php }?> 
<?php if ($step=="4")
 {
 ?>
	    <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0"  >

 <tr>
        <td height="30" bgcolor="#EFF9FE"  style=" border-bottom:1px  #C6E6F4  solid;border-top:1px #C6E6F4  solid;"><strong>&nbsp;&nbsp;升级成功</strong></td>
      </tr>
 <tr>
   <td align="center"  style="color: #FF0000"   >
   
   <br />
   <br />
  恭喜，数据库升级成功！请上传骑士人才系统<?php echo $version_new?>文件，覆盖<?php echo $version_old?>完成升级,升级后请登录后台更新缓存！</td>
 </tr>
</table>
	    <?php
		 }
		 ?>	
</body>
</html>
<?php
function runquery($query)
{
	global $db, $pre;
	$query = str_replace("\r\n", "\n",$query);
	$query = str_replace("\r", "\n", str_replace(' qs_', ' '.$pre, $query));
	$query=str_replace(' `qs_', ' `'.$pre, $query);
	$query = str_replace("AS a,qs_", "AS a,".$pre,$query);
	$expquery = explode(";\n", $query);
	foreach($expquery as $sql)
	{
		$sql = trim($sql);
		if($sql == '' || $sql[0] == '#') continue;
		if(strtoupper(substr($sql, 0, 12)) == 'CREATE TABLE')
		{
		$db->query(createtable($sql, QISHI_DBCHARSET));
		}
		else
		{
		$db->query($sql);
		}
	}
	return true;
}
function createtable($sql, $dbcharset)
{
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
		(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
}
function check_dirs($dirs)
{
    $checked_dirs = array();
    foreach ($dirs AS $k=> $dir)
    {
	$checked_dirs[$k]['dir'] = $dir;
        if (!file_exists(QISHI_ROOT_PATH .'/'. $dir))
        {
            $checked_dirs[$k]['read'] = '<span style="color:red;">目录不存在</span>';
			$checked_dirs[$k]['write'] = '<span style="color:red;">目录不存在</span>';
        }
		else
		{		
        if (is_readable(QISHI_ROOT_PATH.'/'.$dir))
        {
            $checked_dirs[$k]['read'] = '<span style="color:green;">√可读</span>';
        }else{
            $checked_dirs[$k]['read'] = '<span sylt="color:red;">×不可读</span>';
        }
        if(is_writable(QISHI_ROOT_PATH.'/'.$dir)){
        	$checked_dirs[$k]['write'] = '<span style="color:green;">√可写</span>';
        }else{
        	$checked_dirs[$k]['write'] = '<span style="color:red;">×不可写</span>';
        }
		}
    }
    return $checked_dirs;
}  
function updataopen($url,$limit = 0, $post = '', $cookie = '', $bysocket = FALSE	, $ip = '', $timeout = 15, $block = TRUE, $encodetype  = 'URLENCOD')
{
		$return = '';
		$matches = parse_url($url);
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;

		if($post) {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$boundary = $encodetype == 'URLENCODE' ? '' : ';'.substr($post, 0, trim(strpos($post, "\n")));
			$out .= $encodetype == 'URLENCODE' ? "Content-Type: application/x-www-form-urlencoded\r\n" : "Content-Type: multipart/form-data$boundary\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host:$port\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
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

		if(function_exists('fsockopen')) {
			$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} elseif (function_exists('pfsockopen')) {
			$fp = @pfsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} else {
			$fp = false;
		}

		if(!$fp) {
			return '';
		} else {
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out']) {
				while (!feof($fp)) {
					if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
						break;
					}
				}

				$stop = false;
				while(!feof($fp) && !$stop) {
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if($limit) {
						$limit -= strlen($data);
						$stop = $limit <= 0;
					}
				}
			}
			@fclose($fp);
			return $return;
		}
}
function update_inserttable($tablename, $insertsqlarr, $returnid=0, $replace = false, $silent=0) {
	global $db;
	$insertkeysql = $insertvaluesql = $comma = '';
	foreach ($insertsqlarr as $insert_key => $insert_value) {
		$insertkeysql .= $comma.'`'.$insert_key.'`';
		$insertvaluesql .= $comma.'\''.addslashes($insert_value).'\'';
		$comma = ', ';
	}
	$method = $replace?'REPLACE':'INSERT';
	$state = $db->query($method." INTO $tablename ($insertkeysql) VALUES ($insertvaluesql)", $silent?'SILENT':'');
	if($returnid && !$replace) {
		return $db->insert_id();
	}else {
	    return $state;
	} 
}
function update_updatetable($tablename, $setsqlarr, $wheresqlarr, $silent=0) {
	global $db;
	$setsql = $comma = '';
	foreach ($setsqlarr as $set_key => $set_value) {
		if(is_array($set_value)) {
			$setsql .= $comma.'`'.$set_key.'`'.'='.$set_value[0];
		} else {
			$setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
		}
		$comma = ', ';
	}
	$where = $comma = '';
	if(empty($wheresqlarr)) {
		$where = '1';
	} elseif(is_array($wheresqlarr)) {
		foreach ($wheresqlarr as $key => $value) {
			$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	return $db->query("UPDATE ".($tablename)." SET ".$setsql." WHERE ".$where, $silent?"SILENT":"");
}
?>