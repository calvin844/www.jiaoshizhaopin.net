<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:39 �й���׼ʱ�� */  $_templatelite_tpl_vars = $this->_vars;
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
<h2>��ʾ��</h2>
<p>
<span style="color:#FF6600">�����������վ�����վ���ݺ�ģ����ڴ����ظ������ܻᵼ���������潵Ȩ����Kվ��</span><br />
������վ����Ҫ����վ������������վ��ͬʱ����վ��󶨴˵ķ�վ������
</p>
</div> 
<script type="text/javascript">
$(document).ready(function()
{
//
	//$("form[name=form1]").submit(function(){
		//	if ($("#oldsubsite").val()!=$("#subsitetype  :radio[checked]").val())
		//	{	
			//	if (confirm('������رշ�վ��ʹ���������ؽ����˹���ִ��ʱ��ȡ�����������Ĵ�С��\n\n��ִ�����벻Ҫ�뿪��ҳ�档\n\n��ȷ��Ҫ�޸���'))
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
<div class="toptit">������վ</div>
<form action="?act=setsave" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="5" id="set">
	<tr>
      <td width="80" align="right">������վ��</td>
      <td id="subsitetype">
	  <label><input name="subsite" type="radio"  value="0"  <?php if ($this->_vars['config']['subsite'] == "0"): ?>checked="checked"<?php endif; ?>/>��</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="subsite" value="1"   <?php if ($this->_vars['config']['subsite'] == "1"): ?>checked="checked"<?php endif; ?>/>��</label>
	   
	   <input name="oldsubsite"   id="oldsubsite" type="hidden" value="<?php echo $this->_vars['config']['subsite']; ?>
" />	   </td>
      </tr>
	<tr>
	  <td align="right">&nbsp;</td>
	  <td><input name="submit" type="submit" class="admin_submit"  id="sub"   value="�����޸�"/></td>
	  </tr>
  </table>
 <table width="600" height="100" border="0" cellpadding="5" cellspacing="0" id="hide" style="display:none">
          <tr>
            <td align="center" valign="bottom"><img src="images/ajax_loader.gif"  border="0" /></td>
          </tr>
          <tr>
            <td align="center" valign="top" style="color: #009900">�����ؽ��������������Ժ�......</td>
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