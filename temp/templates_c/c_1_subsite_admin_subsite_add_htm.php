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
 <div class="toptit">������վ</div>
 
    <form id="form1" name="form1" method="post" enctype="multipart/form-data" action="?act=add_save" >
	<?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="4" cellspacing="0"   >
    <tr>
      <td width="120" height="30" align="right"  >��վ���ƣ�</td>
      <td  ><input name="s_sitename" type="text" class="input_text_200"   maxlength="100" value=""/>
      <span class="admin_note">�磺̫ԭ�˲���</span></td>
    </tr>
	    <tr>
      <td height="30" align="right"  >��վ״̬��</td>
      <td   >
	   <label><input name="s_effective" type="radio"   value="1" checked="checked"/>����</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_effective" value="0"  />����</label>
	  </td>
    </tr>

	    <tr>
      <td height="30" align="right"  >�������ƣ�</td>
      <td   ><input name="s_districtname" type="text" class="input_text_200"  maxlength="100" value=""/>
      <span class="admin_note">�磺̫ԭ</span></td>
    </tr>
    <tr>
      <td height="30" align="right"  >�����󶨣�</td>
      <td   ><input name="s_domain" type="text" class="input_text_200"    value=""/>
      <span class="admin_note">��������������߶�������������http://���磺0351.rencai.com</span></td>
    </tr>
    <tr>
      <td height="30" align="right"  >���ģ�棺</td>
      <td ><input name="s_tpl" type="text" class="input_text_200"  value=""/>
	  <span class="admin_note">�粻��д��ΪĬ��ģ��</span>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >��վLOGO��</td>
      <td ><input name="s_logo" type="file"   style="width:273px; font-size:12px; padding:3px;" onKeyDown="alert('�����Ҳࡰ�����ѡ���������ϵ�ͼƬ��');return false"/> 
	  </td>
    </tr>
	  
	  <tr>
      <td height="30" align="right"  >����ɸѡ��</td>
      <td >
	   <label><input name="s_filter_notice" type="radio"   value="1" checked="checked"/>����վ</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_notice" value="0"  />ȫվ</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >ְλɸѡ��</td>
      <td >
	   <label><input name="s_filter_jobs" type="radio"   value="1" checked="checked"/>����վ</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_jobs" value="0"  />ȫվ</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >����ɸѡ��</td>
      <td >
	   <label><input name="s_filter_resume" type="radio"   value="1" checked="checked"/>����վ</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_resume" value="0"  />ȫվ</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >���ɸѡ��</td>
      <td >
	   <label><input name="s_filter_ad" type="radio"   value="1" checked="checked"/>����վ</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_ad" value="0"  />ȫվ</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >����ɸѡ��</td>
      <td >
	   <label><input name="s_filter_links" type="radio"   value="1" checked="checked"/>����վ</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_links" value="0"  />ȫվ</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >��Ѷɸѡ��</td>
      <td >
	   <label><input name="s_filter_news" type="radio"   value="1" checked="checked"/>����վ</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_news" value="0"  />ȫվ</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >˵��ҳɸѡ��</td>
      <td >
	   <label><input name="s_filter_explain" type="radio"   value="1" checked="checked"/>����վ</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_explain" value="0"  />ȫվ</label>
	  </td>
    </tr>
		<tr>
      <td height="30" align="right"  >��Ƹ��ɸѡ��</td>
      <td >
	   <label><input name="s_filter_jobfair" type="radio"   value="1" checked="checked"/>����վ</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_jobfair" value="0"  />ȫվ</label>
	  </td>
    </tr>
		<tr>
      <td height="30" align="right"  >΢��Ƹɸѡ��</td>
      <td >
	   <label><input name="s_filter_simple" type="radio"   value="1" checked="checked"/>����վ</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_simple" value="0"  />ȫվ</label>
	  </td>
    </tr>
	
	
    <tr>
      <td height="30" align="right"  >&nbsp;</td>
      <td height="50"  > 
            <input name="submit3" type="submit" class="admin_submit"    value="����"/>
        <input name="submit22" type="button" class="admin_submit"    value="����" onclick="Javascript:window.history.go(-1)"/>
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