<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 16:43 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("safety/admin_safety_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
多个关键字请用 “|” 隔开。<br />
      关键字支持使用正则表达式。<br />

</p>
</div>

 <div class="toptit">禁止访问IP</div>
 <form action="?act=setsave" method="post"  name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

	  <table width="700" border="0" cellspacing="10" cellpadding="1" style=" margin-bottom:3px;">
        <tr>
          <td width="162" align="right" valign="top" style="padding:3px;">ip地址：<br />
            (请用“|”隔开多个ip地址)</td>
          <td valign="top"><textarea name="filter_ip" class="input_text_400" id="filter_ip" style="height:100px;"><?php echo $this->_vars['config']['filter_ip']; ?>
</textarea></td>
        </tr>
        <tr>
          <td align="right">出错提示</td>
          <td><input name="filter_ip_tips" type="text"  class="input_text_400" id="filter_ip_tips" value="<?php echo $this->_vars['config']['filter_ip_tips']; ?>
" maxlength="150"/></td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td>
            <input name="submit2" type="submit" class="admin_submit"    value="保存修改"/>
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