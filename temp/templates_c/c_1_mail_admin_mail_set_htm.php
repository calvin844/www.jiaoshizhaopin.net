<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:38 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript"> 
$(document).ready(function()
{
	setsendmailshow();
	$("#methodsel :radio").click(function () {setsendmailshow();});
	function setsendmailshow()
	{
		var stlval=$("#methodsel :radio[checked]").val();
		if (stlval=="1")
		{
		$("#method_sendmail,#add_form").show();
		$("#add_form").show();
		}
		else
		{
		$("#method_sendmail,#add_form").hide();
		}
	}
	$("#add_form").click(function()
	{
	$("#html").append($(".html_tpl").html());
	});
	
	$(".delsmtp").hover(function(){
	$(this).parentsUntil('div').css('background-color','#F3F3F3');
	},function(){
	$(this).parentsUntil('div').css('background-color','');
	});
	
	$('.delsmtp').live('click', function()
	{
		if ($("#method_sendmail div:nth-child(2)").text()=='')
		{
		alert('至少留一个SMTP账户！');
		}
		else
		{
		$(this).parentsUntil('div').empty();
		}
	});
});
</script>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("mail/admin_mail_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>您可以通过发送测试邮件来调试配置信息。<br />
通过连接 SMTP 服务器发送邮件需邮箱账户开通SMTP服务。
<br />
您可以添加多个SMTP账户，系统将随机使用SMTP账户。
</p>
  </div>
<div class="toptit">设置</div>
<form action="?act=email_set_save" method="post"   name="form1" id="form1">
<?php echo $this->_vars['inputtoken']; ?>

<table width="100%" border="0" cellspacing="8" cellpadding="0" style="padding-left:15px;" id="methodsel">
  <tr>
    <td>
	<label>
	<input name="method" type="radio" value="1"  <?php if ($this->_vars['mailconfig']['method'] == "1"): ?>checked="checked"<?php endif; ?>/>
      通过连接 SMTP 服务器发送邮件
	  </label>
	  </td>
    </tr>
  <tr>
    <td>
	<label>
	<input type="radio" name="method" value="2" <?php if ($this->_vars['mailconfig']['method'] == "2"): ?>checked="checked"<?php endif; ?>/>
      通过sendmail 发送邮件
	  </label>
	</td>
    </tr>
  <tr>
    <td>
	<label>
	<input type="radio" name="method" value="3" <?php if ($this->_vars['mailconfig']['method'] == "3"): ?>checked="checked"<?php endif; ?>/>
     通过 PHP 函数 SMTP 发送邮件

	  </label>
	</td>
    </tr>
</table>
<div style=" display:none1"  id="method_sendmail">

<?php if (count((array)$this->_vars['mailconfigli'])): foreach ((array)$this->_vars['mailconfigli'] as $this->_vars['li']): ?>
<div class="html_tpl">
		<table width="700" border="0" cellspacing="8" cellpadding="1" style=" margin-bottom:3px; border-bottom:1px #CCCCCC solid" >
          <tr>
            <td width="121" align="right">SMTP服务器地址：</td>
            <td><input name="smtpservers[]" type="text"  class="input_text_200" id="smtpservers" value="<?php echo $this->_vars['li']['smtpservers']; ?>
" maxlength="30"/>
              如：smtp.qq.com</td>
            <td><span style="color:#0066CC; cursor:pointer" class="delsmtp">X 删除此账户</span></td>
          </tr>
          <tr>
            <td align="right">SMTP服务帐户名：</td>
            <td><input name="smtpusername[]" type="text"  class="input_text_200" id="smtpusername" value="<?php echo $this->_vars['li']['smtpusername']; ?>
" maxlength="100"/></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">SMTP服务密码：</td>
            <td><input name="smtppassword[]" type="password"  class="input_text_200" id="smtppassword" value="<?php echo $this->_vars['li']['smtppassword']; ?>
" maxlength="40"/></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">发信人邮件地址：</td>
            <td><input name="smtpfrom[]" type="text"  class="input_text_200" id="site_title" value="<?php echo $this->_vars['li']['smtpfrom']; ?>
" maxlength="60"/>            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">SMTP 端口：</td>
            <td><input name="smtpport[]" type="text"  class="input_text_200" id="smtpport" value="<?php echo $this->_vars['li']['smtpport']; ?>
" maxlength="60"/>
默认：25</td>
            <td>&nbsp;</td>
          </tr>
      </table>
	  </div>
	  
 <?php endforeach; endif; ?>	  
	 <div id="html"></div>
    </div>
	  <table width="700" border="0" cellspacing="8" cellpadding="1"  >
        
          <tr>
            <td width="120" align="right">&nbsp;</td>
            <td> 
              <input name="save" type="submit" class="admin_submit"    value="保存修改"/>
			  <input type="button" name="add_form" id="add_form" value="继续添加" class="admin_submit" />
            </td>
          </tr>
      </table>
  </form>
 
  
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>