<?php
 /*
 * 74cms 网站首页
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
set_time_limit(0);
define('IN_QISHI', true);
require_once('../include/common.inc.php'); 
require_once(QISHI_ROOT_PATH.'include/fun_user.php');
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
$t1 = exectime();
require_once('conversion_cngfig.php');
$locoyspider=get_cache('locoyspider');
$id=intval($_GET['id']);
$del = intval($_GET['del']);
if($del == 1){
    del_jobs($id);
    del_jobs_app($id);
    exit;
}

$sql="select  top 1 *  from pH_Job_Base where JobId = ".$id; 
$rs = ms_getone($sql);   //执行搜索语句

require_once(QISHI_ROOT_PATH.'include/splitword.class.php');
$sp = new SPWord();

$thisid=$rs['JobId'];
//var_dump($thisid);exit;
if ($rs['JobName']){ 
    $userinfo_rs = ms_getone("select TOP 1 UserName,NCID from pH_Company_Base WHERE Comid='".escape_str($rs['Comid'])."'");
    if(empty($userinfo_rs)){
        echo $thisid." 公司数据有误!";
        exit;
    }else{
        $userinfo=get_user_inusername(escape_str($userinfo_rs['UserName']));
        if(!$userinfo){
            access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_user_company_save.php?id=".$userinfo_rs['NCID']);
            $userinfo=get_user_inusername(escape_str($userinfo_rs['UserName']));
        }
        $company_profile=get_company($userinfo['uid']);
        if(empty($company_profile['id'])){
            access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_company_save.php?id=".$userinfo_rs['NCID']);
            $company_profile=get_company($userinfo['uid']);
        }
    }
    $sql = "select id,addtime from ".table('jobs')." where id=".intval($rs['JobId']);
    $jobinfo=$db->getone($sql);
    $sql = "select id,addtime from ".table('jobs_tmp')." where id=".intval($rs['JobId']);
    $jobtmpinfo=$db->getone($sql);
    //当记录已存在时，重新导入_Calvin_20140624
    if(!empty($jobinfo['id']) || !empty($jobtmpinfo['id'])){
        del_jobs($thisid);
        del_jobs_app($thisid);
        sleep(1);
    }
    $setsqlarr['id'] = intval($rs['JobId']);
    $setsqlarr['add_mode']=1;
    $setsqlarr['uid']=$userinfo['uid'];
    $setsqlarr['jobs_name']=escape_str($rs['JobName']);
    $setsqlarr['companyname']=mysql_escape_string(trim($company_profile['companyname']));
    $setsqlarr['company_id']=$company_profile['id'];
    $setsqlarr['company_addtime']=$company_profile['addtime'];
    $setsqlarr['company_audit']=$company_profile['audit'];
    $setsqlarr['contents']=escape_str($rs['Require']);
    $nature=get_jobs_nature($rs['JobType']);
    $setsqlarr['nature']=$nature[0];
    $setsqlarr['nature_cn']=$nature[1];
    $gender=get_sex($rs['Sex']);
    $setsqlarr['sex']=$gender[0];
    $setsqlarr['sex_cn']=$gender[1];
    $setsqlarr['amount']=get_jobs_amount($rs['Number']);
    $setsqlarr['emergency'] = $rs['BestFlag']?1:0;
    $category=get_jobs_category_arr(escape_str($rs['JobClass']));
    $setsqlarr['category']=$category['category'];
    $setsqlarr['subclass']=$category['subclass'];
    $setsqlarr['category_cn']=$category['category_cn'];
    $setsqlarr['trade']=$company_profile['trade'];
    $setsqlarr['trade_cn']=$company_profile['trade_cn'];
    $setsqlarr['scale']=$company_profile['scale'];
    $setsqlarr['scale_cn']=$company_profile['scale_cn'];
    //修改地区属性转换＿Calvin_20140618
    $cname=escape_str($rs['Work_City']);
    $aname=escape_str($rs['Work_Area']);
    if(empty($cname) || $cname == "不限" || $cname == "其他" ||  $cname == "其它"){
        $cname = match_other_city($setsqlarr['id'],escape_str($rs['Address']), $aname);
        if(!$cname){
            exit;
        }
    }
    $caty=get_jobs_city_arr($cname,$aname);
    $setsqlarr['district']=$caty['district'];
    $setsqlarr['sdistrict']=$caty['sdistrict'];
    $setsqlarr['district_cn']=$caty['district_cn']."/".$caty['sdistrict_cn'];
    $setsqlarr['tag']="";
    $setsqlarr['street']=$company_profile['street'];
    $setsqlarr['street_cn']=$company_profile['street_cn'];
    //$setsqlarr['officebuilding']=$company_profile['officebuilding'];
    //$setsqlarr['officebuilding_cn']=$company_profile['officebuilding_cn'];	
    $education=get_jobs_education($rs['Edus']);
    $setsqlarr['education']=$education['id'];
    $setsqlarr['education_cn']=$education['cn'];
    $experience=get_jobs_experience($rs['Works']);
    $setsqlarr['experience']=$experience['id'];
    $setsqlarr['experience_cn']=$experience['cn'];
    $wage=$rs['Deal'];
    $wage=explode('.',$wage);
    $wage=$wage[0];
    $wage=get_jobs_wage($wage);
    $setsqlarr['wage']=$wage['id'];
    $setsqlarr['wage_cn']=$wage['name'];
    $setsqlarr['graduate']=$rs['JobType']==3?1:0;
    //修改添加时间为最后刷新时间_Calvin_20140618
    $end_date=$rs['End_Date'];
    $end_date=explode(' ',$end_date);
    $add_date=$rs['LastUpdate_Time'];
    $add_date=explode(' ',$add_date);
    $setsqlarr['deadline']=strtotime($rs['End_Date']);
    $setsqlarr['addtime']=strtotime($rs['LastUpdate_Time']);
    $setsqlarr['refreshtime']=strtotime($rs['LastUpdate_Time']);
    $setsqlarr['key']=$setsqlarr['jobs_name'].$setsqlarr['companyname'].$setsqlarr['category_cn'].$setsqlarr['district_cn'].$setsqlarr['contents'];
    $setsqlarr['key']="{$setsqlarr['jobs_name']} {$setsqlarr['companyname']} ".$sp->extracttag($setsqlarr['key']);
    $setsqlarr['key']=$sp->pad($setsqlarr['key']);
    $setsqlarr['subsite_id']=0;
    $setsqlarr['tpl']=$company_profile['tpl'];
    $setsqlarr['map_x']=$company_profile['map_x'];
    $setsqlarr['map_y']=$company_profile['map_y'];
    $setsqlarr['click']=$rs['ViewClicks'];
    $setsqlarr['audit']=($rs['JobFlag'] == 1)?1:2;
    $setsqlarr_contact['contact']=escape_str($rs['ContactPerson']);
    $setsqlarr_contact['qq']="";
    $setsqlarr_contact['telephone']=$rs['Phone'];
    $setsqlarr_contact['address']=escape_str($rs['Address']); 
    $setsqlarr_contact['email']=escape_str($rs['Email']);
    $setsqlarr_contact['notify']=0;
    //添加职位信息
    //var_dump($setsqlarr);exit;
    if($setsqlarr['deadline']<  time() || $setsqlarr['audit'] != 1){
        $pid=inserttable(table('jobs_tmp'),$setsqlarr,true);
    }else{
        $pid=inserttable(table('jobs'),$setsqlarr,true);
    }
    empty($pid)?showmsg("添加失败！",0):'';
    //补充转换简历投递量_Calvin_20140619(需写在此)---begin
    $sql="select  *  from pH_Company_InBox where JobId=".$rs['JobId']; 
    $sc_rs = ms_getall($sql);
    if(!empty($sc_rs)){
        foreach($sc_rs as $sc_rs){
            //查询简历信息
            $pr_sql="select NCID,UserName,RealName from pH_Person_Info where PerId='".$sc_rs['PerId']."'";
            $pr_rs = ms_getone($pr_sql);
            //检查记录是否存在
            $pj_sql="select did from ".table('personal_jobs_apply')." where resume_id='".$pr_rs['NCID']."' and apply_addtime=".strtotime($sc_rs['AddDate'])." LIMIT 1";
            $pj_info=$db->getone($pj_sql);
            //补充记录
            if(empty($pj_info)){
                $sc_arr['resume_id'] = $pr_rs['NCID'];
                $sc_arr['resume_name'] = escape_str($pr_rs['RealName']);
                $db_sql="select uid from ".table('members')." where username='".escape_str($pr_rs['UserName'])."' LIMIT 1";
                $info=$db->getone($db_sql);
                $sc_arr['personal_uid'] = $info['uid'];
                $sc_arr['jobs_id'] = $pid;
                $sc_arr['jobs_name'] = $setsqlarr['jobs_name'];
                $sc_arr['company_id'] = $setsqlarr['company_id'];
                $sc_arr['company_name'] = $setsqlarr['companyname'];
                $sc_arr['company_uid'] = $setsqlarr['uid'];
                $sc_arr['apply_addtime']=strtotime($sc_rs['AddDate']);
                if($sc_rs['Flag']){
                    $sc_arr['personal_look'] = 2;
                }else{
                    $sc_arr['personal_look'] = 1;
                }
                $sc_arr['notes'] = "无";
                inserttable(table('personal_jobs_apply'),$sc_arr);
            }
        }
    }
    //补充转换简历投递量_Calvin_20140619(需写在此)---end
    $setsqlarr_contact['pid']=$pid;
    !inserttable(table('jobs_contact'),$setsqlarr_contact)?showmsg("添加失败！",0):'';
    $searchtab['id']=$pid;
    $searchtab['uid']=$setsqlarr['uid'];
    $searchtab['subsite_id']=$setsqlarr['subsite_id'];
    $searchtab['recommend']=$setsqlarr['recommend'];
    $searchtab['emergency']=$setsqlarr['emergency'];
    $searchtab['nature']=$setsqlarr['nature'];
    $searchtab['sex']=$setsqlarr['sex'];
    $searchtab['category']=$setsqlarr['category'];
    $searchtab['subclass']=$setsqlarr['subclass'];
    $searchtab['trade']=$setsqlarr['trade'];
    $searchtab['district']=$setsqlarr['district'];
    $searchtab['sdistrict']=$setsqlarr['sdistrict'];	
    $searchtab['street']=$company_profile['street'];
    //$searchtab['officebuilding']=$company_profile['officebuilding'];	
    $searchtab['education']=$setsqlarr['education'];
    $searchtab['experience']=$setsqlarr['experience'];
    $searchtab['wage']=$setsqlarr['wage'];
    $searchtab['refreshtime']=$setsqlarr['refreshtime'];
    $searchtab['scale']=$setsqlarr['scale'];	
    inserttable(table('jobs_search_wage'),$searchtab);
    inserttable(table('jobs_search_scale'),$searchtab);
    $searchtab['map_x']=$setsqlarr['map_x'];
    $searchtab['map_y']=$setsqlarr['map_y'];
    inserttable(table('jobs_search_rtime'),$searchtab);
    unset($searchtab['map_x'],$searchtab['map_y']);
    $searchtab['stick']=$setsqlarr['stick'];
    inserttable(table('jobs_search_stickrtime'),$searchtab);
    unset($searchtab['stick']);
    $searchtab['click']=$setsqlarr['click'];
    inserttable(table('jobs_search_hot'),$searchtab);
    unset($searchtab['click']);
    $searchtab['key']=$setsqlarr['key'];
    $searchtab['map_x']=$setsqlarr['map_x'];
    $searchtab['map_y']=$setsqlarr['map_y'];
    $searchtab['likekey']=$setsqlarr['jobs_name'].",".$setsqlarr['companyname'];
    inserttable(table('jobs_search_key'),$searchtab);
    unset($searchtab);
    $tag=explode('|',$setsqlarr['tag']);
    $tagindex=1;
    $tagsql['tag1']=$tagsql['tag2']=$tagsql['tag3']=$tagsql['tag4']=$tagsql['tag5']=0;
    if (!empty($tag) && is_array($tag)){
        foreach($tag as $v){
            $vid=explode(',',$v);
            $tagsql['tag'.$tagindex]=intval($vid[0]);
            $tagindex++;
        }
    }
    $tagsql['id']=$pid;
    $tagsql['uid']=$setsqlarr['uid'];
    $tagsql['category']=$setsqlarr['category'];
    $tagsql['subclass']=$setsqlarr['subclass'];
    $tagsql['district']=$setsqlarr['district'];
    $tagsql['sdistrict']=$setsqlarr['sdistrict'];	
    inserttable(table('jobs_search_tag'),$tagsql);
}

function get_jobs_nature($str)
{
	global $db,$locoyspider;
	$arr=array();
	switch ($str)
	{
	case "1":
	  $str= "全职";$arr=array(62,'全职');break;
	case "2":
	  $str=  "兼职";$arr=array(63,'兼职');break;
	default:
	  $str=  "全职";$arr=array(62,'全职');break;
	}
	return $arr;
}
 

function get_jobs_amount($str)
{
	global $locoyspider;
	$str=intval($str);
	if ($str>0)
	{
	return $str;
	}
	else
	{
	return mt_rand($locoyspider['jobs_amount_min'],$locoyspider['jobs_amount_max']);
	}
}

function get_jobs_education($str=NULL)
{
    //查询是否存在“不限”学历要求，没有时补充_Calvin_20140623
    global $db;
    $sql = "select c_id,c_name from ".table('category')." where c_alias='QS_education'  and c_name='不限' LIMIT 1";
    //var_dump($sql);exit;
	$info=$db->getone($sql);
        if(empty($info['c_id'])){
            $edu_in_arr = array('c_alias' =>'QS_education','c_name' => '不限' );
            $info['c_id'] = inserttable(table('category'),$edu_in_arr,TRUE);
        }
	$arr=array();
	switch ($str){
            case "10":
              $str= "小学";$arr=array('id'=>65,'cn'=>'初中');break;//如果是小学，赋值为初中
            case "20":
              $str=  "初中";$arr=array('id'=>65,'cn'=>'初中');break;
            case "30":
              $str=  "高中";$arr=array('id'=>66,'cn'=>'高中');break;
            case "40":
              $str=  "中专";$arr=array('id'=>68,'cn'=>'中专');break;
            case "50":
              $str=  "大专";$arr=array('id'=>69,'cn'=>'大专');break;
             case "60":
              $str=  "本科";$arr=array('id'=>70,'cn'=>'本科');break;
              case "70":
              $str=  "硕士";$arr=array('id'=>71,'cn'=>'硕士');break;
              case "80":
              $str=  "博士";$arr=array('id'=>72,'cn'=>'博士');break;
              case "90":
              $str=  "博士后";$arr=array('id'=>73,'cn'=>'博后');break;
            default:
              $str=  "不限";$arr=array('id'=>$info['c_id'],'cn'=>'不限');break;
	}
	return $arr;
}

function get_jobs_experience($str=NULL)
{
	$arr=array();
								  
        switch ($str)
	{
	case "0":
	  $str= "不限";$arr=array('id'=>76,'cn'=>'1-3年');break;//如果市不限，直接赋值为1-3年
	case "1":
	  $str= "一年以上";$arr=array('id'=>76,'cn'=>'1-3年');break;
	case "2":
	  $str=  "二年以上";$arr=array('id'=>76,'cn'=>'1-3年');break;
	  case "3":
	  $str=  "三年以上";$arr=array('id'=>77,'cn'=>'3-5年');break;
	  case "4":
	  $str=  "四年以上";$arr=array('id'=>77,'cn'=>'3-5年');break;
	  case "5":
	  $str=  "五年以上";$arr=array('id'=>78,'cn'=>'5-10年');break;
	  case "10":
	  $str=  "十年以上";$arr=array('id'=>79,'cn'=>'10年以上');break;
	  case "20":
	  $str=  "二十年以上";$arr=array('id'=>79,'cn'=>'10年以上');break;
	  case "30":
	  $str=  "三十年以上";$arr=array('id'=>79,'cn'=>'10年以上');break;
	default:
	  $str=  "专职";$arr=array('id'=>76,'cn'=>'1-3年');break;
	  
	}	
	return $arr;
}

function get_jobs_wage($str=NULL)
{
	$str=intval($str);
	$arr=array();
	if($str==0){
		$arr=array('id'=>55,'name'=>'面议');
	}elseif($str>=1000 && $str<=1500){
		$arr=array('id'=>56,'name'=>'1000~1500元/月');
	}elseif($str>1500 && $str<2000){
		$arr=array('id'=>57,'name'=>'1500~2000元/月');
	}elseif($str>=2000&& $str<=3000){
		$arr=array('id'=>58,'name'=>'2000~3000元/月');
	}elseif($str>3000 && $str<5000){
		$arr=array('id'=>59,'name'=>'3000~5000元/月');
	}elseif($str>=5000 && $str<=10000){
		$arr=array('id'=>60,'name'=>'5000~10000元/月');
	}elseif($str>10000){
		$arr=array('id'=>61,'name'=>'一万以上/月');
	}
	return $arr;
}


function get_sex($str)
{
	switch ($str)
	{
	case "1":
	  $str= "男";break;
	case "0":
	  $str=  "女";break;
	 case "2":
	  $str=  "不限";break;
	default:
	  $str= "不限";
	}
	if ($str=="男")
	{
	return array(1,'男');
	}
	elseif ($str=="女")
	{
	return array(2,'女');
	}
	else
	{
	return array(3,'不限');
	}
}

function get_company($uid)
{
	global $db;
	$sql = "select * from ".table('company_profile')." where uid=".intval($uid)." LIMIT 1 ";
	return $db->getone($sql);
}

/*
 * 方法名：get_jobs_category
 * 作用：返回对应输入分类中的“其他”子分类
 * 输入：$str（顶级分类名称）
 * 输出：$jc（对应“其他”子分类） 
 */
