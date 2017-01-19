<?php
/** 加载核心部件 */
require('../width.php');

/** url参数 */
$FileId =  get_argget('id') ;
$Width = intval( get_argget('w') );
$Heigth = intval( get_argget('h') );

if(is_numeric($FileId)){
    /**　附件信息，必要的处理　*/
    $TheFile =$DB->getonerow("SELECT file_id,path,name,dateline FROM ".DB_PREFIX."file WHERE file_id = '$FileId'");
	$Filepath=$dyhb_options[blogurl]."/width/upload/{$TheFile[path]}";
}else{
    $Filepath=$FileId;
}

if($Filepath){
   Thumb_GD($Filepath,$Width,$Heigth);
}

?>