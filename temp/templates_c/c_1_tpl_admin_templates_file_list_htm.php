<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:39 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
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
编辑前强烈建议您先做模板备份<br />
      请尽量避免在网速较慢时编辑模板，以防止编辑过程中出错。
</p>
</div>
<div class="toptit">编辑模板</div>
 <table width="460" border="0" cellspacing="12" cellpadding="0"  style="line-height:180%">
    <tr>
      <td width="225"><a href="../" target="_blank"><img src="../templates/<?php echo $this->_vars['templates']['dir']; ?>
/thumbnail.jpg" alt="<?php echo $this->_vars['templates']['info']['name']; ?>
" width="225" height="136" border="1"  style="border: #CCCCCC;" /></a></td>
      <td width="220" class="link_lan">名称：<?php echo $this->_vars['templates']['info']['name']; ?>
<br />
        版本：<?php echo $this->_vars['templates']['info']['version']; ?>
<br />
        作者：<a href="<?php echo $this->_vars['templates']['info']['authorurl']; ?>
" target="_blank"><?php echo $this->_vars['templates']['info']['author']; ?>
</a> <br />
		模版ID：<?php echo $this->_vars['templates']['dir']; ?>

		<br />
	  <input type="button" name="Submit22" value="备份此模板" class="admin_submit"    onclick="window.location='?act=backup&tpl_name=<?php echo $this->_vars['templates']['dir']; ?>
&<?php echo $this->_vars['urltoken']; ?>
'"  style="margin-top:10px;"/>	  </td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0"  id="list" class="link_lan">
        <tr >
          <td class="admin_list_tit admin_list_first">模板文件名</td>
          <td width="15%" align="center" class="admin_list_tit">修改时间</td>
          <td width="15%" align="center" class="admin_list_tit">大小(字节)</td>
          <td width="15%" align="center" class="admin_list_tit">操作</td>
        </tr>
		<?php if (isset($this->_sections['list'])) unset($this->_sections['list']);
$this->_sections['list']['name'] = 'list';
$this->_sections['list']['loop'] = is_array($this->_vars['list']) ? count($this->_vars['list']) : max(0, (int)$this->_vars['list']);
$this->_sections['list']['show'] = true;
$this->_sections['list']['max'] = $this->_sections['list']['loop'];
$this->_sections['list']['step'] = 1;
$this->_sections['list']['start'] = $this->_sections['list']['step'] > 0 ? 0 : $this->_sections['list']['loop']-1;
if ($this->_sections['list']['show']) {
	$this->_sections['list']['total'] = $this->_sections['list']['loop'];
	if ($this->_sections['list']['total'] == 0)
		$this->_sections['list']['show'] = false;
} else
	$this->_sections['list']['total'] = 0;
if ($this->_sections['list']['show']):

		for ($this->_sections['list']['index'] = $this->_sections['list']['start'], $this->_sections['list']['iteration'] = 1;
			 $this->_sections['list']['iteration'] <= $this->_sections['list']['total'];
			 $this->_sections['list']['index'] += $this->_sections['list']['step'], $this->_sections['list']['iteration']++):
$this->_sections['list']['rownum'] = $this->_sections['list']['iteration'];
$this->_sections['list']['index_prev'] = $this->_sections['list']['index'] - $this->_sections['list']['step'];
$this->_sections['list']['index_next'] = $this->_sections['list']['index'] + $this->_sections['list']['step'];
$this->_sections['list']['first']	  = ($this->_sections['list']['iteration'] == 1);
$this->_sections['list']['last']	   = ($this->_sections['list']['iteration'] == $this->_sections['list']['total']);
?>
        <tr>
          <td  class="admin_list admin_list_first"><a href="?act=edit_file&tpl_name=<?php echo $this->_vars['list'][$this->_sections['list']['index']]['name']; ?>
&tpl_dir=<?php echo $this->_vars['templates']['dir']; ?>
"><?php echo $this->_vars['list'][$this->_sections['list']['index']]['name']; ?>
</a></td>
          <td align="center" class="admin_list admin_list_first"> <?php echo $this->_vars['list'][$this->_sections['list']['index']]['modify_time']; ?>
</td>
          <td align="center" class="admin_list admin_list_first"><?php echo $this->_vars['list'][$this->_sections['list']['index']]['size']; ?>
</td>
          <td align="center" class="admin_list admin_list_first"><a href="?act=edit_file&tpl_name=<?php echo $this->_vars['list'][$this->_sections['list']['index']]['name']; ?>
&tpl_dir=<?php echo $this->_vars['templates']['dir']; ?>
">编辑</a></td>
        </tr>
		<?php endfor; endif; ?>
  </table>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>