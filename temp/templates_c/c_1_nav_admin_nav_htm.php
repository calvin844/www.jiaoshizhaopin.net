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
echo $this->_fetch_compile_include("nav/admin_nav_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
<h2>��ʾ��</h2>
<p>
ҳ���������뵼�����������ͬʱ(���������������ҳ������в鿴)����֮������ҳ�潫������ʾ��        ���磺��ҳ������У���ҳ�ĵ����������Ϊhomepage,���Զ��嵼����������վ��ҳ��Ŀ��ҳ�������Ϊhomepage����ô����վ��ҳҳ�棬�����Ŀ������ʾ��
</p>
</div>
  <form action="?act=site_navigation_all_save" method="post"  name="form1" id="form1"> 
  <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="list" class="link_lan">
      <tr>
        <td width="8%"  class="admin_list_tit admin_list_first" >��ʾ</td>
        <td  class="admin_list_tit">����</td>
        <td width="15%" align="center" class="admin_list_tit">ҳ��������</td>
        <td width="8%" align="center" class="admin_list_tit">λ��</td>
        <td width="15%" align="center" class="admin_list_tit">�򿪷�ʽ</td>
        <td width="8%" align="center" class="admin_list_tit">����</td>
        <td width="12%" align="center" class="admin_list_tit">�༭</td>
      </tr>
	   <?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
	     <tr>
        <td  class="admin_list admin_list_first">
		 <?php if ($this->_vars['li']['display'] == "1"): ?>
	  <span style="color: #FF3300">��ʾ</span>
	  <?php else: ?>
	<span style="color:#999999">����ʾ</span>
	  <?php endif; ?>
		</td>
        <td    class="admin_list">
		<input name="title[]" type="text"  class="input_text_200" id="title" value="<?php echo $this->_vars['li']['title']; ?>
"   />
		</td>
        <td align="center"    class="admin_list"><?php echo $this->_vars['li']['tag']; ?>
&nbsp;</td>
        <td align="center"   class="admin_list"><?php echo $this->_vars['li']['categoryname']; ?>
</td>
        <td align="center"   class="admin_list">
		<?php if ($this->_vars['li']['target'] == "_blank"): ?>
		�´���
		<?php endif; ?>
		<?php if ($this->_vars['li']['target'] == "_self"): ?>
		<span style="color:#666666">ԭ����</span>
		<?php endif; ?>		</td>
        <td align="center"  class="admin_list" >
		<input name="navigationorder[]" type="text"  class="input_text_50" id="title" value="<?php echo $this->_vars['li']['navigationorder']; ?>
"   />
		</td>
        <td align="center"  class="admin_list" >
		<a href="?act=site_navigation_edit&id=<?php echo $this->_vars['li']['id']; ?>
">�޸�</a>		<?php if ($this->_vars['li']['systemclass'] != "1"): ?>
		<a href="?act=del_navigation&id=<?php echo $this->_vars['li']['id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
"  onclick="return confirm('��ȷ��Ҫɾ����')">ɾ��</a>
		<?php endif; ?>
		<input name="id[]" type="hidden" value="<?php echo $this->_vars['li']['id']; ?>
" />	
		</td>
      </tr>
	   <?php endforeach; endif; ?>
    </table>
	<table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
<input type="submit" name="Submit22" value="�����޸�" class="admin_submit"   />

		<input name="add" type="button" class="admin_submit" id="add"    value="�����Ŀ"  onclick="window.location='?act=site_navigation_add'"/>
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