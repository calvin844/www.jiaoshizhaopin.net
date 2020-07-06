<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.default.php'); $this->register_modifier("default", "tpl_modifier_default",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:39 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript">
$(document).ready(function()
{
	//点击批量取消	
	$("#ButDel").click(function(){
		
		if (confirm('你确定要删除吗？'))
		{
			$("form[name=form1]").submit()
		}
	});
		
});
</script>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("hotword/admin_hotword_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
<h2>提示：</h2>
<p>
您可以修改关键字搜索次数来改变关键词排序；
</p>
</div> 

  <form id="form1" name="form1" method="post" action="?act=hottype_del">
  <?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="list" class="link_lan">
    <tr>
      <td width="45%" class="admin_list_tit admin_list_first">
      <label id="chkAll"><input type="checkbox" name="chkAll"  id="chk" title="全选/反选" />关键词</label>
	  </td>
      <td   align="center" class="admin_list_tit"> 搜索次数 </td>

      <td width="180" align="center"  class="admin_list_tit" >操作</td>
    </tr>
	 <?php if (count((array)$this->_vars['hotword'])): foreach ((array)$this->_vars['hotword'] as $this->_vars['list']): ?>
    <tr>
      <td   class="admin_list admin_list_first">
	  <input name="id[]" type="checkbox"  value="<?php echo $this->_vars['list']['w_id']; ?>
" />
	  <?php echo $this->_vars['list']['w_word']; ?>

	   </td>
      <td align="center"  class="admin_list">
	  <?php echo $this->_vars['list']['w_hot']; ?>

	  </td>
	
      <td align="center"   class="admin_list">
	  <a href="?act=edit&id=<?php echo $this->_vars['list']['w_id']; ?>
">修改</a> <a href="?act=hottype_del&id=<?php echo $this->_vars['list']['w_id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('你确定要删除吗？')">删除</a>	  </td>
    </tr>
	 <?php endforeach; endif; ?>
  </table>
  </form>
  <?php if (! $this->_vars['hotword']): ?>
<div class="admin_list_no_info">没有任何信息！</div>
<?php endif; ?>
<table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
<input type="button" class="admin_submit" id="ButAudit" value="添加词语"  onclick="window.location='?act=add'"/>
<input type="button" class="admin_submit" id="ButDel" value="删除所选"/>
		</td>
        <td width="305" align="right">
		<form id="formseh" name="formseh" method="get" action="?">	
			<div class="seh">
			    <div class="keybox"><input name="key" type="text"   value="<?php echo $_GET['key']; ?>
" /></div>
			    <div class="selbox">
					<input name="key_type_cn"  id="key_type_cn" type="text" value="<?php echo $this->_run_modifier($_GET['key_type_cn'], 'default', 'plugin', 1, "关键词"); ?>
" readonly="true"/>
						<div>
								<input name="key_type" id="key_type" type="hidden" value="<?php echo $this->_run_modifier($_GET['key_type'], 'default', 'plugin', 1, "1"); ?>
" />
												 
						</div>				
				</div>
				<div class="sbtbox">
				<input name="act" type="hidden" value="list" />
				<input type="submit" name="" class="sbt" id="sbt" value="搜索"/>
				</div>
				<div class="clear"></div>
		  </div>
		  </form>
		
	    </td>
      </tr>
  </table>
<div class="page link_bk"><?php echo $this->_vars['page']; ?>
</div>
  
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>