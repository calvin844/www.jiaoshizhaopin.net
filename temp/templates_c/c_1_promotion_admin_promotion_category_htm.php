<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-10 10:19 �й���׼ʱ�� */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("promotion/admin_promotion_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>��ʾ��</h2>
	<p>
ϵͳ���÷�������ɾ��<br />
��Ա�����ƹ㷽����ϵͳ���Զ���ͨ�����ں��Զ�ȡ����
</p>
</div>
 
		  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="list" class="link_lan">
          <tr>
            <td  class="admin_list_tit admin_list_first">��������</td>
			<td width="15%" align="center" class="admin_list_tit">����</td>
            <td width="15%" align="center" class="admin_list_tit">״̬</td>
            <td width="15%" align="center" class="admin_list_tit" >����</td>
            <td width="15%" align="center" class="admin_list_tit">����</td>

			
            <td width="15%" align="center" class="admin_list_tit">�༭</td>
            </tr>
			<?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
			
			  <tr>
            <td  class="admin_list admin_list_first">
		 
			 <a href="?act=edit_category&id=<?php echo $this->_vars['li']['cat_id']; ?>
"><?php echo $this->_vars['li']['cat_name']; ?>
</a>
			</td>
			 <td align="center" class="admin_list">
			<?php echo $this->_vars['li']['cat_points']; ?>
/��
			</td>
            <td class="admin_list" align="center">
			<?php if ($this->_vars['li']['cat_available'] == "1"): ?>
			����
			<?php else: ?>
			<span style="color:#999999">����</span>
			<?php endif; ?>
			</td>
            <td align="center" class="admin_list" >	
			
			<?php if ($this->_vars['li']['cat_type'] == "1"): ?>
			<span style="color:#FF6600">����</span>
			<?php else: ?>
			�Զ���
			<?php endif; ?>					
			
			</td>
            <td align="center" class="admin_list">
			<?php echo $this->_vars['li']['cat_order']; ?>

			</td>
		 
			
            <td align="center" class="admin_list">
	 <a href="?act=edit_category&id=<?php echo $this->_vars['li']['cat_id']; ?>
">�޸�</a>
	 
	  <?php if ($this->_vars['li']['cat_type'] == "1"): ?>
	  <span style="color:#999999">ɾ��</span>
	  <?php else: ?>
		 <a href="?act=del_ad&id=<?php echo $this->_vars['li']['id']; ?>
"  onclick="return confirm('��ȷ��Ҫɾ����')">ɾ��</a>
			<?php endif; ?>	
				</td>
            </tr>
			<?php endforeach; endif; ?>
  </table>
  <br />
<br />
<br />

<!--		<table width="100%" border="0" cellspacing="10"  class="admin_list_btm">
<tr>
      <td>
       <input type="button" name="Submit22" value="��������" class="admin_submit"    onclick="window.location='?act=ad_category_add'"/> 
	  </td>
      <td width="360">	  
	  </td>
     </tr>
  </table> -->
 
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>