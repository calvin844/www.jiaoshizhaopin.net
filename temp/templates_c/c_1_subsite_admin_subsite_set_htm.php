<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:39 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("subsite/admin_subsite_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
<h2>提示：</h2>
<p>
<span style="color:#FF6600">请谨慎开启分站，如分站数据和模版存在大量重复，可能会导致搜素引擎降权甚至K站。</span><br />
新增分站后，需要将分站域名解析至主站，同时主网站需绑定此的分站域名；
</p>
</div> 
<script type="text/javascript">
$(document).ready(function()
{
//
	//$("form[name=form1]").submit(function(){
		//	if ($("#oldsubsite").val()!=$("#subsitetype  :radio[checked]").val())
		//	{	
			//	if (confirm('开启或关闭分站会使数据索引重建，此过程执行时间取决于数据量的大小。\n\n在执行中请不要离开此页面。\n\n您确定要修改吗？'))
			//	{
			//	$("#hide").show();
			//	$("#set").hide();
			//	}
			//	else
			//	{
			//	return false;
			//	}
		//	}
//	});
});
</script>
<div class="toptit">开启分站</div>
<form action="?act=setsave" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="5" id="set">
	<tr>
      <td width="80" align="right">开启分站：</td>
      <td id="subsitetype">
	  <label><input name="subsite" type="radio"  value="0"  <?php if ($this->_vars['config']['subsite'] == "0"): ?>checked="checked"<?php endif; ?>/>否</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="subsite" value="1"   <?php if ($this->_vars['config']['subsite'] == "1"): ?>checked="checked"<?php endif; ?>/>是</label>
	   
	   <input name="oldsubsite"   id="oldsubsite" type="hidden" value="<?php echo $this->_vars['config']['subsite']; ?>
" />	   </td>
      </tr>
	<tr>
	  <td align="right">&nbsp;</td>
	  <td><input name="submit" type="submit" class="admin_submit"  id="sub"   value="保存修改"/></td>
	  </tr>
  </table>
 <table width="600" height="100" border="0" cellpadding="5" cellspacing="0" id="hide" style="display:none">
          <tr>
            <td align="center" valign="bottom"><img src="images/ajax_loader.gif"  border="0" /></td>
          </tr>
          <tr>
            <td align="center" valign="top" style="color: #009900">正在重建数据索引，请稍候......</td>
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