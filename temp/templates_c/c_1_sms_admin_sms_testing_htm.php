<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 16:43 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript"> 
$(document).ready(function()
{
	$("#check").click(function () {	
	$("#submitbox").hide();
	$("#hide").show();	
	});
});
</script>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sms/admin_sms_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
 
		  <div class="toptit">发送测试短信</div>
    <span id="submitbox">
		<form action="?act=sms_testing" method="post"   name="form1" id="form1">
		<?php echo $this->_vars['inputtoken']; ?>

		<table width="700" border="0" cellspacing="10" cellpadding="1" style=" margin-bottom:3px;">
          <tr>
            <td width="100" align="right">接收手机:</td>
            <td width="560">
			<input name="mobile" type="text"  class="input_text_200"  value="" maxlength="11"/></td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td><input name="check" type="submit" class="admin_submit" id="check"    value="立即测试" /></td>
          </tr>
        </table>
	    </form>
  </span>
<span id="hide" style="display: none; color: #009900; padding-left:30px; padding-bottom:80px;">
 正在发送.......<br />
<br />
</span>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>