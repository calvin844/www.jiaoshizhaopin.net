<?php
 /*
 * 74cms 举报
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
if ($_SESSION['utype']!='1')
{
	exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
		    <tr>
				<td width="20" align="right"></td>
				<td>
					必须是企业会员才可以举报简历信息！
				</td>
		    </tr>
		</table>');
}
require_once(QISHI_ROOT_PATH.'include/fun_company.php');
$user=get_user_info($_SESSION['uid']);
if ($user['status']=="2") 
{
	exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
		    <tr>
				<td width="20" align="right"></td>
				<td>
					您的账号处于暂停状态，请联系管理员设为正常后进行操作！
				</td>
		    </tr>
		</table>');
}
if ($act=="report")
{		
		$id=isset($_GET['resume_id'])?$_GET['resume_id']:exit("id 丢失");
		$resume=get_resume_basic($id);
		if (empty($resume))
		{
			exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
			    <tr>
					<td width="20" align="right"></td>
					<td>
						举报信息失败！
					</td>
			    </tr>
			</table>');
		}
		if (check_resume_report($_SESSION['uid'],intval($_GET['resume_id'])))
		{
			exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
			    <tr>
					<td width="20" align="right"></td>
					<td>
						您已经举报过此简历！
					</td>
			    </tr>
			</table>');
		}
?>
<script type="text/javascript">
$(".but80").hover(function(){$(this).addClass("but80_hover")},function(){$(this).removeClass("but80_hover")});
//计算今天申请数量

//验证
$("#ajax_report").click(function() {
	var content=$("#content").val();
	if (content=="")
	{
	alert("请输入描述");
	}
	else
	{
		$("#report").hide();
		$("#waiting").show();
		
		$.post("<?php echo $_CFG['site_dir'] ?>user/user_report_resume.php", { "resume_id": $("#resume_id").val(),"full_name": $("#full_name").val(),"content": $("#content").val(),"resume_addtime":$("#resume_addtime").val(),"act":"app_save"},

	 	function (data,textStatus)
	 	 {
			if (data=="ok")
			{
				$("#report").hide();
				$("#waiting").hide();
				$("#app_ok").show();
					$("#app_ok .closed").click(function(){
					});
			}
			else
			{
				$("#report").show();
				$("#waiting").hide();
				$("#app_ok").hide();
				alert("举报失败！"+data);
			}
	 	 });
	}
});
function DialogClose()
{
	$("#FloatBg").hide();
	$("#FloatBox").hide();
}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall" id="report">
	<input type="hidden" id="resume_id" value="<?php echo intval($_GET['resume_id']);?>">
	<input type="hidden" id="full_name" value="<?php echo trim($_GET['full_name']);?>">
	<input type="hidden" id="resume_addtime" value="<?php echo trim($_GET['resume_addtime']);?>">
    <tr>
		<td width="120" align="right">投诉的简历：</td>
		<td>
			<?php echo trim($_GET['full_name']);?>
		</td>
    </tr>
    <tr>
		<td align="right">相关描述：</td>
		<td>
			<textarea name="content" id="content"  style="width:300px; height:60px; line-height:180%; font-size:12px;"></textarea>
		</td>
    </tr>
    <tr>
		<td></td>
		<td>
			<input type="button" name="Submit"  id="ajax_report" class="but130lan" value="提交" />
		</td>
    </tr>
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
			<strong style="font-size:14px ; color:#0066CC;margin-left:20px">举报成功，管理员会认真处理!</strong>
		</td>
    </tr>
</table>
<?php
}

elseif ($act=="app_save")
{
	$setsqlarr['content']=trim($_POST['content'])?trim($_POST['content']):exit("出错了");
	$setsqlarr['resume_id']=$_POST['resume_id']?intval($_POST['resume_id']):exit("出错了");
	$setsqlarr['title']=trim($_POST['full_name'])?trim($_POST['full_name']):exit("出错了");
	$setsqlarr['resume_addtime']=intval($_POST['resume_addtime']);
	$setsqlarr['uid']=intval($_SESSION['uid']);
	$setsqlarr['addtime']=time();
	if (strcasecmp(QISHI_DBCHARSET,"utf8")!=0)
	{
	$setsqlarr['content']=utf8_to_gbk($setsqlarr['content']);
	$setsqlarr['title']=utf8_to_gbk($setsqlarr['title']);
	}
	$resume=get_resume_basic($setsqlarr['resume_id']);
	if (empty($resume))
	{
	exit("简历丢失");
	}
	else
	{
		$insert_id = inserttable(table('report_resume'),$setsqlarr,1);
	}
	if($insert_id)
	 {
	 exit("ok");
	 }
}

?>
