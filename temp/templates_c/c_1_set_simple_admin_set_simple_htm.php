<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 16:41 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script  charset="utf-8" src="kindeditor/kindeditor.js"></script>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
开启微招聘模块可能会有虚假、违法信息发布，请谨慎开启。<br />

</p>
</div>

<div class="toptit">开启微招聘</div>
 
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="95%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
		<tr>
      <td width="200" align="right">开启微招聘：</td>
      <td><label>
        <input name="simple_open" type="radio"   value="1"  <?php if ($this->_vars['config']['simple_open'] == "1"): ?>checked=checked <?php endif; ?>/>开启</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="simple_open" value="0"    <?php if ($this->_vars['config']['simple_open'] == "0"): ?>checked=checked<?php endif; ?>/>关闭</label></td>
	</tr>
		<tr>
	  <td align="right" valign="top">&nbsp;</td>
	  <td> 
	    <input name="submit" type="submit" class="admin_submit"    value="保存修改"/>	  </td>
	  </tr>
	  </table>
  </form>
	<div class="toptit">微招聘设置</div>
 
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="95%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">	 
	<tr>
      <td width="200" align="right">新发布微招聘默认审核状态：</td>
      <td>
	  <label>
        <input name="simple_add_audit" type="radio" id="simple_add_audit" value="1"  <?php if ($this->_vars['config']['simple_add_audit'] == "1"): ?>checked=checked <?php endif; ?>/>审核通过</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label>
        <input name="simple_add_audit" type="radio" id="simple_add_audit" value="2"  <?php if ($this->_vars['config']['simple_add_audit'] == "2"): ?>checked=checked <?php endif; ?>/>审核中</label>
		&nbsp;&nbsp;&nbsp;&nbsp;
<label>
        <input name="simple_add_audit" type="radio" id="simple_add_audit" value="3"  <?php if ($this->_vars['config']['simple_add_audit'] == "3"): ?>checked=checked <?php endif; ?>/>审核未通过</label></td>
    </tr>
		<tr>
      <td align="right">修改微招聘后审核状态变为：</td>
      <td>
	  <label>
        <input name="simple_edit_audit" type="radio" id="simple_edit_audit" value="-1"  <?php if ($this->_vars['config']['simple_edit_audit'] == "-1"): ?>checked=checked <?php endif; ?>/>保持不变</label>
&nbsp;&nbsp;&nbsp;&nbsp;
	  <label>
        <input name="simple_edit_audit" type="radio" id="simple_edit_audit" value="1"  <?php if ($this->_vars['config']['simple_edit_audit'] == "1"): ?>checked=checked <?php endif; ?>/>审核通过</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label>
        <input name="simple_edit_audit" type="radio" id="simple_edit_audit" value="2"  <?php if ($this->_vars['config']['simple_edit_audit'] == "2"): ?>checked=checked <?php endif; ?>/>审核中</label>
		&nbsp;&nbsp;&nbsp;&nbsp;
<label>
        <input name="simple_edit_audit" type="radio" id="simple_edit_audit" value="3"  <?php if ($this->_vars['config']['simple_edit_audit'] == "3"): ?>checked=checked <?php endif; ?>/>审核未通过</label></td>
    </tr>
	
 		
	<tr>
      <td align="right">联系电话重复：</td>
      <td><label>
        <input name="simple_tel_repeat" type="radio" id="simple_tel_repeat" value="1"  <?php if ($this->_vars['config']['simple_tel_repeat'] == "1"): ?>checked=checked <?php endif; ?>/>允许重复</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="simple_tel_repeat" value="0" id="simple_tel_repeat"  <?php if ($this->_vars['config']['simple_tel_repeat'] == "0"): ?>checked=checked<?php endif; ?>/>禁止重复</label>
<span class="admin_note">联系电话禁止重复可阻止垃圾信息</span>

</td>
	</tr>
	<tr>
      <td align="right">修改联系电话：</td>
      <td><label>
        <input name="simple_tel_edit" type="radio" id="simple_tel_edit" value="1"  <?php if ($this->_vars['config']['simple_tel_edit'] == "1"): ?>checked=checked <?php endif; ?>/>允许修改</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="simple_tel_edit" value="0" id="simple_tel_edit"  <?php if ($this->_vars['config']['simple_tel_edit'] == "0"): ?>checked=checked<?php endif; ?>/>禁止修改</label>

<span class="admin_note">禁止修改联系电话可过滤虚假信息</span>
</td>
	</tr>
		<tr>
	  <td align="right" valign="top">&nbsp;</td>
	  <td> 
	    <input name="submit" type="submit" class="admin_submit"    value="保存修改"/>	  </td>
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