function get_jobs_category($str){
	switch ($str){
            case "幼儿":
              $jc= "其它职位";
              break;
            case "中小学":
              $jc= "中小学其他教师";
              break;
            case "高校与科研院所":
              $jc= "其它职位";
              break;
            case "职教与培训":
              $jc= "其它职教与培训";
              break;
            case "销售市场客服":
              $jc= "其它";
              break;
            case "行政后勤财务":
              $jc= "其它";
              break;
            case "其他":
              $jc= "其它职位";
              break;
          default:
              $jc= "其它职位";
        }
        return $jc;
}




 //添加补充职位分类信息记录_Calvin_20140620
function get_jobs_category_arr($str){
	global $db,$locoyspider;
        if(strstr($str,"-")){
            $jc_arr = explode("-", $str);
        }else{
            $jc_arr[0] = $str;
            $jc_arr[1] = "其它职位";
        }
        if(substr($jc_arr[0],-2) == "类"){
            $jc_arr[0] = substr($jc_arr[0],0,strlen($jc_arr[0])-2);
        }
        if($jc_arr[0] == "不限" || $jc_arr[0] == "其他" || $jc_arr[0] == "其它"){
            $jc_arr[0] = "其它";
        }
        if(strstr($jc_arr[1],"&#183;")){
            $postion = strpos($jc_arr[1], "（");
            $jc_arr[1] = substr($jc_arr[1],0,$postion);
        }
        if(substr($jc_arr[1],-2) == "类"){
            $jc_arr[1] = substr($jc_arr[1],0,strlen($jc_arr[1])-2);
        }
	$sql = "select id,categoryname from ".table('category_jobs_test')." where parentid = 0";
	$big_info=$db->getall($sql);
        $big_return=locoyspider_search_str($big_info,$jc_arr[0],"categoryname",1);
        if(!$big_return){
            $big_return['id']= 7;
        }
	$sql = "select id,parentid,categoryname from ".table('category_jobs_test')." where parentid = ".$big_return['id'];
	$small_info=$db->getall($sql);
        $small_return=locoyspider_search_str($small_info,$jc_arr[1],"categoryname",50);
        if(!$small_return){
            $sql = "select id,parentid,categoryname from ".table('category_jobs_test')." where parentid = ".$big_return['id']." and categoryname = '其它职位'";
            $small_return=$db->getone($sql);
        }
        $jc_re = array("category"=>$big_return['id'],"subclass"=>$small_return['id'],"category_cn"=>$small_return['categoryname']);
        return $jc_re;
}


