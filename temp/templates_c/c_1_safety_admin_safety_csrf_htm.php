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
echo $this->_fetch_compile_include("safety/admin_safety_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
 

 <div class="toptit">CSRF防御</div>
 <form action="?act=setsave" method="post"  name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

	  <table width="700" border="0" cellspacing="10" cellpadding="1" style=" margin-bottom:3px;">
        <tr>
          <td width="162" align="right">开启CSRF防御：</td>
          <td>
		  
		  <label>
        <input name="open_csrf" type="radio" id="open_csrf" value="1"  <?php if ($this->_vars['config']['open_csrf'] == "1"): ?>checked=checked <?php endif; ?>/>开启</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="open_csrf" value="0" id="open_csrf"  <?php if ($this->_vars['config']['open_csrf'] == "0"): ?>checked=checked<?php endif; ?>/>关闭</label>
		<span class="admin_note">开启后可有效防御CSRF攻击、跨域提交数据、重复提交表单等操作。</span>  
		  </td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td>
            <input name="submit2" type="submit" class="admin_submit"    value="保存修改"/>          </td>
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