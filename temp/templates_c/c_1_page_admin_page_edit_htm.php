<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:38 �й���׼ʱ�� */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
<script>
function isdisplay(i)     
{      
if(document.getElementById(i).style.display=="")     
{     
 document.getElementById(i).style.display="none";     
}     
else     
{     
document.getElementById(i).style.display="";     
  
}     
 }
</script>
<div class="toptip">
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
	<h2>��ʾ��</h2>
	<p>
��������������ʱ��·����������վ��װĿ¼Ϊ������·����<br />
          ���޸���α��̬�������޸ķ�����α��̬�����ļ��Ķ�Ӧ����<br />
</p>
</div>
  <form action="?act=edit_page_save" method="post"   name="form1" id="form1">
  <?php echo $this->_vars['inputtoken']; ?>

  <div class="toptit">��������</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="link_lan">
 
      <tr>
        <td style=" line-height:220%; color:#666666;"><table width="700" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
          <tr>
            <td width="150" align="right">
			<input name="id" type="hidden" value="<?php echo $this->_vars['list']['id']; ?>
" />
			<input name="systemclass" type="hidden" value="<?php echo $this->_vars['list']['systemclass']; ?>
" />
			ҳ�����ͣ�</td>
            <td> <?php if ($this->_vars['list']['systemclass'] == "1"): ?>
              <span style="color:#FF0000">ϵͳ����</span>
              <?php else: ?>
              �Զ���ҳ��
              <?php endif; ?> </td>
          </tr>
		  <tr>
            <td width="150" align="right">����ID��</td>
            <td>
			<?php if ($this->_vars['list']['systemclass'] == "1"): ?>
              <strong><?php echo $this->_vars['list']['alias']; ?>
</strong>
			  <input name="alias" type="hidden" value="<?php echo $this->_vars['list']['alias']; ?>
" />
              <?php else: ?>
             <input name="alias" type="text"  class="input_text_200" id="alias" value="<?php echo $this->_vars['list']['alias']; ?>
" maxlength="30"  />
              �Զ���ҳ��������Ʋ������� &quot;QS_&quot; ��ͷ<?php endif; ?>			</td>
          </tr>
          <tr>
            <td width="150" align="right">ҳ�����ƣ�</td>
            <td><input name="pname" type="text"  class="input_text_200" id="title" value="<?php echo $this->_vars['list']['pname']; ?>
" maxlength="60"  /></td>
          </tr>
          <tr>
            <td width="150" align="right">����������ǣ�</td>
            <td><input name="tag" type="text"  class="input_text_200" id="tag" value="<?php echo $this->_vars['list']['tag']; ?>
" maxlength="60"  /></td>
          </tr>
          <tr>
            <td align="right">�����Ż���</td>
            <td>
			 <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="120"><label >
              <input type="radio" name="url" value="0" <?php if ($this->_vars['list']['url'] == "0"): ?>checked=checked <?php endif; ?>/>
              ԭʼ���� </label></td>
                    
                    <td><label >
              <input type="radio" name="url" value="1" <?php if ($this->_vars['list']['url'] == "1"): ?>checked=checked <?php endif; ?>/>
              α��̬</label></td>
                  </tr>
                </table></td>
          </tr>
          <tr>
            <td align="right">ҳ�����ͣ�</td>
            <td> 
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="120"><label >
              <input type="radio" name="pagetpye" value="1" <?php if ($this->_vars['list']['pagetpye'] == "1"): ?>checked=checked <?php endif; ?>  onclick="show_seo('seo');"  <?php if ($this->_vars['list']['systemclass'] == "1"): ?>disabled<?php endif; ?>/>
��ҳ��Ƶ����ҳ </label></td>
    <td width="120"><label >
<input type="radio" name="pagetpye" value="2"   <?php if ($this->_vars['list']['pagetpye'] == "2"): ?>checked=checked <?php endif; ?>  onclick="show_seo('seo');"  <?php if ($this->_vars['list']['systemclass'] == "1"): ?>disabled<?php endif; ?>/>
��Ϣ�б�ҳ </label>
</td>
    <td><label >
<input type="radio" name="pagetpye" value="3"   <?php if ($this->_vars['list']['pagetpye'] == "3"): ?>checked=checked <?php endif; ?>  onclick="show_seo('seo');"  <?php if ($this->_vars['list']['systemclass'] == "1"): ?>disabled<?php endif; ?>/>
��Ϣ����ҳ</label></td>
  </tr>
</table></td>
          </tr>
        </table></td>
      </tr>
	  </table>
	   <div class="toptit">ҳ������</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="link_lan">
 
	  <tr>
	    <td  ><table width="700" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
          <tr>
            <td width="150" align="right">�ļ�·����</td>
            <td width="210"><input name="file" type="text"  class="input_text_200" id="file" value="<?php echo $this->_vars['list']['file']; ?>
" /></td>
            <td>&nbsp;</td>
          </tr>
  <tr>
    <td width="150" align="right">ģ��·����</td>
    <td><input name="tpl" type="text"  class="input_text_200" id="tpl" value="<?php echo $this->_vars['list']['tpl']; ?>
" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="150" align="right">α��̬����</td>
    <td><input name="rewrite" type="text"  class="input_text_200" id="rewrite" value="<?php echo $this->_vars['list']['rewrite']; ?>
" /></td>
    <td><span style="color:#666666">(�޸ĺ����޸ķ�����α��̬�����ļ�)</span>&nbsp;</td>
  </tr>
 
  <tr>
    <td align="right"><a  href="javascript:isdisplay('caching_help')" >(?)</a>&nbsp;&nbsp;����ʱ�䣺</td>
    <td><input name="caching" type="text"  class="input_text_200" id="caching" value="<?php echo $this->_vars['list']['caching']; ?>
" maxlength="30"/></td>
    <td>���� <span style="color:#666666">(0Ϊ������) </span></td>
  </tr>
	  
        </table>
		<table width="100%" border="0" cellpadding="0" style="line-height:180%; padding-left:15px; display:none" id="caching_help">
          <tr>
            <td><span style="color:#666666">
              <strong style="color:#003399">����˵��</strong><br />�����ǵ���ѯ����ʱ����ѽ�����л��󱣴浽�ļ��У��Ժ�ͬ���Ĳ�ѯ�Ͳ��ò�ѯ���ݿ⣬���Ǵӻ����л�á���һ�Ľ�ʹ�ó����ٶȵ���̫����������<br />
              ����ʱ���ǻ������¼������ڣ�����Խ�������ݿ�ĸ���ԽС���������ڼ���Ϊ50�룬��ÿ50��ˢ�»���һ�Ρ�</span><br /></td>
          </tr>
        </table>
		</td>
      </tr>
    </table>
<div id="seo">
  <div class="toptit">���������Ż�(SEO)</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="link_lan"  >

        <td style=" line-height:220%; color:#666666;">
        <table width="700" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
		 <tr>
              <td width="150" align="right">���ñ�ǩ��</td>
              <td>
			    <div id="{sitename}" class="sellabel">��վ����</div>
				<div id="{domain}" class="sellabel">��վ����</div>
				<div id="{district}" class="sellabel">����(���ڷ�վģʽ����Ч)</div>
				<div class="clear"></div>
				</td>
            </tr>
      <tr>
            <tr>
              <td width="150" align="right">title��</td>
              <td><input name="title" type="text"  class="input_text_400" id="labtitle" value="<?php echo $this->_vars['list']['title']; ?>
"  /></td>
            </tr>
            <tr>
              <td width="150" align="right">keywords��</td>
              <td><textarea name="keywords" class="input_text_400" id="labkeywords" style="height:60px;"><?php echo $this->_vars['list']['keywords']; ?>
</textarea></td>
            </tr>
            <tr>
              <td width="150" align="right">description��</td>
              <td><textarea name="description" class="input_text_400" id="labdescription" style="height:60px;"><?php echo $this->_vars['list']['description']; ?>
</textarea></td>
            </tr>
        </table></td>
      </tr>
    </table>
	</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="link_lan">
      <tr>
        <td height="80"  style=" padding-left:170px;"  >
		<input type="submit" name="Submi1t2" value="�����޸�" class="admin_submit"   />
          <input name="submit222" type="button" class="admin_submit"    value="�� ��" onclick="location.href='?'"/>
		  
		  </td>
      </tr>
    </table>
  </form>
</div>
<script>
//��ȡ��ѡ��ֵ
function radios_val(val)
    {
        var radios=document.getElementsByName(val);
        for(var i=0;i<radios.length;i++)
        {
            if(radios[i].checked==true)
            {
			return radios[i].value;
            break;
            }
        }
    }
show_seo("seo");
function show_seo(showid)
{
var caching_val=radios_val("pagetpye");
if (caching_val=="1")
{
 document.getElementById(showid).style.display="";   
}
else
{
document.getElementById(showid).style.display="none";   
}
}
</script>
<script type="text/javascript" src="js/jquery.caretInsert.js"></script>
<script language="JavaScript" type="text/javascript"> 
(function($)
{
	$(".sellabel").hover(function(){$(this).css("background-color","#ffffff");},function()	{$(this).css("background-color","#F4FAFF");});
	$("#labtitle").unbind().focus(function() {
		$('#labtitle').setCaret();
		 $('.sellabel').unbind("click").click(function(){ 
			 $('#labtitle').insertAtCaret($(this).attr("id"));
		 });
	});
	$("#labkeywords").unbind().focus(function() {
		$('#labkeywords').setCaret();
		 $('.sellabel').unbind("click").click(function(){ 
			 $('#labkeywords').insertAtCaret($(this).attr("id"));
		 });
	});
	$("#labdescription").unbind().focus(function() {
		$('#labdescription').setCaret();
		 $('.sellabel').unbind("click").click(function(){ 
			 $('#labdescription').insertAtCaret($(this).attr("id"));
		 });
	}); 
})($);   
</script>  
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>