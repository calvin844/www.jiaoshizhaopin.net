<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\function.qishi_news_property.php'); $this->register_function("qishi_news_property", "tpl_function_qishi_news_property",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.default.php'); $this->register_modifier("default", "tpl_modifier_default",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 14:48 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script  charset="utf-8" src="kindeditor/kindeditor.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	showmenu("#type_id_cn","#menu1","#type_id");
});
</script>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("article/admin_article_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptit">新增新闻</div>
  <form action="?act=addsave" method="post" enctype="multipart/form-data" name="FormData" id="FormData" >
  <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellpadding="3" cellspacing="0"  class="admin_table">
      <tr>
        <td width="70" align="right"  style=" border-top:0px">新闻标题：</td>
        <td width="400" style=" border-top:0px"><input name="title" type="text"  class="input_text_400" /></td>
        <td style=" border-top:0px">
		<div class="color_layer">	
		<div id="color_box" onclick="color_box_display()" ></div><input type="hidden" name="tit_color" id="tit_color" >
		<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_select_color.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
		</div>
		</td>
      </tr>
      <tr>
        <td align="right" >新闻分类：</td>
        <td colspan="2" >		
		<div>		
		<input type="text" value="<?php echo $this->_run_modifier($_GET['type_id_cn'], 'default', 'plugin', 1, "请选择"); ?>
"  readonly="readonly" name="type_id_cn" id="type_id_cn" class="input_text_200 input_text_selsect"/>
		<input name="type_id" id="type_id" type="hidden" value="<?php echo $_GET['type_id']; ?>
" />
		<div id="menu1" class="menu">
			<ul>	
			<?php if (count((array)$this->_vars['article_category'])): foreach ((array)$this->_vars['article_category'] as $this->_vars['list']): ?>		
			<li id="<?php echo $this->_vars['list']['id']; ?>
" title="<?php echo $this->_vars['list']['categoryname']; ?>
" ><?php echo $this->_vars['list']['categoryname']; ?>
</li>
			<?php endforeach; endif; ?>
			</ul>
		</div>
		  </div>
		  </td>
      </tr>
	  <tr>
        <td align="right" >缩&nbsp;&nbsp;略&nbsp;&nbsp;图：</td>
        <td colspan="2" ><input type="file" name="Small_img"    onKeyDown="alert('请点击右侧“浏览”选择您电脑上的图片！');return false"   style="height:21px; width:210px; border:1px #999999 solid" /></td>
      </tr>
    </table>
    <table width="700" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="100" ><textarea id="content" name="content" style="width:700px;height:300px;visibility:hidden;"></textarea>
		<script>
			KE.show({
				id : 'content',
				shadowMode : false,
				autoSetDataMode: false,
				allowPreviewEmoticons : false,
				//skinType : 'tinymce',
				urlType : '',
				filterMode : true,
				//allowFileManager : true,
				afterCreate : function(id) {
					KE.event.add(KE.$('FormData'), 'submit', function() {
						KE.util.setData(id);
					});
				}
			});
		</script>
</td>
      </tr>
      <tr>
        <td height="50" align="center" ><input type="submit" name="Submit" value="确定提交" class="admin_submit"   />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="reset" name="Submit2" value="重置表单" class="admin_submit"   /></td>
      </tr>
    </table>
    <table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin_table">
      <tr>
        <td width="120" align="right" >是否显示：</td>
        <td width="200" >
<label><input name="is_display" type="radio" value="1" checked="checked" />显示</label>
<label><input type="radio" name="is_display" value="0" /> 不显示</label>

</td>
        <td width="120" align="right" >文章排序：</td>
        <td ><input name="article_order" type="text"  class="input_text_150" id="article_order" style="width:50px;" maxlength="8" onkeyup="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');"onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/>
        <span>&nbsp;&nbsp;&nbsp;&nbsp;数字越大越靠前</span></td>
        <td width="250" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" >作者：</td>
        <td ><input name="author" type="text" class="input_text_150" id="author" value="<?php echo $this->_vars['author']; ?>
" maxlength="20"/></td>
        <td align="right" >标题加粗：</td>
        <td ><input name="tit_b" type="checkbox" id="tit_b" value="1" />
        加粗</td>
        <td >&nbsp;</td>
      </tr>
	  <tr>
        <td align="right" >来源：</td>
        <td ><input name="source" type="text" class="input_text_150" id="source" maxlength="50"/></td>
        <td align="right" >外部链接：</td>
        <td ><input name="is_url" type="text" class="input_text_150" id="is_url" value="" maxlength="100"/></td>
	    <td >&nbsp;</td>
	  </tr>
 
	  <?php if ($this->_vars['QISHI']['subsite'] == "1"): ?>
	  <tr>
        <td align="right" >显示在：</td>
        <td colspan="4" >
		 <label ><input name="subsite_id" type="radio" value="0"  <?php if ($this->_vars['QISHI']['subsite_id'] == "0"): ?>checked="checked"<?php endif; ?> />全站</label>
		&nbsp;&nbsp;&nbsp;
		<?php if (count((array)$this->_vars['subsite'])): foreach ((array)$this->_vars['subsite'] as $this->_vars['li']): ?>
		<label ><input name="subsite_id" type="radio" value="<?php echo $this->_vars['li']['s_id']; ?>
"  <?php if ($this->_vars['QISHI']['subsite_id'] == $this->_vars['li']['s_id']): ?>checked="checked"<?php endif; ?>/><?php echo $this->_vars['li']['s_districtname']; ?>
</label>
		&nbsp;&nbsp;&nbsp;
		<?php endforeach; endif; ?>
		
	 </td>
      </tr>
	   <?php endif; ?>
	  
	  
	  
	  
	  
	  <tr>
        <td align="right" >文章属性：</td>
        <td colspan="4" >
		<?php echo tpl_function_qishi_news_property(array('set' => "列表名:property"), $this);?>
		<?php if (count((array)$this->_vars['property'])): foreach ((array)$this->_vars['property'] as $this->_vars['list']): ?>
		<label><input name="focos" type="radio" value="<?php echo $this->_vars['list']['id']; ?>
" <?php if ($this->_vars['property']['0']['id'] == $this->_vars['list']['id']): ?>checked="checked" <?php endif; ?>/>
		<?php echo $this->_vars['list']['categoryname']; ?>
  </label>&nbsp;&nbsp;&nbsp; 
		<?php endforeach; endif; ?>
		
	 </td>
      </tr>
      <tr>
        <td align="right" > 
      
        keywords：</td>
        <td colspan="4" ><input name="seo_keywords" type="text" class="input_text_400" id="keywords" value="" maxlength="30"   /></td>
      </tr>
      <tr>
        <td align="right" valign="top" >description：</td>
        <td colspan="4" ><textarea name="seo_description" class="input_textarea_400" id="description"></textarea></td>
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