<?php

/*
 * ���������ϴ�
  |   @param: $dir      -- ���Ŀ¼,����"/" [�ִ�]
  |   @param: $file_var -- ������ [�ִ�]
  |   @param: $max_size -- �趨����ϴ�ֵ,��kΪ��λ. [����/������]
  |   @param: $type     -- �޶������(Сд)�������"/"����,���޶������� [�ִ�]
  |   @param: $name     -- �ϴ�������,������Ϊԭ��,trueΪϵͳ������� [����ֵ]
  |   return: �ϴ����ļ���
 */

function _asUpResume($dir, $file_var, $max_size = '', $type = '') {
    if ($_SESSION['uid'] == '') {
        header("Location: " . url_rewrite('QS_login') . "?act=logout");
        exit();
    }
    if (!file_exists($dir)) {
        showmsg("�ϴ�ʧ�ܣ��ϴ�Ŀ¼ " . $dir . " ������!", 0);
    } elseif (!is_writable($dir)) {
        showmsg("�ϴ�ʧ�ܣ��ϴ�Ŀ¼ " . $dir . " �޷�д��!", 0);
        exit();
    }
    $upfile = & $_FILES["$file_var"];
    $upfilename = $upfile['name'];

    if (!empty($upfilename)) {
        if (!is_uploaded_file($upfile['tmp_name'])) {
            showmsg('�ϴ�ʧ�ܣ���ѡ����ļ��޷��ϴ�', 0);
            exit();
        } elseif ($max_size > 0 && $upfile['size'] / 1024 > $max_size) {
            showmsg("�ϴ�ʧ�ܣ��ļ���С���ܳ���  " . $max_size . "KB", 0);
            exit();
        }
        //$ext_name = strtolower(str_replace(".", "", strrchr($upfilename, ".")));
        $ext_name = pathinfo($upfilename, PATHINFO_EXTENSION);
        if (!empty($type)) {
            $arr_type = explode('/', $type);
            $arr_type = array_map("strtolower", $arr_type);
            if (!in_array($ext_name, $arr_type)) {
                showmsg("�ϴ�ʧ�ܣ�ֻ�����ϴ� " . $type . " ���ļ���", 0);
                exit();
            }
            $harmtype = array("asp", "php", "jsp", "js", "css", "php3", "php4", "ashx", "aspx", "exe");
            if (in_array($ext_name, $harmtype)) {
                exit("ERR!");
            }
        }
        $uploadname = "��ʦ��Ƹ��" . "--" . $_SESSION['uid'] . "--" . str_replace(' ', ',', $upfile['name']);
        if (!move_uploaded_file($upfile['tmp_name'], $dir . $uploadname)) {
            showmsg('�ϴ�ʧ�ܣ��ļ��ϴ�����', 0);
            exit();
        }
        return $uploadname;
    }
    return '';
}

?>