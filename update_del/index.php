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
	exit("û���ҵ�74CMS�ĳ���Ŀ¼��");
	}
require_once(dirname(__FILE__).'/mysql.class.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
$version_old = '3.4';
$version_new = '3.5';
function update_category($name,$id){
		switch ($name) 
		{
			case '��Ŀ����/��Ʒ����':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=705;
				$catearr['category_cn']='��Ʒ����/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����Ŀ����/����':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=715;
				$catearr['category_cn']='�������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ŀִ��/Э����Ա':
				$catearr['topclass']=74;
				$catearr['category']=78;
				$catearr['subclass']=729;
				$catearr['category_cn']='IT��Ŀִ��/Э��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������ʦ':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=715;
				$catearr['category_cn']='�������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ϵͳ����ʦ':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=718;
				$catearr['category_cn']='ϵͳ����/�ܹ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ϵͳ����ʦ':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=715;
				$catearr['category_cn']='�������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�з�����ʦ':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=701;
				$catearr['category_cn']='�������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ӳ������ʦ':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=723;
				$catearr['category_cn']='Ӳ������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ӳ�����Թ���ʦ':
				$catearr['topclass']=74;
				$catearr['category']=79;
				$catearr['subclass']=735;
				$catearr['category_cn']='Ӳ������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������Թ���ʦ':
				$catearr['topclass']=74;
				$catearr['category']=79;
				$catearr['subclass']=734;
				$catearr['category_cn']='�������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ݿ⹤��ʦ':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=716;
				$catearr['category_cn']='���ݿ⿪��/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=1;
				$catearr['category']=4;
				$catearr['subclass']=574;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��վ�Ż�/SEO�ƹ�':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=707;
				$catearr['category_cn']='��վ�Ż�/SEO';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������������ʦ':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=701;
				$catearr['category_cn']='�������������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������Ƶ��������ʦ':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=742;
				$catearr['category_cn']='����ͨ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ͨ�ż�������ʦ':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=737;
				$catearr['category_cn']='ͨ�ż�������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ϣ��ȫ����ʦ':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=711;
				$catearr['category_cn']='������Ϣ��ȫ����ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���繤��ʦ':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=710;
				$catearr['category_cn']='���繤��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��վӪ�˾���/����':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=703;
				$catearr['category_cn']='��վ��Ӫ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��վ�߻�':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=708;
				$catearr['category_cn']='��վ�߻�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��վ�༭':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=709;
				$catearr['category_cn']='��վ�༭';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҳ���/����':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=699;
				$catearr['category_cn']='��ҳ���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������Ա/����':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=714;
				$catearr['category_cn']='����������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ý������뿪��':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=927;
				$catearr['category_cn']='��ý�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����֧�ֹ���ʦ':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=603;
				$catearr['category_cn']='�����ͷ�/����֧��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ϵͳά������ʦ':
				$catearr['topclass']=74;
				$catearr['category']=75;
				$catearr['subclass']=692;
				$catearr['category_cn']='�����ά��/ά��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������Ա':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=702;
				$catearr['category_cn']='�������Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ϸ����뿪��':
				$catearr['topclass']=74;
				$catearr['category']=76;
				$catearr['subclass']=713;
				$catearr['category_cn']='��Ϸ���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����������ְλ':
				$catearr['topclass']=74;
				$catearr['category']=75;
				$catearr['subclass']=698;
				$catearr['category_cn']='���������Ӧ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ӫ���ܼ�':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=580;
				$catearr['category_cn']='�г�Ӫ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�г��ܼ�':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=577;
				$catearr['category_cn']='�г��ܼ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����ܼ�':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=542;
				$catearr['category_cn']='�����ܼ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����ܼ�':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=787;
				$catearr['category_cn']='�����з�����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����ܼ�':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=649;
				$catearr['category_cn']='�����ܼ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����ܼ�':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=637;
				$catearr['category_cn']='�����ܼ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������Դ�ܼ�':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=624;
				$catearr['category_cn']='������Դ�ܼ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ӫ�ܼ�':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=623;
				$catearr['category_cn']='������Ӫ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ϣ�ܼ�':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=971;
				$catearr['category_cn']='�鱨��Ϣ����/����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ܾ���':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=615;
				$catearr['category_cn']='���ܾ���/���ܲ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ܾ���/CEO':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=614;
				$catearr['category_cn']='CEO/�ܲ�/�ܾ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����ܼ�/����':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=618;
				$catearr['category_cn']='���´�/�ֹ�˾����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ܲ�����/�ܾ�������':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=616;
				$catearr['category_cn']='�ܲ�����/�ܾ�������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ŀ�ϻ���':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=623;
				$catearr['category_cn']='������Ӫ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ž���':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=620;
				$catearr['category_cn']='���ž���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ֹ�˾/���´�����':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=618;
				$catearr['category_cn']='���´�/�ֹ�˾����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������ְλ':
				$catearr['topclass']=19;
				$catearr['category']=20;
				$catearr['subclass']=623;
				$catearr['category_cn']='������Ӫ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�г�������':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=577;
				$catearr['category_cn']='�г��ܼ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�г�����':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=579;
				$catearr['category_cn']='�г�רԱ/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�г��߻�����':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=581;
				$catearr['category_cn']='�г��߻�����/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�г���������':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=583;
				$catearr['category_cn']='�г�����/ҵ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�г�����/����רԱ':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=583;
				$catearr['category_cn']='�г�����/ҵ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ƷƷ�ƾ���/����':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=584;
				$catearr['category_cn']='Ʒ�ƾ���/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ͻ���������':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=601;
				$catearr['category_cn']='�ͻ���ϵ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ͻ�ά������':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=601;
				$catearr['category_cn']='�ͻ���ϵ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ͻ�����':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=557;
				$catearr['category_cn']='�ͻ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=556;
				$catearr['category_cn']='����Ա/����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=556;
				$catearr['category_cn']='����Ա/����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���������':
				$catearr['topclass']=169;
				$catearr['category']=170;
				$catearr['subclass']=905;
				$catearr['category_cn']='���ͻ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���߻�':
				$catearr['topclass']=169;
				$catearr['category']=170;
				$catearr['subclass']=908;
				$catearr['category_cn']='�İ�/�߻�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������':
				$catearr['topclass']=169;
				$catearr['category']=170;
				$catearr['subclass']=907;
				$catearr['category_cn']='��洴��/���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�İ�����':
				$catearr['topclass']=169;
				$catearr['category']=170;
				$catearr['subclass']=908;
				$catearr['category_cn']='�İ�/�߻�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ؾ���/����':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=588;
				$catearr['category_cn']='���ؾ���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ý�龭��':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=591;
				$catearr['category_cn']='ý�龭��/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ý���ƹ�רԱ':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=591;
				$catearr['category_cn']='ý�龭��/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�г�Ӫ��רԱ':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=580;
				$catearr['category_cn']='�г�Ӫ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�г��󻮾���/����':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=581;
				$catearr['category_cn']='�г��߻�����/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�г���רԱ':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=581;
				$catearr['category_cn']='�г��߻�����/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������/����':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=590;
				$catearr['category_cn']='������/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����רԱ':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=590;
				$catearr['category_cn']='������/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ӫ����ְλ':
				$catearr['topclass']=1;
				$catearr['category']=5;
				$catearr['subclass']=593;
				$catearr['category_cn']='�����г�/�߻�/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���۲�����':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=543;
				$catearr['category_cn']='���۾���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ҵ����չ����/����':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=550;
				$catearr['category_cn']='ҵ����չ(BD)����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ͻ�����':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=551;
				$catearr['category_cn']='��ͻ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���۹���ʦ':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=555;
				$catearr['category_cn']='���۹���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������/����':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=545;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=548;
				$catearr['category_cn']='�������۾���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ͻ�����':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=547;
				$catearr['category_cn']='�ͻ�����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���´�����/����':
				$catearr['topclass']=1;
				$catearr['category']=20;
				$catearr['subclass']=618;
				$catearr['category_cn']='���´�/�ֹ�˾����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���澭��':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=548;
				$catearr['category_cn']='�ͻ�����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������/����':
				$catearr['topclass']=1;
				$catearr['category']=2;
				$catearr['subclass']=549;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������':
				$catearr['topclass']=1;
				$catearr['category']=4;
				$catearr['subclass']=571;
				$catearr['category_cn']='������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����רԱ/����':
				$catearr['topclass']=1;
				$catearr['category']=4;
				$catearr['subclass']=572;
				$catearr['category_cn']='����רԱ/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���۴���':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=554;
				$catearr['category_cn']='ҵ��Ա/���۴���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ͻ�����/��ѯ':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=970;
				$catearr['category_cn']='��ѯԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ǰ/�ۺ󹤳�ʦ':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=559;
				$catearr['category_cn']='��ǰ����֧��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѯ����/��������':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=594;
				$catearr['category_cn']='��������/�绰�ͷ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�绰����':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=556;
				$catearr['category_cn']='�绰����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�绰����':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=561;
				$catearr['category_cn']='�绰����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ͷ�רԱ':
				$catearr['topclass']=1;
				$catearr['category']=3;
				$catearr['subclass']=596;
				$catearr['category_cn']='�ͷ�רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������ְλ':
				$catearr['topclass']=1;
				$catearr['category']=6;
				$catearr['subclass']=603;
				$catearr['category_cn']='�����ͷ�/����֧��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ƽ�����������':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=922;
				$catearr['category_cn']='ƽ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ά�������':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=925;
				$catearr['category_cn']='����/3D���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҳ�������':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=928;
				$catearr['category_cn']='�����༭/���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ʒ��װ���':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=929;
				$catearr['category_cn']='��װ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����װ�����':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=924;
				$catearr['category_cn']='�������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҵ�������':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=931;
				$catearr['category_cn']='����Ʒ/�鱦���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҵ/��Ʒ���':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=932;
				$catearr['category_cn']='��ҵ���/��Ʒ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��װ/�������':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=934;
				$catearr['category_cn']='��װ/��֯Ʒ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�Ҿ�/�Ҿ���Ʒ���':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=930;
				$catearr['category_cn']='�Ҿ�/�Ҿ���Ʒ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=935;
				$catearr['category_cn']='�����������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�鱦���':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=931;
				$catearr['category_cn']='����Ʒ/�鱦���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=935;
				$catearr['category_cn']='�����������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ƹ�����Ա':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=744;
				$catearr['category_cn']='������Ŀ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/ͼ�����':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=928;
				$catearr['category_cn']='�����༭/���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ý�������':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=927;
				$catearr['category_cn']='��ý�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ý�����':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=927;
				$catearr['category_cn']='��ý�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/չ�����':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=926;
				$catearr['category_cn']='����/����/չ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=935;
				$catearr['category_cn']='�����������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���������ְλ':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=935;
				$catearr['category_cn']='�����������';
				update_data($catearr,$id);
				unset($catearr);
				break;	
			case '���ӹ���ʦ':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=865;
				$catearr['category_cn']='���ӹ���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������ʦ':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=882;
				$catearr['category_cn']='��������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���Ź���ʦ':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=742;
				$catearr['category_cn']='����ͨ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ͨ�ż�������ʦ':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=737;
				$catearr['category_cn']='ͨ�ż�������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ƕ��ʽϵͳ�������':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=724;
				$catearr['category_cn']='Ƕ��ʽ��/Ӳ������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��·����ʦ':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=737;
				$catearr['category_cn']='ͨ�ż�������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�Զ����ƹ���ʦ':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=883;
				$catearr['category_cn']='�Զ����ƹ���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ߵ缼��':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=738;
				$catearr['category_cn']='����ͨ�Ź���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ߴ��乤��ʦ':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=739;
				$catearr['category_cn']='����ͨ�Ź���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ͨ�Ź���ʦ':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=738;
				$catearr['category_cn']='����ͨ�Ź���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ƶ�ͨ�Ź���ʦ':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=740;
				$catearr['category_cn']='�ƶ�ͨ�Ź���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ͨ�Ź���ʦ':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=739;
				$catearr['category_cn']='����ͨ�Ź���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ֵ��Ʒ��������ʦ':
				$catearr['topclass']=74;
				$catearr['category']=80;
				$catearr['subclass']=742;
				$catearr['category_cn']='����ͨ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����Ʒ��������ʦ':
				$catearr['topclass']=74;
				$catearr['category']=77;
				$catearr['subclass']=724;
				$catearr['category_cn']='Ƕ��ʽ��/Ӳ������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ɵ�·(IC)���':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=870;
				$catearr['category_cn']='��ͼ/��·���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�뵼�幤��ʦ':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=871;
				$catearr['category_cn']='���Ӳ���/�뵼��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���칤��ʦ/����Ա':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=865;
				$catearr['category_cn']='���ӹ���ʦ/����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ӵ���ά�޹���ʦ':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=874;
				$catearr['category_cn']='�����豸ά��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������ͨ����ְλ':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=880;
				$catearr['category_cn']='��������/�뵼��/�Ǳ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������ʦ':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=890;
				$catearr['category_cn']='��������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��е����ʦ':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=828;
				$catearr['category_cn']='��е����ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ģ�߹���ʦ':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=830;
				$catearr['category_cn']='ģ�߹���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ģ�����':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=851;
				$catearr['category_cn']='ģ�߹�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��е��ͼԱ':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=827;
				$catearr['category_cn']='��е���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���繤��ʦ':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=884;
				$catearr['category_cn']='���繤��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����һ�廯':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=884;
				$catearr['category_cn']='���繤��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���칤��ʦ':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='������е';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���칤��ʦ':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='������е';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ע�ܹ���ʦ':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='������е';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'CNC����ʦ':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='������е';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѹ����ʦ':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='������е';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��е���ʦ':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=827;
				$catearr['category_cn']='��е���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ܻ�е':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=833;
				$catearr['category_cn']='���ܻ�е/�����Ǳ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����Ǳ�':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=833;
				$catearr['category_cn']='���ܻ�е/�����Ǳ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��֯��е':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='������е';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��еά�޹���ʦ':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=831;
				$catearr['category_cn']='��еά�޹���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������':
				$catearr['topclass']=116;
				$catearr['category']=119;
				$catearr['subclass']=819;
				$catearr['category_cn']='������ƹ���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���������':
				$catearr['topclass']=116;
				$catearr['category']=119;
				$catearr['subclass']=819;
				$catearr['category_cn']='������е';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ӹ���ʦ':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=841;
				$catearr['category_cn']='�纸/í����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��¯����ʦ':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=840;
				$catearr['category_cn']='�յ�/����/��¯��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'װ�乤��ʦ':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=845;
				$catearr['category_cn']='װж/�泵��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������ʦ':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=853;
				$catearr['category_cn']='������������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�豸ά�ް�װ��Ա':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=849;
				$catearr['category_cn']='��װ/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Դˮ��':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=853;
				$catearr['category_cn']='������������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ұ��':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=896;
				$catearr['category_cn']='���ʿ���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������ʦ':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=882;
				$catearr['category_cn']='��������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Դ����������':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=892;
				$catearr['category_cn']='��Դ����������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ˮ��/ˮ�繤��ʦ':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=889;
				$catearr['category_cn']='ˮ��/ˮ�繤��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/��������ʦ':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=897;
				$catearr['category_cn']='��������/��Դ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�յ�/���ܹ���ʦ':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=894;
				$catearr['category_cn']='�յ�/���ܹ���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������е��ְλ':
				$catearr['topclass']=116;
				$catearr['category']=120;
				$catearr['subclass']=837;
				$catearr['category_cn']='������е';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����ܹ���ʦ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=743;
				$catearr['category_cn']='�߼���������ʦ/�ܹ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������ʦ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=753;
				$catearr['category_cn']='��ľ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������ʦ':
				$catearr['topclass']=136;
				$catearr['category']=138;
				$catearr['subclass']=882;
				$catearr['category_cn']='��������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ˮ����ʦ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=761;
				$catearr['category_cn']='����ˮ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ůͨ����ʦ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=762;
				$catearr['category_cn']='����ůͨ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ṹ����ʦ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=754;
				$catearr['category_cn']='�ṹ����ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������ʦ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=751;
				$catearr['category_cn']='����ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ṹ���ʦ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=754;
				$catearr['category_cn']='�ṹ����ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��۹���ʦ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=746;
				$catearr['category_cn']='���/Ԥ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ԥ����':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=746;
				$catearr['category_cn']='���/Ԥ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���̲�����':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=744;
				$catearr['category_cn']='������Ŀ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ŀ����/���̼���':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=769;
				$catearr['category_cn']='���ز���Ŀ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ز�����':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=770;
				$catearr['category_cn']='���ز�����/�߻�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ز��߻�':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=770;
				$catearr['category_cn']='���ز�����/�߻�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ز�����':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=776;
				$catearr['category_cn']='���ز�����ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ز�����':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=773;
				$catearr['category_cn']='���ز�����/��ҵ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=779;
				$catearr['category_cn']='�������ز�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҵ����/��¥Ա':
				$catearr['topclass']=96;
				$catearr['category']=98;
				$catearr['subclass']=773;
				$catearr['category_cn']='���ز�����/��ҵ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ܴ���/�ۺϲ���':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=764;
				$catearr['category_cn']='���ܴ���ϵͳ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��漼��':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=767;
				$catearr['category_cn']='���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������/���й滮':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=757;
				$catearr['category_cn']='����滮���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������/���й滮':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=750;
				$catearr['category_cn']='����滮���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������ʦ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=768;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ļǽ����ʦ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=760;
				$catearr['category_cn']='Ļǽ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������װ�����':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=763;
				$catearr['category_cn']='�������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��·�����������':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=756;
				$catearr['category_cn']='·��/�ۿ�/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ʩ��Ա/����Ա':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=747;
				$catearr['category_cn']='ʩ��Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '԰��/�̻�':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=758;
				$catearr['category_cn']='԰��/�������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '԰��/�������':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=758;
				$catearr['category_cn']='԰��/�������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������ͼ/��ģ/���':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=767;
				$catearr['category_cn']='���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҵ����':
				$catearr['topclass']=96;
				$catearr['category']=99;
				$catearr['subclass']=781;
				$catearr['category_cn']='��ҵ����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҵά����Ա':
				$catearr['topclass']=96;
				$catearr['category']=99;
				$catearr['subclass']=781;
				$catearr['category_cn']='��ҵ����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������ְλ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=768;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������Դ����/����':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=625;
				$catearr['category_cn']='������Դ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������/����':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=638;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����רԱ/����':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=639;
				$catearr['category_cn']='����רԱ/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����רԱ/����':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=627;
				$catearr['category_cn']='������ԴרԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ƸרԱ/����':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=630;
				$catearr['category_cn']='��ƸרԱ/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�칫������':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=640;
				$catearr['category_cn']='�칫������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=642;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�߼�����':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=642;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ƹ����':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=626;
				$catearr['category_cn']='������Դ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'н�ʸ�������/רԱ':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=631;
				$catearr['category_cn']='н�ʸ�������/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ч��������/רԱ':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=632;
				$catearr['category_cn']='��Ч���˾���/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ա����ѵ�뷢չ����':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=633;
				$catearr['category_cn']='��ѵ����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѵ������':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=633;
				$catearr['category_cn']='��ѵ����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѵ����/רԱ':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=634;
				$catearr['category_cn']='��ѵרԱ/��ѵʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'н�����ʦ':
				$catearr['topclass']=19;
				$catearr['category']=21;
				$catearr['subclass']=631;
				$catearr['category_cn']='н�ʸ�������/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ա/����':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=641;
				$catearr['category_cn']='��Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������Ա':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=645;
				$catearr['category_cn']='ͼ������/�ĵ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�Ӵ�/�ܻ�/����':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=643;
				$catearr['category_cn']='ǰ̨/�Ӵ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���Բ���Ա/����Ա':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=646;
				$catearr['category_cn']='���Բ���Ա/����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������Դ��ϢϵͳרԱ':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=648;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������ְ��ְλ':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=648;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�곤':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1007;
				$catearr['category_cn']='�곤/��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1043;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1007;
				$catearr['category_cn']='�곤/��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ɹ�������':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=611;
				$catearr['category_cn']='�ɹ�����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1007;
				$catearr['category_cn']='�곤/��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���Ա/����Ա':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1010;
				$catearr['category_cn']='���Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1011;
				$catearr['category_cn']='����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1009;
				$catearr['category_cn']='����Ա/����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ӪҵԱ/��Ա':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1008;
				$catearr['category_cn']='��Ա/ӪҵԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1011;
				$catearr['category_cn']='����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=74;
				$catearr['category']=75;
				$catearr['subclass']=698;
				$catearr['category_cn']='���������Ӧ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա/�ڱ�':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1012;
				$catearr['category_cn']='����Ա/�ڱ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ʦ/������ӹ�':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='��������/����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ʳ/����ʳƷ�ӹ�':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='��������/����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������۷�����':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1014;
				$catearr['category_cn']='�����ٻ�/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������/��ķ':
				$catearr['topclass']=241;
				$catearr['category']=243;
				$catearr['subclass']=1018;
				$catearr['category_cn']='��������/��ķ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=241;
				$catearr['category']=243;
				$catearr['subclass']=1015;
				$catearr['category_cn']='����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����':
				$catearr['topclass']=241;
				$catearr['category']=243;
				$catearr['subclass']=1015;
				$catearr['category_cn']='����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ճ���Ա':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1050;
				$catearr['category_cn']='��½���˲���Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������Ա':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1050;
				$catearr['category_cn']='��½���˲���Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�г�˾��':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1049;
				$catearr['category_cn']='����/����/�೵˾��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1050;
				$catearr['category_cn']='��½���˲���Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ա':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1050;
				$catearr['category_cn']='��½���˲���Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;	
			case '˾��':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1048;
				$catearr['category_cn']='����˾��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���˹�':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1052;
				$catearr['category_cn']='�ٵ�Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��๤':
				$catearr['topclass']=241;
				$catearr['category']=243;
				$catearr['subclass']=1017;
				$catearr['category_cn']='����/��๤';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ֿⱣ��':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1042;
				$catearr['category_cn']='�ֿ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=648;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ѷ����':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='��������/����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������ְλ':
				$catearr['topclass']=19;
				$catearr['category']=22;
				$catearr['subclass']=648;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;

			case '��������':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1043;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1043;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����רԱ/����':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1044;
				$catearr['category_cn']='����רԱ/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ó�׾���/����':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=604;
				$catearr['category_cn']='����ó�׾���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ó����/����':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=604;
				$catearr['category_cn']='����ó�׾���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���侭��/����':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1043;
				$catearr['category_cn']='��������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ҵ�����':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=607;
				$catearr['category_cn']='ҵ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա/��֤Ա':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=608;
				$catearr['category_cn']='����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���Ա/�ٵ�Ա':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=613;
				$catearr['category_cn']='����ó��/�ɹ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ɹ�����/����':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=611;
				$catearr['category_cn']='�ɹ�����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ɹ�Ա/�ɹ�����':
				$catearr['topclass']=1;
				$catearr['category']=7;
				$catearr['subclass']=612;
				$catearr['category_cn']='�ɹ�Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ֿ����':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1042;
				$catearr['category_cn']='�ֿ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ӧ������':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1047;
				$catearr['category_cn']='���Ϲ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ӧ������/רԱ':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1054;
				$catearr['category_cn']='��������/��ͨ/�ִ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���Ͼ���':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1047;
				$catearr['category_cn']='���Ϲ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������/רԱ':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1047;
				$catearr['category_cn']='���Ϲ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ֿ⾭��/����':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1042;
				$catearr['category_cn']='�ֿ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ֿ����Ա':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1042;
				$catearr['category_cn']='�ֿ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���˴���':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1046;
				$catearr['category_cn']='���˴���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������ְλ':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1054;
				$catearr['category_cn']='��������/��ͨ/�ִ�';
				update_data($catearr,$id);
				unset($catearr);
				break;

			case '������':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=650;
				$catearr['category_cn']='������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ԥ������':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=651;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ɱ�����/����':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=656;
				$catearr['category_cn']='�ɱ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������/����':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=650;
				$catearr['category_cn']='������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=652;
				$catearr['category_cn']='��ƾ���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ʽ�����':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=652;
				$catearr['category_cn']='��ƾ���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ͷ������':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=651;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
			case '��������':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=651;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������ʦ':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=655;
				$catearr['category_cn']='�������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=653;
				$catearr['category_cn']='���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=654;
				$catearr['category_cn']='����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
				$catearr['topclass']=241;
				$catearr['category']=242;
				$catearr['subclass']=1011;
				$catearr['category_cn']='����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=658;
				$catearr['category_cn']='���Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ͳ��':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=659;
				$catearr['category_cn']='ͳ��Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ɱ�����Ա':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=657;
				$catearr['category_cn']='�ɱ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ʽ�רԱ':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=660;
				$catearr['category_cn']='��������/���/˰��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ƾ���/����':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=652;
				$catearr['category_cn']='��ƾ���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '˰����/����':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=652;
				$catearr['category_cn']='��ƾ���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '˰��רԱ/����':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=660;
				$catearr['category_cn']='��������/���/˰��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������ְλ':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=660;
				$catearr['category_cn']='��������/���/˰��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�Ƶ�/��������':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1028;
				$catearr['category_cn']='����/��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/ǰ������':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1021;
				$catearr['category_cn']='���þ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '¥��/���澭��':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1022;
				$catearr['category_cn']='¥�澭��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1023;
				$catearr['category_cn']='ǰ���Ӵ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1024;
				$catearr['category_cn']='�ͷ�����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ʦ':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1031;
				$catearr['category_cn']='��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ʦ':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1032;
				$catearr['category_cn']='����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1025;
				$catearr['category_cn']='����/���й���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/ӭ��/�Ӵ�':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1023;
				$catearr['category_cn']='ǰ���Ӵ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������/����Ա':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='��������/����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ӪҵԱ/����Ա':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1029;
				$catearr['category_cn']='����/��������Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ʊ/��������':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1027;
				$catearr['category_cn']='��Ʊ/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������Ա':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='��������/����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ʦ':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1032;
				$catearr['category_cn']='����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ӫ��ʦ':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=989;
				$catearr['category_cn']='Ӫ��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����Ƶ���ְλ':
				$catearr['topclass']=241;
				$catearr['category']=244;
				$catearr['subclass']=1033;
				$catearr['category_cn']='��������/����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѧ������Ա':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=956;
				$catearr['category_cn']='�������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѧ����/��ʦ':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=962;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ְҵ������ʦ':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=962;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѧ��ʦ':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=947;
				$catearr['category_cn']='��ѧ��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Сѧ��ʦ':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=948;
				$catearr['category_cn']='Сѧ��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�׶�����':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=949;
				$catearr['category_cn']='�׽�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������ѵ':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=957;
				$catearr['category_cn']='��ѵʦ/��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ʦ/����':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=958;
				$catearr['category_cn']='��ѵ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ҽ�':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=955;
				$catearr['category_cn']='�ҽ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ְ��ʦ':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=962;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѵ����':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=958;
				$catearr['category_cn']='��ѵ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѵ��ʦ':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=957;
				$catearr['category_cn']='��ѵʦ/��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѵ�߻�':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=960;
				$catearr['category_cn']='��ѵ�߻�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѵ����':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=958;
				$catearr['category_cn']='��ѵ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������ְλ':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=962;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/����ܼ�':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=912;
				$catearr['category_cn']='����/�ർ/�����ܼ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ܱ�/���ܱ�':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=936;
				$catearr['category_cn']='�ܱ�/���ܱ�/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=912;
				$catearr['category_cn']='����/�ർ/�����ܼ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ƭ��':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=915;
				$catearr['category_cn']='������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=914;
				$catearr['category_cn']='������/����Ա/DJ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ա':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=913;
				$catearr['category_cn']='��Ա/ģ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ŀ������':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=914;
				$catearr['category_cn']='������/����Ա/DJ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ģ��':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=913;
				$catearr['category_cn']='��Ա/ģ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ý�������װ':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=929;
				$catearr['category_cn']='��װ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ױʦ':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1035;
				$catearr['category_cn']='��ױʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ӱ�ӵƹ�':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=918;
				$catearr['category_cn']='��̨���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ӱ�����񹤳�':
				$catearr['topclass']=169;
				$catearr['category']=172;
				$catearr['subclass']=927;
				$catearr['category_cn']='��ý�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ӱ������':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=911;
				$catearr['category_cn']='Ӱ�Ӳ߻�/������Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=920;
				$catearr['category_cn']='����Ӱ��/ý��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ӹ��̼���':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=920;
				$catearr['category_cn']='����Ӱ��/ý��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ּ���':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=938;
				$catearr['category_cn']='����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ӱ����':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=938;
				$catearr['category_cn']='����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ֱ༭':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=937;
				$catearr['category_cn']='�༭/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����༭':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=937;
				$catearr['category_cn']='�༭/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����༭':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=937;
				$catearr['category_cn']='�༭/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ӱ�Ӳ߻�/������Ա':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=911;
				$catearr['category_cn']='Ӱ�Ӳ߻�/������Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/׫����':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=937;
				$catearr['category_cn']='�༭/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ӱʦ/����ʦ':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=916;
				$catearr['category_cn']='��Ӱ/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=919;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�Ű����':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=940;
				$catearr['category_cn']='�Ű����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'У��/¼��':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=941;
				$catearr['category_cn']='У��/¼��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/����':
				$catearr['topclass']=169;
				$catearr['category']=173;
				$catearr['subclass']=943;
				$catearr['category_cn']='����/ӡˢ/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ý����ְλ':
				$catearr['topclass']=169;
				$catearr['category']=171;
				$catearr['subclass']=920;
				$catearr['category_cn']='����Ӱ��/ý��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ӣ��':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=973;
				$catearr['category_cn']='Ӣ�﷭��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=974;
				$catearr['category_cn']='���﷭��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=978;
				$catearr['category_cn']='���﷭��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=976;
				$catearr['category_cn']='���﷭��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=977;
				$catearr['category_cn']='���﷭��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=983;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=981;
				$catearr['category_cn']='�������﷭��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=980;
				$catearr['category_cn']='�������﷭��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������ַ���':
				$catearr['topclass']=203;
				$catearr['category']=207;
				$catearr['subclass']=983;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�繤':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=839;
				$catearr['category_cn']='�繤';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ˮ��':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=847;
				$catearr['category_cn']='ˮ��/ľ��/���Ṥ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ľ��':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=847;
				$catearr['category_cn']='ˮ��/ľ��/���Ṥ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ǯ��':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=842;
				$catearr['category_cn']='ǯ��/���޹�/�ӽ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�纸��':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=841;
				$catearr['category_cn']='�纸/í����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���Ṥ':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=847;
				$catearr['category_cn']='ˮ��/ľ��/���Ṥ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��¯��':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=840;
				$catearr['category_cn']='�յ�/����/��¯��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�泵��/����/ĥ��':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=845;
				$catearr['category_cn']='װж/�泵��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ϳ��/��ѹ��/�๤':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=843;
				$catearr['category_cn']='��/ĥ/ϳ/��/�۹�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�и��/�ӽ�':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=844;
				$catearr['category_cn']='�и��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ģ�߹�':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=851;
				$catearr['category_cn']='ģ�߹�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�յ���/���ݹ�':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=840;
				$catearr['category_cn']='�յ�/����/��¯��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=848;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���޹�':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=853;
				$catearr['category_cn']='������������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�չ�':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=852;
				$catearr['category_cn']='�չ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������ְλ':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=853;
				$catearr['category_cn']='�չ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�Ƹ�':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=858;
				$catearr['category_cn']='Ƥ��/ëƤ�ӹ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=855;
				$catearr['category_cn']='��ɴ/֯��/��֯';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ь':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=857;
				$catearr['category_cn']='Ьñ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ӡˢ':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=860;
				$catearr['category_cn']='ӡȾ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ⱦ������':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=860;
				$catearr['category_cn']='ӡȾ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��װ��֯':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=855;
				$catearr['category_cn']='��ɴ/֯��/��֯';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ʳƷ����':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=902;
				$catearr['category_cn']='ʳƷ/�����з�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�մɼ���':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=904;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ֽ����ֽ����':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=904;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������μӹ�':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=904;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ܹ�ʦ':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=899;
				$catearr['category_cn']='ʵ�����о�Ա/����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ϸ��Ͽ���':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=856;
				$catearr['category_cn']='��װ����/�ư�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ϸ��ϲɹ�':
				$catearr['topclass']=116;
				$catearr['category']=122;
				$catearr['subclass']=862;
				$catearr['category_cn']='������װ/��֯Ʒ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�巿/�ͷ':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=753;
				$catearr['category_cn']='��ľ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/�ư�':
				$catearr['topclass']=96;
				$catearr['category']=97;
				$catearr['subclass']=751;
				$catearr['category_cn']='����ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���Է���Ա':
				$catearr['topclass']=74;
				$catearr['category']=75;
				$catearr['subclass']=695;
				$catearr['category_cn']='���Բ���Ա/����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ֽ��ʦ/���幤':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=850;
				$catearr['category_cn']='�ü���������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ô�':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=850;
				$catearr['category_cn']='�ü���������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����Ṥ��ְλ':
				$catearr['topclass']=116;
				$catearr['category']=121;
				$catearr['subclass']=853;
				$catearr['category_cn']='������������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1056;
				$catearr['category_cn']='������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѧӦ���ҵ��':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1056;
				$catearr['category_cn']='������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ר/ְУ��':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1056;
				$catearr['category_cn']='������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�о���':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1057;
				$catearr['category_cn']='��ѧ�о���Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����ɲ�':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1056;
				$catearr['category_cn']='������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ʵϰ��':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1055;
				$catearr['category_cn']='ʵϰ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѵ��':
				$catearr['topclass']=258;
				$catearr['category']=259;
				$catearr['subclass']=1056;
				$catearr['category_cn']='������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ϳ�ϼ���':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=904;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���⼼��':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=892;
				$catearr['category_cn']='��Դ����������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���⼼��':
				$catearr['topclass']=136;
				$catearr['category']=137;
				$catearr['subclass']=877;
				$catearr['category_cn']='����/����Ӽ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ʿ�̽':
				$catearr['topclass']=136;
				$catearr['category']=139;
				$catearr['subclass']=896;
				$catearr['category_cn']='���ʿ���/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ũ������ҵ':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1059;
				$catearr['category_cn']='������ֳ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������ʦ':
				$catearr['topclass']=225;
				$catearr['category']=228;
				$catearr['subclass']=999;
				$catearr['category_cn']='��������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ˮ������ʦ':
				$catearr['topclass']=225;
				$catearr['category']=228;
				$catearr['subclass']=1002;
				$catearr['category_cn']='ˮ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ѱ��ʦ':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1061;
				$catearr['category_cn']='��ҽ/����ҽ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ũ������ҵ����':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1062;
				$catearr['category_cn']='԰��԰��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ũ��ʦ':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1058;
				$catearr['category_cn']='ũ��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ʦ':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1060;
				$catearr['category_cn']='����Ӫ��/�����з�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
				$catearr['topclass']=258;
				$catearr['category']=260;
				$catearr['subclass']=1059;
				$catearr['category_cn']='������ֳ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѯ�ܼ�/����':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=969;
				$catearr['category_cn']='��ѯ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѯ/����':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=968;
				$catearr['category_cn']='רҵ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѯרԱ/����':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=970;
				$catearr['category_cn']='��ѯԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������ѯ':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=972;
				$catearr['category_cn']='������ѯ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ϣ��ѯ/�н����':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=972;
				$catearr['category_cn']='������ѯ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ʦ':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=963;
				$catearr['category_cn']='��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ʦ����':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=963;
				$catearr['category_cn']='��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������Ա':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=965;
				$catearr['category_cn']='������Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'רҵ��ѵʦ':
				$catearr['topclass']=203;
				$catearr['category']=204;
				$catearr['subclass']=957;
				$catearr['category_cn']='��ѵʦ/��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҵ�������':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=968;
				$catearr['category_cn']='רҵ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ͷ����':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=968;
				$catearr['category_cn']='רҵ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ɹ���':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=964;
				$catearr['category_cn']='���ɹ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=965;
				$catearr['category_cn']='������Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�Ϲ澭��':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=967;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�Ϲ�רԱ':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=967;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '֪ʶ��Ȩ/ר������':
				$catearr['topclass']=203;
				$catearr['category']=205;
				$catearr['subclass']=966;
				$catearr['category_cn']='֪ʶ��Ȩ/ר������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������ѯ��ְλ':
				$catearr['topclass']=203;
				$catearr['category']=206;
				$catearr['subclass']=972;
				$catearr['category_cn']='������ѯ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '֤ȯ�ڻ�':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=661;
				$catearr['category_cn']='֤ȯ/�ڻ�/��㾭����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�����Ŵ�����':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=672;
				$catearr['category_cn']='�Ŵ�����/��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '֤ȯ����ʦ':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=662;
				$catearr['category_cn']='֤ȯ����ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���մ���/������':
				$catearr['topclass']=49;
				$catearr['category']=52;
				$catearr['subclass']=683;
				$catearr['category_cn']='���մ�����/������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ҵ����':
				$catearr['topclass']=49;
				$catearr['category']=52;
				$catearr['subclass']=677;
				$catearr['category_cn']='����ҵ����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ͻ�����/������':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=665;
				$catearr['category_cn']='�ͻ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ʽ����/�������':
				$catearr['topclass']=49;
				$catearr['category']=50;
				$catearr['subclass']=651;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ʲ�����':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=673;
				$catearr['category_cn']='�ʲ�����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ա/���л��':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=674;
				$catearr['category_cn']='���й�Ա/���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ʊ/�ڻ�������':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=663;
				$catearr['category_cn']='��Ʊ/�ڻ�������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/�����о�Ա':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=676;
				$catearr['category_cn']='����֤ȯ/�ڻ�/Ͷ��/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ͷ��/������Ŀ����':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=667;
				$catearr['category_cn']='Ͷ��/������Ŀ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ͷ��/��ƹ���':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=666;
				$catearr['category_cn']='Ͷ��/��ƹ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ʾ���/����רԱ':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=669;
				$catearr['category_cn']='���ʾ���/רԱ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ʽ���/��㽻��':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=664;
				$catearr['category_cn']='���/����/��ծ������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ʦ':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=670;
				$catearr['category_cn']='����/�䵱/����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������ְλ':
				$catearr['topclass']=49;
				$catearr['category']=51;
				$catearr['subclass']=676;
				$catearr['category_cn']='����֤ȯ/�ڻ�/Ͷ��/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ҽ��':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='ҽ��/ҽʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ҽʦ':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='ҽ��/ҽʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ҽҩ����':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=992;
				$catearr['category_cn']='����ҽԺ/ҽ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ҽ��':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=987;
				$catearr['category_cn']='����ҽ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=985;
				$catearr['category_cn']='ҽ�Ƽ�����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ٴ�ҽѧ':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='ҽ��/ҽʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҽ':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='ҽ��/ҽʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҽ':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='ҽ��/ҽʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ҽ':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=984;
				$catearr['category_cn']='ҽ��/ҽʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ʿ��/��ʿ':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=986;
				$catearr['category_cn']='��ʿ/������Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������/������Ա':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=986;
				$catearr['category_cn']='��ʿ/������Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ӫ��ʦ':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=989;
				$catearr['category_cn']='Ӫ��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ݻ�ױʦ/����ʦ':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1034;
				$catearr['category_cn']='����ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ҩ��ʦ':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=991;
				$catearr['category_cn']='ҩ������/ҩ��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ҽҩ����':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=996;
				$catearr['category_cn']='ҽҩ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ױ���':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=986;
				$catearr['category_cn']='��ʿ/������Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������ʦ':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1034;
				$catearr['category_cn']='����ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ʦ':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=985;
				$catearr['category_cn']='ҽ�Ƽ�����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ױ��ѵʦ':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=985;
				$catearr['category_cn']='��ױʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ħ/����':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1041;
				$catearr['category_cn']='��Ħ/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�������/����':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1038;
				$catearr['category_cn']='�������/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�٤/�赸��ʦ':
				$catearr['topclass']=241;
				$catearr['category']=245;
				$catearr['subclass']=1039;
				$catearr['category_cn']='�赸��ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҽ/����ҽ��':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=990;
				$catearr['category_cn']='��ҽ/����ҽ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����ҽ����ְλ':
				$catearr['topclass']=225;
				$catearr['category']=226;
				$catearr['subclass']=992;
				$catearr['category_cn']='����ҽԺ/ҽ��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���û���':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=898;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���ﻯ��':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=898;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������ҩ':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=994;
				$catearr['category_cn']='���＼����ҩ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���Ϲ���ʦ':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=901;
				$catearr['category_cn']='��/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ϳ���з�����ʦ':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=899;
				$catearr['category_cn']='ʵ�����о�Ա/����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѧҩƷ':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=993;
				$catearr['category_cn']='ҩƷ����/�ʹ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������ʦ':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=898;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ѧ��������Ա':
			case '��ױƷ�з���Ա':
			case '����ʵ�����о�Ա':
			case 'ҽҩ�����з���Ա':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=899;
				$catearr['category_cn']='ʵ�����о�Ա/����Ա';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ɫ����Ա':
			case '�л�����':
			case '�޻�����':
			case '��ϸ����':
			case '��������':
			case '�߷��ӻ���':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=898;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/��������':
				$catearr['topclass']=225;
				$catearr['category']=228;
				$catearr['subclass']=999;
				$catearr['category_cn']='��������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/��������':
				$catearr['topclass']=225;
				$catearr['category']=228;
				$catearr['subclass']=999;
				$catearr['category_cn']='��������ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ҩƷ����/��������':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=993;
				$catearr['category_cn']='ҩƷ����/�ʹ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���﹤��':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=994;
				$catearr['category_cn']='���＼����ҩ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ٴ�����/ҩƷע��':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=993;
				$catearr['category_cn']='ҩƷ����/�ʹ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ҽҩ����':
				$catearr['topclass']=225;
				$catearr['category']=227;
				$catearr['subclass']=996;
				$catearr['category_cn']='ҽҩ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'ʳƷ/�����з�':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=902;
				$catearr['category_cn']='ʳƷ/�����з�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����������ְλ':
				$catearr['topclass']=136;
				$catearr['category']=140;
				$catearr['subclass']=904;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����/������':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=788;
				$catearr['category_cn']='����/������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ܹ���ʦ/���ܹ���ʦ':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=789;
				$catearr['category_cn']='�ܹ�/���ܹ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������/��������':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=790;
				$catearr['category_cn']='��������/��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��Ʒ�������':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=800;
				$catearr['category_cn']='��ҵ���/��Ʒ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ȫ������Ա':
				$catearr['topclass']=116;
				$catearr['category']=118;
				$catearr['subclass']=812;
				$catearr['category_cn']='��ȫ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '������Ŀ����/����':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=791;
				$catearr['category_cn']='���鳤/��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case 'Ʒ��/��������(QA��QC)':
				$catearr['topclass']=116;
				$catearr['category']=118;
				$catearr['subclass']=805;
				$catearr['category_cn']='Ʒ�ʱ�֤/�������ƾ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��������':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=796;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���Ϲ���':
				$catearr['topclass']=241;
				$catearr['category']=246;
				$catearr['subclass']=1047;
				$catearr['category_cn']='���Ϲ���';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�豸����':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=794;
				$catearr['category_cn']='����/�豸����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ɹ�����':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=797;
				$catearr['category_cn']='�ɹ�����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ֿ����':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=795;
				$catearr['category_cn']='�ֿ����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '�ƻ�Ա':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=792;
				$catearr['category_cn']='�����ƻ�';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=793;
				$catearr['category_cn']='��������';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
			case '����Ա':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=803;
				$catearr['category_cn']='����/����';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���չ���ʦ':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=799;
				$catearr['category_cn']='��Ʒ/���չ���ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '��ҵ����ʦ��IE��':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=801;
				$catearr['category_cn']='��ҵ����ʦ';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '���Ϸ�������ʦ':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=802;
				$catearr['category_cn']='����ά��';
				update_data($catearr,$id);
				unset($catearr);
				break;
			case '����Ա':
			case 'ͳ��Ա':
			case '���칤��ʦ':
			case '������Ա':
			case '����������ְλ':
				$catearr['topclass']=116;
				$catearr['category']=117;
				$catearr['subclass']=802;
				$catearr['category_cn']='���������������';
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
	//ģ������
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
	exit("��������������� 74cms v".$version_old."��74cms v".$version_new);
	}
	$Field=$db->getone("SHOW COLUMNS FROM ".table('jobs')." WHERE Field = 'topclass' ");
	if (!empty($Field))
	{
	exit("�������ݿ��Ѿ�ִ�й������ˣ����Ƚ����ݿ�ָ��� {$version_old} �汾��Ȼ�������б�����!");
	}
	if (empty($_POST['key']))
	{
	exit("<script language=javascript>alert('����д��Ȩ�룡');window.location='index.php?step=3'</script>");
	}
$sql=updataopen("http://www.74cms.com/updata/3.4_3.5.php?key={$_POST['key']}&domain={$_SERVER['SERVER_NAME']}");
if (empty($sql))
{
exit("<script language=javascript>alert('��ȡ��������ʧ�ܣ�����ϵ��ʿ�ͷ�Э��������');window.location='index.php?step=3'</script>");
}
else
{
	if ($sql=="err_1")
	{
	exit("<script language=javascript>alert('��Ȩ�����');window.location='index.php?step=3'</script>");
	}
	elseif ($sql=="err_2")
	{
	exit("<script language=javascript>alert('�˰汾�������Ѿ�����1��');window.location='index.php?step=3'</script>");
	}
	elseif ($sql=="err_3")
	{
	exit("<script language=javascript>alert('����(".$_SERVER['SERVER_NAME'].")δ��Ȩ��');window.location='index.php?step=3'</script>");
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

		// ְλ����
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
(1, 0, '����|�г�|�ͷ�|ó��', 0, '', '', ''),
(2, 1, '���۹���', 0, '', '', ''),
(3, 1, '������Ա', 0, '', '', ''),
(4, 1, '������������', 0, '', '', ''),
(5, 1, '�г�/�߻�/����', 0, '', '', ''),
(6, 1, '�ͷ�/����֧��', 0, '', '', ''),
(7, 1, 'ó��/�ɹ�', 0, '', '', ''),
(638, 22, '��������/����', 0, '', '', '��λְ��\r\n1����˾�˹��ɱ����������õ�Ԥ�������\r\n2���ƶ����̽��衢���������֯���칫����������˾����ɹ�ͳ��̶��ʲ�������������˾�ִ�����Ʒ�����\r\n3������������ϵ����칫��������װ�ޡ������ɹ���ϵ�ά����������ἰ��Ҫ���֯��\r\n4���ɹ�������˾�̶��ʲ����ֵ�׺�Ʒ���ǰ칫��Ʒ���ճ���Ʒ����\r\n5������̶��ʲ�̨����ˡ���֯�̶��ʲ��̵㹤����\r\n\r\n��λҪ��\r\n1����������ѧ������ҵ���������������������ϣ�\r\n2���̶��ʲ������顢�ʲ������ͬ�������д�����ҵ���Ͽ�ܾ��飻\r\n3����һ�������飻\r\n4����ǿ�������ĺ;�ҵ�������õ���֯Э����������ͨ��������ǿ�ķ������������������\r\n5������ʹ�ð칫����Ͱ칫�Զ����豸��'),
(637, 22, '�����ܼ�', 0, '', '', '��λְ��\r\n1��Э�����߲��ƶ���˾��չս�ԣ������书�������ڶ��ڼ����ڵĹ�˾���ߺ�ս�ԣ��Թ�˾�г���Ŀ��Ĵ�ɲ�����ҪӰ�죻\r\n2�����ݹ�˾Ҫ���ƶ���˾��������ķ��롢���ߺ��ƶȣ�\r\n3������˾�ճ������Ĺ������Ϣ���Ľ��裻\r\n4��������ҵ�Ļ�������ƹ㣻\r\n5������˾�̶��ʲ��Ĺ������ϸ�����˾�ʲ��Ĺ����ƶȻ������򻯣�\r\n6������˾��Χ����ҵ�����ڹ���'),
(636, 21, '����������Դ', 0, '', '', ''),
(635, 21, '��ҵ�Ļ�/Ա����ϵ', 0, '', '', ''),
(634, 21, '��ѵרԱ/��ѵʦ', 0, '', '', ''),
(633, 21, '��ѵ����/����', 0, '', '', ''),
(19, 0, '��Ӫ����|������Դ|����', 0, '', '', ''),
(20, 19, '��Ӫ����', 0, '', '', ''),
(21, 19, '������Դ', 0, '', '', ''),
(22, 19, '����/����', 0, '', '', ''),
(632, 21, '��Ч���˾���/רԱ', 0, '', '', ''),
(631, 21, 'н�ʸ�������/רԱ', 0, '', '', ''),
(630, 21, '��ƸרԱ/����', 0, '', '', ''),
(629, 21, '��Ƹ����/����', 0, '', '', ''),
(628, 21, '������Դ����', 0, '', '', ''),
(627, 21, '������ԴרԱ', 0, '', '', ''),
(626, 21, '������Դ����', 0, '', '', ''),
(625, 21, '������Դ����', 0, '', '', ''),
(624, 21, '������Դ�ܼ�', 0, '', '', ''),
(623, 20, '������Ӫ����', 0, '', '', ''),
(622, 20, '��ҵ�߻���Ա', 0, '', '', ''),
(621, 20, '��Ŀ����', 0, '', '', ''),
(620, 20, '���ž���', 0, '', '', ''),
(619, 20, '��Ӫ����', 0, '', '', ''),
(618, 20, '���´�/�ֹ�˾����', 0, '', '', ''),
(617, 20, '�ܼ�', 0, '', '', ''),
(616, 20, '�ܲ�����/�ܾ�������', 0, '', '', ''),
(615, 20, '���ܾ���/���ܲ�', 0, '', '', ''),
(614, 20, 'CEO/�ܲ�/�ܾ���', 0, '', '', ''),
(49, 0, '����|����|����', 0, '', '', ''),
(50, 49, '����/���/˰��', 0, '', '', ''),
(51, 49, '֤ȯ/�ڻ�/Ͷ��/����', 0, '', '', ''),
(52, 49, '����', 0, '', '', ''),
(665, 51, '�ͻ�����', 0, '', '', ''),
(664, 51, '���/����/��ծ������', 0, '', '', ''),
(663, 51, '��Ʊ/�ڻ�������', 0, '', '', ''),
(662, 51, '֤ȯ����ʦ', 0, '', '', ''),
(661, 51, '֤ȯ/�ڻ�/��㾭����', 0, '', '', ''),
(660, 50, '��������/���/˰��', 0, '', '', NULL),
(659, 50, 'ͳ��Ա', 0, '', '', '��λְ��\r\n1��������˾����״�����о���ҵ�ڹ�˾��Ϣ���Գ����ʲ��Խ��в�������Ͳƾ����߸��٣�\r\n2��������������ҵ��͸�����ҵ�����ṩ������;���֧�֣�\r\n3��Ԥ�Ⲣ�ල��˾�ֽ����͸����ʽ�ʹ�������\r\n4������Ͷ�ʺ�������Ŀ�Ĳ�����㡢�ɱ������������Է���������ƶ�Ͷ�ʺ����ʷ�����\r\n5��׫д����������桢Ͷ�ʲ�����б��桢�������о����棻\r\n6��Э����˾�Ͳ��ŵ�����������\r\n\r\n��λҪ��\r\n1����������ѧ�������ڡ��ƾ������רҵ��\r\n2���������ϱ���λ��ҵ���飬��ҵ�������нϺõ��ȶ��ԣ�\r\n3���н�ǿ���Ŷ�Э������֯Э�������Լ�ѧϰ�������Ϻõ�Ӣ�Ĺ�ͨ����������ͬʱ�߱��Ͻ���ʱ�����ͳɱ�����\r\n4���������渺������������������ǿ���߱����õ�ְҵ�����������ʣ���������Ժ����ֱ��������\r\n5���ܹ���Ŀ��������ѧ�������г������������������Ʒ֪ʶ�ȷ������ѵ��'),
(658, 50, '���Ա', 0, '', '', '��λְ��\r\n1������˰�ձ�������˰��ɱ�Ԥ��ͷ������棬�걨��˰��\r\n2������˰��Ǽ�֤�İ�����켰ע����˰�����ϵ�����ͱ��ܣ�\r\n3��˰����ˡ�˰����鹤�����Լ���ȸ���˰�ֵ���˰�걨�����������й�˰�ռ���������\r\n4��������˰����ƹ�����\r\n5������Э�������ܲ����Ƹ����������ƶȡ�\r\n\r\n��λҪ��\r\n1�����񡢻�ƻ�˰�����רҵ�����Ƽ�����ѧ��������ע��˰��ʦִҵ�ʸ�\r\n2���������Ϲ������飬�޲���ִҵ��¼��\r\n3���ܹ�����˰����ѯ��˰����ƻ�˰��Ϲ�ȷ���Ĺ���, ����ʵ��רҵ����֪ʶ��\r\n4����Ϥ���ҡ��ط��Ĳ�˰���߼����ɷ��棬�ó�˰��ﻮ������\r\n5���������˹�ͨ���������õĿͻ���ͨЭ���������Ŷ�Э��������\r\n6������ʹ�ð칫�����EXCEL��WORD �ȡ�'),
(657, 50, '�ɱ����', 0, '', '', NULL),
(656, 50, '�ɱ�����', 0, '', '', NULL),
(655, 50, '�������', 0, '', '', '��λְ��\r\n1��ȫ��������ල��˾���񼰾�Ӫ״����\r\n2��������ҵ����ģ�ͣ��ռ�������Ϣ��׫д�������棻\r\n3��������񱨱��ṩ���������\r\n4����ʵʩ�Ĳ��񷽰�������Ľ�����;���֧�֣�\r\n5���о�������˾�ɱ����������\r\n6��Э�������Ԥ����ơ��ɱ����ƺ����ⲿ��ƹ�����\r\n7�����������������ļ��͵�����\r\n\r\n��λҪ��\r\n1���ƻᡢ��Ƶ����רҵ��������ѧ�����л��ʦ�ʸ����֤�����ȣ�\r\n2��3�����ϲ������������ع������飻\r\n3����Ϥ�������Ԥ����ơ��ɱ��������Ƶ�ҵ�����̣�\r\n4���ó������������Ϥ�������ģ�ͣ�����ʹ�ò��������\r\n5�����õ�Э������ͨ�������Ŷ�Э������'),
(654, 50, '����', 0, '', '', '��λְ��\r\n1�������ճ���֧�Ĺ���ͺ˶ԣ�\r\n2���칫�һ�������ĺ˶ԣ�\r\n3�������ռ������ԭʼƾ֤����֤����������ԭʼ���ݵĺϷ��ԡ�׼ȷ�ԣ�\r\n4������Ǽ��ֽ����д���ռ��˲�׼ȷ¼��ϵͳ����ʱ�������д�������ڱ�\r\n5���������ƾ֤�ı�š�װ�������桢�鵵����������ϣ�\r\n6�����𿪾߸���Ʊ�ݣ�\r\n7������ܻḺ��칫�Ҳ������ͳ�ƻ��ܡ�\r\n\r\n��λҪ��\r\n1����ѧר������ѧ�������ѧ��������רҵ��ҵ��\r\n2������1�����ϳ��ɹ������飻\r\n3����Ϥ�������������Excel��Word�Ȱ칫�����\r\n4������Ҫ���ּ�������׼ȷ����ʱ����Ŀ�����½ᣬ�������׼ȷ����ʱ��\r\n5���������棬̬�ȶ�����\r\n6���˽���Ҳƾ����ߺͻ�ơ�˰�񷨹棬��Ϥ���н���ҵ��'),
(653, 50, '���', 0, '', '', '��λְ��\r\n1������������֧�����Ĳ���ר�ⱨ��ͻ�Ʊ������ش�Ĳ�����֧�ƻ������ú�ͬ���л�ǩ��\r\n2������Ԥ���ִ��Ԥ�㣬�����ⶩ�ʽ����ʹ�÷�����ȷ���ʽ����Чʹ�ã�\r\n3����鹫˾�����ṩ�Ļ�����ϣ�\r\n4��������˹�˾�����͸�������λ�ϱ��Ļ�Ʊ���ͼ��Ź�˾��Ʊ������Ʋ����ۺϷ��������ר��������棬Ϊ��˾�쵼�����ṩ�ɿ������ݣ�\r\n5���ƶ���˾�ڲ����񡢻���ƶȺ͹������򣬾���׼����֯ʵʩ���ලִ�У�\r\n6����֯������ʵ�ֹ�˾�Ĳ�����֧�ƻ����Ŵ��ƻ���ɱ����üƻ���\r\n\r\n��λҪ��\r\n1��������רҵ����ר����ѧ����\r\n2��2�����Ϲ������飬��һ����˰����ҵ�������������ȣ�\r\n3������ϸ�£����ھ�ҵ���Կ����ͣ������õ�ְҵ���أ�\r\n4��˼ά���ݣ���������ǿ���ܶ���˼���������ܽṤ�����飻\r\n5������Ӧ�ò���Office�칫������Խ�������ѵȲ���ϵͳ��ʵ�ʲ��������ȣ�\r\n6���������õĹ�ͨ������\r\n7���л�ƴ�ҵ�ʸ�֤�飬ͬʱ�߱���Ƴ����ʸ�֤�����ȿ��ǡ�'),
(652, 50, '��ƾ���/����', 0, '', '', NULL),
(651, 50, '��������', 0, '', '', NULL),
(650, 50, '������', 0, '', '', '��λְ��\r\n1���ճ�������㡢���ƾ֤�����ɡ�˰��������ˣ�\r\n2���о��ƶ�������ߺͲ���ָ�����������׼��\r\n3����˹�˾���񱨱��˶Թ����������ϲ��������в��������\r\n4������Ͷ����Ҫ�󣬶����ṩ�����±����������걨��\r\n5����֯ҵ��ѧϰ����ѵ�ͻ�Ƹ�λ����ѵ����\r\n6�����ݷ��ù���涨��������Ʒ���֧����\r\n7��������֯���������ִ��������Ͽز������գ�����������⣻\r\n8��Э��������ƣ��ṩ����ƻ����ϡ�\r\n\r\n��λҪ��\r\n1���ƻ�רҵ��ѧ����ѧ����\r\n2���л��֤��ע����ʦ�ʸ������ȣ�\r\n3��5�����ϻ�ƹ������飬2��������ƹ������飻\r\n4����Ϥ����������̣��в���ѧϰ����Ը��������\r\n5�������õĹ�ͨ���˼ʽ�����������֯Э�������ͳ�ѹ������'),
(649, 50, '�����ܼ�', 0, '', '', '��λְ��\r\n1�����ֹ�˾����ս�Ե��ƶ�����������ڲ����ƹ����������ҵ����ƻ���\r\n2�����ò���������ƹ���ԭ��Ϊ��˾��Ӫ�����ṩ���ݣ�Э���ܾ����ƶ���˾ս�ԣ������ֹ�˾����ս�Թ滮���ƶ���\r\n3���ƶ���˾�ʽ���Ӫ�ƻ����ල�ʽ�������Ԥ�����㣻\r\n4���Թ�˾Ͷ�ʻ����Ҫ���ʽ��뷽ʽ���гɱ����㣻\r\n5���Ｏ��˾��Ӫ�����ʽ𣬱�֤��˾ս�Է�չ���ʽ�����������˾�ش��ʽ�����\r\n6�����ֶ��ش�Ͷ����Ŀ�;�Ӫ��ķ���������ָ�������ٺͲ�����տ��ƣ�\r\n7��Э����˾ͬ���С����̡�˰����������ŵĹ�ϵ��ά����˾���棻\r\n8�����빫˾��Ҫ����ķ����;��ߣ�Ϊ��ҵ��������Ӫ��ҵ��չ������Ͷ�ʵ������ṩ������ķ����;������ݣ�\r\n9����˲��񱨱��ύ������������档\r\n\r\n��λҪ��\r\n1����ƻ����רҵ��������ѧ������ע����ʦ�ʸ������ȣ�\r\n2��5�����ϲ�����������飬��3�������������ְλ���飻\r\n3����Ϥ��ơ���ơ�˰�񡢲��������Ƶ��㻯����ط��ɷ��棻\r\n4���������ո߼������������Ͱ칫�����\r\n5����ɫ�Ĳ�����������ʺ��ʽ����������\r\n6�����õ���֯��Э�����������õı���������ŶӺ�������\r\n7����ͨ�����������к�����ѧ���������ȡ�'),
(74, 0, '�����|ͨ��', 0, '', '', ''),
(75, 74, '�����Ӧ��', 0, '', '', NULL),
(76, 74, '������/����', 0, '', '', NULL),
(77, 74, '�������Ӳ��', 0, '', '', NULL),
(78, 74, 'IT����', 0, '', '', NULL),
(79, 74, 'ITƷ��/����֧��', 0, '', '', NULL),
(80, 74, 'ͨ��', 0, '', '', NULL),
(693, 75, '��ӡ��/��ӡ��ά��', 0, '', '', NULL),
(692, 75, '�����ά��/ά��', 0, '', '', NULL),
(691, 75, 'Ӧ��ϵͳ����', 0, '', '', NULL),
(690, 75, '����ϵͳ����', 0, '', '', NULL),
(689, 75, '���ܴ���ϵͳ����', 0, '', '', NULL),
(688, 75, '���������ϵͳ����', 0, '', '', NULL),
(687, 75, '����/3D���', 0, '', '', '��λְ��\r\n1��������Ŀ��Ʒ��ͼ�ġ�����ý��Ĳ߻���ƹ�����\r\n2����������Ƭ��Ƭͷ��Ƭ�ж����Ĵ����������\r\n3������Ʒ������ѡ���������������\r\n4��ָ������ѵ��������������Ա����ơ�����������\r\n5�������ƶ�ƽ��Ͷ�����������ر�׼�����̹涨�����¹�����\r\n\r\n��λҪ��\r\n1���Ӿ�������ƻ�����������רҵ��ҵ����ר������ѧ����\r\n2����������ƽ�桢������ƾ��飬�ж�ý����ҵ��ҵ����Ϊ�ţ�\r\n3������ʹ����ض�ý����Ƽ����ڱ༭��������������ն����ű����ԣ�\r\n4����������ǿ�����⹹˼���ء�������ǿ���������õ��Ŷ�Э������\r\n5����ǿ�Ľ�����ƺͶ����Ʋ߻��������Ϻõ��ֻ��������˽�־�ͷ���Ʒ���\r\n6���߱������������̵Ĺ�ͨ����������'),
(686, 75, 'ƽ�����', 0, '', '', '��λְ��\r\n1����վ���ݽ���Ĳ��ֺͽṹ�ȷ��������滮�����ֱ༭������\r\n2��������վ�ճ�������ƺ��������ϵ�������\r\n3�������Ϣ���ݵĲ߻����ճ�������ά����\r\n4����д��վ�������ϼ���ز�Ʒ���ϣ�\r\n5����ϲ߻��ƹ���������ִ�У�\r\n\r\n��λҪ��\r\n1���е���������վ�༭����򿪹��Ա����̾��������ȣ�\r\n2����ǿ�Ĵ��⡢�߻����������õ����ֱ��������˼ά���ݣ�\r\n3������ʹ��Photoshop��Flash��fireworks��Dreamweaver�ȳ���������������\r\n4���������棬�������ģ�̤ʵ�ϸɣ������ŶӾ���\r\n5���߱����õ��������������õĴ��⹹˼������'),
(685, 75, '������������/CAD', 0, '', '', '��λְ��\r\n1�����3D���ʦ��ɻ�չ��Ŀ��CADʩ��ͼ��\r\n2�����������ʦ��ָ���������������Ŀ��ʩ��ͼ��CAD���ƣ�\r\n3�����������Ŀ�������̲��ĸ������\r\n\r\n��λҪ��\r\n1����ר������ѧ����ƽ��������רҵ���������ø��������\r\n2���߱��߶ȵĹ������飬��ͨЭ��������ǿ��\r\n3���л�չ��ҵ��ҵ���������ȡ�'),
(96, 0, '����|���ز�|��ҵ����', 0, '', '', ''),
(97, 96, '����', 0, '', '', NULL),
(98, 96, '���ز�', 0, '', '', NULL),
(99, 96, '��ҵ����', 0, '', '', NULL),
(757, 97, '����滮���', 0, '', '', NULL),
(756, 97, '·��/�ۿ�/����', 0, '', '', '��λְ��\r\n1��ȫ��������Ŀ������������\r\n2����֤�������������ȺͰ�ȫ�����Ϲ�˾Ҫ��\r\n3�������ƶ���ʵʩʵʩ��ʩ����֯��ơ��ش�ʩ��������ʩ���ƻ�����ȫ��������취�ȣ���\r\n4������ʩ���ֳ��ļ���ָ������ʱ����ʩ���������⣻��\r\n5�����ָ��༼�����Ϻͼ����ļ����ռ�������ͱ��ƹ�������֯���Ʊ����̿����ļ��ͼ����ܽ᣻\r\n6���μӿ������պͲ�Ȩ�ƽ���������\r\n7��Э����Ŀ����ץ����������Э����ʩ���ӵĹ�ϵ����\r\n8��ָ�����ල����������������Ա�ļ������������Ʋ�ʵʩ����Ŀ��������Ա�ļ�����ѵ�ƻ���\r\n\r\n��λҪ��\r\n1��������·����רҵ��ѧ��������ѧ����\r\n2��������Ժ���滮Ժ�������������ȿ��ǣ�\r\n3���ж����е���רҵ������Ŀ��֯��Ƶľ�������������ȣ�\r\n4�����м�����רҵְ�����ȣ���רҵע���ʸ����ȣ�\r\n5����������CAD��������·רҵ��������\r\n6��רҵ����֪ʶ��ʵ����Ϥרҵ�淶����רҵ֪ʶ�н�ǿ�����о���\r\n7�����õĺ�������ͽ�ǿ�Ĺ��������ġ�'),
(755, 97, '��������', 0, '', '', NULL),
(754, 97, '�ṹ����ʦ', 0, '', '', NULL),
(753, 97, '��ľ����', 0, '', '', '��λְ��\r\n1����������ͼֽ����ˣ������ֳ����졢���졢��棬�����������̸�Ԥ�㣬������Ƶ�λ��Ҫ���ͼֽ�����޸ĺ����ƣ�\r\n2��Э���б깤�����μ��б깤��ͼֽ���ɣ���������רҵ�������������רҵ�����Ƿ������ع涨�������շ��Ƿ����\r\n3��ʩ�������У���������ʩ�����������Ⱥͳɱ��Ŀ��ƣ����ʩ���г��ֵľ���רҵ�������⣻\r\n4��Э��ҵ����ʩ����λ�ͼ���λ֮���Լ���������רҵ֮��Ĺ�ϵ��\r\n5����֯��Ա��鿢�����ϺͶԵ�λ���̼�����̳������֯�������գ������������̽���˶������������㵥��\r\n\r\n��λҪ��\r\n1����ѧ���Ƽ�����ѧ�������������񽨡���ľ���������רҵ��\r\n2��2�����������������ʩ���������飬�����м�����ְ�������ȣ�\r\n3����Ϥ���Ҽ��ط���ط��桢���ߣ���Ϥ������ʩ��ͼ��ʩ��������й�������ʩ���淶��Ҫ��������Ŀ�滮��������ơ�ʩ�������չ淶���������׵Ȼ����������\r\n4����ͨ�����������嵥����۱��ƣ�����ʹ��Ԥ���嵥�������Ϥʩ���ֳ��������̺ͻ��ڣ��˽��г����������Ϣ��������Ϣ��\r\n5���������õļ���Ӣ��ˮƽ�ͼ������������������ʹ��CAD��ͼ�����������ġ���ҵ�ļ��ŶӺ�������'),
(752, 97, '�������/��ͼ', 0, '', '', NULL),
(751, 97, '����ʦ', 0, '', '', '��λְ��\r\n1������˾����ʩ�����������ͼ���������������ƣ�\r\n2������˾��������ļ�������֯�ƶ���ʵʩ�ش������ߺͼ���������\r\n3�����ʩ���ֳ��ļ������⣻\r\n4��������֯���ش������¹ʵļ����ʹ��������ڶ�ʩ���ֳ����м�飬��ʱ��ع����������������⼰ʱ�ټ������Ա���д���\r\n5�������С�Ͷ�깤������ؼ������ϵȣ�\r\n6��������Ŀ���ϱ���ʩ����֯��ơ�ʩ�����������ƶ�ʩ�����ղ�����\r\n7���μӼ׷���֯��ͼֽ���󡢷�����֤��\r\n8������Թ�����֯����������ֲ��͵�λ���̵���������;\r\n9������ִ᳹�й��ҿƼ���������ߣ�������ȫ��Ӧ�Ĺ����ƶȣ�\r\n10������ʩ���ֳ��ϱ����Լ����ݼ���������ⵥλ�ĳ���������ݣ��Թ�������������������������Ӧ��Ӧ��Ԥ����\r\n11�����ֽ����������ļ����ϵı��ƣ��μӽ��������ա���֯ʩ�������ܽ��ѧ�����ĵ�׫д��������˺����ϼ����档\r\n\r\n��λҪ��\r\n1����ѧ����ѧ������������ľ����רҵ��\r\n2��10���������Ժ�������飬5��������ҵȫ�澭Ӫ���������飻\r\n3����Ϥ����רҵ��ƹ淶��\r\n4�����нϺõ�����Э�������͹�ͨ�������нϺõ����ֱ�������Ϳ�ͷ���������'),
(750, 97, '����ʦ', 0, '', '', NULL),
(749, 97, '��ȫԱ', 0, '', '', '��λְ��\r\n1����֯��ȫ�ļ��ı�д����ȫ��������ȫ�ļ��Ĺ���\r\n2����ʩ���ֳ����а�ȫ�ල����顢ָ���������ð�ȫ����¼��\r\n3���Բ����ϰ�ȫ�淶ʩ���İ��鼰���˽��а�ȫ����������������ʱ�������ģ�\r\n4������ȫԤ�����Ľ������ı��ƣ�\r\n5����ȷ�ʩ���ֳ���ȫ��ʩ�������İ�ȫ�������棬���������ȫ�����������������������\r\n6����֯��ȫ��顢��ȫ��������ȫ���������ҵ��Ա��ѵ�Ϳ��ˣ�\r\n7������һ���Եİ�ȫ�¹ʡ�\r\n\r\n��λҪ��\r\n1����ѧר�Ƽ�����ѧ���������񽨡������ͽṹ�����רҵ��\r\n2��2�����Ϲ��̰�ȫ���������飬���а�ȫԱ��λ����֤�����ȣ�\r\n3����Ϥ���Ҹ��ȫ���ɷ��棬��Ϥ�����ֳ���ȫ�������̡���ȫ�����淶�Ͱ�ȫ����ĳ����ܹ���ʱ���ְ�ȫ���������������\r\n4����Ϥ���ս���ʩ���������̼���ȫ�����������ٵ����ذ�ȫ���¡���׼���ճ���ȫ�����и߶ȵ������ģ�\r\n5������һ����Э������֯�͹�ͨ����������һ�������Ա��������'),
(748, 97, '����Ա', 0, '', '', '��λְ��\r\n1�����𹤳̲������ļ��Ĺ鵵���ƽ������Ĺ���\r\n2�����𹤳����ϡ�ͼֽ�Ĺ��������ļ��Ĵ���\r\n3����������Ҫ���ܹ����ƻ����¶ȹ����򱨵ȹ�������\r\n4������ϼ��������������\r\n\r\n��λҪ��\r\n1����ѧר�Ƽ�����ѧ�������̹������񽨡�������������רҵ��\r\n2��������ع���1�����ϣ��߱�һ���Ĺ������Ϲ����飻\r\n3������ʹ��CAD��WORD��EXCEL�Ȼ�ͼ���칫�����\r\n4���������õ��ŶӺ�������������ǿ��\r\n5�������������н�ǿЭ��������'),
(747, 97, 'ʩ��Ա', 0, '', '', NULL),
(746, 97, '���/Ԥ����', 0, '', '', '��λְ��\r\n1����ĿͶ�ʷ����������ճ��ɱ����㣬�ṩ��Ʊ���ɱ����飻\r\n2���������ƹ��㡢ʩ��ͼԤ�㡢�б��ļ����ơ����������������ˣ�\r\n3����֯�ڲ��б�ʵʩ������ⲿ�бꣻ\r\n4����ͬ�ļ��������������ٷ�����ִͬ����������������\r\n5�����̿�֧����ˣ����������Ԥ������㱨�棻\r\n6�����Ǣ����˼������������ˡ�\r\n\r\n��λҪ��\r\n1���������̡���ۡ�Ԥ������רҵ��ר����ѧ����\r\n2��2��������ع������飬����ע�����ʦ�ʸ�\r\n3����������������򹤳���۹���ͳɱ��������̣��˽���ع涨�����ߣ�\r\n4������׫д�б��ļ�����ͬ����������̸�У�\r\n5�������Ͻ������ڹ�ͨ���߱����õ��ŶӺ��������ְҵ���أ�\r\n6��׿Խ��ִ��������ѧϰ�����Ͷ�������������'),
(745, 97, '���̼���', 0, '', '', '��λְ��\r\n1����רҵ������ʦ��ָ���¿�չ��������\r\n2��Э��רҵ������ʦ��ɹ������ĺ˶���\r\n3�������ֳ����������������⼰ʱ��רҵ������ʦ���棻\r\n4���Գн���λʵʩ�ƻ��ͽ��Ƚ��м�鲢��¼��\r\n5���н���λʵʩ�����е�������豸��װ�����ԡ����Խ��мල����¼��\r\n6�������ͼ����ر�׼���Գа���λ�Ĺ��չ��̺�ʩ��������м��ͼ�¼��\r\n\r\n��λҪ��\r\n1����ѧר�Ƽ�����ѧ������������ľ�����������רҵ��\r\n2��1�����Ϲ��̼��������飬��������ʦ�ʸ������ȣ�\r\n3����ͨ���̼������̹�������רҵ֪ʶ���˽⽨��������ͬ������Ͷ�귨����ط��ɷ��棬�˽⹤�̸�Ԥ�����֪ʶ��\r\n4�����н�ǿ�Ĺ�ͨ��������֯Э���������ܹ�������Ч��Э��������ع����������Ͻ������桢ϸ�£��߱�һ���ļ��������������\r\n5��������ǿ���Կ����ͣ�����Ӧ�������'),
(744, 97, '������Ŀ����', 0, '', '', NULL),
(743, 97, '�߼���������ʦ/�ܹ�', 0, '', '', NULL),
(116, 0, '����|�ʹ�|����', 0, '', '', ''),
(117, 116, '��������/��Ӫ', 0, '', '', ''),
(118, 116, '����/��ȫ����', 0, '', '', ''),
(119, 116, '����', 0, '', '', ''),
(120, 116, '��е', 0, '', '', ''),
(121, 116, '��������', 0, '', '', ''),
(122, 116, '��װ/��֯Ʒ', 0, '', '', ''),
(798, 117, '�����з�', 0, '', '', ''),
(797, 117, '�ɹ�����', 0, '', '', ''),
(796, 117, '��������', 0, '', '', ''),
(795, 117, '�ֿ����', 0, '', '', ''),
(794, 117, '����/�豸����', 0, '', '', ''),
(793, 117, '��������', 0, '', '', ''),
(792, 117, '�����ƻ�', 0, '', '', ''),
(791, 117, '���鳤/��������', 0, '', '', ''),
(790, 117, '��������/��������', 0, '', '', ''),
(789, 117, '�ܹ�/���ܹ�', 0, '', '', ''),
(788, 117, '����/������', 0, '', '', ''),
(787, 117, '�����з�����/����', 0, '', '', ''),
(136, 0, '����|����|��Դ|����', 0, '', '', ''),
(137, 136, '����/�뵼��/����/�Ǳ�', 0, '', '', ''),
(138, 136, '����', 0, '', '', ''),
(139, 136, '����/��Դ', 0, '', '', ''),
(140, 136, '����', 0, '', '', ''),
(880, 137, '��������/�뵼��/�Ǳ�', 0, '', '', ''),
(879, 137, '�������ӹ���ʦ', 0, '', '', ''),
(878, 137, '����ά��', 0, '', '', ''),
(877, 137, '����/����Ӽ���', 0, '', '', ''),
(876, 137, '���/��Դ����', 0, '', '', ''),
(875, 137, '�����豸װ�����', 0, '', '', ''),
(874, 137, '�����豸ά��', 0, '', '', ''),
(873, 137, '����/�Ǳ�/����', 0, '', '', ''),
(872, 137, '����Ԫ��������ʦ', 0, '', '', ''),
(871, 137, '���Ӳ���/�뵼��', 0, '', '', ''),
(870, 137, '��ͼ/��·���', 0, '', '', ''),
(869, 137, 'Ƕ��ʽ��/Ӳ������', 0, '', '', ''),
(868, 137, '���̾���/����', 0, '', '', ''),
(867, 137, '���Ӽ�������', 0, '', '', ''),
(866, 137, '���Ӽ����з�', 0, '', '', ''),
(865, 137, '���ӹ���ʦ/����Ա', 0, '', '', ''),
(169, 0, '���|ý��|����|����', 0, '', '', ''),
(170, 169, '���', 0, '', '', NULL),
(171, 169, 'Ӱ��/ý��', 0, '', '', NULL),
(172, 169, '�������', 0, '', '', NULL),
(173, 169, '���ų���', 0, '', '', NULL),
(927, 172, '��ý�����', 0, '', '', NULL),
(926, 172, '����/����/չ�����', 0, '', '', NULL),
(925, 172, '����/3D���', 0, '', '', NULL),
(924, 172, '�������ʦ', 0, '', '', NULL),
(923, 172, '�Ű����', 0, '', '', NULL),
(922, 172, 'ƽ�����', 0, '', '', NULL),
(921, 172, '������������/CAD', 0, '', '', NULL),
(920, 171, '����Ӱ��/ý��', 0, '', '', NULL),
(919, 171, '��������', 0, '', '', NULL),
(918, 171, '��̨���', 0, '', '', NULL),
(917, 171, '����/����', 0, '', '', NULL),
(916, 171, '��Ӱ/����', 0, '', '', NULL),
(915, 171, '������', 0, '', '', NULL),
(914, 171, '������/����Ա/DJ', 0, '', '', NULL),
(913, 171, '��Ա/ģ��', 0, '', '', NULL),
(912, 171, '����/�ർ/�����ܼ�', 0, '', '', NULL),
(911, 171, 'Ӱ�Ӳ߻�/������Ա', 0, '', '', NULL),
(910, 170, '�������', 0, '', '', NULL),
(909, 170, '����ָ��', 0, '', '', NULL),
(908, 170, '�İ�/�߻�', 0, '', '', NULL),
(907, 170, '��洴��/���', 0, '', '', NULL),
(906, 170, '���ͻ�רԱ', 0, '', '', NULL),
(905, 170, '���ͻ�����', 0, '', '', NULL),
(203, 0, '����|����|��ѯ|����', 0, '', '', ''),
(204, 203, '����/��ѵ', 0, '', '', NULL),
(205, 203, '����', 0, '', '', NULL),
(206, 203, '��ѯ', 0, '', '', NULL),
(207, 203, '����', 0, '', '', NULL),
(961, 204, '����/�γ̹���', 0, '', '', NULL),
(960, 204, '��ѵ�߻�', 0, '', '', NULL),
(959, 204, '������Ʒ����', 0, '', '', NULL),
(958, 204, '��ѵ����', 0, '', '', NULL),
(957, 204, '��ѵʦ/��ʦ', 0, '', '', NULL),
(956, 204, '�������', 0, '', '', NULL),
(955, 204, '�ҽ�', 0, '', '', NULL),
(954, 204, 'У��/��У��', 0, '', '', NULL),
(953, 204, '�赸��ʦ', 0, '', '', NULL),
(952, 204, '������ʦ', 0, '', '', NULL),
(951, 204, '���ֽ�ʦ', 0, '', '', NULL),
(950, 204, '�����ʦ', 0, '', '', NULL),
(949, 204, '�׽�', 0, '', '', NULL),
(948, 204, 'Сѧ��ʦ', 0, '', '', NULL),
(947, 204, '��ѧ��ʦ', 0, '', '', NULL),
(225, 0, 'ҽ��|��ҩ|����', 0, '', '', ''),
(226, 225, 'ҽԺ/ҽ��', 0, '', '', NULL),
(227, 225, '��ҩ/ҽ����е', 0, '', '', NULL),
(228, 225, '����', 0, '', '', NULL),
(992, 226, '����ҽԺ/ҽ��', 0, '', '', NULL),
(991, 226, 'ҩ������/ҩ��ʦ', 0, '', '', '��λְ��\r\n1������ҩƷ���ŵ�Ӫ�ˡ���������\r\n2������ҩƷ�ĵ��������ż�ҩ����ѯ������\r\n3�����ա�GSP����׼���ƶ�Ҫ����б�����ļ�����\r\n��λҪ��\r\n1����ר������ѧ����1������ҩƷ���ۡ������飻\r\n2����ҩ��ʦ/ִҵҩ��ʦְ��֤�飬GSP�ϸ�֤\r\n3�������������������и߶ȵ����θС���ҵ������ŶӺ�������'),
(990, 226, '��ҽ/����ҽ��', 0, '', '', '��λְ��\r\n1������Ȯ���ճ���࣬Ȯֻ���ճ�����\r\n2���ܶԳ��Ｒ�������ٴ���ϡ����ƣ�����ҩ�����롢��Һ�ȣ�\r\n3������������������������ǰ׼�������м໤������Ļ�������\r\n��λҪ��:\r\n1��ϲ��Ȯֻ���߱����ĺ����ԣ�\r\n2���������������н�ȡ�ģ����õ�ѧϰ���������Ա���������Ŷ�Э��������\r\n3������ҽѧ�����רҵ��ר�Ƽ�����ѧ����'),
(989, 226, 'Ӫ��ʦ', 0, '', '', '��λְ��\r\n1��������ͻ�������Ч��ͨ�������û������ṩ��ѯ����\r\n2������Կͻ�������ѵ��ָ�������������Ľ���ͷ������棻\r\n3��������չҵ�񣬲��Ͽ����µĿͻ���ά���Ͽͻ���\r\n4���������ز��Ž��о�����Ŀ�Ĺ�ͨ����֤��ѯ��Ŀ��˳������\r\n��λҪ��\r\n1��3��������ع������飻\r\n2����������̤ʵ���ܳ���һ���Ĺ���ѹ����\r\n3��Ʒ�ж��������彡�������о�ҵ����\r\n4���������ģ��ŶӺ�������ǿ��'),
(988, 226, 'ҽԺ������Ա', 0, '', '', NULL),
(987, 226, '����ҽ��', 0, '', '', NULL),
(986, 226, '��ʿ/������Ա', 0, '', '', '��λְ��\r\n1�����ҽ�����öԲ��˵����ƹ�����\r\n2���۲첡�˵Ĳ���ת�������\r\n��λҪ��\r\n1���������רҵ��ר������ѧ����\r\n2��һ�����Ϲ������飬�л�ʿ�ϸ�֤���ȣ�\r\n3���׺���ǿ�����ڰ��ģ�̤ʵ��ҵ��'),
(985, 226, 'ҽ�Ƽ�����Ա', 0, '', '', NULL),
(984, 226, 'ҽ��/ҽʦ', 0, '', '', '��λְ��\r\n1���Ӵ��ճ���������ҽ�ƹ����������黼�߲��飬ϸ����ϣ���ȷ������������ҩ���ž����\r\n2�����ݰ������÷����������ռ������;Ȼ�֪ʶ��\r\n3�����ڶ�ҽ���ҵĸ���ҽ����е������������\r\n4��ҩƷ����飬�Թ���ҩƷ��ʱ����ȷ��Ա����ҩ��ȫ��\r\n��λҪ��\r\n1����ר����ѧ����ҽѧ���ٴ��������רҵ��\r\n2�����Ա���������������������õĽ�����ͨ�������׺�����\r\n3����Ϥ���������֪ʶ������������\r\n4���������õ�ְҵ���º��Ŷ�Э������'),
(241, 0, '����ҵ', 0, '', '', ''),
(242, 241, '�ٻ�/����', 0, '', '', NULL),
(243, 241, '����/����', 0, '', '', NULL),
(244, 241, '����/����/����', 0, '', '', NULL),
(245, 241, '����/����', 0, '', '', NULL),
(246, 241, '����/��ͨ/�ִ�', 0, '', '', NULL),
(1014, 242, '�����ٻ�/����', 0, '', '', NULL),
(1013, 242, 'Ʒ�����', 0, '', '', NULL),
(1012, 242, '����Ա/�ڱ�', 0, '', '', '��λְ��\r\n1��ά���������򣬱�֤�����Ʋ���ȫ���˿ͺ�Ա����ȫ��\r\n2�������������ϵͳ�������Ȱ�ȫ��ʩ��ʹ��״��������ά���뱣����\r\n3��Ѳ����Ʒ�ڸ�����ȫͨ���������ص������Լ�����������ž�һ�а�ȫ������\r\n4��������Ʒ���ԭ��Э�������������\r\n5����ϱ���������������ǡ�����'),
(1011, 242, '����Ա', 0, '', '', '��λְ��\r\n1�����ظ�������ƶȺͲ�������\r\n2�����涨Ϊ�����˰������������ȷ�����������֮ǰ���������Ŀ��������\r\n3����������δ�����Ŀ����δ����Ŀ��������ø���\r\n4��������˿����ʻ�ת�ƣ�\r\n5������Ϊ���˶һ���ң��ṩ������Ʒ�Ĵ汣���䣻\r\n��λҪ��\r\n1����������ѧ����������XX�����ϣ� �������ʺã�\r\n2���Ǳ�����Ա����豾�л����˵���;\r\n3�����оƵ�ǰ̨�����������飬������Ӧ�Ļ��֪ʶ��'),
(1010, 242, '���Ա', 0, '', '', '��λְ��\r\n1����Ϥ������Ʒ���ŵ���Ʒ���ơ����ء����ҡ������;�����ܡ��������ޣ�\r\n2�����س��вֿ�������Ʒ�������йع涨������ҵ���̽��и������\r\n3��������Ʒ��۵�֪ʶ����ȷ��ü۸�\r\n4������������Ʒ���е��й�רҵ֪ʶ�����������õ�ʵ�ʹ����У�\r\n5����û�����������\r\n��λҪ��\r\n1����������ѧ����\r\n2���д����̳����й������������ȣ�\r\n3��Ʒò�������Ȱ�������ҵ���Կ����ͣ�������ǿ�����彡�����к�ǿ�ľ�ҵ��������õ��������ʣ�\r\n4���߱��򵥵ļ�����������ɣ��˽���Ʒ����ʹ洢֪ʶ��\r\n5���ܹ�ʤ�η��ص���������������Ӧ��ҹ����ݰ��š�'),
(1009, 242, '����Ա/����Ա', 0, '', '', '��λְ��\r\n1��ȫ�����ֵ���Ĺ�����������ܲ��ĸ���Ӫ�����Ե�ʵʩ��\r\n2��ִ���ܲ��´�ĸ�������\r\n3�������ŵ�������ŵķֹ���������\r\n4���ල��Ʒ��Ҫ�����ϻ������������ý������ա���Ʒ���С���Ʒ�����ͷ�������������й���ҵ��\r\n5���ල�ŵ���Ʒ��Ĺ���������Ʒ��ĳ߶ȣ�'),
(1008, 242, '��Ա/ӪҵԱ', 0, '', '', '��λְ��\r\n1��ȫ�����ֵ���Ĺ�����������ܲ��ĸ���Ӫ�����Ե�ʵʩ��\r\n2��ִ���ܲ��´�ĸ�������\r\n3�������ŵ�������ŵķֹ���������\r\n4���ල��Ʒ��Ҫ�����ϻ������������ý������ա���Ʒ���С���Ʒ�����ͷ�������������й���ҵ��\r\n5���ල�ŵ���Ʒ��Ĺ���������Ʒ��ĳ߶ȣ�'),
(1007, 242, '�곤/��������', 0, '', '', '��λְ��\r\n1��ȫ�����ֵ���Ĺ�����������ܲ��ĸ���Ӫ�����Ե�ʵʩ��\r\n2��ִ���ܲ��´�ĸ�������\r\n3�������ŵ�������ŵķֹ���������\r\n4���ල��Ʒ��Ҫ�����ϻ������������ý������ա���Ʒ���С���Ʒ�����ͷ�������������й���ҵ��\r\n5���ල�ŵ���Ʒ��Ĺ���������Ʒ��ĳ߶ȣ�\r\n��λҪ��\r\n1����ר������ѧ��,רҵ���ޣ�\r\n2��3����������ҵ���������飬���н�ǿ�ĵ�������飻\r\n3����ͨ�Ŷӹ����ͻ�������Ʒ�������й����������ͣ���Ϥ����ĸ������̵��ƶ���ִ�У�\r\n4����ǿ���Ŷӹ��������͹�ͨ�������ܹ����ܽϴ�Ĺ���ǿ�Ⱥ͹���ѹ����\r\n5������35�����£�'),
(258, 0, 'ѧ��|�繤|����|ũҵ|����', 0, '', '', ''),
(259, 258, 'ѧ��/�繤/����', 0, '', '', NULL),
(260, 258, 'ũ/��/��/��ҵ', 0, '', '', NULL),
(261, 258, '����', 0, '', '', NULL),
(1064, 260, '��ҵˮ��', 0, '', '', NULL),
(1063, 260, '��ҵ��ľ', 0, '', '', NULL),
(1062, 260, '԰��԰��', 0, '', '', NULL),
(1061, 260, '��ҽ/����ҽ��', 0, '', '', NULL),
(1060, 260, '����Ӫ��/�����з�', 0, '', '', NULL),
(1059, 260, '������ֳ', 0, '', '', NULL),
(1058, 260, 'ũ��ʦ', 0, '', '', NULL),
(1057, 259, '��ѧ�о���Ա', 0, '', '', NULL),
(1056, 259, '������', 0, '', '', NULL),
(1055, 259, 'ʵϰ��', 0, '', '', NULL),
(773, 98, '���ز�����/��ҵ����', 0, '', '', '��λְ��\r\n1�� ����ͻ��ĽӴ�����ѯ������Ϊ�ͻ��ṩרҵ�ķ��ز���ҵ��ѯ����\r\n2�� ��ͬ�ͻ��������ٳɶ��ַ�����������ҵ��\r\n3�� ����˾��Դ��������ۣ�����ҵ���������õ�ҵ��Э����ϵ��\r\n\r\n��λҪ��\r\n1��������20��30���꣬��ר����ѧ����\r\n2����ʵ���ţ��Կ����ͣ��������õ��ŶӾ���\r\n3���ܳ��ܽ�ǿ�Ĺ���ѹ����Ը����ս��н��\r\n4����ͨ��������\r\n5������ؾ��������ȡ�'),
(772, 98, '���ز�������', 0, '', '', NULL),
(771, 98, '���ز�Ӫ���߻�', 0, '', '', NULL),
(770, 98, '���ز�����/�߻�', 0, '', '', '��λְ��\r\n1�����𷿵ز���Ŀ����ǰ�ڲ߻��Ĺ���������Ŀ��λ������Ͷ��������������ľ������ý���ȣ�\r\n2�����𷿵ز���Ŀ�����ĺ���Ӫ���߻��Ĺ���������Ŀ���λ�ĳɹ��������Ӫ���ַ������ã�\r\n3�����������ý��Խ�,��ù�˾�����ƹ���Ŀ,���û�Ĳ߻�,��װ,����,������ʵʩ������\r\n4��׫дȫ�̲߻����桢��λ���桢�滮���顢ִ�б��棻\r\n5����������ۼ��߻����Ƚ��ж�̬�ƿء�\r\n\r\n��λҪ��\r\n1��Ӫ������桢��������ľ�����רҵ��ѧר�Ƽ�����ѧ����\r\n2��2�����Ϸ��ز���ҵ�߻���ѯ�������飬�гɹ��߻����������ȣ�\r\n3����Ϥ���ز��߻����ܹ����ǰ�ڲ߻����桢Ӫ���߻����桢�ƹ�߻���ȷ��ز����������еĲ߻���׫д������\r\n4����Ϥ���н��衢���ز���ҵ����Ϥ���ز�������ط��ɷ��棬��ʱ���շ��ز��г���̬������������г����������ж�����\r\n5���Ȱ����ز��߻���ҵ���Ͻ���ǿ�����г����ͻ�������һ���������ԡ�'),
(769, 98, '���ز���Ŀ����', 0, '', '', NULL),
(768, 97, '��������', 0, '', '', NULL),
(767, 97, '���/����', 0, '', '', '��λְ��\r\n1��������Ŀ�Ľ���滮����֯��չ��·,����ȷ��濱�⣻\r\n2������Ŀ���̽�����̽���ָ�����ල��\r\n3������Ŀ���輰������Ӫ����������������׼ȷ�ķ�����Ԥ�⣻\r\n4��������Ʒ�����֯�ֳ�ʩ�����������Ӧ�ĸĽ����顣\r\n\r\n��λҪ��\r\n1����ר������ѧ�������̲��������⣬������Ϣ�����רҵ��ҵ��\r\n2��2�����������ҵ����ʩ�����飻\r\n3��GPS��ȫվ�ǲ���������\r\n4���Ϸ�CASS��AUTOCAD�����������������������\r\n5�����й����ֳ�����ͼ���ָ�����飬�ܷ����������м������⣬ָ��������Ա��ҵ��\r\n6���н�ǿ�Ĺ�ͨ�����;�ҵ����'),
(766, 97, '��������', 0, '', '', NULL),
(765, 97, '�ۺϲ���', 0, '', '', '��λְ��\r\n1����������ϵͳ�����滮����ƺʹ������ʩ��ͼֽ������������ʽ�㱨�����ٸ������罨����ȣ�������ֽ�������� \r\n2��������ͬ����ȥ��Կͻ��˽�ͻ���������ǰ����Ƶ���������˿ͶԲ��߷����Ҫ�� \r\n3���������û���ͨ,��ϸ�˽�����,�������Ƶ�ʩ���Ĺ������û����̿����ĵ����ơ��ύ��ȫ���̹���\r\n4�����ϵͳ����ʦ���ϵͳ������Ŀ���ڵ�ʵʩ������\r\n\r\n��λҪ��\r\n1. ��������רҵ����Ϥ��������������֪ʶ������CAD��ͼ��\r\n2. ��������ϵͳ���飨��Ҫ���ۺϲ��ߡ�ͨ�Ż����豸��װ/���ԡ����������ϵͳ������ϵͳ�ȣ���\r\n3. ��Ϥ������ҵ����ع淶�ͱ�׼�ȣ��߱���ǿ����Ŀ���Ŷӹ���Э��������\r\n4. �������飬������ǿ�����д��¾��񣬾������õ�ѧϰ��������������ͽ������������� \r\n5. �˼ʹ�ͨ����ǿ���������õ��Ŷ�Э������ \r\n6���ܹ�ִ�й�˾ҵ�����̼�����ʩ���淶��������ǿ��'),
(764, 97, '���ܴ���ϵͳ����', 0, '', '', NULL),
(763, 97, '�������ʦ', 0, '', '', NULL),
(762, 97, '����ůͨ', 0, '', '', '��λְ��\r\n1���ƶ�������Ŀˮů���̵ľ���ʩ���������ֳ�ָ������ʩ�����̲��ṩ����֧�֣�\r\n2������Ƶ�λ��ͨ��Э��������ʹ�˾��ůͨ���������ůͨ��Ʒ�����ͼֽ������\r\n3��Ϊůͨʩ���ṩ����֧�֣���ůͨ�豸��ѡ���ṩ����Ľ�����޸������\r\n4���μ��ֳ�Ѳ�ӣ���Ϲ���ʩ�������գ������ֳ�ʩ�����⣻\r\n5���Թ�����Ŀ�е�ˮů���̽��м������������мල�͹���\r\n6��������ʩ����λ������λ��Э����ͨ��\r\n\r\n��λҪ��\r\n1������25�����ϣ�����ˮ��ůͨ��������רҵר������ѧ����\r\n2��3������ůͨ�������飬���й�����ز��Ű䷢��ְҵ�ʸ�֤�飨�ϸ�֤����\r\n3����Ϥůͨ����ʩ�����ա�ʩ�����̼�������չ淶���˽�ůͨ����ʩ�������г�����͹�����Ƶ���ҵ�淶��\r\n4��������ʵ�Ĺ����ֳ�����������õ�������ʶ���ɱ���ʶ����ȿ���������\r\n5���������õĹ�ͨ��Э���������ḻ���ֳ�Э�����������õ��ŶӾ����뾴ҵ����\r\n6�����彡�����Կ����ͣ��ܹ��������ɡ�'),
(761, 97, '����ˮ', 0, '', '', '��λְ��\r\n1���������ˮ������ϵͳ������ơ�ʩ���������ֳ���������\r\n2����˸���ˮ�ȹ����б��ļ��ļ������֣�\r\n3��Э��ʩ����λ������λ�����ֳ����⣻\r\n4�����𹤳̸����������ա��������ռ��������յ����չ�����\r\n5�����ñ�רҵ�йؼ������ϵ���������\r\n6����������쵼���������������\r\n\r\n��λҪ��\r\n1�����̡�����ˮ�����רҵ��ѧר�Ƽ�����ѧ����\r\n2��2��������ع������飬�м�����ְ�ƣ�\r\n3����Ϥ�����ҵ��ҵ��������ƹ������̣��߱���רҵ�Ļ�������֪ʶ���˽����רҵ֪ʶ������������Office��Auto��CAD�ȼ�������ϵͳ��\r\n4��ϸ���Ͻ����ܳԿ����ͣ������ŶӾ��񼰹�ͨЭ��������'),
(760, 97, 'Ļǽ���', 0, '', '', '��λְ��\r\n1��ȫ��Э����Ƽල�����Ŀ�������\r\n2��������Ƽල��Ҫ����ƻ��޸�ͼֽ����������ͼ��ƽ��ͼ������ͼ�ȣ�\r\n3��������Ƽලί�ɵĲ���ͳ���嵥��\r\n4��������Ƽලί�ɵĲ��϶�����Ϣ/���飻\r\n5������������ɵĹ����ύ��ͼֽ���Ա����Ƽලȷ�ϣ�\r\n6��Э����Ƽලά�����������������ļ��ռ������ơ���ӡ���Ǽǵȣ�\r\n7��Э��רҵ�ܹ���������ֳ����ֵļ������⡣\r\n\r\n��λҪ��\r\n1����ר������ѧ����5�����ϼ׼����Ժ����ͷ��ز�����/ʩ����˾�������飻\r\n2������ʹ��AUTOCAD��ͼ������칫�����\r\n3����Ϥ������صĹ��ҹ淶�����棻\r\n4���д����ۺ���Ŀ��ƾ��飬�������ս���ʩ��ͼҪ��������\r\n5���е�����ͼֽ��Ӧרҵ��˹�����\r\n6�����ֳ�ʩ����Ͼ��飬��סլ���͡��淶����ƾ��飻\r\n7���߱����õĹ�ͨЭ�����˼ʹ�ϵ������\r\n8���м�����ʦ��'),
(759, 97, '��������ʦ', 0, '', '', NULL),
(758, 97, '԰��/�������', 0, '', '', '��λְ��\r\n1����֯��ʵ���۹�����ƹ���������ͼֽ������Ľ������ȡ���ȼ������ԣ�\r\n2�����ơ���龰�۹��̼ƻ������ƶ���Ʊ�׼�����ƾ�����ƣ����ƾ���ʵʩЧ����\r\n3�����ܾ��۷�����ʩ��ͼ���ļ�����ˣ�\r\n4����֯���۹��̲��ϵ�ѡ�������幤����\r\n5����֯�ɹ����۹�������Ҫ��СƷ��\r\n6�����𾰹۹��̿������ա����ʩ�����ȡ������������ؼ������⡣\r\n\r\n��λҪ��\r\n1��԰�֡�԰�ա�������������רҵ��ѧר�Ƽ�����ѧ����\r\n2��2�����Ͼ���������������������飬�м�����ְ�ƣ�\r\n3����Ϥ��ľ���ԣ������ֳ��������ܶ�����֯������Ŀ����ƺ�ʩ������������Ч�Ŀ��ƹ��ں;���ʵʩЧ��������ʹ�õ��԰칫�������ͼרҵ�����\r\n4���߱�רҵ�������������ո�Ч�Ĺ����������߱����õ�Э�����������м�ǿ�ľ�ҵ����������ġ�'),
(742, 80, '����ͨ��', 0, '', '', NULL),
(741, 80, 'ͨ�Ų�Ʒά��', 0, '', '', NULL),
(740, 80, '�ƶ�ͨ�Ź���ʦ', 0, '', '', NULL),
(739, 80, '����ͨ�Ź���ʦ', 0, '', '', NULL),
(738, 80, '����ͨ�Ź���ʦ', 0, '', '', '��λְ��\r\n1�����߲�Ʒ���Ʒ����ģ�鲿�ֵĿ�����ơ�����ѡ�ͣ�\r\n2���о����ߵ粨����ģ�ͣ�\r\n3�����ߵ���ؼ���Ӧ���о���\r\n4����д�з������ĵ���\r\n\r\n��λҪ��\r\n1�����ߵ��ͨ��רҵ�����Ƽ�����ѧ����Ӣ�����ã�\r\n2��2��������ع���������\r\n3����ͨ���ߵ�ͨ���������֪ʶ����Ϥ�����źŴ����㷨��'),
(737, 80, 'ͨ�ż�������ʦ', 0, '', '', '��λְ��\r\n1�����𱾹�˾��Ʒ��ά�ޡ���������Ƶ����ͨ����صȹ�����\r\n2�������ʹ�ñ���˾��Ʒ������վ����ж���Ѳ�칤������ʱ������������ʱ������ϣ�\r\n3�������û�Ҫ����豸���칤����\r\n4����ʱ�����������⣻\r\n5��������ά���������˻㱨ά�����������\r\n\r\n��λҪ��\r\n1��ͨ���ࡢ��������רҵ��\r\n2��1�����Ϲ������飬������ʸ�֤�������ȣ�\r\n3����Ϥ������������豸����˼�ơ���Ϊ����\r\n4����ǿ�Ķ�����������ͽ�������������'),
(736, 79, '����ITƷ��/����֧��', 0, '', '', NULL),
(735, 79, 'Ӳ������', 0, '', '', '��λְ��\r\n1����ɹ�˾��Ŀ����Ʒ��������ز��Թ�����\r\n2�����ݲ�Ʒ���������ĵ����ƶ����Լƻ�������������������Ʋ������̣�\r\n3�����ݲ�Ʒ����������ɲ��Ի�������������ù�����\r\n4��ִ�о����������ȷ�ϲ��Խ����ȱ�ݸ��٣���ɲ��Ա����Լ����Խ��������\r\n5���ڲ��Ը������뿪������Ʒ�Ȳ��Ź�ͨ��֤����������������ȷ�Ժ��걸�ԣ�\r\n6����ɲ�Ʒȱ����֤��ȷ�ϣ������������ֵ�ȱ�ݣ���Ҫ��ɿ�����ԭ���������֤��\r\n7�������ύ��Ʒȱ��ͳ�Ʒ������沢��ɲ�Ʒ�����ܽᱨ�棻\r\n8����ɲ����ŶӵĹ����˼�ҵ����ѵ����\r\n\r\n��λҪ��\r\n1�����רҵ��������ѧ����Ӣ�����ã�\r\n2������������ز��Թ������飻\r\n3����Ϥ��Ʒ�ṹ�������������ۣ������ƶ����Լƻ������Ʋ��Է������������ܹ��淶�������̣���Ϥ��ز��Թ��ߣ�����ʹ�ð칫�����\r\n4���߱����õ�ҵ��ͨ���������,�н�ǿ�����θм���ȡ����'),
(734, 79, '�������', 0, '', '', '��λְ��\r\n1����ɹ�˾��Ŀ����Ʒ��������ز��Թ�����\r\n2�����ݲ�Ʒ���������ĵ����ƶ����Լƻ�������������������Ʋ������̣�\r\n3�����ݲ�Ʒ����������ɲ��Ի�������������ù�����\r\n4��ִ�о����������ȷ�ϲ��Խ����ȱ�ݸ��٣���ɲ��Ա����Լ����Խ��������\r\n5���ڲ��Ը������뿪������Ʒ�Ȳ��Ź�ͨ��֤����������������ȷ�Ժ��걸�ԣ�\r\n6����ɲ�Ʒȱ����֤��ȷ�ϣ������������ֵ�ȱ�ݣ���Ҫ��ɿ�����ԭ���������֤��\r\n7�������ύ��Ʒȱ��ͳ�Ʒ������沢��ɲ�Ʒ�����ܽᱨ�棻\r\n8����ɲ����ŶӵĹ����˼�ҵ����ѵ������\r\n\r\n��λҪ��\r\n1�����רҵ��������ѧ����Ӣ�����ã�\r\n2������������ز��Թ������飻\r\n3����Ϥ��Ʒ�ṹ�������������ۣ������ƶ����Լƻ������Ʋ��Է������������ܹ��淶�������̣���Ϥ��ز��Թ��ߣ�����ʹ�ð칫�����\r\n4���߱����õ�ҵ��ͨ���������,�н�ǿ�����θм���ȡ����'),
(733, 79, 'ITƷ�ʹ���', 0, '', '', '��λְ��\r\n1���������缰���豸��ά�������������ų����ճ�������ȷ����˾�����ճ�������������\r\n2������˾�칫��������Ӳ��������ϵͳ���ճ�ά����\r\n3��ά���ͼ�ع�˾������������������֤���������У�ȷ�����������������ڹ����ڼ��ڰ�ȫ�ȶ����У�\r\n4����װ��ά����˾�������������ϵͳ�����Ӧ�������ͬʱΪ���������ṩ��Ӳ������֧�֣�\r\n5������ų�������Ӳ�����ϣ����ü�¼����������ϵͳ���б��棻\r\n6��ά���������ģ���ϵͳ���ݽ��б��ݡ�\r\n\r\n��λҪ��\r\n1��ͨ�š����ӹ��̡��Զ���������������רҵ����ר������ѧ����1����������ϵͳ��ITϵͳά���������飻\r\n2����Ϥ�����ո��ּ������Ӳ�����ɶ������а�װ�����Լ������ų���\r\n3����ͨ��������ά�������簲ȫ֪ʶ�����������о������Ĵ�������豸�Ļ���ά���͹��ϴ���\r\n4����������WINDOWS��server20002003�ȶԷ���������ά�������\r\n5�������õ�רҵӢ���дˮƽ������������ǿ������ϸ�£��������ģ��߱��ŶӺ�������'),
(732, 79, 'IT����֧��/ά������ʦ', 0, '', '', '��λְ��\r\n1���������缰���豸��ά�������������ų����ճ�������ȷ����˾�����ճ�������������\r\n2������˾�칫��������Ӳ��������ϵͳ���ճ�ά����\r\n3��ά���ͼ�ع�˾������������������֤���������У�ȷ�����������������ڹ����ڼ��ڰ�ȫ�ȶ����У�\r\n4����װ��ά����˾�������������ϵͳ�����Ӧ�������ͬʱΪ���������ṩ��Ӳ������֧�֣�\r\n5������ų�������Ӳ�����ϣ����ü�¼����������ϵͳ���б��棻\r\n6��ά���������ģ���ϵͳ���ݽ��б��ݡ�\r\n\r\n��λҪ��\r\n1��ͨ�š����ӹ��̡��Զ���������������רҵ����ר������ѧ����1����������ϵͳ��ITϵͳά���������飻\r\n2����Ϥ�����ո��ּ������Ӳ�����ɶ������а�װ�����Լ������ų���\r\n3����ͨ��������ά�������簲ȫ֪ʶ�����������о������Ĵ�������豸�Ļ���ά���͹��ϴ���\r\n4����������WINDOWS��server20002003�ȶԷ���������ά�������\r\n5�������õ�רҵӢ���дˮƽ������������ǿ������ϸ�£��������ģ��߱��ŶӺ�������'),
(731, 79, 'IT����֧��/ά������', 0, '', '', NULL),
(730, 78, '����IT����', 0, '', '', NULL),
(729, 78, 'IT��Ŀִ��/Э��', 0, '', '', NULL),
(728, 78, 'IT��Ŀ����/����', 0, '', '', '��λְ��\r\n1��������վ��Ŀ���������������Ŀ������ƺ��Ż�����������\r\n2��������ơ�ϸ����ʵʩ���򿪷��ƻ���������򿪷��Ŷӣ�\r\n3������ܹ���ƣ����ܺ�ģ�黮�֣��������ģ�鿪����\r\n4����������ѵ㡣\r\n\r\n��λҪ��\r\n1����������ѧ����3������PHP�������飬1�����Ͽ��������飻\r\n2����ͨjavascript,��css��php����ϤajaxӦ�á�SQL���ݿ���Ƽ�����Ӧ��SQL���ԣ�\r\n3����Ϥ����������̡����ģʽ���Ϻõ��ĵ����������õı�����\r\n4����Ƶ���μ�������E-learning���������������ȼѣ�\r\n5�����õ����ͱ�����������ڹ�ͨ���ܺõ��ŶӺ�����ʶ��'),
(727, 78, '��Ϣ��������/רԱ', 0, '', '', NULL),
(726, 78, 'IT�����ܼ�/����', 0, '', '', '��λְ��\r\n1����֯�ƶ���ʵʩ�ش������ߺͼ����������ƶ�������չս�ԡ��滮��չ����\r\n2���������Ŀ�����ƻ������ύ��Ŀ�����飻\r\n3��������Ŀ�ƻ�������ͳ����켼���Ŷ������Ŀ�������ĵ�����\r\n4�����м�������Ĺ��غ�Ԥ�У�\r\n5��ʵ������ļ������󣻽��ͻ�����ļ������⣬�ṩ����֧�֣�\r\n6���ⶨ�ŶӵĹ���Ŀ�겢�ලʵʩ��\r\n7���Ŷӹ���ָ��ѧϰ��������ѵ�������ŶӼ���ˮƽ��\r\n\r\n��λҪ��\r\n1�������רҵ����������ѧ����\r\n2��5�����Ϲ������飻3��������Ŀʵʩ�͹����飻\r\n3��������ͨ������ҵ����������˽⣻\r\n4��˼·���������Ա������ǿ�������õ�Ӣ��������\r\n5����ǿ��ѧϰ�������¼�����������\r\n6���������õ����������Լ��ŶӺ��������н�ǿ�������ġ�'),
(725, 77, '�����������Ӳ��', 0, '', '', NULL),
(724, 77, 'Ƕ��ʽ��/Ӳ������', 0, '', '', NULL),
(723, 77, 'Ӳ������ʦ', 0, '', '', '��λְ��\r\n1��������ɲ�Ʒ��Ӳ�����塢�߼���·������뿪����Э��PCB��Ƽ��������Ƽӹ���\r\n2����ĿҪ��������巽��������ѡ�͡�ԭ��ͼ��ơ����ԡ�����ά���Ż��ȹ��������������������\r\n3����ʱ��д�����ĵ��ͱ�׼�����ϣ�\r\n4���Ա���Ԫ��Ʒ�ṩ����֧�֣�\r\n5����ѵ��ָ��������������Ա��������ԪӲ�����̡�\r\n\r\n��λҪ��\r\n1�����Ƽ�����ѧ����ͨ�š�����������ӵ����רҵ��2��������ع������飻\r\n2���нϺõ���ģ��·���ź���ϵͳ����֪ʶ���߱�һ�������ϵ���ģ��·���Ծ��飻\r\n3����ͨProtel�ȿ������ߣ���ͨ����C���Կ�����\r\n4���ܹ������Ķ�Ӣ��������ϣ�\r\n5���������θ�ǿ���нϺõ����о�����ŶӺ�����ʶ��'),
(722, 77, '����������', 0, '', '', NULL),
(721, 77, 'WEBǰ�˿���', 0, '', '', NULL),
(720, 77, '�ֻ��������', 0, '', '', NULL),
(719, 77, 'ERP��������/Ӧ��/ʵʩ', 0, '', '', NULL),
(718, 77, 'ϵͳ����/�ܹ�', 0, '', '', '��λְ��\r\n1������ϵͳ��������к����������׫д��ؼ����ĵ�\r\n2���ϵͳ�������������ϵͳ��ܺͺ��Ĵ����ʵ�֣�\r\n3����Ŀ��Ҫ��ơ���ϸ��ơ������ƻ��ȵı��Ʋ�ʵʩ��\r\n4��ϵͳ�������ԡ�����ͼ��ɣ�\r\n5�����������������еļ������⣻\r\n6���������ά���뱸�ݡ�\r\n\r\n��λҪ��\r\n1��2������ʵ��JAVA��Ŀ����ʵʩ�������飬�������������רҵ����ѧ�����ϣ�\r\n2�����зḻJ2EE�ܹ���ƾ��飻��ͨjava��̡����ģʽ�������������Ϥ��ϵ�����ݿ⣻\r\n3������ʹ��uml��xml��صĿ������ߺʹ��������Ϥspring��struts��,hibernate�ȳ��ÿ�Դϵͳ\r\n4����Ϥ����JavaӦ�÷�������ʹ�ã���ϤLINUX��UNIX����ϵͳ��\r\n5������ʹ��eclipse��cvs��svn��ant��tomcat��mysql�ȳ��ÿ������ߺͿ���������\r\n6����Ϥ��Restful��WebService��xml-rpc��jax-ws��xmlschema�ȱ�׼������Ϥ��axis2��xfire��cxf��restlet���е����������Ϥ��apache��ϵ�п�Դ�����\r\n7���˽⡢JMS��JSON��restlet��json-rpc��Osgi��SCA��JMX��ESB���˽����ģʽ�����ģʽ���ܹ�������ã�\r\n8���߱����õ��ŶӺ�������͹�ͨ����������'),
(717, 77, '�������Ա', 0, '', '', '��λְ��\r\n1������������վ������ϵͳ������\r\n2��������Ŀ�������̣������з����ŵ������������\r\n3��������ϸ��ơ����뿪������ϲ��ԣ������������Ŀ��\r\n4�����뼼�����⹥�ء���֯�������۵ȹ�����\r\n\r\n��λҪ��\r\n1��һ�����Ͽ������飬����ҵ��Ӧ�ÿ������飻\r\n2����ͨJava/J2EE��̣���Spring+Hibernate�����ƿ�ܵ�ʵ����Ŀ���飬����ʹ��Eclipse�ȿ������ߣ�\r\n3����ϤJavaScript��Ajax��XML����ؼ�����\r\n4����ϤVelocity��Freemaker��ģ�����棻\r\n5����ϤOracle��MySQL�����ݿ⿪����SQL���ܵ��ţ�\r\n6���������ճ��õ�Linux�����shell�ű���Windows��Server�ĸ������Ӧ�����ã�\r\n7���������OO˼�룬��ϤUML���ԣ�\r\n8�������õĴ�����д��ע�ͺ͵�Ԫ����ϰ�ߣ��������ö���������ģʽ��\r\n9���߱����õĹ�ͨ�������ɣ���ǿ�������ļ��ŶӺ�������'),
(716, 77, '���ݿ⿪��/����', 0, '', '', '��λְ��\r\n1������ҵ��ϵͳ���ݿ�Ĺ滮����ơ�ʵʩ����Ʋ��Ż����ݿ������跽��\r\n2�������ݿ���й����������ݿ�Ӧ��ϵͳ����Ӫ����أ�\r\n3��ҵ��ϵͳ���ݿ�Ķ���ά�����쳣����\r\n4�������ݿ����ܷ�������ţ��Ŵ���֤���ݰ�ȫ��\r\n5�������ݿ���ж��ڱ��ݡ��Ͱ���ָ���\r\n6������������Ž��е����ݴ�����ѯ��ͳ�ƺͷ���������\r\n\r\n��λҪ��\r\n1����������רҵ����������ѧ����\r\n2������������ع������飻\r\n3����ͨ��ϵ���ݿ�ԭ��,��Ϥ���ݿ�ϵͳ�Ĺ滮����װ�����á����ܵ��ԣ�\r\n4����ͨSQL�ű��ı�д���зḻ�����ݿ������ά���ž��飻\r\n5������ʹ�����ݿ������������ƹ��ߣ�\r\n6�����ٴ���ϵͳͻ���¼�����������ǿ��ѧϰ�ʹ���������\r\n7�����õ����õĹ�ͨ�������ŶӺ�������'),
(715, 77, '�������ʦ', 0, '', '', '��λְ��\r\n1��������ϵͳ�����ʵ�֣���д����ע�ͺͿ����ĵ���\r\n2����������ϵͳ�Ĺ��ܶ���,������ƣ�\r\n3����������ĵ�������˵����ɴ����д�����ԣ����Ժ�ά����\r\n4�����������������������е����⣻\r\n5��Э�����Թ���ʦ�ƶ����Լƻ�����λ���ֵ����⣻\r\n6�������Ŀ��������������Ŀ�ꡣ\r\n\r\n��λҪ��\r\n1������������רҵ����ѧ�����ϣ�\r\n2��2����������������飻\r\n3����Ϥ�������˼�룬��ͨ��̣����Ժ���ؼ�����\r\n4����ϤӦ�÷������İ�װ�����ԡ����ü�ʹ�ã�\r\n5���߱����������ϵͳ������������Լ���ǿ���߼������Ͷ����������������\r\n6���������Ķ����ġ�Ӣ�ļ����ĵ��������ŶӾ���,���θк͹�ͨ������'),
(714, 76, '����������/����', 0, '', '', NULL),
(713, 76, '��Ϸ���/����', 0, '', '', '��λְ��\r\n1��������Ϸ��ں;���ϸ�ڵĲ߻�����ƹ�����\r\n2�����������Ϸ�����ձ���Ч����\r\n3��������Ϸ�����ִ��⡢������Ƶȹ�����\r\n4������Э������Ա��ԭ�������Ա�����Ϸʵ�֣�\r\n5����������г����С���������ȣ������û�ʹ������������棻\r\n6������ָ��ʱ�����͹������񣬲��ල��ʱ��ɡ�\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ����1����Ϸ�߻��������飻\r\n2����ͨphotoshop�����ͱ༭����ͼ�أ�����ƽ����ƻ�����\r\n3����Ϥ��Ϸ���������̼��������ڣ���Ϥ��Ϸ�г�������ҵ��չ��������ʶ��\r\n4�������õ��߼�˼ά������������Ĵ���������������\r\n5�����������ֹ��ף��߱����õ�ְҵ�������ŶӺ�������'),
(712, 76, '�ۺϲ���', 0, '', '', '��λְ��\r\n1����������ϵͳ�����滮����ƺʹ������ʩ��ͼֽ������������ʽ�㱨�����ٸ������罨����ȣ�������ֽ�������� \r\n2��������ͬ����ȥ��Կͻ��˽�ͻ���������ǰ����Ƶ���������˿ͶԲ��߷����Ҫ�� \r\n3���������û���ͨ,��ϸ�˽�����,�������Ƶ�ʩ���Ĺ������û����̿����ĵ����ơ��ύ��ȫ���̹���\r\n4�����ϵͳ����ʦ���ϵͳ������Ŀ���ڵ�ʵʩ������\r\n\r\n��λҪ��\r\n1. ��������רҵ����Ϥ��������������֪ʶ������CAD��ͼ��\r\n2. ��������ϵͳ���飨��Ҫ���ۺϲ��ߡ�ͨ�Ż����豸��װ/���ԡ����������ϵͳ������ϵͳ�ȣ���\r\n3. ��Ϥ������ҵ����ع淶�ͱ�׼�ȣ��߱���ǿ����Ŀ���Ŷӹ���Э��������\r\n4. �������飬������ǿ�����д��¾��񣬾������õ�ѧϰ��������������ͽ������������� \r\n5. �˼ʹ�ͨ����ǿ���������õ��Ŷ�Э������ \r\n6���ܹ�ִ�й�˾ҵ�����̼�����ʩ���淶��������ǿ��'),
(711, 76, '������Ϣ��ȫ����ʦ', 0, '', '', '��λְ��\r\n1������˾��ȫ���յ��������ӹ̺���ƣ�\r\n2������˾��ȫ�¼��ķ�������Ӧ��\r\n3������˾��ȫ���ԵĶ��ƺ�Ӧ�ã�\r\n4������˾��ȫ֪ʶ���о��ͳ���\r\n5��Ϊ��˾�ṩ��Ӧ�İ�ȫ������ѵ��\r\n\r\n��λҪ��\r\n1���˽���Ϣ��ȫ��ϵ�Ͱ�ȫ��׼��BS7799��ISO27001��������Ϣ��ȫ��ϵ�Ͱ�ȫ���������н�ȫ�����ʶ��\r\n2����Ϥ����·����������ǽ�������������ؾ���������豸��ѡ�͡�����ά������ȫ������\r\n3������Windows��Linux�Ȳ���ϵͳ��ϵͳ��ȫ���Ժ�ʵʩ��\r\n4����Ϥ������簲ȫ��Ʒ����AD�򡢷���ǽ��IDS��IPS��������ϵͳ��©���������ߡ���ز�Ʒ�ȣ�\r\n5������ص���Ŀ���飬�������İ�ȫ��Ʒ�Ƚ���Ϥ���ܱ�д�������ĵ����˽���ֹ��������������'),
(710, 76, '���繤��ʦ', 0, '', '', NULL),
(709, 76, '��վ�༭', 0, '', '', '��λְ��\r\n1��������վ�����Ŀ/Ƶ������Ϣ�Ѽ����༭����У�ȹ�����\r\n2�������Ϣ���ݵĲ߻����ճ�������ά����\r\n3����д��վ�������ϼ���ز�Ʒ���ϣ�\r\n4���ռ����о��ʹ���������ߵ�����ͷ�����Ϣ��\r\n5��������α༭��֯�߻��ƹ���������ִ�У�\r\n6��Э�����Ƶ����������Ŀ�ķ�չ�滮���ٽ���վ֪���ȵ���ߣ�\r\n7����ǿ���ڲ���ز��ź���֯�ⲿ�Ĺ�ͨ��Э����\r\n\r\n��λҪ��\r\n1���༭�����桢���š����ĵ����רҵ��ר������ѧ����\r\n2����ý��༭�����ҵ���������ȣ�\r\n3�������������õ���ҳ��������������������ߣ��˽���վ���������м�ά�������֪ʶ��\r\n4�����õ����ֹ��ף���ǿ����վר��߻�����Ϣ�ɱ�������\r\n5���ϸߵ�ְҵ��������ҵ�����ŶӾ������ڹ�ͨ��'),
(708, 76, '��վ�߻�', 0, '', '', '��λְ��\r\n1������˾��վ�ĸ���������ݵĹ滮�����\r\n2������˾��վ���ݵı༭����̳���ճ�����\r\n3��������վ��Ϣ���ݵĸ��º�ά��\r\n4��������Ŀ���Ϻ���Ϣ���Ѽ�������\r\n5��������վ��Ϣ���ݵı༭����У����֤��Ϣ���ݵĽ���\r\n6������ѡȡ��׫д��ժ¼��ת�ظ���վ���������\r\n7��Э�����ܲ߻���վ��վ�㡢Ƶ��ҳ�漰ר����\r\n\r\n��λҪ��\r\n1�����Ƽ�����ѧ�������š����ġ��������רҵ\r\n2�����нϹ��֪ʶ�棬���õ����ֱ༭��д������\r\n3����Ϥ���Բ�������ϤPhotoshop��Dreamweaver��Frontpage���������\r\n4����ϤHTML����ʹ�ã���������֪ʶ������վ��ҵ�����������ý���ҵ����������\r\n5���������ϣ�ѧϰ�����ã�������ʶǿ���ܹ����ܹ���ѹ��������������ǿ�������ŶӺ�������'),
(707, 76, '��վ�Ż�/SEO', 0, '', '', NULL),
(706, 76, 'it��Ŀ����', 0, '', '', NULL),
(705, 76, '��Ʒ����/רԱ', 0, '', '', NULL),
(704, 76, '��վ�ƹ�', 0, '', '', NULL),
(703, 76, '��վ��Ӫ', 0, '', '', '��λְ��\r\n1��������վ����ơ����輰�ճ�ά������£�\r\n2������վϵͳ���ݿ�����ճ�����ͳ�����ݿ��������Ϣ��\r\n3������˾��վ��Ӫ����˾Ʒ����ҵ���ƹ㣻\r\n4�������������еİ�ȫ�ԡ��ɿ��Լ��ȶ��ԣ�\r\n5��ά����˾������������վ�����ӡ���̨����ֽ��ý��ĺ�����ϵ��\r\n6������˾��վ�����ӡ���潻������վ����ĺ����ƹ㹤����\r\n\r\n��λҪ��\r\n1����������רҵ����������ѧ����\r\n2����һ������Ŀ��վ���辭�飬�д�����վ�������������ȿ��ǣ�\r\n3������ʹ��photoshop��flash��dreamweaver�ȹ��ߣ���ϤASP��JAVA,SQL,HTML�ȿ������ԣ�\r\n4�����Զ��������վǰ��̨��������Ϥ������B2B��B2C��վ����Ӫ���ƹ�Ӫ����\r\n5�����õĹ�ͨ�������Ŷ�Э�����������������ġ�ѧϰ����ǿ��'),
(702, 76, '�������Ա', 0, '', '', '��λְ��\r\n1�������ڲ���������ά����\r\n2������С�ͻ�����������·�������豸�����Լ�����ƽ̨�����м�غ�ά����\r\n3�����а칫�豸���ճ�ά����������������ά����\r\n4�����𲡶��Ĳ�ɱ��ά������ϵͳ��ȫ��\r\n5���������缰��������ϣ�\r\n6�������ڲ���Ϣϵͳ���衢ά����������������̨���ݡ��������\r\n\r\n��λҪ��\r\n1���������IT���רҵ����ѧ���ƣ�25������\r\n2��һ�������������������ܹ������飻\r\n3����Ϥ·������������������ǽ�������豸�����������\r\n4���˽����ϵͳ����ϤWEB��FTP��MAIL�������ļ��裻\r\n5��ѧϰ����ǿ���ϺõĹ�ͨ��Э����������ǿ��ִ�����͹�ͨ�������߱����õķ�����ʶ��'),
(701, 76, '�������������', 0, '', '', NULL),
(700, 76, '�Ա�����', 0, '', '', NULL),
(699, 76, '��ҳ���/����', 0, '', '', '��λְ��\r\n1������˾��վ����ơ��İ桢���£�\r\n2������˾��Ʒ�Ľ��������ơ��༭�������ȹ�����\r\n3���Թ�˾��������Ʒ����������ƣ�\r\n4������ͻ���ϵͳ�ڵĹ���ר�����ƣ�\r\n5�������뿪����Ա��������Ͻ��վ��ǰ̨ҳ����ƺͱ༭��\r\n6�����������������صĹ�����\r\n\r\n��λҪ��\r\n1��������ƽ��������רҵ��ר�Ƽ�����ѧ����\r\n2������������ҳ��Ƽ�ƽ����ƹ������飻\r\n3������ʵ���������ס����õĴ���˼ά������������ܼ�ʱ���տͻ�����\r\n4����ͨPhotoshop\\Dreamweaver\\Illustrator������������ͼƬ��Ⱦ���Ӿ�Ч���нϺ���ʶ��\r\n5���������˹�ͨ�����õ��ŶӺ�������͸߶ȵ����θУ��ܹ�����ѹ�����д��¾��񣬱�֤����������\r\n6��ӦƸʱ������ṩ������Ʒ��'),
(698, 75, '���������Ӧ��', 0, '', '', NULL),
(697, 75, '���Ի�ͼ', 0, '', '', '��λְ��\r\n1����������ƽ̨��ά����\r\n2����������ϵͳ���ϣ���������ϵͳ������\r\n3��ͳ������ϵͳ���������ݣ�\r\n4��Э����Ŀ���ܽ����Ŷӹ���\r\n\r\n��λҪ��\r\n1�����������ۻ�����\r\n2����ϤCisco������ص���Ӳ����\r\n3����ά������Cisco����ƽ̨���飻\r\n4��Ӣ����˵��д������\r\n5������Ը�����ڸ��༼����ѧϰ��ʵ������Cisco�������������֤�����ȡ�'),
(696, 75, '��ý�����', 0, '', '', '��λְ��\r\n1��������Ŀ��Ʒ��ͼ�ġ�����ý��Ĳ߻���ƹ�����\r\n2����������Ƭ��Ƭͷ��Ƭ�ж����Ĵ����������\r\n3������Ʒ������ѡ���������������\r\n4��ָ������ѵ��������������Ա����ơ�����������\r\n6�������ƶ�ƽ��Ͷ�����������ر�׼�����̹涨�����¹�����\r\n\r\n��λҪ��\r\n1���Ӿ�������ƻ�����������רҵ��ҵ����ר������ѧ����\r\n2����������ƽ�桢������ƾ��飬�ж�ý����ҵ��ҵ����Ϊ�ţ�\r\n3������ʹ����ض�ý����Ƽ����ڱ༭��������������ն����ű����ԣ�\r\n4����������ǿ�����⹹˼���ء�������ǿ���������õ��Ŷ�Э������\r\n5����ǿ�Ľ�����ƺͶ����Ʋ߻��������Ϻõ��ֻ��������˽�־�ͷ���Ʒ���\r\n7���߱������������̵Ĺ�ͨ����������'),
(695, 75, '���Բ���Ա/����Ա', 0, '', '', NULL),
(694, 75, 'ϵͳ����Ա/����', 0, '', '', NULL),
(684, 52, '��������', 0, '', '', '��λְ��\r\n1���������е��ճ�ҵ����������������ȹ�����\r\n2����������Ӫҵ��������û�ƺ��㣻\r\n3�����𿪾������ո�ƾ֤,��ʱ����,ƾ֤��������ȹ�����\r\n4��������˰�걨�����ƾ֤���ʲ��װ���ȹ�����\r\n5�����������ճ�ƾ֤���º�ල�������ܣ�\r\n6�����������ճ����񱨱�ı��ƺ͹��Լ�飻\r\n7����������Ӫҵ���ʽ������̨����ͺ��㣻\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ�������񡢻�Ƶ����רҵ��\r\n2����Ϥ�й�˰���ɣ��걨��ҵ����˰��\r\n3����Ϥ�ʲ���ծ�������Ȳ��񱨱�ı��ƣ�\r\n4������2���������в����飬�л��֤�����ȣ�\r\n5����������OFFICE�Ȱ칫����Ͳ��������\r\n6�����к�ǿ�������ģ�����ϸ�ģ����档'),
(683, 52, '���մ�����/������', 0, '', '', '��λְ��\r\n1���������е��ճ�ҵ����������������ȹ�����\r\n2����������Ӫҵ��������û�ƺ��㣻\r\n3�����𿪾������ո�ƾ֤,��ʱ����,ƾ֤��������ȹ�����\r\n4��������˰�걨�����ƾ֤���ʲ��װ���ȹ�����\r\n5�����������ճ�ƾ֤���º�ල�������ܣ�\r\n6�����������ճ����񱨱�ı��ƺ͹��Լ�飻\r\n7����������Ӫҵ���ʽ������̨����ͺ��㣻\r\n8����������Ӫҵ���ڿ��ƶȵ��ƶ��ͼ�鹤��\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ�������񡢻�Ƶ����רҵ��\r\n2����Ϥ�й�˰���ɣ��걨��ҵ����˰��\r\n3����Ϥ�ʲ���ծ�������Ȳ��񱨱�ı��ƣ�\r\n4������2���������в����飬�л��֤�����ȣ�\r\n5����������OFFICE�Ȱ칫����Ͳ��������\r\n6�����к�ǿ�������ģ�����ϸ�ģ����档'),
(682, 52, '���վ���/�з�/��ѵ', 0, '', '', '��λְ��\r\n1���������е��ճ�ҵ����������������ȹ�����\r\n2����������Ӫҵ��������û�ƺ��㣻\r\n3�����𿪾������ո�ƾ֤,��ʱ����,ƾ֤��������ȹ�����\r\n4��������˰�걨�����ƾ֤���ʲ��װ���ȹ�����\r\n5�����������ճ�ƾ֤���º�ල�������ܣ�\r\n6�����������ճ����񱨱�ı��ƺ͹��Լ�飻\r\n7����������Ӫҵ���ʽ������̨����ͺ��㣻\r\n8����������Ӫҵ���ڿ��ƶȵ��ƶ��ͼ�鹤����\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ�������񡢻�Ƶ����רҵ��\r\n2����Ϥ�й�˰���ɣ��걨��ҵ����˰��\r\n3����Ϥ�ʲ���ծ�������Ȳ��񱨱�ı��ƣ�\r\n4������2���������в����飬�л��֤�����ȣ�\r\n5����������OFFICE�Ȱ칫����Ͳ��������\r\n6�����к�ǿ�������ģ�����ϸ�ģ����档'),
(681, 52, '��������', 0, '', '', '��λְ��\r\n1���������е��ճ�ҵ����������������ȹ�����\r\n2����������Ӫҵ��������û�ƺ��㣻\r\n3�����𿪾������ո�ƾ֤,��ʱ����,ƾ֤��������ȹ�����\r\n4��������˰�걨�����ƾ֤���ʲ��װ���ȹ�����\r\n5�����������ճ�ƾ֤���º�ල�������ܣ�\r\n6�����������ճ����񱨱�ı��ƺ͹��Լ�飻\r\n7����������Ӫҵ���ʽ������̨����ͺ��㣻\r\n8����������Ӫҵ���ڿ��ƶȵ��ƶ��ͼ�鹤����\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ�������񡢻�Ƶ����רҵ��\r\n2����Ϥ�й�˰���ɣ��걨��ҵ����˰��\r\n3����Ϥ�ʲ���ծ�������Ȳ��񱨱�ı��ƣ�\r\n4������2���������в����飬�л��֤�����ȣ�\r\n5����������OFFICE�Ȱ칫����Ͳ��������\r\n6�����к�ǿ�������ģ�����ϸ�ģ����档'),
(680, 52, '�ͻ�����/���ڹ���', 0, '', '', '��λְ��\r\n1���������е��ճ�ҵ����������������ȹ�����\r\n2����������Ӫҵ��������û�ƺ��㣻\r\n3�����𿪾������ո�ƾ֤,��ʱ����,ƾ֤��������ȹ�����\r\n4��������˰�걨�����ƾ֤���ʲ��װ���ȹ�����\r\n5�����������ճ�ƾ֤���º�ල�������ܣ�\r\n6�����������ճ����񱨱�ı��ƺ͹��Լ�飻\r\n7����������Ӫҵ���ʽ������̨����ͺ��㣻\r\n8����������Ӫҵ���ڿ��ƶȵ��ƶ��ͼ�鹤����\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ�������񡢻�Ƶ����רҵ��\r\n2����Ϥ�й�˰���ɣ��걨��ҵ����˰��\r\n3����Ϥ�ʲ���ծ�������Ȳ��񱨱�ı��ƣ�\r\n4������2���������в����飬�л��֤�����ȣ�\r\n5����������OFFICE�Ȱ칫����Ͳ��������\r\n6�����к�ǿ�������ģ�����ϸ�ģ����档'),
(679, 52, '�˱�����', 0, '', '', '��λְ��\r\n1���������е��ճ�ҵ����������������ȹ�����\r\n2����������Ӫҵ��������û�ƺ��㣻\r\n3�����𿪾������ո�ƾ֤,��ʱ����,ƾ֤��������ȹ�����\r\n4��������˰�걨�����ƾ֤���ʲ��װ���ȹ�����\r\n5�����������ճ�ƾ֤���º�ල�������ܣ�\r\n6�����������ճ����񱨱�ı��ƺ͹��Լ�飻\r\n7����������Ӫҵ���ʽ������̨����ͺ��㣻\r\n8����������Ӫҵ���ڿ��ƶȵ��ƶ��ͼ�鹤����\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ�������񡢻�Ƶ����רҵ��\r\n2����Ϥ�й�˰���ɣ��걨��ҵ����˰��\r\n3����Ϥ�ʲ���ծ�������Ȳ��񱨱�ı��ƣ�\r\n4������2���������в����飬�л��֤�����ȣ�\r\n5����������OFFICE�Ȱ칫����Ͳ��������\r\n6�����к�ǿ�������ģ�����ϸ�ģ����档'),
(678, 52, '��ƹ���/����滮ʦ', 0, '', '', NULL),
(677, 52, '����ҵ����/����', 0, '', '', '��λְ��\r\n1���������е��ճ�ҵ����������������ȹ�����\r\n2����������Ӫҵ��������û�ƺ��㣻\r\n3�����𿪾������ո�ƾ֤,��ʱ����,ƾ֤��������ȹ�����\r\n4��������˰�걨�����ƾ֤���ʲ��װ���ȹ�����\r\n5�����������ճ�ƾ֤���º�ල�������ܣ�\r\n6�����������ճ����񱨱�ı��ƺ͹��Լ�飻\r\n7����������Ӫҵ���ʽ������̨����ͺ��㣻\r\n8����������Ӫҵ���ڿ��ƶȵ��ƶ��ͼ�鹤����\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ�������񡢻�Ƶ����רҵ��\r\n2����Ϥ�й�˰���ɣ��걨��ҵ����˰��\r\n3����Ϥ�ʲ���ծ�������Ȳ��񱨱�ı��ƣ�\r\n4������2���������в����飬�л��֤�����ȣ�\r\n5����������OFFICE�Ȱ칫����Ͳ��������\r\n6�����к�ǿ�������ģ�����ϸ�ģ����档'),
(676, 51, '����֤ȯ/�ڻ�/Ͷ��/����', 0, '', '', ''),
(675, 51, '���п�/���������ƹ�', 0, '', '', ''),
(674, 51, '���й�Ա/���', 0, '', '', ''),
(673, 51, '�ʲ�����/����', 0, '', '', ''),
(672, 51, '�Ŵ�����/��������', 0, '', '', ''),
(671, 51, '���տ���', 0, '', '', ''),
(670, 51, '����/�䵱/����/����', 0, '', '', ''),
(669, 51, '���ʾ���/רԱ', 0, '', '', ''),
(668, 51, 'Ͷ������ҵ��', 0, '', '', ''),
(667, 51, 'Ͷ��/������Ŀ����', 0, '', '', ''),
(666, 51, 'Ͷ��/��ƹ���', 0, '', '', ''),
(648, 22, '��������/����', 0, '', '', NULL),
(647, 22, '�������ϱ�д/����', 0, '', '', NULL),
(646, 22, '���Բ���Ա/����Ա', 0, '', '', NULL),
(645, 22, 'ͼ������/�ĵ�����', 0, '', '', NULL),
(644, 22, '����/����', 0, '', '', '��λְ��\r\n1����������ѧ������ҵ���������������������ϣ�\r\n2���̶��ʲ������顢�ʲ������ͬ�������д�����ҵ���Ͽ�ܾ��飻\r\n3����һ�������飻\r\n4����ǿ�������ĺ;�ҵ�������õ���֯Э����������ͨ��������ǿ�ķ������������������\r\n5������ʹ�ð칫����Ͱ칫�Զ����豸��\r\n\r\n��λҪ��\r\n1��1�������������ܹ������飻\r\n2����Ϥ�����������̣��칫��Ʒ�ɹ����̣���ҵ�ʲ�����\r\n3����ǿ�������ĺ;�ҵ�������õ���֯Э����������ͨ��������ǿ�ķ������������������\r\n4������ʹ�ð칫����Ͱ칫�Զ����豸��'),
(643, 22, 'ǰ̨/�Ӵ�', 0, '', '', NULL),
(642, 22, '��������/����', 0, '', '', NULL),
(641, 22, '��Ա', 0, '', '', '��λְ��\r\n1������ã����ʼѣ�������20-30�꣬Ůʿ���ȣ�\r\n2��1��������ع������飬���ء�������������רҵ���ȿ��ǣ�\r\n3����Ϥ�칫����������֪ʶ���������̣��߱����������ź�д����������ǿ������Ϳ�ͷ���������\r\n4����Ϥ����д����ʽ����������OFFICE�Ȱ칫�����\r\n5��������ϸ���档\r\n\r\n��λҪ��\r\n1��Ůʿ���ȣ�����ã����ʼѣ�����18��24�꣬���1.65���ϣ�\r\n2����ר������ѧ����1����ع������飬���ء�������������רҵ���ȿ��ǣ�\r\n3����ǿ�ķ�����ʶ������ʹ�õ��԰칫�����\r\n4���߱����õ�Э����������ͨ���������������ģ��Ը���ÿ��ʣ������׺�����\r\n5����ͨ��׼ȷ������\r\n6���߱�һ����������֪ʶ��'),
(640, 22, '�칫������', 0, '', '', NULL),
(639, 22, '����רԱ/����', 0, '', '', '��λְ��\r\n1����ݺ��޸ı��桢�ĸ�ȣ�\r\n2����ʱ׼ȷ�ĸ���Ա��ͨѶ¼������˾���硢���䣻\r\n3�������ճ��칫��Ʒ�ɹ������š��Ǽǹ����칫���豸����\r\n4��������ȱ�����־���շ��ճ�������־�������ʼ���\r\n5��Ա������ϵͳά��������ͳ�Ƽ������Ա����\r\n6����֤ǰ̨�������ʵĳ��㣨��ˮ��ֽ���豸���Ĳļ��������ݱ��ȣ������ý��㡣\r\n\r\n��λҪ��\r\n1�����ء�������������רҵ��ר����ѧ����\r\n2������������ع������飻\r\n3����Ϥ�칫����������֪ʶ���������̣���Ϥ����д����ʽ���߱����������ź�д����������������OFFICE�Ȱ칫�����\r\n4��������ϸ���桢������ǿ��Ϊ����ֱ���߱���ǿ������Ϳ�ͷ���������\r\n5������ã����ʼѣ�������20-30�꣬Ů�����ȡ�'),
(613, 7, '����ó��/�ɹ�', 0, '', '', NULL),
(612, 7, '�ɹ�Ա', 0, '', '', '��λְ��\r\n1��ִ�вɹ������Ͳɹ���ͬ����ʵ����ɹ����̣�\r\n2������ɹ�����������ȷ�ϡ����ŷ��������ٵ������ڣ�\r\n3��ִ�в����Ƴɱ����ͼ����Ʒ�����\r\n4�����������󡢹���Ӧ�̣�ά�������ϵ��\r\n5����д�йزɹ�����ύ�ɹ��������ܽᱨ�棻\r\n6����ɲɹ����ܰ��ŵ�����������\r\n\r\n��λҪ��\r\n1����ר������ѧ����XX�����רҵ��\r\n2��xx��ҵ1��������ع������飻\r\n3����Ϥ�ɹ����̣����õĹ�ͨ������̸�������ͳɱ���ʶ��\r\n4������ϸ�����棬������ǿ��˼ά���ݣ����н�ǿ���ŶӺ�������Ӣ������ǿ�����ȿ��ǣ�\r\n5�������õ�ְҵ���º��������ܳ���һ������ѹ����'),
(611, 7, '�ɹ�����/����', 0, '', '', '��λְ��\r\n1������˾�ɹ�������������ѯ�ۡ��ȼۡ�ǩ���ɹ���ͬ�����ա��������������ܹ�����\r\n2�����顢����������Ŀ���г���ȷ����Ҫ�Ͳɹ�ʱ����\r\n3�����ƹ�˾�ɹ��ƶȣ��ƶ����Ż��ɹ����̣����Ʋɹ�������ɱ���\r\n4����֯�Թ�Ӧ�̽�����������֤���������ˣ�\r\n5���ƶ����ŵĶ̡��С����ڹ����ƻ������Ʋ��ύ����Ԥ�㣻\r\n6������ɹ���Ա�ĸ�ǰ��ѵ���ڸ���ѵ������֯���ˣ�\r\n7��Э����˾�����ż乤����\r\n\r\n��λҪ��\r\n1����ѧ���Ƽ�����ѧ���������ࡢ���������רҵ��\r\n2��xx��ҵ3��������زɹ���ҵ���������飬��������ҵ�ɹ����������������ȣ�\r\n3����Ϥ�б�Ͳɹ����̣���Ϥ��Ӧ�����������ˣ���Ϥ���������ϵ��׼��\r\n4���߱����ò����ںͿ粿�ŵ���֯��Э�����������õ�̸�С��˼ʹ�ͨ�������Ŷ�Э������ǿ��\r\n5���߱���ǿְҵ�������ʡ�'),
(610, 7, '��֤Ա', 0, '', '', '��λְ��\r\n1������ó�׿�֤����֤�����յ����Ƶ��ĳ���������������ȵ��ݽ����ȫ����ִ�к͸��٣�ȷ����֤�������������������ȷ�ԣ�\r\n2���������񺯵硢��ͬ�ķ�����鵵������\r\n3����ϵ��ͨ�ͻ�����ʱ�����ͻ����������\r\n4���ϼ������������ع�����\r\n\r\n��λҪ��\r\n1����ר������ѧ��������ó�ס�����Ӣ�������רҵ��\r\n2��2������ó�׵�֤������������顢��������ع������������ȿ��ǣ�\r\n3����Ϥ��ó��֤�������̣���Ϥ����֤�����֪ʶ����һ���������֪ʶ�����ȿ��ǣ�\r\n4��������Ӣ�Ŀ�ͷ�������＼�ɣ������������ð칫�����\r\n5����������ϸ�¡��Ͻ����н�ǿ�Ĺ�����������θС�'),
(609, 7, '����Ա', 0, '', '', NULL),
(608, 7, '����Ա', 0, '', '', '��λְ��\r\n1����������ڻ�Ʒ�ı��ء����鼰���ֵ�֤����˺����ƣ�\r\n2������Э������,�̼��빫˾�ڲ�������˵����磻\r\n3������������Ӧ�ļ���ʱ�ջأ�\r\n4��������װ��ʱ������װ���������ز����Ӧ������ί�б����мල���������·��䣻\r\n5����ɹ�����ر���\r\n\r\n��λҪ��\r\n1����ר������ѧ��������ó�ס����������רҵ��\r\n2��һ������������������飬������������������������ȿ��ǣ�\r\n3����Ϥ��ر������̣���Ϥ��Ӧ�Ĺ��ҷ��ɷ��棬���б���Ա�ʸ�֤�����ȿ��ǣ�\r\n4���߱�һ���Ĺ�ͨ���������������������һ��Ӣ��������\r\n5���Ը��ʡ�Ϊ����ֱ������ϸ�ĸ���'),
(607, 7, 'ҵ�����', 0, '', '', '��λְ��\r\n1��������ڶ����ĸ��١���֤���������Լ������������漰�ĸ������ݣ�\r\n2��Э�����������Լ������������˾֮������磻\r\n3���ͻ���ҵ����ϵ��ͨ��\r\n4����ϲ������ú��������ʹ�����\r\n5���������ͳ�ƺ�������ع�����\r\n\r\n��λҪ��\r\n1����ר������ѧ��������ó�ס�����Ӣ����������רҵ��\r\n2��2��������ó����������������飬������������������������ȿ��ǣ�\r\n3����Ϥ������ҵ������������̣���Ϥ�������䰲�ż����ע�����\r\n4�����õ�Ӣ����˵��д�����������ļ����Ӧ�ü��ɣ���ǿ�Ĺ�ͨ�����������������������ǿ��\r\n5����ʵ��ҵ����ǿ�ҵĿ�ѹ�Ժ������ģ��ŶӾ���ѡ�'),
(606, 7, '����ó��רԱ', 0, '', '', NULL),
(605, 7, '����ó��רԱ', 0, '', '', '��λְ��\r\n1��ִ�й�˾��ó��ҵ��ʵʩó�׹�̣������г���\r\n2��������ϵ�ͻ������Ʊ��ۡ���������̸�У�ǩ����ͬ��\r\n3�������������١��������ֳ���װ��\r\n4������֤��ˡ����ء����㡢�ۺ����ȹ�����\r\n5���ͻ�����չ��ά����\r\n6��ҵ��������ϵ�����͹鵵��\r\n7�����ҵ�����Ļ㱨��\r\n\r\n��λҪ��\r\n1����ר������ѧ��������ó�ס�����Ӣ�������רҵ��\r\n2��2������ó������ҵ��������飬�����������������ȿ��ǣ�\r\n3����Ϥó�ײ������̼���ط��ɷ��棬�߱�ó������רҵ֪ʶ��\r\n4�����нϸߵ�Ӣ��ˮƽ���Ϻõļ��������ˮƽ���б���֤�����ó�ײ���֤�������ȿ��ǣ�\r\n5���������õ�ҵ����չ����������̸�м��ɣ�������ʶǿ�����н�ǿ����ҵ�ġ��ŶӺ�������Ͷ����������������ڿ��غʹ��¡�'),
(604, 7, '����ó�׾���/����', 0, '', '', '��λְ��\r\n1����֯����ó�ײ����ƶ����Ź����ƻ������Ԥ�㣬ȫ������ó�ײ����ճ���������\r\n2���ƶ����滮������ҵ�����̣��ռ���������ҵ��Ҫ��Ϣ���ݡ��������ع������г���\r\n3������ǩ������ó�׺�ͬ���ල��ִͬ�У���������������ˣ�\r\n4����Ҫ�ͻ��Ľ�Ǣ���硢��ϵά����\r\n5������ó�ײ���Ա�����������ˡ���ѵ�����͵ȹ�����\r\n\r\n��λҪ��\r\n1����ѧ���Ƽ�����ѧ��������ó�������רҵ��\r\n2��5�����Ͻ�����ҵ����������飬������������������������ȿ��ǣ�\r\n3����Ϥ������ҵ�����̣���Ϥ��ó�����ڷ������棬�߱�ó�׹���רҵ֪ʶ����ؼ��ܣ�\r\n4�����������Ӣ������˵������д��������Ϥʹ�ð칫�����\r\n5���߱��������֯�������������õĹ�ͨ��̸�м��ɣ����õĴ�����ʶ���ŶӺ���������������ʶ��������ǿ��'),
(603, 6, '�����ͷ�/����֧��', 0, '', '', NULL),
(602, 6, '�ͻ���ѵʦ', 0, '', '', NULL),
(601, 6, '�ͻ���ϵ����', 0, '', '', '��λְ��\r\n1����غ�ά��CRMϵͳ��ָ��ҵ��Ա����������¼��͹���ͻ���Ϣ���ݣ�\r\n2������˽�ͻ�����ϸ�ֿͻ����ͣ����зּ���������������ھ�Ǳ�ڿͻ����ص�ͻ���\r\n3���������ۡ������ҵ�����������ݣ��ṩ��Ч���棻\r\n4���ල����Ա�ͻ������ж��������ʱ������������ܲ��ƶ�������\r\n\r\n��λҪ��\r\n1����ع�������2�����ϣ�\r\n2�����õĹ�ͨ�����Ա���������ж���������˼����������������\r\n3���ܳ��ܽ�ǿ�Ĺ���ѹ���������õ�ѧϰ������\r\n4����Ч���й�ͨ��Э������������֯����/Ӧ��������ǿ��\r\n5������Ӧ��Office�칫�����\r\n6��Ϊ����ֱ������̤ʵ�ڷܣ���CRM��ع������������ȡ�'),
(600, 6, '�ۺ���֧��', 0, '', '', '��λְ��\r\n1���˽�ͻ�����������Ϣ��������Ч���٣�������ǰ���ۺ�ָ���ͷ������� \r\n1���������ù�˾��Ʒ�����ͻ����ʲ���ʵ���⣻ \r\n3������ز��Ž�����ϣ�Э����ͨ��\r\n5��ά���ͻ���ϵ���������¿ͻ�\r\n\r\n��λҪ��\r\n1������1���������ۻ�ͷ��������飻\r\n2���߱��������ҵ��ʶ����ǿ��Ӧ����������ͷ����빵ͨ������\r\n3���н�ǿ���ƹ��ά��Э���ͻ�����������Ϥ�ͻ��������̣�\r\n4���߱���ǿ��ѧϰ�������ɿ�������רҵ֪ʶ����ʱ��չ������\r\n5����������office�����õ��ĵ�д��������\r\n6�������Ͻ����ƻ���ǿ�����ڷ���˼�����⣬�������ģ�'),
(599, 6, '��ǰ����֧��', 0, '', '', '��λְ��\r\n1���˽�ͻ�����������Ϣ��������Ч���٣�������ǰ���ۺ�ָ���ͷ������� \r\n1���������ù�˾��Ʒ�����ͻ����ʲ���ʵ���⣻ \r\n3������ز��Ž�����ϣ�Э����ͨ��\r\n4��ά���ͻ���ϵ���������¿ͻ�\r\n\r\n��λҪ��\r\n1������1���������ۻ�ͷ��������飻\r\n2���߱��������ҵ��ʶ����ǿ��Ӧ����������ͷ����빵ͨ������\r\n3���н�ǿ���ƹ��ά��Э���ͻ�����������Ϥ�ͻ��������̣�\r\n4���߱���ǿ��ѧϰ�������ɿ�������רҵ֪ʶ����ʱ��չ������\r\n5����������office�����õ��ĵ�д��������\r\n6�������Ͻ����ƻ���ǿ�����ڷ���˼�����⣬�������ģ�\r\n7���ڷ�̤ʵ�����õķ�����ʶ���ŶӺ�������'),
(598, 6, '�Ա��ͷ�', 0, '', '', NULL),
(597, 6, '����ͷ�', 0, '', '', NULL),
(596, 6, '�ͷ�רԱ', 0, '', '', '��λְ��\r\n1�����������绰�ͻ����ܹ���ʱ���ֿͻ����Ⲣ������ȷ������Ļظ���\r\n2����ͻ��������õ���ϵ����Ϥ���ھ�ͻ����󣬲��Կͻ�����ϵͳ��Ӧ����ѵ��\r\n3���߱��������⡢���Ž�չ���������̡���ͨ����������������ʶ������������޶ȵ���߿ͻ�����ȡ��������ܽ�������ⰴ�����ύ�����Ա�����ܴ��������ٽ�չֱ�������\r\n4���߱�һ����������������Թ�˾���еĿͻ�����Ӫ�����ÿͻ����ܸ�Ϊ�㷺�������Ʒ���ﵽ��õ�����Ӫ����Ч����\r\n5�����Ͻ��ܹ�˾�ĸ���ҵ��ͼ���������ѵ��\r\n\r\n��λҪ��\r\n1��ר��ѧ������һ���ͻ���������������۾��飬��һ���Ŀͻ�����֪ʶ������ ��\r\n2�����������������office�칫���ʹ����������һ��������֪ʶ����������ʹ��Photoshop����ͼ���������ȿ��ǡ�\r\n3��Ҫ��һ��Ҫ�С��ͻ�Ϊ�ȡ��ķ�����һ�дӰ����ͻ�������ͻ��Ƕȳ����� \r\n4���Ը�Ҫ����ȡ����̣�������������ͬ���ģ��ֹۡ���������ͨ����׼����������Ӧ������ \r\n5���Ȱ���������ҵ���ڿң�����˼�����������ҷ�չ������Ը��������ѧϰ���������ʵ��Ӱ������ȡ�'),
(595, 6, '�ͷ�����/����', 0, '', '', NULL),
(594, 6, '��������/�绰�ͷ�', 0, '', '', '��λְ��\r\n1�����ݹ�˾�ṩ�Ŀͻ��绰����ͻ��ƹ㹫˾�Ĳ�Ʒ���� \r\n2����������ͻ����ߣ�Ϊ�ͻ����⡢�ƹ��Ʒ��\r\n3��ͨ���绰����ͻ���Լ�ù�����\r\n4��Э����������Ŷӣ���������ҵ����\r\n\r\n��λҪ��\r\n1��������������ͨ����׼����ͨ��������ѣ�\r\n2�����������칫�Զ����豸��OFFICE�����\r\n3�����õ�ִ�������ŶӺ�������\r\n4����Ϥ�绰���ۻ�ͻ������ҵ��ģʽ���е绰���۾��������ȡ�'),
(593, 5, '�����г�/�߻�/����', 0, '', '', NULL),
(592, 5, '����/���߻�', 0, '', '', NULL),
(591, 5, 'ý�龭��/רԱ', 0, '', '', '��λְ��\r\n1���������硢��չ��ά��ý���ϵ������������ϵ������ý����Դ���Ϻ�ά����\r\n2����ý�������Ϣ������⣬�������γɼ�ⱨ�棬���紫������Ѽ���������Ŀ�����ȣ�\r\n3���߻���ִ����ý�������Ŀ���ƹ����Լ����������ִ�У�\r\n4�����о������ֹ��ش���������з��������ڳ��ߵ��б��棻\r\n5����Լý����Ա�μӸ������͵Ļ�ȡ�\r\n\r\n��λҪ��\r\n1����������ѧ�������ء����Ŵ���ѧ���г�Ӫ�������רҵ���ȣ�\r\n2���������Ϲ��ع�˾����ҵ�������飬�л�������ҵ��ҵ���������ȣ�\r\n3���ܹ�������֯���ŷ����ᡢר�ü��������ػ��\r\n4��ӵ�зḻ��ý����Դ���ܹ�����׫д���������ļ���\r\n5����������Ա���������ܹ��ڹ��ػ�н��и��ڸ�Ⱦ�����ݽ���\r\n6����ǿ�Ĺ۲�����Ӧ��������������˼ʽ�����Э����������ǿ�����������'),
(590, 5, '������/רԱ', 0, '', '', '��λְ��\r\n1��������˺�����λ�Ļ�չ�ƻ���Э����չ��Ŀ��\r\n2������չ���г��ƹ���̵ľ������񣬲�Э����ɻ��֯�빵ͨЭ���ľ��幤����\r\n3��Э�����ܽ��л�������ƶ�������Э�����ϸ�����Դ��ȷ�Ϻ�ִ�л�������Իȫ���̽�����֯����\r\n4����չ�ṫ˾��������������ƶ��������ۡ�\r\n\r\n��λҪ��\r\n1����洫ý���г�Ӫ�������רҵר�Ƽ�����ѧ����\r\n2����������չ��չʾ��ع������飬�д���չ��ʵս�������������ȣ�\r\n3����Ϥ����չ����ִ�����̣�\r\n4������һ������Ŀ��������������Ͻ��ҵ����֪��\r\n5����������Ĺ�ͨ���ɺ��˼ʽ�����'),
(589, 5, '����רԱ', 0, '', '', '��λְ��\r\n1�����𹫹ؿͻ��Ŀ�����ά����\r\n2������ֱ���쵼�İ��ţ����г���ͬ��Эͬ����������Ч���ʵ����������ͻ��ճ���������\r\n3������˾������Ҫ��ĽӴ������ع�����\r\n4������˾��Ʒ�Ľ��⼰���ܡ�\r\n\r\n��λҪ��\r\n1��1�����Ϲ�����ҵ���飬���Ŵ�����������ϵ�����ġ��г�Ӫ�������רҵר�Ƽ�����ѧ����\r\n2����ͨ����׼�����о�ֹ�󷽵��壻\r\n3������Ķ����������ѵĹ�ͨ��������ǿѧϰ������Э���������ŶӾ���\r\n4���ܳ��ܽϴ���ѹ������Ӧ�����ڼӰࡣ'),
(588, 5, '���ؾ���/����', 0, '', '', '��λְ��\r\n1�������ƶ���ִ���г����ؼƻ�����Ϲ�˾��Ŀ�߻������Ŷ���ĸ���ػ��\r\n2�������ύ���ػ���沢���г���������ṩ���飻\r\n3����չ���ڹ�ϵ���飬����ʱ���������������ߣ�\r\n4���߻�������Ҫ�Ĺ���ר����Э�����������Ĺ�ϵ��\r\n5�������ƶ���ʵʩ��˾���Ŵ����ƻ���ʵʩ���������ļල��Ч��������\r\n6���ṩ�г����ؼ����������ˡ�չ�ᡢ�ֳ���ȷ���Ĺ���֧�֣�Э���Ӵ���˾������\r\n7��������ά��������ϵ���ݿ⡢�����ĵ���\r\n\r\n��λҪ��\r\n1��ר������ѧ�������ء����Ŵ���ѧ���г�Ӫ�������רҵ���ȣ�\r\n2����Ϥ_____��ҵ���������ϴ�������ҵ���ء��г���������򹫹ع�˾�������飻\r\n3������ҵ�Ļ��������봫���͹�����ϵ�Ľ�����ά���н�Ϊ��̵���⣻\r\n4�����га���䡢���ŷ�������ش��Ĺ������飻\r\n5�����õ����ֹ��ף����н�ǿ����֯������Э����������Դ��������\r\n6��˼ά���ݡ����ڹ�ͨ���׺���ǿ���������ʼѣ�������'),
(587, 5, '��������/����', 0, '', '', NULL),
(586, 5, '�г�����', 0, '', '', NULL),
(585, 5, '��߻�/ִ��', 0, '', '', NULL),
(584, 5, 'Ʒ�ƾ���/רԱ', 0, '', '', '��λְ��\r\n1��Э��Ʒ������ʵʩ��ҵ��Ʒ���ƹ�ƻ���\r\n2��������ά����ҵ��������ϵ����������Ͳ߻����\r\n3�����в�Ʒ�г��ƹ�Ĳ߻���ʵʩ,�����ƹ�Ч�����и��٣�\r\n4�����ڷ����г����,�������Ч�ƹ�Ľ��顣\r\n\r\n��λҪ��\r\n1���г�Ӫ���������ࡢ���������רҵר������ѧ����\r\n2������_______��ҵ�Ĵ�ҵ��������Ʒ��רԱ�������������ȣ�\r\n3���нϺõ��ۺ����ʼ��Ļ�������\r\n4����ʵ�ڷܣ������õĹ�ͨ��Э����������ǿ��ִ��������\r\n5�������׺�������ҵ�����ŶӺ�������\r\n6����������OFFICE�����'),
(583, 5, '�г�����/ҵ�����', 0, '', '', '��λְ��\r\n1��Э�������ƶ��г���չ�ƻ����������ר����е���֯��ִ�У�\r\n2����������ڸ��������л����Ĺ�ͨ��������ԭʼ���ݺ;����Ҫ���������������档\r\n3����ϸ���������γ��ŵ�����������ĵ���������Ӧ�ܽ���������γɾ�����ۣ�\r\n4�������г�����������Ϣ��Ӧ�̵�ͳһ�������������\r\n5��������ͳ�Ʒ�������ȱ���\r\n6���ռ��������ֻ��Ʒ�ĸ�����Ϣ������ԡ�����ƹ����ϵȣ���������г����С�\r\n\r\n��λҪ��\r\n1���г�Ӫ������������רҵ��������ѧ����\r\n2������_______��ҵ�Ĵ�ҵ��������רҵ�����г����й����������Ͼ��������ȣ�\r\n3����Ϥ��Ʒ�ṹ�������Ʒ�������������з�ɢ��˼ά�ʹ�����ʶ��\r\n4������˼�����߱����õ�Ӧ����������ͨЭ��������������֯������\r\n5����������Ķ�������һ���ķ����ж�������\r\n6�������������Ը��ʣ��ܹ�������Ϊ�㷺����ҵ��������ϵ��'),
(582, 5, '�г���չ', 0, '', '', NULL),
(581, 5, '�г��߻�����/רԱ', 0, '', '', '��λְ��\r\n1��������ҵ�鱨���ռ�����Ϣƽ̨�Ĺ滮���о��г��ĺ�۷������Ϣ�������г���̬������Ʒ�ƶ��򡢲�Ʒ���г���Ϣ��\r\n2�������ƶ��ꡢ�����¶��г��ƹ㷽����������ִ�У�\r\n3��������ɹ��߻�������Ʒ���ƹ㷽����������Ʊ����׫д��\r\n4��Э����˾�ڲ�������ʵʩ�������Ʒ�ơ���Ʒ�ƹ��Ч������������Ľ�������\r\n\r\n��λҪ��\r\n1���г�Ӫ������������רҵר������ѧ����\r\n2������_______��ҵ�Ĵ�ҵ���������г��߻��������飻\r\n3��������İ����ף��н�ǿ�Ĵ�����˼ά���������������õĹ�ͨ������\r\n4���˽��г���̬�������г��仯��ʱ�߻��ƶ���������������߻����ڵĴ������\r\n5����һ������֯ʵʩ���飬�ල��ָ������ʵ�������ִ�У��гɹ��Ĳ߻����������ȣ�\r\n6�����ۺ����ð������߻����������������ػ�����ڵĸ���Ӫ����ʽ�����г�������Ʒ���ƹ��������\r\n7�����������칫�����'),
(580, 5, '�г�Ӫ��', 0, '', '', NULL),
(579, 5, '�г�רԱ/����', 0, '', '', '��λְ��\r\n1��Э��������֯չ���г������������۽�����ϣ�ִ����ز�Ʒ���г�Ӫ����ƻ�����������Ӧ�ķ����뷴����\r\n2�����г��������ָ���£������Ʒ�ն˳��С�չʾģʽ����������ѵ��ָ����\r\n3�������Ʒ���ʹ����ƻ���ִ�С����ٺͷ�����������Ʒʹ�õ�ִ�кͼල��\r\n4���˽⡢�����������г����������Э���������������Ʒ��ͻ���¼���\r\n5��Э��չ���г����顢�����г���Ը��֯����������������г���ְ�������Э����ִ�к͹���\r\n6�������Ҫ�г����Ͷ����������׼�����ṩ��ҵ�г����ݵĴ���������\r\n7��Э��������������г��ƻ���\r\n\r\n��λҪ��\r\n1����ѧ��������ѧ�����г�Ӫ���������������������רҵ���ȣ�\r\n2����Ϥ_______��ҵ��һ��������ع������飬���в�Ʒ�г�רԱ��ҵ���������ȣ�\r\n3���߱���ǿ����Ӣ���ֱ���������ĵ���֯��д������\r\n4������ʹ��MS Office�����д��Ʒ�ĵ�����Ʒ��ʾ�ĸ�ͽ������ݷ�����\r\n5�����н�ǿ�Ĺ滮�����������ʹ�����ʶ���Բ�Ʒ��������Ӫ���� , ˼ά������������\r\n6�����õĹ�ͨ��Э���������������ǿ��ͻ����ִ��������\r\n7�����õ�ְҵ���ʺ;�ҵ����'),
(578, 5, '�г�����/����', 0, '', '', '��λְ��\r\n1��������й�˾�г�ս�Թ滮���ƶ���˾���г����幤���ƻ�������г��ƹ㡢Ʒ�ơ����ء���ȷ���ľ��巽���ʵʩ������\r\n2����֯�ͼලʵʩ����г��ƹ�ƻ���\r\n3�������г�������������о�ͬ�С�ҵ�緢չ״�������ڽ����г�Ԥ�⼰�鱨������Ϊ��˾�����ṩ���ݣ�\r\n4���ƶ���˾���幫�ز��Լ�Σ�����ص�Ӧ�Դ���\r\n5�����������г������������Լ��ƶȹ淶��\r\n6���ƶ��г��ƹ����Ԥ�㼰�г���ȫ���������Ԥ���ƶ��������Լ����Ƽ��������ƶȣ�\r\n7�������г��Ŷӣ������Ŷӳ�Ա����ز��Ž����г���ѵ��ָ����\r\n\r\n��λҪ��\r\n1���г�Ӫ������������רҵ��������ѧ����\r\n2�������г�Ӫ���������飬�������ҵ��ְ�г��ܼ��������ϣ������г��߻���ҵ�Ĵ�ҵ�������Ը�����չ�������⣻\r\n3���߱���ǿ�Ĳ߻���������Ϥ����ý��������ʽ���д����г���ƹ�ɹ����飻\r\n4���������е���ҵ���г���ʶ���������⼰�����������ǿ�������������Դ����������ҵ���ƽ�������\r\n5���߱����õĹ�ͨ�������ɼ��ḻ���Ŷӽ��辭�顣'),
(577, 5, '�г��ܼ�', 0, '', '', NULL),
(576, 4, '������������������', 0, '', '', NULL),
(575, 4, '�г�����', 0, '', '', NULL),
(574, 4, '��������', 0, '', '', NULL),
(573, 4, '�������', 0, '', '', '��λְ��\r\n1����������ͻ����ϣ�\r\n2�����ܿͻ��������������۶������������Խӣ�\r\n3���������繵ͨ�ͻ���\r\n4��Э���������Ʋ��Ź����ƶȺͲ���������淶���������۵ĺ�̨֧�֣�\r\n\r\n��λҪ��\r\n1����ר������ѧ��������ó�ס�����Ӣ����רҵ��\r\n2��1����������������������飻\r\n3���������ƹ㡢�߻�����ľ��飻\r\n4��Ӣ�Ｐ�����������������һ������֪ʶ�����ȿ��ǣ�'),
(572, 4, '����רԱ/����', 0, '', '', '��λְ��\r\n1����������ͻ����ϣ�\r\n2�����ܿͻ��������������۶������������Խӣ�\r\n3���������繵ͨ�ͻ���\r\n4��Э���������Ʋ��Ź����ƶȺͲ���������淶���������۵ĺ�̨֧�֣�\r\n6����ص�����Э�顢��ͬ�ȴ浵����\r\n\r\n��λҪ��\r\n1����ר������ѧ��������ó�ס�����Ӣ����רҵ��\r\n2��1����������������������飻\r\n3���������ƹ㡢�߻�����ľ��飻\r\n4��Ӣ�Ｐ�����������������һ������֪ʶ�����ȿ��ǣ�\r\n6�����õ����Ա�Ｐ��ǿ�Ĺ�ͨ��������������ϸ�£�������ȡ������ѧϰ�봴�¡�'),
(571, 4, '������/����', 0, '', '', '��λְ��\r\n1����������ͻ����ϣ�\r\n2�����ܿͻ��������������۶������������Խӣ�\r\n3���������繵ͨ�ͻ���\r\n4��Э���������Ʋ��Ź����ƶȺͲ���������淶���������۵ĺ�̨֧�֣�\r\n5����ص�����Э�顢��ͬ�ȴ浵����\r\n\r\n��λҪ��\r\n1����ר������ѧ��������ó�ס�����Ӣ����רҵ��\r\n2��1����������������������飻\r\n3���������ƹ㡢�߻�����ľ��飻\r\n4��Ӣ�Ｐ�����������������һ������֪ʶ�����ȿ��ǣ�\r\n5�����õ����Ա�Ｐ��ǿ�Ĺ�ͨ��������������ϸ�£�������ȡ������ѧϰ�봴�¡�'),
(570, 4, '����������Ա', 0, '', '', '��λְ��\r\n1������˾���ۺ�ͬ���ļ����ϵĹ������ࡢ���������ͱ��ܣ�\r\n2�������������ָ����¶ȡ����ȡ����ͳ�Ʊ���ͱ������������д������ʱ�㱨���۶�̬��\r\n3�������ռ������������г����飬����������棻\r\n4��Э�����۾������õ绰���ù�������������Աȱϯʱ��ʱת��ͻ���Ϣ�����ƴ���\r\n5��Э�����۾������ò������񡢸����ڲ�����ļ�¼�ȹ�����\r\n\r\n��λҪ��\r\n1��ר������ѧ�����������ʼѣ�\r\n2�����¹����������ͳ���๤�������ȿ��ǣ�\r\n3���������桢ϸ�ġ�����\r\n4������ʹ��office�Ȱ칫�����\r\n5�����з�����ʶ������Ӧ�ϴ�Ĺ���ѹ����\r\n6�����������н�ǿ�Ĺ�ͨЭ��������'),
(569, 4, '��������', 0, '', '', NULL),
(568, 4, '������������/����', 0, '', '', NULL),
(567, 3, '����������Ա', 0, '', '', NULL),
(566, 3, '����Ա/����Ա', 0, '', '', '��λְ��\r\n1�����ݹ�˾������滮��Э�������ƶ���ȴ����ƻ�����������Ԥ�㣻\r\n2������˾�Ĳ�Ʒ�ƹ㣬�����ۺ���񣬽������õĿͻ���ϵ��\r\n3����ʱ������ɴ����������ͳ�Ʊ���\r\n4��������Ʒ����Ʒ����ơ����������Ź���\r\n5����Ʒ��Ϣ���ռ��뷴����\r\n6�����մ����ƻ�ʵʩ������\r\n��λҪ��\r\n1���г�Ӫ���������ࡢ���������רҵר������ѧ����\r\n2����һ���г��߻���������ͨЭ��������\r\n3���нϺõ��ۺ����ʼ��Ļ���������ҵ�����ŶӺ�������\r\n4�������׺�������ǿ��ִ��������\r\n5����������OFFICE�����'),
(565, 3, '���մ�����/������', 0, '', '', '��λְ��\r\n1��������¿ͻ�Ͷ����ƹ滮�ͱ��ղ�Ʒ���ۣ�\r\n2�����������֯�ķ�չ��ѵ������������\r\n3������Э�������ͻ��ı��ռ�����״����\r\n4�������ڽ���ҵ����ѵ��\r\n5�����𸨵����˵����ۡ���ѵ����������\r\n6������Ϊ�α��ͻ��ṩ�����۱��յ�һ�з���\r\n\r\n��λҪ��\r\n1����ר������ѧ����������25-45���꣬��Ů���ޣ�\r\n2�����վ��á����ڡ������רҵ֪ʶ��\r\n3����Ϥ���ղ�Ʒ�ر���ص�רҵ֪ʶ��\r\n4�����н�ǿ�Ĺ�ͨ����֯Э���������׺�����\r\n5���������õ����Ա�������������ж�������\r\n6���л�����ȡ�ľ��񼰽�����ս���Ը�\r\n7���������õ������ġ���һ�����Ŷ�Э������'),
(564, 3, '���ز�����/��ҵ����', 0, '', '', '��λְ��\r\n1�� ����ͻ��ĽӴ�����ѯ������Ϊ�ͻ��ṩרҵ�ķ��ز���ҵ��ѯ����\r\n2�� ��ͬ�ͻ��������ٳɶ��ַ�����������ҵ��\r\n3�� ����˾��Դ��������ۣ�����ҵ���������õ�ҵ��Э����ϵ��\r\n\r\n��λҪ��\r\n1��������20��30���꣬��ר����ѧ����\r\n2����ʵ���ţ��Կ����ͣ��������õ��ŶӾ���\r\n3���ܳ��ܽ�ǿ�Ĺ���ѹ����Ը����ս��н��\r\n4����ͨ��������'),
(563, 3, '��������/������', 0, '', '', '��λְ��\r\n1�������������۷���ͽ���ͻ���ѯ����\r\n2��������������͵��������ϼ��ͻ�������\r\n3�������ز�Ʒ�������г�����ɸ�������ָ�ꣻ\r\n4�������ھ�ͻ�����ʵ�ֲ�Ʒ���ۣ�\r\n5��������ǰҵ��������ۺ�ͻ�άϵ������\r\n\r\n��λҪ��\r\n1����ר������ѧ�����м�ʻ֤����ʻ����������ã����ʼѣ�\r\n2��������ǿ������̬�Ȼ������Ȱ��������۹�����\r\n3���н�ǿ����ҵ�ģ����������ս��\r\n4�����õĹ�ͨ�ͱ��������Ӧ�������ͽ��������������������ʼѣ�\r\n5�����õ��Ŷ�Э������Ϳͻ�������ʶ��\r\n6�������۾�����г�Ӫ��רҵ���ȡ�'),
(562, 3, 'ҽ����е����', 0, '', '', NULL),
(561, 3, 'ҽҩ����', 0, '', '', '��λְ��\r\n1����Ͻ����ҽԺ���й�˾��Ʒ���ƹ����ۣ������������\r\n2��������Ҫ�ݷ�ҽ����Ա����ͻ��ƹ��Ʒ��������߲�Ʒ�г��ݶ\r\n3������Ǳ�ڵ�ҽԺ�����ͻ������Լ��еĿͻ�����ά����\r\n4������˽��г�״̬����ʱ���ϼ����ܷ�ӳ�������ֵ�������г���̬������������飻\r\n5���ƶ���ʵʩϽ��ҽԺ�������ƻ�����֯ҽԺ�ڸ����ƹ���\r\n6��������˾���������� �Թ�˾��ҵ�����������ܡ�\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ����ҽҩ��Ӫ�������רҵ��\r\n2��2���������۹������飬��ҽ�����ġ��Ĳġ�ҩƷ���۾��������ȣ�\r\n3����ҽԺ���۾��飬��ϤҽԺ�������̣�ӵ�����õ�ҽԺ��Դ�������������Ȱ�ҩƷ���۷�������\r\n4�����н�ǿ�Ķ��������������罻���ɣ��ϺõĹ�ͨ������Э���������ŶӺ���������\r\n5�����彡�������ж��������ͽ�������������'),
(560, 3, '��������', 0, '', '', '��λְ��\r\n1������������й�˾��Ʒ�����ۼ��ƹ㣻\r\n2������˾����ó��ƽ̨�Ĳ�������Ͳ�Ʒ��Ϣ�ķ�����\r\n3���˽���Ѽ������ϸ�ͬ�м�������Ʒ�Ķ�̬��Ϣ��\r\n4��ͨ�������������������ҵ����չ��\r\n5����ʱ�����������\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ�����г�Ӫ�������רҵ��\r\n2��2�������������۹������飬���������������������ȣ�\r\n3����ͨ�����������ۼ��ɣ������Ͽ������ع������飬��Ϥ�����Ż���վ����������վ��\r\n4����Ϥ�������磬����ʹ�����罻�����ߺ͸��ְ칫�����\r\n5���н�ǿ�Ĺ�ͨ������'),
(559, 3, '����רԱ', 0, '', '', NULL),
(558, 3, '�������', 0, '', '', NULL),
(557, 3, '�ͻ�����', 0, '', '', NULL),
(556, 3, '�绰����', 0, '', '', '��λְ��\r\n1�������Ѽ��¿ͻ������ϲ����й�ͨ�������¿ͻ���\r\n2��ͨ���绰��ͻ�������Ч��ͨ�˽�ͻ�����, Ѱ�����ۻ��Ტ�������ҵ����\r\n3��ά���Ͽͻ���ҵ���ھ�ͻ������Ǳ����\r\n4������������ͻ����й�ͨ���������õĳ��ں�����ϵ��\r\n\r\n��λҪ��\r\n1��20-30�꣬�ڳ���������ͨ���������������и�Ⱦ����\r\n2�������۹����нϸߵ����飻\r\n3���߱���ǿ��ѧϰ����������Ĺ�ͨ������\r\n4���Ը���ͣ�˼ά���ݣ��߱����õ�Ӧ�������ͳ�ѹ������\r\n5����������г�����������ǿ�ҵ���ҵ�ġ������ĺͻ����Ĺ���̬�ȣ�����ص绰���۹������������ȡ�'),
(555, 3, '���۹���ʦ', 0, '', '', NULL),
(553, 2, '�������۹���', 0, '', '', NULL),
(552, 2, '�Ź�����/����', 0, '', '', NULL),
(551, 2, '��ͻ�����', 0, '', '', NULL),
(550, 2, 'ҵ����չ(BD)����', 0, '', '', NULL),
(549, 2, '��������/����', 0, '', '', '��λְ��\r\n1�������������������̵����硢������ɸѡ����̭�͸��¹�����\r\n2����ҵ�ƹ�������չ���Ʒ�����\r\n3��ִ�������̵���ѵ����ǰЭ�����ۺ�ͻ�����ͼ���֧�֣�\r\n4����������������ųɱ������Ϳ��Ʒ�����\r\n5������쵼�������������\r\n6����Ӧ���ڳ��\r\n\r\n��λҪ��\r\n1�������������ۺ��г����飬�߱�����������������г�����������\r\n2����ǿ�ҵ���ҵ�ĺ����θУ��߱����õ��˼ʽ������������������̸��������\r\n3���Թ����м��顢ִ�š���ҵ, ˼ά��������Ծ��\r\n4���Ϻõ�̸�£�����ã����ʼѣ�\r\n5���������õ��Ŷ�Э���������õ�Э������ͨ�Ͱ���ȫ�ֵ�������\r\n6��˼ά���񣬼������¾��񣬻�����Ӧ����ǿ����ѹ������ǿ��'),
(548, 2, '�������۾���/����', 0, '', '', '��λְ��\r\n1�������Ͻ����Ĳ�Ʒ��������������Ʒ�������ڵ�ռ�ȣ�\r\n2��������Ͻ�������г��Ŀ��ء��ͻ��Ŀ���������Ĳ��ּ��¿ͻ�ǰ�ڽ���̸�й�����\r\n3��������Ͻ�����������ĳ����滮���ã����������ά����\r\n4��������Ͻ�����ڵĲ�Ʒ�ߵ��趨����Ʒ���ۼۡ���۵��ƶ�������۸���ϵ��ά����\r\n5��������Ͻ�����ڿͻ������������������ʱ�����ͻ�����ƻ�����������״����\r\n6���������������������ƶ�\r\n7������Ԥ�㡢ȷ�������ͻ��ĸ�����ã���ʱ���ˡ��߿\r\n8��������Ͻ�����ھ�Ʒ��̬���ڼ��մ�����ƻ������ƶ�����Ӧ���ԡ�\r\n\r\n��λҪ��\r\n1�����Ƽ�����ѧ�����г�Ӫ���򾭼á����������רҵ���ȣ�\r\n2������1�����ϼҵ�����Ʒ��ҵ�����۹����������ȣ��Լҵ���ҵ�����������г������н�ǿ������ȼѣ�\r\n3���Կ����ͣ��н�ǿ�Ĺ��������ĺ��Ŷ�Э������\r\n4��Office�칫�������������������PPT�㱨����������Excel��������\r\n5�����������߿��ʵ��ſ�Ҫ��'),
(547, 2, '�ͻ�����/����', 0, '', '', '��λְ��\r\n1�������г����к����������\r\n2������������۵�Ԥ�⣬Ŀ����ƶ����ֽ⣻\r\n3��ȷ�����۲���Ŀ����ϵ��������\r\n4���ƶ����ۼƻ�������Ԥ�㣻\r\n5���������������Ϳͻ��Ĺ���\r\n6���齨���۶��飬��ѵ������Ա��\r\n7����������ҵ�������������Ŷӡ�\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ�����г�Ӫ�������רҵ��\r\n2��2������������ҵ�������飬�����۹��������������ȣ�\r\n3�����зḻ�Ŀͻ���Դ�Ϳͻ���ϵ��ҵ�����㣻\r\n4���߱���ǿ���г�������Ӫ�����ƹ����������õ��˼ʹ�ͨ��Э�������������ͽ�������������\r\n5���н�ǿ����ҵ�ģ��߱�һ�����쵼������'),
(542, 2, '�����ܼ�', 0, '', '', '��λְ��\r\n1��Э���ܾ����ƶ���˾�ķ�չս�ԣ�����ս�ԣ��ƶ�����֯ʵʩ���������ۼƻ����쵼�Ŷӽ��ƻ�ת��Ϊ���۽����\r\n2������������ҵҵ����ͻ���ͬ��ҵ�䣨������ҵ���������õĺ�����ϵ��\r\n3���ƶ�ȫ�����۷���Ԥ�㣬�����Ϳ����г����۹����ķ���ͽ��ȣ�\r\n4���ֽ���������ָ�꣬�ƶ����Ρ��������۰취���ƶ�������������Ӫ���ߣ�\r\n5������������ҵ�ͻ����ݿ⣬�˽ⲻͬ��ģ�û�����״���������\r\n6����֯���ſ������������ֶΣ�������ۼƻ����ؿ�����\r\n7�������Ŷӽ��裬�������������䡢��չ���������۶���\r\n8�����ֹ�˾�ش�Ӫ����ͬ��̸����ǩ��������\r\n9�����пͻ��������ھ��û����󣬿����µĿͻ����µ��г�����\r\n��λҪ��\r\n1��28��40�꣬��ר����ѧ���������õ�ְҵ���أ�Ʒ�����㣬�ۺ����ʸߣ�\r\n2���������������г�Ӫ������������飻\r\n3����������ǿ���������ǿ��\r\n4�����н�ǿ���г����������ۼ��ܣ�\r\n5���߱�����Ĺ�ͨ�������ŶӺ��������齨����ѵ�ŶӾ���ḻ����������ҵ�����ã�\r\n6���߱���ǿ��ʱ����������͹�������������\r\n7���кܺõ�������ҵ�˼���Դ��'),
(543, 2, '���۾���', 0, '', '', NULL),
(554, 3, 'ҵ��Ա/���۴���', 0, '', '', '��λְ��\r\n1������˾��Ʒ�����ۼ��ƹ㣻\r\n2�������г�Ӫ���ƻ�����ɲ�������ָ�ꣻ\r\n3���������г�,��չ�¿ͻ�,���Ӳ�Ʒ���۷�Χ��\r\n4������Ͻ���г���Ϣ���ռ����������ֵķ�����\r\n5�������������������ۻ�Ĳ߻���ִ�У������������\r\n6������ά���ͻ���ϵ�Լ��ͻ���ĳ���ս�Ժ����ƻ���\r\n\r\n��λҪ��\r\n1������˾��Ʒ�����ۼ��ƹ㣻\r\n2�������г�Ӫ���ƻ�����ɲ�������ָ�ꣻ\r\n3���������г�,��չ�¿ͻ�,���Ӳ�Ʒ���۷�Χ��\r\n4������Ͻ���г���Ϣ���ռ����������ֵķ�����\r\n5�������������������ۻ�Ĳ߻���ִ�У������������\r\n6������ά���ͻ���ϵ�Լ��ͻ���ĳ���ս�Ժ����ƻ���'),
(545, 2, '��������', 0, '', '', '��λְ��\r\n1�������г����к����������\r\n2������������۵�Ԥ�⣬Ŀ����ƶ����ֽ⣻\r\n3��ȷ�����۲���Ŀ����ϵ��������\r\n4���ƶ����ۼƻ�������Ԥ�㣻\r\n5���������������Ϳͻ��Ĺ���\r\n6���齨���۶��飬��ѵ������Ա��\r\n7����������ҵ�������������Ŷӡ�\r\n\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ�����г�Ӫ�������רҵ��\r\n2��2������������ҵ�������飬�����۹��������������ȣ�\r\n3�����зḻ�Ŀͻ���Դ�Ϳͻ���ϵ��ҵ�����㣻\r\n4���߱���ǿ���г�������Ӫ�����ƹ����������õ��˼ʹ�ͨ��Э�������������ͽ�������������\r\n5���н�ǿ����ҵ�ģ��߱�һ�����쵼����'),
(774, 98, '���ز����۾���', 0, '', '', NULL),
(775, 98, '���ز��н�/����', 0, '', '', NULL),
(776, 98, '���ز�����ʦ', 0, '', '', NULL),
(777, 98, '���ز����׹���ʦ', 0, '', '', NULL),
(778, 98, '���ز���Ŀ��Ͷ��', 0, '', '', NULL),
(779, 98, '�������ز�', 0, '', '', NULL),
(780, 99, '��ҵ������/����', 0, '', '', '��λְ��\r\n1���ܱ�д��ҵ��Ŀ�Ĺ���ָ���飬�Լ���Ŀ�ķ���ƻ���������Ŀ��Ԥ�㣻\r\n2��ȫ�渺����ҵ��Ŀ����Ա�������������Ʒ����͸����ֳ�����\r\n3���ܼ�ʱ������Ŵ��﹫˾���������ľ��񼰰䲼���ƶȣ������ϸ�ִ�У�\r\n4����ʵ��Ա������ҵ��¥������ճ�ά�ޡ���ɨ���ࡢ�̻�����������������������������������������ֹΥ��װ�޺�Υ�´�Ⱦ�������������\r\n5�����ݹ�˾����ҵ��Ŀҵί���Ҫ�����֡���顢ǩ������ĸ���ú�ͬ��\r\n6���������ݡ�������ʩ��ά�ޡ������ƻ���ҵ��/�⻧װ�����룬��֯ά����Ա��ʱ������ɸ������񣬲���鶽��ҵ��/�⻧���涨����װ�ޣ�\r\n7����ҵ��/�⻧֮������ҵʹ���з������������Э����\r\n8��������ҵί�ᱨ����ҵά�ޡ����·��õ���֧��Ŀ��������ˣ�\r\n9�����ֵܽ��ͷ��ز������ŵ�ָ���ͼල���������������������\r\n\r\n��λҪ��\r\n1�����彡��������ѣ�����30��45�꣬��Ů�Կɣ�\r\n2����ר����ѧ������ҵ����רҵ���ѣ�\r\n3���������ϸߵ�סլ��ҵ��5��Ԫ/ƽ�����г��ۼۣ�������������ͨ��ҵ���������ľ������ݡ���׼������\r\n4����ȫ����ҵ�������ʸ�֤�����ҵ����ʦ֤�飻\r\n5���Ϻ��л��������Ϻ������������졢���ڻ����ҵ������\r\n6��Ӣ�����������ȣ�\r\n7���������ҵ��ҵ�����ȡ�'),
(781, 99, '��ҵ����Ա', 0, '', '', '��λְ��\r\n1������Ӵ��������������õǼǣ�\r\n2������Ϊҵ����������ס��װ������������ҵ����ѯ��Ͷ�߹�����\r\n3������˾��ࡢ�̻����ΰ���ά�޵ȷ�������\r\n4�������������в��ϸ�ķ�����Ŀ�����и��١���֤��\r\n5��ȫ������������ҵ������ʩ���豸��ʹ�ù��̡�\r\n\r\n��λҪ��\r\n1��30�����£���ר����ѧ������ҵ�������רҵ��\r\n2���������ϸߵ���ҵ������Ǽ��Ƶ���ҵ�ͷ��������飻\r\n3����ǿ�Ŀͻ�������ʶ�����õĹ�ͨ�����������ǿ��Э��������\r\n4��������ҵ�����ϸ�֤����Ʒ����ҵ��ҵ�����������ȿ��ǡ�'),
(782, 99, '��ҵ����/����/����', 0, '', '', NULL),
(783, 99, '��ҵ����', 0, '', '', NULL),
(784, 99, '��ҵ��ʩ����', 0, '', '', NULL),
(785, 99, '��ҵά��', 0, '', '', '��λְ��\r\n1���Թ�˾�涨����Ŀ��Ĺ������м���ά�ޣ�\r\n2����ֵ���ڼ䷢�������⼰ʱ�ϱ�����\r\n3��������ҵ�豸�ļ��ȣ�\r\n4��ִ�С��μӹ�˾��ʱί�ɵ�����\r\n\r\n��λҪ��\r\n1��2�������ۺ���ҵά�޹������飻\r\n2�������豸��ʩ��ά�������������޹������飻\r\n3����Ϥˮ��ů��ͨ��רҵ֪ʶ��\r\n4����ά�޵繤�ϸ�֤(��ѹ�繤�ϸ�֤)����ά��ǯ���ȼ�֤(ά��ǯ���ȼ�֤)�ȸ�λ����֤�飻\r\n5����һ����ͨ������ִ���������ŶӺ�������������ǿ���ܹ��Կ����͡�'),
(786, 99, '������ҵ����', 0, '', '', NULL),
(799, 117, '��Ʒ/���չ���ʦ', 0, '', '', ''),
(800, 117, '��ҵ���/��Ʒ���', 0, '', '', ''),
(801, 117, '��ҵ����ʦ', 0, '', '', ''),
(802, 117, '����ά��', 0, '', '', ''),
(803, 117, '����/����', 0, '', '', ''),
(804, 117, '���������������', 0, '', '', ''),
(805, 118, 'Ʒ�ʱ�֤/�������ƾ���', 0, '', '', ''),
(806, 118, '�������ƹ���ʦ', 0, '', '', ''),
(807, 118, '��������/����', 0, '', '', ''),
(808, 118, '��ϵ��֤����ʦ/���Ա', 0, '', '', ''),
(809, 118, '��Ӧ�̹���', 0, '', '', ''),
(810, 118, '�ɹ���������', 0, '', '', ''),
(811, 118, '��ȫ/����/��������ʦ', 0, '', '', ''),
(812, 118, '��ȫ����', 0, '', '', ''),
(813, 118, '��ȫ����', 0, '', '', ''),
(814, 118, '��������/��ȫ����', 0, '', '', ''),
(815, 119, '��������', 0, '', '', NULL),
(816, 119, '4S�꾭��/ά��վ����', 0, '', '', NULL),
(817, 119, '��������/������', 0, '', '', NULL),
(818, 119, '������������ʦ', 0, '', '', NULL),
(819, 119, '������ƹ���ʦ', 0, '', '', NULL),
(820, 119, '�������ӹ���ʦ', 0, '', '', NULL),
(821, 119, '����װ������', 0, '', '', NULL),
(822, 119, '����վ����Ա', 0, '', '', NULL),
(823, 119, '��������', 0, '', '', NULL),
(824, 120, '��Ʒ/���չ���ʦ', 0, '', '', ''),
(825, 120, '����/�豸����ʦ', 0, '', '', ''),
(826, 120, '����/��е��ͼԱ', 0, '', '', ''),
(827, 120, '��е���ʦ', 0, '', '', ''),
(828, 120, '��е����ʦ', 0, '', '', ''),
(829, 120, 'CNC/���ع���ʦ', 0, '', '', ''),
(830, 120, 'ģ�߹���ʦ', 0, '', '', ''),
(831, 120, '��еά�޹���ʦ', 0, '', '', ''),
(832, 120, '�豸ά��', 0, '', '', ''),
(833, 120, '���ܻ�е/�����Ǳ�', 0, '', '', ''),
(834, 120, '���Ϲ���ʦ', 0, '', '', ''),
(835, 120, 'ұ���е', 0, '', '', ''),
(836, 120, '������е', 0, '', '', ''),
(837, 120, '������е', 0, '', '', ''),
(838, 121, '����', 0, '', '', NULL),
(839, 121, '�繤', 0, '', '', NULL),
(840, 121, '�յ�/����/��¯��', 0, '', '', NULL),
(841, 121, '�纸/í����', 0, '', '', NULL),
(842, 121, 'ǯ��/���޹�/�ӽ�', 0, '', '', NULL),
(843, 121, '��/ĥ/ϳ/��/�۹�', 0, '', '', NULL),
(844, 121, '�и��', 0, '', '', NULL),
(845, 121, 'װж/�泵��', 0, '', '', NULL),
(846, 121, '����ά��', 0, '', '', NULL),
(847, 121, 'ˮ��/ľ��/���Ṥ', 0, '', '', NULL),
(848, 121, '��������', 0, '', '', NULL),
(849, 121, '��װ/����', 0, '', '', NULL),
(850, 121, '�ü���������', 0, '', '', NULL),
(851, 121, 'ģ�߹�', 0, '', '', NULL),
(852, 121, '�չ�', 0, '', '', NULL),
(853, 121, '������������', 0, '', '', NULL),
(854, 122, '��װ/��֯Ʒ���', 0, '', '', NULL),
(855, 122, '��ɴ/֯��/��֯', 0, '', '', NULL),
(856, 122, '��װ����/�ư�', 0, '', '', NULL),
(857, 122, 'Ьñ����', 0, '', '', NULL),
(858, 122, 'Ƥ��/ëƤ�ӹ�', 0, '', '', NULL),
(859, 122, '�ü���������', 0, '', '', NULL),
(860, 122, 'ӡȾ', 0, '', '', NULL),
(861, 122, '��װ/��֯/Ƥ�����', 0, '', '', NULL),
(862, 122, '������װ/��֯Ʒ', 0, '', '', NULL),
(881, 138, '�������ʦ', 0, '', '', NULL),
(882, 138, '��������ʦ', 0, '', '', NULL),
(883, 138, '�Զ����ƹ���ʦ', 0, '', '', NULL),
(884, 138, '���繤��ʦ', 0, '', '', NULL),
(885, 138, '�������', 0, '', '', NULL),
(886, 138, '����ά��', 0, '', '', NULL),
(887, 138, '�������', 0, '', '', NULL),
(888, 138, '��������', 0, '', '', NULL),
(889, 139, 'ˮ��/ˮ�繤��ʦ', 0, '', '', NULL),
(890, 139, '��������ʦ', 0, '', '', NULL),
(891, 139, '�����豸����', 0, '', '', NULL),
(892, 139, '��Դ����������', 0, '', '', NULL),
(893, 139, '���ϵͳ����ʦ', 0, '', '', NULL),
(894, 139, '�յ�/���ܹ���ʦ', 0, '', '', NULL),
(895, 139, 'ʯ����Ȼ������', 0, '', '', NULL),
(896, 139, '���ʿ���/����', 0, '', '', NULL),
(897, 139, '��������/��Դ', 0, '', '', NULL),
(898, 140, '��������', 0, '', '', ''),
(899, 140, 'ʵ�����о�Ա/����Ա', 0, '', '', ''),
(900, 140, 'ʯ�ͻ���', 0, '', '', ''),
(901, 140, '��/����', 0, '', '', ''),
(902, 140, 'ʳƷ/�����з�', 0, '', '', ''),
(903, 140, 'ˮ����', 0, '', '', ''),
(904, 140, '��������', 0, '', '', ''),
(928, 172, '�����༭/���', 0, '', '', NULL),
(929, 172, '��װ���', 0, '', '', NULL),
(930, 172, '�Ҿ�/�Ҿ���Ʒ���', 0, '', '', NULL),
(931, 172, '����Ʒ/�鱦���', 0, '', '', NULL),
(932, 172, '��ҵ���/��Ʒ���', 0, '', '', NULL),
(933, 172, '��̨���', 0, '', '', NULL),
(934, 172, '��װ/��֯Ʒ���', 0, '', '', NULL),
(935, 172, '�����������', 0, '', '', NULL),
(936, 173, '�ܱ�/���ܱ�/����', 0, '', '', NULL),
(937, 173, '�༭/����', 0, '', '', NULL),
(938, 173, '����', 0, '', '', NULL),
(939, 173, '�İ�/�߻�', 0, '', '', NULL),
(940, 173, '�Ű����', 0, '', '', NULL),
(941, 173, 'У��/¼��', 0, '', '', NULL),
(942, 173, '�����༭/���', 0, '', '', NULL),
(943, 173, '����/ӡˢ/����', 0, '', '', NULL),
(944, 173, '�ư�/װ��/�̽�', 0, '', '', NULL),
(945, 173, 'ӡˢ��', 0, '', '', NULL),
(946, 173, '��������/����', 0, '', '', NULL),
(962, 204, '��������', 0, '', '', NULL),
(963, 205, '��ʦ', 0, '', '', NULL),
(964, 205, '���ɹ���', 0, '', '', NULL),
(965, 205, '������Ա', 0, '', '', NULL),
(966, 205, '֪ʶ��Ȩ/ר������', 0, '', '', NULL),
(967, 205, '��������', 0, '', '', NULL),
(968, 206, 'רҵ����', 0, '', '', NULL),
(969, 206, '��ѯ����', 0, '', '', NULL),
(970, 206, '��ѯԱ', 0, '', '', NULL),
(971, 206, '�鱨��Ϣ����/����Ա', 0, '', '', NULL),
(972, 206, '������ѯ', 0, '', '', NULL),
(973, 207, 'Ӣ�﷭��', 0, '', '', NULL),
(974, 207, '���﷭��', 0, '', '', NULL),
(975, 207, '���﷭��', 0, '', '', NULL),
(976, 207, '���﷭��', 0, '', '', NULL),
(977, 207, '���﷭��', 0, '', '', NULL),
(978, 207, '���﷭��', 0, '', '', NULL),
(979, 207, '������﷭��', 0, '', '', NULL),
(980, 207, '�������﷭��', 0, '', '', NULL),
(981, 207, '�������﷭��', 0, '', '', NULL),
(982, 207, '�������﷭��', 0, '', '', NULL),
(983, 207, '��������', 0, '', '', NULL),
(993, 227, 'ҩƷ����/�ʹ�', 0, '', '', '��λְ��\r\n1������ҽҩ�м��塢ԭ��ҩ���ʼ칤������ػ�ѧ������\r\n2������ѧ�ϳ�ʵ�飻\r\n3����д�ʼ챨�沢���ϼ��㱨��\r\n��λҪ��\r\n1����ר����ѧ���������ѧ�����רҵ��\r\n2����1������ҩƷ���鹤�����飬��Ϥ���������̼���׼��\r\n3���ܹ�����ʹ�ð칫����������豸��'),
(994, 227, '���＼����ҩ', 0, '', '', '��λְ��\r\n1���������﹤��Ӧ�ü�����ǰ�ڵ��У�ȷ��������Ŀ�����ƿ������о����棬�����걨��˾�ڡ��ⲿ���\r\n2���������﹤�̷�����Ŀ���з������������������ںͳɱ�����\r\n3���������﹤�̼�������Ʒ�ļ�������ר���걨��������ƹ�������������ѵ��\r\n4������ָ�����﹤�̼����з��ɹ��ڹ����е�Ӧ�ü������ĳ������ƺ���ߣ�\r\n��λҪ��\r\n1�����Ƽ�����ѧ�����������������﹤���з����飻\r\n2����һ�����Ͼ��������ľ��飻\r\n3������ʹ������������׼ȷ�������ݷ�����\r\n4���н�ǿ�����ݷ����������߼�����������\r\n5��Ӣ���������������⼮ר�ҽ������ϰ���'),
(995, 227, 'ҽ���豸����', 0, '', '', '��λְ��\r\n1������ҽ����е��Ʒ������\r\n2������Ѱ�ҹ��ںϸ����������֮��ͨ��Ʒʵ�ַ�����ȷ�����ռ���Ʒ������׼�����۴��������ʣ�\r\n3���ʿغϸ���Э����֤��Ʒ��������Ԥ�ڱ�׼��\r\n4��������е��Ʒ�����ļ��������ļ���\r\n5�����Ʒ�����ҵ�������̵���Ŀ�ƻ����������С���ʵ����أ������豸��ģ�����������ʵ�֡�\r\n��λҪ��\r\n1����ѧ��������ѧ����5�����ϻ�е���Ӳ�Ʒ��ҵ���飻\r\n2����Ϥע�ܡ���Ϳ��PCB���գ���Ϥע�ܡ���Ϳ��PCB�����������ƣ�\r\n3������ʹ��AutoCAD�����UG��Solidwork��pro-E��Catia����֮һ��\r\n4��ʵ��ҽ����е���Ӳ�Ʒ�з�������ʵ�־��鲻����3�ꣻ\r\n5���߱�������ƿ����ͷ������������������н�ǿ�Ķ���������\r\n6�������õĹ�ͨ���Ŷ�Э������������Ӣ����п��ｻ����\r\n7����Ϥҽ����е��ҵ�����ȡ�'),
(996, 227, 'ҽҩ����', 0, '', '', '��λְ��\r\n1����Ͻ����ҽԺ���й�˾��Ʒ���ƹ����ۣ������������\r\n2��������Ҫ�ݷ�ҽ����Ա����ͻ��ƹ��Ʒ��������߲�Ʒ�г��ݶ\r\n3������Ǳ�ڵ�ҽԺ�����ͻ������Լ��еĿͻ�����ά����\r\n4������˽��г�״̬����ʱ���ϼ����ܷ�ӳ�������ֵ�������г���̬������������飻\r\n5���ƶ���ʵʩϽ��ҽԺ�������ƻ�����֯ҽԺ�ڸ����ƹ���\r\n��λҪ��\r\n1��ר�Ƽ�����ѧ����ҽҩ��Ӫ�������רҵ��\r\n2��2���������۹������飬��ҽ�����ġ��Ĳġ�ҩƷ���۾��������ȣ�\r\n3����ҽԺ���۾��飬��ϤҽԺ�������̣�ӵ�����õ�ҽԺ��Դ�������������Ȱ�ҩƷ���۷�������\r\n4�����н�ǿ�Ķ��������������罻���ɣ��ϺõĹ�ͨ������Э���������ŶӺ���������\r\n5�����彡�������ж��������ͽ�������������'),
(997, 227, 'ҽ����е����', 0, '', '', NULL),
(998, 227, '������ҩ/ҽ����е', 0, '', '', NULL),
(999, 228, '��������ʦ', 0, '', '', NULL),
(1000, 228, '�������۹���ʦ', 0, '', '', NULL),
(1001, 228, '�������', 0, '', '', NULL),
(1002, 228, 'ˮ����', 0, '', '', '��λְ��\r\n1���������ദ����Ŀ����������\r\n2������������ദ������Ŀ�Ĺ��ա�������������ơ�ʩ��ͼ��Ƶȣ�\r\n3���ܹ�����ʩ���ֳ��ļ���ָ�������е��ԡ�\r\n��λҪ��\r\n1����ѧ���Ƽ�����ѧ�����������̼����רҵ��\r\n2����Ϥ��ˮ����������̣��˽⻷�����ɷ��棻\r\n3��3��������ع������顣'),
(1003, 228, '�̷Ϲ���ʦ', 0, '', '', NULL),
(1004, 228, '����������ʦ', 0, '', '', NULL),
(1005, 228, '��������/԰�־�������', 0, '', '', NULL),
(1006, 228, '��������', 0, '', '', NULL),
(1015, 243, '����/����', 0, '', '', '��λְ��\r\n1��ȷʵ���հ�ȫ���ˣ������ڴ���ǰ�������ڡ����ż���ָ��֮�����ڣ�\r\n2�����б�������ָ֮ʾ�����ڰ�ȫ��������ȷ���Ʋ���˿Ͱ�ȫ��\r\n��λҪ��\r\n1��18��30���꣬���175CM���ϣ����彡������ò������\r\n2����Ϥ��ȫ�ƶȼ���ȫ����ʹ�á������¼��������¹�֮Ԥ���밲�ţ�\r\n3�����õ��׺���������������ȿ��ǡ�'),
(1016, 243, '�п�Ա', 0, '', '', NULL),
(1017, 243, '����/��๤', 0, '', '', '��λְ��\r\n1�����������������ͻ���������๤����\r\n2����֤���ʰ�������������������\r\n3�����ӷ���Ͱ��š�\r\n��λҪ��\r\n1����Ů���ޣ����彡��������40-55�ꣻ\r\n2������ع������������ȿ��ǡ�'),
(1018, 243, '��������/��ķ', 0, '', '', '��λְ��\r\n1������������ͻ��ļ�ͥ������๤����\r\n2�������ͥ�ò͵�ʳ�Ĳɹ��������ȹ�����\r\n3�������չ˺�С������ʳ��ӡ�ѧУ���͵����\r\n4�����ӷ���Ͱ��š�\r\n��λҪ��\r\n1��Ů�����彡��������20-45�ꣻ\r\n2������ع������������ȿ��ǡ�'),
(1019, 243, '��������/����', 0, '', '', NULL),
(1020, 244, '�Ƶ�/���ݾ���', 0, '', '', NULL),
(1021, 244, '���þ���', 0, '', '', '��λְ��\r\n1������Ա���������ò�ǰ׼����ȷ��������׼��\r\n2����ʽ���ͺ󣬶�������Ա�������÷����������Բμӷ�������\r\n3����ʱ���١����̨�棬�Բ��ϸ�ĵط�����ָ����������\r\n4����ʱ�Բ�̨�ϲ��ٶȡ�����˽⣬��ʱ�߲ˣ�\r\n5���ͺ���֯����Ա��ʱ��̨������ò�����������������\r\n��λҪ��\r\n1�����������Ļ��̶ȣ��������ʼѣ�\r\n2����Ϥ��������ͷ������֪ʶ�����������ķ����ܣ�\r\n3���нϸߵĴ������ͻ���¼���Ӧ���������Կ͹�ͨ������\r\n4���Ȱ�������������̤ʵ�����棬�н�ǿ����ҵ�ĺ����θС�'),
(1022, 244, '¥�澭��', 0, '', '', NULL),
(1023, 244, 'ǰ���Ӵ�', 0, '', '', NULL),
(1024, 244, '�ͷ�����Ա', 0, '', '', NULL),
(1025, 244, '����/���й���', 0, '', '', '��λְ��\r\n1�����������ڵ����۹�����������Ӧ�ͻ���ѯ��������ͻ���ͨ��ѵ����β�Ʒ�����������ͻ����۲�ֱ����ɽ��ף�\r\n2�����Լ����������ڿͻ����ֽ��ܵ���ϵ����ʱ�绰�ݷã�����Ѱ������\r\n3����ͻ���ɽ��׺󣬸���Գɽ���ҵ���������ز��Ž���Э����ͨ�븨�����\r\n��λҪ��\r\n1����ר����ѧ������������ҵ��ҵ�������ȣ�\r\n2���Ը����򡢷�Ӧ���ݡ��������ǿ�������õ��ŶӺ�����ʶ��\r\n3�����нϸߵ����ۼ�̸�м��ɣ������׺�����\r\n4���Ȱ�������ҵ���������ģ��ܳ��ܽϴ�Ĺ���ѹ�����Կ����ͣ�\r\n5���������õ�Э������֯���߻����������ڹ�ͨ��'),
(1026, 244, '�г̹���/�Ƶ�', 0, '', '', NULL),
(1027, 244, '��Ʊ/����', 0, '', '', NULL),
(1028, 244, '����/��������', 0, '', '', NULL),
(1029, 244, '����/��������Ա', 0, '', '', '��λְ��\r\n1��������ల�������������Ρ�����������������̨��׼���ø�����Ʒ��ȷ������Ӫҵʹ�á�\r\n2���Ӵ��˿�Ӧ���������顢��ò�����ġ��ܵ���ʹ�˿��б������֮�У�\r\n3��������ò���ԣ�Ϊ�����ṩ��ѷ���\r\n4��������˿ͽ��ܺ�������������Ʒ����ɫ�˵㣻\r\n5�������๤�������ӹ���\r\n��λҪ��\r\n1������18-30�꣬���彡����Ů�����1��58m���ϡ��������1��70���ϣ��ܳԿ࣬\r\n2��Ʒ�ж������ܳԿ����ͣ����������Ļ��̶ȡ�'),
(1030, 244, '����/ӭ��', 0, '', '', '��λְ��\r\n1���������ѱ��͵�ӭ���ͽӴ����������ܱ��͸���������Ԥ����������ʵ��\r\n2����ϸ����Ԥ����¼��\r\n3���˽���ռ����͵Ľ�����������ʱ�������ϼ��쵼��\r\n4���Թ淶�ķ�����ڣ�������˾Ʒ�����ʣ����ŵķ�������\r\n��λҪ��\r\n1��Ů�ԣ�����18��28���꣬���彡��������ȳơ���ٶ�ׯ�����1��65��1��72�ס�\r\n2���������õĹ�ͨЭ��������������ʶ����Ӧ��������ׯ�󷽡���ֹ���ţ�\r\n3����ҵ��ҵ�����н�ǿ�������ĺͳԿ����͵�ְҵ�������߱�һ����Ӣ��ˮƽ��\r\n4���߱��Ǽ��Ƶ�ǰ̨���������ߵ�����д'),
(1031, 244, '��ʦ', 0, '', '', '��λְ��\r\n1���������������������������\r\n2��ִ�в��������´�ĸ��������͹���ָʾ��\r\n3�������ƶ������ĸ��ֹ����ƻ���\r\n4���Գ����ĳ�Ʒ��������ʳƷ�ɱ��е���Ҫ�����Σ�\r\n5�����ֶԳ�����Χ��Ѳ�ӣ�������Ա�����ж�������ʱ����ֳ����������⣬����������߹���������\r\n��λҪ��\r\n1��������ʮ�����ϣ���������ѧ�������彡�����������棬���������Ǽ��Ƶ��ʦ���������飻\r\n2������ǿ�ҵ������ģ����ڿ��غʹ��£����������\r\n3��ӵ�нϸߵ���⿼������˽����ϤʳƷ���ϵĲ��ء����������һ�������\r\n4���Գɱ����ƹ���ʳƷӪ��ѧ���������豸֪ʶӵ��'),
(1032, 244, '����/����', 0, '', '', '��λְ��\r\n1��������������ʦ�������֪ͨ��ע������ȣ�\r\n2��Ϊ��ʦ��������ʳ�ġ���֤ʳ�ĵ����ʡ������ȣ�\r\n3��������̨��������������ʹ�á�\r\n��λҪ��\r\n1������18��45�꣬�Ա��ޣ����彡����\r\n2�����������ģ����õ�ִ�������͹�ͨ�������ܹ��ϸ��ձ�׼������\r\n3���ڷ�Ŭ�����Բ��������нϸߵĹ������顣'),
(1033, 244, '��������/����/����', 0, '', '', NULL),
(1034, 245, '����ʦ', 0, '', '', '��λְ��\r\n1��Ϊ�˿��ṩƤ�����������������\r\n2����װ��������Ҫ�󡢳������ܽ������ݻ��������\r\n3�����ֹ��������ĸɾ����ࣻ\r\n4��ѧϰ��Ʒ֪ʶ��רҵ�����������������ְҵ���ʺͼ��ܣ�\r\n5����ָ��������������\r\n��λҪ��\r\n1������ã����ʼѣ���18��30��֮�䣻\r\n2����һ��������������/���壬�����������飻\r\n3����ͨ�������ǿ�������ַ��ã��з�����ʶ��\r\n4�������׺������ŶӾ������Ͻ��ģ�\r\n5��������ʦ�ʸ�֤��������ҽ֤�����ȿ���'),
(1035, 245, '��ױʦ', 0, '', '', '��λְ��\r\n1�����ݲ�ͬ�����㷽��Ҫ����Ʋ�ͬ��ױ�����ͣ�\r\n2�����������Ļ�ױ�����͡���װ�����ε�����������ƣ�\r\n3��Э����Ӱʦ������㹤����\r\n��λҪ��\r\n1����������һ�����ϣ�����Ӱ�����һ���Ӱ¥�������飻\r\n2����Ʒ�ã���������Ϻã���ҵ��ҵ��\r\n3����������˹�ͨ��ױ������������Ȼ���Լ���һ���뷨��\r\n4����ͨ��������ǿ���ܺܺõ��ŶӺ�������'),
(1036, 245, '����ʦ', 0, '', '', '��λְ��\r\n1���ܰ�������������͹淶������ò�ĽӴ����ͣ����ṩһ�����������ѯ����\r\n2�����ݹ˿Ͳ�ͬ������������������Ϲ˿�Ҫ��ķ��ͣ�\r\n3���ܶ�������ϴ�������������硢�����̷���Ⱦ���ļ���������\r\n4����������ʹ��������Ʒ�������豸��\r\n5����ָ��������������\r\n��λҪ��\r\n1����������ʼѣ���������ʱ������������\r\n2���������Ϸ���ʦ���飬������׼ϸ�£��������͹�������������ʱ�ж���Ʒλ����Ӣ��ɳ�����Ϻ��ķ�ɳ������ѵ�����ߡ��нϴ���ʱ�л�������������������ȣ�\r\n3����ͨ�����ã��ܹ�Ϊ�˿��ṩ���ʷ���'),
(1037, 245, '����ʦ', 0, '', '', NULL),
(1038, 245, '�������/����', 0, '', '', '��λְ��\r\n1�������Ա������е��ָ����ѵ����\r\n2�������Ա�ٿε��ƶ������Σ�\r\n2���ܹ���֯���ƹ���չѵ����Ŀ��\r\n��λҪ��\r\n1����2��������ع������飻\r\n2�������������ܻ�ŷ���������ʸ�֤�ߣ�\r\n3���зḻ��˽�˽�����ٿδ��ξ��飻\r\n4�������˶������Ρ��Ը��ʡ�'),
(1039, 245, '�赸��ʦ', 0, '', '', '��λְ��\r\n1���нϸߵ�ʾ����ָ��������\r\n2���ܹ�������Ӧ����Ⱥ�ƶ���Ӧ���ڿη�����\r\n3���ܹ�������Ӧ�ĵ�������ѵ�������ƶ���Ӧ���ڿη�����\r\n4����ȷ�����٤���������ʽ��\r\n5������ȷ���ƶ��٤��ʳ���׷�����\r\n��λҪ��\r\n1����ר����ѧ��������ص�ָ���ʸ�֤�飻\r\n2������������ؾ��飬�����ù������θн�ǿ�߿����ȿ��ǣ�\r\n3�����彡���������õ����ʽ��\r\n4���Ȱ��٤��ҵ���������õ�ְҵ����\r\n5�����ܹ�����ϵͳ���٤רҵ��ѵ��\r\n6����ͨ����ǿ�����з�����ʶ��Ϊ����ֱ����ʵ����ҵ'),
(1040, 245, '��Ħ/����', 0, '', '', '��λְ��\r\n1��Ϊ�˿ͽ�����ɫ��Ŀ��������ã�\r\n2�����չ涨�ĳ����ʱ�������ɹ˿����ѵ���Ŀ��\r\n3����ɨ��ά���Լ�����������\r\n��λҪ��\r\n1������20-30�꣬��������ѧ����\r\n2��1��������ع������飻\r\n3���׺���ǿ���������÷�����ʶ��\r\n4���������õĹ�ͨ�������ŶӺ�������\r\n5���߱����֤�������ȿ��ǡ�'),
(1041, 245, '��������/����', 0, '', '', NULL),
(1042, 246, '�ֿ����', 0, '', '', '��λְ��\r\n1��ִ�����ʹ�������ֿ��йص�SOP��ȷ���ֿ���ҵ˳�����У�\r\n2������ֿ��ճ����ʵ����ա���⡢��š����ܡ��̵㡢���˵ȹ�����\r\n3������ֿ��ճ����ʵļ�ѡ�����ˡ�װ�������˹�����\r\n4�����𱣳ֲ��ڻ�Ʒ�ͻ�������ࡢ���������������\r\n5��������ص�֤�ı�����浵��\r\n6���ֿ����ݵ�ͳ�ơ��浵�������ϵͳ���ݵ����룻\r\n7���������ܽ�����������ˡ�\r\n��λҪ��\r\n1����ר������ѧ���������ִ������רҵ��\r\n2��1�������������ʵ��ҵ��������飬������������������������ȿ��ǣ�\r\n3����Ϥ�ֿ�������������̣��߱����ʱ���רҵ֪ʶ�ͼ��ܣ�\r\n4����Ϥ���԰칫�������,����SAP���������ȿ��ǣ�\r\n5���������͡�������ǿ�����к����ʹ��¾���'),
(1043, 246, '��������/����', 0, '', '', '��λְ��\r\n1���������ճ����������������������䡢�ִ������͡���������ȣ�\r\n2���ƶ���ִ�����������ƻ��������������淶�����ܽ�����ƣ�\r\n3���ලʵʩ������ϵְ��������׼��\r\n4�������ͻ��Ͳִ��ɱ���\r\n5�������ƶ�����Ʋ�����������Ԥ�㣻\r\n6���ƶ�������������������ͻ�����ȣ�\r\n7�����ڻ����ϱ���������������\r\n8���������ڲ�����Ա�Ŀ��ˡ���ѵ������\r\n��λҪ��\r\n1����ר������ѧ���������ࡢ���������רҵ��\r\n2��3�������������������������飬��������ҵ�������������������ȣ�\r\n3����Ϥ��������ҵ�����̣��зḻ�����̹���������ܣ�\r\n4����ϤERP��������Ϣ����ϵͳ����ʵʩ���飻\r\n5�����õĹ�ͨ��̸���������Ŷӹ���������������������ǿ���ܳ��ܽϴ���ѹ����'),
(1044, 246, '����רԱ/����', 0, '', '', NULL),
(1045, 246, '����/�������', 0, '', '', NULL),
(1046, 246, '���˴���', 0, '', '', NULL),
(1047, 246, '���Ϲ���', 0, '', '', NULL),
(1048, 246, '����˾��', 0, '', '', NULL),
(1049, 246, '����/����/�೵˾��', 0, '', '', NULL),
(1050, 246, '��½���˲���Ա', 0, '', '', NULL),
(1051, 246, '����Ա', 0, '', '', '��λְ��\r\n1�����������Ƴ��������ƶȣ�\r\n2����˾�����ĵ��ȣ���˾������·�̡������Ƚ��а��ţ�\r\n3���������͵Ŀ���������ʹ�ã�\r\n4����֯�������ճ�����ά�ޡ���������֤����������Ͱ�ȫ��\r\n5�����˾�����ճ��������˵ȹ�����\r\n6����֯˾�����н�ͨ���桢��ȫ֪ʶ��ѧϰ��\r\n��λҪ��\r\n1����������ѧ����\r\n2������������ظ�λ�������飻\r\n3������Ա�������Ĺ�����зḻ�ľ��飬��Ϥ����֤�յİ������̣���Ϥ��ͨ���ɡ����棻\r\n4���������ġ��Կ����ͣ���ҵ���ڡ�����̬�Ȼ���������Ĺ�ͨ��Э����ִ��������\r\n5�����ԣ�40�����¡�'),
(1052, 246, '�ٵ�Ա', 0, '', '', '��λְ��\r\n1�����������ڵ���Ʒ�ʹＰ����ļ�ʱ���أ�\r\n2��ִ��ҵ��������̣�׼ʱ�ʹ���Ʒ��ָ���ͻ���д������ϲ���ʱȡ�أ�\r\n3�������ʵ����ҵ�񵥾ݺ����ϣ�\r\n4���ͻ���ά�����ͻ���ѯ�Ĵ��������ķ�����\r\n5��ͻ���¼��Ĵ���\r\n��λҪ��\r\n1�����м�����ѧ����\r\n2����Ϥ���ص��Σ���ͬ��ҵ�������������ȣ�\r\n3���Կ����ͣ���Ʒ������������ϸ���棻\r\n4�����彡�����޲����Ⱥ�'),
(1053, 246, '���Ա', 0, '', '', '��λְ��\r\n1����Ϥ������Ʒ���ŵ���Ʒ���ơ����ء����ҡ������;�����ܡ��������ޣ�\r\n2�����س��вֿ�������Ʒ�������йع涨������ҵ���̽��и������\r\n3��������Ʒ��۵�֪ʶ����ȷ��ü۸�\r\n4������������Ʒ���е��й�רҵ֪ʶ�����������õ�ʵ�ʹ����У�\r\n5����û�����������\r\n��λҪ��\r\n1����������ѧ����\r\n2���д����̳����й������������ȣ�\r\n3��Ʒò�������Ȱ�������ҵ���Կ����ͣ�������ǿ�����彡�����к�ǿ�ľ�ҵ��������õ��������ʣ�\r\n4���߱��򵥵ļ�����������ɣ��˽���Ʒ����ʹ洢֪ʶ��\r\n5���ܹ�ʤ�η��ص���������������Ӧ��ҹ����ݰ��š�'),
(1054, 246, '��������/��ͨ/�ִ�', 0, '', '', NULL);";
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
<title>��ʿCMS�������� </title>
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
        <td height="30" bgcolor="#EFF9FE"  style=" border-bottom:1px #C6E6F4 solid;border-top:1px #C6E6F4  solid;"><strong>&nbsp;&nbsp;������ʾ</strong></td>
      </tr>
 <tr>
   <td  class="txt">
    �������������������74cms��ҵ�� v<?php echo  $version_old ?> ������ 74cms��ҵ�� v<?php echo $version_new ?> <br />
	 ��������޸Ĺ���վ�����ļ�,�ر������ݿ�ṹ���벻Ҫ���д˳��򣬷��򽫻ᵼ�³������<br />
���˴�����������Ҫ�ϳ�ʱ�䣬���������������Զ���ɵģ����������в�Ҫ�رմ��ڣ�<br>
<span>������֮ǰ��ر������ݿ�����,������ܲ����޷��ָ��ĺ��!<br /></span>
  ������������������ɺ���ɾ��(��dataĿ¼��)�����ļ����ļ��У�Ȼ���ϴ�<?php echo $version_new ?>���������ļ����ļ��С�<br> 
  ������������������ɺ��������ļ��л��ļ����ö�дȨ��,��ϸ�뿴�������ض���<br> 
 ��������Ϻ������ɾ�����ļ�!<br>
   �������ʹ�õĲ��ǹٷ�Ĭ��ģ�棬��������ܳ���ҳ��հף����ں�̨��ģ�����ó�Ϊ�ٷ�Ĭ��ģ�档<br>
  ������������������ϵ��ʿ�ͷ�����!<br>

  </td>
 </tr>
 <tr>
   <td align="center"   class="txt">
   <input type="button" value="��֪����"  onclick="window.location='?act=check'"/>   </td>
 </tr>
</table>
 

	  <?php }?>
	  <?php if ($step=="2") {?>
	    <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" id="update">

 <tr>
        <td height="30" bgcolor="#EFF9FE"  style=" border-bottom:1px  #C6E6F4  solid;border-top:1px #C6E6F4  solid;"><strong>&nbsp;&nbsp;Ŀ¼Ȩ�޼��</strong></td>
      </tr>
 <tr>
   <td   >
   
   
   
   
     <table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td width="200" height="30"   style="border-bottom:1px  #CADFF0 solid; padding-left:20px;"><strong>Ŀ¼</strong></td>
            <td align="center" style="border-bottom:1px #CADFF0 solid"><strong>��ȡȨ��</strong></td>
            <td align="center" style="border-bottom:1px #CADFF0 solid"><strong>д��Ȩ��</strong></td>
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
	�������ȷ������Ŀ¼Ȩ������,������ʼ�����������������벻Ҫ�رմ���<br /><br />

<input type="button" value="��һ��"   onclick="window.location='?step=3'"  id="BTNupdate"/>
		</div>
   
   
   <!--<input type="button" value="��ʼ����"  onclick="window.location='?act=update'" id="BTNupdate"/> -->
   
   
   </td>
 </tr>
</table>
 
 <?php }?> 
   <?php if ($step=="3") {?>
	    <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" id="update">

 <tr>
        <td height="30" bgcolor="#EFF9FE"  style=" border-bottom:1px  #C6E6F4  solid;border-top:1px #C6E6F4  solid;"><strong>&nbsp;&nbsp;��������ҵ�û���Ȩ��</strong></td>
      </tr>
 <tr>
   <td   >
   <br /><br />
     
     <form id="form1" name="form1" method="post" action="index.php?act=upit">
	  <div align="center"  style="color:#FF0000; margin-top:15px; margin-bottom:10px;">
	  <table width="500" border="0" align="center" cellpadding="0" cellspacing="5">
       <tr>
         <td width="180" align="right">������������Ȩ�룺</td>
         <td align="left"><label>
             <input name="key" type="text" id="key" size="30" />
         </label></td>
       </tr>
     </table>
	�������֪��������Ȩ�룬������ʿ�ͷ���ȡ��ÿ���汾������Ȩ��ֻ����ʹ��1�Σ����������뼰ʱ��ϵ��ʿ�ͷ�����
	<br />
	�����ʼ�������������̿�����Ҫ3-5���ӣ���������������ز�Ҫ�رմ���<br /><br />

<input name="�ύ" type="submit"    value="��ʼ����"  id="BTNupdate"/>
	  </div>
   
   
    
      </form>
      <!--<input type="button" value="��ʼ����"  onclick="window.location='?act=update'" id="BTNupdate"/> -->
   
   
   </td>
 </tr>
</table>
<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" style=" display:none;" id="updatewait">

 <tr>
        <td height="30" bgcolor="#EFF9FE"  style=" border-bottom:1px  #C6E6F4  solid;border-top:1px #C6E6F4  solid;"><strong>&nbsp;&nbsp;������</strong></td>
  </tr>
 <tr>
   <td align="center"  style="color:#009900"   >
   
   <br />
   <img src="images/30.gif" />   <br />
   <br />
   �����������������̿�����Ҫ3-5���ӣ�����ز�Ҫ�رմ���</td>
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
        <td height="30" bgcolor="#EFF9FE"  style=" border-bottom:1px  #C6E6F4  solid;border-top:1px #C6E6F4  solid;"><strong>&nbsp;&nbsp;�����ɹ�</strong></td>
      </tr>
 <tr>
   <td align="center"  style="color: #FF0000"   >
   
   <br />
   <br />
  ��ϲ�����ݿ������ɹ������ϴ���ʿ�˲�ϵͳ<?php echo $version_new?>�ļ�������<?php echo $version_old?>�������,���������¼��̨���»��棡</td>
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
            $checked_dirs[$k]['read'] = '<span style="color:red;">Ŀ¼������</span>';
			$checked_dirs[$k]['write'] = '<span style="color:red;">Ŀ¼������</span>';
        }
		else
		{		
        if (is_readable(QISHI_ROOT_PATH.'/'.$dir))
        {
            $checked_dirs[$k]['read'] = '<span style="color:green;">�̿ɶ�</span>';
        }else{
            $checked_dirs[$k]['read'] = '<span sylt="color:red;">�����ɶ�</span>';
        }
        if(is_writable(QISHI_ROOT_PATH.'/'.$dir)){
        	$checked_dirs[$k]['write'] = '<span style="color:green;">�̿�д</span>';
        }else{
        	$checked_dirs[$k]['write'] = '<span style="color:red;">������д</span>';
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