function match_other_city($id,$title,$aname){
    global $db;
    $aname = trim($aname);
    $tmp_str = substr($aname,-2);
    if($tmp_str == "市" || $tmp_str == "省"){
        $aname = substr($aname,0,strlen($aname)-2);
    }
    $sql = "select id,parentid,categoryname from ".table('category_district')." where parentid > 0";
    $info=$db->getall($sql);
    foreach ($info as $i) {
        if(strstr($title,$i['categoryname'])){
            $return = $i;
            break;
        }
    }
    if(!$return){
        if(empty($aname)){
            $error_in_arr['pid'] = $id;
            $error_in_arr['type'] = 'job';
            $error_in_arr['addtime'] = time();
            inserttable(table('91_city_error'),$error_in_arr);
            return FALSE;
        }
        $return['categoryname'] =  $aname;
    }
    //var_dump($return['categoryname']);
    return $return['categoryname'];
}

//添加补充职位地区信息记录_Calvin_20140618
function get_jobs_city_arr($cname,$aname){
    global $db;
    $cname = trim($cname);
    $aname = trim($aname);
    $tmp_str = substr($cname,-2);
    if($tmp_str == "市" || $tmp_str == "省"){
        $cname = substr($cname,0,strlen($cname)-2);
    }
    $tmp_str = substr($aname,-2);
    if($tmp_str == "市" || $tmp_str == "省"){
        $aname = substr($aname,0,strlen($aname)-2);
    }
    if(!empty($cname)){
        $sql = "select id,parentid,categoryname from ".table('category_district');
        $info=$db->getall($sql);
        $return=locoyspider_search_str($info,$cname,"categoryname",60);
        if (!$return){
            //如果地区表里没有相应数据，对地区表数据进行补充
            $sql = "select id,categoryname from ".table('category_district')." where parentid = 0";
            $info_a=$db->getall($sql);
            $return_a=locoyspider_search_str($info_a,$aname,"categoryname",60);
            if(!$return_a){
                $add_area = array("parentid" => 0,"categoryname" => $aname);
                $return_a['id'] = inserttable(table('category_district'),$add_area,true);
                access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$return_a['id']);
                if($cname == $aname){
                    $return['id'] =$return_a['id'];
                    $return['parentid'] =0;
                    $return['parentname'] = $aname;
                    $return['categoryname'] =$aname;
                }else{
                    $add_city = array("parentid" => $return_a['id'],"categoryname" => $cname);
                    $c_id=inserttable(table('category_district'),$add_city,true);
                    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$c_id);
                    $return['id'] = $c_id;
                    $return['parentid'] = $return_a['id'];
                    $return['parentname'] = $return_a['categoryname'];
                    $return['categoryname'] = $cname;
                }
            }else{
                $add_city = array("parentid" => $return_a['id'],"categoryname" => $cname);
                $c_id=inserttable(table('category_district'),$add_city,true);
                access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$c_id);
                $return['parentid'] = $return_a['id'];
                $return['parentname'] = $return_a['categoryname'];
                $return['id'] = $c_id;
                $return['categoryname'] = $cname;
            }
        }else{
            if($cname != $aname){
                $sql = "select categoryname from ".table('category_district')." where id = ".$return['parentid'];
                $area_info=$db->getone($sql);
                $return['parentname'] = $area_info['categoryname'];
            }else{
                $return['parentname'] = $cname;
            }
        }
    }
    return array("district"=>$return['parentid'],"district_cn"=>$return['parentname'],"sdistrict"=>$return['id'],"sdistrict_cn"=>$return['categoryname']);
}
 

