<?php
/** ���غ��Ĳ��� */
require('../width.php');

/** url���� */
$FileId =  get_argget('id') ;
$Width = intval( get_argget('w') );
$Heigth = intval( get_argget('h') );

if(is_numeric($FileId)){
    /**��������Ϣ����Ҫ�Ĵ���*/
    $TheFile =$DB->getonerow("SELECT file_id,path,name,dateline FROM ".DB_PREFIX."file WHERE file_id = '$FileId'");
	$Filepath=$dyhb_options[blogurl]."/width/upload/{$TheFile[path]}";
}else{
    $Filepath=$FileId;
}

if($Filepath){
   Thumb_GD($Filepath,$Width,$Heigth);
}

?>