<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-25 14:43 �й���׼ʱ�� */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("ads/admin_ad_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>��ʾ��</h2>
	<p>
ϵͳ���ù��λ���ܱ༭<br />�Զ�����λ�������������� ��QS_����ͷ
</p>
</div>
 
		  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="list" class="link_lan">
          <tr>
            <td  class="admin_list_tit admin_list_first">���λ����</td>
            <td class="admin_list_tit">��������</td>
            <td width="12%" align="center" class="admin_list_tit" >����</td>
            <td width="12%" align="center" class="admin_list_tit">����</td>
            <td width="12%" align="center" class="admin_list_tit">�༭</td>
            </tr>
			<?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
			
			  <tr>
            <td  class="admin_list admin_list_first">
			<?php echo $this->_vars['li']['categoryname']; ?>
	
			</td>
            <td class="admin_list">
			<?php echo $this->_vars['li']['alias']; ?>

			</td>
            <td align="center" class="admin_list" >
			<?php if ($this->_vars['li']['type_id'] == "1"): ?>����
	  <?php elseif ($this->_vars['li']['type_id'] == "2"): ?>ͼƬ
	  <?php elseif ($this->_vars['li']['type_id'] == "3"): ?>����
	  <?php elseif ($this->_vars['li']['type_id'] == "4"): ?>FLASH
	  <?php elseif ($this->_vars['li']['type_id'] == "5"): ?>����
	  <?php elseif ($this->_vars['li']['type_id'] == "6"): ?>��Ƶ
	  <?php endif; ?>			</td>
            <td align="center" class="admin_list">
			<?php if ($this->_vars['li']['admin_set'] == "1"): ?>
			<span style="color:#FF6600">ϵͳ����</span>
			<?php else: ?>
			�Զ�����λ
			<?php endif; ?>			</td>
            <td align="center" class="admin_list">
			<?php if ($this->_vars['li']['admin_set'] != "1"): ?>
			<a href="?act=edit_ad_category&id=<?php echo $this->_vars['li']['id']; ?>
"  >�޸�</a>
			&nbsp;&nbsp;&nbsp;
			<a href="?act=del_ad_category&id=<?php echo $this->_vars['li']['id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('��ȷ��Ҫɾ����')">ɾ��</a>
			<?php else: ?>
			<span style="color:#999999">�޸�</span>&nbsp;&nbsp;&nbsp;
			<span style="color:#999999">ɾ��</span>
			
			<?php endif; ?>			</td>
            </tr>
			<?php endforeach; endif; ?>
  </table>
		<table width="100%" border="0" cellspacing="10"  class="admin_list_btm">
<tr>
      <td>
       <input type="button" name="Submit22" value="�������λ" class="admin_submit"    onclick="window.location='?act=ad_category_add'"/> 
	  </td>
      <td width="360">	  
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