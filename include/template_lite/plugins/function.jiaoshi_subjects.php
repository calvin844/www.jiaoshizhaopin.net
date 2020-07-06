<?php

function tpl_function_jiaoshi_subjects($params, &$smarty) {
    global $db, $_CFG;
    $sql = "select * from " . table('jiaoshi_subject') ;
    $subject_res = get_mem_cache($sql, "getall"); 
    $smarty->assign('subjects', $subject_res);
}

?>