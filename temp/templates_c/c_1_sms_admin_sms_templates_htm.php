<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 16:43 �й���׼ʱ�� */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sms/admin_sms_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
	<div class="toptip">
	<h2>��ʾ��</h2>
		<p>����ģ����������HTML��<br />
�������ݲ��ܳ���60���ַ������������Զ�ɾ�������ַ���
</p>
		</div>
	<div class="toptit">�ʼ����͹���</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
       <tr>
        <td height="30" style="font-size:12px;padding-left:20px;">
		<form action="admin_mail.php?act=email_config_save" method="post"   name="form1" id="form1">
		<table width="242" border="0" cellspacing="7" cellpadding="1" style=" margin-bottom:3px;" class="link_lan">
            <tr>
              <td align="right" width="137">����ְλ��</td>
              <td><a href="?act=edit_tpl&templates_name=set_applyjobs&thisname=����ְλ">[�༭ģ��]</a></td>
            </tr>
            <tr>
              <td align="right">�������ԣ�</td>
              <td><a href="?act=edit_tpl&templates_name=set_invite&thisname=��������">[�༭ģ��]</a></td>
            </tr>
            <tr>
              <td align="right">����������</td>
              <td><a href="?act=edit_tpl&templates_name=set_order&thisname=�����ֵ">[��������]</a></td>
            </tr>
            <tr>
              <td align="right">��ֵ�ɹ���</td>
              <td><a href="?act=edit_tpl&templates_name=set_payment&thisname=��ֵ�ɹ�">[�༭ģ��]</a></td>
            </tr>
            <tr>
              <td align="right">�޸����룺</td>
              <td><a href="?act=edit_tpl&templates_name=set_editpwd&thisname=�޸�����">[�༭ģ��]</a></td>
            </tr>
            <tr>
              <td align="right">ְλ���ͨ����</td>
              <td><a href="?act=edit_tpl&templates_name=set_jobsallow&thisname=ְλ���ͨ��">[�༭ģ��]</a></td>
            </tr>
            <tr>
              <td align="right">ְλ���δͨ����</td>
              <td><a href="?act=edit_tpl&templates_name=set_jobsnotallow&thisname=ְλ���δͨ��">[�༭ģ��]</a></td>
            </tr>
            <tr>
              <td align="right">Ӫҵִ�����ͨ����</td>
              <td><a href="?act=edit_tpl&templates_name=set_licenseallow&thisname=Ӫҵִ�����ͨ��">[�༭ģ��]</a></td>
            </tr>
            <tr>
              <td align="right">Ӫҵִ�����δͨ����</td>
              <td><a href="?act=edit_tpl&templates_name=set_licensenotallow&thisname=Ӫҵִ�����δͨ��">[�༭ģ��]</a></td>
            </tr>
            <tr>
              <td align="right">��ͨ���ӵ�ͼ��</td>
              <td><a href="?act=edit_tpl&templates_name=set_addmap&thisname=��ͨ���ӵ�ͼ">[�༭ģ��]</a></td>
            </tr>
            <tr>
              <td align="right">�������ͨ����</td>
              <td><a href="?act=edit_tpl&templates_name=set_resumeallow&thisname=�������ͨ��">[�༭ģ��]</a></td>
            </tr>
            <tr>
              <td align="right">�������δͨ����</td>
              <td><a href="?act=edit_tpl&templates_name=set_resumenotallow&thisname=�������δͨ��">[�༭ģ��]</a></td>
            </tr>
            <tr>
              <td height="55" align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
		  </form>		  </td>
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