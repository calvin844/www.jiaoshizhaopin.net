<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 16:41 �й���׼ʱ�� */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("nav/admin_nav_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
<h2>��ʾ��</h2>
<p>
ϵͳ���÷��಻���Ա༭����ɾ��<br />�Զ������������������� ��QS_����ͷ
</p>
</div>

		  <table width="100%" border="0" cellpadding="2" cellspacing="0" id="list" class="link_lan">
          <tr>
            <td width="15%" class="admin_list_tit admin_list_first">��������</td>
            <td class="admin_list_tit">��������</td>
            <td width="15%" align="center" class="admin_list_tit">����</td>
            <td width="15%" align="center" class="admin_list_tit">�༭</td>
            </tr>
			<?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
          <tr>
            <td    class="admin_list admin_list_first"><strong><?php echo $this->_vars['li']['alias']; ?>
</strong></td>
            <td class="admin_list">
			<?php echo $this->_vars['li']['categoryname']; ?>
			</td>
            <td align="center" class="admin_list">			
			<?php if ($this->_vars['li']['admin_set'] == "1"): ?>
			<span style="color:#FF6600">ϵͳ����</span>
			<?php else: ?>
			�Զ���
			<?php endif; ?>			</td>
            <td align="center" class="admin_list">
			<?php if ($this->_vars['li']['admin_set'] != "1"): ?>
			<a href="?act=site_navigation_category_edit&alias=<?php echo $this->_vars['li']['alias']; ?>
"  >�޸�</a>
			&nbsp;&nbsp;&nbsp;
			<a href="?act=site_navigation_category_del&id=<?php echo $this->_vars['li']['id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('��ȷ��Ҫɾ����')">ɾ��</a>
			<?php else: ?>
			<span style="color:#999999">�޸�</span>&nbsp;&nbsp;&nbsp;
			<span style="color:#999999">ɾ��</span>
			
			<?php endif; ?>
&nbsp;			</td>
          </tr>
		  <?php endforeach; endif; ?>
  </table>
		  <table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
 <input type="button" name="Submit22" value="�������" class="admin_submit"    onclick="window.location='?act=site_navigation_category_add'"/>
		</td>
        <td width="305" align="right">		
	    </td>
      </tr>
  </table>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>