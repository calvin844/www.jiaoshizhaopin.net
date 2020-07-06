<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:39 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
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
<h2>提示：</h2>
<p>
新增分站后，需要将分站域名解析至主站，同时主网站需绑定此的分站域名；
</p>
</div>  
 <div class="toptit">新增分站</div>
 
    <form id="form1" name="form1" method="post" enctype="multipart/form-data" action="?act=add_save" >
	<?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="4" cellspacing="0"   >
    <tr>
      <td width="120" height="30" align="right"  >分站名称：</td>
      <td  ><input name="s_sitename" type="text" class="input_text_200"   maxlength="100" value=""/>
      <span class="admin_note">如：太原人才网</span></td>
    </tr>
	    <tr>
      <td height="30" align="right"  >分站状态：</td>
      <td   >
	   <label><input name="s_effective" type="radio"   value="1" checked="checked"/>可用</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_effective" value="0"  />禁用</label>
	  </td>
    </tr>

	    <tr>
      <td height="30" align="right"  >地区名称：</td>
      <td   ><input name="s_districtname" type="text" class="input_text_200"  maxlength="100" value=""/>
      <span class="admin_note">如：太原</span></td>
    </tr>
    <tr>
      <td height="30" align="right"  >域名绑定：</td>
      <td   ><input name="s_domain" type="text" class="input_text_200"    value=""/>
      <span class="admin_note">输入独立域名或者二级域名，不带http://，如：0351.rencai.com</span></td>
    </tr>
    <tr>
      <td height="30" align="right"  >风格模版：</td>
      <td ><input name="s_tpl" type="text" class="input_text_200"  value=""/>
	  <span class="admin_note">如不填写则为默认模版</span>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >分站LOGO：</td>
      <td ><input name="s_logo" type="file"   style="width:273px; font-size:12px; padding:3px;" onKeyDown="alert('请点击右侧“浏览”选择您电脑上的图片！');return false"/> 
	  </td>
    </tr>
	  
	  <tr>
      <td height="30" align="right"  >公告筛选：</td>
      <td >
	   <label><input name="s_filter_notice" type="radio"   value="1" checked="checked"/>仅分站</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_notice" value="0"  />全站</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >职位筛选：</td>
      <td >
	   <label><input name="s_filter_jobs" type="radio"   value="1" checked="checked"/>仅分站</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_jobs" value="0"  />全站</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >简历筛选：</td>
      <td >
	   <label><input name="s_filter_resume" type="radio"   value="1" checked="checked"/>仅分站</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_resume" value="0"  />全站</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >广告筛选：</td>
      <td >
	   <label><input name="s_filter_ad" type="radio"   value="1" checked="checked"/>仅分站</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_ad" value="0"  />全站</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >友链筛选：</td>
      <td >
	   <label><input name="s_filter_links" type="radio"   value="1" checked="checked"/>仅分站</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_links" value="0"  />全站</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >资讯筛选：</td>
      <td >
	   <label><input name="s_filter_news" type="radio"   value="1" checked="checked"/>仅分站</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_news" value="0"  />全站</label>
	  </td>
    </tr>
	<tr>
      <td height="30" align="right"  >说明页筛选：</td>
      <td >
	   <label><input name="s_filter_explain" type="radio"   value="1" checked="checked"/>仅分站</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_explain" value="0"  />全站</label>
	  </td>
    </tr>
		<tr>
      <td height="30" align="right"  >招聘会筛选：</td>
      <td >
	   <label><input name="s_filter_jobfair" type="radio"   value="1" checked="checked"/>仅分站</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_jobfair" value="0"  />全站</label>
	  </td>
    </tr>
		<tr>
      <td height="30" align="right"  >微招聘筛选：</td>
      <td >
	   <label><input name="s_filter_simple" type="radio"   value="1" checked="checked"/>仅分站</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="s_filter_simple" value="0"  />全站</label>
	  </td>
    </tr>
	
	
    <tr>
      <td height="30" align="right"  >&nbsp;</td>
      <td height="50"  > 
            <input name="submit3" type="submit" class="admin_submit"    value="添加"/>
        <input name="submit22" type="button" class="admin_submit"    value="返回" onclick="Javascript:window.history.go(-1)"/>
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