function convert_datefm1 ($date,$format,$separator="-")
{
	 if ($format=="1")
	 {
	 return date("Y-m-d", $date);
	 }
	 else
	 {
		if (!preg_match("/^[0-9]{4}(\\".$separator.")[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/",$date))  return $date;
		$date=explode($separator,$date);
		return mktime(0,0,0,$date[1],$date[2],$date[0]);
	 }
}

function del_jobs($id){
    global $db;
    $db->query("Delete from ".table('jobs')." WHERE id='{$id}' LIMIT 1");
    $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id='" . $id . "' and type = 'jobs' LIMIT 1");
    $db->query("Delete from ".table('jobs_tmp')." WHERE id='{$id}' LIMIT 1");
    $db->query("Delete from ".table('jobs_search_hot')." WHERE id='{$id}'");
    $db->query("Delete from ".table('jobs_search_key')." WHERE id='{$id}'");
    $db->query("Delete from ".table('jobs_search_rtime')." WHERE id='{$id}'");
    $db->query("Delete from ".table('jobs_search_scale')." WHERE id='{$id}'");
    $db->query("Delete from ".table('jobs_search_stickrtime')." WHERE id='{$id}'");
    $db->query("Delete from ".table('jobs_search_wage')." WHERE id='{$id}'");
    $db->query("Delete from ".table('jobs_search_tag')." WHERE id='{$id}'");
}

function del_jobs_app($id){
    global $db;
    $db->query("Delete from ".table('personal_jobs_apply')." WHERE jobs_id='{$id}'");
}
?>