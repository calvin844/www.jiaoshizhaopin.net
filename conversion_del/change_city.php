<?php
 /*
 * 74cms ��ҵ�û�ת��
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
*/
set_time_limit(0);
define('IN_QISHI', true);
require_once('../include/common.inc.php'); 
require_once(QISHI_ROOT_PATH.'include/fun_user.php');
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
require_once('conversion_cngfig.php'); 

/*
$sql="select * from PH_City";
$rs = ms_getall($sql);;   //ִ���������

foreach($rs as $rs){
    $setsqlarr['parentid']=$rs['provinceid'];
    $setsqlarr['categoryname']=escape_str($rs['city']);
    $c_id = inserttable(table('category_district'),$setsqlarr,1);
    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$c_id);
}
$url_in_arr = array("url" => "conversion_user_company.php","addtime"=> strtotime("now"));
$cons_id = inserttable(table('cons'), $url_in_arr,TRUE);
if(!empty($cons_id)){
    echo '���г����Ѿ�ȫ��ת����ɣ�';
    echo '<script type="text/javascript" language="javascript">window.location.href="conversion_user_company.php";</script> ';
}
 exit("���г����Ѿ�ȫ��ת����ɣ�");
 
 * 
 */
 $sql = "select * from ".  table("category_district");
 $all_res = $db->getall($sql);
 //var_dump($city_res);exit;
$action = $_GET['action'];
$c_id = $_GET['c_id'];
$type = intval($_POST['dotype']);
$old = $_POST['old'];
$new_area = $_POST['new_area'];
$new_city = $_POST['new_city'];
$select = "<option selected value=0>��ѡ��</option>";
$cname=!empty($_POST['cname'])?$_POST['cname']:"";
$aname=!empty($_POST['aname'])?$_POST['aname']:"";
//var_dump($_POST['aname']);
//var_dump($_POST['cname']);exit;

if(!empty($aname) && !empty($cname)){
    $cname = trim($cname);
    $aname = trim($aname);
    $tmp_str = substr($cname,-2);
    if($tmp_str == "��" || $tmp_str == "ʡ"){
        $cname = substr($cname,0,strlen($cname)-2);
    }
    $tmp_str = substr($aname,-2);
    if($tmp_str == "��" || $tmp_str == "ʡ"){
        $aname = substr($aname,0,strlen($aname)-2);
    }
    if(!empty($cname)){
        //�����������û����Ӧ���ݣ��Ե��������ݽ��в���
        $sql = "select id,categoryname from ".table('category_district');
        $info_a=$db->getall($sql);
        $return_a=locoyspider_search_str($info_a,$aname,"categoryname",100);
        if(!$return_a){
            $add_area = array("parentid" => 0,"categoryname" => $aname);
            $return_a['id'] = inserttable(table('category_district'),$add_area,true);
            access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$return_a['id']);
            if($cname == $aname){
                $return['id'] =$return_a['id'];
                $return['parentid'] =0;
                $return['categoryname'] =$aname;
            }else{
                $return_c=locoyspider_search_str($info_a,$cname,"categoryname",100);
                if(!$return_c){
                    $add_city = array("parentid" => $return_a['id'],"categoryname" => $cname);
                    $c_id=inserttable(table('category_district'),$add_city,true);
                    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$c_id);
                }else{
                    $c_id = $return_c['id'];
                }
                $return['id'] = $c_id;
                $return['parentid'] = $return_a['id'];
                $return['categoryname'] = $cname;
            }
        }else{
            $add_city = array("parentid" => $return_a['id'],"categoryname" => $cname);
            $c_id=inserttable(table('category_district'),$add_city,true);
            access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$c_id);
            $return['parentid'] = $return_a['id'];
            $return['id'] = $c_id;
            $return['categoryname'] = $cname;
        }
    }
    /*
    var_dump(array("district"=>$return['parentid'],"sdistrict"=>$return['id'],"district_cn"=>$return['categoryname']));
    
    echo "<br>";
    echo "<br>���";
     * 
     */
    $new_area = $return['parentid']."-".$aname;
    $new_city = $return['id']."-".$cname;
}

