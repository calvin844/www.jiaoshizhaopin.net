<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:38 �й���׼ʱ�� */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript">
$(document).ready(function()
{
	$("#SetUrl").QSdialog({
	DialogTitle:"��ѡ��",
	DialogContent:"#UrlHtml",
	DialogContentType:"id",
	DialogAddObj:"#OpUrl",
	DialogAddType:"html"	
	});
	$("#SetCaching").QSdialog({
	DialogTitle:"��ѡ��",
	DialogContent:"#CachingHtml",
	DialogContentType:"id",
	DialogAddObj:"#OpCaching",	
	DialogAddType:"html"	
	});	
 	//�������ɾ��	
	$("#ButDel").click(function(){
		if (confirm('��ȷ��Ҫɾ����'))
		{
			$("form[name=form1]").attr("action","?act=del_page");
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
echo $this->_fetch_compile_include("page/admin_page_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>��ʾ��</h2>
	<p>
�����ͨ��ȫѡ����������ҳ������ӷ�ʽ�ͻ���ʱ��<br />
        ְλ�б�ҳ���˲��б�ҳ����Ա���ģ�ְλ�Ա�ҳ������ܿ�������
		<br />ϵͳ������ҳ���޷�ɾ����
		<br />ǿ�ҽ��鿪��ҳ�滺�棬������ϵͳ����������ߣ�
</p>
</div>
  <form action="?act=set_page" method="post"  name="form1" id="form1">
  <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="link_lan"   id="list">
     <tr>
      <td   class="admin_list_tit admin_list_first">
      <label id="chkAll"><input type="checkbox" name="chkAll"  id="chk" title="ȫѡ/��ѡ" />ҳ������</label>
	  </td>
      <td class="admin_list_tit"> ������ </td>
      <td   align="center" class="admin_list_tit">����</td>
      <td   align="center"  class="admin_list_tit">����</td>
      <td   align="center"   class="admin_list_tit">����</td>
      <td width="130" align="center"  class="admin_list_tit" >�༭</td>
    </tr>
	 <?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
	<tr>
      <td class="admin_list admin_list_first">  
	  <input type="checkbox" name="id[]" value="<?php echo $this->_vars['li']['id']; ?>
"/>   
	 <?php echo $this->_vars['li']['pname']; ?>

	  </td>
      <td class="admin_list"> 
	  <?php echo $this->_vars['li']['alias']; ?>
	   </td>
      <td   align="center" class="admin_list">
	  
	  <?php if ($this->_vars['li']['systemclass'] == "1"): ?>
		<span style="color: #FF6600">ϵͳ����</span>
		<?php else: ?>
		�Զ���ҳ��
		<?php endif; ?>
		</td>
      <td   align="center"  class="admin_list">
	   <?php if ($this->_vars['li']['url'] == "0"): ?>ԭʼ����<?php endif; ?>
		 <?php if ($this->_vars['li']['url'] == "1"): ?>α��̬<?php endif; ?>
	  </td>
      <td   align="center"   class="admin_list">
	  <?php if ($this->_vars['li']['caching'] == "0"): ?>
		<span style="color:#999999">�ѹر�</span>
		<?php else: ?>
		<em><?php echo $this->_vars['li']['caching']; ?>
</em> ��
		<?php endif; ?>	
	  </td>
      <td   align="center"  class="admin_list" >
	  <a href="?act=edit_page&id=<?php echo $this->_vars['li']['id']; ?>
">�޸�</a><?php if ($this->_vars['li']['systemclass'] != "1"): ?> <a href="?act=del_page&id=<?php echo $this->_vars['li']['id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('��ȷ��Ҫɾ����')">ɾ��</a> <?php endif; ?>
	  </td>
    </tr>
	<?php endforeach; endif; ?>
    </table> 
    <table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
<input name="add" type="button" class="admin_submit" id="add"    value="����ҳ��"  onclick="window.location='?act=add_page'"/>
<input type="button" name="open" value="��������" class="admin_submit"  id="SetUrl"/>
<input type="button" name="open1" value="���û���" class="admin_submit"  id="SetCaching"/>
<input type="button" name="ButDel" id="ButDel" value="ɾ��ҳ��" class="admin_submit"   />
		</td>
        <td width="305" align="right">
		
	    </td>
      </tr>
  </table>
  <span id="OpUrl"></span>
  <span id="OpCaching"></span>
   </form>
<div class="page link_bk"><?php echo $this->_vars['page']; ?>
</div>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <!--�����������-->
<div id="UrlHtml" style="display: none" >
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="6" >
    <tr>
      <td width="20" height="30">&nbsp;</td>
      <td height="30"><strong  style="color:#0066CC; font-size:14px;">����ѡҳ����������Ϊ��</strong></td>
    </tr>
	      <tr>
      <td height="25">&nbsp;</td>
      <td>
	  <label >
                      <input name="url" type="radio" value="0" checked="checked"  />
                      ԭʼ���� </label>	  </td>
    </tr>
    <tr>
      <td height="25">&nbsp;</td>
      <td><label >
                      <input type="radio" name="url" value="1"  />
                      α��̬</label></td>
    </tr>

	  <tr>
	    <td height="25">&nbsp;</td>
	    <td><input type="submit" name="set_url" value="ȷ��" class="admin_submit"    /></td>
      </tr>
  </table>
</div>
<!--����������ݽ���--> 
<div id="CachingHtml" style="display: none" >
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="6" >
    <tr>
      <td height="30">&nbsp;</td>
      <td height="30"><strong  style="color:#0066CC; font-size:14px;">����ѡҳ�滺������Ϊ��</strong></td>
    </tr>
	      <tr>
      <td width="20" height="25">&nbsp;</td>
      <td>
	 <input name="caching" type="text"  class="input_text_200" id="caching" value="0" maxlength="30"/>

            ���� <br /><br />

			<span style="color:#666666">(0Ϊ������,������Ϊ180 ����) </span></td>
    </tr>
 
	      <tr>
	        <td height="25">&nbsp;</td>
	        <td><input type="submit" name="set_caching" value="ȷ��" class="admin_submit"></td>
    </tr>
  </table>
</div>
<!--����������ݽ���-->
</body>
</html>