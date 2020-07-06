<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 10:02 ?D1ú±ê×?ê±?? */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript"> 
$(document).ready(function()
{
	$(".tpl_list").hover(
  function () {
    $(this).css("background-color","#E4F4FC");
  },
  function () {
    $(this).css("background-color","");
  }
);
});
</script>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"><?php echo $this->_vars['pageheader']; ?>
</div>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
新增企业模板只需将模板文件上传至 ./templates/tpl_company目录下，更多模版请到 <a href="http://www.74cms.com/bbs" target="_blank" style="color:#009900">[论坛]</a> 获取。<br />
增加或删除模版后请点击下方<strong>刷新</strong>按钮。<br />
      如果您熟悉html语法，则可以参考 <a href="http://www.74cms.com/handbook" target="_blank" style="color:#009900">[模版开发手册]</a> 自定义风格模版。
</p>
</div>
<div class="toptit">企业模版设置</div>
<form id="form1" name="form1" method="post" action="?act=com_tpl_save">
<?php echo $this->_vars['inputtoken']; ?>

	<?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
	<div style=" line-height:180%; padding:5px; border-bottom:1px #D0E2F0 solid"  class="link_lan tpl_list">
	<table width="100%" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td width="225"><img src="../templates/tpl_company/<?php echo $this->_vars['li']['tpl_dir']; ?>
/thumbnail.jpg" alt="<?php echo $this->_vars['li']['info']['name']; ?>
" width="225" height="136" border="1"  style="border: #CCCCCC;"/></td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
        <td width="50" align="right">名称：</td>
        <td><input name="tpl_id[]" type="hidden" value="<?php echo $this->_vars['li']['tpl_id']; ?>
" />
          <input name="tpl_name[]" type="text" value="<?php echo $this->_vars['li']['tpl_name']; ?>
"  class="input_text_200" />
		  
		  </td>
      </tr>
      <tr>
        <td width="50" align="right">默认：</td>
        <td><label>
          <input name="tpl_company" type="radio" value="<?php echo $this->_vars['li']['tpl_dir']; ?>
" <?php if ($this->_vars['li']['tpl_dir'] == $this->_vars['QISHI']['tpl_company']): ?>checked="checked" <?php endif; ?>/>
        </label></td>
      </tr>
	  <tr>
        <td align="right">可用：</td>
        <td><label>
         <input name="tpl_display[]" type="checkbox" value="1" <?php if ($this->_vars['li']['tpl_display'] == "1"): ?>checked="checked" <?php endif; ?>/>
        </label></td>
      </tr>
      <tr>
        <td align="right">售价：</td>
        <td><input type="text" class="input_text_200" maxlength="3" value="<?php echo $this->_vars['li']['tpl_val']; ?>
" name="tpl_val[]"/>
		点积分
		
		
		<span class="admin_note">企业会员使用此模版需要支付的积分，0为免费</span></td>
      </tr>
	  <tr>
        <td align="right">版本：</td>
        <td><?php echo $this->_vars['li']['info']['version']; ?>
</td>
      </tr>
	        <tr>
        <td align="right">作者：</td>
        <td><a href="<?php echo $this->_vars['li']['info']['authorurl']; ?>
" target="_blank"><?php echo $this->_vars['li']['info']['author']; ?>
</a></td>
      </tr>
    </table></td>
  </tr>
</table>
</div>
	<?php endforeach; endif; ?>
	<table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
		 <input type="submit" name="" value="保存" class="admin_submit" />
		 <input type="button" name="Submit22" value="刷新" class="admin_submit"    onclick="window.location='?act=refresh_tpl&type=1&tpl_dir=tpl_company&<?php echo $this->_vars['urltoken']; ?>
'"/>
		</td>
        <td width="305" align="right">		
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