//echo 1;
//var_dump($_POST['type']);exit;
if($action == "get_city" && empty($type)){
    $c_id = explode("-", $c_id);
    $c_id = $c_id[0];
    $sql = "select * from ".  table("category_district")." where parentid = ".$c_id;
    $city_res = $db->getall($sql);
    foreach($city_res as $cr){
        $select .= "<option value=".$cr['id']."-".$cr['categoryname'].">".$cr['categoryname']."</option>";
    }
    echo $select;exit;
}else{
    $old = explode("-", $old);
    $new_area = explode("-", $new_area);
    $new_city = explode("-", $new_city);
    if($old[0] == 0 || $new_area[0] == 0 || $new_city[0] == 0){
        echo "��������";
    }else{
        $up_arr = array('district'=>$new_area[0],'district_cn'=>$new_area[1],'sdistrict'=>$new_city[0],'sdistrict_cn'=>$new_city[1]);
        
        //���²���ͬ������������
        if($type == 1 || $type == 3){
            updatetable(table("course"), array('district'=>$new_area[0],'sdistrict'=>$new_city[0],'district_cn'=>$new_city[1]), " district = ".$old[0]." or sdistrict = ".$old[0]);
            updatetable(table("hunter_jobs"), array('district'=>$new_area[0],'sdistrict'=>$new_city[0],'district_cn'=>$new_city[1]), " district = ".$old[0]." or sdistrict = ".$old[0]);
            updatetable(table("hunter_profile"), array('district'=>$new_area[0],'sdistrict'=>$new_city[0],'district_cn'=>$new_city[1]), " district = ".$old[0]." or sdistrict = ".$old[0]);
            updatetable(table("jobs_subscribe"), array('district'=>$new_city[0],'district_cn'=>$new_city[1]), " district = ".$old[0]);
            updatetable(table("jobs_templates"), array('district'=>$new_area[0],'sdistrict'=>$new_city[0],'district_cn'=>$new_city[1]), " district = ".$old[0]." or sdistrict = ".$old[0]);
            updatetable(table("manager_resume"), array('district'=>$new_area[0],'sdistrict'=>$new_city[0],'district_cn'=>$new_city[1]), " district = ".$old[0]." or sdistrict = ".$old[0]);
            updatetable(table("simple"), array('district'=>$new_area[0],'district_cn'=>$new_area[1],'sdistrict'=>$new_city[0],'sdistrict_cn'=>$new_city[1]), " district = ".$old[0]." or sdistrict = ".$old[0]);
            updatetable(table("train_profile"), array('district'=>$new_area[0],'sdistrict'=>$new_city[0],'district_cn'=>$new_city[1]), " district = ".$old[0]." or sdistrict = ".$old[0]);
            updatetable(table("train_teachers"), array('district'=>$new_area[0],'sdistrict'=>$new_city[0],'district_cn'=>$new_city[1]), " district = ".$old[0]." or sdistrict = ".$old[0]);
            updatetable(table("hunter_jobs"), array('district'=>$new_area[0],'sdistrict'=>$new_city[0],'district_cn'=>$new_city[1]), " district = ".$old[0]." or sdistrict = ".$old[0]);
        }else{
            $db->query("Delete from ".table('course')." WHERE district = ".$old[0]." or sdistrict = ".$old[0]);
            $db->query("Delete from ".table('hunter_jobs')." WHERE district = ".$old[0]." or sdistrict = ".$old[0]);
            $db->query("Delete from ".table('hunter_profile')." WHERE district = ".$old[0]." or sdistrict = ".$old[0]);
            $db->query("Delete from ".table('jobs_subscribe')." WHERE district = ".$old[0]);
            $db->query("Delete from ".table('jobs_templates')." WHERE district = ".$old[0]." or sdistrict = ".$old[0]);
            $db->query("Delete from ".table('manager_resume')." WHERE district = ".$old[0]." or sdistrict = ".$old[0]);
            $db->query("Delete from ".table('simple')." WHERE district = ".$old[0]." or sdistrict = ".$old[0]);
            $db->query("Delete from ".table('train_profile')." WHERE district = ".$old[0]." or sdistrict = ".$old[0]);
            $db->query("Delete from ".table('train_teachers')." WHERE district = ".$old[0]." or sdistrict = ".$old[0]);
            $db->query("Delete from ".table('hunter_jobs')." WHERE district = ".$old[0]." or sdistrict = ".$old[0]);
        }
        
        //����ͬ������������
        $sql = "select * from ".  table("company_profile")." where district = ".$old[0]." or sdistrict = ".$old[0];
        $company_res = $db->getall($sql);
        if(!empty($company_res)){
            foreach ($company_res as $r) {
                echo $r['id']."<br>"; 
                $sql = "UPdate [pH_Company_Base] Set Locus_Area='".$new_area[1]."',Locus_City='".$new_city[1]."' Where NCID=".$r['id'];
                $sql = iconv("gbk",'utf-8//IGNORE',$sql);
                $query = ms_update($sql);
                access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_company_save.php?id=".$r['id']);
            }
        }
        
        $sql = "select * from ".  table("jobs")." where district = ".$old[0]." or sdistrict = ".$old[0];
        $jobs_res = $db->getall($sql);
        if(!empty($jobs_res)){
            foreach ($jobs_res as $r) {
               echo $r['id']."<br>"; 
               if($type == 1 || $type == 3){
                    $sql = "UPdate [pH_Job_Base] Set Work_Area='".$new_area[1]."',Work_City='".$new_city[1]."' Where JobId=".$r['id'];
                    $sql = iconv("gbk",'utf-8//IGNORE',$sql);
                    $query = ms_update($sql);
                    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_jobs_save.php?id=".$r['id']);
               }elseif($type == 2){
                    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/del_jobs.php?id=".$old[0]);
               }
            }
        }
        
        $sql = "select * from ".  table("jobs_tmp")." where district = ".$old[0]." or sdistrict = ".$old[0];
        $jobs_res = $db->getall($sql);
        if(!empty($jobs_res)){
            foreach ($jobs_res as $r) {
               echo $r['id']."<br>"; 
               if($type == 1 || $type == 3){
                    $sql = "UPdate [pH_Job_Base] Set Work_Area='".$new_area[1]."',Work_City='".$new_city[1]."' Where JobId=".$r['id'];
                    $sql = iconv("gbk",'utf-8//IGNORE',$sql);
                    $query = ms_update($sql);
                    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_jobs_save.php?id=".$r['id']);
               }elseif($type == 2){
                    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/del_jobs.php?id=".$old[0]);
               }
            }
        }
        
        $sql = "select * from ".  table("resume")." where district = ".$old[0]." or sdistrict = ".$old[0]." or residence like '%".$old[0]."%'";
        //echo $sql;exit;
        //$sql = "select * from ".  table("resume")." where id = 430200";
        $resume_res = $db->getall($sql);
        //var_dump($type);
        if(!empty($resume_res)){
            foreach ($resume_res as $r) {
                $r_arr = explode(".", $r['residence']);
                //var_dump($r_arr);
                if($r_arr[0] == $old[0] || $r_arr[1] == $old[0]){
                    echo $r['id']."1<br>"; 
                    //var_dump($type);
                    if($type == 1 || $type == 3){
                        $sql = "UPdate [pH_Person_Info] Set Door_Area='".$new_area[1]."',Door_City='".$new_city[1]."' Where NCID=".$r['id'];
                        $sql = iconv("gbk",'utf-8//IGNORE',$sql);
                        $query = ms_update($sql);
                        access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_resume_save.php?id=".$r['id']);
                    }elseif($type == 2){
                        access_url("http://".$_SERVER['HTTP_HOST']."/conversion/del_resume.php?aid=".$r['id']."&id=".$old[0]);
                    }
                }
                if($r['district'] == $old[0] || $r['sdistrict'] == $old[0]){
                    echo $r['id']."<br>"; 
                    if($type == 1 || $type == 3){
                        $sql = "UPdate [pH_Person_Info] Set AreaWill1='".$new_area[1]."-".$new_city[1]."' Where NCID=".$r['id'];
                        $sql = iconv("gbk",'utf-8//IGNORE',$sql);
                        $query = ms_update($sql);
                        access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_resume_save.php?id=".$r['id']);
                    }elseif($type == 2){
                        //echo "http://".$_SERVER['HTTP_HOST']."/conversion/del_resume.php?id=".$old[0];exit;
                        access_url("http://".$_SERVER['HTTP_HOST']."/conversion/del_resume.php?aid=".$r['id']."&id=".$old[0]);
                    }
                }
            }
        }
                
        
        $sql = "select * from ".  table("article")." where seo_description like '%".$old[1]."-%' or  seo_description like '%-".$old[1]."%'";
        $article_res = $db->getall($sql);
        //var_dump($article_res);exit;
        if(!empty($article_res)){
            foreach ($article_res as $r) {
                echo $r['id']."<br>"; 
                $sql="select *  from pH_New_Info where NewId=".$r['id']." and (Info_Area='".$old[1]."' or Info_City='".$old[1]."')";
                //echo $sql;exit;
                $sql = iconv("gbk",'utf-8//IGNORE',$sql);
                $rs = ms_getone($sql);   //ִ���������
                //var_dump($rs);exit;
                if(!empty($rs['NewId'])){
                    if($type == 1 || $type == 3){
                        $sql = "UPdate [pH_New_Info] Set Info_Area='".$new_area[1]."',Info_City='".$new_city[1]."' Where NewId=".$r['id'];
                        //echo $sql."<br>";
                        $sql = iconv("gbk",'utf-8//IGNORE',$sql);
                        //echo $sql."<br>";
                        $query = ms_update($sql);
                        //var_dump($query);exit;
                        access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_news_save.php?id=".$r['id']);
                    }elseif($type == 2){
                        access_url("http://".$_SERVER['HTTP_HOST']."/conversion/del_news.php?id=".$old[0]);
                    }
                }
            }
        }
        
        
        $sql = "select * from ".  table("company_profile")." where district = ".$old[0]." or sdistrict = ".$old[0];
        $company_res = $db->getall($sql);
        
        $sql = "select * from ".  table("jobs")." where district = ".$old[0]." or sdistrict = ".$old[0];
        $jobs_res = $db->getall($sql);
        
        $sql = "select * from ".  table("jobs_tmp")." where district = ".$old[0]." or sdistrict = ".$old[0];
        $jobs_tmp_res = $db->getall($sql);
        
        $sql = "select * from ".  table("resume")." where district = ".$old[0]." or sdistrict = ".$old[0]." or residence like '%".$old[0]."%'";
        $resume_res = $db->getall($sql);
        
        $sql = "select * from ".  table("article")." where seo_description like '%".$old[1]."-%' or  seo_description like '%-".$old[1]."%'";
        $article_res = $db->getall($sql);
        
        var_dump($company_res);
        echo "<br><br><br>";
        var_dump($jobs_res);
        echo "<br><br><br>";
        var_dump($jobs_tmp_res);
        echo "<br><br><br>";
        var_dump($resume_res);
        echo "<br><br><br>";
        var_dump($article_res);
        echo "<br><br><br>";
        
        if(empty($company_res) && empty($jobs_res) && empty($jobs_tmp_res) && empty($resume_res) && empty($article_res)){
            $db->query("Delete from ".table('category_district')." WHERE id=".$old[0]." LIMIT 1");
            $db->query("Delete from ".table('category_district_pinyin')." WHERE d_id=".$old[0]." LIMIT 1");
            if($type == 1 || $type == 3){
                echo "<script>alert('���и������');</script>";
            }elseif($type == 2){
                echo "<script>alert('����ɾ�����');</script>";
            }
        }else{
            echo "<script>alert('���������Ϣ���²�����');</script>";
        }
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<script type="text/javascript" src="jquery.min.js"></script>
<title>���б��</title>
</head>
<body>
    <form action="" method="post">
    <span><b>��Ҫ���µĳ��У�</b></span>
    <br/>
    <select name="old">
        <option selected value="0">��ѡ��</option>
        <?php foreach ($all_res as $ar):?>
            <?php if($ar['parentid']==0):?>
            <option value="<?php echo $ar['id']."-".$ar['categoryname'];?>"><?php echo $ar['id'];?>_ʡ - <?php echo $ar['categoryname'];?></option>
            <?php else:?>
            <option value="<?php echo $ar['id']."-".$ar['categoryname'];?>"><?php echo $ar['id'];?>_�� - <?php echo $ar['categoryname'];?></option>
            <?php endif;?>
        <?php endforeach;?>
    </select>
    <br/>
    <br/>
    <span><b>����Ϊ��</b></span>
    <br/>
    <select name="new_area" onchange="javascript:get_city(this.options[this.options.selectedIndex].value);">
        <option selected value="0">��ѡ��</option>
        <?php foreach ($all_res as $ar):?>
            <?php if($ar['parentid']==0):?>
                <option value="<?php echo $ar['id']."-".$ar['categoryname'];?>"><?php echo $ar['categoryname'];?></option>
            <?php endif;?>
        <?php endforeach;?>
    </select>
    <br/>
    <select id="new_city" name="new_city">
        <option selected value="0">��ѡ��</option>
    </select>
    <input type="text" name="aname" />
    <input type="text" name="cname" />
    <br/>
    <br/>
    <span><b>������</b></span>
    <br/>
    <select name="dotype">
        <option value="1">����</option>
        <option value="2">ɾ��</option>
        <option selected value="3">���²�ɾ��</option>
    </select>
    <br/>
    <br/>
    <input type="submit" value="�ύ" />
    <p style="display: none;" id="smg">�����У������ĵȴ�...</p>
    </form>
    <script>
        function get_city(id){
            $.get("", { action: "get_city", c_id: id },
                function(data){
                  $("#new_city").html(data);
                }
            );
        }
        $("form").submit(function(){
            $("#smg").show();
        })
    </script>
    
    
</body>
</html>