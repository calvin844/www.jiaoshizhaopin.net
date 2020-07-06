<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:38 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
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
 <div class="toptip link_g">
	<h2>提示：</h2>
		<p>
		短信模块属收费模块，需申请开通后才能使用，请点击 <a href="http://www.74cms.com/sms.php" target="_blank">这里</a> 申请开通。
		<br />
		资费标准请联系骑士销售获取，更多介绍请进入 <a href="http://www.74cms.com" target="_blank">骑士人才系统官方网站</a></p>
  </div>
<div class="toptit">设置</div>
<form action="?act=set_save" method="post"   name="form1" id="form1">
<?php echo $this->_vars['inputtoken']; ?>

		<table width="700" border="0" cellspacing="8" cellpadding="1" style=" margin-bottom:3px; " id="method_sendmail">
          <tr>
            <td width="121" align="right">开启短信发送：</td>
            <td width="560">
			<label><input name="open" type="radio" value="1"    <?php if ($this->_vars['sms']['open'] == "1"): ?>checked="checked"<?php endif; ?>/> 开启</label>
			&nbsp;&nbsp;&nbsp;
			<label><input name="open" type="radio" value="0"    <?php if ($this->_vars['sms']['open'] == "0"): ?>checked="checked"<?php endif; ?>/>关闭</label>            </td>
          </tr>
          <tr>
            <td align="right">短信通帐户名：</td>
            <td><input name="sms_name" type="text"  class="input_text_200"  value="<?php echo $this->_vars['sms']['sms_name']; ?>
"  /></td>
          </tr>
          <tr>
            <td align="right">短信通密钥：</td>
            <td><input name="sms_key" type="text"  class="input_text_200"  value="<?php echo $this->_vars['sms']['sms_key']; ?>
"  /></td>
          </tr>
      </table>
	  <table width="700" border="0" cellspacing="8" cellpadding="1"  >
        
          <tr>
            <td width="120" align="right">&nbsp;</td>
            <td> 
              <input name="save" type="submit" class="admin_submit"    value="保存"/>
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