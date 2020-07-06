<?php
 /*
 * 74cms 申请课程
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(__FILE__).'/../include/common.inc.php');
$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'app';
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
if((empty($_SESSION['uid']) || empty($_SESSION['username']) || empty($_SESSION['utype'])) &&  $_COOKIE['QS']['username'] && $_COOKIE['QS']['password'] && $_COOKIE['QS']['uid'])
{
	require_once(QISHI_ROOT_PATH.'include/fun_user.php');
	if(check_cookie($_COOKIE['QS']['uid'],$_COOKIE['QS']['username'],$_COOKIE['QS']['password']))
	{
	update_user_info($_COOKIE['QS']['uid'],false,false);
	header("Location:".get_member_url($_SESSION['utype']));
	}
	else
	{
	unset($_SESSION['uid'],$_SESSION['username'],$_SESSION['utype'],$_SESSION['uqqid'],$_SESSION['activate_username'],$_SESSION['activate_email'],$_SESSION["openid"]);
	setcookie("QS[uid]","",time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	setcookie('QS[username]',"", time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	setcookie('QS[password]',"", time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	setcookie("QS[utype]","",time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	}
}
if ($_SESSION['uid']=='' || $_SESSION['username']=='')
{
	$captcha=get_cache('captcha');
	$smarty->assign('verify_userlogin',$captcha['verify_userlogin']);
	$smarty->display('plus/ajax_login.htm');
	exit();
}
if ($_SESSION['utype']!='2')
{
	exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
		    <tr>
				<td width="20" align="right"></td>
				<td class="ajax_app">
					必须是个人会员才可以申请课程！
				</td>
		    </tr>
		</table>');
}
require_once(QISHI_ROOT_PATH.'include/fun_personal.php');
$user=get_user_info($_SESSION['uid']);
if ($user['status']=="2") 
{
	exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
		    <tr>
				<td width="20" align="right"></td>
				<td class="ajax_app">
					您的账号处于暂停状态，请联系管理员设为正常后进行操作！
				</td>
		    </tr>
		</table>');
}
if ($act=="app")
{		
		$id=isset($_GET['id'])?$_GET['id']:exit("id 丢失");
		$course=app_get_course($id);
		if (empty($course))
		{
			exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
			    <tr>
					<td width="20" align="right"></td>
					<td class="ajax_app">
						申请课程失败！
					</td>
			    </tr>
			</table>');
		}
?>
<script type="text/javascript">
$(".but80").hover(function(){$(this).addClass("but80_hover")},function(){$(this).removeClass("but80_hover")});
//计算今天申请数量
var app_max="30";
var app_today="<?php echo get_now_applycour_num($_SESSION['uid']) ?>";
$(".ajax_app_tip > span:eq(0)").html(app_max);
$(".ajax_app_tip > span:eq(1)").html(app_today);
$(".ajax_app_tip > span:eq(2)").html(app_max-app_today);
//验证
$("#ajax_app").click(function() {
	if (app_max-app_today==0 || app_max-app_today<0 )
	{
	alert("您今天申请课程数量已经超出最大限制！");
	}
	else if ($("#realname").val()=="")
	{
	alert("请填写姓名！");
	}
	else if ($("#mobile").val()=="")
	{
	alert("请填写联系电话！");
	}
	else if ($("#email").val()=="")
	{
	alert("请填写联系邮箱！");
	}
	else
	{
		$("#app").hide();
		$("#notice").hide();
		$("#waiting").show();
		var tsTimeStamp= new Date().getTime();
			 var courseid=$("#courseid").val();
			 var uid="<?php echo $_SESSION['uid'];?>;"
		$.post("<?php echo $_CFG['website_dir'] ?>user/user_apply_course.php", { "uid": uid,"courseid": courseid,"notes": $("#notes").val(),"realname": $("#realname").val(),"mobile": $("#mobile").val(),"email":$("#email").val(),"time":tsTimeStamp,"act":"app_save"},
	 	function (data,textStatus)
	 	 {
			if (data=="ok")
			{
				$("#app").hide();
				$("#notice").hide();
				$("#waiting").hide();
				$("#app_ok").show();
					$("#app_ok .closed").click(function(){
					});
			}
			else if(data=="repeat")
			{
				$("#app").show();
				$("#notice").show();
				$("#waiting").hide();
				$("#app_ok").hide();
				alert("您申请过此课程，不能重复申请");
			}
			else
			{
				$("#app").show();
				$("#notice").show();
				$("#waiting").hide();
				$("#app_ok").hide();
				alert("申请失败！"+data);
			}
	 	 })
	}
});
function DialogClose()
{
	$("#FloatBg").hide();
	$("#FloatBox").hide();
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall" id="app">
    <tr>
		<td width="120" align="right">要申请的课程：</td>
		<td class="ajax_app">
			<ul>
			 <li><label><input name="courseid" id="courseid" type="hidden" value="<?php echo $course['id']?>" /><?php echo $course['course_name']?></label>
			 </li>
			 </ul>
		</td>
    </tr>
    <tr>
		<td width="120" align="right">真实姓名：</td>
		<td>
			<input name="realname" id="realname"  class="input_text_150" maxlength="30"/>
		</td>
    </tr>
     <tr>
		<td width="120" align="right">联系电话：</td>
		<td>
			<input name="mobile" id="mobile"  class="input_text_150" maxlength="15"/>
		</td>
    </tr>
     <tr>
		<td width="120" align="right">邮箱：</td>
		<td>
			<input name="email" id="email" class="input_text_150" maxlength="40"/>
		</td>
    </tr>
    <tr>
		<td align="right">其他说明：</td>
		<td>
			<textarea name="notes" id="notes"  style="width:300px; height:60px; line-height:180%; font-size:12px;"></textarea>
		</td>
    </tr>
    <tr>
		<td></td>
		<td>
			<input type="button" name="Submit"  id="ajax_app" class="but130lan" value="申请" />
		</td>
    </tr>
</table>
<table id="notice" width="100%" border="0" style="border-top:1px #CCCCCC dotted;background-color: #EEEEEE; line-height: 230%;padding: 15px; margin-top: 10px; ">
    <tbody><tr>
	    <td class="ajax_app_tip">您每天可以申请<span></span>门课程，今天已经申请了<span></span>门，还可以申请<span></span>门
	    </td>
    </tr>
</tbody>
</table>
<table width="100%" border="0" cellspacing="5" cellpadding="0" id="waiting"  style="display:none">
  <tr>
    <td align="center" height="60"><img src="<?php echo  $_CFG['site_template']?>images/30.gif"  border="0"/></td>
  </tr>
  <tr>
    <td align="center" >请稍后...</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall" id="app_ok" style="display:none">
    <tr>
		<td width="140" align="right"><img height="100" src="<?php echo  $_CFG['site_template']?>images/14.gif" /></td>
		<td>
			<strong style="font-size:14px ; color:#0066CC;margin-left:20px">申请成功!</strong>
			<div style="border-top:1px #CCCCCC solid; line-height:180%; margin-top:10px; padding-top:10px; height:50px;margin-left:20px"  class="dialog_closed">
			<a href="javascript:void(0)"  class="DialogClose">申请完成</a>
			</div>
		</td>
    </tr>
</table>
<?php
}
elseif ($act=="app_save")
{
	$courseid=isset($_POST['courseid'])?$_POST['courseid']:exit("出错了");
	$uid=isset($_POST['uid'])?intval($_POST['uid']):exit("出错了");
	$realname=isset($_POST['realname'])?trim($_POST['realname']):exit("出错了");
	$mobile=isset($_POST['mobile'])?trim($_POST['mobile']):exit("出错了");
	$email=isset($_POST['email'])?trim($_POST['email']):exit("出错了");
	if (!preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$email)) exit('邮箱格式不正确');
	$notes=isset($_POST['notes'])?trim($_POST['notes']):"";
	$course=app_get_course($courseid);
	if (empty($course))
	{
	exit("课程丢失");
	}
 		if (check_course_apply($course['id'],$_SESSION['uid']))
			{
			exit('repeat');
			}
			$addarr['realname']=$realname;
			$addarr['email']=$email;
			$addarr['mobile']=$mobile;
			$addarr['personal_uid']=intval($_SESSION['uid']);
			$addarr['course_id']=$course['id'];
			$addarr['course_name']=$course['course_name'];
			$addarr['train_id']=$course['train_id'];
			$addarr['train_name']=$course['trainname'];
			$addarr['train_uid']=$course['uid'];
			$addarr['notes']= $notes;
			if (strcasecmp(QISHI_DBCHARSET,"utf8")!=0)
			{
			$addarr['notes']=utf8_to_gbk($addarr['notes']);
			$addarr['realname']=utf8_to_gbk($addarr['realname']);
			$addarr['mobile']=utf8_to_gbk($addarr['mobile']);
			}
			$addarr['apply_addtime']=time();
			$addarr['personal_look']=1;
			if (inserttable(table('personal_course_apply'),$addarr))
			{
					$mailconfig=get_cache('mailconfig');					
					$course['contact']=$db->getone("select notify  from ".table('course_contact')." where pid='{$course['id']}' LIMIT 1 ");
					$sms=get_cache('sms_config');	
					$trainuser=get_user_info($course['uid']);	
					if ($mailconfig['set_applycou']=='1'  && $trainuser['email_audit']=='1' && $course['contact']['notify']=='1')
					{	
						dfopen("{$_CFG['website_dir']}plus/asyn_mail.php?uid={$_SESSION['uid']}&key=".asyn_userkey($_SESSION['uid'])."&act=set_applycou&course_id={$course['id']}&coursename={$course['course_name']}&personal_fullname={$realname}&email={$trainuser['email']}");
					}
					//sms	
					if ($sms['open']=="1"  && $sms['set_applycou']=="1"  && $trainuser['mobile_audit']=="1")
					{
						dfopen("{$_CFG['website_dir']}plus/asyn_sms.php?uid={$_SESSION['uid']}&key=".asyn_userkey($_SESSION['uid'])."&act=set_applycou&course_id={$course['id']}&coursename={$course['course_name']}&personal_fullname={$realname}&mobile={$trainuser['mobile']}");
					}
					write_memberslog($_SESSION['uid'],2,s,$_SESSION['username'],"申请课程，课程:{$course['course_name']}");
			}
	 exit("ok");
}
?>
