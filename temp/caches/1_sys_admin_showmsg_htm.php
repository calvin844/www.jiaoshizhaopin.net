a:2:{s:6:"config";a:0:{}s:8:"template";a:3:{i:0;s:21:"sys/admin_showmsg.htm";i:1;s:20:"sys/admin_header.htm";i:2;s:20:"sys/admin_footer.htm";}}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<link rel="shortcut icon" href="/favicon.ico" />
<meta name="author" content="骑士CMS" />
<meta name="copyright" content="74cms.com" />
<title> Powered by 74CMS</title>
<link href="css/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.QSdialog.js"></script>
<script type="text/javascript" src="js/jquery.vtip-min.js"></script>
<script type="text/javascript" src="js/jquery.grid.rowSizing.pack.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body style="background-color:#E0F0FE"><div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit">骑士人才系统管理中心</div>
  <div class="clear"></div>
</div>
<div class="toptit">系统提示</div>
<div class="showmsg">
	  <div class="left m2">
	  </div>
	   <div class="right">
	   <h2>更新成功！</h2>
	   <div id="redirectionMsg">如果您不做出选择，将在 <span id="spanSeconds">3</span> 秒后跳转到第一个链接地址。</div>
	   <ul style="margin:0;list-style:none" >
				<li><a href="javascript:history.go(-1)" >返回上一页</a></li>
				</ul>
	   </div>
	   <div class="clear"></div>
</div>
</div>
<script language="JavaScript">
<!--
var seconds = 3;
var defaultUrl = "javascript:history.go(-1)";


onload = function()
{
  if (defaultUrl == 'javascript:history.go(-1)' && window.history.length == 0)
  {
    document.getElementById('redirectionMsg').innerHTML = '';
    return;
  }

  window.setInterval(redirection, 1000);
}
function redirection()
{
  if (seconds <= 0)
  {
    window.clearInterval();
    return;
  }

  seconds --;
  document.getElementById('spanSeconds').innerHTML = seconds;

  if (seconds == 0)
  {
    window.clearInterval();
    location.href = defaultUrl;
  }
}
//-->
</script>

<div class="footer link_lan">
Powered by <a href="http://www.74cms.com" target="_blank"><span style="color:#009900">74</span><span style="color: #FF3300">CMS</span></a> 3.3.20130801
</div>
<div class="admin_frameset" >
  <div class="open_frame" title="全屏" id="open_frame"></div>
  <div class="close_frame" title="还原窗口" id="close_frame"></div>
</div>
</body>
</html></body>
</html>