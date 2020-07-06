<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-07-08 11:33 中国标准时间 */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<link rel="shortcut icon" href="<?php echo $this->_vars['QISHI']['site_dir']; ?>
favicon.ico" />
<title>Powered by 74CMS</title>
<link href="css/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
$("li").first().addClass("hover");
$("li>a").click(function(){
	$("li").removeClass("hover");
	$(this).parent().addClass("hover");
	$(this).blur();
	})
})
</script>
</head>
<body>
<div  class="admin_left_box">
<ul>
<li >
<a href="admin_personal.php?act=list"  target="mainFrame" >简历列表</a>
</li>
<li>
<a href="admin_personal.php?act=list&amp;audit=2&amp;display=&amp;tabletype=2"  target="mainFrame" >待审核简历</a>
</li>
<li>
<a href="admin_personal.php?act=list&amp;audit=&amp;display=&amp;talent=2"  target="mainFrame" >高级人才 </a>
</li>
<li>
<a href="admin_personal.php?act=list&amp;audit=&amp;display=&amp;talent=3"  target="mainFrame" >待开通高级人才 </a>
</li>
<li>
<a href="admin_personal.php?act=list&amp;photo=1" target="mainFrame"  >照片简历</a>
</li>

<li><a href="admin_personal.php?act=list&amp;photo_audit=2" target="mainFrame"  >待审核照片 </a></li>


<li>
<a href="admin_personal.php?act=members_list" target="mainFrame" >个人会员 </a>
</li>
</ul>
</div>
</body>
</html>