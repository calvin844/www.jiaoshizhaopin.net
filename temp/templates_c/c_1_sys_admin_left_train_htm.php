<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 10:02 ?D1��������?����?? */ ?>
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
<li><a href="admin_train.php"  target="mainFrame" >�γ��б�</a></li>
<li><a href="admin_train.php?sortype=2&audit=2"  target="mainFrame" >����˿γ�</a></li>
<li><a href="admin_train.php?act=train_list"  target="mainFrame" >��������</a></li>
<li><a href="admin_train.php?act=teacher_list"  target="mainFrame" >��ʦ����</a></li>
<li><a href="admin_train.php?act=members_list" target="mainFrame"  >��ѵ��Ա</a></li>
<li><a href="admin_train.php?act=order_list" target="mainFrame" >��������</a></li>
<li><a href="admin_train.php?act=train_img" target="mainFrame"  >����ͼƬ</a></li>
<li><a href="admin_train.php?act=train_news" target="mainFrame"  >��������</a></li>
</ul>
</div>
</body>
</html>