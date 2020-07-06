<?php
 /*
 * 74cms 企业用户转换
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
set_time_limit(0);
define('IN_QISHI', true);
require_once('../include/common.inc.php'); 
require_once(QISHI_ROOT_PATH.'include/fun_user.php');
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
require_once('conversion_cngfig.php'); 
//$start_id = $_GET['id'];
$action = $_GET['action'];
$sql = "select * from ".  table("category_jobs");
$cat_rs = $db->getall($sql);
var_dump($action);

if($action == "get_cat"){
    $c_id = explode("-", $_GET['c_id']);
    $c_id = $c_id[0];
    $sql = "select * from ".  table("category_jobs")." where parentid = ".$c_id;
    $cat_res = $db->getall($sql);
    foreach($cat_res as $cr){
        $select .= "<option value=".$cr['id']."-".$cr['categoryname'].">".$cr['categoryname']."</option>";
    }
    echo $select;exit;
}

if(!empty($_POST['new']) && $action != "get_cat"){
    $old_arr = explode("-", $_POST['old']);
    $new_arr = explode("-", $_POST['new']);
    //var_dump($old_arr);
    $sql = "select * from ".  table("category_jobs")." where id =".$old_arr[0];
    $s_cat_rs = $db->getone($sql);
    //var_dump($s_cat_rs['parentid']);
    $sql = "select * from ".  table("category_jobs")." where id =".$s_cat_rs['parentid'];
    $b_cat_rs = $db->getone($sql);
    
    $sql = "select * from ".  table("category_jobs")." where id =".$new_arr[0];
    $n_s_cat_rs = $db->getone($sql);
    $sql = "select * from ".  table("category_jobs")." where id =".$n_s_cat_rs['parentid'];
    $n_b_cat_rs = $db->getone($sql);
    
    $sql = "select id from ".table("jobs")." where subclass = ".$old_arr[0];
    $jobs_rs = $db->getall($sql);
    //var_dump($jobs_rs);exit;
    $up_arr['category'] = $n_b_cat_rs['id'];
    $up_arr['subclass'] = $n_s_cat_rs['id'];
    $up_arr['category_cn'] = $n_s_cat_rs['categoryname'];
    
    //var_dump($up_arr);exit;
    if(!empty($jobs_rs)){
        //var_dump($jobs_rs);exit;
        foreach ($jobs_rs as $j) {
            echo "开始更新职位：".$j['id']."<br>";
            $sql = "UPdate [pH_Job_Base] Set JobClass='".$n_b_cat_rs['categoryname']."-".$n_s_cat_rs['categoryname']."' where JobId = ".$j['id'];
            $sql = iconv("gbk",'utf-8//IGNORE',$sql);
            $query = ms_update($sql);
            $wheresqlarr = " id = ".$j['id'];
            updatetable(table("jobs"), $up_arr, $wheresqlarr);
        }
    }
    $sql = "select id from ".table("jobs")." where subclass = ".$old_arr[0];
    $jobs_rs = $db->getall($sql);
    if(empty($check_jobs_rs) && empty($check_jobs_tmp_rs)){
        echo "分类：".$s_cat_rs['id']."-".$s_cat_rs['categoryname']." 更新完成<br><br><br><br>";
        $db->query("Delete from ".table('category_jobs')." WHERE id=".$old_arr[0]." LIMIT 1");
    }else{
        echo "分类：".$s_cat_rs['id']."-".$s_cat_rs['categoryname']." 更新不完整<br><br><br><br>";
    }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<script type="text/javascript" src="jquery.min.js"></script>
<title>分类变更</title>
</head>
<body>
    <form action="" method="post">
    <span><b>需要更新的分类：</b></span>
    <br/>
    <select onchange="javascript:get_cat(this.options[this.options.selectedIndex].value);">
        <option selected value="0">请选择</option>
        <?php foreach ($cat_rs as $cr):?>
            <?php if($cr['parentid']==0):?>
                <option value="<?php echo $cr['id']."-".$cr['categoryname'];?>"><?php echo $cr['categoryname'];?></option>
            <?php endif;?>
        <?php endforeach;?>
    </select>
    <select id="old" name="old">
        <option selected value="0">请选择</option>
    </select>
    <br/>
    <br/>
    <span><b>更新为：</b></span>
    <br/>
    <select onchange="javascript:get_cat2(this.options[this.options.selectedIndex].value);">
        <option selected value="0">请选择</option>
        <?php foreach ($cat_rs as $cr):?>
            <?php if($cr['parentid']==0):?>
                <option value="<?php echo $cr['id']."-".$cr['categoryname'];?>"><?php echo $cr['categoryname'];?></option>
            <?php endif;?>
        <?php endforeach;?>
    </select>
    <select id="new" name="new">
        <option selected value="0">请选择</option>
    </select>
    <br/>
    <br/>
    <!--
    <span><b>操作：</b></span>
    <br/>
    <select name="dotype">
        <option value="1">更新</option>
        <option value="2">删除</option>
        <option selected value="3">更新并删除</option>
    </select>
    <br/>
    <br/>
    -->
    <input type="submit" value="提交" />
    <p style="display: none;" id="smg">进行中，请耐心等待...</p>
    </form>
    <script>
        function get_cat(id){
            $.get("", { action: "get_cat", c_id: id },
                function(data){
                  $("#old").html(data);
                }
            );
        }
        
        
        function get_cat2(id){
            $.get("", { action: "get_cat", c_id: id },
                function(data){
                  $("#new").html(data);
                }
            );
        }
        
        $("form").submit(function(){
            $("#smg").show();
        })
    </script>
    
    
</body>
</html>
