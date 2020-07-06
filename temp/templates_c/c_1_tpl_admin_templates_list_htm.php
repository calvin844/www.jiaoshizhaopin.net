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
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("tpl/admin_templates_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
新增模板只需将模板文件上传至 ./templates目录下，更多模版请到 <a href="http://www.74cms.com/bbs" target="_blank" style="color:#009900">[论坛]</a> 获取。<br />
      使用与骑士CMS不同版本的模板易产生错误<br />
      如果您熟悉html语法，则可以参考 <a href="http://www.74cms.com/handbook" target="_blank" style="color:#009900">[模版开发手册]</a> 自定义风格模版。
</p>
</div>
<div class="toptit">当前模板</div>
<table width="460" border="0" cellspacing="12" cellpadding="0" class="link_lan"  >
    <tr>
      <td width="225">
	  <a href="../" target="_blank"><img src="../templates/<?php echo $this->_vars['templates']['dir']; ?>
/thumbnail.jpg" alt="<?php echo $this->_vars['templates']['info']['name']; ?>
" width="225" height="136" border="1"  style="border: #CCCCCC;" />
	  </a>
	  </td>
      <td width="220" class="link_lan" style="line-height:180%">
	  名称：<?php echo $this->_vars['templates']['info']['name']; ?>
<br />
        版本：<?php echo $this->_vars['templates']['info']['version']; ?>
<br />
        作者：<a href="<?php echo $this->_vars['templates']['info']['authorurl']; ?>
" target="_blank"><?php echo $this->_vars['templates']['info']['author']; ?>
</a><br />
		模版ID：<?php echo $this->_vars['templates']['dir']; ?>

		<br />
	  <input type="button" name="Submit22" value="备份此模板" class="admin_submit"    onclick="window.location='?act=backup&tpl_name=<?php echo $this->_vars['templates']['dir']; ?>
&<?php echo $this->_vars['urltoken']; ?>
'"  style="margin-top:10px;"/>
	  </td>
    </tr>
  </table>
	<div class="toptit">可用模板</div>
	<?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
	  <div style="float:left; width:240px;  text-align:center; padding:15px; line-height:180%"  class="link_lan tpl_list">
	  <a href="?act=set&tpl_dir=<?php echo $this->_vars['li']['dir']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('你确定要使用此模板吗？(提示：频繁更换模版会影响网站排名)')">
	  <img src="../templates/<?php echo $this->_vars['li']['dir']; ?>
/thumbnail.jpg" alt="<?php echo $this->_vars['li']['info']['name']; ?>
" width="225" height="136" border="1"  style="border: #CCCCCC;"/>
	  </a>
	  <br />
	 <strong><?php echo $this->_vars['li']['info']['name']; ?>
</strong>
	 <br />
	<?php echo $this->_vars['li']['info']['version']; ?>
 (作者:<a href="<?php echo $this->_vars['li']['info']['authorurl']; ?>
" target="_blank"><?php echo $this->_vars['li']['info']['author']; ?>
</a>)
	 <br />
	模版ID：<?php echo $this->_vars['li']['dir']; ?>

	 </div>
	<?php endforeach; endif; ?>
	<div class="clear"></div>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>