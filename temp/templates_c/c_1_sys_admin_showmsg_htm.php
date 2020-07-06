<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:35 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit">骑士人才系统管理中心</div>
  <div class="clear"></div>
</div>
<div class="toptit">系统提示</div>
<div class="showmsg">
	  <div class="left <?php if ($this->_vars['msg_type'] == "0"): ?>m0<?php elseif ($this->_vars['msg_type'] == "1"): ?>m1<?php else: ?>m2<?php endif; ?>">
	  </div>
	   <div class="right">
	   <h2><?php echo $this->_vars['msg_detail']; ?>
</h2>
	   <div id="redirectionMsg"><?php if ($this->_vars['auto_redirect']): ?>如果您不做出选择，将在 <span id="spanSeconds"><?php echo $this->_vars['seconds']; ?>
</span> 秒后跳转到第一个链接地址。<?php endif; ?></div>
	   <ul style="margin:0;list-style:none" >
		<?php if (count((array)$this->_vars['links'])): foreach ((array)$this->_vars['links'] as $this->_vars['link']): ?>
		<li><a href="<?php echo $this->_vars['link']['href']; ?>
" <?php if ($this->_vars['link']['target']): ?> target="<?php echo $this->_vars['link']['target']; ?>
"<?php endif; ?>><?php echo $this->_vars['link']['text']; ?>
</a></li>
		<?php endforeach; endif; ?>
		</ul>
	   </div>
	   <div class="clear"></div>
</div>
</div>
<?php if ($this->_vars['auto_redirect']): ?>
<script language="JavaScript">
<!--
var seconds = <?php echo $this->_vars['seconds']; ?>
;
var defaultUrl = "<?php echo $this->_vars['default_url']; ?>
";

<?php echo '
onload = function()
{
  if (defaultUrl == \'javascript:history.go(-1)\' && window.history.length == 0)
  {
    document.getElementById(\'redirectionMsg\').innerHTML = \'\';
    return;
  }

  window.setInterval(redirection, 1000);
}
function redirection()
{
  if (seconds <= 0)
  {
    window.clearInterval();
    return;
  }

  seconds --;
  document.getElementById(\'spanSeconds\').innerHTML = seconds;

  if (seconds == 0)
  {
    window.clearInterval();
    location.href = defaultUrl;
  }
}
//-->
</script>
'; ?>

<?php endif; ?>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>