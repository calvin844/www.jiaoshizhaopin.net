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
������վ����Ҫ����վ������������վ��ͬʱ����վ��󶨴˵ķ�վ������
</p>
</div> 
<form action="?act=subsite_del" method="post"  name="form1" id="form1">
<?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="list" class="link_lan">
    <tr>
      <td width="180" class="admin_list_tit admin_list_first"><label id="chkAll"><input type="checkbox" name="chkAll"  id="chk" title="ȫѡ/��ѡ" />��վ����</label></td>
      <td  class="admin_list_tit">����</td>
      <td width="100"  class="admin_list_tit">״̬</td>
      <td width="150"  class="admin_list_tit">����</td>
      <td width="150"  class="admin_list_tit">ģ��</td>
      <td width="150" align="center"  class="admin_list_tit">����</td>
    </tr>
	 <?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
	   <tr>
      <td  class="admin_list admin_list_first"><input type="checkbox" name="id[]" value="<?php echo $this->_vars['li']['s_id']; ?>
"/> <?php echo $this->_vars['li']['s_sitename']; ?>
 
	  <span style="color: #999999">(id:<?php echo $this->_vars['li']['s_id']; ?>
)</span>
	  </td>
      <td   class="admin_list"><a href="http://<?php echo $this->_vars['li']['s_domain']; ?>
" target="_blank"><?php echo $this->_vars['li']['s_domain']; ?>
</a></td>
      <td   class="admin_list"><?php if ($this->_vars['li']['s_effective'] == "1"): ?>����<?php else: ?><span style="color:#CCCCCC">����</span><?php endif; ?> </td>
      <td   class="admin_list">
	   <?php echo $this->_vars['li']['s_districtname']; ?>

	  </td>
      <td   class="admin_list">
	  <?php if ($this->_vars['li']['s_tpl'] == ""): ?>Ĭ��<?php else:  echo $this->_vars['li']['s_tpl'];  endif; ?>
	  </td>
      <td align="center"   class="admin_list">
	  <a href="?act=edit&id=<?php echo $this->_vars['li']['s_id']; ?>
" >�޸�</a>
		&nbsp;&nbsp;
		<a href="?act=subsite_del&id=<?php echo $this->_vars['li']['s_id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('ɾ�����޷��ָ�����ȷ��Ҫɾ����')">ɾ��</a>	  </td>
    </tr>
	  <?php endforeach; endif; ?>
  </table>
	<?php if (! $this->_vars['list']): ?>
<div class="admin_list_no_info">û���κ���Ϣ��</div>
<?php endif; ?>
<table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
<input type="button" class="admin_submit" id="ButAudit" value="��ӷ�վ"  onclick="window.location='?act=add'"/>
<input name=" " type="submit" class="admin_submit" id="ButAudit"   value="ɾ��"/>
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