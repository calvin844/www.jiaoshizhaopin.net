<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:38 �й���׼ʱ�� */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>��ʾ��</h2>
	<p>
�����Ը�����վ����������ѡ��������ʽ��<br />
ȫ������Ч�ʸ��ߣ�like�������Ӿ�׼��<br />
likeֻ������ְλ���ƺ͹�˾���ơ�<br />
ȫ��������������������ݣ��������ؼ���ȡ���ڹؼ��ֵ��ֵ䣬�ֵ�λ�ã�include/word.txt�� �����Ե����ֵ�������Ż�ȫ��������<br />
</p>
</div>
<div class="toptit">ְλ����</div>
  <form action="?act=search_save" method="post"  name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="5">
    <tr>
      <td width="120" align="right">�����ؼ���������</td>
      <td>
	   <label><input name="jobsearch_purview" type="radio"  value="1"  <?php if ($this->_vars['config']['jobsearch_purview'] == "1"): ?>checked="checked"<?php endif; ?>/>�κ��û�</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="jobsearch_purview" value="2"  <?php if ($this->_vars['config']['jobsearch_purview'] == "2"): ?>checked="checked"<?php endif; ?>/>���ѵ�¼��Ա</label>	  </td>
    </tr>
	 <tr>
      <td width="120" align="right">����ģʽ��</td>
      <td>
	   <label><input name="jobsearch_type" type="radio"  value="1"  <?php if ($this->_vars['config']['jobsearch_type'] == "1"): ?>checked="checked"<?php endif; ?>/>ȫ������</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="jobsearch_type" value="2"  <?php if ($this->_vars['config']['jobsearch_type'] == "2"): ?>checked="checked"<?php endif; ?>/>like</label>	  </td>
    </tr>
	 <tr>
      <td width="120" align="right">�����������</td>
      <td>
	   <label><input name="jobsearch_sort" type="radio"  value="1"  <?php if ($this->_vars['config']['jobsearch_sort'] == "1"): ?>checked="checked"<?php endif; ?>/>Ĭ��</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label><input name="jobsearch_sort" type="radio" value="2"  <?php if ($this->_vars['config']['jobsearch_sort'] == "2"): ?>checked="checked"<?php endif; ?>/>ˢ��ʱ��</label>	
	   <span class="admin_note">��ˢ��ʱ�������Ӱ������Ч��</span>
	   </td>
    </tr>
	 
	      
	       <tr>
	         <td align="right">&nbsp;</td>
	         <td height="50"> 
	           <input name="submit" type="submit" class="admin_submit"    value="����"/>             </td>
      </tr>
  </table>
  </form>
  <div class="toptit">��������</div>
  <form action="?act=search_save" method="post"  name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="5">
    <tr>
      <td width="120" align="right">�����ؼ���������</td>
      <td>
	   <label><input name="resumesearch_purview" type="radio"  value="1"  <?php if ($this->_vars['config']['resumesearch_purview'] == "1"): ?>checked="checked"<?php endif; ?>/>�κ��û�</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="resumesearch_purview" value="2"  <?php if ($this->_vars['config']['resumesearch_purview'] == "2"): ?>checked="checked"<?php endif; ?>/>���ѵ�¼��Ա</label>	  </td>
    </tr>
	 <tr>
      <td width="120" align="right">����ģʽ��</td>
      <td>
	   <label><input name="resumesearch_type" type="radio"  value="1"  <?php if ($this->_vars['config']['resumesearch_type'] == "1"): ?>checked="checked"<?php endif; ?>/>ȫ������</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="resumesearch_type" value="2"  <?php if ($this->_vars['config']['resumesearch_type'] == "2"): ?>checked="checked"<?php endif; ?>/>like</label>	  </td>
    </tr>
	 <tr>
      <td width="120" align="right">�����������</td>
      <td>
	   <label><input name="resumesearch_sort" type="radio"  value="1"  <?php if ($this->_vars['config']['resumesearch_sort'] == "1"): ?>checked="checked"<?php endif; ?>/>Ĭ��</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label><input name="resumesearch_sort" type="radio" value="2"  <?php if ($this->_vars['config']['resumesearch_sort'] == "2"): ?>checked="checked"<?php endif; ?>/>ˢ��ʱ��</label>
	   <span class="admin_note">��ˢ��ʱ�������Ӱ������Ч��</span>
	   </td>
    </tr>
	       <tr>
	         <td align="right">&nbsp;</td>
	         <td height="50"> 
	           <input name="submit" type="submit" class="admin_submit"    value="����"/>
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